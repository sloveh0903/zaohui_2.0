<?php
namespace app\wechat\controller;
use think\Controller;
use think\Db;
use app\admin\model\CourseCategory;
use app\api\controller\Api;

/**
 * Class Index 问答
 * @package app\api\controller
 */
class Ask extends  Controller
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
    /**
     *单分类 首页
     */
      public function index()
        {
            $uid = session('uid');
            //显示课程评论列表
            $cid = input('cid');
            $cname = '';
            $course_list_data =  apiget('course/allindex',['audit'=>1]);
            $course =  $course_list_data['data'];
            $turn_cid = $cid;
            
            if(!$cid && !empty($course)){
                $turn_cid = $course[0]['cid'];
                $cid =0;//显示全部评论
                $catname =  '课程答疑';//分类名称
            }
            else{
                $catname = db('course')->where('cid','=',$cid)->value('title');//分类名称
            }
            $this->assign('catname',$catname);
            if($cid && !empty($course)){
                $find = find('Course',['cid'=>$cid]);
                $cid = $find['cid'];
                $cname = $find['title'];
            }
            $this->assign('cid',$cid);
            $this->assign('turn_cid',$turn_cid);
            $this->assign('cname',$cname);
            $this->assign('category',$course);
            //已付费课程
            $canask = $this->canask();
            $this->assign('canask',$canask);
            if($canask){
                $pay_course_list_data =  apiget('course/allbuy',['uid'=>$uid]);
            }else{
                $pay_course_list_data =  apiget('course/allindex',['uid'=>$uid]);
                
            }
            $pay_course = $pay_course_list_data['data'];
            $this->assign('pay_category',$pay_course);
            return view('index');
        }
        
        /*提问权限*/
        protected  function canask(){
            //返回0 权限都有
            $is_vip = isvip();
            if(in_array($is_vip, array(1,2))){
                return false;
            }else{
                $is_canask = show_switch('is_canask');
                if($is_canask==0){
                    return true;
                }else {
                    return false;
                }
            }
            
        }
    /**
     *多分类 首页
     */
 
    public function mulitindex()
    {
        $uid = session('uid');
        $cid = input('cid',0);
        $msg = input('msg','');
        $this->assign('msg',$msg);//回复后提示文字
        $topcate_lists = array();
        $cname = '';
        if(!$cid){
            $cid =0;//显示全部评论
            $catname =  '课程答疑';//分类名称
        }else{
            $catname = db('course')->where(['cid'=>$cid,'audit'=>1])->value('title');//分类名称
        }
        $this->assign('cid',$cid);
        $this->assign('turn_cid',$cid);
        $this->assign('catname',$catname);
        //筛选
        /*$topcate_lists  = array();
        $top_course_cate_list = lists('CourseCategory',['pid'=>0,'closed'=>0],['orderby'=>'desc'],['id','cate_name','pid']);
        foreach ($top_course_cate_list as $key=>$top_cate_list){
            $topcate_lists[$key]['id'] = $top_cate_list['id'];
            $topcate_lists[$key]['cate_name'] = $top_cate_list['cate_name'];
            $topcate_lists[$key]['topcourse']= lists('Course',['audit'=>1,'pid'=>$top_cate_list['id'],'child_id'=>0,'closed'=>0],['orderby'=>'desc'],['cid','title','face','price']);
            $child_course_cate_list = lists('CourseCategory',['pid'=>$top_cate_list['id'],'closed'=>0],['orderby'=>'desc'],['id','cate_name','pid']);
            foreach ($child_course_cate_list as &$childcate){
                $childcate['courselist'] = lists('Course',['audit'=>1,'pid'=>$top_cate_list['id'],'child_id'=>$childcate['id'],'closed'=>0],['orderby'=>'desc'],['cid','title','face','price']);
            }
            $topcate_lists[$key]['childcate'] = $child_course_cate_list;
        }*/


        $cate = Db::name('courseCategory')->where(array('closed'=>'0'))->order(array('orderby'=>'asc'))->select();
        foreach ($cate as $c) {
            $ret_cate[$c['id']] = $c;

        }
        $course = Db::name('course')->field('cid,title,pid,child_id,title')->where(array('closed'=>'0','audit'=>'1'))->select();

        foreach ($cate as $k=>$v){
            $ret_cate[$k] = $v;
            foreach ($course as $item) {
                if($item['child_id'] == 0 and $v['pid'] == 0 and $v['id'] == $item['pid']){
                    $ret_cate[$k]['topcourse'][] = $item;
                }elseif($v['pid'] == $item['pid'] and $v['id'] == $item['child_id']){
                    $ret_cate[$k]['courselist'][] = $item;
                }
            }
        }
        $ret_child = $ret_father = $ret_c = [];
        foreach ($ret_cate as $value) {
            if($value['pid'] == '0'){
                $ret_father[] = $value;
            }else{
                $ret_child[] = $value;
            }
        }
        foreach ($ret_father as $kf=>$vf) {
            $ret_c[$kf] = $vf;
            foreach ($ret_child as $c) {
                if($vf['id'] == $c['pid'] && isset($c['courselist'])){
                    $ret_c[$kf]['childcate'][] = $c;
                }

            }
        }
        foreach ($ret_c as $k=>$item) {
            if(!(isset($item['topcourse']) or isset($item['childcate']))){
                unset($ret_c[$k]);
            }else{
                $topcate_lists[] = $item;
            }
        }

        
        $this->assign('topcate_lists',$topcate_lists);
        //已付费课程

        $pay_course_list_data =  apiget('course/allbuy',['uid'=>$uid]);
        $canask = $this->canask();
        $this->assign('canask',$canask);
        if($canask){
            $pay_course_list_data =  apiget('course/allbuy',['uid'=>$uid]);
        }else{
            $pay_course_list_data =  apiget('course/allindex',['uid'=>$uid]);

        }
        $pay_course = $pay_course_list_data['data'];
        foreach ($pay_course as $pcourse){
            $course_id = $pcourse['cid'];
            $pid = $pcourse['pid'];
            $child_id = $pcourse['child_id'];
            $topcatname = db('course_category')->where(['id'=>$pid])->value('cate_name');
            if($child_id){
                $pay_catelist[$pid]['toplist'] =array('id'=>$pid,'cate_name'=>$topcatname);//顶级分类
                $pay_catelist[$pid]['childlist'][$child_id]['id']=$child_id;
                $pay_catelist[$pid]['childlist'][$child_id]['cate_name']=db('course_category')->where(['id'=>$child_id])->value('cate_name');;
                $pay_catelist[$pid]['childlist'][$child_id]['courselist'][]=$pcourse; 
            }
            else{
                $pay_catelist[$pid]['toplist'] = array('id'=>$child_id,'cate_name'=>$topcatname);
                $pay_catelist[$pid]['courselist'][] = $pcourse;
            }
        }
        if(empty($pay_catelist)){$pay_catelist =array();}
        $this->assign('pay_catelist',$pay_catelist);
        //是否购买过
        $is_buy_bool = 0;
        if(!empty($pay_catelist)){
            $is_buy_bool = 1;
        }
        if(!$this->canask()){
            $is_buy_bool = 1;
        }
        $this->assign('is_buy_bool',$is_buy_bool);
        return view('mulitindex');
    }
    /**
     *回答
     */
    public function answer()
    {
        return view('answer');
    }
    /**
     * 多分类  回答
     */
    public function mulitanswer()
    {
        return view('mulitanswer');
    }
    /**
     *问答详情
     */
    public function detail()
    {
        $id = input('id'); //问题id
        $msg = input('msg','');
        $this->assign('msg',$msg);//回复后提示文字
        //问题详情
        $ask_where['id'] = $id;
        $ask = find('Ask',$ask_where);
        if(!$ask) {
            $this->error('问题已删除','/wechat');
        }
        $cid = $ask['cid'];
        //是否购买
        $uid = session('uid');
        $buy_where['uid'] = $uid;
        $buy_where['cid']=$cid;
        $is_canask = show_switch('is_canask');
        if($is_canask==0){//1 用户可以针对所有课程提问和回复 0用户购买课程才能提问和回复
            $is_pay = 0;
            $is_pay = checkbuy($buy_where);
            if(!$is_pay){
                //课程0元 可以提问回复
                $price = db('course')->where('cid',$cid)->value('price');
                if($price<=0){
                    $is_pay = 1;
                }
            }
        }else {
            $is_pay = 1;
        }
        $this->assign('is_pay',$is_pay);
        //问题课程
        $course =  find('Course',['cid'=>$cid]);
        if(!$course) {
            $this->error('该问题课程已下架');
        }
        $this->assign('course_arr',$course);
        //老师回答
        $answer_admin_list  = array();
        $answer_where['aid'] = $id;
        $answer_where['uid'] =0;
        $answer_where['closed'] =0;
        $answer_admin_list = db('answer')->where($answer_where)->order('id desc')->select();
        foreach ($answer_admin_list as $key=>$va){
            $p_nickname ='';
            if($va['puid'] >0){
                $p_nickname= db('user')->where('uid','=',$va['puid'])->value('nickname'); 
            }
           $answer_admin_list[$key]['p_nickname'] = $p_nickname;
        }
        $this->assign('answer_admin_list',$answer_admin_list);
        return view('detail');
    }

    /**
     *回复
     */
    public function reply()
    {
        return view('reply');
    }

    /**
     * 子回复 第三层
     */
    public function discuss(){
        $id = input('id');
        $answer = find('Answer',['id'=>$id]);
        if(!$answer){
            $this->error('问题不存在');
        }
        $answer_uid = $answer['uid'];
        $answer_user = find('User',['uid'=>$answer_uid]);
        if(!$answer_user && $answer_uid > 0){
            $this->error('回复人不存在');
        }
        if($answer_uid == 0){
            $answer['nickname'] = '老师';
            $answer['face'] = '/public/gzadmin/images/admin_img.png';
        }else{
            $answer['nickname'] = $answer_user['nickname'];
            $answer['face'] = $answer_user['face'];
        }

        //是否购买
        $aid = $answer['aid'];
        $ask = find('Ask',['id'=>$aid]);
        if(!$ask){
            $this->error('问题不存在');
        }
        //是否点赞
        $like = find('Like',['itemid'=>$id,'uid'=>session('uid')],['typeid '=>2]);
        $haslike = 0;
        if($like){
            $haslike = 1;
        }
        $cid = $ask['cid'];
        $uid = session('uid');
        $buy_where['uid'] = $uid;
        $buy_where['cid'] = $cid;
        $is_pay = checkbuy($buy_where);
        $is_pay = $is_pay?$is_pay:0;
        $this->assign('is_pay',$is_pay);
        $this->assign('answer',$answer);
        $this->assign('aid',$aid);
        $this->assign('id',$id);
        $this->assign('haslike',$haslike);
        return view();
    }

}
