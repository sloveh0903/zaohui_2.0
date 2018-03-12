<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::group('api',function(){
    Route::controller('index','api/Index');      //首页
    Route::controller('user','api/User');        //用户
    Route::controller('course','api/Course');   //课程
    Route::controller('ask','api/Ask');          //问答
    Route::controller('answer','api/Answer');   //回答
    Route::controller('askCategory','api/AskCategory'); //问题分类
    Route::controller('article','api/Article'); //文章
    Route::controller('articleCategory','api/ArticleCategory'); //文章分类
    Route::controller('comment','api/Comment'); //评价
    Route::controller('like','api/Like');        //点赞
    Route::controller('order','api/Order');      //订单
    Route::controller('wxpay','api/WxPay');      //微信支付
    Route::controller('browseRecord','api/BrowseRecord');      //浏览记录
    Route::controller('favorite','api/Favorite');   //收藏
    Route::controller('follow','api/Follow');   //关注
    Route::controller('studyList','api/StudyList');   //学习记录
    Route::controller('wechat','api/Wechat');    //微信
    Route::controller('feedback','api/Feedback');    //反馈
    Route::controller('search','api/Search');    //搜索
    Route::controller('config','api/Config');    //配置
    Route::controller('api','api/Api');
    Route::controller('art','api/Art');
    Route::controller('read','api/Read'); //消息阅读
    Route::controller('notify','api/Notify'); //消息通知
    Route::controller('courseIntroduce','api/CourseIntroduce'); //课程简介
    Route::controller('rebate','api/Rebate'); //分销
    Route::controller('task','api/Task'); //虚拟币任务
    Route::controller('recharge','api/Recharge'); //充值回调
    Route::controller('customMenu','api/CustomMenu'); //自定义菜单
    Route::controller('wechatReply','api/WechatReply'); //关注回复
    Route::controller('wechatKeywords','api/WechatKeywords'); //关键词回复
    Route::controller('usercard','api/UserCard'); //会员卡
    Route::controller('ucardrecord','api/UcardRecord'); //会员卡
});






