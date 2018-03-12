<?php

namespace app\admin\controller;
use think\Db;
use app\model\Testitem_list;

class Exceltestitem extends Base
{

    public function import(){
        $list = [];
        $testitem = new Testitem_list();
        $flag = 0;
        if(request()->isPost()){
            $param = input('post.');
            $bank_id = $param['bank_id'];
            if(!$bank_id|| !$param['excel_path']){
                return json(['status' => -1,'url'=>'', 'data' => '', 'msg' => '请上传Excel']);
            }
            $file_path = '.'.$param['excel_path'];
            $result = $this->readExcel($file_path);
            $resultList = $result[0];
            $arr = [];
            $list_count= 0;
            //var_dump($resultList);
            foreach ($resultList as $k => $v) {
                
                if($k > 0){
                   $data =[];
                   $type = $v[0];
                   if(in_array($type, array(1,2,3,4))){
                       $data['bank_id'] = $bank_id;
                       $data['type'] = $type;
                       $data['level'] = $v[1];
                       $data['name'] = $v[2];
                       $correct_option = $v[3];
                       if(in_array($v[1], array(1,2,3,)) && $v[2]!=''){
                           if($type==4){
                               $temp_correct_option = explode(';', $correct_option);
                               $correct_option = json_encode($temp_correct_option);
                               $data['option']  = $correct_option;
                           }
                           $data['correct_option'] = $correct_option;
                           $data['parse'] = $v[4];
                           $temp_option_val = [];
                           if($type !=4){
                               $option_temp_key = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
                               $count = count($v)-1;
                               for($i=5;$i<=$count;$i++){
                                   if($v[$i] !=''){
                                       $temp_option_val[] = $v[$i];
                                   }
                                   
                               }
                               if($type==3){
                                   $option_temp_key =array_slice($option_temp_key,0,2);
                                   $temp_option_val = array_slice($temp_option_val,0,2);
                               }else{
                                   $option_temp_key =array_slice($option_temp_key,0,count($temp_option_val));
                               }
                               $option_temp= array_combine($option_temp_key,$temp_option_val);
                               $option = json_encode($option_temp);
                               $data['option']  = $option;
                           }
                           
                           $data['create_time'] = time();
                           $data['update_time'] = time();
                           $list_count++;
                           $arr[] = $data;
                       }
                       
                   }
                        
                }
            }
            
            if($arr){
                $list = $testitem->saveAll($arr);
                $uda['list_count'] = $list_count;
                update('Testitem_bank', ['id'=>$bank_id],$uda);
            }
        }
        if($list){
            return json(['status' => 200,'url'=>'/admin/testitemlist/index?bank_id='.$bank_id, 'data' => '', 'msg' => '导入成功']);
        }else{
            if($flag){
                return json(['status' => -1,'url'=>'', 'data' => '', 'msg' => '数据重复']);
            }else{
                return json(['status' => -1,'url'=>'', 'data' => '', 'msg' => '导入失败']);
            }
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






}