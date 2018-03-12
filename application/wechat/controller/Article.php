<?php
namespace app\wechat\controller;
use think\Controller;


/**
 * Class Index
 * @package app\api\controller
 */
class Article extends Controller
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
        $cid = input('cid');
        if(!$cid){
            $cid = 0;//显示全部
            $catname =  '文章阅读';//分类名称
        }
        else {
            $catname = db('ArticleCategory')->where('id','=',$cid)->value('cate_name');//分类名称
        }
        $this->assign('catname',$catname);
        $this->assign('cid',$cid);
        $article_category = lists('ArticleCategory',[],['orderby'=>'asc']);
        $this->assign('category',$article_category);
        return view('index');
    }

    public function detail(){
        return view('detail');
    }

    public function teacher(){
        return view('teacher');
    }
    
}
