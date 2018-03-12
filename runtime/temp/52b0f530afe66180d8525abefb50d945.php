<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:40:"./template/mulit/wechat/course/card.html";i:1518064648;s:41:"./template/mulit/wechat/common/share.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>VIP会员</title>
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" type="text/css" href="/public/mobile/css/buyvip.css"/>
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
		<div class="head_top">
			<div class="headl">
				<img src="<?php echo session('face') ?>" class="face" />
				<div class="headl-right">
					<?php if($vip == 0): ?>
					<p class="nickname"><?php echo $user['nickname']; ?></p>
					<?php else: ?>
					<p class="nickname"><?php echo $user['nickname']; ?><span class="VIP_logo">VIP</span></p>
					<?php endif; if($vip == 2): ?>
					<p class="VIP_con">终身会员</p>
					<?php endif; if($vip == 1): ?>
					<p class="VIP_con">会员 <?php echo date('Y-m-d',$expire_time) ?>到期</p>
					<?php endif; if($vip == -1): ?>
					<p class="VIP_con">会员 <?php echo date('Y-m-d',$expire_time) ?>已到期</p>
					<?php endif; ?>
					<!-- <span class="">VIP会员</span> -->
				</div>				
			</div>
			
		</div>

		<?php if($vip == -1 || $vip == 1): ?>
		<p class="vip-explain">续费延长VIP有效期</p>
		<?php endif; ?>
		<div class="all_height">
			<div class="vip-content">
				<ul>
					<?php if(is_array($user_card) || $user_card instanceof \think\Collection || $user_card instanceof \think\Paginator): $i = 0; $__LIST__ = $user_card;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
					<li class="<?php if($i==1): ?>cur<?php endif; ?>" data-id="<?php echo $vo['id']; ?>">
						<div class="con-left">
							<p><span>VIP</span><?php echo $vo['title']; ?></p>
							<?php if($vo['type'] == 'mouth'): ?>
							<i>一个月内可观看所有课程</i>
							<?php endif; if($vo['type'] == 'season'): ?>
							<i>三个月内可观看所有课程</i>
							<?php endif; if($vo['type'] == 'year'): ?>
							<i>一年内可观看所有课程</i>
							<?php endif; if($vo['type'] == 'life'): ?>
							<i>可永久观看所有课程</i>
							<?php endif; ?>
						</div>
						<p class="con-right">￥<span><?php echo $vo['price']; ?></span></p>
					</li>
					<?php endforeach; endif; else: echo "" ;endif; ?>
				</ul>
			</div>
			<div class="explain">
				<i></i>
				<p></p>
				<i></i>
			</div>
			<p class="explain2"><?php echo $user_card[0]['explain']; ?></p>
		</div>
		<div class="grazy-copyright hastab">
			<i>格子匠 GRAZY.CN 技术支持</i>
	    </div>
    	<?php if($vip != 2): ?>
    	<i class="buyvip buy">￥<span style="margin-right: 5px;"><?php echo $user_card[0]['price']; ?></span>购买</i>
    	<?php else: ?>
		<i class="buyvip" style="background: #f5f5f5;color: rgba(41, 43, 51, .4);border: 0px;">已购买</i>
    	<?php endif; ?>
	</body>
	<script type="text/javascript" src="/public/mobile/js/jquery-1.8.3.min.js" ></script>
	<script type="text/javascript" src="/public/mobile/js/mui.min.js"></script>
	<script src="/public/mobile/js/StackBlur.js"></script>
	<script type="text/javascript" src="/public/mobile/js/globla.js"></script>
	<script type="text/javascript">
		$(function(){
			if($(window).innerHeight() - $('.all_height').innerHeight() - 170 > 0) {
               $('.grazy-copyright').addClass('bottom-fixed'); 
            }
// 			if(){
// 				//width: 100%;position: absolute;bottom: 0px;
// 			}
			//	头像模糊
			/*var BLUR_RADIUS = 30;
					
			  var canvas = document.getElementById("heroCanvas");
			  var canvasContext = canvas.getContext('2d');
			  
			  var canvasBackground = new Image();
			  canvasBackground.src = '';
			  
			  var drawBlur = function(){
			    var w = canvas.width;
			    var h = canvas.height;
			    canvasContext.drawImage(canvasBackground, 0, 0, w, h);
			    stackBlurCanvasRGBA('heroCanvas', 0, 0, w, h, BLUR_RADIUS);
			  };
			  
			  canvasBackground.onload = function() {
			    drawBlur();
			  }*/
			var uid = '<?php echo $userinfo["uid"]; ?>';
			var openid = '<?php echo $userinfo['openid']; ?>'; //微信openid
			var vip = '<?php echo $vip; ?>';
			$(".vip-content ul li").click(function(){
				$(this).addClass("cur").siblings().removeClass("cur")
				if(vip != 2){
					var money= $('.vip-content ul li.cur>p.con-right>span').text()
					$(".buyvip>span").text(money)
				}	
			})

			//点击购买
			$('.buy').click(function(){
				if(vip != 2){
					var id = $('.cur').attr('data-id');
					var url = '/wechat/ordersubmit/index?usercard_id='+id+'&order_type=usercard';
					window.location.href=url;
// 					$.post(host+'ucardrecord/create', {card_id:id,uid:uid},function(ret){  
// 						if(ret.code == 1){
// 							var card_sn = ret.data.card_sn;
// 							$.get(host+"wxpay/unifiedorder?openid="+openid+"&card_sn="+card_sn+'&pay_channel=usercard', function(res){
// 									var appId =  String(res.data.appid);
// 									var timeStamp = String(res.data.timeStamp);
// 									var nonceStr = res.data.nonce_str
// 									var package = res.data.package
// 									var paySign = res.data.sign
// 									WeixinJSBridge.invoke(
// 								       'getBrandWCPayRequest', {
// 								           "appId":appId,     //公众号名称，由商户传入
// 								           "timeStamp":timeStamp,  //时间戳，自1970年以来的秒数
// 								           "nonceStr":nonceStr, //随机串
// 								           "package":package,
// 								           "signType":"MD5",  //微信签名方式：
// 								           "paySign":paySign //微信签名
// 								       },
// 								       function(res){
// 								           if(res.err_msg == "get_brand_wcpay_request:ok" ) {
// 								           		setTimeout(function(){					 
// 													url = "<?php echo url('wechat/member/index'); ?>";
// 	                								window.location.href = url;
// 								           		},1000);

// 								           }
// 								           // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
// 								       }
// 								   );
// 								});

// 						}
// 						console.log(ret);
// 					},'json'); 
				}
			})
		})
	</script>
</html>
