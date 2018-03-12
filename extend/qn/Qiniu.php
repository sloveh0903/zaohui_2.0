<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/25
 * Time: 17:14
 */

namespace qn;


use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Processing\PersistentFop;
use think\Cache;
use think\Config;
use think\Exception;
use Qiniu\Http\Client;
use Qiniu\Storage\BucketManager;

require 'qiniu/autoload.php';

class Qiniu
{
    private $_accessKey;
    private $_secretKey;
    private $_bucket;

    private $_error;

    /**
     * Qiniu constructor.
     * @param string $accessKey
     * @param string $secretKey
     * @param string $bucketName
     * 初始化参数可以直接配置到tp的配置中
     */
    public function __construct($accessKey = "", $secretKey = "", $bucketName = "")
    {
        if (empty($accessKey) || empty($secretKey) || empty($bucketName)) {
            $qiniuConfig = Config::get('qiniu');
            if (empty($qiniuConfig['accesskey']) || empty($qiniuConfig['secretkey'])) {
                $this->_error = '你的配置信息不完整！';
                return false;
            }
            $this->_accessKey = $qiniuConfig['accesskey'];
            $this->_secretKey = $qiniuConfig['secretkey'];
        }else{
            $this->_accessKey = $accessKey;
            $this->_secretKey = $secretKey;
        }

        if (!empty($bucketName)) {
            $this->_bucket = $bucketName;
        }
    }

    /**
     * @return bool|string
     * 获取bucket
     */
    private function _getBucket()
    {
        if (!empty($this->_bucket)) {
            return $this->_bucket;
        }
        $bucket = Config::get('qiniu.bucket');
        if (empty($bucket)) {
            return false;
        }
        return $bucket;
    }

    /**
     * @param string $saveName
     * @param string $bucket
     * @return mixed
     * @throws Exception
     * @throws \Exception
     * 单文件上传，如果添加多个文件则只上传第一个
     */
    public function upload($saveName = '', $bucket = '')
    {
        $token = $this->_getUploadToken($bucket);

        $files = $_FILES;
        if (empty($files)) {
            throw new Exception('没有文件被上传', 10002);
        }
        $values = array_values($files);

        $uploadManager = new UploadManager();
        if (empty($saveName)) {
            $saveName = hash_file('sha1', $values[0]['tmp_name']).time();
        }
        $infoArr = explode('.', $values[0]['name']);
        $extension = array_pop($infoArr);
        $fileInfo = $saveName . '.' . $extension;
        list($ret, $err) = $uploadManager->putFile($token, $fileInfo, $values[0]['tmp_name']);
        if ($err !== null) {
            throw new Exception('上传出错'.serialize($err));
        }
        return $ret['key'];
    }




    public function uploadByVideo($saveName = '', $bucket = '',$notifyUrl = ''){
        $auth = new Auth($this->_accessKey, $this->_secretKey);
        $bucket = empty($bucketName)? $this->_getBucket():$bucketName;
        $files = $_FILES;
        if (empty($files)) {
            throw new Exception('没有文件被上传', 10002);
        }
        $values = array_values($files);
        $uploadManager = new UploadManager();
        if (empty($saveName)) {
            $saveName = hash_file('sha1', $values[0]['tmp_name']).time();
        }

        $pfop = "avthumb/m3u8";
        $infoArr = explode('.', $values[0]['name']);
        $extension = array_pop($infoArr);
        $fileName = $saveName . '.' . $extension;

        $pipeline = 'fuwangdian';

        if($notifyUrl){
            $policy = array(
                'persistentOps' => $pfop,
                'persistentNotifyUrl' => $notifyUrl,
                'persistentPipeline' => $pipeline
            );
        }else{
            $policy = array(
                'persistentOps' => $pfop,
                'persistentPipeline' => $pipeline
            );
        }

        $token = $auth->uploadToken($bucket,null,3600,$policy);
        list($ret, $err) = $uploadManager->putFile($token, null, $values[0]['tmp_name']);
        if ($err !== null) {
            throw new Exception('上传出错'.serialize($err));
        }
        return $ret['key'];
    }


    /**
     * @param string $key
     * @param string $notifyUrl
     * @return mixed
     * 视频转码
     */
    public function qiniuConvert($key,$notifyUrl=''){
        $auth = new Auth($this->_accessKey, $this->_secretKey);
        $bucket = empty($this->_bucket) ? $this->_getBucket():$this->_bucket;
        $pipeline = 'convert';
        //$notifyUrl = 'http://375dec79.ngrok.com/notify.php';
        if($notifyUrl){
            $pfop = new PersistentFop($auth, $bucket, $pipeline,$notifyUrl);
        }else{
            $pfop = new PersistentFop($auth, $bucket, $pipeline);
        }
        $newKey = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).".m3u8";
        $saveAs = \Qiniu\base64_urlSafeEncode($bucket.":".$newKey);       //saveas接口 参数
        $fops = "avthumb/m3u8/h264Crf/18|saveas/".$saveAs;

        list($id, $err) = $pfop->execute($key, $fops);

        if ($err != null) {
            return false;
        } else {
            $data['id'] = $id;
            $data['new_key'] = $newKey;
            return $data;
        }
    }

    /**
     * @param string $key
     * @param string $notifyUrl
     * @return mixed
     * 视频转码
     */
    public function qiniuConvert2($key,$notifyUrl=''){
        $auth = new Auth($this->_accessKey, $this->_secretKey);
        $bucket = empty($this->_bucket)? $this->_getBucket():$this->_bucket;
        $pipeline = 'convert';
        //$notifyUrl = 'http://375dec79.ngrok.com/notify.php';
        $notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].'/api/recharge/convert.html';
        if($notifyUrl){
            $pfop = new PersistentFop($auth, $bucket, $pipeline,$notifyUrl);
        }else{
            $pfop = new PersistentFop($auth, $bucket, $pipeline);
        }
        $newKey = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).".mp4";
        $saveAs = \Qiniu\base64_urlSafeEncode($bucket.":".$newKey);       //saveas接口 参数
        $fops = "avthumb/mp4/ab/160k/ar/44100/acodec/libfaac/r/30/vb/2400k/vcodec/libx264/s/1280x720/autoscale/1/stripmeta/0|saveas/".$saveAs;

        list($id, $err) = $pfop->execute($key, $fops);

        if ($err != null) {
            return false;
        } else {
            $data['id'] = $id;
            $data['new_key'] = $newKey;
            return $data;
        }
    }





    public function getToken($bucket = ''){
        $auth = new Auth($this->_accessKey, $this->_secretKey);
        $bucket = empty($bucketName)? $this->_getBucket():$bucketName;
        $upToken = $auth->uploadToken($bucket);
        return $upToken;
    }




    /**
     * @param $bucketName
     * @return mixed|string
     * @throws Exception
     * 只有设置到配置的bucket才会使用缓存功能
     */
    private function _getUploadToken($bucketName)
    {
        $upToken = Cache::get('qiniu_upload_token');
        if (!empty($upToken) && empty($bucketName)) {
            return $upToken;
        }else{
            $auth = new Auth($this->_accessKey, $this->_secretKey);
            $bucket = empty($bucketName)? $this->_getBucket():$bucketName;
            if ($bucket === false) {
                throw new Exception('你还没有设置或者传入bucket', 100001);
            }
            $upToken = $auth->uploadToken($bucket);
            Cache::set('qiniu_upload_token', $upToken);
            return $upToken;
        }
    }

    /**
     * 获取标准存储
     * Create By Xenos
     * Return
     */
    public function getSpace($sdate,$edate,$key,$secret,$bucketName = null){
        $param = "begin=".$sdate."&end=".$edate."&g=day";
        if($bucketName){
            $param .= "&bucketname=".$bucketName;
        }
        $url = "https://api.qiniu.com/v6/space?".$param;
        $auth = new Auth($key,$secret);
        $authorization = $auth->authorization($url,'','application/x-www-form-urlencoded');
        $headers['Authorization'] = $authorization['Authorization'];
        $response = Client::get($url,$headers);
        if (!$response->ok()) {
            return false;
        }
        $result = $response->json();
        return $result;
    }


    /**
     * 获取CDN流量
     * 日期跨度不能大于31天
     * 日期格式为2017-10-10
     * 域名列表，以 ；分割
     * Create By Xenos
     * Return
     */
    public function bandwidth($sdate,$edate,$domains,$key,$secret){
        $url = "http://fusion.qiniuapi.com/v2/tune/flux";
        $auth = new Auth($key,$secret);
        $authorization = $auth->authorization($url,'','application/x-www-form-urlencoded');
        $headers['Authorization'] = $authorization['Authorization'];
        $headers['Content-type'] = 'application/json';
        $arr = ['startDate'=>$sdate,'endDate'=>$edate,'granularity'=>'day', 'domains'=>$domains];
        $data = json_encode($arr);
        $response = Client::post($url,$data,$headers);
        if (!$response->ok()) {
            return false;
        }
        $result = $response->json();
        return $result;
    }

    public function videorename($oldKey,$newKey){
        $bucket = empty($this->_bucket)? $this->_getBucket():$this->_bucket;
        $new = \Qiniu\base64_urlSafeEncode($bucket.":".$newKey);
        $old = \Qiniu\base64_urlSafeEncode($bucket.":".$oldKey);
        $param = "/".$old."/".$new."/force/true";
        $url = "http://rs.qiniu.com/move".$param;
        //$auth = new Auth($key,$secret);
        $auth = new Auth($this->_accessKey, $this->_secretKey);
        $authorization = $auth->authorization($url,'','application/x-www-form-urlencoded');
        $headers['Authorization'] = $authorization['Authorization'];
        $headers['Content-type'] = 'application/json';
        $response = Client::post($url,'',$headers);
        if (!$response->ok()) {
            return false;
        }
    }



    public function checkFunction(){
        return 'success';
    }


    public function callbackCheck($body,$authorization,$url){
        $auth = new Auth($this->_accessKey, $this->_secretKey);
        //获取回调的body信息
        $callbackBody = $body;
        //回调的contentType
        $contentType = 'application/x-www-form-urlencoded';
        //回调的签名信息，可以验证该回调是否来自七牛
        //$authorization = $_SERVER['HTTP_AUTHORIZATION'];
        //七牛回调的url，具体可以参考：http://developer.qiniu.com/docs/v6/api/reference/security/put-policy.html
        // = 'http://172.30.251.210/upload_verify_callback.php';
        $isQiniuCallback = $auth->verifyCallback($contentType, $authorization, $url, $callbackBody);
        if ($isQiniuCallback) {
            $resp = array('ret' => 'success');
        } else {
            $resp = array('ret' => 'failed');
        }
        return $resp;
    }

    public function deleteVideo($bucket,$key){
        //初始化Auth状态：
        $auth = new Auth($this->_accessKey, $this->_secretKey);
        //初始化BucketManager
        $bucketMgr = new BucketManager($auth);
        //你要测试的空间， 并且这个key在你空间中存在
        //$bucket = 'video';
        //删除$bucket 中的文件 $key
        $err = $bucketMgr->delete($bucket, $key);
        if ($err !== null) {
            $resp = 'failed';
        } else {
            $resp = "success";
        }
        return $resp;
    }


    public function qiniuConvert3($key,$notifyUrl=''){
        $auth = new Auth($this->_accessKey, $this->_secretKey);
        $bucket = empty($this->_bucket)? $this->_getBucket():$this->_bucket;
        $pipeline = 'convert';
        //$notifyUrl = 'http://375dec79.ngrok.com/notify.php';
        //$notifyUrl = 'http://'.$_SERVER['HTTP_HOST'].'/api/recharge/convert3.html';
        if($notifyUrl){
            $pfop = new PersistentFop($auth, $bucket, $pipeline,$notifyUrl);
        }else{
            $pfop = new PersistentFop($auth, $bucket, $pipeline);
        }
        $newKey = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT).".mp4";
        $saveAs = \Qiniu\base64_urlSafeEncode($bucket.":".$newKey);       //saveas接口 参数
        $fops = "avthumb/mp4/s/1280x720/autoscale/1/vb/1500k|saveas/".$saveAs;

        list($id, $err) = $pfop->execute($key, $fops);

        if ($err != null) {
            return false;
        } else {
            $data['id'] = $id;
            $data['new_key'] = $newKey;
            return $data;
        }
    }

}