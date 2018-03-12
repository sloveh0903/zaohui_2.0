<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
/**
 * Class Config 网站配置信息
 * @package app\api\controller
 */
class Config extends Controller
{
    public function _initialize()
    {

    }

    public function getIndex()
    {
        $config =  find('Config');
        $configData['sitename'] = "";
        $configData['siteurl'] = "";
        $configData['logo'] = "";
        $configData['aboutus'] = "";
        $configData['officer_url'] = "";
        $configData['seo_keywords'] = "";
        $configData['seo_description'] = "";
        $configData['wx_open_appid'] = "";
        $configData['copyright'] = "";
        if($config){
            $configData['sitename'] = $config['sitename'];
            $configData['siteurl'] = $config['siteurl'];
            $configData['logo'] = $config['logo'];
            $configData['aboutus'] = $config['aboutus'];
            $configData['officer_url'] = $config['officer_url'];
            $configData['seo_keywords'] = $config['seo_keywords'];
            $configData['seo_description'] = $config['seo_description'];
            $configData['wx_open_appid'] = $config['wx_open_appid'];
            $configData['copyright'] = $config['copyright'];
        }
        $data['config'] = $configData;
        successJson('操作完成',$data);
    }



}
