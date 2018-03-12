<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:90:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/wechatkeywords/customer.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
	        <span>公众号配置</span>
	        

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
			   <li><a href="<?php echo url('operate/index'); ?>">系统参数</a></li>
			   <li><a href="<?php echo url('customenu/index'); ?>" >自定义菜单</a></li>
			   <li><a href="<?php echo url('operate/subscribereply'); ?>" >关注自动回复</a></li>
			   <li><a href="<?php echo url('wechatkeywords/index'); ?>">关键字回复</a></li>
			   <li><a href="<?php echo url('wechatkeywords/customer'); ?>" class="active">微信客服设置</a></li>
			   <li><a href="<?php echo url('operate/follow'); ?>">公众号关注引导</a></li>
		   </ul>
	   </div>
		<div class="success_tip displayNone">已完成</div>

	    <div class="add_article_main ">
	    </div>
        <div class="articleInfo_fill">
	        <div class="layui-form-item ">
	         <section class="panel panel-padding">
	                <form class="layui-form" action='<?php echo url("index"); ?>'>
	                 <label class="layui-form-label"  style='padding:0 0 0 0;width:99px;'>开通微信客服</label>
	                <div class="layui-input-block">
	                <input type="checkbox" name="switch" lay-skin="switch" lay-text="" <?php if($is_customer==1): ?> checked="checked" <?php endif; ?> lay-filter="ajax" data-params='{"url":"<?php echo url("wechatkeywords/customer"); ?>","confirm":"true","data":""}'>
	                <span style='padding:0 0 0 0;color:#999999;'>（仅认证服务号可在公众平台开通微信客服）</span>
	                <br><br><br>
<!-- 	                <span style='color:#999999;'>开启后，无匹配自动回复自动失效，用户微信咨询信息轮流分配并提醒客服人员；微信端课程详情页，显示咨询按钮，用户点击咨询可以识别二维码跳转到公众号咨询客服； -->
<!-- 	                <br> -->
<!-- 	                		关闭后，无匹配自动回复自动生效，用户微信咨询信息不会提醒客服人员；</span> -->
	                </div>
	                </form>
	        </div>
	        <div class="clearfix"></div>
	  	</div>
	        <div id="list" class="layui-form">
	         </div> 
	 
	</div>

</div>

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
<script src="/public/gzadmin/js/clipboard.min.js"></script>
<script>
    layui.use('adv');
</script>
</body>
</html>

