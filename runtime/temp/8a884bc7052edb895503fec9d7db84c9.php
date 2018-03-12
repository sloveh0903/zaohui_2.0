<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/recharge/index.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
	        <span>我的充值</span>
	        

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
                <li><a href="javascript:void(0)" class="active goto" data-src='<?php echo url("recharge/index"); ?>'>充值</a></li>
                <li><a href="javascript:void(0)" class="goto" data-src='<?php echo url("recharge/statistic"); ?>'>流量与存储</a></li>
            </ul>
        </div>
        <div class="success_tip displayNone">已完成</div>
	     <div class="articleInfo_fill">
		        <div class="operation_div">
		            <div class="recharge-header mt30 clearFloat">
		                <p style="float: left;">
		                    <span style='width:auto;'>当前余额 <?php echo $money; ?> 元</span>
		                    <i>如退款请联系客服</i>
		                </p>
		           
		                <span class="btn-info span_add btn btn-recharge" style="float: right">充值</span>
		            </div>
		            <div class="clearfix"></div>
		        </div>
		        <div id="list" class="layui-form"></div>
		        <div class="text-right" id="page"></div>
		    </div>
    </div>
	
</div>
<script id="list-tpl" type="text/html" data-params='{"url":"<?php echo url("recharge/index"); ?>","pageid":"#page"}'>
    <table id="example" class="layui-table" lay-skin="line">
        <thead>
        <tr>
            <th>充值时间</th>
            <th>充值金额</th>
            <th>状态</th>
        </tr>
        </thead>
        <tbody>
        {{# layui.each(d.list, function(index, item){ }}
        <tr>
            <td>{{ item.create_time}}</td>
            <td>{{ item.price}}</td>
            <td>
                {{#if (item.pay_status){ }}
                成功
                {{# }else{ }}
                失败
                {{# } }}
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
<div class="modal-bms recharge-modal">
    <div class="modal-dialog-bms">
        <div class="modal-header">
            <h5>充值</h5>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body mt20">
            <input class="add-chapter-input price" type="text" name="price" placeholder="最低充值100元">
        </div>
        <div class="modal-footer mt20">
            <span class="pay-title">支付方式</span>
<!--             <a class="pay-weixin" target="_blank"></a> -->
            <a class="pay-alipay" target="_blank"></a>
        </div>
    </div>
</div>
<div class="modal-bms recharge-tip-modal">
    <div class="modal-dialog-bms">
        <div class="modal-header">
            <h5>充值提示</h5>
            <span class="close">&times;</span>
        </div>
        <div class="modal-body mt20">
            <p class="course-delete-text">充值完成后请点击刷新按钮查看结果</p>
        </div>
        <div class="modal-footer mt40">
            <span class="btn-primary">刷新</span>
        </div>
    </div>
</div>
<script>
    layui.use('list');
</script>
<script>
    $(function() {
        $('.btn-recharge').on('click',function () {
            $('.recharge-modal').css('display','flex');
        });
        $('.recharge-modal').on('click','.close',function () {
            $('.recharge-modal').hide();
        });
        $('.pay-weixin').on('click',function () {
            $('.recharge-modal').hide();
            $('.recharge-tip-modal').css('display','flex');
            $.ajax({
                url: "<?php echo url('/admin/recharge/create'); ?>",
                type: "post",
                async:false,
                data:{
                    price:$('.price').val(),
                    type:1
                },
                success: function (data) {
                    if(data.status == 200){
                        window.open(data.data.string);
                        //newTab.location.href=data.data.string;
                    }
                }
            });
        });
        $('.recharge-tip-modal').on('click','.close',function () {
            $('.recharge-tip-modal').hide();
        });
        $('.pay-alipay').on('click',function () {
            $('.recharge-modal').hide();
            $('.recharge-tip-modal').css('display','flex');
            $.ajax({
                url: "<?php echo url('/admin/recharge/create'); ?>",
                type: "post",
                async:false,
                data:{
                    price:$('.price').val(),
                    type:2
                },
                success: function (data) {
                    if(data.status == 200){
                        window.open(data.data.string);
                        // var tempwindow=window.open();
                        // tempwindow.location=data.data.string;
                    }
                }
            });
        });
        $('.btn-primary').on('click',function () {
            $('.recharge-tip-modal').hide();
            location.reload();
        });

    });
</script>
</body>
</html>