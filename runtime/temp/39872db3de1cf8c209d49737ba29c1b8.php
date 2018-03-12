<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:45:"./template/mulit/wechat/member/studylist.html";i:1518064648;s:41:"./template/mulit/wechat/common/share.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>学习记录</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/member.css" />
		<!--<link rel="stylesheet" href="/public/mobile/css/study-list.css" />-->
		<style type="text/css">
			html,body{
				background: #fff;
			}
		</style>
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
		<div class="mui-row timeline">
			<ul id="studylist">
			</ul>
		</div>
	<div class="buy-dialog" id="BuyDialog">
		<div class="alert-box">
			<h1 class="fs-20 fc-8">提示</h1>
			<p class="fs-16 fc-6">是否要清除全部学习记录？</p>
			<div class="buy-dialog-btn">
				<a class="Cancel">取消</a> <a id="onBridgeReady" class="onBridgeReady">确定</a>
			</div>
		</div>
	</div>
	<a class="btn hollow btn-clear" href=''>清除</a>
		<script type="text/javascript" src="/public/mobile/js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/mui.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/globla.js"></script>
		<script>
            var uid = '<?php echo $userinfo['uid']; ?>';
            var isbind = '<?php echo $userinfo['is_bind']; ?>';
            $.ajax({
                url:host+'studyList/recordList',
                data:{
                    uid:uid
                },
                type:'GET', //GET
                async:true,    //或false,是否异步
                timeout:5000,    //超时时间
                dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                success:function(data,textStatus,jqXHR){
                    if(data.code==1){
                        //console.log(data);
                        var list = "";
                        var result = data.data;
                        if(result.browse_record.length == 0){
                            var empty = '<div class="emptypage-wrapper"><div class="empty-box"> <img class="empty-img" src="/public/mobile/img/icon/purchase-empty.png"> <p class="empty-text">还没有学习记录</p></div></div>';
                                $(".mui-row").html(empty);
                                $('.btn-clear').hide();
                        }
                        $.each(result.browse_record,function(i,item){
                            var courseNow = 0;
                            var courseNum = Object.getOwnPropertyNames(item).length;  //获取课程数
                            list += '<li><dl><dd><span class="timeline-time fs-12 fc-6">'+ i +'</span>';
                            $.each(item,function (j,course) {
                                console.log(course);
                                list += '<h1 class="timeline-time-t fc-10 fs-16">'+ j +'</h1>';
                                $.each(course,function (z,video) {
                                    list += '<p><span class="timeline-info fs-14 fc-10" data-id="'+ video.cid +'">'+ video.video_title +'</span></p>'
                                });
                                if(courseNow == courseNum-1){
                                    //如果是否到最后一个课程
                                    list += '</dd>';
								}else{
                                    list += '</dd><dd>';
								}
                                courseNow++;
                            });
                            list += '</dl><i class="timeline-dian"></i></li>';
                        });
                        $("#studylist").append(list);
                    }
                }
            });
            mui('#studylist').on('tap','h1',function () {
                var cid = $(this).next().find('.timeline-info').attr('data-id');
                url = "<?php echo url('wechat/course/detail'); ?>";
                window.location.href = url + '?cid=' + cid;
            });
            mui('#studylist').on('tap','.timeline-info',function () {
                var cid = $(this).attr('data-id');
                url = "<?php echo url('wechat/course/detail'); ?>";
                window.location.href = url + '?cid=' + cid;
            });
            mui('body').on('tap','.btn-clear',function () {
            	$("#BuyDialog").addClass("buy-dialog-in");
                
            });
            mui("body").on('tap', '.Cancel', function (event) {
				$("#BuyDialog").removeClass("buy-dialog-in");
			})
			mui("body").on('tap', '#onBridgeReady', function (event) {
                $.ajax({
			        url:host + 'StudyList/clean_studylist/',
			        data:{
			            uid:uid
			        },
			        type:'GET', //GET
			        async:true,    //或false,是否异步
			        timeout:5000,    //超时时间
			        dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
			        success:function(data,textStatus,jqXHR){
						if(data.code==1){
							url = "<?php echo url('wechat/member/studylist'); ?>";
							window.location.href = url;
						}
			        }
			    })
			})
		</script>
		<script src="/public/mobile/js/bindmobile.js"></script>
	</body>
</html>