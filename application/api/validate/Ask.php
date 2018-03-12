<?php
namespace app\api\validate;

use think\Validate;

class Ask extends Validate
{
    protected $rule = [
        'uid'  =>  'require|number',
        'cid'  =>  'require|number',
        'title'  =>  'require',
        'content'  =>  'require',
    ];
    protected $message  =   [
        'cid.number'   => '课程id必须',
        'uid.require' => '用户id必须',
        'title.require' => '标题不能为空',
        'content.require' => '内容不能为空',

    ];

}