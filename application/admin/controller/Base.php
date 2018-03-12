<?php

namespace app\admin\controller;
use app\admin\model\AuthRule;
use think\Controller;

abstract class Base extends Controller
{
    public function _initialize()
    {
        if(!session('admin_uid')){
            $url = url('login/index');
            exit('<script>top.location.href="'.$url.'"</script>');
           // $this->redirect(url('login/index'));
        }

        $auth = new \com\Auth();

        $module     = strtolower(request()->module());
        $controller = strtolower(request()->controller());
        $action     = strtolower(request()->action());
        $url        = $module."/".$controller."/".$action;


        //跳过检测以及主页权限
        if(session('admin_uid')!=1){
            if($action != "checkrule"){

                if(!in_array($url, ['admin/index/index','admin/login/loginout','admin/index/center','admin/index/indexpage','admin/index/norule','admin/ajax/getMenuList'])){
                    if(!$auth->check($url,session('admin_uid'))){
                        return json(array('code'=>'-100','error_info'=>$this->error('抱歉，您没有操作权限')));
                    }
                }
            }
        }

        /*$menu = new AuthRule();
        $menu_list=$menu->getMenu(session('rule'));

        $this->assign([
            'username' => session('admin_username'),
            'menu' => $menu_list,
            'rolename' => session('rolename')
        ]);*/

    }

    public function checkRule(){
        if(!session('admin_uid')){
            $this->redirect(url('login/index'));
        }

        $auth = new \com\Auth();
        $getAction = input('param.action');
        $getContent = input('param.content');
        $getAction = str_replace('.html','',$getAction);

        $urlArr = explode('/',$getAction);
        $url        = $urlArr[1]."/".$urlArr[2]."/".$urlArr[3];
        p($url);
        //跳过检测以及主页权限
        if(session('admin_uid')!=1){
            if(!in_array($url, ['admin/index/index','admin/login/loginout','admin/index/center','admin/index/indexpage','admin/index/norule','admin/ajax/getMenuList'])){

                if(!$auth->check($url,session('admin_uid'))){
                    return json(array('code'=>'-100','error_info'=>$this->error('抱歉，您没有操作权限')));
                }
            }
        }

    }


}