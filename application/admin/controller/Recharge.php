<?php

namespace app\admin\controller;
use app\admin\model\AuthGroup;
use app\admin\model\RechargeRecord;
use think\Controller;
use think\Db;
use think\config;

class Recharge extends Controller
{
    public function _empty(){
        return $this->index();
    }
    //充值页面
    public function index()
    {
        $map = [];
        $map['closed'] = 0;
        $Nowpage = input('get.p') ? input('get.p'):1;
        $money = Db::name('qiniu_account')->limit(1)->value('money');
        $recharge = new RechargeRecord();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $recharge->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $recharge->where($map)->order('id desc')->limit($start,$limits)->select();
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        $this->assign('money', $money ? $money : 0);
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="充值列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }

    /**
     * 存储跟流量使用情况
     * Create By Xenos
     * Return
     */
    public function statistic(){
        $limits = 10;// 获取总条数
        $Account = Db::name('qiniu_account')->limit(1)->find();
        $Nowpage = input('get.p') ? input('get.p'):1;
        $start = $limits * ($Nowpage - 1);
        $map['closed'] = '0';
        $list = Db::name('qiniu_statistic')->where($map)->order('id desc')->limit($start,$limits)->select();
        $where = [];
        $count = Db::name('qiniu_statistic')->where($where)->order('id desc')->count();
        $allpage = ceil($count / $limits);
        $data = [];
        foreach ($list as $value) {
            $retdata = [];
            $retdata['id'] = $value['id'];
            $retdata['type'] = $value['type'];
            $retdata['total'] = round($value['total']/1073741824,2);
            $retdata['money'] = $value['money'];
            $retdata['create_time'] = date('Y-m-d',$value['create_time']);
            $data[] = $retdata;
        }
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $data;
            $msg['data']['title']="流量存储列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        $this->assign('space', round($Account['space']/1073741824,2)); //存储
        $this->assign('flux', round($Account['flux']/1073741824,2)); //流量
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('data', $data); //数据
        return $this->fetch();
    }

    /**
     * 充值中心
     * Create By Xenos
     * Return
     */
    public function create()
    {
        $type = input('type',1);
        $price = input('price',0);
        $recharge = new RechargeRecord();
        if($price == 0){
            return json(['status' => -4, 'data' => '', 'msg' => '请输入金额']);
        }
        $data = [
            'pay_type'=>$type,
            'price'=>$price,
            'sn'=>time() . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT),
        ];
        if(!$result = $recharge->InsertData($data)){
            return json(['status' => -4, 'data' => '', 'msg' => '充值下单失败']);
        }
        $param['money'] = $data['price'];
        $param['nonce_str'] = time();
        $param['sn'] = $data['sn'];
        $param['notify'] = 'http://'.$_SERVER['HTTP_HOST'].'/api/recharge/notify.html';
        $stringA = $stringB = '';
        ksort($param);
        foreach ($param as $key => $val) {
            $stringA.= $key.'='.$val.'&';
            if($key == 'notify'){
                $stringB.= $key.'='.urlencode($val).'&';
            }else{
                $stringB.= $key.'='.$val.'&';
            }

        }
        $appkey = Config::get('pay_secret');
        $stringSignTemp = $stringA.'key='.$appkey;
        $token2 = strtoupper(md5($stringSignTemp));
        $stringB .= '&sign'.'='.$token2;
        if($type == '1'){
            $data['string'] = "http://saas.grazy.cn/index/index/wxpay.html?".$stringB;
        }else{
            $data['string'] = "http://saas.grazy.cn/index/index/alipay.html?".$stringB;
        }
        return json(['status' => 200, 'data' => $data, 'msg' => '下单成功']);

    }


}