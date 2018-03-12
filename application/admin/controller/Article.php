<?php

namespace app\admin\controller;
use app\admin\model\Article as Art;
use app\admin\model\ArticleCategory;
use app\admin\model\ArticleComment;
use think\Db;

class Article extends Base
{

    public function _empty(){
        return $this->index();
    }

    /**
     * [index 文章列表]
     * @author [xenos]
     */
    public function index(){
        $key = $so['title'] = input('key');
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $cid = $so['cid'] = input('cid');
        $map = [];
        $map['closed'] = 0;
        if($key&&$key!==""){
            $map['title'] = ['like',"%" . $key . "%"];
        }

        if($cid){
            $map['cid'] = $so['cid'] = $cid;
        }
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }elseif($end_date){
            $map['create_time'] = ['<',strtotime($end_date)];
        }
        if($end_date && $start_date){
            $map['create_time'] = ['between',[strtotime($start_date),strtotime($end_date)]];
        }
        $Nowpage = input('get.p') ? input('get.p'):1;
        $cate = new ArticleCategory();
        $catelist = $cate->where(array('closed'=>'0'))->field('id,cate_name')->order('orderby desc')->select();
        $article = new Art();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $article->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $article->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $lists[$k]['cate_name'] = Db::name('article_category')->where('id',$v['cid'])->value('cate_name');
            $lists[$k]['edit_url'] = \think\Url::build('article/edit_article','id='.$v['id']);
            $lists[$k]['comment_url'] = \think\Url::build('article/comment','id='.$v['id']);
        }
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        $this->assign('val', $key);
        $this->assign('catelist', $catelist);
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="文章列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }


    /**
     * [add_article 添加文章]
     * @return [type] [description]
     * @author [xenos]
     */
    public function add_article()
    {
        if(request()->isPost()){
            $param = input('post.');
            $article = new Art();
            //p($_POST);
            /*if(isset($param['photopath'])){
                $photos = array_values($param['photopath']);
                $param['photopath'] = json_encode($photos);
            }*/

            $flag = $article->InsertData($param);
            return json(['status' => $flag['status'],'url'=>'/admin/article/index', 'data' => '', 'msg' => $flag['msg']]);

        }
     
        $cate = new ArticleCategory();//文章分类模型
        $cate_list = $cate->where(array('pid'=>0,'closed'=>'0'))->field('id,cate_name')->select();
        $this->assign('cate',$cate_list);
        
        //file_put_contents('');
        return $this->fetch();
    }


    /**
     * [edit_article 编辑文章]
     * @return [type] [description]
     * @author [xenos]
     */
    public function edit_article()
    {


        $article = new Art();
        if(request()->isPost()){
            $param = input('post.');
            /*if(isset($param['photopath'])){
                $photos = array_values($param['photopath']);
                $param['photopath'] = json_encode($photos);
            }*/
            if(!isset($param['hot'])){
                $param['hot'] = 0;
            }
            $where['id'] = $param['id'];
            $flag = $article->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'/admin/article/index', 'data' => '', 'msg' => $flag['msg']]);

        }

        $id = input('param.id');
        //\think\Url::build();
       
        
        $articleInfo = $article->get($id);
        $cate = new ArticleCategory();
        $cate_list = $cate->where(array('pid'=>0,'closed'=>'0'))->field('id,cate_name')->select();
        $this->assign('cate',$cate_list);
        $this->assign('article',$articleInfo);
        return $this->fetch();
    }

    /**
     * [del_article 删除文章]
     * @return [type] [description]
     * @author [xenos]
     */
    public function del_article()
    {
        $id = input('param.id');
        $cate = new Art();
        $where['id'] = $id;
        $flag = $cate->DeleteData($where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function delall_article(){
        $ids = input('param.checkbox');
        $where['id'] = ['in',$ids];
        $article = new Art();
        $flag = $article->DeleteData($where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => "批量删除成功"]);
    }

    public function setHot_article(){
        $ids = input('param.checkbox');
        $where['id'] = ['in',$ids];
        $param['hot'] = 1;
        $article = new Art();
        $flag = $article->SetParam($param,$where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    public function setAudit_article(){
        $ids = input('param.checkbox');
        $where['id'] = ['in',$ids];
        $param['audit'] = 1;
        $article = new Art();
        $flag = $article->SetParam($param,$where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }



    /**
     * [article_state 文章状态]
     * @return [type] [description]
     * @author [xenos]
     */
    public function article_state()
    {
        $id=input('param.id');
        $article = new Art();
        $status = $article->where(array('id'=>$id))->value('audit');//判断当前状态情况
        $where['id'] = $id;
        if($status==1)
        {
            $param['audit'] = 0;
            $flag = $article->SetParam($param,$where);
            return json(['status' => $flag['status'], 'data' => '', 'msg' => '取消审核']);
        } else {
            $param['audit'] = 1;
            $flag = $article->SetParam($param,$where);
            return json(['status'  => $flag['status'], 'data' => '', 'msg' => '审核成功']);
        }

    }


    public function comment(){
        $id=input('param.id');
        $key = $so['content'] = input('key');
        $type = input('type');
        $uid = [];
        $map = [];
        $map['closed'] = 0;
        $map['rootid'] = 0;
        if($id){
            $map['aid'] = $id;
        }
        if($type == 'nickname'){
            $where['nickname'] = ['like',"%" . $key . "%"];
            $where['closed'] = 0;
            $where['audit'] = 1;
            $uid = Db::name('user')->where($where)->field('uid')->select();
            if($uid){
                foreach ($uid as $u) {
                    $ret_uid[] = $u['uid'];
                }
                $map['uid'] = ['in',$ret_uid];
            }
        }else{
            $map['content'] = ['like',"%" . $key . "%"];
        }

        $Nowpage = input('get.p') ? input('get.p'):1;
        $comment = new ArticleComment();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $comment->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $comment->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $lists[$k]['user'] = Db::name('user')->where('uid',$v['uid'])->find();
        }
        if($type == 'nickname' && !$uid){
            $lists = [];
            $allpage = 0;
        }
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        $this->assign('val', $key);
        $this->assign('aid', $id);
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="文章列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }

    public function moveUpDown(){
        $id = input('id');
        $updown = input('updown');
        $cate = new ArticleCategory();
        $data_this = $cate->where('id',$id)->find();

        $orderby = $data_this['orderby'];

        if($updown == "up"){
            $map['orderby'] = ['>',$orderby];
            $order = 'orderby asc';
        }else{
            $map['orderby'] = ['<',$orderby];
            $order = 'orderby desc';
            
        }
        $data_next = $cate->where($map)->order($order)->find();
        if($data_next){
            $next_id = $data_next['id'];
            $next_order = $data_next['orderby'];
            $list = [
                ['id'=>$id, 'orderby'=>$next_order],
                ['id'=>$next_id, 'orderby'=>$orderby]
            ];
            $cate->saveAll($list);
        }
        return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => '排序成功']);
    }


    public function del_comment(){
        $id = input('param.id');
        $comment = new ArticleComment();
        $where['id'] = $id;
        $flag = $comment->DeleteData($where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }


    /**
     * [index_cate 分类列表]
     * @return [type] [description]
     * @author [xenos]
     */
    public function index_cate(){

        $key = $so['cate_name'] = input('key');
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $map = [];
        $map['closed'] = 0;
        if($key&&$key!==""){
            $map['cate_name'] = ['like',"%" . $key . "%"];
        }
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }
        if($end_date){
            $map['create_time'] = $so['create_time '] = ['<',strtotime($end_date)];
        }
        $Nowpage = input('get.p') ? input('get.p'):1;
        $cate = new ArticleCategory();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $cate->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $cate->where($map)->order('orderby desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $lists[$k]['edit_url'] = \think\Url::build('article/edit_cate','id='.$v['id']);
        }
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        $this->assign('val', $key);
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="文章分类";
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
        if(request()->isPost()){
            $param = input('post.');
            $cate = new ArticleCategory();
            $arcate = $cate->field('orderby')->order('orderby desc')->find();
            $orderby = 1;
            if($arcate){
                $orderby = $arcate['orderby']+1;
            }
            $param['orderby'] = $orderby;
            $flag = $cate->InsertData($param);
            return json(['status' => $flag['status'],  'url'=>'/admin/article/index_cate', 'data' => '', 'msg' => $flag['msg']]);

        }
        return $this->fetch();

    }


    /**
     * [edit_cate 编辑分类]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function edit_cate()
    {

        $cate = new ArticleCategory();
        if(request()->isPost()){
            $param = input('post.');
            $where['id'] = $param['id'];
            $flag = $cate->UpdateData($param,$where);
            return json(['status' => $flag['status'],  'url'=>'/admin/article/index_cate' , 'data' => '', 'msg' => '修改成功']);

        }

        $id = input('param.id');
        //\think\Url::build();
        $cateInfo = $cate->get($id);
        $this->assign('cateInfo',$cateInfo);
        return $this->fetch();

    }

    /**
     * [del_cate 删除分类]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function del_cate()
    {
        $id = input('param.id');
        $cate = new ArticleCategory();
        $article = new Art();
        $item_where['cid'] = $id;
        $where['id'] = $id;
        $article->DeleteData($item_where);
        $flag = $cate->DeleteData($where);
        return json(['status' => $flag['status'],  'url'=>'reload' , 'data' => '', 'msg' => $flag['msg']]);
    }

    public function batch_cate(){
        $id = input('param.checkbox');
        $cate = new ArticleCategory();
        $article = new Art();
        $item_where['cid'] = ['in',$id];
        $where['id'] = ['in',$id];
        $article->DeleteData($item_where);
        if($cate->DeleteData($where)){
            return json(['status' => 200,  'url'=>'reload' , 'data' => '', 'msg' => '批量删除成功']);
        }else{
            return json(['status' => -1,  'url'=>'reload' , 'data' => '', 'msg' => '批量删除失败']);
        }
    }


    /**
     * [set_orderby 设置分类排序]
     * @return [type] [description]
     * @author [jonny] [980218641@qq.com]
     */
    public function set_orderby()
    {
        $id = input('param.id');
        $orderby = input('param.orderby');
        $where['id'] = $id;
        $param['orderby'] = $orderby;
        $cate = new ArticleCategory();
        $flag = $cate->SetParam($param,$where);
        return json(['status' => $flag['status'],  'url'=>'reload' , 'data' => '', 'msg' => $flag['msg']]);
    }


    /**
     * [cate_state 分类状态]
     * @return [type] [description]
     * @author [xenos]
     */
    public function cate_state()
    {
        $id=input('param.id');
        $cate = new ArticleCategory();
        $status = $cate->where(array('id'=>$id))->value('closed');//判断当前状态情况
        if($status==0)
        {
            $flag = $cate->where(array('id'=>$id))->setField(['closed'=>1]);
            return json(['status' => $flag['status'],  'url'=>'reload', 'data' => $flag['data'], 'msg' => '已禁止']);
        }
        else
        {
            $flag = $cate->where(array('id'=>$id))->setField(['closed'=>0]);
            return json(['status' => $flag['status'],  'url'=>'reload', 'data' => $flag['data'], 'msg' => '已开启']);
        }

    }

    /**
     * 获取文章二级分类
     */
    public function get_cate_child(){

        $cate = new ArticleCategory();
        $pid=input('post.cate_id');
        $cate_list=$cate->where('pid',$pid)->field('id,cate_name')->select();
        return json($cate_list);
    }

}