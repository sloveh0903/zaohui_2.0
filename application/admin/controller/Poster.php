<?php

namespace app\admin\controller;
use app\admin\model\Course;



class Poster extends Base
{
    public function _empty(){
        return $this->index();
    }

    /**
     * [index 课程海报]
     * @author [xenos]
     */
    public function index(){
        $map = [];
        $map['closed'] = 0;
        $Nowpage = input('get.p') ? input('get.p'):1;
        $course = new Course();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $course->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $course->where($map)->field('cid,title,poster_audit')->order('cid desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            switch ($v['poster_audit']){
                case "0":
                    $poster_status = "未开启";
                    break;
                case "1":
                    $poster_status = "已开启";
                    break;
                case "2":
                    $poster_status = "已关闭";
                    break;
            }
            $lists[$k]['poster_status'] = $poster_status;
        }
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="营销海报";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }

    /**
     * 添加海报
     * Create By Xenos
     * Return
     */
    public function add_poster(){
        $cid = input('cid');
        $course = new Course();
        if(request()->isPost()){
            $param = input('post.');
            $where['cid'] = $param['cid'];
            $flag = $course->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'/admin/poster/index', 'data' => '', 'msg' => $flag['msg']]);

        }
        $data = $course->where(array('cid'=>$cid,'closed'=>'0'))->field('cid,poster,poster_audit')->find();
        $this->assign('cid', $cid);
        $this->assign('course', $data);
        return $this->fetch();
    }


}