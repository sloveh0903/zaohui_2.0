<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/index/config.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
<script type="text/javascript" charset="utf-8"
	src="/public/webueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8"
	src="/public/webueditor/ueditor.all.min.js">
	
</script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script type="text/javascript" charset="utf-8"
	src="/public/webueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">
	//实例化编辑器

	//建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例

	UE.getEditor('cdcontent', {
		initialFrameHeight : 100,
		autoHeightEnabled:true,
		imagePopup:false
	});
	UE.getEditor('introducecontent', {
		initialFrameHeight : 100,
		autoHeightEnabled:true,
		imagePopup:false
	});
	
</script>
<body>
	<div class="article_manage mCustomScrollbar">
		<div class="right-side-header clearfix">
			<span>店铺管理</span> 

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
					<li><a href="javascript:void(0)" class="active goto"
						data-src='<?php echo url("index/config"); ?>'>网站配置</a></li>
				</ul>
			</div>
			<div class="success_tip displayNone">已完成</div>
			<div class="add_article_main ">
				<div class="articleInfo_fill">
					<section class="panel panel-padding">
						<form id="form1" class="layui-form layui-form-pane"
							action="<?php echo url('config'); ?>">
							<div class="layui-form-item">
								<label class="layui-form-label">网站标题</label>
								<div class="layui-input-block">
									<input type="hidden" name="id" value="<?php echo $web['id']; ?>"> <input
										type="text" name="sitename" required value="<?php echo $web['sitename']; ?>"
										jq-verify="required|title" jq-error="请输入标题"
										placeholder="请输入标题" autocomplete="off" class="layui-input ">
								</div>
							</div>
							<div class="layui-form-item">
								<label class="layui-form-label">关键字</label>
								<div class="layui-input-block">
									<input type="text" name="seo_keywords"
										value="<?php echo $web['seo_keywords']; ?>" required jq-verify="required"
										placeholder="请输入关键字，多个请用英文逗号隔开" autocomplete="off"
										class="layui-input">
								</div>
							</div>
							<div class="layui-form-item ">
								<label class="layui-form-label">描述</label>
								<div class="layui-input-block">
									<textarea name="seo_description" placeholder="请输入描述"
										class="layui-textarea"><?php echo $web['seo_description']; ?></textarea>
								</div>
							</div>
							<div class="layui-form-item">
								<label class="layui-form-label">联系方式</label>
								<div class="layui-input-block">
									<input type="text" name="phone" value="<?php echo $web['phone']; ?>" required
										jq-verify="required" placeholder="请输入平台联系方式"
										autocomplete="off" class="layui-input">
								</div>
							</div>
							<!-- 	                    <div class="layui-form-item"> -->
							<!-- 	                        <label class="layui-form-label">公众号</label> -->
							<!-- 	                        <div class="layui-input-block"> -->
							<!-- 	                            <input type="text" name="wechat" required jq-verify="required" jq-error="请输入公众号" value="<?php echo $web['wechat']; ?>" placeholder="请输入公众号" autocomplete="off" class="layui-input "> -->
							<!-- 	                        </div> -->
							<!-- 	                    </div> -->
							<div class="layui-form-item">
								<label class="layui-form-label">微信二维码</label>
								<div class="layui-input-block">
									<input type="file" name="file" class="layui-upload-file">
									<input type="hidden" name="wechat" value="<?php echo $web['wechat']; ?>" jq-verify="required" jq-error="请上传图片" error-id="ewm-error">
									<p id="ewm-error" class="error"></p>
								</div>
								<div class="layui-input-block">
									<div class="imgbox">
										<img src="<?php echo $web['wechat']; ?>" alt="..." class="img-thumbnail">
									</div>
								</div>
							</div>
							<div class="layui-form-item">
								<label class="layui-form-label">网站Logo</label>
								<div class="layui-input-block">
									<input type="file" name="file" class="layui-upload-file">
									<input type="hidden" name="logo" value="<?php echo $web['logo']; ?>" jq-verify="required" jq-error="请上传图片" error-id="img-error">
									<p class="upload-info">建议高度80PX，宽度按比例缩放即可</p>
									<p id="img-error" class="error"></p>
								</div>
								<div class="layui-input-block">
									<div class="imgbox" style="height: 80px">
										<img src="<?php echo $web['logo']; ?>" alt="..." class="img-thumbnail" style="height: 80px;width: auto">
									</div>
								</div>
							</div>
							<div class="layui-form-item">
								<label class="layui-form-label">ICO图标</label>
								<div class="layui-input-block">
									<input type="file" name="file" class="layui-upload-file">
									<input type="hidden" name="favicon" value="<?php echo $web['favicon']; ?>"
										jq-verify="required" jq-error="请上传图片" error-id="favicon-error">
									<p class="upload-info">请上传ico格式图片</p>
									<p id="favicon-error" class="error"></p>
								</div>
								<div class="layui-input-block">
									<div class="imgbox">
										<img src="<?php echo $web['favicon']; ?>" alt="..." class="img-thumbnail">
									</div>
								</div>
							</div>
							<div class="layui-form-item">
								<label class="layui-form-label">PC首页底部介绍</label>
								<div class="layui-input-block">
									<textarea name="introduce" jq-verify="introducecontent" id="introducecontent"><?php echo $web['introduce']; ?></textarea>
								</div>
							</div>
							<div class="layui-form-item">
								<label class="layui-form-label">版权信息</label>
								<div class="layui-input-block">
									<input type="text" name="copyright" value="<?php echo $web['copyright']; ?>"
										placeholder="请输入版权信息" autocomplete="off" class="layui-input">
								</div>
							</div>

<!-- 							<div class="layui-form-item"> -->
<!-- 								<label class="layui-form-label">首页介绍标题</label> -->
<!-- 								<div class="layui-input-block"> -->
<!-- 									<input type="text" name="title" value="<?php echo $web['title']; ?>" -->
<!-- 										placeholder="请输入介绍标题" autocomplete="off" class="layui-input"> -->
<!-- 								</div> -->
<!-- 							</div> -->



							<div class="layui-form-item">
								<label class="layui-form-label">关于我们</label>
								<div class="layui-input-block">
									<textarea name="aboutus" jq-verify="content" id="cdcontent"><?php echo $web['aboutus']; ?></textarea>
								</div>
							</div>

							<div class="layui-form-item">
								<div class="layui-input-block">
									<button class="layui-btn" jq-submit lay-filter="submit">立即提交</button>
<!-- 									<button type="reset" class="layui-btn layui-btn-primary">重置</button> -->
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
	<script>
		layui.use('myform');
	</script>
</body>
</html>