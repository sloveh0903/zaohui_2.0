<?php
namespace app\api\controller;
use think\Controller;
use app\api\validate as validate;

/**
 * Class Favorite 关注
 * @package app\api\controller
 */
class Follow extends Controller
{
    public function _initialize()
    {

    }

    /**
     * 添加关注
     * @author王宣成
     * @return Json
     */
    public function postAdd(){
        $tid = input('tid');
        $uid = input('uid');
        //验证器
        $validate = validate('Follow');
        $data = [
            'tid'=>$tid,
            'uid'=>$uid,
        ];
        if(!$validate->check($data)){
            errorJson(($validate->getError()));
        }
        $follow = find('Follow',$data,false,true);
        if($follow) {
            if($follow['closed'] == 1){
                $save['closed'] = 0;
                update('Follow',$data,$save);
            }
            setInc('User',['uid'=>$tid],'follow'); //关注数+1
            successJson('操作成功');
        }
        $favorite = insert('Follow',$data);
        if(!$favorite) errorJson('操作失败');
        setInc('User',['uid'=>$tid],'follow'); //关注数+1
        successJson('操作成功');
    }

    /**
     * 关注列表
     * @author王宣成
     * @return Json
     */
    public function getIndex(){
        $uid = input('uid',0);
        $page = input('page',1);
        $size = input('size',10);
        if(!$uid) errorJson('参数错误');
        $uid_arr = [];
        $follow = lists('Follow',['uid'=>$uid,'page'=>$page,'size'=>$size]);
        $ret_user = $followList = [];
        if($follow){
            foreach($follow as $v){
                $uid_arr[] = $v['tid'];
            }
            $user_where['uid'] = ['in',$uid_arr];
            $user =  lists('User',$user_where,[],'uid,realname,face');
            foreach ($user as $list) {
                $ret_user[$list['uid']] = $list;
            }
            foreach($follow as $v){
                $ret_follow = [];
                $ret_follow['id'] = $v['id'];
                $ret_follow['uid'] = $v['uid'];
                $ret_follow['tid'] = $v['tid'];
                $ret_follow['realname'] = "";
                $ret_follow['face'] = "";
                if(array_key_exists($v['tid'],$ret_user)){
                    $ret_follow['face'] = $ret_user[$v['tid']]['face'];
                    $ret_follow['realname'] = $ret_user[$v['tid']]['realname'];
                }
                $followList[] = $ret_follow;
            }
        }
        $data['follow'] = $followList;
        successJson('操作成功',$data);
    }

    /**
     * 取消关注
     * @author王宣成
     * @return Json
     */
    public function postDel(){
        $uid = input('uid');
        $tid = input('tid');
        if(!$uid)  errorJson('缺少uid');
        if(!$tid)  errorJson('缺少tid');
        $where['uid'] = $uid;
        $where['tid'] = $tid;
        $favorite = delete('Follow',$where);
        if(!$favorite) errorJson('操作失败');
        setDec('User',['uid'=>$tid],'follow'); //关注数-1
        successJson('操作成功');
    }

}
