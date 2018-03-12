<?php
namespace app\wechat\controller;
use think\Controller;
use think\db\Query;
use Aliyun\Core\Regions\ProductDomain;

class Testitembank extends Controller
{
    public function _initialize()
    {
        //微信sdk
        require_once EXTEND_PATH."org/wxsdk/jssdk.php";
        $config = find('Config');
        $jssdk = new \JSSDK($config['wx_public_appid'], $config['wx_public_secret']);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage',$signPackage);
        //用户信息
        $this->assign('config',$config);
        $this->assign('userinfo', wechatLogin());

    }

    /**
     *题库首页
     */
    public function index()
    {
        $uid =session('uid');
        $bank_testlist = array();$course_testlist = array();
        $bank_testlist = $this->get_testitem_list($uid,2);//题库练习
        $course_testlist = $this->get_testitem_list($uid,1);//课后练习
        $this->assign('bank_testlist',$bank_testlist);
        $this->assign('course_testlist',$course_testlist);
        return view('index');
    }
    /**
     * 做试题
     */
    public function do_testitem(){
        $uid =session('uid');
        $bank_id  = input('bank_id');//题库id
        $map['closed'] = 0;
        $map['bank_id'] = $bank_id;
        //插入用户练习记录 
        $record_id = db('testitem_record')->where(['bank_id'=>$bank_id,'uid'=>$uid])->value('id');
        if(!$record_id){
            $record_data['uid'] = $uid;
            $record_data['bank_id'] = $bank_id;
            $record_data['is_finish'] = 0;
            $record_data['create_time'] =time();
            $record_data['update_time'] = time();
            db('testitem_record')->insert($record_data);
        }
        $count = counts('Testitem_list',$map);//总试题
        $this->assign('lastPage',$count);
        $correct_option_arr = array();
        $testitem_lists = db('testitem_list')->where($map)->order('id asc')->field('id,name,type,correct_option,option')->select();
       // var_dump($testitem_lists);die();
        foreach($testitem_lists as $key=>$lists){
            $typelist =$this->get_type_name($lists['type']);
            $testitem_lists[$key]['type_class'] = $typelist['class'];
            $testitem_lists[$key]['type_name'] = $typelist['name'];
            $testitem_lists[$key]['option_arr'] = json_decode($lists['option'],true);
        }
        $testitem_lists_json = json_encode($testitem_lists);
        $this->assign('testitem_lists_json',$testitem_lists_json);
        $this->assign('bank_id',$bank_id);
        $this->assign('uid',$uid);
        $this->assign('this_time',time());
        //是否拥有权限
        $lists = db('testitem_bank')->where(['closed'=>0,'id'=>$bank_id])->order('course_id asc')->field('course_id,name,privilege,audit')->find();
        $this->assign('name',$lists['name']);
        $course_id = $lists['course_id'];
        $this->assign('course_id',$course_id);
        $audit = $lists['audit'];
        $this->assign('audit',$audit);
        $prvi_id =$this->get_can_visit($uid,$lists['course_id'],$lists['privilege']);
        $this->assign('prvi_id',$prvi_id);
        return view('do_testitem');
    }
    //解析
    public function testitem_userlist(){
        $uid =session('uid');
        $bank_id  = input('bank_id');//题库id
        
        $testitem_user =db('testitem_user')->where(['closed'=>0,'bank_id'=>$bank_id,'uid'=>$uid])->order('list_id asc')->field('list_id,select_option,is_right')->select();
        $testitem_list = db('testitem_list')->where(['closed'=>0,'bank_id'=>$bank_id])->order('id asc')->field('id,type,name,level,parse,correct_option,option')->select();
        $count =0;
        foreach ($testitem_list as $key=>$val){
            $list_id= $val['id'];
            $type_list = $this->get_type_name($val['type']);
            if($val['type']==4){
                $view_lists[$key]['correct_option'] = json_decode($val['correct_option']);
                $view_lists[$key]['select_option'] = json_decode($testitem_user[$key]['select_option']);
            }else{
                $view_lists[$key]['select_option'] = $testitem_user[$key]['select_option'];
                $view_lists[$key]['correct_option']=$val['correct_option'];
            }
            $view_lists[$key]['is_right'] = $testitem_user[$key]['is_right'];
            $view_lists[$key]['type'] =$val['type'];
            $view_lists[$key]['type_class'] =$type_list['class'];
            $view_lists[$key]['type_name']=$type_list['name'];
            $view_lists[$key]['level_name']=$this->get_level_name($val['level']);
            $view_lists[$key]['name'] = $val['name'];
            $view_lists[$key]['parse']=$val['parse'];
            
            $view_lists[$key]['option']=json_decode($val['option'],true);
            $count = $count+1;
        }
        //var_dump($view_lists);die();
        $view_lists_json = json_encode($view_lists);//var_dump($view_lists_json);die();
        $this->assign('view_lists_json',$view_lists_json);
        $this->assign('uid',$uid);
        $this->assign('bank_id',$bank_id);
        $this->assign('count',$count);
        return view('testitem_userlist');
    }
    protected  function get_type_name($type){
        switch ($type){
            case 1:
                $typelist['class'] = 'single';
                $typelist['name'] = '单选题';
                break;
            case 2:
                $typelist['class'] = 'multi';
                $typelist['name'] = '多选题';
                break;
            case 3:
                $typelist['class'] = 'single';
                $typelist['name'] = '判断题';
                break;
            case 4:
                $typelist['class'] = 'single';
                $typelist['name'] = '填空题';
                break;
        }
        return $typelist;
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
    /**
     * 题库练习 更多
     */
    public function  bank_more(){
        $uid =session('uid');
        $bank_testlist = $this->get_testitem_list($uid,2);
        $this->assign('bank_testlist',$bank_testlist);
        return view('bank_more');
    }
    /**
     * 课后练习 更多
     */
    public function  course_more(){
        $course_id = input('cid',0);
        $uid =session('uid');
        $course_testlist = $this->get_testitem_list($uid,1,$course_id);
        $this->assign('course_testlist',$course_testlist);
        if($course_id){//单个课程
            $this->assign('course_name',$course_testlist[$course_id][0]['course_name']);
            $template = 'course_one';
        }else{
            $template = 'course_more';
        }
        return view($template);
    }
    /**
     * 获取题库
     * $is_course 1 表示查询课后练习  2 表示查询题库练习 0表示所有
     * */
    public function get_testitem_list($uid,$is_course=0,$course_id=0){
        $testitem_list = array();
        $user_list = array();
        $user_list = db('testitem_record')->where(['uid'=>$uid,'is_finish'=>1])->order('bank_id desc')->column('bank_id');
        $map['closed'] = 0;
        $map['audit'] = 1;
        switch ($is_course){
            case 1:
                if($course_id){
                    $map['course_id']=['=',$course_id];
                }else {
                    $map['course_id']=['<>',0];
                } 
                $lists = db('testitem_bank')->where($map)->order('id desc')->select();
                break;
            case 2:
                $map['course_id']=['=',0];
                $lists = db('testitem_bank')->where($map)->order('id desc')->select();
                break;
        }
        
        foreach ($lists as $val){
            $key = $val['course_id'];
            //课程是否上架
            if($key){
                $audit = db('course')->where(['cid'=>$key])->value('audit');
            }
            else{
                $audit =1;
            }
            if($audit){
                if (in_array($val['id'], $user_list)){
                    $val['is_finish'] = 1;
                }else{
                    $val['is_finish'] = 0;
                }
                $val['prvi_id'] = $this->get_can_visit($uid,$val['course_id'],$val['privilege']);
                $testitem_list[$key][] = $val;
            }
           
        }
        return $testitem_list;
    }
    /**
     * 做题权限
     * $privilege  1所有用户可以做题  2购买关联课程可以做题   3付费用户可以做题
     * return  $prvi_id  0没权限答题 1有权限答题
     */
    protected  function get_can_visit($uid,$cid,$privilege){
        switch ($privilege){
            case 1:
                $prvi_id = 1;//有权限
                break;
            case 2:
                $prvi_id = $this->get_order_ispay($uid,$cid);
                break;
            case 3:
                $prvi_id = $this->get_order_ispay($uid);
                break;
        }
        return $prvi_id;
        
    }
    /*用户是否是付费/购买课程  用户*/
    protected function get_order_ispay($uid,$cid=0){
        //首先判断用户是否为vip卡有效用户
        $isvip = isvip();
        if(in_array($isvip, array(1,2))){
            $user_is_pay = 1;
        }else{//然后查看权限  用户是否付费用户  是否购买课程用户
            $map['closed'] = 0;
            $map['pay_status'] = 1;
            $map['uid'] = $uid;
            if($cid!=0){
                $map['course_id'] = $cid;
            }
            $user_is_pay = db('order')->where($map)->value('pay_status');
            //套餐
            if($cid !=0 && $user_is_pay ==0){
                $pack_map['closed'] = 0;
                $pack_map['uid'] = $uid;
                $pack_map['course_id'] = $cid;
                $user_is_pay = db('Package_order')->where($pack_map)->value('id');
            }
            $user_is_pay = $user_is_pay>0?$user_is_pay:0;
        }
        return $user_is_pay;
    }
    

    
   



}
