<?php
namespace app\api\controller;
use think\Controller;
use app\model\IntegralDetail;

class Integral extends Controller
{
    public function _initialize()
    {

    }
    /**
     * 注册送积分
     * $uid 用户id
     */
    public  function  register($uid){
        $data['integral'] = 0;
        $data['integral_code'] = -1;
        $config_id  = 1;
        $where = array();
        $signed = $this->is_signed($uid, $config_id, $where);
        if($signed==0){
            $config_lists = $this->get_config($config_id);
            $integral = $config_lists['integral'];
            if($integral>0){
                $return_bool = $this->integral_detail($uid,$config_id,'注册',$integral,$remark='注册增加'.$integral.'积分');//注册增加记录
                if($return_bool){
                    $this->user_update($uid, $integral); //更新用户积分信息
                    $data['integral'] = $integral;
                    $data['integral_code'] = 1;
                }
            }
            
        }
        return $data;
    }
    /**
     * 检查签到
     */
    public function checksign($uid,$is_json=0){
        $data['integral_code'] = -1;
        $today_signed = $this->today_count($uid, 2);//今天是否签到
        if($today_signed){
            $data['integral_code'] = 1;
        }
        if($is_json){
            successJson('操作成功',$data);
        }else{
            return $data;
        }
        
    }
    /**
     * 签到加积分（包括连续签到）
     * $uid  用户id
     */
    public function  sign($uid,$is_json=0){
        $data['integral'] = 0;
        $data['integral_code'] = -1;
        $data['msg']='签到成功';
        $today_signed = $this->today_count($uid, 2);//今天是否签到
        if($today_signed==0){
            $user_days = 0;
            $msg = '签到成功';
            $ids  = array(2,6);//签到设置  连续签到设置
            $config_lists = $this->get_config($ids);
            $integral = $config_lists[0]['integral'];
            if($integral>0){
                $msg = '签到成功 +'.$integral.'积分';
            }
            $return_bool = $this->integral_detail($uid,2,'签到',$integral,$remark='签到增加'.$integral.'积分');//签到增加记录
            $user_sign_days = db('user')->where('uid',$uid)->value('sign_days');//用户签到天数
            $config_days = $config_lists[1]['sign_days'];//连续签到奖励天数设置
            $yesterday_where['create_time'] = ['<=',mktime(0,0,0,date('m'),date('d')-1,date('Y'))];
            $yesterday_where['create_time'] = ['>=',mktime(0,0,0,date('m'),date('d'),date('Y'))-1];
            $yesterday_signed = $this->is_signed($uid, 2, $yesterday_where);//昨天是否签到
            if(($config_days-1)<=$user_sign_days &&$yesterday_signed&&$config_days>0 && $config_lists[1]['integral']>0){
                $integral = $integral + $config_lists[1]['integral'];//签到获取积分+连续签到获取积分
                $msg = '连续签到 '.$config_days.'天 +'.$integral.'积分';
                $return_bool = $this->integral_detail($uid,6,'连续签到',$config_lists[1]['integral'],$remark='连续签到增加'.$config_lists[1]['integral'].'积分');//签到增加记录
             }
            if($yesterday_signed && ($user_sign_days+1)<$config_days){ //昨天签到 且 连续天数小于连续签到奖励天数
                $user_days  = $user_sign_days+1;
            }
            if($return_bool){
                $data['msg'] = $msg;
                $data['integral'] = $integral;
                $data['integral_code'] = 1;
                $this->user_update($uid, $integral, $user_days,1);//更新用户积分信息
            }
        }
        if($is_json){
            successJson('操作成功',$data);
        }else{
            return $data;
        }
    }
    
    /**
     * 课程评价加积分 
     * $uid  用户id
     * $course_id 课程id
     */
    public  function comment($uid,$course_id,$is_json=0){
        $data['integral'] = 0;
        $data['integral_code'] = -1;
        $data['msg']='';
        $config_id  = 3;
        $where['course_id'] = $course_id;
        $signed = $this->is_signed($uid, $config_id, $where);
        if($signed==0){
            $config_lists = $this->get_config($config_id);
            $integral = $config_lists['integral'];
            if($integral>0){
                $return_bool =$this->integral_detail($uid,$config_id,'课程评价',$integral,$remark='课程评价增加'.$integral.'积分',0,$course_id);//签到增加记录
                if($return_bool){
                    $data['integral'] = $integral;
                    $data['integral_code'] = 1;
                    $data['msg']='已评价  +'.$integral.'积分';
                    $this->user_update($uid, $integral); //更新用户积分信息
                }
                
            }
        }
        if($is_json){
            successJson('操作成功',$data);
        }else{
            return $data;
        }
    }
    
    /**
     * 提问加积分
     * $uid  用户id
     * $course_id 课程id
     */
    public  function  ask($uid,$course_id){
        $data['integral'] = 0;
        $data['integral_code'] = -1;
        $data['msg']= '';
        $config_id  = 4;//提问
        $config_lists = $this->get_config($config_id);
        $integral = $config_lists['integral'];
        if($integral>0){
            $today_signed_count = $this->today_count($uid, $config_id);//今天是否签到
            if($today_signed_count<$config_lists['maxmum']){//每人每天限制次数
                $return_bool = $this->integral_detail($uid,$config_id,'提问',$integral,$remark='提问增加'.$integral.'积分',0,$course_id);//签到增加记录
                if($return_bool){
                    $data['integral'] = $integral;
                    $data['integral_code'] = 1;
                    $data['msg']='已提问  +'.$integral.'积分';
                    $this->user_update($uid, $integral); //更新用户积分信息
                }
            }
        }
        return $data;
    }
    /**
     * 回答加积分
     * $uid 用户id
     * $aid 回答问题id
     */
    public  function answer($uid,$aid){
        $data['integral'] = 0;
        $data['integral_code'] = -1;
        $data['msg']='';
        $config_id  = 5;//回答
        $today_signed_count = $this->today_count($uid, $config_id);//今天是否签到
        $config_lists = $this->get_config($config_id);
        if($today_signed_count<$config_lists['maxmum']){//每人每天限制次数
            $integral = $config_lists['integral'];
            if($integral>0){
                $return_bool =$this->integral_detail($uid,$config_id,'回答',$integral,$remark='回答增加'.$integral.'积分',0,0,$aid);//签到增加记录
                if($return_bool){
                    $data['integral'] = $integral;
                    $data['integral_code'] = 1;
                    $data['msg']='已回答  +'.$integral.'积分';
                    $this->user_update($uid, $integral); //更新用户积分信息
                }
            }
        }
        return $data;
    }
    /**
     * 消费加积分
     * $uid 用户id
     * $order_sn 订单编号
     * $total_money 订单总金额
     */
    public function  consume($uid,$order_sn,$total_money){
        $data['integral'] = 0;
        $data['integral_code'] = -1;
        $config_id  = 7;//消费
        $data['msg']='';
        $where['order_sn'] = $order_sn;
        $signed = $this->is_signed($uid, $config_id, $where);
        if($signed==0){
            $config_lists= $this->get_config($config_id);
            //$config_lists['consume_money'] = 0.01;  //测试的时候开启
            if($total_money>=$config_lists['consume_money']&&$config_lists['consume_money']>0 && $config_lists['integral']>0){//单笔消费满多少元
                //$integral = floor(($total_money*$config_lists['integral'])/$config_lists['consume_money']);
                $integral =floor($total_money/$config_lists['consume_money'])*$config_lists['integral'];
                $return_bool =$this->integral_detail($uid,$config_id,'消费',$integral,$remark='消费增加'.$integral.'积分',$order_sn);//签到增加记录
                if($return_bool){
                    $data['integral'] = $integral;
                    $data['integral_code'] = 1;
                    $this->user_update($uid, $integral); //更新用户积分信息
                }
            }
        }
        return $data;
    }
    /**
     * 后台修改积分
     * $uid 用户id
     */
    public function admin($uid,$integral,$status,$remark){
        $data['integral'] = 0;
        $data['integral_code'] = -1;
        $config_id  = 8;//后台
        if($integral){
            if($status){
                $remark_temp = '后台增加'.$integral.'积分';
                $final_integral =$integral;
            }else{
                $remark_temp= '后台减少'.$integral.'积分';
                $final_integral =-$integral;
            }
            if(abs($final_integral)>0){
                $remark = $remark?$remark:$remark_temp;
                $return_bool =$this->integral_detail($uid,$config_id,'后台',$final_integral,$remark);//签到增加记录
                if($return_bool){
                    $data['integral'] = $integral;
                    $data['integral_code'] = 1;
                    $this->user_update($uid, $final_integral); //更新用户积分信息
                }
            }
            
        }
        return $data;
    }
    /**
     * 下单校验积分抵扣
     * $uid           用户id
     * $old_price     商品 价格
     * $coupon_minus   优惠码减掉的价格
     * $switch         积分抵现前台开关  0 关闭  1开启
     * $is_json        是否返回数据 json 
     * return $data
     */
    public function check_intergral($uid,$old_price,$coupon_minus=0,$is_switch=0,$is_json=0){
        $deducted_integral = 0;//订单抵扣用户积分
        $deducted_money  = 0;//订单抵扣金额
        $consume_integral = 0;
        $config_id = 9;
        $config_lists = $this->get_config($config_id);
        $config_integral = $config_lists['deducted_integral'];//积分兑换  积分可抵扣1元
        $cash_percent = $config_lists['cash_percent']*0.01;//使用积分抵消实际付款金额，最多可抵消课程金额的百分比
        $status = $config_lists['status'];//积分抵现后台开关
        $total_price = $old_price-$coupon_minus;
        if($status && $total_price>0){
            $user_integral = $this->now_integral($uid);
            $most_money = floor($cash_percent*$total_price);//最多抵扣现金
            $most_integral =$most_money * $config_integral;//最多抵扣积分
            if($user_integral<$most_integral){
                $deducted_money = floor($user_integral*(1/$config_integral));
                $deducted_integral = $deducted_money*$config_integral;
            }else{
                $deducted_integral = $most_integral;
                $deducted_money = $most_money;
            }
            if($is_switch==0){
                $deducted_money =0;
            }
        }
        $order_price = $total_price-$deducted_money;
        $config_lists= $this->get_config(7);
        if($order_price>=$config_lists['consume_money']&& $config_lists['integral']>0 && $config_lists['consume_money']>0){//单笔消费满多少元
            $consume_integral = floor($order_price/$config_lists['consume_money'])*$config_lists['integral'];
        }    
        $return_data['status'] = $status;
        $return_data['consume_integral'] = $consume_integral;
        $return_data['deducted_integral'] = $deducted_integral;
        $return_data['deducted_money'] = sprintf("%.2f", $deducted_money);
        $return_data['order_price'] =sprintf("%.2f", $order_price);
        if($is_json){
            successJson('操作成功',$return_data);
        }else{
            return $return_data;
        }
        
    }
    /**
     * 下单完成 处理积分
     * $uid  用户id
     * $order_sn  订单编号
     * $pay_money  支付金额
     * $deducted_integral 订单中减掉用户积分
     * $deducted_money    抵扣了订单金额
     */
    public function pay_after_intergral($uid,$order_sn,$pay_money,$deducted_integral,$deducted_money){
        if($deducted_integral>0){
            $config_id = 9;
            $config_lists = $this->get_config($config_id);
            $status = $config_lists['status'];//积分抵现后台开关
            if($status){
                $where['order_sn'] = $order_sn;
                $signed = $this->is_signed($uid, $config_id, $where);
                if($signed==0){
                    $integral  = abs($deducted_integral);
                    $insert_num = -$integral;
                    $this->integral_detail($uid,$config_id,'积分抵现',$insert_num,$remark='积分抵现扣除积分'.$integral.'抵扣订单金额'.$deducted_money,$order_sn);//签到增加记录
                    $this->user_update($uid, $insert_num); //更新用户积分信息
                }
            }
        }
        $data = $this->consume($uid,$order_sn,$pay_money);
        return $data;
    }
    /**
     * 学习视频加积分
     * $uid  用户id
     * $vid 视频id
     * $lenght  时长
     */
    public  function study_video($uid,$vid,$lenght,$is_json=0){
        $data['integral'] = 0;
        $data['integral_code'] = -1;
        $data['msg']='';
        $config_id  = 10;
        $where['vid'] = $vid;
        $signed = $this->is_signed($uid, $config_id, $where);
        if($signed==0){
            $config_lists = $this->get_config($config_id);
            $integral = 0;
            $status = $config_lists['status'];
            if($status){
                if($lenght<=60){
                    $integral = 1;
                }else{
                    $integral= floor($lenght/60);
                }
            }else{
                if($config_lists['integral']>0){
                    $integral = $config_lists['integral'];
                }
            }
            if($integral>0){
                $return_bool =$this->integral_detail($uid,$config_id,'学习视频',$integral,$remark='学习视频增加'.$integral.'积分',0,0,0,$vid);//签到增加记录
                if($return_bool){
                    $data['integral'] = $integral;
                    $data['integral_code'] = 1;
                    $data['msg']='学习视频  +'.$integral.'积分';
                    $this->user_update($uid, $integral); //更新用户积分信息
                }
                
            }
        }
        if($is_json){
            successJson('操作成功',$data);
        }else{
            return $data;
        }
    }
    /**
     * 积分使用详情
     * $uid   用户id
     * $type  0 全部  1 获取 2使用
     */
    public function detail(){
        $uid = input('uid');
        $type = input('type',0);
        $page = input('page',1);
        $size = input('size',10);
        $user_integral = db('user')->where('uid',$uid)->field('now_integral,integral_count')->find();
        $data['integral_count'] = $user_integral['integral_count'];
        $data['now_integral'] = $user_integral['now_integral'];
        $where['uid'] =  $uid;
        switch ($type){
            case 1:
                $where['integral'] = ['>=',0];
                break;
            case 2:
                $where['integral'] = ['<',0];
                break;
        }
        $total = counts('IntegralDetail',$where);
        $where['page'] = $page;
        $where['size'] = $size;
        $field = 'config_name,integral,create_time';
        $orderby['create_time'] = 'desc';
        $detail_data = lists('IntegralDetail',$where,$orderby,$field);
        $detail_data = $detail_data?$detail_data:array();
        $data['detail'] = $detail_data;
        $data['currentPage'] = $page;
        $data['counts'] = $total;
        $data['total'] = ceil($total/$page);
        successJson('操作成功',$data);
    }

    /**
     * 积分使用详情
     * $uid   用户id
     * $type  0 全部  1 获取 2使用
     */
    public function integralList(){
        $uid = input('uid');
        $type = input('type',0);
        $page = input('page',1);
        $size = input('size',10);
        $user_integral = db('user')->where('uid',$uid)->field('now_integral,integral_count')->find();
        $data['integral_count'] = $user_integral['integral_count'];
        $data['now_integral'] = $user_integral['now_integral'];
        $where['uid'] =  $uid;
        switch ($type){
            case 1:
                $where['integral'] = ['>=',0];
                break;
            case 2:
                $where['integral'] = ['<',0];
                break;
        }
        $total = counts('IntegralDetail',$where);
        $where['page'] = $page;
        $where['size'] = $size;
        $field = 'config_name,integral,create_time';
        $orderby['create_time'] = 'desc';
        $detail_data = lists('IntegralDetail',$where,$orderby,$field);
        $detail_data = $detail_data?$detail_data:array();
        $data['detail'] = $detail_data;
        $data['currentPage'] = $page;
        $data['total'] = ceil($total/$size);
        header("Content-type: application/json");
        exit(json_encode($data));
    }
    
    /**
     * 获取用户可用积分数
     * $uid 用户id
     */
    public function now_integral($uid){
        $now_integral = db('user')->where('uid',$uid)->value('now_integral');
        return $now_integral;
    }
    /**
     * 获取积分配置
     * $ids  config_id（配置id）
     */
    private  function get_config($ids){
        if(is_array($ids)){
            $config_lists = db('IntegralConfig')->where('id','in',$ids)->select();
        }else{
            $config_lists = db('IntegralConfig')->where('id',$ids)->find();
        }
        $config_lists = $config_lists?$config_lists:array();
        return $config_lists;
    }
    /**
     * 查询是否获取积分
     * $uid       用户id
     *$config_id   config_id（配置id）
     * $where      其他查询条件
     */
    private function is_signed($uid,$config_id,$where){
        $where['config_id']  = $config_id;
        $where['uid']  = $uid;
        $signed = db('IntegralDetail')->where($where)->count();
        $signed = $signed?$signed:0;
        return $signed;
    }
    /**
     * 今天获取积分的次数
     * $uid         用户id
     * $config_id   config_id（配置id）
     */
    private function today_count($uid,$config_id){
        $today_start_time = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $today_end_time = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $where['create_time'] = ['between',[$today_start_time,$today_end_time]];
        $today_signed = $this->is_signed($uid, $config_id, $where);
        return $today_signed;
    }
    /**
     * 插入积分流水详情
     * $uid  用户id
     * $config_id  config_id
     * $config_name 名称
     * $integral    积分
     * $remark      备注
     * $order_sn    订单编号
     * $course_id   课程id
     * $aid         回答问题id
     * $vid         视频id
     */
    private  function integral_detail($uid,$config_id,$config_name,$integral,$remark='',$order_sn=0,$course_id=0,$aid=0,$vid=0){
        $data['uid'] = $uid;
        $data['config_id'] = $config_id;
        $data['config_name']=$config_name;
        $data['integral'] = $integral;
        $data['remark'] = $remark;
        $data['order_sn'] = $order_sn;
        $data['course_id'] = $course_id;
        $data['aid'] = $aid;
        $data['vid'] = $vid;
        $data['create_time'] = time();
        $integraldetail = new IntegralDetail();
        $return_bool =  $integraldetail->insert($data);
        return $return_bool;
    }
    /**
     *更新用户积分数
     *uid 用户id
     *integral  更新积分数
     *sign_days  连续签到天数
     */
    private  function user_update($uid,$integral,$sign_days=0,$is_sign=0){
        $prefix = config('database.prefix');
        if($is_sign){
            $sql  = "UPDATE `".$prefix."user` SET `sign_days` =".$sign_days.",`integral_count` = `integral_count`+'".$integral."', `now_integral` = `now_integral` + ".$integral."  WHERE `uid` = ".$uid;
        }else{
            $sql  = "UPDATE `".$prefix."user` SET `integral_count` = `integral_count`+'".$integral."', `now_integral` = `now_integral` + ".$integral."  WHERE `uid` = ".$uid;
        }
        db('user')->query($sql);
    }
    

   
}
