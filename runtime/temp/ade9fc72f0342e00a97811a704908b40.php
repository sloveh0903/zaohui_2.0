<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:58:"C:\php\zaohui_2.0/application/admin\view\course\index.html";i:1520849330;s:59:"C:\php\zaohui_2.0/application/admin\view\common\header.html";i:1519891268;s:58:"C:\php\zaohui_2.0/application/admin\view\common\admin.html";i:1519891268;s:65:"C:\php\zaohui_2.0/application/admin\view\common\course_bread.html";i:1519891268;s:60:"C:\php\zaohui_2.0/application/admin\view\common\version.html";i:1519891268;}*/ ?>
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
<style type="text/css">
    .mincode_img{
        position: fixed;
        top: 50%;
        left: 50%;
        background-color: #fff;
        width:250px;
        height: 250px;
        -webkit-transform: translateX(-50%) translateY(-50%);
        border:solid 1px #292B33;
        padding: 23px;
    }
    .put-floating-layer{
    	height:460px;
    }
</style>
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
	            <span class="span_add btn span_add_course goto" data-src='<?php echo url("course/create"); ?>'>添加课程</span>
	            <div class="select_search " >
	
	                <form class="layui-form" action='<?php echo url("index"); ?>'>
	                    <!--<input class="keyword" type="text" name="key" placeholder="收索..">-->
	                    <input class="search_btn" type="image" src="/public/gzadmin/images/gray_search@2x20.png" lay-submit name="submit" lay-filter="search" align="" >
	                    <div class="layui-form">
							<div class="layui-inline" style="width: 120px">
								<select name="insur_switch" lay-verify="required">
									<option value="1">insur开启</option>
									<option value="0">insur关闭</option>
								</select>
							</div>
	                        <div class="layui-inline" style="width: 100px">
	                            <select name="audit" lay-verify="required">
	                                <option value="1">上架</option>
	                                <option value="0">下架</option>
	                            </select>
	                        </div>
	                        <div class="layui-inline">
	                            <select name="pid">
	                                <option value="">请选择分类</option>
	                                <?php foreach($catelist as $vo): ?>
	                                <option value="<?php echo $vo['id']; ?>"><?php echo $vo['cate_name']; ?></option>
	                                <?php endforeach; ?>
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
	         <div id="list" class="layui-form">
	         </div> 
			 <div class="text-right" id="page"></div>
	 
	      </div>
   	</div>
</div>

<script id="list-tpl" type="text/html" data-params='{"url":"<?php echo url("course/index"); ?>","pageid":"#page"}'>
    <table id="example" class="layui-table" lay-skin="line">
        <thead>
        <tr>
           <th>封面</th>
    			 <th>名称</th>
    			 <th>价格</th>
			     <th>insur价格</th>
			     <th>insur支付开关</th>
    			 <th>上架／下架</th>
    			 <th>排序</th>
    			 <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {{# layui.each(d.list, function(index, item){ }}
        <tr class="courselist-tr">
             <td><img class="img_cover" src="{{ item.banner}}" height="30" alt="封面"></td>
			 <td>{{ item.title}}</td>
			 <td>{{ item.price}}</td>
			 <td>{{ item.insur_price}}</td>
			 <td><input type="checkbox" name="insur_switch" lay-skin="switch" lay-text="" {{#if (item.insur_switch){ }}checked="checked" {{# } }} lay-filter="ajax" data-params='{"url":"<?php echo url("course/course_insur_state"); ?>","confirm":"true","data":"id={{ item.cid}}"}'></td>
			 <td><input type="checkbox" name="switch" lay-skin="switch" lay-text="" {{#if (item.audit){ }}checked="checked" {{# } }} lay-filter="ajax" data-params='{"url":"<?php echo url("course/course_state"); ?>","confirm":"true","data":"id={{ item.cid}}"}'></td>
			 <td class="icon"><i class="rise ajax" data-params='{"url": "<?php echo url("course/moveUpDown"); ?>","data":"id={{ item.cid}}&updown=up"}'></i><i class="drop ajax" data-params='{"url": "<?php echo url("course/moveUpDown"); ?>","data":"id={{ item.cid}}&updown=down"}'></i></td>
			 <td style="position: relative"><i class="i_edit goto" data-src='<?php echo url("course/edit_course"); ?>?cid={{ item.cid }}'>编辑</i> <i class="btn_wxcode" data-id="{{ item.cid}}" style="cursor: pointer">投放</i> <i class="i_delete ajax" data-params='{"url": "<?php echo url("course/del_course"); ?>","data":"id={{ item.cid}}","confirm":"true"}'>删除</i>
                 <div class="put-floating-layer">
                     <div class="floating-layer-heading">
                         <span class="close">&times;</span>
                     </div>
                     <div class="floating-layer-body clearFloat">
                         <span class="publicSignal-code">
                             <i>公众号二维码</i>
                             <img src="/public/gzadmin/images/kong-wechat.png" class="publicSignal_code">
                         </span>
                         <span class="smallProgram-code">
                             <i>小程序码</i>
                             <img src="/public/gzadmin/images/kong-miniprom.png" class="smallProgram_code">
                         </span>
                     </div>
                     <div class="floating-layer-footer mt20">
                         <p>课程链接：</p>
                         <span id="course-addr{{ item.cid}}" class="copy-dom course-link-addr"  style="width: 228px;"><?php echo host();?>/wechat/course/detail?cid={{ item.cid}}</span>
                         <span class="btn-success copy-link-addr" data-clipboard-action="copy" data-clipboard-target="#course-addr{{ item.cid}}">复制链接</span>
                     </div>
					 <div class="floating-layer-footer mt20">
						 <p>小程序链接：</p>
						 <span id="mincode-addr{{ item.cid}}" class="copy-dom course-link-addr"  style="width: 228px;">/pages/course/detail?cid={{ item.cid}}</span>
						 <span class="btn-success copy-link-addr" data-clipboard-action="copy" data-clipboard-target="#mincode-addr{{ item.cid}}">复制链接</span>
					 </div>
                 </div>
             </td>
        </tr>
        {{# }); }}
        </tbody>
    </table>
    <div class="mincode_img" style="display: none;">
        <img src="" width="200px" height="200px">
    </div>
    <div class="add-subcat">
</div>
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
<script src="/public/gzadmin/js/clipboard.min.js"></script>
<script>
    var host = window.location.host;
    layui.use('list');
    $(document).on("click",'.mincode_img',function(){
        $('.mincode_img').hide();
    });
    $(document).on("click",'.btn_wxcode',function(){
        putFloatingLayer = $(this).parent().find(".put-floating-layer");
        display = putFloatingLayer.css("display");
        if("none" ==display ){
            $(".put-floating-layer").hide();
            putFloatingLayer.show();
        }else{
            putFloatingLayer.hide();
        }
        var link = $(this).parent().find('.course-link-addr').text();
        link = encodeURIComponent(link);
        var course_url = 'http://tool.oschina.net/action/qrcode/generate?data='+link+'&output=image%2Fjpeg&error=L&type=0&margin=8';
        $(this).parent().find('.publicSignal_code').attr('src',course_url);

        var cid = $(this).attr('data-id');
        var url = 'http://'+host+'/api/wechat/wxacode?cid='+cid;
        var smallProgram_code = $(this).parent().find('.smallProgram_code');
        $.get(url, function(result){
            if(result.code == 1){
            	console.log(result.data);
                smallProgram_code.attr('src',result.data);
            }
        });
    });
    $(document).on('click','.close',function(){
        var putFloatingLayer = $(this).closest(".put-floating-layer");
        putFloatingLayer.hide();
    });
    var clipboard = new Clipboard('.copy-link-addr');
    clipboard.on('success', function(e) {
        console.log(e);
        layer.msg('复制成功');
    });
    /*$(document).on('click','.copy-link-addr',function () {
        var link = decodeURI($(this).prev('span.course-link-addr').text());

        var clipboard = new Clipboard('.copy-link-addr', {
            text: function() {
                return link;
            }
        });
        /!*window.clipboardData.setData("Text",$(this).prev('span.course-link-addr').html());*!/
    });*/
</script>
</body>
</html>

