<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
header("Access-Control-Allow-Origin: *");
/*ob_start();
if($_SERVER['REQUEST_SCHEME'] == 'http' && $_SERVER['REQUEST_URI'] == '/'){
    header("location:https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    exit;
}
if(strlen($_SERVER['REDIRECT_URL']) >=6){
    $substr = substr($_SERVER['REDIRECT_URL'] , 1 , 5);
}else{
    $substr = '';
}

if($_SERVER['REQUEST_SCHEME'] == 'http' && $substr == 'index'){
    header("location:https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    exit;
}*/

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');

// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';


