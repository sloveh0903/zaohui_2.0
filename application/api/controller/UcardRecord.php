<?php
namespace app\api\controller;
use think\Controller;
use think\Db;

/**
 * Class UcardRecord 会员卡订单信息
 * @package app\api\controller
 */
class UcardRecord extends Controller
{
    public function _initialize()
    {

    }

    public function postAdd(){
        $card_id = input('card_id',0);
        $uid = input('uid',0);
        $source = input('source','');
        $expire_time = input('expire_time','');
        $user = find('User',['uid'=>$uid]);
        if(!$user)  errorJson('用户不存在');
        $mobile = $user['mobile'];
        $UserCard = find('UserCard',['id'=>$card_id]);
        if(!$UserCard) errorJson('会员卡不存在');
        $card_name = $UserCard['title'];
        $card_price = $UserCard['price'];
        $card_sn = date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        if($card_price == 0){
            $pay_status = 1;
        }else{
            $pay_status = 0;
        }
        $data = [
            'card_id' => $card_id,
            'card_sn' => $card_sn,
            'price' => $card_price,
            'card_name' => $card_name,
            'uid' => $uid,
            'mobile' => $mobile,
            'pay_status'=> $pay_status,
            'pay_type' => 1,
            'create_time' => time(),
            'update_time' => time(),
            'expire_time' => strtotime("+".$UserCard['mouth']." mouth"),
            'source' => $source
        ];
        // 启动事务
        Db::startTrans();
        try{
            if($UserCard['type'] == 'life'){
                $userdata['cardtype'] = 2;
                $userdata['expire_time'] = strtotime("2050-01-01");
            }else{
                $userdata['cardtype'] = 1;
                if($expire_time > time()){
                    $userdata['expire_time'] = strtotime("+".$UserCard['mouth']." month",$expire_time);
                }else{
                    $userdata['expire_time'] = strtotime("+".$UserCard['mouth']." month",time());
                }
            }
            Db::name('user')->where(['uid'=>$uid])->update($userdata);
            Db::name('UcardRecord')->insert($data);
            // 提交事务
            Db::commit();
            $result['card_sn'] = $card_sn;
            $result['pay_status'] = $pay_status;
            successJson('操作成功',$result);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            errorJson('提交失败');
        }
    }

    public function postCreate(){
        $card_id = input('card_id',0);
        $uid = input('uid',0);
        $source = input('source','');
        $user = find('User',['uid'=>$uid]);
        if(!$user)  errorJson('用户不存在');
        $mobile = $user['mobile'];
        $UserCard = find('UserCard',['id'=>$card_id]);
        if(!$UserCard) errorJson('会员卡不存在');
        switch ($UserCard['type'])
        {
            case 'mouth':
                $expire_time = strtotime('+1 month');
                break;
            case 'season':
                $expire_time = strtotime('+3 month');
                break;
            case 'year':
                $expire_time = strtotime('+1 year');
                break;
            default:
                $expire_time =  strtotime('+100 year');
        }
        $card_name = $UserCard['title'];
        $card_price = $UserCard['price'];
        $card_sn = date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        if($card_price == 0){
            $pay_status = 1;
        }else{
            $pay_status = 0;
        }
        $data = [
            'card_id' => $card_id,
            'card_sn' => $card_sn,
            'price' => $card_price,
            'card_name' => $card_name,
            'uid' => $uid,
            'mobile' => $mobile,
            'pay_status'=> $pay_status,
            'pay_type' => 1,
            'create_time' => time(),
            'update_time' => time(),
            'expire_time' => $expire_time,
           // 'source' => $source
        ];


        // 启动事务
        Db::startTrans();
        try{
//            if($UserCard['type'] == 'life'){
//                $userdata['cardtype'] = 2;
//                $userdata['expire_time'] =  strtotime('+100 year');;
//            }else{
//                $userdata['cardtype'] = 1;
//                if($expire_time > time()){
//                    $userdata['expire_time'] = strtotime("+".$UserCard['mouth']." month",$expire_time);
//                }else{
//                    $userdata['expire_time'] = strtotime("+".$UserCard['mouth']." month",time());
//                }
//            }
           // Db::name('user')->where(['uid'=>$uid])->update($userdata);
            Db::name('UcardRecord')->insert($data);
            // 提交事务
            Db::commit();
            $result['card_sn'] = $card_sn;
            $result['pay_status'] = $pay_status;
            successJson('操作成功',$result);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            errorJson('提交失败');
        }

    }

}
