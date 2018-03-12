<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\config;

/**
 * Class Notify 消息通知
 * @package app\api\controller
 */
class Notify extends Controller
{
    public function _initialize()
    {

    }

    /**
     * 消息列表
     */
    public function getIndex(){
        $uid = input('uid',0);
        $pre = config('database.prefix');
        $result =  Db::table($pre.'notify')
            ->alias('n')
            ->join($pre.'read r','r.nid = n.id')
            ->where('r.uid',$uid)
            ->order('r.isread asc')
            ->order('n.type asc')
            ->select();
        successJson('操作成功',$result);
    }





}
