<?php
namespace app\index\controller;
use think\Controller;
use app\api\controller\Api;
use think\Db;

/**
 * Class 问答
 * @package app\api\controller
 */
class Ask extends Controller
{
    public $uid;

    public function _initialize()
    {
        //session('uid',null);
        $this->assign('uid',session('uid'));
    }

    /**
     *首页
     */
    public function index()
    {
        //登录   用户头像 用户昵称  我的提问（链接） 我的回答（链接）  我要提问 （跳转提问框页面）只显示已购买的课程
        //未登录  显示暂未登陆  我的提问（登陆）
        //显示所有课程 以及显示课程评论列表
        $cid = input('cid');
        $page = input('page',1);
        $size = input('size',5);
        $cname = '';
        //课程
//         $result = apiget('course/index');
//         $course = $result['data']['course'];
//        $course_list_data =  apiget('course/allindex',['audit'=>1]);
//        $course =  $course_list_data['data'];
        $course = lists('Course',['audit'=>1]);
        $cid_arr = [];
        if(!$cid){
            foreach($course as $v){
                $cid_arr[] = $v['cid'];
            }
        }
        if(!$cid && !empty($course)){
            $cid = $course[0]['cid'];
        }
        if($cid && !empty($course)){
            $find = find('Course',['cid'=>$cid]);
            $cid = $find['cid'];
            $cname = $find['title'];
        }
        $cid_arr[] = $cid;
        //问答
        $ask_where['cid'] = ['in',$cid_arr];
        $ask_where['page'] = $page;
        $ask_where['size'] = $size;
        if(session('uid')){
            $ask_where['uid'] = session('uid');
        }
        //问答列表
        $result = apiget('ask/index',$ask_where);
        $ask = $result['data']['ask'];
        //精华
        $ask_where['hot'] = 1;
        $hot_count = counts("Ask",['hot'=>1]);
        $hot_ask = lists('Ask',['hot'=>1]);
        //统计
        $ask_count = counts('Ask',['cid'=>$cid]);
        //是否购买
        $checkbuy =  checkbuy(['cid'=>$cid,'uid'=>session('uid')]);
        if($checkbuy == 1){
            $buy = 1;
        }else{
            $buy = 0;
        }
        $tmp_config = config('template');
        if($tmp_config['view_path'] == './template/single/pc/'){
            $course_category = $course;
        }else{
            $course_category = $this->coursCategory(1);
        }
        $canask = $this->canask();
        $showdialog = '1';
        //判断需要购买课程才能提问
        if(!$canask){
            $showdialog = '0';
        }else{
            $vip = isvip();
            if($vip > 0){
                $showdialog = '0';
            }else{
                $freecourse = find('Course',['price'=>'0','closed'=>'0','audit'=>'1']);
                if(session('mobile')){
                    $isorder = Db::name('order')
                        ->where("uid=:uid or mobile =:mobile", ['uid' => session('uid'), 'mobile' => session('mobile')])
                        ->where('closed','0')
                        ->where('pay_status',1)
                        ->find();
                }else{
                    $isorder = Db::name('order')
                        ->where("uid", session('uid'))
                        ->where('closed','0')
                        ->where('pay_status',1)
                        ->find();
                }
                if($freecourse or $isorder){
                    $showdialog = '0';
                }
            }
        }

        $this->assign('cid',$cid);
        $this->assign('cname',$cname);
        $this->assign('category',$course);
        $this->assign('ask',$ask);
        $this->assign('hot_ask',$hot_ask);
        $this->assign('ask_count',ceil($ask_count/$size));
        $this->assign('hot_count',$hot_count);
        $this->assign('page',$page);
        $this->assign('size',$size);
        $this->assign('token',session('token'));
        $this->assign('buy',$buy);
        $this->assign('showdialog',$showdialog);
        $this->assign('course_category',$course_category);
        return view('index');
    }

    /**
     * 问答详情
     */
    public function detail(){
        $id = input('id');
        $uid = session('uid');
        $page = input('page',1);
        $size = input('size',10);
        $where['id'] = $id;
        $where['page'] = $page;
        $where['size'] = $size;
        if($uid){
            $where['uid'] = $uid;
        }
        $ask = apiget('ask/detail',$where);
        if($ask['code'] == -1){
            $this->error($ask['message']);
        }
        $ask = $ask['data'];
        $count = counts('Answer',['aid'=>$id]);
        //是否购买
        $cid = $ask['question']['cid'];
        $checkbuy =  checkbuy(['cid'=>$cid,'uid'=>$uid]);
        if($checkbuy == 1){
            $buy = 1;
        }else{
            $buy = 0;
        }
        $course = find('Course',['cid'=>$cid]);
        if($course['price'] == 0){
            $buy = 1;
        }
        $this->assign('ask',$ask);
        $this->assign('count',ceil($count/$size));
        $this->assign('page',$page);
        $this->assign('size',$size);
        $this->assign('id',$id);
        $this->assign('buy',$buy);
        return view('detail');
    }
        
    /**
     * 课程分类
     */
    public function coursCategory($type = 0){
        $course = lists('Course',['audit'=>1]);
        if($type == 0){
            if(isvip() == 0 || isvip() == -1){
                $order = $this->myorder();
                $cid_arr = [];
                foreach($order as $o){
                    $cid_arr[] = $o['cid'];
                }
                foreach($course as $k=>$v){
                    if($v['price'] > 0 && !in_array($v['cid'],$cid_arr)){
                        if($this->canask()){
                            unset($course[$k]);
                        }
                        
                    }
                }
            }
        }

        $course_category = db::name('course_category')->where('pid',0)->where('closed',0)->select();
        if(!empty($course_category)){
            $course_category_chirden = db::name('course_category')->where('pid','>',0)->where('closed',0)->select();
            foreach($course_category as &$v){
                $v['chirden'] = [];
                $v['course'] = [];
                foreach($course as $c){
                    if($c['pid'] == $v['id']){
                        $v['course'][] = $c;
                    }
                }
                foreach($course_category_chirden as &$v2){
                    $v2['course'] = [];
                    foreach($course as $c){
                        if($c['pid'] == $v2['id']){
                            $v2['course'][] = $c;
                        }
                    }
                    if($v['id'] == $v2['pid']){
                        $v['chirden'][] = $v2;
                    }
                }
            }
        }
        unset($v);
        foreach($course_category as $k=>$v){
            if(empty($v['course'])){
                unset($course_category[$k]);
                continue;
            }
            foreach($v['chirden'] as $k2=>$v2){
                if(empty($v2['course'])){
                    unset($course_category[$k]['chirden'][$k2]);
                }
            }

        }
       // p($course_category);
        return $course_category;
    }
        /*提问权限*/
       protected  function canask(){
           $is_canask = show_switch('is_canask');
           if($is_canask==0){//1 用户可以针对所有课程提问和回复 0用户购买课程才能提问和回复
               return true;
           }else {
               return false;
           }
       }
    /**
     * 提问
     */
    public function putquestion(){
        $uid = session('uid');
        $is_canask = $this->canask();
        if(!$uid){
            $course_category = [];
        }else{
            $course = lists('Course',['audit'=>1]);
            $tmp_config = config('template');
            if($tmp_config['view_path'] == './template/single/pc/'){
                $order = $this->myorder();
                if(empty($order)){
                    $course_category = [];
                    if($is_canask==0){
                        $course_category =$course;
                    }
                }else{
                    if(isvip() == -1 || isvip() == 0){
                        $cid_arr = [];
                        foreach($order as $o){
                            $cid_arr[] = $o['cid'];
                        }
                        foreach($course as $k=>$v){
                            if($v['price'] > 0 && !in_array($v['cid'],$cid_arr)){
                                if($is_canask){
                                    unset($course[$k]);
                                }
                                
                            }
                        }
                    }
                    $course_category = $course;
                }
            }else{
                $course_category = $this->coursCategory();
            }
        }
        
        $this->assign('is_canask',$is_canask);
        $this->assign('course_category',$course_category);
        return view();
    }

    //我的订单
    public function myorder(){
        $uid = session('uid');
        if(!$uid){
            return [];
        }
        $prefix = config('database.prefix');
        $user = find('User',['uid'=>$uid]);
        $mobile = $user['mobile'];
        if($mobile && $user['mobile_bind'] == 1){
            $where =  " (o.uid = ".$uid." or  o.mobile = ".$mobile.") ";
        }else{
            $where = " o.uid = ".$uid;
        }
        $sql = "SELECT c.cid FROM ".$prefix."order as o LEFT JOIN  ".$prefix."course as  c  on c.cid = o.course_id".
            " WHERE".$where.
            " AND o.pay_status = '1' and o.closed=0 AND c.closed=0 and c.audit = 1";
        $order = db('order')->query($sql);
        $package_sql = "SELECT c.cid FROM ".$prefix."package_order as o LEFT JOIN ".$prefix."course as c on c.cid = o.course_id WHERE ".$where." AND o.closed=0 AND c.closed=0 and c.audit = 1";
        $package_order =db('order')->query($package_sql);
        $new_order= array_merge($order, $package_order);
        return $new_order;
    }

}
