<?php
namespace app\wechat\controller;
use think\Controller;
use app\api\controller\Api;

/**
 * Class Index
 * @package app\api\controller
 */
class Task extends Controller
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
     *我的任务
     */
    public function mytask()
    {
        return view();
    }

    /**
     *任务大厅
     */
    public function taskhall()
    {
        return view();
    }

    /**
     *分享海报
     */
    public function poster()
    {
        $uid = session('user_id');
        $poster = apiget('task/poster',['uid'=>$uid]);
        if($poster['code'] == -1){
            errorJson('获取海报失败');
        }
        $this->assign('poster',$poster['data']);
        return view();
    }


    /**
     *兑换说明
     */
    public function explain()
    {
        return view();
    }






}
