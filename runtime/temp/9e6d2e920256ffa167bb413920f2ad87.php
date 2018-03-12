<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:57:"C:\php\zaohui_2.0/application/admin\view\index\index.html";i:1519891268;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>后台管理系统</title>
    <link rel="stylesheet" href="/public/gzadmin/css/jquery.mCustomScrollbar.min.css">
    <link rel="stylesheet" href="/public/gzadmin/css/all.css">
</head>
<body>
<div class="public-box">

	<div class="public-box-left">
		<div class="side-header">
			<a href='<?php echo url("index"); ?>'>
				<img src="/public/gzadmin/images/grazy_logo@2x.png" class="logo_bms">
			</a>
		</div>
		<!--目录-->
		<div class="side-body content mCustomScrollbar _mCS_1">
			<div class="up_nav_div mCustomScrollBox">
				<ul class="up_nav_ul mCSB_container">
					<li class="one-level active leftmenu" data-src='<?php echo url("index/center"); ?>'>
						<h2>
							<i class="icon light"></i>
							<span><a href="javascript:void(0)">概况</a></span>
						</h2>
					</li>
					<li  class="one-level ">
						<h2>
							<i class="icon" ></i>
							<span><a href="javascript:void(0)">店铺管理</a></span>
							<i class="point" style="background: url(/public/gzadmin/images/select.png) no-repeat center/cover;"></i>
						</h2>
						<ul class="two-level ">
							<li><a href="javascript:void(0)" class="leftmenu" data-src='<?php echo url("admin/customtemplate/index"); ?>'>移动首页自定义</a></li>
							<li><a href="javascript:void(0)" class="leftmenu" data-src='<?php echo url("admin/index/config"); ?>'>网站设置</a></li>
							<li><a href="javascript:void(0)" class="leftmenu" data-src='<?php echo url("admin/ad/index"); ?>'>广告管理</a></li>
							
						</ul>

					</li>
					<li class="one-level">
						<h2>
							<i class="icon" ></i>
							<span><a href="javascript:void(0)">知识管理</a></span>
							<i class="point" style="background: url(/public/gzadmin/images/select.png) no-repeat center/cover"></i>
						</h2>
						<ul class="two-level">
							<li><a href="javascript:void(0)" class="leftmenu" data-src='<?php echo url("admin/course/index"); ?>'>课程管理</a></li>
							<li><a href="javascript:void(0)" class="leftmenu" data-src='<?php echo url("admin/usercard/index"); ?>'>VIP卡管理</a></li>
							<li><a href="javascript:void(0)" class="leftmenu" data-src='<?php echo url("admin/package/index"); ?>'>套餐管理</a></li>
						</ul>
					</li>
					<li class="one-level leftmenu" data-src='<?php echo url("admin/testitembank/index"); ?>'>
						<h2>
							<i class="icon"></i>
							<span><a href="javascript:void(0)">题库管理</a></span>
						</h2>
					</li>
					<li class="one-level leftmenu" data-src='<?php echo url("admin/member/index"); ?>'>
						<h2>
							<i class="icon"></i>
							<span><a href="javascript:void(0)">用户管理</a></span>
						</h2>
					</li>
					<li class="one-level">
						<h2>
							<i class="icon" ></i>
							<span><a href="javascript:void(0)" data-src="">营销中心</a></span>
							<i class="point" style="background: url(/public/gzadmin/images/select.png) no-repeat center/cover;"></i>
						</h2>
						<ul class="two-level">
						    <li><a href="javascript:void(0)" class="leftmenu" data-src='<?php echo url("admin/couponcode/index"); ?>'>优惠码</a></li>
							<li><a href="javascript:void(0)" class="leftmenu" data-src='<?php echo url("admin/integral/index"); ?>'>积分营销</a></li>
							<li><a href="javascript:void(0)" class="leftmenu" data-src='<?php echo url("admin/distribution/index"); ?>'>全员分销</a></li>
						</ul>
					</li>
					<li class="one-level leftmenu" data-src='<?php echo url("admin/order/index"); ?>'>
						<h2>
							<i class="icon"></i>
							<span><a href="javascript:void(0)">订单管理</a></span>
						</h2>
					</li>
					
					<li class="one-level we-chat">
						<h2>
							<i class="icon"></i>
							<span><a href="javascript:void(0)">微信设置</a></span>
							<i class="point " style="background: url(/public/gzadmin/images/select.png) no-repeat center/cover;"></i>
						</h2>
						<ul class="two-level">
							<li><a href="javascript:void(0)" class="leftmenu" data-src='<?php echo url("admin/operate/index"); ?>'>公众号配置</a></li>
							<li><a href="javascript:void(0)" class="leftmenu" data-src='<?php echo url("admin/operate/applet"); ?>'>小程序配置</a></li>
							<li><a href="javascript:void(0)" class="leftmenu" data-src='<?php echo url("admin/operate/openplatform"); ?>'>开放平台配置</a></li>
						</ul>
					</li>
					<li class="one-level leftmenu" data-src='<?php echo url("admin/statistics/index"); ?>'>
						<h2>
							<i class="icon"></i>
							<span><a href="javascript:void(0)">数据统计</a></span>
						</h2>
					</li>
					
					<li class="one-level leftmenu" data-src='<?php echo url("admin/user/index"); ?>'>
						<h2>
							<i class="icon"></i>
							<span><a href="javascript:void(0)">系统设置</a></span>
						</h2>
					</li>
					<li class="one-level leftmenu" data-src='<?php echo url("admin/recharge/index"); ?>'>
						<h2>
							<i class="icon"></i>
							<span><a href="javascript:void(0)">我的充值</a></span>
						</h2>
					</li>
					<li class="one-level help-center">
						<h2>
							<i class="icon"></i>
							<span><a href="http://www.grazy.cn/help" target="_blank">帮助中心</a></span>
						</h2>
					</li>

				</ul>
			</div>
		</div>
	</div>

    <div class="public-box-right">
        <iframe src="<?php echo url('index/center'); ?>" frameborder="0" id="iframe" style="height:100%;visibility:inherit; width:100%;z-index:1;"></iframe>
    </div>
</div>
<!-- tr批量删除弹框提示end -->
<script src="/public/gzadmin/js/jquery-1.11.0.min.js"></script>
<script src="/public/gzadmin/js/public_PC.js"></script>
<script type="text/javascript" src="/public/gzadmin/js/jquery.mCustomScrollbar.js" ></script>
<script src="/public/gzadmin/js/jquery.mousewheel.js"></script>
<script>
    $(function(){
        initHeight();
    })
    //浏览器改变触发该事件
    //初始化height
    function initHeight(){
        var window_height =  $(window).height();

        var topHeight = $(".course_right_top").outerHeight();
        $(".course_left").outerHeight( window_height );
        $(".course_right").outerHeight( window_height );
        console.log(window_height)
    }


    var h=$(window).innerHeight();
    var s=h-70;//头部为70px
    $(".content").css("height",s);
    //	alert($(document).height());
    //一级导航
    //鼠标经过图标点亮函数
	/*$(".one-level").mouseenter(function(){
        $(this).find("i.icon").addClass("light")
    })
    //鼠标离开图标变暗
	$(".one-level").mouseleave(function(){
        var state=$(this).hasClass("active")
        //alert(state)
        if(state==true){
            $(this).find("i.icon").addClass("light")
            $(this).siblings().find("i.icon").removeClass("light")
        }else{
            $(this).find("i.icon").removeClass("light")
        }
    })*/

    //一级导航下拉按钮有无判断
    var one=$(".one-level")
    $(one).each(function(){
        var src = $(this).find("h2 a").attr("data-src");
        //frame(src);
        $(this).click(function(){
            var le=$(this).children(".two-level").children("li").length;
            if(le<=0){
                $(this).children("h2").children("i.point").css("background","none");
                //frame(src);
            }
            //点击图标点亮
//			$(this).addClass("active").siblings().removeClass("active");
            //$(this).find("i.icon").addClass("light")
//			$(this).siblings().find("i.icon").removeClass("light")
        })
    })

    $(".one-level h2").click(function(){

        var length=$(this).siblings(".two-level").length
//		alert(length)
        if(length==0){
            $(this).parent().addClass("active").siblings().removeClass("active")
            $(this).find("i.icon").addClass("light")
            $(this).parent().siblings().find("i.icon").removeClass("light")
            $(this).parent().siblings().children("h2").removeClass('on_bg')
            $(this).parent().siblings().children(".two-level").css("display","none")
            $(this).parent().siblings().children(".two-level").children("li").removeClass("cur")
            $(this).parent().siblings().find("i.point").css("background","url(/public/gzadmin/images/select.png) no-repeat center/cover");
        }else{
            var state=$(this).siblings(".two-level").css("display");
            //alert(state)
            if(state=="none"){
                $(this).siblings(".two-level").show();
                $(this).children("i.point").css("background","url(/public/gzadmin/images/selected.png) no-repeat center/cover");
                $(this).siblings(".two-level").css("display","block");
                $(this).parent().siblings().children(".two-level").css("display","none");
                $(this).parent().siblings().find("i.point").css("background","url(/public/gzadmin/images/select.png) no-repeat center/cover");
                $(this).addClass("on_bg").parent().siblings().children("h2").removeClass("on_bg")
            }else if(state=="block"){
                $(this).removeClass("on_bg");
                $(this).siblings(".two-level").hide();
                $(this).children("i.point").css("background","url(/public/gzadmin/images/select.png) no-repeat center/cover");
                $(this).siblings(".two-level").css("display","none");
            }
        }

    })

    //	二级导航
    $(".two-level>li").click(function(){
        $(this).parents(".one-level").addClass("active").siblings().removeClass("active")
        $(this).parent().siblings().find("i.icon").addClass("light")
        $(this).parents(".one-level").siblings().find("i.icon").removeClass("light")
        $(this).addClass("cur").siblings().removeClass("cur");
        $(this).parents(".one-level").siblings().children(".two-level").children("li").removeClass("cur");
        var src = $(this).find("a").attr("data-src");
        //frame(src)
    })

</script>
</body>
</html>