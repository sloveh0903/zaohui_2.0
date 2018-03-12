<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/couponcode/index.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
.layui-form-select dl dd{cursor: pointer;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;width: 200px;}
.layui-form-select dl dd.layui-this{background-color:#00B6F2;width: 200px;}
</style>
<body>
<div class="article_manage">
	<div class="right-side-header clearfix">
	        <span>营销中心</span>
	        

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
		 		<li><a href="javascript:void(0)" class="goto active" data-src='<?php echo url("couponcode/index"); ?>'>优惠码管理</a></li>
		     </ul>
		</div>
	   <div class="course_right_main ">
	         <div class="operation_div">
	         	<span class="span_add btn modal-catch" data-params='{"content":".add-subcat","act":"<?php echo url("couponcode/create"); ?>", "title":"添加优惠码","type":"1","area":"650px,570px"}'>添加优惠码</span>
		        <div class="select_search " style='bottom: 10px;'>
                    <form class="layui-form" action='<?php echo url("index"); ?>'>
                        <input class="search_btn" type="image" src="/public/gzadmin/images/gray_search@2x20.png" lay-submit name="submit" lay-filter="search" align="" >

                        <div class="layui-form">
                            <div class="layui-inline" style="width: 120px">
                                <select name="coupon_type" lay-verify="required">
                                    <option value="0">类型</option>
                                    <option value="1">关联课程</option>
                                    <option value="3">VIP课程</option>
                                    <option value="4">套餐课程</option>
                                    <option value="2">全站通用</option>

                                </select>
                            </div>
                            <div class="layui-inline">
                                <div class="layui-input-inline">
                                    <input class="layui-input" name="key" placeholder="优惠码名称">
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

<script id="list-tpl" type="text/html" data-params='{"url":"<?php echo url("couponcode/index"); ?>","pageid":"#page"}'>
    <table id="example" class="layui-table" lay-skin="line">
        <thead>
         <tr>
          
    	   <th>优惠码名称</th>
    	   <th>适用范围</th>
			<th>优惠码</th>
    	   <th>优惠方式</th>
    	   <th>可用数量</th>
		   <th>有效期</th>
		   <th>状态</th>
    	   <th>操作</th>
        </tr>
        </thead>
        <tbody>
        {{# layui.each(d.list, function(index, item){ }}
        <tr>
             <td style='width: 210px;'>{{ item.name}}</td>
			<td style='width: 210px;'>{{ item.coupon_type_name}}{{#if (item.item_name){ }} <br><span style='color: gray;'> {{ item.item_name}} </span> {{# } }}</td>
			<td style='width: 30px;'>{{ item.coupon_code}}</td>
			<td style='width: 110px;'>{{ item.discount_type_name}}</td>
			<td style='width: 180px;'> {{ item.use_number_name}}</td>
			<td style='width: 210px;'>
									起：{{ item.start_time}}<br>
									至：{{ item.end_time}}
			</td>
			 <td style='width: 110px;'><input type="checkbox" name="switch" lay-skin="switch" lay-text="" {{#if (item.audit){ }}checked="checked" {{# } }} lay-filter="ajax" data-params='{"url":"<?php echo url("couponcode/state"); ?>","confirm":"true","data":"id={{ item.id}}"}'></td>
			 
			 <td style="width: 217px;position: relative">
				<a href="javascript:void(0)" class="goto" data-src='<?php echo url("couponcode/uselist"); ?>?id={{ item.id}}'>使用情况</a>
				
				 <i class="modal-catch i_edit" data-cid="{{ item.item_id}}" data-type="{{ item.coupon_type}}" data-discounttype="{{ item.discount_type}}"  data-discount="{{ item.discount}}" data-usenumber="{{ item.use_number}}"  lay-filter="ajax" data-params='{"content": ".edit-subcat","act":"<?php echo url("couponcode/edit"); ?>","title":"编辑  {{ item.name}}  优惠码","data":"id={{ item.id}}&course_id={{ item.course_id}}&discount_type={{ item.discount_type}}&coupon_type={{ item.coupon_type}}&name={{ item.name}}&discount={{ item.discount}}&start_time={{ item.start_time}}&end_time={{ item.end_time}}","type":"1","area":"650px,570px"}'>编辑</i>
				<i class="i_delete ajax" data-params='{"url": "<?php echo url("couponcode/delelte"); ?>","data":"id={{ item.id}}","confirm":"true"}'>删除</i>
             </td>
        </tr>
        {{# }); }}
        </tbody>
    </table>
</script>
<!-- 添加优惠券 -->
<div class="add-subcat">
    <form id="form1" class="layui-form layui-form-pane" action='<?php echo url("couponcode/create"); ?>'>
        <div class="layui-form-item">
             <label class="layui-form-label">优惠码名称*</label>
             <div class="layui-input-block">
                 <input type="text" name="name" required jq-verify="required" jq-error="请输入优惠码名称" placeholder="请输入优惠码名称" value="" autocomplete="off" class="layui-input ">
             </div>
         </div>
        <div class="layui-form-item">
              <label class="layui-form-label">适用范围*</label>
              <div class="layui-input-inline">
                  <input type="radio" name="coupon_type" lay-filter="coupon_type" title="全站通用" value="2" checked />
                  <input type="radio" name="coupon_type" lay-filter="coupon_type" title="课程" value="1"  />
                  <input type="radio" name="coupon_type" lay-filter="coupon_type" title="VIP" value="3"  />
                  <input type="radio" name="coupon_type" lay-filter="coupon_type" title="套餐" value="4"  />
              </div>
          </div>
        <div class="layui-form-item" id='coursestyle' style="display: none">
             <label class="layui-form-label scopeTitle">关联课程</label>
             <div class="layui-input-inline">
                 <select name="item_id">
                     <option></option>
                 </select>
             </div>
         </div>
         <div class="layui-form-item">
              <label class="layui-form-label">优惠方式</label>
              <div class="layui-input-inline">

                  <input type="radio" name="discount_type" lay-filter="discount_type" title="打折" value="1" checked />
                  <input type="radio" name="discount_type" lay-filter="discount_type" title="抵扣" value="2" />
                  <input type="radio" name="discount_type" lay-filter="discount_type" title="免费" value="0"  />
                  <!--<select name="discount_type" lay-filter="ajax" data-params='{"url": "<?php echo url("get_discount_type"); ?>","data":"","complete":"add_discount_type_id"}'>
                       <option  value="0" selected>免费</option>
                       <option  value="1">打折</option>
                       <option  value="2" >抵价</option>
                  </select>-->
              </div>
          </div>
          <div class="layui-form-item" id='discountstyle'>
             <label class="layui-form-label" id='discountname'></label>
             <div class="layui-input-inline">
                 <input type="number" name="discount"  jq-verify="discount"  jq-error="折扣必须在1-10之间" placeholder="" value="" autocomplete="off" class="layui-input discount">

             </div>
              <div class="beizhu"><p style="font-size: 14px;line-height: 32px">折</p></div>
         </div>
         <div class="layui-form-item">
           <label class="layui-form-label">可使用数量*</label>
           <div class="layui-input-inline">
               <input type="number" name="use_number" required jq-verify="number" jq-error="可使用数量为非负整数" placeholder="留空则为不受限制" value="" autocomplete="off" class="layui-input ">
           </div>
         </div>
                
	    <div class="layui-form-item">
           <label class="layui-form-label">开始时间</label>
           <div class="layui-input-inline">
             <input class="layui-input" required jq-verify="required"  class="star_time" name="start_time" placeholder="开始日" id="LAY_demorange_s">
           </div>
        </div>
        <div class="layui-form-item">
           <label class="layui-form-label">结束时间</label>
           <div class="layui-input-inline">
             <input class="layui-input" required jq-verify="required" class="end_time" name="end_time"  placeholder="截止日" id="LAY_demorange_e">
           </div>
       </div>
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <button class="layui-btn" jq-submit jq-filter="submit">确定</button>
            </div>
        </div>
    </form>
</div>
<!-- 编辑优惠码 -->
<div class="edit-subcat" style="display: none">
    <form id="form2" class="layui-form layui-form-pane" action='<?php echo url("couponcode/edit"); ?>'>
         <div class="layui-form-item">
             <label class="layui-form-label">优惠码名称*</label>
             <div class="layui-input-block">
                 <input type="text" name="name" required jq-verify="required" jq-error="请输入优惠码名称" placeholder="请输入优惠码名称" value="" autocomplete="off" class="layui-input ">
             </div>
         </div>
        <div class="layui-form-item">
              <label class="layui-form-label">优惠码类型</label>
              <div class="layui-input-inline">
                  <input type="radio" name="coupon_type" lay-filter="edit_coupon_type" title="全站通用" value="2" />
                  <input type="radio" name="coupon_type" lay-filter="edit_coupon_type" title="课程" value="1" />
                  <input type="radio" name="coupon_type" lay-filter="edit_coupon_type" title="VIP" value="3" />
                  <input type="radio" name="coupon_type" lay-filter="edit_coupon_type" title="套餐" value="4" />
              </div>
          </div>
        <div class="layui-form-item" id='edit_coursestyle'>
             <label class="layui-form-label scopeEditTitle">关联课程</label>
             <div class="layui-input-inline">
                 <select name="item_id" class="editScope"  lay-filter="item_id">
                     <option></option>
                 </select>
             </div>
         </div>
        <div class="layui-form-item">
            <label class="layui-form-label">优惠方式</label>
            <div class="layui-input-inline">
                <input type="radio" name="discount_type" lay-filter="edit_discount_type" title="打折" value="1" />
                <input type="radio" name="discount_type" lay-filter="edit_discount_type" title="抵扣" value="2" />
                <input type="radio" name="discount_type" lay-filter="edit_discount_type" title="免费" value="0" />
                <!--<select name="discount_type" lay-filter="ajax" data-params='{"url": "<?php echo url("get_discount_type"); ?>","data":"","complete":"add_discount_type_id"}'>
                     <option  value="0" selected>免费</option>
                     <option  value="1">打折</option>
                     <option  value="2" >抵价</option>
                </select>-->
            </div>
        </div>
         <div class="layui-form-item" style='display:none;' id='edit_discountstyle'>
             <label class="layui-form-label" id='edit_discountname'></label>
             <div class="layui-input-inline">
                 <input type="discount" name="discount" id='discountid' jq-verify="discount"  jq-error="折扣必须在1-10之间" placeholder="" value="" autocomplete="off" class="layui-input edit_discount">
             </div>
             <div class="editbeizhu"><p style="font-size: 14px;line-height: 32px">折</p></div>
         </div>
       <div class="layui-form-item">
           <label class="layui-form-label">可使用数量*</label>
           <div class="layui-input-inline">
               <input type="number" name="use_number" id='usenumberid' required jq-verify="number" jq-error="可使用数量为非负整数"  placeholder="输入0为不受限制" value="" autocomplete="off" class="layui-input ">
           </div>
       </div>
                
	 <div class="layui-form-item">
           <label class="layui-form-label">开始时间</label>
           <div class="layui-input-inline">
             <input class="layui-input" required jq-verify="required"  class="star_time" name="start_time" placeholder="开始日" id="LAY_demorange_edit_s">
           </div>
        </div>
        <div class="layui-form-item">
           <label class="layui-form-label">结束时间</label>
           <div class="layui-input-inline">
             <input class="layui-input" required jq-verify="required" class="end_time" name="end_time"  placeholder="截止日" id="LAY_demorange_edit_e">
           </div>
       </div>
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <button class="layui-btn" jq-submit jq-filter="submit">确定</button>
            </div>
        </div>
    </form>
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
<script src="/public/gzadmin/js/clipboard.min.js"></script>
<script>
    layui.use('list');
    layui.use('laydate', function(){
  	  var laydate = layui.laydate;
  	  var start = {
  	    min: laydate.now()
  	    ,max: '2055-06-16 23:59:59'
  	    ,istoday: false
  	    ,choose: function(datas){
  	      end.min = datas; //开始日选好后，重置结束日的最小日期
  	      end.start = datas; //将结束日的初始值设定为开始日
  	      //赋值  查询报名时间
  	      //document.getElementById("star_time").value= datas;
  	    }
  	  };
  	  var end = {
  	    min: laydate.now()
  	    ,max:'2055-06-16 23:59:59'
  	    ,istoday: false
  	    ,choose: function(datas){
  	      start.max = datas; //结束日选好后，重置开始日的最大日期
  	    //赋值 查询报名时间
  	    //document.getElementById("end_time").value=datas;
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
  	
  	document.getElementById('LAY_demorange_edit_s').onclick = function(){
  	    start.elem = this;
  	    laydate(start); 
  	  }
  	  document.getElementById('LAY_demorange_edit_e').onclick = function(){
  	    end.elem = this
  	    laydate(end);  
  	  }
  	  
  	});
    //添加    优惠码类型  关联课程
    function add_coupon_type_id(ret, options, $) {
    	if(ret==1){
        	$('#coursestyle').css('display','block');
        }else{
        	$('#coursestyle').css('display','none');
        }
    }
    //添加  折扣类型   折扣
    function add_discount_type_id(ret, options, $) {
        if(ret==0){
        	$('#discountstyle').css('display','none');
        }else if(ret==1){
        	$('#discountname').html('折扣');
        	$('#discountstyle').css('display','block');
        }
        else{
        	$('#discountname').html('抵价');
        	$('#discountstyle').css('display','block');
        }
    }
  //编辑
    $(document).on("click",'.i_edit',function(){
         console.log('aa');
    	 var cid = $(this).attr("data-cid");
    	 var type = $(this).attr("data-type");
    	 var discounttype =$(this).attr("data-discounttype");
    	 var discount = $(this).attr("data-discount");
    	 var use_number = $(this).attr("data-usenumber");
    	 console.log(type);
    	 var title = '';
    	 if(cid==0){
    		 $('#edit_coursestyle').css('display','none');
         }else{
    	     switch (type){
                 case "1":
                     title = "课程";
                     break;
                 case "3":
                     title = "VIP";
                     break;
                 case "4":
                     title = "套餐";
                     break;
             }
             var form = layui.form();
             $.ajax({
                 url:"<?php echo url('/admin/couponcode/getTypeData'); ?>",
                 type:"get",
                 data:{
                     type: type
                 },
                 success: function (ret) {
                     console.log(ret);
                     $('.scopeEditTitle').html('关联'+title);
                     if(ret.code == "1"){
                         $('form').find('.editScope').html('<option>==请选择'+title+'==</option>');
                         if(ret.data.length > 0){
                             $.each(ret.data, function (i, n) {
                                 if(n.id == cid){
                                     var proHtml = '<option value="' + n.id + '" selected>' + n.title + '</option>';
                                 }else{
                                     var proHtml = '<option value="' + n.id + '">' + n.title + '</option>';
                                 }
                                 $('form').find('.editScope').append(proHtml);
                             });
                         }
                         form.render();
                     }
                 }
             });
         }

        if(discounttype == 0){
            $('.edit_discount').attr('jq-verify','');
            $('#edit_discountstyle').css('display','none');
            $('.editbeizhu').find('p').html('');
        }else if(discounttype == 1){
            $('#edit_discountstyle').css('display','block');
            $('.edit_discount').attr('jq-verify','discount');
            $('.editbeizhu').find('p').html('折');
        }else if(discounttype == 2){
            $('#edit_discountstyle').css('display','block');
            $('.edit_discount').attr('jq-verify','price');
            $('.editbeizhu').find('p').html('元');
        }
         $('#usenumberid').val(use_number);
    	 
    });
  
  //编辑    优惠码类型  关联课程
    function edit_coupon_type_id(ret, options, $) {
    	if(ret==1){
        	$('#edit_coursestyle').css('display','block');
        }else{
        	$('#edit_coursestyle').css('display','none');
        }
    }
    //编辑  折扣类型   折扣
    function edit_discount_type_id(ret, options, $) {
        if(ret==0){
        	$('#edit_discountstyle').css('display','none');
        }else if(ret==1){
        	$('#edit_discountname').html('折扣');
        	$('#edit_discountstyle').css('display','block');
        }
        else{
        	$('#edit_discountname').html('抵价');
        	$('#edit_discountstyle').css('display','block');
        }
    }

    layui.use(['form'], function(){
        var form = layui.form();
        form.on('radio(coupon_type)', function(data){
            if(data.value==2){
                $('#coursestyle').css('display','none');
            }else{
                $('#coursestyle').css('display','block');
            }
            $.ajax({
                url:"<?php echo url('/admin/couponcode/getTypeData'); ?>",
                type:"get",
                data:{
                    type: data.value
                },
                success: function (ret) {
                    console.log(ret);
                    $('.scopeTitle').html('关联'+data.elem.title);
                    if(ret.code == "1"){
                        $('form').find('select[name=item_id]').html('<option>==请选择'+data.elem.title+'==</option>');
                        if(ret.data.length > 0){
                            $.each(ret.data, function (i, n) {
                                var proHtml = '<option value="' + n.id + '">' + n.title + '</option>';
                                $('form').find('select[name=item_id]').append(proHtml);
                            });
                        }
                        form.render();
                    }
                }
            });

            console.log(data.elem.title); //得到radio原始DOM对象
            console.log(data.value); //被点击的radio的value值
        });

        form.on('radio(discount_type)', function(data){
            if(data.value == 0){
                $('#discountstyle').css('display','none');
                $('.discount').attr('jq-verify','');
                $('.beizhu').find('p').html('');
            }else if(data.value == 1){
                $('#discountstyle').css('display','block');
                $('.discount').attr('jq-verify','discount');
                $('.beizhu').find('p').html('折');

            }else if(data.value == 2){
                $('#discountstyle').css('display','block');
                $('.discount').attr('jq-verify','price');
                $('.beizhu').find('p').html('元');
            }
        });

        //编辑优惠码
        form.on('radio(edit_coupon_type)', function(data){
            if(data.value==2){
                $('#edit_coursestyle').css('display','none');
            }else{
                $('#edit_coursestyle').css('display','block');
            }
            $.ajax({
                url:"<?php echo url('/admin/couponcode/getTypeData'); ?>",
                type:"get",
                data:{
                    type: data.value
                },
                success: function (ret) {
                    console.log(ret);
                    $('.scopeEditTitle').html('关联'+data.elem.title);
                    if(ret.code == "1"){
                        $('form').find('.editScope').html('<option>==请选择'+data.elem.title+'==</option>');
                        if(ret.data.length > 0){
                            $.each(ret.data, function (i, n) {
                                var proHtml = '<option value="' + n.id + '">' + n.title + '</option>';
                                $('form').find('.editScope').append(proHtml);
                            });
                        }
                        form.render();
                    }
                }
            });
            console.log(data.elem); //得到radio原始DOM对象
            console.log(data.value); //被点击的radio的value值
        });
        form.on('radio(edit_discount_type)', function(data){
            if(data.value == 0){
                $('.edit_discount').attr('jq-verify','');
                $('#edit_discountstyle').css('display','none');
                $('.editbeizhu').find('p').html('');
            }else if(data.value == 1){
                $('#edit_discountstyle').css('display','block');
                $('.edit_discount').attr('jq-verify','discount');
                $('.editbeizhu').find('p').html('折');
            }else if(data.value == 2){
                $('#edit_discountstyle').css('display','block');
                $('.edit_discount').attr('jq-verify','price');
                $('.editbeizhu').find('p').html('元');
            }
        });
    });


</script>
</body>
</html>

