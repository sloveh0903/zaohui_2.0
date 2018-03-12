<?php
namespace app\api\validate;

use think\Validate;

class Favorite extends Validate
{
    protected $rule = [
        'fid'  =>  'require|number',
        'uid'  =>  'require|number',
        'type'  =>  'require',
        'typeid'  =>  'number|between:1,2',
    ];


}