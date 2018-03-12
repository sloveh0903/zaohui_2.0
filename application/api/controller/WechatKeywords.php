<?php
namespace app\api\controller;
use think\Controller;

/**
 * Class 关键词回复
 * @package app\api\controller
 */
class WechatKeywords extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->getIndex();
    }

    /**
     * 关键词列表
     * @author王宣成
     * @return Json
     */
    public function getIndex()
    {
        $wechatKeywords = lists('WechatKeywords');
        successJson('操作完成',$wechatKeywords);
    }

    /**
     * 添加关键词
     */
    public function postCreate(){
        $content = input('content');
        $keywords = input('keywords');
        if(empty($keywords)){
            errorJson('关键词不能为空');
        }
        if(empty($content)){
            errorJson('内容不能为空');
        }
        insert('WechatKeywords',['keywords'=>$keywords,'content'=>$content]);
        successJson();
    }

    /**
     * 编辑关键词
     */
    public function postUpdate(){
        $id = input('id');
        $content = input('content');
        update('WechatKeywords',['id'=>$id],['content'=>$content]);
        successJson();
    }

    /**
     * 删除关键词
     */
    public function postDelete(){
        $id = input('id');
        delete('WechatKeywords',['id'=>$id]);
        successJson();
    }


}
