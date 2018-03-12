<?php

namespace app\admin\controller;
use app\admin\model\AuthRule;
use app\admin\model\AuthGroup;
use app\admin\model\AuthGroupAccess;

class Role extends Base
{
    public function _empty(){
        return $this->index();
    }

    /**
     * [index 角色列表]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function index(){

        $key = $so['title'] = input('key');
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $map = [];
        if($key&&$key!==""){
            $map['title'] = ['like',"%" . $key . "%"];
        }
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }
        if($end_date){
            $map['create_time'] = $so['create_time '] = ['<',strtotime($end_date)];
        }
        $role = new AuthGroup();
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $role->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $role->where($map)->order('id asc')->limit($start,$limits)->select();
        foreach ($lists as $k => $v){
            $lists[$k]['get_role'] = \think\Url::build('role/get_role','id='.$v['id']);
        }
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="角色列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }


    /**
     * [roleAdd 添加角色]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function add_role()
    {
        if(request()->isPost()){

            $param = input('post.');
            $role = new AuthGroup();
            $flag = $role->InsertData($param);
            return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
        }

        return $this->fetch();
    }


    /**
     * [roleEdit 编辑角色]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function edit_role()
    {
        $role = new AuthGroup();
        if(request()->isPost()){
            $param = input('post.');
            $where['id'] = $param['id'];
            $flag = $role->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
        }

        return $this->fetch();
    }


    /**
     * [role_state 用户状态]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function roleStatus()
    {
        $role = new AuthGroup();
        $id = input('param.id');
        $status = $role->where(array('id'=>$id))->value('status');//判断当前状态情况
        if($status==1)
        {
            $flag = $role->where(array('id'=>$id))->setField(['status'=>0]);
            return json(['status' => 1, 'data' => '', 'msg' => '已禁用']);
        }
        else
        {
            $flag = $role->where(array('id'=>$id))->setField(['status'=>1]);
            return json(['status' => 0, 'data' => '', 'msg' => '已启用']);
        }

    }

    /**
     * [roleDel 删除角色]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function del_role()
    {
        $id = input('param.id');
        $role = new AuthGroup();
        $where['id'] = $id;
        $flag = $role->DeleteData($where,true);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function delAll_role()
    {
        $ids = input('param.checkbox');
        $role = new AuthGroup();
        $where['id'] = ['in',$ids];
        $flag = $role->DeleteData($where,true);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    public function get_role(){
        $param = input('param.');
        $node = new AuthRule();

        $nodeStr = $node->field('id,title,pid')->select();
        $role = new AuthGroup();
        $rule = $role->field('rules')->where('id', $param['id'])->find();
        if(!empty($rule)){
            $rule = explode(',', $rule);
        }
        $arr = $this->formatTree($nodeStr,$rule);

        //获取现在的权限
        //分配新权限
        if(request()->isPost()){
            $rules = implode(',',array_values($param['status']));
            $doparam = [
                'id' => $param['id'],
                'rules' => $rules
            ];
            $user = new AuthGroup();
            $where['id'] = $doparam['id'];
            $flag = $user->UpdateData($doparam,$where);
            //return json($param);
            return json(['status' => $flag['status'],'url'=>'/admin/role/index', 'data' => '', 'msg' => $flag['msg']]);
        }
        $this-> assign('list',$arr);
        $this-> assign('id',$param['id']);
        return $this->fetch();
    }




    public function formatTree($array,$rule, $pid = 0){
        $arr = array();
        $tem = array();
        foreach ($array as $v) {
            if(!empty($rule) && in_array($v['id'], $rule)){
                $v['checked'] = "checked";
            }else{
                $v['checked'] = "";
            }
            if ($v['pid'] == $pid) {
                $tem = $this->formatTree($array,$rule, $v['id']);
                //判断是否存在子数组
                $tem && $v['child'] = $tem;
                $arr[] = $v;
            }
        }
        return $arr;
    }



    /**
     * [giveAccess 分配权限]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function giveAccess()
    {
        if(request()->isAjax()) {
            $param = input('param.');
            $node = new AuthRule();
            //获取现在的权限
            if ('get' == $param['type']) {
                $nodeStr = $node->getNodeInfo($param['id']);
                return json(['code' => 1, 'data' => $nodeStr, 'msg' => 'success']);
            }
            //分配新权限
            if ('give' == $param['type']) {

                $doparam = [
                    'id' => $param['id'],
                    'rules' => $param['rule']
                ];
                $user = new AuthGroup();
                $where['id'] = $doparam['id'];
                $flag = $user->UpdateData($doparam,$where);
                //return json($param);
                return json(['code' => $flag['code'], 'data' => '', 'msg' => $flag['msg']]);
            }
        }
        return $this->fetch();
    }

}