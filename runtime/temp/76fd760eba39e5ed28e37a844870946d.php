<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:46:"./template/mulit/wechat/course/subcomment.html";i:1518064648;s:41:"./template/mulit/wechat/common/share.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>我的评价</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/my-comment.css" />
	</head>
	<style>
		.icon-star-blue{
			background:url('http://api.fuwangdian.com/public/images/selected.svg')
		}
	</style>
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
		<div class="mui-row mcom">
			<div class="container">
        <div class="mcom-star">
          <i class="icon-star-blue" data-id="1"></i>
          <i class="icon-star-blue" data-id="2"></i>
          <i class="icon-star-blue" data-id="3"></i>
          <i class="icon-star-blue" data-id="4"></i>
          <i class="icon-star-blue" data-id="5"></i>
        </div>
        <span class="comment-text">超级好</span>
      </div>
		</div>
		<textarea placeholder="评价" class="content"></textarea>
		<div class="q-btn">
			<a>评价</a>
		</div>
		<div class="toast-box limit">
			<div class="toast-main" id="toast-main"></div>
		</div>
		<script src="/public/mobile/js/jquery-3.2.1.min.js"></script>
		<script src="/public/mobile/js/mui.min.js"></script>
		<script src="/public/mobile/js/globla.js"></script>
	</body>
	<script>
		var star = 5;
        var uid = '<?php echo $userinfo['uid']; ?>';
        var isbind = '<?php echo $userinfo['is_bind']; ?>';
		var cid = GetQueryString('cid'); //课程id
		mui("body").on('tap', '.icon-star-blue', function (event) {
			var id = this.getAttribute("data-id");
			star = id;
			if(id <= 2) {
				$('.comment-text').html('一般');
			}else if(id == 3) {
				$('.comment-text').html('好');
			}else {
				$('.comment-text').html('超级好');
			}
			for(var i=0;i<5;i++){
			console.log(i+id);
				if(id > i){
					$('.icon-star-blue').eq(i).css('background-image','url(/public/mobile/img/icon/blue_fullStar.png)');
				}else{
					$('.icon-star-blue').eq(i).css('background-image','url(/public/mobile/img/icon/blue_emptyStar.png)');
				}
			}
		});
		mui("body").on('tap', '.q-btn', function (event) {
			var content = $('.content').val();
			if(content == ""){
				mui.alert('请填写评价内容');return;
			}
			$.post(host+"comment/add",{uid:uid,cid:cid,star:star,content:content},function(res){ 
				console.log(res);
				if(res.code == 1){
					var alert_str = '提交成功';
					if(res.data.integral_code==1){
						var alert_str = res.data.msg;
					}
					document.getElementById('toast-main').innerHTML=alert_str;
					toastBox()
					$('.content').val('');
					var url = "<?php echo url('wechat/course/detail'); ?>";
					setTimeout(function(){window.location.href=url+'?cid='+cid;},1000);
					
				}else{
					document.getElementById('toast-main').innerHTML=res.message;
					toastBox()
				}
			});
			
		});
		function GetQueryString(name)
		{
			var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
			var r = window.location.search.substr(1).match(reg);
			if(r!=null)return  unescape(r[2]); return null;
		}
		
	</script>
	<script src="/public/mobile/js/bindmobile.js"></script>
</html>