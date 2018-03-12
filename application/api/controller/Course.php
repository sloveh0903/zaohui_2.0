<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use app\admin\model\Course as Cr;
/**
 * Class Course 课程
 * @package app\api\controller
 */
class Course extends Controller
{
    public function _initialize()
    {

    }

    /**
     * 课程列表
     * @author王宣成
     * @return Json
     */
    public function getIndex(){
        $uid = input('uid');
        $pid = input('pid');
        $page = input('page',1);
        $size = input('size',10);
        $audit = input('audit',1);  //1上架  0下架  2全部
        if($audit == 1){
            $where['audit']  = 1;
        }
        if($audit == 0){
            $where['audit'] = 0;
        }
        $where['page'] = $page;
        $where['size'] = $size;
        if($uid) $where['uid'] = $uid;
        if($pid) $where['pid'] = $uid;
        $order['orderby'] = 'asc';
        $course = lists('Course',$where,$order);
        $ret_user = $course_data = [];

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
            foreach ($course as &$v) {
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

                $course_data[] = $ret_course;
            }

        }
        $data['course'] = $course_data;
        successJson('操作完成',$data);
    }
    /**
     * 获取所有课程
     */
    public function getAllIndex(){
        $audit = input('audit',1);  //1上架  0下架  2全部
        if($audit == 1){
            $where['audit']  = 1;
        }
        if($audit == 0){
            $where['audit'] = 0;
        }
        $where['closed'] = 0;
        $order['orderby'] = 'asc';
        $data = db('course')->field('cid,pid,title,face,desc,banner,price,subscribe_num,child_id')->where($where)->order($order)->select();

        successJson('操作完成',$data);
    }
    /**
     * 获取用户所有已购课程
     */
    public function getAllBuy(){
        $uid = input('uid');
        $user = find('User',['uid'=>$uid],'mobile,mobile_bind');
        $mobile = trim($user['mobile']);
        $order = array();
        $package_order = array();
        $order_course_id = array();
        if($mobile && $user['mobile_bind'] == 1){
            $where =  " (o.uid = ".$uid." or  o.mobile = ".$mobile.") ";
        }else{
            $where = " o.uid = ".$uid;
        }
        $prefix = config('database.prefix');
        $course_sql = "select * from ".$prefix."course  where audit=1 and closed=0  and price <=0";
        $course = db('order')->query($course_sql);
        $sql = "SELECT * FROM ".$prefix."order as o LEFT JOIN  ".$prefix."course as  c  on c.cid = o.course_id".
            " WHERE".$where.
            " AND o.pay_status = '1' and o.closed=0 AND c.closed=0 and c.audit = 1";
        $order = db('order')->query($sql);
        $package_sql = "SELECT * FROM ".$prefix."package_order as o LEFT JOIN ".$prefix."course as c on c.cid = o.course_id WHERE ".$where." AND o.closed=0 AND c.closed=0 and c.audit = 1";
        $package_order =db('order')->query($package_sql);
        if($order||$package_order){
            $new_order= array_merge($order, $package_order);
        }else{
            $new_order =array();
        }
        $return_order= array_merge($course, $new_order);
        $retrun_arr = array();
        foreach($return_order as $my_o){
            $retrun_arr[$my_o['cid']] = $my_o;
        }
        successJson('操作完成',$retrun_arr);
    }
    /**
     * 多分类首页
     */
    public function getmulitindex(){
        $pid = input('pid',0); //课程顶级分类id
        $child_id = input('child_id',0); //课程子分类id
        $page = input('page',1);
        $limits = input('limits',16);
        $course_list = array();
        $map['closed'] = 0;
        $map['audit'] = 1;
        if($child_id==0&&$pid==0){//全部课程
            $count = Db::name('course')->where($map)->count();//计算总页面
            $allpage = ceil($count / $limits);
            $course_list = lists('Course',['audit'=>1,'page'=>$page,'size'=>$limits],['orderby'=>'desc']);
        }else{
            $map['pid'] = $pid;
            if($child_id !=0){
                $map['child_id'] = $child_id;
                $course_list = lists('Course',['audit'=>1,'pid'=>$pid,'child_id'=>$child_id,'page'=>$page,'size'=>$limits],['orderby'=>'desc']);
            } else{
                $course_list = lists('Course',['audit'=>1,'pid'=>$pid,'page'=>$page,'size'=>$limits],['orderby'=>'desc']);
            }
            $count = Db::name('course')->where($map)->count();//计算总页面
            $allpage = ceil($count / $limits);
        }
        $data['courselist'] = $course_list;
        $data['currentPage'] = $page;
        $data['total'] = $allpage;
        header("Content-type: application/json");
        exit(json_encode($data));
    }
    /**
     * 微信分类 获取 顶级分类课程+二级分类+二级分类课程 
     */
    public function getwxmulitindex(){
        $centcid =input('id',0); 
        $child_list =lists('CourseCategory',['closed'=>'0','pid'=>$centcid],['orderby'=>'desc'],['id','cate_name','pid']);
        $course_map['pid|child_id'] = $centcid;
        $course_map['closed'] = '0';
        $course_map['audit'] = '1';
        $course = lists('Course',$course_map,['orderby'=>'desc']); //获取此大类id所有课程
        $top_course_list = array();
        $ret_child = array();
        $havecourse = '0';
        
        $course_studynum =get_course_studynum();
        foreach ($course as $k=>$cv) {
            //是否拥有虚拟人数
            if($course[$k]['virtual_amount']){
                $studynum = $cv['virtual_amount'];
            }else{
                $studynum = isset($course_studynum[$cv['cid']])?$course_studynum[$cv['cid']]:0;
            }
            $cv['study_count'] =  $studynum;
            if($cv['pid'] == $centcid and $cv['child_id'] == '0'){
                $top_course_list[] = $cv;
                unset($k);
            }
        }
        foreach ($child_list as $key=>$value) {
            $ret_child[$key] = $value;
            foreach ($course as $c) {
                if($c['child_id'] == $value['id']){
                    //是否拥有虚拟人数
                    if($c['virtual_amount']){
                        $studycount = $c['virtual_amount'];
                    }else{
                        //$studycount = db('order')->where(['course_id'=>$course[$k]['cid'],'pay_status'=>1])->count('id') ;
                        $studycount =isset($course_studynum[$c['cid']])?$course_studynum[$c['cid']]:0;
                    }
                    $c['study_count'] =  $studycount;
                    if($c){
                        $havecourse = '1';
                    }
                    $ret_child[$key]['child_course_list'][] = $c;
                }
            }
        }
        if($top_course_list){
            $havecourse = '1';
        }
        $data['topcourse'] = $top_course_list;
        $data['childcate'] = $ret_child;
        $data['havecourse'] = $havecourse;
        header("Content-type: application/json");
        exit(json_encode($data));
    }
    /**
     * 微信分类 获取 顶级分类 包括  顶级分类课程+二级分类+二级分类课程
     */
    public function getwxmulitallcate(){
        $top_course_list = array();
        $child_course_cate_list = array();
        $top_course_cate_list = array();
        $cate_list  = array();
        $top_course_cate_list = lists('CourseCategory',['pid'=>0,'closed'=>0],['orderby'=>'desc'],['id','cate_name','pid']);
        foreach ($top_course_cate_list as $key=>$top_cate_list){
            $cate_list[$key]['id'] = $top_cate_list['id'];
            $cate_list[$key]['cate_name'] = $top_cate_list['cate_name'];
            $cate_list[$key]['topcourse']= lists('Course',['pid'=>$top_cate_list['id'],'child_id'=>0,'closed'=>0],['orderby'=>'desc'],['cid','title','face','price']);
            $child_course_cate_list = lists('CourseCategory',['pid'=>$top_cate_list['id'],'closed'=>0],['orderby'=>'desc'],['id','cate_name','pid']);
            foreach ($child_course_cate_list as &$childcate){
                $childcate['courselist'] = lists('Course',['audit'=>1,'pid'=>$top_cate_list['id'],'child_id'=>$childcate['id'],'closed'=>0],['orderby'=>'desc'],['cid','title','face','price']);
            }
            $cate_list[$key]['childcate'] = $child_course_cate_list;
        }
        
        header("Content-type: application/json");
        exit(json_encode($cate_list));
    }
    /**
     * 课程简介
     * @author王宣成
     * @return Json
     */
    public function getDetail(){
        $cid = input('cid',0);
        $uid = input('uid');
		$config = db::name('Config')->where(array('id'=>1))->field(array('officer_url,allow'))->find();
        $officer_url = $config['officer_url'];
        $is_rebate = 0;
        if($uid){
            $user = find('User',['uid'=>$uid]);
            if($user){
                $is_rebate = $user['is_rebate'];
            }
        }
        //课程简介
        $course_where['cid'] = $cid;
        $course = find('Course',$course_where);
        $rebate_config = find('RebateConfig');
        if(!$course) errorJson('课程数据不存在');
        $study_count = db::name('Order')->where(array('course_id'=>$cid,'pay_status'=>1,'closed'=>'0'))->field(array('uid'))->group('uid')->count();
        $checkbuy = 0;//是否可以观看
        $is_pay = 0;//是否购买过
        $pay = checkbuy(['uid'=>$uid,'cid'=>$cid]);//是否购买
        if($pay==1){
            $checkbuy = 1;
            $is_pay = 1;
        }
        if($course['price']==0){
            $checkbuy = 1;
        }
		$is_favorite = 0;
        if($uid){
            $favorite = find('Favorite',['uid'=>$uid,'fid'=>$cid,'type'=>'course']);
            if($favorite){
                $is_favorite = $favorite['id'];
            }
        }
        //是否订阅
        $tid = $course['uid'];
        $follow = find('Follow',['uid'=>$uid,'tid'=>$tid]);
        if($follow){
            $course['follow'] = 1;
        }else{
            $course['follow'] = 0;
        }
        $course['discount'] = 10; //默认不打折
        $course['rebate_money'] = 0;
        $course['rebate_status'] = isset($rebate_config['status']) ? $rebate_config['status'] : 0;
        $course['discount_price']=0;
        if($rebate_config['status'] == '1' && $course['price'] > 0){
            $course['discount'] = $rebate_config['discount'];
            if($uid){
                $user_field = 'uid,uname,face,info,realname,is_rebate,p1';
                $user = find('User',['uid'=>$uid],$user_field);
                if(($user['is_rebate'] == 1 || $user['p1'] > 0) && $rebate_config['discount']<10){
                    $price = $course['price']*$rebate_config['discount']/10;
                    $discount_price = ($price >= 0.01) ? $price : 0.01;
                    $course['discount_price'] = sprintf("%.2f",$discount_price);
                }
            }
            $course['rebate_money'] = $course['price']*($rebate_config['first']/100);
            if($course['rebate_money'] < 0.01){
                $course['rebate_money'] = 0;
            }
            $course['rebate_money'] = sprintf("%.2f", $course['rebate_money']);

        }
        if($course['rebate_money'] == 0){
            $course['rebate_status'] = 0;
        }
        $star = score( $course['score']);
        $course['star'] = $star;//评价星星
		$user_where['uid'] = ['=',$course['uid']];
        $user_field = 'uid,uname,face,info,realname,is_rebate,p1';
        $user = find('User',$user_where,$user_field);
        //多少人评价
//         $comment = counts('Comment',['cid'=>$cid]);
//         $course['comment'] = $comment;

        //时长转换
        $lenght = $course['lenght'];
        if($lenght >= 3600){
            $h = intval($lenght/3600).'h';
            $m = (($lenght-($h * 3600))/60);
            $lenght = $h.intval($m).'m';
        }else if($lenght >= 60){
            $m = intval($lenght/60).'m';
            $lenght = $m;
        }else{
            $m = $lenght.'s';
            $lenght = $m;
        }
		if($checkbuy == 1){
            $data['free_time'] = 0;
        }
        //是否有试看视频
        $vidio_free = get_first_free_video($cid);
        $free_video_path = $vidio_free?$vidio_free['video_path']:0;
        if($checkbuy == 1){
            $free_video_path = 0;
        }
        $vip = isvip($uid);
        $course['checkbuy'] = $checkbuy;
        $course['lenght'] = $lenght;
        $course['is_vip'] = $vip;
		$course['is_favorite'] = $is_favorite;
        if($ucard = find('UserCard',['closed'=>0])){
            $data['user_card'] = 1;
        }else{
            $data['user_card'] = 0;
        }
        //是否开启了小程序客服
        $data['is_mini_customer']=show_switch('is_mini_customer');
        //是否允许小程序访问
		$data['user'] = $user;
		$data['officer_url'] = $officer_url;
        $data['allow'] = $config['allow'];
        $data['study_count'] = $study_count;
        $data['course_detail'] = $course;
        $data['is_pay'] = $is_pay;
        $data['follow'] =$follow;
        $data['is_rebate'] = $is_rebate;
        $data['rebate_status'] = $course['rebate_status'];
        $data['free_video_path']  = $free_video_path;
        successJson('操作完成',$data);
    }

    public function getCategory(){
        $pid = input('pid',0);
        $child_list =lists('CourseCategory',['closed'=>'0','pid'=>$pid],['orderby'=>'desc'],['id','cate_name','pid']);
        $data['category'] = $child_list;
        successJson('操作完成',$data);
    }

    /**
     * 课程章节
     * @author王宣成
     * @return Json
     */
    public function getChapter(){
        $cid = input('cid');
        $uid = session('uid');
        $audit = input('audit',1);
        //视频分类
        $video_category_where['cid'] = $cid;
        if($audit == 1){
            $video_category_where['audit'] = 1;
        }
        if($audit == 0){
            $video_category_where['audit'] = 0;
        }
        $video_category_field = 'id,cid,cate_name,audit';
        $video_category_order['orderby'] = 'asc';
        $video_category = lists('VideoCategory',$video_category_where,$video_category_order,$video_category_field);

        //视频列表
        $video_where['cid'] = $cid;
        $video_where['audit'] = 1;
        $video = lists('Video',$video_where,['orderby'=>'asc']);
        $videoList = [];
        if($video){
            $arr_vid = [];
            foreach ($video as $value){
                $arr_vid[] = $value['id'];
            }
            $statuswhere['cid'] = $cid;
            $statuswhere['status'] = 2;
            $statuswhere['uid'] = $uid;
            $statuswhere['vid'] = ['in',$arr_vid];
            $statuswhere['cleaned'] = 0;
            $statusLists = db('study_list')->where($statuswhere)->field('vid,status')->select();
            foreach ($statusLists as $v) {
                $ret_status = [];
                $ret_status[$v['vid']] = $v['status'];
            }
            foreach($video as $v2){
                /*$studywhere['vid'] =  $v2['id'];
                $studywhere['cid'] =  $cid;
                $studywhere['status'] = 2;//观看完
                //$studywhere['closed'] = 0;
                $studywhere['uid'] = $uid;
                $is_study = db('study_list')->where($studywhere)->value('status');*/
                $is_study = 0;
                if(isset($ret_status[$v2['id']])){
                    $is_study = $ret_status[$v2['id']];
                }
                $v2['is_study'] = $is_study>0?1:0;
                $v2['second'] = $v2['lenght'];
                $v2['lenght'] = changeTimeType($v2['lenght']);
                $v2['study_time'] = '00:00:00';
                $videoList[$v2['tid']][] = $v2;
            }
        }

        $category_item = [];
        foreach($video_category as $k => $v){
            $v['video_list'] = [];
            if($k == 0){
                $v['switch'] = 'on';
            }else{
                $v['switch'] = 'off';
            }
            if(array_key_exists($v['id'],$videoList)){
                $v['video_list'] = $videoList[$v['id']];
            }
            $category_item[] = $v;
        }
        $data['video'] = $category_item;
        successJson('操作完成',$data);
    }
    
    /**
     * 是否购买
     * @author王宣成
     * @return Json
     */
    public function getCheckbuy()
    {
        $uid = input('uid');
        $cid = input('cid');
        $data = checkbuy(['uid'=>$uid,'cid'=>$cid]);
        if($data == 0){
           errorJson('未购买课程');
        }
        if($data == -1){
            errorJson('课程已过期');
        }
        successJson('操作完成',$data);
    }

    /**
     * 课程介绍
     */
    public function getIntroduce(){
        $course_introduce = lists('CourseIntroduce',[],['orderby'=>'asc'],'id,cid,title');
        successJson('操作成功',$course_introduce);
    }

    //授权播放
    public function getValidate(){
        $uid = session('uid');
        $user =  find('User',['uid'=>$uid]);
        if($user['mobile_bind'] == 1){
            $username = $user['mobile'];
        }else{
            $username =  'ID:'.$user['uname'];
        }
        $secretkey = "RcJed1awOK";
        $vid = input("vid");
        $t = input("t");
        $code = input("code");
        $fontSize="20";
        $fontColor="0xFFE900";
        $speed="200";
        $filter="on";
        $setting="3";
        $alpha="0.1";
        $filterAlpha="1";
        $filterColor="0x3914AF";
        $blurX="2";
        $blurY="2";
        $tweenTime="1";
        $interval="5";
        $lifeTime="3";
        $strength="4";
        $show="on";
        $msg="error!";
        $status = 1;
        if(!empty($_GET["callback"])){
            $callback = $_GET["callback"];
        }else{
            $callback = '';
        }
        if($callback!=''){
            $sign=md5("vid=$vid&secretkey=$secretkey&username=$username&code=$code&status=$status&t=$t");
            $array=Array("status"=>$status,"username"=>$username,"sign"=>$sign);
            $Json = json_encode($array);
            echo $callback."(".$Json.")";
        } else{
            $sign = md5("vid=$vid&secretkey=$secretkey&username=$username&code=$code&status=$status&t=$t&msg=$msg&fontSize=$fontSize&fontColor=$fontColor&speed=$speed&filter=$filter&setting=$setting&alpha=$alpha&filterAlpha=$filterAlpha&filterColor=$filterColor&blurX=$blurX&blurY=$blurY&interval=$interval&lifeTime=$lifeTime&tweenTime=$tweenTime&strength=$strength&show=$show");
            $array = Array("status"=>$status,"username"=>$username,"sign"=>$sign,"msg"=>$msg,"fontSize"=>$fontSize,"fontColor"=>$fontColor,"speed"=>$speed,"filter"=>$filter,"setting"=>$setting,"alpha"=>$alpha,"filterAlpha"=>$filterAlpha,"filterColor"=>$filterColor,"blurX"=>$blurX,"blurY"=>$blurY,"tweenTime"=>$tweenTime,"interval"=>$interval,"lifeTime"=>$lifeTime,"strength"=>$strength,"show"=>$show,);
            $Json = json_encode($array);
            die($Json);
        }

    }

    /**
     * 生成分销海报
     */
    public function getPoster(){
        //$poster_type = input('poster_type',2);
        $poster_type = 2;
        $flag = input('flag',1); //是否签名
        $uid = input('uid'); //用户id
        $cid = input('cid'); //课程id
        $font = input('font','msyh.ttf'); //字体
        $font = ROOT_PATH . 'public' . DS .'font/'.$font;
        $user = find('User',['uid'=>$uid]);
        if(!$user){
            errorJson('用户不存在');
        }
        $course = find('Course',['cid'=>$cid]);
        if(!$course){
            errorJson('课程不存在');
        }
        if(empty($course['poster'])){
            $course['poster'] = '/public/image/poster.jpg';
        }


        //生成二维码
        $poster_dir = ROOT_PATH . 'public/poster/';
        if (!is_dir($poster_dir)) mkdir($poster_dir); // 如果不存在则创建
        if (!is_dir($poster_dir.'qrcode/')) mkdir($poster_dir.'qrcode/'); // 如果不存在则创建
        if (!is_dir($poster_dir.'user/')) mkdir($poster_dir.'user/');
        if (!is_dir($poster_dir.'poster/')) mkdir($poster_dir.'poster/');
        $day = date('Y-m-d',time());
        $filename = $uid.'_'.$cid.'_'.$day.'.jpg';
        $key = $uid.'_'.$cid.'_0';
        $new_qrcode_path = $poster_dir.'qrcode/'.$uid.'_'.$cid.'_'.$day.'.png';
        if(!file_exists ($poster_dir.'qrcode/'.$filename)){
            $getAccessToken =  getAccessToken();
            $token = $getAccessToken['access_token'];
            $qrcode_url ='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token;
            $data = '{"expire_seconds": 604800, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$key.'"}}}';
            $curl = doCurlPostRequest($qrcode_url,$data);
            $json_curl = json_decode($curl,true);
            $ticket = $json_curl['ticket'];
            $qrcode_path = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
            $getImage = getImage($qrcode_path,$poster_dir.'/qrcode/',$filename);
            $qrcode_path = $getImage['save_path'];
        }else{
            $qrcode_path = $poster_dir.'qrcode/'.$filename;
        }
        $dst_path = ROOT_PATH.$course['poster']; //原图
        $src_path = ROOT_PATH . 'public/poster/user/'.$uid.'.png'; //头像水印图
        $face_path = $user['face'];
        $substr = substr($face_path, 0, 4);
        if ($substr != 'http') {
            $face_path = ROOT_PATH.$face_path;
        }else{
            $filename = $uid.'.jpg';
            if(!file_exists ($poster_dir.'user/'.$filename)){
                $getImage = getImage($face_path,$poster_dir.'user/',$filename);
                $face_path = $getImage['save_path'];
            }else{
                $face_path = ROOT_PATH . 'public/poster/user/'.$uid.'.jpg';
            }
        }
        $text = $user['nickname'];
        $text1 = '向您推荐一门课程';

        //图片大小
        $face_str = substr($face_path , 0 , 5);
        if($face_str != 'https'){
            $face_path = str_replace("http","https",$face_path);
        }
        if($poster_type == 1){
            resize_image($face_path, $src_path, '120', '120');
        }

        if($poster_type == 2){
            resize_image($face_path, $src_path, '80', '80');
        }

        resize_image($qrcode_path, $new_qrcode_path, '200', '200');

        //裁剪头像圆形
        $imgg = yuanimg($src_path);
        imagepng($imgg,$src_path);

        //创建图片的实例
        $dst = imagecreatefromstring(file_get_contents($dst_path));
        $src = imagecreatefromstring(file_get_contents($src_path));
        $qrcode = imagecreatefromstring(file_get_contents($new_qrcode_path));

        //打上文字
        $black = imagecolorallocate($dst,51, 51, 51);//字体颜色
        $black2 = imagecolorallocate($dst, 153, 153, 153);//字体颜色

        //获取水印图片的宽高
        list($src_w, $src_h) = getimagesize($src_path);
        list($dst_w, $dst_h) = getimagesize($dst_path);
        list($qr_w, $qr_h) = getimagesize($new_qrcode_path);


        //获取水印图片的宽高
        $fontSize = 18;//像素字体
        $fontBox = imagettfbbox($fontSize, 0, $font, $text);//文字水平居中实质
        $fontBox1 = imagettfbbox($fontSize, 0, $font, $text1);//文字水平居中实质

        if($poster_type == 1){
            if($flag == 1){
                imagefttext($dst, $fontSize, 0, ceil(($dst_w - $fontBox[2]) / 2), 230, $black, $font, $text);
            }
            imagefttext($dst, $fontSize, 0, ceil(($dst_w - $fontBox1[2]) / 2), 270, $black2, $font, $text1);
            //将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
            //imagecopymerge($dst, $src, 200, 300, 0, 0, $src_w, $src_h, 100);
            //如果水印图片本身带透明色，则使用imagecopy方法
            imagecopy($dst, $src, ceil(($dst_w - $src_w) / 2), 70, 0, 0, $src_w, $src_h);
            imagecopy($dst, $qrcode, ceil(($dst_w - $qr_w) / 2), $dst_h-250, 0, 0, $qr_w, $qr_h);
        }

        if($poster_type == 2){
            if($flag == 1){
                $text == "";
            }

            $smallHeight = 260;
            $newImage = imagecreate($dst_w, $smallHeight);
            imagecolorallocate($newImage, 255, 255, 255);
            imagecopy($dst, $newImage, 0, $dst_h-$smallHeight, 0, 0, $dst_w, $smallHeight);
            imagefttext($dst, 22, 0, 160, $dst_h-180, $black, $font, $text);
            imagefttext($dst, 19, 0, 160, $dst_h-130, $black2, $font, $text1);
            imagefttext($dst, 19, 0, 160, $dst_h-80, $black2, $font, '长按二维码一起来学习');
          //  imagefttext($dst, $fontSize, 0, 100, $dst_h-200, $black, $font, $text.$text1);
            //将水印图片复制到目标图片上，最后个参数50是设置透明度，这里实现半透明效果
            //imagecopymerge($dst, $src, 200, 300, 0, 0, $src_w, $src_h, 100);
            //如果水印图片本身带透明色，则使用imagecopy方法

            imagecopy($dst, $src, 40, $dst_h-210, 0, 0, $src_w, $src_h);
            imagecopy($dst, $qrcode, $dst_w-230, $dst_h-230, 0, 0, $qr_w, $qr_h);
        }

        //输出图片
        header('Content-Type: image/jpeg');
        //水印图
        $posterfile = ROOT_PATH . 'public/poster/poster/';
        //imagejpeg($dst);
        imagejpeg($dst,$posterfile.$uid.'_'.$cid.'.jpg',70);
        imagedestroy($dst);
        $poster_find = find('Poster',['uid'=>$uid,'cid'=>$cid]);
        $poster_path = '/public/poster/poster/'.$uid.'_'.$cid.'.jpg';
        if($poster_find){
            update('Poster',['uid'=>$uid,'cid'=>$cid],['photo'=>$poster_path]);
        }else{
            insert('Poster',['uid'=>$uid,'cid'=>$cid,'photo'=>$poster_path]);
        }
        successJson('ok',$poster_path);
    }

    /**
     * 验证课程密码
     */
    public function postVerificationPass(){
        $uid = input('uid');
        $cid = input('cid');
        $pass = input('pass');
        if(!$uid || !$cid || !$pass){
            errorJson('缺少参数');
        }
        $user = find('User',['uid'=>$uid]);
        $course = find('Course',['cid'=>$cid]);
        if(!$course || !$user){
            errorJson('数据错误');
        }
        if($pass == $course['password']){
            insert('CoursePassword',['uid'=>$uid,'cid'=>$cid,'password'=>$pass]);
            successJson('验证通过');
        }else{
            errorJson('密码不正确');
        }

    }

    /**
     * 删除
     */
    public function getdelete(){
        $id = input('id');
        if(!$id){
            errorJson('缺少课程id参数');
        }
        $video_category = dbFind('video_category',['cid'=>$id]);
        if($video_category){
            errorJson('此课程下有视频章节，请删除再试');
        }
        $video = dbFind('video',['cid'=>$id]);
        if($video){

          errorJson('此课程下有视频，请先删除再试');
        }
        db('course')->where('cid',$id)->delete();

        successJson('删除成功');
    }
  /**
   * 排序
   */
    public function getmoveUpDown(){
        $id = input('cid');
        $updown = input('updown');
        $course = new Cr();
        $data_this = $course->where('cid',$id)->find();
        $orderby = $data_this['orderby'];
        if($updown == "up"){
            $map['orderby'] = ['>',$orderby];
            $order = 'orderby asc';
        }else{
            $map['orderby'] = ['<',$orderby];
            $order = 'orderby desc';
        }
        $data_next = $course->where($map)->order($order)->find();
        if($data_next){
            $next_id = $data_next['cid'];
            $next_order = $data_next['orderby'];
            $list = [
                ['cid'=>$id, 'orderby'=>$next_order],
                ['cid'=>$next_id, 'orderby'=>$orderby]
            ];
            $course->saveAll($list);
        }
        return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => '排序成功']);
    }

    /**
     * 是否购买课程
     */
    public function getIsbuy(){
        $uid = input('uid');
        $cid = input('cid');
        $course = find('Course',['cid'=>$cid]);
        if($course['price'] == 0){
            successJson('已购买');
        }
        $data = checkbuy(['uid'=>$uid,'cid'=>$cid]);
        if($data == 1){
            successJson('已购买');
        }
        if($data == -1){
            errorJson('课程已过期不能提问');
        }
        if($data == 0){
            errorJson('课程未购买不能提问');
        }
    }

}
