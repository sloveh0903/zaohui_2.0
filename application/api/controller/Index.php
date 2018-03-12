<?php
namespace app\api\controller;
use think\Controller;
use think\Loader;
use think\Session;

/**
 * Class Index 首页
 * @package app\api\controller
 */
class Index extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->getIndex();
    }

    /**
     * 首页
     * @author王宣成
     * @return Json
     */
    public function getIndex()
    {
        $uid = input('uid');
        $keyword = trim(input('keyword'));
        //搜索
        if($keyword && !empty($keyword)){
            $course_where['title'] = ['like','%'.$keyword.'%'];
        }
        //分销
        $is_rebate = 0;
        if($uid){
            $user = find('User',['uid'=>$uid]);
            if($user){
                $is_rebate = $user['is_rebate'];
            }
        }
        $rebate_config = find('RebateConfig');
        $rebate_status = isset($rebate_config['status']) ? $rebate_config['status'] : 0;

        //课程列表
        $page = input('page',1);
        $size = input('size',10);
        $audit = input('audit',1);  //1上架  0下架  2全部
        if($audit == 1){
            $course_where['audit']  = 1;
        }
        if($audit == 0){
            $course_where['audit'] = 0;
        }

        $course_where['page'] = $page;
        $course_where['size'] = $size;
        $course_field = 'cid,uid,title,face,desc,banner,price,content,subscribe_num,virtual_amount';
        $course_order['orderby'] = 'desc';
        $course = lists('Course',$course_where,$course_order,$course_field);
        $uid_arr = $course_data = $ret_user = [];
        if(!empty($course)){
            foreach($course as $v){
                $uid_arr[] = $v['uid'];
            }
            $user_where['uid'] = ['in',$uid_arr];
            $user_field = 'uid,uname,face,info,realname,follow';
            $user = lists('User',$user_where,[],$user_field);
            foreach ($user as $list) {
                $ret_user[$list['uid']] = $list;
            }
            foreach ($course as $v) {
                $ret_course = [];
                $ret_course['cid'] = $v['cid'];
                $ret_course['title'] = $v['title'];
                $ret_course['face'] = $v['face'];
                $ret_course['desc'] = $v['desc'];
                $ret_course['banner'] = $v['banner'];
                $ret_course['price'] = $v['price'];
                $ret_course['subscribe_num'] = $v['subscribe_num'];
                $ret_course['follow'] = 0;
                $ret_course['realname'] = "";
                $ret_course['info'] = "";
                if (array_key_exists($v['uid'], $ret_user)) {
                    $ret_course['realname'] = $ret_user[$v['uid']]['realname'];
                    $ret_course['info'] = $ret_user[$v['uid']]['info'];
                    $ret_course['follow'] = $ret_user[$v['uid']]['follow'];
                }
                //学习课程人数
                $studywhere['course_id'] =  $v['cid'];
                $studywhere['pay_status'] =  1;
             	 //虚拟学员数量
                if ($v['virtual_amount'] == 0) {
                    $ret_course['study_count']  = db('order')->where($studywhere)->count('id') ;
                }else{
                    $ret_course['study_count']  = $v['virtual_amount'];
                }
                //是否开启分销
                if($rebate_config && $rebate_config['status'] != 1){
                    $is_rebate = 0;
                }

                if($rebate_config && $is_rebate == 1){
                    $ret_course['share_price'] = $ret_course['price']*($rebate_config['first']/100);
                    if($ret_course['share_price'] < 0.01){
                        $ret_course['share_price'] = 0;
                    }
                }else{
                    $ret_course['share_price'] = 0;
                }
                $ret_course['share_price'] = sprintf("%.2f", $ret_course['share_price']);
                $course_data[] = $ret_course;
            }
        }
        //网站配置信息
        $config =  find('Config',['id'=>1]);
        $configData['sitename'] = "";
        $configData['siteurl'] = "";
        $configData['logo'] = "";
        $configData['aboutus'] = "";
        $configData['officer_url'] = "";
        $configData['seo_keywords'] = "";
        $configData['seo_description'] = "";
        if($config){
            $configData['sitename'] = $config['sitename'];
            $configData['siteurl'] = $config['siteurl'];
            $configData['logo'] = $config['logo'];
            $configData['aboutus'] = $config['aboutus'];
            $configData['officer_url'] = $config['officer_url'];
            $configData['seo_keywords'] = $config['seo_keywords'];
            $configData['seo_description'] = $config['seo_description'];
        }

        $data['course'] = $course_data;
        $data['config'] = $configData;
        $data['is_rebate'] = $is_rebate;
        $ret_course['rebate_status'] = $rebate_status;
        successJson('操作完成',$data);
    }

  /*小程序首页自定义 */

    public function getCustomindex(){
        $return_arr = array();
        //网站配置信息
        $config =  find('Config',['id'=>1]);
        $configData['sitename'] = "";
        $configData['siteurl'] = "";
        $configData['logo'] = "";
        $configData['aboutus'] = "";
        $configData['officer_url'] = "";
        $configData['seo_keywords'] = "";
        $configData['seo_description'] = "";
        if($config){
            $configData['sitename'] = $config['sitename'];
            $configData['siteurl'] = $config['siteurl'];
            $configData['logo'] = $config['logo'];
            $configData['aboutus'] = $config['aboutus'];
            $configData['officer_url'] = $config['officer_url'];
            $configData['seo_keywords'] = $config['seo_keywords'];
            $configData['seo_description'] = $config['seo_description'];
        }
        $return_arr['config'] = $configData;
        //是否开启了小程序客服
        $return_arr['is_mini_customer']=show_switch('is_mini_customer');
        //自定义模板
        $course_study = get_course_studynum();//获取课程id  所学人数
        $lists = db('Custom_template')->where(['closed'=>0])->order('orderby asc')->select();
        
        if($lists){
            foreach ($lists as $key=>$value){
                $key =$value['orderby'];
                $type=$value['type'];
                $return_arr[$key]['type'] = $type;
                if(in_array($type, array(1,3,6))){
                    $content= json_decode($value['content'],true);
                }else{
                    $content= $value['content'];
                }
                if($type==1){
                    foreach($content as $mkey=>$mval){
                        if(isset($mval['type'])&&$mval['type']=='link'){
                            $content[$mkey]['link'] = '';
                        }
                    }
                }
                else if($type==3){
                    $content['title'] = $content['title'];
                    $content['show_more'] = $content['show_more'];
                    $course_id_arrstr = $content['course_id'];
                    if($content['sort']==3){
                        $order_str = 'orderby desc';
                    }else{
                        $order_str = 'cid desc';
                    }
                    $course_list = db('Course')->where(['cid'=>['in',$course_id_arrstr],'closed'=>0,'audit'=>1])->field('cid,title,face,banner,desc,price,virtual_amount')->order($order_str)->select();
                    foreach($course_list as $ckey=>$cval){////
                        $cid = $cval['cid'];
                         //是否设置虚拟人数
                       if ($cval['virtual_amount'] == 0) {
                                if(isset($course_study[$cid])){
                                $course_list[$ckey]['study_count']=$course_study[$cid];
                            }else{
                                $course_list[$ckey]['study_count'] =0;
                            }
                        }else{
                            $course_list[$ckey]['study_count'] = $cval['virtual_amount'];
                        }  
                    }
                    //最热课程  排序冒泡
                    $len = count($course_list);
                    if(isset($content['sort'])&&$content['sort']==2){
                        for($k=1;$k<$len;$k++)
                        {
                            for($j=0;$j<$len-$k;$j++){
                                if($course_list[$j]['study_count']<$course_list[$j+1]['study_count']){
                                    $temp =$course_list[$j+1];
                                    $course_list[$j+1] =$course_list[$j] ;
                                    $course_list[$j] = $temp;
                                }
                            }
                        }
                    }
                    $content['course_list'] =$course_list;
                }
                else if($type==6){//套餐
                    $package_id = $content['package_id'];
                    $packageList = array();
                    if($package_id){
                        $map['id'] = ['in',$package_id];
                        $map['closed'] = 0;
                        $map['audit'] = 1;
                        $lists = db('Package')->where($map)->order('orderby desc')->select();
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
                    }
                    if($packageList){
                        $return_arr[$key]['title'] = $content['title'];
                        $return_arr[$key]['show_more'] = $content['show_more'];
                        $return_arr[$key]['package_id'] =$content['package_id'];
                        $return_arr[$key]['package_list']=$packageList;
                    }
                    
                }
                $return_arr[$key]['content'] = $content;
            }
        }
        successJson('操作完成',$return_arr);
        
        
    }
    public function getregisterseession(){
        $data['integral'] = session('integral');
        $data['is_register'] = session('is_register');
        $data['msg'] = '新用户注册 +'.$data['integral'].'积分';
        successJson('操作完成',$data);
    }
    public function getunsetregisterseession(){
        session('integral',0);
        session('is_register',0);
        successJson('操作完成');
    }


}
