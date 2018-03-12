<?php
namespace app\api\controller;
use think\Controller;
use think\File;

/**
 * Class 统计
 * @package app\api\controller
 */
class Statistics extends Controller
{
    public function _initialize()
    {

    }



    /**
     * 添加访问记录
     */
    public function postStaticadd(){
        if(!input('uid') && !session('uid')){
            errorJson('缺少用户uid');
        }
        $uid = session('uid');
        if(!$uid){
            $uid = input('uid');
        }
        $terminal = input('terminal',1);
        if($uid){
            $map = [];
            $map['uid'] = $uid;
            $map['closed'] = 0;
            if(db('user')->where($map)->find()){
                $map = [];
                $map['uid'] = $uid;
                $map['terminal'] = $terminal;
                if(db('access_statistics')->where($map)->whereTime('create_time', 'today')->find()){
                    db('access_statistics')->where($map)->whereTime('create_time', 'today')->setInc('number');
                    if(db('access_statistics')->where('uid',$uid)->whereTime('create_time', 'today')->find()){
                        $data['source'] = 1;
                    }
                }else{
                    $data = ['uid' => $uid,'terminal'=>$terminal,'create_time' => time()];
                    if(!db('access_statistics')->where('uid',$uid)->whereTime('create_time', 'today')->find()){
                        $data['source'] = 1;
                    }
                    db('access_statistics')->insert($data);
                }
            }else{
                errorJson('用户不存在');
            }
        }
        successJson('ok');
    }


}
