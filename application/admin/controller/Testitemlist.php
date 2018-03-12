<?php
namespace app\admin\controller;

class Testitemlist extends Base
{
    public function _empty(){
        return $this->index();
    }
    /**
     * 首页
     */
    public function index(){  
        $key = input('key');
        $bank_id = input('bank_id');
        if($bank_id){
            $map['bank_id']  =$bank_id;//题库id
        }
        if($key){
            $map['name'] = ['like',"%" . $key . "%"];
        }
        $map['closed']  = 0;
        $Nowpage = input('get.p') ? input('get.p'):1;
        $limits = 10;// 条数
        $start = $limits * ($Nowpage - 1);
        $lists =db('testitem_list')->where($map)->order('orderby desc')->limit($start,$limits)->select();
        $count = counts('Testitem_list',$map);//计算总页面
        $allpage = ceil($count / $limits);
        foreach ($lists as $key=>$val){
            $lists[$key]['name'] = str_replace('</p><p>', '', $lists[$key]['name']);
            $lists[$key]['create_time'] = date("Y-m-d H:i:s",$val['create_time']);
            $lists[$key]['type_name'] = $this->get_type_name($val['type']);
            $lists[$key]['level_name'] =$this->get_level_name($val['level']);
            if($val['type']==4){
                $lists[$key]['correct_option']  = implode(',', json_decode($val['correct_option'],true));
            }else{
                $lists[$key]['correct_option']  = $val['correct_option'];
            }
            
        }
        if(request()->isAjax()){
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="题库";
            $msg['bank_id'] = $bank_id;
            $msg['pages'] = $allpage;
            return json($msg);
        }
        $this->assign('bank_id', $bank_id);
        $this->assign('lists', $lists);
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        
        return $this->fetch();
    }
    protected  function get_level_name($level){
        switch ($level){
            case 1:
                $level_name = '简单';
                break;
            case 2:
                $level_name= '一般';
                break;
            case 3:
                $level_name = '困难';
                break;
        }
        return $level_name;
    }
    protected  function get_type_name($type){
        switch ($type){
            case 1:
                $type_name = '单选';
                break;
            case 2:
                $type_name = '多选';
                break;
            case 3:
                $type_name = '判断';
                break;
            case 4:
                $type_name = '填空';
                break;
        }
        return $type_name;
    }
    /**
     * 创建
     */
    public function create(){
        $bank_id = input('bank_id');
        $this->assign('bank_id', $bank_id);
        return $this->fetch();
    }
    /**
     * 保存数据
     */
    public function save(){
        $param  = input();
        $bank_id = $param['bank_id'];
        $back_url = $param['back_url'];
        if($back_url==1){
            $url = '/admin/testitemlist/create?bank_id='.$bank_id;
        }else{
            $url = '/admin/testitemlist/index?bank_id='.$bank_id;
        }
        unset($param['back_url']);
        unset($param['content']);

        if(in_array($param['type'], array(1,2))){
            //$param['option'] = json_encode($param['select_option']);  
            //unset($param['select_option']);
          
            foreach(json_decode($param['select_option'],true) as $key=>$val){
                $temp[$key]=$val;
            }
            $param['option'] = json_encode($temp);
            
            //var_dump($param);die();
        }
        if($param['type']==3){
            $param['option'] = $param['select_option'];
        }
        unset($param['select_option']);
        //填空题
        if($param['type']==4){
            $select_option_json = json_encode($param['fill_option']);
            $param['option']=$select_option_json;
            $param['correct_option']=$select_option_json;
        }
        unset($param['fill_option']);
        $orderby = db('testitem_list')->where(['closed'=>0,'bank_id'=>$bank_id])->order('orderby desc')->value('orderby');
        $param['create_time'] =time();
        $param['orderby'] =$orderby+1;

        $is_in = insert('Testitem_list', $param);
        if($is_in){
            setInc('Testitem_bank',['id'=>$bank_id],'list_count');
            $this->redirect($url);
        }
    }
    /**
     * 删除试题
     */
    public function delelte(){
        $id = input('param.id');
        $bank_id = input('param.bank_id');
        $is_delete = db('testitem_list')->where(['id'=>$id])->update(['closed'=>1]);
        if($is_delete){
            setDec('Testitem_bank',['id'=>$bank_id],'list_count');
            return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => '删除题库成功']);
        }
        else {
            return json(['status' => -200,'url'=>'reload', 'data' => '', 'msg' => '删除题库失败']);
        }
    }
    /**
     * 编辑
     * */
    
    public function edit(){
        $id = input('id');
        $lists = db('testitem_list')->where(['closed'=>0,'id'=>$id])->order('orderby desc')->find();
        //var_dump($lists);
        $option_arr = json_decode($lists['option'],true);
        $this->assign('option_arr', $option_arr);
        $this->assign('option_count', count($option_arr));
        $type= $lists['type'];
        if($type==4){
            $correct_option_arr = json_decode($lists['correct_option'],true);
            //var_dump($correct_option);die();
        }else{
            $mystrlen = strlen($lists['correct_option']);
            for ($i=0;$i<$mystrlen;$i++){
                $correct_option_arr[$i] =mb_substr($lists['correct_option'],$i,1);
            }
        }
        
        $this->assign('correct_option_arr', $correct_option_arr);
        $this->assign('lists', $lists);
        return $this->fetch('edit');
    }
    public function  editsave(){
        $input_temp = input();
        unset($input_temp['option_count']);
        
        $type = $input_temp['type'];
        if(in_array($input_temp['type'], array(1,2))){
            //$param['option'] = json_encode($param['select_option']);
            //unset($param['select_option']);
            
            foreach(json_decode($input_temp['select_option'],true) as $key=>$val){
                $temp[$key]=$val;
            }
            $input_temp['option'] = json_encode($temp);
            
            //var_dump($input_temp);die();
        }
        else if($input_temp['type']==4){
            $input_temp['option'] = json_encode($input_temp['fill_option']);
            $input_temp['correct_option'] = $input_temp['option'];
        }
        unset($input_temp['select_option']);
        unset($input_temp['fill_option']);
        //var_dump($input_temp);
        //die();
        $id = $input_temp['id'];
        $bank_id = $input_temp['bank_id'];
//         $option_arr = $input_temp['option'];
//         $this->get_private($input_temp['type'],$option_arr,$input_temp['correct_option']);
//         if(is_array($option_arr)){
//             $option_json = json_encode($option_arr);
//             $input_temp['option'] = $option_json;
//         }else{
//             return json(['status' => -200,'url'=>'reload', 'data' => '', 'msg' => '选项填写错误']);
//         }
        $is_up = update('Testitem_list', ['id'=>$id],$input_temp);
        if($is_up){
            $url = '/admin/testitemlist/index?bank_id='.$bank_id;
            $this->redirect($url);
            //return json(['status' =>'200','url'=>'/admin/testitemlist/index?bank_id='.$bank_id,  'data' => '', 'msg' =>'修改成功']);
        }
    }
    /**
     * 提交数据是否正确
     */
    protected function  get_private($type,$option_arr,$correct_option){
        if($type==3){//判断题
            if(count($option_arr)>2){
                //$this->error('判断题不能添加多于两个的选项');
            }
            if(in_array($correct_option,['a','b'])){
                $this->error('判断题正确选项填写不正确');
            }
        }elseif ($type==1){
            $correct_option = explode(',', $correct_option);
            if(count($correct_option)>1){
                $this->error('单选题正确选项必须唯一');
            }
        }
     }
}