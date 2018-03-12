<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:84:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/statistics/course.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>后台管理系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <!-- load css -->
    <link rel="stylesheet" type="text/css" href="/public/jqadmin/css/bootstrap.min.css?v=v3.3.7" media="all">
    <link rel="stylesheet" type="text/css" href="/public/jqadmin/css/font/iconfont.css?v=1.0.0" media="all">
    <link rel="stylesheet" type="text/css" href="/public/jqadmin/css/layui.css?v=1.0.9" media="all">
    <link rel="stylesheet" type="text/css" href="/public/jqadmin/css/main.css?v1.3.1" media="all">
    <link rel="stylesheet" href="/public/gzadmin/css/all.css">
    <link rel="stylesheet" href="/public/gzadmin/css/main.css">
    
</head>
<link rel="stylesheet" href="/public/gzadmin/css/data_analyse.css">

	<body>
	<div class="article_manage">
      <!-- 9.13 替换右侧头部 -->
      <div class="right-side-header clearfix">
        <span>数据分析</span>
        <div class="user-box">
          

<div class="user-box">
  <span class="user-self">
    <i><?php echo session('rolename'); ?>(<?php echo session('admin_username'); ?>)</i>
    <img class="img_myself" src="/public/image/logo.png" alt="自身头像">
  </span>
  <ul class="user-set-ul">
   	<li><i class="modal-catch" data-params='{"content":".edit_pswd","act":"<?php echo url("user/edit_password"); ?>", "title":"修改密码","type":"1"}'>修改密码</i></li>
    <li><a href='<?php echo url("login/loginOut"); ?>'  target="_blank"><i>退出</i></a></li>
  </ul>
</div>
<div class="success_tip displayNone">已完成</div>
        
        </div>
        <div class="success_tip displayNone">已完成</div>
      </div>
      <!-- 替换右侧头部 结束 -->
      <!--内容开始-->
      	
      <!--用户管理选项卡-->
      <div class="system_guanli_div data_guanli_div ">
        	<ul class="system_guanli_ul user_edit_ul">
	           	<li><a href="<?php echo url('index'); ?>" data-src="<?php echo url('index'); ?>">交易统计</a></li>
	            <li><a href="<?php echo url('user'); ?>" data-src="<?php echo url('user'); ?>">用户统计</a></li>
	            <li><a href="<?php echo url('course'); ?>" class="active"  data-src="data_analyse">课程统计</a></li>
        	</ul>
      </div>
      <div class="data_analyse_content">      	
        <!--数据分析详情一级模块-->               			
            <!--选项卡详情--> 
            <ul class="tab_main">
            	
            	
            	<!--课程统计-->
            	<li class="course_box ">
            		<div class="bgwhite course_count_tab" >
            			<div class="course_count">
	            			<table  class="user_table data_table ">
	            			<thead>
	            				<tr>
	            					<th class="course_img">封面</th>
	            					<th class="course_name">课程名称</th>
	            					<th class="course_num">销量</th>
	            					<th class="course_user_num">
		        						<p>已学人数</p>
		        						<img class="help" src="/public/gzadmin/images/help@2x.png" alt="帮助"/>
		        						<div class="float_div">
		        							<div class="float_box">
			        							<span></span>
			        							<p>统计标准：用户学习完课程的70%，系统定义为已学习</p>
		        							</div>
		        						</div>
		        					</th>    
	            					<th class="course_money th">销售金额</th>
	            					<th class="course_oprate">操作</th>            					
	            				</tr>
	            			</thead>
	            			<tbody>
	            			<?php if($list_arr): foreach($list_arr as $vo): ?> 
	            				<tr>
	            					<td class="course_img"><img src="<?php echo $vo['banner']; ?>" alt="封面" /></td>
	            					<td class="course_name"><?php echo $vo['title']; ?></td>
	            					<td class="course_num"><?php echo $vo['sale_count']; ?></td>
	            					<td class="course_user_num"><?php echo $vo['studynum']; ?></td>
	            					<td class="course_money">¥<?php echo $vo['totalmoney']; ?></td>
	            					<td class="course_oprate"><a href='<?php echo url("Statistics/video_detail"); ?>?cid=<?php echo $vo['cid']; ?>&title=<?php echo $vo['title']; ?>&studynum=<?php echo $vo['studynum']; ?>'>视频统计</a></td>
	            				</tr>
	            				<?php endforeach; endif; ?>
	            				
	            				
	            			</tbody>
	            		</table>
	            		</div>	            	
		            	
				        <div class="page_bt">
	            			   <div class="page_div3 paging" onselectstart="return false"
			                   style="font-size: 13px; font-family: 微软雅黑; font-weight: 400; height: 32px; width : auto;">
			                  </div>
					            <div class="clearfix" style="clear: both;overflow: hidden;"></div>
					        </div>
	            		</div>
            	</li>
            </ul>
            
	  </div>  
   	</div>    
	</body>
	<script type="text/javascript" src="/public/gzadmin/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="/public/jqadmin/js/layui/layui.js"></script>
<!-- 	<script type="text/javascript" src="/public/gzadmin/js/data_analyse.js"></script> -->
	<script src="/public/gzadmin/js/jquery.paging.js"></script>
	<script>
$(function(){

 //自定义皮肤，精简模式一
   var page4 = $(".page_div3").paging({
       total: '<?php echo $allpage; ?>',
       currentPage: '<?php echo $Nowpage; ?>',
       url: "/admin/statistics/course" 
   });
   var t = '<?php echo $allpage; ?>';
   if (parseInt(t) == 0) {
       page4.HiddenLast(0);
       page4.HiddenFirst(0);
   }
//鼠标经过学习人数问号，float_box出现
$(".course_user_num").on("mouseover",".help", function(){
	$(".float_div").show()
})
$(".course_user_num").on("mouseleave",".help", function(){
	$(".float_div").hide()
})
		
		
	})
</script>
</html>
