<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/integral/cash.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
span {
color: #292B33;
    opacity: .8;
}
</style>
<body>
<div class="article_manage">
	<div class="right-side-header clearfix">
	        <span>积分营销</span>
	        

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
                <li><a href="javascript:void(0)" class="goto" data-src='<?php echo url("integral/index"); ?>'>获取积分设置</a></li>
                <li><a href="javascript:void(0)" class="active  goto" data-src='<?php echo url("integral/cash"); ?>'>积分抵现</a></li>
            </ul>
        </div>
		<div class="success_tip displayNone">已完成</div>
	    <div class="course_right_main ">
	        <section class="panel panel-padding">
	            <form id="form1" class="layui-form layui-form-pane" action="<?php echo url('cash'); ?>">
	                <div class="layui-form-item">
	                    <label class="layui-form-label">积分抵现</label>
	                    <div class="layui-input-inline">
							<input type="checkbox" name="status" class="status" lay-skin="switch" <?php if($status==1): ?>checked="checked" <?php endif; ?>>
	                    </div>
	                </div>
	                <div class="rule" <?php if($status==0): ?>style='display:none'<?php endif; ?>>
	                <div class="layui-form-item ">
	                    <label class="layui-form-label">积分抵现：每</label>
	                    <div class="layui-input-inline">
	                        <input type="text" name="deducted_integral" value="<?php echo $deducted_integral; ?>" jq-verify="number" style="width: 80px" autocomplete="off" class="layui-input ">
	                    </div>
	                    <label class="layui-form-label"  style="width: auto">积分可抵扣1元</label>
	                </div>
	                <p style="color:rgb(41,43,51);opacity: 0.4;font-size: 14px;padding-left: 110px;">在结算时，用户可直接用积分抵扣实际付款金额<br><br></p>
	                <div class="layui-form-item ">
	                    <label class="layui-form-label">最高积分抵现</label>
	                    <div class="layui-input-inline">
	                        <input type="text" name="cash_percent" value="<?php echo $cash_percent; ?>" name="number" jq-verify="number" style="width: 80px" autocomplete="off" class="layui-input ">
	                    </div>
	                    <label class="layui-form-label"  style="width: auto">%</label>
	                </div>
	                <p style="color:rgb(41,43,51);opacity: 0.4;font-size: 14px;padding-left: 110px;">使用积分抵消实际付款金额，最多可抵消课程金额的百分比<br><br></p>
					</div>
					<div class="line"></div>
	                <div class="layui-form-item">
	                    <div class="layui-input-block">
	                        <button class="layui-btn" jq-submit lay-filter="submit">保存</button>
	                    </div>
	                </div>
	            </form>
	        </section>
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
<script>
    layui.use('course');
    $(document).on("click",'.layui-form-switch',function(){
        if($(this).hasClass('layui-form-onswitch')){
			$('.rule').show();
		}else{
            $('.rule').hide();
		}
	})
</script>
</body>
</html>