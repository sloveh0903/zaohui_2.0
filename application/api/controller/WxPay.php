<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\Log;
use app\api\controller\Integral;
/**
 * Class WxPay 微信支付
 * @package app\api\controller
 */
class WxPay extends Controller
{
    public function _initialize()
    {
        require_once EXTEND_PATH."org/wxpay/lib/WxPay.Config.php";
        require_once EXTEND_PATH."org/wxpay/lib/WxPay.Api.php";
        require_once EXTEND_PATH."org/wxpay/lib/WxPay.Notify.php";
        require_once EXTEND_PATH."org/wxpay/example/WxPay.NativePay.php";
    }
    public function _empty()
    {
        return $this->postNotify();
    }


    //临时回调
    public function postActivityNotify(){
//        $xml = file_get_contents('php://input');
//        Log::write($xml,'infopay',true);
        $config = find('Config');
        $key = $config['wx_public_appkey'];
        $appid = $config['wx_public_appid'];
        $mchid = $config['wx_public_mchid'];
        $secret = $config['wx_public_secret'];
        $WxPayConfig = new \WxPayConfig();
        $WxPayConfig::$APPID = $appid;
        $WxPayConfig::$MCHID = $mchid;
        $WxPayConfig::$KEY = $key;
        $WxPayConfig::$APPSECRET = $secret;
        $WxPayNotify = new \WxPayNotify;
        $Handle = $WxPayNotify->Handle();
        if($Handle['result_code'] == 'SUCCESS' && $Handle['return_code'] == 'SUCCESS'){
            $order_sn = $Handle['out_trade_no'];
            $attach = $Handle['attach'];
            parse_str($attach, $param);
            //Log::write($param,'ok',true);//todo
            $price = $Handle['total_fee']/100;//Log::write($price,'price',true);//todo
            $order = find('Order',['order_sn'=>$order_sn],'id,price,uid,usercard_id,package_id,course_name');
            $save = [
                'pay_type' => 1,
                'pay_status' => 1,
                'pay_price' => $price,
                'create_time'=>time(),
                'update_time'=>time(),
            ];
            if($order['price'] == $price){
                $expire_time = strtotime('+1 year');//有效时间 一年
                $save['expire_time'] = $expire_time;
                update('Order',['order_sn'=>$order_sn],$save);
                echo "SUCCESS";
            }

        }
    }


    /**
     * 回调通知
     * @author王宣成
     * @return Json
     */
    public function postNotify(){
//        $xml = file_get_contents('php://input');
//        Log::write($xml,'infopay',true);
        $config = find('Config');
        $key = $config['wx_public_appkey'];
        $appid = $config['wx_public_appid'];
        $mchid = $config['wx_public_mchid'];
        $secret = $config['wx_public_secret'];
        $WxPayConfig = new \WxPayConfig();
        $WxPayConfig::$APPID = $appid;
        $WxPayConfig::$MCHID = $mchid;
        $WxPayConfig::$KEY = $key;
        $WxPayConfig::$APPSECRET = $secret;
        $WxPayNotify = new \WxPayNotify;
        $Handle = $WxPayNotify->Handle();
        if($Handle['result_code'] == 'SUCCESS' && $Handle['return_code'] == 'SUCCESS'){
            $order_sn = $Handle['out_trade_no'];
            $attach = $Handle['attach'];
            parse_str($attach, $param);
            //Log::write($param,'ok',true);//todo
            $price = $Handle['total_fee']/100;//Log::write($price,'price',true);//todo
            $order = find('Order',['order_sn'=>$order_sn],'id,price,uid,usercard_id,package_id,course_name,integral,cash_deducted');
            $uid = $order['uid'];
            $user = find('User',['uid'=>$uid],'uid,class,expire_time,mobile');
            $order_id = $order['id'];
            if($order['price'] == $price){
                $save = [
                    'pay_type' => 1,
                    'pay_status' => 1,
                    'pay_price' => $price,
                    'create_time'=>time(),
                    'update_time'=>time(),
                ];
                if(isset($param['pay_channel']) && $param['pay_channel'] == 'course'){
                    $expire_time = strtotime('+1 year');//有效时间 一年
                    $save['expire_time'] = $expire_time;
                    update('Order',['order_sn'=>$order_sn],$save);


                    if($user['class'] == 0){
                        $count_where['class'] = ['>',0];
                        $count = counts('User',$count_where);
                        $class = ceil($count/50);
                        update('User',['uid'=>$uid],['class'=>$class]);
                    }
                    update('User',['uid'=>$uid],['is_rebate'=>1]);
                    addRebate($order_id);//分销金额
                }elseif(isset($param['pay_channel']) && $param['pay_channel'] == 'usercard'){
                    //会员卡购买回调
                    $card_id = $order['usercard_id'];
                    $userCard = find('UserCard',['id'=>$card_id]);
                    if($userCard['type'] == 'life'){
                        $cardtype = 2;
                    }else{
                        $cardtype = 1;
                    }
                    if($user['expire_time'] > time()){
                        //如果用户为过期，则在本身用户的过期时间基础上加上会员卡时间
                        $expire_time = $save['expire_time'] = strtotime("+".$userCard['mouth']." month",$user['expire_time']);
                    }else{
                        $expire_time = $save['expire_time'] = strtotime("+".$userCard['mouth']." month");
                    }
                    update('Order',['order_sn'=>$order_sn],$save);
                    update('User',['uid'=>$uid],['is_rebate'=>1,'cardtype'=>$cardtype,'expire_time'=>$expire_time]);
                }elseif(isset($param['pay_channel']) && $param['pay_channel'] == 'package'){
                    $package_id = $order['package_id'];
                    $package = find('Package',['id'=>$package_id]);
                    $save['expire_time'] = strtotime('+1 year');//有效时间 一年
                    //Log::write($save,'usercard',true);
                    $cid_arr = json_decode($package['course_id']);
                    $package_order = [];
                    foreach ($cid_arr as $v) {
                        $data = [];
                        $data['order_sn'] = $order_sn;
                        $data['package_id'] = $package_id;
                        $data['course_id'] = $v;
                        $data['mobile'] = $user['mobile'];
                        $data['uid'] = $uid;
                        $data['is_import'] = 0;
                        $data['create_time'] = time();
                        $data['update_time'] = time();
                        $data['expire_time'] = $save['expire_time'];
                        $package_order[] = $data;
                    }
                    if($package_order){
                        Db::name('PackageOrder')->insertAll($package_order);
                    }
                    update('Order',['order_sn'=>$order_sn],$save);
                    update('User',['uid'=>$uid],['is_rebate'=>1]);
                }
                //积分处理 todo
                    $integral_api = new Integral();
                    $integral_api->pay_after_intergral($uid,$order_sn,$save['pay_price'],$order['integral'],$order['cash_deducted']);
                
                echo "SUCCESS";
            }

        }
    }

    /**
     * 小程序回调通知
     * @author王宣成
     * @return Json
     */
    public function postNotify2(){
       // $xml = file_get_contents('php://input');
        // Log::write($xml,'infopay',true);
        $config = find('Config');
        $key = $config['wx_appkey'];
        $appid = $config['wx_appid'];
        $mchid = $config['wx_mchid'];
        $secret = $config['wx_secret'];
        $WxPayConfig = new \WxPayConfig();
        $WxPayConfig::$APPID = $appid;
        $WxPayConfig::$MCHID = $mchid;
        $WxPayConfig::$KEY = $key;
        $WxPayConfig::$APPSECRET = $secret;
        $WxPayNotify = new \WxPayNotify;
        $Handle = $WxPayNotify->Handle();
        if($Handle['result_code'] == 'SUCCESS' && $Handle['return_code'] == 'SUCCESS'){
            $order_sn = $Handle['out_trade_no'];
            $attach = $Handle['attach'];
            parse_str($attach, $param);
            $price = $Handle['total_fee']/100;
            //$order = find('Order',['order_sn'=>$order_sn],'id,price');
            $order = find('Order',['order_sn'=>$order_sn],'id,price,uid,usercard_id,package_id,course_name,integral,cash_deducted');
            $uid = $order['uid'];
            $user = find('User',['uid'=>$uid],'uid,class,expire_time,mobile');
            $order_id = $order['id'];
            if($order['price'] == $price){
                $save = [
                    'pay_type' => 1,
                    'pay_status' => 1,
                    'pay_price' => $price,
                    'create_time'=>time(),
                    'update_time'=>time(),
                ];
                if(isset($param['pay_channel']) && $param['pay_channel'] == 'course'){
                    $expire_time = strtotime('+1 year');//有效时间 一年
                    $save['expire_time'] = $expire_time;
                    update('Order',['order_sn'=>$order_sn],$save);

                    if($user['class'] == 0){
                        $count_where['class'] = ['>',0];
                        $count = counts('User',$count_where);
                        $class = ceil($count/50);
                        update('User',['uid'=>$uid],['class'=>$class]);
                    }
                    update('User',['uid'=>$uid],['is_rebate'=>1]);
                    addRebate($order_id);//分销金额
                }elseif(isset($param['pay_channel']) && $param['pay_channel'] == 'usercard'){
                    //会员卡购买回调
                    $card_id = $order['usercard_id'];
                    $userCard = find('UserCard',['id'=>$card_id]);
                    if($userCard['type'] == 'life'){
                        $cardtype = 2;
                    }else{
                        $cardtype = 1;
                    }
                    if($user['expire_time'] > time()){
                        //如果用户为过期，则在本身用户的过期时间基础上加上会员卡时间
                        $expire_time = $save['expire_time'] = strtotime("+".$userCard['mouth']." month",$user['expire_time']);
                    }else{
                        $expire_time = $save['expire_time'] = strtotime("+".$userCard['mouth']." month");
                    }
                    update('Order',['order_sn'=>$order_sn],$save);
                    update('User',['uid'=>$uid],['is_rebate'=>1,'cardtype'=>$cardtype,'expire_time'=>$expire_time]);
                }elseif(isset($param['pay_channel']) && $param['pay_channel'] == 'package'){
                    //套餐回调
                    $package_id = $order['package_id'];
                    update('User',['uid'=>$uid],['is_rebate'=>1]);
                    $package = find('Package',['id'=>$package_id]);
                    $save['expire_time'] = strtotime('+1 year');//有效时间 一年
                    $cid_arr = json_decode($package['course_id']);
                    $package_order = [];
                    foreach ($cid_arr as $v) {
                        $data = [];
                        $data['order_sn'] = $order_sn;
                        $data['package_id'] = $package_id;
                        $data['course_id'] = $v;
                        $data['mobile'] = $user['mobile'];
                        $data['uid'] = $uid;
                        $data['is_import'] = 1;
                        $data['create_time'] = time();
                        $data['update_time'] = time();
                        $data['expire_time'] = $save['expire_time'];
                        $package_order[] = $data;
                    }
                    if($package_order){
                        Db::name('PackageOrder')->insertAll($package_order);
                    }
                    update('Order',['order_sn'=>$order_sn],$save);
                }
                //积分处理 todo
                $integral_api = new Integral();
                $integral_api->pay_after_intergral($uid,$order_sn,$save['pay_price'],$order['integral'],$order['cash_deducted']);
                
                echo "SUCCESS";
            }
        }
    }


    /**
     * 统一下单
     * @author王宣成
     * @return Json
     */
    public function getUnifiedorder(){
        $pay_type = input('pay_type',1); //1 h5 2小程序
        if($pay_type == 1){
            $notify = 'http://'.$_SERVER['HTTP_HOST'].'/api/wxpay/notify';
        }else{
            $notify = 'https://'.$_SERVER['HTTP_HOST'].'/api/wxpay/notify2';
        }
        $openId = input('openid');
        $order_sn = input('order_sn');
        $order = find('Order',['order_sn'=>$order_sn]);
        if(!$order)  errorJson('订单不存在');
        $price = $order['price'];
        $trade_no = $order_sn;
        if($order['order_type'] == 'course'){
            $body = $order['course_name'];
        }elseif($order['order_type'] == 'usercard'){
            $body = $order['card_name'];
        }elseif($order['order_type'] == 'package'){
            $body = $order['package_name'];
        }
        $param['body'] = $body;
        $param['price'] = $price;
        $param['trade_no'] = $trade_no;
        $param['notify'] = $notify;
        $param['pay_type'] = $pay_type;
        $param['openid'] = $openId;
        $param['attach'] = 'pay_channel='.$order['order_type'];
        //var_dump($param);
        $result = unified($param);
        if(isset($result['timeStamp'])){
            successJson('操作成功',$result);
        }else{
            errorJson('操作失败',$result);
        }
    }


    /**
     * 临时活动统一下单
     * @author王宣成
     * @return Json
     */
    public function getActivityUnifiedorder(){
        $pay_type = input('pay_type',1); //1 h5 2小程序
        if($pay_type == 1){
            $notify = 'http://'.$_SERVER['HTTP_HOST'].'/api/wxpay/activityNotify';
        }else{
            $notify = 'https://'.$_SERVER['HTTP_HOST'].'/api/wxpay/notify2';
        }
        $openId = input('openid');
        $order_sn = input('order_sn');
        $order = find('Order',['order_sn'=>$order_sn]);
        if(!$order)  errorJson('订单不存在');
        $price = $order['price'];
        $trade_no = $order_sn;
        $body = "活动报名";
        $param['body'] = $body;
        $param['price'] = $price;
        $param['trade_no'] = $trade_no;
        $param['notify'] = $notify;
        $param['pay_type'] = $pay_type;
        $param['openid'] = $openId;
        $param['attach'] = 'pay_channel='.$order['order_type'];
        //积分处理 todo
        $integral_api = new Integral();
        $integral_api->pay_after_intergral($uid,$order_sn,$order['pay_price'],$order['integral'],$order['cash_deducted']);
        //var_dump($param);
        $result = unified($param);
        if(isset($result['timeStamp'])){
            successJson('操作成功',$result);
        }else{
            errorJson('操作失败',$result);
        }
    }




    /**
     * 扫码支付
     */
    public function getNavtive(){
        $order_sn = input('order_sn');
        $order = find('Order',['order_sn'=>$order_sn]);
        if($order['price'] == 0){
            update('Order',['order_sn'=>$order_sn],['pay_status'=>1]);
            successJson('操作成功',$order,2);
        }
        
        //避免订单重复
        $new_sn = date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        update('Order',['order_sn'=>$order_sn],['order_sn'=>$new_sn]);
        if($order['price'] == 0){
            $order = find('Order',['order_sn'=>$new_sn]);
            update('Order',['order_sn'=>$new_sn],['pay_status'=>1]);
            successJson('操作成功',$order,2);
        }
        $config = find('Config');
        $key = $config['wx_public_appkey'];
        $appid = $config['wx_public_appid'];
        $mchid = $config['wx_public_mchid'];
        $secret = $config['wx_public_secret'];
        $attach = 'pay_channel=course';
        $notify_url = 'http://'.$_SERVER['HTTP_HOST'].'/api/wxpay/notify';
        $WxPayConfig = new \WxPayConfig();
        $WxPayConfig::$APPID = $appid;
        $WxPayConfig::$MCHID = $mchid;
        $WxPayConfig::$KEY = $key;
        $WxPayConfig::$APPSECRET = $secret;
        $notify = new \NativePay();
        $input = new \WxPayUnifiedOrder();
        $input->SetBody($order['course_name']);
       // $input->SetAttach("test");
        $input->SetOut_trade_no($new_sn);
        $input->SetTotal_fee($order['price']*100);
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        //$input->SetGoods_tag($order['course_name']);
        $input->SetAttach($attach);
        $input->SetNotify_url($notify_url);
        $input->SetTrade_type("NATIVE");
        $input->SetProduct_id($order['course_id']);
        $result = $notify->GetPayUrl($input);
        if(!isset($result["code_url"])){
            errorJson($result['err_code_des']);
        }else{
            $url = $result["code_url"];
        }
        $data['order_sn'] = $new_sn;
        $data['url'] = $url;
        successJson('操作成功',$data);
    }
    public function getpcbackpay($order_sn){
        $order = find('Order',['order_sn'=>$order_sn]);
        //积分处理 todo
        $integral_api = new Integral();
        $integral_api->pay_after_intergral($order['uid'],$order_sn,$order['pay_price'],$order['integral'],$order['cash_deducted']);
        successJson('操作成功');
    }
}
