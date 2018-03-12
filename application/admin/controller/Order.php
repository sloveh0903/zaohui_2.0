<?php

namespace app\admin\controller;
use app\admin\model\Order as Od;
use app\admin\model\Package;
use app\admin\model\PackageOrder;
use app\admin\model\User;
use app\admin\model\UcardRecord;
use app\admin\model\UserCard as Ucard;
use think\Db;
use think\Log;

class Order extends Base
{
    public function _empty(){
        return $this->index();
    }

    public function index(){
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $tid = $so['tid'] = input('tid');
        $type = input('type');
        $key = input('key');
        $uid = input('uid');
        $source = input('source',0);
        $map = [];
        $map['closed'] = 0;
        $map['order_type'] = 'course';
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }
        if($type){
            $map[$type] = ['like',"%" . $key . "%"];
        }
        if($source){
            $map['source'] = $source;
        }
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }elseif($end_date){
            $map['create_time'] = ['<',strtotime($end_date)];
        }
        if($end_date && $start_date){
            $map['create_time'] = ['between',[strtotime($start_date),strtotime($end_date)]];
        }
        $user = new User();
        $Nowpage = input('get.p') ? input('get.p'):1;
        $order = new Od();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $map['pay_status'] = 1;
        if($uid){
            $users = find('User',['uid'=>$uid]);
            if($users){
                if(!empty($users['mobile'])){
                    $count = $order->where($map)->where("uid=:uid or mobile =:mobile", ['uid' => $uid, 'mobile' => $users['mobile']])->group('course_id')->count();//计算总页面
                    $lists = $order->where($map)->where("uid=:uid or mobile =:mobile", ['uid' => $uid, 'mobile' => $users['mobile']])->group('course_id')->order('id desc')->limit($start,$limits)->select();
                }else{
                    $map['uid'] = $uid;
                    $count = $order->where($map)->group('course_id')->count();//计算总页面
                    $lists = $order->where($map)->order('id desc')->group('course_id')->limit($start,$limits)->select();
                }
            }else{
                $count = $order->where($map)->count();//计算总页面
                $lists = $order->where($map)->order('id desc')->limit($start,$limits)->select();
            }
        }else{
            $count = $order->where($map)->count();//计算总页面
            $lists = $order->where($map)->order('id desc')->limit($start,$limits)->select();
        }
        $allpage = ceil($count / $limits);
        foreach ($lists as $k=>$v) {
            $tid = Db::name('course')->where('cid',$v['course_id'])->value('uid');
            $lists[$k]['nickname'] = Db::name('user')->where('uid',$v['uid'])->value('nickname');
            $lists[$k]['edit_url'] = \think\Url::build('edit_order','id='.$v['id']);
            $lists[$k]['mobile'] = ($v['mobile']) ? $v['mobile'] : "";
            $lists[$k]['expire_time'] = ($v['expire_time']) ? date('Y-m-d',$v['expire_time']) : "";
            $lists[$k]['createtime'] = date('Y-m-d',strtotime($v['create_time']));
            if($lists[$k]['nickname'] == null){
                $lists[$k]['nickname'] = '';
            }
            switch ($v['pay_type']){
                case "0":
                    $lists[$k]['pay_type'] = "";
                    break;
                case "1":
                    $lists[$k]['pay_type'] = "微信";
                    break;
                case "2":
                    $lists[$k]['pay_type'] = "支付宝";
                    break;
            }
            $couponcode_price = $v['old_price']-$v['pay_price'];
            $couponcode_text = '(优惠'.$couponcode_price.'元)';
            $lists[$k]['couponcode_text'] =$v['coupon_id']?$couponcode_text:'';
            $source_text='导入';
            switch ($v['source']){
                case 'import':
                    $source_text = '导入';
                    break;
                case 'pc':
                    $source_text = 'PC端';
                    break;
                case 'wechat':
                    $source_text = '公众号';
                    break;
                case 'miniwechat':
                    $source_text = '小程序';
                    break;
            }
            $lists[$k]['source_text'] =$source_text;

        }
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        if(request()->isAjax()){
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="订单列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        $course = Db::name('course')->where(array('closed'=>'0','audit'=>'1'))->order(array('cid'=>'desc'))->select();
        $this->assign('course', $course);
        $this->assign('uid', $uid);
        return $this->fetch();
    }

    public function add_order(){
        $course = Db::name('course')->where(array('closed'=>'0','audit'=>'1'))->order(array('cid'=>'desc'))->select();
        $this->assign('course', $course); //课程信息
        if(request()->isPost()){
            $param = input('post.');
            $param['source'] = 'import';
            $order = new Od();
            if($order->where(array('course_id'=>$param['course_id'],'mobile'=>$param['mobile'],'expire_time'=>['>',time()],'closed'=>'0','pay_status'=>'1'))->find()){
                return json(['status' => -1,'url'=>'/admin/order/index', 'data' => '', 'msg' => '此手机已购买此课程']);
            }else{
                $courseinfo = array();
                foreach ($course as $v) {
                    if($v['cid'] == $param['course_id']){
                        $courseinfo = $v;
                    }
                }
                if($courseinfo){
                    $param['order_sn'] = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                    $param['course_name'] = $courseinfo['title'];
                    $param['course_banner'] = $courseinfo['banner'];
                    $param['price'] = $courseinfo['price'];
                    $param['pay_price'] = $courseinfo['price'];
                    $param['pay_status'] = 1;
                    $param['is_import'] = 1;
                    $param['source'] = 'import';
                    $param['order_type'] = 'course';
                    $param['create_time'] = strtotime($param['create_time']);
                    $param['expire_time'] = strtotime($param['expire_time']);
                    $uid = Db::name('user')->where('mobile',$param['mobile'])->value('uid');
                    if($uid){
                        $param['uid'] = $uid;
                    }
                    $flag = $order->InsertData($param);
                }else{
                    return json(['status' => -1,'url'=>'/admin/order/index', 'data' => '', 'msg' => '添加失败']);
                }

            }

            return json(['status' => $flag['status'],'url'=>'/admin/order/index', 'data' => '', 'msg' => $flag['msg']]);
        }
        return $this->fetch();
    }

    public function edit_order(){
        $course = Db::name('course')->where(array('closed'=>'0','audit'=>'1'))->order(array('cid'=>'desc'))->select();
        $this->assign('course', $course); //课程信息
        if(request()->isPost()){
            $order = new Od();
            $param = input('post.');
            //var_dump($param);die();
            if(trim($param['mobile'])){
                if($order->where(array('id'=>['<>',$param['id']],'course_id'=>$param['course_id'],'mobile'=>trim($param['mobile']),'expire_time'=>['>',time()],'closed'=>'0','pay_status'=>'1'))->find()){
                    return json(['status' => -1,'url'=>'/admin/order/index', 'data' => '', 'msg' => '此手机已购买此课程']);
                }
                $uid = Db::name('user')->where('mobile',trim($param['mobile']))->value('uid');
                if($uid){
                    $param['uid'] = $uid;
                }
            }
            $param['create_time'] = strtotime($param['create_time']);
            $param['expire_time'] = strtotime($param['expire_time']);
            $where['id'] = $param['id'];
            $flag = $order->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'/admin/order/index', 'data' => '', 'msg' => $flag['msg']]);
        }
        return $this->fetch();
    }



    public function del_order()
    {
        $id = input('param.id');
        $order = new Od();
        $where['id'] = $id;
        $flag = $order->DeleteData($where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function delall_order(){
        $ids = input('param.checkbox');
        $order = new Od();
        $where['id'] = ['in',$ids];
        $flag = $order->DeleteData($where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function ucardorder(){
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $type = input('type');
        $key = input('key');
        $map = [];
        $map['closed'] = 0;
        $map['order_type'] = 'usercard';
        $source = input('source',0);
        if($source){
            $map['source'] = $source;
        }
        if($type){
            $map[$type] = ['like',"%" . $key . "%"];
        }
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }elseif($end_date){
            $map['create_time'] = ['<',strtotime($end_date)];
        }
        if($end_date && $start_date){
            $map['create_time'] = ['between',[strtotime($start_date),strtotime($end_date)]];
        }
        $user = new User();
        $Nowpage = input('get.p') ? input('get.p'):1;
        $ucardrecord = new Od();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $ucardrecord->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $map['pay_status'] = 1;
        $lists = $ucardrecord->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $lists[$k]['nickname'] = Db::name('user')->where('uid',$v['uid'])->value('nickname');
            $lists[$k]['edit_url'] = \think\Url::build('edit_order','id='.$v['id']);
            $lists[$k]['mobile'] = ($v['mobile']) ? $v['mobile'] : "";
            $lists[$k]['expire_time'] = ($v['expire_time']) ? date('Y-m-d',$v['expire_time']) : "";
            $lists[$k]['createtime'] = date('Y-m-d',strtotime($v['create_time']));
            if($lists[$k]['nickname'] == null){
                $lists[$k]['nickname'] = '';
            }
            switch ($v['pay_type']){
                case "0":
                    $lists[$k]['pay_type'] = "";
                    break;
                case "1":
                    $lists[$k]['pay_type'] = "微信";
                    break;
                case "2":
                    $lists[$k]['pay_type'] = "支付宝";
                    break;
            }
            $couponcode_price = $v['old_price']-$v['pay_price'];
            $couponcode_text = '(优惠'.$couponcode_price.'元)';
            $lists[$k]['couponcode_text'] =$couponcode_price>0?$couponcode_text:'';
            $source_text='导入';
            switch ($v['source']){
                case 'import':
                    $source_text = '导入';
                    break;
                case 'pc':
                    $source_text = 'PC端';
                    break;
                case 'wechat':
                    $source_text = '公众号';
                    break;
                case 'miniwechat':
                    $source_text = '小程序';
                    break;
            }
            $lists[$k]['source_text'] =$source_text;
            $lists[$k]['pay_status'] = ($v['pay_status']=='1') ? $lists[$k]['pay_type']."已付" : "未付";

        }
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        if(request()->isAjax()){
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="会员卡订单列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        $usercard = Db::name('UserCard')->where(array('closed'=>'0'))->order(array('mouth'=>'asc'))->select();
        $this->assign('usercard', $usercard);
        return $this->fetch();
    }


    public function add_usercard(){
        if(request()->isPost()){
            $param = input('post.');
            $ucardrecord = new Od();
            $Ucard = new Ucard();
            $cardinfo = $Ucard->where(array('id'=>$param['card_id']))->find();

            if($cardinfo){
                if($ucardrecord->where(array('mobile'=>$param['mobile'],'order_type'=>'usercard','uid'=>'0','closed'=>'0'))->find()){
                    return json(['status' => -200,'url'=>'', 'data' => '', 'msg' => '此手机已添加会员卡，并未绑定']);
                }
                $param['order_sn'] = date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                $param['price'] = $cardinfo['price'];
                $param['pay_price'] = $cardinfo['price'];
                $param['card_name'] = $cardinfo['title'];
                $param['pay_status'] = 1;
                $param['is_import'] = 1;
                $param['order_type'] = 'usercard';
                $param['source'] = 'import';
                $param['usercard_id'] = $param['card_id'];
                $param['create_time'] = strtotime($param['create_time']);
                $param['expire_time'] = strtotime("+".$cardinfo['mouth']." month",$param['create_time']);
                $userinfo = Db::name('user')->where('mobile',$param['mobile'])->field('uid,expire_time')->find();
                if($userinfo){
                    $param['uid'] = $userinfo['uid'];
                }
                $updateData['cardtype'] = 0;
                if($cardinfo['type'] == 'life'){
                    $updateData['cardtype'] = 2;
                }else{
                    $updateData['cardtype'] = 1;
                }
                if($userinfo['expire_time'] > time()){
                    //如果会员未到期
                    $updateData['expire_time'] = strtotime("+".$cardinfo['mouth']." month",$userinfo['expire_time']);
                }else{
                    $updateData['expire_time'] = $param['expire_time'];
                }
                unset($param['card_id']);
                //p($param);
                Db::name('user')->where('uid',$userinfo['uid'])->update($updateData);

                $flag = $ucardrecord->InsertData($param);
            }
            return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
        }
        return $this->fetch();
    }

    public function edit_ucardrecord(){
        if(request()->isPost()){
            $param = input('post.');
            $ucardrecord = new Od();
            $Ucard = new Ucard();
            $where['id'] = $param['id'];
            $order = $ucardrecord->where(array('id'=>$param['id']))->find();
            $cardinfo = $Ucard->where(array('id'=>$order['usercard_id']))->find();
            if(trim($param['mobile'])){
                if($ucardrecord->where(array('mobile'=>trim($param['mobile']),'id'=>['<>',$param['id']],'order_type'=>'usercard','uid'=>'0','closed'=>'0'))->find()){
                    return json(['status' => -200,'url'=>'', 'data' => '', 'msg' => '此手机已添加会员卡，并未绑定']);
                }
                $userinfo = Db::name('user')->where('mobile',trim($param['mobile']))->field('uid,expire_time')->find();
                if($userinfo){
                    $param['uid'] = $userinfo['uid'];
                    $updateData['cardtype'] = 0;
                    if($cardinfo['type'] == 'life'){
                        $updateData['cardtype'] = 2;
                    }else{
                        $updateData['cardtype'] = 1;
                    }
                    if($userinfo['expire_time'] > time()){
                        //如果会员未到期
                        $updateData['expire_time'] = strtotime("+".$cardinfo['mouth']." month",$userinfo['expire_time']);
                    }else{
                        $updateData['expire_time'] = strtotime("+".$cardinfo['mouth']." mouth");
                    }
                    Db::name('user')->where('uid',$userinfo['uid'])->update($updateData);
                }
            }
            $flag = $ucardrecord->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
        }
        return $this->fetch();
    }


    public function del_ucardrecord()
    {
        $id = input('param.id');
        $ucardrecord = new UcardRecord();
        $where['id'] = $id;
        $flag = $ucardrecord->DeleteData($where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function delall_ucardrecord(){
        $ids = input('param.checkbox');
        $ucardrecord = new UcardRecord();
        $where['id'] = ['in',$ids];
        $flag = $ucardrecord->DeleteData($where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    public function packageorder(){
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $type = input('type');
        $key = input('key');
        $map = [];
        $map['closed'] = 0;
        $map['order_type'] = 'package';
        if($type){
            $map[$type] = ['like',"%" . $key . "%"];
        }
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }elseif($end_date){
            $map['create_time'] = ['<',strtotime($end_date)];
        }
        if($end_date && $start_date){
            $map['create_time'] = ['between',[strtotime($start_date),strtotime($end_date)]];
        }
        $source = input('source',0);
        if($source){
            $map['source'] = $source;
        }
        $map['pay_status'] = 1;
        $user = new User();
        $Nowpage = input('get.p') ? input('get.p'):1;
        $packageOrder = new Od();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $packageOrder->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $packageOrder->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $lists[$k]['nickname'] = Db::name('user')->where('uid',$v['uid'])->value('nickname');
            $lists[$k]['edit_url'] = \think\Url::build('edit_order','id='.$v['id']);
            $lists[$k]['mobile'] = ($v['mobile']) ? $v['mobile'] : "";
            $lists[$k]['expire_time'] = ($v['expire_time']) ? date('Y-m-d',$v['expire_time']) : "";
            $lists[$k]['createtime'] = date('Y-m-d',strtotime($v['create_time']));
            if($lists[$k]['nickname'] == null){
                $lists[$k]['nickname'] = '';
            }
            switch ($v['pay_type']){
                case "0":
                    $lists[$k]['pay_type'] = "";
                    break;
                case "1":
                    $lists[$k]['pay_type'] = "微信";
                    break;
                case "2":
                    $lists[$k]['pay_type'] = "支付宝";
                    break;
            }
            $couponcode_price = $v['old_price']-$v['pay_price'];
            $couponcode_text = '(优惠'.$couponcode_price.'元)';
            $lists[$k]['couponcode_text'] =$couponcode_price>0?$couponcode_text:'';
            $source_text='导入';
            switch ($v['source']){
                case 'import':
                    $source_text = '导入';
                    break;
                case 'pc':
                    $source_text = 'PC端';
                    break;
                case 'wechat':
                    $source_text = '公众号';
                    break;
                case 'miniwechat':
                    $source_text = '小程序';
                    break;
            }
            $lists[$k]['source_text'] =$source_text;
            $lists[$k]['pay_status'] = ($v['pay_status']=='1') ? $lists[$k]['pay_type']."已付" : "未付";

        }
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        $Package = Db::name('Package')->where(array('closed'=>'0','audit'=>'1'))->order(array('orderby'=>'desc'))->select();
        $this->assign('package', $Package);
        if(request()->isAjax()){
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="套餐订单列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }


    public function add_package(){
        if(request()->isPost()){
            $param = input('post.');
            $order = new Od();
            $package = new Package();
            $packageorder = new PackageOrder();
            $packageinfo = $package->where(array('id'=>$param['package_id']))->find();
            if($packageinfo){
                if(strlen($param['mobile'])!=11||preg_match("/[^\d-., ]/",$param['mobile'])){
                    return json(['status' => -1, 'data' => '', 'msg' => '手机号码填写错误']);
                }
                if($order->where(array('mobile'=>$param['mobile'],'package_id'=>$param['package_id'],'expire_time'=>['>',time()],'order_type'=>'package','closed'=>'0'))->find()){
                    return json(['status' => -1,'url'=>'/admin/order/index', 'data' => '', 'msg' => '此手机购买的此套餐还在有效期内']);
                }
                $param['source'] = 'import';
                $param['order_sn'] = date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                $param['price'] = $packageinfo['price'];
                $param['pay_price'] = $packageinfo['price'];
                $param['package_name'] = $packageinfo['title'];
                $param['pay_status'] = 1;
                $param['is_import'] = 1;
                $param['source'] = 'import';
                $param['order_type'] = 'package';
                $param['create_time'] = strtotime($param['create_time']);
                $param['update_time'] = strtotime($param['create_time']);
                $param['expire_time'] = strtotime("+12 month",$param['create_time']);
                $uid = Db::name('user')->where('mobile',$param['mobile'])->value('uid');
                $cid_arr = json_decode($packageinfo['course_id']);
                if(!$uid){
                    $uid = 0;
                }
                $param['uid'] = $uid;
                $package_order = [];
                foreach ($cid_arr as $v) {
                    $data['order_sn'] = $param['order_sn'];
                    $data['package_id'] = $param['package_id'];
                    $data['course_id'] = $v;
                    $data['mobile'] = $param['mobile'];
                    $data['uid'] = $uid;
                    $data['is_import'] = 1;
                    $data['create_time'] = $param['create_time'];
                    $data['update_time'] = $param['create_time'];
                    $data['expire_time'] = $param['expire_time'];
                    $package_order[] = $data;
                }
                if($package_order){
                    $packageorder->insertAll($package_order);
                }
                $flag = $order->InsertData($param);
            }
            if($flag){
                return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
            }else{
                return json(['status' => -1,'url'=>'/admin/order/index', 'data' => '', 'msg' => '添加失败']);
            }
           
        }
        return $this->fetch();
    }

    public function edit_package(){
        if(request()->isPost()){
            $package_order = [];
            $param = input('post.');
            $param['create_time'] = strtotime($param['create_time']);
            $param['expire_time'] = strtotime($param['expire_time']);
            $order = new Od();
            $orderinfo = $order->where('id',$param['id'])->find();
            if($order->where(array('mobile'=>$param['mobile'],'id'=>['<>',$param['id']],'package_id'=>$orderinfo['package_id'],'expire_time'=>['>',time()],'order_type'=>'package','closed'=>'0'))->find()){
                return json(['status' => -1,'url'=>'/admin/order/index', 'data' => '', 'msg' => '此手机购买的此套餐还在有效期内']);
            }
            $package = new Package();
            $packageorder = new PackageOrder();
            $packageinfo = $package->where(array('id'=>$orderinfo['package_id']))->find();
            if(trim($param['mobile'])){
//                 if(strlen($param['mobile'])!=11||preg_match("/[^\d-., ]/",$param['mobile'])){
//                     return json(['status' => -1, 'data' => '', 'msg' => '手机号码填写错误']);
//                 }
                $uid = Db::name('user')->where('mobile',trim($param['mobile']))->value('uid');
                //$cid_arr = json_decode($packageinfo['course_id']);
                if($uid){
                    $param['uid'] = $uid;
                }else{
                    $uid = 0;
                }
                //更新 套餐订单表中 有效期
                $package_param['expire_time'] =  $param['expire_time'];
                $package_where['uid'] = $uid;
                $package_where['package_id'] = $orderinfo['package_id'];
                $package_where['mobile'] = trim($param['mobile']);
                $packageorder->UpdateData($package_param,$package_where);
            }
            $where['id'] = $param['id'];
            $flag = $order->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
        }
        return $this->fetch();
    }
}