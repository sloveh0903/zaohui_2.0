<?php
namespace app\component\controller;
use think\Controller;
use app\api\controller\Api;
use think\Log;

/**
 * Class Index
 * @package app\api\controller
 */
class Index extends Controller
{

    public $appid;
    public $encodingaeskey;

    public function _initialize()
    {
        $config = find('Config');
        //$appid = 'wx9512a67e83867e1b';
        $appid = $config['component_appid'];
        //$encodingaeskey = 'GBG5S72ouz97tRjHolwCR4RTTzIHHfMqiXAyW2sRgbH';
        $encodingaeskey = $config['encodingaeskey'];
        $this->appid = $appid;
        $this->encodingaeskey = $encodingaeskey;
    }

    /**
     *登录授权
     */
    public function index()
    {
        $host = input('host');
        $host = 'http://api.grazy.cn';
        $appid = $this->appid;

        //获取预授权码
        $pre_auth_code = pre_auth_code();
        $this->assign('appid', $appid);
        $this->assign('preAuthCode', $pre_auth_code);
        $this->assign('host', urlencode($host));
        return view('index');
    }

    //获取微信推送的ticket
    public function ticket()
    {
        include EXTEND_PATH . "org/crypto/wxBizMsgCrypt.php";
        $encryptMsg = file_get_contents('php://input');
        Log::write($encryptMsg, 'xml', true);
        //$arr = $this->xmlToArray($xml);

        $xml_tree = new \DOMDocument();
        $xml_tree->loadXML($encryptMsg);
        $array_e = $xml_tree->getElementsByTagName('Encrypt');
        $array_s = $xml_tree->getElementsByTagName('AppId');
        $encrypt = $array_e->item(0)->nodeValue;
        $appId = $array_s->item(0)->nodeValue;

        Log::write($encrypt, 'xml', true);
        Log::write($appId, 'appId', true);
        $token = 'weixin';
        $encodingAesKey = $this->encodingaeskey;
        $pc = new  \WXBizMsgCrypt($token, $encodingAesKey, $appId);
        $format = "<xml><ToUserName><![CDATA[toUser]]></ToUserName><Encrypt><![CDATA[%s]]></Encrypt></xml>";
        $from_xml = sprintf($format, $encrypt);
        Log::write($from_xml, 'form_xml', true);
        $msg = '';
        $msg_sign = input('msg_signature');
        $timeStamp = input('timestamp');
        $nonce = input('nonce');
        $errCode = $pc->decryptMsg($msg_sign, $timeStamp, $nonce, $from_xml, $msg);
        if ($errCode == 0) {
            $xml = new \DOMDocument();
            $xml->loadXML($msg);
            $array_e = $xml->getElementsByTagName('ComponentVerifyTicket');
            $encrypt = $array_e->item(0)->nodeValue;
            Log::write("解密后: " . $encrypt . "\n", 'ticketinfo', true);
            $data = ['component_verify_ticket' => $encrypt,];
            set_php_file('component_verify_ticket.php', json_encode($data));
            die('success');
        } else {
            Log::write($errCode, 'ticketinfo', true);
        }
    }

    //授权成功回调
    public function callback()
    {
        $host = input('host');
        $auth_code = input('auth_code'); //授权码
        $expires_in = input('expires_in');//获取时间
        if (!$host) {
            errorJson('确实host参数');
        }
        if ($auth_code) {
            $param = [
                'component_appid' => $this->appid,
                'authorization_code' => $auth_code
            ];
            $component_access_token = component_access_token();
            $url = 'https://api.weixin.qq.com/cgi-bin/component/api_query_auth?component_access_token=' . $component_access_token;
            $param = json_encode($param);
            $curl = httprequest($url, $param);
            $json_data = json_decode($curl, true);
            if (isset($json_data['errcode'])) {
                p($curl);
                die;
            }
            $authorizer_appid = $json_data['authorization_info']['authorizer_appid'];
            $authorizer_access_token = $json_data['authorization_info']['authorizer_access_token'];
            $authorizer_refresh_token = $json_data['authorization_info']['authorizer_refresh_token'];
            $func_info = $json_data['authorization_info']['func_info'];
            $data = [
                'appid' => $authorizer_appid,
                'access_token' => $authorizer_access_token,
                'refresh_token' => $authorizer_refresh_token,
                'expire' => time() + 7000,
                'func_info' => json_encode($func_info),
                'host' => urldecode($host)
            ];
            $account_wechats = find('AccountWechats', ['appid' => $authorizer_appid]);
            if ($account_wechats) {
                update('AccountWechats', ['appid' => $authorizer_appid], $data);
            } else {
                insert('AccountWechats', $data);
            }

            $param = [
                'component_appid' => $this->appid,
                'authorizer_appid' => $authorizer_appid,
            ];
            $url = 'https://api.weixin.qq.com/cgi-bin/component/api_get_authorizer_info?component_access_token=' . $component_access_token;
            $param = json_encode($param);
            $curl = httprequest($url, $param);
            $json_data = json_decode($curl, true);
            if (isset($json_data['errcode'])) {
                p($curl);
                die;
            }
            $authorizer_info = $json_data['authorizer_info'];
            $data = [
                'nick_name' => $authorizer_info['nick_name'],
                'head_img' => $authorizer_info['head_img'],
                'service_type_info' => $authorizer_info['service_type_info']['id'],
                'verify_type_info' => $authorizer_info['verify_type_info']['id'],
                'user_name' => $authorizer_info['user_name'],
                'alias' => $authorizer_info['alias'],
                'qrcode_url' => $authorizer_info['qrcode_url'],
                'business_info' => json_encode($authorizer_info['business_info']),
                'idc' => $authorizer_info['idc'],
                'principal_name' => $authorizer_info['principal_name'],
                'signature' => $authorizer_info['signature'],
            ];
            update('AccountWechats', ['appid' => $authorizer_appid], $data);
            die('ok');
        } else {
            die('error');
        }
    }

    /**
     * 微信消息事件
     */
    public function message()
    {
        die('success');
    }


    //xml转数组
    public function xmlToArray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring), true);
        return $val;
    }


}