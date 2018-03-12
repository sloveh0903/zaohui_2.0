<?php
namespace app\api\controller;
use think\Controller;
use think\File;
use app\api\validate as validate;

/**
 * Class AskCategory 问答分类
 * @package app\api\controller
 */
class AskCategory extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->getIndex();
    }

    /**
     * 问答分类列表
     * @author王宣成
     * @return Json
     */
    public function getIndex(){
        $ask_category = lists('AskCategory',[],['orderby' => 'asc']);
        $data['ask_category'] = $ask_category;
        successJson('操作完成',$data);
    }

}
