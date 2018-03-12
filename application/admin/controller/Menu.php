<?php

namespace app\admin\controller;
use app\admin\model\AuthRule;
use think\Db;
use org\Leftnav;

class Menu extends Base
{
    public function _empty(){
        return $this->index();
    }

    /**
     * [index 菜单列表]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function index()
    {
        $menu = new AuthRule();
        $admin_rule = $menu->getAllMenu();
        $arr = Leftnav::rule($admin_rule);
        foreach ($arr as $k=>$v) {
            $arr[$k]['edit_url'] = \think\Url::build('edit_menu','id='.$v['id']);
        }
        $this->assign('admin_rule',$arr);
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $arr;
            $msg['data']['title']="菜单列表";
            $msg['pages'] = 1;
            return json($msg);
        }
        $admin_rule = $menu->getAllMenu();
        $arr = Leftnav::rule($admin_rule);
        $this->assign('admin_rule',$arr);
        return $this->fetch('index');
    }


    /**
     * [add_menu 添加菜单]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function add_menu()
    {
        //return $this->fetch('index/center');
        $menu = new AuthRule();
        if(request()->isPost()){
            $param = input('post.');
            $flag = $menu->InsertData($param);
            return json(['status' => $flag['status'],'url'=>'/admin/menu/index', 'data' => '', 'msg' => $flag['msg']]);
        }else{
            $admin_rule = $menu->getAllMenu();
            $arr = Leftnav::rule($admin_rule);
            $this->assign('admin_rule',$arr);
            return $this->fetch();
        }
    }



    /**
     * [edit_menu 编辑菜单]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function edit_menu()
    {
        $menu = new AuthRule();

        if(request()->isPost()){
            $param = input('post.');
            $where['id'] = $param['id'];
            $flag = $menu->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'/admin/menu/index', 'data' => '', 'msg' => $flag['msg']]);
        }

        $admin_rule = $menu->getAllMenu();
        $arr = Leftnav::rule($admin_rule);
        $this->assign('admin_rule',$arr);
        $id = input('param.id');
        $this->assign('menu',$menu->getOneMenu($id));

        return $this->fetch();
    }


    /**
     * [del_menu 删除菜单]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function del_menu()
    {
        $id = input('param.id');
        $menu = new AuthRule();
        $where['id'] = $id;
        $flag = $menu->DeleteData($where,true);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function delAll_menu()
    {
        $ids = input('param.checkbox');
        $menu = new AuthRule();
        $where['id'] = ['in',$ids];
        $flag = $menu->DeleteData($where,true);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    /**
     * [ruleorder 排序]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function ruleOrder()
    {
        if (request()->isAjax()){
            $param = input('post.');
            $auth_rule = Db::name('auth_rule');
            foreach ($param as $id => $sort){
                $auth_rule->where(array('id' => $id ))->setField('sort' , $sort);
            }
            return json(['code' => 1, 'msg' => '排序更新成功']);
        }
    }


    /**
     * [rule_state 菜单状态]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function ruleState()
    {
        $menu = new AuthRule();
        $id = input('param.id');
        $status = $menu->where(array('id'=>$id))->value('status');//判断当前状态
        if($status==1)
        {
            $flag = $menu->where(array('id'=>$id))->setField(['status'=>0]);
            return json(['code' => 1, 'data' => $flag['data'], 'msg' => '已禁止']);
        }
        else
        {
            $flag = $menu->where(array('id'=>$id))->setField(['status'=>1]);
            return json(['code' => 0, 'data' => $flag['data'], 'msg' => '已开启']);
        }

    }



}