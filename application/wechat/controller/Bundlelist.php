<?php
namespace app\wechat\controller;
use think\Controller;


/**
 * Class Index
 * @package app\api\controller
 */
class Bundlelist extends Controller
{
    public function _initialize()
    {
        //微信sdk
        require_once EXTEND_PATH."org/wxsdk/jssdk.php";
        $config = find('Config');
        $jssdk = new \JSSDK($config['wx_public_appid'], $config['wx_public_secret']);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage',$signPackage);
        //用户信息
        $this->assign('config',$config);
        $this->assign('userinfo', wechatLogin());

    }
   public function index(){
       return $this->fetch();
   }
   public function detail(){
       $uid = session('uid');
       $this->assign('uid',$uid);
       $id = input('id',0);
       $this->assign('id',$id);
       $type = input('type',0);
       $this->assign('type',$type);
       //title
       $title = db('Package')->where(['id'=>$id])->value('title');
       $this->assign('title',$title);
       return $this->fetch();
   }




}
