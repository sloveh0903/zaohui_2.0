<?php

namespace app\admin\controller;

class Qi extends Base
{
    public function upload(){
        try{
            $qiniu = new \qn\Qiniu();
            $result = $qiniu->uploadByVideo();
            p($result);
        }catch (Exception $e){
            dump($e);
        }
    }
}