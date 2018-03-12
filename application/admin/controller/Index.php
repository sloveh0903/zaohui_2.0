<?php
namespace app\admin\controller;

use app\admin\model\Config;
use think\Db;

class Index extends Base
{

    public function _empty()
    {
        return $this->index();
    }

    public function index()
    {
        return $this->fetch('index');
    }

    public function center()
    {
        // 财务
        $month_time = mFristAndLast(date('Y'), date('m'));
        $firstday = $month_time['firstday']; // 当月开始时间戳
        $lastday = $month_time['lastday']; // 当月结束时间戳
        $start_date = date('Y-m-d 00:00:00', time()); // 当天开始时间戳
        $start_time = strtotime($start_date);
        $end_time = (int) $start_time + 86400; // 当天结束时间戳
        $map = [];
        $map['closed'] = 0;
        $map['create_time'] = ['between',[$start_time,$end_time]];
        $order_day = Db::name('order')->where($map)->sum('pay_price'); // 今日收入
        if (! $order_day) {
            $order_day = 0;
        }
        $map = [];
        $map['closed'] = 0;
        $order = Db::name('order')->where($map)->sum('pay_price'); // 总收入
        if (! $order) {
            $order = 0;
        }
        $map = [];
        $map['closed'] = 0;
        $map['create_time'] = ['between',[$firstday,$lastday]];
        $order_month = Db::name('order')->where($map)->sum('pay_price'); // 当月收入
        if (! $order_month) {
            $order_month = 0;
        }
        $money['day'] = $order_day;
        $money['month'] = $order_month;
        $money['all'] = $order;
        $this->assign('money', $money);
        
        // 用户
        $map = [];
        $map['closed'] = 0;
        $map['create_time'] = ['between',[$start_time,$end_time]];
        $user_day = Db::name('user')->where($map)->count(); // 今日新增
        $map = [];
        $map['closed'] = 0;
        $map['create_time'] = ['between',[$firstday,$lastday]];
        $user_month = Db::name('user')->where($map)->count(); // 今月新增
        $map = [];
        $map['closed'] = 0;
        $user_all = Db::name('user')->where($map)->count(); // 总数
        $user['day'] = $user_day;
        $user['month'] = $user_month;
        $user['all'] = $user_all;
        $this->assign('user', $user);
        // 学习
        $map = [];
        $map['create_time'] = ['between',[$start_time,$end_time]];
        $study_day = Db::name('study_list')->where($map)
            ->group('uid')
            ->count(); // 今日学员
        $map = [];
        $map['create_time'] = ['between',[$firstday,$lastday]];
        $study_month = Db::name('study_list')->where($map)
            ->group('uid')
            ->count(); // 当月学员
        $map = [];
        $study_all = Db::name('study_list')->where($map)
            ->group('uid')
            ->count(); // 学员总数
        $study['day'] = $study_day;
        $study['month'] = $study_month;
        $study['all'] = $study_all;
        $this->assign('study', $study);
        // 流量与存储
        $Account = Db::name('qiniu_account')->limit(1)->find();
        $space = round($Account['space'] / 1073741824, 2);
        $this->assign('space', $space); // 存储
        $flux = round($Account['flux'] / 1073741824, 2);
        $this->assign('flux', $flux); // 流量
        //公众号小程序二维码
        $config_data = db('config')->where('id',1)->field("wxcode,wxmincode")->find();
        $config_wxcode = $config_data['wxcode'] ? $config_data['wxcode']: '/public/gzadmin/images/kong-miniprom.png';
        $config_mincode = $config_data['wxmincode'] ? $config_data['wxmincode']: '/public/gzadmin/images/kong-miniprom.png';
        $this->assign('config_wxcode', $config_wxcode);
        $this->assign('config_mincode', $config_mincode);
        // 格子匠官网的官方公告
        $officialnotice_data = $this->getOfficialnotice();
        foreach ($officialnotice_data as $key => $data) {
            if (mb_strlen($data['post_title']) > 10) {
                $officialnotice_data[$key]['post_title'] = mb_substr($data['post_title'], 0, 10, 'utf-8') . '...';
            }
        }
        $this->assign('officialnotice_data', $officialnotice_data);
        // 版本号
        $config_version = db('config')->value('version');
        $this->assign('config_version', $config_version);
        return $this->fetch();
    }

    /* 格子匠官网的官方公告 */
    private function getOfficialnotice()
    {
        $api_url = 'http://www.grazy.cn/portal/index/announcement';
        $curl_data = doCurlGetRequest($api_url);
        $curl_data = json_decode($curl_data, true);
        return $curl_data;
    }

   

    public function norule()
    {
        return $this->fetch();
    }

    public function config()
    {
        $config = new Config();
        $webInfo = $config->limit(1)->find();
        $this->assign('web', $webInfo);
        if (request()->isPost()) {
            $param = input('post.');
            $param['introduce'] = strip_tags($param['introduce']);
            $where['id'] = $param['id'];
            $flag = $config->UpdateData($param, $where);
            return json([
                'status' => $flag['status'],
                'url' => 'reload',
                'data' => '',
                'msg' => $flag['msg']
            ]);
        }
        return $this->fetch();
    }

    /**
     * 后台登录设置 微信手机号码验证 pc手机号码验证 开关
     *
     * @return \think\response\Json|mixed|string
     */
    public function setlogin()
    {
        $config_data = db('show_switch')->field('is_pcmobile,is_wxmobile')->find();
        $is_pcmobile = $config_data['is_pcmobile'];
        $is_wxmobile = $config_data['is_wxmobile'];
        $is_pcswitch = input('pcmobileswitch');
        $is_mbswitch = input('wxmobileswitch');
        if (request()->isPost()) {
            if (isset($is_pcswitch)) {
                $switch_is_pcmobile = $is_pcmobile == 1 ? 0 : 1;
                update_show_switch('is_pcmobile', $switch_is_pcmobile);
            }
            if (isset($is_mbswitch)) {
                $switch_is_wxmobile = $is_wxmobile == 1 ? 0 : 1;
                update_show_switch('is_wxmobile', $switch_is_wxmobile);
            }
            return json([
                'status' => 200,
                'data' => '',
                'msg' => '操作成功'
            ]);
        }
        $this->assign('is_pcmobile', $is_pcmobile);
        $this->assign('is_wxmobile', $is_wxmobile);
        return $this->fetch();
    }
}