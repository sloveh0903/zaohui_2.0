<?php
namespace app\api\controller;
use think\Controller;

/**
 * 用户反馈
 * @package app\api\controller
 */
class Feedback extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->postAdd();
    }

    /**
     * 提交反馈
     * @author王宣成
     * @return Json
     */
    public function postAdd()
    {
        $uid = input('uid');
        $content = input('content');
        $data = [
            'uid'=>$uid,
            'content' => $content,
        ];
        //验证器
        $validate = validate('Feedback');
        if(!$validate->check($data)){
            errorJson(($validate->getError()));
        }
        $feedback = lists('Feedback',['uid'=>$uid,'page'=>1,'size'=>1],['id'=>'desc']);
        if(!empty($feedback)){
            if($feedback[0]['create_time']+10 > time()){
                errorJson('操作频繁');
            }
            $feedback = find('Feedback',['uid'=>$uid,'content'=>$content]);
            if($feedback){
                errorJson('提交反馈内容重复');
            }
        };
        $feedback = insert('Feedback',$data);
        if(!$feedback) errorJson('操作失败');
        successJson('操作完成');
    }


}
