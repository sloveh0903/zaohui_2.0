<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:78:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/ad/edit_adv.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
<div class="article_manage mCustomScrollbar">
	<div class="right-side-header clearfix">
	        <span>系统管理</span>
	        

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
	        <div class="breadcrumb">
	              <a href='<?php echo url("ad/index",["id"=>$advInfo["id"]]); ?>'>广告列表</a>
	              /
	              <span>编辑广告</span>
	         </div>
	        <div class="ad_operation_div">
	            <div class="add_article_step">
	            	<ul class="editCourse-nav-ul clearFloat">
	                	<!-- <li data-src='<?php echo url("ad/index"); ?>?id=1'class="goto <?php if($advInfo['id']==1): ?> active <?php endif; ?>"><i data-name="index">小程序首页</i></li>
	                	<li data-src='<?php echo url("ad/index"); ?>?id=3' class="goto <?php if($advInfo['id']==3): ?> active <?php endif; ?>" ><i data-name="pc_index">pc首页</i></li> -->
	            	</ul>
	        	</div>
	        </div>
	        <div class="articleInfo_fill">
	            <section class="panel panel-padding">
	                <form id="form1" class="layui-form layui-form-pane" action="<?php echo url('edit_adv'); ?>">
	                    <div class="layui-form-item">
	                        <label class="layui-form-label">标题*</label>
	                        <div class="layui-input-inline">
	                            <input type="hidden" name="id" value="<?php echo $itemInfo['id']; ?>">
	                            <input type="hidden" name="adv_id" value="<?php echo $itemInfo['adv_id']; ?>">
	                            <input type="text" name="title" value="<?php echo $itemInfo['title']; ?>" required jq-verify="required" jq-error="请输入标题" placeholder="请输入标题" autocomplete="off" class="layui-input ">
	                        </div>
	                    </div>
	                    <div class="layui-form-item">
	                        <label class="layui-form-label">上传图片</label>
	                        <div class="layui-input-block">
	                            <input type="file" name="file" class="layui-upload-file">
	                            <input type="hidden" name="photopath" value="<?php echo $itemInfo['photopath']; ?>" jq-verify="required" jq-error="请上传图片" error-id="img-error">
	                            <p class="upload-info">图片尺寸：1180*413 支持格式：JPG PNG</p>
	                            <p id="img-error" class="error"></p>
	                        </div>
	                        <div class="layui-input-block">
	                            <div class="imgbox">
	                                <img src="<?php echo $itemInfo['photopath']; ?>" alt="..." class="img-thumbnail">
	                            </div>
	                        </div>
	                    </div>
	                    <?php if($advInfo['id']==1): ?>
	                    <div class="layui-form-item">
	                        <label class="layui-form-label">课程选择</label>
	                        <div class="layui-input-inline">
	                            <select name="item_id"  lay-filter="item_id">
	                                <option value="">==请选择==</option>
	                                <?php if(!empty($data)): if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): if( count($data)==0 ) : echo "" ;else: foreach($data as $key=>$vo): ?>
	                                <option value="<?php echo $vo['id']; ?>" <?php if($vo['id'] == $itemInfo['item_id']): ?>selected<?php endif; ?>><?php echo $vo['title']; ?></option>
	                                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
	                            </select>
	                        </div>
	                    </div>
	                    <?php else: ?>
	                    <div class="layui-form-item" >
	                        <label class="layui-form-label">自定义链接</label>
	                        <div class="layui-input-inline" style="width: 50%;">
	                            <input type="text" name="link" required jq-error="请输入完整url链接" value='<?php echo $itemInfo['link']; ?>'  placeholder="<?php echo $itemInfo['link']; ?>" autocomplete="off" class="layui-input ">
	                        </div>
	                    </div>
	                    
	                    <?php endif; ?>
	                    <!--<div class="layui-form-item">
	                        <label class="layui-form-label">启用</label>
	                        <div class="layui-input-inline">
	                            <input type="radio" name="closed" value="0" title="是" <?php if($itemInfo['closed'] == '0'): ?>checked<?php endif; ?>>
	                            <input type="radio" name="closed" value="1" title="否" <?php if($itemInfo['closed'] == '1'): ?>checked<?php endif; ?>>
	                        </div>
	                    </div>-->
<!-- 	                    <div class="layui-form-item"> -->
<!-- 	                        <label class="layui-form-label">排序</label> -->
<!-- 	                        <div class="layui-input-inline"> -->
<!-- 	                            <input class="layui-input" type="text" name="orderby" value="<?php echo $itemInfo['orderby']; ?>" placeholder="排序"  lay-verify="number"> -->
<!-- 	                        </div> -->
<!-- 	                    </div> -->
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
<script>
    layui.use('adv');
    function add_item_id(ret, options, $) {
        console.log(ret);
        layui.use(['form'], function(){
            $('form').find('select[name=item_id]').html('<option>==请选择==</option>');
            var form = layui.form();
            if(ret.length > 0){
                $.each(ret, function (i, n) {
                    var proHtml = '<option value="' + n.id + '">' + n.title + '</option>';
                    $('form').find('select[name=item_id]').append(proHtml);
                });
            }
            form.render();
        });
    }
</script>

</body>
</html>