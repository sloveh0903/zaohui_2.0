<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:41:"./template/mulit/wechat/member/index.html";i:1518064648;s:41:"./template/mulit/wechat/common/share.html";i:1518064648;s:40:"./template/mulit/wechat/common/menu.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>会员中心</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/member.css" />		
		<script src="/public/mobile/js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/mui.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/globla.js"></script>
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
				<img src="" class="face" />
				<div class="headl-right">
					<?php if($vip == 0): ?>
					<p class="nickname"></p>
					<?php else: ?>
					<p class="nickname"></p>
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
			<div class="headr">
				<div class="sign" id="sign" style="display: none;">
					<i><img src="/public/mobile/img/icon/sign@2x.png"/ alt="签到"></i>
					<span>签到</span>
				</div>
				<div class="signed" id="signed" style="display: none;">
					<span>已签到</span>
				</div>
			</div>
		</div>
		<div class="merber-1 mui-row font-color-6">
			<div>
				<span>完成课程</span>
				<span id="coursenum"></span>
			</div>
			<div id="jifen">
				<span>积分</span>
				<span id="point"></span>			
			</div>
			<div>
				<span>班级排名</span>
				<span id="ranking"></span>
			</div>
		</div>
		<div class="mui-row merber-2 font-color-6">
			<ul>
				<li>
					<i id="Monday"></i>
					<span>周一</span>
				</li>
				<li>
					<i id="Tuesday"></i>
					<span>周二</span>
				</li>
				<li>
					<i id="Wednesday"></i>
					<span>周三</span>
				</li>
				<li>
					<i id="Thursday"></i>
					<span>周四</span>
				</li>
				<li>
					<i id="Friday"></i>
					<span>周五</span>
				</li>
				<li>
					<i id="Saturday"></i>
					<span>周六</span>
				</li>
				<li>
					<i id="Sunday"></i>
					<span>周天</span>
				</li>
			</ul>
		</div>
		<div class="mui-row merber-list font-color-8">
			<ul class="iterm-box">
				<?php if($user_card): ?>
				<li class="createVIP">
					<a href="<?php echo url('/wechat/course/card'); ?>" >
						<div class="iterm-img">
							<img src="/public/mobile/img/icon2/icon_vip@3x.png" alt="icon"/>
							<img src="" alt="icon" class="got-VIP" style="display: none;"/>
						</div>
						<div class="iterm-content">
							<span>我的VIP</span>	
							<?php if($vip == 0): ?>
							<i class="open">开通VIP</i>
							<?php else: ?>
							<i class="arrow mui-icon mui-icon-forward"></i>
							<?php endif; ?>
						</div>	
					</a> 
				</li>
				<?php endif; ?>
				<li>
					<a href="<?php echo url('/wechat/member/order'); ?>">
						<div class="iterm-img">
							<img src="/public/mobile/img/icon2/icon_bought@3x.png" alt="icon"/>
						</div>
						<div class="iterm-content">
							<span>已购买&nbsp;&nbsp;<span id="order"></span></span>
							<i class="mui-icon mui-icon-forward"></i>
						</div>
					</a>
				</li>
				<li>
					<a href="<?php echo url('/wechat/member/ask'); ?>">
						<div class="iterm-img">
							<img src="/public/mobile/img/icon2/icon_question@3x.png" alt="icon"/>
						</div>
						<div class="iterm-content">
							<span>我的提问&nbsp;&nbsp;<span id="ask"></span></span>
							<i class="mui-icon mui-icon-forward"></i>
						</div>
					</a>
				</li>
				<li>
					<a href="<?php echo url('/wechat/member/answer'); ?>">
						<div class="iterm-img">
							<img src="/public/mobile/img/icon2/icon_answer@3x.png" alt="icon"/>
						</div>
						<div class="iterm-content">
							<span>我的解答&nbsp;&nbsp;<span id="answer"></span></span>
							<i class="mui-icon mui-icon-forward"></i>
						</div>
					</a>
				</li>
				<li>
					<a href="<?php echo url('/wechat/member/studylist'); ?>">
						<div class="iterm-img">
							<img src="/public/mobile/img/icon2/icon_studyrecord@3x.png" alt="icon"/>
						</div>
						<div class="iterm-content">
							<span>学习记录</span>
							<i class="mui-icon mui-icon-forward"></i>
						</div>
					</a>
				</li>
				<li class="rebate" style="display: none">
					<a href="<?php echo url('/wechat/rebate/index'); ?>">
						<div class="iterm-img">
							<img src="/public/mobile/img/icon2/icon__distribution@3x.png" alt="icon"/>
						</div>
						<div class="iterm-content">
							<span>我的分销</span>
							<i class="mui-icon mui-icon-forward"></i>
						</div>
					</a>
				</li>
				<li>
					<a href="<?php echo url('/wechat/member/setup'); ?>">
						<div class="iterm-img">
							<img src="/public/mobile/img/icon2/icon_set@3x.png" alt="icon"/>
						</div>
						<div class="iterm-content">
							<span>设置</span>
							<i class="mui-icon mui-icon-forward"></i>
						</div>
					</a>
				</li>
			</ul>
		</div>
		<div class="grazy-copyright hastab">
			<i>格子匠 GRAZY.CN 技术支持</i>
	   </div>
	   <!--文本提示框-->
		<div class="toast-box limit">
			<div class="toast-main" id="toast-main"></div>
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


	</body>
<script type="text/javascript">

	document.getElementById('jifen').addEventListener('tap',function(){
		 window.location.href = "<?php echo url('wechat/member/jifen'); ?>"
	})

    var uid = '<?php echo $userinfo['uid']; ?>'; //用户id
    var isbind = '<?php echo $userinfo['is_bind']; ?>';
    var token = '<?php echo $userinfo['token']; ?>';
    var vip = '<?php echo $vip; ?>';
	document.getElementById("sign").addEventListener('tap',function(){
		$.ajax({
	        url:host + 'integral/sign/',
	        data:{
	            uid:uid,
	            is_json:1
	        },
	        type:'GET', //GET
	        async:true,    //或false,是否异步
	        timeout:5000,    //超时时间
	        dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
	        success:function(data,textStatus,jqXHR){
	        	if(data.data.integral_code==1){
	        		var msg = data.data.msg;
	        		document.getElementById('toast-main').innerHTML=msg;
	        		toastBox();
	        		var point = parseInt($('#point').html())+data.data.integral;
	        	    $('#point').html(point);
	        		document.getElementById('sign').style.display='none';
	        		document.getElementById("signed").style.display='block';
	        	}
	        }
	    })
		
	});
    $.ajax({
        url:host + 'integral/checksign/',
        data:{
            uid:uid
        },
        type:'GET', //GET
        async:true,    //或false,是否异步
        timeout:5000,    //超时时间
        dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
        success:function(data,textStatus,jqXHR){
        	if(data.integral_code==1){
        		document.getElementById('sign').style.display='none';
        		document.getElementById("signed").style.display='block';
        	}else{
        		document.getElementById('sign').style.display='block';
        		document.getElementById("signed").style.display='none';
        	}
        }
    })
    
    $.ajax({
        url:host + 'user/center/index',
        data:{
            uid:uid,
            token:token
        },
        type:'GET', //GET
        async:true,    //或false,是否异步
        timeout:5000,    //超时时间
        dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
        success:function(data,textStatus,jqXHR){
        	if(data.code==-2){
        		mui.alert('token验证失败');
        		return false;
        	}
        	if(data.code==-3){
        		location.reload();
        	}
            if(data.code==1){
                var result = data.data;
                var studyinfo = "自"+result.study_begin+"学习了"+result.study_day+"天";
                var new_comment = "";
                if(result.user.is_rebate == 1){
                	$('.rebate').show();
                	//$('.head').hide();
                }
                $(".money").text(result.user.money);
                $(".face").attr('src',result.user.face);
                $('.bg').css("background","url("+result.user.face+") no-repeat center");
                if(Number(vip) > 0){
                    nk = result.user.nickname+'<span class="VIP_logo">VIP</span>';
                }else{
                    nk = result.user.nickname;
                }
                $(".nickname").html(nk);
                $(".studyinfo").html(studyinfo);
                $("#coursenum").html(result.study_complete);
                $("#point").html(result.user.now_integral);
                if(result.week.Monday){
                    $("#Monday").html("<em></em>");
                    $("#Monday").addClass('active');
				}
                if(result.week.Tuesday){
                    $("#Tuesday").html("<em></em>");
                    $("#Tuesday").addClass('active');
                }
                if(result.week.Wednesday){
                    $("#Wednesday").html("<em></em>");
                    $("#Wednesday").addClass('active');
                }
                if(result.week.Thursday){
                    $("#Thursday").html("<em></em>");
                    $("#Thursday").addClass('active');
                }
                if(result.week.Friday){
                    $("#Friday").html("<em></em>");
                    $("#Friday").addClass('active');
                }
                if(result.week.Saturday){
                    $("#Saturday").html("<em></em>");
                    $("#Saturday").addClass('active');
                }
                if(result.week.Sunday){
                    $("#Sunday").html("<em></em>");
                    $("#Sunday").addClass('active');
                }
                $("#ranking").html(result.ranking);
                $("#studytime").html(result.study_time);
                //$("#favorite").html(result.favorite_count);//收藏 去除
                $("#order").html(result.order_count);
                //$("#follow").html(result.follow);//关注去除
                $("#ask").html(result.ask_count);
                $("#answer").html(result.answer_count);
                // if(result.new_comments > 0){
                //     new_comment = result.new_comments+"条新回答";
                //     $("#new_comment").html(new_comment);
                //     $('#new_comment').next().addClass('red');
                // }


            }
        }
    })
	
</script>
	<script src="/public/mobile/js/bindmobile.js"></script>
</html>