<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/question/index.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:86:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/course_bread.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
	        <span>知识店铺</span>
	        

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
 		<li><a href="javascript:void(0)" class="goto <?php if($course_bread==2): ?> active <?php endif; ?> " data-src='<?php echo url("course/index_cate"); ?>'>课程分类</a></li>
	    <li><a href="javascript:void(0)" class="goto <?php if($course_bread==1): ?> active <?php endif; ?>" data-src='<?php echo url("course/index"); ?>'>课程列表</a></li>
         <li><a href="javascript:void(0)" class="goto <?php if($course_bread==3): ?> active <?php endif; ?> " data-src='<?php echo url("Studentcomment/index"); ?>'>学员评价</a></li>
         <li><a href="javascript:void(0)" class="goto <?php if($course_bread==4): ?> active <?php endif; ?> " data-src='<?php echo url("question/index"); ?>'>问答中心</a></li>
     </ul>
</div>
<div class="success_tip displayNone">已完成</div>
	    <div class="course_right_main ">
	        <div class="operation_div">
	            <span class="span_delete btn ajax-all" data-name="checkbox" data-params='{"url": "<?php echo url("delall_ask"); ?>","data":"","confirm":"true"}'>删除</span>
	             <span class="modal-catch i_edit" style="background: white;color: rgba(41, 43, 51, .8);line-height: 28px;width:60px;cursor: pointer" data-params='{"content":".edit-subcat","act":"<?php echo url("question/edit_switch"); ?>","data":"is_showask=<?php echo $is_showask; ?>&is_canask=<?php echo $is_canask; ?>", "title":"权限设置","type":"1"}'>权限设置</span>
	            <div class="select_search " >
	                <form class="layui-form" action='<?php echo url("index"); ?>'>
	                    <input class="search_btn" type="image" src="/public/gzadmin/images/gray_search@2x20.png" lay-submit name="submit" lay-filter="search" align="" >
	                    <div class="layui-form">
	                        <div class="layui-inline" style="width: 150px">
	                            <select name="status">
	                                <option value="">全部状态</option>
	                                <option value="1">精选</option>
	                                <option value="2">未精选</option>
	                                <option value="3">回复</option>
	                                <option value="4">未回复</option>
	                            </select>
	                        </div>
	                        <div class="layui-inline" style="width: 100px">
	                            <select name="type">
	                                <option value="content">问题</option>
	                                <option value="nickname">昵称</option>
	                            </select>
	                        </div>
	                        <div class="layui-inline">
	                            <div class="layui-input-inline">
	                                <input class="layui-input" name="key" placeholder="关键字">
	                            </div>
	                        </div>
	                    </div>
	                </form>
	            </div>
	            <div class="clearfix"></div>
	        </div>
	        <div id="list" class="layui-form"></div>
	        <div class="text-right" id="page"></div>
	    </div>
	</div>
</div>
<div class="edit-subcat" style="display: none">
    <form id="form1" class="layui-form layui-form-pane" action='<?php echo url("question/edit_switch"); ?>'>
       <div class="layui-input-inline" style='margin-left:0px;margin-bottom:20px;width:500px;'>
			<div style='float:left;font-size: 14px;'>
		          <span>店铺端显示</span><p style='color:rgba(41, 43, 51, .6);font-size: 12px;'>关闭后问答不会再店铺端显示</p>
	        </div>
	   	    <div style='float:left;margin-left:100px;'>
				<input type="checkbox" name="is_showask" value="1" lay-skin="switch" lay-text="">
	        </div>  
        </div> 
        <div class="layui-input-inline" style='margin-left:0px;margin-bottom:20px;width:400px;'>
			<div style='float:left;font-size: 14px;'>
		          <span>提问/回复权限</span><p style='color:rgba(41, 43, 51, .6);font-size: 12px;'>开启后用户可以针对所有课程提问和回复</p>
	        </div>
	   	    <div style='float:left;margin-left:40px;'>
				<input type="checkbox" name="is_canask" value="1" lay-skin="switch" lay-text="">
	        </div>  
        </div>
		<div class="layui-form-item">
			<div class="layui-input-block">
				<button class="layui-btn" jq-submit jq-filter="submit">确定</button>
			</div>
		</div>
    </form>
</div>

<script id="list-tpl" type="text/html" data-params='{"url":"<?php echo url("question/index"); ?>","pageid":"#page"}'>
    <table id="example" class="layui-table" lay-skin="line">
        <thead>
        <tr>
            <th style="width: 6%"><input type="checkbox" id="checkall" data-name="checkbox" lay-filter="check" lay-skin="primary"></th>
            <th>头像/昵称</th>
            <th style="width: 28%">问题</th>
            <th>提问时间</th>
            <th>浏览数</th>
            <th>评论数</th>
            <th>精华</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {{# layui.each(d.list, function(index, item){ }}
        <tr>
            <td>
                <input type="checkbox" name="checkbox" value="{{ item.id}}" lay-skin="primary">
            </td>
            <td>
                <img class="commenter" src="{{ item.face}}" alt="{{ item.nickname}}">
                <i class="userNick">{{ item.nickname}}</i>
            </td>
            <td>{{ item.content}}</td>
            <td>{{ item.create_time}}</td>
            <td>{{ item.views}}</td>
            <td class="ask"><i {{#if (item.comments == 0){ }}class="active" {{# } }}>{{ item.comments}}</i></td>
            <td><img class="best ajax" {{#if (item.hot){ }}src="/public/gzadmin/images/essence@2x21x20.png" alt="普通" {{# }else{ }}src="/public/gzadmin/images/commom@2x21x20.png" alt="精华" {{# } }} lay-filter="ajax" data-params='{"url":"<?php echo url("ask_hot"); ?>","data":"id={{ item.id}}"}'></td>
            <td>
                <i class="goto i_edit" data-src='<?php echo url("repay/index"); ?>?id={{ item.id }}'>详情</i>／<i class="i_delete ajax" data-params='{"url": "<?php echo url("del_ask"); ?>","confirm":"true","data":"id={{ item.id}}"}'>删除</i>
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