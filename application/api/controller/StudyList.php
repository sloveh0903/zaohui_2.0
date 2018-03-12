<?php
namespace app\api\controller;
use think\Controller;

/**
 * Class StudyList 学习记录
 * @package app\api\controller
 */
class StudyList extends Controller
{
    public function _initialize()
    {

    }

    /**
     * 添加学习记录
     * @author王宣成
     * @return Json
     */
    public function postAdd(){
        $vid = input('vid',0);
        $uid = input('uid',0);
        $status = input('status',1);
        $video =  find('Video',['id'=>$vid,'audit'=>'1']);
        if(!$video) errorJson('视频不存在');
        $cid = $video['cid'];
        $day = date('Y-m-d');
        $data = [
            'vid'=>$vid,
            'uid'=>$uid,
            'cid'=>$cid,
            'status'=>$status,
            'day' => $day
        ];
        
        $study_result = find('StudyList',['vid'=>$vid,'uid'=>$uid]);
        if($study_result){
            if($study_result['day'] == $day && $study_result['cleaned']==0){
                successJson('操作成功');
            }
            $studylist = update('StudyList',['id'=> $study_result['id']],['cleaned'=>0,'day'=>$day]);
            successJson('操作成功');
        }
        $studylist = insert('StudyList',$data);
        if(!$studylist) errorJson('操作失败');
        successJson('操作成功');
    }

    /**
     * 修改学习记录
     * @author王宣成
     * @return Json
     */
    public function postUpdate(){
        $vid = input('vid');
        $uid = input('uid');
        $status = input('status',1);
        $study_time = input('study_time');
        $day = date('Y-m-d');
        $video =  find('Video',['id'=>$vid,'audit'=>'1'],'cid,lenght');
        if(!$video) errorJson('视频不存在');
        $cid = $video['cid'];
        $data = [
            'vid'=>$vid,
            'uid'=>$uid,
            'cleaned'=>0
        ];
        $study = find('StudyList',$data,'id,day,cleaned,status');
        if(!$study) errorJson('数据不存在');
        if($study_time > $video['lenght']*0.7){
            if($study['day'] ==$day && $study['cleaned']==0 && $study['status']==2){
                successJson('操作成功');
            }else{
                $status = 2;
                $save = [
                    'status' => $status,
                    'cid'=>$cid,
                    'day'=>$day,
                    'cleaned'=>0
                ];
                if($study_time){
                    $save['study_time']= intval($study_time);
                }
                $study_id = $study['id'];
                $studylist = update('StudyList',['id'=>$study_id],$save);
                if(!$studylist) errorJson('操作失败');
                //增加积分  todo
                $integral_api = new Integral();
                $integral_api->study_video($uid, $vid, $video['lenght']);
                successJson('操作成功');
            }
        }
        successJson('操作成功');
        
        
        
    }

    /**
     * 获取单条学习记录
     * @author王宣成
     * @return Json
     */
    public function getDetail(){
        $vid = input('vid');
        $uid = input('uid');
        $data = [
            'vid'=>$vid,
            'uid'=>$uid
        ];
        $study = find('StudyList',$data,false,false,['id'=>'desc']);
        if(!$study) errorJson('数据不存在');
        successJson('操作成功',$study);
    }

    /**
     * 获取最近的一条学习记录
     * @author王宣成
     * @return Json
     */
    public function getLately(){
        $second = 0;
        $cid = input('cid',0);
        $uid = input('uid');
        $where['uid'] = $uid;
        $where['cid'] = $cid;
        $where['study_time'] = ['>',0];
        $result = find('StudyList',$where,false,false,['id'=>'desc']);
        if(!$result) {
            $video_category = find('VideoCategory',['cid'=>$cid],'id',false,['orderby'=>'asc']);
            if(!$video_category)  errorJson('暂无视频');
            $category_id = $video_category['id'];
            $filter['tid'] = $category_id;
        }else{
            $filter['id'] = $vid = $result['vid'];
            $second =  $result['study_time'];
        }
        $filter['audit'] = 1;
        $video = find('Video',$filter,false,['orderby'=>'asc']);
        $data['vid'] = $video['id'];
        $data['polyv_id'] = $video['vid'];
        $data['transcoding_path'] = $video['transcoding_path'];
        $data['blob_path'] = $video['blob_path'];
        $data['vtype'] = $video['type'];
        $data['tid'] = $video['tid'];
        $data['path'] =  $video['video_path'];
        $data['second'] = $second;
        $data['free'] = $video['free'];
        successJson('操作成功',$data);
    }
    /**
     * 获取最近的一条试看视频
     */
    public function getfreevideo(){
        $second = 0;
        $cid = input('cid',0);
        $vidio_free = get_first_free_video($cid);
        if($vidio_free){
            $data['vid'] = $vidio_free['id'];
            $data['polyv_id'] = $vidio_free['vid'];
            $data['transcoding_path'] = $vidio_free['transcoding_path'];
            $data['blob_path'] = $vidio_free['blob_path'];
            $data['vtype'] = $vidio_free['type'];
            $data['tid'] = $vidio_free['tid'];
            $data['path'] =  $vidio_free['video_path'];
            $data['second'] = $second;
            $data['free'] = $vidio_free['free'];
            successJson('操作成功',$data);
        }
        
    }
    
    /**
     * 我的学习记录
     */
    public function getrecordList(){
        $uid = input('uid',0);
        $where['uid'] = $uid;
        $strtotime = strtotime(date('Y-m-d'));
        $week = 3600*24*7;
        $diff = $strtotime - $week;
        $where['update_time'] = ['>',$diff];
        $where['cleaned']=0;
        $browse_record = lists('StudyList',$where,['update_time'=>'desc']);
        $vid_attr = [];
        $retLists = [];
        $recordList = [];
        $arrlist = [];
        if($browse_record){
            foreach ($browse_record as $b) {
                $vid_attr[$b['vid']] = $b['vid'];
                $recordList[$this->T($b['update_time'])][$b['vid']] = $b;
            }
            foreach ($recordList as $list) {
                foreach ($list as $item) {
                    $arrlist[] = $item;
                }

            }
            if($vid_attr){
                $video_where['audit'] = 1;
                $video_where['id'] = ['in',$vid_attr];
                $video = lists('Video',$video_where);
                $cid_arr = [];
                foreach ($video as $vd) {
                    $cid_arr[$vd['cid']] = $vd['cid'];
                }
                $course_where['cid'] = ['in',$cid_arr];
                $course = lists('Course',$course_where,['cid'=>'desc'],'cid,title');
                $cou = [];
                foreach ($course as $c) {
                    $cou[$c['cid']] = $c;
                }
                foreach ($arrlist as &$record) {
                    foreach($video as $v){
                        if($record['vid'] == $v['id']){
                            $record['cid'] = $v['cid'];
                            $record['video_title'] = $v['title'];
                        }
                    }
                    if(array_key_exists($record['cid'],$cou)){
                        $retLists[$this->T($record['update_time'])][$cou[$record['cid']]['title']][] = $record;
                    }
                }
            }

        }
        $data['browse_record'] = $retLists;
        $data['count'] = count($retLists);
        successJson('操作完成',$data);

    }
    //清除
    public function getclean_studylist($uid)
    {
        $sty_where['uid'] = $uid;
        $sty_where['closed'] = 0;
        $db_clean_bool = db('study_list')->where($sty_where)->update(['cleaned'=>1]);
        successJson('操作完成');
    }

    protected function T($date)
    {
        //获取今天凌晨的时间戳
        $day = strtotime(date('Y-m-d',time()));
        //获取昨天凌晨的时间戳
        $pday = strtotime(date('Y-m-d',strtotime('-1 day')));

        $time = strtotime($date);
        if($time<$pday){
            $str = date('Y-m-d',$time);
        }elseif($time<$day && $time>$pday){
            $str = "昨天";
        }else{
            $str = "今天";
        }
        return $str;
    }
}
