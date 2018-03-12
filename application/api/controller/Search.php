<?php
namespace app\api\controller;
use think\Controller;
use think\Db;

/**
 * Class Search 搜索
 * @package app\api\controller
 */
class Search extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->getIndex();
    }


    /**
     * 课程视频搜索
     * @author王宣成
     * @return Json
     */
    public function getIndex(){
        $keyword = input('keyword','');
        $uid = input('uid');
        //$type = input('type',1);
        $page = input('page',1);
        $size = input('size',10);
        $audit = input('audit',1);  //1上架  0下架  2全部
        $data['course'] = [];
        $data['course_count'] = 0;
        $data['video'] = [];
        $data['video_count'] = 0;
        $where['page'] = $page;
        $where['size'] = $size;
        $where['title'] = $keyword;
        //$where['audit'] = $audit;

        $orderby = ['create_time'=>'desc'];
        //if($type == 1){
            //课程
            if(!empty($keyword)){
                $where['title'] = ['like','%'.$keyword.'%'];
            }
            //  audit 只有一个表(Course)中拥有  其他表中没有此字段
            $course_where  = $where;
            $course_where['audit']=$audit;
              //end
            $course = lists('Course',$course_where,$orderby);
            unset($where['page']);
            unset($where['size']);
            // 
            unset($course_where['page']);
            unset($course_where['size']);
             //end
//             if(empty($course)) {
//                 $course = lists('Course',['page'=>1,'size'=>3],$orderby);
//                 $data['course_count'] = 0;
//             }else{
//                $data['course_count'] = counts('Course',$course_where);
//            }
//             if(empty($keyword)){
//                 $course = lists('Course',['page'=>1,'size'=>3],$orderby);
//                 $data['course_count'] = 0;
//             }
            $courseList = array();
            $uid_arr = [];
            if($course){
                foreach($course as $v){
                    $uid_arr[] = $v['uid'];
                }
                $user_where['uid'] = ['in',$uid_arr];
                $user_field = 'uid,uname,face,info,realname,follow';
                $user = lists('User',$user_where,[],$user_field);
                $ret_user = $courseList = [];
                foreach ($user as $list) {
                    $ret_user[$list['uid']] = $list;
                }
                foreach ($course as $v) {
                    $ret_course = [];
                    $ret_course['cid'] = $v['cid'];
                    $ret_course['uid'] = $v['uid'];
                    $ret_course['title'] = $v['title'];
                    $ret_course['face'] = $v['face'];
                    $ret_course['info'] = $v['info'];
                    $ret_course['desc'] = $v['desc'];
                    $ret_course['banner'] = $v['banner'];
                    $ret_course['price'] = $v['price'];
                    $ret_course['free'] = $v['free'];
                    $ret_course['follow'] = 0;
                    $ret_course['realname'] = '';
                    if($v['virtual_amount']){
                        $studycount = $v['virtual_amount'];
                    }else{
                        $studycount = db('order')->where(['course_id'=>$v['cid'],'pay_status'=>1])->count('id');
                    }
                    $ret_course['study_count'] = $studycount?$studycount:0 ;
                    $ret_course['uname'] = '';
                    $ret_course['info'] = '';
                    if(array_key_exists($v['uid'],$ret_user)){
                        $ret_course['realname'] = $ret_user[$v['uid']]['realname'];
                        $ret_course['info'] = $ret_user[$v['uid']]['info'];
                        $ret_course['follow'] = $ret_user[$v['uid']]['follow'];
                        $ret_course['uname'] = $ret_user[$v['uid']]['uname'];
                    }
                    $courseList[] = $ret_course;
                }
            }
            $data['course'] = $courseList;
       // }

        //if($type == 2){
            //视频
            if(!empty($keyword)){
                $where['title'] = ['like','%'.$keyword.'%'];
            }
            $where['page'] = $page;
            $where['size'] = $size;
            $course = lists('Course',['audit'=>1]);
            
            $cid_arr = [];
            foreach($course as $v){
                $cid_arr[] = $v['cid'];
            }
            $where['audit'] = 1;
            $where['cid'] = ['in',$cid_arr];
            $video = lists('Video',$where,$orderby);
            unset($where['page']);
            unset($where['size']);
//             if(empty($video)) {
//                 $video = lists('Video',['page'=>1,'size'=>3],$orderby);
//                 $data['video_count'] = 0;
//             }else{
               // $data['video_count'] = counts('Video',$where);
//            }

//            if(empty($keyword)){
//                $video = lists('Video',['page'=>1,'size'=>3],$orderby);
//                $data['video_count'] = 0;
//            }
            $videoList = array();
            if($video){
                $studylist = $videoList = [];
                if($uid) {
                    $vid_arr = [];
                    foreach ($video as $v) {
                        $vid_arr[] = $v['id'];
                    }
                    $study_where['vid'] = ['in', $vid_arr];
                    $study_where['uid'] = $uid;
                    $study_where['study_time'] = ['>', 0];
                    $studylist = lists('StudyList', $study_where, ['create_time' => 'asc']);
                }
                foreach($video as $v2){
                    $ret_video = [];
                    $ret_video['id'] = $v2['id'];
                    $ret_video['cid'] = $v2['cid'];
                    $ret_video['tid'] = $v2['tid'];
                    $ret_video['title'] = $v2['title'];
                    $ret_video['video_path'] = $v2['video_path'];
                    $ret_video['lenght'] = changeTimeType($v2['lenght']);
                    $ret_video['free'] = $v2['free'];
                    $ret_video['desc'] = $v2['desc'];
                    $ret_video['create_time'] = $v2['create_time'];
                    $ret_video['study_time'] = '00:00:00';
                    $ret_video['second'] = 0;
                    $videoList[] = $ret_video;
                }
            }
            
            $data['video'] = $videoList;
       // }
        successJson('操作完成',$data);
    }

    /**
     * 搜索词
     */
    public function getKeywords(){
        $search = lists('Search',[],['orderby' => 'asc']);
        $data['search'] = $search;
        successJson('操作完成',$data);
    }

}
