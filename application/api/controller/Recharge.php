<?php
namespace app\api\controller;
use think\Controller;
use think\Db;
use think\config;
use think\Log;

/**
 * Class Rebate SAAS支付回调
 * @package app\api\controller
 */
class Recharge extends Controller
{
    public function _initialize()
    {

    }

    /**
     * SAAS充值回调
     */
    public function postNotify(){
        $param = input();
        $sign = $param['sign'];
        if(!$sign){
            errorJson('参数错误');
        }
        if(!$param['sn']){
            errorJson('缺少参数');
        }
        if(!$param['money']){
            errorJson('缺少金额');
        }
        if(!$param['nonce_str']){
            errorJson('缺少参数');
        }
        if(!$param['pay_type']){
            errorJson('缺少参数');
        }
        unset($param['sign']);
        $stringA = '';
        $data['sn'] = $param['sn'];
        $data['money'] = $param['money'];
        $data['nonce_str'] = $param['nonce_str'];
        $data['pay_type'] = $param['pay_type'];
        ksort($data);
        foreach ($data as $key => $val) {
            $stringA.= $key.'='.$val.'&';
        }
        $appkey = Config::get('pay_secret');
        $stringSignTemp = $stringA.'key='.$appkey;
        $sign2 = strtoupper(md5($stringSignTemp));
        if($sign != $sign2){
            errorJson('签名错误');
        }else{
            $record = Db::name('recharge_record')->where(array('sn'=>$param['sn'],'pay_status'=>'0'))->find();
            if(!isset($record) or $record['price'] != $param['money'] or $record['pay_type'] != $param['pay_type']){
                errorJson('价格不正确或渠道错误');
            }else{
                Db::name('recharge_record')->where(array('sn'=>$param['sn']))->update(array('pay_status'=>'1','pay_price'=>$param['money']));
                $account = Db::name('qiniu_account')->find();
                if($account){
                    Db::name('qiniu_account')->where('id',$account['id'])->setInc('money',$param['money']);
                    if($account['is_disabled'] == 1 && ($account['money']+$param['money']) > 0){
                        $data['uid'] = $account['uid'];
                        $data['remark'] = '充值';
                        $data['nonce_str'] = time();
                        ksort($data);
                        $stringA = '';
                        foreach ($data as $key => $val) {
                            $stringA.= $key.'='.$val.'&';
                        }
                        $appkey = Config::get('pay_secret');
                        $stringSignTemp = $stringA.'key='.$appkey;
                        $token2 = strtoupper(md5($stringSignTemp));
                        $data['sign'] = $token2;

                        $result = json_decode(httprequest('http://saas.grazy.cn/index/index/enableChild.html',$data),true);
                        if($result['code'] == 200){
                            Db::name('qiniu_account')->where('id',$account['id'])->update(array('is_disabled'=>'0'));
                        }
                    }
                }
                successJson('提交成功');
            }

        }
    }

    public function getIndex(){

        $param = input();
        if($param['task'] != "timedTask"){
            Log::info('参数错误');
            return false;
        }

        $qiniuList = Db::name('qiniu_account')->where(array('closed'=>0))->field('id,uid,domain,key,secret,bucket,money')->limit(1)->find();

        $qiniu = new \qn\Qiniu($qiniuList['key'],$qiniuList['secret'],$qiniuList['domain']);
        $edate = date("Y-m-d");
        $sdate = date("Y-m-d",strtotime("-1 day"));
        $spaceSdate = str_replace('-','',$sdate).'000000';
        $spaceEdate = str_replace('-','',$edate).'000000';

        $data = $arr = $fee = $qiniu_account = [];
        $todayMoney = $spaceFee = $fluxFee = $convertFee = 0;

        if($qiniuList){
            $space = $qiniu->getSpace($spaceSdate,$spaceEdate,$qiniuList['key'],$qiniuList['secret'],$qiniuList['bucket']);
            $flux = $qiniu->bandwidth($sdate,$sdate,$qiniuList['domain'],$qiniuList['key'],$qiniuList['secret']);
            if(isset($space['datas'][0]) && $space['datas'][0] > 79872){
                //开通七牛云会上传crossdomain.xml，大小为78K
                if(!(Db::name('qiniu_statistic')->where(array('create_time'=>strtotime($sdate),'type'=>'space'))->find())){
                    if(isset($space['datas'][0])){
                        //今天产生的存储，计算到总存储里面
                        Db::name('qiniu_account')->where('id',$qiniuList['id'])->update(array('space'=>$space['datas'][0]));
                        $spaceTotal = $space['datas'][0];
                        $spaceFee = qiniuCost($spaceTotal,'space');
                        //当天商户存储数据
                        $statistic = [];
                        $statistic['total'] = $spaceTotal;
                        $statistic['sdate'] = $sdate;
                        $statistic['edate'] = $edate;
                        $statistic['money'] = $spaceFee;
                        $statistic['type'] = 'space';
                        $statistic['create_time'] = strtotime($sdate);
                        $statistic['update_time'] = strtotime($sdate);
                        $arr[] = $statistic;

                    }
                }
                if(!(Db::name('qiniu_statistic')->where(array('create_time'=>strtotime($sdate),'type'=>'flux'))->find())){
                    $china = $oversea = 0;
                    if(isset($flux['data'][$qiniuList['domain']]['china'][0])){
                        $china = $flux['data'][$qiniuList['domain']]['china'][0];
                    }
                    if(isset($flux['data'][$qiniuList['domain']]['oversea'][0])){
                        $oversea = $flux['data'][$qiniuList['domain']]['oversea'][0];
                    }
                    $fluxTotal = $china + $oversea;
                    if($fluxTotal > 0){
                        $fluxFee = qiniuCost($china,'china') + qiniuCost($oversea,'oversea');
                        Db::name('qiniu_account')->where('id',$qiniuList['id'])->setInc('flux',$fluxTotal);
                        $statistic = [];
                        $statistic['total'] = $fluxTotal;
                        $statistic['sdate'] = $sdate;
                        $statistic['edate'] = $edate;
                        $statistic['money'] = $fluxFee;
                        $statistic['type'] = 'flux';
                        $statistic['create_time'] = strtotime($sdate);
                        $statistic['update_time'] = strtotime($sdate);
                        $arr[] = $statistic;
                    }
                }
                if(!(Db::name('qiniu_statistic')->where(array('create_time'=>strtotime($sdate),'type'=>'convert'))->find())){
                    $convertFee = Db::name('convert')->where('create_time',strtotime($sdate))->sum('money');
                    if($convertFee > 0){
                        $statistic = [];
                        $statistic['total'] = '0';
                        $statistic['sdate'] = $sdate;
                        $statistic['edate'] = $edate;
                        $statistic['money'] = $convertFee;
                        $statistic['type'] = 'convert';
                        $statistic['create_time'] = strtotime($sdate);
                        $statistic['update_time'] = strtotime($sdate);
                        $arr[] = $statistic;
                    }
                }
            }
            $todayMoney = $spaceFee + $fluxFee + $convertFee;
        }
        if($arr){
            //存储流量记录写入
            Db::name('qiniu_statistic')->insertAll($arr);
        }
        if($todayMoney > 0){
            //产生费用及扣除相对应的费用

            Db::name('qiniu_account')->where('id',$qiniuList['id'])->setDec('money',$todayMoney);
            if(($qiniuList['money'] - $todayMoney) <= 0){
                //余额不足是禁用账号
                $data['uid'] = $qiniuList['uid'];
                $data['remark'] = '余额不足';
                $data['nonce_str'] = time();
                ksort($data);
                $stringA = '';
                foreach ($data as $key => $val) {
                    $stringA.= $key.'='.$val.'&';
                }
                $appkey = Config::get('pay_secret');
                $stringSignTemp = $stringA.'key='.$appkey;
                $token2 = strtoupper(md5($stringSignTemp));
                $data['sign'] = $token2;

                $result = json_decode(httprequest('http://saas.grazy.cn/index/index/disableChild.html',$data),true);
                if($result['code'] == 200){
                    Db::name('qiniu_account')->where('id',$qiniuList['id'])->update(array('is_disabled'=>'1'));
                }

                //$qiniu->disableChild($qiniuList['uid'],'余额不足');
            }
        }

    }


    public function postConvert(){
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/api/recharge/convert.html';
        $callbackBody = file_get_contents('php://input');
        $body = json_decode($callbackBody,true);
        $qiniuList = Db::name('qiniu_account')->where(array('closed'=>0))->field('id,uid,domain,key,secret,bucket,money')->limit(1)->find();
        $qiniu = new \qn\Qiniu($qiniuList['key'],$qiniuList['secret'],$qiniuList['bucket']);
        if($body['code'] == 0){
            //删除原视频，并转码m3u8
            //$qiniu->deleteVideo();
            if(isset($body['items'][0]['key']) && isset($body['items'][0]['returnOld']) && $body['items'][0]['returnOld'] == 0){
                $qiniu->deleteVideo($body['inputBucket'],$body['inputKey']);
                if($result = $qiniu->qiniuConvert($body['items'][0]['key'])){
                    $video_info = json_decode(doCurlGetRequest('http://'.$qiniuList['domain'].'/'.$body['items'][0]['key'].'?avinfo'),true);
                    if(isset($video_info['format'])){
                        //时长按秒计算
                        $duration = ceil($video_info['format']['duration']);
                        $lenght = $duration;
                        if($video_info['streams'][0]['codec_type'] == 'video'){
                            $width = $video_info['streams'][0]['width'];
                            $height = $video_info['streams'][0]['height'];
                        }else{
                            $width = $video_info['streams'][1]['width'];
                            $height = $video_info['streams'][1]['height'];
                        }
                        $ct = convertCost($lenght,($width>$lenght)?$width:$height);
                    }
                    $data['transcoding_path'] = 'http://'.$qiniuList['domain'].'/'.$result['new_key'];
                    if(isset($ct)){
                        $convert['money'] = $ct['money'];
                        $convert['videopath'] = $data['transcoding_path'];
                        $convert['resolution'] = $ct['resolution'];
                        $convert['lenght'] = $lenght;
                        $convert['create_time'] = strtotime(date("Y-m-d"));
                        $convert['update_time'] = strtotime(date("Y-m-d"));
                        Db::name('convert')->insert($convert);
                    }
                    Db::name('Video')->where(array('video_id'=>$body['id']))->update($data);
                    
                }
            }

            echo 'success';
        }
    }



}
