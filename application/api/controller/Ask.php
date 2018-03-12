<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\File;
use app\api\validate as validate;
use think\Log;
use app\api\controller\Integral;

/**
 * Class 问答
 * @package app\api\controller
 */
class Ask extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->getIndex();
    }

    public function getAllCourse(){
        $cate = Db::name('courseCategory')->where(array('closed'=>'0'))->order(array('orderby'=>'asc'))->select();
        foreach ($cate as $c) {
            $ret_cate[$c['id']] = $c;
            
        }
        $course = Db::name('course')->field('cid,title,pid,child_id,title')->where(array('closed'=>'0','audit'=>'1'))->select();

        foreach ($cate as $k=>$v){
            $ret_cate[$k] = $v;
            foreach ($course as $item) {
                if($item['child_id'] == 0 and $v['pid'] == 0 and $v['id'] == $item['pid']){
                    $ret_cate[$k]['topcourse'][] = $item;
                }elseif($v['pid'] == $item['pid'] and $v['id'] == $item['child_id']){
                    $ret_cate[$k]['courselist'][] = $item;
                }
            }
        }
        $ret_child = $ret_father = $ret_c = [];
        foreach ($ret_cate as $value) {
            if($value['pid'] == '0'){
                $ret_father[] = $value;
            }else{
                $ret_child[] = $value;
            }
        }
        foreach ($ret_father as $kf=>$vf) {
            $ret_c[$kf] = $vf;
            foreach ($ret_child as $c) {
                if($vf['id'] == $c['pid'] && isset($c['courselist'])){
                    $ret_c[$kf]['childcate'] = $c;
                }

            }
        }
        foreach ($ret_c as $k=>$item) {
            if(!(isset($item['topcourse']) or isset($item['childcate']))){
                unset($ret_c[$k]);
            }
        }

        $data['data'] = $ret_c;
        successJson('操作完成',$data);
    }
    function is_assoc($array) {
        if(is_array($array)) {
            $keys = array_keys($array);
            return $keys != array_keys($keys);
        }
        return false;
    }

    public function digui($cate){
        $arr=array();
        foreach ($cate as $v){
            if($v['pid']==0){
                $arr[]=$v;
                $arr= array_merge($arr,$this->digui($cate));
            }
        }
        return $arr;
    }

    public function getTree($data,$pId)
    {
        $tree = '';
        foreach($data as $k => $v)
        {

            if($v['pid'] == $pId) {        //父亲找到儿子
                $v['c_list'] = $this->getTree($data, $v['id']);
                $tree[] = $v;
                //unset($data[$k]);
            }


        }
        return $tree;
    }

    /**
     * 问答列表
     * @author王宣成
     * @return Json
     */
    public function getIndex(){
        $cid = input('cid'); //课程id
        $type = input('type');
        $uid = input('uid');
        $keyword = input('keyword'); //搜索词
        $hot = input('hot');
        $ask_order = [];
        switch($type){
            case 1:
                $ask_order['hot'] = 'desc';
                break;
            case 2:
                $ask_order['id'] = 'desc';
                break;
            case 3:
                $ask_where['comments'] = 0;
                break;
        }
        if($keyword){
            $ask_where['title'] = ['like','%'.$keyword.'%'];
        }
        if(is_array($cid)){
            $ask_where['cid'] = ['in',$cid];
        }else{
            if($cid > 0){
                $ask_where['cid'] = $cid;
            }
        }
        if(!$cid || $cid == 0){
            $cid_arr = [];
            $course = lists('Course',['audit'=>1]);
            foreach($course as $v){
                $cid_arr[] = $v['cid'];
            }
            $ask_where['cid'] = ['in',$cid_arr];
        }

      //  if($uid) $ask_where['uid'] = $cid;
        $page = input('page',1);
        $size = input('size',10);
        //问答列表
        $ask_where['page'] = $page;
        $ask_where['size'] = $size;
        if($hot){
            $ask_where['hot'] = $hot;
        }
        if(!$ask_order){
            $ask_order['hot'] = 'desc';
        }
        $ask_order['create_time'] = 'desc';
        $ask =  lists('Ask',$ask_where,$ask_order,'id,uid,cid,vid,pid,title,content,photopath,photopath_thumb,hot,views,comments,anonymous,create_time');
        $uid_arr = [];
        $aid_arr = [];
        foreach($ask as $v){
            $uid_arr[] = $v['uid'];
            $aid_arr[] = $v['id'];
        }
        //用户信息
        $user_where['uid'] = ['in',$uid_arr];
        $user_field = 'uid,uname,nickname,face,realname';
        $ask_user = lists('User',$user_where,[],$user_field);
        $ret_user = [];
        foreach ($ask_user as $list) {
            $ret_user[$list['uid']] = $list;
        }
        //回答
        $answer_where['aid'] = ['in',$aid_arr];
        $answer = lists('Answer',$answer_where);
        $answer_uid_arr = [];
        foreach($answer as $a){
            $answer_uid_arr[] = $a['uid'];
            $answer_uid_arr[] = $a['puid'];
        }
        $user_where['uid'] = ['in',$answer_uid_arr];
        $answer_user = lists('User',$user_where,[],$user_field);
        $ret_answer_user = [];
        foreach ($answer_user as $userlist) {
            $ret_answer_user[$userlist['uid']] = $userlist;
        }
       // p($user);
        foreach($answer as &$a){
            $a['realname'] = "";
            $a['nickname'] = "";
            $a['uname'] = "";
            $a['face'] = "";
            $a['puname'] = "";
            $a['pnickname'] = "";
            if(array_key_exists($a['uid'],$ret_answer_user)){
                $a['realname'] = $ret_answer_user[$a['uid']]['realname'];
                $a['uname'] = $ret_answer_user[$a['uid']]['uname'];
                $a['nickname'] = $ret_answer_user[$a['uid']]['nickname'];
                $a['face'] = $ret_answer_user[$a['uid']]['face'];
            }
            if(array_key_exists($a['puid'],$ret_answer_user)){
                $a['puname'] = $ret_answer_user[$a['puid']]['uname'];
                $a['pnickname'] = $ret_answer_user[$a['puid']]['nickname'];
            }
            $a['create_time'] = timeFormat(strtotime($a['create_time']));
        }
        unset($a);
        $itemid = [];
        if($uid){
            //我的点赞数据
            $like = lists('Like',['uid'=>$uid],['typeid '=>2],'id,uid,itemid,typeid');
            if(!$like) $like = [];
            foreach($like as $v){
                $itemid[] = $v['itemid'];
            }
        }
       //分类标题
        $data['catename'] = str_replace("\n","<br />",db('course')->where('cid',$cid)->value('title'));
        //组装问答用户
        
        foreach($ask as &$v){
            $v['uname'] = '';
            $v['realname'] = '';
            $v['nickname'] = '';
            $v['face'] = '';
            $v['create_time'] = timeFormat(strtotime($v['create_time']));
            $v['cate_name'] =str_replace("\n","<br />",db('course')->where('cid',$v['cid'])->value('title'));
            $v['title'] = str_replace("\n","<br />",$v['title']);
            if(array_key_exists($v['uid'],$ret_user)){
                $v['realname'] = $ret_user[$v['uid']]['realname'];
                $v['uname'] = $ret_user[$v['uid']]['uname'];
                $v['nickname'] = $ret_user[$v['uid']]['nickname'];
                $v['face'] = $ret_user[$v['uid']]['face'];
            }
            $v['photopath'] = json_decode($v['photopath'],true);
            $v['photopath_thumb'] = json_decode($v['photopath_thumb'],true);
            if(!is_array( $v['photopath'])){
                $v['photopath'] = [];
            }
            if(!is_array( $v['photopath_thumb'])){
                $v['photopath_thumb'] = [];
            }
            $v['answer'] = [];
            foreach($answer as $a){
                if($a['aid'] == $v['id']){
                    $v['answer'][] = $a;
                }
            }
            if(!empty($v['answer'])){
               // $v['comments'] = count( $v['answer']);
                $v['answer'] = $v['answer'][0];
                $v['answer']['has_likes'] = 0;
                if(in_array($v['answer']['id'], $itemid)){
                    $v['answer']['has_likes'] = 1;
                }
            }
            $v['create_time'] = timeFormat($v['create_time']);
        }
        unset($v);
        $data['ask'] = $ask;
        successJson('操作完成',$data);
    }

    /**
     * 问答详情
     * @author王宣成
     * @return Json
     */
    public function getDetail(){
        $id = input('id'); //问题id
        $uid = input('uid');
        $page = input('page',1);
        $size = input('size',10);
        //问题详情
        $ask_where['id'] = $id;
        $ask = find('Ask',$ask_where,'id,uid,cid,vid,pid,title,content,photopath,photopath_thumb,hot,views,comments,anonymous,create_time');
        if(!$ask) errorJson('问题数据不存在');
        //问题课程
        $cid = $ask['cid'];
        $course =  find('Course',['cid'=>$cid],'cid,audit,face,banner,title,desc,price');
        if(!$course) $course = [];
        $data['course'] = $course;
        $user_where['uid'] = $ask['uid'];
        $user_field = 'uid,uname,nickname,face';
        //问题人头像名称
        $user = find('User',$user_where,[],$user_field);
        if(!$user)errorJson('用户数据不存在');
        $ask['uname'] = $user['uname'];
        $ask['face'] = $user['face'];
        $ask['nickname'] = $user['nickname'];
        $ask['create_time'] = timeFormat(strtotime($ask['create_time']));
        if(empty($ask['photopath'])){
            $ask['photopath']= [];
            $ask['photopath_thumb'] = [];
        }else{
            $ask['photopath'] = json_decode($ask['photopath'],true);
            if(empty($ask['photopath_thumb'])){
                $ask['photopath_thumb'] = [];
            }else{
                $ask['photopath_thumb']= json_decode($ask['photopath_thumb'],true);
            }
            
        }
        $ask['title'] = str_replace("\n","<br />",$ask['title']);
        $data['question'] = $ask;
        //回答列表
        $answer_where['aid'] = $id;
        $answer_where['pid'] = 0;
        $answer_where['page'] = $page;
        $answer_where['size'] = $size;
       // $answer = lists('Answer',$answer_where,[]);
        $answer = lists('Answer',$answer_where,['uid'=>'asc','id'=>'desc']);
//        p($answer);die;
        $ret_user = $answerList = [];
        if(!empty($answer)){
            //我的点赞数据
            $itemid = [];
            if($uid){
                $like = lists('Like',['uid'=>$uid],['typeid '=>2]);
                if(!$like) $like = [];
                foreach($like as $v){
                    $itemid[] = $v['itemid'];
                }
            }
            $uid_arr = [];
            foreach($answer as $v){
                $uid_arr[] = $v['uid'];
                $uid_arr[] = $v['puid'];
            }
            $user_field = 'uid,uname,nickname,face';
            $user_where['uid'] = ['in',$uid_arr];
            $answer_user = lists('User',$user_where,[],$user_field);

            foreach ($answer_user as $list) {
                $ret_user[$list['uid']] = $list;
            }
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
                $reply_count = db('answer')->where(['closed'=>0,'pid'=>$v['id']])->count();
                $ret_answer['reply_count'] =$reply_count?$reply_count:0;
                $ret_answer['anonymous'] = $v['anonymous'];
                $ret_answer['uname'] = '';
                $ret_answer['pname'] = '';
                $ret_answer['pnickname'] = '';
                $ret_answer['nickname'] = '';
                $ret_answer['face'] = '';

                $ret_answer['create_time'] = timeFormat(strtotime($v['create_time']));
                if(empty($v['photopath'])){
                    $ret_answer['photopath'] = [];
                }else{
                    $ret_answer['photopath'] = json_decode($v['photopath'],true);
                }

                //是否点过赞
                $ret_answer['has_likes'] = 0;
                if(in_array($v['id'], $itemid)){
                    $ret_answer['has_likes'] = 1;
                }
                if(array_key_exists($v['puid'],$ret_user)){
                    $ret_answer['pname'] = $ret_user[$v['puid']]['uname'];
                    $ret_answer['pnickname'] = $ret_user[$v['puid']]['nickname'];
                }
                if(array_key_exists($v['uid'],$ret_user)){
                    $ret_answer['uname'] = $ret_user[$v['uid']]['uname'];
                    $ret_answer['nickname'] = $ret_user[$v['uid']]['nickname'];
                    $ret_answer['face'] = $ret_user[$v['uid']]['face'];

                }
                $answerList[] = $ret_answer;
            }
        }
        if($uid){
            //清空未读消息提示
            update('Ask',['id'=>$id],['new_comments'=>0]);
        }
        SetInc('Ask',['id'=>$id],'views');
        $data['answer'] = $answerList;
        successJson('操作完成',$data);
    }

    /**
     * pc问答详情
     * @author王宣成
     * @return Json
     */
    public function getDetailpc(){
        $id = input('id'); //问题id
        $uid = input('uid');
        $page = input('page',1);
        $size = input('size',10);
        //问题详情
        $ask_where['id'] = $id;
        $ask = find('Ask',$ask_where,'id,uid,cid,vid,pid,title,content,photopath,hot,views,comments,anonymous,create_time');
        if(!$ask) errorJson('问题数据不存在');
        //问题课程
        $cid = $ask['cid'];
        $course =  find('Course',['cid'=>$cid]);
        if(!$course) $course = [];
        $data['course'] = $course;
        $user_where['uid'] = $ask['uid'];
        $user_field = 'uid,uname,nickname,face';
        //问题人头像名称
        $user = find('User',$user_where,[],$user_field);
        if(!$user)errorJson('用户数据不存在');
        $ask['uname'] = $user['uname'];
        $ask['face'] = $user['face'];
        $ask['nickname'] = $user['nickname'];
        $ask['create_time'] = timeFormat(strtotime($ask['create_time']));
        if(empty($ask['photopath'])){
            $ask['photopath'] = [];
        }else{
            $ask['photopath'] = json_decode($ask['photopath'],true);
        }
        $ask['title'] = str_replace("\n","<br />",$ask['title']);
        $data['question'] = $ask;
        //回答列表
        $answer_where['aid'] = $id;
        $answer_where['pid'] = ['=',0];
        // $answer_where['root_id'] = 0;
        $answer_where['page'] = $page;
        $answer_where['size'] = $size;
        $answer = lists('Answer',$answer_where,['uid'=>'asc','id'=>'desc']);
        $ret_user = $answerList = [];
        if(!empty($answer)){
            //我的点赞数据
            $itemid = [];
            if($uid){
                $like = lists('Like',['uid'=>$uid],['typeid '=>2]);
                if(!$like) $like = [];
                foreach($like as $v){
                    $itemid[] = $v['itemid'];
                }
            }
            $uid_arr = [];
            foreach($answer as $v){
                $uid_arr[] = $v['uid'];
                $uid_arr[] = $v['puid'];
            }
            $user_field = 'uid,uname,nickname,face';
            $user_where['uid'] = ['in',$uid_arr];
            $answer_user = lists('User',$user_where,[],$user_field);

            foreach ($answer_user as $list) {
                $ret_user[$list['uid']] = $list;
            }
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
                $ret_answer['uname'] = '';
                $ret_answer['pname'] = '';
                $ret_answer['pnickname'] = '';
                $ret_answer['nickname'] = '';
                $ret_answer['face'] = '';

                $ret_answer['create_time'] = timeFormat(strtotime($v['create_time']));
                if(empty($v['photopath'])){
                    $ret_answer['photopath'] = [];
                }else{
                    $ret_answer['photopath'] = json_decode($v['photopath'],true);
                }

                //是否点过赞
                $ret_answer['has_likes'] = 0;
                if(in_array($v['id'], $itemid)){
                    $ret_answer['has_likes'] = 1;
                }
                if(array_key_exists($v['puid'],$ret_user)){
                    $ret_answer['pname'] = $ret_user[$v['puid']]['uname'];
                    $ret_answer['pnickname'] = $ret_user[$v['puid']]['nickname'];
                }
                if(array_key_exists($v['uid'],$ret_user)){
                    $ret_answer['uname'] = $ret_user[$v['uid']]['uname'];
                    $ret_answer['nickname'] = $ret_user[$v['uid']]['nickname'];
                    $ret_answer['face'] = $ret_user[$v['uid']]['face'];

                }
                $answerList[] = $ret_answer;
            }
        }
        if($uid){
            //清空未读消息提示
            update('Ask',['id'=>$id],['new_comments'=>0]);
        }
        SetInc('Ask',['id'=>$id],'views');
        $data['answer'] = $answerList;
        successJson('操作完成',$data);
    }


    /**
     * 提问
     * @author王宣成
     * @return Json
     */
    public function postQuestions(){
        $uid = input('uid',0,'htmlspecialchars');
        $cid = input('cid',0,'htmlspecialchars');
        $pid = input('pid',0,'htmlspecialchars');
        $content = input('content','','htmlspecialchars');
        $title = input('title','','htmlspecialchars');
        $anonymous = input('anonymous',0,'htmlspecialchars');

        //是否购买课程
        $getCheckbuy = checkbuy(['uid'=>$uid,'cid'=>$cid]);
        if($getCheckbuy == 0){
            //errorJson('购买课程后方可提问');
        }
        if($getCheckbuy == -1){
            errorJson('课程已过期');
        }

        // 获取表单上传文件
        $files = request()->file('image',[]);
        if(count($files)>3) {
            errorJson('最多上传三张图片');
        }
        $img_arr = [];
        if(!is_array($files)){
            $files = [];
        }
        foreach($files as $file){
           // p($file);
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->validate(['size'=>256780,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads/image/ask/');
            if($info){
                // 成功上传后 获取上传信息
                $img_arr[] = '/public/uploads/image/ask/'.$info->getSaveName();
            }else{
                // 上传失败获取错误信息
                errorJson($file->getError());
            }
        }
        $photopath = json_encode($img_arr);
        //验证器
        $validate = validate('Ask');
        $data = [
            'uid'=>$uid,
            'cid'=>$cid,
            'pid'=>$pid,
            'title'=>$title,
            'content'=>$content,
            'photopath'=>$photopath,
            'anonymous' => $anonymous,
        ];
        if(!$validate->check($data)){
            errorJson(($validate->getError()));
        }

        $user = find('User',['uid'=>$uid]);
        if(!$user) errorJson('用户不存在');

        //增加积分  todo
        $integral_api = new Integral();
        $integral_data  = $integral_api->ask($uid,$cid);//提问
        $answer = insert('Ask',$data,'id');
        if(!$answer) errorJson('提交失败');
        //发送消息
        $course= find('Course',['cid'=>$cid]);
        $course_uid = $course['uid'];
        $param = array ("type" => 1,"sender" => $uid,"content"=>$content,"target"=>$answer, 'targetType' => 'ask', 'action' => 'comment');
        $nid = sendnotify($param);
        $param = [
            'uid' => $course_uid,
            'nid' =>$nid,
            'isread'=> 0,
        ];
        sendread($param);
        $data['msg'] = $integral_data['msg'];
        $data['integral'] = $integral_data['integral'];
        $data['integral_code'] = $integral_data['integral_code'];
        successJson('操作完成',$data);
    }


    /**
     * 提问
     */
    public function postSubmit(){
        $uid = input('uid',0,'htmlspecialchars');
        $cid = input('cid',0,'htmlspecialchars');
        $pid = input('pid',0,'htmlspecialchars');
        $content = input('content','','htmlspecialchars');
        $title = input('title','','htmlspecialchars');
        $anonymous = input('anonymous',0,'htmlspecialchars');
        $photopath = input('imgpath','htmlspecialchars');
        $photopath = trim($photopath,',');
        $photopath_thumb = input('imgthumb','htmlspecialchars');
        $photopath_thumb = trim($photopath_thumb,',');
        if(!empty($photopath)){
            $photopath = explode(",",$photopath);
            $photopath = json_encode($photopath);
        }
        if(!empty($photopath_thumb)){
            $photopath_thumb = explode(",",$photopath_thumb);
            $photopath_thumb = json_encode($photopath_thumb);
        }
        //验证器
        $validate = validate('Ask');
        $data = [
            'uid'=>$uid,
            'cid'=>$cid,
            'pid'=>$pid,
            'title'=>$title,
            'content'=>$content,
            'photopath'=>$photopath,
            'photopath_thumb'=>$photopath_thumb,
            'anonymous' => $anonymous,
        ];
        if(!$validate->check($data)){
            errorJson(($validate->getError()));
        }

        $course= find('Course',['cid'=>$cid]);

        //是否购买课程
        if(show_switch('is_canask')==1){
            $getCheckbuy =1;
        }else{
            $getCheckbuy = checkbuy(['uid'=>$uid,'cid'=>$cid]);
        }

        if($getCheckbuy == 0 && $course['price'] > 0 && show_switch('is_canask') == 0){
            errorJson('购买课程后方可提问');
        }
        if($getCheckbuy == -1){
            errorJson('课程已过期');
        }

        $user = find('User',['uid'=>$uid]);
        if(!$user) errorJson('用户不存在');
        $answer = insert('Ask',$data,'id');
        if(!$answer) errorJson('提交失败');
        //增加积分  todo
        $integral_api = new Integral();
        $integral_data = $integral_api->ask($uid,$cid);//提问
        
        //发送消息

        $course_uid = $course['uid'];
        $param = array ("type" => 1,"sender" => $uid,"content"=>$content,"target"=>$answer, 'targetType' => 'ask', 'action' => 'comment');
        $nid = sendnotify($param);
        $param = [
            'uid' => $course_uid,
            'nid' =>$nid,
            'isread'=> 0,
        ];
        sendread($param);
        $data['msg'] = $integral_data['msg'];
        $data['integral'] = $integral_data['integral'];
        $data['integral_code'] = $integral_data['integral_code'];
        successJson('操作完成',$data);
    }

    //上传图片
    public function postUpload(){
        $file = request()->file('image');

        if(!$file){
            errorJson('请选择图片');
        }
        $info = $file->validate(['size'=>5048576,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads/image/ask/');
        if($info){
            // 成功上传后 获取上传信息
            $path = '/public/uploads/image/ask/'.$info->getSaveName();
            successJson('操作完成', $path);
        }else{
            // 上传失败获取错误信息
            errorJson($file->getError());
        }
    }

    //上传图片2
    public function postUploadfile(){
        header('content-type:application/json;charset=utf-8');
        $img = isset($_POST['img'])? $_POST['img'] : '';
        if(empty($img)){
            $ret = array('img'=>'');
            echo json_encode($ret);
        }
        // 获取图片
        list($type, $data) = explode(',', $img);

        // 判断类型
        if(strstr($type,'image/jpeg')!=''){
            $ext = '.jpg';
        }elseif(strstr($type,'image/gif')!=''){
            $ext = '.gif';
        }elseif(strstr($type,'image/png')!=''){
            $ext = '.png';
        }else{
            $ext = '.jpg';
        }

        // 生成的文件名
        $md5_str =  md5(time());
        $photo = $md5_str.$ext;

        // 生成文件
        file_put_contents($photo, base64_decode($data), true);
        $newFile= ROOT_PATH . 'public' . DS . 'uploads/image/ask/'.date('Ymd'); //新目录
        if(!is_dir($newFile)){
            mkdir($newFile,0777,true);
        }

        //移动文件
        copy($photo, $newFile.'/' . $photo);
        $returncode = $this->my_image_resize($newFile.'/' . $photo,$newFile.'/' .  $md5_str.'_thumb'.$ext,'216px','216px');
        $thumb ='/public' . DS . 'uploads/image/ask/'.date('Ymd').'/'.$md5_str.'_thumb'.$ext;
        if($returncode==1){
            $thumb = '1';
        }
        unlink($photo); //删除旧目录下的文件

        // 返回
        $ret = array('img'=>  '/public' . DS . 'uploads/image/ask/'.date('Ymd').'/'.$photo,'thumb'=> $thumb);
        echo json_encode($ret);
    }
    public function pictype ( $file )
    {
        /*$png_header = "/x89/x50/x4e/x47/x0d/x0a/x1a/x0a";
         $jpg_header = "/xff/xd8";*/
        $header = file_get_contents ( $file , 0 , NULL , 0 , 5 );
        //echo bin2hex($header);
        if ( $header { 0 }. $header { 1 }== "/x89/x50" )
        {
            return 'png' ;
        }
        else if( $header { 0 }. $header { 1 } == "/xff/xd8" )
        {
            return 'jpeg' ;
        }
        else if( $header { 0 }. $header { 1 }. $header { 2 } == "/x47/x49/x46" )
        {
            if( $header { 4 } == "/x37" )
                return 'gif' ;
                else if( $header { 4 } == "/x39" )
                    return 'gif' ;
        }
    }
    // 获得任意大小图像，不足地方拉伸，不产生变形，不留下空白
    public  function my_image_resize($src_file, $dst_file , $new_width , $new_height) {
        $new_width= intval($new_width);
        $new_height=intval($new_width);
        if($new_width <1 || $new_height <1) {
            echo "params width or height error !";
            exit();
        }
        if(!file_exists($src_file)) {
            echo $src_file . " is not exists !";
            exit();
        }
        // 图像类型
        $type = strrchr($src_file,'.');
        //$support_type=array(IMAGETYPE_JPEG , IMAGETYPE_PNG , IMAGETYPE_GIF);
        $support_type = array('.jpg','.jpeg','.gif','.png');
        if(!in_array($type, $support_type,true)) {
            echo "this type of image does not support! only support jpg , gif or png";
            exit();
        }
        //Load image
        switch($type) {
            case '.jpeg' :
                $src_img=imagecreatefromjpeg($src_file);
                break;
            case '.jpg' :
                $src_img=imagecreatefromjpeg($src_file);
                break;
            case '.png' :
                $src_img=imagecreatefrompng($src_file);
                break;
            case '.gif' :
                $src_img=imagecreatefromgif($src_file);
                break;
            default:
                echo "Load image error!";
                exit();
        }
        $w=imagesx($src_img);
        $h=imagesy($src_img);
        if($w>108 || $h>108){
            //原图中 宽大于高
            if($w>$h){
                $inter_w = ($w-$h)/2;
                $inter_h = $h;
                // 定义一个中间的临时图像，该图像的宽高比 正好满足目标要求
                $inter_img=imagecreatetruecolor($inter_h , $inter_h);
                imagecopy($inter_img, $src_img, 0,0,$inter_w,0,$inter_h,$inter_h);
            }else{
                $inter_w = ($h-$w)/2;
                $inter_h = $w;
                // 定义一个中间的临时图像，该图像的宽高比 正好满足目标要求
                $inter_img=imagecreatetruecolor($inter_h , $inter_h);
                imagecopy($inter_img, $src_img, 0,0,0,0,$inter_h,$inter_h);
            }
            $new_img=imagecreatetruecolor($new_width,$new_height);
            imagecopyresampled($new_img,$inter_img,0,0,0,0,$new_width,$new_height,$inter_h,$inter_h);
            switch($type) {
                case '.jpeg' :
                    imagejpeg($new_img, $dst_file,100); // 存储图像
                    break;
                case '.jpg' :
                    imagejpeg($new_img, $dst_file,100); // 存储图像
                    break;
                case '.png' :
                    imagepng($new_img,$dst_file,9);
                    break;
                case '.gif' :
                    imagegif($new_img,$dst_file,100);
                    break;
                default:
                    break;
            }
        }else{
            return 1;
        }
        
    }
}
