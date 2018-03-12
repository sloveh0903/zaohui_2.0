<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
/**
 * Class Like 点赞
 * @package app\api\controller
 */
class Like extends Controller
{
    public function _initialize()
    {

    }

    /**
     * 点赞
     * @author王宣成
     * @return Json
     */
    public function postAdd(){
        $itemid = input('itemid');
        $uid = input('uid');
        $typeid = input('typeid',1);
        //验证器
        $validate = validate('Like');
        $data = [
            'itemid' => $itemid,
            'uid' => $uid,
            'typeid' => $typeid,
        ];
        $like =  find('Like',$data);
        if($like){
            successJson('已点赞过');
        }
        if(!$validate->check($data)){
            errorJson(($validate->getError()));
        }

        Db::startTrans();
        try{
            //问题点赞+1
            if($typeid == 1){
                $ask = find('Ask',['id'=>$itemid]);
                if($ask){
                    $like_num = $ask['likes'] + 1;
                    $update = update('Ask',['id'=>$itemid],['likes'=>$like_num]);
                    if($update){
                        $like = insert('Like',$data);
                        if(!$like) json_encode('点赞失败');
                    }
                }
            }
            //回答点赞+1
            if($typeid == 2){
                $answer = find('Answer',['id'=>$itemid]);
                if($answer){
                    $aid = $answer['aid'];
                    setInc("Ask",['id'=>$aid],'likes');
                    setInc("Answer",['id'=>$itemid],'likes');
                    $like = insert('Like',$data);
                    if(!$like) json_encode('点赞失败');
                }else{
                    if(!$like) json_encode('点赞失败');
                }
            }

            if($typeid == 3){
                $articleComment = find('ArticleComment',['id'=>$itemid]);
                if($articleComment){
                    setInc('ArticleComment',['id'=>$itemid],'likes');
                    $like = insert('Like',$data);
                    if(!$like) json_encode('点赞失败');
                }else{
                    if(!$like) json_encode('点赞失败');
                }
            }

            //发送消息

            $content = "";
            $read_uid = 0;
            $target = 0;
            if($typeid == 1 || $typeid == 2){
                $targetType = 'ask';
                $answer = find('Answer',['id'=>$itemid]);
                $target = $answer['aid'];
                $read_uid= $answer['uid'];
                $content = $answer['content'];
            }
            if($typeid == 3){
                $targetType = 'article';
                $ArticleComment = find('ArticleComment',['id'=>$itemid]);
                $target = $ArticleComment['aid'];
                $read_uid= $ArticleComment['uid'];
                $content = $ArticleComment['content'];
            }
            if($target != 0){
                $param = array ("type" => 1,"sender" => $uid,"content"=>$content,"target"=>$target, 'targetType' => $targetType, 'action' => 'like');
                $nid = sendnotify($param);
                $param = [
                    'uid' => $read_uid,
                    'nid' =>$nid,
                    'isread'=> 0,
                ];
                sendread($param);
            }
            // 提交事务
            Db::commit();
            successJson('点赞成功');
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            json_encode('点赞失败');
        }

    }

}
