<?php
namespace app\api\validate;

use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'cid'  =>  'require|number',
        'uid'  =>  'require|number',
        'title'  =>  'require',
        'content'  =>  'require',
    ];
    protected $message  =   [
        'cid.require' => '分类id必须',
        'uid.require' => '用户id必须',
        'title.require' => '标题不能为空',
        'content.require' => '内容不能为空',
        'cid.number'   => 'cid必须是数字',
        'uid.number'   => 'uid必须是数字',
    ];

}