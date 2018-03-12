<?php
namespace app\api\controller;
use think\Controller;
use app\api\controller\Integral;
/**
 * Class Comment 评价
 * @package app\api\controller
 */
class Comment extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->getIndex();
    }

    /**
     * 评价列表
     * @author王宣成
     * @return Json
     */
    public function getIndex()
    {
        $cid = input('cid',0);
        $uid = input('uid');
        $page = input('page',1);
        $size = input('size',10);
        $comment_where['cid'] = $cid;
        $comment_where['page'] = $page;
        $comment_where['size'] = $size;
        $comment_where['audit'] = 1;
        $is_comment = 0;
        if($uid){
            if(find('Comment',['uid'=>$uid],true)){
                $is_comment = 1;
            }
        }
        $checkbuy = checkbuy(['cid'=>$cid,'uid'=>$uid]);
        if(!$checkbuy){
            $is_comment = 1;
        }
        $comment = lists('Comment',$comment_where);
        $ret_user = $commentList = [];
        if(!empty($comment)){
            $uid_arr = [];
            $user_where = [];
            foreach($comment as $v){
                $uid_arr[] = $v['uid'];
            }
            $user_where['uid'] = ['in',$uid_arr];
            $user_field = 'uid,uname,nickname,face,realname';
            $user = lists('User',$user_where,[],$user_field);
            foreach ($user as $list) {
                $ret_user[$list['uid']] = $list;
            }
            foreach($comment as $v){
                $ret_comment = [];
                for ($x=1; $x<= 5; $x++) {
                    if($x <= $v['star']){
                        $ret_comment['star_arr'][] = 1;
                    }else{
                        $ret_comment['star_arr'][] = 0;
                    }
                }
                $ret_comment['id'] = $v['id'];
                $ret_comment['cid'] = $v['cid'];
                $ret_comment['uid'] = $v['uid'];
                $ret_comment['star'] = $v['star'];
                $ret_comment['content'] = $v['content'];
                $ret_comment['create_time'] = $v['create_time'];
                $ret_comment['reply']=$v['reply']?('<strong>老师回复:</strong>'.$v['reply']):'';
                $ret_comment['nickname'] = '';
                $ret_comment['realname'] = '';
                $ret_comment['face'] = '';
                if(array_key_exists($v['uid'],$ret_user)){
                    $ret_comment['nickname'] = $ret_user[$v['uid']]['nickname'];
                    $ret_comment['face'] = $ret_user[$v['uid']]['face'];
                    $ret_comment['realname'] = $ret_user[$v['uid']]['realname'];
                }
                $commentList[] = $ret_comment;
            }
        }
        unset($comment_where['page']);
        unset($comment_where['size']);
        $comment_count = counts('Comment',$comment_where);
        $course = find('Course',['cid'=>$cid]);
        $score = $course['score'];
        //评价星星
        $star = score($score);
        
        //是否已经评论
        if($page==1&&$uid&&$cid!=0){
            $comment_sataus_where['cid'] = $cid;
            $comment_sataus_where['uid'] = $uid;
            $comment_status_id = lists('Comment',$comment_sataus_where,'id desc','id');
            $comment_status  = 0;
            if($comment_status_id){
                $comment_status = 1;
            }
            $data['comment_status'] = $comment_status;
        }
        
        $data['comment'] = $commentList;
        $data['comment_count'] = $comment_count;
        $data['average'] = $score ? $score : "10";
        $data['star'] = $star;
        $data['is_comment'] = $is_comment;
        successJson('操作完成',$data);
    }

    /**
     * 评价提交
     * @author王宣成
     * @return Json
     */
    public function postAdd(){
        $cid = input('cid',0,'htmlspecialchars');
        $uid = input('uid',0,'htmlspecialchars');
        $star = input('star',5,'htmlspecialchars');
        $content = input('content','','htmlspecialchars');
        //验证器
        $validate = validate('Comment');
        $data = [
            'cid' => $cid,
            'uid' => $uid,
            'star' => $star,
            'content' => $content,
        ];
        if(!$validate->check($data)){
            errorJson(($validate->getError()));
        }
        $comment = find('Comment',['uid'=>$uid,'cid'=>$cid]);
        if($comment) errorJson('您已评价过');
        $checkbuy = checkbuy(['uid'=>$uid,'cid'=>$cid]);
        if($checkbuy == 0){
            errorJson('未购买课程');
        }
        if($checkbuy == -1){
            errorJson('课程已过期');
        }
        $comment = insert('Comment',$data);
        if(!$comment) errorJson('提交失败');
        $comment_where['cid'] = $cid;
        $comment_sum = sum('Comment',$comment_where,'star');
        $comment_count = counts('Comment',$comment_where);
        if($comment_count){
            $score = (round($comment_sum/$comment_count, 1))*2;
        }else{
            $score = 10;
        }
        //增加积分 todo
        $integral_api = new Integral();
        $integral_data = $integral_api->comment($uid,$cid);//课程评价
        update('Course',['cid'=>$cid],['score'=>$score]);
        $data['integral'] = $integral_data['integral'];
        $data['integral_code'] = $integral_data['integral_code'];
        $data['msg'] = $integral_data['msg'];
        successJson('操作完成',$data);
    }


}
