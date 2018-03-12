<?php
namespace app\wechat\controller;
use think\Controller;
use app\api\controller\Api;

/**
 * Class Search 搜索
 * @package app\api\controller
 */
class Search extends Controller
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

    /**
     *首页
     */
    public function index()
    {
        return view('index');
    }

    /**
     *视频
     */
    public function video()
    {
        $keyword = input('keyword','');
        $this->assign('keyword',$keyword);
        return view('video');
    }

    /**
     *课程
     */
    public function course()
    {
        $keyword = input('keyword','');
        $this->assign('keyword',$keyword);
        return view('course');
    }



}
