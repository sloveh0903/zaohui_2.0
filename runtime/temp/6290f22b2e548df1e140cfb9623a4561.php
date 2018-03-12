<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:41:"./template/mulit/wechat/member/setup.html";i:1518064648;s:41:"./template/mulit/wechat/common/share.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>设置</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/member.css" />
		
	</head>
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
		<div class="mui-row help-list">
			<ul class="iterm-box">
				<li>
					<a href="#">
						<div class="iterm-content about">
							<span>关于我们</span>
							<i class="mui-icon mui-icon-forward"></i>
						</div>
					</a>
				</li>
				<li class="wechat">
					<a href="#">
						<div class="iterm-content">
							<span>关注微信</span>
							<i class="mui-icon mui-icon-forward"></i>
						</div>
					</a>
				</li>
				<li>
					<a href="#">
						<div class="iterm-content bindphone">
							<span>手机号码</span>
							<i></i>
						</div>
					</a>
					<a href="#" class="change-list"  style="display: none;">
						<div class="iterm-content changephone">
							<span>手机号码</span>
							<i class="more-dotted">
								<span class="phonenumber fs-14 fc-4"></span>
								<img src="/public/mobile/img/icon/more_vert@2x.png"/>
							</i>
						</div>
						<!--浮框-->
						<div class="fs-16 classify-box">
							<div class="classify-name">
								<a href="#">更换号码</a>
							</div>
						</div>
					</a>
				</li>
				
			</ul>
			<!--底部版权-->
			<div class="grazy-copyright bottom-fixed">
			<i>格子匠 GRAZY.CN 技术支持</i>
	    </div>
		</div>
		<!--关注我们弹框-->
		<div class="contact-dialog" id="kefu">
			<div class="dialog-main">
				<h1>关注微信</h1>
				<img src="<?php echo $config_wechat; ?>" alt="二维码">
				<p>长按识别二维码添加好友</p>
			</div>
			<div class="dialog-close">
			    <i class="dialog-closebtn "></i>
			</div>
		</div>
		<script type="text/javascript" src="/public/mobile/js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/mui.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/globla.js"></script>
		<script>
            var uid = '<?php echo $userinfo['uid']; ?>';
            var isbind = '<?php echo $userinfo['is_bind']; ?>';
            $.ajax({
                url:host + 'user/center/index',
                data:{
                    uid:uid
                },
                type:'GET', //GET
                async:true,    //或false,是否异步
                timeout:5000,    //超时时间
                dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                success:function(data,textStatus,jqXHR){
                    if(data.code==1){
                        mobile = data.data.user.mobile;
                        wechat = data.data.config.wechat;
                       // $('#wxid').html(wechat);
                       if(wechat==''){
                           $('.wechat').css('display','none');
                    	   //wechat ='/public/gzadmin/images/kong-miniprom.png';
                       }
                        $('#wxid').attr('src',wechat);
                        if(mobile){
                            $('.mui-row li').find('i').eq(2).css('width','120px');
                            $('.mui-row li').find('i').eq(2).html(data.data.user.mobile);
                        }else{
                            $('.mui-row li').find('i').eq(2).addClass('mui-icon-forward');
                            $('.mui-row li').find('i').eq(2).addClass('mui-icon');
						}
                        //console.log(data);
                    }
                }
            });
            mui('.help-list').on('tap','.bindphone', function(){
                var mobile = $(this).find('i').html();
                if(!mobile){
                    url = "<?php echo url('wechat/member/bindphone'); ?>";
                    window.location.href = url;
				}

            });
            mui('.help-list').on('tap','.wechat',function () {
                $("#kefu").css('display','flex');
                $(".mui-popup-backdrop").show();
            });
            mui('.dialog-close').on('tap','.dialog-closebtn',function () {
                $("#kefu").hide();
                $(".mui-popup-backdrop").hide();
            });
            mui('.help-list').on('tap','.about', function(){
                var mobile = $(this).find('i').html();
                if(!mobile){
                    url = "<?php echo url('wechat/member/aboutus'); ?>";
                    window.location.href = url;
				}
            });
            //二级浮框菜单
            //点击区域外弹框消失
//		   $(".classify-box").click(function(e) {
//		        e ? e.stopPropagation() : event.cancelBubble = true;//取消事件处理
//		   });
//		    $(document).click(function(e){
//		    	console.log(e);
//		    	if(e.target.className != 'classify-name') {
//		    		$(".classify-box").fadeOut();
//		    	}
//		    })
//          $('.iterm-content.changephone').on('click','.more-dotted',function(e){
//          	e.stopPropagation();
//          	$('.classify-box').fadeIn()
//          });
//          $('.classify-box').on('click','.classify-name',function(e){
//          	$('.classify-box').fadeOut()
//          })
		</script>
		<script src="/public/mobile/js/bindmobile.js"></script>
	</body>

</html>