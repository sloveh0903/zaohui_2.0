<?php
namespace app\api\controller;
use think\Config;
use think\Controller;
use think\Loader;
use think\Session;
use think\Log;
use think\Db;
use think\Cache;
use app\admin\model\Config as cofg;
/**
 * Class Wechat 微信
 * @package app\api\controller
 */
class Wechat extends Controller
{


    /**
     * 微信小程序登录凭证
     */
    public function getOnLogin(){
        $code = input('code');
        $config = find('Config');
        $appid = $config['wx_appid'];
        $secret = $config['wx_secret'];
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
        $data = json_decode(doCurlGetRequest($url),true);
        if(!isset($data['session_key']) || !isset($data['openid'])){
            errorJson('操作失败');
        }
        $session_key =  $data['session_key'];
        $openid =  $data['openid'];
        $result = [
            'openid' => $openid,
            'sessionKey' => $session_key,
        ];
        successJson('操作成功',$result);
    }

    //微信小程序用户信息
    function postUserInfo(){
        Loader::import('org.wxsign.wxBizDataCrypt');
        $nickname = input('nickname','');
        $openid = input('openid','');
        $sex = input('sex',0);
        $face = input('face','');
        $encryptedData = input('encryptedData');
        $sessionKey = input('sessionKey');
        $iv = input('iv');
        $config = find('Config');
        $appid = $config['wx_appid'];
        $pc = new \WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encryptedData, $iv );
        if(!isset($errCode['unionId']) || empty($errCode['unionId'])){
            $errCode['unionId'] = $openid;
        }
        $unionId = $errCode['unionId'];
        if(empty($openid)){
            errorJson('openid为空');
        }
        $param = [
            'openid'=>$openid,
            'unionid'=>$unionId,
            'headimgurl'=>$face,
            'nickname'=>$nickname,
            'sex'=>$sex,
            'type'=>2
        ];
        $result = createUser($param);
        $is_wxmobile = show_switch('is_wxmobile');
        if($is_wxmobile && !$result['mobile']){
            //手机为空和开启绑定开关   判断是否已绑定
            $result['is_bind'] = 'no';
        }else{
            $result['is_bind'] = 'yes';
        }
        successJson('操作成功',$result);
    }

    public function getCheckbind(){
        $uid = input('uid');
        $result = find('User',['uid'=>$uid],'uid,mobile,audit');
        $is_wxmobile = show_switch('is_wxmobile');
        if($is_wxmobile && !$result['mobile']){
            //手机为空和开启绑定开关   判断是否已绑定
            $result['is_bind'] = 'no';
        }else{
            $result['is_bind'] = 'yes';
        }
        successJson('操作成功',$result);
    }


    /**
     * 微信扫码登陆
     */
    public function getScanlogin(){
        header("Content-Type: text/html;charset=utf-8");
        $backurl = urldecode(input('backurl'));
        $code = input('code');
        $config = find('Config');
        $appid = $config['wx_open_appid'];
        $secret = $config['wx_open_secret'];
        $url =  "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
        $data = json_decode(doCurlGetRequest($url),true);
        if(isset($data['errcode'])){
            p($data);exit;
        }
        $openid = $data['openid'];
        $access_token = $data['access_token'];
        //获取用户基本信息
        $url='https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
        $data = json_decode(doCurlGetRequest($url),true);
        if(isset($data['errcode'])){
            p($data);exit;
        }
        if(!isset($data['unionid'])){
            $data['unionid'] = $openid;
        }
        $unionid = $data['unionid'];
        $param = [
            'openid'=>$openid,
            'unionid'=>$unionid,
            'headimgurl'=>$data['headimgurl'],
            'nickname'=>$data['nickname'],
            'sex'=>$data['sex'],
            'type'=>3
        ];
        $result = createUser($param);
        $uid = $result['uid'];
        $is_pcmobile = show_switch('is_pcmobile');
        if($is_pcmobile && !$result['mobile']){
            session('uid', null);
            $result['is_bind'] = 'no';
        }else{
            $result['is_bind'] = 'yes';
        }
        if($result['audit']){
            session('audit', 'yes');
        }else{
            session('audit', 'no');
        }
        session('is_bind', $result['is_bind']);

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
        session('msg_num',$message_num);
        if(is_HTTPS()){
            $this->redirect('https://'.$backurl,302);
        }else{
            $this->redirect('http://'.$backurl,302);
        }

    }

    /**
     * 发送消息模板
     */
    public function sendMessageTemplate($param = ['template_data'=>'','template_id'=>'','openid'=>'']){
        $template_data = $param['template_data']; //模板显示数据
        $template_id = $param['template_id']; //模板的id
        $openid = $param['openid'];           //接收者openid
        $getAccessToken = getAccessToken(); //获取access_token
        if(isset($getAccessToken['access_token'])){
            $ACCESS_TOKEN = $getAccessToken['access_token'];
            $template=array(
                'touser'=>$openid,
                'template_id'=>$template_id,
                'data'=>json_decode($template_data,true)
            );
            $json_template = json_encode($template);
            $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$ACCESS_TOKEN";
            httprequest($url,$json_template);
        }
    }

    /**
     * 获取模板列表
     */
    public function getTemplateList(){
        $getAccessToken = getAccessToken(); //获取access_token
        if(isset($getAccessToken['access_token'])){
            $ACCESS_TOKEN = $getAccessToken['access_token'];
            $url = "https://api.weixin.qq.com/cgi-bin/template/get_all_private_template?access_token=$ACCESS_TOKEN";
            $res = httprequest($url);
            $data = json_decode($res,true);
            return $data['template_list'];
        }
    }

    /**
     * 支付模板通知
     */
    public function getPaytemp(){
        $unionid = session('unionid');
        $order_id = input('order_id');
        $user  = find('User',['unionid'=>$unionid]);
        $order = find('Order',['id'=>$order_id]);
        if($user && $order){
            $openid = $user['service_openid'];
            if(!empty($openid)){
                $template_data  = array(
                    'first'=>array(
                        'value'=>'',
                        'color'=>'#173177'
                    ),
                    'keyword1'=>array(
                        'value'=>$order['course_name'],
                        'color'=>'#173177'
                    ),
                    'keyword2'=>array(
                        'value'=>'课程',
                        'color'=>'#173177'
                    ),
                    'keyword3'=>array(
                        'value'=>$order['pay_price'].'元',
                        'color'=>'#173177'
                    ),
                    'keyword4'=>array(
                        'value'=>date('Y-m-d'),
                        'color'=>'#173177'
                    ),
                    'keyword5'=>array(
                        'value'=>$user['nickname'],
                        'color'=>'#173177'
                    ),
                    'remark'=>array(
                        'value'=>'',
                        'color'=>'#173177'
                    )
                );
                $this->template($template_data,'费用支出通知');
            }
        }
    }

    /**
     * 课程状态模板通知
     */
    public function getCourseStateTemp()
    {
        $title = input('title','');
        $date = input('date',date('Y-m-d'));
        $unionid = session('unionid');
        $user = find('User', ['unionid' => $unionid]);
        if($user){
            $template_data = array(
                'userName' => array(
                    'value' => $user['nickname'],
                    'color' => '#173177'
                ),
                'courseName' => array(
                    'value' => $title,
                    'color' => '#173177'
                ),
                'date' => array(
                    'value' => $date,
                    'color' => '#173177'
                ),
                'courseState' => array(
                    'value' => '开课了',
                    'color' => '#173177'
                ),
                'remark' => array(
                    'value' => '',
                    'color' => '#173177'
                ),
            );
           $this->template($template_data,'课程状态变化通知');
        }
    }

    /**
     * 获取模板id
     * @param array $template_data
     * @param string $title
     */
    public function template($template_data = [],$title = '' ,$primary_industry = '教育'){
        $openid = session('openid');
        $template_data = json_encode($template_data);
        $template_list = $this->getTemplateList('wechat/templateList');
        foreach ($template_list as $v) {
            if ($v['title'] == $title && $v['primary_industry'] == $primary_industry) {
                $template_id = $v['template_id'];
                $this->sendMessageTemplate(['template_data' => $template_data, 'template_id' => $template_id, 'openid' => $openid]);
                break;
            }
        }
    }

    /**
     * 小程序服务器接入
     */
    public function  postDevprofile(){
        //echo $_GET['echostr'];
        $json_data = file_get_contents("php://input");
       // Log::write($json_data,'jsondata1',true);
        $arr_data = json_decode($json_data,true);
        $fromUsername = $arr_data['FromUserName'];
        $toUsername = $arr_data['ToUserName'];
        /**
         * 小程序客服消息
         */
        $textTpl = "<xml>
                 <ToUserName><![CDATA[%s]]></ToUserName>
                 <FromUserName><![CDATA[%s]]></FromUserName>
                 <CreateTime>%s</CreateTime>
                 <MsgType><![CDATA[transfer_customer_service]]></MsgType>
            </xml>";
        //Log::write($json_data,'xmldata1',true);
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time());
        echo $resultStr;die;

    }

    /**
     * 接入服务器配置
     */
    public function getDistribution(){
        $echostr = input('echostr');
        if($echostr){
            echo $echostr;
        }
    }

    /**
     * 接入服务器配置
     */
    public function postDistribution(){
        $xml = file_get_contents("php://input");
        //Log::write($xml,'xmldata',true);
        $postObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json_data = @json_encode($postObj);
        $arr_data = @json_decode($json_data,true);
        $fromUsername = $arr_data['FromUserName'];
        $toUsername = $arr_data['ToUserName'];
        $textTpl = "<xml>
                      <ToUserName><![CDATA[%s]]></ToUserName>
                      <FromUserName><![CDATA[%s]]></FromUserName>
                      <CreateTime>%s</CreateTime>
                      <MsgType><![CDATA[%s]]></MsgType>
                      <Content><![CDATA[%s]]></Content>
                    </xml>";
        $imageTpl = "<xml><ToUserName><![CDATA[%s]]></ToUserName><FromUserName><![CDATA[%s]]></FromUserName><CreateTime>%s</CreateTime><MsgType><![CDATA[%s]]></MsgType><Image><MediaId>%s</MediaId></Image></xml>";


        //关键词回复
        if(isset($arr_data['Content'])){
            $keywords = $arr_data['Content'];
            //精确搜索
            $wechat_keywords = find('WechatKeywords',['type'=>1,'keywords'=>$keywords]);
            if($wechat_keywords){
                if($wechat_keywords['msgtype'] == '0'){
                    $content = str_replace("</p><p>","\n",$this->_strip_tags(['p'],$wechat_keywords['content']));
                    $content = str_replace('&nbsp;',' ',$content);
                    $content = preg_replace("/<p.*?>|<\/p>/is","", $content);
                    $xml = sprintf($textTpl, $fromUsername, $toUsername, time(), 'text',"{$content}");
                    exit($xml);
                }else{
                    $xml = sprintf($imageTpl, $fromUsername, $toUsername, time(),'image', $wechat_keywords['mediaid']);
                    exit($xml);
                }

            }
            //模糊搜索
            $keywords_where['keywords'] = ['like','%'.$keywords.'%'];
            $keywords_where['type'] = 2;
            $wechat_keywords = find('WechatKeywords',$keywords_where);
            if($wechat_keywords) {
                if ($wechat_keywords['msgtype'] == '0') {
                    $content = str_replace("</p><p>", "\n", $this->_strip_tags(['p'],$wechat_keywords['content']));
                    $content = str_replace('&nbsp;', ' ', $content);
                    $content = preg_replace("/<p.*?>|<\/p>/is", "", $content);
                    $xml = sprintf($textTpl, $fromUsername, $toUsername, time(), 'text', "{$content}");
                    exit($xml);
                } else {
                    $xml = sprintf($imageTpl, $fromUsername, $toUsername, time(), 'image', $wechat_keywords['mediaid']);
                    exit($xml);
                }
            }
        }
        //查询后台是否开启多客服功能
        $is_customer = show_switch('is_customer');
        if($is_customer)
        {
            //公众号客服消息
            if(isset($arr_data['Content']) && $arr_data['Content'] == '客服'){
                $textTpl = "<xml>
                     <ToUserName><![CDATA[%s]]></ToUserName>
                     <FromUserName><![CDATA[%s]]></FromUserName>
                     <CreateTime>%s</CreateTime>
                     <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                </xml>";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time());
                exit($resultStr);
            }
        }
        //click 事件文字
        
        if(isset($arr_data['Event']) && $arr_data['Event'] == 'CLICK'){
            $menu_key_arr = db::name('custom_menu')->where('type', 'click')->column('key');
            if(in_array($arr_data['EventKey'], $menu_key_arr)){
                $menutext_content = db::name('custom_menu')->where('key', $arr_data['EventKey'])->value('content');
                if($menutext_content){
                    $xml = sprintf($textTpl, $fromUsername, $toUsername, time(), 'text',"{$menutext_content}");
                    return $xml;
                }
                
            }
        }

        //我的用户信息
        $myuser = find('User',['service_openid'=>$fromUsername]);

        //关注取消
        if(isset($arr_data['Event']) && $myuser){
            if($arr_data['Event'] == 'subscribe'){
                update('User',['service_openid'=>$fromUsername],['subscribe'=>1]);
                $WechatReply = find('WechatReply');
                if($WechatReply['msgtype'] == '1'){
                    $xml = sprintf($imageTpl, $fromUsername, $toUsername, time(),'image', $WechatReply['mediaid']);
                }else{
                    $content = str_replace("</p><p>","\n",$this->_strip_tags(['p'],$WechatReply['content']));
                    $content = str_replace('&nbsp;',' ',$content);
                    $content = str_replace('<br/>', "\n", $content);
                    $content = preg_replace("/<p.*?>|<\/p>/is","", $content);
                    $xml = sprintf($textTpl, $fromUsername, $toUsername, time(), 'text',"{$content}");
                }
                return $xml;
            }
            if($arr_data['Event'] == 'unsubscribe' ){
                update('User',['service_openid'=>$fromUsername],['subscribe'=>0]);
            }
        }

        //二维码
        if(!empty($arr_data)  && isset($arr_data['EventKey']) && !empty($arr_data['EventKey'])){
            $eventKey = $arr_data['EventKey'];
            $keyArray = @explode("_", $eventKey);
            //pc登录二维码
            if(count($keyArray)>1) {
                if($keyArray[1] == 'pclogin' || $keyArray[0] == 'pclogin'){
                    //二维码参数code值
                    if ($keyArray[0] == 'qrscene') {
                        $code = $keyArray[2];
                    } else {
                        $code = $keyArray[1];
                    }
                    //获取用户详细信息
                    $getAccessToken = getAccessToken();
                    $basic_access_token = $getAccessToken['access_token'];
                    $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $basic_access_token . '&openid=' . $fromUsername . '&lang=zh_CN';
                    $json_data = doCurlGetRequest($url);
                    $data = json_decode($json_data, true);
                    if(isset($data['errcode'])) die;
                    if(!isset($data['unionid'])){
                        $data['unionid'] = $fromUsername;
                    }
                    $unionid = $data['unionid'];
                    //生成用户
                    $param = [
                        'openid'=>$fromUsername,
                        'unionid'=>$unionid,
                        'headimgurl'=>$data['headimgurl'],
                        'nickname'=>$data['nickname'],
                        'sex'=>$data['sex'],
                        'type'=>1
                    ];
                    Cache::set($code,json_encode($param),3600);
                    exit();
                }
            }

            //分享的二维码
            if(count($keyArray)>=3){
                if ($keyArray[0] == 'qrscene'){   //已关注者扫描
                    $uid = $keyArray[1];          //推荐人id
                    $cid = $keyArray[2];          //课程id
                    $type = $keyArray[3];         //1推荐 0分销
                }else{                            //未关注者关注后推送事件
                    $uid = $keyArray[0];
                    $cid = $keyArray[1];
                    $type = $keyArray[2];
                }
                $user = find('User',['uid'=>$uid]);
                //获取用户详细信息
                $getAccessToken = getAccessToken();
                $basic_access_token = $getAccessToken['access_token'];
                $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $basic_access_token . '&openid=' . $fromUsername . '&lang=zh_CN';
                $json_data = doCurlGetRequest($url);
                $data = json_decode($json_data, true);
                if(isset($data['errcode'])){
                    p($json_data);die;
                }
                if(!isset($data['unionid'])){
                    $data['unionid'] = $fromUsername;
                }
                $unionid = $data['unionid'];
                //生成用户
                $param = [
                    'openid'=>$fromUsername,
                    'unionid'=>$unionid,
                    'headimgurl'=>$data['headimgurl'],
                    'nickname'=>$data['nickname'],
                    'sex'=>$data['sex'],
                    'type'=>1
                ];
                $result = createUser($param);
                $subscribe = $data['subscribe'];
                $myuser = find('user',['uid'=>$result['uid']]);
                addDistribution($myuser,$user['uname']);
                update('User',['uid'=>$result['uid']],['subscribe'=>$subscribe]);
               // Log::write($result,'userinfo',true);
                if($type == 0){
                    //分销推送
                    $fromname = $user['uname'];
                    $course = find('Course',['cid'=>$cid]);
                    $title = $course['title'];
                    $course_url = 'http://'.$_SERVER['HTTP_HOST'].'/wechat/rebate/sharelink.html?uname='.$fromname.'&cid='.$cid.'&event='.$arr_data['Event'];
                    if($uid == 0){
                        $contentStr = "《".$title."》购买成功".'<a href="'.$course_url.'">点击了解</a>';
                    }else{
                        $contentStr = "欢迎一起学习《".$title."》好友推荐一起学习可享受更优惠的价格".'<a href="'.$course_url.'">点击了解</a>';
                    }
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), 'text', $contentStr);
                    exit($resultStr);
                }else{
                    //邀请增加虚拟币
                    $task = find('Task');
                    $taskRule = find('TaskRule');
                    $taskUser = find('TaskUser',['uid'=>$uid,'be_invite'=>$result['uid']]);
                    if($task['audit'] == 1 && $taskRule['coin'] > 0 && !$myuser){
                        if(!$taskUser){
                            $data = [
                                'uid'=>$uid,
                                'face'=>$user['face'],
                                'nickname'=>$user['nickname'],
                                'be_invite'=>$result['uid'],
                                'be_face'=>$result['face'],
                                'be_nickname'=>$result['nickname'],
                            ];
                            insert('TaskUser',$data);
                        }
                        $counts = counts('TaskUser',['uid'=>$uid,'status'=>0]);
                        $taskRule['is_repeat'];
                        if($taskRule['is_repeat'] == 0){
                            $taskReward = find('TaskReward',['uid'=>$uid]);
                            if(!$taskReward && $counts == $taskRule['nums']){
                                insert('TaskReward',['uid'=>$uid,'nums'=>$counts,'coin'=>$taskRule['coin']]);
                                update('TaskUser',['uid'=>$uid],['status'=>1]);
                                setInc('User',['uid'=>$uid],'coin',$taskRule['coin']);
                            }
                            if($taskReward){
                                update('TaskUser',['uid'=>$uid],['status'=>1]);
                            }
                        }else{
                            if($counts == $taskRule['nums']){
                                insert('TaskReward',['uid'=>$uid,'nums'=>$counts,'coin'=>$taskRule['coin']]);
                                update('TaskUser',['uid'=>$uid],['status'=>1]);
                                setInc('User',['uid'=>$uid],'coin',$taskRule['coin']);
                            }
                        }
                    }
                    $content = $taskRule['push_content'];
                    $url = 'http://'.$_SERVER['HTTP_HOST'].'/wechat';
                    $contentStr = '<a href="'.$url.'">'.$content.'</a>';
                    //Log::write($contentStr,'contentStr',true);
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, time(), 'text', $contentStr);
                    exit($resultStr);
                }
            }
        }

     
       
        exit();
    }



    /**
     * 创建自定义菜单
     */
    public function getCreateMenu(){
        die;
        $getAccessToken = getAccessToken(); //获取access_token
        $index = 'http://'.$_SERVER['HTTP_HOST'].'/wechat';
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$getAccessToken['access_token'];
        $jsonmenu = '{
                      "button":[
                          {
                           "type":"view",
                           "name":"学习课程",
                           "url":"'.$index.'"
                          ]
                      }';
        $curl = httprequest($url,$jsonmenu);
//        p($curl);
//        die;
        $data = [
            'button'=>[
                0=>[
                    'type'=>'view',
                    'name'=>urlencode('申请体验'),
                    'url'=>'http://m.grazy.cn/',
                ],
                1=>[
                    'name'=>urlencode('格子课堂'),
                    'sub_button'=>[
//                        0=>[
//                            'type'=>'view_limited',
//                            'name'=>urlencode('优质案例'),
//                            'media_id'=>'i3bKGPnMuQttD8CCuPYcswU4Nyh6qhemujaPTR6Aku4',
//                        ],
//                        1=>[
//                            'type'=>'view_limited',
//                            'name'=>urlencode('常见问题'),
//                            'media_id'=>'i3bKGPnMuQttD8CCuPYcsz3GdIn-sBlbIgQhpfBcoBA',
//                        ],
                        0=>[
                            'type'=>'view',
                            'name'=>urlencode('联系我们'),
                            'url'=>'http://m.grazy.cn/',
                        ],
                    ]
                ],
                2=>[
                    'name'=>urlencode('我的格子'),
                    'sub_button'=>[
                        0=>[
                            'type'=>'view',
                            'name'=>'开始学习',
                            'url'=>'http://'.$_SERVER["HTTP_HOST"].'/wechat',
                        ],
                    ],
                ]
            ],
        ];
        $result = json_encode($data, JSON_UNESCAPED_UNICODE);
        $result = urldecode($result);
        p(json_encode($result));
        $curl = httprequest($url,$result);
        p($curl);
    }

    /**
     * 获取自定义菜单
     */
    public function getMenulist(){
        $getAccessToken = getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$getAccessToken['access_token'];
        $curl = httprequest($url);
        p($curl);
    }

    /**
     * 获取素材列表
     */
    public function getMedia(){
        $getAccessToken = getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$getAccessToken['access_token'];
        $data = '{
           "type":"news",
           "offset":"0",
           "count":"20"
        }';
        $curl = httprequest($url,$data);
        p($curl);
    }

    /**
     * 获取小程序access_token
     */
    function getMinAccessToken(){
        $data = getMinAccessToken();
        successJson('获取成功',$data['access_token']);
    }

    /**
     * 发送小程序模板消息
     */
    public function getSendMinMessage(){
        $data = getMinAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='.$data['access_token'];
        $tpl = '{
          "touser": "OPENID",
          "template_id": "TEMPLATE_ID",
          "page": "index",
          "form_id": "FORMID",
          "data": {
              "keyword1": {
                  "value": "339208499",
                  "color": "#173177"
              },
              "keyword2": {
                  "value": "2015年01月05日 12:30",
                  "color": "#173177"
              },
              "keyword3": {
                  "value": "粤海喜来登酒店",
                  "color": "#173177"
              } ,
              "keyword4": {
                  "value": "广州市天河区天河路208号",
                  "color": "#173177"
              }
          },
          "emphasis_keyword": "keyword1.DATA"
        }';
        $curl = httprequest($url,$tpl);
        $json_data = json_decode($curl,true);
    }
    /**
     * 小程序二维码
     */
    public function getcourseWxacode(){
        
        $cid = input('cid',0);
        $course = find('Course',['cid'=>$cid]);
        
        if(!$course){
            errorJson('课程不存在');
//             echo -1;
//             die();
        }
        if($course['wxmincode'] == ''){
            $getMinAccessToken = getMinAccessToken();
            $url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token='.$getMinAccessToken['access_token'];
            $param = [
                'path'=>'pages/course/detail?cid='.$cid,
                'width'=>430,
            ];
            $result = doCurlPostRequest($url,json_encode($param));
            if(isset($result['errcode'])){
                errorJson('课程不存在');
              // errorJson($result['errmsgc']);
//                 echo -1;
//                 die();
            }
            // $img = base64_encode($result);
            $basedir = ROOT_PATH . 'public' . DS .'/image/';
            $course_path = $basedir."course_wxcode_".$cid.'.png';
            fopen($course_path, "w");
            file_put_contents($course_path,$result);
            update('Course',['cid'=>$cid],['wxmincode'=>'/public/image/course_wxcode_'.$cid.'.png']);
            $img = 'http://'.$_SERVER["HTTP_HOST"].'/public/image/course_wxcode_'.$cid.'.png';
//             echo $img;
//             die();
            successJson('ok',$img);
        }else{
            $img = 'http://'.$_SERVER["HTTP_HOST"].$course['wxmincode'];
//             echo $img;
//             die();
            successJson('ok',$img);
        }
    }

    /**
     * 小程序课程二维码
     */
    public function getWxacode(){
        $cid = input('cid',0);
        $course = find('Course',['cid'=>$cid]);
        if(!$course){
            errorJson('课程不存在');
        }
        if($course['wxmincode'] == ''){
            $getMinAccessToken = getMinAccessToken();
            $url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token='.$getMinAccessToken['access_token'];
            $param = [
                'path'=>'pages/course/detail?cid='.$cid,
                'width'=>430,
            ];
            $result = doCurlPostRequest($url,json_encode($param));
            if(isset($result['errcode'])){
                errorJson('errmsgc');
            }
           // $img = base64_encode($result);
            $basedir = ROOT_PATH . 'public' . DS .'/image/';
            $course_path = $basedir."course_wxcode_".$cid.'.png';
            fopen($course_path, "w");
            file_put_contents($course_path,$result);
            update('Course',['cid'=>$cid],['wxmincode'=>'/public/image/course_wxcode_'.$cid.'.png']);
            $img = 'http://'.$_SERVER["HTTP_HOST"].'/public/image/course_wxcode_'.$cid.'.png';
            successJson('ok',$img);
        }else{
            $img = 'http://'.$_SERVER["HTTP_HOST"].$course['wxmincode'];
            successJson('ok',$img);
        }
    }
    /**
     * 小程序套餐二维码
     */
    public function getPackageacode(){
        $id = input('id',0);
        $package = find('Package',['id'=>$id]);
        if(!$package){
            errorJson('套餐不存在');
        }
        if($package['wxminicode'] == ''){
            $getMinAccessToken = getMinAccessToken();
            $url = 'https://api.weixin.qq.com/wxa/getwxacode?access_token='.$getMinAccessToken['access_token'];
            $param = [
                'path'=>'pages/course/package_detail?id='.$id,
                'width'=>430,
            ];
            $result = doCurlPostRequest($url,json_encode($param));
            if(isset($result['errcode'])){
                errorJson('errmsgc');
            }
            // $img = base64_encode($result);
            $basedir = ROOT_PATH . 'public' . DS .'/image/';
            $package_path = $basedir."package_wxcode_".$id.'.png';
            fopen($package_path, "w");
            file_put_contents($package_path,$result);
            update('Package',['id'=>$id],['wxminicode'=>'/public/image/package_wxcode_'.$id.'.png']);
            $img = 'http://'.$_SERVER["HTTP_HOST"].'/public/image/package_wxcode_'.$id.'.png';
            successJson('ok',$img);
        }else{
            $img = 'http://'.$_SERVER["HTTP_HOST"].$package['wxminicode'];
            successJson('ok',$img);
        }
    }

    /**
     * pc登录获取临时带参数二维码
     */
    public function getTemporaryqrcode(){
        $code = input('code');
        if(!Cache::get('login_'.$code)){
            $getAccessToken = getAccessToken();
            $token = $getAccessToken['access_token'];
            $qrcode_url ='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$token;
            $key = 'pclogin_'.$code;
            $data = '{"expire_seconds": 3600, "action_name": "QR_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$key.'"}}}';
            $curl = doCurlPostRequest($qrcode_url,$data);
            $json_curl = json_decode($curl,true);
            if(!isset($json_curl['ticket'])){
                errorJson($json_curl['errmsg']);
            }
            $ticket = $json_curl['ticket'];
            $qrcode = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$ticket;
            Cache::set('pclogin_'.$code,$qrcode,3600);
        }else{
            $qrcode = Cache::get('login_'.$code);
        }
        successJson('ok',$qrcode);
    }

    /**
     * pc通过二维码获取信息
     */
    public function getLogincode(){
        $code = input('code');
        if(!Cache::get($code)){
            errorJson('二维码已过期');
        }else{
            successJson('ok',Cache::get($code));
        }
    }

    function _strip_tags($tagsArr,$str) {
        foreach ($tagsArr as $tag) {
            $p[]="/(<(?:\/".$tag."|".$tag.")[^>]*>)/i";
        }
        $return_str = preg_replace($p,"",$str);
        return $return_str;
    }
}
