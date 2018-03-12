<?php
namespace app\api\controller;
use think\Controller;

/**
 * Class Article 文章
 * @package app\api\controller
 */
class Art extends Api
{
    public function _initialize()
    {
         parent::signature();         //签名验证
         parent::token(['postAdd']); //需要登录的数据进行token验证
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
        $uid = input('uid');
        $page = input('page',1);
        $size = input('size',10);
        if($cid)  $article_where['cid'] = $cid;
        /*if($uid)  $article_where['uid'] = $uid;*/
        $article_where['page'] = $page;
        $article_where['size'] = $size;
        $article_where['audit'] = 1;
        $article = $this->lists('Art',$article_where,['id'=>'desc']);
        if(!empty($article)){
            $uid_arr = [];
            foreach($article as $v){
                $uid_arr[] = $v['uid'];
            }
            $user_where['uid'] = ['in',$uid_arr];
            $user_field = 'uid,uname,realname,face,info';
            $user = $this->lists('User',$user_where,[],$user_field);
            foreach($article as &$v){
                $v['realname'] = '';
                $v['face'] = '';
                $v['create_time'] = timeFormat(strtotime( $v['create_time']));
                $v['photopath'] = json_decode($v['photopath']);
                foreach($user as $u){
                    if($v['uid'] == $u['uid']){
                        $v['realname'] = $u['realname'];
                        $v['face'] = $u['face'];
                    }
                }
            }
            unset($v);
        }
        $data['article'] = $article;
        successJson('操作完成',$data);
    }

    public function getArticles(){
        $page = input('page',1);
        $size = input('size',10);
        $article_where['page'] = $page;
        $article_where['size'] = $size;
        $article_category = $this->lists('ArticleCategory');
        $article_data = [];
        foreach ($article_category as $category) {
            $article_where['cid'] = $category['id'];
            $article_where['audit'] = 1;
            $article = $this->lists('Art',$article_where,['id'=>'desc']);
            if(!empty($article)){
                $uid_arr = [];
                foreach($article as $v){
                    $uid_arr[] = $v['uid'];
                }
                $user_where['uid'] = ['in',$uid_arr];
                $user_field = 'uid,realname,face,info';
                $user = $this->lists('User',$user_where,[],$user_field);
                foreach($article as &$v){
                    $v['realname'] = '';
                    $v['face'] = '';
                    $v['create_time'] = timeFormat(strtotime( $v['create_time']));
                    $v['photopath'] = json_decode($v['photopath']);
                    foreach($user as $u){
                        if($v['uid'] == $u['uid']){
                            $v['realname'] = $u['realname'];
                            $v['face'] = $u['face'];
                        }
                    }
                }
                unset($v);
            }
            $category['article'] = $article ? $article : [];
            $article_data[] = $category;
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
        $article = $this->find('Art',$article_where);
        $follow = $this->find('Follow',['tid'=>$article['uid'],'uid'=>$uid]);

        if(!$article) errorJson('文章不存在');
        $user_where['uid'] = $article['uid'];
        $user_field = 'uid,uname,realname,face,info';
        $user = $this->find('User',$user_where,$user_field);
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
        $content = input('content','');
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
        $article = $this->insert('Art',$data);
        if(!$article) json_encode('添加失败');
        successJson('操作完成');
    }

}
