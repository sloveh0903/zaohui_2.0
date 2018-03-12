<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use app\admin\model\Course;
use app\model\VideoCategory;

/**
 * Class Chapter 章节
 * @package app\api\controller
 */
class Chapter extends Controller
{
    public function _initialize(){
        parent::_initialize();
    }

    /**
     * 章节列表
     */
    public function index(){
        $cid = input('cid',0);
        $uid = input('uid');
        $where['cid'] = $cid;
        $where['closed'] = 0;
        $query = lists('VideoCategory',$where,['orderby'=>'asc']);
        if(!empty($query)){
            foreach($query as $v){
                $in[] = $v['id'];
            }
            $video = db('video')->where(array('cid'=>$cid,'closed'=>'0','audit'=>'1'))->select();
            foreach($query as &$c){
                $c['video'] = [];
                foreach($video as $v){
                    if($c['id'] == $v['cid']){
                        $c['video'][] = $v;
                    }
                }
            }
            unset($c);
        }
        successJson($query);
    }
    /**
     * 视频列表
     */
    public function  videolist(){
        $tid = input('id');
        $uid = input('uid');
        $cid = input('cid');
        $vid_arr = [];
        $video = lists('Video',['tid'=>$tid,'closed'=>0,'audit'=>'1'],['orderby'=>'asc'],'id,free,vid,cid,tid,title,video_path,lenght');
        foreach($video as &$v){
            $v['is_look'] = 0;
            $studywhere['vid'] =  $v['id'];
            $studywhere['cid'] =  $cid;
            $studywhere['status'] = 2;//观看完
            $studywhere['cleaned'] = 0;
            $studywhere['uid'] = $uid;
            $is_study = db('study_list')->where($studywhere)->value('status');
            $v['is_study'] = $is_study>0?1:0;
            
            $v['lenght'] = changeTimeType($v['lenght']);
            $vid_arr[] = $v['id'];
        }
        unset($v);
        successJson('操作成功',$video);
    }





}
