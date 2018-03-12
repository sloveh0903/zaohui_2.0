<?php

namespace app\admin\model;
use think\Model;

class Course extends Base{

    protected $autoWriteTimestamp = true;




    public function incLenght($cid,$lenght){
        try{
            $this->where('cid',$cid)->setInc('lenght',$lenght);
            return ['status' => 200, 'data' => '', 'msg' => '时长修改成功'];
        }catch( PDOException $e){
            return ['status' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function decLenght($cid,$lenght){
        try{
            $this->where('cid',$cid)->setDec('lenght',$lenght);
            return ['status' => 200, 'data' => '', 'msg' => '时长修改成功'];
        }catch( PDOException $e){
            return ['status' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

}