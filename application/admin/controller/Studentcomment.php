<?php 
namespace app\admin\controller;
use app\admin\model\Comment;
use app\admin\model\User;
use app\admin\model\Course as Cr;

class Studentcomment extends Base
{
    public function index()
    {
        //面包屑导航 固定位置
        $this->assign('course_bread',3);
        return $this->fetch();
    }
    public function comment(){
        $map = [];
        $key = input('key');
        //查询 课程名称对应课程id
        if($key&&$key!==""){
            $c_map['title'] = ['like',"%" . $key . "%"];
            $course = new Cr();
            $course_lists = $course->where($c_map)->column('cid');
            $cid_lists = implode(',',$course_lists);
            $map['cid'] =  ['in',$cid_lists];
        }
        $audit = input('audit');
        
        $map['closed'] = 0;
        
        if($audit == '0'){
            $map['audit'] = 0;
        }elseif($audit == '1'){
            $map['audit'] = 1;
        }
        $map['closed'] = 0;
        $Nowpage = input('get.p') ? input('get.p'):1;
        $comment = new Comment();
        $user = new User();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $comment->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $comment->where($map)->order('id desc')->limit($start,$limits)->select();
        if($lists){
            foreach ($lists as $k=>$v) {
                //$lists[$k]['content2'] = $lists[$k]['content'];
                //$lists[$k]['content'] = mb_substr($lists[$k]['content'],0,40);
                $lists[$k]['nickname'] = $user->where('uid',$v['uid'])->value('nickname');
                $cid_arr[] = $v['cid'];
            }
            //var_dump($cid_arr);
            $course_lists = db('Course')->where('cid','in',$cid_arr)->select();
            foreach($course_lists as $c_list){
                $couse_arr[$c_list['cid']] = $c_list['title'];
            }
            foreach ($lists as $fkey=>$fval){
                $lists[$fkey]['course_name'] = $couse_arr[$fval['cid']];
            }
        }else{
            $lists = array();
        }
        
        if(request()->isAjax()){
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="课程评价管理";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        
        return $this->fetch('index');
    }
    /**
     * 审核
     * @return \think\response\Json
     */
    public function setAudit_comment(){
        $ids = input('param.checkbox');
        $comment = new Comment();
        $where['id'] = ['in',$ids];
        $param['audit'] = 1;
        $flag = $comment->SetParam($param,$where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }
    /**
     * 删除
     * @return \think\response\Json
     */
    public function del_comment(){
        $id = input('param.id');
        $comment = new Comment();
        $where['id'] = $id;
        $flag = $comment->DeleteData($where);
        $cid = db('comment')->where('id',$id)->value('cid');
        $comment_where['cid'] = $cid;
        $comment_where['id'] = ['<>',$id];
        $comment_sum = sum('Comment',$comment_where,'star');
        $comment_count = counts('Comment',$comment_where);
        if($comment_count){
            $score = (round($comment_sum/$comment_count, 1))*2;
        }else{
            $score = 10;
        }
        update('Course',['cid'=>$cid],['score'=>$score]);
        
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    /**
     * 批量删除
     * @return \think\response\Json
     */
    public function delall_comment(){
        $ids_str = input('param.checkbox');
        $ids = explode(',', $ids_str);
        //var_dump($ids);die();
        foreach ($ids as $val){
            $id = $val;
            $cid = db('comment')->where('id',$id)->value('cid');
            $comment_where['cid'] = $cid;
            $comment_where['id'] = ['<>',$id];
            $comment_sum = sum('Comment',$comment_where,'star');
            $comment_count = counts('Comment',$comment_where);
            if($comment_count){
                $score = (round($comment_sum/$comment_count, 1))*2;
            }else{
                $score = 10;
            }
            update('Course',['cid'=>$cid],['score'=>$score]);
        }
        $comment = new Comment();
        $where['id'] = ['in',$ids];
        if($comment->DeleteData($where)){
            return json(['status' => 200,  'url'=>'reload' , 'data' => '', 'msg' => '批量删除成功']);
        }else{
            return json(['status' => -1,  'url'=>'reload' , 'data' => '', 'msg' => '批量删除失败']);
        }
    }
    
    /**
     * 管理员回复
     */
    public function reply(){
        $param = input();
        $id = $param['id'];
        $reply = $param['reply'];
        if(!$id||!$reply){
            return json(['status' => -1,  'url'=>'reload' , 'data' => '', 'msg' => '请填写回复内容']);
        }else{
            $comment = new Comment();
            $data['reply'] = $reply;
            $where['id'] = $id;
            $flag = $comment->UpdateData($data,$where);
            return json(['status' => $flag['status'],'url'=>'/admin/studentcomment/index', 'data' => '', 'msg' => $flag['msg']]);
        }
    }
    public function batchreply(){
        $param = input();
        var_dump($param);
    }
}

?>

