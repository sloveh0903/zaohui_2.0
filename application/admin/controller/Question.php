<?php

namespace app\admin\controller;
use app\admin\model\AskCategory;
use app\admin\model\Ask;
use app\admin\model\Course;
use app\admin\model\User;
use app\admin\model\Video;
use app\admin\model\Answer;


class Question extends Base
{
    public function _empty(){
        return $this->index();
    }

    /**
     * [index 问题列表]
     * @author [xenos]
     */
    public function index(){


        $key = $so['title'] = input('key');
        $cid = $so['cid'] = input('cid');
        $status = input('status');
        $type = input('type');
        $map = [];
        $map['closed'] = 0;
        $user = new User();
        if(isset($status)){
            switch ($status){
                case '1':
                    $map['hot'] = 1;
                    break;
                case '2':
                    $map['hot'] = 0;
                    break;
                case '3':
                    $map['comments'] = ['>','0'];
                    break;
                case '4':
                    $map['comments'] = 0;
                    break;
            }
        }

        if(isset($type)){
            switch ($type){
                case 'nickname':
                    $userInfo = $user->where(array('nickname'=>['like',"%" . $key . "%"],'closed'=>'0','audit'=>'1'))->field('uid')->select();
                    if($userInfo){
                        foreach ($userInfo as $info) {
                            $uid[] = $info['uid'];
                        }
                        if(isset($uid)){
                            $map['uid'] = ['in',$uid];
                        }
                    }
                    break;
                case 'content':
                    $map['content'] = ['like',"%" . $key . "%"];
                    break;
            }
        }


        $Nowpage = input('get.p') ? input('get.p'):1;

        $cate = new AskCategory();
        $catelist = $cate->where(array('closed'=>'0'))->field('id,cate_name')->order('orderby asc')->select();
        $ask = new Ask();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $ask->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $ask->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $lists[$k]['edit_url'] = \think\Url::build('question/edit_ask','id='.$v['id']);
            $lists[$k]['repay_url'] = \think\Url::build('repay/index','id='.$v['id']);
            if($cateInfo = $cate->where('id',$v['pid'])->value('cate_name')){
                $lists[$k]['cate_name'] = $cateInfo;
            }else{
                $lists[$k]['cate_name'] = "";
            }
            $lists[$k]['nickname'] = "";
            $lists[$k]['content'] = mb_substr($v['content'], 0, 30, 'utf-8');
            $lists[$k]['face'] = "";
            if($v['uid']){
                $userInfo = $user->where('uid',$v['uid'])->field('nickname,face')->find();
                if($userInfo){
                    $lists[$k]['nickname'] = $userInfo['nickname'] ;
                    $lists[$k]['face'] = $userInfo['face'] ;
                }
            }

        }
        $this->assign('catelist', $catelist);
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="问题列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        //面包屑导航 固定位置
        $this->assign('course_bread',4);
        //
        $config_data=db('show_switch')->field('is_showask,is_canask')->find();
        $this->assign('is_showask',$config_data['is_showask']);
        $this->assign('is_canask', $config_data['is_canask']);
        return $this->fetch();
    }


    //权限设置开关
   public function edit_switch(){
       if(request()->isAjax()){
           $param = input('post.');
           if(!isset($param['is_showask'])){
               $param['is_showask'] = 0;
           }
           if(!isset($param['is_canask'])){
               $param['is_canask'] = 0;
           }
           db('show_switch')->where(['id'=>1])->update($param);
           return json(['status'  =>'200','url'=>'reload', 'data' => 'reload', 'msg' => '设置成功']);
       }
   }
    /**
     * [add_ask 添加问题]
     * @author [xenos]
     */
    public function add_ask(){
        if(request()->isAjax()){
            $param = input('post.');
            $ask = new Ask();
            /*$param['cid'] = $param['course_list'];
            $param['vid'] = $param['video_list'];*/
            $flag = $ask->InsertData($param);
            return json(['code' => $flag['code'], 'data' => '', 'msg' => $flag['msg']]);
        }

        $cate = new AskCategory();//文章分类模型
        $course = new Course();
        $course_list = $course->field('cid,title')->select();
        $cate_list = $cate->field('id,cate_name')->select();
        $this->assign('cate',$cate_list);
        $this->assign('course',$course_list);
        return $this->fetch();
    }



    /**
     * [edit_ask 编辑问题]
     * @return [type] [description]
     * @author [xenos]
     */
    public function edit_ask()
    {
        $id = input('param.id');
        $ask = new Ask();
        if(request()->isPost()){
            $param = input('post.');
            /*$param['cid'] = $param['course_list'];
            $param['vid'] = $param['video_list'];*/
            $where['id'] = $param['id'];
            $photos = array_values($param['photopath']);
            $param['photopath'] = json_encode($photos);
            $flag = $ask->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'/admin/question/index', 'data' => '', 'msg' => $flag['msg']]);
        }
        $askinfo = $ask->get($id);
        $cate = new AskCategory();//文章分类模型
        $course = new Course();
        $video = new Video();
        $course_list = $course->field('cid,title')->select();
        $cate_list = $cate->field('id,cate_name')->select();
        $video_list = $video->where(array('cid'=>$askinfo['cid']))->field('id,title')->select();
        $askinfo->photopath = json_decode($askinfo->photopath);
        $this->assign('cate',$cate_list);
        $this->assign('course',$course_list);
        $this->assign('video',$video_list);
        $this->assign('ask',$askinfo);
        return $this->fetch();
    }

    public function ask_hot(){
        $id = input('param.id');
        $ask = new Ask();
        $hot = $ask->where(array('id'=>$id))->value('hot');//判断当前状态情况
        if($hot==0)
        {
            $flag = $ask->where(array('id'=>$id))->setField(['hot'=>1]);
            return json(['status' => '200','url'=>'reload', 'data' => '', 'msg' => '设置精华成功']);
        } else {
            $flag = $ask->where(array('id'=>$id))->setField(['hot'=>0]);
            return json(['status'  => '200','url'=>'reload', 'data' => '', 'msg' => '取消精华成功']);
        }

    }


    public function set_hot(){
        $ids = input('param.checkbox');
        $ask = new Ask();
        $where['id'] = ['in',$ids];
        $param['hot'] = 1;
        $flag = $ask->SetParam($param,$where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }



    /**
     * [del_ask 删除问题]
     * @return [type] [description]
     * @author [xenos]
     */
    public function del_ask()
    {
        $id = input('param.id');
        $ask = new Ask();
        $where['id'] = $id;
        $flag = $ask->DeleteData($where);
        //删除对应回答
        $answer_where['aid'] = $id;
        $answer = new Answer();
        $answer->DeleteData($answer_where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    public function delall_ask(){
        $ids = input('param.checkbox');
        $ask = new Ask();
        $where['id'] = ['in',$ids];
        $flag = $ask->DeleteData($where);
        //删除对应回答
        $answer_where['aid'] = ['in',$ids];
        $answer = new Answer();
        $answer->DeleteData($answer_where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }




    /**
     * [ask_cate 分类列表]
     * @return [type] [description]
     * @author [xenos]
     */
    public function ask_cate(){

        $key = $so['cate_name'] = input('key');
        $map = [];
        $map['closed'] = 0;
        if($key&&$key!==""){
            $map['cate_name'] = ['like',"%" . $key . "%"];
        }
        $Nowpage = input('get.p') ? input('get.p'):1;
        $cate = new AskCategory();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $cate->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $cate->where($map)->order('orderby asc,id desc')->limit($start,$limits)->select();
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        $this->assign('val', $key);
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="问题分类";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();

    }

    /**
     * [add_cate 添加分类]
     * @return [type] [description]
     * @author [xenos]
     */
    public function add_cate()
    {
        $param = input('post.');
        $cate = new AskCategory();
        $flag = $cate->InsertData($param);
        return json(['status' => $flag['status'],  'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    /**
     * [edit_cate 编辑分类]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function edit_cate()
    {
        $cate = new AskCategory();
        $param = input('post.');
        $where['id'] = $param['id'];
        $flag = $cate->UpdateData($param,$where);
        return json(['status' => $flag['status'],  'url'=>'reload' , 'data' => '', 'msg' => $flag['msg']]);
    }

    /**
     * [del_cate 删除分类]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function del_cate()
    {
        $id = input('param.id');
        $cate = new AskCategory();
        $ask = new Ask();
        $where['id'] = $id;
        $ask_where['pid'] = $id;
        $ask->DeleteData($ask_where);
        $flag = $cate->DeleteData($where);
        return json(['status' => $flag['status'],  'url'=>'reload' , 'data' => '', 'msg' => $flag['msg']]);
    }


    public function set_orderby()
    {
        $id = input('param.id');
        $orderby = input('param.orderby');
        $cate = new AskCategory();
        $where['id'] = $id;
        $param['orderby'] = $orderby;
        $flag = $cate->SetParam($param,$where);
        return json(['status' => $flag['status'],  'url'=>'reload' , 'data' => '', 'msg' => $flag['msg']]);
    }


    public function batch_cate(){
        $ids = input('param.checkbox');
        $cate = new AskCategory();
        $ask = new Ask();
        $where['id'] = ['in',$ids];
        $ask_where['pid'] = ['in',$ids];
        $ask->DeleteData($ask_where);
        $flag = $cate->DeleteData($where);
        return json(['status' => $flag['status'],  'url'=>'reload' , 'data' => '', 'msg' => $flag['msg']]);

    }


    /*
     * [get_video_list 获取视频列表]
     * @return [type] [description]
     * @author [xenos]
     *
     */
    public function get_video_list(){
        $cid=input('param.cid');
        $video = new Video();
        return json($video->where(array('closed'=>'0','cid'=>$cid))->field('id,title')->select());
    }

}