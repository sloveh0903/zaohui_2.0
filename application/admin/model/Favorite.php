<?php

namespace app\admin\model;
use think\Model;

class Favorite extends Base{


    public function delAllFavorite($ids){
        try{
            $this->where('id','exp'," IN (".$ids.")")->delete();
            return ['status' => 200, 'data' => '', 'msg' => '批量删除成功'];
        }catch( PDOException $e){
            return ['status' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }

    public function delFavorite($id)
    {
        try{
            $this->where(array('id'=>$id))->delete();
            return ['status' => 200, 'data' => '', 'msg' => '删除成功'];
        }catch( PDOException $e){
            return ['status' => 0, 'data' => '', 'msg' => $e->getMessage()];
        }
    }


}