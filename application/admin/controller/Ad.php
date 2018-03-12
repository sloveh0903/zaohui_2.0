<?php

namespace app\admin\controller;
use app\admin\model\Adv;
use app\admin\model\AdvItem;
use think\Db;

class Ad extends Base
{

    public function _empty(){
        return $this->index();
    }

    //首页即为移动首页
    public function index(){
        //$adv_id = input('id');
        $adv_id = 3;
        $adv = new Adv();
        $adv_item = new AdvItem();
        $lists = $adv_item->where(array('adv_id'=>$adv_id,'closed'=>'0'))->order('orderby desc')->select();
        foreach ($lists as $k => $v) {
            $lists[$k]['edit_url'] = \think\Url::build('edit_adv','id='.$v['id']);
            switch ($v['menu']){
                case "course":
                    $menu = "课程";
                    break;
                case "article":
                    $menu = "文章";
                    break;
                case "question":
                    $menu = "问答";
                    break;
                default:
                    $menu = "";
                    break;
            }
            if($v['adv_type'] == '1'){
                $lists[$k]['info'] = "小程序:(".$menu.") id:".$v['item_id'];
            }else{
                $lists[$k]['info'] = "H5:".$v['link'];
            }
        }
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="广告列表";
            $msg['pages'] = 1;
            return json($msg);
        }
        $adv_id = $adv_id?$adv_id:3;
        $adv_count = 1;
        if($adv_id==3){
            $adv_count = $adv_item->where(['adv_id'=>3,'closed'=>0])->count();
        }
        $this->assign('adv_count',$adv_count);
        $this->assign('id',$adv_id);
        $advInfo = $adv->get($adv_id);
        $this->assign('advInfo',$advInfo);
        return $this->fetch();
    }
    //pc 首页
    public  function pc_index()
    {
        $advItem = new AdvItem();
        $adv_item =$advItem->where('adv_id',3)->where('closed',0)->field('id,photopath')->order('id desc')->find();
        $pc_url = isset($adv_item['photopath'])?$adv_item['photopath']:'/public/pc/images/indexBG@2x.jpg';//默认
        $pc_id = $adv_item['id'];
        if(request()->isPost()){
            $param = input('post.');
            $flag['status']=200;
            $flag['msg']='更新成功';
            if($param['photopath']!=$pc_url)
            {
                //删除旧图片
                $old_photopath = $pc_url;
                $need_delete_phpotopath =ROOT_PATH.$old_photopath;
                if(file_exists($need_delete_phpotopath)){
                    unlink($need_delete_phpotopath);
                }

                
                unset($param['file']);
                if($pc_id){
                    $where['id'] = $pc_id;
                    $flag = $advItem->UpdateData($param,$where);
                }
                else{
                    $param['adv_id'] =3;
                    $param['title'] ='pc首页';
                    $flag = $advItem->InsertData($param);
                }
                
            }
            return json(['status' => $flag['status'],'url'=>'/admin/ad/pc_index', 'data' => '', 'msg' => $flag['msg']]);
        }
        $this->assign('pc_id',$pc_id);
        $this->assign('pc_url',$pc_url);
        return $this->fetch();
    }


    public function add_adv(){
        $adv_id = input('param.id');
        if(request()->isAjax()){
            $param = input('post.');
            //pengpian  添加 将orderby 默认为 表中最后一条数据
            $adv_id = $param['adv_id'];
            $aditem = new AdvItem();
            $lists = $aditem->where('adv_id',$adv_id)->order('orderby desc')->find();
            $next_order_by = ($lists['orderby'])+1;
            $param['orderby'] = $next_order_by;
            //end
            $advItem = new AdvItem();
            $flag = $advItem->InsertData($param);
            return json(['status' => $flag['status'],'url'=>'/admin/ad/index/id/'.$param['adv_id'].".html", 'data' => '', 'msg' => $flag['msg']]);
        }
        $adv = new Adv();
        $advInfo = $adv->get($adv_id);
        $course = Db::name('course')->where(array('closed'=>'0','audit'=>'1'))->field('cid,title')->select();
        $this->assign('adv_id',$adv_id);
        $this->assign('advInfo',$advInfo);
        $this->assign('course',$course);
        return $this->fetch();
    }

    public function edit_adv(){
        $id = input('param.id');
        $advItem = new AdvItem();
        $data = [];
        if(request()->isPost()){
            $param = input('post.');
            $old_photopath =$advItem->where('id','=',$id)->value('photopath');
            //删除服务器旧图片
            if($old_photopath!=$param['photopath'] && !empty($param['photopath'])){
                $need_delete_phpotopath =ROOT_PATH.$old_photopath;
                if(file_exists($need_delete_phpotopath)) {
                    unlink($need_delete_phpotopath);
                }
            }
            
            $where['id'] = $param['id'];
            $flag = $advItem->UpdateData($param,$where);
            return json(['status' => $flag['status'],'url'=>'/admin/ad/index/id/'.$param['adv_id'].".html", 'data' => '', 'msg' => $flag['msg']]);
        }
        $advInfo = [];
        if($itemInfo = $advItem->get($id)){
            if($itemInfo['menu'] == 'course'){
                $item = Db::name('course')->field(array('cid','title'))->where(array('closed'=>'0','audit'=>'1'))->select();

                foreach ($item as $v) {
                    $ret_data = [];
                    $ret_data['id'] = $v['cid'];
                    $ret_data['title'] = $v['title'];
                    $data[] = $ret_data;
                }
            }
            $adv = new Adv();
            $advInfo = $adv->get($itemInfo['adv_id']);
        }
        $this->assign('itemInfo',$itemInfo);
        $this->assign('advInfo',$advInfo);
        $this->assign('data',$data);
        return $this->fetch();

    }

    public function batch_adv(){
        $id = input('param.checkbox');
        $advItem = new AdvItem();
        $where['id'] = ['in',$id];
        //删除服务器旧图片
        $old_photopath_arr =$advItem->where($where)->column('photopath');
        if($old_photopath_arr){
            foreach ($old_photopath_arr as $old_photopath){
                
                if($old_photopath){
                    $need_delete_phpotopath =ROOT_PATH.$old_photopath;
                    if(file_exists($need_delete_phpotopath)){
                        unlink($need_delete_phpotopath);
                    }

                }
            }
        }
        if($flag = $advItem->DeleteData($where)){
            return json(['status' => $flag['status'],  'url'=>'reload' , 'data' => '', 'msg' => '批量删除成功']);
        }else{
            return json(['status' => -1,  'url'=>'reload' , 'data' => '', 'msg' => '批量删除失败']);
        }
    }


    public function del_adv(){
        $id = input('param.id');
        $advItem = new AdvItem();
        $old_photopath =$advItem->where('id',$id)->value('photopath');
        //删除服务器旧图片
        if($old_photopath){
            $need_delete_phpotopath =ROOT_PATH.$old_photopath;
            if(file_exists($need_delete_phpotopath)) {
                unlink($need_delete_phpotopath);
            }
        }
        
        $where['id'] = $id;
        $flag = $advItem->DeleteData($where);
        return json(['status' => $flag['status'],  'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function set_orderby()
    {
        $id = input('param.id');
        $orderby = input('param.orderby');
        $advItem = new AdvItem();
        $where['id'] = $id;
        $param['orderby'] = $orderby;
        $flag = $advItem->SetParam($param,$where);
        return json(['status' => $flag['status'],  'url'=>'reload' , 'data' => '', 'msg' => $flag['msg']]);
    }

    public function index_position(){
        $key = $so['title'] = input('key');
        $map = [];
        $map['closed'] = 0;
        if($key&&$key!==""){
            $map['title'] = ['like',"%" . $key . "%"];
        }
        $Nowpage = input('get.p') ? input('get.p'):1;
        $adCate = new Adv();
        $limits = 10;// 获取总条数
        $start = $limits * ($Nowpage - 1);
        $count = $adCate->where($map)->count();//计算总页面
        $allpage = ceil($count / $limits);
        $lists = $adCate->where($map)->order('orderby asc,id desc')->limit($start,$limits)->select();
        foreach ($lists as $k => $v){
            $lists[$k]['type'] = ($v['type'] == "0") ? "图片广告(宽".$v['width']." 高".$v['height'].")" : "文字广告" ;
        }
        $this->assign('val', $key);
        if(request()->isAjax()){
            //$article->getlastsql();
            $msg['status'] =200;
            $msg['data']['list'] = $lists;
            $msg['data']['title']="广告分类";
            $msg['pages'] = $allpage;
            return json($msg);
        }
        return $this->fetch();
    }

    public function add_position(){
        if(request()->isPost()){
            $param = input('post.');
            $position = new Adv();
            $flag = $position->InsertData($param);
            return json(['status' => $flag['status'],  'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
        }

        return $this->fetch();
    }

    public function edit_position(){
        $position = new Adv();

        if(request()->isPost()){
            $param = input('post.');
            $where['id'] = $param['id'];
            $flag = $position->UpdateData($param,$where);
            return json(['status' => $flag['status'],  'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
        }

        $id = input('param.id');
        $this->assign('position',$position->get($id));
        return $this->fetch();

    }

    public function del_position(){
        $id = input('param.id');
        $position = new Adv();
        $where['id'] = $id;
        $flag = $position->DeleteData($where);
        return json(['status' => $flag['status'],  'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function set_position()
    {
        $id = input('param.id');
        $orderby = input('param.orderby');
        $position = new Adv();
        $where['id'] = $id;
        $param['orderby'] = $orderby;
        $flag = $position->SetParam($param,$where);
        return json(['status' => $flag['status'],  'url'=>'reload' , 'data' => '', 'msg' => $flag['msg']]);
    }

    public function batch_del(){
        $id = input('param.checkbox');
        $cate = new Adv();
        $advItem = new AdvItem();
        $where['id'] = ['in',$id];
        $item_where['adv_id'] = ['in',$id];
        if($cate->DeleteData($where) && $advItem->DeleteData($item_where)){
            return json(['status' => 200,  'url'=>'reload' , 'data' => '', 'msg' => '批量删除成功']);
        }else{
            return json(['status' => -1,  'url'=>'reload' , 'data' => '', 'msg' => '批量删除失败']);
        }
    }

    public function get_item(){
        $type = input('param.menu','course');
        $data = [];

        if($type == 'course'){
            $item = Db::name('course')->field(array('cid','title'))->where(array('closed'=>'0','audit'=>'1'))->select();

            foreach ($item as $v) {
                $ret_data = [];
                $ret_data['id'] = $v['cid'];
                $ret_data['title'] = $v['title'];
                $data[] = $ret_data;
            }
        }
        return json($data);

    }
    /**
     * 广告排序 
     * pengpian
     */
    public function moveUpDown(){
        $id = input('id');
        $updown = input('updown');
        $ad_item = new AdvItem();//adv_item
        $ad_this = $ad_item->where('id',$id)->find();
        $orderby = $ad_this['orderby'];

        $map['adv_id'] =$ad_this['adv_id'];
        $map['closed'] =0;
        if($updown =='up')
        {
            $map['orderby'] = ['>',$orderby];
            $order = 'orderby desc';
        }
        else 
        {
            $map['orderby'] = ['<',$orderby];
            $order = 'orderby asc';
        }

        $data_next = $ad_item->where($map)->order($order)->limit(1)->find();
 
        if($data_next)
        {
            $next_id = $data_next['id'];
            $next_order = $data_next['orderby'];
            $list = [
                            ['id'=>$id, 'orderby'=>$next_order],
                            ['id'=>$next_id, 'orderby'=>$orderby]
                    ];
            $ad_item->saveAll($list);
        }
        return json(['status' => 200,'url'=>'reload', 'data' => '', 'msg' => '排序成功']);
    }
}