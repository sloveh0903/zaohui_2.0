<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use app\api\controller\Integral;

/**
 * Class Order 订单
 * @package app\api\controller
 */
class Order extends Controller
{
    public function _initialize()
    {
        
    }
    public function getIndex(){
        $uid = input('uid',0);
        $user = find('User',['uid'=>$uid]);
        $mobile = $user['mobile'];
        if(!$user)  errorJson('用户不存在');
        $page = input('page',1);
        $size = input('size',10);
        $pre = ($page-1)*$size;
        $where = ' o.order_type="course"';
        if(!empty($mobile)){
            $where .=  " and (o.uid = ".$uid." or  o.mobile = ".$mobile.") ";
        }else{
            $where .= " and o.uid = ".$uid; 
        }
        $prefix = config('database.prefix');
        $sql = "SELECT c.cid,c.title,c.face,c.desc,c.virtual_amount,o.pay_price,o.coupon_id,o.integral FROM ".$prefix."order as o LEFT JOIN  ".$prefix."course as  c  on c.cid = o.course_id".
            " WHERE".$where.
            " AND o.pay_status = '1' and o.closed=0 AND c.closed=0 ".
            "limit ".$pre.",".$size." ";
        $order= db('order')->query($sql);
        foreach($order as $key=>$o){
            $cid = $o['cid'];
            if($o['virtual_amount']){
                $study_count = $o['virtual_amount'];
            }else{
                $study_count = db('order')->where(['course_id'=>$cid,'closed'=>0,'pay_status'=>1])->count();
            }
            $study_count = $study_count?$study_count:0;
            $order[$key]['study_count'] = $study_count;
            if(!$order[$key]['coupon_id'] && !$order[$key]['integral']&&$order[$key]['pay_price']==0){
                $order[$key]['pay_price_html'] = '免费';
            }else{
                $order[$key]['pay_price_html'] = '￥'.$order[$key]['pay_price'];
            }
            
        }
        $data['order'] = $order;
        successJson('操作成功',$data);
    }
    /**
     * 订单套餐列表
     * @return Json
     */
    public function getPackagelist(){
        $uid = input('uid',0);
        $user = find('User',['uid'=>$uid]);
        $mobile = $user['mobile'];
        if(!$user)  errorJson('用户不存在');
        $page = input('page',1);
        $size = input('size',10);
        $pre = ($page-1)*$size;
        $where = ' o.order_type="package"';
        if(!empty($mobile)){
            $where .=  " and (o.uid = ".$uid." or  o.mobile = ".$mobile.") ";
        }else{
            $where .= " and o.uid = ".$uid;
        }
        $prefix = config('database.prefix');
        $sql = "SELECT p.id,p.title,p.course_id,p.banner,o.pay_price FROM ".$prefix."order as o LEFT JOIN  ".$prefix."package as  p  on p.id = o.package_id".
            " WHERE".$where.
            " AND o.pay_status = '1' and o.closed=0 AND p.closed=0 ".
            "limit ".$pre.",".$size." ";
        $order= db('order')->query($sql);
        foreach ($order as $k=>$v){
            $course_id = json_decode($v['course_id']);
            $ret_banner = '';
            if($v['banner']){
                $ret_banner = $v['banner'];
            }
            $banner_arr = get_package_banner($ret_banner,$course_id);
            $order[$k]['banner'] = $banner_arr['banner'];
            $order[$k]['banner_color'] = $banner_arr['banner_color'];
        }
        
        
        $data['order'] = $order;
        successJson('操作成功',$data);
    }
    /**
     * 订单详情
     * @author王宣成
     * @return Json
     */
    public function getDetail(){
        $id = input('id');
        $order_sn = input('order_sn');
        if($id)  $where['id'] = $id;
        if($order_sn)  $where['order_sn'] = $order_sn;
        $order =  find('Order',$where);
        if(!$order) errorJson('操作失败');
        successJson('操作成功',$order);
    }
    
    
    /**
     * 订单删除
     * @author王宣成
     * @return Json
     */
    public function postDel(){
        $id = input('id');
        $uid = input('uid');
        $result = delete('Order',['id'=>$id,'uid'=>$uid,'pay_status'=>'0']);
        if(!$result) errorJson('删除失败');
        successJson('操作完成');
    }
    
    /**
     * 提交订单
     * @author王宣成
     * @return Json
     */
    public function postAdd(){
        $cid = input('cid',0);
        $uid = input('uid',0);
        $is_switch = input('is_switch',0);
        $coupon_minus = 0;//优惠码折扣价格
        $order_type = input('order_type','course'); //订单类型  course课程     usercard会员卡  package套餐
        $package_id = input('package_id',0);
        $usercard_id = input('usercard_id',0);
        $source = input('source','');
        $user = find('User',['uid'=>$uid]);
        if(!$user)  errorJson('用户不存在');
        $mobile = $user['mobile'];
        $coupon_id = 0;
        $coupon_code_data = $data = [];
        //是否是免费码
        $is_free_coupon_code = 0;
        //优惠码
        switch ($order_type) {
            case "course":
                $coupon_type = 1;
                $title = "课程";
                $item_id = $cid;
                break;
            case "usercard":
                $coupon_type = 3;
                $title = "VIP";
                $item_id = $usercard_id;
                break;
            case "package":
                $coupon_type = 4;
                $title = "套餐";
                $item_id = $package_id;
                break;
        }
        $coupon_code= input('coupon_code',0);
        if($coupon_code){
            $coupon_code_data = db('Coupon_code')->where('coupon_code',$coupon_code)->find();
            if($coupon_code_data){
                $coupon_id =  $coupon_code_data['id'];
                $nowtime = strtotime(date('Y-m-d 00:00:00')); //当天开始时间戳
                $end_time = (int)$nowtime+86400;   //当天结束时间戳
                if($coupon_code_data['start_time'] > $nowtime){
                    errorJson('优惠码未在使用期，无法使用！');
                }
                if($coupon_code_data['end_time'] < $end_time){
                    errorJson('优惠码未在使用期，无法使用！');
                }
                $user_order = db('order')->where(['pay_status'=>1,'uid'=>$uid,'coupon_id'=>$coupon_id,'closed'=>0])->value('id');
                if($user_order){
                    errorJson('优惠码已被使用，无法再次使用！');
                }
                if($coupon_code_data['use_number']>0){//等于0  无限制
                    $used_count = db('order')->where(['pay_status'=>1,'coupon_id'=>$coupon_id,'closed'=>0])->count();
                    if($used_count>=$coupon_code_data['use_number']){
                        errorJson('优惠码已超过数量限制！无法使用');
                    }
                }
                if ($coupon_code_data['coupon_type'] != 2) {
                    //如果不是通用课程
                    if ($coupon_code_data['item_id'] != $item_id || $coupon_code_data['coupon_type'] != $coupon_type) {
                        errorJson('此优惠码无法在此'.$title.'上使用');
                    }
                }
            }
        }
        $order_sn = date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        if($order_type == "course" && $cid){
            //课程订单
            $course = find('Course',['cid'=>$cid]);
            if(!$course) errorJson('课程不存在');
            $checkbuy = checkbuy(['uid'=>$uid,'cid'=>$cid]);
            //是否重复购买
            if($checkbuy == 1) {
                //$result['order_sn'] = $order['order_sn'];  //TODO:暂时不知道返回订单号有什么作用
                $result['pay_status'] = 1;
                successJson('已购买',$result);
            }

            $course_name = $course['title'];
            $course_banner = $course['banner'];
            $course_price = $course['price'];

            if($course_price == 0){
                $pay_status = 1;
            }else{
                $pay_status = 0;
            }
            //优惠码
            $old_price = $course_price;
            $data = [
                'course_id' => $cid,
                'order_sn' => $order_sn,
                'price' => $course_price,
                'course_name' => $course_name,
                'course_banner' => $course_banner,
                'uid' => $uid,
                'mobile' => $mobile,
                'pay_status'=> $pay_status,
                'pay_type' => 0,
                'order_type' => $order_type,
                'create_time' => time(),
                'update_time' => time(),
                'expire_time' => strtotime("+1 year"),
                'source' => $source,
                'coupon_id'=>$coupon_id,
                'coupon_code'=>$coupon_code,
                'old_price'=>$old_price
            ];
            $rebateConfig = find('RebateConfig');
            if($rebateConfig && $rebateConfig['status'] == 1 && $data['price'] > 0) {
                if($user['is_rebate'] == 1 || $user['p1'] > 0){
                    $price = $data['price']*$rebateConfig['discount']/10;
                    $data['price'] = ($price >= 0.01) ? $price : 0.01;
                    $old_price = $data['old_price']*$rebateConfig['discount']/10;
                    $data['old_price']  = ($old_price >= 0.01) ? $old_price : 0.00;
                }
            }
        }elseif($order_type == "usercard" && $usercard_id){
            //会员卡订单
            $usercard = find('UserCard',['id'=>$usercard_id]);
            if(!$usercard) errorJson('会员卡不存在');
            $old_price = $usercard['price'];
            if($usercard['price'] == 0){
                $pay_status = 1;
            }else{
                $pay_status = 0;
            }
            $data = [
                'usercard_id' => $usercard_id,
                'order_sn' => $order_sn,
                'price' => $usercard['price'],
                'cardtype' => $usercard['type'],
                'card_name' => $usercard['title'],
                'uid' => $uid,
                'mobile' => $mobile,
                'pay_status'=> $pay_status,
                'pay_type' => 0,
                'order_type' => $order_type,
                'create_time' => time(),
                'update_time' => time(),
                'expire_time' => strtotime("+1 year"),
                'source' => $source,
                'coupon_id'=>$coupon_id,
                'coupon_code'=>$coupon_code,
                'old_price'=>$old_price
            ];
        }elseif($order_type == "package" && $package_id){
            //套餐订单
            $package = find('Package',['id'=>$package_id]);
            if(!$package) errorJson('套餐不存在');
            $old_price = $package['price'];
            if($package['price'] == 0){
                $pay_status = 1;
            }else{
                $pay_status = 0;
            }
            $data = [
                'package_id' => $package_id,
                'order_sn' => $order_sn,
                'price' => $package['price'],
                'package_name' => $package['title'],
                'uid' => $uid,
                'mobile' => $mobile,
                'pay_status'=> $pay_status,
                'pay_type' => 0,
                'order_type' => $order_type,
                'create_time' => time(),
                'update_time' => time(),
                'expire_time' => strtotime("+1 year"),
                'source' => $source,
                'coupon_id'=>$coupon_id,
                'coupon_code'=>$coupon_code,
                'old_price'=>$old_price
            ];

        }
        if(isset($coupon_code_data['discount']) && isset($data['price'])){
            if($coupon_code_data['discount_type']==2){//抵价
                $coupon_minus =  $coupon_code_data['discount'];
                $data['price'] =  $data['price']-$coupon_minus;
            }
            if($coupon_code_data['discount_type']==1){//打折
                if($coupon_code_data['discount']>0){
                    $data['price'] =  $data['price']*$coupon_code_data['discount']*0.1;
                    $coupon_minus = $old_price-($old_price*$coupon_code_data['discount']*0.1);
                }
            }
            if($coupon_code_data['discount_type']==0){//免费
                $data['price'] =0;
                $data['pay_status'] = 1;
                $pay_status = 1;
                //是免费码
                $is_free_coupon_code = 1;
                $coupon_minus = $old_price;
            }
        }
        // 启动事务
        Db::startTrans();
        try{
            $data['integral'] = 0;
            //判断积分抵现
            if($is_switch){
                $integral_api = new Integral();
                $integral_data  = $integral_api->check_intergral($uid,$old_price,$coupon_minus,1);
                $data['integral']  = $integral_data['deducted_integral'];
                $data['cash_deducted']  = $integral_data['deducted_money'];
                $data['price'] = $data['price']-$integral_data['deducted_money'];
            }
            //支付金额为0 回调执行
            if($data['price']==0){
                $pay_status = 1;
                //套餐
                if($order_type == "package" && $package_id){
                    $cid_arr = json_decode($package['course_id']);
                    $data['expire_time'] = strtotime('+1 year');//有效时间 一年
                    $package_order = [];
                    foreach ($cid_arr as $v) {
                        $data_pack = [];
                        $data_pack['order_sn'] = $order_sn;
                        $data_pack['package_id'] = $package_id;
                        $data_pack['course_id'] = $v;
                        $data_pack['mobile'] = $user['mobile'];
                        $data_pack['uid'] = $uid;
                        $data_pack['is_import'] = 0;
                        $data_pack['create_time'] = time();
                        $data_pack['update_time'] = time();
                        $data_pack['expire_time'] = $data['expire_time'];
                        $package_order[] = $data_pack;
                    }
                    if($package_order){
                        Db::name('PackageOrder')->insertAll($package_order);
                    }
                }else if($order_type == "usercard" && $usercard_id){//会员卡
                    if($usercard['type'] == 'life'){
                        $cardtype = 2;
                    }else{
                        $cardtype = 1;
                    }
                    if($user['expire_time'] > time()){
                        //如果用户为过期，则在本身用户的过期时间基础上加上会员卡时间
                        $expire_time = $data['expire_time'] = strtotime("+".$usercard['mouth']." month",$user['expire_time']);
                    }else{
                        $expire_time = $data['expire_time'] = strtotime("+".$usercard['mouth']." month");
                    }
                    update('User',['uid'=>$uid],['cardtype'=>$cardtype,'expire_time'=>$expire_time]);
                }  
            }
            if($user['class'] == 0){
                $count_where['class'] = ['>',0];
                $count = counts('User',$count_where);
                $class = ceil($count/50);
                Db::name('user')->where(['uid'=>$uid])->update(['class'=>$class]);
            }
            if($data['price']<0.01&&$data['price']>0&&$is_free_coupon_code==0){
                $data['price'] = 0.01;
            }
            
            //积分处理 todo
            if($is_switch&&$data['integral']>0&&$data['price']==0){
                $data['pay_status'] = 1;
                $integral_api = new Integral();
                $integral_api->pay_after_intergral($uid,$order_sn,$data['price'],$data['integral'],$data['cash_deducted']);
            }
            $data['price'] = sprintf('%.2f', $data['price']);
            Db::name('order')->insert($data);
            // 提交事务
            Db::commit();
            $result['order_sn'] = $order_sn;
            $result['pay_status'] = $pay_status;
            successJson('操作成功',$result);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            errorJson('订单提交失败');
        }
    }

    /**
     * 订单提交
     */
    public function getSubmit(){
        $uid = input('uid');
        $cid = input('cid');
        $user = find('User',['uid'=>$uid]);
        if(!$user){
            errorJson('用户不存在');
        }
        $course = find('Course',['cid'=>$cid]);
        if(!$course){
            errorJson('课程不存在');
        }
        $checkbuy = checkbuy(['cid'=>$cid,'uid'=>$uid]);
        $stuty_count = counts('Order',['course_id'=>$cid,'pay_status'=>1]);
        $course['stuty_count'] = $stuty_count;
        $course['checkbuy'] = $checkbuy;
        successJson('ok',$course);
    }


    public function getIncome(){
        $days = input('days',7);
        $date = $incomeInfo = $offlineInfo = [];
        $incomeSql = 'select t2.days,IFNULL(t3.amount,0) amount from   
(SELECT @cdate := date_add(@cdate,interval -1 day) days from (SELECT @cdate :=date_add(CURDATE(),interval +1 day) from gz_temp limit '.$days.') t1) t2  
LEFT JOIN (SELECT sum(pay_price) as amount,FROM_UNIXTIME(create_time,\'%Y-%m-%d\') as create_time FROM gz_order WHERE pay_status = 1 and closed = 0 and is_import = 0 and DATE_SUB(CURDATE(),INTERVAL '.$days.' DAY) <= FROM_UNIXTIME(create_time,\'%Y-%m-%d\') GROUP BY FROM_UNIXTIME( create_time,  \'%Y-%m-%d\' ) ) t3
ON t2.days = t3.create_time order by days asc';
        $incomeData =  Db::query($incomeSql);
        $income_list = array_column($incomeData, 'amount');
        $max_income = $income_list[array_search(max($income_list),$income_list)]; //线上最大值

        foreach ($incomeData as $income) {
            $date[] = $income['days'];
            $incomeInfo[] = $income['amount'];
        }

        $offlineSql = "select t2.days,IFNULL(t3.amount,0) amount from   
(SELECT @cdate := date_add(@cdate,interval -1 day) days from (SELECT @cdate :=date_add(CURDATE(),interval +1 day) from gz_temp limit ".$days.") t1) t2  
LEFT JOIN (SELECT sum(pay_price) as amount,FROM_UNIXTIME(create_time,'%Y-%m-%d') as create_time FROM gz_order WHERE pay_status = 1 and closed = 0 and is_import = 1 and DATE_SUB(CURDATE(),INTERVAL ".$days." DAY) <= FROM_UNIXTIME(create_time,'%Y-%m-%d') GROUP BY FROM_UNIXTIME( create_time,  '%Y-%m-%d' ) ) t3
ON t2.days = t3.create_time order by days asc";
        $offlineData =  Db::query($offlineSql);

        $offline_list = array_column($offlineData, 'amount');
        $max_offline = $offline_list[array_search(max($offline_list),$offline_list)];  //线下最大值

        foreach ($offlineData as $offline) {
            $offlineInfo[] = $offline['amount'];
        }
        $date = implode(',',$date);
        $incomeInfo = implode(',',$incomeInfo);
        $offlineInfo = implode(',',$offlineInfo);

        $max_day = ($max_income > $max_offline) ? $max_income : $max_offline;
        $data['max_day'] = ($max_day < 500) ? 500 : $max_day;
        $data['date'] = $date;
        $data['incomeInfo'] = $incomeInfo;
        $data['offlineInfo'] = $offlineInfo;
        successJson('操作成功',$data);
    }
    /**
     * 订单提交页面
     */
    public function getOrdersubmit(){
        $uid = input('uid');
        $error_tip='';
        $old_price = 0;
        $order_type = input('order_type','course'); //订单类型  course课程     usercard会员卡  package套餐
        $cid = input('cid',0);
        $course  = array();
        $checkbuy = 0;
        $package_id = input('package_id',0);
        $packageList = array();
        $usercard_id = input('usercard_id',0);
        $user_card = array();
        //$back_url = '/wechat/index';//返回url
        if(!$uid){
            $error_tip = '用户不存在';
        }
        if($order_type=='course'&&$cid&&$error_tip=='')////订单类型  course课程
        {
            //是否购买
            $pay = checkbuy(['uid'=>$uid,'cid'=>$cid]);
            if($pay == 1){
                //$checkbuy = 1;
                $error_tip = '您已经购买该课程，无需再购买';
            }else{
                $rebate_config = find('RebateConfig');
                $course = db('Course')->where(['cid'=>$cid,'audit'=>1,'closed'=>0])->find();
                if($course){
                    $course['rebate_status'] = isset($rebate_config['status']) ? $rebate_config['status'] : 0;
                    if($rebate_config['status'] == '1' && $course['price'] > 0){
                        $course['discount'] = $rebate_config['discount'];
                        $course['rebate_money'] = $course['price']*($rebate_config['first']/100);
                        if($course['rebate_money'] < 0.01){
                            $course['rebate_money'] = 0;
                        }
                        $course['rebate_money'] = sprintf("%.2f", $course['rebate_money']);
                        
                        if($uid){
                            $user_field = 'uid,uname,face,info,realname,is_rebate,p1';
                            $user = find('User',['uid'=>$uid],$user_field);
                            if($user['is_rebate'] == 1 || $user['p1'] > 0){
                                $price = $course['price']*$rebate_config['discount']/10;
                                $course['price'] = ($price >= 0.01) ? $price : 0.01;
                            }
                        }
                    }
                    $old_price = $course['price'];
                    
                }else{
                    $error_tip = '课程下架';
                }
            }
            //$back_url ='/wechat/course/detail?cid='.$cid;
            
        }else if($order_type=='usercard'&&$usercard_id&&$error_tip==''){//usercard会员卡
            $is_vip = isvip($uid);//会员卡是否购买 0未购买 -1过期 1未过期 2 终身
            if($is_vip==2){
                //$checkbuy = 1;
                $error_tip = '您已经是终身会员卡用户，无需再购买';
            }else{
                $user_card = db('UserCard')->where(['id'=>$usercard_id,'closed'=>0])->order('mouth asc')->find();
                $old_price = $user_card['price'];
            }
            //$back_url ='/wechat/course/card';
        }
        else if($order_type=='package'&&$package_id&&$error_tip==''){//package套餐
            $is_vip = isvip($uid);//会员卡是否购买 0未购买 -1过期 1未过期 2 终身
            if($is_vip==1||$is_vip==2){
                $error_tip = '您已经是vip会员，无需再购买';
            }
            else{
                $param['uid'] =$uid;
                $param['package_id'] =$package_id;
                $is_package_buy = checkpackage($param);
                if($is_package_buy==1){
                    //$checkbuy = 1;
                    $error_tip = '您已经购买该套餐，无需再购买';
                }else{
                    $map['closed'] = 0;
                    $map['audit'] = 1;
                    $map['id'] = $package_id;
                    $packageList = db('Package')->where($map)->find();
                    if($packageList){
                        $ret_banner = '';
                        $course_id = json_decode($packageList['course_id']);
                        if($packageList['banner']){
                            $ret_banner = $packageList['banner'];
                        }
                        $banner_arr = get_package_banner($ret_banner,$course_id);
                        $packageList['banner'] = $banner_arr['banner'];
                        $packageList['banner_color'] = $banner_arr['banner_color'];
                    }else {
                        $error_tip = '套餐已经下架';
                    }
                }
            }
            $old_price = $packageList['price'];
            //$back_url ='/wechat/bundlelist/detail?id='.$package_id;
        }
        else{
            $error_tip = '操作错误';
        }
        //支付后 跳转url
        //$this->assign('back_url',$back_url);
        
        $data['order_type']=$order_type;
        $data['uid']=$uid;
        //错误提醒
        $data['error_tip']=$error_tip;
        //原来价格
        $data['old_price']=sprintf("%.2f",$old_price);
        //课程
        $data['cid']=$cid;
        $data['course']=$course;
        //会员卡
        $data['usercard_id']=$usercard_id;
        $data['user_card']=$user_card;
        //套餐
        $data['package_id']=$package_id;
        $data['packageList']=$packageList;
        
        successJson('ok',$data);
    }


    public function getOrderSource(){
        $date = input('date');
        if($date){
            $lastdate = $date.'/01';
            $last = date("Ym", strtotime("-1 months", strtotime(".$lastdate.")));
            //当月数据
            $sourselist = ['wechat','import','miniwechat','pc'];
            $sourseSql = 'select count(id) as order_num,sum(pay_price) as total,source from gz_order where pay_status = 1 and FROM_UNIXTIME(create_time,\'%Y%m\')='.str_replace("/","",$date).' and closed = 0 group by (source)';
            $sourseData = Db::query($sourseSql);
            $sourse = array_column($sourseData, 'source');
            $thisMouthData = [];
            foreach ($sourselist as $v) {
                $ret_mouth = [];
                if(!in_array($v,$sourse)){
                    $ret_mouth['order_num'] = 0;
                    $ret_mouth['total'] = 0;
                    $ret_mouth['source'] = $v;
                    $thisMouthData[] = $ret_mouth;
                }
            }
            $sourseData = (array_merge($sourseData,$thisMouthData));
            $sourseData = array_combine(array_column($sourseData, 'source'),$sourseData);


            //上月数据
            $lastMouthSql = 'select count(id) as order_num,sum(pay_price) as total,source from gz_order where pay_status = 1 and closed = 0 and FROM_UNIXTIME(create_time,\'%Y%m\')='.$lastdate.'  group by (source)';
            $lastMouthData = Db::query($lastMouthSql);
            $lastsourse = array_column($lastMouthData, 'source');
            $lastMouth = [];
            foreach ($sourselist as $v) {
                $ret_mouth = [];
                if(!in_array($v,$lastsourse)){
                    $ret_mouth['order_num'] = 0;
                    $ret_mouth['total'] = 0;
                    $ret_mouth['source'] = $v;
                    $lastMouth[] = $ret_mouth;
                }
            }
            $lastMouthData = (array_merge($lastMouthData,$lastMouth));
            $lastMouthData = array_combine(array_column($lastMouthData, 'source'),$lastMouthData);
            $ret_data = [];
            foreach ($sourseData as $k=>$v){
                switch ($k){
                    case "wechat":
                        $v['title'] = '微信公众号';
                        break;
                    case "miniwechat":
                        $v['title'] = '小程序';
                        break;
                    case "pc":
                        $v['title'] = 'PC端';
                        break;
                    case "import":
                        $v['title'] = '线下导入';
                        break;
                }
                if(isset($lastMouthData[$k])){
                    $v['last_order_num'] = $lastMouthData[$k]['order_num'];
                    $v['last_total'] = $lastMouthData[$k]['total'];
                    if($lastMouthData[$k]['total'] > $v['total']){
                        $v['totalrate'] = 'down';
                    }else{
                        $v['totalrate'] = 'up';
                    }
                    if($lastMouthData[$k]['order_num'] > $v['order_num']){
                        $v['orderrate'] = 'down';
                    }else{
                        $v['orderrate'] = 'up';
                    }
                    if($lastMouthData[$k]['order_num'] == 0){
                        $v['orderpercent'] = 0;
                    }else{
                        $v['orderpercent'] = abs($v['order_num']-$lastMouthData[$k]['order_num'])/$lastMouthData[$k]['order_num']*100;
                    }
                    if($lastMouthData[$k]['total'] == 0){
                        $v['totalpercent'] = 0;
                    }else{
                        $v['totalpercent'] = abs($v['total']-$lastMouthData[$k]['total'])/$lastMouthData[$k]['total']*100;
                    }
                }
                $ret_data[] = $v;
            }
            successJson('ok',$ret_data);
        }else{
            errorJson('参数错误');
        }


    }

    public function getTrade(){
        $date = input('date');
        $daterange = input('daterange');

        switch ($date){
            case 'today':
                $tradeSql = 'select count(id) as order_num,sum(pay_price) as total,is_import from gz_order where pay_status = 1 and FROM_UNIXTIME(create_time,\'%Y-%m-%d\')=CURDATE() and closed = 0 group by (is_import)';
                break;
            case 'seven':
                $tradeSql = 'select count(id) as order_num,sum(pay_price) as total,is_import from gz_order where pay_status = 1 and DATE_SUB(CURDATE(),INTERVAL 7 DAY) <= FROM_UNIXTIME(create_time,\'%Y-%m-%d\') and closed = 0 group by (is_import)';
                break;
            case 'thirty':
                $tradeSql = 'select count(id) as order_num,sum(pay_price) as total,is_import from gz_order where pay_status = 1 and DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= FROM_UNIXTIME(create_time,\'%Y-%m-%d\') and closed = 0 group by (is_import)';
                break;
            case 'all':
                $tradeSql = 'select count(id) as order_num,sum(pay_price) as total,is_import from gz_order where pay_status = 1 and closed = 0 group by (is_import)';
                break;
        }
        if($daterange){
            $daterange = explode(' - ',$daterange);
            $starttime = strtotime($daterange[0].' 00:00:00');
            $endtime = strtotime($daterange[1].' 23:59:59');
            $tradeSql = 'select count(id) as order_num,sum(pay_price) as total,is_import from gz_order where pay_status = 1 and closed = 0 and create_time BETWEEN '.$starttime.' AND '.$endtime.'  group by (is_import)';
        }
        if(!$tradeSql){
            $tradeSql = 'select count(id) as order_num,sum(pay_price) as total,is_import from gz_order where pay_status = 1 and FROM_UNIXTIME(create_time,\'%Y-%m-%d\')=CURDATE() and closed = 0 group by (is_import)';
        }
        //交易数据

        $tradeData = Db::query($tradeSql);
        $tradeInfo = [
            'total'=>0,
            'onlineTotal'=>0,
            'offlineTotal'=>0,
            'onlineOrders'=>0,
            'offlineOrders'=>0
        ];

        if($tradeData){
            foreach ($tradeData as $v) {
                if($v['is_import'] == 1){
                    $tradeInfo['offlineTotal'] = $v['total'];
                    $tradeInfo['offlineOrders'] = $v['order_num'];
                    $tradeInfo['total'] += $v['total'];
                }else{
                    $tradeInfo['onlineTotal'] = $v['total'];
                    $tradeInfo['onlineOrders'] = $v['order_num'];
                    $tradeInfo['total'] += $v['total'];
                }
            }
        }
        successJson('ok',$tradeInfo);
    }
    
}
