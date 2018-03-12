<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:41:"./template/mulit/wechat/search/index.html";i:1518064648;s:41:"./template/mulit/wechat/common/share.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>搜索</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/search.css" />
		<script src="/public/mobile/js/compatible.js"></script>
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
		<!-- <div class="clearfix seachOne-seach"> -->
		<div class="search-box">
			<input type="text" placeholder="搜索课程与视频" class="search-input"/>
			<i class="search-blueicon"></i>
		</div>
		<div class="seachOne-num font-color-4 hot-search">热门搜索</div>
		<div class="mui-row seachOne-list">
			<ul class="keyword">

			</ul>
		</div>
		<div class="seachOne-num font-color-4 list">相关课程</div>
		<div class="mui-row list-content list">
			<ul class="mui-table-view course-list">

			</ul>
		</div>
		<div class="seachTwo-more course-more" style="display: none">
			<a href="#">查看更多...</a>
		</div>
		<div class="seachOne-num font-color-4 list">相关视频</div>
		<div class="mui-row list-content-one list">
			<ul class="navigate-ul ">
				
			</ul>
		</div>
		<div class="seachTwo-more video-more" style="display: none">
			<a href="#">查看更多...</a>
		</div>
		<script type="text/javascript" src="/public/mobile/js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/mui.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/globla.js"></script>
		<script>
		//var host = 'https://api.fuwangdian.com/api/';
		var uid = '<?php echo $userinfo['uid']; ?>';
    	var isbind = '<?php echo $userinfo['is_bind']; ?>';
		var search = [];
		var page = 1;
		var size = 3;
		var keyword = '';
		var course_url = "<?php echo url('wechat/search/course'); ?>";
		var video_url = "<?php echo url('wechat/search/video'); ?>";
		$.ajax({
			url:host+"search/keywords",
			type:'GET',
			async:false,
			success: function(result){
		      if(result.code == 1){
		      	search = result.data.search;
		      	var inner = '';
		      	for(var i=0;i<search.length;i++){
		      		inner += '<li>'+search[i].name+'</li>';
		      	}
		      	$('.seachOne-list ul').append(inner);
		      }else{
		      	mui.alert('数据请求失败');
		      }
		    }
		});

		//点击关键词搜索
		mui(".mui-row").on('tap', '.seachOne-list li', function (event) {
			keyword = $(this).html()
			var data = {
				'keyword':keyword,
				'page':page,
				'size':size,
			};
			getlist(data);	
		})

		//更多课程
		$('.course-more').click(function(){
			window.location.href=course_url+'?keyword='+keyword;return;
		});
		
		//更多视频
		$('.video-more').click(function(){
			window.location.href=video_url+'?keyword='+keyword;return;
		});

		//点输入框显示搜索词
		mui(".mui-row").on('tap', '.seachOne-seach input', function (event) {
			$('.keyword').show();
		});

		//点搜索按钮
		mui(".search-box").on('tap', '.search-blueicon', function (event) {
		    keyword = $('.search-box input').val();
			if(keyword != ""){
        $('.hot-search').hide();
				var data = {
					'keyword':keyword,
					'page':page,
					'size':size,
				};
				getlist(data);
			}
		});

		mui(".mui-row").on('tap', '.mui-table-view li,.navigate-ul li', function (event) {
			var cid = $(this).attr('data-id');
			var url = "<?php echo url('wechat/course/detail'); ?>";
			window.location.href=url+'?cid='+cid;
		});
		
		
		
		function getlist(data){
			console.log(data);
			$.ajax({
				url:host+"search/index",
				type:'GET',
				data:data,
				async:false,
				success: function(result){
				  $('.list').show();
				  $('.keyword').hide();
				  console.log(result);
			      if(result.code == 1){
					var course = result.data.course;
					var course_count = course.length;
					var video= result.data.video; 
					var video_count = video.length;
					var course_innser = '';
					var video_innser = '';
					

					if(course_count > size){
						$('.course-more').show();
					}else{
						$('.course-more').hide();
					}
					if(video_count > size){
						$('.video-more').show();
					}else{
						$('.video-more').hide();
					}
					//课程
					if(course_count>0){
						for(var i=0;i<course_count;i++){ 
							course_innser += 
							'<li data-id="'+course[i].cid+'"><div class="teacher-box">'+
								'<img src="'+course[i].face+'" /></div>'+
								'<div class="course-content">'+
									'<h1>'+course[i].title+'</h1>'+
									'<span>'+course[i].desc+'</span>'+
									'<i>'+course[i].study_count+'人在学</i>'+
								'</div>'+
								'<span class="course-price">'+
								'￥'+course[i].price+
							'</span></li>';
						}
					}
					
					//视频
					if(video_count>0){
						for(var i=0;i<video.length;i++){
				              video_innser += '<li class="video-li" data-id="'+video[i].cid+'">'+
				                  '<div class="video-img"><img class="embed play-icon" src="/public/mobile/img/icon/play-iconb.png"/></div>'+
				                  '<div class="video-content"><h2 class="class-header">'+video[i].title+'</h2>'+
				                  '</div></li>';
						}
					}
					if(course_count==0 && video_count==0){
						$('.seachOne-num').eq(1).html('');
						$('.seachOne-num').eq(2).html('');
						$('.list-content').hide();
						$('.hot-search').show().html('没有搜索到相关内容，请尝试不同名称');
					}else{
						if(course_count>0){
							$('.seachOne-num').eq(1).html(course_count+'相关课程');
						}else{
							$('.seachOne-num').eq(1).html();
						}
						if(video_count>0){
							$('.seachOne-num').eq(2).html(video_count+'相关视频');
						}else{
							$('.seachOne-num').eq(2).html('');
						}
						$('.mui-table-view').html(course_innser);
						$('.navigate-ul').html(video_innser);
					}
					
			      }else{
			      	mui.alert('数据请求失败');
			      }
			    }
			});
		}

		</script>
		<script src="/public/mobile/js/bindmobile.js"></script>
	</body>
</html>
