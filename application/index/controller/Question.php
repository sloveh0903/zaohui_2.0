<?php
namespace app\index\controller;
use think\Controller;
use app\api\controller\Api;
use think\Log;
use think\Db;

/**
 * Class Index
 * @package app\api\controller
 */
class Question extends Controller
{
    public $browser;

    public function _initialize()
    {
        if(is_mobile()){
            if(is_weixin()){
                $browser = 2;
                //微信sdk
                require_once EXTEND_PATH."org/wxsdk/jssdk.php";
                $config = find('Config');
                $jssdk = new \JSSDK($config['wx_public_appid'], $config['wx_public_secret']);
                $signPackage = $jssdk->GetSignPackage();
                $this->assign('signPackage',$signPackage);
                //用户信息
                $this->assign('config',$config);
                $this->assign('userinfo', wechatLogin());
            }else{
                $browser = 1;
            }
        }else{
            $browser = 0;
        }
        $this->browser = $browser;
        $this->assign('browser',$browser);
        $this->assign('uid',session('uid'));
    }

    /**
     * 首页
     */
    public function index(){
        $type = input('type',1);
        //问答分类
        $category = Db::name('ask_category')->where('closed',0)->select();
        //推荐问答
        $recommend_ask = Db::name('ask')
            ->alias('a')
            ->where('a.hot',1)
            ->where('a.title','<>','')
            ->where('a.closed',0)
            ->field('a.*,u.nickname,u.face')
            ->join('gz_user u','u.uid = a.uid')
            ->limit(5)
            ->select();
        if($type == 1) {
            //热门问答
            $ask = Db::name('ask')
                ->alias('a')
                ->order('comments','desc')
                ->where('a.title','<>','')
                ->where('a.closed',0)
                ->field('a.*,u.nickname,u.face,c.cate_name')
                ->join('gz_user u','u.uid = a.uid')
                ->join('gz_ask_category c','c.id = a.pid')
                ->paginate(10,true);
        }else{
            //最新问答
            $ask = Db::name('ask')
                ->alias('a')
                ->order('id','desc')
                ->where('a.closed',0)
                ->field('a.*,u.nickname,u.face,c.cate_name')
                ->join('gz_user u','u.uid = a.uid')
                ->join('gz_ask_category c','c.id = a.pid')
                ->paginate(10,true);
        }
        $page = $ask->render();
        $this->assign('page',$page);
        $this->assign('type',$type);
        $this->assign('ask',$ask);
        $this->assign('category',$category);
        $this->assign('recommend_ask',$recommend_ask);
        return view();
    }


    /**
     * 问题分类
     * @return \think\response\View
     */
    public function category()
    {
        $id = input('id');
        $category = Db::name('ask_category')->where('id',$id)->where('closed',0)->find();
        if(!$category){
            $this->errror('问题分类不存在');
        }
        //推荐问答
        $hot_ask = Db::name('ask')
            ->alias('a')
            ->where('a.hot',1)
            ->where('a.closed',0)
            ->field('a.*,u.nickname,u.face')
            ->join('gz_user u','u.uid = a.uid')
            ->limit(5)
            ->select();
        $ask = Db::name('ask')
            ->alias('a')
            ->where('a.title','<>','')
            ->where('a.pid',$id)
            ->where('a.closed',0)
            ->order('id','desc')
            ->field('a.*,u.nickname,u.face')
            ->join('gz_user u','u.uid = a.uid')
            ->paginate(10,true);
        $page = $ask->render();
        $this->assign('page',$page);
        $this->assign('ask',$ask);
        $this->assign('hot_ask',$hot_ask);
        $this->assign('category',$category);
        return view();
    }

    /**
     * 问答详情
     */
    public function detail()
    {
        $page = input('page',1);
        $size = 10;
        $pindex = ($page-1)*$size;
        $id = input('id');
        $ask = find('Ask',['id'=>$id]);
        if(!$ask){
            $this->error('问题不存在');
        }

        //问题分类
        $ask_category = Db::name('ask_category')->where('id',$ask['pid'])->where('closed',0)->find();
        if(!$ask_category){
            $this->error('问题分类不存在');
        }

        //相关问题
        $relevant_ask = Db::name('ask')
            ->alias('a')
            ->where('a.title','<>','')
            ->where('a.pid',$ask['pid'])
            ->where('a.closed',0)
            ->order('id','desc')
            ->field('a.*,u.nickname,u.face')
            ->join('gz_user u ','u.uid= a.uid')
            ->limit(3)
            ->select();

        //问题用户信息
        $ask['uid'];
        $user = find('User',['uid'=>$ask['uid']]);
        if(!$user){
            $this->error('用户不存在');
        }
        $ask['nickname'] = $user['nickname'];
        $ask['face'] = $user['face'];

        //回答
        $page_answer = Db::name('answer')
            ->alias('a')
            ->where('a.pid',0)
            ->where('a.aid',$id)
            ->where('a.root_id',0)
            ->where('a.closed',0)
            ->field('a.*,u.nickname,u.face')
            ->join('gz_user u ','u.uid= a.uid')
            ->paginate($size,true);
        $answer = Db::name('answer')
            ->alias('a')
            ->where('a.pid',0)
            ->where('a.aid',$id)
            ->where('a.root_id',0)
            ->where('a.closed',0)
            ->field('a.*,u.nickname,u.face')
            ->join('gz_user u ','u.uid= a.uid')
            ->limit($pindex,$size)
            ->select();

        //回答评论
        $answer_id = [];
        foreach($answer as $v){
            $answer_id[] = $v['id'];
        }
        $in_where['a.pid'] = ['in',$answer_id];
        $answer_comment = Db::name('answer')
            ->alias('a')
            ->where('a.aid',$id)
            ->where($in_where)
            ->where('a.closed',0)
            ->field('a.*,u.nickname,u.face,pu.nickname pnickname,pu.face pface')
            ->join('gz_user u ','u.uid = a.uid')
            ->join('gz_user pu ','pu.uid = a.puid')
            ->select();
        $panswer_id = [];
        foreach($answer_comment as $v){
            $panswer_id[] = $v['pid'];
        }
        $p_in_where['a.root_id'] = ['in',$panswer_id];
        $answer_comment2 = Db::name('answer')
            ->alias('a')
            ->where('a.aid',$id)
            ->where($p_in_where)
            ->where('a.closed',0)
            ->field('a.*,u.nickname,u.face,pu.nickname pnickname,pu.face pface')
            ->join('gz_user u ','u.uid = a.uid')
            ->join('gz_user pu ','pu.uid = a.puid')
            ->select();
        foreach($answer as &$v){
            $v['has_like'] = 0;
            $v['comments'] = [];
            if($v['comments'] > 0){
                foreach($answer_comment as $c){
                    if($v['id'] == $c['pid']){
                        $v['comments'][] = $c;
                    }
                }
                foreach($answer_comment2 as $c2){
                    if($v['id'] == $c2['root_id']){
                        $v['comments'][] = $c2;
                    }
                }
            }
        }
        unset($v);

        //点赞
        if(session('uid')){
            $like_in_where['itemid'] = ['in',$answer_id];
            $like = Db::name('like')
                ->where($like_in_where)
                ->where('closed',0)
                ->where('typeid',2)
                ->select();
            foreach($answer as &$v){
                foreach($like as $v2){
                    if($v['id'] == $v2['itemid']){
                        $v['has_like'] = 1;
                    }
                }
            }
        }

        //话题
        $topic = Db::name('search_term')
            ->limit(15)
            ->select();

        //头像
        if(session('uid')){
            $user = find('User',['uid'=>session('uid')]);
            $face = $user['face'];
        }else{
            $face = '/public/wenda/images/no_login_header@2x.png';
        }

        //增加浏览数
        setInc('Ask',['id'=>$id],'views');
        $page = $page_answer->render();
        $this->assign('id', $id);
        $this->assign('user',$user);
        $this->assign('ask',$ask);
        $this->assign('ask_category',$ask_category);
        $this->assign('answer',$answer);
        $this->assign('relevant_ask', $relevant_ask);
        $this->assign('topic', $topic);
        $this->assign('page', $page);
        $this->assign('face', $face);
        return view();
    }

    /**
     * 提问
     */
    public function publish(){
        if(!session('uid')){
            if($this->browser == 2){
                $this->error('请关注公众号');
            }
            if($this->browser == 0){
                $api_url = $_SERVER['SERVER_NAME'].'/api/config/index';
                $curl_data = json_decode(doCurlGetRequest($api_url),true);
                $config_data = $curl_data['data']['config'];
                $backurl = urlencode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
                if(isHTTPS()){
                    $redirect_uri = urlencode("https://".$_SERVER['HTTP_HOST']."/api/wechat/scanlogin?backurl=".$backurl);
                }else{
                    $redirect_uri = urlencode("http://".$_SERVER['HTTP_HOST']."/api/wechat/scanlogin?backurl=".$backurl);
                }
                $this->redirect('https://open.weixin.qq.com/connect/qrconnect?appid='.$config_data['wx_open_appid'].'&redirect_uri='.$redirect_uri.'&response_type=code&scope=SCOPE&state=STATE#wechat_redirect');
            }
        }
        $category = Db::name('ask_category')->where('closed',0)->select();
        $this->assign('category',$category);
        return view();
    }


}
