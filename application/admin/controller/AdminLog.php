<?php

namespace app\admin\controller;
use app\model\AdminLog as mylog;
class AdminLog extends Base
{
    public function  log($type,$list){
        if($list){
            $detaillog = new mylog();
            $data['type'] = $type;
            $data['list'] = json_encode($list);
            $data['create_time'] = time();
            $data['daytime'] = date("Y-m-d H:i:s",time());
            $return_bool =  $detaillog->insert($data);
        }
    }
}