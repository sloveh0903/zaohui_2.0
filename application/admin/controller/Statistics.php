<?php

namespace app\admin\controller;

use think\Db;


class Statistics extends Base
{
    public function _empty(){
        return $this->index();
    }

    //订单统计
    public function index(){
        //统计销售总金额
        $total = Db::name('Order')->where(array('pay_status'=>'1','closed'=>'0'))->sum('pay_price');

        //交易数据
        $tradeSql = 'select count(id) as order_num,sum(pay_price) as total,is_import from gz_order where pay_status = 1 and FROM_UNIXTIME(create_time,\'%Y-%m-%d\')=CURDATE() and closed = 0 group by (is_import)';
        $tradeData = Db::query($tradeSql);
        $tradeInfo = [
            'total'=>0,
            'onlineTotal'=>0,
            'offlineTotal'=>0,
            'onlineOrders'=>0,
            'offlineOrders'=>0
        ];

        if($tradeData){
            foreach ($tradeData as $v) {
                if($v['is_import'] == 1){
                    $tradeInfo['offlineTotal'] = $v['total'];
                    $tradeInfo['offlineOrders'] = $v['order_num'];
                    $tradeInfo['total'] += $v['total'];
                }else{
                    $tradeInfo['onlineTotal'] = $v['total'];
                    $tradeInfo['onlineOrders'] = $v['order_num'];
                    $tradeInfo['total'] += $v['total'];
                }
            }
        }

        //收入增长 线上交易
        $date = $incomeInfo = $offlineInfo = [];
        $max_income = $max_offline = 0;
        $incomeSql = 'select t2.days,IFNULL(t3.amount,0) amount from   
(SELECT @cdate := date_add(@cdate,interval -1 day) days from (SELECT @cdate :=date_add(CURDATE(),interval +1 day) from gz_temp limit 7) t1) t2  
LEFT JOIN (SELECT sum(pay_price) as amount,FROM_UNIXTIME(create_time,\'%Y-%m-%d\') as create_time FROM gz_order WHERE pay_status = 1 and closed = 0 and is_import = 0 and DATE_SUB(CURDATE(),INTERVAL 7 DAY) <= FROM_UNIXTIME(create_time,\'%Y-%m-%d\') GROUP BY FROM_UNIXTIME( create_time,  \'%Y-%m-%d\' ) ) t3
ON t2.days = t3.create_time order by days asc';
        $incomeData =  Db::query($incomeSql);
        $income_list = array_column($incomeData, 'amount');
        if($income_list){
            $max_income = $income_list[array_search(max($income_list),$income_list)]; //线上最大值
        }

        foreach ($incomeData as $income) {
            $date[] = $income['days'];
            $incomeInfo[] = $income['amount'];
        }

        $offlineSql = "select t2.days,IFNULL(t3.amount,0) amount from   
(SELECT @cdate := date_add(@cdate,interval -1 day) days from (SELECT @cdate :=date_add(CURDATE(),interval +1 day) from gz_temp limit 7) t1) t2  
LEFT JOIN (SELECT sum(pay_price) as amount,FROM_UNIXTIME(create_time,'%Y-%m-%d') as create_time FROM gz_order WHERE pay_status = 1 and closed = 0 and is_import = 1 and DATE_SUB(CURDATE(),INTERVAL 7 DAY) <= FROM_UNIXTIME(create_time,'%Y-%m-%d') GROUP BY FROM_UNIXTIME( create_time,  '%Y-%m-%d' ) ) t3
ON t2.days = t3.create_time order by days asc";
        $offlineData =  Db::query($offlineSql);

        $offline_list = array_column($offlineData, 'amount');
        if($offline_list){
            $max_offline = $offline_list[array_search(max($offline_list),$offline_list)];  //线下最大值
        }

        foreach ($offlineData as $offline) {
            $offlineInfo[] = $offline['amount'];
        }
        $date = implode(',',$date);
        $incomeInfo = implode(',',$incomeInfo);
        $offlineInfo = implode(',',$offlineInfo);

        $max_day = ($max_income > $max_offline) ? $max_income : $max_offline;
        //订单来源
        //本月数据
        $sourselist = ['wechat','import','miniwechat','pc'];
        $sourseSql = 'select count(id) as order_num,sum(pay_price) as total,source from gz_order where pay_status = 1 and MONTHNAME(FROM_UNIXTIME(create_time))=MONTHNAME(NOW()) and closed = 0 group by (source)';
        $sourseData = Db::query($sourseSql);
        $sourse = array_column($sourseData, 'source');
        $thisMouthData = [];
        foreach ($sourselist as $v) {
            $ret_mouth = [];
            if(!in_array($v,$sourse)){
                $ret_mouth['order_num'] = 0;
                $ret_mouth['total'] = 0;
                $ret_mouth['source'] = $v;
                $thisMouthData[] = $ret_mouth;
            }
        }
        $sourseData = (array_merge($sourseData,$thisMouthData));
        $sourseData = array_combine(array_column($sourseData, 'source'),$sourseData);


        //上月数据
        $lastMouthSql = 'select count(id) as order_num,sum(pay_price) as total,source from gz_order where pay_status = 1 and closed = 0 and PERIOD_DIFF(DATE_FORMAT(now(),\'%Y%m\'),FROM_UNIXTIME(create_time,\'%Y%m\')) = 1  group by (source)';
        $lastMouthData = Db::query($lastMouthSql);
        $lastsourse = array_column($lastMouthData, 'source');
        $lastMouth = [];
        foreach ($sourselist as $v) {
            $ret_mouth = [];
            if(!in_array($v,$lastsourse)){
                $ret_mouth['order_num'] = 0;
                $ret_mouth['total'] = 0;
                $ret_mouth['source'] = $v;
                $lastMouth[] = $ret_mouth;
            }
        }
        $lastMouthData = (array_merge($lastMouthData,$lastMouth));
        $lastMouthData = array_combine(array_column($lastMouthData, 'source'),$lastMouthData);


        foreach ($sourseData as $k=>$v){
            switch ($k){
                case "wechat":
                    $v['title'] = '微信公众号';
                    break;
                case "miniwechat":
                    $v['title'] = '小程序';
                    break;
                case "pc":
                    $v['title'] = 'PC端';
                    break;
                case "import":
                    $v['title'] = '线下导入';
                    break;
            }
            if(isset($lastMouthData[$k])){
                $v['last_order_num'] = $lastMouthData[$k]['order_num'];
                $v['last_total'] = $lastMouthData[$k]['total'];
                if($lastMouthData[$k]['total'] > $v['total']){
                    $v['totalrate'] = 'down';
                }else{
                    $v['totalrate'] = 'up';
                }
                if($lastMouthData[$k]['order_num'] > $v['order_num']){
                    $v['orderrate'] = 'down';
                }else{
                    $v['orderrate'] = 'up';
                }
                if($lastMouthData[$k]['order_num'] == 0){
                    $v['orderpercent'] = 0;
                }else{
                    $v['orderpercent'] = round(abs($v['order_num']-$lastMouthData[$k]['order_num'])/$lastMouthData[$k]['order_num']*100,2);
                }
                if($lastMouthData[$k]['total'] == 0){
                    $v['totalpercent'] = 0;
                }else{
                    $v['totalpercent'] = round(abs($v['total']-$lastMouthData[$k]['total'])/$lastMouthData[$k]['total']*100,2);
                }
            }
            if(in_array($k,$sourselist)){
                $ret_data[] = $v;
            }
        }
        /*p($ret_data);
        die();*/

        $this->assign('total',$total);
        $this->assign('tradeInfo', $tradeInfo);
        $this->assign('date', $date);
        $this->assign('incomeInfo', $incomeInfo);
        $this->assign('offlineInfo', $offlineInfo);
        $this->assign('max_day', ($max_day < 500) ? 500 : $max_day);
        $this->assign('ret_data', $ret_data);
        $this->assign('sourseData', $sourseData);
        $this->assign('lastMouthData', $lastMouthData);
        return $this->fetch();

    }




    /**
     * 课程统计
     */
    public  function course(){
        $page = input('page',1);
        $size = 10;
        $limitstart = ($page-1)*$size;
        $list_arr = array();
        $prefix = config('database.prefix');
        $count_sql = "SELECT count(c.cid) as count
                FROM ".$prefix."course as c
                left join
                    (select course_id as cid,sum(pay_price) as totalmoney,count(id) as sale_count from ".$prefix."order where closed=0  and pay_status=1 group by course_id) as o on o.cid = c.cid
                where c.closed=0 order by c.orderby desc";
        $count_arr = Db::query($count_sql);
        $count = $count_arr[0]['count'];//总数
        $sql = "SELECT c.cid,c.title,c.banner,o.totalmoney,o.sale_count
                FROM ".$prefix."course as c 
                left join 
                    (select course_id as cid,sum(pay_price) as totalmoney,count(id) as sale_count from ".$prefix."order where closed=0  and pay_status=1 group by course_id) as o on o.cid = c.cid
                where c.closed=0 order by c.orderby desc limit ".$limitstart.",".$size;
        $list_arr = Db::query($sql);
        if($list_arr){
            foreach($list_arr as $list){
                $cid_arr[] = $list['cid'];
            }
            $cid_str_arr = implode(',', $cid_arr);
            $study_sql = "select temp.id,temp.cid,count(temp.uid) as studynum from (SELECT s.id,s.uid,s.cid,count(s.vid)as study_vidcount,v.vid_count FROM `".$prefix."study_list` as s 
                          LEFT JOIN (SELECT cid,count(id)as vid_count FROM `".$prefix."video` as minv  where minv.closed=0 group by minv.cid) as v on v.cid = s.cid
                         where s.closed=0 and s.status=2  
                         group by s.uid,s.cid HAVING study_vidcount>vid_count*0.7
                         ORDER BY cid) as temp where cid in (".$cid_str_arr.") GROUP BY temp.cid";
            $study_arr = Db::query($study_sql);
            foreach ($study_arr as $study){
                $study_list[$study['cid']] = $study['studynum'];
            }
            foreach($list_arr as $key=>$list){
                $list_arr[$key]['studynum'] = isset($study_list[$list['cid']])?$study_list[$list['cid']]:0;
                $list_arr[$key]['totalmoney'] = $list['totalmoney']?$list['totalmoney']:0;
                $list_arr[$key]['sale_count'] = $list['sale_count']?$list['sale_count']:0;
            }
        }
        $allpage = ceil($count/$size);
        $this->assign('Nowpage',$page);
        $this->assign('allpage', $allpage);
        $this->assign('list_arr', $list_arr);
        
        return $this->fetch();
    }
    /*
     * 视频统计
     */
    public function video_detail(){
        $cid = input('cid',0);
        $title =input('title','');
        $studynum=input('studynum','');
        $page = input('page',1);
        $size = 10;
        $limitstart = ($page-1)*$size;
        $search_str = "?cid=".$cid."&title=".$title."&studynum=".$studynum;
        $prefix = config('database.prefix');
        $count_sql = "SELECT count(cate.id) as count FROM `".$prefix."video_category` as cate
                    left join (select * from  ".$prefix."video where closed=0) as v on v.tid = cate.id
                    left join (
                        SELECT mys.cid,mys.vid,count(mys.uid)as video_study_count FROM (
                                SELECT * FROM `".$prefix."study_list` where closed=0 and status=2 GROUP BY uid,cid,vid
                                ) as mys where mys.closed=0 and mys.status=2  group by mys.vid
                    )as minv on minv.vid=v.id
                where cate.cid=".$cid." and cate.closed=0 order by cate.id";
        $count_arr = Db::query($count_sql);
        $count = $count_arr[0]['count'];//总数
        if($cid){
            $sql = "SELECT cate.id,cate.cid,cate.cate_name,v.id as vid,v.title,minv.video_study_count as video_study_count FROM `".$prefix."video_category` as cate
                    left join (select * from  ".$prefix."video where closed=0) as v on v.tid = cate.id
                    left join (
                        SELECT mys.cid,mys.vid,count(mys.uid)as video_study_count FROM (
                                SELECT * FROM `".$prefix."study_list` where closed=0 and status=2 GROUP BY uid,cid,vid
                                ) as mys where mys.closed=0 and mys.status=2  group by mys.vid
                    )as minv on minv.vid=v.id
                where cate.cid=".$cid." and cate.closed=0 order by cate.id limit ".$limitstart.",".$size;
            $course_arr = Db::query($sql);
        }
        $allpage = ceil($count/$size);
        $this->assign('cid',$cid);
        $this->assign('title',$title);
        $this->assign('studynum',$studynum);
        $this->assign('Nowpage',$page);
        $this->assign('allpage', $allpage);
        $this->assign('list_arr', $course_arr);
        $this->assign('search_str', $search_str); //分页搜索条件
        return $this->fetch();
    }
    /*
     * 视频学习详情
     */
   public function video_study(){
       $vid = input('vid',0);
       $cid = input('cid',0);
       $page = input('page',1);
       $prefix = config('database.prefix');
       $size = 10;
       $limitstart = ($page-1)*$size;
       $user_arr = array();
       $allpage = 0;
       if($vid){
           $count_sql =  "SELECT s.id
                FROM `".$prefix."study_list` as s
                left join (SELECT  uid,nickname,mobile,face,sex FROM `".$prefix."user`) as u on u.uid=s.uid
                where s.closed=0 and s.status=2 and s.cid =".$cid." and s.vid =".$vid." group by s.uid,s.cid,s.vid";
           $count_arr = Db::query($count_sql);
           $count = count($count_arr);//总数
           $allpage = ceil($count/$size);
           if($count>0){
               $sql = "SELECT s.update_time as update_time , u.uid,u.nickname,u.mobile,u.face,u.sex
                FROM `".$prefix."study_list` as s
                left join (SELECT  uid,nickname,mobile,face,sex FROM `".$prefix."user`) as u on u.uid=s.uid
                where s.closed=0 and s.status=2 and s.cid =".$cid." and s.vid =".$vid." group by s.uid,s.cid,s.vid order by s.update_time desc limit ".$limitstart.",".$size;;
               $user_arr = Db::query($sql);
               foreach ($user_arr as &$v){
                   $v['update_time'] = date('Y-m-d H:i:s',$v['update_time']);
                   switch ($v['sex']){
                       case 0:
                           $v['sex']='保密';
                           break;
                       case 1:
                           $v['sex']='男';
                           break;
                       case 2:
                           $v['sex']='女';
                           break;
                   }
               }
           }
       }
       
       
       $data['user_data'] = $user_arr;
       $data['allpage'] = $allpage;
       successJson('操作完成',$data);
   }

    /**
     * 用户统计
     */
    public function user(){
        apiget('user/payuserup'); //批量更新付费用户
        $inc_user = $this->getIncuser();
        $user_data = $this->getUserdata();
        $pay_user = $this->getPayuser();
        $terminal = $this->getTerminal();
        $this->assign('inc_user',$inc_user);
        $this->assign('user_data',$user_data);
        $this->assign('pay_user',$pay_user);
        $this->assign('terminal',$terminal);
        $inc_user_time = [];   //新增用户时间
        $inc_user_number = []; //新增注册用户
        $pay_user_number = []; //新增付费用户
        $max = 50;
        foreach($inc_user['inc_user'] as $v){
            $inc_user_time[] = $v['time'];
            $inc_user_number[] = $v['user_number'];
            $max = $v['user_number']>$max?$v['user_number']:$max;
        }
        foreach($inc_user['pay_user'] as $v){
            $pay_user_number[] = $v['user_number'];
            $max = $v['user_number']>$max?$v['user_number']:$max;
        }
        
        $this->assign('inc_user_time',json_encode($inc_user_time));
        $this->assign('pay_user_number',json_encode($pay_user_number));
        $this->assign('inc_user_number',json_encode($inc_user_number));
        $this->assign('user_data',$user_data);
        $this->assign('pay_user',$pay_user);
        $this->assign('terminal',$terminal);
        $this->assign('max',$max);
        return $this->fetch();
    }

    /**
     * 增长用户
     */
    public function getIncuser(){
        $type = input('type','view');
        $day = input('day',7);
        if($day != 7){
            $day = 30;
        }
        //注册用户
        $sql = "select t2.`time`,IFNULL(t3.user_number,0) user_number from (SELECT @cdate := date_add(@cdate,interval -1 day) time from (SELECT @cdate :=date_add(CURDATE(),interval +1 day) from gz_temp limit ".$day.") t1) t2 LEFT JOIN (SELECT count(uid) as user_number,FROM_UNIXTIME(create_time,'%Y-%m-%d') as `time` FROM gz_user WHERE closed = 0 and DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= FROM_UNIXTIME(create_time,'%Y-%m-%d') GROUP BY FROM_UNIXTIME(create_time,'%Y-%m-%d')) t3 ON t2.`time` = t3.`time` order by t2.`time` asc";
        $inc_data = Db::query($sql);

        //付费用户
        //通过uid查询
        $sql = "select t2.`time`,IFNULL(t3.user_number,0) user_number from (SELECT @cdate := date_add(@cdate,interval -1 day) time from (SELECT @cdate :=date_add(CURDATE(),interval +1 day) from gz_temp limit ".$day.") t1) t2 LEFT JOIN (SELECT count(uid) as user_number,FROM_UNIXTIME(create_time,'%Y-%m-%d') as `time` FROM gz_user WHERE closed = 0 and is_pay = 1 and DATE_SUB(CURDATE(),INTERVAL 30 DAY) <= FROM_UNIXTIME(create_time,'%Y-%m-%d') GROUP BY FROM_UNIXTIME(create_time,'%Y-%m-%d')) t3 ON t2.`time` = t3.`time` order by t2.`time` asc";
        $pay_data = Db::query($sql);
        $data = [
            'inc_user'=>$inc_data,
            'pay_user'=>$pay_data
        ];
        if($type == 'json'){
            $inc_user = $data;
            $max = 50;
            foreach($inc_user['inc_user'] as $v){
                $inc_user_time[] = $v['time'];
                $inc_user_number[] = $v['user_number'];
                $max = $v['user_number']>$max?$v['user_number']:$max;
            }
            foreach($inc_user['pay_user'] as $v){
                $pay_user_number[] = $v['user_number'];
                $max = $v['user_number']>$max?$v['user_number']:$max;
            }
            $data = [
                'max_day'=>$max,
                'date'=>$inc_user_time,
                'inc_user'=>$inc_user_number,
                'pay_user'=>$pay_user_number
            ];
            successJson('ok',$data);
        }
        return $data;
    }

    /**
     * 用户时间筛选
     * @param $h 小时
     */
    public function userdate($time){
        $total_user = db('user')->where('closed',0)->count(); //总用户
        $new_user = db('user')->whereTime('create_time', $time)->where('closed',0)->count();   //新增用户
        $pay_user = db('user')->whereTime('create_time', $time)->where('is_pay',1)->count();   //付费用户
        $today_user = db('access_statistics')->whereTime('create_time', $time)->group('uid')->count();       //访问用户
        return $data = [
            'total_user'=>$total_user,
            'new_user'=>$new_user,
            'pay_user'=>$pay_user,
            'today_user'=>$today_user,
        ];
    }

    /**
     * 用户数据
     */
    public function getUserdata(){
        $type = input('type');
        $date = input('date','today');
        if($date == 'today'){
            $data = $this->userdate('today');
        }else if($date == 'seven'){
            $data = $this->userdate('-168 hours');
        }else if($date == 'thirty'){
            $data = $this->userdate('-720 hours');
        }else if($date == 'all'){
            $total_user = db('user')->where('closed',0)->count();
            $new_user = db('user')->where('closed',0)->count();
            $pay_user = db('user')->where('is_pay',1)->count();
            $today_user = db('access_statistics')->group('uid')->count();
            $data = [
                'total_user'=>$total_user,
                'new_user'=>$new_user,
                'pay_user'=>$pay_user,
                'today_user'=>$today_user,
            ];
        }else{
            $date = (explode("-",$date));
            $start_date =  strtotime(trim($date[0].' 00:00:00'));
            $end_date = strtotime(trim($date[1]).' 23:59:59');
            $map['create_time'] = ['between',[$start_date,$end_date]];
            $total_user = db('user')->where('closed',0)->count();
            $new_user = db('user')->where($map)->where('closed',0)->count();
            $pay_user = db('user')->where($map)->where('is_pay',1)->count();
            $today_user = db('access_statistics')->where($map)->count();
            $data = [
                'total_user'=>$total_user,
                'new_user'=>$new_user,
                'pay_user'=>$pay_user,
                'today_user'=>$today_user,
            ];
        }
        if($type == 'json'){
            successJson('ok',$data);
        }
        return $data;
    }

    /**
     * 付费用户
     */
    public function getPayuser(){
        $total_user = db('user')->where('closed',0)->count();//总用户
        $order_user = db('user')->where('closed',0)->where('is_pay',1)->count();//付费用户
        $reg_user = $total_user-$order_user; //非付费用户
        $data = [
            'total_user' => $total_user,
            'order_user'=>$order_user,
            'reg_user'=>$reg_user
        ];
        return $data;
    }

    /**
     * 访问记录用户端来源
     */
    public function getTerminal(){
        $type = input('type');
        $date = input('date',date('Y/m/d'));
        $start_date = strtotime($date);
        $end_date = mktime(23, 59, 59, date('m', strtotime($date))+1, 00);
        $map['create_time'] = ['between',[$start_date,$end_date]];
        $map['source'] = 1;
        $pc = $h5 = $wx = 0; //pc pc电脑 h5微信  wx小程序
        $result = db('access_statistics')->field('terminal,count(id) as c')->where($map)->group('terminal')->select();
        foreach($result as $v){
            switch($v['terminal']){
                case '1':
                    $pc = $v['c'];
                    break;
                case '2':
                    $h5 = $v['c'];
                    break;
                case '3':
                    $wx = $v['c'];
                    break;
            }
        }
        $data = [
            'pc'=>$pc,
            'h5'=>$h5,
            'wx'=>$wx
        ];
        if($type == 'json'){
            successJson('ok',$data);
        }
        return $data;
    }
}