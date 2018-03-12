<?php
namespace app\api\controller;
use think\Controller;

/**
 * Class 举报
 */
class Report extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->getIndex();
    }

    public function postAdd(){
        $content = input('content');
        $uid =  input('uid') ? input('uid') : 0 ;
        $tid = input('tid');
        $name = input('name');
        $data = array(
            'content'=>$content,
            'uid'=>$uid,
            'tid'=>$tid,
            'name'=>$name,
        );
        $result = insert('Report',$data);
        if($result){
            successJson();
        }else{
            errorJson('举报失败');
        }
    }



}
