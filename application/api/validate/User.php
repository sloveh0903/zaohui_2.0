<?php
namespace app\api\validate;

use think\Validate;

class User extends Validate
{
    protected $rule = [
        'uname'  =>  'require',
        'password'  =>  'require',
    ];
    protected $message  =   [
        'uname.require' => '用户名必须',
        'password.require' => '密码必须',
    ];

}