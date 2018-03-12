<?php
namespace app\index\controller;
use think\Controller;
use app\api\controller\Api;
use think\Db;


/**
 * Class Course 学习平台
 * @package app\api\controller
 */
class Course extends Controller
{
    public $uid;

    public function _initialize()
    {

    }

    /**
     *课程介绍
     */
    public function index()
    {
        $cid = input('cid'); //课程id
        $introduce_id = input('introduce_id'); //介绍id
        $num = input('num',1);
        //$course_one 取一个课程
        if($cid){
            $course_one =  find('Course',['cid'=>$cid,'audit'=>1]);
        }else{
            $course_one =  find('Course',['audit'=>1]);
        }
        //$course_introduce 课程介绍
        if($course_one){
            $cid = $course_one['cid'];
            $course_introduce = lists('CourseIntroduce',['cid'=>$cid],['orderby'=>'asc']);
            //$course_introduce_one 取一条介绍
            if($introduce_id){
                $course_introduce_one = find('CourseIntroduce',['cid'=>$cid,'id'=>$introduce_id],false,false,['orderby'=>'asc']);
            }else{
                $course_introduce_one = find('CourseIntroduce',['cid'=>$cid],false,false,['orderby'=>'asc']);
            }
        }else{
            $course_introduce = [];
            $course_introduce_one  = false;
        }
        if($course_introduce_one){
            $introduce_id = $course_introduce_one['id'];
        }
        $course_introduce_count = counts('CourseIntroduce',['cid'=>$cid]);
        $result =  apiget('index/index');
        $course = $result['data']['course'];

        foreach($course as $k=>$v){
            if($v['cid'] == $cid){
                unset($course[$k]);
            }
        }
        //下一篇
        if($course_introduce_count >1 && $course_introduce_count > $num){
            foreach($course_introduce as $k=>$v){
                if($k == ($num-1)){
                    $next_key = $k+1;
                }
            }
            $next_id = $course_introduce[$next_key]['id'];
            $next_url = url('course/index',['cid'=>$cid,'num'=>$num+1,'introduce_id'=>$next_id]);
            $this->assign('next_url',$next_url);
        }
        //是否购买
        $check_buy = 0;
        if(session('uid')){
            $check_buy = checkbuy(['cid'=>$cid,'uid'=>session('uid')]);
        }
        $this->assign('check_buy',$check_buy);
        $this->assign('course_one',$course_one);
        $this->assign('course',$course);
        $this->assign('course_introduce',$course_introduce);
        $this->assign('course_introduce_one',$course_introduce_one);
        $this->assign('course_introduce_count',$course_introduce_count);
        $this->assign('num',$num);
        $this->assign('introduce_id',$introduce_id);
        $this->assign('cid',$cid);
        return view('index');
    }

    /**
     * 课程列表
     */
    public function course(){
        $cid = input('cid');
        $tid = input('tid');
        $uid = session('uid');
        $course_where = [];
        if($cid){
            $course_where['cid'] = $cid;
        }
        $course_where['audit']=1;
        //课程信息
        $course = find('Course',$course_where);
        if(!$course){
            return alert('暂无课程','',2,2);
        }
        if(!$cid){
            $cid = $course['cid'];
        }
        $score = $course['score'];
        //评价星星
        $star = score($score);
        $course['star'] = $star;
        //课程老师信息
//         $user = find('User',['uid'=>$course['uid']]);
//         if(!$user){
//             return alert('获取老师信息失败','',2,2);
//         }
        //课程列表
        $course_list_arr=  apiget('course/allindex',['audit'=>1]);
        $course_list = $course_list_arr['data'];
        //章节列表
        $video_category_where['cid'] = $cid;
        $video_category_field = 'id,cid,cate_name';
        $video_category_order['orderby'] = 'asc';
        $video_category = lists('VideoCategory',$video_category_where,$video_category_order,$video_category_field);
        //视频列表
        if(empty($video_category)){
            $video = [];
        }else{
            if(!$tid){
                $tid = $video_category[0]['id'];
            }
            $vid_arr = [];
            $video = lists('Video',['tid'=>$tid,'audit'=>'1'],['orderby'=>'asc'],'id,free,vid,cid,tid,title,video_path,lenght');
            foreach($video as &$v){
                $v['is_look'] = 0;
                $v['lenght'] = changeTimeType($v['lenght']);
                $vid_arr[] = $v['id'];
            }
            unset($v);
            if($uid){
                $studylist = lists('StudyList',['uid'=>$uid,'vid'=>['in',$vid_arr]]);
                foreach($video as &$v){
                    foreach($studylist as $s){
                        if($v['id'] == $s['vid']){
                            if($s['status'] == 2){
                                $v['is_look'] = 1;
                            }
                        }
                    }
                }
                unset($v);
            }

        }
        //$course['user'] = $user;
        //评价列表
        $comment = apiget('comment/index',['cid'=>$cid,'page'=>1,'size'=>3]);
        $comment = $comment['data']['comment'];
        //多少学员
        //$study_count = counts('Order',['course_id'=>$cid,'pay_status'=>1]);
        //学员列表
        //$study_list = lists('Order',['course_id'=>$cid,'pay_status'=>1,'page'=>1,'size'=>8]);
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

        /*foreach($study_list as &$v){
            $v['face'] = '';
           foreach($user as $u){
               if($v['uid'] == $u['uid']){
                   $v['face'] = $u['face'];
               }
           }
        }
        unset($v);*/
        //是否购买
        $buy = false;
        if($uid){
            $checkbuy = checkbuy(['uid'=>$uid,'cid'=>$cid]);
            if($checkbuy == 1){
               $buy = true;
            }
        }
        //完成进度
        if($uid){
            $video_count = counts('Video',['cid'=>$cid]);
            $iscomment = Db::name('Comment')->where(array('uid'=>$uid,'cid'=>$cid))->find();
            if($iscomment){
                $speed = 0;
            }else{
                $study_list_count = Db::name('study_list')
                    ->group('vid')
                    ->where('cid',$cid)
                    ->where('status',2)
                    ->where('closed',0)
                    ->where('uid',$uid)
                    ->count();
                if($study_list_count == 0 || $video_count == 0){
                    $speed = 0;
                }else{
                    $speed = ($study_list_count / $video_count)*100;
                }
            }
        }else{
            $speed = 0;
        }

        //课程密码
        $course_password = find('CoursePassword',['uid'=>$uid,'cid'=>$cid,'password'=>$course['password']]);

        //免费课程需要密码
        if($buy && $course['price'] == 0){
            $is_pass = 1;
        }

        if($course_password  || empty($course['password'])){
            $is_pass = 0;
        }else{
            $is_pass = 1;
        }
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
        //非免费课程不需要密码
        if($buy && $course['price'] > 0){
            $is_pass = 0;
        }
        $vip = isvip($uid);
        $this->assign('speed',$speed);
        $this->assign('uid',$uid);
        $this->assign('vip',$vip);
        $this->assign('buy',$buy);
        $this->assign('course',$course);
        $this->assign('course_list',$course_list);
        $this->assign('cid',$course['cid']);
        $this->assign('tid',$tid);
        $this->assign('chapter',$video_category);
        $this->assign('video',$video);
        $this->assign('comment',$comment);
        $this->assign('study_count',$study_count);
        //$this->assign('study_list',$study_list);
        $this->assign('student_user',$student_user);
        $this->assign('is_pass',$is_pass);
        return view('course');
    }

    /**
     * 课程详情
     */
    public function detail(){
        return view('detail');
    }

    /**
     * 付款
     */
    public function payment(){
        $cid = input('cid');
        $uid = session('uid');
        if(!$uid){
            $url = url('/');
            return alert('请先登录',$url,2,2);
        }
        $course = find('Course',['cid'=>$cid]);
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

        $order_sn = date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
        $order['order_sn'] = $order_sn;
        $order['course_name'] = $course['title'];
        $order['price'] = $course['price'];
        $this->assign('order',$order);
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
        $checkbuy = checkbuy(['uid'=>$uid,'cid'=>$cid]);
        if($checkbuy != 1){
            return alert('请购买课程','',2,2);
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
