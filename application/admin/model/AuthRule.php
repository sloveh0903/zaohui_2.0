<?php

namespace app\admin\model;
use think\Model;
use think\Db;

class AuthRule extends Base{

    protected $name = "auth_rule";

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    /**
     * [getNodeInfo 获取节点数据]
     * @author [xenos] 肖彬
     */
    public function getNodeInfo($id)
    {
        $result = $this->field('id,title,pid')->select();
        $str = "";
        $role = new AuthGroup();
        $rule = $role->getRuleById($id);

        if(!empty($rule)){
            $rule = explode(',', $rule);
        }
        foreach($result as $key=>$vo){
            $str .= '{ "id": "' . $vo['id'] . '", "pId":"' . $vo['pid'] . '", "name":"' . $vo['title'].'"';

            if(!empty($rule) && in_array($vo['id'], $rule)){
                $str .= ' ,"checked":1';
            }

            $str .= '},';
        }

        return "[" . substr($str, 0, -1) . "]";
    }






    /**
     * [getMenu 根据节点数据获取对应的菜单]
     * @author [xenos] 肖彬
     */
    public function getMenu($nodeStr = '')
    {

        //超级管理员没有节点数组
        $authRule = new AuthRule();
        $where = empty($nodeStr) ? 'status = 1' : 'status = 1 and id in('.$nodeStr.')';
        $result = $authRule->where($where)->order('sort')->select();
        $new_result=array();
        foreach($result as $k=>$v){
            $new_result[$k]['id']=$v['id'];
            $new_result[$k]['pid']=$v['pid'];
            $new_result[$k]['url']=$v['name'];
            $new_result[$k]['name']=$v['title'];
            $new_result[$k]['iconfont']=$v['css'];
            // $new_result[$k]['href']=$v['href'];
        }
        $menu = getMenuList($new_result);
        $data['list'] = $menu;

        return $data;
    }

    public function getAllMenu()
    {
        $authRule = new AuthRule();
        return $authRule->order('id asc')->select();
    }




}