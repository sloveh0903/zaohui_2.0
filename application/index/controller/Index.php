<?php
namespace app\index\controller;
use think\Controller;
use app\api\controller\Api;
use think\Log;
use think\db;

/**
 * Class Index
 * @package app\api\controller
 */
class Index extends Controller
{
    public $uid;

    public function _initialize()
    {
        //session('uid',69);
    }

    /**
     *首页
     */
    public function index()
    {
        $pid_arr = $child_cate = array();
        $result =  apiget('index/index');
        $config = find('Config');
        $course = Db::name('course')->where(array('closed'=>'0','audit'=>'1'))->order(array('cid'=>'desc'))->field('cid,banner,price,title,desc')->limit(10)->select();
        $cate_list =  Db::name('CourseCategory')->where(array('closed'=>'0'))->field('id,pid,cate_name')->order('orderby desc')->select();
        $cates = $this->recursionTree($cate_list,'id','pid','0');
        if(!$config['wxmincode']){
            $config['wxmincode'] = '/public/gzadmin/images/kong-miniprom.png';
        }
        $this->assign('course',$result['data']['course']);
        $this->assign('config',$config);
        //pc首页图片url
        $adv_item =Db::name('AdvItem')->where('adv_id',3)->where('closed',0)->field('photopath,link')->order('id desc')->select();
//         $pc_url = isset($adv_item['photopath'])?$adv_item['photopath']:'/public/pc/images/indexBG@2x.jpg';//默认
//         $this->assign('pc_url',$pc_url);
        $this->assign('adv_item',$adv_item);
        $this->assign('course',$course);
        $this->assign('cate',$cates);

        return view('index');
    }

    public function poster(){
        $uid = input('uid',0);
        $cid = input('cid',1);
        $poster_type = input('poster_type',1);
        apiget('course/poster',['cid'=>$cid,'uid'=>$uid,'poster_type'=>$poster_type]);
        $getPosterFile = getPosterFile($uid,$cid);
        echo "<img src='".$getPosterFile."' style='width:100%;height:100%'>";die;
    }

    public function deltb(){
        db('adv_item')->delete();
        db('answer')->delete();
        db('article')->delete();
        db('article_category')->delete();
        db('article_comment')->delete();
        db('ask')->delete();
        db('ask_category')->delete();
        db('browse_record')->delete();
        db('comment')->delete();
        db('course')->delete();
        db('course_category')->delete();
        db('course_introduce')->delete();
        db('course_password')->delete();
        db('favorite')->delete();
        db('feedback')->delete();
        db('follow')->delete();
        db('like')->delete();
        db('log')->delete();
        db('notify')->delete();
        db('order')->delete();
        db('point')->delete();
        db('poster')->delete();
        db('read')->delete();
        db('rebate')->delete();
        db('recharge_record')->delete();
        db('search')->delete();
        db('share')->delete();
        db('sms')->delete();
        db('study_list')->delete();
        db('template_list')->delete();
        db('user')->delete();
        db('video')->delete();
        db('video_category')->delete();
        db('wechat_keywords')->delete();
        db('withdraw')->delete();
    }

    public function recursionTree($arr,$key,$fkey,$num)
    {
        $list = array();
        foreach($arr as $val){
            if($val[$fkey] == $num){
                $tmp = $this->recursionTree($arr,$key,$fkey,$val[$key]);
                if($tmp){
                    $val['son'] = $tmp;
                }
                $list[] = $val;
            }
        }
        return $list;
    }

}
