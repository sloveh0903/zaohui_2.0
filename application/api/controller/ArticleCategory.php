<?php
namespace app\api\controller;
use think\Controller;

/**
 * Class ArticleCategory 文章分类
 * @package app\api\controller
 */
class ArticleCategory extends Controller
{
    public function _initialize()
    {

    }

    public function _empty()
    {
        return $this->getIndex();
    }

    /**
     * 文章分类列表
     * @author王宣成
     * @return Json
     */
    public function getIndex()
    {
        $article_category_first  =
        array(0=>
            array(
                'id'=>0,'cate_name'=>'全部','small_photo'=>'','big_photo'=>'','orderby'=>'','create_time'=>'','update_time'=>'','closed'=>'','pid'=>''
            )
        )
        ;
        $article_category_list = lists('ArticleCategory',[],['orderby'=>'asc']);
        $article_category =array_merge_recursive($article_category_first,$article_category_list);
        $data['article_category'] = $article_category;
        successJson('操作完成',$data);
    }


}
