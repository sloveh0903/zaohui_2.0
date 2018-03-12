<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:87:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/package/edit_package.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
<link rel="stylesheet" href="/public/gzadmin/css/custom-template.css">
<body>
<div class="article_manage mCustomScrollbar">
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
	    <div class="add_article_main ">
	        <div class="breadcrumb">
	              <a href='<?php echo url("package/index"); ?>'>套餐列表</a>
	              /
	              <span>编辑套餐</span>
	         </div>
	         <div class="add_article_step"></div>
	        <div class="articleInfo_fill">
	            <section class="panel panel-padding">
	                <form id="form1" class="layui-form layui-form-pane" action="<?php echo url('edit_package'); ?>">
	                    <div class="layui-form-item">
	                        <label class="layui-form-label">套餐标题*</label>
	                        <div class="layui-input-block">
	                            <input type="hidden" name="id" value="<?php echo $package['id']; ?>" autocomplete="off" class="layui-input ">
	                            <input type="text" name="title" required jq-verify="required" value="<?php echo $package['title']; ?>" jq-error="请输入标题" placeholder="请输入标题" autocomplete="off" class="layui-input ">
	                        </div>
	                    </div>
						<div class="layui-form-item articleInfo_fill">
							<label class="layui-form-label">选择课程*</label>
							<div class="layui-input-inline">
								<div class="choose-course">
									<input type="hidden" name="course_id" class="course_id" value="<?php echo $package['choose_course_id']; ?>">
									<span style="color:#292B33;opacity: 0.6;line-height: 32px;padding-right:5px;">已选择<?php echo $package['course_count']; ?>门课程</span>
									<p style="cursor: pointer;font-size: 14px;color: #00B6F2;line-height: 32px;display: inline-block">选择</p>
								</div>
							</div>
						</div>
						<div class="layui-form-item">
							<label class="layui-form-label">宣传图片</label>
							<div class="layui-input-block">
								<input type="file" name="file" class="layui-upload-file">
								<input type="hidden" value="<?php echo $package['banner']; ?>" name="banner" jq-error="请上传图片" error-id="small-error">
								<p class="upload-info">图片尺寸：750*420 支持格式：JPG PNG</p>
								<p id="small-error" class="error" style="margin-left: 300px;"></p>
							</div>
							<?php if($package['banner']): ?>
							<div class="layui-input-block">
								<div class="imgbox">
									<img src="<?php echo $package['banner']; ?>" name="face" alt="" style="height:60px;">
								</div>
							</div>
							<?php endif; ?>
						</div>
	                    <div class="layui-form-item ">
	                        <label class="layui-form-label">价格*</label>
	                        <div class="layui-input-block">
								<input type="text" name="price" required jq-verify="required" value="<?php echo $package['price']; ?>" jq-error="请输入价格" placeholder="￥" autocomplete="off" autocomplete="off" class="layui-input ">
	                        </div>
	                    </div>

	                    <div class="layui-form-item">
	                        <div class="layui-input-block">
	                            <button class="layui-btn" jq-submit lay-filter="submit">立即提交</button>
<!-- 	                            <button type="reset" class="layui-btn layui-btn-primary">重置</button> -->
	                        </div>
	                    </div>
	                </form>
	            </section>
	        </div>
    	</div>
    </div>
</div>
<div class="dialog">
	<div class="table-box">
		<div class="cross-icon"></div>
		<div class="row">
			<p>选择课程</p>
			<div class="select-box">
				<div class="placeholder-box">选择分类</div>
				<div class="slide-box">
					<div class="slide-item course-link" data-id="0">全部</div>
					<?php if(is_array($top_pid_arr) || $top_pid_arr instanceof \think\Collection || $top_pid_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $top_pid_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
					<div class="slide-item course-link" data-id="<?php echo $vo['id']; ?>"><?php echo $vo['cate_name']; ?></div>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</div>
				<i class="slide-icon"></i>
			</div>
			<div class="search-box" data-id='' id="search-boxid">
				<input type="text" placeholder="搜索..." id='search-boxtext'>
				<i class="search-icon"></i>
			</div>
		</div>
		<div class="table-content">
			<div id='change_banner_html'></div>
			<div class="row operation-row">
				<i class="btn confirm-btn">确认</i>
				<div class="pager">
					<div class="table-pager-list" style="text-align: center">

						<div class="page_div3 paging" onselectstart="return false"
							 style="font-size: 13px; font-family: 微软雅黑; font-weight: 400; height: 32px; width : auto;">
						</div>

					</div>
				</div>
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
<script src="/public/gzadmin/js/jquery-1.11.0.min.js"></script>
<script src="/public/static/js/jquery.paging.js"></script>
<script>
	$('body').on('click',function(){
		$('.slide-box').css('display','none');
	});
    var tmpTag = 'https:' == document.location.protocol ? false : true;
    if(tmpTag){
        var protocol = 'http';
    }else{
        var protocol = 'https';
    }
    var host = protocol+'://' + window.location.host + '/api/';
    var choose_course_id = '<?php echo $package['choose_course_id']; ?>';
    var store_addcousrse = choose_course_id.split(',');
    store_addcousrse = [...new Set(store_addcousrse)];
    
    layui.use('package');
    $('.dialog').on('click', '.select-box', function (e) {
    	e.stopPropagation();
        $(this).find('.slide-box').toggle();
        //console.log(e.target);
    })
    // 课程设置点击选择课程，显示多选框弹窗以及确认按钮
    $('.layui-form-pane').on('click', '.choose-course p', function () {
    	store_addcousrse = [...new Set(store_addcousrse)];
        var title='';var pid=0;
        get_course(title,pid);
        $('.dialog').css('display', 'flex').find('.table-box').addClass('multi').removeClass('single').show().siblings().hide();
        $(this).parents('.choose-course').addClass('modifying');
        $('#search-boxtext').val('');
        $('#search-boxid').attr('data-id','');
        $('.dialog').find('.placeholder-box').html('选择分类');
    })

    // 多课程关联，确定后前端页面改变
    $('.dialog').on('click', '.multi .confirm-btn', function () {
        //
        store_addcousrse = [...new Set(store_addcousrse)];
        if(store_addcousrse.length > 0) {
            var count = store_addcousrse.length;
            $('.modifying').css('display', 'flex').find('span').remove();
            $('<span style="color:#292B33;opacity: 0.6;line-height: 32px;padding-right:5px;">已选择' + count + '门课程</span>').insertBefore($('.modifying p'));
            $('.course_id').val(store_addcousrse.join());
        } else if ($('.dialog .checked').length == 0) {
            $('.choose-course').find('span').remove();
            $('.course_id').val('');
        }
        $('.dialog').hide();
    })

    $('body').on('dragover', function (e) {
        if(e.target.classList.contains('template-content') || e.target.classList.contains('banner-setting') || e.target.classList.contains('add-btn')) {
            $('.tip-box').remove();
            isTipCreated = false;
        }
    })
    //选择分类
    $('.dialog').on('click','.slide-item', function (e) {
        e.stopPropagation();
        $(this).parent().hide();
        var id = $(this).data('id');
        //console.log(id);
        $(this).parent().siblings('.placeholder-box').html($(this).html()).parent().siblings('.search-box').attr('data-id', id);
    })
    //点击搜索
    $('.dialog').on('click','.search-icon', function () {
        pid = $(this).parent().attr('data-id');
        title = $('#search-boxtext').val();
        get_course(title,pid);
    })
    $('.dialog').on('click', '.multi_check', function () {
        $(this).toggleClass('checked');
        store_addcousrse = [...new Set(store_addcousrse)];
        if($(this).attr('id') == 'check_All') {
            $('.multi_check').attr('class', $(this).attr('class'));
            if($('#check_All').hasClass('checked')) {
                $(this).parents('.row').nextAll('.row').find('.multi_check').each(function (index, elem) {
                	var id = $(elem).data('id')+'';
                    store_addcousrse.push(id)
                })
            } else {
                $(this).parents('.row').nextAll('.row').find('.multi_check').each(function (index, elem) {
                	var id = $(elem).data('id')+'';
                    store_addcousrse.splice(store_addcousrse.indexOf(id), 1);
                })
            }

        } else {
            var id = parseInt($(this).attr('data-id'));
            if($(this).hasClass('checked')){
                store_addcousrse.push(id);
            }else{
                store_addcousrse.splice(store_addcousrse.indexOf(id), 1);
            }
        }
        $('.multi_check').each(function (index, elem) {
            if(!elem.classList.contains('checked')) {
                $('#check_All').removeClass('checked');
            }
        })
    })
    $('.dialog').on('click', '.cross-icon, .cencel', function () {
        $('.dialog').hide();
        //title pid 还原
        title = '';
        pid = 0;

    })
    //
    //点击 课程选择 方法
    function get_course(title,pid){
        $(".page_div3").html('');
        var page4 = $(".page_div3").paging({
            submitStyle:"ajax",
            ajaxSubmitType: "get",
            url:host + 'customtemplate/getcourse',
            ajaxData:{title:title,pid:pid},
            ajaxSubmitBack: function (data) {
                var currentpage = data.currentPage;
                changecourselist(data.courselist);
                if(currentpage==1&&data.courselist.length<10)
                {
                    $('.jqpagediv').hide();
                }
            },
        });
        function changecourselist(courselist){
            var temphtml ='';
            var temp_arr = [];
            if(courselist.length==0){
                temphtml =temphtml+'<div><span>暂时无数据..</span></div>';
                $('.jqpagediv').hide();
            }else{
                $('.jqpagediv').show();
                temphtml = '<div class="row table-caption">'+
                    '<div class="course-caption"><p>课程</p></div>'+
                    '<div class="price-caption"><p>价格</p></div>'+
                    '<div class="choose-caption"><i class="multi_check" id="check_All"></i></div>'+
                    '</div>';
                if(courselist.length>0){
                    for (var i = 0; i < courselist.length; i++) {
                        var isset = 1;
                        if(store_addcousrse!=[]){
                            var isset = store_addcousrse.indexOf(courselist[i]['cid']);//-1不存在
                        }
                        //temp_arr.push(courselist[i]['cid']);
                        temphtml =temphtml+'<div class="row table-row">'+
                            '<div class="course-name"><p>'+courselist[i]['title']+'</p></div>'+
                            '<div class="price"><p>¥'+courselist[i]['price']+'</p></div>'+
                            '<div class="choose"><i class="btn check-btn" data-id="'+courselist[i]['cid']+'"></i><i class="multi_check ';
                        if(isset !=-1){
                            temphtml =temphtml+'checked';
                        }
                        temphtml =temphtml+'" data-id="'+courselist[i]['cid']+'"></i></div>'+
                            '</div>';
                    }
                }

            }
            $('#change_banner_html').html(temphtml);
        }
    }
</script>

</body>
</html>