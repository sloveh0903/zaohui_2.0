<?php

namespace app\admin\controller;
use app\admin\model\Article;
use app\admin\model\Course;
use app\admin\model\Favorite;
use app\admin\model\Follow;
use app\admin\model\StudyList;
use app\admin\model\User;
use app\admin\model\Video;
use think\Db;
use think\Session;
use think\Log;
use app\model\IntegralDetail;
use app\api\controller\Integral;

class Member extends Base
{
    public function _empty(){
        return $this->index();
    }

    public function index(){
        $key = $so['nickname'] = input('key');
        $type = input('type');
        $map = [];
        $map['closed'] = 0;
        $map['type'] = 1;
        if($type){
            $map[$type] = ['like',"%" . $key . "%"];
        }
        $member = new User();
        $Nowpage = input('get.p') ? input('get.p'):1;
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $member->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $member->where($map)->order('uid desc')->limit($start,$limits)->select();
        foreach ($lists as $k => $v) {
            $total = [];
            $lists[$k]['ip'] = long2ip($v['ip']);
            $lists[$k]['sex'] = ($v['sex'] == "1") ? "男" : "女" ;
            $lists[$k]['nickname'] = str_replace("'","",$v['nickname']);
            $lists[$k]['edit_url'] = \think\Url::build('member/edit_member','uid='.$v['uid']);
            $lists[$k]['follow_url'] = \think\Url::build('member/follower','uid='.$v['uid']);
            $lists[$k]['favorite_url'] = \think\Url::build('member/favorite','uid='.$v['uid']);
            if($v['mobile']){
                $total = Db::name('order')
                    ->where("uid=:uid or mobile =:mobile", ['uid' => $v['uid'], 'mobile' => $v['mobile']])
                    ->where('closed','0')
                    ->where('pay_status','1')
                    ->field('sum(pay_price) as total')
                    ->find();
            }else{
                $total = Db::name('order')
                    ->where("uid", $v['uid'])
                    ->where('closed','0')
                    ->where('pay_status','1')
                    ->field('sum(pay_price) as total')
                    ->find();
            }
            $total['total']= $total['total'] ? $total['total'] : 0.00;
            $lists[$k]['total'] = sprintf("%.2f", $total['total']);//+$ucard_record_total['total'];
        }
        if(request()->isAjax()){
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="会员列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }

    public function code(){
        $config = Db::name('config')->where('id','1')->field(array('wx_open_appid'))->find();
        $data['wx_open_appid'] = $config['wx_open_appid'];
        $data['url'] = 'http://'.$_SERVER['HTTP_HOST'].'/admin/member/scanlogin.html';
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function scanlogin(){
        header("Content-Type: text/html;charset=utf-8");
        $code = input('code');
        $config = Db::name('config')->where('id','1')->field(array('wx_open_appid,wx_open_secret'))->find();
        $appid = $config['wx_open_appid'];
        $secret = $config['wx_open_secret'];
        $url =  "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
        $data = json_decode(doCurlGetRequest($url),true);
        if(!$data){
            $this->redirect(url('index/index'));
        }
        $unionid = $data['unionid'];
        $openid = $data['openid'];
        $access_token = $data['access_token'];
        $url='https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $data = json_decode(doCurlGetRequest($url),true);
        $nickname =  $data['nickname'];
        $sex = $data['sex'];
        $face = $data['headimgurl'];
        $user_service_openid = Db::name('user')->where('open_openid',$openid)->find();
        $user_unionid = Db::name('user')->where('unionId',$unionid)->find();
        if ($user_service_openid || $user_unionid) {
            if ($user_service_openid) {
                $user = $user_service_openid;
            } else {
                $user = $user_unionid;
            }
            $uid = $user['uid'];
            $user_face = $user['face'];
            $substr = substr($user_face, 0, 5);
            if ($substr != 'https' || empty($user_face)) {
                $user_data['face'] = $face;
            }
            $bind = $user['mobile_bind'];
            $str = $user['noncestr'];
            $time = time();
            $uname = $user['uname'];
            $token = md5($uname . $str . $time);
            $user_data['nickname'] = $nickname;
            $user_data['sex'] = $sex;
            $user_data['token'] = $token;
            $user_data['ip'] = ip2long($_SERVER["REMOTE_ADDR"]);
            $user_data['unionId'] = $unionid;
            $user_data['open_openid'] = $openid;
            Db::name('user')->where('uid',$uid)->update($user_data);
        } else {
            //创建账号
            $count = Db::name('user')->count();
            $uname = uniqid() . rand(99, 999);
            for ($i = 0; $i < $count; $i++) {
                $uname = uniqid() . rand(99, 999);
                $user_uname = Db::name('user')->where('uname',$uname)->find();
                if (!$user_uname) {
                    break;
                }
            }
            $class = ceil($count / 50);
            $str = createRandomStr(10);
            $password = md5($str);
            $time = time();
            $token = md5($uname . $str . $time);
            $data = [
                'uname' => $uname,
                'password' => $password,
                'noncestr' => $str,
                'token' => $token,
                'open_openid' => $openid,
                'face' => $face,
                'unionId' => $unionid,
                'sex' => $sex,
                'nickname' => $nickname,
                'class' => $class,
                'ip' => ip2long($_SERVER["REMOTE_ADDR"])
            ];
            $user = new User();
            $user->allowField(true)->save($data);
            $uid = $user->uid;
            if (!$uid) errorJson('注册失败');
        }
        $this->redirect(url('member/teacherInfo'));
    }
    /*调整积分*/
    public  function add_integral(){
        $uid = input('param.uid');
        $integral =  input('param.integral');
        $status = input('param.status');
        $remark = input('param.remark');
        $integral_api = new Integral();
        $integral_data  = $integral_api->admin($uid,$integral,$status,$remark);
        if($integral_data['integral_code']==1){
            return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => '添加成功']);
        }else{
            return json(['status' => -200,'url'=>'reload', 'data' => '', 'msg' => '添加失败']);
        }    
    }
    public function teacherInfo(){
        return $this->fetch();
    }

    public function add_teacher(){
        $uid = Session::pull('teacher_uid');
        $member = new User();
        $itemInfo = $member->get($uid);
        $this->assign('itemInfo',$itemInfo);
        return $this->fetch();
    }

    public function read_member(){
        $uid = input('param.id');
        $member = new User();
        $itemInfo = $member->get($uid);
        $this->assign('itemInfo',$itemInfo);
        return $this->fetch();
    }

    public function member_state(){
        $uid = input('param.uid');
        $member = new User();
        $status = $member->where(array('uid'=>$uid))->value('audit');//判断当前状态情况
        if($status==1)
        {
            $flag = $member->where(array('uid'=>$uid))->setField(['audit'=>0]);
            return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => '已禁止']);
        }
        else
        {
            $flag = $member->where(array('uid'=>$uid))->setField(['audit'=>1]);
            return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => '已开启']);
        }
    }

    public function delall_member(){
        $ids = input('param.checkbox');
        $member = new User();
        $where['uid'] = ['in',$ids];
        $flag = $member->DeleteData($where);
        return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    public function del_member(){
        $uid = input('param.id');
        $member = new User();
        $where['uid'] = $uid;
        $flag = $member->DeleteData($where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function setaudit_member(){
        $ids = input('param.checkbox');
        $member = new User();
        $where['uid'] = ['in',$ids];
        $param['audit'] = 1;
        $flag = $member->SetParam($param,$where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }



    public function setRole(){
        $ids = input('param.uid');
        $member = new User();
        $where['uid'] = ['in',$ids];
        $param['type'] = 2;
        $flag = $member->SetParam($param,$where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    public function favorite(){
        $key = $so['title'] = input('key');
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $uid = input('param.uid');
        $favorite = new Favorite();
        $course = new Course();
        $member = new User();
        $article = new Article();
        $map = [];
        $map['uid'] = $uid;
        if($key&&$key!==""){
            $map['title'] = ['like',"%" . $key . "%"];
        }
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }
        if($end_date){
            $map['create_time'] = $so['create_time '] = ['<',strtotime($end_date)];
        }


        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $favoriteList = $favorite->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($favoriteList as $k=>$v) {
            if($v['type'] == "course"){
                $course = $course->where(array('cid'=>$v['fid'],'closed'=>'0'))->find();
                $info = "课程：".$course['title'];
            }else{
                $article = $article->where(array('id'=>$v['fid'],'closed'=>'0'))->find();
                $info = "文章：".$article['title'] ? $article['title'] : "" ;
            }
            $favoriteList[$k]['info'] = $info;
            $favoriteList[$k]['nickname'] = $member->where(array('uid'=>$uid,'closed'=>'0'))->value('nickname');

        }
        $count = $favorite->where($map)->count();
        $allpage = ceil($count / $limits);

        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $favoriteList;
            $msg['data']['title']="会员列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        $this->assign('uid',$uid);
        return $this->fetch();
    }


    public function delall_favorite(){
        $ids = input('param.checkbox');
        $favorite = new Favorite();
        $where['id'] = ['in',$ids];
        $flag = $favorite->DeleteData($where);
        return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    public function del_favorite(){
        $id = input('param.id');
        $favorite = new Favorite();
        $where['id'] = $id;
        $flag = $favorite->DeleteData($where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    public function follower(){
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $uid = input('param.uid');
        $follow = new Follow();
        $member = new User();
        $map = [];
        $map['uid'] = $uid;
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }
        if($end_date){
            $map['create_time'] = $so['create_time '] = ['<',strtotime($end_date)];
        }
        $Nowpage = input('get.page') ? input('get.page'):1;
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $followList = $follow->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($followList as $k => $v) {
            $memberInfo = $member->where(array('uid'=>$v['tid']))->find();
            $followList[$k]['info'] = "老师：".$memberInfo['realname'];
            $followList[$k]['nickname'] = $member->where(array('uid'=>$uid,'closed'=>'0'))->value('nickname');
        }
        $count = $follow->where($map)->count();
        $allpage = ceil($count / $limits);
        $this->assign('uid', $uid);
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $followList;
            $msg['data']['title']="关注列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();

    }

    public function delall_follower(){
        $ids = input('param.checkbox');
        $follow = new Follow();
        $where['id'] = ['in',$ids];
        $flag = $follow->DeleteData($where);
        return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    public function del_follower(){
        $id = input('param.id');
        $follow = new Follow();
        $where['id'] = $id;
        $flag = $follow->DeleteData($where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }




    public function studyList(){
        $uid = input('param.id');
        $study = new StudyList();
        $video = new Video();
        $map = [];
        $map['uid'] = $uid;
        $StudyList = $study->where($map)->order('id desc')->select();
        foreach ($StudyList as $k => $v) {
            $StudyList[$k]['videoInfo'] = $video->where(array('id'=>$v['vid']))->find();
        }
        $this->assign('followList',$StudyList);
        return $this->fetch();
    }



    public function teacher(){
        $key = $so['nickname'] = input('key');
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $map = [];
        $map['closed'] = 0;
        $map['type'] = 2;
        if($key&&$key!==""){
            $map['nickname'] = ['like',"%" . $key . "%"];
        }
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }
        if($end_date){
            $map['create_time'] = $so['create_time '] = ['<',strtotime($end_date)];
        }
        $member = new User();
        $Nowpage = input('get.p') ? input('get.p'):1;
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $member->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $member->where($map)->order('uid desc')->limit($start,$limits)->select();
        foreach ($lists as $k => $v) {
            $lists[$k]['ip'] = long2ip($v['ip']);
            $lists[$k]['sex'] = ($v['sex'] == "1") ? "男" : "女" ;
            $lists[$k]['edit_url'] = \think\Url::build('teacher/edit_teacher','uid='.$v['uid']);
            $lists[$k]['follow_url'] = \think\Url::build('teacher/follower','uid='.$v['uid']);
        }

        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="老师列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }

        return $this->fetch();
    }

    public function edit_bind(){
        $member = new User();
        if(request()->isPost()){
            $param = input('post.');
            $userinfo = [];
            $userinfo = $member->where(array('uid'=>$param['uid'],'closed'=>'0','audit'=>'1'))->find();
            if(strlen($param['mobile'])!=11||preg_match("/[^\d-., ]/",$param['mobile'])){
                return json(['status' => -1, 'data' => '', 'msg' => '手机号码填写错误']);
            }
            if(isset($userinfo['mobile_bind']) && $userinfo['mobile_bind'] == 1){
                if($member->where(array('mobile'=>$param['mobile'],'uid'=>['<>',$param['uid']],'closed'=>'0','audit'=>'1'))->find()){
                    return json(['status' => -1, 'data' => '', 'msg' => '此手机已被绑定']);
                }else{
                    $tag = 'Unbundling';
                }
            }else{
                $param['mobile_bind'] = 1;
                $tag = 'Relation';
            }
            unset($param['nickname']);
            $where['uid'] = $param['uid'];
            $flag = $member->UpdateData($param,$where);
            if($tag == 'Relation'){
                updateRelation($param['uid'],$param['mobile']);
            }elseif($tag == 'Unbundling'){
                Unbundling($userinfo['mobile'],$param['mobile']);//换手机绑定
            }
            updateRelation($param['uid'],$param['mobile']);
            return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
        }
    }


    public function edit_teacher()
    {
        $member = new User();
        if(request()->isPost()){
            $param = input('post.');
            if(strpos('http://', $param['face']) !== false){
                $param['face'] = 'http://'.$_SERVER['HTTP_HOST'].$param['face'];
            }
            $where['uid'] = $param['uid'];
            $flag = $member->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'/admin/member/teacher', 'data' => '', 'msg' => $flag['msg']]);
        }
        $uid = input('param.uid');
        $itemInfo = $member->get($uid);
        $this->assign('itemInfo',$itemInfo);
        return $this->fetch();
    }

    /**
     * 删除老师设为学生
     * @return mixed|\think\response\Json
     */
    public function set_student()
    {
        $member = new User();
        if(request()->isPost()){
            $id = input('id');
            $param['type'] = 1;
            $where['uid'] = $id;
            $flag = $member->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'/admin/member/teacher', 'data' => '', 'msg' => $flag['msg']]);
        }
    }

    /**
     * 用户详情
     */
    public function detail(){
        $page = input('page',1);
        $limit = 10;
        $type = input('type',1);
        $uid = input('uid');
        $seach_id = input('seach_id',0);
        $member = new User();
        $userinfo = $member->find($uid);
        if(!$userinfo){
            return json(['status' => -1, 'data' => '', 'msg' => '用户不存在']);
        }
        if($userinfo['sex'] == '1'){
            $userinfo['sex'] = '男';
        }else{
            $userinfo['sex'] = '女';
        }
        //购买课程统计
        $vip = isvip($uid);
        $order_count = db('order')->group('course_id')->where(['uid'=>$uid,'pay_status'=>1,'closed'=>0])->count();
        //消费金额
        $mobile = $userinfo['mobile'];
        if($mobile){
            $total = Db::name('order')
            ->where("uid=:uid or mobile =:mobile", ['uid' =>$uid, 'mobile' => $mobile])
            ->where('closed','0')
            ->where('pay_status','1')
            ->field('sum(price) as total')
            ->find();
        }else{
            $total = Db::name('order')
            ->where("uid", $uid)
            ->where('closed','0')
            ->where('pay_status','1')
            ->field('sum(price) as total')
            ->find();
        }
        //vip
        if($mobile){
            $ucard_record_total = Db::name('order')
            ->where("uid=:uid or mobile =:mobile", ['uid' =>$uid, 'mobile' => $mobile])
            ->where('order_type','order_type')
            ->where('closed','0')
            ->where('pay_status','1')
            ->field('sum(price) as total')
            ->find();
        }else{
            $ucard_record_total = Db::name('order')
            ->where("uid", $uid)
            ->where('closed','0')
            ->where('pay_status','1')
            ->field('sum(price) as total')
            ->find();
        }
        $order_sum = $total['total']?$total['total']:0;
        $ucard_record_sum = $ucard_record_total['total']?$ucard_record_total['total']:0;
        
        $page_lists =array();
        $prefix = config('database.prefix');
        $size = 10;
        $start_num = 0;
        if($page==1){
            $study_limit =" limit 0,".$size."";
        }else{
            $start_num = ($page-1)*$size;
            $study_limit ="limit  ".$start_num.",".$size."";
        }
        //学习记录
        if($type == 1){
            $page_lists = db('study_list')->group('cid')->where(['uid'=>$uid,'closed'=>0])->paginate($limit,false,[
                'query'  => ['uid'=>$uid],
                'type'     => 'bootstrap',
                'var_page' => 'page',
            ]);
            //学习记录列表
            
            $sql = "select s.cid,s.day,s.create_time,temp_v.title as v_title,s.vid,max(s.status) as status,max(s.update_time) as update_time,o.create_time as order_time,c.title,c.face,IFNULL(temp_s.count,0)as study_video_count,IFNULL(v.count_video,0) as count_video  from ".$prefix."study_list as s
            left join ".$prefix."course as c on c.cid=s.cid
            left join (select count(temps.id) as count,temps.cid from (select id,cid,vid,uid from ".$prefix."study_list where status=2 and uid = ".$uid." and closed=0 GROUP BY vid,uid) as temps group by cid ) as temp_s on temp_s.cid=s.cid
            left JOIN (SELECT IFNULL(count(id),0) as count_video,cid from ".$prefix."video  where closed=0 GROUP BY cid) as v on v.cid = s.cid
            left JOIN ".$prefix."video as temp_v on temp_v.id = s.vid
            left JOIN ".$prefix."order as o on o.course_id = s.cid
            where s.uid = ".$uid."  and s.closed=0
            GROUP BY s.cid 
            ORDER BY s.update_time desc ".$study_limit;
            $lists  = Db::query($sql);
            foreach($lists as $key=>$value){
                $lists[$key]['update_time'] = date('Y-m-d',$value['update_time']);
                $lists[$key]['order_time']  = date('Y-m-d',$value['order_time']);
                if($value['count_video']<=0){
                    $lists[$key]['percent'] =0;
                    
                }else{
                    $lists[$key]['percent']=round($value['study_video_count']/$value['count_video']*100);
                }
            }
        }else if($type == 2){
           //做题记录
            $page_lists = db('testitem_record')->where('uid',$uid)->paginate($limit,false,[
                'query'  => ['uid'=>$uid],
                'type'     => 'bootstrap',
                'var_page' => 'page',
            ]);

            //$uid = 36;
            $lists = db('testitem_record')
                ->alias('r')
                ->where('r.uid',$uid)
                ->field('r.*,b.name,b.course_id')
                ->group('r.bank_id')
                ->join('__TESTITEM_BANK__ b','b.id = r.bank_id')
                ->limit($start_num,$size)
                ->select();
            $testitem_record_id = [];
            foreach($lists as $v){
                $testitem_record_id[] = $v['id'];
            }
            $lists = db('testitem_record')
                ->alias('r')
                ->where(['r.id'=>['in',$testitem_record_id]])
               // ->where('r.uid',$uid)
                ->order('update_time','desc')
                ->field('r.*,b.name,b.course_id')
                ->group('r.bank_id')
                ->join('__TESTITEM_BANK__ b','b.id = r.bank_id')
                //->limit(($page-1)*$limit,$limit)
                ->select();


            $bank_arr = [];
            $cid_arr = [];
            foreach($lists as $v){
                $cid_arr[] = $v['course_id'];
                $bank_arr[] = $v['bank_id'];
            }

            $course = db('course')->where(['cid'=>['in',$cid_arr]])->select();
            $course_list = [];
            foreach($course as $v){
                $course_list[$v['cid']] = $v['title'];
            }
            $testitem_record = db('testitem_record')->where(['uid'=>$uid,'bank_id'=>['in',$bank_arr]])->select();
            foreach($lists as $k=>&$v){
                $v['course_name'] = '';
                $v['recore_count'] = 0;
                $v['recore_wrong'] = 0;
                if(isset($course_list[$v['course_id']])){
                    $v['course_name'] = $course_list[$v['course_id']];
                }else{
                    $v['course_name'] = '';
                }
                foreach($testitem_record as $r){
                    if($v['bank_id'] == $r['bank_id']){
                        if(!empty($r['record'])){
                            $record = json_decode($r['record'],true);
                            foreach($record as $r2){
                                if($r2['count'] > 0){
                                    $v['recore_count'] = $v['recore_count']+$r2['count'];
                                }
                                if($r2['wrong'] > 0){
                                    $v['recore_wrong'] = $v['recore_wrong']+$r2['wrong'];
                                }
                            }
                        }
                    }
                }
                if($v['recore_count'] == 0){
                    $v['proportion'] = 0;
                }else{
                    $v['proportion'] =  100 - intval($v['recore_wrong']/$v['recore_count']*100);
                }
            }
            unset($v);

        }
        else if($type == 3){
            $size = 10;
            $start_num = 0;
            if($page==1){
                $study_limit =" limit 0,".$size."";
            }else{
                $start_num = ($page-1)*$size;
                $study_limit ="limit  ".$start_num.",".$size."";
            }
            $page_where['uid'] = $uid; 
            if($seach_id==1){
                $page_where['integral'] = ['>=',0]; 
            }else if($seach_id==2){
                $page_where['integral'] = ['<',0]; 
            }
            $page_lists = db('IntegralDetail')->where($page_where)->order('create_time desc')->paginate($limit,false,[
                'query'  => ['type'=>$type,'uid'=>$uid,'seach_id'=>$seach_id],
                'type'     => 'bootstrap',
                'var_page' => 'page',
            ]);
            $lists = db('IntegralDetail')->where($page_where)->order('create_time desc')->limit($start_num,$size)->select();
            
        }
        $this->assign('seach_id',$seach_id);
        $this->assign('userinfo',$userinfo);
        $this->assign('order_count',$order_count);
        $this->assign('vip',$vip);
        $this->assign('order_sum',$order_sum+$ucard_record_sum);
        $this->assign('list',$lists);
        $this->assign('page_lists',$page_lists);
        $this->assign('uid',$uid);
        $this->assign('type',$type);
        return $this->fetch();
    }
  public function videodetail(){
      $cid = input('cid',0);
      $uid = input('uid',0);
      $page = input('page',1);
      $prefix = config('database.prefix');
      $size = 5;
      $limitstart = ($page-1)*$size;
      $lists = array();
      $allpage = 0;
      
      if($cid&&$uid){
          $count_sql = "select count(lists.id) as counts from
            (SELECT cate.id,cate.cid,cate.cate_name,v.id as vid,v.title,v.closed FROM `".$prefix."video_category` as cate
            left join ".$prefix."video as v on v.tid = cate.id
            where cate.cid=".$cid." and cate.closed=0  ) as lists
              left join (select MAX(`status`) as status ,update_time,vid from ".$prefix."study_list where uid=".$uid." and cid=".$cid."  and closed=0 GROUP BY vid) as s on s.vid = lists.vid order by lists.id asc  ";
          
          $count_arr = Db::query($count_sql);
          $count = $count_arr[0]['counts'];//总数
          $allpage = ceil($count/$size);
          //var_dump($count_arr);
           // var_dump($allpage);
           
            $sql = "select lists.id,lists.cid,lists.cate_name,lists.vid,lists.title,s.status,s.update_time from
            (SELECT cate.id,cate.cid,cate.cate_name,v.id as vid,v.title,v.closed FROM `".$prefix."video_category` as cate
            left join ".$prefix."video as v on v.tid = cate.id
            where cate.cid=".$cid." and cate.closed=0  ) as lists
              left join (select MAX(`status`) as status ,update_time,vid from ".$prefix."study_list where uid=".$uid." and cid=".$cid."  and closed=0 GROUP BY vid) as s on s.vid = lists.vid order by lists.id asc  limit ".$limitstart.",".$size; 
              $lists = Db::query($sql);
              //var_dump($lists);
              //die();
              foreach ($lists as $k=>$v){
                  if($v['title']){
                      if($v['status']){
                          switch ($v['status']){
                              case 1:
                                  $lists[$k]['status_text'] = '观看中';
                                  break;
                              case 2:
                                  $lists[$k]['status_text'] = '已完成';
                                  break;
                          }
                          $lists[$k]['update_date'] = date('Y-m-d H:i:s',$v['update_time']);
                      }else{
                          $lists[$k]['status_text'] = '未观看';
                          $lists[$k]['update_date'] = '---';
                      }
                  }else{
                      $lists[$k]['title']='---';
                      $lists[$k]['status_text'] = '---';
                      $lists[$k]['update_date'] = '---';
                  }
                  
              }
      }
     $data['lists'] = $lists;
     $data['allpage'] = $allpage;
     successJson('操作完成',$data);
  }
  public function videodetailcount(){
      $cid = input('cid',0);
      $uid = input('uid',0);
      $prefix = config('database.prefix');
      if($cid&&$uid){
          $count_sql =  'select count(lists.vid) as counts from
                    (SELECT cate.id,cate.cid,cate.cate_name,v.id as vid,v.title,v.closed FROM `'.$prefix.'video_category` as cate
                    left join '.$prefix.'video as v on v.tid = cate.id
                    where cate.cid='.$cid.' and cate.closed=0  ) as lists
                    left join (select * from '.$prefix.'study_list where uid='.$uid.' and cid='.$cid.' and closed=0 ) as s on s.vid = lists.vid  where lists.closed=0 ';
          $count_arr = Db::query($count_sql);
          $count = $count_arr[0]['counts'];//总数
      }
      successJson('操作完成',$count);
  }
}