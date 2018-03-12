<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:58:"C:\php\zaohui_2.0/application/admin\view\index\center.html";i:1519891268;s:58:"C:\php\zaohui_2.0/application/admin\view\common\admin.html";i:1519891268;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>后台管理系统</title>
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport"  content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="format-detection" content="telephone=no">
<!-- load css -->
<link rel="stylesheet" type="text/css"  href="/public/jqadmin/css/bootstrap.min.css?v=v3.3.7" media="all">
<link rel="stylesheet" type="text/css"  href="/public/jqadmin/css/font/iconfont.css?v=1.0.0" media="all">
<link rel="stylesheet" type="text/css"  href="/public/jqadmin/css/layui.css?v=1.0.9" media="all">
<link rel="stylesheet" type="text/css"  href="/public/jqadmin/css/main.css?v1.3.1" media="all">
<link rel="stylesheet" href="/public/gzadmin/css/swiper.min.css">
<link rel="stylesheet" href="/public/gzadmin/css/all.css">
<link rel="stylesheet" href="/public/gzadmin/css/main.css">
<script src="/public/gzadmin/js/jquery-1.11.0.min.js"></script>
<script src="/public/gzadmin/js/public_PC.js"></script>
</head>
<body>
	<div class="article_manage">
		<div class="right-side-header clearfix">
			<span>概况</span> 

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
			<div class="course_right_main " style='padding: 0'>
				<div class="dataCount_main">
					<link rel="stylesheet"
						href="/public/gzadmin/css/jquery.mCustomScrollbar.min.css">

					<div class="survey-header clearFloat">
						<p>
							<span><?php echo session('rolename'); ?>(<?php echo session('admin_username'); ?>)</span>
							<!-- 		               <i class="state state-shouquaned">已授权</i> -->
						</p>
						<ul class="phone-web-ul clearFloat">
							<li data-name="icon-weixin" class="active"><i>公众号</i>
								<div class="weixin-cord">
									<h5>微信扫一扫访问公众号</h5>
									<img src="<?php echo $config_wxcode; ?>"class="publicSignal_code">
								</div>
							</li>
							<li data-name="icon-xiaocx" class="active"><i>小程序</i>
								<div class="xiaocx-cord">
									<h5>微信扫一扫访问小程序</h5>
									<img src="<?php echo $config_mincode; ?>" class="smallProgram_code">
								</div>
							</li>
							<li data-name="icon-version" class="active"><i
								style="cursor: default;">版本号:&nbsp;<?php echo $config_version; ?></i>
							</li>
						</ul>
						<div class="fluid">
							<span> <i>流量已使用：<?php echo $flux; ?>GB</i><i>空间已使用：<?php echo $space; ?>GB</i>
							</span>
						</div>
					</div>
					<div class="survey-body mt50">
						<ul class="user-detail-ul">
							<li>
								<h5 data-name="revenue" class="h5classfloat">营收情况</h5> <span
								class="h5spanclass"><a href="/admin/statistics/index">更多</a></span>
								<div class="clearFloat user-detail-box">
									<span> <em>¥<?php echo $money['all']; ?></em> <i>总收入(元)</i>
									</span> <span> <em>¥<?php echo $money['day']; ?></em> <i>今日收入(元)</i>
									</span> <span> <em>¥<?php echo $money['month']; ?></em> <i>当月收入(元)</i>
									</span>
								</div>
							</li>
							<li>
								<h5 data-name="user-data" class="h5classfloat">用户数据</h5> <span
								class="h5spanclass"><a href="/admin/statistics/user">更多</a></span>
								<div class="clearFloat">
									<span> <em><?php echo $user['day']; ?>人</em> <i>今日新增</i>
									</span> <span> <em><?php echo $user['month']; ?>人</em> <i>当月新增人数</i>
									</span> <span> <em><?php echo $user['all']; ?>人</em> <i>用户总数</i>
									</span>
								</div>
							</li>
						</ul>
					</div>
					<p class='broadcast-title'>
						<i></i>格子匠动态
					</p>
					<!-- Slider main container -->
					<div class="swiper-container broadcast-swiper">
						<!-- Additional required wrapper -->
						<div class="swiper-wrapper">
							<?php if(is_array($officialnotice_data) || $officialnotice_data instanceof \think\Collection || $officialnotice_data instanceof \think\Paginator): $i = 0; $__LIST__ = $officialnotice_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?>
							<div class="swiper-slide">
								<a href="http://<?php echo $data['url']; ?>" target="_blank"> <img
									src="/public/gzadmin/images/broadcast-bullet.png" />
									<?php echo $data['post_title']; ?>
								</a>
							</div>
							<?php endforeach; endif; else: echo "" ;endif; ?>
						</div>
					</div>
					<script src="/public/gzadmin/js/swiper.jquery.min.js"></script>
					<script src="/public/gzadmin/js/jquery.mCustomScrollbar.concat.min.js"></script>
					<script src="/public/gzadmin/js/placeholder.js"></script>
					<script>
						$(function() {
							$('.swiper-container').width(
									$('.user-detail-box').find('span')
											.innerWidth() * 3 + 30);
							var tmpTag = 'https:' == document.location.protocol ? false
									: true;
							if (tmpTag) {
								var protocol = 'http';
							} else {
								var protocol = 'https';
							}

							initHeight();
							//initialize swiper when document ready  
							var mySwiper = new Swiper('.swiper-container', {
								// Optional parameters
								direction : 'horizontal',
								loop : true,
								speed : 10000,
								slidesPerView : 6,
								autoplay : true,
								autoplayDisableOnInteraction : false
							})
						})
						//浏览器改变触发该事件
						$(window).resize(function() {
							initHeight();
						})
						//初始化height
						function initHeight() {
							var window_height = $(window).height();
							$(".user_manage").outerHeight(window_height)
						}
						var myParent = window.parent.document; //父级窗口
						$("p.p_modul img").on(
								"click",
								function() {
									var img_class = $(this).attr("class");
									var iframe = myParent
											.getElementById("iframe")
									if ($(this).is(".pointer_tiwen")) {
										iframe.src = "wenda.html";
										$(".up_nav_ul li", myParent)
												.removeClass("active");
										$(".up_nav_ul li:nth-of-type(5)",
												myParent).addClass("active");
									} else if ($(this).is(".pointer_notice")) {
										iframe.src = "notice_record.html";
									}
								})
						//登录退出浮层显示
						$(".tag_group>img.img_myself").on("click", function() {
							var display = $(".exits_ul").css("display");
							if ("none" == display) {
								$(".exits_ul").show();
							} else {
								$(".exits_ul").hide();
							}
						})
						$(document).bind("click", function(e) {
							var tg = $(e.target);
							var str = ".tag_group>img.img_myself"
							var thisParent = tg.closest(str);
							if (!thisParent.is(str)) {
								$(".exits_ul").hide();
							}
						})
					</script>
				</div>
			</div>
		</div>
	</div>
	<!--  <div class="add-subcat" style="display: none"> -->
	<!--     <form id="form1" class="layui-form layui-form-pane" method="POST" action='<?php echo url("repay/add_repay"); ?>'> -->

	<!--         <div class="layui-form-item"> -->
	<!--             <label class="layui-form-label">内容*</label> -->
	<!--             <div class="layui-input-block"> -->
	<!--                 <textarea name="content" class="layui-textarea" jq-verify="required" value="" placeholder="请输入内容"></textarea> -->
	<!--             </div> -->
	<!--         </div> -->
	<!--         <div class="layui-form-item"> -->
	<!--             <div class="layui-input-block"> -->
	<!--                 <button class="layui-btn" jq-submit jq-filter="submit">确定</button> -->
	<!--             </div> -->
	<!--         </div> -->
	<!--     </form> -->
	<!--  </div> -->
	<script type="text/javascript" src="/public/jqadmin/js/layui/layui.js"></script>
	<script>
		layui.config({
			base : '/public/jqadmin/js/',
			version : "1.3.1"
		}).extend({
			elem : 'jqmodules/elem',
			tabmenu : 'jqmodules/tabmenu',
			jqmenu : 'jqmodules/jqmenu',
			ajax : 'jqmodules/ajax',
			dtable : 'jqmodules/dtable',
			jqdate : 'jqmodules/jqdate',
			modal : 'jqmodules/modal',
			tags : 'jqmodules/tags',
			jqform : 'jqmodules/jqform',
			echarts : 'lib/echarts',
			webuploader : 'lib/webuploader'
		})
	</script>


	<div class="edit_pswd" style="display: none">
		<form id="form1" class="layui-form layui-form-pane"
			action="<?php echo url('user/edit_password'); ?>">
			<input type="hidden" name="id" value="<?php echo session('admin_uid'); ?>">
			<div class="layui-form-item">
				<label class="layui-form-label">原密码</label>
				<div class="layui-input-inline">
					<input class="layui-input" type="password" name="passwd"
						placeholder="密码" jq-verify="pass">
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">新密码</label>
				<div class="layui-input-inline">
					<input class="layui-input" type="password" name="newpasswd"
						placeholder="新密码" jq-verify="pass">
				</div>
			</div>
			<div class="layui-form-item">
				<label class="layui-form-label">确认密码</label>
				<div class="layui-input-inline">
					<input class="layui-input" type="password" name="morepasswd"
						placeholder="确认密码" jq-verify="pass">
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
		$(".user-self")
				.click(
						function(e) {
							e.stopPropagation();
							var $usersetul = $(this).next(".user-set-ul"), display = $usersetul
									.css("display");
							if ("none" == display) {
								$usersetul.slideDown();
							} else {
								$usersetul.slideUp();
							}
						});
		$('body').on('click', function() {
			if ($('.user-set-ul').css('display') == 'block') {
				$(".user-set-ul").slideUp();
			}
		})
	</script>
	<script>
		layui
				.define(
						[ 'jquery', 'modal', 'jqform', 'ajax' ],
						function(exports) {
							var $ = layui.jquery, form = layui.jqform, modal = layui.modal;

							//初始化
							modal.init();
							form.set({
								"blur" : true,
								"form" : "#form1"
							}).init();
							exports('main', {});
						});
		//layui.use('course');
	</script>
	<script>
		/*$(function(){
		   initHeight();
		   initPhoneNum();
		   $(".mCustomScrollbar").mCustomScrollbar({
		      theme:"minimal-dark"
		   });
		})*/
		//浏览器改变触发该事件
		$(window).resize(function() {
			initHeight();
		})
		//初始化height
		function initHeight() {
			var window_height = $(window).height();
			$(".user_manage").outerHeight(window_height)
		}
		var myParent = window.parent.document; //父级窗口
		$("p.p_modul img").on("click", function() {
			var img_class = $(this).attr("class");
			var iframe = myParent.getElementById("iframe")
			if ($(this).is(".pointer_tiwen")) {
				iframe.src = "<?php echo url('question/index'); ?>";
				$(".up_nav_ul li", myParent).removeClass("active");
				$(".up_nav_ul li:nth-of-type(5)", myParent).addClass("active");
			} else if ($(this).is(".pointer_notice")) {
				iframe.src = "<?php echo url('question/index'); ?>";
			} else if ($(this).is(".pointer_notice2")) {
				iframe.src = "<?php echo url('message/remind'); ?>";
			}
		})
		//登录退出浮层显示

		$(document).bind("click", function(e) {
			var tg = $(e.target);
			var str = ".tag_group>img.img_myself"
			var thisParent = tg.closest(str);
			if (!thisParent.is(str)) {
				$(".exits_ul").hide();
			}
		})

		$('.span_confirm').click(function() {
			layui.use('layer', function() {
				var layer = layui.layer;
				var content = $('.count_list_other textarea').val();
				if (content == '') {
					layer.msg('请填写公告内容');
					return false;
				}
				var url = "<?php echo url('message/add_remind'); ?>";
				$.post(url, {
					content : content
				}, function(result) {
					if (result.status == 200) {
						layer.msg('发送成功');
						$('.count_list_other textarea').val('');
					} else {
						layer.msg(result.msg);
						return false;
					}
				});

			});

		})
	</script>
</body>
</html>