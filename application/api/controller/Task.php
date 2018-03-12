<?php
namespace app\api\controller;
use think\Controller;
use think\Db;

/**
 * Class Task 虚拟币任务
 * @package app\api\controller
 */
class Task extends Controller
{
    public function _initialize()
    {

    }


    /**
     *我的任务列表
     */
    public function getList(){
        $page = input('page',1);
        $size = input('size',10);
        $uid = input('uid');
        $taskUser = lists('TaskUser',['uid'=>$uid,'page'=>$page,'size'=>$size]);
        $data = [
            'taskUser'=>$taskUser,
        ];
        successJson('ok',$data);
    }

    /**
     * 虚拟币信息
     */
    public function getTask(){
        $uid = input('uid');
        $user = find('User',['uid'=>$uid]);
        if(!$user){
            errorJson('用户不存在');
        }
        $coin = $user['coin'];
        $taskRule = find('TaskRule');
        $task = find('Task');
        $count = counts('TaskUser',['uid'=>$uid]);
        $data = [
            'coin'=>$coin,
            'taskRule'=>$taskRule,
            'task'=>$task,
            'count'=>$count,
        ];
        successJson('ok',$data);
    }

    /**
     * 分享海报
     */
    public function getPoster(){
        $uid = input('uid'); //用户id
        $id = input('id',1); //背景图id
        $font = input('font','simsun.ttc'); //字体
        $font = ROOT_PATH . 'public' . DS .'font/'.$font;
        $user = find('User',['uid'=>$uid]);
        if(!$user){
            errorJson('用户不存在');
        }

        //生成二维码
        $task_dir = ROOT_PATH . 'public/task/';
        if (!is_dir($task_dir)) mkdir($task_dir); // 如果不存在则创建
        if (!is_dir($task_dir.'qrcode/')) mkdir($task_dir.'qrcode/'); // 如果不存在则创建
        if (!is_dir($task_dir.'user/')) mkdir($task_dir.'user/');
        if (!is_dir($task_dir.'poster/')) mkdir($task_dir.'poster/');
        $day = date('Y-m-d',time());
        $filename = $uid.'_'.$day.'.jpg';
        $key = $uid.'_0_1';
        $new_qrcode_path = $task_dir.'qrcode/'.$uid.'_'.$day.'.png';
        if(!file_exists ($task_dir.'qrcode/'.$filename)){
            $getAccessToken =  getAccessToken();
            $token = $getAccessToken['access_token'];
            $qrcode_url ='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token;
            $data = '{"expire_seconds": 604800, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$key.'"}}}';
            $curl = doCurlPostRequest($qrcode_url,$data);
            $json_curl = json_decode($curl,true);
            $ticket = $json_curl['ticket'];
            $qrcode_path = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
            $getImage = getImage($qrcode_path,$task_dir.'/qrcode/',$filename);
            $qrcode_path = $getImage['save_path'];
        }else{
            $qrcode_path = $task_dir.'qrcode/'.$filename;
        }
        $dst_path = ROOT_PATH . '/public/image/task_b'.$id.'.png'; //原图
        $src_path = ROOT_PATH . 'public/task/user/'.$uid.'.png'; //头像水印图

        //头像地址
        $face_path = $user['face'];
        $substr = substr($face_path, 0, 4);
        if ($substr != 'http') {
            $face_path = ROOT_PATH.$face_path;
        }else{
            $filename = $uid.'.jpg';
            if(!file_exists ($task_dir.'user/'.$filename)){
                $getImage = getImage($face_path,$task_dir.'user/',$filename);
                $face_path = $getImage['save_path'];
            }else{
                $face_path = ROOT_PATH . 'public/task/user/'.$uid.'.jpg';
            }
        }
        $text = $user['nickname'];
        $text1 = "向您推荐知识店铺";
        $taskRule = find('TaskRule');
        $text3 = $taskRule['content'];

        //图片大小
        $face_str = substr($face_path , 0 , 5);
        if($face_str != 'https'){
            $face_path = str_replace("http","https",$face_path);
        }

        resize_image($face_path, $src_path, '120', '120');
        resize_image($qrcode_path, $new_qrcode_path, '180', '180');

        //裁剪头像圆形
        $imgg = yuanimg($src_path);
        imagepng($imgg,$src_path);

        //创建图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));
        $src = imagecreatefromstring(file_get_contents($src_path));
        $qrcode = imagecreatefromstring(file_get_contents($new_qrcode_path));

        //打上文字
        $black = imagecolorallocate($dst, 0x00, 0x00, 0x00);//字体颜色

        //获取水印图片的宽高
        list($src_w, $src_h) = getimagesize($src_path);
        list($dst_w, $dst_h) = getimagesize($dst_path);
        list($qr_w, $qr_h) = getimagesize($new_qrcode_path);

        //获取水印图片的宽高
        $fontSize = 18;//像素字体
        $fontBox = imagettfbbox($fontSize, 0, $font, $text);//文字水平居中实质
        $fontBox1 = imagettfbbox($fontSize, 0, $font, $text1);//文字水平居中实质
        $fontBox2 = imagettfbbox($fontSize, 0, $font, $text3);//文字水平居中实质

        imagefttext($dst, $fontSize, 0, ceil(($dst_w - $fontBox[2]) / 2), 440, $black, $font, $text);
        imagefttext($dst, $fontSize, 0, ceil(($dst_w - $fontBox1[2]) / 2), 480, $black, $font, $text1);
        imagefttext($dst, $fontSize, 0, ceil(($dst_w - $fontBox2[2]) / 2), 550, $black, $font, $text3);
        //将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
        //imagecopymerge($dst, $src, 200, 300, 0, 0, $src_w, $src_h, 100);
        //如果水印图片本身带透明色，则使用imagecopy方法
        imagecopy($dst, $src, ceil(($dst_w - $src_w) / 2), 270, 0, 0, $src_w, $src_h);
        imagecopy($dst, $qrcode, ceil(($dst_w - $qr_w) / 2), $dst_h-480, 0, 0, $qr_w, $qr_h);

        //输出图片
        header('Content-Type: image/jpeg');
        //imagejpeg($dst);
        $basedir = ROOT_PATH . 'public/task/poster/';
        imagejpeg($dst,$basedir.$uid.'.jpg',70);
        imagedestroy($dst);
        successJson('ok','/public/task/poster/'.$uid.'.jpg');
    }


}
