<?php
namespace app\model;

use think\Model;

class Favorite extends Model
{
    protected $autoWriteTimestamp = true;
    //自定义初始化
    protected function initialize()
    {
        //需要调用`Model`的`initialize`方法;
        parent::initialize();
    }




}