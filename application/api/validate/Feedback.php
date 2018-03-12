<?php
namespace app\api\validate;

use think\Validate;

class Feedback extends Validate
{
    protected $rule = [
        'uid'  =>  'require|number',
        'content'  =>  'require',
    ];
    protected $message  =   [

    ];

}