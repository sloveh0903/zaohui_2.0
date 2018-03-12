<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/course/add_intro.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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

<script type="text/javascript" charset="utf-8" src="/public/webueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/public/webueditor/ueditor.all.min.js"> </script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script type="text/javascript" charset="utf-8" src="/public/webueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">

    //实例化编辑器
    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例

    UE.getEditor('cdcontent',{initialFrameHeight:400});

</script>
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
   
    <div class="right_side_content">
	    <div class="add_article_main ">
	        <?php if($type == "create"): ?>
	        <div class="breadcrumb">
	              <a href='<?php echo url("course/introduce"); ?>?cid=<?php echo $cid; ?>'>pc版介绍</a>
	              /
	              <span>添加</span>
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
	            <a href='<?php echo url("course/introduce"); ?>?cid=<?php echo $cid; ?>'>pc版介绍</a>
	            /
	            <span>添加</span>
	          </div>
	        <div class="add_article_step">
	            <ul class="editCourse-nav-ul clearFloat">
	                <li data-src='<?php echo url("course/edit_course",["cid"=>$cid]); ?>' class="goto"><i data-name="edit_course">课程简介</i></li>
	                <li class="goto" data-src='<?php echo url("chapter/update",["id"=>$cid]); ?>'><i data-name="edit_course_video">课程视频</i></li>
	                <li class="goto  active " data-src='<?php echo url("course/introduce",["cid"=>$cid,"type"=>"update"]); ?>'><i data-name="edit_pc_production">pc版介绍</i></li>
	            </ul>
	        </div>
	        
	        <?php endif; ?>
	        <div class="articleInfo_fill">
	            <form id="form1" class="layui-form layui-form-pane" action="<?php echo url('add_intro'); ?>">
	                <div class="layui-form-item">
	                    <label class="layui-form-label">标题</label>
	                    <div class="layui-input-block">
	                        <input type="hidden" name="cid" value="<?php echo $cid; ?>">
	                        <input type="hidden" name="type" value="<?php echo $type; ?>">
	                        <input type="text" name="title" required jq-verify="required|title" jq-error="请输入标题|请输入数字" placeholder="标题字数不超过20个字" autocomplete="off" class="layui-input ">
	                    </div>
	                </div>
	<!--                 <div class="layui-form-item">
	                    <label class="layui-form-label">状态</label>
	                    <div class="layui-input-inline">
	                        <input type="radio" name="closed" title="启用" value="0" checked />
	                        <input type="radio" name="closed" title="禁用" value="1" />
	                    </div>
	                </div> -->
	                <div class="layui-form-item">
	                    <label class="layui-form-label">详情</label>
	                    <div class="layui-input-block">
	                        <textarea name="content" jq-verify="content" id="cdcontent"></textarea>
	                    </div>
	                </div>
	                <div class="layui-form-item">
	                    <div class="layui-input-block">
	                        <button class="layui-btn" jq-submit lay-filter="submit">立即提交</button>
	                    </div>
	                </div>
	            </form>
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
    layui.use('course');
</script>

</body>
</html>

