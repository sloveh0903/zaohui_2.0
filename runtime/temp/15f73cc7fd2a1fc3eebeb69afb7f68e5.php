<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:77:"/home/wwwroot/lnmp/domain/qusukj/web/application/admin/view/member/index.html";i:1517539928;s:78:"/home/wwwroot/lnmp/domain/qusukj/web/application/admin/view/common/header.html";i:1517539928;s:77:"/home/wwwroot/lnmp/domain/qusukj/web/application/admin/view/common/admin.html";i:1517539928;s:79:"/home/wwwroot/lnmp/domain/qusukj/web/application/admin/view/common/version.html";i:1517539928;}*/ ?>
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
	        <span>用户管理</span>
	        

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
            
        </div>
        
	    <div class="course_right_main ">
	        <div class="operation_div">
	<!--         用户导出 -->
	         <span class="span_batch_import btn modal-catch" data-params='{"content":".batch-export","act":"<?php echo url("exceluser/index"); ?>", "title":"导出","type":"1","area":"550px,400px"}'>导出</span>
	            <!-- <span class="span_delete btn ajax-all" data-name="checkbox" data-params='{"url": "<?php echo url("member/delall_member"); ?>","data":"","confirm":"true"}'>删除</span> -->
	            <div class="select_search " >
	                <form class="layui-form" action='<?php echo url("index"); ?>'>
	                    <input class="search_btn" type="image" src="/public/gzadmin/images/gray_search@2x20.png" lay-submit name="submit" lay-filter="search" align="" >
	                    <div class="layui-form">
	                        <div class="layui-inline" style="width: 100px">
	                            <select name="type" lay-verify="required">
	                                <option value="nickname">昵称</option>
	                                <option value="mobile">手机号</option>
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
    <form id="form2" class="layui-form layui-form-pane" action='<?php echo url("edit_bind"); ?>'>
        <div class="layui-form-item">
            <label class="layui-form-label">昵称</label>
            <div class="layui-input-inline">
                <input type="text" name="nickname" readonly autocomplete="off" class="layui-input ">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">电话号码</label>
            <div class="layui-input-inline">
                <input type="text" name="mobile" jq-verify="phone" jq-error="手机号码错误" autocomplete="off" class="layui-input ">

            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" jq-submit jq-filter="submit">确定</button>
            </div>
        </div>
    </form>
</div>
<div class="edit-integral" style="display: none">
    <form id="form2" class="layui-form layui-form-pane" action='<?php echo url("add_integral"); ?>'>
         <div class="layui-form-item">
             <label class="layui-form-label" style="padding: 8px 18px;">调整积分*</label>
             <div class="layui-input-inline">
                 <input type="radio" name="status" title="增加" value="1"  checked/>
                 <input type="radio" name="status" title="减少" value="0" />
             </div>
         </div>
          <div class="layui-form-item ">
              <label class="layui-form-label"></label>
              <div class="layui-input-inline">
                  <input type="text" name="integral" value="" jq-verify="number" style="width: 80px" autocomplete="off" class="layui-input ">
              </div>
              <label class="layui-form-label"  style="width: auto">积分</label>
          </div>
          <div class="layui-form-item">
            <label class="layui-form-label" style="padding: 8px 18px;">备注</label>
            <div class="layui-input-inline">
                <textarea name="remark" style="width: 109%" placeholder="请输入备注" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" jq-submit jq-filter="submit">确定</button>
            </div>
        </div>
    </form>
</div>
<div class="batch-export" style="display: none">
    <form id="form4" class="layui-form layui-form-pane" >
        <div class="layui-form-item" style="margin-top: 30px;margin-bottom: 50px;">
            <label class="layui-form-label">注册时间</label>
            <div class="layui-input-inline">
            <input type='hidden'  name="star_time" id="star_time" value=''>
              <input class="layui-input" class="star_time" name="star_time" placeholder="开始日" id="LAY_demorange_s">
            </div>
            <div class="layui-input-inline">
            <input type='hidden'  name="end_time" id="end_time" value=''>
              <input class="layui-input" class="end_time" name="end_time"  placeholder="截止日" id="LAY_demorange_e">
            </div>
        </div>
        <br><br>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn"  id='export-submit'>确定</button>
            </div>
        </div>
    </form>
</div>
<script id="list-tpl" type="text/html" data-params='{"url":"<?php echo url("member/index"); ?>","pageid":"#page"}'>
    <table id="example" class="layui-table" lay-skin="line">
        <thead>
        <tr>
  <!--           <th style="width: 6%"><input type="checkbox" id="checkall" data-name="checkbox" lay-filter="check" lay-skin="primary"></th> -->
            <th>头像/昵称</th>
            <th>手机号</th>
            <th>积分</th>
            <th>性别</th>
            <th>消费总金额</th>
            <th>创建时间</th>
            <th>禁用／启用</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {{# layui.each(d.list, function(index, item){ }}
        <tr>
<!--             <td>
                <input type="checkbox" name="checkbox" value="{{ item.uid}}" lay-skin="primary">
            </td> -->
            <td>
                <img class="commenter" src="{{ item.face}}" alt="{{ item.nickname}}">
                <i class="userNick">{{ item.nickname}}</i>
            </td>
            <td>{{ item.mobile}}</td>
            <td>{{ item.now_integral}}</td>
            <td>{{ item.sex}}</td>
            <td>￥{{ item.total}}</td>
            <td>{{ item.create_time}}</td>
            <td><input type="checkbox" name="switch" lay-skin="switch" lay-text="" {{#if (item.audit){ }}checked="checked" {{# } }} lay-filter="ajax" data-params='{"url":"<?php echo url("member/member_state"); ?>","confirm":"true","data":"uid={{ item.uid}}"}'></td>
            <td><i class="modal-catch i_edit" data-params='{"content": ".edit-subcat","act":"<?php echo url("edit_bind"); ?>","title":"绑定","data":"uid={{ item.uid}}&mobile={{ item.mobile}}&nickname={{ item.nickname}}","type":"1","area":"500px,300px"}'>绑定</i>／<i class="modal-catch i_edit" data-params='{"content": ".edit-integral","act":"<?php echo url("add_integral"); ?>","title":"调整积分","data":"uid={{ item.uid}}","type":"1","area":"500px,400px"}'>调整积分</i>／ <i class="i_edit goto" data-src='<?php echo url("member/detail"); ?>?uid={{ item.uid}}'>详情</i></td>
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
<script>
layui.use('laydate', function(){
  var laydate = layui.laydate;
  var start = {
    min: '2015-06-16 23:59:59'
    ,max: laydate.now()
    ,istoday: false
    ,choose: function(datas){
      end.min = datas; //开始日选好后，重置结束日的最小日期
      end.start = datas; //将结束日的初始值设定为开始日
      //赋值（pp） 查询报名时间
      document.getElementById("star_time").value= datas;
    }
  };
  var end = {
    min: '2015-06-16 23:59:59'
    ,max: laydate.now()
    ,istoday: false
    ,choose: function(datas){
      start.max = datas; //结束日选好后，重置开始日的最大日期
    //赋值（pp） 查询报名时间
    document.getElementById("end_time").value=datas;
    }
  };  
  document.getElementById('LAY_demorange_s').onclick = function(){
    start.elem = this;
    laydate(start); 
  }
  document.getElementById('LAY_demorange_e').onclick = function(){
    end.elem = this
    laydate(end); 
  } 
});
 document.getElementById('export-submit').onclick = function(){
    var star_time = $('#star_time').val();
    var end_time =$('#end_time').val();
    if(typeof(star_time) == 'undefined'){
        star_time = '';
    }
    if(typeof(end_time) == 'undefined'){
        end_time = '';
    }
    var host = window.location.host;
    var url = 'http://'+host+'/admin/exceluser/index.html?star_time='+star_time+'&end_time='+end_time;
    window.open(url)
  }

</script>
</body>
</html>