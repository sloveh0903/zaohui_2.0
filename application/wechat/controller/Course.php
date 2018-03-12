<?php
namespace app\wechat\controller;

use think\Controller;
use think\Db;
use app\admin\model\Config;
use app\admin\model\CourseCategory;
use app\api\controller\Api;
use app\admin\model\Course as cr;

/**
 * Class Index
 * @package app\api\controller
 */
class Course extends Controller
{
    public function _initialize()
    {
        //微信sdk
        require_once EXTEND_PATH . "org/wxsdk/jssdk.php";
        $config = find('Config');
        $jssdk = new \JSSDK($config['wx_public_appid'], $config['wx_public_secret']);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage', $signPackage);
        //用户信息
        $this->assign('config', $config);
        $this->assign('userinfo', wechatLogin());

    }

    /**
     *课程详情
     */
    public function detail()
    {
        $uid = session('uid');
        $cid = input('cid', 0);
        $qrcode = db('config')->where('id', 1)->value('wxcode');//公众号关注二维码
        $is_testitemshop = show_switch('is_testitemshop'); //第一 是否开启题库显示功能
        $testitem_url = '';
        if ($is_testitemshop == 1) {
            $bank_map['closed'] = 0;
            $bank_map['audit'] = 1;
            $bank_map['course_id'] = ['=', $cid];
            $lists = db('testitem_bank')->where($bank_map)->order('course_id asc')->select();
            $testitem_count = count($lists);
            if ($testitem_count > 0) {//第二课程下 是否有题库  第三只有一个
                $bank_id = $lists[0]['id'];
                $testitem_url = '/wechat/testitembank/course_more?&cid=' . $cid;
                if ($testitem_count == 1) {
                    $testitem_url = '/wechat/testitembank/do_testitem?&bank_id=' . $bank_id;
                }
            }
        }
        $this->assign('is_testitemshop', $is_testitemshop);
        $this->assign('testitem_url', $testitem_url);
        $show_switch = db('show_switch')->where(['id' => 1])->field('is_customer,is_follow')->find();
        $this->assign('is_customer', $show_switch['is_customer']);
        $this->assign('is_follow', $show_switch['is_follow']);
        //检查是否购买
        $checkbuy = checkbuy(['uid' => $uid, 'cid' => $cid]);
        //$comment = find('Comment',['uid'=>$uid,'cid'=>$cid]);
        $this->assign('checkbuy', $checkbuy);
        //评论个数
        $have_comment = db('Comment')->where(['cid' => $cid, 'closed' => 0])->count();
        $have_comment = $have_comment ? $have_comment : 0;
        $this->assign('have_comment', $have_comment);
        //用户是否评论过
        $is_comment = db('Comment')->where(['uid' => $uid, 'cid' => $cid, 'closed' => 0])->count();
        $this->assign('is_comment', $is_comment);
        $course = new Cr();
        $title = $course->where('cid', '=', $cid)->value('title');
        //是否有试看视频
        $vidio_free = get_first_free_video($cid);
        $have_free_video_id = $vidio_free ? $vidio_free['id'] : 0;
        $this->assign('vip', isvip());
        $this->assign('have_free_video_id', $have_free_video_id);
        $this->assign('title', $title);
        $this->assign('cid', $cid);
        $this->assign('qrcode', $qrcode);
        return view('detail');
    }

    /**
     *  课程分类
     **/
    public function category()
    {
        $map = [];
        $map['closed'] = 0;
        $map['pid'] = 0;//顶级分类
        $lists = lists('CourseCategory', $map, ['orderby' => 'desc'], ['id', 'cate_name', 'pid']);
        $centcid = 0;
        if (!empty($lists)) {
            $centcid = $lists[0]['id'];
        }
        $config = new Config();
        $qrcode=$config->where('id',1)->field('wxcode,version')->find();//公众号关注二维码
        $this->assign('qrcode',$qrcode['wxcode']);
        $this->assign('version',$qrcode['version']);
        $this->assign('id', $centcid);
        $this->assign('catlists', $lists);//课程顶级分类列表
        return view('category');
    }

    /**
     * 课程评价
     */
    public function comment()
    {
        return view('comment');
    }

    /**
     * 提交评价
     */
    public function subcomment()
    {
        return view('subcomment');
    }

    /**
     * 购买成功
     */
    public function buysuccess()
    {
        $cid = input('cid');
        $this->assign('cid', $cid);
        $rebate_config = find('RebateConfig');
        if (!$rebate_config || $rebate_config['status'] != 1) {
            $url = 'http://' . $_SERVER['HTTP_HOST'] . '/wechat/course/detail?cid=' . $cid;
            $this->redirect('https://' . $url, 302);
        }
        return view();
    }

    public function index()
    {

    }

    /**
     *会员卡列表
     */
    public function card()
    {
        $user_card = lists('UserCard', [], ['mouth' => 'asc']);
        if (empty($user_card)) {
            $this->error('未设置会员卡');
        }
        $vip = isvip();
        $user = find('User', ['uid' => session('uid')]);
        $this->assign('user', $user);
        $expire_time = $user['expire_time'];
        $this->assign('expire_time', $expire_time);
        $this->assign('vip', $vip);
        $this->assign('user_card', $user_card);
        return view();
    }

    //下订单
    public function order_submit()
    {
        $cid = input('cid');
        $this->assign('cid', $cid);
        $uid = session('uid');
        $this->assign('uid', $uid);
        $rebate_config = find('RebateConfig');
        $course = db('Course')->where(['cid' => $cid])->find();
        $course['rebate_status'] = isset($rebate_config['status']) ? $rebate_config['status'] : 0;
        if ($rebate_config['status'] == '1' && $course['price'] > 0) {
            $course['discount'] = $rebate_config['discount'];
            $course['rebate_money'] = $course['price'] * ($rebate_config['first'] / 100);
            if ($course['rebate_money'] < 0.01) {
                $course['rebate_money'] = 0;
            }
            $course['rebate_money'] = sprintf("%.2f", $course['rebate_money']);
            if ($uid) {
                $user_field = 'uid,uname,face,info,realname,is_rebate,p1';
                $user = find('User', ['uid' => $uid], $user_field);
                if ($user['is_rebate'] == 1 || $user['p1'] > 0) {
                    $price = $course['price'] * $rebate_config['discount'] / 10;
                    $course['price'] = ($price >= 0.01) ? $price : 0.01;
                }
            }
        }

        $this->assign('course', $course);
        //是否购买
        $pay = checkbuy(['uid' => $uid, 'cid' => $cid]);
        if ($pay != 1) {
            $checkbuy = 0;
        } else {
            $checkbuy = 1;
        }
        $this->assign('checkbuy', $checkbuy);
//         $userinfo['openid'] = 'oktAP0Y3KPY7hsdahdui1yn1FLgE';
//         $userinfo['uname'] = '幸福的大毛毛童鞋';
//         $this->assign('userinfo',$userinfo);
        return view('');
    }


}
