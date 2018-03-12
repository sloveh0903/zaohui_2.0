<?php
namespace app\admin\controller;

use app\admin\model\Course;
use app\admin\model\Package;
use app\admin\model\UserCard;

class Couponcode extends Base
{
    public function _empty(){
        return $this->index();
    }
    /**
     * 首页
     */
    public function index(){  
        //优惠码使用次数
        //$coupon_useuum = get_coupon_useuum();
        $lists = db('Coupon_code')->find();
        $key =input('key');
        $coupon_type = input('coupon_type',0);
        if($key){
            $map['name'] = ['like',"%" . $key . "%"];
        }
        if($coupon_type){
            $map['coupon_type']=$coupon_type;
        }
        $map['closed']  = 0;
        $Nowpage = input('get.p') ? input('get.p'):1;
        $limits = 10;// 条数
        $start = $limits * ($Nowpage - 1);
        $lists =db('Coupon_code')->where($map)->order('id desc')->limit($start,$limits)->select();
        $count = counts('Coupon_code',$map);//计算总数
        $allpage = ceil($count / $limits);
        foreach ($lists as $key=>$val){
            $use_number_name = $val['use_number']==0?'不受限制':$val['use_number'].'次';
//             $id = $val['id'];
//             $used_num = 0;
//             if(isset($coupon_useuum[$id])){
//                 $used_num = $coupon_useuum[$id];
//             }
//             $use_number_name = $use_number_name.'(已用'.$used_num.'次)';
            $lists[$key]['start_time'] = date("Y-m-d",$val['start_time']);
            $lists[$key]['end_time'] = date("Y-m-d",$val['end_time']);
            $lists[$key]['use_number_name'] =$use_number_name;
            $lists[$key]['discount_type_name'] = $this->get_discount_type_name($val['discount_type'],$val['discount']);
            $lists[$key]['item_name'] = $val['item_name'];
            $lists[$key]['coupon_type_name'] = $this->get_coupon_type_name($val['coupon_type']);
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
        $course_list = lists('Course',['closed'=>0,'audit'=>1],'orderby desc',['cid','title']);
        $this->assign('course_list', $course_list);
        return $this->fetch();
    }
    /**
     * 使用情况
     */
    public  function uselist(){
        $key =input('key');
        $id = input('id');
        if($key){
            $user_map['nickname'] = ['like',"%" . $key . "%"];
            $user_lists =db('User')->where($user_map)->column('uid');
            $map['uid']  = ['in',$user_lists];
        }
        $map['closed']  = 0;
        $map['pay_status']  = 1;
        $map['coupon_id']  =$id;
        $Nowpage = input('get.p') ? input('get.p'):1;
        $limits = 10;// 条数
        $start = $limits * ($Nowpage - 1);
        $lists =db('Order')->where($map)->order('id asc')->limit($start,$limits)->select();
        $count = counts('Order',$map);//计算总数
        $allpage = ceil($count / $limits);
        $nickname_lists = [];
        if($lists){
            foreach ($lists as $key=>$val){
                $uid_arr[] = $val['uid'];
            }
            $uid_arr = array_unique($uid_arr);
            $user_nickname_lists =db('User')->where(['uid'=>['in',$uid_arr]])->select();
            foreach ($user_nickname_lists as $ulist){
                $nickname_lists[$ulist['uid']] = $ulist['nickname'];
            }
            foreach ($lists as $key=>$val){
                if(isset($nickname_lists[$val['uid']])){
                    $lists[$key]['nickname'] = $nickname_lists[$val['uid']];
                }else{
                    //用户被删除的情况
                    unset($lists[$key]);
                    continue;
                }
                $lists[$key]['pay_status_name']= $val['pay_status']?'已支付':'未支付';
                $lists[$key]['create_time'] = date('Y-m-d H:i:s',$val['create_time']);
            }
        }
        
        if(request()->isAjax()){
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="使用情况";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        $this->assign('id', $id);
        $this->assign('lists', $lists);
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        return $this->fetch('uselist');
    }
    /*优惠码类型*/
    private function get_coupon_type_name($coupon_type){
        $coupon_type_name = '';
        switch ($coupon_type){
            case 1:
                $coupon_type_name = '关联课程 ';
                break;
            case 2:
                $coupon_type_name = '全站通用 ';
                break;
            case 3:
                $coupon_type_name = '关联VIP ';
                break;
            case 4:
                $coupon_type_name = '关联套餐 ';
                break;
        }
        return $coupon_type_name;
    }
    /*折扣类型*/
    private function get_discount_type_name($discount_type,$discount){
        $discount_type_name = '';
        switch ($discount_type){
            case 0:
                $discount_type_name = '免费 ';
                break;
            case 1:
                $discount_type_name = $discount.'折优惠';
                break;
            case 2:
                $discount_type_name = $discount.'元优惠';
                break;
        }
        return $discount_type_name;
    }
    /**
     * 创建
     */
    public function create(){
        $param = input('post.');
        $data = $this->change_data($param);
        if(isset($data['code'])){
            $msg = $data['msg'];
            return json(['status' =>'-200','url'=>'', 'data' => '', 'msg' =>$msg]);
        }
        $data['coupon_code'] = $this->get_unique_code();
        $is_in = insert('Coupon_code', $data);
        if($is_in){
            $this->log($data);
            return json(['status' =>'200','url'=>'/admin/couponcode/index', 'data' => '', 'msg' =>'添加成功']);
        }
    }
    /**
     * 编辑
     */
    public function edit(){
        $param = input('post.');
        $id = $param['id'];
        $data = $this->change_data($param);
        if(isset($data['code'])){
            $msg = $data['msg'];
            return json(['status' =>'-200','url'=>'', 'data' => '', 'msg' =>$msg]);
        }
        $is_up = update('Coupon_code', ['id'=>$id],$data);
        if($is_up){
            $data['id'] = $id;
            $this->log($data);
            return json(['status' =>'200','url'=>'/admin/couponcode/index', 'data' => '', 'msg' =>'修改成功']);
        }
    }
    
    private function change_data($param){
        $data['name']=$param['name'];//优惠码名称
        $data['use_number']=$param['use_number'];//可使用数量
        $data['start_time']=strtotime($param['start_time'].' 00:00:00');//开始时间
        $data['end_time']=strtotime($param['end_time'].' 23:59:59');//结束时间
        $data['audit'] =1;//上架
        $coupon_type = $param['coupon_type'];
        $data['coupon_type']=$coupon_type;//优惠码类型
        //使用数量
        if(empty($data['use_number'])){
            $data['use_number'] = 0;
        }
        if($coupon_type!=2){//关联课程
            $item_id =$param['item_id'];
            if($item_id){
                switch ($coupon_type){
                    case "1":
                        $item_name = db('Course')->where(['closed'=>0,'audit'=>1,'cid'=>$item_id])->value('title');
                        break;
                    case "3":
                        $item_name = db('UserCard')->where(['closed'=>0,'id'=>$item_id])->value('title');
                        break;
                    case "4":
                        $item_name = db('Package')->where(['closed'=>0,'id'=>$item_id])->value('title');
                        break;
                }
                $data['item_id']=$item_id;
                if(empty($item_name)){
                    $msg['code'] = 1;
                    $msg['msg'] = '关联的项目已下架或被删除';
                    return $msg;
                }
                $data['item_name']=$item_name;
            }else{
                $msg['code'] = 1;
                $msg['msg'] = '关联ID必须选择';
                return $msg;
            }
        }
        //折扣类型
        $discount_type =$param['discount_type'];
        $data['discount_type']=$discount_type;
        $discount = $param['discount'];//折扣
        if($discount_type==1){//打折
            if($discount==''){
                $msg['code'] = 1;
                $msg['msg'] = '折扣不能为空';
                return $msg;
            }
            if($discount<0||$discount>10){
                $msg['code'] = 1;
                $msg['msg'] = '折扣类型为打折，折扣不能大于10';
                return $msg;
            }
        }
        if($discount_type==2){//抵价
            if($discount==''){
                $msg['code'] = 1;
                $msg['msg'] = '抵价不能为空';
                return $msg;
            }
            if($discount<0){
                $msg['code'] = 1;
                $msg['msg'] = '折扣类型为抵价，抵价需大于0';
                return $msg;
            }
        }
        
        if($discount_type==0){
            $discount =0;
        }
        $data['discount']=$discount;
        return $data;
    }
    /**
     * 上下架
     */
    public function state(){
        $id = input('param.id');
        $status = db('Coupon_code')->where(['id'=>$id])->value('audit');
        if($status==0)
        {
            $flag = db('Coupon_code')->where(['id'=>$id])->setField(['audit'=>1]);
            return json(['status' => $flag['status'], 'data' => '', 'msg' => '上架']);
        }
        else
        {
            $flag = db('Coupon_code')->where(['id'=>$id])->setField(['audit'=>0]);
            return json(['status'  => $flag['status'], 'data' => '', 'msg' => '下架']);
        }
    }
    /**
     * 删除
     */
    public function delelte(){
        $id = input('param.id');
        $is_delete = db('Coupon_code')->where(['id'=>$id])->update(['closed'=>1]);
        if($is_delete){
            return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => '删除成功']);
        }
        else {
            return json(['status' => -200,'url'=>'reload', 'data' => '', 'msg' => '删除失败']);
        }
    }
    //获取唯一优惠码
    private function get_unique_code(){
        $unique_code  = $this->unique_code();
        $is_set_code = db('Coupon_code')->where(['coupon_code'=>$unique_code])->value('id');
        if($is_set_code==1){
            $this->get_unique_code();
        }else{
            return $unique_code;
        }
           
    }
    private function  unique_code(){
        $unique_code = substr(md5(uniqid(mt_rand(), true)), 0,6);
        return $unique_code;
    }
    //优惠码类型
    public function get_coupon_type(){
        $coupon_type = input('param.coupon_type');
        return $coupon_type;
    }
    
    //折扣类型
    public function get_discount_type(){
        $discount_type = input('param.discount_type');
        return $discount_type;
    }

    public function getTypeData(){
        $type = input('type');
        $course = new Course();
        $vip = new UserCard();
        $package = new Package();
        $list = [];
        $where['closed'] = 0;
        switch ($type){
            case "1":
                $list = $course->where(array('closed'=>'0','audit'=>'1'))->field('cid as id,title')->select();
                break;
            case "3":
                $list = $vip->where($where)->field('id,title')->select();
                break;
            case "4":
                $list = $package->where($where)->field('id,title')->select();
                break;
        }
        return successJson('查询成功',$list);
    }
    private function  log($list){
        $config_id = 4;
        if($list){
            $adminlog_detail = new AdminLog();
            $adminlog_detail->log($config_id, $list);
        }
    }
}