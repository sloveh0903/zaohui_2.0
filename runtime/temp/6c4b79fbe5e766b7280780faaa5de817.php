<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:85:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/testitembank/index.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
.layui-form-select dl dd.layui-this{background-color:#00B6F2;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 200px;}
.layui-form-select dl dd{cursor: pointer;width: 200px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;}
</style>
<body>
<div class="article_manage">
	<div class="right-side-header clearfix">
	        <span>题库管理</span>
	        

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
		 		<li><a href="javascript:void(0)" class="goto active" data-src='<?php echo url("testitembank/index"); ?>'>题库</a></li>
		     </ul>
		</div>
	   <div class="course_right_main ">
	         <div class="operation_div">
	         	
	            <span class="span_add btn modal-catch" data-params='{"content":".add-subcat","act":"<?php echo url("testitembank/create"); ?>", "title":"添加题库","type":"1"}'>添加题库</span>
		        
		        <div class="select_search " >
	                <form class="layui-form" action='<?php echo url("index"); ?>'>
	                
	                    <input class="search_btn" type="image" src="/public/gzadmin/images/gray_search@2x20.png" lay-submit name="submit" lay-filter="search" align="" >
	                    <div class="layui-form">
	                        <div class="layui-inline">
	                            <div class="layui-input-inline">
	                                <input class="layui-input" name="key" placeholder="搜索题库名称">
	                            </div>
	                        </div>
	                    </div>

	                </form>
	            </div>
	            <div style="width:auto; float: right;padding-right: 20px;">
		        <form class="layui-form" action='<?php echo url("index"); ?>'>
			    	<div class="layui-input-block">
			    	    <input type="checkbox" name="testitemshopswitch" lay-skin="switch" lay-text="" <?php if($is_testitemshop==1): ?> checked="checked" <?php endif; ?> lay-filter="ajax" data-params='{"url":"<?php echo url("testitembank/testitemshop"); ?>","confirm":"true","data":""}'>
		                <p>题库店铺端显示</p>
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
<div class="add-subcat">
    <form id="form1" class="layui-form layui-form-pane" action='<?php echo url("testitembank/create"); ?>'>
        <div class="layui-form-item">
            <label class="layui-form-label">题库名称*</label>
            <div class="layui-input-inline">
                <input type="text" name="name" required jq-verify="required" jq-error="请输入题库名称" placeholder="标题字数不超过30个字" autocomplete="off" class="layui-input ">
            </div>
        </div>
        <div class="layui-form-item">
              <label class="layui-form-label">选择分类</label>
              <div class="layui-input-inline">
                  <select name="pid" lay-filter="ajax" data-params='{"url": "<?php echo url("get_item"); ?>","data":"","complete":"add_item_id"}'>
                      <option value="0">==请选择==</option>
                      <?php if(!empty($cate)): if(is_array($cate) || $cate instanceof \think\Collection || $cate instanceof \think\Paginator): if( count($cate)==0 ) : echo "" ;else: foreach($cate as $key=>$vo): ?>
	                    <option  value="<?php echo $vo['id']; ?>"><?php if($vo['lvl']==2): ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?><?php echo $vo['cate_name']; ?></option>
	                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                  </select>
              </div>
          </div>
        <div class="layui-form-item">
             <label class="layui-form-label">关联课程</label>
             <div class="layui-input-inline">
                 <select name="course_id"  lay-filter="course_id">
                     <option value="0">==请选择==</option>
                     
                 </select>
             </div>
         </div>
        <div class="layui-form-item">
            <label class="layui-form-label">做题权限*</label>
            <div class="layui-input-inline">
               <select name="privilege" jq-verify="required" jq-error="请输入做题权限" lay-filter="verify">
                		<option  value="1">所有用户可以做题</option>
                		<option  value="3">付费用户可以做题</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <button class="layui-btn" jq-submit jq-filter="submit">确定</button>
            </div>
        </div>
    </form>
</div>
<div class="edit-subcat" style="display: none">
    <form id="form2" class="layui-form layui-form-pane" action='<?php echo url("testitembank/edit"); ?>'>
         <div class="layui-form-item">
            <label class="layui-form-label">题库名称*</label>
            <div class="layui-input-inline">
                <input type="text" name="name" required jq-verify="required" jq-error="请输入题库名称" placeholder="请输入题库名称" autocomplete="off" class="layui-input ">
            </div>
        </div>
        <div class="layui-form-item">
              <label class="layui-form-label">选择分类</label>
              <div class="layui-input-inline">
                  <select name="pid" lay-filter="ajax" data-params='{"url": "<?php echo url("get_item"); ?>","data":"","complete":"edit_item_id"}'>
                      <option value="0">==请选择==</option>
                      <?php if(!empty($cate)): if(is_array($cate) || $cate instanceof \think\Collection || $cate instanceof \think\Paginator): if( count($cate)==0 ) : echo "" ;else: foreach($cate as $key=>$vo): ?>
	                    <option  value="<?php echo $vo['id']; ?>"><?php if($vo['lvl']==2): ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?><?php echo $vo['cate_name']; ?></option>
	                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                  </select>
              </div>
          </div>
        <div class="layui-form-item">
             <label class="layui-form-label">关联课程</label>
             <div class="layui-input-inline">
                 <select name="course_id"  lay-filter="course_id">
                     <option value="0">==请选择==</option>
                     
                 </select>
                 
             </div>
         </div>
        <div class="layui-form-item">
            <label class="layui-form-label">做题权限*</label>
            <div class="layui-input-inline">
                <select name="privilege"  lay-filter="privilege">
                     <option value="0">==请选择==</option>
                     
                 </select>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <button class="layui-btn" jq-submit jq-filter="submit">确定</button>
            </div>
        </div>
    </form>
</div>

<script id="list-tpl" type="text/html" data-params='{"url":"<?php echo url("testitembank/index"); ?>","pageid":"#page"}'>
    <table id="example" class="layui-table" lay-skin="line">
        <thead>
         <tr>
           <th>题库名称</th>
    	   <th>关联课程</th>
    	   <th>做题权限</th>
			<th>创建时间</th>
    	   <th>上架／下架</th>
    	   <th>排序</th>
    	   <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {{# layui.each(d.list, function(index, item){ }}
        <tr class="courselist-tr">
             <td style='width: 210px;'>{{ item.name}}</td>
			 <td style='width: 210px;'>{{ item.course_name}}</td>
			 <td>{{ item.privilege_name}}</td>
			 <td>{{ item.create_time}}</td>
			 <td><input type="checkbox" name="switch" lay-skin="switch" lay-text="" {{#if (item.audit){ }}checked="checked" {{# } }} lay-filter="ajax" data-params='{"url":"<?php echo url("testitembank/state"); ?>","confirm":"true","data":"id={{ item.id}}"}'></td>
			 <td class="icon">
				<i class="rise ajax" data-params='{"url": "<?php echo url("testitembank/moveUpDown"); ?>","data":"id={{ item.id}}&updown=up"}'></i>
				<i class="drop ajax" data-params='{"url": "<?php echo url("testitembank/moveUpDown"); ?>","data":"id={{ item.id}}&updown=down"}'></i>
			</td>
			 <td style="position: relative">
				<a href="javascript:void(0)" class="goto" data-src='<?php echo url("testitemlist/index"); ?>?bank_id={{ item.id}}'>习题管理</a>
				<br>
				 <i class="modal-catch i_edit" data-cid="{{ item.course_id}}" data-pid="{{ item.pid}}" date-prive = "{{ item.privilege}}"  lay-filter="ajax" data-params='{"content": ".edit-subcat","act":"<?php echo url("testitembank/edit"); ?>","title":"编辑  {{ item.name}}  题库","data":"id={{ item.id}}&privilege={{ item.privilege}}&course_id={{ item.course_id}}&pid={{ item.pid}}&name={{ item.name}}","type":"1"}'>编辑</i>
				<i class="i_delete ajax" data-params='{"url": "<?php echo url("testitembank/delelte"); ?>","data":"id={{ item.id}}","confirm":"true"}'>删除</i>
                 <br>
				 <i class="btn_wxcode" data-id="{{ item.id}}" data-url='<?php echo host();?>/wechat/testitembank/do_testitem?bank_id={{ item.id}}' style="cursor: pointer">二维码</i> 
				
					<div class="put-floating-layer" style='min-width: 154px;left: -170px;padding: 0 10px 10px;'>
                     <div class="floating-layer-heading" style='height: 20px;'>
                         <span class="close" style='right:-18px;'>&times;</span>
                     </div>
                     <span class="publicSignal-code">
                            <img src="/public/gzadmin/images/kong-wechat.png" class="publicSignal_code">
                            <p style="text-align: center">扫码进入微信端做题</p>
                     </span>
                     
                 </div>
				
             </td>
        </tr>
        {{# }); }}
        </tbody>
    </table>
    <div class="mincode_img" style="display: none;">
        <img src="" width="200px" height="200px">
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
    layui.use('list');
    //二维码生成
    $(document).on("click",'.btn_wxcode',function(){
        putFloatingLayer = $(this).parent().find(".put-floating-layer");
        display = putFloatingLayer.css("display");
        if("none" ==display ){
            $(".put-floating-layer").hide();
            putFloatingLayer.show();
        }else{
            putFloatingLayer.hide();
        }
        var link = $(this).attr("data-url");
        link = encodeURIComponent(link);
        var course_url = 'http://tool.oschina.net/action/qrcode/generate?data='+link+'&output=image%2Fjpeg&error=L&type=0&margin=8';
        $(this).parent().find('.publicSignal_code').attr('src',course_url);
    });
    $(document).on('click','.close',function(){
        console.log('log');
        var putFloatingLayer = $(this).closest(".put-floating-layer");
        putFloatingLayer.hide();
    });
    //编辑
    $(document).on("click",'.i_edit',function(){
    	 var pid = $(this).attr("data-pid");
    	 var cid = $(this).attr("data-cid");
    	 var prive = $(this).attr("date-prive");
    	 
    	 if(cid==0){
        	 if(prive==1){
            	 var privilegehtml = '<option value="1" selected>所有用户可以做题</option><option value="3" >付费用户可以做题</option>';
             }else{
            	 var privilegehtml = '<option value="1" >所有用户可以做题</option><option value="3" selected>付费用户可以做题</option>';
             }
         }else{
        	 $.ajax({
                 url:"<?php echo url('/admin/testitembank/get_item'); ?>",
                 type:"post",
                 data:{pid: pid},
                 success: function (ret) {
                    	 $('form').find('.edit-subcat select[name=course_id]').html('<option value="0">==请选择==</option>');
                         var form = layui.form();
                         if(ret.length > 0){
                             $.each(ret, function (i, n) {
                            	 if(cid==n.cid){
                             		var proHtml = '<option value="' + n.cid + '" selected>' + n.title + '</option>';
                             	}else{
                             		var proHtml = '<option value="' + n.cid + '">' + n.title + '</option>';
                             	}
                                 $('.edit-subcat  form').find('select[name=course_id]').append(proHtml);
                             });
                           form.render();
                         } 
                 }
                 
             });
        	 if(prive==1){
            	 var privilegehtml = '<option value="1" selected>所有用户可以做题</option><option value="2" >购买关联课程可以做题</option>';
             }else{
            	 var privilegehtml = '<option value="1" >所有用户可以做题</option><option value="2" selected>购买关联课程可以做题</option>';
             }
         }
        
        $('.edit-subcat form').find('select[name=privilege]').html(privilegehtml);
         
    	 
    });
    function edit_item_id(ret, options, $) {
        layui.use(['form'], function(){
            $('.edit-subcat form').find('select[name=course_id]').html('<option value="0">==请选择==</option>');
            var form = layui.form();
            if(ret.length > 0){
                $.each(ret, function (i, n) {
                	var proHtml = '<option value="' + n.cid + '">' + n.title + '</option>';
                    $('.edit-subcat form').find('select[name=course_id]').append(proHtml);
                });
                var privilegehtml = '<option value="1" >所有用户可以做题</option><option value="2" >购买关联课程可以做题</option>';
                $('.edit-subcat form').find('select[name=privilege]').html(privilegehtml);
            }else{
            	 var privilegehtml = '<option value="1" >所有用户可以做题</option><option value="3" >付费用户可以做题</option>';
                 $('.edit-subcat form').find('select[name=privilege]').html(privilegehtml);
            }
           
            form.render();
        });
    }
    //添加
    function add_item_id(ret, options, $) {
        layui.use(['form'], function(){
            $('.add-subcat form').find('select[name=course_id]').html('<option value="0">==请选择==</option>');
            var form = layui.form();
            if(ret.length > 0){
                $.each(ret, function (i, n) {
                	var proHtml = '<option value="' + n.cid + '">' + n.title + '</option>';
                    $('.add-subcat form').find('select[name=course_id]').append(proHtml);
                });
                var privilegehtml = '<option value="1" >所有用户可以做题</option><option value="2" >购买关联课程可以做题</option>';
                $('.add-subcat form').find('select[name=privilege]').html(privilegehtml);
            }else{
            	 var privilegehtml = '<option value="1" >所有用户可以做题</option><option value="3" >付费用户可以做题</option>';
                 $('.add-subcat form').find('select[name=privilege]').html(privilegehtml);
            }
            form.render();
        });
    }

</script>
</body>
</html>

