<?php
namespace app\wechat\controller;
use think\Controller;
use app\api\controller\Integral;


class Ordersubmit extends Controller
{
    public function _initialize()
    {
        //微信sdk
        require_once EXTEND_PATH."org/wxsdk/jssdk.php";
        $config = find('Config');
        $jssdk = new \JSSDK($config['wx_public_appid'], $config['wx_public_secret']);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage',$signPackage);
        //用户信息
        $this->assign('config',$config);
        $this->assign('userinfo', wechatLogin());
        
    }
   
    public function index(){
        $uid = session('uid');
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
        $back_url = '/wechat/index';//返回url
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
                    $course['price'] =sprintf("%.2f",$course['price']);
                }else{
                    $error_tip = '课程下架';
                }
            }
            $back_url ='/wechat/course/detail?cid='.$cid;
            
        }else if($order_type=='usercard'&&$usercard_id&&$error_tip==''){//usercard会员卡
            $is_vip = isvip($uid);//会员卡是否购买 0未购买 -1过期 1未过期 2 终身
            if($is_vip==2){
                //$checkbuy = 1;
                $error_tip = '您已经是终身会员卡用户，无需再购买';
            }else{
                $user_card = db('UserCard')->where(['id'=>$usercard_id,'closed'=>0])->order('mouth asc')->find();
                $old_price = $user_card['price'];
            }
            $back_url ='/wechat/course/card';
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
                        $old_price = $packageList['price'];
                        
                    }else {
                        $error_tip = '套餐已经下架';
                    }
                }
            }
            
            
            $back_url ='/wechat/bundlelist/detail?id='.$package_id;
        }
        else{
            $error_tip = '操作错误';
        }
        //支付后 跳转url
        $this->assign('back_url',$back_url);
        //var_dump($error_tip);
        //课程
        
        $this->assign('cid',$cid);
        $this->assign('course',$course);
        //$this->assign('checkbuy',$checkbuy);
        //会员卡
        $this->assign('usercard_id',$usercard_id);
        $this->assign('user_card',$user_card);
        //套餐
        $this->assign('package_id',$package_id);
        $this->assign('packageList',$packageList);
        //错误提醒
        $this->assign('order_type',$order_type);
        $this->assign('error_tip',$error_tip);
        $this->assign('uid',$uid);
        //原来价格
        $this->assign('old_price',sprintf("%.2f",$old_price));
        //积分情况
        $integral = new Integral();
        $integral_data = $integral->check_intergral($uid, $old_price);
        $this->assign('integral_data',$integral_data);
        return view('');
    }
}
