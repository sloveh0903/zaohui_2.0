<?php
namespace app\api\controller;
use think\Controller;
use think\File;

/**
 * Class 微信消息回复
 * @package app\api\controller
 */
class WechatReply extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->getIndex();
    }

    /**
     * 显示回复内容
     */
    public function getIndex(){
        $wechat_reply = find('WechatReply');
        successJson('ok',$wechat_reply);
    }

    /*
     * 修改回复内容
     */
    public function postUpdate(){
        $content = input('content');
        update('WechatReply',['id'=>1],['content'=>$content]);
        successJson();
    }



}
