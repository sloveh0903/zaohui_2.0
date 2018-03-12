<?php

namespace app\admin\model;
use think\Model;
use think\Db;

class AuthGroup extends Base{

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    /**
     * [getRoleInfo 获取角色信息]
     * @author [jonny] [980218641@qq.com]
     */
    public function getRoleInfo($id){

        $result = Db::name('auth_group')->where('id', $id)->find();
        if(empty($result['rules'])){
            $where = '';
        }else{
            $where = 'id in('.$result['rules'].')';
        }
        $res = Db::name('auth_rule')->field('name')->where($where)->select();

        foreach($res as $key=>$vo){
            if('#' != $vo['name']){
                $result['name'][] = $vo['name'];
            }
        }

        return $result;
    }

}