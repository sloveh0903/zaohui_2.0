<?php

namespace app\admin\controller;

use app\admin\model\Config;
use think\Db;

class WechatKeywords extends Base
{
    public function index(){
        $Nowpage = input('get.p') ? input('get.p'):1;
        $limits = 20;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = counts('WechatKeywords');
        $allpage = ceil($count / $limits);
        $list = lists('WechatKeywords',['page'=>$start,'size'=>$limits]);
        if(request()->isAjax()){
            $msg['status'] =200;
            $msg['data']['list'] = $list;
            $msg['data']['title']="关键词回复列表";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        $this->assign('Nowpage', $Nowpage); //当前页
        $this->assign('allpage', $allpage); //总页数
        $this->assign('count', $count);
        return view();
    }

    public function create(){
        if(request()->isPost()){
            $param = input('post.');
            if ($param['msgtype'] == 1) {
                // 回复图片
                if ($param['photopath'] == '') {
                    return json([
                        'status' => '-200',
                        'url' => '',
                        'data' => '',
                        'msg' => '图片不能为空'
                    ]);
                }
                $filesize = filesize($_SERVER['DOCUMENT_ROOT'] . $param['photopath']);
                if ($filesize > 2097152) {
                    return json([
                        'status' => '-200',
                        'url' => '',
                        'data' => '',
                        'msg' => '图片不能大于2M'
                    ]);
                }
                $file_info = array(
                    'media' => "@{$_SERVER['DOCUMENT_ROOT']}{$param['photopath']}"
                );

                $config = Db::name('config')->where(array('id' => '1'))->field('wx_public_appid,wx_public_secret')->find();
                $result = load_wechat('Media', array('appid' => $config['wx_public_appid'], 'appsecret' => $config['wx_public_secret']))->uploadForeverMedia($file_info, 'image');
                if (isset($result['media_id'])) {
                    $param['mediaid'] = $result['media_id'];
                    $param['url'] = $result['url'];
                }
            }
            unset($param['file']);
            $result = insert('WechatKeywords',$param);
            if(!$result){
                return json(['status'=>'-200','url'=>'/admin/wechatkeywords/create', 'data' => '', 'msg' => '添加失败']);
            }
            return json(['status'=>'200','url'=>'/admin/wechatkeywords/index', 'data' => '', 'msg' => '添加成功']);
        }
        return view();
    }

    public function update(){
        $id = input('id');
        $result = find('WechatKeywords',['id'=>$id]);
        if(!$result){
            return json(['status'=>'-200','url'=>'/admin/wechatkeywords/create', 'data' => '', 'msg' => '数据不存在']);
        }
        if(request()->isPost()){
            $param = input('post.');
            $data = find('WechatKeywords',['id'=>$id]);
            if ($param['msgtype'] == 1) {
                // 回复图片
                if ($param['photopath'] == '') {
                    return json([
                        'status' => '-200',
                        'url' => '',
                        'data' => '',
                        'msg' => '图片不能为空'
                    ]);
                }
                $filesize = filesize($_SERVER['DOCUMENT_ROOT'] . $param['photopath']);
                if ($filesize > 2097152) {
                    return json([
                        'status' => '-200',
                        'url' => '',
                        'data' => '',
                        'msg' => '图片不能大于2M'
                    ]);
                }
                $file_info = array(
                    'media' => "@{$_SERVER['DOCUMENT_ROOT']}{$param['photopath']}"
                );

                $config = Db::name('config')->where(array('id' => '1'))->field('wx_public_appid,wx_public_secret')->find();
                if($data['photopath'] != $param['photopath']) {
                    $result = load_wechat('Media', array('appid' => $config['wx_public_appid'], 'appsecret' => $config['wx_public_secret']))->uploadForeverMedia($file_info, 'image');
                    if (isset($result['media_id'])) {
                        load_wechat('Media',array('appid'=>$config['wx_public_appid'],'appsecret'=>$config['wx_public_secret']))->delForeverMedia($data['mediaid']);
                        $param['mediaid'] = $result['media_id'];
                        $param['url'] = $result['url'];
                    }
                }
            }
            unset($param['file']);
            $result = update('WechatKeywords',['id'=>$id],$param);
            return json(['status'=>'200','url'=>'/admin/wechatkeywords/index', 'data' => $result, 'msg' => '编辑成功']);
        }
        $this->assign('result',$result);
        return view();
    }

    public function delete(){
        $id = input('id');
        $data = find('WechatKeywords',['id'=>$id]);
        $config = Db::name('config')->where(array('id' => '1'))->field('wx_public_appid,wx_public_secret')->find();
        if($data['mediaid']) {
            load_wechat('Media',array('appid'=>$config['wx_public_appid'],'appsecret'=>$config['wx_public_secret']))->delForeverMedia($data['mediaid']);
        }
        delete('WechatKeywords',['id'=>$id],true);
        return json(['status'=>'200','url'=>'/admin/wechatkeywords/index', 'data' => '', 'msg' => '删除成功']);
    }
    /**
     * 微信多客服开关
     */
    public function customer()
    {
        $is_customer = show_switch('is_customer');
        $switch= input('switch');
        if(request()->isPost()){
            $switch_is_customer = $is_customer==1?0:1;
            update_show_switch('is_customer', $switch_is_customer);
            return json(['status' => 200, 'data' => '', 'msg' => '操作成功']);
        }
        $this->assign('is_customer', $is_customer);
        return view();
    }
}