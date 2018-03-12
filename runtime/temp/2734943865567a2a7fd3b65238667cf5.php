<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:40:"./template/mulit/wechat/index/index.html";i:1518064648;s:41:"./template/mulit/wechat/common/share.html";i:1518064648;s:40:"./template/mulit/wechat/common/menu.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>首页</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/index-v75.css" />
	</head>
	<script src="/public/mobile/js/jquery-3.2.1.min.js"></script>
	<script src="/public/mobile/js/mui.min.js"></script>
	<script src="/public/mobile/js/globla.js"></script>
	<?php
    $data = $_GET;
    unset($data['code']);
    $data['uname'] = $userinfo['uname'];
    $url_data = '?'.http_build_query($data);
    $link = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL'].$url_data;
    $config = find('Config');
    $logo = $config['logo'];
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script type="text/javascript">
            var logo = '<?php echo $logo; ?>';
            if(logo == ''){
                logo = '/public/image/logo@1x.png';
            }
            wx.config({
                debug: false,
                appId: '<?php echo $signPackage["appId"];?>',
                timestamp: '<?php echo $signPackage["timestamp"];?>',
                nonceStr: '<?php echo $signPackage["nonceStr"];?>',
                signature: '<?php echo $signPackage["signature"];?>',
                jsApiList: [
                'onMenuShareTimeline','onMenuShareAppMessage'
                  // 所有要调用的 API 都要加到这个列表中
                ]
             });
             
            wx.ready(function(){
                // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
                //分享给朋友
                wx.onMenuShareAppMessage({
                    title: '<?php echo $config["sitename"]; ?>', // 分享标题
                    desc: '<?php echo $config["introduce"]; ?>', // 分享描述
                    link: '<?php echo $link; ?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    imgUrl: 'http://'+window.location.host+logo, // 分享图标
                    type: '', // 分享类型,music、video或link，不填默认为link
                    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                    success: function () { 
                        // 用户确认分享后执行的回调函数
                    },
                    cancel: function () { 
                        // 用户取消分享后执行的回调函数
                    }
                });

                //分享到朋友圈
                wx.onMenuShareTimeline({
                    title: "<?php echo $config['sitename']; ?>", // 分享标题
                    link: "<?php echo $link; ?>", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    imgUrl: 'http://'+window.location.host+logo, // 分享图标
                    success: function () { 
                        // 用户确认分享后执行的回调函数
                    },
                    cancel: function () { 
                        // 用户取消分享后执行的回调函数
                    }
                });
            });

            wx.error(function(res){
                console.log(res)
                // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
            });


</script>
<body>
<?php if($is_follow): if($userinfo['subscribe'] == 0): ?>
<div class="concern_top">
	<i class="top_close"></i>
	<p class="fs-14">关注公众号便于下次学习</p>
	<div class="concern btn fs-14">关注</div>
</div>
<?php endif; endif; ?>
<!--下拉刷新容器-->
<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;if($data['type']==1): ?>
  <div class="slider-box">
    <div class="mui-slider banner-slider">
      <div class="mui-slider-group mui-slider-loop">
      <!--支持循环，需要重复图片节点-->
        <div class="mui-slider-item mui-slider-item-duplicate">
          <a href="<?php echo $data['banner2_link']; ?>">
            <img src="<?php echo $data['banner2']; ?>" />
          </a>
        </div>
        <?php if(is_array($data['content']) || $data['content'] instanceof \think\Collection || $data['content'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['content'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$content): $mod = ($i % 2 );++$i;?>
        <div class="mui-slider-item">
          <a href="<?php echo $content['link']; ?>"><img src="<?php echo $content['img']; ?>"/></a>
        </div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
      <!--支持循环，需要重复图片节点-->
        <div class="mui-slider-item mui-slider-item-duplicate">
          <a href="<?php echo $data['banner1_link']; ?>"><img src="<?php echo $data['banner1']; ?>"/></a>
        </div>
      </div>
      <div class="mui-slider-indicator">
        <?php if(is_array($data['content']) || $data['content'] instanceof \think\Collection || $data['content'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['content'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$content): $mod = ($i % 2 );++$i;?>
        <div class="mui-indicator"></div>
        <?php endforeach; endif; else: echo "" ;endif; ?>
      </div>
    </div>
  </div>
  <?php endif; if($data['type']==2): ?>
  <div class="search-box">
    <a class="search" href="">
        <span class="search-icon">
      </span>
      <span class="search-text">搜索...</span>
    </a>
  </div>
  <?php endif; if($data['type']==3): ?>
  <div class="list-content <?php if($data['content']['title']): ?> withtitle <?php endif; ?>">
    <div class="course-box" <?php if(!$data['content']['title']): ?> style="display:none;" <?php endif; ?>>
      <?php if($data['content']['title']): ?>
      <p><?php echo $data['content']['title']; ?></p>
      <?php if($data['content']['show_more']): ?><a href="/wechat/index/show_more" class="show_more">更多</a><?php endif; endif; ?>
    </div>
    <ul class="mui-table-view course-list">
    <?php if(is_array($data['content']['course_list']) || $data['content']['course_list'] instanceof \think\Collection || $data['content']['course_list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['content']['course_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$course): $mod = ($i % 2 );++$i;?>
      <li data-id='<?php echo $course['cid']; ?>'>
        <div class="teacher-box">
          <img src="<?php echo $course['face']; ?>" />
        </div>
        <div class="course-content">
          <h1><?php echo $course['title']; ?></h1>
          <span><?php echo $course['desc']; ?></span>
          <i><?php echo $course['study_count']; ?>人在学</i>
        </div>
        <span class="course-price">
        <?php if($course['price']==0.00): ?>免费<?php else: ?>￥<?php echo $course['price']; endif; ?>
        </span>
      </li>
      <?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
  </div>
  <?php endif; if($data['type']==4): ?>
  <div class="text-box"><?php echo $data['content']; ?></div>
  <?php endif; if($data['type']==5): ?>
  <div class="border-box">
    <?php if($data['content']=='1'): ?>
    <div class="border-1px"></div>
    <?php else: ?>
    <div class="border-12px"></div>
    <?php endif; ?>
  </div>
  <?php endif; if($data['type']==6): if($data['package_list']): ?>
      <div class="bundle-box <?php if($data['content']['title']): ?> withtitle <?php endif; ?>">
        <div class="bundle-title-box" <?php if(!$data['content']['title']): ?> style="display:none;" <?php endif; ?>>
        <?php if($data['content']['title']): ?>
          <p><?php echo $data['content']['title']; ?></p>
          <?php if($data['content']['show_more']): ?><a href="/wechat/bundlelist" class="show_more">更多</a><?php endif; endif; ?>
        </div>
        <ul class="bundle-list">
        <?php if(is_array($data['package_list']) || $data['package_list'] instanceof \think\Collection || $data['package_list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $data['package_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$package_list): $mod = ($i % 2 );++$i;?>
          <li data-id="<?php echo $package_list['id']; ?>">
            <div class="bundle-showbox">
            <div class="img1">
                <img src="<?php echo $package_list['banner']; ?>" alt="banner2">
              </div>
            <?php if(is_array($package_list['banner_color']) || $package_list['banner_color'] instanceof \think\Collection || $package_list['banner_color'] instanceof \think\Paginator): $i = 0; $__LIST__ = $package_list['banner_color'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$banner): $mod = ($i % 2 );++$i;?>
              <div class="img2" style="background:<?php echo $banner; ?>">
              </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="bundle-content">
              <h1><?php echo $package_list['title']; ?></h1>
              <i class="bundle-price">¥<?php echo $package_list['price']; ?></i>
            </div>
          </li>
        <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
      </div>
    <?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
<div class="grazy-copyright hastab">
  <i>格子匠 GRAZY.CN 技术支持</i>
</div>
<?php 
	$controller = request()->controller();
	$show_switch_arr= db('show_switch')->where(['id'=>1])->find();
	$is_testitemshop = $show_switch_arr['is_testitemshop'];
	$is_showask = $show_switch_arr['is_showask'];
	if(session('uid')){
	    apipost('statistics/postStaticadd',['uid'=>session('uid'),'terminal'=>2]);
	}
	 ?>
	<div class="mui-row menuNav">
			<ul class="mui-table-view">
				<li>
					<div class="navcontent <?php if($controller=='Index'): ?> NavActive <?php endif; ?>">
						<a href="<?php echo url('/wechat/index/index'); ?>">
              				<i class="bottom-tab-icon index-icon"></i>
              				<span>主页</span>
            			</a>
					</div>
				</li>
				<li>
					<div class="navcontent <?php if($controller=='Course'): ?> NavActive <?php endif; ?>">
						<a href="<?php echo url('/wechat/course/category'); ?>">
			              	<i class="bottom-tab-icon course-icon"></i>
			              	<span>课程</span>
			            </a>
					</div>
				</li>
				<?php if($is_testitemshop==1): ?>
				<li>
					<div class="navcontent <?php if($controller=='Testitembank'): ?> NavActive <?php endif; ?>">
						<a href="<?php echo url('/wechat/testitembank/index'); ?>">
                			<i class="bottom-tab-icon quiz-icon"></i>
              				<span>题库</span>
            			</a>
					</div>
				</li>
				<?php endif; if($is_showask==1): ?>
				<li>
					<div class="navcontent <?php if($controller=='Ask'): ?> NavActive <?php endif; ?> ">
						<a href="<?php echo url('/wechat/ask/mulitindex'); ?>">
              				<i class="bottom-tab-icon discuss-icon"></i>
              				<span>问答</span>
            			</a>
					</div>
				</li>
				<?php endif; ?>
				<li>
					<div class="navcontent <?php if($controller=='Member' || $controller=='Rebate'): ?> NavActive <?php endif; ?>  ">
						<a href="<?php echo url('/wechat/member/index'); ?>">
              				<i class="bottom-tab-icon info-icon"></i>
              				<span>我的</span>
            			</a>
					</div>
				</li>
			</ul>
		</div>

<script>
$(function () {
	mui(".mui-table-view").on('tap', '.navcontent a', function (event) {
		var url = $(this).attr('href');
		window.location.href=url;
	});
});

</script>


<?php if($is_customer): ?>
<div class="img-customerService"></div>
<div class="contact-dialog" id="kefu">
  <div class="dialog-main">
		<h1>联系客服</h1>
		<img src="<?php echo $qrcode; ?>">
		<p>
		1.长按识别二维码进入公众号;
		<br>
		2.回复“客服”联系客服咨询;
		</p>
	</div>
	<div class="dialog-close">
    <i class="dialog-closebtn "></i>
  </div>
</div>
<?php endif; ?>

<div class="contact-dialog" id="guanzhu">
	<div class="dialog-main">
		<h1>关注公众号</h1>
		<img src="<?php echo $qrcode; ?>">
		<p class="fc-6">长按识别二维码进入公众号</p>
	</div>
	<div class="dialog-close">
    <i class="dialog-closebtn"></i>
	</div>
</div>




<script type="text/javascript">
var page = 1;
var size = 10;
var uid = '<?php echo $userinfo['uid']; ?>';
var isbind = '<?php echo $userinfo['is_bind']; ?>';
var gallery = mui('.mui-slider');
gallery.slider({
  interval:5000//自动轮播周期，若为0则不自动播放，默认为0；
});
mui('.course-list').on('tap', 'li', function() {
	var cid = $(this).attr('data-id');
	url = "<?php echo url('wechat/course/detail'); ?>";
	window.location.href = url + '?cid=' + cid + '&version=' + '<?php echo $version; ?>';
});
mui('.search-box').on('tap', '.search', function() {
	url = "<?php echo url('wechat/search/index'); ?>";
	window.location.href = url;
});
mui('.course-box').on('tap', '.show_more', function() {
	url = "<?php echo url('wechat/index/show_more'); ?>";
	window.location.href = url;
});

//点击跳转
$(".bundle-list").on("click","li",function(){
	var id = $(this).attr('data-id');
	var url = '/wechat/bundlelist/detail?id='+id;
	window.location.href=url;
})
//客服
$('.img-customerService').click(function(){
	$('#kefu').css('display', 'flex');
});
$('.concern_top').on('click','.concern',function () {
    $('#guanzhu').css('display', 'flex');
});
$('.contact-dialog').on('click', '.dialog-closebtn', function(){
    $(this).parents('.contact-dialog').hide();
});
$('.mui-slider-indicator').find('.mui-indicator').eq(0).addClass('mui-active');
// //积分文本提示框
// 	document.getElementById("toastBtn").addEventListener('tap',function(){
// 		mui.toast('<div class="toast-content"><p>'+'新用户注册'+'</p>'+'<p>+'+'10'+'积分</p></div>');
// 	});
</script>
<script src="/public/mobile/js/bindmobile.js"></script>