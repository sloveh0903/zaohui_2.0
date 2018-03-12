<?php
namespace app\api\controller;
use app\admin\model\Video as videomodel;
use think\Controller;
use think\Db;
use app\admin\model\Course;
use app\admin\model\VideoCategory;
/**
 * Class Video 视频
 * @package app\api\controller
 */
class Video extends Controller
{

    public function _initialize(){
        parent::_initialize();
    }

    /**
     * 视频列表
     */
    public function index(){
        $course_id = input('course_id'); //课程id
        $chapter_id = input('chapter_id');//章节id（video_category id）
        $where = array();
        if($chapter_id){
            $where['tid'] = $chapter_id;
        }
        if($course_id){
            $where['cid'] = $course_id;
        }
        $query = lists('Video',$where,['orderby'=>'asc']);
        successJson($query);
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

        $video_info = json_decode(doCurlGetRequest($video_path.'?avinfo'),true);
        if(isset($video_info['format'])){
            //时长按秒计算
            $duration = ceil($video_info['format']['duration']);
            $data['lenght'] = $lenght = $duration;
            if($video_info['streams'][0]['codec_type'] == 'video'){
                $width = $video_info['streams'][0]['width'];
                $height = $video_info['streams'][0]['height'];
            }else{
                $width = $video_info['streams'][1]['width'];
                $height = $video_info['streams'][1]['height'];
            }
            $ct = convertCost($lenght,($width>$lenght)?$width:$height);
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


        //获取视频后缀名
        $pathinfo = pathinfo($video_path, PATHINFO_EXTENSION);

        $data['title'] = $title;


        //转mp4
        if($pathinfo != 'mp4'){
            if($result = $qiniu->qiniuConvert2($key)){
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
        //update('Course',['cid'=>$course_id]);
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
        $video = new videomodel;
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

    public function videoRealname(){
        $qiniuAccount = Db::name('qiniu_account')->limit(1)->find();
        $qiniu = new \qn\Qiniu($qiniuAccount['key'],$qiniuAccount['secret'],$qiniuAccount['bucket']);

        $oldname = '2017122591097.mp4';
        $newname = '2017122591097.m3u8';

        $qiniu->videorename($oldname,$newname);
        successJson('操作完成');
    }
}
