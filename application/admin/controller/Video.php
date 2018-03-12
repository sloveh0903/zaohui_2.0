<?php

namespace app\admin\controller;
use app\admin\model\Course;
use app\admin\model\Video as Vdo;
use think\Db;

class Video extends Base
{
    public function _empty(){
        return $this->index();
    }

    /**
     * [index 视频列表]
     * @author [xenos]
     */
    public function index(){

        $key = $so['title'] = input('key');
        $start_date = $so['start_date'] = input('start_date');
        $end_date = $so['end_date'] = input('end_date');
        $cid = $so['cid'] = input('cid');
        $tid = $so['tid'] = input('tid');
        $map = [];
        $map['closed'] = 0;
        if($key&&$key!==""){
            $map['title'] = ['like',"%" . $key . "%"];
        }
        if($start_date){
            $map['create_time'] = ['>',strtotime($start_date)];
        }
        if($cid){
            $map['cid'] = $so['cid'] = $cid;
        }
        if($tid){
            $map['tid'] = $so['tid'] = $tid;
        }
        if($end_date){
            $map['create_time'] = $so['create_time '] = ['<',strtotime($end_date)];
        }
        $Nowpage = input('get.p') ? input('get.p'):1;
        $course = new Course();
        $courselist = $course->where(array('closed'=>'0'))->field('cid,title')->order('cid desc')->select();
        $video = new Vdo();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $video->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $video->where($map)->order('id desc')->limit($start,$limits)->select();
        foreach ($lists as $k=>$v) {
            $lists[$k]['edit_url'] = \think\Url::build('video/edit_video','id='.$v['id']);
            $lists[$k]['type_url'] = \think\Url::build('video/video_type','id='.$v['id']);
            $lists[$k]['course_name'] = "";
            if($course_name = Db::name('course')->where('cid',$v['cid'])->value('title')){
                $lists[$k]['course_name'] = $course_name;
            }
            if($typename = Db::name('video_category')->where('id',$v['tid'])->value('cate_name')){
                $lists[$k]['type_name'] = $typename ? $typename : "" ;
            }else{
                $lists[$k]['type_name'] = "";
            }
        }
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        $this->assign('val', $key);
        $this->assign('tid', $tid);
        $this->assign('cid', $cid);
        $this->assign('courselist', $courselist);
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="视频列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();

    }
    /**
     * 添加视频
     */
    public function create()
    {
        $hash = input('hash');
        $course_id = input('course_id');//课程id
        $chapter_id = input('chapter_id');//章节id（video_category id）
        $last_video = find('Video',array('cid'=>$course_id,'tid'=>$chapter_id),'',false,['orderby'=>'desc']);
        $key = input('key');
        $domain = input('domain');
        $lenght = $width = $height = 0;
        $title = input('title');
        $video_path = input('video_path');
        $qiniuAccount = Db::name('qiniu_account')->limit(1)->find();
        $qiniu = new \qn\Qiniu($qiniuAccount['key'],$qiniuAccount['secret'],$qiniuAccount['bucket']);
        
        $data = [
            'cid'=>$course_id,
            'tid'=>$chapter_id,
            'title'=>$title,
            'video_path'=>$video_path,
            'desc'=>$title,
        ];
        $codec_name = '';
        $video_info = json_decode(doCurlGetRequest($video_path.'?avinfo'),true);
        if(isset($video_info['format'])){
            //时长按秒计算
            $duration = ceil($video_info['format']['duration']);
            $data['lenght'] = $lenght = $duration;
            if($video_info['streams'][0]['codec_type'] == 'video'){
                $width = $video_info['streams'][0]['width'];
                $height = $video_info['streams'][0]['height'];
                $codec_name = $video_info['streams'][0]['codec_name'];
            }else{
                $width = $video_info['streams'][1]['width'];
                $height = $video_info['streams'][1]['height'];
                $codec_name = $video_info['streams'][1]['codec_name'];
            }
            $ct = convertCost($lenght,($width>$lenght)?$width:$height);
        }
        

        //获取视频后缀名
        $pathinfo = pathinfo($video_path, PATHINFO_EXTENSION);
        $data['title'] = $title;
        //转mp4
	    if($result = $qiniu->qiniuConvert2($key)){
		  $data['video_id'] = $result['id'];
		  $data['video_path'] = 'http://'.$qiniuAccount['domain'].'/'.$result['new_key'];
		  if(isset($ct)){
			$convert['money'] = $ct['money'];
			$convert['videopath'] = $data['video_path'];
			$convert['resolution'] = $ct['resolution'];
			$convert['lenght'] = $lenght;
			$convert['create_time'] = strtotime(date("Y-m-d"));
			$convert['update_time'] = strtotime(date("Y-m-d"));
			Db::name('convert')->insert($convert);
		  }
		}
	    if($result = $qiniu->qiniuConvert($key)){
		  $data['video_id'] = $result['id'];
		  $data['transcoding_path'] = 'http://'.$qiniuAccount['domain'].'/'.$result['new_key'];
		  if(isset($ct)){
			$convert['money'] = $ct['money'];
			$convert['videopath'] = $data['transcoding_path'];
			$convert['resolution'] = $ct['resolution'];
			$convert['lenght'] = $lenght;
			$convert['create_time'] = strtotime(date("Y-m-d"));
			$convert['update_time'] = strtotime(date("Y-m-d"));
			Db::name('convert')->insert($convert);
		  }
		}
        $course = find('Course',['cid'=>$course_id]);
        if(!$course){
            errorJson('课程不存在');
        }
        $map['cid'] = $course_id;
        $map['id'] = $chapter_id;
        $chapter = db('video_category')->where($map)->find();
        if(!$chapter){
            errorJson('章节不存在');
        }
        if($last_video){
            $data['orderby'] = $last_video['orderby']+1;
        }else{
            $data['orderby'] = 1;
        }
        $id = insert('Video',$data,'id');
        if(!$id){
            errorJson('添加视频失败');
        }
        $data['video_id'] = $id;
        successJson('操作完成',$data);
    }
    
    /**
     * 编辑视频
     */
    public function update()
    {
        $id = input('id');
        $data = input();
        if(!$id){
            errorJson('缺少视频id参数');
        }
        if(isset($data['tid'])){
            $last_video = find('Video',array('tid'=>$data['tid']),'',false,['orderby'=>'desc']);
            $data['orderby'] = $last_video['orderby']+1;
        }
        update('Video',['id'=>$id],$data);
        successJson('操作完成');
    }
    
    /**
     * 删除视频
     */
    public function delete()
    {
        $id = input('id');
        if(!$id){
            errorJson('缺少视频id参数');
        }
        $delete_bool = db('video')->where('id',$id)->update(['closed' => 1]);
        if($delete_bool)
        {
            successJson('删除成功');
        }
    }
    
    /**
     * 视频排序
     */
    public function orderby(){
        $this_id = input('this_id');
        $next_id = input('next_id');
        $chapter_id = input('chapter_id');
        $cid = input('cid');
        if(!$cid){
            errorJson('视频cid必须');
        }
        $where['tid'] = $chapter_id;
        if($this_id == $next_id){
            $query = lists('Video',$where,['orderby'=>'asc']);
            successJson($query);
        }
        $this_chapter = find('Video',['id'=>$this_id]);
        $next_chapter = find('Video',['id'=>$next_id]);
        if($this_id){
            $this_orderby = $this_chapter['orderby'];
        }else{
            errorJson('参数错误');
        }
        if($next_id){
            $next_orderby = $next_chapter['orderby'];
        }else{
            errorJson('参数错误');
        }
        $query = lists('Video',$where,['orderby'=>'asc']);
        if($this_orderby > $next_orderby){
            $min_orderby = $next_orderby;
            $max_orderby = $this_orderby;
        }else{
            $min_orderby = $this_orderby;
            $max_orderby = $next_orderby;
        }
        $between_course = [];
        foreach($query as $v){
            if($v['orderby'] > $min_orderby && $v['orderby'] < $max_orderby){
                $between_course[] = $v;
            }
        }
        update('Video',['id'=>$this_id],['orderby'=>$next_orderby]);
        $between_course[] = $next_chapter;
        if($this_orderby > $next_orderby){
            foreach($between_course as $v){
                setInc('Video',['id'=>$v['id']],'orderby');
            }
        }else{
            foreach($between_course as $v){
                setDec('Video',['id'=>$v['id']],'orderby');
            }
        }
        $query = lists('Video',$where,['orderby'=>'asc']);
        successJson($query);
    }
    
    //视频上下移动排序
    public function moveUpDown(){
        $id = input('id');
        $updown = input('updown');
        $video =new Vdo();
        $data_this = $video->where('id',$id)->find();
        $orderby = $data_this['orderby'];
        $cid = $data_this['cid'];//同一课程
        $tid = $data_this['tid'];//同一视频分类
        $map['cid'] = ['=',$cid];
        $map['tid'] = ['=',$tid];
        $map['closed'] = 0;
        if($updown == "up"){
            $map['orderby'] = ['<',$orderby];
            $order = 'orderby desc';
        }else{
            $map['orderby'] = ['>',$orderby];
            $order = 'orderby asc';
            
        }
        $data_next = $video->where($map)->order($order)->find();
        if($data_next){
            $next_id = $data_next['id'];
            $next_order = $data_next['orderby'];
            $list = [
                ['id'=>$id, 'orderby'=>$next_order],
                ['id'=>$next_id, 'orderby'=>$orderby]
            ];
            $video->saveAll($list);
        }
        successJson('操作完成');
    }
    

    /**
     * 视频试看功能
     * Create By Xenos
     * Return
     */
    public function set_free(){
        $id = input('param.id');
        $free = input('param.free');
        $video = new Vdo();
        $where['id'] = $id;
        $param['free'] = $free;
        $flag = $video->SetParam($param,$where);
        return json(['status' => $flag['status'],  'url'=>'reload' , 'data' => '', 'msg' => $flag['msg']]);
    }

    public function setAudit(){
        $id = input('param.id');
        $audit = input('param.audit');
        $video = new Vdo();
        $where['id'] = $id;
        $param['audit'] = $audit;
        $flag = $video->SetParam($param,$where);
        return json(['status' => $flag['status'],  'url'=>'reload' , 'data' => '', 'msg' => $flag['msg']]);
    }

    public function uploadVideo(){
        $qiniu = new \qn\Qiniu();
        $id = $qiniu->qiniuConvert('2.mp4');
        p($id);
        /*$token = $qiniu->getToken();
        $domain = "http://oqwyd0iz7.bkt.clouddn.com/";
        $this->assign('token',$token);
        $this->assign('domain',$domain);*/

        /*return $this->fetch('upload');*/
    }



}