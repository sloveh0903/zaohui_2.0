<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:62:"C:\php\zaohui_2.0/application/admin\view\course\introduce.html";i:1519891268;s:59:"C:\php\zaohui_2.0/application/admin\view\common\header.html";i:1519891268;s:58:"C:\php\zaohui_2.0/application/admin\view\common\admin.html";i:1519891268;s:60:"C:\php\zaohui_2.0/application/admin\view\common\version.html";i:1519891268;}*/ ?>
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
<body>
<div class="article_manage">
    <div class="right-side-header clearfix">
	        <span>知识管理</span>
	        

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
    <div class="right_side_content ">
    	<div class="add_article_main ">	
	        <?php if($type == 'create'): ?>
	        <div class="breadcrumb">
	              <a href='<?php echo url("course/index"); ?>'>课程列表</a>
	              /
	              <span>添加课程</span>
	        </div>
	        <div class="add_article_step">
	            <div class="step-liucheng">
	                <ul class="step-liucheng-ul clearFloat">
	                    <li><i>1</i>添加课程</li>
	                    <li><i>2</i>添加视频</li>
	                    <li class="active"><i>3</i>pc介绍</li>
	                    <li><i>4</i>完成</li>
	                </ul>
	            </div>
	        </div>
	        <?php else: ?>
	        <div class="breadcrumb">
	              <a href='<?php echo url("course/index"); ?>'>课程列表</a>
	              /
	              <span>编辑课程</span>
	         </div>
	        <div class="add_article_step">
	            <ul class="editCourse-nav-ul clearFloat">
	                <li data-src='<?php echo url("course/edit_course",["cid"=>$cid]); ?>' class="goto"><i data-name="edit_course">课程简介</i></li>
	                <li class="goto" data-src='<?php echo url("chapter/index",["cid"=>$cid,"type"=>"update"]); ?>'><i data-name="edit_course_video">课程视频</i></li>
	                <li class="goto active" data-src='<?php echo url("course/introduce",["cid"=>$cid,"type"=>"update"]); ?>'><i data-name="edit_pc_production">pc版介绍</i></li>
	            </ul>
	        </div>
	        <?php endif; ?>
	        <div class="operation_div">
	            <span class="span_add btn goto" data-src='<?php echo url("course/add_intro",["cid"=>$cid,"type"=>$type]); ?>' style="line-height: 31px">添加</span>
	            <span class="span_delete btn ajax-all" data-name="checkbox" data-params='{"url": "<?php echo url("course/del_Allintro"); ?>","data":"","confirm":"true"}'>删除</span>
	            <div class="clearfix"></div>
	        </div>
	        <div id="list" class="layui-form"></div>
	        <div class="text-right" id="page"></div>
	    </div>
    </div>
    <?php if($type == 'create'): ?>
    <div class="course_right_footer" style="position: fixed;width: 100%;bottom:0;left:0">
        <form id="form1" class="layui-form layui-form-pane" action="<?php echo url('step_change'); ?>" method='post'>
            <input type="hidden" name="step" id ="step" value=3>
            <input type="hidden" name="cid" id ="cid" value=<?php echo $cid; ?>>
            <div class="layui-form-item" style="margin-bottom: 0;">
                <div class="layui-input-block" style="margin-left:0;">
                    <a href='<?php echo url("course/create"); ?>?cid=<?php echo $cid; ?>&step=2' style="line-height: 30px;font-size: 14px;color:#00B6F2;margin-right: 20px;">上一步</a>
                    <button class="layui-btn" jq-submit lay-filter="submit">保存课程</button>
                    <i style="line-height: 30px;font-size: 14px;color:#00B6F2;margin-left: 20px;">(无PC站点直接保存课程)</i>
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>
<script id="list-tpl" type="text/html" data-params='{"url":"<?php echo url("course/introduce",["cid"=>$cid]); ?>","pageid":"#page"}'>
    <table id="example" class="layui-table" lay-skin="line">
        <thead>
        <tr>
            <th style="width: 6%"><input type="checkbox" id="checkall" data-name="checkbox" lay-filter="check" lay-skin="primary"></th>
            <th>序号</th>
            <th>名称</th>
            <th>排序</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {{# layui.each(d.list, function(index, item){ }}
        <tr>
            <td>
                <input type="checkbox" name="checkbox" value="{{item.id}}" lay-skin="primary">
            </td>
            <td><i title>{{item.id}}</i></td>
            <td><i title>{{item.title}}</i></td>
            <td class="icon"><i class="rise ajax" data-params='{"url": "<?php echo url("course/moveIntroduceUpDown"); ?>","data":"id={{ item.id}}&cid={{ item.cid}}&updown=up"}'></i><i class="drop ajax" data-params='{"url": "<?php echo url("course/moveIntroduceUpDown"); ?>","data":"id={{ item.id}}&cid={{ item.cid}}&updown=down"}'></i>
            </td>

            <td>
                <i class=" i_edit goto"  data-src='<?php echo url("course/edit_intro"); ?>?id={{ item.id}}&type=<?php echo $type; ?>&cid=<?php echo $cid; ?>'>编辑</i>／
                <i class="i_delete ajax" data-params='{"url": "<?php echo url("course/del_intro"); ?>","data":"id={{ item.id}}","confirm":"true"}'>删除</i>
            </td>
        </tr>
        {{# }); }}
        </tbody>
    </table>
</script>
<script type="text/javascript" src="/public/jqadmin/js/layui/layui.js"></script>
<script>
    layui.config({
        base: '/public/jqadmin/js/',
        version: "1.3.1"
    }).extend({
        elem: 'jqmodules/elem',
        tabmenu: 'jqmodules/tabmenu',
        jqmenu: 'jqmodules/jqmenu',
        ajax: 'jqmodules/ajax',
        dtable: 'jqmodules/dtable',
        jqdate: 'jqmodules/jqdate',
        modal: 'jqmodules/modal',
        tags: 'jqmodules/tags',
        jqform: 'jqmodules/jqform',
        echarts: 'lib/echarts',
        webuploader: 'lib/webuploader'
    })
</script>
	<script src="/public/gzadmin/js/jquery-1.11.0.min.js"></script>
	<script src="/public/gzadmin/js/public_PC.js"></script>

<div class="edit_pswd" style="display: none">
    <form id="form1" class="layui-form layui-form-pane" action="<?php echo url('user/edit_password'); ?>">
        <input type="hidden" name="id" value="<?php echo session('admin_uid'); ?>">
        <div class="layui-form-item">
            <label class="layui-form-label">原密码</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="password" name="passwd" placeholder="密码"  jq-verify="pass" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">新密码</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="password" name="newpasswd" placeholder="新密码"  jq-verify="pass" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">确认密码</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="password" name="morepasswd" placeholder="确认密码"  jq-verify="pass" >
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" jq-submit lay-filter="submit">立即提交</button>
            </div>
        </div>
    </form>
</div>

<script>
//点击用户头像显示隐藏菜单
$(".user-self").click(function(e){
	e.stopPropagation();
   var $usersetul = $(this).next(".user-set-ul"),
       display = $usersetul.css("display");
   if("none"==display){
      $usersetul.slideDown();
   }else{
      $usersetul.slideUp();
   }    
});
$('body').on('click', function () {
	if($('.user-set-ul').css('display') == 'block') {
		$(".user-set-ul").slideUp();
	}
})
</script>
<script>
    layui.use('list');
</script>
</body>
</html>
