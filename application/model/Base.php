<?php

namespace app\model;
use think\Model;

class Base extends Model{
    public function InsertData($data){
        $msg = [];
        try{
            $result = $this->allowField(true)->save($data);
            if(false === $result){
                $msg['status'] = '-200';
                $msg['msg'] = $this->getError();
            }else{
                $msg['status'] = '200';
                $msg['msg'] = '添加成功';
            }
        }catch( PDOException $e){
            $msg['status'] = '-200';
            $msg['msg'] = $e->getMessage();
        }
        return $msg;
    }

    public function UpdateData($data,$where){
        $msg = [];
        try{
            $result = $this->allowField(true)->save($data,$where);
            if(false === $result){
                $msg['status'] = '-200';
                $msg['msg'] = $this->getError();
            }else{
                $msg['status'] = '200';
                $msg['msg'] = '编辑成功';
            }
        }catch( PDOException $e){
            $msg['status'] = '-200';
            $msg['msg'] = $e->getMessage();
        }
        return $msg;
    }

    public function DeleteData($where,$closed = false){
        $msg = [];
        if($closed){
            try{
                $result = $this->where($where)->delete();
                if(false === $result){
                    $msg['status'] = '-200';
                    $msg['msg'] = $this->getError();
                }else{
                    $msg['status'] = '200';
                    $msg['msg'] = '删除成功';
                }
            }catch( PDOException $e){
                $msg['status'] = '-200';
                $msg['msg'] = $e->getMessage();
            }
        }else{
            try{
                $result = $this->where($where)->setField(['closed'=>1]);
                if(false === $result){
                    $msg['status'] = '-200';
                    $msg['msg'] = $this->getError();
                }else{
                    $msg['status'] = '200';
                    $msg['msg'] = '删除成功';
                }
            }catch( PDOException $e){
                $msg['status'] = '-200';
                $msg['msg'] = $e->getMessage();
            }
        }

        return $msg;
    }

    public function SetParam($param,$where){
        $msg = [];
        try{
            $result = $this->where($where)->setField($param);
            if(false === $result){
                $msg['status'] = '-200';
                $msg['msg'] = $this->getError();
            }else{
                $msg['status'] = '200';
                $msg['msg'] = '设置成功';
            }
        }catch( PDOException $e){
            $msg['status'] = '-200';
            $msg['msg'] = $e->getMessage();
        }
        return $msg;
    }

}