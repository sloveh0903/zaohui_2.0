<?php

namespace app\admin\controller;

use app\model\IntegralConfig;
use app\model\IntegralLog;
use think\Log;
class integral extends Base
{
    /**
     * 获取积分
     */
    public  function index(){
        $integral = new IntegralConfig();
        $param = input();
        //保存  积分设置
        if($param){
            $list = [
                ['id'=>1, 'integral'=>$param['integral'][1]],
                ['id'=>2, 'integral'=>$param['integral'][2]],
                ['id'=>3, 'integral'=>$param['integral'][3]],
                ['id'=>4, 'integral'=>$param['integral'][4], 'maxmum'=>$param['maxmum'][4]],
                ['id'=>5, 'integral'=>$param['integral'][5], 'maxmum'=>$param['maxmum'][5]],
                ['id'=>6, 'integral'=>$param['integral'][6], 'sign_days'=>$param['sign_days'][6]],
                ['id'=>7, 'integral'=>$param['integral'][7], 'consume_money'=>$param['consume_money'][7]],
                ['id'=>10, 'integral'=>$param['integral'][10], 'status'=>$param['status'][10]],
            ];
            $integral->saveAll($list);
            $this->log(1,$list);
            return json(['status' => 200,'url'=>'/admin/integral/index', 'data' => '', 'msg' => '保存成功']);
        }
        $lists = $integral->select();
       foreach ($lists as $val){
           $integral[$val['id']] = $val['integral'];
           $sign_days[$val['id']] = $val['sign_days'];
           $consume_money[$val['id']] = $val['consume_money'];
           $maxmum[$val['id']] = $val['maxmum'];
           $status[$val['id']]=$val['status'];
       }
       $this->assign('integral',$integral);
       $this->assign('sign_days',$sign_days);
       $this->assign('consume_money',$consume_money);
       $this->assign('maxmum',$maxmum);
       $this->assign('status',$status);
        return $this->fetch('index');
    }
   

    /**
     * 积分抵现
     */
    public   function cash(){
        $integral = new IntegralConfig();
        $param = input();
        //保存  积分设置
        if($param){
            if(!isset($param['status'])){
                $param['status'] = 0;
            }elseif($param['status'] == 'on'){
                $param['status'] = 1;
            }
            $list = [
                ['id'=>9,'status'=>$param['status'], 'deducted_integral'=>$param['deducted_integral'], 'cash_percent'=>$param['cash_percent']<=100?$param['cash_percent']:100]
            ];
            $integral->saveAll($list);
            $this->log(2,$list);
            return json(['status' => 200,'url'=>'/admin/integral/cash', 'data' => '', 'msg' => '保存成功']);
        }
        
        $lists = $integral->where('id',9)->field('id,cash_percent,deducted_integral,status')->find();
        $this->assign('cash_percent',$lists['cash_percent']);
        $this->assign('deducted_integral',$lists['deducted_integral']);
        $this->assign('status',$lists['status']);
        return $this->fetch('cash');
    }



    private function  log($config_id,$list){
        if($list){
           $adminlog_detail = new AdminLog();
           $adminlog_detail->log($config_id, $list);
        }
    }
}