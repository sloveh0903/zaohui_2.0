<?php

// +----------------------------------------------------------------------
// | wechat-php-sdk
// +----------------------------------------------------------------------
// | 版权所有 2014~2017 广州楚才信息科技有限公司 [ http://www.cuci.cc ]
// +----------------------------------------------------------------------
// | 官方文档: https://www.kancloud.cn/zoujingli/wechat-php-sdk
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | github开源项目：https://github.com/zoujingli/wechat-php-sdk
// +----------------------------------------------------------------------

namespace Wechat;

use Wechat\Lib\Cache;
use Wechat\Lib\Common;
use Wechat\Lib\Tools;

/**
 * 微信Token类
 *
 * @author Anyon <zoujingli@qq.com>
 * @date 2016/10/26 14:47
 */
class WechatToken extends Common
{

    /**
     * 获取Token
     * @param string $appid
     * @param string $appsecret
     * @return $token
     */
    public function token($appid, $appsecret)
    {
        $token = $this->getAccessToken($appid,$appsecret);
        return $token;
    }


}
