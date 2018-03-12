<?php
namespace app\api\validate;

use think\Validate;

class Like extends Validate
{
    protected $rule = [
        'itemid'  =>  'require|number',
        'uid'  =>  'require|number',
        'typeid'  =>  'number|between:1,3',
    ];
    protected $message  =   [
        'itemid.require' => '问题id必须',
        'uid.require' => '用户id必须',
        'aid.number'   => 'aid必须是数字',
        'uid.number'   => 'uid必须是数字',
    ];

}