<?php
namespace app\api\validate;

use think\Validate;

class Answer extends Validate
{
    protected $rule = [
        'uid'  =>  'require|number',
        'aid'  =>  'require|number',
        'content'  =>  'require',
    ];
    protected $message  =   [
        'aid.number'   => '课程id必须',
        'uid.require' => '用户id必须',
        'content.require' => '内容不能为空',
    ];

}