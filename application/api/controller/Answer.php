<?php
namespace app\api\controller;
use think\Controller;
use think\File;
use think\Db;
use app\api\validate as validate;
use app\api\controller\Integral;
/**
 * Class Answer 回答
 * @package app\api\controller
 */
class Answer extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->getIndex();
    }

    /**
     * 回答列表
     * @author王宣成
     * @return Json
     */
    public function getIndex(){
        $aid = input('aid'); //问题id
        $pid = input('pid'); //父级id
        $uid = input('uid');
        $page = input('page',1);
        $size = input('size',10);
        if($aid) $answer_where['aid'] = $aid;
        if($pid) $answer_where['pid'] = $pid;
        if($uid) $answer_where['uid'] = $uid;
        $answer_where['page'] = $page;
        $answer_where['size'] = $size;
        $answer = lists('Answer',$answer_where);
        $uid_arr = [];
        foreach($answer as $v){
            $uid_arr[] = $v['uid'];
            $uid_arr[] = $v['puid'];
        }
        //用户信息
        $user_where['uid'] = ['in',$uid_arr];
        $user_field = 'uid,nickname,face';
        $user = lists('User',$user_where,[],$user_field);
        $ret_user = $answerList = [];
        foreach ($user as $list) {
            $ret_user[$list['uid']] = $list;
        }

        //点赞数据
        $itemid = [];
        if($uid){
            $like = lists('Like',['uid'=>$uid],['typeid '=>2]);
            if(!$like) $like = [];
            foreach($like as $v){
                $itemid[] = $v['itemid'];
            }
        }
        //组装问答用户
        foreach($answer as $v){
            $ret_answer = [];
            $ret_answer['id'] = $v['id'];
            $ret_answer['uid'] = $v['uid'];
            $ret_answer['aid'] = $v['aid'];
            $ret_answer['likes'] = $v['likes'];
            $ret_answer['comments'] = $v['comments'];
            $ret_answer['content'] = $v['content'];
            $ret_answer['pid'] = $v['pid'];
            $ret_answer['puid'] = $v['puid'];
            $ret_answer['anonymous'] = $v['anonymous'];
            $ret_answer['photopath'] = json_decode($v['photopath']);
            $ret_answer['create_time'] = $v['create_time'];
            $ret_answer['nickname'] = "";
            $ret_answer['face'] = "";
            $ret_answer['pnickname'] = "";
            $ret_answer['pface'] = "";
            $ret_answer['root_id'] = $v['root_id'];;
            if(array_key_exists($v['uid'],$ret_user)){
                $ret_answer['nickname'] = $ret_user[$v['uid']]['nickname'];
                $ret_answer['face'] = $ret_user[$v['uid']]['face'];
            }
            if(array_key_exists($v['puid'],$ret_user)){
                $ret_answer['pnickname'] = $ret_user[$v['puid']]['nickname'];
                $ret_answer['pface'] = $ret_user[$v['puid']]['face'];
            }
            //是否点过赞
            $ret_answer['has_likes'] = 0;
            if(in_array($v['id'], $itemid)){
                $ret_answer['has_likes'] = 1;
            }
            $answerList[] = $ret_answer;
        }
        $data['answer'] = $answerList;
        successJson('操作完成',$data);
    }

    /**
     * 回答列表
     * @author王宣成
     * @return Json
     */
    public function getIndexpc(){
        $aid = input('aid'); //问题id
        $pid = input('pid'); //父级id
        $uid = input('uid');
        $page = input('page',1);
        $size = input('size',10);
        if($aid) $answer_where['aid'] = $aid;
//        if($pid) {
//            $answer_where['pid'] = $pid;
//            array(['=',1],['=',3],'or');
//        }
//        $answer_where['page'] = $page;
//        $answer_where['size'] = $size;
        //$answer = lists('Answer',$answer_where);

        if($pid){
            $answer = db::name('answer')
                ->where("root_id=:root_id or pid =:pid", ['root_id' => $pid, 'pid' => $pid])
                ->where('closed','0')
                ->where($answer_where)
                ->order('id','desc')
                ->limit(($page-1)*$size,$size)
                ->select();
        }else{
            $answer = db::name('answer')
                ->where('closed','0')
                ->where($answer_where)
                ->order('id','desc')
                ->limit(($page-1)*$size,$size)
                ->select();
        }

        echo db('answer')->getlastsql();

        $uid_arr = [];
        foreach($answer as $v){
            $uid_arr[] = $v['uid'];
            $uid_arr[] = $v['puid'];
        }
        //用户信息
        $user_where['uid'] = ['in',$uid_arr];
        $user_field = 'uid,nickname,face';
        $user = lists('User',$user_where,[],$user_field);
        $ret_user = $answerList = [];
        foreach ($user as $list) {
            $ret_user[$list['uid']] = $list;
        }

        //点赞数据
        $itemid = [];
        if($uid){
            $like = lists('Like',['uid'=>$uid],['typeid '=>2]);
            if(!$like) $like = [];
            foreach($like as $v){
                $itemid[] = $v['itemid'];
            }
        }
        //组装问答用户
        foreach($answer as $v){
            $ret_answer = [];
            $ret_answer['id'] = $v['id'];
            $ret_answer['uid'] = $v['uid'];
            $ret_answer['aid'] = $v['aid'];
            $ret_answer['likes'] = $v['likes'];
            $ret_answer['comments'] = $v['comments'];
            $ret_answer['content'] = $v['content'];
            $ret_answer['pid'] = $v['pid'];
            $ret_answer['puid'] = $v['puid'];
            $ret_answer['anonymous'] = $v['anonymous'];
            $ret_answer['photopath'] = json_decode($v['photopath']);
            $ret_answer['create_time'] = $v['create_time'];
            $ret_answer['nickname'] = "";
            $ret_answer['face'] = "";
            $ret_answer['pnickname'] = "";
            $ret_answer['pface'] = "";
            $ret_answer['root_id'] = $v['root_id'];
            if(is_numeric($ret_answer['create_time'])){
                $ret_answer['create_time'] = timeFormat($ret_answer['create_time']);
            }else{
                $ret_answer['create_time'] = timeFormat(strtotime($ret_answer['create_time']));
            }
            if(array_key_exists($v['uid'],$ret_user)){
                $ret_answer['nickname'] = $ret_user[$v['uid']]['nickname'];
                $ret_answer['face'] = $ret_user[$v['uid']]['face'];
            }
            if(array_key_exists($v['puid'],$ret_user)){
                $ret_answer['pnickname'] = $ret_user[$v['puid']]['nickname'];
                $ret_answer['pface'] = $ret_user[$v['puid']]['face'];
            }
            //是否点过赞
            $ret_answer['has_likes'] = 0;
            if(in_array($v['id'], $itemid)){
                $ret_answer['has_likes'] = 1;
            }
            $answerList[] = $ret_answer;
        }
        $data['answer'] = $answerList;
        successJson('操作完成',$data);
    }


    /**
     * 回答详情
     * @author王宣成
     * @return Json
     */
    public function getDetail(){
        $id = input('id'); //回答id
        $aid = input('aid',0); //问题id
        $root_id = input('root_id',0); //回答id
        $page = input('page',1);
        $size = input('size',10);
        $user_id = input('uid');
        $where['id'] = $id;
        $answer = find('Answer',$where);
        if(!$answer) errorJson('数据不存在');
        //获取点赞
        $answer['has_likes'] = 0;
        if($user_id){
            $like = lists('Like',['uid'=>$user_id],['typeid '=>2,'itemid'=>$id]);
            if($like){
                $answer['has_likes'] = 1;
            };
        }
        $uid = $answer['uid'];
        $user_field = 'uid,uname,nickname,face';
        $user = find('User',['uid'=>$uid],[],$user_field);
        if(!$user)  errorJson('数据错误');
        $answer['nickname'] = $user['nickname'];
        $answer['uname'] = $user['uname'];
        $answer['face'] = $user['face'];
        $answer['create_time'] = dateymd($answer['create_time']);
        if(empty($answer['photopath'])){
            $answer['photopath'] =[];
        }else{
            $answer['photopath'] = json_decode($answer['photopath'],true);
        }
        $answer_common = lists('Answer',['aid'=>$aid,'root_id'=>$root_id,'page'=>$page,'size'=>$size]);
        $uid_arr = [];
        foreach($answer_common as $v){
            $uid_arr[] = $v['uid'];
            $uid_arr[] = $v['puid'];
        }
        $user_where['uid'] = ['in',$uid_arr];
        $user = lists('User',$user_where,[],$user_field);
        $ret_user = $answer_common_list = [];
        foreach ($user as $list) {
            $ret_user[$list['uid']] = $list;
        }
        foreach($answer_common as $v){
            $ret_answer_common = [];
            $ret_answer_common['id'] = $v['id'];
            $ret_answer_common['uid'] = $v['uid'];
            $ret_answer_common['aid'] = $v['aid'];
            $ret_answer_common['likes'] = $v['likes'];
            $ret_answer_common['comments'] = $v['comments'];
            $ret_answer_common['content'] = $v['content'];
            $ret_answer_common['pid'] = $v['pid'];
            $ret_answer_common['puid'] = $v['puid'];
            $ret_answer_common['anonymous'] = $v['anonymous'];
            $ret_answer_common['photopath'] = json_decode($v['photopath']);
            $ret_answer_common['create_time'] = dateymd($v['create_time']);
            $ret_answer_common['uname'] = '';
            $ret_answer_common['nickname'] = '';
            $ret_answer_common['pnickname'] = '';
            $ret_answer_common['pface'] = '';
            $ret_answer_common['face'] = '';
            if(array_key_exists($v['uid'],$ret_user)){
                $ret_answer_common['uname'] = $ret_user[$v['uid']]['uname'];
                $ret_answer_common['nickname'] = $ret_user[$v['uid']]['nickname'];
                $ret_answer_common['face'] = $ret_user[$v['uid']]['face'];
            }
            if(array_key_exists($v['puid'],$ret_user)){
                $ret_answer_common['pnickname'] = $ret_user[$v['puid']]['nickname'];
                $ret_answer_common['face'] = $ret_user[$v['puid']]['face'];
            }
            $answer_common_list[] = $ret_answer_common;
        }
        $data['answer'] = $answer;
        $data['answer_common'] = $answer_common_list;
        successJson('操作完成',$data);
    }



    /**
     * 回答
     * @author王宣成
     * @return Json
     */
    public function postAdd(){
        $uid = input('uid',0,'htmlspecialchars');
        $aid = input('aid',0,'htmlspecialchars');
        $pid = input('pid',0,'htmlspecialchars');
        $puid = input('puid',0,'htmlspecialchars');
        $root_id = input('root_id',0,'htmlspecialchars');
        $content = input('content','','htmlspecialchars');
        //$content = string_remove_xss(input('content',''));
        $anonymous = input('anonymous',0,'htmlspecialchars');

        // 获取表单上传文件
        $files = request()->file('image',[]);
        if(!is_array($files)) $files = [];
        if(count($files)>3) errorJson('最多上传三张图片');
        $img_arr = [];
        foreach($files as $file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->validate(['size'=>256780,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads/image/answer/');
            if($info){
                // 成功上传后 获取上传信息
                $img_arr[] = '/public/uploads/image/answer/'.$info->getSaveName();
            }else{
                // 上传失败获取错误信息
                errorJson($file->getError());
            }
        }
        $photopath = json_encode($img_arr);
        //验证器
        $validate = validate('Answer');
        $data = [
            'uid'=>$uid,
            'aid'=>$aid,
            'pid'=>$pid,
            'puid'=>$puid,
            'content'=>$content,
            'photopath'=>$photopath,
            'anonymous' => $anonymous,
            'root_id' =>$root_id,
            'ip' =>ip2long($_SERVER["REMOTE_ADDR"]),
        ];
        if(!$validate->check($data)){
            errorJson(($validate->getError()));
        }
        $ask = find('Ask',['id'=>$aid]);
        if(!$ask) errorJson('问题不存在');
        if(show_switch('is_canask') != 1){
            $cid = $ask['cid'];
            $course = find('Course',['cid'=>$cid]);
            $checkbuy = checkbuy(['uid'=>$uid,'cid'=>$cid]);
            if($course > 0 && $checkbuy == 0 && $course['price']>0){
                errorJson('购买该课程后回复');
            }
        }
        $user = find('User',['uid'=>$uid]);
        if(!$user) errorJson('用户不存在');
        $is_answer = find('Answer',$data);
        if($is_answer) errorJson('重复回答');
        $answer = insert('Answer',$data);
        if(!$answer) errorJson('回复失败');

        //回答
        if($pid == 0){
            setInc('Ask',['id'=>$aid],'comments');
        }

        //回复
        if($pid > 0 && $root_id == 0){
            setInc('Answer',['id'=>$pid],'comments');
        }

        //回复@
        if($root_id > 0){
            setInc('Answer',['id'=>$root_id],'comments');
            setInc('Answer',['id'=>$pid],'comments');
        }

        $remark = '';
        if($puid > 0){
            if($puid == 0){
                $remark = $user['nickname'].'回答了问题';
            }else{
                $puser = find('User',['uid'=>$puid]);
                if(!$puser){
                    errorJson('回复人不存在');
                }
                $remark = $user['nickname'].'回复了'.$puser['nickname'];
            }
        }
        //增加积分  todo
        $integral_api = new Integral();
        $integral_data = $integral_api->answer($uid,$aid);//回答
        //发送消息
        if($pid == 0){
            $target = $aid;
            $ask = find('Ask',['id'=>$aid]);
            $read_uid = $ask['uid'];
        }else{
           // $target = $pid;
            $answer = find('Answer',['id'=>$pid]);
            $read_uid = $answer['uid'];
            $target = $answer['aid'];
        }
        $param = array ("type" => 1,"sender" => $uid,"content"=>$content,"target"=>$target, 'targetType' => 'ask', 'action' => 'comment');
        $nid = sendnotify($param);
        $param = [
            'uid' => $read_uid,
            'nid' =>$nid,
            'isread'=> 0,
        ];
        sendread($param);
        $data['integral'] = $integral_data['integral'];
        $data['integral_code'] = $integral_data['integral_code'];
        $data['msg'] = $integral_data['msg'];
        successJson('操作完成',$data);
    }


    /**
     * 回答
     */
    public function postSubmit(){
        $uid = input('uid');
        $aid = input('aid',0);
        $pid = input('pid',0);
        $puid = input('puid',0);
        $root_id = input('root_id',0);
        $content = input('content','');
        $anonymous = input('anonymous',0);
        $photopath = input('imgpath','');
        $photopath = trim($photopath,',');
        if(!empty($photopath)){
            $photopath = explode(",",$photopath);
            $photopath = json_encode($photopath);
        }

        //验证器
        $validate = validate('Answer');
        $data = [
            'uid'=>$uid,
            'aid'=>$aid,
            'pid'=>$pid,
            'puid'=>$puid,
            'content'=>$content,
            'photopath'=>$photopath,
            'anonymous' => $anonymous,
            'root_id' =>$root_id,
            'ip' =>ip2long($_SERVER["REMOTE_ADDR"]),
        ];
        if(!$validate->check($data)){
            errorJson(($validate->getError()));
        }
        $is_answer = find('Answer',$data);
        if($is_answer) errorJson('重复回答');
        $user = find('User',['uid'=>$uid]);
        if(!$user) errorJson('用户不存在');
        $answer = insert('Answer',$data);
        if(!$answer) errorJson('回复失败');
        $ask = find('Ask',['id'=>$aid]);
        if($ask){
            $save = [
                'comments'=>$ask['comments']+1,
                'new_comments' => $ask['new_comments']+1,
            ];
            update('Ask',['id'=>$aid],$save);
        }

        if($puid == 0){
            $remark = $user['nickname'].'回答了问题';
        }else{
            $puser = find('User',['uid'=>$puid]);
            if(!$puser){
                errorJson('回复人不存在');
            }
            $remark = $user['nickname'].'回复了'.$puser['nickname'];
        }
        //增加积分  todo
        $integral_api = new Integral();
        $integral_data = $integral_api->answer($uid,$aid);//回答
        //发送消息
        if($pid == 0){
            $target = $aid;
            $ask = find('Ask',['id'=>$aid]);
            $read_uid = $ask['uid'];
        }else{
            $target = $pid;
            $answer = find('Answer',['id'=>$pid]);
            $read_uid = $answer['uid'];

        }
        $param = array ("type" => 1,"sender" => $uid,"content"=>$content,"target"=>$target, 'targetType' => 'ask', 'action' => 'comment');
        $nid = sendnotify($param);
        $param = [
            'uid' => $read_uid,
            'nid' =>$nid,
            'isread'=> 0,
        ];
        sendread($param);
        $data['integral'] = $integral_data['integral'];
        $data['integral_code'] = $integral_data['integral_code'];
        $data['msg'] = $integral_data['msg'];
        successJson('操作完成',$data);
    }

    //上传图片
    public function postUpload(){
        $file = request()->file('image');
        if(!$file){
            errorJson('请选择图片');
        }
        $info = $file->validate(['size'=>5048576,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads/image/answer/');
        if($info){
            // 成功上传后 获取上传信息
            $path = '/public/uploads/image/answer/'.$info->getSaveName();
            successJson('操作完成', $path);
        }else{
            // 上传失败获取错误信息
            errorJson($file->getError());
        }
    }



}
