<?php
namespace app\admin\controller;

class Customtemplate extends Base
{
    public function _empty(){
        return $this->index();
    }
    /**
     * 首页
     */
    public function index(){  
        //最后一个id值
        $drop_id = db('Custom_template')->order('id desc')->value('id');
        $this->assign('drop_id', $drop_id+100); //数据
        //分类
        $top_pid_arr = db('Course_category')->where(['closed'=>0,'pid'=>0])->select();
        $this->assign('top_pid_arr', $top_pid_arr); //数据
        $url = host().'/wechat/index/index';
        $qrcode = 'http://tool.oschina.net/action/qrcode/generate?data='.urlencode($url).'&output=image%2Fjpeg&error=L&type=0&margin=8';
        $this->assign('qrcode',$qrcode);
        return $this->fetch();
    }    
}