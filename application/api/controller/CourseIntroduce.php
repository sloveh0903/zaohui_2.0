<?php
namespace app\api\controller;
use think\Controller;
use think\Db;

/**
 * Class CourseIntroduce 课程介绍
 * @package app\api\controller
 */
class CourseIntroduce extends Controller
{
    public function _initialize()
    {

    }

    public function index(){
        $page = input('page',1);
        $size = input('size',10);
        $where['page'] = $page;
        $where['size'] = $size;
        $result = lists('CourseIntroduce');
        successJson('操作成功',$result);
    }

    public function detail(){
        $id = input('id',0);
        $result = lists('CourseIntroduce',['id'=>$id]);
        successJson('操作成功',$result);
    }
    public function getdetail(){
        $id = input('id',0);
        $map['id'] = $id;
        $map['closed'] =0;
        $result  = db('course_introduce')->where($map)->value('content');
        successJson('操作成功',$result);
    }

}
