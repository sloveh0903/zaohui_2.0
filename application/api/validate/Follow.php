<?php
namespace app\api\validate;

use think\Validate;

class Follow extends Validate
{
    protected $rule = [
        'tid'  =>  'require|number',
        'uid'  =>  'require|number',
    ];

}