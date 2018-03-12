<?php
namespace app\api\controller;
use think\Controller;
use think\db;

/**
 * Class User 用户
 * @package app\api\controller
 */
class User extends Controller
{
    public function _initialize()
    {

    }

    /**
     * 注册账号
     * @author王宣成
     * @return Json
     */
    public function  postRegister(){
        $type = input('reg_type','account');
        switch($type){
            case 'account':
                $param['uname'] = trim(input('username',''));
                $param['password'] = trim(input('password',''));
                $this->regAccount($param);
                break;
            default:
                errorJson('参数错误');
        }
    }

    /**
     * 账号密码注册
     * @author王宣成
     * @return Json
     */
    private function regAccount($param){
        //验证器
        $validate = validate('User');
        if(!$validate->check($param)){
            errorJson(($validate->getError()));
        }
        $where['uname'] = $param['uname'];
        $uname = find('User',$where);
        if($uname) errorJson('用户名已存在');
        $str = createRandomStr(10);
        $password = md5(md5($param['password']).$str);
        $time = time();
        $token = md5($param['uname'].$str.$time);
        $count = counts('User');
        $class = ceil($count/50);
        $data =[
            'uname'=> $param['uname'],
            'password' => $password,
            'noncestr' => $str,
            'token_time' => $time,
            'token' => $token,
            'class' => $class,
            'ip' => ip2long($_SERVER["REMOTE_ADDR"])
        ];
        $user_id = insert('User',$data,'uid');
        if(!$user_id) errorJson('注册失败');

        $result = [
            'uid'=>$user_id,
            'uname' => $param['uname'],
            'token' => $token,
        ];
        successJson('操作完成',$result);
    }

    /**
     * 登录
     * @author王宣成
     * @return Json
     */
    public function postLogin(){
        $mobile = input('mobile','');
        $password = input('password','');
        if(empty($mobile)) errorJson('请输入手机号');
        if(empty($password)) errorJson('请输入密码');
        $user = find('User',['mobile'=>$mobile]);
        if(!$user) errorJson('手机号未注册');
        $str = $user['noncestr'];
        $password = md5(md5($password).$str);
        if($user['password'] != $password) errorJson('密码错误');
        $uid = $user['uid'];
        $read_num = counts('Read',['uid'=>$uid,'isread'=>0]);
        $notify_num =  counts('Notify',['type'=>'2']);
        $notify =  lists('Notify',['type'=>'2']);
        $nid_arr = [];
        foreach($notify as $n){
            $nid_arr[] = $n['id'];
        }
        $read_where['uid'] = $uid;
        $read_where['nid'] = ['in',$nid_arr];
        $read_notify_num =  counts('Read',$read_where);
        $message_num = $read_num + ($notify_num - $read_notify_num);
        $token = md5($user['uname'] . $user['noncestr'] . time());
        update('User',['uid'=>$uid],['token'=>$token]);
        session('msg_num',$message_num);
        session('uid', $user['uid']);
        session('face', $user['face']);
        session('nickname', $user['nickname']);
        session('bind', $user['mobile_bind']);
        session('token', $token);
        successJson('登录成功');
    }

    /**
     *是否登陆
     */
    public function getChecklogin(){
        if(session('uid')){
            successJson('已登录',session('uid'));
        }else{
            errorJson('未登录');
        }
    }

    /**
     * 退出登录
     * @author王宣成
     * @return Json
     */
    public function postLoginout(){
        session('uid', null);
        successJson('操作成功');
    }


    /**
     * 设置is_bind为null
     * @author王宣成
     * @return Json
     */
    public function postSetBind(){
        session('is_bind', null);

        successJson('操作成功');
    }

    public function postClearAudit(){
        session('uid', null);
        session('audit', null);
        successJson('操作成功');
    }

    public function postBindSuccess(){
        $uid = input('uid');
        session('is_bind', 'yes');
        session('uid', $uid);
        successJson('操作成功');
    }

    /**
     * 个人中心
     * @author王宣成
     * @return Json
     */
    public function getCenter(){
        //已购买
        $uid = input('uid',0);
        $fielde = 'uid,uname,nickname,money,face,sex,address,mobile,realname,class,point,money,is_rebate,expire_time,cardtype,now_integral';
        $user = find('User',['uid'=>$uid],$fielde);
        $mobile = $user['mobile'];
        $user['mobile'] = ($user['mobile']) ? substr_replace($user['mobile'],'****',3,4) : "";
        if(!$user) errorJson('用户不存在');
        //订单统计
        $order_count = 0;
        if(!empty($mobile)){
            $where =  " (o.uid = ".$uid." or  o.mobile = ".$mobile.") ";
        }else{
            $where = " o.uid = ".$uid;
        }
        //查询 课程+套餐 订单总数
        $where .=" and (o.order_type = 'course' or o.order_type = 'package') ";
        $prefix = config('database.prefix');
        $sql = "SELECT count('id') countorder FROM ".$prefix."order as o LEFT JOIN  ".$prefix."course as  c  on c.cid = o.course_id".
            " WHERE".$where.
            " AND o.pay_status = '1'";
        //echo $sql;
        $ordercount= db('order')->query($sql);
        if($ordercount){
            $order_count = $ordercount[0]['countorder'];
        }
        $expire_time = '';
        $vip = isvip($uid);
        if($vip == 1){
            $expire_time = date('Y-m-d',$user['expire_time']);
        }

        //收藏
        $favorite_count = counts('Favorite',['uid'=>$uid]);
        //我的提问
        $ask_count = counts('Ask',['uid'=>$uid]);
        //新回答
        $new_comments =  sum('Ask',['uid'=>$uid],'new_comments');
        if(!$new_comments)  $new_comments = 0;
        //我的解答
        $answer_count = counts('Answer',['uid'=>$uid]);
        //从什么时候开始学习
        $study_complete = db('study_list')->where(['uid'=>$uid,'study_time'=>['>',0]])->select();
        $study_begin = isset($study_complete[0]['create_time']) ? $study_complete[0]['create_time'] : time();
        $study_begin = date('Y-m-d',$study_begin);

        //学了多少天
        //$study_day_where['study_time'] = ['>',0];
        $study_day_where['uid'] = ['=',$uid];
       // $study_day_where['closed'] = ['=',0];
        $study_day = db('study_list')->where($study_day_where)->group('day')->count();
        //班级排名
        $class = $user['class'];
        $class_user = lists('User',['class'=>$class],[],$fielde);
        $class_uid = [];
        foreach($class_user as $v){
            $class_uid[] = $v['uid'];
        }
        $study_where['uid'] = ['in',$class_uid];
        $study_list = lists('StudyList',$study_where);
        foreach($class_user as &$v){
            $v['study_time'] = 0;
            foreach($study_list as $s){
                if($v['uid'] == $s['uid']){
                    $v['study_time'] += $s['study_time'];
                }
            }
        }
        unset($v);
        array_multisort(array_column($class_user,'study_time'),SORT_DESC,$class_user);
        $ranking = $i = 1;
       // p($class_user);
        foreach($class_user as $v){
            if($v['uid'] == $uid){
                $ranking = $i;
            }
            $i++;
        }
        //累计学习时间
        $course_sum = sum('StudyList',['uid'=>$uid],'study_time');
        if($course_sum >= 3600){
            $h = intval($course_sum/3600).'时';
            $m = $course_sum-($h * 3600).'分';
            $course_sum = $h.$m;
        }else if($course_sum >= 60){
            $m = intval($course_sum/60).'分';
            $course_sum = $m;
        }else{
            $m = $course_sum.'秒';
            $course_sum = $m;
        }
        //订阅数
        $follow = counts('Follow',['uid'=>$uid]);
        //完成课程
       // $study_complete = db('study_list')->where(['uid'=>$uid,'status'=>2,'closed'=>0])->count();
        $study_complete_list = lists('StudyList',['uid'=>$uid,'status'=>2,'closed'=>0]);
        $course_arr = [];
        $cid_arr = [];
        $study_complete = 0;
        foreach($study_complete_list as $s){
            $cid = $s['cid'];
            $course_arr[$cid] = [];
        }
        foreach($course_arr as $k=>&$c){
            $cid_arr[] = $k;
            foreach($study_complete_list as $s){
               if($k == $s['cid']){
                   $vid = $s['vid'];
                   $c[$vid] = $vid;
               }
            }
        }
        unset($c);
        foreach($course_arr as $k=>&$c){
            $c['count'] = count($c);
        }
        //p($course_arr);
        unset($c);
        $video_list = lists('Video',['audit'=>'1','cid'=>['in',$cid_arr]]);
        //p($video_list);
        foreach($course_arr as $k=>&$c){
            $c['num'] = 0;
            foreach($video_list as $v){
                if($v['cid'] == $k){
                    $c['num']+=1;
                }
            }
            if($c['num'] == $c['count']){
                $study_complete += 1;
            }
        }
        unset($c);

        //学习记录 当前周
        $study_list = db('study_list')->where(['uid'=>$uid,'closed'=>0])->whereTime('create_time', 'w')->select();
        $week = [
            'Monday' => 0,
            'Tuesday' => 0,
            'Wednesday' => 0,
            'Thursday'=> 0,
            'Friday' => 0,
            'Saturday' => 0,
            'Sunday' => 0,
        ];
        //判断这周哪天学习过 0未学习 1已学习
        foreach($study_list as $v){
            for($i=1;$i<=7;$i++){
                if(getWeek($i) == date('Y-m-d',$v['create_time'])){
                    switch($i){
                        case 1:
                            $week['Monday'] = 1;
                            break;
                        case 2:
                            $week['Tuesday'] = 1;
                            break;
                        case 3:
                            $week['Wednesday'] = 1;
                            break;
                        case 4:
                            $week['Thursday'] = 1;
                            break;
                        case 5:
                            $week['Friday'] = 1;
                            break;
                        case 6:
                            $week['Saturday'] = 1;
                            break;
                        case 7:
                            $week['Sunday'] = 1;
                            break;
                        default:;
                    }
                }
            }
        }

        $config =  find('Config',['id'=>1]);

        //是否开启分销
        $rebate_config = find('RebateConfig');
        if(!$rebate_config || $rebate_config['status'] != 1){
            $user['is_rebate'] = 0;//关闭分销
        }



        //会员卡功能
        $usercard = Db('userCard')->where(array('closed'=>'0'))->select();
        if($usercard){
            $havacard = '1';
        }else{
            $havacard = '0';
        }


        $data = [
            'user' => $user,
            'config' => $config,
            'order_count' => $order_count,
            'ask_count' => $ask_count,
            'favorite_count' => $favorite_count,
            'new_comments' => $new_comments,
            'answer_count' => $answer_count,
            'week' => $week,
            'study_complete' => $study_complete,
            'study_time' => $course_sum,
            'study_begin' => $study_begin,
            'study_day' => $study_day,
            'ranking' => $ranking,
            'follow' => $follow,
            'havecard' => $havacard,
            'vip'=>$vip,
            'expire_time'=>$expire_time
        ];
        successJson('操作成功',$data);
    }

    /**
     * 我的问题
     * @author王宣成
     * @return Json
     */
    public function getAsk(){
        $uid = input('uid',0);
        $page = input('page',1);
        $size = input('size',10);
        $where['uid'] = $uid;
        $where['page'] = $page;
        $where['size'] = $size;
        $cid_arr = [];
        $course = lists('Course',['audit'=>1]);
        foreach($course as $v){
            $cid_arr[] = $v['cid'];
        }
        $where['cid'] = ['in',$cid_arr];
        $ask = lists('Ask',$where,['new_comments'=>'desc','id'=>'desc'],'id,title,new_comments');
        $aid = [];
        foreach($ask as $v){
            $aid[] = $v['id'];
        }
        $answer_where['aid'] = ['in',$aid];
        $answer_where['root_id'] = "0";
        $answer = lists('Answer',$answer_where);
        /*p($answer);
        die;*/
        foreach($ask as &$v){
            $v['answer'] = "";
            $v['answer_id'] = "";
            $count = 0;
            foreach($answer as $a){
                if($v['id'] == $a['aid']){
                    $v['answer'] = $a['content'];
                    $v['answer_id'] = $a['id'];
                    $count++;
                }
            }
            //$v['answer'] = end($v['answer']);
            $v['answer_count'] = $count;
        }
        unset($v);
        $data['ask'] = $ask;
        successJson('操作成功',$data);
    }

    /**
     * 我的回答
     * @author王宣成
     * @return Json
     */
    public function getAnswer(){
        $uid = input('uid',0);
        $page = input('page',1);
        $size = input('size',10);
        $where['uid'] = $uid;
        $where['page'] = $page;
        $where['size'] = $size;
        $where['root_id'] = '0';
        $answer = lists('Answer',$where);
        $aid = [];
        foreach($answer as $v){
            $aid[] = $v['aid'];
        }
        $ask_where['id'] = ['in',$aid];
        $ask = lists('Ask',$ask_where);
        foreach ($answer as &$a) {
            $a['ask_title'] = "";
            $a['ask_id'] = "";
            foreach ($ask as $v) {
                if($v['id'] == $a['aid']){
                    $a['ask_title'] = $v['title'];
                    $a['ask_id'] = $v['id'];
                }
            }
        }
        unset($v);
        $data['list'] = $answer;
        successJson('操作成功',$data);
    }

    /**
     * 老师信息
     * @author王宣成
     * @return Json
     */
    public function getTeacher(){
        //老师信息
        $muid = input('muid'); //我都uid
        $uid = input('uid');   //老师uid
        $user_field = 'uid,realname,face,info,wechat';
        $where['uid'] = $uid;
        $where['type'] = 2;
        $user = find('User',$where,$user_field);
        if(!$user) errorJson('数据不存在');
        //多少人购买
        $course = lists('Course',['uid'=>$uid]);
        $cid_arr = [];
        foreach($course as $v){
            $cid_arr[] = $v['cid'];
        }
        $order_where['course_id'] = ['in',$cid_arr];
        $order = counts('Order',$order_where);
        //多少人订阅
        $like = counts('Follow',['tid'=>$uid]);
        $follow = find('Follow',['tid'=>$uid,'uid'=>$muid]);
        if($follow){
            $user['is_follow'] = 1;
        }else{
            $user['is_follow'] = 0;; //未订阅
        }
        $user['like'] = $like;
        $user['student'] = $order;
        $data = [
            'user' => $user
        ];


        successJson('操作成功',$data);
    }

    public function getTeacherList(){
        $user_field = 'uid,realname,face,info,wechat';
        $where['type'] = 2;
        $user = lists('User',$where,['uid'=>'asc'],$user_field);
        successJson('操作成功',$user);
    }

    /**
     * 发送短信验证码
     */
    public function postSendcode(){
        $uid = input('uid');
        if(!$uid) errorJson('缺少uid参数');
        $phone = input('phone');
        $type = input('type','center');
        if(!$phone) errorJson('请填写手机号');
        if(!is_numeric($phone) || strlen($phone) != 11) errorJson('请填写正确的手机号');
        $code = rand(1000,9999);
        $user = find('User',['uid'=>$uid]);
        if(!$user)  errorJson('用户不存在');
        $sms = find('Sms',['uid'=>$uid,'type'=>$type]);
        $data = sendsms($phone,$code);
        if($data->Code !='OK') errorJson('发送失败');
        if($sms){
            if($sms['update_time']+60 > time()){
                errorJson('60秒内不能重复发送');
            }
            update('Sms',['uid'=>$uid,'type'=>$type],['code'=>$code,'phone'=>$phone]);
        }else{
            $inser_data = [
                'code' => $code,
                'uid' => $uid,
                'phone' => $phone,
                'type' => $type,
            ];
            insert('Sms',$inser_data);
        }
        successJson('操作成功',$code);
    }

    /**
     * 绑定手机号码
     */
    public function postChecksms(){
        $uid = input('uid');
        if(!$uid) errorJson('缺少uid参数');
        $phone = input('phone');
        if(!$phone) errorJson('请填写手机号');
        if(!is_numeric($phone) || strlen($phone) != 11) errorJson('请填写正确的手机号');
        $code = input('code');
        if(!$code)  errorJson('缺少验证码');
        $sms = find('Sms',['uid'=>$uid,'phone'=>$phone,'type'=>'center','code'=>$code],false,false,['id'=>'desc']);
        if(!$sms)  errorJson('验证码错误');
        if($sms['update_time']+600 > time()){
            errorJson('验证码失效,请重新发送');
        }
        $modile = find('User',['mobile'=>$phone]);
        if($modile) errorJson('手机号已被绑定');
        update('User',['uid'=>$uid],['mobile'=>$phone,'mobile_bind'=>1]);
        updateRelation($uid,$phone);
        successJson('操作成功',$phone);
    }




    /**
     * 检测验证码
     * Create By Xenos
     * Return
     */
    public function postCheck(){
        $uid = input('uid');
        $type = input('type','center');
        if(!$uid) errorJson('缺少uid参数');
        $phone = input('phone');
        if(!$phone) errorJson('请填写手机号');
        if(!is_numeric($phone) || strlen($phone) != 11) errorJson('请填写正确的手机号');
        $code = input('code');
        if(!$code)  errorJson('缺少验证码');
        $sms = find('Sms',['uid'=>$uid,'phone'=>$phone,'type'=>$type,'code'=>$code],false,false,['id'=>'desc']);
        if($sms){
            successJson('操作成功',$phone);
        }else{
            errorJson('验证失败');
        }

    }


    /**
     * 设置密码
     */
    public function postSetpassword(){
        $mobile = input('mobile');
        $password = input('password');
        $code = input('code');
        $uid = input('uid');
        if(empty($mobile)) errorJson('请输入手机号');
        if(empty($code)) errorJson('请输入验证码');
        if(empty($password)) errorJson('请输入密码');
        $user = find('User',['uid'=>$uid]);
        if(!$user) errorJson('用户不存在');
        $sms = find('Sms',['uid'=>$uid,'phone'=>$mobile,'type'=>'setpassword','code'=>$code]);
        if(!$sms)  errorJson('验证码错误');
        if($sms['update_time']+600 > time()){
            errorJson('验证码失效,请重新发送');
        }
        $str = $user['noncestr'];
        $md5password = md5(md5($password).$str);
        $update = update('User',['uid'=>$uid],['password'=>$md5password]);
        if(!$update) errorJson('设置密码失败');
        successJson('操作成功');
    }


    public function postBindphone(){
        $mobile = input('mobile');
        $password = input('password');
        $code = input('code');
        $uid = input('uid');
        if(empty($mobile)) errorJson('请输入手机号');
        if(empty($code)) errorJson('请输入验证码');
        if(empty($password)) errorJson('请输入密码');
        $user = find('User',['uid'=>$uid]);
        if(!$user) errorJson('用户不存在');
        $modile = find('User',['mobile'=>$mobile]);
        if($modile) errorJson('手机号已被绑定');
        $sms = find('Sms',['uid'=>$uid,'phone'=>$mobile,'type'=>'setpassword','code'=>$code]);
        if(!$sms)  errorJson('验证码错误');
        if($sms['update_time']+600 > time()){
            errorJson('验证码失效,请重新发送');
        }
        $str = $user['noncestr'];
        $md5password = md5(md5($password).$str);
        $update = update('User',['uid'=>$uid],['password'=>$md5password,'mobile'=>$mobile,'mobile_bind'=>'1']);
        updateRelation($uid,$mobile);
        if(!$update) errorJson('设置密码失败');
        successJson('操作成功');
    }

    public function postUpdatePhone(){
        $mobile = input('mobile');
        $code = input('code');
        $uid = input('uid');
        $type = input('type','center');
        if(empty($mobile)) errorJson('请输入手机号');
        if(empty($code)) errorJson('请输入验证码');
        $user = find('User',['uid'=>$uid]);
        if(!$user) errorJson('用户不存在');
        $sms = find('Sms',['uid'=>$uid,'phone'=>$mobile,'type'=>$type,'code'=>$code]);
        if(!$sms)  errorJson('验证码错误');
        if($sms['update_time']+600 > time()){
            errorJson('验证码失效,请重新发送');
        }
        $old_phone = Db::name('user')->where('uid',$uid)->value('mobile');
        $update = update('User',['uid'=>$uid],['mobile'=>$mobile]);
        if(!$update){
            errorJson('操作失败');
        }else{
            if($old_phone){
                //所有的订单跟套餐订单号码修改
                Db::name('order')->where(array('mobile'=>$old_phone))->update(array('mobile'=>$mobile));
                Db::name('packageOrder')->where(array('mobile'=>$old_phone))->update(array('mobile'=>$mobile));
            }
            successJson('操作成功');
        }

    }

    public function getInfo(){
        $uid = input('uid');
        $user = find('User',['uid'=>$uid],['uname,face,point,type,wechat,info,mobile,mobile_bind,audit']);
        successJson('操作成功',$user);
    }

    /**
     * 获取用户详情
     */
    public function getUserinfo(){
        $user =  lists('User',[],[],'uid,service_openid');
        $arr = [];
        $list = [];
        foreach($user as $v){
            $openid = $v['service_openid'];
            //获取用户详细信息
            $getAccessToken = getAccessToken();
            $basic_access_token = $getAccessToken['access_token'];
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $basic_access_token . '&openid=' . $openid . '&lang=zh_CN';
            $json_data = doCurlGetRequest($url);
            $data = json_decode($json_data, true);
            if(isset($data['unionid'])){
                $arr['uid'] = $v['uid'];
                $arr['unionId'] = $data['unionid'];
                $list[] = $arr;
            }

//            if($v['uid'] > 10){
//               break;
//            }
        }
        $model = 'User';
        $class = '\app\model\\'.$model;
        $obj = model($class);
        $obj->saveAll($list);
        //p($list);
    }

    /**
     * 付费用户批量更新
     */
    public function getPayuserup(){
        $map['price'] = ['>',0];
        $map['pay_status'] = 1;
        $map['uid'] = ['>',0];
        $user = [];
        $order = db('order')->where($map)->group('uid')->select();
        foreach($order as $v){
            $user[] = $v['uid'];
        }
        $user = array_unique($user);
        $map = [];
        $map['uid'] = ['in',$user];
        db('user')->where($map)->setField('is_pay', '1');
    }

}
