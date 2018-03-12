<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:85:"/home/wwwroot/lnmp/domain/qusukj/web/application/admin/view/wechatkeywords/index.html";i:1517539928;s:78:"/home/wwwroot/lnmp/domain/qusukj/web/application/admin/view/common/header.html";i:1517539928;s:77:"/home/wwwroot/lnmp/domain/qusukj/web/application/admin/view/common/admin.html";i:1517539928;s:79:"/home/wwwroot/lnmp/domain/qusukj/web/application/admin/view/common/version.html";i:1517539928;}*/ ?>
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
                <li><a href="<?php echo url('wechatkeywords/index'); ?>" class="active">关键字回复</a></li>
                <li><a href="<?php echo url('wechatkeywords/customer'); ?>" >微信客服设置</a></li>
                <li><a href="<?php echo url('operate/follow'); ?>">公众号关注引导</a></li>
            </ul>
        </div>
		<div class="success_tip displayNone">已完成</div>
	    <div class="add_article_main ">
	    </div>
	    <div class="course_right_main">
	        <div class="operation_div">
	            <span class="span_add btn goto" data-src='<?php echo url("wechatkeywords/create"); ?>'>添加关键词</span>
	        </div><br /><br />
	        <div id="list" class="layui-form"></div>
	        <div class="text-right" id="page"></div>
	    </div>
	  </div>
	</div>
	
    <script id="list-tpl" type="text/html" data-params='{"url":"<?php echo url("wechatkeywords/index"); ?>","pageid":"#page"}'>
    <table id="example" class="layui-table" lay-skin="line">
        <thead>
        <tr>
            <th>关键词</th>
            <th>匹配类型</th>
            <th>回复类型</th>
            <th width="40%">回复内容</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
           {{# layui.each(d.list, function(index, item){ }}
        <tr>
            <td>{{item.keywords}}</td>
            <td>
                {{# if(item.type == '1'){ }}
                精准
                {{# }else{ }}
                模糊
                {{# } }}
            </td>
            <td>
                {{# if(item.msgtype == '0'){ }}
                文字
                {{# }else{ }}
                图片
                {{# } }}
            </td>
            <td>
                {{# if(item.msgtype == '0'){ }}
                {{ item.content}}
                {{# }else{ }}
                <img src="{{ item.photopath}}" style="width: 60px;height: auto">
                {{# } }}
            </td>
            <td>
                <i class="goto i_edit" data-src='<?php echo url("wechatkeywords/update"); ?>?id={{ item.id }}'>编辑</i>／
                <i class="i_delete ajax" data-params='{"url": "<?php echo url("wechatkeywords/delete"); ?>","confirm":"true","data":"id={{ item.id}}"}'>删除</i>
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
    <script src="/public/gzadmin/js/jquery-1.11.0.min.js"></script>
    <script src="/public/gzadmin/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="/public/gzadmin/js/placeholder.js"></script>
    <script src="/public/gzadmin/js/public_PC.js"></script>
    <script>
          layui.use('list');
    </script>     
</body>
</html>