<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/message/remind.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
<style>
    .layui-layer-page .layui-layer-content{
        overflow: visible !important;
    }
</style>
<body>
<div class="article_manage mCustomScrollbar">
	<div class="right-side-header clearfix">
	        <span>系统设置</span>
	        

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
   <div class="right_side_content">
    	<div class="system_guanli_div">
	 		<ul class="system_guanli_ul">
		    	<li><a href="javascript:void(0)" class="goto" data-src='<?php echo url("user/index"); ?>'>后台用户管理</a></li>
                <li><a href="javascript:void(0)" class="goto" data-src='<?php echo url("role/index"); ?>'>角色管理</a></li>
                <li><a href="javascript:void(0)" class="goto" data-src='<?php echo url("index/setlogin"); ?>'>登录配置</a></li>
                <li><a href="javascript:void(0)" class=" active goto" data-src='<?php echo url("message/remind"); ?>'>公告</a></li>
           </ul>
		</div>
		<div class="success_tip displayNone">已完成</div>
      
   		<div class="course_right_main ">
	         <div class="operation_div">
					<span class="span_add btn modal-catch" data-params='{"content":".add-subcat","act":"<?php echo url("message/add_remind"); ?>", "title":"添加公告","type":"1"}'>添加公告</span>
		            <div class="select_search "  style=' bottom: 10px;'>
		                <form class="layui-form" action='<?php echo url("message/remind"); ?>'>
		                    <!--<input class="keyword" type="text" name="key" placeholder="收索..">-->
		                    <input class="search_btn" type="image" src="/public/gzadmin/images/gray_search@2x20.png" lay-submit name="submit" lay-filter="search" align="" >
		                    <div class="layui-form">
		                        <div class="layui-inline">
		                            <div class="layui-input-inline">
		                                <input class="layui-input normal-date" name="start_date" placeholder="开始日">
		                            </div>
		                            <div class="layui-input-inline">
		                                <input class="layui-input normal-date" name="end_date" placeholder="截止日">
		                            </div>
		                        </div>
		                        <div class="layui-inline">
		                            <div class="layui-input-inline">
		                                <input class="layui-input" name="key" placeholder="关键字">
		                            </div>
		                        </div>
		                      </div>
		                </form>
		            </div>
	         	</div>
	          <div id="list" class="layui-form"></div> 
	         <div class="text-right" id="page"></div>
      </div>
    </div>
</div>
<div class="add-subcat">
    <form id="form1" class="layui-form layui-form-pane" action='<?php echo url("message/add_remind"); ?>'>
        <div class="layui-form-item">
            <label class="layui-form-label">公告内容</label>
            <div class="layui-input-inline">
                <textarea rows="" name="content" required jq-verify="required" jq-error="请输入公告内容" cols="" class="layui-textarea "></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <button class="layui-btn" jq-submit jq-filter="submit">确定</button>
            </div>
        </div>
    </form>
</div>
<div class="edit-subcat" style="display: none">
    <form id="form2" class="layui-form layui-form-pane" action='<?php echo url("message/edit_remind"); ?>'>
        <div class="layui-form-item">
            <label class="layui-form-label">公告内容</label>
            <div class="layui-input-inline">
                <textarea rows="" name="content" required jq-verify="required" jq-error="请输入公告内容" cols="" class="layui-textarea "></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <button class="layui-btn" jq-submit jq-filter="submit">确定</button>
            </div>
        </div>
    </form>
</div>
<script id="list-tpl" type="text/html" data-params='{"url":"<?php echo url("message/remind"); ?>","pageid":"#page"}'>
    <table id="example" class="layui-table" lay-skin="line">
        <thead>
        <tr>
            <th>序号</th>
            <th width="50%">标题</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {{# layui.each(d.list, function(index, item){ }}
        <tr>
            <td>{{ item.id}}</td>     
            <td>{{ item.content}}</td>
            <td>{{ item.create_time}}</td>
            <td><i class="i_edit btn modal-catch" data-params='{"content":".edit-subcat","act":"<?php echo url("message/edit_remind"); ?>","data":"id={{ item.id}}&content={{ item.content}}", "title":"编辑公告","type":"1"}'>
                编辑</i>
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