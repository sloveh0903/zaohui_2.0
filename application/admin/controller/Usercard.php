<?php

namespace app\admin\controller;
use app\admin\model\UserCard as Ucard;
use think\Db;


class Usercard extends Base
{
    public function _empty(){
        return $this->index();
    }

    public function index(){
        $Ucard = new Ucard();
        $lists = $Ucard->select();
        $flag['mouth'] = ['id'=>'0','msg'=>'uncreate','price'=>'0'];
        $flag['season'] = ['id'=>'0','msg'=>'uncreate','price'=>'0'];
        $flag['year'] = ['id'=>'0','msg'=>'uncreate','price'=>'0'];
        $flag['life'] = ['id'=>'0','msg'=>'uncreate','price'=>'0'];
        $explain = $ret_list = '';
        if($lists){
            foreach ($lists as $item) {
                $explain = $item['explain'];
                switch ($item['type']){
                    case "mouth":
                        if($item['closed']){
                            $flag['mouth']['msg'] = 'closed';
                        }else{
                            $flag['mouth']['msg'] = 'create';
                        }
                        $flag['mouth']['price'] = $item['price'];
                        $flag['mouth']['id'] = $item['id'];
                        break;
                    case "season":
                        if($item['closed']){
                            $flag['season']['msg'] = 'closed';
                        }else{
                            $flag['season']['msg'] = 'create';
                        }
                        $flag['season']['price'] = $item['price'];
                        $flag['season']['id'] = $item['id'];
                        break;
                    case "year":
                        if($item['closed']){
                            $flag['year']['msg'] = 'closed';
                        }else{
                            $flag['year']['msg'] = 'create';
                        }
                        $flag['year']['price'] = $item['price'];
                        $flag['year']['id'] = $item['id'];
                        break;
                    case "life":
                        if($item['closed']){
                            $flag['life']['msg'] = 'closed';
                        }else{
                            $flag['life']['msg'] = 'create';
                        }
                        $flag['life']['price'] = $item['price'];
                        $flag['life']['id'] = $item['id'];
                        break;
                }
            }
        }

        $this->assign('flag',$flag);
        $this->assign('explain',$explain);
        return $this->fetch();
    }

    public function addCard(){
        if(request()->isPost()){
            $param = input('post.');
            $Ucard = new Ucard();
            if(!($Ucard->where('type',$param['type'])->find())){
                switch ($param['type']){
                    case "mouth":
                        $param['title'] = '月卡';
                        $param['mouth'] = '1';
                        break;
                    case "season":
                        $param['title'] = '季卡';
                        $param['mouth'] = '3';
                        break;
                    case "year":
                        $param['title'] = '年卡';
                        $param['mouth'] = '12';
                        break;
                    case "life":
                        $param['title'] = '终身卡';
                        $param['mouth'] = '100';
                        break;
                }
                $flag = $Ucard->InsertData($param);
                $this->log($param);
                return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
            }else{
                return json(['status' => '-200','url'=>'reload', 'data' => '', 'msg' => '已存在']);
            }


        }

        return $this->fetch();
    }

    public function editCard(){
        $Ucard = new Ucard();
        if(request()->isPost()){
            $param = input('post.');
            $where['id'] = $param['id'];
            $flag = $Ucard->UpdateData($param,$where);
            $this->log($param);
            return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
        }
        $id = input('param.id');
        $UcardInfo = $Ucard->get($id);
        $this->assign('ucardinfo',$UcardInfo);
        return $this->fetch();

    }

    public function setClosed(){
        $id = input('param.id');
        $Ucard = new Ucard();
        $where['id'] = $id;
        $flag = $Ucard->DeleteData($where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function setOpen(){
        $id = input('param.id');
        $Ucard = new Ucard();
        $where['id'] = $id;
        $param['closed'] = '0';
        $flag = $Ucard->SetParam($param,$where);
        return json(['status' => $flag['status'],'url'=>'reload', 'data' => '', 'msg' => $flag['msg']]);
    }

    public function addExplain(){
        if(request()->isPost()) {
            $param = input('post.');
            $Ucard = new Ucard();
            $where['id'] = ['>','0'];
            $flag = $Ucard->UpdateData($param, $where);
            return json(['status' => $flag['status'], 'url' => 'reload', 'data' => '', 'msg' => $flag['msg']]);
        }
        return $this->fetch();
    }
    private function  log($list){
        $config_id = 7;
        if($list){
            $adminlog_detail = new AdminLog();
            $adminlog_detail->log($config_id, $list);
        }
    }
}