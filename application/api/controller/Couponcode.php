<?php
namespace app\api\controller;

use think\Controller;
use app\api\controller\Integral;
use think\Log;
/**
 * 优惠码
 * @package app\api\controller
 */
class Couponcode extends Controller
{
    public function _initialize()
    {

    }

    public function check_code()
    {
        if (input('uid')) {
            $uid = input('uid');
        } else {
            $uid = session('uid');
        }
        $cid = input('cid', 0);
        $package_id = input('package_id',0);
        $usercard_id = input('usercard_id',0);
        $is_switch = input('is_switch',0);
        $order_type = input('order_type', 'course');    //course是从课程过来的，usercard是从会员卡过来的 package是套餐
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
        $price = input('price', 0);
        if (!$uid) {
            errorJson('您未登录');
        }
        $minus = '';
        $tip = '';
        $return_order_price = $price;
        $coupon_code = input('coupon_code');
        if (!$coupon_code) {
            errorJson('优惠码未填写');
        } else {
            if ($price == 0) {
                errorJson('0元无法使用优惠码');
            }
            $coupon_code_data = db('Coupon_code')->where(['coupon_code' => trim($coupon_code), 'closed' => 0, 'audit' => 1])->find();
            if ($coupon_code_data) {
                $nowtime = strtotime(date('Y-m-d 00:00:00')); //当天开始时间戳
                $end_time = (int)$nowtime + 86400;   //当天结束时间戳
                if ($coupon_code_data['start_time'] > $nowtime || $coupon_code_data['end_time'] < $end_time) {
                    errorJson('优惠码未在使用期，无法使用！');
                }

                $user_order = db('order')->where(['pay_status' => 1, 'uid' => $uid, 'coupon_id' => $coupon_code_data['id'], 'closed' => 0])->value('id');
                if ($user_order) {
                    errorJson('优惠码已被使用，无法再次使用！');
                }
                if ($coupon_code_data['use_number'] > 0) {//等于0  无限制
                    $used_count = db('order')->where(['pay_status' => 1, 'coupon_id' => $coupon_code_data['id'], 'closed' => 0])->count();
                    if ($used_count >= $coupon_code_data['use_number']) {
                        errorJson('优惠码已超过数量限制！无法使用');
                    }
                }

                if ($coupon_code_data['coupon_type'] != 2) {
                    //如果不是通用课程

                    if ($coupon_code_data['item_id'] != $item_id || $coupon_code_data['coupon_type'] != $coupon_type) {
                        errorJson('此优惠码无法在此'.$title.'上使用');
                    }
                }

                if ($coupon_code_data['discount_type'] == 0) {//免费
                    $return_order_price = 0;
                    $tip = '免费折扣';
                    $minus = $price;
                    $coupon_minus = $price;
                }
                if ($coupon_code_data['discount_type'] == 2) {//抵价
                    $return_order_price = $price - $coupon_code_data['discount'];
                    if ($return_order_price < 0) {
                        errorJson('订单金额小于优惠金额无法使用');
                    }
                    if ($return_order_price < 0.01) {
                        $return_order_price = 0.01;
                    }
                    $tip = $coupon_code_data['discount'] . '元抵扣';
                    $minus =  round($coupon_code_data['discount'], 2);
                    $coupon_minus = round($coupon_code_data['discount'], 2);
                }
                if ($coupon_code_data['discount_type'] == 1) {//打折
                    $tip = $coupon_code_data['discount'] . '折优惠';
                    $mins_price = 0;
                    if ($coupon_code_data['discount'] > 0) {
                        $return_order_price = $price * $coupon_code_data['discount'] * 0.1;
                    }
                    if ($return_order_price < 0.01) {
                        $return_order_price = 0.01;
                    }
                    $minus =  round(($price - $return_order_price), 2);
                    $coupon_minus = round(($price - $return_order_price), 2);
                }
            } else {
                errorJson('优惠码错误');
            }
            $integral = new Integral();
            $checkData = $integral->check_intergral($uid,$price,$coupon_minus,$is_switch);
            $return_order_price =$return_order_price-$checkData['deducted_money'];
            $data['id'] = $coupon_code_data['id'];
            $data['coupon_code'] = $coupon_code_data['coupon_code'];
            $data['minus'] = $minus;
            $data['minus_name'] = '-￥'.$minus;
            $data['tip'] = $tip;
            $data['coupon_minus'] = $coupon_minus;
            $data['order_price'] = '￥' . round($return_order_price, 2);
            $data['order_price2'] = round($return_order_price, 2);
            $data['checkData'] = $checkData;
            successJson('ok', $data);

        }


    }

    public function delete_code()
    {
        $coupon_code = input('coupon_code');
        $order_sn = input('order_sn');
        $return_order_price = 0.00;
        if ($coupon_code && $order_sn) {
            $return_order_price = delete_code($coupon_code, $order_sn);
        }
        if ($return_order_price) {
            errorJson('优惠码无效');
        } else {
            successJson('ok', $return_order_price);
        }

    }


    public function integralCode(){
        $uid = input('uid');
        $price = input('price');
        $coupon_minus = input('coupon_minus',0);
        $status = input('status');
        $integral = new Integral();
        $checkData = $integral->check_intergral($uid,$price,$coupon_minus,$status);
        successJson('ok', $checkData);
    }


}
