<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:91:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/distribution/edit_config.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
	        <span>营销中心</span>
	        

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
                <li><a href="javascript:void(0)" class="active goto" data-src='<?php echo url("distribution/index"); ?>'>分销规则</a></li>
                <li><a href="javascript:void(0)" class="goto" data-src='<?php echo url("distribution/rebater"); ?>'>分销员</a></li>
                <li><a href="javascript:void(0)" class="goto" data-src='<?php echo url("distribution/rebatelist"); ?>'>分销记录</a></li>
                <li><a href="javascript:void(0)" class="goto" data-src='<?php echo url("distribution/withdraw"); ?>'>提现记录</a></li>
                <li><a href="javascript:void(0)" class="goto" data-src='<?php echo url("poster/index"); ?>'>营销海报</a></li>
            </ul>
        </div>
		<div class="success_tip displayNone">已完成</div>
	    <div class="course_right_main ">
	        <section class="panel panel-padding">
	            <form id="form1" class="layui-form layui-form-pane" action="<?php echo url('index'); ?>">
	                <?php if(!$poster): ?>
	                <div class="layui-form-item" style="height: 60px;color: #8282D9; background:rgba(0,182,242,.13);border-radius: 2px;line-height: 60px">
	                    <div style="line-height: 60px;font-size: 16px;padding-left: 20px; width: 94%;float: left">
	                        开启营销海报功能 传播效果更佳
	                    </div>
	                    <div style="font-size: 14px;line-height: 60px;">
	                        <span class="goto" data-src='<?php echo url("poster/index"); ?>'>去设置</span>
	                    </div>
	                </div>
	                <?php endif; ?>
	                <div class="line"></div>
	                <div class="layui-form-item ">
	                    <label class="layui-form-label">直销提成</label>
	                    <div class="layui-input-inline">
	                        <input type="hidden" name="id" value="<?php echo $config['id']; ?>">
	                        <input type="text" name="first" value="<?php echo $config['first']; ?>" jq-verify="number" style="width: 80px" autocomplete="off" class="layui-input ">
	                    </div>
	                    <label class="layui-form-label"  style="width: auto">%&nbsp;&nbsp;&nbsp;&nbsp;设置为0默认不开启，本规则对所有课程有效，下同&nbsp;&nbsp;&nbsp;&nbsp;</label>
	                </div>
	                <div class="layui-form-item ">
	                    <label class="layui-form-label">上级提成</label>
	                    <div class="layui-input-inline">
	                        <input type="text" name="second" value="<?php echo $config['second']; ?>" jq-verify="number" style="width: 80px" autocomplete="off" class="layui-input ">
	                    </div>
	                    <label class="layui-form-label">%</label>
	                </div>
	                <div class="layui-form-item ">
	                    <label class="layui-form-label">上2级提成</label>
	                    <div class="layui-input-inline">
	                        <input type="text" name="third" value="<?php echo $config['third']; ?>" jq-verify="number" style="width: 80px" autocomplete="off" class="layui-input ">
	                    </div>
	                    <label class="layui-form-label">%</label>
	                </div>
	                <div class="layui-form-item ">
	                    <label class="layui-form-label">分销折扣</label>
	                    <div class="layui-input-inline">
	                        <input type="text" name="discount" value="<?php echo $config['discount']; ?>"  jq-verify="discount" style="width: 80px" autocomplete="off" class="layui-input ">
	                    </div>
	                    <label class="layui-form-label" style="width: auto">折&nbsp;&nbsp;&nbsp;&nbsp;分销员分享的购买链接可以享受的折扣优惠，最多精确1位小数(折扣不大于10，10为不打折)&nbsp;&nbsp;&nbsp;&nbsp;</label>
	                </div>
	                <div class="line"></div>
	                <div class="layui-form-item">
	                    <label class="layui-form-label">是否开启</label>
	                    <div class="layui-input-inline">
	                        <input type="radio" name="status" title="启用" value="1" <?php if($config['status'] == 1): ?>checked<?php endif; ?> />
	                        <input type="radio" name="status" title="禁用" value="0" <?php if($config['status'] == 0): ?>checked<?php endif; ?> />
	                    </div>
	                </div>
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
</script>
</body>
</html>