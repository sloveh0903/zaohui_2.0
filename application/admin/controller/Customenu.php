<?php
namespace app\admin\controller;

use think\Db;
use app\model\CustomMenu;

class Customenu extends Base
{

    /**
     * 菜单列表
     * 
     * @return \think\response\View
     */
    public function index()
    {
        $list = [];
        $menu = lists('CustomMenu', [
            'fid' => 0
        ], [
            'orderby' => 'asc'
        ]);
        $submenu = lists('CustomMenu', [
            'fid' => [
                '>',
                0
            ]
        ], [
            'orderby' => 'asc'
        ]);
        foreach ($menu as $k => $v) {
            $v['type_name'] = $this->get_type_name($v['type']);
            $list[] = $v;
            foreach ($submenu as $s) {
                if ($v['id'] == $s['fid']) {
                    $s['type_name'] = $this->get_type_name($s['type']);
                    $list[] = $s;
                }
            }
        }
        if (request()->isAjax()) {
            // $article->getlastsql();
            $msg['status'] = 200;
            $msg['data']['list'] = $list;
            $msg['data']['title'] = "自定义菜单列表";
            return json($msg);
        }
        return view();
    }

    /**
     * 添加菜单
     * 
     * @return \think\response\Json|\think\response\View
     */
    public function create()
    {
        $custom_menu = lists('CustomMenu', [
            'fid' => 0
        ], [
            'orderby' => 'asc'
        ]);
        if (request()->isPost()) {
            $title = input('title');
            $fid = input('fid', 0);
            $type = input('type');
            if (empty($title)) {
                return json([
                    'status' => '-200',
                    'url' => '/admin/customenu/create',
                    'data' => '',
                    'msg' => '标题不能为空'
                ]);
            }
            if (mb_strlen($title) > 8) {
                return json([
                    'status' => '-200',
                    'url' => '/admin/customenu/create',
                    'data' => '',
                    'msg' => '菜单字数不超过8个字'
                ]);
            }
            $config = Db::name('config')->where(array('id'=>'1'))->field('wx_public_appid,wx_public_secret')->find();
            if ($fid > 0) {
                $count = counts('CustomMenu', [
                    'fid' => $fid
                ]);
                if ($count == 5) {
                    return json([
                        'status' => '-200',
                        'url' => '/admin/customenu/create',
                        'data' => '',
                        'msg' => '子菜单最多能添加五个'
                    ]);
                }
                //将一级菜单的 事件清空
                $menu_select_data = db::name('custom_menu')->where('id', $fid)->field('type,media_id')->find();
                if($menu_select_data['type']=='media_id'){//删除微信 永久图片素材
                    if($menu_select_data['media_id']){
                        load_wechat('Media',array('appid'=>$config['wx_public_appid'],'appsecret'=>$config['wx_public_secret']))->delForeverMedia($menu_select_data['media_id']);
                    }
                }
                db::name('custom_menu')->where('id', $fid)->update(['type' => '','content' => '','key' => '','media_id' => '','appid' => '','pagepath' => '','link' => '']);
                $last = find('CustomMenu', ['fid' => $fid], false, false, ['orderby' => 'desc']);
            } else {
                $count = counts('CustomMenu', [
                    'fid' => 0
                ]);
                if ($count == 3) {
                    return json([
                        'status' => '-200',
                        'url' => '/admin/customenu/create',
                        'data' => '',
                        'msg' => '一级菜单最多能添加三个'
                    ]);
                }
                $last = find('CustomMenu', [
                    'fid' => 0
                ], false, false, [
                    'orderby' => 'desc'
                ]);
            }
            if (! $last) {
                $last['orderby'] = 1;
            }
            $data = [
                'type' => $type,
                'fid' => $fid,
                'title' => $title,
                'orderby' => $last['orderby'] + 1
            ];
            if ($type == 'view') { // 链接
                $link = input('link');
                if (! preg_match('/http:|https:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is', $link)) {
                    return json([
                        'status' => '-200',
                        'url' => '/admin/customenu/create',
                        'data' => '',
                        'msg' => '链接地址格式不正确'
                    ]);
                }
                $data['link'] = $link;
            }
            elseif ($type == 'indexview') {
                $data['type']='view';
                $data['link'] = host().'/wechat/index';
                $data['pagepath'] = 'index';
            }
            elseif ($type == 'click') {
                $content = input('content');
                if(!$content){
                    return json([
                        'status' => '-200',
                        'url' => '/admin/customenu/create',
                        'data' => '',
                        'msg' => '文字不能为空'
                    ]);
                }
                $content = str_replace("</p><p>","\n",$content);
                $content = str_replace('&nbsp;',' ',$content);
                $content = preg_replace("/<p.*?>|<\/p>/is","", $content);
                $data['content']=$content;
            }
            elseif ($type == 'miniprogram') { // 小程序
                $pagepath = input('pagepath');
                $mini_wxappid = db('config')->where('id', 1)->value('wx_appid'); // 小程序appid
                if (! $mini_wxappid) {
                    return json([
                        'status' => '-200',
                        'url' => '/admin/customenu/create',
                        'data' => '',
                        'msg' => '小程序未配置'
                    ]);
                }
                $data['appid'] = $mini_wxappid;
                $data['pagepath'] = $pagepath;
                $data['link'] = 'http://'.$_SERVER['HTTP_HOST'].'/wechat';
            } elseif ($type == 'media_id') { // 图片
                $photopath = input('photopath');
                if ($photopath == '') {
                    return json([
                        'status' => '-200',
                        'url' => '',
                        'data' => '',
                        'msg' => '图片不能为空'
                    ]);
                }
                $filesize = filesize($_SERVER['DOCUMENT_ROOT'] . $photopath);
                if ($filesize > 2097152) {
                    return json([
                        'status' => '-200',
                        'url' => '',
                        'data' => '',
                        'msg' => '图片不能大于2M'
                    ]);
                }
                $file_info = array(
                    'media' => "@{$_SERVER['DOCUMENT_ROOT']}{$photopath}"
                );
                $result = load_wechat('Media',array('appid'=>$config['wx_public_appid'],'appsecret'=>$config['wx_public_secret']))->uploadForeverMedia($file_info,'image');
                if(isset($result['media_id'])){
                    $data['media_id'] = $result['media_id'];
                    $data['link'] = $result['url'];
                    $data['photopath'] =$photopath;
                }
            }
            if($type=='click'){
                $custom_menu = new CustomMenu();
                $result = $custom_menu->insert($data);
                $id = $custom_menu->getLastInsID();
                $clickkey = "clickkey".$id;
                db::name('custom_menu')->where('id', $id)->update(['key'=>$clickkey]);
            }else{
                $result = insert('CustomMenu',$data);
            }
            
            if (! $result) {
                return json([
                    'status' => '-200',
                    'url' => '/admin/customenu/create',
                    'data' => '',
                    'msg' => '添加失败'
                ]);
            }
            
            
            return json([
                'status' => '200',
                'url' => '/admin/customenu/index',
                'data' => '',
                'msg' => '添加成功'
            ]);
        }
        $this->assign('custom_menu', $custom_menu);
        return view();
    }

    /**
     * 编辑菜单
     * 
     * @return \think\response\View
     */
    public function update()
    {
        $id = input('id');
        $menu = find('CustomMenu', [
            'id' => $id
        ]);
        $custom_menu = lists('CustomMenu', [
            'fid' => 0
        ], [
            'orderby' => 'asc'
        ]);
        $has_link = true;
        if ($menu['fid'] == 0) {
            $count = counts('CustomMenu', [
                'fid' => $menu['id']
            ]);
            if ($count > 0) {
                $has_link = false;
            }
        }
        if (! $custom_menu) {
            return json([
                'status' => '-200',
                'url' => '/admin/customenu/update',
                'data' => '',
                'msg' => '数据存在'
            ]);
        }
        if (request()->isPost()) {
            $id = input('id');
            $title = input('title');
            $link = input('link', '');
            $type = input('type');
            if (empty($title)) {
                return json([
                    'status' => '-200',
                    'url' => '/admin/customenu/update',
                    'data' => '',
                    'msg' => '标题不能为空'
                ]);
            }
            if (mb_strlen($title) > 8) {
                return json([
                    'status' => '-200',
                    'url' => '/admin/customenu/update',
                    'data' => '',
                    'msg' => '字数不超过8个字'
                ]);
            }
            if($has_link){
                update('CustomMenu', [
                    'id' => $id
                ], [
                    'title' => $title,
                ]);
            }else{
                
            }

            $data = [
                'type' => $type,
                'title' => $title,
            ];
            if ($type == 'view') { // 链接
                $link = input('link');
                if (! preg_match('/http:|https:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is', $link)) {
                    return json([
                        'status' => '-200',
                        'url' => '/admin/customenu/update',
                        'data' => '',
                        'msg' => '链接地址格式不正确'
                    ]);
                }
                $data['link'] = $link;
            }elseif ($type == 'indexview') {
                $data['type']='view';
                $data['link'] = host().'/wechat/index';
                $data['pagepath'] = 'index';
            }elseif ($type == 'click') {
                $content = input('content');
                if(!$content){
                    return json([
                        'status' => '-200',
                        'url' => '/admin/customenu/update',
                        'data' => '',
                        'msg' => '文字不能为空'
                    ]);
                }
                $content = str_replace("</p><p>","\n",$content);
                $content = str_replace('&nbsp;',' ',$content);
                $content = preg_replace("/<p.*?>|<\/p>/is","", $content);
                $data['content']=$content;
                $data['key'] = "clickkey".$id;
            }
            elseif ($type == 'miniprogram') { // 小程序
                $pagepath = input('pagepath');
                $mini_wxappid = db('config')->where('id', 1)->value('wx_appid'); // 小程序appid
                if (! $mini_wxappid) {
                    return json([
                        'status' => '-200',
                        'url' => '/admin/customenu/update',
                        'data' => '',
                        'msg' => '小程序未配置'
                    ]);
                }
                if(!$pagepath){
                    return json([
                        'status' => '-200',
                        'url' => '/admin/customenu/update',
                        'data' => '',
                        'msg' => '小程序地址未配置'
                    ]);
                }
                $data['appid'] = $mini_wxappid;
                $data['pagepath'] = $pagepath;
                $data['link'] = 'http://'.$_SERVER['HTTP_HOST'].'/wechat';
            } elseif ($type == 'media_id') { // 图片
                $photopath = input('photopath');
                if ($photopath == '') {
                    return json([
                        'status' => '-200',
                        'url' => '',
                        'data' => '',
                        'msg' => '图片不能为空'
                    ]);
                }
                $filesize = filesize($_SERVER['DOCUMENT_ROOT'] . $photopath);
                if ($filesize > 2097152) {
                    return json([
                        'status' => '-200',
                        'url' => '',
                        'data' => '',
                        'msg' => '图片不能大于2M'
                    ]);
                }
                $file_info = array(
                    'media' => "@{$_SERVER['DOCUMENT_ROOT']}{$photopath}"
                );
                $old_photopath =db::name('custom_menu')->where('id', $id)->value('photopath');
                $menu_select_data = db::name('custom_menu')->where('id', $id)->field('photopath,media_id')->find();
                if($menu_select_data['photopath']!=$photopath){//删除微信 永久图片素材
                    if($menu_select_data['media_id']){
                        load_wechat('Media',array('appid'=>$config['wx_public_appid'],'appsecret'=>$config['wx_public_secret']))->delForeverMedia($menu_select_data['media_id']);
                    }
                }
                $result = load_wechat('Media',array('appid'=>$config['wx_public_appid'],'appsecret'=>$config['wx_public_secret']))->uploadForeverMedia($file_info,'image');
                if(isset($result['media_id'])){
                    $data['media_id'] = $result['media_id'];
                    $data['link'] = $result['url'];
                    $data['photopath'] =$photopath;
                }
            }
            
            db::name('custom_menu')->where('id', $id)->update($data);
                return json([
                    'status' => '200',
                    'url' => '/admin/customenu/index',
                    'data' => '',
                    'msg' => '编辑成功'
                ]);

        }
        $this->assign('has_link', $has_link);
        $this->assign('menu', $menu);
        $this->assign('custom_menu', $custom_menu);
        return view();
    }

    /**
     * 删除菜单
     */
    public function delete()
    {
        $id = input('id');
        $menu = find('CustomMenu', ['id' => $id]); 
        if ($menu) {
            $config = Db::name('config')->where(array('id'=>'1'))->field('wx_public_appid,wx_public_secret')->find();
            if($menu['type']=='media_id'){//删除微信 永久图片素材
                if($menu['media_id']){
                    load_wechat('Media',array('appid'=>$config['wx_public_appid'],'appsecret'=>$config['wx_public_secret']))->delForeverMedia($menu['media_id']);
                }
            }
            delete('CustomMenu', ['id' => $id], true);
            if ($menu['fid'] == 0) {
                delete('CustomMenu', ['fid' => $id], true);
            }
        }
        return json([
            'status' => '200',
            'url' => '/admin/customenu/index',
            'data' => '',
            'msg' => '删除成功'
        ]);
    }

    /**
     * 发布菜单
     */
    public function release()
    {
        $button = [];
        // 一级菜单
        $menu = lists('CustomMenu', [
            'fid' => 0
        ], [
            'orderby' => 'asc'
        ]);
        // 子菜单
        $submenu_where['fid'] = [
            '>',
            0
        ];
        $submenu = lists('CustomMenu', $submenu_where, [
            'orderby' => 'asc'
        ]);
        $i = 0;
        foreach ($menu as &$v) {
            $count = counts('CustomMenu', ['fid' => $v['id']]);
            if ($count == 0 && $v['link'] == ''&&!$v['type']) {
                return json([
                    'status' => '-200',
                    'url' => '/admin/customenu/create',
                    'data' => '',
                    'msg' => $v['title'] . '菜单未填写链接地址'
                ]);
            }
            if ($count == 0) {
                $button[$i]['name'] = urlencode($v['title']);
                $button[$i]['type'] = $v['type'];
                switch ($v['type']){
                    case 'view':
                        $button[$i]['url'] = $v['link'];
                        break;
                    case 'media_id':
                        $button[$i]['media_id'] = $v['media_id'];
                        break;
                    case 'miniprogram':
                        $button[$i]['url'] = $v['link'];
                        $button[$i]['appid'] = $v['appid'];
                        $button[$i]['pagepath'] = $v['pagepath'];
                        break;
                    case 'click':
                        $button[$i]['key'] = $v['key'];
                        break;
                }
            } else {
                $button[$i]['name'] = urlencode($v['title']);
                $j = 0;
                foreach ($submenu as $s) {
                    if ($v['id'] == $s['fid']) {
                        $button[$i]['sub_button'][$j]['name'] = urlencode($s['title']);
                        $button[$i]['sub_button'][$j]['type'] = $s['type'];
                        switch ($s['type']){
                            case 'view':
                                $button[$i]['sub_button'][$j]['url'] = $s['link'];
                                break;
                            case 'media_id':
                                $button[$i]['sub_button'][$j]['media_id'] = $s['media_id'];
                                break;
                            case 'miniprogram':
                                $button[$i]['sub_button'][$j]['url'] = $s['link'];
                                $button[$i]['sub_button'][$j]['appid'] = $s['appid'];
                                $button[$i]['sub_button'][$j]['pagepath'] = $s['pagepath'];
                                break;
                            case 'click':
                                $button[$i]['sub_button'][$j]['key'] = $s['key'];
                                break;
                        }
                        $j ++;
                    }
                }
            }
            $i ++;
        }
        $arr['button'] = $button;
       
        $result = json_encode($arr, JSON_UNESCAPED_UNICODE);
        $result = urldecode($result);
        $getAccessToken = getAccessToken(); // 获取access_token
        //var_dump($result);
        //var_dump($getAccessToken);
        //die();
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $getAccessToken['access_token'];
        $curl = httprequest($url, $result);
        $decode = json_decode($curl, true);
        if ($decode['errmsg'] == 'ok') {
            return json([
                'status' => '200',
                'url' => '/admin/customenu/index',
                'data' => '',
                'msg' => '发布成功'
            ]);
        } else {
            return json([
                'status' => '-200',
                'url' => '/admin/customenu/create',
                'data' => '',
                'msg' => $decode['errmsg']
            ]);
        }
    }
    private  function get_type_name($name){
        $type_name='';
        switch ($name){
            case 'view':
                $type_name = '链接';
                break;
            case 'media_id':
                $type_name = '图片';
                break;
            case 'miniprogram':
                $type_name = '小程序';
                break;
            case 'click':
                $type_name = '文字';
                break;
        }
        return $type_name;
    }
}