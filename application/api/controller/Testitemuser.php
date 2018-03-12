<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use app\model\Testitem_user;

class Testitemuser extends Controller
{
    public function _initialize()
    {

    }

    /**
     * 答题记录
     */
    public function Add(){
        $parm = input();
        $bank_id =$parm['bank_id']; 
        $uid = $parm['uid'];
        $answer = $parm['answer'];
        $is_finish = db('testitem_record')->where(['bank_id'=>$bank_id,'uid'=>$uid])->value('is_finish');
        
        $map['closed'] = 0;
        $map['bank_id'] = $bank_id;
        $testitem_lists = db('testitem_list')->where($map)->order('id asc')->field('id,type,correct_option')->select();
        $answer_result[1]['name'] = '单选题';
        $answer_result[1]['count'] = 0;//单选题总数
        $answer_result[1]['right'] = 0;//单选题答对个数
        $answer_result[1]['wrong'] = 0;//单选题答错个数
        $answer_result[2]['name'] = '多选题';
        $answer_result[2]['count'] = 0;//多选题总数
        $answer_result[2]['right'] = 0;//多选题答对个数
        $answer_result[2]['wrong'] = 0;//多选题答错个数
        $answer_result[3]['name'] = '判断题';
        $answer_result[3]['count'] = 0;//判断题总数
        $answer_result[3]['right'] = 0;//判断题答对个数
        $answer_result[3]['wrong'] = 0;//判断题答错个数
        $answer_result[4]['name'] = '填空题';
        $answer_result[4]['count'] = 0;//填空题总数
        $answer_result[4]['right'] = 0;//填空题答对个数
        $answer_result[4]['wrong'] = 0;//填空题答错个数
        foreach ($testitem_lists as $key=>$list){
            $list_id = $list['id'];
            $select_option = $answer[$key];
            $correct_option = $list['correct_option'];
            $temp_id = $list['type'];
            if($temp_id==4){
                $correct_option_arr = json_decode($correct_option,true);
                if($correct_option_arr == $select_option){
                    $is_right = 1;
                    $right_count = 1;
                    $wrong_count = 0;
                }
                else{
                    $is_right = 0;
                    $right_count = 0;
                    $wrong_count =1;
                }
                $select_option = json_encode($select_option);
            }else{
                if($select_option ==$correct_option){
                    $is_right = 1;
                    $right_count = 1;
                    $wrong_count = 0;
                }
                else{
                    $is_right = 0;
                    $right_count = 0;
                    $wrong_count =1;
                }  
            }
            if($temp_id){
                $answer_result[$temp_id]['count'] = $answer_result[$temp_id]['count']+1;
                $answer_result[$temp_id]['right'] = $answer_result[$temp_id]['right']+$right_count;
                $answer_result[$temp_id]['wrong'] = $answer_result[$temp_id]['wrong']+$wrong_count;
            }
            $insetdata[$key]['bank_id'] = $bank_id;
            $insetdata[$key]['uid'] = $uid;
            if($is_finish){
                $insetdata[$key]['id'] = db('Testitem_user')->where(['closed'=>0,'list_id'=>$list['id'],'uid'=>$uid])->value('id');
                $insetdata[$key]['update_time'] = time();
            }else{
                $insetdata[$key]['create_time'] = time();
            }
            $insetdata[$key]['list_id'] = $list['id'];
            $insetdata[$key]['select_option'] = $select_option;
            $insetdata[$key]['is_right']= $is_right;  
            $answer_json = array_values($answer_result); 
        }
        if($is_finish){
            $testitem_user = new Testitem_user();
            $bool_data = $testitem_user->saveAll($insetdata);
        }else {
            //插入用户答题记录
             $bool_data = db('Testitem_user')->insertAll($insetdata);
        }
        if($bool_data){ 
            db('testitem_record')->where(['uid'=>$uid,'bank_id'=>$bank_id])->update(['is_finish'=>1,'update_time'=>time(),'record'=>json_encode($answer_json)]);
            header("Content-type: application/json");
            $str = '{"data":'.json_encode($answer_json).',"nowtime":'.time().',"code":1,"message":"成功"}';
            die($str);
        }
        else {
            errorJson('失败');
        }

    }
    
}
