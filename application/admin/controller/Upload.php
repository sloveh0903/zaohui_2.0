<?php

namespace app\admin\controller;

use think\Controller;
use think\File;
use think\Request;
use think\Loader;
use think\Log;

class Upload extends Base
{


    /*
     * 上传广告图片
     */
    public function uploadAdImage()
    {
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/image/adv');
        if ($info) {
            $res['status'] = 1;
            $res['image_name'] = $info->getSaveName();
            return json($res);

        } else {
            $res['status'] = 0;
            $res['error_info'] = $file->getError();
            return json($res);
        }
    }

    //图片上传
    public function upload($path = 'default')
    {
        $file = request()->file('file');
        $fileInfo = $file->getInfo();
        if($fileInfo['type'] == 'image/x-icon'){
            $info = $file->move(ROOT_PATH,'favicon');
        }else{
            $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/image/' . $path);
        }

        if ($info) {
            $res['status'] = 1;
            if($fileInfo['type'] == 'image/x-icon') {
                $res['image_name'] = "/" . str_replace('\\', '/', $info->getSaveName());
            }else{
                $res['image_name'] = '/public/uploads/image/' . $path . "/" . str_replace('\\', '/', $info->getSaveName());
            }
            return json($res);
        } else {
            $res['status'] = 0;
            $res['error_info'] = $file->getError();
            return json($res);
        }
    }

    public function advUpload()
    {
        return $this->upload('adv');
    }


    public function courseUpload()
    {
        return $this->upload('course');
    }

    public function userUpload()
    {
        return $this->upload('face');
    }

    public function articleUpload()
    {
        return $this->upload('article');
    }

    public function editorUpload()
    {
        return $this->uploadImage();
    }

    /*
         * 编辑器图片上传接口
         */
    public function uploadImage()
    {
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/editor');
        if ($info) {
            $res['src'] = 'http://' . $_SERVER['HTTP_HOST'] . '/public/uploads/editor/' . str_replace('\\', '/', $info->getSaveName());
            $result = array('code' => 0, 'data' => $res, 'msg' => '上传成功');
            return json($result);

        } else {

            $result = array('code' => -1, 'data' => '', 'msg' => $file->getError());

            return json($result);
        }
    }


    //文件上传
    public function uploadFile()
    {
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/file/down');
        if ($info) {
            $res['status'] = 1;
            $res['file_name'] = $info->getFilename();
            $res['file_path'] = "/public/uploads/file/down/" . str_replace('\\', '/', $info->getSaveName());
            return json($res);

        } else {
            $res['status'] = 0;
            $res['error_info'] = $file->getError();
            return json($res);
        }
    }

    //会员头像上传
    public function uploadface()
    {
        $file = request()->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/face');
        if ($info) {
            echo $info->getSaveName();
        } else {
            echo $file->getError();
        }
    }


    public function uploadVideo()
    {

        Loader::import('org.PolyvSDK');
        $PolyvSDK = new \PolyvSDK;
        //$file = $_FILES;
        $filename = $_FILES['file']['name'];
        $path = $_FILES['file']['tmp_name'];
        $type = $_FILES['file']['type'];


        if (class_exists('\CURLFile')) {
            $data = array('file' => new \CURLFile(realpath($path), $type, $filename));
        } else {
            $data = array(
                'file' => "@" . realpath($path) . ";type=" . $type . ";filename=" . $filename
            );
        }
        $result = $PolyvSDK->uploadfile(basename($filename), basename($filename), '', '1490687316233', $data['file']);

        if ($result) {
            $result['status'] = 1;
        } else {
            $result['status'] = 0;
            $result['error_info'] = "上传失败";
        }

        return json($result);

    }


}