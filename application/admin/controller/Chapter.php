<?php
namespace app\admin\controller;
use app\admin\model\VideoCategory;
use think\Controller;

class Chapter extends Controller
{

    public function _initialize(){
       parent::_initialize();
    }

    /**
     * 首页概况
     */
    public function index()
    {
        $account = db('qiniu_account')->field('money,key,secret,bucket,domain')->find();
        if($account['money'] <= 0){
            $this->error('账户余额不足，请及时充值');
        }
        $cid = input('cid');
        if(!$cid){
            $this->error('缺少课程id');
        }
        $course = find('Course',['cid'=>$cid],'',true);
        if(!$course){
            $this->error('课程不存在');
        }
        $type = input('type','create');
        $chapter  = db('video_category')->where(['cid'=>$cid,'closed'=>0])->order('orderby asc')->select();
        if(!empty($chapter))
        {
            foreach($chapter as $v){
                $in[] = $v['id'];
            }
            $v_where['tid'] = ['in',$in];
            $v_where['closed'] = 0;
            $video  = db('video')->where($v_where)->order('orderby asc')->select();
            foreach($chapter as &$c){
                $c['video'] = [];
                foreach($video as $v){
                    if($c['id'] == $v['tid']){
                        $c['video'][] = $v;
                    }
                }
            }
            unset($c);
        }
        $qiniu = new \qn\Qiniu($account['key'],$account['secret'],$account['bucket']);
        $token = $qiniu->getToken();
        $domain = "http://".$account['domain'];
        $this->assign('type',$type);
        $this->assign('cid',$cid);
        $this->assign('token',$token);
        $this->assign('domain',$domain);
        $this->assign('id',$cid);
        $this->assign('chapter',$chapter);
        return view();
    }
    /**
     * 添加章节
     */
    public function create()
    {
        $cid = input('cid');
        $course = db('course')->where(['cid'=>$cid,'closed'=>0])->find();
        $orderby = db('VideoCategory')->where(['cid'=>$cid,'closed'=>0])->order('orderby desc')->value('orderby');
        if(!$course){
            errorJson('课程不存在');
        }
        $title = input('title');
        if(!$title){
            errorJson('章节名称不能为空');
        }
        if($orderby){
            $data['orderby'] =$orderby+1;
        }else{
            $data['orderby'] = 1;
        }
        $data['cid'] = $cid;
        $data['cate_name'] = $title;
        $id = insert('VideoCategory',$data,'id');
        if(!$id){
            errorJson('添加章节失败');
        }
        $data['id'] = $id;
        successJson('操作完成',$data);
    }
    /**
     * 编辑章节
     */
    public function update(){
        $id = input('id');
        $data = input();
        if(!$id){
            errorJson('缺少章节id参数');
        }
        if(empty($data['title'])){
            errorJson('章节名称不能为空');
        }
        $data['cate_name']  = $data['title'];
        unset($data['token']);
        unset($data['acid']);
        unset($data['title']);
        update('VideoCategory',['id'=>$id],$data);
        successJson('操作完成');
    }
    /**
     * 删除章节
     */
    public function delete(){
        $id = input('id');
        if(!$id){
            errorJson('缺少章节id参数');
        }
        $video = find('Video',['tid'=>$id,'closed'=>0]);
        if($video){
            errorJson('该章节内含有视频，请先清除视频后再行删除',-1);
        }
        $delete_bool = db('VideoCategory')->where('id',$id)->update(['closed' => 1]);
        successJson('操作完成');
    }

    public function checkVideo(){
        $id = input('id');
        if(!$id){
            errorJson('缺少章节id参数');
        }
        $video = find('Video',['tid'=>$id,'closed'=>0]);
        if($video){
            $data['flag'] = true;
        }else{
            $data['flag'] = false;
        }
        successJson('操作完成',$data);
    }
    
    //章节上下移动排序
    public function moveUpDown(){
        $id = input('id');
        $cid = input('cid');
        $updown = input('updown');
        $videoc = new VideoCategory();
        $data_this = $videoc->where('id',$id)->find();
        $orderby = $data_this['orderby'];
        $map['cid'] = $cid;
        $map['closed'] = 0;
        if($updown == "up"){
            $map['orderby'] = ['<',$orderby];
            $order = 'orderby desc';
        }else{
            $map['orderby'] = ['>',$orderby];
            $order = 'orderby asc';
        }
        $data_next = $videoc->where($map)->order($order)->find();
        if($data_next){
            $next_id = $data_next['id'];
            $next_order = $data_next['orderby'];
            $list = [
                ['id'=>$id, 'orderby'=>$next_order],
                ['id'=>$next_id, 'orderby'=>$orderby]
            ];
            $videoc->saveAll($list);
        }
        successJson('操作完成');
    }
}
