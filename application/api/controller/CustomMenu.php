<?php
namespace app\api\controller;
use think\Controller;
use think\File;
use app\api\validate as validate;

/**
 * 自定义菜单
 * @package app\api\controller
 */
class CustomMenu extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->getIndex();
    }

    /**
     * 菜单列表
     */
    public function getIndex(){
        //一级菜单
        $menu = lists('CustomMenu',['fid'=>0],['orderby'=>'asc']);
        //子菜单
        $submenu_where['fid'] = ['>',0];
        $submenu = lists('CustomMenu',$submenu_where,['orderby'=>'asc']);
        foreach($menu as &$v){
            $v['children'] = [];
            foreach($submenu as $s){
                if($v['id'] == $s['fid']){
                    $v['children'][] = $s;
                }
            }
        }
        successJson('ok',$menu);
    }

    /**
     * 编辑菜单
     */
    public function postUpdate(){
        $id = input('id');
        $title = input('title');
        $link = input('link');
        if(empty($title)){
            errorJson('标题不能为空');
        }
        if(mb_strlen($title) > 4){
            errorJson('字数不超过4个字');
        }
        if(!preg_match('/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$link)){
            errorJson('链接地址格式不正确');
        }
        update('CustomMenu',['id'=>$id],['title'=>$title,'link'=>$link]);
        successJson('ok');
    }

    /**
     * 添加菜单
     */
    public function postCreate(){
        $title = input('title');
        $fid = input('fid',0);
        $link = input('link');
        if(empty($title)){
           errorJson('标题不能为空');
        }
        if(mb_strlen($title) > 4){
            errorJson('字数不超过4个字');
        }
        if(!preg_match('/http:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$link)){
            errorJson('链接地址格式不正确');
        }
        if($fid > 0){
            $count = counts('CustomMenu',['fid'=>$fid]);
            if($count == 5){
                errorJson('子菜单最多能添加五个');
            }
            update('CustomMenu',['id'=>$fid],['link'=>'']);
            $last = find('CustomMenu',['fid'=>$fid],false,false,['orderby'=>'asc']);
        }else{
            $count = counts('CustomMenu',['fid'=>0]);
            if($count == 3){
                errorJson('一级菜单最多能添加三个');
            }
            $last = find('CustomMenu',['fid'=>0],false,false,['orderby'=>'asc']);
        }
        if(!$last){
            $last['orderby'] = 1;
        }
        $data = [
            'fid'=>$fid,
            'title'=>$title,
            'link'=>$link,
            'orderby'=>$last['orderby']+1
        ];
        $result = insert('CustomMenu',$data);
        if(!$result){
            errorJson('添加失败');
        }
        successJson('添加成功');

    }

    /**
     * 删除菜单
     */
    public function postDelete(){
        $id = input('id');
        $menu = find('CustomMenu',['id'=>$id]);
        if($menu){
            delete('CustomMenu',['id'=>$id]);
            if($menu['fid'] == 0){
                delete('CustomMenu',['fid'=>$id]);
            }
        }
        successJson('ok');
    }

    /**
     * 发布菜单
     */
    public function postRelease(){
        $button = [];
        //一级菜单
        $menu = lists('CustomMenu',['fid'=>0],['orderby'=>'asc']);
        //子菜单
        $submenu_where['fid'] = ['>',0];
        $submenu = lists('CustomMenu',$submenu_where,['orderby'=>'asc']);
        $i = 0;
        foreach($menu as &$v){
            $count = counts('CustomMenu',['fid'=>$v['id']]);
            if($count == 0 && $v['link'] == ''){
                errorJson('链接地址不能为空');
            }
            if($count == 0){
                $button[$i]['type'] = 'view';
                $button[$i]['name'] = urlencode($v['title']);
                $button[$i]['url'] = $v['link'];
            }else{
                $button[$i]['name'] = urlencode($v['title']);
                $j = 0;
                foreach($submenu as $s){
                    if($v['id'] == $s['fid']){
                        $button[$i]['sub_button'][$j]['type'] = 'view';
                        $button[$i]['sub_button'][$j]['name'] = urlencode($s['title']);
                        $button[$i]['sub_button'][$j]['url'] = $s['link'];
                        $j++;
                    }
                }
            }
            $i++;
        }
        $arr['button'] = $button;
        $result = json_encode($arr, JSON_UNESCAPED_UNICODE);
        $result = urldecode($result);
        $getAccessToken = getAccessToken(); //获取access_token
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$getAccessToken['access_token'];
        $curl = httprequest($url,$result);
        $decode = json_decode($curl,true);
        if($decode['errmsg'] == 'ok'){
            successJson('ok');
        }else{
            errorJson($decode['errmsg']);
        }
    }

    //上下移动排序
    public function getMoveUpDown(){
        $id = input('id');
        $table = 'CustomMenu';
        $updown = input('updown');
        $data_this = find($table,['id'=>$id]);
        $orderby = $data_this['orderby'];
        if($updown == "up"){
            $map['orderby'] = ['<',$orderby];
            $order = 'desc';
        }else{
            $map['orderby'] = ['>',$orderby];
            $order = 'asc';
        }
        $fid = $data_this['fid'];
        $map['fid'] = $fid;
        $data_next = find($table,$map,false,false,['orderby'=>$order]);
        if($data_next){
            $next_id = $data_next['id'];
            $next_order = $data_next['orderby'];
            update($table,['id'=>$id],['orderby'=>$next_order]);
            update($table,['id'=>$next_id],['orderby'=>$orderby]);
        }
        successJson();
    }

}
