<?php

namespace app\admin\controller;
use app\admin\model\Order;
use app\admin\model\PackageOrder;
use app\admin\model\UcardRecord;
use app\admin\model\User;
use app\admin\model\UserCard;
use think\Db;
use think\Log;

class Excel extends Base
{
    public function _empty(){
        return $this->index();
    }


    public function index(){
        $course = Db::name('course')->where('closed',0)->select();
        $this->assign('course', $course);
        return $this->fetch();
    }

    public function import(){

        $list = [];
        $order = new Order();
        $user = new User();
        $flag = 0;
        if(request()->isPost()){
            $param = input('post.');
            $cid = $param['cid'];
            $file_path = '.'.$param['excel_path'];
            if(!$cid|| !$param['excel_path']){
                return json(['status' => -1,'url'=>'', 'data' => '', 'msg' => '请上传Excel']);
            }
            $result = $this->readExcel($file_path);
            $resultList = $result[0];
            $data = $arr = [];
            $course = Db::name('course')->where('cid',$cid)->find();
            foreach ($resultList as $k => $v) {
                if($k > 0 && preg_match("/^1[34578]{1}\d{9}$/",$v[1])){
                    $create_time = is_numeric($v[2]) ? strtotime($this->excelTime($v[2])) : strtotime(str_replace('/','-',$v[2]));
                    $expire_time = is_numeric($v[3]) ? strtotime($this->excelTime($v[3])) : strtotime(str_replace('/','-',$v[3]));
                    if(!$order->where(array('course_id'=>$cid,'mobile'=>$v[1],'expire_time'=>['>',time()],'closed'=>'0'))->find()){
                        $data['order_sn'] = date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                        $data['course_id'] = $course['cid'];
                        $data['price'] = $course['price'];
                        $data['pay_price'] = $course['price'];
                        $data['realname'] = $v[0];
                        $data['mobile'] = $v[1];
                        $data['order_type'] = 'course';
                        $data['source'] = 'import';
                        $data['uid'] = ($uid = $user->where(array('mobile'=>$v[2]))->value('uid')) ? $uid : 0 ;
                        $data['is_import'] = 1;
                        $data['create_time'] = $create_time;
                        $data['expire_time'] = $expire_time;
                        $data['course_name'] = $course['title'];
                        $data['course_banner'] = $course['banner'];
                        $data['pay_status'] = 1;
                        $data['pay_type'] = 1;
                        $arr[] = $data;
                    }else{
                        $flag = 1;
                    }
                }
            }
            if($arr){
                $list = $order->saveAll($arr);
            }
        }
        if($list){
            return json(['status' => 200,'url'=>'/admin/order/index', 'data' => $list, 'msg' => '导入成功']);
        }else{
            if($flag){
                return json(['status' => -1,'url'=>'', 'data' => '', 'msg' => '数据重复']);
            }else{
                return json(['status' => -1,'url'=>'', 'data' => '', 'msg' => '导入失败']);
            }
        }

    }

    public function cardImport(){
        $list = [];
        $order = new Order();
        $user = new User();
        if(request()->isPost()){
            $param = input('post.');
            $id = $param['id'];
            if(!$id|| !$param['excel_path']){
                return json(['status' => -1,'url'=>'', 'data' => '', 'msg' => '请上传Excel']);
            }
            $usercard = Db::name('userCard')->where('id',$id)->find();
            $mouth = $usercard['mouth'];
            $file_path = '.'.$param['excel_path'];
            $result = $this->readExcel($file_path);
            $resultList = $result[0];
            $data = $arr = [];

            foreach ($resultList as $k => $v) {
                if($k > 0 && preg_match("/^1[34578]{1}\d{9}$/",$v[1])){
                    $uid = 0;
                    if(!$order->where(array('mobile'=>$v[1],'order_type'=>'usercard','uid'=>'0','closed'=>'0'))->find()) {
                        $userinfo = $user->where(array('mobile' => $v[1]))->field('uid,expire_time')->find();
                        $data['order_sn'] = date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                        $data['usercard_id'] = $usercard['id'];
                        $data['price'] = $usercard['price'];
                        $data['pay_price'] = $usercard['price'];
                        $data['realname'] = $v[0];
                        $data['mobile'] = $v[1];
                        $data['uid'] = (isset($userinfo['uid'])) ? $userinfo['uid'] : 0;
                        $data['is_import'] = 1;
                        $data['source'] = 'import';
                        $data['order_type'] = 'usercard';
                        $data['create_time'] = is_numeric($v[2]) ? strtotime($this->excelTime($v[2])) : strtotime(str_replace('/', '-', $v[2]));
                        $data['expire_time'] = strtotime("+" . $usercard['mouth'] . " month", $data['create_time']);
                        $data['card_name'] = $usercard['title'];
                        $data['pay_status'] = 1;
                        $data['pay_type'] = 1;
                        if(isset($userinfo['uid'])){
                            $updateData['cardtype'] = 0;
                            if($usercard['type'] == 'life'){
                                $updateData['cardtype'] = 2;
                            }else{
                                $updateData['cardtype'] = 1;
                            }
                            if($userinfo['expire_time'] > time()){
                                //如果会员未到期
                                $updateData['expire_time'] = strtotime("+".$usercard['mouth']." month",$userinfo['expire_time']);
                            }else{
                                $updateData['expire_time'] = $data['expire_time'];
                            }
                            Db::name('user')->where('uid',$data['uid'])->update($updateData);
                        }

                        $arr[] = $data;
                    }
                }
            }

            if($arr){
                $list = $order->saveAll($arr);
            }
        }
        if($list){
            return json(['status' => 200,'url'=>'/admin/order/ucardorder', 'data' => $list, 'msg' => '导入成功']);
        }else{
            return json(['status' => -1,'url'=>'', 'data' => '', 'msg' => '导入失败']);
        }
    }


    public function packageImport(){
        $list = [];
        $order = new Order();
        $user = new User();
        $packageOrder = new PackageOrder();
        if(request()->isPost()){
            $param = input('post.');
            $id = $param['id'];
            if(!$id|| !$param['excel_path']){
                return json(['status' => -1,'url'=>'', 'data' => '', 'msg' => '请上传Excel']);
            }
            $package = Db::name('Package')->where('id',$id)->find();
            $file_path = '.'.$param['excel_path'];
            $result = $this->readExcel($file_path);
            $resultList = $result[0];
            $data = $arr = [];
            $package_order = [];
            foreach ($resultList as $k => $v) {
                if($k > 0 && preg_match("/^1[34578]{1}\d{9}$/",$v[1])){
                    $create_time = is_numeric($v[2]) ? strtotime($this->excelTime($v[2])) : strtotime(str_replace('/', '-', $v[2]));
                    $expire_time = strtotime("+12 month", $create_time);
                    //是否有订单未过期 == 数据重复
                    if(!$order->where(array('package_id'=>$id,'mobile'=>$v[1],'expire_time'=>['>',time()],'closed'=>'0'))->find()) {
                        $data['order_sn'] = date('Ymdhis') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
                        $data['package_id'] = $package['id'];
                        $data['price'] = $package['price'];
                        $data['pay_price'] = $package['price'];
                        $data['realname'] = $v[0];
                        $data['mobile'] = $v[1];
                        $data['is_import'] = 1;
                        $data['source'] = 'import';
                        $data['uid'] = ($uid = $user->where(array('mobile' => $v[1]))->value('uid')) ? $uid : 0;
                        $data['order_type'] = 'package';
                        $data['create_time'] = $create_time;
                        $data['expire_time'] = $expire_time;
                        $data['package_name'] = $package['title'];
                        $data['pay_status'] = 1;
                        $data['pay_type'] = 1;
                        $arr[] = $data;
                        $cid_arr = json_decode($package['course_id']);
                        foreach ($cid_arr as $value) {
                            $ret_package = [];
                            $ret_package['order_sn'] = $data['order_sn'];
                            $ret_package['package_id'] = $package['id'];
                            $ret_package['course_id'] = $value;
                            $ret_package['mobile'] = $data['mobile'];
                            $ret_package['uid'] = $uid > 0 ? $uid : 0;
                            $ret_package['is_import'] = 1;
                            $ret_package['create_time'] = $data['create_time'];
                            $ret_package['update_time'] = $data['create_time'];
                            $ret_package['expire_time'] = $data['expire_time'];
                            $package_order[] = $ret_package;
                        }
                    }
                }
            }
            if($arr){
                $list = $order->saveAll($arr);
            }
            if($package_order){
                $list = $packageOrder->saveAll($package_order);
            }

        }
        if($list){
            return json(['status' => 200,'url'=>'/admin/order/packageorder', 'data' => $list, 'msg' => '导入成功']);
        }else{
            return json(['status' => -1,'url'=>'', 'data' => '', 'msg' => '数据重复']);
        }
    }




    public function readExcel($file_path)
    {
        \think\Loader::import('org.PHPExcel.IOFactory');
        $arrData = [];
        $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $AllSheets = $objReader->load($file_path);
        $AllSheet = $AllSheets->getAllSheets();
        foreach ($AllSheet as $sheet) {
            $arrData[] = $sheet->toArray();
        }
        return $arrData;
    }

    //excel时间转换成正常日期
    public function excelTime($date, $time = false) {
        if(function_exists('GregorianToJD')){
            if (is_numeric( $date )) {
                $jd = GregorianToJD( 1, 1, 1970 );
                $gregorian = JDToGregorian( $jd + intval ( $date ) - 25569 );
                $date = explode( '/', $gregorian );
                $date_str = str_pad( $date [2], 4, '0', STR_PAD_LEFT )
                    ."-". str_pad( $date [0], 2, '0', STR_PAD_LEFT )
                    ."-". str_pad( $date [1], 2, '0', STR_PAD_LEFT )
                    . ($time ? " 00:00:00" : '');
                //return $date_str;
                return date('Y-m-d',strtotime("$date_str - 1 days"));
            }
        }
        return $date;
    }

    /**
     * 订单导出
     */
    public function export(){
        //die('export');
        
        $cid = input('cid');
        $star_time = input('star_time');
        $end_time = input('end_time');
        $pay_status = input('pay_status');
        //课程
        if($cid > 0){
            $where['course_id'] = $cid;
        }
        //报名时间
        $firstday  = strtotime($star_time);
        $lastday  = strtotime($end_time)+86400;
        if(!empty($star_time) && !empty($end_time)){
            $where['create_time'] = ['between',[$firstday,$lastday]];
        }else{
            if(!empty($star_time)){
                $where['create_time'] = ['>=',$firstday];
            }
            if(!empty($end_time)){
                $where['create_time'] = ['<=',$lastday];
            }
        }
        //支付状态
        $where['pay_status'] = 1;
        /*if($pay_status == 1){
            $where['pay_status'] = 1;
        }
        if($pay_status == 0){
            $where['pay_status'] = 0;
        }*/
        $where['order_type'] = 'course';
        $where['closed'] = 0;
        $order_data = db::name('order')->where($where)->select();
        
        if(!empty($order_data)){
            foreach($order_data as $key=>$v){
                $order_data[$key]['create_time'] = date("Y-m-d H:i:s",$order_data[$key]['create_time']);
                $user_data = db::name('user')->where('uid',$order_data[$key]['uid'])->find();
                $order_data[$key]['nickname'] = filterEmoji($user_data['nickname']);
                $order_data[$key]['mobile']= $user_data['mobile'];
            }
            \think\Loader::import('org.PHPExcel.IOFactory');
            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->getProperties()->setCreator("")
                ->setLastModifiedBy("")
                ->setTitle("数据EXCEL导出")
                ->setSubject("数据EXCEL导出")
                ->setDescription("备份数据")
                ->setKeywords("excel")
                ->setCategory("result file");
            $k = 1;
            $objPHPExcel->setActiveSheetIndex(0)
                //Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue('A'.$k, '用户昵称')
                ->setCellValue('B'.$k, '姓名')
                ->setCellValue('C'.$k, '手机号')
                ->setCellValue('D'.$k, '课程名称')
                ->setCellValue('E'.$k, '课程价格')
                ->setCellValue('F'.$k, '支付价格')
                ->setCellValue('G'.$k, '订单编号')
                ->setCellValue('H'.$k, '报名时间');
            foreach($order_data as $k => $v){
                $num=$k+2;
                $objPHPExcel->setActiveSheetIndex(0)
                    //Excel的第A列，uid是你查出数组的键值，下面以此类推
                    ->setCellValue('A'.$num, $v['nickname'])
                    ->setCellValue('B'.$num, $v['realname'])
                    ->setCellValue('C'.$num, ' '.$v['mobile'])
                    ->setCellValue('D'.$num, $v['course_name'])
                    ->setCellValue('E'.$num, $v['price'])
                    ->setCellValue('F'.$num, $v['pay_price'])
                    ->setCellValue('G'.$num, ' '.$v['order_sn'])
                    ->setCellValue('H'.$num, ' '.$v['create_time']);
            }
            $objPHPExcel->getActiveSheet()->setTitle('User');
            $objPHPExcel->setActiveSheetIndex(0);
            $name = date('Y-m-d').'_order';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$name.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            echo ('<script>
                alert("没有订单数据");
                window.opener=null;
                window.open("","_self");
                window.close();
                </script>');

        }

    }




}