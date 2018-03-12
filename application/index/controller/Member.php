<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use app\api\controller\Api;

/**
 * Class Index
 * @package app\api\controller
 */
class Member extends Controller
{
    public function _initialize()
    {
        if(!session('uid')){
            $this->redirect('/');
        }
        $action = strtolower(request()->action());
        $this->assign('action',$action);
        // parent::signature();
    }

    public function _empty()
    {
        return $this->Index();
    }

    public function Index(){
        if($uid = session('uid')){
            $user = $this->userInfo($uid);
            $lists = array();
            $prefix = config('database.prefix');
            $sql = "select s.cid,s.day,s.create_time,temp_v.title as v_title,s.vid,max(s.status) as status,max(s.update_time) as update_time,o.create_time as order_time,c.title,c.face,IFNULL(temp_s.count,0)as study_video_count,IFNULL(v.count_video,0) as count_video  from ".$prefix."study_list as s
            left join ".$prefix."course as c on c.cid=s.cid
            left join (select count(temps.id) as count,temps.cid from (select id,cid,vid,uid from ".$prefix."study_list where status=2 and uid = ".$uid." and closed=0 GROUP BY vid,uid) as temps group by cid ) as temp_s on temp_s.cid=s.cid
            left JOIN (SELECT IFNULL(count(id),0) as count_video,cid from ".$prefix."video  where closed=0 GROUP BY cid) as v on v.cid = s.cid
            left JOIN ".$prefix."video as temp_v on temp_v.id = s.vid
            left JOIN ".$prefix."order as o on o.course_id = s.cid
            where s.uid = ".$uid."  and s.closed=0 and s.cleaned=0
            GROUP BY s.cid
            ORDER BY s.update_time desc";
            $lists  = Db::query($sql);
            
            foreach($lists as $key=>$value){
                $lists[$key]['update_time'] = date('Y-m-d',$value['update_time']);
                if($value['count_video']<=0){
                    $lists[$key]['percent'] =0;
                    
                }else{
                    $lists[$key]['percent']=round($value['study_video_count']/$value['count_video']*100);
                }
            }
            $this->assign('uid',$uid);
            $this->assign('lists',$lists);
            $this->assign('user',$user);
            return $this->fetch();
        }else{
            return alert('您还未登录！请先登录','',2,2);
        }

    }

    public function message(){
        if($uid = session('uid')){
            $user = $this->userInfo($uid);
            $send_uids = [];
            $msgList = [];
            $message = Db::name('notify')
                ->alias('n')
                ->join('gz_read r','n.id = r.nid')
                ->where(array('n.`type`'=>'1','n.closed'=>'0','r.uid'=>$uid))
                ->select();
            $notice = Db::name('notify')
                ->where(array('`type`'=>'2','closed'=>'0'))
                ->select();
            foreach ($message as $v){
                $send_uids[] = $v['sender'];
            }
            $userList = Db::name('user')
                ->where(array('`uid`'=>['in',$send_uids],'closed'=>'0'))
                ->field('uid,nickname,face,type,realname')
                ->select();
            foreach ($userList as $item) {
                $ret_data[$item['uid']] = $item;
            }

            foreach ($message as $msg) {
                $ret_msg = [];
                if(isset($ret_data[$msg['sender']]['type'])){
                    if($ret_data[$msg['sender']]['type'] == '2'){
                        $nickname = $ret_data[$msg['sender']]['realname']."老师";
                    }else{
                        $nickname = $ret_data[$msg['sender']]['nickname'];
                    }
                }else {
                    $nickname = '**';
                }
                
                switch ($msg['action']){
                    case "comment":
                        $tips = "评论了我";
                        break;
                    case "like":
                        $tips = "点赞了我";
                        break;
                    case "notice":
                        $tips = "系统通知";
                        break;
                }
                $ret_msg['type'] = '1';
                $ret_msg['tips'] = $nickname.$tips;
                $ret_msg['face'] = isset($ret_data[$msg['sender']]['face'])?$ret_data[$msg['sender']]['face']:'';
                $ret_msg['date'] = $msg['create_time'];
                $ret_msg['create_time'] = date('Y/m/d',$msg['create_time']);
                $ret_msg['target'] = $msg['target'];
                $ret_msg['targetType'] = ($msg['targetType']=="answer" ) ? "ask" : $msg['targetType'] ;
                $ret_msg['isread'] = $msg['isread'];
                $ret_msg['content'] = $msg['content'];
                $msgList[] = $ret_msg;
            }
            $r_data = [];
            $ntList = [];
            foreach ($notice as $nt) {
                $ret_nt = [];
                $ret_nt['tips'] = '系统通知';
                $ret_nt['type'] = '2';
                $ret_nt['face'] = '/public/pc/images/notice.png';
                $ret_nt['date'] = $nt['create_time'];
                $ret_nt['create_time'] = date('Y/m/d',$nt['create_time']);
                $ret_nt['target'] = $nt['target'];
                $ret_nt['targetType'] = $nt['targetType'];
                if(Db::name('read')->where(array('`nid`'=>$nt['id'],'uid'=>$uid))->find()){
                    $ret_nt['isread'] = 1;
                }else{
                    $ret_nt['isread'] = 0;
                    $read_data['isread'] = 1;
                    $read_data['uid'] = $uid;
                    $read_data['nid'] = $nt['id'];
                    $r_data[] = $read_data;
                }
                $ret_nt['content'] = $nt['content'];
                $ntList[] = $ret_nt;

            }
            $messageList = array_merge($ntList,$msgList);
            $st = [];
            foreach ($messageList as $key => $value) {
                $st[$key] = $value['date'];
            }


            array_multisort($st,SORT_DESC,$messageList);
            Db::name('notify')
                ->alias('n')
                ->join('gz_read r','n.id = r.nid')
                ->where(array('n.`type`'=>'1','n.closed'=>'0','r.isread'=>'0','r.uid'=>$uid))
                ->update(array('r.isread'=>'1'));

            if($r_data){
                Db::name('read')->insertAll($r_data);
            }

            //$messageList = array_multisort($sort,SORT_DESC,$messageList);
            /*p($messageList);
            die();*/
            $this->assign('uid',$uid);
            session('msg_num','0');
            $this->assign('messageList',$messageList);
            $this->assign('user',$user);
            return $this->fetch();
        }else{
            return alert('您还未登录！请先登录','',2,2);
        }
    }

    public function order(){
        if($uid = session('uid')){
            $user = $this->userInfo($uid);
            $mobile = $user['user']['mobile'];
            if(!$user)  errorJson('用户不存在');

            if($mobile){
                $order = Db::name('order')
                    ->where("uid=:uid or mobile =:mobile", ['uid' => $uid, 'mobile' => $mobile])
                    ->where('closed','0')
                    ->where('pay_status','1')
                    ->select();
            }else{
                $order = Db::name('order')
                    ->where("uid", $uid)
                    ->where('closed','0')
                    ->where('pay_status','1')
                    ->select();
            }
            $disOrder = $allOrder = [];
            foreach ($order as $k=>$v) {
                $ret_order = [];
                $ret_order['id'] = $v['id'];
                if($v['order_type'] == 'course'){
                    $ret_order['course_name'] = '课程：'.$v['course_name'];
                }elseif($v['order_type'] == 'usercard'){
                    $ret_order['course_name'] = '会员卡：'.$v['card_name'];
                }elseif($v['order_type'] == 'package'){
                    $ret_order['course_name'] = '套餐：'.$v['package_name'];
                }
                $ret_order['course_id'] = $v['course_id'];
                $ret_order['order_sn'] = $v['order_sn'];
                if(!$v['coupon_id'] && !$v['integral']&&$v['pay_price']==0){
                    $ret_order['pay_price_html'] = '免费';
                }else{
                    $ret_order['pay_price_html'] = '￥'.$v['pay_price'];
                }
                $ret_order['price'] = $v['price'];
                $ret_order['pay_status'] = $v['pay_status'];
                $ret_order['pay_name'] = "已付款";
                if($v['pay_status'] == 0){
                    $ret_order['pay_name'] = "未付款";
                    $disOrder[] = $ret_order;
                }else{
                    $allOrder[] = $ret_order;
                }

            }
            $this->assign('uid',$uid);
            $this->assign('user',$user);
            $this->assign('disOrder',$disOrder);
            $this->assign('allOrder',$allOrder);
            return $this->fetch();
        }else{
            return alert('您还未登录！请先登录','',2,2);
        }
    }

    function userInfo($uid){
        $fielde = 'uid,uname,nickname,face,sex,address,mobile,realname,class,point,now_integral,integral_count';
        $user = Db::name('User')->where('uid',$uid)->field($fielde)->find();
        if(!$user) errorJson('用户不存在');


        //从什么时候开始学习
        $study_complete = Db::name('study_list')->where(['status'=>2,'uid'=>$uid])->select();
        $study_begin = isset($study_complete[0]['create_time']) ? $study_complete[0]['create_time'] : time();
        $study_begin = date('Y-m-d',$study_begin);

        //学了多少天
        //$study_day_where['study_time'] = ['>',0];
        $study_day_where['uid'] = ['=',$uid];
        $study_day_where['closed'] = ['=',0];
        $study_day = Db::name('study_list')->where($study_day_where)->group('day')->count();
        //班级排名
        $class = $user['class'];
        $class_user = Db::name('User')->where($class)->field($fielde)->select();
        $class_uid = [];
        foreach($class_user as $v){
            $class_uid[] = $v['uid'];
        }
        $study_where['uid'] = ['in',$class_uid];
        $study_list = Db::name('study_list')->where($study_where)->select();
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
        //完成课程
        $study_complete = db('study_list')->where(['uid'=>$uid,'closed'=>0])->select();
        $vid_arr = [];
        foreach($study_complete as $v){
            $vid_arr[] = $v['vid'];
        }
        $video_where['id'] = ['in',$vid_arr];
        $video_count =  db('video')->where(['id'=>['in',$vid_arr],'closed'=>0])->count();
        $user = [
            'user' => $user,
            'study_complete' => $video_count,
            'study_begin' => $study_begin,
            'study_day' => $study_day,
            'ranking' => $ranking,
        ];

        return $user;
    }

    public function bindphone(){
        if($uid = session('uid')) {
            $user = $this->userInfo($uid);
            $this->assign('uid',$uid);
            $this->assign('user',$user);
            return $this->fetch();
        }
    }

    public function editpsw(){
        if($uid = session('uid')){
            $user = Db::name('user')->where('uid',$uid)->find();
            $bindurl = \think\Url::build('bindphone');
            if($user['mobile_bind'] == 0){
                return alert('您还未完善信息！请先完善个人信息',$bindurl,2,2);
            }else{
                $mobile = $user['mobile'];
                $this->assign('mobile',$mobile);

                return $this->fetch();
            }
        }else{
            return alert('您还未登录！请先登录','',2,2);
        }
    }

    //我的答疑
    public function ask(){
        $uid = session('uid');
        $type = input('type',1);
        $size = input('size',6);
        if(!$uid){
            return alert('您还未登录！请先登录','',2,2);
        }
        //提问

        $param['type'] = 1;
        $list = Db::name('ask')
            ->alias('a')
            ->where('c.closed',0)
            ->where('c.audit',1)
            ->where('a.uid',$uid)
            ->where('a.closed',0)
            ->field('a.id,a.title,c.title as ctitle,a.comments')
            ->join('gz_course c','c.cid = a.cid')
            ->paginate($size,false,['query'=>$param]);
        $page = $list->render();
        //回答
        $param['type'] = 2;
        $list_answer = Db::name('answer')
            ->alias('a')
            ->where('c.closed',0)
            ->where('c.audit',1)
            ->where('a.uid',$uid)
            ->where('a.closed',0)
            ->field('a.aid,a.content,c.title,s.title ask_title')
            ->join('gz_ask s','s.id = a.aid')
            ->join('gz_course c','c.cid = s.cid')
            ->paginate($size,false,['query'=>$param]);
        $page_answer = $list_answer->render();
        $user = $this->userInfo($uid);
        $this->assign('uid',$uid);
        $this->assign('user',$user);
        $this->assign('lists',$list);
        $this->assign('list_answer',$list_answer);
        $this->assign('page',$page);
        $this->assign('page_answer',$page_answer);
        $this->assign('type',$type);
        return $this->fetch();
    }

    public function bp(){
        $uid = session('uid');
        $user = $this->userInfo($uid);
        $this->assign('user',$user);
        $this->assign('uid',$uid);
        return $this->fetch();
    }

}
