<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:79:"/home/wwwroot/lnmp/domain/qusukj/web/application/admin/view/operate/applet.html";i:1517539928;s:78:"/home/wwwroot/lnmp/domain/qusukj/web/application/admin/view/common/header.html";i:1517539928;s:77:"/home/wwwroot/lnmp/domain/qusukj/web/application/admin/view/common/admin.html";i:1517539928;s:79:"/home/wwwroot/lnmp/domain/qusukj/web/application/admin/view/common/version.html";i:1517539928;}*/ ?>
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
<style type="text/css">
  .copy-link-addr{
    color:#00b6f2;
    cursor:pointer
  }
</style>
<body>
<div class="article_manage">
    <div class="right-side-header clearfix">
	        <span>小程序配置</span>
	        

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
                <li><a href="<?php echo url('operate/applet'); ?>" class="active"  >小程序配置</a></li>
                <li><a href="<?php echo url('operate/customer'); ?>" >小程序客服</a></li>
           </ul>
		</div>
		<div class="success_tip displayNone">已完成</div>

	    <div class="add_article_main ">
	        <div class="articleInfo_fill">
	            <section class="panel panel-padding">
	                <form id="form1" class="layui-form layui-form-pane" action="<?php echo url('operate/applet'); ?>">
	                    <div class="layui-form-item">
	                        <label class="layui-form-label">appid</label>
	                        <div class="layui-input-block">
	                            <input type="text" name="wx_appid"  value="<?php echo $config['wx_appid']; ?>" class="layui-input ">
	                        </div>
	                    </div>
	                    <div class="layui-form-item">
	                        <label class="layui-form-label">secret</label>
	                        <div class="layui-input-block">
	                            <input type="text" name="wx_secret" value="<?php echo $config['wx_secret']; ?>" class="layui-input "></textarea>
	                        </div>
	                    </div>
	                    <div class="layui-form-item">
	                        <label class="layui-form-label">商户id</label>
	                        <div class="layui-input-block">
	                            <input type="text" name="wx_mchid" value="<?php echo $config['wx_mchid']; ?>" class="layui-input "></textarea>
	                        </div>
	                    </div>
	                     <div class="layui-form-item">
	                        <label class="layui-form-label">支付密钥</label>
	                        <div class="layui-input-block">
	                            <input type="text" name="wx_appkey" value="<?php echo $config['wx_appkey']; ?>" class="layui-input "></textarea>
	                        </div>
	                    </div>
	                    <div class="layui-form-item">
	                        <div class="layui-input-block">
	                            <button class="layui-btn" jq-submit lay-filter="submit">立即提交</button>
	                        </div>
	                    </div>
	                </form>
	            </section>
	        </div>
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
    <script src="/public/gzadmin/js/jquery-1.11.0.min.js"></script>
    <script src="/public/gzadmin/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="/public/gzadmin/js/placeholder.js"></script>
    <script src="/public/gzadmin/js/public_PC.js"></script>
    <script src="/public/gzadmin/js/clipboard.min.js"></script>
    <script>
        layui.use('course');
    </script>     
</body>
</html>