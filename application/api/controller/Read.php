<?php
namespace app\api\controller;
use think\Controller;
use think\Db;

/**
 * Class Reak 消息阅读
 * @package app\api\controller
 */
class Read extends Controller
{
    public function _initialize()
    {

    }


    /**
     * 修改消息
     */
    public function postUpdate(){
        $id = input('id',0);
        $isread = input('isread',0);
        $read = find('Read',['id'=>$id]);
        if(!$read){
            successJson('消息不存在');
        }
        $result =  update('Read',['id'=>$id],['isread'=>$isread]);
        if(!$result){
            errorJson('操作失败');
        }
        successJson('操作成功');
    }

}
