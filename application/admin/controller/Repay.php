<?php

namespace app\admin\controller;



use app\admin\model\Answer;
use app\admin\model\Ask;
use app\admin\model\User;

class Repay extends Base
{
    public function _empty(){
        return $this->index();
    }

    /**
     * [index 问题回答列表]
     * @author [xenos]
     */
    public function index(){

        $key = $so['content'] = input('key');
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $aid = $so['aid'] = input('id');
        $map = [];
        $map['closed'] = 0;
        if($key&&$key!==""){
            $map['content'] = ['like',"%" . $key . "%"];
        }
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }
        if($aid){
            $map['aid'] = $so['aid'] = $aid;
        }
        if($end_date){
            $map['create_time'] = $so['create_time '] = ['<',strtotime($end_date)];
        }
        $Nowpage = input('get.p') ? input('get.p'):1;
        $user = new User();
        $ask = new Ask();
        $askInfo = $ask->where(array('id'=>$aid))->find();
        if($askInfo){
            $askInfo['photopath'] = $askInfo['photopath'] ? json_decode($askInfo['photopath']) : '' ;
            $askInfo['user'] = $askInfo['uid'] ? $user->where(array('uid'=>$askInfo['uid']))->find() : '' ;
        }
        $answer = new Answer();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $answer->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $answer->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $puserinfo = [];
            $lists[$k]['edit_url'] = \think\Url::build('repay/edit_repay','id='.$v['id']);
            $lists[$k]['comment_url'] = \think\Url::build('repay/index_comment','id='.$v['id']);
            $lists[$k]['nickname'] = "";
            $lists[$k]['face'] = "";
            $lists[$k]['pnickname'] = "";
            $userinfo = $user->where('uid',$v['uid'])->field('nickname,face')->find();
            if($v['puid']){
                $puserinfo = $user->where('uid',$v['puid'])->field('nickname,face')->find();
            }
           
            if($userinfo){
                $lists[$k]['nickname'] = $userinfo['nickname'] ;
                $lists[$k]['face'] = $userinfo['face'] ;
            }
            if($puserinfo){
                $lists[$k]['pnickname'] = $puserinfo['nickname'] ;
            }
            if ($v['puid']==-1){
                $lists[$k]['pnickname'] = '老师' ;
            }
        }
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="问题列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        $this->assign('aid', $aid);
        $this->assign('askInfo', $askInfo);
        return $this->fetch();

    }


    public function add_repay(){
        $aid = input('param.aid');
        if(request()->isAjax()){
            $answer = new Answer();
            $ask = new Ask();
            $param = input('post.');
            if(isset($param['pid'])){
                $parentAnswer = $answer->where('id',$param['pid'])->find();
                $param['puid'] = $parentAnswer['uid'];
                $param['root_id'] = $parentAnswer['root_id'];
                //回答

                if($param['pid'] == 0){
                    setInc('Ask',['id'=>$param['aid']],'comments');
                }

                //回复
                if($param['pid'] > 0 && $param['root_id'] == 0){
                    $param['root_id'] = $parentAnswer['pid'];
                    setInc('Answer',['id'=>$param['pid']],'comments');
                }

                //回复@
                if($param['root_id'] > 0){
                    setInc('Answer',['id'=>$param['root_id']],'comments');
                    setInc('Answer',['id'=>$param['pid']],'comments');
                }
            }else{
                $ask->where('id',$param['aid'])->setInc('comments');
            }
            $param['ip'] = ip2long($_SERVER["REMOTE_ADDR"]);
            $flag = $answer->InsertData($param);
            return json(['status' => $flag['status'],'url'=>'/admin/repay/index.html?id='.$aid, 'data' => '', 'msg' => $flag['msg']]);
        }
        $this->assign('aid',$aid);
        return $this->fetch();
    }


    public function edit_repay(){
        $pid = input('param.pid');
        $aid = input('param.aid');
        if(request()->isAjax()){
            $answer = new Answer();
            $ask = new Ask();
            $param = input('post.');
            if(isset($param['pid'])){
                $parentAnswer = $answer->where('id',$param['pid'])->find();
                $param['puid'] = $parentAnswer['uid'];
                $param['root_id'] = $parentAnswer['root_id'];
                //回答

                if($param['pid'] == 0){
                    setInc('Ask',['id'=>$param['aid']],'comments');
                }

                //回复
                if($param['pid'] > 0 && $param['root_id'] == 0){
                    $param['root_id'] = $parentAnswer['pid'];
                    setInc('Answer',['id'=>$param['pid']],'comments');
                }

                //回复@
                if($param['root_id'] > 0){
                    setInc('Answer',['id'=>$param['root_id']],'comments');
                    setInc('Answer',['id'=>$param['pid']],'comments');
                }
            }else{
                $ask->where('id',$param['aid'])->setInc('comments');
            }
            $param['ip'] = ip2long($_SERVER["REMOTE_ADDR"]);
            $flag = $answer->InsertData($param);
            return json(['status' => $flag['status'],'url'=>'/admin/repay/index.html?id='.$aid, 'data' => '', 'msg' => $flag['msg']]);
        }
        $this->assign('pid',$pid);
        $this->assign('aid',$aid);
        return $this->fetch();
    }


    /**
     * [del_repay 删除回答]
     * @return [type] [description]
     * @author [xenos]
     */
    public function del_repay()
    {
        $id = input('param.id');
        $answer = new Answer();
        $where['id'] = $id;
        $answer_data = $answer->where($where)->find();
        $aid = $answer_data['aid'];
        $pid = $answer_data['pid'];
        $root_id = $answer_data['root_id'];

        //第一层
        if($pid == 0){
            setDec('Ask',['id'=>$aid],'comments');
            $answer->DeleteData(['pid'=>$id]);
        }

        //第二层
        if($pid > 0 && $root_id == 0){
            $count = counts('Answer',['pid'=>$id]);
            setDec('Answer',['id'=>$pid],'comments',$count+1);
            $answer->DeleteData(['pid'=>$id]);
            $answer->DeleteData(['root_id'=>$id]);
        }

        //第三层
        if($pid > 0 && $root_id > 0){
            setDec('Answer',['id'=>$root_id],'comments');
        }
        $flag = $answer->DeleteData(['id'=>$id]);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function delall_repay(){
        $ids = input('param.checkbox');
        $answer = new Answer();
        $where['id'] = ['in',$ids];
        $id_arr = explode(",",$ids);
        $ask = new Ask();
        foreach($id_arr as $v){
            $id = $v;
            $answer_data = $answer->where(['id'=>$v])->find();
            $aid = $answer_data['aid'];
            $pid = $answer_data['pid'];
            $root_id = $answer_data['root_id'];

            //第一层
            if($pid == 0){
                setDec('Ask',['id'=>$aid],'comments');
                $answer->DeleteData(['pid'=>$id]);
            }

            //第二层
            if($pid > 0 && $root_id == 0){
                $panswer = $ask->where(['id'=>$pid])->find();
                if(!$panswer){
                    continue;
                }
                $count = counts('Answer',['pid'=>$id]);
                setDec('Answer',['id'=>$pid],'comments',$count+1);
                $answer->DeleteData(['pid'=>$id]);
                $answer->DeleteData(['root_id'=>$id]);
            }

            //第三层
            if($pid > 0 && $root_id > 0){
                $panswer_data = $answer->where(['id'=>$pid])->find();
                if(!$panswer_data){
                    continue;
                }
                $root_answer_data = $answer->where(['id'=>$root_id])->find();
                if(!$root_answer_data){
                    continue;
                }
                setDec('Answer',['id'=>$root_id],'comments');
            }
        }
        $flag = $answer->DeleteData($where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    public function index_comment(){
        $key = $so['content'] = input('key');
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $root_id = $so['root_id'] = input('id');
        $map = [];
        $map['closed'] = 0;
        if($key&&$key!==""){
            $map['content'] = ['like',"%" . $key . "%"];
        }
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }
        if($root_id){
            $map['root_id'] = $so['root_id'] = $root_id;
        }
        if($end_date){
            $map['create_time'] = $so['create_time '] = ['<',strtotime($end_date)];
        }
        $Nowpage = input('get.p') ? input('get.p'):1;
        $user = new User();
        $ask = new Ask();

        $answer = new Answer();
        $answerInfo = $answer->where(array('id'=>$root_id,'closed'=>'0'))->find();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $answer->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $answer->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $lists[$k]['add_comment'] = \think\Url::build('repay/add_comment','id='.$v['id']);
            $lists[$k]['edit_url'] = \think\Url::build('repay/edit_comment','id='.$v['id']);
            if($nickname = $user->where('uid',$v['uid'])->value('nickname')){
                $lists[$k]['nickname'] = $nickname ;
            }else{
                $lists[$k]['nickname'] = "";
            }
            if($repaynickname = $user->where('uid',$v['puid'])->value('nickname')){
                $lists[$k]['repaynickname'] = $repaynickname ;
            }else{
                $lists[$k]['repaynickname'] = "";
            }
        }
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="评论列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        $this->assign('root_id', $root_id);
        $this->assign('answerInfo', $answerInfo);
        return $this->fetch();

    }


    public function add_comment(){
        $id = input('param.id');
        $pid = input('param.pid');
        $answer = new Answer();
        $answerinfo = $answer->get($id);
        if(request()->isAjax()){
            $param = input('post.');
            $flag = $answer->InsertData($param);
            return json(['code' => $flag['code'], 'data' => '', 'msg' => $flag['msg']]);
        }
        $this->assign('answerinfo',$answerinfo);
        $this->assign('pid',$pid);
        return $this->fetch();
    }


    public function edit_comment(){
        $id = input('param.id');
        $answer = new Answer();
        $answerinfo = $answer->get($id);
        if(request()->isPost()){
            $param = input('post.');
            $where['id'] = $param['id'];
            $flag = $answer->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'/admin/repay/index_comment/id/'.$param['root_id'].'.html', 'data' => '', 'msg' => $flag['msg']]);
        }
        $this->assign('repay',$answerinfo);

        return $this->fetch();
    }


    /**
     * [comment_state 回复状态]
     * @return [type] [description]
     * @author [xenos]
     */
    public function comment_state()
    {
        $id=input('param.id');
        $answer = new Answer();
        $status = $answer->where(array('id'=>$id))->value('closed');//判断当前状态情况
        if($status==0)
        {
            $flag = $answer->where(array('id'=>$id))->setField(['closed'=>1]);
            return json(['code' => 1, 'data' => '', 'msg' => '已禁止']);
        }else{
            $flag = $answer->where(array('id'=>$id))->setField(['closed'=>0]);
            return json(['code' => 0, 'data' => '', 'msg' => '已开启']);
        }

    }

    //删除评论
    public function del_comment(){
        $id = input('param.id');
        $answer = new Answer();
        $where['id'] = $id;
        $flag = $answer->DeleteData($where);
        return json(['code' => $flag['code'], 'data' => '', 'msg' => $flag['msg']]);
    }

}