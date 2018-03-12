<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use app\api\controller\Integral;
use think\Cache;
use think\Config;
use think\Cookie;
use think\Db;
use think\Debug;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\Lang;
use think\Loader;
use think\Log;
use think\Model;
use think\Request;
use think\Response;
use think\Session;
use think\Url;
use think\View;
/**
 * 是否https
 * @return bool
 */
function isHTTPS(){
    if(!isset($_SERVER['HTTPS']))  return FALSE;
    if($_SERVER['HTTPS'] === 1){  //Apache
        return TRUE;
    }elseif($_SERVER['HTTPS'] === 'on'){ //IIS
        return TRUE;
    }elseif($_SERVER['SERVER_PORT'] == 443){ //其他
        return TRUE;
    }
    return FALSE;
}
/**
 * 当前域名
 */
function host(){
    if(isHTTPS()){
        $host = 'https://'.$_SERVER['HTTP_HOST'];
    }else{
        $host = 'http://'.$_SERVER['HTTP_HOST'];
    }
    return $host;
}

/**
 * 表是否存在acid字段
 * @param $table
 * @return bool
 */
function issetField($table,$field = 'acid'){
    $prefix = config('database.prefix');
    $table = $prefix.$table;
    $query = Db::query("desc `".$table."` `".$field."`");
    if($query){
        return true;
    }else{
        return false;
    }
}


//返回json错误信息
function errorJson($message = 'error',$code = -1,$data = []){
    header("Content-type: application/json");
    $str = '{"data":'.json_encode($data).',"code":'.$code.',"message":"'.$message.'"}';
    die($str);
}

//返回json正确信息
function successJson($message = 'success',$data = [],$code = 1){
    header("Content-type: application/json");
    $str = '{"data":'.json_encode($data).',"code":'.$code.',"message":"'.$message.'"}';
    die($str);
}

//打印方法
function p($arr){
    echo "<pre>";
    print_r($arr);
}

//对象转数组
function objArray($obj){
   $arr = json_decode(json_encode($obj),true);
    return $arr;
}

//生成随机字符串
function createRandomStr($length){
    $str = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';//62个字符
    $strlen = 62;
    while($length > $strlen){
        $str .= $str;
        $strlen += 62;
    }
    $str = str_shuffle($str);
    return substr($str,0,$length);
}

/**
 * 获取本周
 * @param int 1-7
 */
function getWeek($i=1){
    return date('Y-m-d',(time()+($i-(date('w')==0?7:date('w')))*24*3600));
}

/**
 *@desc 封闭curl的调用接口，get的请求方式。
 */
function doCurlGetRequest($url,$timeout = 10){
    if($url == "" || $timeout <= 0){
        return false;
    }
    $con = curl_init((string)$url);
    curl_setopt($con, CURLOPT_HEADER, 0);
    curl_setopt($con, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
    curl_setopt($con, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($con, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);
    return curl_exec($con);
}

/**
 ** @desc 封装 curl 的调用接口，post的请求方式
 **/
function doCurlPostRequest($url,$requestString = [],$timeout = 10){
    if($url == '' || $timeout <=0){
        return false;
    }
    $con = curl_init((string)$url);
    curl_setopt($con, CURLOPT_HEADER, false);
    curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
    curl_setopt($con, CURLOPT_POST,true);
    curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($con, CURLOPT_TIMEOUT,(int)$timeout);
    curl_setopt($con, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
    curl_setopt($con, CURLOPT_SSL_VERIFYHOST, FALSE);
    return curl_exec($con);
}

function timeFormat($timeInt,$format='Y/m/d'){
    if(empty($timeInt)||!is_numeric($timeInt)||!$timeInt){
        return $timeInt;
    }
    $d=time()-$timeInt;
    if($d<0){
        return date('Y-m-d');
    }else{
        if($d<60){
            return $d.'秒前';
        }else{
            if($d<3600){
                return floor($d/60).'分钟前';
            }else{
                if($d<86400){
                    return floor($d/3600).'小时前';
                }else{
                    if($d<259200){//3天内
                        return floor($d/86400).'天前';
                    }else{
                        return date($format,$timeInt);
                    }
                }
            }
        }
    }
}
function changeTimeType($seconds){
    $seconds = intval($seconds);
    if ($seconds > 3600){$hours = intval($seconds/3600);
    $minutes = $seconds % 3600;
        $time = $hours.":".gmstrftime('%M:%S', $minutes);
    }else{
        $time = gmstrftime('%H:%M:%S', $seconds);
    }
    return $time;
}

//时间年月日
function dateymd($time,$format = 'Y/m/d'){
    $time = strtotime($time);
    $time = date($format,$time);
    return $time;
}

function getsite(){
    return $_SERVER["REQUEST_SCHEME"].'://'.$_SERVER["SERVER_NAME"];
}

function alert($msg='',$url='',$icon='',$time=3){
    //icon 1为正确 2错误 3疑问 4锁定 5失败 6成功 7警告
    if($url==''){
        $tourl = "window.history.back();";
    }else{
        $tourl = "self.location.href='".$url."'";
    }
    $str='<script type="text/javascript" src="/public/jqadmin/js/layui/layui.js"></script>';//加载jquery和layer
    //$str.='<script>$(function(){layer.msg("'.$msg.'",{icon:'.$icon.',time:'.($time*1000).'});setTimeout(function(){self.location.href="'.$url.'"},2000)});</script>';//主要方法
    $str.='<script>layui.use(\'layer\', function(){var layer = layui.layer;layer.msg("'.$msg.'",{icon:'.$icon.',time:'.($time*1000).'});setTimeout(function(){'.$tourl.'},2000);});</script>';//主要方法
    return $str;
}

//评价星星
function score($score = 10){
    if($score > 0 && $score< 1.5){
        $star = 1;
    }else if($score >= 1.5 && $score< 2.5){
        $star = 2;
    }else if($score >= 2.5 && $score< 3.5){
        $star = 3;
    }else if($score >= 3.5 && $score< 4.5){
        $star = 4;
    }else if($score >= 4.5 && $score< 5.5){
        $star = 5;
    }else if($score >= 5.5 && $score< 6.5){
        $star = 6;
    }else if($score >= 6.5 && $score< 7.5){
        $star = 7;
    }else if($score >= 7.5 && $score< 8.5){
        $star = 8;
    }else if($score >= 8.5 && $score< 9.5){
        $star = 9;
    }else if($score >= 9.5 && $score<= 10){
        $star = 10;
    }else{
        $star = 10;
    }
    return $star;
}

function string_remove_xss($html) {
    preg_match_all("/\<([^\<]+)\>/is", $html, $ms);
    $searchs[] = '<';
    $replaces[] = '&lt;';
    $searchs[] = '>';
    $replaces[] = '&gt;';

    if ($ms[1]) {
        $allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote';
        $ms[1] = array_unique($ms[1]);
        foreach ($ms[1] as $value) {
            $searchs[] = "&lt;".$value."&gt;";

            $value = str_replace('&amp;', '_uch_tmp_str_', $value);
            $value = string_htmlspecialchars($value);
            $value = str_replace('_uch_tmp_str_', '&amp;', $value);

            $value = str_replace(array('\\', '/*'), array('.', '/.'), $value);
            $skipkeys = array('onabort','onactivate','onafterprint','onafterupdate','onbeforeactivate','onbeforecopy','onbeforecut','onbeforedeactivate',
                'onbeforeeditfocus','onbeforepaste','onbeforeprint','onbeforeunload','onbeforeupdate','onblur','onbounce','oncellchange','onchange',
                'onclick','oncontextmenu','oncontrolselect','oncopy','oncut','ondataavailable','ondatasetchanged','ondatasetcomplete','ondblclick',
                'ondeactivate','ondrag','ondragend','ondragenter','ondragleave','ondragover','ondragstart','ondrop','onerror','onerrorupdate',
                'onfilterchange','onfinish','onfocus','onfocusin','onfocusout','onhelp','onkeydown','onkeypress','onkeyup','onlayoutcomplete',
                'onload','onlosecapture','onmousedown','onmouseenter','onmouseleave','onmousemove','onmouseout','onmouseover','onmouseup','onmousewheel',
                'onmove','onmoveend','onmovestart','onpaste','onpropertychange','onreadystatechange','onreset','onresize','onresizeend','onresizestart',
                'onrowenter','onrowexit','onrowsdelete','onrowsinserted','onscroll','onselect','onselectionchange','onselectstart','onstart','onstop',
                'onsubmit','onunload','javascript','script','eval','behaviour','expression','style','class');
            $skipstr = implode('|', $skipkeys);
            $value = preg_replace(array("/($skipstr)/i"), '.', $value);
            if (!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
                $value = '';
            }
            $replaces[] = empty($value) ? '' : "<" . str_replace('&quot;', '"', $value) . ">";
        }
    }
    $html = str_replace($searchs, $replaces, $html);

    return $html;
}

function string_htmlspecialchars($string, $flags = null) {
    if (is_array($string)) {
        foreach ($string as $key => $val) {
            $string[$key] = string_htmlspecialchars($val, $flags);
        }
    } else {
        if ($flags === null) {
            $string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
            if (strpos($string, '&amp;#') !== false) {
                $string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
            }
        } else {
            if (PHP_VERSION < '5.4.0') {
                $string = htmlspecialchars($string, $flags);
            } else {
                if (!defined('CHARSET') || (strtolower(CHARSET) == 'utf-8')) {
                    $charset = 'UTF-8';
                } else {
                    $charset = 'ISO-8859-1';
                }
                $string = htmlspecialchars($string, $flags, $charset);
            }
        }
    }

    return $string;
}


//列表数据
function lists($model,$data = [],$order = [],$field = false){
    $page = isset($data['page']) && $data['page'] > 0 ? (($data['page']-1) * $data['size']) : false;
    $size = isset($data['size']) && $data['size'] > 0 ? intval($data['size']) : false;
    unset($data['page']);
    unset($data['size']);
    if(!isset($data['closed'])){
        $data['closed'] = ['=',0];
    }
    $class = '\app\model\\'.$model;
    $obj = model($class);
    $obj->where($data);
    if(is_array($order)){
        foreach($order as $k=>$v){
            $obj->order($k,$v);
        }
    }
    if($field) $obj->field($field);
    if($page>=0 && $size>=0) $obj->limit($page,$size);
    $result = $obj->select();
    $array = objArray($result);
    //去除null值
    foreach($array as &$v){
        foreach($v as $k2=>$v2){
            if(is_null($v2)){
                $v[$k2] = '';
            }
        }
    }
    unset($v);
    return $array;
}

//一条数据
function find($model,$data = [],$field = false,$closed=false,$order = []){
    $class = '\app\model\\'.$model;
    $obj = model($class);
    if(!$closed){
        if(!isset($data['closed'])){
            $data['closed'] = ['=',0];
        }
    }
    $obj->where($data);
    foreach($order as $k=>$v){
        $obj->order($k,$v);
    }
    if($field) $obj->field($field);
    $result = $obj->find();
    if(!$result) return false;
    $array =  objArray($result);
    foreach($array as &$v){
        if(is_null($v)){
            $v = '';
        }
    }
    unset($v);
    return $array;
}

//统计
function counts($model,$data = [],$closed=false){
    $class = '\app\model\\'.$model;
    $obj = model($class);
    if(!$closed){
        if(!isset($data['closed'])){
            $data['closed'] = ['=',0];
        }
    }
    $obj->where($data);
    $result = $obj->count();
    return $result;
}

//总计
function sum($model,$data = [],$field,$closed=false){
    $class = '\app\model\\'.$model;
    $obj = model($class);
    if(!$closed){
        if(!isset($data['closed'])){
            $data['closed'] = ['=',0];
        }
    }
    $obj->where($data);
    $result = $obj->sum($field);
    if(!$result) return 0;
    return $result;
}


//增加
function insert($model,$data,$key = false){
    if(empty($data)) return true;
    $class = '\app\model\\'.$model;
    $obj = model($class);
    $result = $obj->save($data);
    if(!$result) return false;
    if($key && $result)  return  $obj->$key;
    return true;
}

//修改
function update($model,$data,$save=[]){
    if(empty($data)) return true;
    $class = '\app\model\\'.$model;
    $obj = model($class);
    $result = $obj->save($save,$data);
    return $result;
}

//删除
function delete($model,$data,$closed = false){
    if(empty($data)) return true;
    $class = '\app\model\\'.$model;
    $obj = model($class);
    $obj->where($data);
    if($closed){
        $result = $obj->delete();
    }else{
        $result = $obj->setField(['closed'=>1]);
    }
    return $result;
}

//自增
function setInc($model,$data,$field = 'id',$num = 1,$closed = false){
    $class = '\app\model\\'.$model;
    $obj = model($class);
    if(!$closed){
        if(!isset($data['closed'])){
            $data['closed'] = ['=',0];
        }
    }
    $obj->where($data);
    $result = $obj->setInc($field,$num);
    return $result;
}

//自减
function setDec($model,$data,$field = 'id',$num = 1,$closed = false){
    $class = '\app\model\\'.$model;
    $obj = model($class);
    if(!$closed){
        if(!isset($data['closed'])){
            $data['closed'] = ['=',0];
        }
    }
    $obj->where($data);
    $result = $obj->setDec($field,$num);
    return $result;
}


/**
 * 发送短信
 */
/* function sendsms($phone,$code,$templateCode='SMS_62470009',$product='付费用户'){
    require_once EXTEND_PATH."org/aliyun/Message.php";
    $client = new \Message();
    $phone = (string)$phone;
    $SMSTemplateCode = (string)$templateCode;
    $TemplateParams['code'] = (string)$code;
    $TemplateParams['product'] = (string)$product;
    $result = $client->send($SMSTemplateCode,$phone,$TemplateParams);
    if($result == 0){
        return false;
    }
    return true;
}*/


/**
 * 阿里大于短信发送新版
 */
function sendsms($phone,$code){
    header('Content-Type: text/plain; charset=utf-8');
    require_once EXTEND_PATH."org/aliyun/sms/api_demo/SmsDemo.php";
    $demo = new \SmsDemo(
        "LTAIcVDfM7WiEE90",
        "kJuCSeZN92dvoAuLbPP1PytFJQcWpI"
    );
    $response = $demo->sendSms(
        "格子匠", // 短信签名
        "SMS_88315010", // 短信模板编号
        $phone, // 短信接收者
        Array(  // 短信模板中字段的值
            "code"=>$code,
            // "product"=>"dsd"
        )
    );
    return $response;
    //print_r(json_decode(json_encode($response),true));
}

/**
 * 获取手机号
 */
function getMobile($uid){
    $user =  find('User',['uid'=>$uid]);
    if(!$user){
        return false;
    }
    return $user['mobile'];
}

//获取用户信息
function getUser($uid = 0,$closed = false,$field="uid,nickname,face,uname,realname"){
    $class = '\app\model\\User';
    $obj =  model($class);
    if(!$closed){
        if(!isset($data['closed'])){
            $data['closed'] = ['=',0];
        }
    }
    $obj->where(['uid'=>$uid]);
    if($field) $obj->field($field);
    $result = $obj->find();
    if(!$result) return false;
    $array =  objArray($result);
    foreach($array as &$v){
        if(is_null($v)){
            $v = '';
        }
    }
    unset($v);
    return $array;
}

/**
 * 是否购买课程
 * @param array $param
 * @return int 0未购买 1购买 -1 过期
 */
function checkbuy($param = ['uid'=>0,'cid'=>0]){
    $uid = $param['uid'];
    $cid = $param['cid'];
    $vip = isvip($uid);
    if($vip == 1 || $vip == 2){
        return 1;
    }
    if(!$uid || $uid == 0 || !$cid || $cid == 0){
        return 0;
    }
    $user = find('User',['uid'=>$uid],'mobile,mobile_bind');
    $mobile = trim($user['mobile']);
    if($mobile && $user['mobile_bind'] == 1){
        //如果有手机，通过手机跟uid查询订单
        $order = Db::name('order')
            ->where('closed','0')
            ->where('pay_status',1)
            ->where('course_id',$cid)
            ->where("uid=:uid or mobile =:mobile", ['uid' => $uid, 'mobile' => $mobile])
            ->order('expire_time','desc')
            ->find();
        //套餐订单查询，是否有这个课程
        $packageOrder = Db::name('PackageOrder')
            ->where('closed','0')
            //->where('pay_status',1)
            ->where('course_id',$cid)
            ->where("uid=:uid or mobile =:mobile", ['uid' => $uid, 'mobile' => $mobile])
            ->order('expire_time','desc')
            ->find();

    }else{
        $order = Db::name('order')
            ->where('closed','0')
            ->where('pay_status',1)
            ->where("uid", $uid)
            ->where("course_id", $cid)
            ->order('expire_time','desc')
            ->find();
        $packageOrder = Db::name('PackageOrder')
           // ->where('pay_status',1)
            ->where('closed','0')
            ->where("uid", $uid)
            ->where('course_id',$cid)
            ->order('expire_time','desc')
            ->find();
    }
    if(!$order && !$packageOrder){
        return 0;
    }

//     if($order['expire_time'] == 1483200000){
//         $last_year = date('Y-m-d H:i:s', strtotime("-1 year"));
//         if($order['create_time'] < $last_year){
//             return -1;
//         }
//     }else{
    if($order || $packageOrder){
        if($order['expire_time'] < time() && $packageOrder['expire_time'] < time()){
            return -1;
        }
        return 1;
    }

//     }
    return 0;
}
/**
 * 是否购买套餐
 * @param array $param
 * @return int 0未购买 1购买 -1 过期
 */
function checkpackage($param = ['uid'=>0,'package_id'=>0]){
    $uid = $param['uid'];
    $package_id = $param['package_id'];
    $vip = isvip($uid);
    if($vip == 1 || $vip == 2){
        return 1;
    }
    if(!$uid || $uid == 0 || !$package_id || $package_id == 0){
        return 0;
    }
    $user = find('User',['uid'=>$uid],'mobile,mobile_bind');
    $mobile = trim($user['mobile']);
    if($mobile && $user['mobile_bind'] == 1){
        //如果有手机，通过手机跟uid查询订单
        $order = Db::name('order')
        ->where('closed','0')
        ->where('pay_status',1)
        ->where('package_id',$package_id)
        ->where("uid=:uid or mobile =:mobile", ['uid' => $uid, 'mobile' => $mobile])
        ->order('expire_time','desc')
        ->find();   
    }else{
        $order = Db::name('order')
        ->where('closed','0')
        ->where('pay_status',1)
        ->where("uid", $uid)
        ->where('package_id',$package_id)
        ->order('expire_time','desc')
        ->find();
    }
    if(!$order){
        return 0;
    }
    if($order){
        if($order['expire_time'] < time()){
            return -1;
        }
    }
    return 1;
}
/**
 * 增加消息通知
 */
function sendnotify($param){
    $type = $param['type'];
    $sender = $param['sender'];
    $target = $param['target'];
    $content = $param['content'];
    $targetType = $param['targetType'];
    $action = $param['action'];
    $data = [
        'type'=>$type,
        'sender'=>$sender,
        'target'=>$target,
        'content'=>$content,
        'targetType'=>$targetType,
        'action'=>$action
    ];
    $result = find('Notify',$data);
    if($result){
        return $result['id'];
    }
    $result =  insert('Notify',$data,'id');
    if(!$result){
        return false;
    }
    return $result;
}

/**
 * 增加阅读消息
 */
function sendread($param){
    $uid = $param['uid'];
    $nid = $param['nid'];
    $isread = $param['isread'];
    $data = [
        'uid'=>$uid,
        'nid'=>$nid,
        'isread'=>$isread
    ];
    $read = find('Read',$data);
    if($read){
        return true;
    }
    $result =  insert('Read',$data);
    if(!$result){
        return false;
    }
    return true;
}

/**
 *@desc 封闭curl的调用接口，get的请求方式。
 */
function apiget($url,$requestString = [],$timeout = 10){
    if(is_HTTPS()){
        $protocol = "https://";
    }else{
        $protocol = "http://";
    }
    $api_url = $protocol.$_SERVER['SERVER_NAME'].'/api/'.$url;
    if(!empty($requestString)){
        $api_url = $api_url.'?'.http_build_query($requestString);
    }
    $con = curl_init((string)$api_url);
    curl_setopt($con, CURLOPT_HEADER, false);
    curl_setopt($con, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);
    curl_setopt($con, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
    curl_setopt($con, CURLOPT_SSL_VERIFYHOST, FALSE);
    return json_decode(curl_exec($con),true);
}

function is_HTTPS(){
    if(!isset($_SERVER['HTTPS']))  return FALSE;
    if($_SERVER['HTTPS'] === 1){  //Apache
        return TRUE;
    }elseif($_SERVER['HTTPS'] === 'on'){ //IIS
        return TRUE;
    }elseif($_SERVER['SERVER_PORT'] == 443){ //其他
        return TRUE;
    }
    return FALSE;
}

/**
 ** @desc 封装 curl 的调用接口，post的请求方式
 **/
function apipost($url,$requestString = []){
    if(is_HTTPS()){
        $protocol = "https://";
    }else{
        $protocol = "http://";
    }
    $url = $protocol.$_SERVER['SERVER_NAME'].'/api/'.$url;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // post数据
    curl_setopt($ch, CURLOPT_POST, 1);
    // post的变量
    curl_setopt($ch, CURLOPT_POSTFIELDS, $requestString);
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output,true);
}

/**
 * 微信h5授权登录
 */
function wechatLogin(){
    $session_unionid = session('unionid');
    $config = find('Config');
    if(empty($session_unionid) || !$session_unionid) {
        $code = input('code');
        $wechatUrl = 'http://'.$_SERVER['HTTP_HOST'].'/wechat';
        $APPID = $config['wx_public_appid'];
        $SECRET = $config['wx_public_secret'];
        $REDIRECT_URI = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        //获取code
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $APPID . "&redirect_uri=" . $REDIRECT_URI . "&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
        if (!$code) {
            Header("Location:$url");die;
        }
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $APPID . '&secret=' . $SECRET . '&code=' . $code . '&grant_type=authorization_code';
        $data = doCurlGetRequest($url);
        $data = json_decode($data, true);
        if(isset($data['errcode'])){
            p($data);die;
        }
        $openid = $data['openid'];
        $access_token = $data['access_token'];
        //获取用户详细信息
        $getAccessToken = getAccessToken();
        $basic_access_token = $getAccessToken['access_token'];
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $basic_access_token . '&openid=' . $openid . '&lang=zh_CN';
        $json_data = doCurlGetRequest($url);
        $data = json_decode($json_data, true);
        if(isset($data['errcode'])){
            //Header("Location:$wechatUrl");die;
            p($json_data);die;
        }
        $subscribe = $data['subscribe']; //1 关注 0 未关注
        if($subscribe == 0){ //未关注获取基本用户信息
            $url = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $access_token . '&openid=' . $openid . '&lang=zh_CN';
            $json_data = doCurlGetRequest($url);
            $data = json_decode($json_data, true);
            if(isset($data['errcode'])){
                //Header("Location:$wechatUrl");die;
                p($json_data);die;
            }
        }
        if(!isset($data['unionid'])){
            $data['unionid'] = $openid;
        }
        $unionid = $data['unionid'];
        session('subscribe', $subscribe);
        $param = [
            'openid'=>$openid,
            'unionid'=>$unionid,
            'headimgurl'=>$data['headimgurl'],
            'nickname'=>$data['nickname'],
            'sex'=>$data['sex'],
            'type'=>1
        ];
        $result = createUser($param);

        update('User',['uid'=>$result['uid']],['subscribe'=>$subscribe]);
        $result['subscribe'] = $subscribe;
    }else{
        $uid = session('user_id');
        //$uid = session('user_id',36);
        $user = find('User',['uid'=>$uid]);
        $result = [
            'uid' => session('user_id'),
            'face' => $user['face'],
            'nickname' => $user['nickname'],
            'openid'=>session('openid'),
            'token' => $user['token'],
            'mobile_bind' => $user['mobile_bind'],
            'uname'=>$user['uname'],
            'mobile'=>$user['mobile'],
            'alipay'=>$user['alipay'],
            'money'=>$user['money'],
            'subscribe'=>$user['subscribe']
        ];
    }
    $is_wxmobile = show_switch('is_wxmobile');
    if($is_wxmobile && !$result['mobile']){
        //手机为空和开启绑定开关   判断是否已绑定
        $result['is_bind'] = 'no';
    }else{
        $result['is_bind'] = 'yes';
    }
    return $result;
}
/**
 * 登录设置  是否开启pc端手机号验证
 */
 function is_pcmobile(){
     $is_pcmobile=show_switch('is_pcmobile');
     return $is_pcmobile;
 }
 /**
  * 登录设置  是否开启微信端手机号验证
  */
 function is_wxmobile(){
     $is_wxmobile=show_switch('is_wxmobile');
     return $is_wxmobile;
 }
/**
 * 生成用户
 */
function createUser($param){
    $openid = $param['openid'];
    $unionid = $param['unionid'];
    $face = $param['headimgurl'];
    $nickname = $param['nickname'];
    $sex = $param['sex'];
    $type = $param['type'];
    if($type == 1){
        $openid_type = 'service_openid'; //公众号
    }elseif($type == 2){
        $openid_type = 'openid';         //小程序
    }else{
        $openid_type = 'open_openid';    //开放平台
    }
    $user_service_openid = find('User', [$openid_type => $openid]);
    $user_unionid = find('User', ['unionId' => $unionid]);
    if ($user_service_openid || $user_unionid) {
        if ($user_service_openid) {
            $user = $user_service_openid;
        } else {
            $user = $user_unionid;
        }
        $uid = $user['uid'];
        $user_face = $user['face'];
        $substr = substr($user_face, 0, 5);
        if ($substr != 'https' || empty($user_face)) {
            $user_data['face'] = $face;
        }
        $str = $user['noncestr'];
        $time = time();
        $uname = $user['uname'];
        $token = md5($uname . $str . $time);
        $user_data['nickname'] = $nickname;
        $user_data['sex'] = $sex;
        $user_data['token'] = $token;
        $user_data['ip'] = ip2long($_SERVER["REMOTE_ADDR"]);
        $user_data['unionId'] = $unionid;
        $user_data[$openid_type] = $openid;
        update('User', ['uid' => $uid], $user_data);
        $result = [
            'uid' => $uid,
            'face' => $face,
            'nickname' => $nickname,
            'openid'=>$openid,
            'token' => $token,
            'mobile_bind' => $user['mobile_bind'],
            'uname'=>$user['uname'],
            'mobile'=>$user['mobile'],
            'alipay'=>$user['alipay'],
            'audit'=>$user['audit'],
            'money'=>$user['money'],
            'is_register'=>0,
            'integral'=>0
        ];
    } else {
        //创建账号
        $count = counts('User');
        $uname = uniqid() . rand(99, 999);
        for ($i = 0; $i < $count; $i++) {
            $uname = uniqid() . rand(99, 999);
            $user_uname = find('User', ['uname' => $uname]);
            if (!$user_uname) {
                break;
            }
        }
        $class = ceil($count / 50);
        $str = createRandomStr(10);
        $password = md5($str);
        $time = time();
        $token = md5($uname . $str . $time);
        $data = [
            'uname' => $uname,
            'password' => $password,
            'noncestr' => $str,
            'token' => $token,
            $openid_type => $openid,
            'face' => $face,
            'unionId' => $unionid,
            'sex' => $sex,
            'nickname' => $nickname,
            'class' => $class,
            'ip' => ip2long($_SERVER["REMOTE_ADDR"])
        ];
        $user_id = insert('User', $data, 'uid');
        if (!$user_id) errorJson('注册失败');
        $user = find('User', ['uid' => $user_id]);
        //增加积分  todo
        $integral_api = new Integral();
        $integral_data  = $integral_api->register($user_id);//注册
        $integral = 0;
        if($integral_data['integral_code']){
            $integral = $integral_data['integral'];
        }
        $result = [
            'uid' => $user_id,
            'face' => $face,
            'nickname' => $nickname,
            'openid'=>$openid,
            'token' => $token,
            'mobile_bind' => $user['mobile_bind'],
            'uname'=>$user['uname'],
            'mobile'=>$user['mobile'],
            'alipay'=>$user['alipay'],
            'audit'=>$user['audit'],
            'money'=>$user['money'], 
            'is_register'=>1,
            'integral'=>$integral
        ];
    }
    session('unionid', $unionid);
    session('openid', $openid);
    session('face', $face);
    session('sex', $sex);
    session('nickname', $nickname);
    session('user_id', $result['uid']);
    session('uid', $result['uid']);
    session('token', $result['token']);
    session('mobile_bind', $result['mobile_bind']);
    session('bind', $result['mobile_bind']);
    session('mobile', $result['mobile']);
    session('alipay', $result['alipay']);
    session('money', $result['money']);
    session('uname', $result['uname']);
    session('is_register',$result['is_register']);
    session('integral',$result['integral']);
    return $result;
}


/**
 * 数据验证
 * @author 王宣成
 * @param $params
 * @param string $secret
 * @return string
 */
function tokenVerification($function = []){
    if(Config::get('signature')){
        Loader::import('org.wxsign.wxBizDataCrypt');
        $config = find('Config');
        $appid = $config['wx_appid'];
        $signature = input('signature');
        $rawData = input('rawData');
        $token = input('token');
        $iv = input('iv');
        $uid = input('uid');
        if($rawData && $iv){
            //微信小程序
            $encryptedData = input('encryptedData');
             if(!$signature) {
                 errorJson('缺少signature参数');
             }
             if(!$token) {
                 errorJson('缺少token参数');
             }
             if(!$encryptedData) {
                 errorJson('缺少encryptedData参数');
             }
             $user = find("User",['uid'=>$uid]);
             if(!$user){
                 errorJson('用户不存在');
             }
             $sessionKey = $user['session_key'];
             $pc = new \WXBizDataCrypt($appid, $sessionKey);
             $errCode = $pc->decryptData($encryptedData, $iv);
             if(!$errCode) {
                 errorJson('非法数据');
             }
             $signature2 = sha1($rawData.$sessionKey);
             if ($signature != $signature2) {
                 errorJson('签名错误');
             }
        }else{
            //其他
            $request = Request::instance()->action();
            foreach($function as &$f){
                $f = strtolower($f);
            }
            unset($f);
            if(in_array($request,$function)){
                $uid = input('uid',0);
                $token = input('token');
                $where['uid'] = $uid;
                $user =  find('User',$where);
                if(!$user) {
                    errorJson('uid错误,用户不存在');
                }
                if(!$token || ($token != $user['token'])){
                    session('unionid', null);
                    session('uid',null);
                    errorJson('token失效请重新登录',-3);
                }
            }
        }
    }
}


/**
 * 获取指定月份的第一天开始和最后一天结束的时间戳
 *
 * @param int $y 年份 $m 月份
 * @return array(本月开始时间，本月结束时间)
 */
function mFristAndLast($y = "", $m = ""){
    if ($y == "") $y = date("Y");
    if ($m == "") $m = date("m");
    $m = sprintf("%02d", intval($m));
    $y = str_pad(intval($y), 4, "0", STR_PAD_RIGHT);

    $m>12 || $m<1 ? $m=1 : $m=$m;
    $firstday = strtotime($y . $m . "01000000");
    $firstdaystr = date("Y-m-01", $firstday);
    $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));

    return array(
        "firstday" => $firstday,
        "lastday" => $lastday
    );
}
/**
 * 获取小程序access_token
 */
function getMinAccessToken(){
    $config = find('Config');
    $APPID = $config['wx_appid'];
    $APPSECRET = $config['wx_secret'];
    $data = get_php_file("min_access_token.php");
    $data = json_decode($data,true);
    if ($data['expire_time'] < time()) {
        // 如果是企业号用以下URL获取access_token
        // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$APPID&secret=$APPSECRET";
        $res = httpGet($url);
        $res  = json_decode($res,true);
        if(!isset($res['access_token'])){
            errorJson($res['errmsg']);
        }
        $access_token = $res['access_token'];
        $data['expire_time'] = $expire_time = time() + 7000;
        $data['access_token'] = $access_token;
        set_php_file("min_access_token.php", json_encode($data));
    } else {
        $expire_time = $data['expire_time'];
        $access_token = $data['access_token'];
    }
    $data = [
        'access_token' => $access_token,
        'token_time' => $expire_time
    ];
    return $data;
}

/**
 * 获取access_token
 */
function getAccessToken(){
    $config = find('Config');
    $APPID = $config['wx_public_appid'];
    $APPSECRET = $config['wx_public_secret'];
    $token = load_wechat('Token',array('appid'=>$APPID,'appsecret'=>$APPSECRET))->token($APPID,$APPSECRET);
    $data = [
        'access_token' => $token,
        'token_time' => 0
    ];
    return $data;
}

//获取第三方授权token
function component_access_token(){
    $config = find('Config');
    $appid = $config['component_appid'];
    $appsecret = $config['component_appsecret'];
    $get_ticket = get_php_file('component_verify_ticket.php');
    $arr_ticket = json_decode($get_ticket,true);
    $ticket = $arr_ticket['component_verify_ticket'];
    $param = [
        'component_appid'=>$appid,
        'component_appsecret'=>$appsecret,
        'component_verify_ticket'=>$ticket
    ];
    $data = json_decode(get_php_file("component_access_token.php"));
    if ($data->expire_time < time()) {
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_component_token';
        $param = json_encode($param);
        $curl = httprequest($url,$param);
        $json_data = json_decode($curl,true);
        $json_data['expire_time'] = time() + 7000;
        $component_access_token = $json_data['component_access_token'];
        set_php_file("component_access_token.php", json_encode($json_data));
    } else {
        $component_access_token = $data->component_access_token;
    }
    return $component_access_token;
}

//获取第三方授权码
function pre_auth_code(){
    $config = find('Config');
    $appid = $config['component_appid'];
    $component_access_token = component_access_token();
    $param = [
        'component_appid'=>$appid,
    ];
    $data = json_decode(get_php_file("pre_auth_code.php"));
    if ($data->expire_time < time()) {
        $url = 'https://api.weixin.qq.com/cgi-bin/component/api_create_preauthcode?component_access_token='.$component_access_token;
        $param = json_encode($param);
        $curl = httprequest($url,$param);
        $json_data = json_decode($curl,true);
        $json_data['expire_time'] = time() + 1600;
        $pre_auth_code = $json_data['pre_auth_code'];
        set_php_file("pre_auth_code.php", json_encode($json_data));
    } else {
        $pre_auth_code = $data->pre_auth_code;
    }
    return $pre_auth_code;
}

function get_php_file($filename) {
    $appHost = "http://".$_SERVER['HTTP_HOST']."/extend/org/wxsdk/";
    return file_get_contents($appHost.$filename);
    // return trim(substr(file_get_contents($this->appHost.$filename), 15));
}

function set_php_file($filename, $content) {
    $appPath =  EXTEND_PATH.'org'.DS.'wxsdk'.DS;
    $fp = fopen($appPath.$filename, "w");
    fwrite($fp, $content);
    fclose($fp);
}

function httprequest($url,$data = null)
{
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
    // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_URL, $url);
    $res = curl_exec($curl);
    curl_close($curl);
    return $res;
}

//添加分销人
function addDistribution($user,$parent_uname){
    $uid = $user['uid'];
    $mobile = $user['mobile'];
    if(!empty($parent_uname) && $user['p1'] == 0){
        $rebateConfig = find('RebateConfig');
        if(isset($rebateConfig['status']) && $rebateConfig['status'] == 1){
            $puser = find('User',['uname'=>$parent_uname]);
            if($puser){
                $puid = $puser['uid'];
                $pmobile = $puser['mobile'];
                if($puser['mobile_bind'] == 1){
                    $order = Db::name('order')
                        ->where("uid=:uid or mobile =:mobile", ['uid' => $puid, 'mobile' => $pmobile])
                        ->where('closed','0')
                        ->where('pay_status',1)
                        ->find();
                }else{
                    $order = Db::name('order')
                        ->where("uid", $puid)
                        ->where('closed','0')
                        ->where('pay_status',1)
                        ->find();
                }
                if($user['mobile_bind'] == 1){
                    $myorder = Db::name('order')
                        ->where("uid=:uid or mobile =:mobile", ['uid' => $uid, 'mobile' => $mobile])
                        ->where('closed','0')
                        ->where('pay_status',1)
                        ->find();
                }else{
                    $myorder = Db::name('order')
                        ->where("uid", $uid)
                        ->where('closed','0')
                        ->where('pay_status',1)
                        ->find();
                }
                if($order && !$myorder){
                    $p1_uid = $puser['uid'];
                    $p2_uid = $puser['p1'];
                    $p3_uid = $puser['p2'];
                    update('User',['uid'=>$uid],['p1'=>$p1_uid,'p2'=>$p2_uid,'p3'=>$p3_uid,'rebate_time'=>time()]);
                }
            }
        }

    }
}

//添加佣金
function addRebate($order_id){
    $rebateConfig = find('RebateConfig');
    $order = find('Order',['id'=>$order_id],'id,course_id,order_sn,pay_price,uid,pay_status');
    if(!$order){
        return false;
    }
    $user = find('User',['uid'=>$order['uid']],'uid,nickname,p1,p2,p3');
    if($rebateConfig && $rebateConfig['status'] == 1 && $order['pay_status'] == 1){
        if($user['p1'] > 0){
            //上一级加佣金
            $data['uid'] = $user['p1'];
            $data['buy_uid'] = $user['uid'];
            $data['nickname'] = $user['nickname'];
            $data['order_sn'] = $order['order_sn'];
            $data['order_id'] = $order_id;
            $data['order_money'] = $order['pay_price'];
            $data['money'] = $order['pay_price']*($rebateConfig['first']/100);
            $data['money'] = sprintf("%.2f", $data['money']);
            $data['fxlevel'] = 1;
            $data['scale'] = $rebateConfig['first'];
            $data['create_time'] = time();
            $data['update_time'] = time();
            $rebate = find('Rebate',['order_id'=>$order_id,'fxlevel'=>1]);
            if(!$rebate){
                $insert_rebate = db::name('rebate')->insert($data);
                if($insert_rebate){
                    setInc('User',['uid'=>$user['p1']],'money',$data['money']);
                }

            }
            unset($data);
        }
        if($user['p2']>0){
            //上上级加佣金
            $data['uid'] = $user['p2'];
            $data['buy_uid'] = $user['uid'];
            $data['nickname'] = $user['nickname'];
            $data['order_sn'] = $order['order_sn'];
            $data['order_id'] = $order_id;
            $data['order_money'] = $order['pay_price'];
            $data['money'] = $order['pay_price']*($rebateConfig['second']/100);
            $data['money'] = sprintf("%.2f", $data['money']);
            $data['fxlevel'] = 2;
            $data['scale'] = $rebateConfig['second'];
            $data['create_time'] = time();
            $data['update_time'] = time();
            $rebate = find('Rebate',['order_id'=>$order_id,'fxlevel'=>2]);
            if(!$rebate){
                $insert_rebate = db::name('rebate')->insert($data);
                if($insert_rebate){
                    setInc('User',['uid'=>$user['p2']],'money',$data['money']);
                }

            }

            unset($data);
        }
        if($user['p3']>0){
            //上3级加佣金
            $data['uid'] = $user['p3'];
            $data['buy_uid'] = $user['uid'];
            $data['nickname'] = $user['nickname'];
            $data['order_sn'] = $order['order_sn'];
            $data['order_id'] = $order_id;
            $data['order_money'] = $order['pay_price'];
            $data['money'] = $order['pay_price']*($rebateConfig['third']/100);
            $data['money'] = sprintf("%.2f", $data['money']);
            $data['fxlevel'] = 3;
            $data['scale'] = $rebateConfig['third'];
            $data['create_time'] = time();
            $data['update_time'] = time();
            $rebate = find('Rebate',['order_id'=>$order_id,'fxlevel'=>3]);
            if(!$rebate){
                $insert_rebate = db::name('rebate')->insert($data);
                if($insert_rebate){
                    setInc('User',['uid'=>$user['p3']],'money',$data['money']);
                }
            }
            unset($data);
        }

    }


}

/**
 * 创建海报路径
 * @param int $uid
 * @param int $cid
 */
function createPosterFile($uid = 0,$cid = 0){
    $basedir = ROOT_PATH . 'public' . DS .'/poster/';
    $course_file = $basedir.'/'.$cid;
    $user_hash = ceil($uid/1000);
    if(!file_exists ($course_file)){
        mkdir ($course_file);
    }
    $user_file = $basedir.'/'.$cid.'/'.$user_hash;
    if(!file_exists ($user_file)){
        mkdir ($user_file);
    }
    return $basedir.'/'.$cid.'/'.$user_hash.'/';
}

/**
 * 获取海报路径
 * @param int $uid
 * @param int $cid
 */
function getPosterFile($uid = 0,$cid = 0){
    $user_hash = ceil($uid/1000);
    return '/public/poster/'.$cid.'/'.$user_hash.'/'.$uid.'.jpg';
}

//xml转数组
function xmlToArray($xml)
{
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
    $val = json_decode(json_encode($xmlstring), true);
    return $val;
}

function yuanimg($imgpath = '') {
    $src_img = imagecreatefromstring(file_get_contents($imgpath));
    $wh  = getimagesize($imgpath);
    $w   = $wh[0];
    $h   = $wh[1];
    $w   = min($w, $h);
    $h   = $w;
    $img = imagecreatetruecolor($w, $h);
    //这一句一定要有
    imagesavealpha($img, true);
    //拾取一个完全透明的颜色,最后一个参数127为全透明
    $bg = imagecolorallocatealpha($img, 255, 255, 255, 127);
    imagefill($img, 0, 0, $bg);
    $r   = $w / 2; //圆半径
    for ($x = 0; $x < $w; $x++) {
        for ($y = 0; $y < $h; $y++) {
            $rgbColor = imagecolorat($src_img, $x, $y);
            if (((($x - $r) * ($x - $r) + ($y - $r) * ($y - $r)) < ($r * $r))) {
                imagesetpixel($img, $x, $y, $rgbColor);
            }
        }
    }
    return $img;
}

/**
 * 改变图片的宽高
 *
 * @author flynetcn (2009-12-16)
 *
 * @param string $img_src 原图片的存放地址或url
 * @param string $new_img_path 新图片的存放地址
 * @param int $new_width 新图片的宽度
 * @param int $new_height 新图片的高度
 * @return bool 成功true, 失败false
 */
function resize_image($img_src, $new_img_path, $new_width, $new_height)
{
    $img_info = @getimagesize($img_src);
    if (!$img_info || $new_width < 1 || $new_height < 1 || empty($new_img_path)) {
        return false;
    }
    if (strpos($img_info['mime'], 'jpeg') !== false) {
        $pic_obj = imagecreatefromjpeg($img_src);
    } else if (strpos($img_info['mime'], 'gif') !== false) {
        $pic_obj = imagecreatefromgif($img_src);
    } else if (strpos($img_info['mime'], 'png') !== false) {
        $pic_obj = imagecreatefrompng($img_src);
    } else {
        return false;
    }

    $pic_width = imagesx($pic_obj);
    $pic_height = imagesy($pic_obj);

    if (function_exists("imagecopyresampled")) {
        $new_img = imagecreatetruecolor($new_width,$new_height);
        imagecopyresampled($new_img, $pic_obj, 0, 0, 0, 0, $new_width, $new_height, $pic_width, $pic_height);
    } else {
        $new_img = imagecreate($new_width, $new_height);
        imagecopyresized($new_img, $pic_obj, 0, 0, 0, 0, $new_width, $new_height, $pic_width, $pic_height);
    }
    if (preg_match('~.([^.]+)$~', $new_img_path, $match)) {
        $new_type = strtolower($match[1]);
        switch ($new_type) {
            case 'jpg':
                imagejpeg($new_img, $new_img_path);
                break;
            case 'gif':
                imagegif($new_img, $new_img_path);
                break;
            case 'png':
                imagepng($new_img, $new_img_path);
                break;
            default:
                imagejpeg($new_img, $new_img_path);
        }
    } else {
        imagejpeg($new_img, $new_img_path);
    }
    imagedestroy($pic_obj);
    imagedestroy($new_img);
    return true;
}

/*
*功能：php完美实现下载远程图片保存到本地
*参数：文件url,保存文件目录,保存文件名称，使用的下载方式
*当保存文件名称为空时则使用远程文件原来的名称
*/
function getImage($url,$save_dir='',$filename='',$type=0){
    if(trim($url)==''){
        return array('file_name'=>'','save_path'=>'','error'=>1);
    }
    if(trim($save_dir)==''){
        $save_dir='./';
    }
    if(trim($filename)==''){//保存文件名
        $ext=strrchr($url,'.');
        if($ext!='.gif'&&$ext!='.jpg'){
            return array('file_name'=>'','save_path'=>'','error'=>3);
        }
        $filename=time().$ext;
    }
    if(0!==strrpos($save_dir,'/')){
        $save_dir.='/';
    }
    //创建保存目录
    if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)){
        return array('file_name'=>'','save_path'=>'','error'=>5);
    }
    //获取远程文件所采用的方法
    if($type){
        $ch=curl_init();
        $timeout=5;
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
        $img=curl_exec($ch);
        curl_close($ch);
    }else{
        ob_start();
        readfile($url);
        $img=ob_get_contents();
        ob_end_clean();
    }
    //$size=strlen($img);
    //文件大小
    $fp2=@fopen($save_dir.$filename,'a');
    fwrite($fp2,$img);
    fclose($fp2);
    unset($img,$url);
    return array('file_name'=>$filename,'save_path'=>$save_dir.$filename,'error'=>0);
}



function utf8Substr($str, $from, $len)
{
    return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
        '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
        '$1',$str);
}

function gPic_Url($content){
    $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";//正则
    preg_match_all($pattern,$content,$match);//匹配图片
    return $match[1];//返回所有图片的路径
}



/*
 * 统计七牛的费用
 */
function qiniuCost($data = 0,$type = 'space'){
    $money = 0;
    if($data > 0){
        $price = config('qiniu.'.$type);
        if($type == 'space'){
            $money = (($data/1073741824)*$price)/30;
        }else{
            $money = ($data/1073741824)*$price;
        }
        $money = ($money < 0.01) ? '0.01' : round($money,2);
    }
    return $money;
}

/*
 * 转码费用
 * */
function convertCost($lenght,$width){
    $resolution = 'sd240';
    if($width <= 320){
        $resolution = 'sd240';
    }elseif($width > 320 && $width <= 640){
        $resolution = 'sd480';
    }elseif($width > 640 && $width <= 1080){
        $resolution = 'sd';
    }elseif($width > 1080 && $width <= 1920){
        $resolution = 'hd';
    }elseif($width > 1920 && $width <= 2560){
        $resolution = '2k';
    }elseif($width > 2560 && $width <= 3820){
        $resolution = '4k';
    }
    $price = array('sd240'=>'0.0077','sd480'=>'0.0092','sd'=>'0.0209','hd'=>'0.0344','2k'=>'0.08','4k'=>'0.14');
    $min = ceil($lenght/60);
    $money = round($min*$price[$resolution],2);
    $result['resolution'] = $resolution;
    $result['money'] = $money;
    return $result;
}


function unified($param){
    ini_set('date.timezone','Asia/Shanghai');
    $config = find('Config');
    $pay_type = $param['pay_type']; //1 h5 2小程序
    if($pay_type == 1){
        $key = $config['wx_public_appkey'];
        $appid = $config['wx_public_appid'];
        $mchid = $config['wx_public_mchid'];
        $secret = $config['wx_public_secret'];
    }else{
        $key = $config['wx_appkey'];
        $appid = $config['wx_appid'];
        $mchid = $config['wx_mchid'];
        $secret = $config['wx_secret'];
    }

    $WxPayConfig = new \WxPayConfig();
    $WxPayConfig::$APPID = $appid;
    $WxPayConfig::$MCHID = $mchid;
    $WxPayConfig::$KEY = $key;
    $WxPayConfig::$APPSECRET = $secret;
    $input = new \WxPayUnifiedOrder();
    $input->SetBody($param['body']); //订单描述
    $input->SetOut_trade_no($param['trade_no']); //商户订单号
    //$input->SetTotal_fee($order['price']/100); //金额
    $input->SetTotal_fee($param['price']*100); //金额
    $input->SetGoods_tag("test");
    $input->SetAttach($param['attach']);
    $input->SetNotify_url($param['notify']);
    $input->SetTrade_type("JSAPI");
    $input->SetOpenid($param['openid']);
    $order = \WxPayApi::unifiedOrder($input);
    if($order['return_code'] == 'SUCCESS'){
        $appid = $order['appid'];
        $nonceStr = $order['nonce_str'];
        $package = 'prepay_id='.$order['prepay_id'];
        $signType = 'MD5';
        $timeStamp = time();
        $appkey = $key;
        $arr = [
            'appId' => $appid,
            'timeStamp' =>$timeStamp,
            'nonceStr' => $nonceStr,
            'package' => $package,
            'signType' => $signType,
        ];
        ksort($arr);
        $stringA = '';
        foreach ($arr as $key => $val) {
            $stringA.= $key.'='.$val.'&';
        }
        $stringSignTemp = $stringA.'key='.$appkey;
        $sign = strtoupper(md5($stringSignTemp));
        $order['sign'] = $sign;
        $order['timeStamp'] = $timeStamp;
        $order['package'] = $package;
        return $order;

    }
    return $order['return_msg'];
}

/**
 * 会员卡是否购买 0未购买 -1过期 1未过期 2 终身
 */
function isvip($uid = 0){
    if($uid == 0){
        $uid = session('uid');
    }
    $user = find('User',['uid'=>$uid]);
    $mobile = $user['mobile'];
    if(!$user || $user['expire_time'] == 0){
        return 0;
    }
    //终身卡
    $user_card = find('UserCard',['type'=>'life']);
    if($user_card){
        $ucard_record = Db::name('Order')
            ->where('pay_status','1')
            ->where('order_type','usercard')
            ->where('closed','0')
            ->where('usercard_id',$user_card['id'])
            ->where("uid=:uid or mobile =:mobile", ['uid' => $uid, 'mobile' => $mobile])
            ->find();
        if($ucard_record){
            return 2;
        }
    }
    if($user['expire_time'] > time()){
        return 1;
    }

    return -1;
}


function updateRelation($uid,$mobile){
    //关联数据更新，订单数据和会员卡数据
    $importUcard = Db::name('Order')
        ->where('order_type','usercard')
        ->where('pay_status','1')
        ->where('is_import','1')
        ->where('closed','0')
        ->where("mobile", $mobile)
        ->where('uid','0')
        ->order('expire_time','desc')
        ->find();
    $user = find('User',['uid'=>$uid]);
    if(!$user || $user['mobile'] != $mobile){
        return 0;
    }
    $data = [];
    if($importUcard){
        //会员卡关联
        $card_id = $importUcard['usercard_id'];
        $usercard = Db::name('user_card')->where('id',$card_id)->find();
        if($usercard['type'] == 'life'){
            $data['cardtype'] = 2;
        }else{
            $data['cardtype'] = 1;
        }

        if($user['expire_time'] > time()){
            //未过期
            if($importUcard['expire_time'] > time()){
                $data['expire_time'] = strtotime("+".$usercard['mouth']." month",$user['expire_time']);
            }
        }else{
            //已过期
            if($importUcard['expire_time'] > $user['expire_time']){
                $data['expire_time'] = $importUcard['expire_time'];
            }

        }

        if($data){
            Db::name('user')->where('uid',$uid)->update($data);
        }
    }
    $package = Db::name('Order')
        ->where('order_type','package')
        ->where("mobile", $mobile)
        ->where('is_import','1')
        ->where('pay_status','1')
        ->where('closed','0')
        ->select();
    if($package){
        //套餐关联
        Db::name('PackageOrder')
            ->where("mobile", $mobile)
            ->update(array('uid'=>$uid));

    }
    Db::name('order')->where(array('mobile'=>$mobile,'pay_status'=>'1','closed'=>'0'))->update(array('uid'=>$uid));
    return 1;
}

//会员解绑
function Unbundling($oldmobile,$mobile){
    Db::name('order')->where(array('mobile'=>$oldmobile,'pay_status'=>'1','closed'=>'0','is_import'=>'1'))->update(array('mobile'=>$mobile));
    Db::name('PackageOrder')->where(array('mobile'=>$oldmobile,'closed'=>'0','is_import'=>'1'))->update(array('uid'=>'0','mobile'=>$mobile));
    return 1;
}


/**
 * 所有课程学习人数
 */
function  get_course_studynum(){
    $data = array();
    $prefix = config('database.prefix');
    $sql = "select count('id') as study_num,course_id FROM ".$prefix."order where  pay_status = 1 GROUP BY course_id ";
    $studynum_arr = db('order')->query($sql);
    if($studynum_arr){
        foreach ($studynum_arr as $val){
            $study_key = $val['course_id'];
            $data[$study_key] = $val['study_num'];
        }
    }
    return $data;
}
/**
 * 所有优惠码使用情况
 */
function  get_coupon_useuum(){
    $data = array();
    $prefix = config('database.prefix');
    $sql = "select coupon_id, count('id') as use_num FROM ".$prefix."order where closed = 0 GROUP BY coupon_id ";
    $usenum_arr = db('order')->query($sql);
    if($usenum_arr){
        foreach ($usenum_arr as $val){
            $key = $val['coupon_id'];
            if($key){
                $data[$key] = $val['use_num'];
            }
            
        }
    }
    return $data;
}
/**
 * 获取课程视频第一个试看的
 */
function get_first_free_video($cid){
    $free_video =0;
    if($cid){
        $video_where['cid'] = $cid;
        $video_where['free'] = 1;
        $video_where['closed'] = 0;
        $free_video=db('video')->where($video_where)->order('id asc')->find();
        if(!$free_video){
            $free_video =0;
        }
    }
    return $free_video;
    
}


/**
 * 删除用户订单 优惠码 
 */
function delete_code($coupon_code,$order_sn){
    $uid = session('uid');
    if(!$uid){
        errorJson('您未登录');
    }
    $order = db('Order')->where(['order_sn'=>$order_sn,'pay_status'=>0,'uid'=>$uid,'coupon_code'=>$coupon_code])->order('id desc')->find();
    $order_price = $order['price'];$return_order_price=0;
    if($order_price&&$coupon_code){//减掉订单增加的金额
        $return_order_price =$order['old_price'];
//         $coupon_code_data = db('Coupon_code')->where(['coupon_code'=>$coupon_code,'closed'=>0,'audit'=>1])->find();
//         if($coupon_code_data['discount_type']==2){//抵价
//             $return_order_price = $order_price+$coupon_code_data['discount'];
//         }elseif ($coupon_code_data['discount_type']==1){//打折
//             if($coupon_code_data['discount']>0){$return_order_price = $order_price/($coupon_code_data['discount']*0.1);}
//         }
//         elseif($coupon_code_data['discount_type']==0){//免费
//             $return_order_price =$order['old_price'];
//         }
        update('Order',['order_sn'=>$order_sn],['price'=>$return_order_price,'coupon_code'=>'','coupon_id'=>0]);
    }
    return $return_order_price;
    
}

/**
 * 系統配置开关是否开启
 */
function show_switch($name){
    $is_switch = db('show_switch')->where(['id'=>1])->value($name);
    return $is_switch;
}
/**
 * 系统配置开关 更新开关
 */
function update_show_switch($name,$value){
    $is_update = db('show_switch')->where(['id'=>1])->setField([$name=>$value]);
    return $is_update;
}


/**
 * 获取微信操作对象（单例模式）
 * @staticvar array $wechat 静态对象缓存对象
 * @param type $type 接口名称 ( Card|Custom|Device|Extend|Media|Oauth|Pay|Receive|Script|User )
 * @return \Wehcat\WechatReceive 返回接口对接
 */
function load_wechat($type = '',$data) {
    Loader::import('wechat.include');
    static $wechat = array();
    $index = md5(strtolower($type));
    if (!isset($wechat[$index])) {
        // 定义微信公众号配置参数（这里是可以从数据库读取的哦）
        $options = array(
            'token'           => 'weixin', // 填写你设定的key
            'appid'           => $data['appid'], // 填写高级调用功能的app id, 请在微信开发模式后台查询
            'appsecret'       => $data['appsecret'], // 填写高级调用功能的密钥
            'encodingaeskey'  => '', // 填写加密用的EncodingAESKey（可选，接口传输选择加密时必需）
            'mch_id'          => '', // 微信支付，商户ID（可选）
            'partnerkey'      => '', // 微信支付，密钥（可选）
            'ssl_cer'         => '', // 微信支付，双向证书（可选，操作退款或打款时必需）
            'ssl_key'         => '', // 微信支付，双向证书（可选，操作退款或打款时必需）
            'cachepath'       => '', // 设置SDK缓存目录（可选，默认位置在Wechat/Cache下，请保证写权限）
        );
        \Wechat\Loader::config($options);
        $wechat[$index] = \Wechat\Loader::get($type);
    }
    return $wechat[$index];
}
/**
 * 获取套餐 banner 以及banner 下的色值
 */
function get_package_banner($banner,$course_id){
    $banner_color = array();
    if($banner){
        $course_id_arr = array_slice($course_id,0,2);
    }else{
        $course_id_arr = array_slice($course_id,0,3);
    }
    $ret_map['cid'] = ['in',$course_id_arr];
    $ret_map['closed'] = 0;
    $ret_map['audit'] = 1;
    $course_arr = db('course')->where($ret_map)->field('banner,main_color')->select();
    if($course_arr){
        if($banner){
            for($i=0;$i<count($course_arr);$i++){
                $banner_color[] = $course_arr[$i]['main_color'];
            }
        }else{
            $banner =$course_arr[0]['banner'];
            for($i=1;$i<count($course_arr);$i++){
                $banner_color[] = $course_arr[$i]['main_color'];
            }
        }
    }
    $banner_arr['banner'] = $banner;
    $banner_arr['banner_color'] = $banner_color;
    return $banner_arr;
}
