<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:42:"./template/mulit/wechat/course/detail.html";i:1518064648;}*/ ?>
<?php
    $data = $_GET;
    unset($data['code']);
    $data['uname'] = $userinfo['uname'];
    $url_data = '?'.http_build_query($data);
    $link = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL'].$url_data;
?>
<!DOCTYPE html>
<html>
	<head> 
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title><?php echo $title; ?></title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/course.css" />
		<script src="/public/mobile/js/compatible.js"></script>
	</head>
	<body>

<?php if($is_follow): if($userinfo['subscribe'] == 0): ?>
<div class="concern_top">
    <i class="top_close"></i>
    <p class="fs-14">关注公众号便于下次学习</p>
    <div class="concern btn fs-14">关注</div>
  </div>
<?php endif; endif; ?>
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
<div class="contact-dialog" id="kefu2">
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
		<div class="mui-row top-container">
			<video id="Video1" src="" controls="controls" style="float: left; width: 100%; height: 210px; display: none;">
				您的浏览器不支持 video 标签。
			</video>
			<div id="a1"></div>
			<?php if($have_free_video_id): ?>
			<div class="banner-box">
		      <img src=""  class="banner">
		      <div class="banner-mask">
		        <i class="play-icon"></i>
		        <p class="preview">试看</p>
		      </div>
		    </div>
		    <div class="banner"  data-free="1"  data-vid="<?php echo $have_free_video_id; ?>"  style="height: 210px;display:none;"><img src="" height="210px" width="100%"></div>
		    <?php else: ?>
		    <div class="banner" data-free="0"  data-vid=""><img src="" height="210px" width="100%"></div>
			<?php endif; ?>
			<div id="tab-item" class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-vertical">
				<a class="mui-control-item mui-active fc-4" id="active_content1" href="#content1">简介</a>
				<a class="mui-control-item fc-4" id="active_content2"  href="#content2">章节</a>
			</div>
		</div>

	<div id="pullrefresh" class="mui-scroll-wrapper">
		<div class="mui-scroll">
		<div class="mui-row" >
			<div id="tab-content" class="mui-row">
				<!--选项卡1-->
				<div id="content1" style="padding: 0px" class="mui-control-content mui-scroll-wrapper mui-active">
				   <div class="course-jianjie-header">
				   	<span class="course-title">课程标题</span>
				   	<p class="course-desc fc-6">介绍</p>
				   	<i class="price"></i>
				    <p class="share recommend_rebate" href="<?php echo url('/wechat/rebate/sharecourse'); ?>" style="display: none;"></p>
				   </div>
				   <?php if($is_testitemshop==1 && !empty($testitem_url)): ?>
				   <div class="course-practice" data-href='<?php echo $testitem_url; ?>'>
				   		<a href="<?php echo $testitem_url; ?>">
				   			<span>课后练习</span>
				   			<i class="mui-icon mui-icon-forward"></i>
				   		</a>
				   </div>
				  
				   <?php endif; ?>
					<div class="student-evaluate">
				      <div class="student-evaluate-wrap <?php if($checkbuy!=1): ?> course-padding-bottom0  <?php endif; ?>">
					      <p>
					      	<span>学员评价</span>
					      	<i class="comment-more" href="<?php echo url('/wechat/course/comment'); ?>?cid=<?php echo $cid; ?>">查看全部</i>
		                </p>
		<!--                 1  此课程无人评价且用户没购买    2 此课程无人评价 且用户已购买 -->
						<?php if($have_comment==0): ?>
		                <div class="comment-empty <?php if($checkbuy == 1): ?> pb20 <?php endif; ?> " >
		                    <div class="emptystar">
		                      <img src="/public/mobile/img/icon/gray-bigStar.png">
		                      <img src="/public/mobile/img/icon/gray-bigStar.png">
		                      <img src="/public/mobile/img/icon/gray-bigStar.png">
		                      <img src="/public/mobile/img/icon/gray-bigStar.png">
		                      <img src="/public/mobile/img/icon/gray-bigStar.png">
		                    </div>
		                  	<p class="fc-3 fs-14">还没有人评价过</p>
		                </div>
		                <?php endif; ?>
					   	<ul class="student-evaluate-ul <?php if($checkbuy!=1): ?> course-margin-bottom0 <?php endif; ?> ">
					   			
					   	</ul>
				      </div>
					<!-- 检查是否有权限评价 是否已评价 -->
				    <?php if($checkbuy == 1 && $is_comment==0): ?>
              <div class="want-evaluate padding0" id="subcomment">
                <i class="pen-icon"></i>
                <a class="subcomment" href="<?php echo url('/wechat/course/subcomment'); ?>?cid=<?php echo $cid; ?>">我要评价</a>
              </div>
				   	<?php endif; ?>
				   </div> 
				 
				   
					<div class="course-jianjie-content ">  
				   	<span>课程简介</span>
				   	<p class="content-info"></p>
				  </div>
				  <div class="grazy-copyright">
				    <i>格子匠 GRAZY.CN 技术支持</i>
				  </div>
				</div>
				<!--选项卡2-->
				<div id="content2" class="mui-control-content mui-scroll-wrapper">
					<ul class="mui-table-view chapter">

					</ul>
				</div>
				<!--选项卡3-->

				<div id="content3" class="mui-control-content mui-scroll-wrapper">
					<a class="icon-add" href="questions.html"></a>
				</div>

			</div>
		</div>
		
		</div>
		</div>


    <div class="buy-dialog" id="BuyDialog">
    	<div class="alert-box">
        <h1 class="fs-20 fc-8">提示</h1>
        <p class="fs-16 fc-6">请先购买该课程</p>
        <div class="buy-dialog-btn">
          <a class="Cancel">取消</a>
          <a id="onBridgeReady" class="onBridgeReady" data-learning='0' data-pay='0'>购买</a>
        </div>
		  </div>
	  </div>
    

		<div class="contact-dialog">
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
				<i class="dialog-closebtn"></i>
			</div>
		</div>
		<?php if($is_customer): ?>
		<div class="img-customerService"></div>
		<?php endif; ?>
		<div class="buy-status">


			<?php if($vip == 1 || $vip == 2): ?>
			<p class="view" id="have-buy"><span>VIP</span>会员免费看</p>
			<?php else: ?>
			<p class="buy_vip" id="buy-vip" href="<?php echo url('wechat/course/card'); ?>">购买<span>VIP</span></p>
			<p class="buy_price" id="buy" data-learning='0' data-pay='0'>¥ 购买</p>
			<p class="already_buy" id="have-buy">已购买</p>
			<?php endif; ?>

		</div>

		<!-- 购买课程成功弹框start   -->
		<div class="buy_success_outside" style="display:none;">
		 <div class="buy_success_alert">
		    <div class="alert_content">
		       <h3>购买课程成功</h3>
		       <p>请关注公众号 及时掌握学习进度</p>
		       <img src="<?php echo $qrcode; ?>" alt="二维码">
		       <a href="<?php echo url('wechat/course/buysuccess'); ?>?cid=<?php echo $cid; ?>" class="no-follow">暂不关注</a>
		    </div>
		 </div>
		</div>
    <!-- 购买课程成功弹框 end -->
    	<div class="toast-box limit">
			<div class="toast-main" id="toast-main"></div>
		</div>
		<div class="mui-popup-backdrop mui-active" id="backdrop" style="display: none;"></div>
		<script type="text/javascript" src="/public/mobile/js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/mui.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/imgpreview/dialog.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/imgpreview/mobile-photo-preview.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/globla.js"></script>
		<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
		<script type="text/javascript" src="/public/ckplayer/ckplayer.js" charset="utf-8"></script>
		<script type="text/javascript">  
		$(function () {
      mui(".mui-row").on('tap', '.course-practice', function (event) {
        var url = $(this).attr('data-href');
        window.location.href=url;
      });
      $('.dialog-closebtn').click(function(){
        $(this).parents('.contact-dialog').hide();
      });
	  });
    var have_free_video_id = '<?php echo $have_free_video_id; ?>';
    var uid = '<?php echo $userinfo['uid']; ?>';
    var isbind = '<?php echo $userinfo['is_bind']; ?>';
		var openid = '<?php echo $userinfo['openid']; ?>'; //微信openid
		var uname = '<?php echo $userinfo['uname']; ?>';
		var cid = '<?php echo $cid; ?>'; //课程id
		var checkbuy = 0;//是否购买 1已购买
		var vid = '';  //视频id
		var prev_vid = ""; //上个视频id
		var sec = 0; //当前视频播放秒数
		var page = 1; //页码
		var size = 10; //页码数据
		var point = 0; //用户积分
		var is_follow = 0; //0未关注  1已关注
		var course_detail = [];
		var type = 1; //1简介 2章节 3问答
		var video = document.getElementById("Video1");
    var vLength;
    var pgFlag = ""; // used for progress tracking
    var play_status = 0; //0未播放  1播放中 
		var video_path = "";
		var rebate_status = 0;
		var is_rebate = 0;
		var share_link = "";  //分享链接
		var share_desc = ""; //分享标题
		var subscribe = "<?php echo $userinfo['subscribe']; ?>"; //1 关注 0 未关注
		var free_time = 0; //试看时间 秒
		var price = 0;
			//获取课程详情
			$.get(host+"course/detail?cid="+cid+"&uid="+uid, function(result){
				if(result.code != 1){
					mui.alert(result.message);
					$('.mui-scroll').html('');
					return false;
				}
				var is_pay = result.data.is_pay;
				price = result.data.course_detail.price;
				free_time = result.data.free_time;
			    course_detail = result.data.course_detail;
				var user = result.data.user;
				is_rebate = result.data.is_rebate;
				rebate_status = result.data.rebate_status;
				checkbuy = course_detail.checkbuy;
				if(course_detail.is_vip <= 0 && result.data.user_card == 1){
                    $('#buy-vip').show();
				}
				//checkbuy=1  已经购买或者0元课程 todo
				//alert(is_pay);//console.log(result);
				if(checkbuy){
					if(is_pay==1&&price>0){
						$('#buy').hide();
						$('.already_buy').show();
						$('#buy').attr('data-learning',0);
						$('#buy').attr('data-pay',0);
						$('#onBridgeReady').attr('data-learning',0);
						$('#onBridgeReady').attr('data-pay',0);
					}else{
						//价格大于0
						if(price>0){
							$('#have-buy').show();
						}else{
							if(is_pay==1){
								$('#buy').html('立即学习');
								$('#buy').attr('data-learning',1);
								$('#buy').attr('data-pay',1);
								$('#onBridgeReady').attr('data-learning',1);
								$('#onBridgeReady').attr('data-pay',1);
								$('#buy').show();
							}else{
								$('#buy').html('立即报名');
								$('#buy').attr('data-learning',2);
								$('#buy').attr('data-pay',0);
								$('#onBridgeReady').attr('data-learning',2);
								$('#onBridgeReady').attr('data-pay',0);
								$('#buy').show();
							}
						}
					}
					
				}else{
					$('#buy').show();
					if(course_detail.discount_price==0){
						$('#buy').text('￥'+course_detail.price+' 购买');
					}else{
						$('#buy').text('折扣价：￥'+course_detail.discount_price+'');
					}
				}	
				if(course_detail.audit == 0){
					$('#buy').hide();
					$('#have-buy').show();
					$('#have-buy').text('已下架');
				}
				if(course_detail.follow == 1){
					is_follow = 1;
					$('.gz').html('已关注');
				}else{
					$('.gz').html('关注');
				}
				$('.course-title').html(course_detail.title);
				if(course_detail.price ==0){
					$('.price').html('免费');
				}else{
					$('.price').html('￥'+course_detail.price);
				}
				$('.course-desc').html(course_detail.desc);
				$('.course-score').html(course_detail.score);
				$('.course-level').html(course_detail.level);
				$('.course-lenght').html(course_detail.lenght);
				//$('.course-comment').html(course_detail.comment);
				$('.content-info').html(course_detail.content);
				$('.study_count').html(result.data.study_count+'学员');
				$('.banner img').attr('src',course_detail.banner);
				$('.banner-box img').attr('src',course_detail.banner);
                if(is_rebate == '1' && course_detail.rebate_status == '1'){
                    var price_str = '￥'+course_detail.price;
                    var rebate_money = course_detail.rebate_money;
                    $('.recommend_rebate').html('<i></i>赚¥'+rebate_money+'元').show();
                    share_desc = "一起学习，独享"+course_detail.discount+"折优惠"+course_detail.title;
                	share_link = 'http://'+window.location.host+'/wechat/rebate/sharelink.html?cid='+course_detail.cid+'&uname='+uname;
                }else{
                	share_desc = course_detail.desc;
                	share_link = "<?php echo $link; ?>";
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
	                    title: course_detail.title, // 分享标题
	                    desc: share_desc, // 分享描述
	                    link: share_link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
	                    imgUrl: 'http://'+window.location.host+course_detail.banner, // 分享图标
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
	                    title: course_detail.title, // 分享标题
	                    link: share_link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
	                    imgUrl: 'http://'+window.location.host+course_detail.banner, // 分享图标 分享图标
	                    success: function () { 
	                        // 用户确认分享后执行的回调函数
	                    },
	                    cancel: function () { 
	                        // 用户取消分享后执行的回调函数
	                    }
	                });
	            });

	            wx.error(function(res){
	                // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
	            });
	          //星星显示数量
	        	function showstar(star) {
	        		var starNum = parseInt(star)
	        		var numType = starNum % 2
	        		var stars = starNum / 2
	        		var starImg = [];
	        		for (var i = 1; i < 6; i++) {
	        		  if (i <= stars) {
	        			starImg[i] = '/public/images/gray_fullStar.svg'
	        		  } else {
	        			if (numType == 1 && i == parseInt(stars) + 1) {
	        			  starImg[i] = '/public/images/gray_halfStar.svg'
	        			} else {
	        			  starImg[i] = '/public/images/gray_emptyStar.svg'
	        			}
	        		  }
	        		}
	        		return starImg;
	        	}
	        	//秒转换时间格式
	            function formatSeconds(s) {
	        	    var t;
	        	    if (s > -1) {
	        	      var hour = Math.floor(s / 3600);
	        	      var min = Math.floor(s / 60) % 60;
	        	      var sec = s % 60;
	        	      if (hour < 10) {
	        	        t = '0' + hour + ":";
	        	      } else {
	        	        t = hour + ":";
	        	      }

	        	      if (min < 10) { t += "0"; }
	        	      t += min + ":";
	        	      if (sec < 10) { t += "0"; }
	        	      t += sec.toFixed(0);
	        	    }
	        	    return t;
	        	}
				//关注
				mui("body").on('tap', '.gz', function (event) {
					if(is_follow == 0){
						$.post(host+"follow/add",{uid:uid,tid:course_detail.uid},function(res){
							if(res.code == 1){
								$('.gz').html('已关注');
								is_follow = 1;
							}
						});
					}else{
						$.post(host+"follow/del",{uid:uid,tid:course_detail.uid},function(res){
							if(res.code == 1){
								$('.gz').html('关注');
								is_follow = 0;
							}
						});
          }
				})
				//在看视频 标记
				mui("body").on('tap', '.video_li', function (event) {
					 $('.mui-collapse-content').find('.video_li').removeClass('focused');
			         $(this).addClass('focused');
				});
				mui("body").on('tap', '.go-comment', function (event) {
				    var url = "<?php echo url('wechat/course/comment'); ?>";
					window.location.href=url+'?cid='+cid;
				});
        mui(".tab-nav").on('tap', 'a', function (event) {
           var itenindex = $(this).index();
            if(itenindex != 0){
              $('.btn-bottom').hide();
					  }else{
              $('.btn-bottom').show();
					  }
        });
				// //星星评分
				// star_inner = '';
				// var showStarSrc = showstar(course_detail.star);
				// for(var i =1;i<showStarSrc.length;i++){
				// 	star_inner += '<embed src="'+showStarSrc[i]+'" width="24" height="24" type="image/svg+xml" />';
				// }
				// $('.mui-col-xs-8').html(star_inner);
			});

			
			//评论
			$.get(host+"comment/index?cid="+cid+"&uid="+uid+'&page=1&size=3', function(result){
				if(result.code == 1){
					//评价状态
					// if(result.data.comment_status==1){
					// 	comment_status = '<a class="subcomment finished" href="">已评价</a>';
					// 	$('#subcomment').html(comment_status);
					// }
					
          var result = result.data.comment;
          if(result.length == 0 ){
            $('.comment-more').addClass('no-more');
            $('.comment-empty').css('display', 'flex');
          }
					var comment_text = ''; 
					for(var i=0;i<result.length;i++){
						star_inner = '';
						var comment_star =result[i].star;
						for(var j=0;j<5;j++){
							if(j<comment_star){
								star_inner+='<img src="/public/mobile/img/icon/star_full@2x.png">';
							}
							else{
								star_inner+='<img src="/public/mobile/img/icon/star_empty@2x.png">';
							}
						}
						comment_text+='<li>'+
              '<img src="'+result[i].face+'" alt="学员头像">'+
              '<div>'+
                '<p>'+
                  '<i>'+result[i].nickname+'</i>'+
                  '<em></em>'+
                '</p>'+
                '<div class="star-container">'+star_inner+'</div>'+
                '<span class="reply-content">'+result[i].content+'</span>'+
                '<span class="teacher-reply">'+result[i].reply+'</span>'+
              '</div>'+
            '</li>';
					}
					$('.student-evaluate-ul').html(comment_text);
				}
			});
			//微信支付
			mui("body").on('tap', '#onBridgeReady,#buy', function (event) {
				var is_learning = $(this).attr('data-learning');
				var pay =  $(this).attr('data-pay');
				if(is_learning==0&&pay == 0){
					 var url = "<?php echo url('wechat/ordersubmit/index'); ?>";
	   				   window.location.href=url+'?cid='+cid+'&order_type=course';
				}
				else{
					if(is_learning==1){
						$('#active_content1').removeClass('mui-active');
						$('#active_content2').addClass('mui-active');
						$('#content1').removeClass('mui-active'); 
						$('#content2').addClass('mui-active');
					}else{
						$.post(host+"order/add",{uid:uid,source:'wechat',coupon_code:'',order_type:'course',cid:cid,package_id:0,usercard_id:0},function(res){
							if(res.code == 1){
								if(res.data.pay_status == 1){
	 								var url = "<?php echo url('wechat/course/detail'); ?>";
	 								window.location.href=url+'?cid='+cid;
								}
							}else{
								mui.alert(''+res.message);
							}
						});
					}
				}
			});
			

			//播放视频
		    function showPlayer(){
	    		video.src = video_path; 
         	video.load();  
          video.play();
		    }

			//获取视频播放时间 学习时间记录
			var timer2 = true;
			//getvideotime();
			function getvideotime() {
			    timer2 = setTimeout(function () {
					var currentTime = video.currentTime.toFixed(1);
					currentTime = parseInt(currentTime);
					if(currentTime > 0){
						$.post(host+"studyList/update",{uid:uid,vid:vid,study_time:currentTime,status:1},function(result){ });
					}
					getvideotime();
				}, 10000);
			}

    		//加载结束时触发事件
	        video.addEventListener("ended", function () {
	        	clearTimeout( timer2 );
				var play_icon_class = '.play-icon-'+vid;
			    play_status = 0;
			    var currentTime = video.currentTime.toFixed(1);
				currentTime = parseInt(currentTime);
				$.post(host+"studyList/update",{uid:uid,vid:vid,study_time:currentTime,status:2},function(result){ });
	        }, false);

	        //开始播放时触发事件
	        video.addEventListener("playing", function () {
	        	clearTimeout( timer2 );
	        	getvideotime();
	        }, false);

	        //暂停时触发事件
	        video.addEventListener("pause", function () {
	        	clearTimeout( timer2 );
          	}, false);


			$.get(host+"course/chapter?cid="+cid+"&uid="+uid, function(result){
				var video = result.data.video;
				var inner= '';
				var inner_star = '';
				var video_li = '';
				var video_list = [];
				var player;
        if (video.length > 0) {
          for(var i = 0;i<video.length;i++){
					 var n = i+1;
					 inner_star = '<li class="mui-table-view-cell mui-collapse ">'+
							'<a class="mui-navigate-right " id="chaptera" href="#"><p>'+n+'.'+video[i].cate_name+'</p><i class="slidedown-icon"></i></a>'+
							'<div class="mui-collapse-content">'
								//+'<ul class="navigate-ul">'
								
								;
							    video_list = video[i].video_list;
								for(var j = 0;j<video_list.length;j++){
								if(vid == ''){
									vid = video_list[j].id;
									video_path = video_list[j].video_path;
								}
								var free_inner = "";
								var m = j+1;
								var data_free = video_list[j].free;
								var data_path = video_list[j].transcoding_path;
								var data_vid = video_list[j].id;
								var is_study = video_list[j].is_study;
								if(video_list[j].free == 1){
									free_inner = "<i class='free' style='float: none;'>试看</i>";
									
								}
								if(is_study){
									img= '<i class="embed play-icon played-icon play-icon-'+data_vid+'"></i>';
								}else{
									img= '<i class="embed play-icon play-icon-'+data_vid+'"></i>';
								}
									video_li +=
						 				'<div class="video_li" data-free="' + data_free + '" data-path="' + data_path + '" data-vid="' + data_vid + '">'+
                      '<div class="img-box">' + img + '</div>'+
                        '<div class="videoli-content">' +
                        '<div class="class-titlebox">' + '<p class="class-title">' + video_list[j].title + '</p>' + free_inner + '</div>'+
                        '<p class="class-duration">' + n + '.' + m + '&nbsp;&nbsp;' + video_list[j].lenght + '</p>' +
                      '</div>'+
                    '</div>';
								}
            inner = inner_star+video_li;
            var inner_end = 
              //'</ul>'+
            '</div></li>';
            $('.chapter').append(inner+inner_end);
            inner_star = '';
            video_li = '';
            inner_star = '';
            inner = '';
          }
        } else {
          var emptyStr = '<div class="emptypage-wrapper"><div class="empty-box"><img src="/public/mobile/img/icon/chapter-empty.png" class="empty-img"/><p class="empty-text">小编正在上传课程视频</p></div></div>';
          $('#content2').html(emptyStr);
          $('.mui-scroll-wrapper').css('overflow', 'initial');
        }
				
				//checkbuy已经购买或者0元课程
				//点击banner图
				mui(".mui-row").on('tap', '.banner,.banner-mask', function (event) {
					$('.banner-box').css('display','none');
					if(checkbuy != 1){
						if(have_free_video_id ==0){
							$("#BuyDialog").addClass("buy-dialog-in");
							$(".mui-popup-backdrop").show();
							$('.banner-box').css('display','block');
							return false;
						}
						else{
							var that = $('.banner');
							$.get(host+"studyList/freevideo?cid="+cid+"&uid="+uid, function(res){
							    var free = res.data.free;
	          					$(that).attr('data-vid',res.data.vid);
	          					$(that).attr('data-path',res.data.transcoding_path);
								playvideo(that,player);
							});	
						}
						
					}else{
						var that = that = $('.banner');;
						$.get(host+"studyList/lately?cid="+cid+"&uid="+uid, function(res){
						    var free = res.data.free;
          					$(that).attr('data-vid',res.data.vid);
          					$(that).attr('data-path',res.data.transcoding_path);
							playvideo(that,player);
						});	
					}
				});

				//点击视频列表播放	
				mui("#pullrefresh").on('tap', '.video_li', function (event) {
					// $('.banner-box').css('display','none');
					var free = $(this).attr('data-free');
					var that = this;

					//未购买 试看 
				    if(checkbuy != 1 && free == 1){
						playvideo(that,player);
					}
					//未购买 非试看
					if(checkbuy != 1 && free == 0){
						$("#BuyDialog").addClass("buy-dialog-in");
						$('.banner-box').css('display','block');
						$(".mui-popup-backdrop").show();
						return false;
					}



					//已购买
					if(checkbuy == 1){
						playvideo(that,player);
					}
				})
			});

			function playvideo(that,player){
				$('video').css('width','100%');
				$('video').css('display','block');
				$('.banner').css('display','none');
				video_path = $(that).attr('data-path');
				vid = $(that).attr('data-vid');
				$.get(host+"studyList/detail?vid="+vid+"&uid="+uid, function(result){
					//新增浏览记录
					$.post(host+"browseRecord/add",{uid:uid,itemid:vid,typeid:2},function(res){
					});
					if(result.code == -1){
						//播放视频
						showPlayer();
						//增加视频学习记录
						$.post(host+"studyList/add",{uid:uid,vid:vid,study_time:1},function(res){
						});
					}else{
						if(vid != prev_vid){
							//$('.play-icon-'+prev_vid).attr('src','/public/mobile/img/icon/play-2.svg');
							//$('.play-icon-'+prev_vid).attr('src','/public/mobile/img/icon/tick.png');
							prev_vid = vid;
							$.post(host+"studyList/add",{uid:uid,vid:vid,study_time:1},function(res){
							});
							//$('.play-icon-'+vid).attr('src','/public/mobile/img/icon/play-1.svg');
							showPlayer();
						}else{
							vid = $(that).attr('data-vid');
							var play_icon_class = '.play-icon-'+vid;
							//$(play_icon_class).attr('src','/public/mobile/img/icon/play-1.svg');
							//$('.play-icon-'+prev_vid).attr('src','/public/mobile/img/icon/tick.png');
							$.post(host+"studyList/add",{uid:uid,vid:vid,study_time:1},function(res){
							});
						}
					}

				});

			};
	
	
			//获取问答
			data={
				page:page,
				size:size
			};
			toList(data,1);//具体取数据的方法


				
			mui.init({
                pullRefresh: {
                    container: '#pullrefresh',
                    // down:{
                    //     auto:false,//可选,默认false.自动下拉刷新一次
                    //     contentdown : "下拉可以刷新",//可选，在下拉可刷新状态时，下拉刷新控件上显示的标题内容
                    //     contentover : "释放立即刷新",//可选，在释放可刷新状态时，下拉刷新控件上显示的标题内容
                    //     contentrefresh : "正在刷新...",//可选，正在刷新状态时，下拉刷新控件上显示的标题内容
                    //     callback: pulldownRefresh
                    // },
                    up : {
                        //height:50,//可选.默认50.触发上拉加载拖动距离
                        //auto:true,//可选,默认false.自动上拉加载一次
                        contentrefresh : "正在加载...",//可选，正在加载状态时，上拉加载控件上显示的标题内容
                        contentnomore:'没有更多数据了',//可选，请求完毕若没有更多数据时显示的提醒内容；
                        callback :pullupRefresh //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
                    }

                }
            });
			
			
			function pulldownRefresh() {
                setTimeout(function() {
                    page = 1;//刷新并显示第一页
                    data={
                        page:page,
						size:size
                    };
                    if($('#content3').hasClass('mui-active')){
						toList(data);//具体取数据的方法
					}else{
						mui('#pullrefresh').pullRefresh().endPulldownToRefresh();
					}
                }, 100);
            }
			
			function pullupRefresh() {
                setTimeout(function() {
                    page++;//翻下一页
                    data={
                        page:page,
						size:size
                    };
                    //toList(data,type);//具体取数据的方法
					if($('#content3').hasClass('mui-active')){
						toList(data);//具体取数据的方法
					}else{
						mui('#pullrefresh').pullRefresh().endPullupToRefresh();
					}
                }, 100);
            }
			
			function toList(data) {
                $.ajax({
                    url:host+"ask/index?cid="+cid+"&uid="+uid,
                    data:data,
                    type:'GET', //GET
                    async:true,    //或false,是否异步
                    timeout:5000,    //超时时间
                    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                    success:function(result,textStatus,jqXHR){
						if(result.code != 1){
							mui.alert('获取问答数据失败');return false;
						}
						point = result.data.point;
						var ask = result.data.ask;
						var inner = '';
					
						if(ask.length == 0){
							 mui('#pullrefresh').pullRefresh().endPullupToRefresh(true);
						}
						for(var i=0;i<ask.length;i++){
							var imgs = ask[i].photopath;
							var img_inner = '';
							for(var j=0;j<imgs.length;j++){
								img_inner+='<a href="'+imgs[j]+'"class="preview">'+
								'<img src="'+imgs[j]+'"></a>';
							}
							if(ask[i].answer.has_likes == 0 || ask[i].answer.has_likes == undefined){
								var zan = '<i class="icon-zan zan-i"></i><i class="icon-zaned zan-i" style="display: none;"></i>';
							}else{
								var zan = '<i class="icon-zan zan-i" style="display: none;"></i><i class="icon-zaned zan-i"></i>';
							}

							if(ask[i].answer.content == undefined){
								ask[i].answer.content = "";
							}
							inner += '<div class="QA" data-id="'+ask[i].id+'">'+
								'<img src="'+ask[i].face+'" class="hp">'+
								'<div class="name"><span class="font-size-16 font-color-8">'+ask[i].nickname+'</span>'+ask[i].create_time+'</div>'+
								'<p class="font-size-16 font-color-8">'+ask[i].title+'</p>'+
								'<div class="preview-list">'+img_inner+'</div>'+
								'<span class="font-size-14 font-color-4 QA-1">'+ask[i].comments+'条解答</span>'+
								'<p class="QA-2 font-color-8">'+ask[i].answer.content+'</p>'+
								'<div class="QA-3">'+
									'<span  class="zan" data-has-like="'+ask[i].answer.has_likes+'" data-id="'+ask[i].answer.id+'">'+zan+'<font>'+ask[i].likes+'</font></span>'+
									'<span><i class="icon-qa"></i><font>'+ask[i].comments+'</font></span>'+
								'</div>'+
							'</div>';
						}
						if(page == 1){
							$('#content3').html(inner);
							mui('#pullrefresh').pullRefresh().endPulldownToRefresh();
						}else{
							$('#content3').append(inner);
							mui('#pullrefresh').pullRefresh().endPullupToRefresh();
						}
					
						//点赞
						mui("#pullrefresh").on('tap', '.zan-i', function (event) {
							var that = this;
							var itemid = $(that).parent().attr('data-id');
							var has_likes = $(that).parent().attr('data-has-like');
							var likes = Number($(that).parent().find('font').html());
							if(has_likes == 0){
								$.post(host+"like/add",{uid:uid,itemid:itemid,typeid:2},function(res){ 
									if(res.code == 1){
										$(that).parent().find(".icon-zan").css('display','none');
										$(that).parent().find(".icon-zaned").css('display','block');
										//$(that).children("i").toggle();
										$(that).parent().attr('data-has-like',1);
										$(that).parent().find('font').html(likes+1);
									}else{
										mui.alert('点赞失败');
									}
								});
							}
						})
				
                mui('.mui-slider').slider({
                    interval: 3000
                });
                      
              }
            })
          }

			

			$('.preview-list').MobilePhotoPreview({
				trigger: '.preview',
			});


		
			mui("body").on('tap', '.QA p,.name,.preview-list', function (event) {
					var id = $(this).parent().attr('data-id');
	                url = "<?php echo url('wechat/ask/detail'); ?>";
	                window.location.href = url + '?id=' + id;
			});
			$(function() {
				// mui("body").on('tap', '#buy', function (event) {
				// 	if(checkbuy == 0){
				// 		$("#BuyDialog").addClass("buy-dialog-in");
				// 		$(".mui-popup-backdrop").show();
				// 	}
				// })
				mui("body").on('tap', '.Cancel,#backdrop', function (event) {
					$("#BuyDialog").removeClass("buy-dialog-in");
					$(".mui-popup-backdrop").hide();
					$('#kefu').hide();
					$('.banner-box').css('display','block');
				})
			})
			
      
		</script>
		<script>
	   /* 课程购买成功弹窗消失 */
	   $(document).bind("click",function(e){
	      var target = $(e.target),
	          str = ".buy_success_alert",
	          thisParent = target.closest( str );
	      if( !thisParent.is(str)){
	         $(".buy_success_outside").hide();
	      }   
	   }) 

	   
	    mui(".buy_success_alert").on('tap', '.no-follow', function (event) {
			window.location.href = $(this).attr('href');
		})

	    mui("#content1").on('tap', '.comment-more', function (event) {
        if($(this).hasClass('no-more')) {
          //mui.toast('还没有人评价过');
          document.getElementById('toast-main').innerHTML='还没有人评价过';
           toastBox();
          return false;
        }
			window.location.href = $(this).attr('href');
		})

	    mui("#content1").on('tap', '.subcomment', function (event) {
      if($(this).hasClass('finished')) {
        return false;
      }
			window.location.href = $(this).attr('href');
		})
		
	    mui(".course-jianjie-header").on('tap', '.recommend_rebate', function (event) {
			window.location.href = $(this).attr('href');
		});
       mui(".buy-status").on('tap', '#buy-vip', function (event) {
           window.location.href = $(this).attr('href');
       });
       if(($('.coursedetail-main-box .lower').innerHeight() < ($(window).innerHeight() - 324)) && ($('.coursedetail-main-box .content-right').innerHeight() < ($(window).innerHeight() - 290))) {
           $('.footer').css({'position': 'absolute', 'bottom': '20px', 'width': '100%'});
       }
     //客服
       $('.img-customerService').click(function(){
       	$('#kefu2').css('display', 'flex');
       });
       $('.concern_top').on('click','.concern',function () {
        $('#guanzhu').css('display', 'flex');
      });
       $('.contact-dialog').on('click', '.dialog-closebtn', function(){
           $(this).parents('.contact-dialog').hide();
       });
     
    //切换tab栏时，回顶
    mui('#tab-item').on('tap', '.mui-control-item', function () {
      $('.mui-scroll').css('transform', 'translate3d(0, 0, 0)');
    })
	  </script>
		<script src="/public/mobile/js/bindmobile.js"></script>
	</body>
</html>