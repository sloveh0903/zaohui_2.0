<?php
namespace app\admin\controller;

use think\Log;
use think\Db;

class Operate extends Base
{

    /**
     * 公众号配置
     */
    public function index()
    {
        $config = find('Config');
        if (request()->isPost()) {
            $business_file = input('business_file');
            $wx_public_appid = input('wx_public_appid');
            $wx_public_secret = input('wx_public_secret');
            $wx_public_mchid = input('wx_public_mchid');
            $wx_public_appkey = input('wx_public_appkey');
            if ($business_file) {
                $myfile = fopen("MP_verify_" . $business_file . ".txt", "w") or json([
                    'status' => '-200',
                    'url' => '/admin/operate/index',
                    'data' => '',
                    'msg' => '写入js域名文件失败'
                ]);
                fwrite($myfile, $business_file);
                fclose($myfile);
            }
            $data = [
                'wx_public_appid' => trim($wx_public_appid),
                'wx_public_secret' => trim($wx_public_secret),
                'wx_public_mchid' => trim($wx_public_mchid),
                'wx_public_appkey' => trim($wx_public_appkey),
                'business_file' => trim($business_file)
            ];
            update('Config', [
                'id' => 1
            ], $data);
            // 生成公众号二维码图片 保存config表中
            $this->getWxcode();
            return json([
                'status' => '200',
                'url' => '/admin/operate/index',
                'data' => '',
                'msg' => '设置成功'
            ]);
        }
        $this->assign('config', $config);
        return view();
    }

    /**
     * 小程序配置
     */
    public function applet()
    {
        $config = find('Config');
        if (request()->isPost()) {
            $wx_appid = input('wx_appid');
            $wx_secret = input('wx_secret');
            $wx_mchid = input('wx_mchid');
            $wx_appkey = input('wx_appkey');
            $data = [
                'wx_appid' => trim($wx_appid),
                'wx_secret' => trim($wx_secret),
                'wx_mchid' => trim($wx_mchid),
                'wx_appkey' => trim($wx_appkey)
            ];
            update('Config', [
                'id' => 1
            ], $data);
            // 生成小程序二维码图片 保存config表中
            $this->getWxmincode();
            return json([
                'status' => '200',
                'url' => '/admin/operate/applet',
                'data' => '',
                'msg' => '设置成功'
            ]);
        }
        $this->assign('config', $config);
        return view();
    }

    /**
     * 小程序多客服开关
     */
    public function customer()
    {
        $is_mini_customer = show_switch('is_mini_customer');
        $switch = input('switch');
        if (request()->isPost()) {
            $switch_mini_customer = $is_mini_customer == 1 ? 0 : 1;
            $is_update = update_show_switch('is_mini_customer', $switch_mini_customer);
            if ($is_update) {
                return json([
                    'status' => 200,
                    'data' => '',
                    'msg' => '操作成功'
                ]);
            } else {
                return json([
                    'status' => - 200,
                    'data' => '',
                    'msg' => '操作失败'
                ]);
            }
        }
        $this->assign('is_mini_customer', $is_mini_customer);
        return view();
    }

    /**
     * 开放平台配置
     */
    public function openplatform()
    {
        $config = find('Config');
        if (request()->isPost()) {
            $wx_open_appid = trim(input('wx_open_appid'));
            $wx_open_secret = trim(input('wx_open_secret'));
            $data = [
                'wx_open_appid' => $wx_open_appid,
                'wx_open_secret' => $wx_open_secret
            ];
            update('Config', [
                'id' => 1
            ], $data);
            return json([
                'status' => '200',
                'url' => '/admin/operate/openplatform',
                'data' => '',
                'msg' => '设置成功'
            ]);
        }
        $this->assign('config', $config);
        return view();
    }

    /**
     * 关注回复
     */
    public function subscribereply()
    {
        $data = find('WechatReply');

        /*$config = Db::name('config')->where(array('id'=>'1'))->field('wx_public_appid,wx_public_secret')->find();
        $result = load_wechat('Media',array('appid'=>$config['wx_public_appid'],'appsecret'=>$config['wx_public_secret']))->getForeverList('image',0,20);
        Log::write($result);
        die();*/

        if (request()->isPost()) {
            $param = input('post.');
            if ($param['msgtype'] == 1) {
                // 回复图片
                if ($param['photopath'] == '') {
                    return json([
                        'status' => '-200',
                        'url' => '',
                        'data' => '',
                        'msg' => '图片不能为空'
                    ]);
                }
                $filesize = filesize($_SERVER['DOCUMENT_ROOT'] . $param['photopath']);
                if ($filesize > 2097152) {
                    return json([
                        'status' => '-200',
                        'url' => '',
                        'data' => '',
                        'msg' => '图片不能大于2M'
                    ]);
                }
                $file_info = array(
                    'media' => "@{$_SERVER['DOCUMENT_ROOT']}{$param['photopath']}"
                );
                $config = Db::name('config')->where(array('id'=>'1'))->field('wx_public_appid,wx_public_secret')->find();
                if($data['photopath'] != $param['photopath']){
                    $result = load_wechat('Media',array('appid'=>$config['wx_public_appid'],'appsecret'=>$config['wx_public_secret']))->uploadForeverMedia($file_info,'image');
                    if(isset($result['media_id'])){
                        load_wechat('Media',array('appid'=>$config['wx_public_appid'],'appsecret'=>$config['wx_public_secret']))->delForeverMedia($data['mediaid']);
                        $param['mediaid'] = $result['media_id'];
                        $param['url'] = $result['url'];
                    }
                }
            }
            unset($param['file']);
            update('WechatReply', [
                'id' => 1
            ], $param);
            return json([
                'status' => '200',
                'url' => '/admin/operate/subscribereply',
                'data' => '',
                'msg' => '设置成功'
            ]);
        }
        $this->assign('data', $data);
        return view();
    }

    public function follow()
    {
        $is_follow = show_switch('is_follow');
        $is_followswitch = input('is_follow');
        if (request()->isPost()) {
            if (isset($is_followswitch)) {
                update_show_switch('is_follow', $is_followswitch);
            }
            return json([
                'status' => 200,
                'data' => '',
                'msg' => '操作成功'
            ]);
        }
        $this->assign('is_follow', $is_follow);
        return $this->fetch();
    }

    /**
     * 小程序二维码
     */
    private function getWxmincode()
    {
        $img = '';
        $getMinAccessToken = getMinAccessToken();
        if ($getMinAccessToken != '') {
            $url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token=' . $getMinAccessToken['access_token'];
            $param = [
                'path' => 'pages/index/index',
                'width' => 430
            ];
            $result = doCurlPostRequest($url, json_encode($param));
            
            if (isset($result['errcode'])) {
                return '';
            }
            $basedir = ROOT_PATH . 'public/image/';
            $img_name = 'mincode.png';
            $mincode_path = $basedir . $img_name;
            fopen($mincode_path, "w");
            file_put_contents($mincode_path, $result);
            $mincode_imgpath = '/public/image/' . $img_name;
            db('config')->where('id', '=', 1)->update([
                'wxmincode' => $mincode_imgpath
            ]);
            $img = 'http://' . $_SERVER["HTTP_HOST"] . '/public/image/' . $img_name;
        }
        return $img;
    }

    /**
     * 微信公众号关注二维码
     *
     * @return string
     */
    private function getWxcode()
    {
        $img = '';
        $getAccessToken = getAccessToken();
        if ($getAccessToken) {
            $token = $getAccessToken['access_token'];
            $qrcode_url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $token;
            $data = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "wechat"}}}';
            $curl = doCurlPostRequest($qrcode_url, $data);
            $json_curl = json_decode($curl, true);
            if (isset($json_curl['errcode']) && $json_curl['errcode'] == 40001) {
                return $img;
            }
            if (isset($json_curl['ticket'])) {
                $ticket = $json_curl['ticket'];
                if ($ticket) {
                    $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $ticket;
                    $url_img = file_get_contents($url);
                    $basedir = ROOT_PATH . 'public/image/';
                    $img_name = 'wxcode.png';
                    $code_path = $basedir . $img_name;
                    fopen($code_path, "w");
                    file_put_contents($code_path, $url_img);
                    $code_imgpath = '/public/image/' . $img_name;
                    db('config')->where('id', '=', 1)->update([
                        'wxcode' => $code_imgpath
                    ]);
                    $img = 'http://' . $_SERVER["HTTP_HOST"] . '/public/image/' . $img_name;
                }
            }

        }
        return $img;
    }
}