<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:78:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/login/login.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
<script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
<script src="http://static.geetest.com/static/tools/gt.js"></script>
<body class="login_body_bg">
<div class="login_top"><img src="/public/gzadmin/images/login_top@2x283.png"></div>
<div class="login_main">
    <!--<form id="form1" class="layui-form layui-form-pane" action="<?php echo url('doLogin'); ?>">-->
    <div class="login_main_up">
        <p>登录</p>
        <span class="error_tip" id="error_tip"></span>
    </div>
    <div class="login_main_down">
        <input type="text" style="width: 100%" name="username" placeholder="账号" autocomplete="off" class="layui-input username" >
        <input type="password" style="width: 100%" name="password" placeholder="密码" autocomplete="off" class="layui-input password">
        <p>
            <?php if(config('verify_type') == 1): ?>
            <div style="margin-bottom:70px">
                <input type="text" class="form-control" placeholder="验证码"
                       style="color:black;width:120px;float:left;margin:0px 0px;" name="code" id="code"/>
                <img src="<?php echo url('checkVerify'); ?>"
                     onclick="javascript:this.src='<?php echo url('checkVerify'); ?>?tm='+Math.random();"
                     style="float:right;cursor: pointer"/>
            </div>
            <?php else: ?>
            <div id="embed-captcha"></div>
            <p id="wait" style="display:none;">正在加载验证码......</p>
            <?php endif; ?>
        </p>
        <i class="i_login_confirm">登录</i>
    </div>
    <!--</form>-->
    <!--<form id="form1" class="layui-form layui-form-pane" action="<?php echo url('add_course'); ?>">
        <input type="text" name="title" required jq-verify="required" jq-error="请输入课程名称" placeholder="请输入课程名称" value="" autocomplete="off" class="layui-input ">

    </form>-->
    <i></i>
</div>
<div class="login_footer">ALL RIGHTS RESVERED</div>

</body>
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
    layui.use('course');
</script>
<script>
    $(function(){
        initHeight();
        var handlerEmbed = function (captchaObj) {
            $("#embed-submit").click(function (e) {
                var validate = captchaObj.getValidate();
                if (!validate) {
                    $("#notice")[0].className = "show";
                    setTimeout(function () {
                        $("#notice")[0].className = "hide";
                    }, 2000);
                    e.preventDefault();
                }
            });
            // 将验证码加到id为captcha的元素里
            captchaObj.appendTo("#embed-captcha");
            captchaObj.onReady(function () {
                $("#wait")[0].className = "hide";
            });
            // 更多接口参考：http://www.geetest.com/install/sections/idx-client-sdk.html
        };
        $.ajax({
            // 获取id，challenge，success（是否启用failback）
            url: "<?php echo url('/admin/Login/getVerify',array('t'=>time())); ?>", // 加随机数防止缓存
            type: "get",
            dataType: "json",
            success: function (data) {
                // 使用initGeetest接口
                // 参数1：配置参数
                // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
                initGeetest({
                    gt: data.gt,
                    challenge: data.challenge,
                    product: "float", // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                    offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                }, handlerEmbed);
            }
        });
        $('.i_login_confirm').on('click', function () {
            var username = $.trim($('.username').val());
            var password = $.trim($('.password').val());
            if (!username) {
                layer.msg('账号不能为空');
                return false;
            }
            if (!password) {
                layer.msg('密码不能为空');
                return false;
            }
            $.ajax({
                url: "<?php echo url('doLogin'); ?>", // 加随机数防止缓存
                type: "post",
                data: {
                    username:username,
                    password:password,
                    geetest_challenge:$('.geetest_challenge').val(),
                    geetest_validate:$('.geetest_validate').val(),
                    geetest_seccode:$('.geetest_seccode').val()
                },
                success: function (res) {
                    layer.msg(res.msg);
                    if(res.status != 200){
                        return false;
                    }else{
                        window.location.href=res.url;

                    }
                }
            });
        });
    });
    //浏览器改变触发该事件
    $(window).resize(function(){
        initHeight();
    })
    //初始化height
    function initHeight(){
        var window_height =  $(window).height();
        $(document.body).height( window_height);
    }
</script>
</html>