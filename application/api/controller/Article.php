<?php
namespace app\api\controller;
use think\Controller;

/**
 * Class Article 文章
 * @package app\api\controller
 */
class Article extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->getIndex();
    }

    /**
     * 文章列表
     * @author王宣成
     * @return Json
     */
    public function getIndex()
    {
        $cid = input('cid');
        $uid = input('puid');
        $page = input('page',1);
        $size = input('size',10);
        if($cid)  $article_where['cid'] = $cid;
        if($uid)  $article_where['uid'] = $uid;
        $article_where['page'] = $page;
        $article_where['size'] = $size;
        $article_where['audit'] = 1;
        $articleList = [];
        $article = lists('Article',$article_where,['id'=>'desc']);
        if(!empty($article)){
            $uid_arr = [];
            foreach($article as $v){
                $uid_arr[] = $v['uid'];
            }
            $user_where['uid'] = ['in',$uid_arr];
            $user_field = 'uid,uname,realname,face,info';
            $user = lists('User',$user_where,[],$user_field);
            $ret_user = [];
            foreach ($user as $list) {
                $ret_user[$list['uid']] = $list;
            }
            foreach($article as $v){
                $ret_article = [];
                $ret_article['id'] = $v['id'];
                $ret_article['cid'] = $v['cid'];
                $ret_article['title'] = $v['title'];
                $ret_article['desc'] = $v['desc'];
                $ret_article['uid'] = $v['uid'];
                $ret_article['photopath'] = json_decode($v['photopath']);
                $ret_article['views'] = $v['views'];
                $ret_article['comments'] = $v['comments'];
                $ret_article['create_time'] = timeFormat(strtotime( $v['create_time']));
                $ret_article['hot'] = $v['hot'];
                $ret_article['seo_description'] = $v['seo_description'];
                $ret_article['seo_keywords'] = $v['seo_keywords'];
                $ret_article['realname'] = "";
                $ret_article['face'] = "";
                $ret_article['banner'] = $this->getImgs($v['content']);
                if(array_key_exists($v['uid'],$ret_user)){
                    $ret_article['realname'] = $ret_user[$v['uid']]['realname'];
                    $ret_article['face'] = $ret_user[$v['uid']]['face'];
                }
                $articleList[] = $ret_article;
            }
        }
        $data['article'] = $articleList;
        successJson('操作完成',$data);
    }


    public function getmulitindex(){
        $cid = input('cid');
        $uid = input('puid');
        $page = input('page',1);
        $size = input('size',10);
        if($cid)  $article_where['cid'] = $cid;
        if($uid)  $article_where['uid'] = $uid;
        $article_where['page'] = $page;
        $article_where['size'] = $size;
        $article_where['audit'] = 1;
        $articleList = [];
        $article = lists('Article',$article_where,['id'=>'desc']);
        unset($article_where['page']);
        unset($article_where['size']);
        $count = counts('Article',$article_where);//计算总页面
        $allpage = ceil($count / $size);
        if(!empty($article)){
            $uid_arr = [];
            foreach($article as $v){
                $uid_arr[] = $v['uid'];
            }
            $user_where['uid'] = ['in',$uid_arr];
            $user_field = 'uid,uname,realname,face,info';
            $user = lists('User',$user_where,[],$user_field);
            $ret_user = [];
            foreach ($user as $list) {
                $ret_user[$list['uid']] = $list;
            }
            foreach($article as $v){
                $ret_article = [];
                $ret_article['id'] = $v['id'];
                $ret_article['cid'] = $v['cid'];
                $ret_article['title'] = $v['title'];
                $ret_article['desc'] = $v['desc'];
                $ret_article['uid'] = $v['uid'];
                $ret_article['photopath'] = json_decode($v['photopath']);
                $ret_article['views'] = $v['views'];
                $ret_article['comments'] = $v['comments'];
                $ret_article['create_time'] = timeFormat(strtotime( $v['create_time']));
                $ret_article['hot'] = $v['hot'];
                $ret_article['seo_description'] = $v['seo_description'];
                $ret_article['seo_keywords'] = $v['seo_keywords'];
                $ret_article['realname'] = "";
                $ret_article['face'] = "";
                $ret_article['banner'] = ($banner=$this->getImgs($v['content'])) ? $banner : '/public/pc/images/moren.png';
                if(array_key_exists($v['uid'],$ret_user)){
                    $ret_article['realname'] = $ret_user[$v['uid']]['realname'];
                    $ret_article['face'] = $ret_user[$v['uid']]['face'];
                }
                $articleList[] = $ret_article;
            }
        }
        $data['article'] = $articleList;
        $data['currentPage'] = $page;
        $data['total'] = $allpage;
        exit(json_encode($data));
    }


    private  function getImgs($content){
        //返回文章中的第一张图片
        $pattern="/<img.*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
        $img = "";
        preg_match_all($pattern,$content,$match);
        if(isset($match[1])&&!empty($match[1])){
            $img = $match[1][0];
        }
        return $img;
    }
    public function getArticles(){
        $page = input('page',1);
        $size = input('size',10);
        $article_where['page'] = $page;
        $article_where['size'] = $size;
        $article_category_first  =
        array(0=>
            array(
                'id'=>0,'cate_name'=>'全部','small_photo'=>'','big_photo'=>'','orderby'=>'','create_time'=>'','update_time'=>'','closed'=>'','pid'=>''
            )
        )
        ;
        $article_category_pop = lists('ArticleCategory',[],['orderby'=>'asc']);
        $article_category =array_merge_recursive($article_category_first,$article_category_pop);
        $article_data = [];
        foreach ($article_category as $category) {
            $articleList = [];
            if($category['id'] !=0){
                $article_where['cid'] = $category['id'];
            }
            $article_where['audit'] = 1;
            $article = lists('Article',$article_where,['id'=>'desc']);
            if(!empty($article)){
                $uid_arr = [];
                foreach($article as $v){
                    $uid_arr[] = $v['uid'];
                }
                $user_where['uid'] = ['in',$uid_arr];
                $user_field = 'uid,realname,face,info';
                $user = lists('User',$user_where,[],$user_field);
                $ret_user = [];
                foreach ($user as $list) {
                    $ret_user[$list['uid']] = $list;
                }
                foreach($article as $v){
                    $ret_article = [];
                    $ret_article['id'] = $v['id'];
                    $ret_article['cid'] = $v['cid'];
                    $ret_article['cate_name'] = $category['cate_name'];
                    $ret_article['title'] = $v['title'];
                    $ret_article['desc'] = $v['desc'];
                    $ret_article['uid'] = $v['uid'];
                    $ret_article['photopath'] = json_decode($v['photopath']);
                    $ret_article['views'] = $v['views'];
                    $ret_article['comments'] = $v['comments'];
                    $ret_article['create_time'] = timeFormat(strtotime( $v['create_time']));
                    $ret_article['hot'] = $v['hot'];
                    $ret_article['seo_description'] = $v['seo_description'];
                    $ret_article['seo_keywords'] = $v['seo_keywords'];
                    $ret_article['realname'] = "";
                    $ret_article['face'] = "";
                    if(array_key_exists($v['uid'],$ret_user)){
                        $ret_article['realname'] = $ret_user[$v['uid']]['realname'];
                        $ret_article['face'] = $ret_user[$v['uid']]['face'];
                    }
                    $articleList[] = $ret_article;
                }
            }
            $article_data[] = $articleList ? $articleList : "";
        }
        $data['article'] = $article_data;
        successJson('操作完成',$data);
    }

    /**
     * 文章详情
     * @author王宣成
     * @return Json
     */
    public function getDetail(){
        $id = input('id');
        $uid = input('uid');
        $article_where['id'] = $id;
        if(!$id) errorJson('文章不存在');
        $article = find('Article',$article_where);
        $follow = find('Follow',['tid'=>$article['uid'],'uid'=>$uid]);
        if(!$article) errorJson('文章不存在');
        $user_where['uid'] = $article['uid'];
        $user_field = 'uid,uname,realname,face,info';
        $user = find('User',$user_where,$user_field);
        $article['uname'] = $user['uname'];
        $article['realname'] = $user['realname'];
        $article['face'] = $user['face'];
        $article['info'] = $user['info'];
        $article['create_time'] = timeFormat(strtotime( $article['create_time']));
        $article['update_time'] = timeFormat(strtotime( $article['update_time']));
        if($follow){
            $article['is_follow'] = 1;
        }else{
            $article['is_follow'] = 0;; //未订阅
        }
        //增加浏览记录
        setInc('Article',['id'=>$id],'views');
        $data['article'] = $article;
        successJson('操作完成',$data);
    }

    /**
     * 添加文章
     * @author王宣成
     * @return Json
     */
    public function postAdd(){
        $cid = input('cid');
        $uid = input('uid');
        $title = input('title','');
        $content = input('content','','htmlspecialchars');
        // 获取表单上传文件
        $files = request()->file('image',[]);
        if(empty($files)) $files = [];
        if(!is_array($files)) errorJson('image参数必须是数组');
        $img_arr = [];
        foreach($files as $file){
            // 移动到框架应用根目录/public/uploads/ 目录下
            $info = $file->validate(['size'=>256780,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads/article/');
            if($info){
                // 成功上传后 获取上传信息
                $img_arr[] = '/public/uploads/image/article/'.$info->getSaveName();
            }else{
                // 上传失败获取错误信息
                errorJson($file->getError());
            }
        }
        $photopath = json_encode($img_arr);
        //验证器
        $validate = validate('Article');
        $data = [
            'cid' => $cid,
            'uid' => $uid,
            'title' => $title,
            'content' => $content,
            'photopath' => $photopath,
//            'create_time' => time(),
//            'update_time' => time(),
        ];
        if(!$validate->check($data)){
            errorJson(($validate->getError()));
        }
        $article = insert('Article',$data);
        if(!$article) json_encode('添加失败');
        successJson('操作完成');
    }

    public function postComment(){
        $uid = input('uid');
        $aid = input('aid',0);
        $pid = input('pid',0);
        $puid = input('puid',0);
        if(!$aid){
            errorJson('评论失败');
        }
        if($uid == $puid){
            $pid = $puid = 0;
        }
        $content = input('content','','htmlspecialchars');
        $validate = validate('ArticleComment');
        $data = [
            'uid'=>$uid,
            'aid'=>$aid,
            'pid'=>$pid,
            'puid'=>$puid,
            'content'=>$content,
            'ip' =>ip2long($_SERVER["REMOTE_ADDR"]),
        ];
        if(!$validate->check($data)){
            errorJson(($validate->getError()));
        }
        $comment = insert('ArticleComment',$data);
        if(!$comment){
            errorJson('评论失败');
        }else{
            setInc('Article',['id'=>$aid],'comments');
            if($pid == 0){
                $target = $aid;
                $article = find('Article',['id'=>$aid]);
                $read_uid = $article['uid'];
            }else{
                $target = $pid;
                $read_uid = $puid;
            }
            $param = array ("type" => 1,"sender" => $uid,"content"=>$content,"target"=>$target, 'targetType' => 'article', 'action' => 'comment');
            $nid = sendnotify($param);
            $param = [
                'uid' => $read_uid,
                'nid' =>$nid,
                'isread'=> 0,
            ];
            sendread($param);
            successJson('操作完成');
        }
    }

}
