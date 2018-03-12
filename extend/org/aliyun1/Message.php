<?php
/*require_once(dirname(dirname(dirname(__FILE__))).'/mns-autoloader.php');*/
require_once EXTEND_PATH."org/aliyun/mns-autoloader.php";
use AliyunMNS\Client;
use AliyunMNS\Topic;
use AliyunMNS\Constants;
use AliyunMNS\Model\MailAttributes;
use AliyunMNS\Model\SmsAttributes;
use AliyunMNS\Model\BatchSmsAttributes;
use AliyunMNS\Model\MessageAttributes;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Requests\PublishMessageRequest;


class Message{
    private $accessId;
    private $accessKey;
    private $endPoint;
    private $client;

    public function __construct()
    {}

    public function send($SMSTemplateCode,$PhoneNumbers,$TemplateParams)
    {
        //$SMSTemplateCode  模板编号
        //$PhoneNumbers  电话号码（数组）
        //$TemplateParams  模板参数(数组：指定短信模板中对应参数的值)
        /**
         * Step 1. 初始化Client
         */
        $this->accessId = "LTAIIWh38tMc1oOh";
        $this->accessKey = "qb5MwJTORqUyeRpOwPXkkVBpZFDlrP";
        $this->endPoint = "http://1457783965472421.mns.cn-hangzhou.aliyuncs.com/";

        $this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
        /**
         * Step 2. 获取主题引用
         */
        $topicName = "sms.topic-cn-hangzhou";
        $topic = $this->client->getTopicRef($topicName);
        /**
         * Step 3. 生成SMS消息属性
         */
        // 3.1 设置发送短信的签名（SMSSignName）和模板（SMSTemplateCode）
        $batchSmsAttributes = new BatchSmsAttributes("优仁电商", $SMSTemplateCode);
        // 3.2 （如果在短信模板中定义了参数）指定短信模板中对应参数的值
        if(is_array($PhoneNumbers)){
            foreach ($PhoneNumbers as $value) {
                $batchSmsAttributes->addReceiver($value, $TemplateParams);
            }
        }else{
            $batchSmsAttributes->addReceiver($PhoneNumbers, $TemplateParams);
        }

        $messageAttributes = new MessageAttributes(array($batchSmsAttributes));
        /**
         * Step 4. 设置SMS消息体（必须）
         *
         * 注：目前暂时不支持消息内容为空，需要指定消息内容，不为空即可。
         */
        $messageBody = "smsmessage";
        /**
         * Step 5. 发布SMS消息
         */
        $request = new PublishMessageRequest($messageBody, $messageAttributes);
        /*try
        {
            $res = $topic->publishMessage($request);
            echo $res->isSucceed();
            echo "\n";
            echo $res->getMessageId();
            echo "\n";
        }
        catch (MnsException $e)
        {
            echo $e;
            echo "\n";
        }*/
        try
        {
            $res = $topic->publishMessage($request);
            $res->isSucceed();

            return $res->getMessageId();
        }
        catch (MnsException $e)
        {
            return "0";
        }
    }


}


?>
