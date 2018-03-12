<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:75:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/ad/index.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
                <li><a href="javascript:void(0)" class="active goto" data-src='<?php echo url("ad/index"); ?>'>pc首页广告</a></li>
           </ul>
		</div>
		<div class="success_tip displayNone">已完成</div>
	    <div class="add_article_main ">
<!-- 	        <div class="ad_operation_div">
	            <div class="add_article_step">
	            	<ul class="editCourse-nav-ul clearFloat">
	                	<li data-src='<?php echo url("ad/index"); ?>?id=1'<?php if($id == 1): ?>class="goto active" <?php else: ?> class="goto" <?php endif; ?>><i data-name="index">小程序首页</i></li>
	                	<li data-src='<?php echo url("ad/index"); ?>?id=3' <?php if($id == 3): ?>class="goto active" <?php else: ?> class="goto" <?php endif; ?>><i data-name="pc_index">pc首页</i></li>

	            	</ul>
	        	</div>
	        </div> -->

	        <div class="operation_div">
	            <?php if($adv_count==8): ?>
	            <span class="span_add btn goto" style='line-height:30px;' data-src=''>添加广告</span>
	            <?php else: ?>
	            <span class="span_add btn goto" style='line-height:30px;' data-src='<?php echo url("ad/add_adv"); ?>?id=<?php echo $id; ?>'>添加广告</span>
	            <?php endif; ?>
	        	
	            <span class="span_delete btn ajax-all" data-name="checkbox" data-params='{"url": "<?php echo url("batch_adv"); ?>","data":"","confirm":"true"}'>删除</span>
	            <?php if($adv_count==8): ?><p>PC首页banner限制八张，您已经达到限制</p><?php endif; ?>
	            <div class="clearfix"></div>
	        </div>

	        <div id="list" class="layui-form"></div>
	        <div class="text-right" id="page"></div>
	    </div>
	 </div>
</div>
<script id="list-tpl" type="text/html" data-params='{"url":"<?php echo url("index",["id"=>$id]); ?>","pageid":"#page"}'>
    <table id="example" class="layui-table" lay-skin="line">
        <thead>
        <tr>
            <th style="width: 6%"><input type="checkbox" id="checkall" data-name="checkbox" lay-filter="check" lay-skin="primary"></th>
            <th>轮播图</th>
            <th>标题</th>
            <th>排序</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {{# layui.each(d.list, function(index, item){ }}
        <tr>

            <td>
                <input type="checkbox" name="checkbox" value="{{ item.id}}" lay-skin="primary">
            </td>
            <td><img class="img_cover" src="{{ item.photopath}}" height="60" alt="封面"></td>
            <td>{{ item.title}}</td>
            <td class="icon"><i class="rise ajax" data-params='{"url": "<?php echo url("ad/moveUpDown"); ?>","data":"id={{ item.id}}&updown=up"}'></i><i class="drop ajax" data-params='{"url": "<?php echo url("ad/moveUpDown"); ?>","data":"id={{ item.id}}&updown=down"}'></i></td>
            <td><i class="i_edit goto" data-src='<?php echo url("edit_adv"); ?>?id={{ item.id }}'>编辑</i>／<i class="i_delete ajax" data-params='{"url": "<?php echo url("del_adv"); ?>","data":"id={{ item.id}}","confirm":"true"}'>删除</i></td>
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