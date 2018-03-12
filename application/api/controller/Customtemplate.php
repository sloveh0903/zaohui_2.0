<?php
namespace app\api\controller;
use think\Controller;
use phpDocumentor\Reflection\Types\Null_;
use think\Console;

/**
 * 自定义模板
 * @package app\api\controller
 */
class Customtemplate extends Controller
{
    public function _initialize()
    {

    }
    /*获取自定义模板所有数据*/
   public function getall(){
       $course_study = get_course_studynum();//获取课程id  所学人数
       $lists = db('Custom_template')->where(['closed'=>0])->order('orderby asc')->select();
       foreach ($lists as $value){
           $key =$value['orderby'];
           $type=$value['type'];
           $return_arr[$key]['id'] =$value['id'];
           $return_arr[$key]['orderby'] = $key;
           $return_arr[$key]['type'] = $type;
           if(in_array($type, array(1,3,6))){
               $content= json_decode($value['content'],true);
           }else{
               $content= $value['content'];
           }
           
           if($type==3){
               $return_arr[$key]['sort'] = $content['sort'];
               $return_arr[$key]['title'] = $content['title'];
               $return_arr[$key]['show_more'] = $content['show_more'];
               $course_id_arrstr =$content['course_id'];
               if($content['sort']==3){
                   $order_str = 'orderby desc';
               }else{
                   $order_str = 'cid desc';
               }
               $course_id = array();
               $course_list = db('Course')->where(['cid'=>['in',$course_id_arrstr],'closed'=>0,'audit'=>1])->field('cid,title,face,banner,desc,price,virtual_amount')->order($order_str)->select();
               foreach ($course_list as $sub_key=>$sub_val){
                   $cid = $sub_val['cid'];
                   $course_id[] = $cid;
                   $study_count = 0;
                   if(isset($course_study[$cid])){
                       $study_count = $course_study[$cid];
                   }
                  if ($sub_val['virtual_amount'] == 0) {
                        $course_list[$sub_key]['study_count'] = $study_count;
                    }else{
                        $course_list[$sub_key]['study_count']  = $sub_val['virtual_amount'];
                  }
               }
               //最热课程  排序冒泡
               $len = count($course_list);
               if(isset($content['sort'])&&$content['sort']==2){
                   for($k=1;$k<$len;$k++)
                   {
                       for($j=0;$j<$len-$k;$j++){
                           if($course_list[$j]['study_count']<$course_list[$j+1]['study_count']){
                               $temp =$course_list[$j+1];
                               $course_list[$j+1] =$course_list[$j] ;
                               $course_list[$j] = $temp;
                           }
                       }
                   }
               }
               $content = array();
               $content['course_id']=$course_id;
               $content['course_list'] = $course_list;
           }
           if($type==6){//课程
               $return_arr[$key]['title'] = $content['title'];
               $return_arr[$key]['show_more'] = $content['show_more'];
               $return_arr[$key]['package_id'] =$content['package_id'];
               $return_package_id = array();
               $package_id = $content['package_id'];
               $package_id_arr = array();
               $packageList = array();
               if($package_id){
                   $map['id'] = ['in',$package_id];
                   $map['closed'] = 0;
                   $map['audit'] = 1;
                   $lists = db('Package')->where($map)->order('orderby desc')->select();
                   if($lists){
                       foreach ($lists as $k=>$v) {
                           $ret_package = [];
                           $ret_package['banner'] = '';
                           $ret_package['id'] = $v['id'];
                           $ret_package['title'] = $v['title'];
                           $ret_package['price'] = $v['price'];
                           $ret_package['create_time'] = $v['create_time'];
                           $ret_package['audit'] = $v['audit'];
                           $course_id = json_decode($v['course_id']);
                           $ret_banner = '';
                           if($v['banner']){
                               $ret_banner = $v['banner'];
                           }
                           $banner_arr = get_package_banner($ret_banner,$course_id);
                           $ret_package['banner'] = $banner_arr['banner'];
                           $ret_package['banner_color'] = $banner_arr['banner_color'];
                           $return_package_id[] = $v['id'];
                           $packageList[] = $ret_package;
                       }
                   }
                   $content['package_id'] = $return_package_id;
                   $return_arr[$key]['package_list']=$packageList;
               }
               
           }
           //var_dump($package_id_arr);
           //
           $return_arr[$key]['content'] = $content;
       }
       $return_arr = array_values($return_arr);
       successJson('操作完成',$return_arr);
   }
   public  function  getcourse(){
       $pid = input('pid',0);
       $title = input('title','');
       $page = input('page',1);
       $size = input('size',7);
       if($pid){
           $map['pid'] = $pid;
       }
       if($title){
           $map['title'] = ['like',"%" . $title . "%"];
       }
       $map['closed']=0;
       $map['audit']=1;
       $count = db('Course')->where($map)->count();//计算总页面
       $allpage = ceil($count / $size);
       if($page ==1){$firset_limit = 0;}else{$firset_limit =($page-1)*$size;}
       
       $course_list = db('Course')->where($map)->order('cid desc')->limit($firset_limit,$size)->select();
       $data['courselist'] = $course_list;
       $data['currentPage'] = $page;
       $data['total'] = $allpage;
       header("Content-type: application/json");
       exit(json_encode($data));
   }
   public  function get_one_course(){
       $course_study = get_course_studynum();//获取课程id  所学人数
       $cid_arr = input('cid_arr','');
       $sort = input('sort',1);
       $list = array();
       if($cid_arr){
           $map['closed']=0;
           $map['audit']=1;
           $map['cid']=['in',json_decode($cid_arr,true)];
           if($sort==3){
               $order_str = 'orderby desc';
           }else{
               $order_str = 'cid desc';
           }
           $data = db('Course')->where($map)->order('cid desc')->select();
       }
       if($data){
           foreach ($data as $key=>$val){
               $cid = $val['cid'];
               $list[$key]['cid'] =$val['cid'];
               $list[$key]['face'] =$val['face'];
               $list[$key]['desc'] =$val['desc'];
               $list[$key]['title'] =$val['title'];
               $list[$key]['price'] =$val['price'];
               $study_count = 0;
               if ($val['virtual_amount'] == 0) {
                  if(isset($course_study[$cid])){
                    $list[$key]['study_count']= $course_study[$cid];  
                  }else{
                    $list[$key]['study_count'] = $study_count;
                  }
               }else{
                  $list[$key]['study_count'] = $val['virtual_amount'];
             }
           }
           //最热课程  排序冒泡
           if($sort==2){
               $len = count($list);
               for($k=1;$k<$len;$k++)
               {
                   for($j=0;$j<$len-$k;$j++){
                       if($list[$j]['study_count']<$list[$j+1]['study_count']){
                           $temp =$list[$j+1];
                           $list[$j+1] =$list[$j] ;
                           $list[$j] = $temp;
                       }
                   }
               }
           }
       }
       header("Content-type: application/json");
       exit(json_encode($list));
   }
   public  function  getpackage(){
       $title = input('title','');
       $page = input('page',1);
       $size = input('size',7);
       if($title){
           $map['title'] = ['like',"%" . $title . "%"];
       }
       $map['closed']=0;
       $map['audit']=1;
       $count = db('Package')->where($map)->count();//计算总页面
       $allpage = ceil($count / $size);
       if($page ==1){$firset_limit = 0;}else{$firset_limit =($page-1)*$size;}
       $lists = db('Package')->where($map)->field('id,title,price')->limit($firset_limit,$size)->order('orderby desc')->select();
       $data['courselist'] = $lists;
       $data['currentPage'] = $page;
       $data['total'] = $allpage;
       header("Content-type: application/json");
       exit(json_encode($data));
   }
   public  function get_one_package(){
       $packageList = array();
       $id_arr = input('id_arr','');
       $list = array();
       if($id_arr){
           $map['closed']=0;
           $map['audit']=1;
           $map['id']=['in',json_decode($id_arr,true)];
           $data = db('Package')->where($map)->order('orderby desc')->select();
       }
       if($data){
           foreach ($data as $k=>$v) {
               $ret_package = [];
               $ret_package['banner'] = '';
               $ret_package['id'] = $v['id'];
               $ret_package['title'] = $v['title'];
               $ret_package['price'] = $v['price'];
               $ret_package['create_time'] = $v['create_time'];
               $ret_package['audit'] = $v['audit'];
               $course_id = json_decode($v['course_id']);
               $ret_banner = '';
               if($v['banner']){
                   $ret_banner = $v['banner'];
               }
               $banner_arr = get_package_banner($ret_banner,$course_id);
               $ret_package['banner'] = $banner_arr['banner'];
               $ret_package['banner_color'] = $banner_arr['banner_color'];
               $packageList[] = $ret_package;
           }
       }
       header("Content-type: application/json");
       exit(json_encode($packageList));
   }
  public function save_all(){
      $old_id = db('Custom_template')->where(['closed'=>0])->order('orderby asc')->column('id');
      $json_arr = input('json_arr','');
      $save_arr = json_decode($json_arr,true);
      foreach($save_arr as $val){
          $type = $val['type'];
          $id = $val['id'];
          $orderby = $val['orderby'];
          $temp_content = array();
          if($type==1){//banner 
              if(count($val['content']) >0){
                  foreach ($val['content'] as $key=>$mytemp){
                      $val['content'][$key]['id'] = isset($mytemp['id'])?$mytemp['id']:0;
                      if(isset($mytemp['temp'])){
                          $temp = $mytemp['temp'];
                          if($temp){
                              $image_name =$this->base64_upload($temp);
                              $val['content'][$key]['img'] = '/public/uploads/banner/'.$image_name;
                              unset($val['content'][$key]['temp']);
                          }
                      }  
                  }
                  $content = json_encode($val['content']);
              }else{
                  $content = 0;
              }
              
              
          }else if(in_array($type, array(2,4,5))){//分割线 //搜索  //富文本
              $content = isset($val['content'])?$val['content']:'';
          }
          else if($type==3){//课程
              if($val['content']&&isset($val['content']['course_id'])){
                  $temp_content['sort'] = isset($val['sort'])?$val['sort']:'';
                  $temp_content['title'] = isset($val['title'])?$val['title']:'';
                  $temp_content['show_more'] = isset($val['show_more'])?$val['show_more']:0;
                  $course_id =$val['content']['course_id'];
                  $temp_content['course_id'] =$course_id;
                  $content = json_encode($temp_content);
              }else{
                  $content = '';
              }
          }
          else{//套餐
              //var_dump($val['content']['package_id']);
              if($val['content']&&$val['content']['package_id']){
                  $temp_content['title'] = isset($val['title'])?$val['title']:'';
                  $temp_content['show_more'] = isset($val['show_more'])?$val['show_more']:0;
                  $package_id =$val['content']['package_id'];
                  $temp_content['package_id'] =$package_id;
                  $content = json_encode($temp_content);
              }else{
                  $content = '';
              }
          }
          $temp_data['type']=$type;
          $temp_data['orderby']=$orderby;
          $temp_data['content']=$content;
          if(in_array($id,$old_id)){//更新
              if($content){
                  db('Custom_template')->where(['id'=>$id])->update($temp_data);
              }else {
                  if($type !=1){
                      db('Custom_template')->where(['id'=>$id])->update(['closed'=>1]);
                  }
                  
              }
               
          }else{//插入
              if($content){
                   db('Custom_template')->insert($temp_data);
              }
          }
      }
      successJson('ok','',1);
      
  }
  
  public function base64_upload($base64) {
       $base64_image = str_replace(' ', '+', $base64);
      //post的数据里面，加号会被替换为空格，需要重新替换回来，如果不是post的数据，则注释掉这一行
      if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image, $result)){
          //匹配成功
          if($result[2] == 'jpeg'){
              $image_name = uniqid().'.jpg';
              //纯粹是看jpeg不爽才替换的
          }else{
              $image_name = uniqid().'.'.$result[2];
          }
          $image_file = "public/uploads/banner/{$image_name}";
          //服务器文件存储路径
          if (file_put_contents($image_file, base64_decode(str_replace($result[1], '', $base64_image)))){
              return $image_name;
          }else{
              return false;
          }
      }else{
          return false;
      }
  }
  

}
