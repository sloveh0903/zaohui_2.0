<?php

namespace app\admin\logic;

use app\admin\model\AuthRule;

class MenuLogic extends Logic
{





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
            $new_result[$k]['name']=$v['name'];
            $new_result[$k]['title']=$v['title'];
            $new_result[$k]['icon']=$v['css'];

            $new_result[$k]['spread']=false;
            // $new_result[$k]['href']=$v['href'];
        }
        $menu = getMenuList($new_result);

        return $menu;
    }

    public function getAllMenu()
    {
        $authRule = new AuthRule();
        return $authRule->order('id asc')->select();
    }
}