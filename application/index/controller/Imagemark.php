<?php
namespace app\index\controller;
use think\Controller;

class Imagemark extends Controller{

    public static function robot($path, $text = "")
    {
        // 字体
        $font = ROOT_PATH.'/public'.DS."ttf/Light.ttf";
        $info = getimagesize($path);

        if ($info && isset($info['mime'])) {

            $width  = $info[0];
            $height = $info[1];
            $image  = false;
            $smallHeight = 80;

            list($tmp, $type) = explode('/', $info['mime']);

            // 根据图片地址，创建图片
            switch (strtolower($type)) {
                case 'png':
                    $image = imagecreatefrompng($path);
                    break;
                case 'jpg':
                case 'jpeg':
                    $image = imagecreatefromjpeg($path);
                    break;
                case 'gif':
                    $image = imagecreatefromgif($path);
                    break;
            }

            if ($image) {

                // 最后生成的图片结果
                $imageResource = imagecreatetruecolor($width, $height + $smallHeight);

                // 图片右下角写入日期
                $dateText = date("Y-m-d");
                $whiteColor = imagecolorallocate($image, 255, 255, 255);
                imagettftext($image, 11, 0, $width - 90, $height - 6, $whiteColor, $font, $text);

                // {{{ 通过代码创建图片
                $newImage = imagecreate($width, $smallHeight);
                $newImageColor = imagecolorallocate($newImage, 255, 255, 255);
                $newImageSmallTextColor = imagecolorallocate($newImage, 128, 128, 128);
                $newImageBigTextColor = imagecolorallocate($newImage, 0, 0, 0);
                imagettftext($newImage, 10, 0, 1, 34, $newImageSmallTextColor, $font, "拍照器材：NIKON");
                imagettftext($newImage, 15, 0, 1, 60, $newImageBigTextColor, $font, $text);
                // }}}

                //  {{{ 将两个处理过的图片合并到 $imageSource
                imagecopy($imageResource, $image, 0, 0, 0, 0, $width, $height);
                imagecopy($imageResource, $newImage, 0, $height, 0, 0, $width, $smallHeight);
                // }}}

                header("Content-Type: image/{$type}");
                switch (strtolower($type)) {
                    case 'png':
                        imagepng($imageResource);
                        break;
                    case 'jpg':
                    case 'jpeg':
                        imagejpeg($imageResource);
                        break;
                    case 'gif':
                        imagegif($imageResource);
                        break;
                }

                imagedestroy($image);
                imagedestroy($newImage);
                imagedestroy($imageResource);
                die();
            }
        }

        return false;
    }
    public function showImg(){
        Imagemark::robot(ROOT_PATH.'/public'.DS.'pc/images/b_img90@2x.png','测试标题');

    }
}
