<?php

namespace app\admin\controller;
use app\admin\model\Rebate;
use app\admin\model\User;
use app\admin\model\RebateConfig;
use app\admin\model\Withdraw;
use think\Db;
use app\model\IntegralLog;
class Distribution extends Base
{

    public function _empty(){
        return $this->index();
    }

    /**
     * 设置分销参数
     * Create By Xenos
     * Return Json
     */
    public function index(){
        $rebate_config = new RebateConfig();
        $config = $rebate_config->where('closed','0')->find();
        if(Db::name('course')->where(array('poster_audit'=>'1','closed'=>'0'))->find()){
            $poster = true;
        }else{
            $poster = false;
        }

        if(request()->isPost()){
            $param = input('post.');
            if(isset($param['id'])){
                $where['id'] = $param['id'];
                $flag = $rebate_config->UpdateData($param,$where);
            }else{
                $flag = $rebate_config->InsertData($param);
            }
            $this->log($param);
            return json(['status' => $flag['status'],'url'=>'', 'data' => '', 'msg' => $flag['msg']]);
        }
        if($config){
            $this->assign('config',$config);
            $this->assign('poster',$poster);
            return $this->fetch('edit_config');
        }else{
            $this->assign('poster',$poster);
            return $this->fetch('add_config');
        }

    }

    /**
     * 分销员列表
     * Create By Xenos
     * Return Json
     */
    public function rebater(){
        $key = input('key');

        $map = [];
        $map['closed'] = 0;
        $map['is_rebate'] = 1;
        if($key){
            $map['nickname'] = ['like',"%" . $key . "%"];
        }

        $member = new User();
        $rebate = new Rebate();
        $Nowpage = input('get.p') ? input('get.p'):1;
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $member->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $member->where($map)->field('uid,mobile,nickname')->order('uid desc')->limit($start,$limits)->select();
        foreach ($lists as $k => $v) {
            $total = $rebate->where(array('fxlevel'=>'1','uid'=>$v['uid'],'closed'=>'0'))->sum('order_money');
            $orther = $rebate->where(array('fxlevel'=>['between',[1,4]],'uid'=>$v['uid'],'closed'=>'0'))->sum('money');
            $lists[$k]['First_total'] = $total ? $total : 0;
            $lists[$k]['First_count'] = $rebate->where(array('fxlevel'=>'1','uid'=>$v['uid'],'closed'=>'0'))->count('id');
            $lists[$k]['Orther_total'] = $orther ? $orther : 0;
            $lists[$k]['Secord_people'] = $member->where(array('p1'=>$v['uid'],'closed'=>'0'))->count('uid');
            $lists[$k]['Third_people'] = $member->where(array('p2'=>$v['uid'],'closed'=>'0'))->count('uid');
        }
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="分销员";
            $msg['pages'] = $allpage;
            return json($msg);
        }

        return $this->fetch();
    }


    /**
     *
     * 分销订单列表
     * Create By Xenos
     * Return Json
     */

    public function rebate(){
        $uid = $so['uid'] = input('uid');
        $key = input('key');
        $type = input('type',"First");
        $map = [];
        switch ($type){
            case "First":
                $map['fxlevel'] = 1;
                break;
            case "Second":
                $map['fxlevel'] = 2;
                break;
            case "Third":
                $map['fxlevel'] = 3;
                break;
        }
        $map['uid'] = $uid;
        $map['closed'] = 0;
        if($key){
            $map['order_sn'] = ['like',"%" . $key . "%"];
        }
        $rebate = new Rebate();
        $Nowpage = input('get.p') ? input('get.p'):1;
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $rebate->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $rebate->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $UpperInfo = [];
            if($type == "Second"){
                $UpperInfo = $this->getSuperior($v['buy_uid'],'uid,nickname',1);
            }else{
                $UpperInfo = $this->getSuperior($v['buy_uid'],'uid,nickname',2);
            }
            if($UpperInfo){
                $lists[$k]['nickname'] = $UpperInfo['nickname'];
            }
        }
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="分销订单列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        $this->assign('uid',$uid);
        $this->assign('type',$type);
        return $this->fetch();
    }

    /**
     * 分销列表
     * Create By Xenos
     * Return
     */
    public function rebateList(){
        $key = input('key');

        $map = [];
        $map['closed'] = 0;
        $map['fxlevel'] = 1;
        if($key){
            $map['order_sn'] = ['like',"%" . $key . "%"];
        }

        $member = new User();
        $rebate = new Rebate();
        $Nowpage = input('get.p') ? input('get.p'):1;
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $rebate->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $rebate->where($map)->field('uid,order_sn,order_id,order_money,money,create_time')->order('uid desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $user = $member->where(array('uid'=>$v['uid'],'closed'=>0))->field('uid,nickname')->find();
            $lists[$k]['nickname'] = $user['nickname'];
        }

        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="分销列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }

        return $this->fetch();
    }

    /**
     * 提现记录
     * Create By Xenos
     * Return
     */
    public function withDraw(){
        $key = input('key');
        $status = input('status',"0");
        $map = [];
        $map['closed'] = 0;
        switch ($status){
            case "0":
                $map['status'] = 0;
                break;
            case "2":
                $map['status'] = 2;
                break;
        }
        if($key){
            $map['cash_no'] = ['like',"%".$key."%"];
        }
        $withdraw = new Withdraw();
        $member = new User();
        $Nowpage = input('get.p') ? input('get.p'):1;
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $withdraw->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $withdraw->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($lists as $k => $v) {
            $lists[$k]['nickname'] = $member->where(array('uid'=>$v['uid'],'closed'=>'0'))->value('nickname');
            switch ($v['status']){
                case "0":
                    $flag = "<font class='nopayment'>未打款</font>";
                    break;
                case "1":
                    $flag = "<font class='payment'>已打款</font>";
                    break;
                case "2":
                    $flag = "<font class='reject'>驳回</font>";
                    break;
            }
            $lists[$k]['flag'] = $flag;
        }
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="提现记录";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        $this->assign('status',$status);
        return $this->fetch();
    }

    /**
     * 确认打款
     * Create By Xenos
     * Return
     */
    public function payment(){
        $id = input('id');
        if(!$id){
            return json(['status' => "-1",'url'=>'', 'data' => '', 'msg' => "参数错误"]);
        }
        $withdraw = new Withdraw();
        $where['id'] = $id;
        $param['status'] = 1;
        $flag = $withdraw->SetParam($param,$where);
        if($flag['status'] == '200'){
            $msg = "打款成功";
        }else{
            $msg = "打款失败";
        }
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $msg]);
    }

    /**
     * 驳回申请提现
     * Create By Xenos
     * Return
     */
    public function reject(){
        $param = input('post.');
        if(!isset($param['id'])){
            return json(['status' => "-1",'url'=>'', 'data' => '', 'msg' => "参数错误"]);
        }
        $withdraw = new Withdraw();
        $member = new User();
        $data = $withdraw->get($param['id']);
        $where['id'] = $param['id'];
        $param['status'] = 2;
        $flag = $withdraw->UpdateData($param,$where);
        if($flag['status'] == "200"){
            $member->where('uid',$data['uid'])->setInc('money',$data['money']);
            $url = url('distribution/withdraw?status=2');
            return json(['status' => "200",'url'=>$url, 'data' => '', 'msg' => "驳回提现成功"]);
        }else{
            return json(['status' => "-1",'url'=>'', 'data' => '', 'msg' => "驳回提现失败"]);
        }

    }

    /**
     * 获取上级信息
     * Create By Xenos
     * Return
     */
    function getSuperior($uid,$field = 'uid,nickname,mobile',$level = 1){
        $user = new User();
        $result = [];
        $superior_uid = "";
        $selfInfo = $user->where(array('uid'=>$uid,'closed'=>'0'))->field('uid,p1,p2,p3')->find();
        if($selfInfo){
            switch ($level){
                case "1":
                    $superior_uid = $selfInfo['p1'];
                    break;
                case "2":
                    $superior_uid = $selfInfo['p2'];
                    break;
                case "3":
                    $superior_uid = $selfInfo['p3'];
                    break;
            }
            if($superior_uid){
                $result = $user->where(array('uid'=>$superior_uid,'closed'=>'0'))->field($field)->find();
            }
        }
        return $result;

    }
    private function  log($list){
        $config_id = 3;
        if($list){
            $adminlog_detail = new AdminLog();
            $adminlog_detail->log($config_id, $list);
        }
    }
}