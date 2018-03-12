<?php
namespace app\api\validate;

use think\Validate;

class Comment extends Validate
{
    protected $rule = [
        'cid'  =>  'require|number',
        'uid'  =>  'require|number',
        'star' =>  'number',
        'content'  =>  'require',
    ];
    protected $message  =   [
        'uid.require' => '用户id必须',
        'cid.require' => '课程id必须',
        'content.require' => '内容不能为空',
        'cid.number'   => 'cid必须是数字',
        'uid.number'   => 'uid必须是数字',
        'star.number'   => '评分必须是数字',
    ];

}