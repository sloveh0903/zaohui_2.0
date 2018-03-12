<?php

namespace app\admin\controller;
use app\admin\model\Notify;
use think\Db;

class Message extends Base
{

    public function index(){
        $key = $so['content'] = input('key');
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $map = [];
        $map['n.type'] = 1;
        $map['n.closed'] = 0;
        if($key&&$key!==""){
            $map['n.content'] = ['like',"%" . $key . "%"];
        }
        if($start_date){
            $map['n.create_time'] = ['>',strtotime($start_date)];
        }
        if($end_date){
            $map['n.create_time'] = $so['create_time '] = ['<',strtotime($end_date)];
        }
        $Nowpage = input('get.p') ? input('get.p'):1;
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = Db::name('notify')->alias('n')
            ->join('gz_read a','n.id = a.nid','left')
            ->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = Db::name('notify')->alias('n')
            ->field('n.*,a.nid,a.uid,a.isread')
            ->join('gz_read a','n.id = a.nid','left')
            ->where($map)
            ->limit($start,$limits)->select();
        //$lists = $notify->fetchSql(true)->join('read','notify.id = read.nid')->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($lists as $k => $v) {
            $lists[$k]['edit_url'] = \think\Url::build('message/edit_remind','id='.$v['id']);
            $item_title = "";
            switch ($v['action']){
                case 'comment':
                    $lists[$k]['action_name'] = "评论";
                    break;
                case 'like':
                    $lists[$k]['action_name'] = "点赞";
                    break;
                case 'notice':
                    $lists[$k]['action_name'] = "通知";
                    break;
            }
            switch ($v['targetType']){
                case 'ask':
                    $lists[$k]['type_name'] = "问答";
                    $item = Db::name('ask')->where('id',$v['target'])->find();
                    $item_title = $item['content'];
                    break;
                case 'answer':
                    $lists[$k]['type_name'] = "问答";
                    $item = Db::name('answer')->where('id',$v['target'])->find();
                    $item_title = $item['content'];
                    break;
                case 'article':
                    $lists[$k]['type_name'] = "文章";
                    $item = Db::name('article')->where('id',$v['target'])->find();
                    $item_title = $item['title'];
                    break;
            }
            $send = Db::name('user')->where('uid',$v['sender'])->field('uid,nickname,face')->find();
            if($v['uid']==0){
                $receive['nickname']= '老师';
            }else{
                $receive = Db::name('user')->where('uid',$v['uid'])->field('uid,nickname,face')->find();
            }
            
            $lists[$k]['sendName'] = $send['nickname'];
            $lists[$k]['receiveName'] = $receive['nickname'];
            $lists[$k]['title'] = $item_title;
            $lists[$k]['isread'] = ($v['isread']) ? "已读" : "未读";
            $lists[$k]['create_time'] = date("Y-m-d",$v['create_time']);
        }
        /*p($lists);
        die();*/
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="消息列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }

    public function remind(){
        $key = $so['content'] = input('key');
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $map = [];
        $map['type'] = 2;
        $map['closed'] = 0;
        if($key&&$key!==""){
            $map['content'] = ['like',"%" . $key . "%"];
        }
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }
        if($end_date){
            $map['create_time'] = $so['create_time '] = ['<',strtotime($end_date)];
        }
        $notify = new Notify();
        $Nowpage = input('get.p') ? input('get.p'):1;
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $notify->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $notify->where($map)->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $lists[$k]['edit_url'] = \think\Url::build('message/edit_remind','id='.$v['id']);
        }
        //$lists = $notify->fetchSql(true)->join('read','notify.id = read.nid')->where($map)->order('id desc')->limit($start,$limits)->select();
        /*p($lists);
        die();*/
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="通知中心";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }

    public function add_remind(){
        if(request()->isPost()) {
            $param = input('post.');
            $param['type'] = '2';
            $param['action'] = 'notice';
            $param['targetType'] = 'notice';
            $notify = new Notify();
            $flag = $notify->InsertData($param);
            return json(['status' => $flag['status'],  'url'=>'/admin/message/remind', 'data' => '', 'msg' => $flag['msg']]);
        }
        return $this->fetch();
    }

    public function edit_remind(){
        $notify = new Notify();
        if(request()->isPost()){
            $param = input('post.');
            $where['id'] = $param['id'];
            $flag = $notify->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'/admin/message/remind', 'data' => '', 'msg' => $flag['msg']]);

        }

        $id = input('param.id');
        //\think\Url::build();
        $message = $notify->where('id',$id)->find();
        $this->assign('message',$message);
        return $this->fetch();
    }

}