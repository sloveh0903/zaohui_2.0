<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:46:"./template/mulit/wechat/ordersubmit/index.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8" />
<meta name="viewport"
	content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<title>订单确认</title>
<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
<link rel="stylesheet" href="/public/mobile/css/global.css" />
<link rel="stylesheet" href="/public/mobile/css/order-submit.css" />
<script src="/public/mobile/js/jquery-3.2.1.min.js"></script>
<script src="/public/mobile/js/mui.min.js"></script>
<script src="/public/mobile/js/globla.js"></script>
</head>
<body>
<input type="hidden" name='coupon_minus' class='coupon_minus' value="0" >
	<?php if($error_tip): ?>
	<!--         <div style="text-align: center;margin-top: 100px;margin-left: 100px;"> -->
	<!--         <p style="width:90%;"><?php echo $error_tip; ?></p> -->
	<!--         </div> -->
	<?php else: ?>
	<div class="order_content">
		<!--订单信息-->
    <?php if($course): ?>
    <ul class="course-list">
      <li>
        <div class="teacher-box">
          <img src="<?php echo $course['face']; ?>">
        </div>
        <div class="course-content">
          <h1><?php echo $course['title']; ?></h1>
          <span><?php echo $course['desc']; ?></span>
         
        </div>
        <span class="course-price">￥<?php echo $course['price']; ?></span>
      </li>
    </ul>
		
		<?php elseif($user_card): ?>
		<div class="vip-content">
			<ul>
				<li class="cur" data-id="<?php echo $usercard_id; ?>">
					<div class="con-left">
						<p>
							<span>VIP</span><?php echo $user_card['title']; ?>
						</p>
						<?php if($user_card['type'] == 'mouth'): ?> <i>一个月内可观看所有课程</i> <?php endif; if($user_card['type'] == 'season'): ?> <i>三个月内可观看所有课程</i> <?php endif; if($user_card['type']== 'year'): ?> <i>一年内可观看所有课程</i> <?php endif; if($user_card['type'] == 'life'): ?> <i>可永久观看所有课程</i> <?php endif; ?>
					</div>
					<p class="con-right">
						￥<span><?php echo $user_card['price']; ?></span>
					</p>
				</li>
			</ul>
		</div>
		<?php elseif($packageList): ?>
		<div class="bundle-box">
			<div class="bundle-title-box"></div>
			<ul class="bundle-list">
				<li data-id="<?php echo $package_id; ?>">
					<div class="bundle-showbox">
						<div class="img1">
							<img src="<?php echo $packageList['banner']; ?>" alt="banner2">
						</div>
						<?php if(is_array($packageList['banner_color']) || $packageList['banner_color'] instanceof \think\Collection || $packageList['banner_color'] instanceof \think\Paginator): $i = 0; $__LIST__ = $packageList['banner_color'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?>
						<div class="img2" style="background: <?php echo $data; ?>"></div>
						<?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
					<div class="bundle-content">
						<h1><?php echo $packageList['title']; ?></h1>
						<i class="bundle-price">¥<?php echo $packageList['price']; ?></i>
					</div>
				</li>
			</ul>
		</div>
		<?php endif; ?>
		<div class="order-iterm-box">
			<div class="iterm-shuoming-head">
				<p class="fc-4 fs-12">购买后1年内有效</p>
			</div>
			<ul class="order_list_ul">
				  <!--优惠券-->
    			<li class="coupon">
	    			<div class="iterm-left">
	    				<p>优惠码</p>				
					</div>
					<div class=" iterm-right">				
						<p class="add_coupon">
							<span></span><i style='align-items: center; font-style: normal;'>添加</i>
						</p>
						<p class="youhui"></p>				
					</div>
    			</li>
    			<!--积分-->
    			<?php if($integral_data['status']==1): ?>
    			<li class="jifen">
	    			<div class="iterm-left">
	    				<p>积分抵扣</p>				
					</div>
					<div class="iterm-right">				
						<p class="jifen-num"><?php echo $integral_data['deducted_integral']; ?>积分可用</p>
						<p class="jifen-open" style="display: none;"><?php echo $integral_data['deducted_integral']; ?>积分<span class="jifen-money">-￥<?php echo $integral_data['deducted_money']; ?></span></p>
						<div class="mui-switch mui-switch-mini" id="mySwitch">
						  <div class="mui-switch-handle"></div>
						</div>
					</div>
    			</li>
    			<?php endif; ?>
    		</ul>
    		<ul class="order_list_ul ordersubmit-totalprice">
    			<!--实付金额-->
    			<li class="sub_money">
    				<div class="iterm-left">
	    				<p>实付金额</p>				
					</div>
					<div class="iterm-right">				
						<p class="sub">
							￥<span><?php echo $old_price; ?></span>
						</p>			
					</div>
    			</li>
    		</ul>
    		<div class="iterm-shuoming">
				<p><?php if($integral_data['status']==1): ?><p>付款后可得<?php echo $integral_data['consume_integral']; ?>积分</p><?php endif; ?></p>
			</div>
		</div>
		
		<!--版权支持-->
		<div class="grazy-copyright bottom-fixed hastab">
			<i>格子匠 GRAZY.CN 技术支持</i>
		</div>
		<!--确认支付-->
		<i class="confirm" data-status="0">确认支付</i>

	</div>
	<?php endif; ?>
	<!--弹框-->
	<div class="box-shadow">
		<div class="alert-box">
			<div id="confirm-box" class="confirm-box">
				<img src="/public/images/coupon.png" title="" alt="" />
				<div class='formdiv'>
					<input type="text" id='coupon_codeid' value="" placeholder="请输入优惠码"
						onfocus="if(value==defaultValue){ value='';this.style.color='rgba(41, 43, 51, .8)'}"
						onblur="if(!value){value=defaultValue;this.style.color='rgba(41, 43, 51, .4)'}" />
					<span></span>
					<button class="confirm-btn" type="submit">确认</button>
				</div>
			</div>
			<div class="close-img">
				<img src="/public/image/wxalertcancel.png" alt="关闭按钮" />
			</div>
		</div>
	</div>

</body>
<script type="text/javascript">
		$(function(){
			
			var checkSubmitFlg = false; 
			var error_tip = "<?php echo $error_tip; ?>";
			if(error_tip){
				window.location.href='/wechat/index/';
			}
			var old_price = "<?php echo $old_price; ?>";
			var back_url = "<?php echo $back_url; ?>";
			var uid = "<?php echo $uid; ?>";
			var order_type = "<?php echo $order_type; ?>";
			var cid = "<?php echo $cid; ?>";
			var package_id = "<?php echo $package_id; ?>";
			var usercard_id = "<?php echo $usercard_id; ?>";
			var openid = '<?php echo $userinfo['openid']; ?>'; //微信openid
			var uname = '<?php echo $userinfo['uname']; ?>';
			var source = 'wechat';//微信公众号
			var status = "<?php echo $integral_data['status']; ?>";
			if(status==1){
				//积分开关
				mui('.mui-switch')['switch']();
				document.getElementById("mySwitch").addEventListener("toggle",function(event){
					  if(event.detail.isActive){
						  check_intergral(uid,old_price,1);
							$('.jifen-num').css('display','none')
							$('.jifen-open').css('display','block')
					  }else{
						  check_intergral(uid,old_price,0);
							$('.jifen-num').css('display','block')
							$('.jifen-open').css('display','none') 
					  }
					})	
			}
			
			
			//点击添加弹出弹框
			$(".add_coupon").click(function(){
				$(".box-shadow").css("display","flex");
				$(".youhui").css("display","none");
				$(".confirm_box").css("display","block");
				
			})
			//点击取消弹框消失
			$(".close-img img").click(function(){
				$(".box-shadow").hide();
				$(".youhui").hide();
				//total();
			})
			//点击垃圾筐取消优惠码优惠
			$(".youhui").click('.delete',function(){
				$(".confirm").attr('data-coupon-code','');
				$(".youhui").css("display","none");
				$('.add_coupon').css("display","flex");
				$('.sub').html('<span>'+old_price+'</span>');
				$('#coupon_codeid').val('');
				$('.coupon_minus').val(0);
				 var is_switch = $('.mui-switch').hasClass('mui-active');
				 var is_switch = $('.mui-switch').hasClass('mui-active');
					 if(is_switch==false){
						var is_switch = 0;
						check_intergral(uid,old_price,0);
					}else{
						var is_switch = 1;
						check_intergral(uid,old_price,1);
					}
			})
			
			//优惠码提交验证
			$(".confirm-btn").click(function(){
				var cid = "<?php echo $cid; ?>";
				var usercard_id = "<?php echo $usercard_id; ?>";
				var package_id =  "<?php echo $package_id; ?>";
				var coupon_code = $('#coupon_codeid').val();
				var order_type = "<?php echo $order_type; ?>";
				var is_switch = $('.mui-switch').hasClass('mui-active');
				 if(is_switch==false){
						var is_switch = 0;
					}else{
						var is_switch = 1;
					}
				if(coupon_code){
					$.get(host + "couponcode/check_code?coupon_code=" + coupon_code+'&cid='+cid+'&usercard_id='+usercard_id+'&package_id='+package_id+'&order_type='+order_type+'&price='+old_price+'&is_switch='+is_switch, function (data) {
						var message = data.message;
						if(data.code==1){
							   var conpon_data = data.data;
							   var tip = conpon_data.tip;
							   var minus = conpon_data.minus;
							   var minus_name = conpon_data.minus_name;
							   var order_price = conpon_data.order_price;
							   $('.coupon_minus').val(minus);
							   $('.formdiv span').html('');
							   $('.add_coupon').hide();
							   $('.confirm_box').hide();
							   $('.box-shadow').hide();
							   $(".youhui").show();
							   $('.youhui').html(tip+'<span>'+minus_name+'</span><i class="delete" style="background:url(/public/images/delete.png) no-repeat center/cover ;"></i>');
	 						   $('.sub').html('<span>'+order_price+'</span>');
	 						  var shuoming_html = '<p>付款后可得'+conpon_data.checkData.consume_integral+'积分</p>';
								if(conpon_data.checkData.status==1){
									var jifen_str =conpon_data.checkData.deducted_integral +'积分可用';
						        	var sub_price = '￥'+conpon_data.checkData.order_price;
						        	var open_html =  conpon_data.checkData.deducted_integral +'积分'+'<span class="jifen-money">-￥'+conpon_data.checkData.deducted_money+'</span>';
						        	$('.sub').html(sub_price);
						        	$('.jifen-num').html(jifen_str);
						        	$('.jifen-open').html(open_html);
								}else{
									$('.jifen').hide();
								}
					        	$('.iterm-shuoming').html(shuoming_html);
	 						   
						}else{
							$('#coupon_codeid').val('');
							$('.formdiv span').html(message);
						}
						
					});
				}
				
			})
			//检查积分
			function check_intergral(uid,old_price,is_switch){
				var coupon_minus = $('.coupon_minus').val();
				$.ajax({
			        url:host + 'integral/check_intergral/',
			        data:{
			            uid:uid,
			            old_price:old_price,
			            coupon_minus:coupon_minus,
			            is_switch:is_switch,
			            is_json:1
			        },
			        type:'GET', //GET
			        async:true,    //或false,是否异步
			        timeout:5000,    //超时时间
			        dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
			        success:function(data,textStatus,jqXHR){
							var shuoming_html = '<p>付款后可得'+data.data.consume_integral+'积分</p>';
							if(data.data.status==1){
								var jifen_str =data.data.deducted_integral +'积分可用';
					        	var sub_price = '￥'+data.data.order_price;
					        	var open_html =  data.data.deducted_integral +'积分'+'<span class="jifen-money">-￥'+data.data.deducted_money+'</span>';
					        	$('.sub').html(sub_price);
					        	$('.jifen-num').html(jifen_str);
					        	$('.jifen-open').html(open_html);
							}else{
								$('.jifen').hide();
							}
				        	$('.iterm-shuoming').html(shuoming_html);
			        	
			        }
			    })
			}
			//提交订单
			$(".confirm").click(function(){
				if(checkSubmitFlg ==true){ 
					return false; //当表单被提交过一次后checkSubmitFlg将变为true,根据判断将无法进行提交。 
				} 
				checkSubmitFlg =true; 
				var that = $(this);
				var status = that.attr('data-status');
				//that.text('正在支付');
				if(status == 1){
					return;
				}
				var is_switch = $('.mui-switch').hasClass('mui-active');
				if(is_switch==false){
					var is_switch = 0;
				}else{
					var is_switch = 1;
				}
				var coupon_code = $('#coupon_codeid').val();
				that.attr('data-status',1);
					$.post(host+"order/add",{uid:uid,source:source,coupon_code:coupon_code,order_type:order_type,cid:cid,package_id:package_id,usercard_id:usercard_id,is_switch:is_switch},function(res){
						that.text('确认支付');
						that.attr('data-status',0);
						if(res.code == 1){
							var order_sn = res.data.order_sn;
							if(res.data.pay_status == 1){
								window.location.href=back_url;
								//var url = "<?php echo url('wechat/course/detail'); ?>";
								//window.location.href=url+'?cid='+cid;
							}else{
								$.get(host+"wxpay/unifiedorder?uid="+uid+"&openid="+openid+"&order_sn="+order_sn+'&pay_channel='+order_type, function(res){
									var appId =  String(res.data.appid);
									var timeStamp = String(res.data.timeStamp);
									var nonceStr = res.data.nonce_str
									var package = res.data.package
									var paySign = res.data.sign
									WeixinJSBridge.invoke(
								       'getBrandWCPayRequest', {
								           "appId":appId,     //公众号名称，由商户传入
								           "timeStamp":timeStamp,  //时间戳，自1970年以来的秒数
								           "nonceStr":nonceStr, //随机串
								           "package":package,
								           "signType":"MD5",  //微信签名方式：
								           "paySign":paySign //微信签名
								       },
								       function(res){
								           if(res.err_msg == "get_brand_wcpay_request:ok" ) {
								           		setTimeout(function(){						      
								           			if(subscribe == 1 && is_rebate == 1){
								           				location.reload();
								           			}
								           			if(subscribe == 1 && is_rebate == 0){
								           				//成功购买页面
								           				window.location.href=back_url;
// 								           				var url = "<?php echo url('wechat/course/buysuccess'); ?>?cid="+cid;
// 														window.location.href = url; 
								           			} 
								           			if(subscribe == 0 && is_rebate == 0){
								           				//关注二维码
								           				$('.buy_success_outside').show();
								           			}
								           			if(subscribe == 0 && is_rebate == 1){
								           				location.reload();
								           			}
								           		},1000);
								           		window.location.href=back_url;
// 								           		var url = "<?php echo url('wechat/course/detail'); ?>";
// 								   				   window.location.href=url+'?cid='+cid;
								           }else{
								        	   //alert('支付失败');
								        	   //alert(res.err_msg);
								        	   window.location.href=back_url;
								        	   
								           }
								           // 使用以上方式判断前端返回,微信团队郑重提示：res.err_msg将在用户支付成功后返回    ok，但并不保证它绝对可靠。
								       }
								   );
								});

							}
						}else{
							mui.alert(''+res.message);
							 window.location.href=back_url;
						}
					});
			});
			
			
			
			
		})
	</script>
</html>
