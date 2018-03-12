<?php
namespace app\wechat\controller;
use think\Controller;
use app\api\controller\Api;

/**
 * Card 会员卡
 * @package app\api\controller
 */
class Card extends Controller
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
        $task = find('Task');
        $this->assign('task',$task);
    }

    /**
     *会员卡列表
     */
    public function index()
    {
        $user_card = lists('UserCard',[],['mouth'=>'asc']);
        if(empty($user_card)){
            $this->error('未设置会员卡');
        }
        $vip = isvip();
        if($vip == 1){
            $user = find('User',['uid'=>session('uid')]);
            $expire_time = $user['expire_time'];
            $this->assign('expire_time',$expire_time);
        }
        $this->assign('vip',$vip);
        $this->assign('user_card',$user_card);
        return view();
    }

}
