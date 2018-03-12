<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\api\controller\Api;

/**
 * Class Index
 * @package app\api\controller
 */
class Article extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->Index();
    }

    /**
     *阅读首页
     */
    public function Index()
    {
        //$teacherList = $teacherList['data'];
        $config = find('Config');
        if(!$config['wxmincode']){
            $config['wxmincode'] = '/public/gzadmin/images/kong-miniprom.png';
        }
        $advs = Db::name('adv_item')->where(array('adv_id'=>'2','closed'=>'0'))->order('orderby','asc')->select();
        $size = 10;
        $tid = input('uid');
        $map['closed'] = 0;
        $map['audit'] = 1;
        $list = Db::name('article')->where($map)->order(array('hot'=>'desc','id'=>'desc'))->paginate($size,false);
        $page = $list->render();
        $data = [];
        foreach ($list as $k=>$v) {
            $ret_article = [];
            if($photo_path = json_decode($v['photopath'])){
                $ret_article['banner'] = $photo_path[0];
            }else{
                $ret_article['banner'] = $this->getImgs($v['content']);
            }
            $ret_article['id'] = $v['id'];
            $ret_article['title'] = $v['title'];
            $ret_article['desc'] = $v['desc'] ? mb_substr($v['desc'],0,50) : mb_substr(strip_tags($v['content']),0,50);
            $ret_article['views'] = $v['views'];
            $ret_article['create_time'] = date('Y/m/d',$v['create_time']);
            $ret_article['comments'] = $v['comments'];
            $data[] = $ret_article;
        }

        $category = Db::name('article_category')->where(array('closed'=>'0'))->order('orderby','asc')->select();
        $this->assign('categorys',$category);
        $this->assign('articles',$data);
        $this->assign('page',$page);
        $this->assign('advs',$advs);
        $this->assign('config',$config);
        $this->assign('tid',$tid);
        return view('index');
    }

    public function article(){
        if($cid = input('cid')){
            $size = 10;
            $map['closed'] = 0;
            $map['audit'] = 1;
            $param['cid'] = $map['cid'] = $cid;
            $cate = Db::name('article_category')->where('id',$cid)->field('id,cate_name,big_photo')->find();
            $list = Db::name('article')->where($map)->paginate($size,false,['query'=>$param]);
            $page = $list->render();
            $data = [];
            if(!empty($list)){
                $uid_arr = [];
                foreach($list as $v){
                    $uid_arr[] = $v['uid'];
                }
                if($uid_arr){
                    $user_where['uid'] = ['in',$uid_arr];
                    $user_where['closed'] = '0';
                    $user_field = 'uid,uname,realname,face,info';
                    $user = Db::name('user')->where($user_where)->field($user_field)->select();
                }
                foreach($list as $v){
                    $ret_article = [];
                    if($photo_path = json_decode($v['photopath'])){
                        $ret_article['banner'] = $photo_path[0];
                    }else{
                        $ret_article['banner'] = $this->getImgs($v['content']);
                    }
                    $ret_article['id'] = $v['id'];
                    $ret_article['title'] = $v['title'];
                    $ret_article['desc'] = $v['desc'] ? mb_substr($v['desc'],0,80) : mb_substr(strip_tags($v['content']),0,80);
                    $ret_article['views'] = $v['views'];
                    $ret_article['create_time'] = date('Y/m/d',$v['create_time']);
                    $ret_article['comments'] = $v['comments'];
                    $ret_article['realname'] = '';
                    $ret_article['face'] = '';
                    foreach($user as $u){
                        if($v['uid'] == $u['uid']){
                            $ret_article['realname'] = $u['realname'];
                            $ret_article['face'] = $u['face'];
                        }
                    }
                    $data[] = $ret_article;


                }
                unset($v);
            }
            $this->assign('articles',$data);
            $this->assign('cate',$cate);
            $this->assign('page',$page);
        }else{
            return alert('参数错误','',2,2);
        }
        return $this->fetch();

    }

    public function detail(){

        if($id = input('id')){
            $size = 10;
            Db::name('article')->where('id',$id)->setInc('views');
            $detail = Db::name('article')->where('id',$id)->find();
            $detail['create_time'] = date('Y/m/d',$detail['create_time']);
            //$user = Db::name('user')->where('uid',$detail['uid'])->field('uid,uname,realname,face,info')->find();
            $comments = Db::name('article_comment')->where(array('aid'=>$id,'closed'=>'0'))->order('id','asc')->paginate($size);
            $page = $comments->render();
            $data = [];

            foreach ($comments as $v) {
                $ret_comment = [];
                $ret_comment['id'] = $v['id'];
                $ret_comment['content'] = $v['content'];
                $ret_comment['create_time'] = date('Y/m/d',$v['create_time']);
                $ret_comment['uid'] = $v['uid'];
                $ret_comment['pid'] = $v['pid'];
                $ret_comment['likes'] = $v['likes'];
                if($uid = session('uid')){
                    if(Db::name('like')->where(array('uid'=>$uid,'typeid'=>'3','itemid'=>$v['id']))->find()){
                        $ret_comment['is_like'] = 1;
                    }else{
                        $ret_comment['is_like'] = 0;
                    }
                }else{
                    $ret_comment['is_like'] = 0;
                }
                $ret_comment['reviewer'] = "";
                $ret_comment['reviewer_face'] = "";
                if($v['uid']){
                    $reviewer = Db::name('user')->where('uid',$v['uid'])->field('face,nickname')->find();
                    $ret_comment['reviewer'] = $reviewer['nickname'];
                    $ret_comment['reviewer_face'] = $reviewer['face'];
                }
                $ret_comment['be_reviewer'] = "";
                if($v['puid']){
                    $ret_comment['be_reviewer'] = Db::name('user')->where('uid',$v['puid'])->value('nickname');
                }
                $data[] = $ret_comment;
            }
            /*p($data);
            die();*/
            $this->assign('detail',$detail);
            $this->assign('comments',$data);
            $this->assign('page',$page);
            //$this->assign('user',$user);

        }else{
            return alert('参数错误','',2,2);
        }
        return $this->fetch();
    }

    function getImgs($content){
        //返回文章中的第一张图片
        $pattern="/<img.*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
        $img = "";
        preg_match_all($pattern,$content,$match);
        if(isset($match[1])&&!empty($match[1])){
            $img = $match[1][0];
        }
        if(!$img){
            $img = '/public/pc/images/moren.png';
        }
        return $img;
    }


}
