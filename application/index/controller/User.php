<?php
namespace app\index\controller;
use think\Controller;
use think\Log;
use think\Db;

/**
 * 个人中心
 * @package app\api\controller
 */
class User extends Controller
{
    public function _initialize()
    {
        if(is_mobile()){
            if(is_weixin()){
                $browser = 2;
                //微信sdk
                require_once EXTEND_PATH."org/wxsdk/jssdk.php";
                $config = find('Config');
                $jssdk = new \JSSDK($config['wx_public_appid'], $config['wx_public_secret']);
                $signPackage = $jssdk->GetSignPackage();
                $this->assign('signPackage',$signPackage);
                //用户信息
                $this->assign('config',$config);
                $this->assign('userinfo', wechatLogin());
            }else{
                $browser = 1;
            }
        }else{
            if(!session('uid')){
                $this->redirect('/');
            }
            $browser = 0;
        }
        $this->assign('browser',$browser);
        $this->assign('uid',session('uid'));
    }

    /**
     * 问答消息
     */
    public function message(){
        $answer = Db::name('answer')
            ->alias('a')
            ->where('a.puid',session('uid'))
            ->where('a.closed',0)
            ->order('a.id','desc')
            ->field('a.*,u.nickname,u.face,k.title,k.id ask_id')
            ->join('gz_user u ','u.uid= a.uid')
            ->join('gz_ask k ','a.aid= k.id')
            ->paginate(10,true);
        $page = $answer->render();
        $this->assign('page', $page);
        $this->assign('answer',$answer);
        return view();
    }

    /**
     * 我的回答
     */
    public function myanswer(){
        $answer = Db::name('answer')
            ->alias('a')
            ->where('a.uid',session('uid'))
            ->where('a.closed',0)
            ->order('a.id','desc')
            ->field('a.*,u.nickname,u.face,k.title,k.id ask_id')
            ->join('gz_user u ','u.uid= a.uid')
            ->join('gz_ask k ','a.aid= k.id')
            ->paginate(10,true);
        $page = $answer->render();
        $this->assign('page', $page);
        $this->assign('answer',$answer);
        return view();
    }

    /**
     * 我的提问
     */
    public function mytiwen(){
        $ask = Db::name('ask')
            ->alias('a')
            ->where('a.uid',session('uid'))
            ->where('a.closed',0)
            ->order('a.id','desc')
            ->field('a.*,u.nickname,u.face,c.cate_name')
            ->join('gz_user u','u.uid = a.uid')
            ->join('ask_category c','c.id = a.pid')
            ->paginate(10,true);
        $page = $ask->render();
        $this->assign('page',$page);
        $this->assign('ask',$ask);
        return view();
    }

    /**
     * 设置
     */
    public function set(){

    }


}