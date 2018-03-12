<?php
namespace app\wechat\controller;
use think\Controller;
use app\admin\model\Config;
use app\api\controller\Api;

/**
 * Class Index
 * @package app\api\controller
 */
class Index extends Controller
{

    public function _initialize()
    {
        //微信sdk
        require_once EXTEND_PATH."org/wxsdk/jssdk.php";
        $config = find('Config');
        $jssdk = new \JSSDK($config['wx_public_appid'], $config['wx_public_secret']);
        $signPackage = $jssdk->GetSignPackage();
        $this->assign('signPackage',$signPackage);
        //用户信息
        $this->assign('config',$config);
        $this->assign('userinfo', wechatLogin());

    }

    public function _empty()
    {
        return $this->index();
    }

    /**
     *首页
     */
    public function index()
    {
        $config = new Config();
        $show_switch = db('show_switch')->where(['id'=>1])->field('is_customer,is_follow')->find();
        $this->assign('is_customer', $show_switch['is_customer']);
        $this->assign('is_follow', $show_switch['is_follow']);
        $cid = 0;
        $qrcode=$config->where('id',1)->field('wxcode,version')->find();//公众号关注二维码
        $this->assign('qrcode',$qrcode['wxcode']);
        $this->assign('version',$qrcode['version']);
        //自定义模板
        $course_study = get_course_studynum();//获取课程id  所学人数
        $lists = db('Custom_template')->where(['closed'=>0])->order('orderby asc')->select();
        foreach ($lists as $key=>$value){
            $key =$value['orderby'];
            $type=$value['type'];
            $return_arr[$key]['type'] = $type;
            if(in_array($type, array(1,3,6))){
                $content= json_decode($value['content'],true);
            }else{
                $content= $value['content'];
            }
            if($type==1){
                $return_arr[$key]['banner1'] = $content[0]['img'];
                $final_imgnum = count($content);
                $return_arr[$key]['banner2'] =$content[$final_imgnum-1]['img'];
                if($content){
                    foreach($content as $mkey=>$mval){
                        $link = '';
                        if(isset($mval['type'])&&$mval['type']){
                            switch ($mval['type']){
                                case 'course':
                                    if($mval['id']){
                                        $link = '/wechat/course/detail?cid='.$mval['id'];
                                    }
                                    break;
                                case 'package':
                                    if($mval['id']){
                                        $link = '/wechat/bundlelist/detail?id='.$mval['id'];
                                    }
                                    break;
                                case 'vip':
                                    $link = '/wechat/course/card/';
                                    break;
                                case 'allcourse':
                                    $link = '/wechat/index/show_more/';
                                    break;
                                case 'allpackage':
                                    $link= '/wechat/bundlelist/';
                                    break;
                                case 'link':
                                    $isMatched = preg_match('/^^((https|http|ftp|rtsp|mms)?:\/\/)[^\s]+$/', $content[$mkey]['link'], $matches);
                                    if($isMatched){
                                        $link = $content[$mkey]['link'];
                                    }
                                    break;
                            }
                        }
                        
                        $content[$mkey]['link'] = $link;
                    }
                }
                
                $return_arr[$key]['banner1_link'] = $content[0]['link'];
                $return_arr[$key]['banner2_link'] =$content[$final_imgnum-1]['link'];
            }
            else if($type==3){
                $content['title'] = $content['title'];
                $content['show_more'] = $content['show_more'];
                $course_id_arrstr = $content['course_id'];
                
                if($content['sort']==3){
                    $order_str = 'orderby desc';
                }else{
                    $order_str = 'cid desc';
                }
                $course_list = db('Course')->where(['cid'=>['in',$course_id_arrstr],'closed'=>0,'audit'=>1])->field('cid,title,face,banner,desc,price,virtual_amount')->order($order_str)->select();
                foreach($course_list as $ckey=>$cval){////
                    $cid = $cval['cid'];
                    //是否设置虚拟人数
                       if ($cval['virtual_amount'] == 0) {
                                if(isset($course_study[$cid])){
                                $course_list[$ckey]['study_count']=$course_study[$cid];
                            }else{
                                $course_list[$ckey]['study_count'] =0;
                            }
                        }else{
                            $course_list[$ckey]['study_count'] = $cval['virtual_amount'];
                        }  
                }
                //最热课程  排序冒泡
                $len = count($course_list);
                if(isset($content['sort'])&&$content['sort']==2){
                    for($k=1;$k<$len;$k++)
                    {
                        for($j=0;$j<$len-$k;$j++){
                            if($course_list[$j]['study_count']<$course_list[$j+1]['study_count']){
                                $temp =$course_list[$j+1];
                                $course_list[$j+1] =$course_list[$j] ;
                                $course_list[$j] = $temp;
                            }
                        }
                    }
                }
                $content['course_list'] =$course_list;
            }
            else if($type==6){//套餐
                $package_id = $content['package_id'];
                $packageList = array();
                if($package_id){
                    $return_arr[$key]['title'] = $content['title'];
                    $return_arr[$key]['show_more'] = $content['show_more'];
                    $return_arr[$key]['package_id'] =$content['package_id'];
                    $map['id'] = ['in',$package_id];
                    $map['closed'] = 0;
                    $map['audit'] = 1;
                    $lists = db('Package')->where($map)->order('orderby desc')->select();
                    if($lists){
                        foreach ($lists as $k=>$v) {
                            $ret_package = [];
                            $ret_package['banner'] = '';
                            $ret_package['id'] = $v['id'];
                            $ret_package['title'] = $v['title'];
                            $ret_package['price'] = $v['price'];
                            $ret_package['create_time'] = $v['create_time'];
                            $ret_package['audit'] = $v['audit'];
                            $course_id = json_decode($v['course_id']);
                            $ret_banner = '';
                            if($v['banner']){
                                $ret_banner = $v['banner'];
                            }
                            $banner_arr = get_package_banner($ret_banner,$course_id);
                            $ret_package['banner'] = $banner_arr['banner'];
                            $ret_package['banner_color'] = $banner_arr['banner_color'];
                            $packageList[] = $ret_package;
                        }
                    }
                    $return_arr[$key]['package_list']=$packageList;
                }
                
            }
            $return_arr[$key]['content'] = $content;
        }
        $this->assign('list',$return_arr);
        return view('index');
    }

    public function  show_more(){
        $config = new Config();
        $qrcode=$config->where('id',1)->field('wxcode,version')->find();//公众号关注二维码
        $this->assign('qrcode',$qrcode['wxcode']);
        $this->assign('version',$qrcode['version']);
        return view('show_more');
    }

    public function disable(){
        return view('disable');
    }
}
