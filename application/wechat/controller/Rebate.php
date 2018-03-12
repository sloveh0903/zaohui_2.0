<?php
namespace app\wechat\controller;
use think\Controller;
use app\api\controller\Api;
use think\Db;

/**
 * Class Rebate 分销
 * @package app\api\controller
 */
class Rebate extends Controller
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
        //是否开启分销
        $rebate_config = find('RebateConfig');
//        if(!$rebate_config || $rebate_config['status'] != 1){
//            errorJson('分销已关闭');
//        }

    }

    //是否分销员
    public function isrebate(){
        $uid = session('user_id');
        $user = find('User',['uid'=>$uid]);
        if(!$user || $user['is_rebate'] != 1){
           exit;
        }
    }

    /**
     * 分销近况
     * Create By Xenos
     * Return
     */
    public function index(){
        $this->isrebate();
        return view('index');
    }

    /**
     * 数据页面
     * Create By Xenos
     * Return
     */
    public function record(){
        $this->isrebate();
        return view('record');
    }

    /**
     * 分销管理
     * Create By Xenos
     * Return
     */
    public function manage(){
        $this->isrebate();
        $phone = Db::name('Config')->value('phone');
        $this->assign('phone',$phone);
        return view('manage');
    }

    /**
     *订单
     */
    public function order()
    {
        $type = input('type',1);
        switch ($type){
            case "1":
                $title = "全部订单";
                break;
            case "2":
                $title = "直销订单";
                break;
            case "3":
                $title = "下级订单";
                break;
            case "4":
                $title = "下2级订单";
                break;
        }
        $this->assign('type',$type);
        $this->assign('title',$title);
        return view('order');
    }

    /**
     * 提现记录
     * Create By Xenos
     * Return
     */
    public function withdraw(){
        return view('withdraw');
    }

    /**
     * 绑定账号
     * Create By Xenos
     * Return
     */
    public function bind_account(){
        $uid = session('uid');
        $user = Db::name('user')->where(array('uid'=>$uid,'closed'=>'0'))->find();
        $this->assign('user',$user);
        return view('bind_account');
    }

    /**
     * 绑定支付宝
     * Create By Xenos
     * Return
     */
    public function bind_alipay(){
        $uid = session('uid');
        $user = Db::name('user')->where(array('uid'=>$uid,'closed'=>'0'))->find();
        $this->assign('user',$user);
        return view();
    }

    /**
     * 提现
     * Create By Xenos
     * Return
     */
    public function draw_apply(){
        return view();
    }

    /**
     * 下级推广员
     * Create By Xenos
     * Return
     */
    public function promoter(){
        $type = input('type',1); //1 下级  2下二级
        $this->assign('type',$type);
        return view();
    }

    /**
     * 分销规则
     * Create By Xenos
     * Return
     */
    public function rule(){
        return view();
    }

    /**
     * 分销课程
     */
    public function sharecourse(){
        $uid = session('user_id');
        $user = find('User',['uid'=>$uid]);
        $this->assign('uname',$user['uname']);
        return view('sharecourse');
    }

    /**
     * 修改手机号码
     * Create By Xenos
     * Return
     */
    public function updatePhone(){
        return view();
    }

    /**
     * 修改支付宝账号
     * Create By Xenos
     * Return
     */
    public function updateAlipay(){
        return view();
    }

    /**
     * 分享链接
     */
    public function sharelink(){
        $event = input('event');
        $cid = input('cid');
        $uid = session('user_id');
        if($event == 'subscribe'){
            update('User',['uid'=>$uid],['subscribe'=>1]);
        }
        if($event == 'unsubscribe'){
            update('User',['uid'=>$uid],['subscribe'=>0]);
        }
        $course = find('Course',['cid'=>$cid]);
        if(!$course){
            errorJson('课程不存在');
        }
        //被邀请人
        $user = find('User',['uid'=>$uid]);
        //推广员信息
        $parent_uname = input('uname','');
        if(!empty($parent_uname)){
            addDistribution($user,$parent_uname);
            $parent_user = find('User',['uname'=>$parent_uname]);
        }else{
            $parent_user = $user;
        }

        //课程信息
        $checkbuy = checkbuy(['uid'=>$uid,'cid'=>$cid]);
        $rebate_config = find('RebateConfig');
        $course['share_price'] = $course['price']; //分享价格
        $course['discount'] = 10;
        if($rebate_config && $rebate_config['status'] == 1){
            if($course['price'] > 0){
                $course['share_price'] = $course['price']*($rebate_config['discount']/10);
                if($course['share_price'] <= 0.01){
                    $course['share_price'] = 0.01;
                }
                $course['share_price'] = sprintf("%.2f", $course['share_price']);
            }
            $course['discount'] = $rebate_config['discount'];
        }
        $getAccessToken =  getAccessToken();
        $token = $getAccessToken['access_token'];
        $qrcode_url ='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token;
        $key = '0_'.$cid;
        $data = '{"expire_seconds": 604800, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$key.'"}}}';
        $curl = doCurlPostRequest($qrcode_url,$data);
        $json_curl = json_decode($curl,true);
        $ticket = $json_curl['ticket'];
        $qrcode = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
        $this->assign('cid',$cid);
        $this->assign('qrcode',$qrcode);
        $this->assign('course',$course);
        $this->assign('checkbuy',$checkbuy);
        $this->assign('parent_user',$parent_user);
        return view('sharelink');
    }

    /**
     * 分享海报
     */
    public function shareposter(){
        $cid = input('cid');
        $uid = session('user_id');
        $this->assign('cid',$cid);
        $poster = apiget('course/poster',['cid'=>$cid,'uid'=>$uid,'poster_type'=>1]);
        $this->assign('poster_img',$poster['data']);
        return view('shareposter');

    }


}
