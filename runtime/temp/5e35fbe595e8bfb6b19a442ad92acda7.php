<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:87:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/studentcomment/index.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:86:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/course_bread.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
    .td_content{
        width:200px;
        height:50px;
        border:1px solid red;
        border-top:4px solid red;
        padding:10px;
        overflow:hidden; /*内容超出宽度时隐藏超出部分的内容 */
        text-overflow:ellipsis;/* 当对象内文本溢出时显示省略标记(...) ；需与overflow:hidden;一起使用。*/
        white-space:nowrap; /*不换行 */
    }
</style>
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
<!--             <span class="span_add btn ajax-all" data-name="checkbox" data-params='{"url": "<?php echo url("setAudit_comment"); ?>","data":"","confirm":"true"}'>批量审核</span> -->
            <span class="span_delete btn ajax-all" data-name="checkbox" data-params='{"url": "<?php echo url("delall_comment"); ?>","data":"","confirm":"true"}'>批量删除</span>
<!--             <span class="span_batch_import btn modal-catch"  data-name="checkbox" data-params='{"content":".batch-add","act":"<?php echo url("batchreply"); ?>", "title":"批量回复","type":"1","area":"300px,300px"}'>批量回复</span> -->
            <div class="select_search " >
                <form class="layui-form" action='<?php echo url("comment"); ?>'>
                    <!--<input class="keyword" type="text" name="key" placeholder="收索..">-->
                    <input class="search_btn" type="image" src="/public/gzadmin/images/gray_search@2x20.png" lay-submit name="submit" lay-filter="search" align="" >
                    <div class="layui-form">
                        <div class="layui-inline" style="width: 100px">
                            <select name="audit" lay-verify="required">
                                <option value="2">全部</option>
                                <option value="1">审核</option>
                                <option value="0">未审核</option>
                            </select>
                        </div>
                        <div class="layui-inline">
                            <div class="layui-input-inline">
                                <input class="layui-input" name="key" placeholder="搜索课程名称">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="clearfix"></div>
        	</div>
         </div>
        <div id="list" class="layui-form"></div>
        <div class="text-right" id="page"></div>
    </div>
</div>
<div class="edit-subcat" style="display: none">
    <form id="form2" class="layui-form layui-form-pane" action='<?php echo url("reply"); ?>'>
        <div class="layui-form-item">
           
            <div class="layui-input-inline">
                <textarea name="reply" style="width: 109%" placeholder="请输入回复信息" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" jq-submit jq-filter="submit">确定</button>
            </div>
        </div>
    </form>
</div>
<!-- <div class="batch-add" style="display: none"> -->
<!--     <form id="form3" class="layui-form layui-form-pane" action='<?php echo url("batchreply"); ?>'> -->
<!--          <div class="layui-form-item"> -->
<!--             <div class="layui-input-inline"> -->
<!--                 <textarea name="reply" style="width: 109%" placeholder="请输入回复信息" class="layui-textarea"></textarea> -->
<!--             </div> -->
<!--         </div> -->
<!--         <div class="layui-form-item"> -->
<!--             <div class="layui-input-block"> -->
<!--                 <button class="layui-btn" jq-submit jq-filter="submit">确定</button> -->
<!--             </div> -->
<!--         </div> -->
<!--     </form> -->
<!-- </div> -->
<script id="list-tpl" type="text/html" data-params='{"url":"<?php echo url("comment"); ?>","pageid":"#page"}'>
    <table id="example" class="layui-table" lay-skin="line">
        <thead>
        <tr>
            <th style="width: 6%"><input type="checkbox" id="checkall" data-name="checkbox" lay-filter="check" lay-skin="primary"></th>
            <th>序号</th>
			<th>课程名称</th>
            <th>名称</th>
            <th>评星</th>
            <th>内容</th>
            <th>状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {{# layui.each(d.list, function(index, item){ }}
        <tr>
            <td>
                <input type="checkbox" name="checkbox" value="{{item.id}}" lay-skin="primary">
            </td>
            <td><i title>{{item.id}}</i></td>
			 <td>{{ item.course_name}}</td>
             <td>{{ item.nickname}}</td>
            <td>{{ item.star}}</td>
            <td class="td_content">
						<p style="width:380px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap; " title="{{ item.content}}">{{ item.content}}</p>
						{{#if (item.reply){ }}<p style="width:380px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap; color: rgba(41,43,51,0.5);" title="{{ item.reply}}">回复：{{ item.reply}}</p>{{# } }}
			</td>
            <td><input type="checkbox" name="switch" lay-skin="switch" lay-text="" {{#if (item.audit){ }}checked="checked" {{# } }} lay-filter="ajax" data-params='{"url":"<?php echo url("course/comment_state"); ?>","confirm":"true","data":"id={{ item.id}}"}'></td>
            <td>
                <i class="modal-catch i_edit" data-params='{"content": ".edit-subcat","act":"<?php echo url("reply"); ?>","title":"管理员回复","data":"id={{ item.id}}&reply={{ item.reply}}","type":"1","area":"300px,300px"}'>回复</i>
				<i class="i_delete ajax" data-params='{"url": "<?php echo url("del_comment"); ?>","data":"id={{ item.id}}","confirm":"true"}'>删除</i>
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