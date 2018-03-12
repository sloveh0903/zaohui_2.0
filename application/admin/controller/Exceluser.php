<?php

namespace app\admin\controller;
use think\Db;

class Exceluser extends Base
{
    public function _empty(){
        return $this->index();
    }

    /**
     * 订单导出
     */
    public function index(){
        //die('export');
        //创建时间
         $star_time = input('star_time');
         $end_time = input('end_time');
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
         $where['closed']  = ['=',0];
         $user_data = db::name('user')->field('uid,nickname,mobile,now_integral,sex,create_time')->where($where)->select();
        if(!empty($user_data)){
            foreach ($user_data as $key=>$v){
                $user_data[$key]['create_time'] = date("Y-m-d H:i:s",$user_data[$key]['create_time']);
			  	$user_data[$key]['nickname'] = filterEmoji($user_data[$key]['nickname']);
                $sex = '保密';
                switch ($user_data[$key]['sex']){
                    case 1:
                        $sex = '男';
                        break;
                    case 2:
                        $sex = '女';
                        break;
                     default:
                         $sex = '保密';
                         break;
                }
                $user_data[$key]['sex'] = $sex;
                if($v['mobile']){
                    $total = Db::name('order')
                    ->where("uid=:uid or mobile =:mobile", ['uid' => $v['uid'], 'mobile' => $v['mobile']])
                    ->where('closed','0')
                    ->where('pay_status','1')
                    ->field('sum(price) as total')
                    ->find();
                }else{
                    $total = Db::name('order')
                    ->where("uid", $v['uid'])
                    ->where('closed','0')
                    ->where('pay_status','1')
                    ->field('sum(price) as total')
                    ->find();
                }
                $user_data[$key]['total'] =$total['total'] ? $total['total'] : 0;
               
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
            ->setCellValue('B'.$k, '手机号')
            ->setCellValue('C'.$k,'性别' )
            ->setCellValue('D'.$k, '积分')
            ->setCellValue('E'.$k, '消费总金额')
            ->setCellValue('F'.$k, '注册时间');
            foreach($user_data as $k => $v){
                $num=$k+2;
                $objPHPExcel->setActiveSheetIndex(0)
                //Excel的第A列，uid是你查出数组的键值，下面以此类推
                ->setCellValue('A'.$num, $v['nickname'])
                ->setCellValue('B'.$num, $v['mobile'])
                ->setCellValue('C'.$num, ' '.$v['sex'])
                ->setCellValue('D'.$num, $v['now_integral'])
                ->setCellValue('E'.$num, $v['total'])
                ->setCellValue('F'.$num, $v['create_time']);
            }
            $objPHPExcel->getActiveSheet()->setTitle('User');
            $objPHPExcel->setActiveSheetIndex(0);
            $name = date('Y-m-d').'_user';
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$name.'.xls"');
            header('Cache-Control: max-age=0');
            $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            $objWriter->save('php://output');
            exit;
        }else{
            echo ('<script>
                alert("没有用户数据");
                window.opener=null;
                window.open("","_self");
                window.close();
                </script>');

        }

    }




}