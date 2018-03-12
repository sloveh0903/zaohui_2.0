<?php
namespace app\api\controller;
use think\Controller;

/**
 * Class BrowseRecord 浏览记录
 * @package app\api\controller
 */
class BrowseRecord extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->recordList();
    }

    /**
     * 我的学习记录
     */
    public function recordList(){
        $uid = input('uid',0);
        $typeid = 2;
        $where['uid'] = $uid;
        $where['typeid'] = $typeid;
        $where['itemid'] = ['gt','0'];
        $strtotime = strtotime(date('Y-m-d'));
        $week = 3600*24*7;
        $diff = $strtotime - $week;
        $where['update_time'] = ['>',$diff];
        $browse_record = lists('BrowseRecord',$where,['update_time'=>'desc']);
        $vid_attr = [];
        $retLists = [];
        $recordList = [];
        $arrlist = [];
        if($browse_record){
            foreach ($browse_record as $b) {
                $vid_attr[$b['itemid']] = $b['itemid'];
                $recordList[$this->T($b['update_time'])][$b['itemid']] = $b;
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
                        if($record['itemid'] == $v['id']){
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


    /**
     * 浏览历史列表
     * @author王宣成
     * @return Json
     */
    public function getIndex(){
        $uid = input('uid',0);
        $typeid = input('typeid',1);
        $where['uid'] = $uid;
        $where['typeid'] = $typeid;
        //最近7天的数据
        $strtotime = strtotime(date('Y-m-d'));
        $week = 3600*24*7;
        $diff = $strtotime - $week;
        $where['create_time'] = ['>',$diff];
        $browse_record = lists('BrowseRecord',$where,['id'=>'desc']);
        /*p($where);
        die;*/
        //视频记录的
        $date_arr = [];
        if($typeid == 1){
            $id_arr = $cid_arr= [];
            foreach($browse_record as $v){
                $id_arr[$v['itemid']] = $v['itemid'];
            }
            $video_where['audit'] = 1;
            $video_where['cid'] = ['in',$id_arr];
            $video = lists('Video',$video_where);

            foreach($browse_record as &$b){
                $key = $b['create_date'] = $b['create_time'];
                $date_arr[$key] = $key;
                $b['video_list'] = [];
                foreach($video as $v){
                    if($b['itemid'] == $v['cid']){
                        $b['video_list'][] = $v;
                        $cid_arr[$v['cid']] = $v['cid'];
                    }
                }
            }
            /*$course_where['cid'] =  ['in',$cid_arr];
            $course = lists('Course',$course_where);*/
            unset($b);
        }

        $i = 0;
        foreach($date_arr as &$v){
            foreach($browse_record as $b){
                if($v == $b['create_date']){
                    $v[$i] = $b;
                    $i++;
                }
            }
        }
        unset($v);
        $browse_record = [];
        $i = 0;
        foreach($date_arr as $k=>$v){
            $browse_record[$i]['list'] = $v;
            $browse_record[$i]['date'] = $k;
            $i++;
        }
        unset($v);
        $data['browse_record'] = $browse_record;
        successJson('操作完成',$data);
    }

    /**
     * 增加浏览记录
     * @author王宣成
     * @return Json
     */
    public function postAdd(){
        $uid = input('uid',0);
        $typeid = input('typeid',0);
        $itemid = input('itemid',0);
        $data = [
            'uid' => $uid,
            'typeid' => $typeid,
            'itemid' => $itemid
        ];
        $where['uid'] = $uid;
        $where['typeid'] = $typeid;
        $where['itemid'] = $itemid;
        $star_time = strtotime(date('Ymd'));
        $end_time = strtotime(date('Ymd')) + 86400;
        $where['create_time'] = ['>=',$star_time];
        $where['create_time'] = ['<=',$end_time];
        $find = find('BrowseRecord',$where);
        if($find)  successJson('今天已浏览过');
        $result = insert('BrowseRecord',$data);
        if(!$result) errorJson('添加失败');
        successJson('操作完成');
    }

    /**
     * 删除浏览记录
     * @author王宣成
     * @return Json
     */
    public function postDel(){
        $uid = input('uid');
        $result = delete('BrowseRecord',['uid'=>$uid]);
        if(!$result) errorJson('删除失败');
        successJson('操作完成');
    }

}
