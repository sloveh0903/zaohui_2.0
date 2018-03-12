<?php
namespace app\api\controller;
use think\Controller;

/**
 * 套餐
 * @package app\api\controller
 */
class Package extends Controller
{
    public function _initialize()
    {

    }
    /*套餐列表*/
    public function alllist(){
        $title = input('title','');
        if($title){
            $map['title'] = ['like',"%" . $title . "%"];
        }
        $map['closed'] = 0;
        $map['audit'] = 1;
        $lists = db('Package')->where($map)->field('id,title,price')->select();
        if(!$lists){
            $lists=array();
        }
        successJson('操作完成',$lists);
    }
    /*所有套餐  包含三个banner*/
    public  function all(){
        $packageList = array();
        $map['closed'] = 0;
        $map['audit'] = 1;
        $lists = db('Package')->where($map)->select();
        if($lists){
            foreach ($lists as $k=>$v) {
                $ret_package = [];
                $ret_package['banner'] = '';
                $ret_package['id'] = $v['id'];
                $ret_package['title'] = $v['title'];
                $ret_package['price'] = $v['price'];
                $ret_package['create_time'] = $v['create_time'];
                $ret_package['audit'] = $v['audit'];
                $course_id = json_decode($v['course_id']);
                $ret_banner = '';
                if($v['banner']){
                    $ret_banner = $v['banner'];
                }
                $banner_arr = get_package_banner($ret_banner,$course_id);
                $ret_package['banner'] = $banner_arr['banner'];
                $ret_package['banner_color'] = $banner_arr['banner_color'];
                $packageList[] = $ret_package;
            }
        }
        successJson('操作完成',$packageList);
    }
    /*套餐详情*/
    public function detail(){
        $course_studynum =get_course_studynum();
        $id = input('id',0);
        $uid = input('uid',0);
        $type  = input('type',0);
        $lists = array();
        $course_id_arr = array();
        $checkbuy=0;
        $checkvip=0;
        if($id&&$uid){
            $map['id'] = $id;
            $map['closed'] = 0;
            $map['audit'] = 1;
            $lists = db('Package')->where($map)->field('id,course_id,price')->find();
            if($type){
                $course_id_arr = db('PackageOrder')->where(['uid'=>$uid,'package_id'=>$id,'closed'=>0])->column('course_id');
            }else{
                $course_id_str = $lists['course_id'];
                $course_id_arr = json_decode($course_id_str);
            }
            if($course_id_arr){
                $ret_map['cid'] = ['in',$course_id_arr];
            }
            $ret_map['closed'] = 0;
            $ret_map['audit'] = 1;
            $course_arr = db('course')->where($ret_map)->field('cid,title,desc,price,banner,face,virtual_amount')->select();
            foreach ($course_arr as $key=>$arr){
                $cid = $arr['cid'];
                if($arr['virtual_amount']){
                    $studynum = $arr['virtual_amount'];
                }else{
                  $studynum = isset($course_studynum[$cid])?$course_studynum[$cid]:0;
                }
                $course_arr[$key]['studynum'] = $studynum;
            }
            //是否购买套餐
            $checkbuy = 0;
            $param['uid'] =$uid;
            $param['package_id'] =$id;
            $is_package_buy = checkpackage($param);
            if($is_package_buy==1){
                $checkbuy=1;
            }
            //是否开启vip
            $openvip = 0;
             $vip_count = db('UserCard')->where(['closed'=>0])->count();
             if($vip_count>0){
                 //是否vip
                 $is_vip = isvip($uid);//会员卡是否购买 0未购买 -1过期 1未过期 2 终身
                 if($is_vip==1||$is_vip==2){
                     $checkvip = 1;
                 }
                 $openvip = 1;
             }else{
                 $openvip = 0;
             }
             $lists['openvip'] = $openvip;
            $lists['checkvip'] = $checkvip;
            $lists['checkbuy'] = $checkbuy;
            $lists['course_arr'] = $course_arr;
            successJson('操作完成',$lists);
        }else{
            errorJson('参数错误');
        }
        
    }

}
