<?php

namespace app\admin\controller;
use app\admin\model\Task as Tk;
use app\admin\model\TaskReward;
use app\admin\model\TaskRule;
use app\admin\model\TaskUser;
use app\admin\model\TaskUsing;
use app\admin\model\User;
use think\Db;

class Task extends Base
{
    public function _empty(){
        return $this->index();
    }

    /**
     * [index 基础设置]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function index(){
        $task = new Tk();
        $taskInfo = $task->where('closed','0')->find();
        if(request()->isPost()){
            $param = input('post.');
            if(isset($param['id'])){
                $where['id'] = $param['id'];
                $flag = $task->UpdateData($param,$where);
            }else{
                $flag = $task->InsertData($param);
            }
            return json(['status' => $flag['status'],'url'=>'', 'data' => '', 'msg' => $flag['msg']]);
        }
        $this->assign('task',$taskInfo);
        return $this->fetch();
    }


    public function rule(){
        $rule = new TaskRule();
        $ruleInfo = $rule->where('closed','0')->find();
        if(request()->isPost()){
            $param = input('post.');
            if(isset($param['id'])){
                $where['id'] = $param['id'];
                $flag = $rule->UpdateData($param,$where);
            }else{
                $flag = $rule->InsertData($param);
            }
            return json(['status' => $flag['status'],'url'=>'', 'data' => '', 'msg' => $flag['msg']]);
        }
        $this->assign('rule',$ruleInfo);
        return $this->fetch();
    }

    public function userList(){
        $map = [];
        $map['closed'] = 0;
        $Nowpage = input('get.p') ? input('get.p'):1;
        $userList = new TaskUser();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $userList->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $userList->where($map)->order('id desc')->limit($start,$limits)->select();
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="拉新列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }

    public function rewardList(){
        $map = [];
        $map['closed'] = 0;
        $Nowpage = input('get.p') ? input('get.p'):1;
        $rewardList = new TaskReward();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $rewardList->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $rewardList->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $lists[$k]['face'] = Db::name('user')->where('uid',$v['uid'])->value('face');
            $lists[$k]['nickname'] = Db::name('user')->where('uid',$v['uid'])->value('nickname');
        }
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="奖励数据";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }

    public function coinList(){
        $key = input('key');
        $map = [];
        $map['closed'] = 0;
        $map['coin'] = ['>',0];
        if($key && $key!==""){
            $map['nickname'] = ['like',"%" . $key . "%"];
        }
        $Nowpage = input('get.p') ? input('get.p'):1;
        $user = new User();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $user->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $user->where($map)->order('coin desc')->field('uid,face,nickname,coin')->limit($start,$limits)->select();
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="使用虚拟币";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }

    public function add_using(){
        $using = new TaskUsing();
        if(request()->isPost()){
            $param = input('post.');
            if(!isset($param['uid'])){
                return json(['status' => -1,'url'=>'', 'data' => '', 'msg' => '参数错误']);
            }else{
                if($param['using'] <= 0){
                    return json(['status' => -1,'url'=>'', 'data' => '', 'msg' => '虚拟币必须是正整数']);
                }elseif($param['using'] > $param['coin']){
                    return json(['status' => -1,'url'=>'', 'data' => '', 'msg' => '虚拟币不能超过剩余虚拟币']);
                }else{
                    $data['uid'] = $param['uid'];
                    $data['coin'] = $param['using'];
                    $flag = $using->InsertData($param);
                    if($flag['status'] == 200){
                        Db::name('user')->where('uid',$data['uid'])->setDec('coin',$data['coin']);
                    }
                    return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
                }
            }
        }
    }

    public function usingList(){
        $key = input('key');
        $map = [];
        $map['closed'] = 0;
        if($key && $key!==""){
            $where['closed'] = 0;
            $where['nickname'] = ['like',"%" . $key . "%"];
            $userList = Db::name('user')->where($where)->field('uid')->select();
            if($userList){
                foreach ($userList as $value) {
                    $userinfo[] = $value['uid'];
                }
                $map['uid'] = ['in',$userinfo];
            }
        }
        $Nowpage = input('get.p') ? input('get.p'):1;
        $using = new TaskUsing();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $using->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $using->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $lists[$k]['face'] = Db::name('user')->where('uid',$v['uid'])->value('face');
            $lists[$k]['nickname'] = Db::name('user')->where('uid',$v['uid'])->value('nickname');
        }
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="使用记录";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }

}