<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:85:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/order/packageorder.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
	        <span>订单管理</span>
	        

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
                 <li><a href="javascript:void(0)" class="goto" data-src='<?php echo url("order/index"); ?>'>课程订单</a></li>
                 <li><a href="javascript:void(0)" class="goto" data-src='<?php echo url("order/ucardorder"); ?>'>会员卡订单</a></li>
                 <li><a href="javascript:void(0)" class="goto active" data-src='<?php echo url("order/packageorder"); ?>'>套餐订单</a></li>
             </ul>
         </div>
		
	    <div class="course_right_main ">
	        <div class="operation_div">
                <img class="img_add modal-catch" data-params='{"content":".add-subcat","act":"<?php echo url("add_package"); ?>", "title":"添加套餐订单","type":"1","area":"500px,500px"}' src="/public/gzadmin/images/img_add@2x32.png" alt="添加图片">
                <span class="span_batch_import btn modal-catch" data-params='{"content":".batch-add","act":"<?php echo url("excel/packageimport"); ?>", "title":"批量导入","type":"1","area":"480px,360px"}'>批量导入</span>
                <span class="download_template"><a href="/packageTpl.xlsx">下载模板</a></span>
	            <div class="select_search " >
	                <form class="layui-form" action='<?php echo url("order/packageorder"); ?>'>
	                    <input class="search_btn" type="image" src="/public/gzadmin/images/gray_search@2x20.png" lay-submit name="submit" lay-filter="search" align="" >
	                    <div class="layui-form">
	                        <div class="layui-inline">
	                            <div class="layui-input-inline" style="width: 120px;">
	                                <input type='hidden'  name="start_date" id="start_date" value=''>
              						<input class="layui-input" class="start_date" name="start_date" placeholder="开始日" id="LAY_start_date_s">
	                            </div>
	                            <div class="layui-input-inline" style="width: 120px;">
 									<input type='hidden'  name="end_date" id="end_date" value=''>
              						<input class="layui-input" class="end_date" name="end_date" placeholder="开始日" id="LAY_end_date_e">
	                            </div>
	                        </div>
	                        <div class="layui-inline" style="width: 100px">
	                            <select name="source">
	                                <option value="0">来源</option>
	                                <option value="import">导入</option>
	                                <option value="pc">PC端</option>
	                                <option value="wechat">公众号</option>
	                                <option value="miniwechat">小程序</option>
	                            </select>
	                        </div>
	                        <div class="layui-inline" style="width: 100px">
	                            <select name="type">
	                                <option value="">请选择</option>
	                                <option value="mobile">手机号</option>
	                                <option value="order_sn">订单号</option>
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
<div class="add-subcat">
    <form id="form1" class="layui-form layui-form-pane" action='<?php echo url("add_package"); ?>'>
        <div class="layui-form-item">
            <label class="layui-form-label">选择套餐</label>
            <div class="layui-input-inline">
                <select name="package_id" jq-verify="required" jq-error="请选择套餐" lay-filter="verify">
                    <option value=""></option>
                    <?php if(!empty($package)): if(is_array($package) || $package instanceof \think\Collection || $package instanceof \think\Paginator): if( count($package)==0 ) : echo "" ;else: foreach($package as $key=>$v): ?>
                    <option value="<?php echo $v['id']; ?>"><?php echo $v['title']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">手机号码</label>
            <div class="layui-input-inline">
                <input type="text" placeholder="请输入手机号码" jq-verify="required|phone" jq-error="手机号码错误" name="mobile" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">购买时间</label>
            <div class="layui-input-inline">
                <div class="layui-input-inline">
                    <input class="layui-input normal-date" jq-verify="required" name="create_time" placeholder="购买时间">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
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
<div class="batch-add" style="display: none">
    <form id="form3" class="layui-form layui-form-pane" action='<?php echo url("excel/packageimport"); ?>'>
        <div class="layui-form-item">
            <label class="layui-form-label">选择套餐</label>
            <div class="layui-input-inline">
                <select name="id" jq-verify="required" jq-error="请选择套餐" lay-filter="verify">
                    <option value=""></option>
                    <?php if(!empty($package)): if(is_array($package) || $package instanceof \think\Collection || $package instanceof \think\Paginator): if( count($package)==0 ) : echo "" ;else: foreach($package as $key=>$v): ?>
                    <option value="<?php echo $v['id']; ?>"><?php echo $v['title']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">上传excel</label>
            <div class="layui-input-block">
                <input type="file" name="file" class="layui-upload-file" lay-title="上传文件"  id="upload-excel">
                <input type="hidden" name="excel_path" error-id="img-error">
            </div>
            <div><p id="img-error" class="error"></p></div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" jq-submit jq-filter="submit">确定</button>
            </div>
        </div>
    </form>
</div>
<div class="edit-subcat" style="display: none">
    <form id="form2" class="layui-form layui-form-pane" action='<?php echo url("edit_package"); ?>'>
        <div class="layui-form-item">
            <label class="layui-form-label">手机号码</label>
            <div class="layui-input-inline">
                <input type="text" placeholder="请输入手机号码" readonly jq-verify="phone" jq-error="手机号码错误" name="mobile" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">购买时间</label>
            <div class="layui-input-inline">
                <div class="layui-input-inline">
                    <input class="layui-input" readonly name="create_time" id="create_time" placeholder="购买时间">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">到期时间</label>
            <div class="layui-input-inline">
                <div class="layui-input-inline">
                    <input class="layui-input expire_time" jq-verify="required" name="expire_time" id="expire_time" placeholder="到期时间">
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-inline">
                <textarea name="remark" style="width: 109%" placeholder="请输入备注" class="layui-textarea remark"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" jq-submit jq-filter="submit">确定</button>
            </div>
        </div>
    </form>
</div>
<script id="list-tpl" type="text/html" data-params='{"url":"<?php echo url("order/packageorder"); ?>","pageid":"#page"}'>
    <table id="example" class="layui-table" lay-skin="line">
        <thead>
        <tr>
            <th>订单号</th>
            <th>套餐名称</th>
            <th>昵称</th>
            <th>来源</th>
            <th>支付金额</th>
            <th>报名时间</th>
            <th>到期时间</th>
            <th>备注</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {{# layui.each(d.list, function(index, item){ }}
        <tr>
            <td>
                {{ item.order_sn}}
            </td>
            <td>
                <i class="userNick">{{ item.package_name}}</i>
            </td>
            <td>
                {{ item.nickname}}<br>{{ item.mobile}}
            </td>
            <td>
                {{ item.source_text}}
            </td>
            <td>
                <i>¥{{ item.pay_price}}<br><i style="color:red;font-size: smaller;">{{ item.couponcode_text}}</i></i>
            </td>
            <td>
                {{ item.createtime}}
            </td>
            <td>
                {{ item.expire_time}}
            </td>
            <td style="position: relative;">
                <img class="help" src="/public/gzadmin/images/remark.png" alt="帮助"/>
                <div class="float_div float_div_right" style="word-wrap:break-word;">
                                            <div class="float_box">
		        							<p style="height: auto;">备注：{{ item.remark}}</p>
											<span></span>
                                            </div>
		        						</div>
            </td>
            <td><i class="modal-catch i_edit" data-remark="{{ item.remark}}" data-params='{"content": ".edit-subcat","act":"<?php echo url("edit_order"); ?>","title":"编辑订单","data":"id={{ item.id}}&create_time={{ item.createtime}}&expire_time={{ item.expire_time}}&mobile={{ item.mobile}}","type":"1"}'>编辑</i></td>
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
      //赋值  查询报名时间
      //document.getElementById("star_time").value= datas;
    }
  };
  var end = {
    min: '2015-06-16 23:59:59'
    ,max: laydate.now()
    ,istoday: false
    ,choose: function(datas){
      start.max = datas; //结束日选好后，重置开始日的最大日期
    //赋值 查询报名时间
    //document.getElementById("end_time").value=datas;
    }
  };

  //查询报名时间
  var start_date = {
    min: '2015-06-16 23:59:59'
    ,max: laydate.now()
    ,istoday: false
    ,choose: function(datas){
      end_date.min = datas; //开始日选好后，重置结束日的最小日期
      end_date.start = datas; //将结束日的初始值设定为开始日
      //赋值  查询报名时间
      document.getElementById("star_date").value= datas;
    }
  };
  var end_date = {
    min: '2015-06-16 23:59:59'
    ,istoday: false
    ,choose: function(datas){
      start_date.max = datas; //结束日选好后，重置开始日的最大日期
    //赋值 查询报名时间
    document.getElementById("end_date").value=datas;
    }
  };
  
  document.getElementById('LAY_start_date_s').onclick = function(){
    start_date.elem = this;
    laydate(start_date); 
  }
  document.getElementById('LAY_end_date_e').onclick = function(){
    end_date.elem = this;
    laydate(end_date);  
  }
  var create_time  = {
	      min: '2001-01-11 23:59:59'
	      ,max: laydate.now()
	      ,istoday: false
	      ,choose: function(datas){
	          expire_time.min = datas; //开始日选好后，重置结束日的最小日期
	          //expire_time.start = datas; //将结束日的初始值设定为开始日
	          //赋值  查询报名时间
	      }
	  };
	    var expire_time = {
	        min: '2001-01-11 23:59:59'
	        ,max: '2099-06-16 23:59:59'
	        ,istoday: false
	        ,choose: function(datas){
	            create_time.max = datas; //结束日选好后，重置开始日的最大日期
	            //赋值 查询报名时间
	        }
	    };
	    $('.create_time').click(function() {
	        create_time.max = $('#expire_time').val();
	        create_time.elem = this;
	        laydate(create_time);
	    })
	    $('.expire_time').click(function() {
	        expire_time.min = $('#create_time').val();
	        expire_time.elem = this;
	        laydate(expire_time);
	    })	  
});
//鼠标经过备注图标，float_box出现
$("#list").on("mouseover",'.help', function(){
	if($(this).parents('tr').index() == $('#example tbody').find('tr').length - 1) {
		$(this).siblings(".float_last_div").show();
		$('#example tbody').find('tr').last().find('.float_div').removeClass('float_div float_div_right').addClass('float_last_div');
		$('.float_last_div').css({'bottom':'40px','right':'30px'}) ;
	}else{
		$(this).siblings(".float_div").show();
	}
	
	
})
$("#list").on("mouseleave",'.help', function(){
	$(this).siblings(".float_div").hide()
	$(this).siblings(".float_last_div").hide()
});
$(document).on("click",'.i_edit',function(){
    var form = layui.form();
    var remark = $(this).attr("data-remark");
    $('.edit-subcat').find('.remark').val(remark);
    form.render();
})

</script>
</body>
</html>