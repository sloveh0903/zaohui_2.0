<?php
namespace app\wechat\controller;
use app\api\controller\Api;
use think\Controller;

/**
 * Class Index
 * @package app\api\controller
 */
class Member extends Controller
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

    public function _empty()
    {
        return $this->getIndex();
    }

    /**
     *首页
     */
    public function getIndex()
    {
        $vip = isvip();
        $user = find('User',['uid'=>session('uid')]);
        $expire_time = $user['expire_time'];
        $this->assign('expire_time',$expire_time);
        $user_card = find('UserCard');
        $this->assign('vip',$vip);
        $this->assign('user_card',$user_card);
        return view('index');
    }

    public function favorite(){
        return view('favorite');
    }

    public function order(){
        return view('order');
    }

    public function follow(){
        return view('follow');
    }

    public function ask(){
        return view('ask');
    }


    public function answer(){
        return view('answer');
    }

    public function studylist(){
        return view('studylist');
    }

    public function setup(){
        $config_data = db('config')->field('phone,wechat')->find();
        $this->assign('config_phone',$config_data['phone']);
        $this->assign('config_wechat',$config_data['wechat']);
        return view('setup');
    }

    public function aboutus(){
        return view('aboutus');
    }

    public function bindphone(){
        return view('bindphone');
    }

    public function jifen(){
        $type = input('type',0);
        $this->assign('type',$type);
        return view('jifen');
    }




}
