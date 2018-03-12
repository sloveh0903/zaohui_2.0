<?php
namespace app\index\controller;
use app\api\controller\Integral;
use think\Controller;
use app\api\controller\Api;
use think\Db;


/**
 * Class Course 学习平台
 * @package app\api\controller
 */
class Mulitcourse extends Controller
{
    public $uid;

    public function _initialize()
    {

    }

    /**
     *课程
     */
    public function index()
    {
        //课程列表
        $topid = input('topid',0); //课程顶级分类id
        $pid = input('pid',0); //课程分类id
        $this->assign('pid',$pid);
        $this->assign('topid',$topid);
        //课程分类列表
        $top_course_cate_list = lists('CourseCategory',['pid'=>'0'],['orderby'=>'desc']);
        foreach ($top_course_cate_list as $key=>$top){
            $top_pid = $top['id'];
            $child_course_cate_list = lists('CourseCategory',['pid'=>$top_pid],['orderby'=>'desc']);
            $top_course_cate_list[$key]['child_list'] = $child_course_cate_list;
        }
        $this->assign('top_course_cate_list',$top_course_cate_list);
        return view('index');
    }
    /**
     * 课程详情
     */
    public function detail(){
        $uid = session('uid');
        $cid = input('cid',0);
        if(!$cid){
            $this->error('请选择课程');
        }
        $map['closed']  =0;
        $map['audit']  =1;
        $map['cid']  =$cid;
        //课程详情
        $course_list = db('course')->where($map)->order('orderby desc')->find();
        if(!$course_list){
            $this->error('课程已下架');
        }
        $score = $course_list['score'];
        $star = score($score);
        $course_list['star'] = $star;//评价星星
        $this->assign('course',$course_list);
        //当前栏目位置
         $curent_toppid= $course_list['pid'];
         $curent_topcatename = '';
         if($curent_toppid){
             $curent_topcatename = db('course_category')->where(['id'=>$curent_toppid])->value('cate_name');
         }
         $curent_pid = $course_list['child_id'];
         $curent_pname ='';
         if($curent_pid){
             $curent_pname = db('course_category')->where(['id'=>$curent_pid])->value('cate_name');
         }
        $this->assign('pid',$curent_pid);
        $this->assign('pname',$curent_pname);
        $this->assign('topid',$curent_toppid);
        $this->assign('topname',$curent_topcatename);
        //课程pc介绍
        $course_introduce = lists('CourseIntroduce',['cid'=>$cid],['orderby'=>'desc']);
        $this->assign('course_introduce',$course_introduce);
        $introdice_id = 0;
        if($course_introduce)
        {
            $introdice_id =  $course_introduce[0]['id'];
        }
        $this->assign('introdice_id',$introdice_id);
        //章节列表
        $video_category_where['cid'] = $cid;
        $video_category_field = 'id,cid,cate_name';
        $video_category_order['orderby'] = 'asc';
        $video_category = lists('VideoCategory',$video_category_where,$video_category_order,$video_category_field);
        $chaterid = 0;
        if($video_category)
        {
            $chaterid =  $video_category[0]['id'];
        }
        $this->assign('chaterid',$chaterid);
        //评价列表
        $comment = apiget('comment/index',['cid'=>$cid,'page'=>1,'size'=>3]);
        $comment = $comment['data']['comment'];
        //学员列表
        $study_list = db::name('Order')->where(array('course_id'=>$cid,'pay_status'=>1,'closed'=>'0'))->field(array('uid'))->group('uid')->select();
        $study_count = count($study_list);
        $uid_arr = [];
        foreach($study_list as $k => $v){
            if($k <= 7){
                $uid_arr[] = $v['uid'];
            }
        }
        $user_where['uid'] = ['in',array_unique($uid_arr)];
        $user_field = 'uid,uname,face';
        $student_user = lists('User',$user_where,[],$user_field);
        $this->assign('video_category',$video_category);
        $this->assign('comment',$comment);
        $this->assign('study_count',$study_count);
        $this->assign('student_user',$student_user);
        $buy = false;//是否购买
        if($uid){
            $checkbuy = checkbuy(['uid'=>$uid,'cid'=>$cid]);
            if($checkbuy == 1){
                $buy = true;
            }
        }
        $this->assign('buy',$buy);
        //是否vip会员
        $vip = isvip($uid);
        $this->assign('vip',$vip);
        //是否评论
        $iscomment = 0;
        if($uid){
            $iscomment = Db::name('Comment')->where(array('uid'=>$uid,'cid'=>$cid,'closed'=>0))->value('cid');
        }
        $this->assign('iscomment',$iscomment);
        //第一 是否开启题库显示功能  第二课程下 是否有题库  第三只有一个
        $is_testitemshop = show_switch('is_testitemshop');
        $testitem_qrcode= '';
        if($is_testitemshop==1){
            $bank_map['closed'] = 0;
            $bank_map['audit'] = 1;
            $bank_map['course_id']=['=',$cid];
            $lists = db('testitem_bank')->where($bank_map)->order('course_id asc')->select();
            $testitem_count = count($lists);
            if($testitem_count>0){
                $bank_id  = $lists[0]['id'];
                $testitem_url=host().'/wechat/testitembank/course_more?&cid='.$cid;
                if($testitem_count==1){$testitem_url=host().'/wechat/testitembank/do_testitem?&bank_id='.$bank_id;}
                $testitem_qrcode = 'http://tool.oschina.net/action/qrcode/generate?data='.urlencode($testitem_url).'&output=image%2Fjpeg&error=L&type=0&margin=8';
            }
        }
        $this->assign('is_testitemshop',$is_testitemshop);
        $this->assign('testitem_qrcode',$testitem_qrcode);
        //课程公众号二维码
        $url = host().'/wechat/course/detail?&cid='.$cid;
        $qrcode = 'http://tool.oschina.net/action/qrcode/generate?data='.urlencode($url).'&output=image%2Fjpeg&error=L&type=0&margin=8';
        $this->assign('qrcode',$qrcode);
        //课程呢小程序二维码
        $minqrcode = $course_list['wxmincode']?$course_list['wxmincode']:'/public/gzadmin/images/kong-miniprom.png';
        $this->assign('minqrcode',$minqrcode);
        $this->assign('uid',$uid);
        $this->assign('cid',$cid);
        return view('detail');
    }



    public function payment(){
        $cid = input('cid');
        $uid = session('uid');
        if(!$uid){
            $url = url('/');
            return alert('请先登录',$url,2,2);
        }
        $course = find('Course',['cid'=>$cid],'cid,title,price,banner,audit');
        if(!$course){
            return alert('课程不存在','',2,2);
        }
        if($course['audit'] == 0){
            return alert('该课程已下架','',2,2);
        }
        $buy = checkbuy(['cid'=>$cid,'uid'=>$uid]);
        if($buy == 1){
            return alert('该课程已购买过','',2,2);
        }
        $rebate_config = find('RebateConfig');
        $course['rebate_status'] = isset($rebate_config['status']) ? $rebate_config['status'] : 0;
        if($rebate_config['status'] == '1' && $course['price'] > 0){
            $course['discount'] = $rebate_config['discount'];
            if($uid){
                $user_field = 'uid,uname,face,info,realname,is_rebate,p1';
                $user = find('User',['uid'=>$uid],$user_field);
                if($user['is_rebate'] == 1 || $user['p1'] > 0){
                    $price = round($course['price']*$rebate_config['discount']/10,2);
                    $course['price'] = ($price >= 0.01) ? $price : 0.01;
                }
            }
            $course['rebate_money'] = $course['price']*($rebate_config['first']/100);
            if($course['rebate_money'] < 0.01){
                $course['rebate_money'] = 0;
            }
            $course['rebate_money'] = sprintf("%.2f", $course['rebate_money']);
        }
        /*$order_sn = date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $order['order_sn'] = $order_sn;
        $order['course_name'] = $course['title'];
        $order['banner'] = $course['banner'];
        $order['price'] = $course['price'];*/

        $integral = new Integral();
        $checkData = $integral->check_intergral($uid,$course['price'],0,0);
        $this->assign('checkData',$checkData);
        $this->assign('course',$course);
        $this->assign('cid',$cid);
        $this->assign('uid',$uid);
        return view('payment');
    }

    /**
     * 视频页面
     */
    public function video(){
        $id = input('id');
        $uid = session('uid');
        if(!$uid){
            $url = url('/');
            return alert('请先登录',$url,2,2);
        }
        $video = find('Video',['id'=>$id,'audit'=>'1']);
        if(!$video){
            return alert('视频不存在','',2,2);
        }
        $cid = $video['cid'];
        $map['closed']  =0;
        $map['audit']  =1;
        $map['cid']  =$cid;
        //课程详情
        $course_price = db('course')->where($map)->order('orderby desc')->value('price');
        if($course_price>0){
            $checkbuy = checkbuy(['uid'=>$uid,'cid'=>$cid]);
            if($checkbuy != 1){
                return alert('请购买课程','',2,2);
            }
        }
        //课程密码
        $course = find('Course',['cid'=>$cid]);
        $course_password = find('CoursePassword',['uid'=>$uid,'cid'=>$cid,'password'=>$course['password']]);
        if(!$course_password && !empty($course['password'])){
            if($course['price'] == 0){
                return alert('视频需要密码观看','',2,2);
            }
        }
        //获取课程详情
        $result =  apiget('course/detail',['cid'=>$cid]);
        if($result['code'] == -1){
            $this->error($result['message']);
        }
        $course = $result['data'];
        //获取章节
        $result = apiget('course/chapter',['cid'=>$cid]);
        if($result['code'] == -1){
            return alert($result['message'],'',2,2);
        }
        $chapter = $result['data']['video'];
        $this->assign('chapter',$chapter);
        $this->assign('course',$course);
        $this->assign('video',$video);
        $this->assign('uid',$uid);
        $this->assign('cid',$cid);
        $this->assign('tid',$video['tid']);
        $this->assign('vid',$video['vid']);
        $this->assign('id',$id);
        $this->assign('vpath',$video['transcoding_path']);
        $this->assign('token',session('token'));
        $this->assign('second',$video['lenght']);
        return view('video');
    }


    /**
     * 试看页面
     */
    public function free(){
        $id = input('id');
        $uid = session('uid');
        if(!$uid){
            $url = url('/');
            return alert('请先登录',$url,2,2);
        }
        $video = find('Video',['id'=>$id,'audit'=>'1']);
        if(!$video){
            return alert('视频不存在','',2,2);
        }

        $cid = $video['cid'];
        //是否购买
        $buy = false;
        if($uid){
            $checkbuy = checkbuy(['uid'=>$uid,'cid'=>$cid]);
            if($checkbuy == 1){
                $buy = true;
            }
        }
        $course = find('Course',['cid'=>$cid]);
        $course_password = find('CoursePassword',['uid'=>$uid,'cid'=>$cid,'password'=>$course['password']]);
        if(!$buy){
            if($course_password  || empty($course['password'])){
                $is_pass = 0;
            }else{
                $is_pass = 1;
            }
            if($is_pass == 1){
                return alert('视频需要密码观看','',2,2);
            }
        }else{
            if($course['price'] == 0){
                $is_pass = 1;
            }
            if($course_password  || empty($course['password'])){
                $is_pass = 0;
            }

            if($course['price'] > 0){
                $is_pass = 0;
            }
            if($is_pass == 1){
                return alert('视频需要密码观看','',2,2);
            }

        }


        $config = find('Config');
        $free_time = $config['free_time'];
        //试看时间
        if($buy){
            $config['free_time'] = 0;
        }
        $cid = $video['cid'];
        $this->assign('video',$video);
        $this->assign('uid',$uid);
        $this->assign('cid',$cid);
        $this->assign('tid',$video['tid']);
        $this->assign('vid',$video['vid']);
        $this->assign('vpath',$video['transcoding_path']);
        $this->assign('id',$id);
        $this->assign('free_time',$free_time);
        return view('free');
    }

}
