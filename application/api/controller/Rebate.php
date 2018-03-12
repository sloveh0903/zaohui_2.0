<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\config;

/**
 * Class Rebate 分销
 * @package app\api\controller
 */
class Rebate extends Controller
{
    public function _initialize()
    {

    }

    /**
     * 近况
     */
    public function getIndex(){
        $uid = input('uid');
        $user = find("User",['uid'=>$uid]);
        $money = $user['money'];
        $nickname = $user['nickname'];
        $face = $user['face'];
        $rebate_where = [
            'uid'=>$uid,
            'page'=>1,
            'size'=>10,
        ];
        $rebate = lists('Rebate',$rebate_where,['id desc']);
        $rebate_data = [];
        if(count($rebate) > 0){
            $oid_arr = [];
            foreach($rebate as $v){
                $oid_arr[] = $v['order_id'];
            }
            $order_where['id'] = ['in',$oid_arr];
            $order = lists('Order',$order_where,array('id'=>'desc'),'id,course_id,course_name');
            foreach ($order as $list) {
                $ret_order[$list['id']] = $list;
            }
            foreach($rebate as &$v){
                $v['course_id'] = 0;
                if(isset($ret_order[$v['order_id']])){
                    $v['course_id'] = $ret_order[$v['order_id']]['course_id'];
                    $v['course_name'] = $ret_order[$v['order_id']]['course_name'];
                }
                //按订单id排序
                $rebate_data[$v['order_id']]['course_name'] =$v['course_name'];
                $rebate_data[$v['order_id']]['create_time'] =$v['create_time'];
                $rebate_data[$v['order_id']]['order_money'] =$v['order_money'];
                $rebate_data[$v['order_id']]['fxlevel'] =$v['fxlevel'];
                $rebate_data[$v['order_id']]['scale'] =$v['scale'];
                $rebate_data[$v['order_id']]['order_sn'] =$v['order_sn'];
                $rebate_data[$v['order_id']]['money'] =$v['money'];
                
            }
            unset($v);
        }
        sort($rebate_data);
        $result['face'] = $face;
        $result['nickname'] = $nickname;
        $result['money'] = $money;
        $result['rebate'] = $rebate_data;
        successJson('操作成功',$result);
    }

    /**
     * 数据
     */
    public function getData(){
        $uid = input('uid');
        $start_time = strtotime(date('Y-m-d 00:00:00', time()));
        $end_time = $start_time+86400;
        //今日直销入账
        $map['create_time'] = ['between',[$start_time,$end_time]];
        $map['uid'] = $uid;
        $map['fxlevel'] = 1;
        $my_day_money = sum('Rebate',$map,'money');
        //今日伙伴贡献
        $user = Db::name('user')
            ->where('p1|p2|p3','=',$uid)
            ->where('closed','0')
            ->select();
        $uid_arr = [];

        foreach($user as $v){
            $uid_arr[] = $v['uid'];
        }
        $map2['create_time'] = ['between',[$start_time,$end_time]];
        $map2['uid'] = ['in',$uid_arr];
        $partner_day_money = sum('Rebate',$map2,'money');
        //历史总收入
        $map3['uid'] = $uid;
        $total_money = sum('Rebate',$map3,'money');
        //下级伙伴贡献佣金
        $user = lists('User',['p1'=>$uid],[],'uid');
        $uid_arr = [];
        foreach($user as $v){
            $uid_arr[] = $v['uid'];
        }
        $map4['uid'] = ['in',$uid_arr];
        $p1_money = sum('Rebate',$map4,'money');
        //今日发展下级多少人
        $map5['p1'] = $uid;
        $map5['rebate_time'] = ['between',[$start_time,$end_time]];
        $day_p1_count = counts('User',$map5);
        //合计下级发展多少人
        $map6['p1'] = $uid;
        $p1_count = counts('User',$map6);
        //下二级伙伴贡献佣金
        $user = lists('User',['p2'=>$uid],[],'uid');
        $uid_arr = [];
        foreach($user as $v){
            $uid_arr[] = $v['uid'];
        }
        $map7['uid'] = ['in',$uid_arr];
        $p2_money = sum('Rebate',$map7,'money');
        //下二级发展多少人
        $map7['p2'] = $uid;
        $p2_count = counts('User',$map7);
        //下二级今日发展多少人
        $map7['p2'] = $uid;
        $map7['rebate_time'] = ['between',[$start_time,$end_time]];
        $day_p2_count = counts('User',$map7);
        //返回数据
        $result['my_day_money'] = $my_day_money;
        $result['partner_day_money'] = $partner_day_money;
        $result['total_money'] = $total_money;
        $result['p1_money'] = $p1_money;
        $result['day_p1_count'] = $day_p1_count;
        $result['p1_count'] = $p1_count;
        $result['p2_money'] = $p2_money;
        $result['p2_count'] = $p2_count;
        $result['day_p2_count'] = $day_p2_count;
        successJson('操作成功',$result);
    }

    //订单
    public function getOrder(){
        $uid = input('uid');
        $page = input('page',1);
        $size = input('size',10);
        $type = input('type',1); //1全部订单 2直销 3下一级 4 下二级
        $where['uid'] = $uid;
        $where['page'] = $page;
        $where['size'] = $size;
        if($type == 2){
            $where['fxlevel'] = 1;
        }
        if($type == 3){
            $where['fxlevel'] = 2;
        }
        if($type == 4){
            $where['fxlevel'] = 3;
        }
        $rebate = lists('Rebate',$where);
        if(count($rebate) > 0){
            $oid_arr = [];
            foreach($rebate as $v){
                $oid_arr[] = $v['order_id'];
            }
            $order_where['id'] = ['in',$oid_arr];
            $order = lists('Order',$order_where,array('id'=>'desc'),'id,course_id,course_name');
            foreach ($order as $list) {
                $ret_order[$list['id']] = $list;
            }
            foreach($rebate as &$v){
                $v['course_id'] = '';
                $v['course_name'] = '';
                if(isset($ret_order[$v['order_id']])){
                    $v['course_id'] = $ret_order[$v['order_id']]['course_id'];
                    $v['course_name'] = $ret_order[$v['order_id']]['course_name'];
                }
            }
            unset($v);
        }


        $result['rebate'] = $rebate;
        successJson('操作成功',$result);
    }

    /**
     * 提现列表
     */
    public function getWithdraw(){
        $uid = input('uid');
        $page = input('page',1);
        $size = input('size',10);
        $user = find('User',['uid'=>$uid]);
        $money = $user['money'];
        $where['page'] = $page;
        $where['size'] = $size;
        $where['uid'] = $uid;
        $withdraw = lists('Withdraw',$where);
        $data = [];
        foreach ($withdraw as $item) {
            $ret_data = [];
            $str_status = "";
            $ret_data['id'] = $item['id'];
            $ret_data['money'] = $item['money'];
            $ret_data['create_time'] = $item['create_time'];
            $ret_data['status'] = $item['status'];
            switch ($item['status']){
                case "0":
                    $str_status = "打款中";
                    $str_class = "applying";
                    break;
                case "1":
                    $str_status = "已打款";
                    $str_class = "playMoneyed";
                    break;
                case "2":
                    $str_status = "被驳回";
                    $str_class = "rejected";
                    break;
            }
            $ret_data['str_status'] = $str_status;
            $ret_data['str_class'] = $str_class;
            $ret_data['remark'] = $item['remark'];
            $data[] = $ret_data;
        }
        $result['withdraw'] = $data;
        $result['money'] = $money;
        successJson('操作成功',$result);
    }

    /**
     * 提现申请
     */
    public function postWithdraw(){
        $uid = input('uid');
        $money = input('money');
        $user = find('User',['uid'=>$uid]);

        if(!$user){
            errorJson('用户不存在');
        }
        if(!$user['alipay']){
            errorJson('未绑定支付宝不能提现');
        }
        $user_money = $user['money'];
        if($money > $user_money){
            errorJson('佣金不足');
        }
        if($money < 50){
            errorJson('至少提现50元');
        }
        $cash_no = date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);

        $data = [
            'uid'=>$uid,
            'cash_no' => $cash_no,
            'money' => $money,
            'status'=>0,
            'alipay'=>$user['alipay']
        ];
        $insert = insert('Withdraw',$data);
        setDec('User',['uid'=>$uid],'money',$money);
        if($insert){
            successJson('提交成功');
        }else{
            errorJson('提交失败');
        }
    }

    /**
     * 绑定支付宝
     */
    public function postBindAlipay(){
        $account = input('account');
        $uid = input('uid');
        $name = input('name');
        if(empty($account)){
            errorJson('请填写支付宝账号');
        }
        if(empty($name)){
            errorJson('请填写姓名');
        }
        $user = find('User',['uid'=>$uid]);
        if(!$user){
            errorJson('用户不存在');
        }
        $update = update('User',['uid'=>$uid],['alipay'=>$account,'alipay_bind'=>1,'realname'=>$name]);
        if($update){
            successJson('绑定成功');
        }else{
            errorJson('绑定失败');
        }
    }

    public function postRelieveAlipay(){
        $uid = input('uid');
        if(!$uid){
            errorJson('参数错误');
        }
        $user = find('User',['uid'=>$uid]);
        if(!$user){
            errorJson('用户不存在');
        }
        $update = update('User',['uid'=>$uid],['alipay'=>'','alipay_bind'=>0]);
        if($update){
            successJson('解绑成功');
        }else{
            errorJson('解绑失败');
        }
    }

    /**
     * 推广员
     */
    public function getPromoters(){
        $page = input('page',1);
        $size = input('size',10);
        $uid = input('uid');
        $user = find('User',['uid'=>$uid]);
        $promoter = [];
        if(!$user){
            errorJson('用户不存在');
        }
        $type = input('type',1); //1 下级  2下二级
        if($type == 1){
            $p1_user = lists('User',['p1'=>$uid,'page'=>$page,'size'=>$size,'closed'=>'0'],[],['uid,nickname,face,p1,p2,p3']);
            foreach($p1_user as $v){
                $ret_data = $map = [];
                $map['fxlevel'] = 1;
                $map['uid'] = $v['uid'];
                $ret_data['uid'] = $v['uid'];
                $ret_data['nickname'] = $v['nickname'];
                $ret_data['face'] = $v['face'];
                $ret_data['count_order'] = counts('Rebate',$map);
                $ret_data['sum_money'] = sum('Rebate',$map,'money');
                $ret_data['sum_order_money'] = sum('Rebate',$map,'order_money');
                $promoter[] = $ret_data;
            }
        }
        if($type == 2){
            $p1_user = lists('User',['p2'=>$uid,'page'=>$page,'size'=>$size,'closed'=>'0'],[],['uid,nickname,face,p1,p2,p3']);
            foreach($p1_user as $v){
                $ret_data = $map = [];
                $map['fxlevel'] = 1;
                $map['uid'] = $v['uid'];
                $ret_data['uid'] = $v['uid'];
                $ret_data['nickname'] = $v['nickname'];
                $ret_data['face'] = $v['face'];
                $ret_data['count_order'] = counts('Rebate',$map);
                $ret_data['sum_money'] = sum('Rebate',$map,'money');
                $ret_data['sum_order_money'] = sum('Rebate',$map,'order_money');
                $promoter[] = $ret_data;
            }
        }
        $result['rebate'] = $promoter;
        successJson('操作成功',$result);
    }

    /**
     * 分销课程
     */
    public function getShareCourse(){
        $page = input('page',1);
        $size = input('size',10);
        $uid = input('uid');
        $rebate_config = find('RebateConfig');
        $buy = 0;
        if($rebate_config){
            $user = find('User',['uid'=>$uid],'mobile,mobile_bind');
            $mobile = trim($user['mobile']);
            if($mobile && $user['mobile_bind'] == 1){
                $order = Db::name('order')
                    ->where("uid=:uid or mobile =:mobile", ['uid' => $uid, 'mobile' => $mobile])
                    ->where('closed','0')
                    ->where('pay_status',1)
                    ->find();
            }else{
                $order = Db::name('order')
                    ->where("uid", $uid)
                    ->where('closed','0')
                    ->where('pay_status',1)
                    ->find();
            }
            if($order){
               $buy = 1;
            }
            $course = lists('Course',['audit'=>1,'page'=>$page,'size'=>$size]);
            $uid_arr = [];
            foreach($course as $v){
                $uid_arr[] = $v['uid'];
            }
            $user_where['uid'] = ['in',$uid_arr];

            //头像姓名
            $user = lists('User',$user_where);
            $new_user = [];
            foreach($user as $u){
                $new_user[$u['uid']] = $u;
            }

            foreach($course as &$v){
                $v['share_price'] = $v['price']*($rebate_config['discount']/10);
                $v['rebate_price'] = $v['price']*($rebate_config['first']/100);
                if($v['rebate_price'] < 0.01){
                    $v['rebate_price'] = 0;
                }
                if($v['share_price'] < 0.01){
                    $v['share_price'] = 0;
                }
                $v['rebate_price'] = sprintf("%.2f", $v['rebate_price']);
                $v['share_price'] = sprintf("%.2f", $v['share_price']);
                $v['commission'] = $rebate_config['first'];
                $v['discount'] = $rebate_config['discount'];
                if(!isset($new_user[$v['uid']])){
                    $v['follow'] = 0;
                    $v['realname'] = '';
                }else{
                    $u = $new_user[$v['uid']];
                    $v['follow'] = $u['follow'];
                    $v['realname'] = $u['realname'];
                }
            }
            unset($v);
        }else{
           $course = [];
        }
        $result['course'] = $course;
        $result['buy'] = $buy;
        successJson('操作成功',$result);
    }
}
