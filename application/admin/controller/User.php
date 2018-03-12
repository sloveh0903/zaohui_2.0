<?php

namespace app\admin\controller;
use app\admin\model\Admin;
use app\admin\model\AuthGroup;
use app\admin\model\AuthGroupAccess;
use think\Db;

class User extends Base
{
    public function _empty(){
        return $this->index();
    }

    /**
     * [index 管理员列表]
     * @return [type] [description]
     * @author [xenos]
     */
    public function index()
    {

        $key = $so['username'] = input('key');
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $map = [];
        if($key&&$key!==""){
            $map['username'] = ['like',"%" . $key . "%"];
        }
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }
        if($end_date){
            $map['create_time'] = $so['create_time '] = ['<',strtotime($end_date)];
        }
        $admin = new Admin();
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $admin->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $admin->where($map)->order('id asc')->limit($start,$limits)->select();
        foreach($lists as $k=>$v) {
            $lists[$k]['edit_url'] = \think\Url::build('edit_user','id='.$v['id']);
            $lists[$k]['bind_url'] = \think\Url::build('bindUser','id='.$v['id']);
            $lists[$k]['logintime'] = $v['logintime'] ? date('Y-m-d H:i:s',$v['logintime']) : "未登录";
            $lists[$k]['loginip'] = $v['loginip'] ? $v['loginip'] : '未登录' ;
            $lists[$k]['title'] = Db::name('auth_group')->where('id',$v['groupid'])->value('title');
            $realname = Db::name('user')->where('uid',$v['uid'])->value('nickname');
            $lists[$k]['bindtitle'] = $v['uid'] ? "已绑定(".$realname.")" : "未绑定";
        }
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="管理员列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        $role = new AuthGroup();
        $this->assign('role',$role->select());
        return $this->fetch();


    }

    /**
     * [userAdd 添加管理员]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function add_user()
    {
        if(request()->isPost()){

            $param = input('post.');
            $result = $this->validate($param, 'UserValidate');
            if(true !== $result){
                return json(['code' => -5, 'data' => '', 'msg' => $result]);
            }
            $param['passwd'] = md5(md5($param['passwd']) . config('auth_key'));
            $user = new Admin();
            $flag = $user->InsertData($param);
            $accdata = array(
                'uid'=> $user['id'],
                'group_id'=> $param['groupid'],
            );
            $group_access = Db::name('auth_group_access')->insert($accdata);
            return json(['status' => $flag['status'],'url'=>'/admin/User/index', 'data' => '', 'msg' => $flag['msg']]);
        }

        $role = new AuthGroup();
        $this->assign('role',$role->select());
        return $this->fetch();
    }


    /**
     * [userEdit 编辑管理员]
     * @return [type] [description]
     * @author [xenos]
     */
    public function edit_user()
    {
        $user = new Admin();

        if(request()->isPost()){

            $param = input('post.');
            $result = $this->validate($param, 'UserValidate');
            if(true !== $result){
                return json(['status' => -5, 'data' => '', 'msg' => $result]);
            }
            if(empty($param['passwd'])){
                unset($param['passwd']);
            }else{
                $param['passwd'] = md5(md5($param['passwd']) . config('auth_key'));
            }
            $where['id'] = $param['id'];
            $flag = $user->UpdateData($param,$where);
            $group_access = Db::name('auth_group_access')->where('uid', $user['id'])->update(['group_id' => $param['groupid']]);
            return json(['status' => $flag['status'],'url'=>'/admin/user/index', 'data' => '', 'msg' => $flag['msg']]);
        }

        $id = input('param.id');
        $role = new AuthGroup();
        $this->assign([
            'user' => $user->get($id),
            'role' => $role->select()
        ]);
        return $this->fetch();
    }


    public function edit_password(){
        $id = session('admin_uid');
        if(request()->isPost()){
            $param = input('post.');
            $user = new Admin();
            $info = $user->get($id);
            if($info['passwd'] == md5(md5($param['passwd']) . config('auth_key'))){
                if($param['newpasswd'] == $param['morepasswd']){
                    $param['passwd'] = md5(md5($param['newpasswd']) . config('auth_key'));
                    unset($param['newpasswd']);
                    unset($param['morepasswd']);
                    $where['id'] = $param['id'];
                    $flag = $user->UpdateData($param,$where);

                    return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
                }else{
                    return json(['status' => -1, 'data' => '', 'msg' => '确认密码跟新密码不一致']);
                }

            }else{
                return json(['status' => -1, 'data' => '', 'msg' => '原密码不正确']);
            }
        }
        $this->assign('id',$id);
        return $this->fetch();
    }



    /**
     * [user_state 管理员状态]
     * @return [type] [description]
     * @author [xenos]
     */
    public function userStatus()
    {
        $user = new Admin();
        $id = input('param.id');
        $status = $user->where(array('id'=>$id))->value('closed');//判断当前状态情况
        if($status==0)
        {
            $flag = $user->where(array('id'=>$id))->setField(['closed'=>1]);
            return json(['status' => 200, 'data' => '', 'msg' => '已禁止']);
        }
        else
        {
            $flag = $user->where(array('id'=>$id))->setField(['closed'=>0]);
            return json(['status' => 200, 'data' => '', 'msg' => '已开启']);
        }

    }

    public function bindUser(){
        $id = input('param.id');
        $user = new Admin();
        if(request()->isPost()){
            $param = input('post.');
            $where['id'] = $param['id'];
            $flag = $user->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'/admin/user/index', 'data' => '', 'msg' => $flag['msg']]);
        }
        $this->assign([
            'user' => $user->get($id)
        ]);
        return $this->fetch();
    }



    /**
     * [UserDel 删除管理员]
     * @return [type] [description]
     * @author [xenos]
     */
    public function del_user()
    {
        $id = input('param.id');
        $role = new Admin();
        $where['id'] = $id;
        $flag = $role->DeleteData($where,true);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function del_alluser(){
        $ids = input('param.checkbox');
        $role = new Admin();
        $where['id'] = ['in',$ids];
        $flag = $role->DeleteData($where,true);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => "批量删除成功"]);
    }

}