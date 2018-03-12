<?php
namespace app\admin\controller;

class Testitembank extends Base
{
    public function _empty(){
        return $this->index();
    }
    /**
     * 首页
     */
    public function index(){  
        $key =input('key');
        if($key){
            $map['name'] = ['like',"%" . $key . "%"];
        }
        $map['closed']  = 0;
        $Nowpage = input('get.p') ? input('get.p'):1;
        $limits = 10;// 条数
        $start = $limits * ($Nowpage - 1);
        $lists =db('testitem_bank')->where($map)->order('orderby desc')->limit($start,$limits)->select();
        $count = counts('Testitem_bank',$map);//计算总数
        $allpage = ceil($count / $limits);
        foreach ($lists as $key=>$val){
            $lists[$key]['create_time'] = date("Y-m-d H:i:s",$val['create_time']);
            switch ($val['privilege']){
                case 1:
                    $privilege_name = '所有用户可以做题 ';
                    break;
                case 2:
                    $privilege_name = '购买关联课程可以做题';
                    break;
                case 3:
                    $privilege_name = '付费用户可以做题';
                    break;
            }
            $lists[$key]['privilege_name'] = $privilege_name;
        }
        
        if(request()->isAjax()){
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="题库";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        $this->assign('lists', $lists);
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        //课程分类
        $cate_list = db('CourseCategory')->where(['closed'=>0])->field('id,pid,cate_name')->order('orderby desc')->select();
        $cates = $this->tree($cate_list);
        $this->assign('cate',$cates);//课程分类列表
        //题库店铺端显示
        $is_testitemshop = show_switch('is_testitemshop');
        $this->assign('is_testitemshop',$is_testitemshop);
        return $this->fetch();
    }
    /**
     * 题库店铺端显示
     */
    public function testitemshop(){
        $is_testitemshop = show_switch('is_testitemshop');
        $switch_is_testitemshop = $is_testitemshop?0:1;
        $switch_msg = $is_testitemshop?'题库店铺端不显示':'题库店铺端显示';
        update_show_switch('is_testitemshop', $switch_is_testitemshop);
        return json(['status'  =>200, 'data' => '', 'msg' => $switch_msg]);
    }
    /**
     * 题库上下架
     */
    public function state(){
        $id = input('param.id');
        $status = db('testitem_bank')->where(['id'=>$id])->value('audit');
        if($status==0)
        {
            $flag = db('testitem_bank')->where(['id'=>$id])->setField(['audit'=>1]);
            return json(['status' => $flag['status'], 'data' => '', 'msg' => '上架']);
        }
        else
        {
            $flag = db('testitem_bank')->where(['id'=>$id])->setField(['audit'=>0]);
            return json(['status'  => $flag['status'], 'data' => '', 'msg' => '下架']);
        }
    }
    /**
     * 排序
     */
    public function moveUpDown(){
        $id = input('id');
        $updown = input('updown');
        $orderby = db('testitem_bank')->where(['closed'=>0,'id'=>$id])->order('orderby desc')->value('orderby');
        $map['closed'] = 0;
        if($updown == "up"){
            $map['orderby'] = ['>',$orderby];
            $order = 'orderby asc';
        }else{
            $map['orderby'] = ['<',$orderby];
            $order = 'orderby desc';
        }
        $data_next = db('testitem_bank')->where($map)->order($order)->find();
        if($data_next){
            $next_id = $data_next['id'];
            $next_order = $data_next['orderby'];
            db('testitem_bank')->where(['id'=>$id])->update(['orderby'=>$next_order]);
            db('testitem_bank')->where(['id'=>$next_id])->update(['orderby'=>$orderby]);
        }
        return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => '排序成功']);
    }

    
    /**
     * 删除题库
     */
    public function delelte(){
        $id = input('param.id');
        $is_delete = db('testitem_bank')->where(['id'=>$id])->update(['closed'=>1]);
        db('testitem_list')->where(['bank_id'=>$id])->update(['closed'=>1]);
        //db('testitem_user')->where(['bank_id'=>$id])->update(['closed'=>1]);
        //db('testitem_record')->where(['bank_id'=>$id])->update(['closed'=>1]);
        if($is_delete){
            return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => '删除题库成功']);
        }
        else {
            return json(['status' => -200,'url'=>'reload', 'data' => '', 'msg' => '删除题库失败']);
        }
    }
    /**
     * 编辑
     * */
    
    public function edit(){
        $param = input('post.');
        $id = $param['id'];
        $name = $param['name'];
        if(mb_strlen($name) > 30){
            return json(['status'=>'-200','url'=>'/admin/testitembank/index', 'data' => '', 'msg' => '题库名称字数不超过30个字']);
        }
        if($param['course_id']){//课后练习
            $cid = $param['course_id'];
            $course_name = db('Course')->where(['closed'=>0,'audit'=>1,'cid'=>$cid])->value('title');
            $param['course_name'] =$course_name;
            if(empty($course_name)){
                return json(['status' =>'-200','url'=>'', 'data' => '', 'msg' =>'课程不存在或者下架']);
            }
            if($param['privilege']==3){
                return json(['status' =>'-200','url'=>'', 'data' => '', 'msg' =>'权限设置错误']);
            }
        }
        else{//能力测试
            $param['course_id'] =0;
            $param['course_name'] ='';
            $param['pid'] =0;
            if($param['privilege']==2){
                return json(['status' =>'-200','url'=>'', 'data' => '', 'msg' =>'权限设置错误']);
            }
        }
        $is_up = update('Testitem_bank', ['id'=>$id],$param);
        if($is_up){
            return json(['status' =>'200','url'=>'/admin/testitembank/index', 'data' => '', 'msg' =>'修改成功']);
        }
    }
    /**
     * 创建
     */
    public function create(){
        $param = input('post.');
        $name = $param['name'];
        if(mb_strlen($name) > 30){
            return json(['status'=>'-200','url'=>'/admin/testitembank/index', 'data' => '', 'msg' => '题库名称字数不超过30个字']);
        }
        if($param['course_id']){//课后练习
            $cid = $param['course_id'];
            $course_name = db('Course')->where(['closed'=>0,'audit'=>1,'cid'=>$cid])->value('title');
            $param['course_name'] =$course_name;
            if(empty($course_name)){
                return json(['status' =>'-200','url'=>'', 'data' => '', 'msg' =>'课程不存在或者下架']);
            }
            if($param['privilege']==3){
                return json(['status' =>'-200','url'=>'', 'data' => '', 'msg' =>'权限设置错误']);
            }
        }
        else{//能力测试
            $param['course_id'] =0;
            $param['course_name'] ='';
            $param['pid'] =0;
            if($param['privilege']==2){
                return json(['status' =>'-200','url'=>'', 'data' => '', 'msg' =>'权限设置错误']);
            }
        }
        $orderby = db('testitem_bank')->where(['closed'=>0])->order('orderby desc')->value('orderby');
        $param['audit'] =0;//下架
        $param['closed'] =0;
        $param['create_time'] =time();
        $param['orderby'] =$orderby+1;
        $is_in = insert('Testitem_bank', $param);
        if($is_in){
              return json(['status' =>'200','url'=>'/admin/testitembank/index', 'data' => '', 'msg' =>'添加成功']);
        }
    }
    /**
     * 二级分类 展示
     */
    public function tree($cate ,$lefthtml = '— — ', $pid=0 ,$lvl=0, $leftpin=0 ){
        $arr=$arr_pid=array();
        foreach ($cate as $v){
            if($v['pid']==$pid){
                $c['id'] = $v['id'];
                $c['pid'] = $v['pid'];
                $c['cate_name'] = $v['cate_name'];
                $c['lvl']=$lvl + 1;
                $arr[]=$c;
                $arr= array_merge($arr,$this->tree($cate,$lefthtml,$v['id'] ,$lvl+1, $leftpin+15));
            }
        }
        
        return $arr;
    }
    /*分类 课程联动菜单*/
    public function get_item(){
        $pid = input('param.pid');
        if($pid==0){
            $data = array();
        }else{
            $course_list = lists('Course',['closed'=>0,'audit'=>1,'pid'=>$pid],'orderby desc',['cid','title']);
            $child_course_list = lists('Course',['closed'=>0,'audit'=>1,'child_id'=>$pid],'orderby desc',['cid','title']);
            $data =array_merge($course_list,$child_course_list);
        }
        
        return json($data);
        
    }
}