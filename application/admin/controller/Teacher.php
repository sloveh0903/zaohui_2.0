<?php

namespace app\admin\controller;
use app\admin\model\Follow;
use app\admin\model\User;

class Teacher extends Base
{
    public function _empty(){
        return $this->index();
    }

    public function index(){
        $key = $so['nickname'] = input('key');
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $map = [];
        $map['closed'] = 0;
        $map['type'] = 2;
        if($key&&$key!==""){
            $map['nickname'] = ['like',"%" . $key . "%"];
        }
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }
        if($end_date){
            $map['create_time'] = $so['create_time '] = ['<',strtotime($end_date)];
        }
        $member = new User();
        $Nowpage = input('get.p') ? input('get.p'):1;
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $member->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $member->where($map)->order('uid desc')->limit($start,$limits)->select();
        foreach ($lists as $k => $v) {
            $lists[$k]['ip'] = long2ip($v['ip']);
            $lists[$k]['sex'] = ($v['sex'] == "1") ? "男" : "女" ;
            $lists[$k]['edit_url'] = \think\Url::build('teacher/edit_teacher','uid='.$v['uid']);
            $lists[$k]['follow_url'] = \think\Url::build('teacher/follower','uid='.$v['uid']);
        }

        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="老师列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }

        return $this->fetch();


    }

    public function read_member(){
        $uid = input('param.id');
        $member = new User();
        $itemInfo = $member->get($uid);
        $this->assign('itemInfo',$itemInfo);
        return $this->fetch();
    }


    public function edit_teacher()
    {
        $member = new User();
        if(request()->isPost()){
            $param = input('post.');
            if(strpos('http://', $param['face']) !== false){
                $param['face'] = 'http://'.$_SERVER['HTTP_HOST'].$param['face'];
            }
            $where['uid'] = $param['uid'];
            $flag = $member->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'/admin/teacher/index', 'data' => '', 'msg' => $flag['msg']]);
        }

        $uid = input('param.uid');
        $itemInfo = $member->get($uid);
        $this->assign('itemInfo',$itemInfo);
        return $this->fetch();
    }



    public function member_state(){
        $uid = input('param.uid');
        $member = new User();
        $status = $member->where(array('uid'=>$uid))->value('audit');//判断当前状态情况
        if($status==1)
        {
            $flag = $member->where(array('uid'=>$uid))->setField(['audit'=>0]);
            return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => '已禁止']);
        }
        else
        {
            $flag = $member->where(array('uid'=>$uid))->setField(['audit'=>1]);
            return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => '已开启']);
        }
    }

    public function delall_member(){
        $ids = input('param.checkbox');
        $member = new User();
        $where['uid'] = ['in',$ids];
        $flag = $member->DeleteData($where);
        return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    public function del_member(){
        $uid = input('param.id');
        $where['uid'] = $uid;
        $member = new User();
        $flag = $member->DeleteData($member);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function setaudit_member(){
        $ids = input('param.checkbox');
        $member = new User();
        $param['audit'] = 1;
        $where['uid'] = ['in',$ids];
        $flag = $member->SetParam($param,$where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function follower(){
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $uid = input('param.uid');
        $follow = new Follow();
        $member = new User();
        $map = [];
        $map['tid'] = $uid;
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }
        if($end_date){
            $map['create_time'] = $so['create_time '] = ['<',strtotime($end_date)];
        }
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $followList = $follow->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($followList as $k => $v) {
            $followList[$k]['nickname'] = $member->where(array('uid'=>$v['uid'],'closed'=>'0'))->value('nickname');
        }
        $count = $follow->where($map)->count();
        $allpage = ceil($count / $limits);
        $this->assign('uid', $uid);
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $followList;
            $msg['data']['title']="关注列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();

    }


    public function delall_follower(){
        $ids = input('param.checkbox');
        $where['id'] = ['in',$ids];
        $follow = new Follow();
        $flag = $follow->DeleteData($where);
        return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    public function del_follower(){
        $id = input('param.id');
        $where['id'] = $id;
        $follow = new Follow();
        $flag = $follow->DeleteData($where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


}