<?php
namespace app\api\controller;
use think\Controller;
use app\api\validate as validate;

/**
 * Class Favorite 收藏
 * @package app\api\controller
 */
class Favorite extends Controller
{
    public function _initialize()
    {

    }

    /**
     * 添加收藏
     * @author王宣成
     * @return Json
     */
    public function postAdd(){
        $fid = input('fid');
        $uid = input('uid');
        $title = input('title');
        $type = input('type','course');
        //验证器
        $validate = validate('Favorite');
        $data = [
            'fid'=>$fid,
            'uid'=>$uid,
            'title'=>$title,
            'type'=>$type,
        ];
        if(!$validate->check($data)){
            errorJson(($validate->getError()));
        }
        $favorite = find('Favorite',['uid'=>$uid,'fid'=>$fid,'type'=>$type],true);
        if($favorite) {
            if($favorite['closed'] == 1){
                $save['closed'] = 0;
                update('Favorite',$data,$save);
            }
            successJson('操作成功!',['id'=>$favorite['id']]);
        }
        $favorite = insert('Favorite',$data,'id');
        if(!$favorite) errorJson('操作失败');
        successJson('操作成功',['id'=>$favorite]);
    }

    /**
     * 收藏列表
     * @author王宣成
     * @return Json
     */
    public function getIndex(){
        $uid = input('uid',0);
        $page = input('page',1);
        $size = input('size',10);
        $type = input('type','course');
        $cid_arr = [];
        $uid_arr = [];
        $favorite = lists('Favorite',['uid'=>$uid,'type'=>$type,'page'=>$page,'size'=>$size]);
        foreach($favorite as $v){
            $cid_arr[] = $v['fid'];
            $uid_arr[] = $v['uid'];
        }
        $ret_user = $favoriteList = $courseitem = [];
        if($type == 'course'){
            $course_where['cid'] = ['in',$cid_arr];
            $course = lists('Course',$course_where);
            foreach($course as $c){
               $uid_arr[] =  $c['uid'];
            }
            $user_where['uid'] = ['in',$uid_arr];
            $user_field = 'uid,realname,face,info,follow';
            $user = lists('User',$user_where,[],$user_field);
            foreach ($user as $list) {
                $ret_user[$list['uid']] = $list;
            }
            foreach($course as $c){
                $ret_course = [];
                $ret_course['cid'] = $c['cid'];
                $ret_course['subscribe_num'] = $c['subscribe_num'];
                $ret_course['price'] = $c['price'];
                $ret_course['desc'] = $c['desc'];
                $ret_course['face'] = $c['face'];
                $ret_course['title'] = $c['title'];
                $ret_course['follow'] = "";
                $ret_course['info'] = "";
                $ret_course['realname'] = "";
                if(array_key_exists($c['uid'],$ret_user)){
                    $ret_course['follow'] = $ret_user[$c['uid']]['follow'];
                    $ret_course['info'] = $ret_user[$c['uid']]['info'];
                    $ret_course['realname'] = $ret_user[$c['uid']]['realname'];
                }
                $courseitem[$c['cid']] = $ret_course;
            }
            foreach($favorite as $f){
                if(array_key_exists($f['fid'],$courseitem)){
                    $ret_favorite = [];
                    $ret_favorite['id'] = $f['id'];
                    $ret_favorite['fid'] = $f['fid'];
                    $ret_favorite['uid'] = $f['uid'];
                    $ret_favorite['type'] = $f['type'];
                    $ret_favorite['create_time'] = $f['create_time'];
                    $ret_favorite['subscribe_num'] = $courseitem[$f['fid']]['subscribe_num'];
                    $ret_favorite['price'] = $courseitem[$f['fid']]['price'];
                    $ret_favorite['desc'] = $courseitem[$f['fid']]['desc'];
                    $ret_favorite['face'] = $courseitem[$f['fid']]['face'];
                    $ret_favorite['title'] = $courseitem[$f['fid']]['title'];
                    $ret_favorite['follow'] = $courseitem[$f['fid']]['follow'];
                    $ret_favorite['info'] = $courseitem[$f['fid']]['info'];
                    $ret_favorite['realname'] = $courseitem[$f['fid']]['realname'];
                    $favoriteList[] = $ret_favorite;
                }
            }
        }
        $data['favorite'] = $favoriteList;
        successJson('操作成功',$data);
    }

    /**
     * 删除收藏
     * @author王宣成
     * @return Json
     */
    public function postDel(){
        $uid = input('uid');
        $id = input('id');
        $where['uid'] = $uid;
        $where['id'] = $id;
        $favorite = delete('Favorite',$where);
        if(!$favorite) errorJson('操作失败');
        successJson('操作成功');
    }

}
