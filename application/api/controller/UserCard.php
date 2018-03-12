<?php
namespace app\api\controller;
use think\Controller;
use think\Db;

/**
 * Class UserCard 会员卡信息
 * @package app\api\controller
 */
class UserCard extends Controller
{
    public function _initialize()
    {

    }

    //获取会员卡信息
    public function getindex(){
        $uid = input('uid');
        if(!$uid){
            errorJson('参数错误');
        }
        $fielde = 'uid,uname,nickname,money,face,sex,address,mobile,realname,class,point,money,is_rebate,expire_time,cardtype';
        $user = find('User',['uid'=>$uid],$fielde);
        $expire_time = '';
        $vip = isvip($uid);
        if($vip == 1){
            $expire_time = date('Y-m-d',$user['expire_time']);
        }
        $page = input('page',1);
        $size = input('size',10);
        $where['page'] = $page;
        $where['size'] = $size;
        $where['closed'] = '0';
        $orderby['mouth'] = 'asc';
        $list = lists('UserCard',$where,$orderby);
        $result['vip'] = $vip;
        $result['expire_time'] = $expire_time;
        $result['user'] = $user;
        $result['usercard'] = $list;
        successJson('操作成功',$result);
    }

}
