<?php

namespace app\admin\controller;
use app\admin\model\Package as pg;
use app\admin\model\Course;
use think\Db;

class Package extends Base
{

    public function _empty(){
        return $this->index();
    }

    public function index(){
        $map = [];
        $map['closed'] = 0;
        $key = input('key');
        if($key){
            $map['title'] = ['like','%'.$key.'%'];
        }
        $Nowpage = input('get.p') ? input('get.p'):1;
        $pg = new pg();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $pg->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $pg->where($map)->order('orderby desc')->limit($start,$limits)->select();
        $packageList = [];
        foreach ($lists as $k=>$v) {
            $ret_package = [];
            $ret_package['id'] = $v['id'];
            $ret_package['title'] = $v['title'];
            $ret_package['price'] = $v['price'];
            $ret_package['insur_price'] = $v['insur_price'];
            $ret_package['create_time'] = $v['create_time'];
            $ret_package['insur_switch'] = $v['insur_switch'];
            $ret_package['audit'] = $v['audit'];
            $ret_package['wxminicode'] = $v['wxminicode'];
            $ret_map['cid'] = ['in',json_decode($v['course_id'])];
            $ret_package['course'] = Db::name('course')->where($ret_map)->field('cid,title')->select();
            $packageList[] = $ret_package;
        }
        $this->assign('lists', $packageList);
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        $this->assign('course_bread', '5');
        if(request()->isAjax()){
            $msg['status'] =200;
            $msg['data']['list'] = $packageList;
            $msg['data']['title']="套餐列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }

    public function add_package(){
        $course = new Course();
        $courselist = $course->where(array('closed'=>'0','audit'=>'1'))->field('cid,title')->select();
        if(request()->isPost()){
            $pg = new pg();
            $param = input('post.');
            $title = $param['title'];
            if(mb_strlen($title) > 30){
                return json(['status'=>'-200','url'=>'/admin/testitembank/index', 'data' => '', 'msg' => '标题字数不超过25个字']);
            }
            $pg_orderby = $pg->field('orderby')->order('orderby desc')->value('orderby');
            if(count(explode(',',$param['course_id'])) <= 1){
                return json(['status' => '-200','url'=>'', 'data' => '', 'msg' => '课程数量不能小于2']);
            }
            if(isset($param['course_id'])){
                $param['course_id'] = json_encode(explode(',',$param['course_id']));
            }
            $orderby = 1;
            if($pg_orderby){
                $orderby = $pg_orderby+1;
            }
            $param['orderby'] = $orderby;
            $param['audit'] = 1;

            $flag = $pg->InsertData($param);
            $log_data['course_id'] = $param['course_id'];
            $log_data['price'] = $param['price'];
            $this->log($log_data);
            return json(['status' => $flag['status'],'url'=>'/admin/package/index', 'data' => '', 'msg' => $flag['msg']]);

        }
        $top_pid_arr = db('Course_category')->where(['closed'=>0,'pid'=>0])->select();
        $this->assign('top_pid_arr', $top_pid_arr); //数据
        $this->assign('course', $courselist);
        return $this->fetch();
    }

    public function edit_package(){
        $pg = new pg();
        if(request()->isPost()){
            $param = input('post.');
            $title = $param['title'];
            //下架的课程id  （需求要求把下架的还保留）
            $package = $pg->get($param['id']);
            $courseid = db('course')->where(['audit'=>0,'closed'=>0,'cid'=>['in',json_decode($package['course_id'])]])->column('cid');
            if(mb_strlen($title) > 30){
                return json(['status'=>'-200','url'=>'/admin/testitembank/index', 'data' => '', 'msg' => '套餐标题字数不超过30个字']);
            }
            $where['id'] = $param['id'];
            if(count(explode(',',$param['course_id'])) <= 1){
                return json(['status' => '-200','url'=>'', 'data' => '', 'msg' => '课程数量不能小于2']);
            }
            if(isset($param['course_id'])){
                $array_one = $courseid;
                $array_two  = explode(',',$param['course_id']);
                $array_merge = array_merge($array_one,$array_two);
                $param['course_id'] = json_encode($array_merge);
            }
            $flag = $pg->UpdateData($param,$where);
            $log_data['id'] = $param['id'];
            $log_data['course_id'] = $param['course_id'];
            $log_data['price'] = $param['price'];
            $this->log($log_data);
            return json(['status' => $flag['status'],'url'=>'/admin/package/index', 'data' => '', 'msg' => $flag['msg']]);
        }
        $id = input('param.id');
        $package = $pg->get($id);
        $courseid = db('course')->where(['audit'=>1,'closed'=>0,'cid'=>['in',json_decode($package['course_id'])]])->column('cid');
        $package['choose_course_id'] = implode(',',$courseid);
        $package['course_count'] = count($courseid);
        $top_pid_arr = db('Course_category')->where(['closed'=>0,'pid'=>0])->select();
        $this->assign('top_pid_arr', $top_pid_arr); //数据
        $this->assign('package',$package);
        return $this->fetch();
    }

    public function package_state(){
        $id = input('param.id');
        $pg = new pg();
        $status = $pg->where(array('id'=>$id))->value('audit');//判断当前状态情况
        if($status==0)
        {
            $flag = $pg->where(array('id'=>$id))->setField(['audit'=>1]);
            return json(['status' => $flag['status'], 'data' => '', 'msg' => '上架']);
        }
        else
        {
            $flag = $pg->where(array('id'=>$id))->setField(['audit'=>0]);
            return json(['status'  => $flag['status'], 'data' => '', 'msg' => '下架']);
        }

    }

    //新增  insur支付开启关闭状态
    public function package_insur_state(){
        $id = input('param.id');
        $pg = new pg();
        $status = $pg->where(array('id'=>$id))->value('insur_switch');//判断当前状态情况
        if($status==0)
        {
            $flag = $pg->where(array('id'=>$id))->setField(['insur_switch'=>1]);
            return json(['status' => $flag['status'], 'data' => '', 'msg' => '开启']);
        }
        else
        {
            $flag = $pg->where(array('id'=>$id))->setField(['insur_switch'=>0]);
            return json(['status'  => $flag['status'], 'data' => '', 'msg' => '关闭']);
        }

    }

    public function del_package(){
        $id = input('param.id');
        $pg = new pg();
        $where['id'] = $id;
        $flag = $pg->DeleteData($where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function moveUpDown(){
        $id = input('id');
        $updown = input('updown');
        $map['closed'] = 0;
        $pg = new pg();
        $orderby = $pg->where('id',$id)->value('orderby');
        if($updown == "up"){
            $map['orderby'] = ['>',$orderby];
            $order = 'orderby asc';
        }else{
            $map['orderby'] = ['<',$orderby];
            $order = 'orderby desc';
        }
        $data_next = $pg->where($map)->order($order)->find();
        if($data_next){
            $next_id = $data_next['id'];
            $next_order = $data_next['orderby'];
            $pg->UpdateData(['orderby'=>$next_order],['id'=>$id]);
            $pg->UpdateData(['orderby'=>$orderby],['id'=>$next_id]);
        }
        return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => '排序成功']);
    }
    private function  log($list){
        $config_id = 6;
        if($list){
            $adminlog_detail = new AdminLog();
            $adminlog_detail->log($config_id, $list);
        }
    }
}