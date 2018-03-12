<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:41:"./template/mulit/wechat/member/order.html";i:1518064648;s:41:"./template/mulit/wechat/common/share.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>已购买</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/my-order.css" />
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
		<div class="mui-row collection-list">
			<div id="tab-item" class="mui-segmented-control mui-segmented-control-inverted mui-segmented-control-vertical">
				<a class="mui-control-item mui-active" id="content1">课程</a>
				<a class="mui-control-item " id="content2">套餐</a>
			</div>
			 <div id="pullrefresh" class="mui-scroll-wrapper course-wrapper">
            	<div class="mui-scroll list-content">
				    <ul id="order" class="course-list">
				    </ul>
				</div>
            </div>
            
            <div id="pullrefresh" class="mui-scroll-wrapper package-wrapper">
            	<div class="mui-scroll list-content">
				    <div class="bundle-showbox-div">
				    </div>
				</div>
            </div>
		</div>
		<script type="text/javascript" src="/public/mobile/js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/mui.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/globla.js"></script>
	</body>
	<script>
	var uid = '<?php echo $userinfo['uid']; ?>';
    var isbind = '<?php echo $userinfo['is_bind']; ?>';
    var type = 1;
	$(function(){
		    $('.package-wrapper').hide();
	        var page = 1;
	        var size = 10;
	        getlist();
	        
	        //点击tab 切换
	        $('#content1').on('click', function (event) {
	        	$("#order").show().html('');
	        	$(".bundle-showbox-div").html('');
	        	$(".emptypage-wrapper").remove();
	        	$('.course-wrapper').show();
	        	$('.package-wrapper').hide();
	        	type = 1;
	        	page = 1;
		        size = 10;
	        	getlist();
	        	mui('.course-wrapper').pullRefresh().endPullupToRefresh();
	        	mui('.course-wrapper').scroll().scrollTo(0,0,100);//100毫秒滚动到顶
	        });
	        $('#content2').on('click', function (event) {
	        	$('.mui-scroll-wrapper').hide();
	        	$("#order").hide().html('');
	        	$(".bundle-showbox-div").html('');
	        	$(".emptypage-wrapper").remove();
	        	$('.course-wrapper').hide();
	        	$('.package-wrapper').show();
	        	type = 2;
	        	page = 1;
		        size = 10;
	        	getpackagelist();
	        	mui('.package-wrapper').pullRefresh().endPullupToRefresh();
	        	mui('.package-wrapper').scroll().scrollTo(0,0,100);//100毫秒滚动到顶
	        	 
	       });
	        function getlist(){
	            $.ajax({
	            url:host + 'order/index',
	                data:{
	                    uid:uid,
	                    page:page,
	                    size:size
	                },
	                type:'GET', //GET
	                async:true,    //或false,是否异步
	                timeout:5000,    //超时时间
	                dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
	                success:function(data,textStatus,jqXHR){
	                    if(data.code==1){
	                        var list = "";
	                        var result = data.data;
	                        $.each(result.order,function(j,item){
								var pay_price_html = item.pay_price_html;
	                            list+='<li data-src="/wechat/course/detail?cid='+item.cid+'">'+
                                      '<div class="teacher-box">'+
                                        '<img src="'+item.face+'" />'+
                                      '</div>'+
                                      '<div class="course-content">'+
                                        '<h1>'+item.title+'</h1>'+
                                        '<span>'+item.desc+'</span>'+
                                        '<i>'+item.study_count+'人在学</i>'+
                                      '</div>'+
                                      '<span class="course-price">'+ pay_price_html + '</span>'+
                                    '</li>'; 
	                        });
	                        if(result.order.length > 0){
	                            $("#order").append(list);
	                            course_copyright();
	                        }else{
	                        	$(".emptypage-wrapper").remove();
	                        	if(page==1){
	                        		var empty_html = '<div class="emptypage-wrapper"><div class="empty-box"> <img class="empty-img" src="/public/mobile/img/icon/purchase-empty.png"> <p class="empty-text">还没有购买课程</p></div></div>';
		                        	$(".course-wrapper").after(empty_html);
	                        	}
	                        }
	                    }
                      mui('.course-wrapper').pullRefresh().endPullupToRefresh();
	                }
	            })
	           
	        }
	        function getpackagelist(){
	            $.ajax({
	            url:host + 'order/packagelist',
	                data:{
	                    uid:uid,
	                    page:page,
	                    size:size
	                },
	                type:'GET', //GET
	                async:true,    //或false,是否异步
	                timeout:5000,    //超时时间
	                dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
	                success:function(data,textStatus,jqXHR){
	                    if(data.code==1){
	                        var list = "";
	                        var result = data.data;
	                        $.each(result.order,function(j,item){
	                        	var banner = item.banner;
	                        	var banner_list = item.banner_color;
	                        	var imghtml = '';
	                        	imghtml = imghtml +'<div class="bundle-showbox" data-id="'+item.id+'"><div class="img1"><img src="'+banner+'" alt=""></div>';
	                        	for(var i=0;i<banner_list.length;i++){
	                        		imghtml = imghtml +'<div class="img2" style="background:'+banner_list[i]+'"></div>';
	                        	}
	                        	imghtml = imghtml +'</div>';
	                        	list += imghtml;
	                        	list += '<div class="bundle-content"><h1>'+item.title+'</h1><i class="bundle-price">￥'+item.pay_price+'</i></div>';

	                        });
	                        if(result.order.length > 0){
	                        	$(".bundle-showbox-div").html(list);
	                        	package_copyright();
	                        }else{
	                        	$(".emptypage-wrapper").remove();
	                        	if(page==1){
	                        		var empty_html = '<div class="emptypage-wrapper"><div class="empty-box"> <img class="empty-img" src="/public/mobile/img/icon/purchase-empty.png"><p class="empty-text">还没有购买套餐</p></div></div>';
		                        	$(".package-wrapper").after(empty_html);
	                        	}
	                        }
	                    }
	                    mui('.package-wrapper').pullRefresh().endPullupToRefresh();
	                }
	            })
	        }
	        
	        mui('#order').on('tap', 'li', function (event) {
	            var url = $(this).attr('data-src');
	            window.location.href=url;
	        });
	        mui('.bundle-showbox-div').on('tap', '.bundle-showbox', function (event) {
	            var id = $(this).attr('data-id');
	            var url = '/wechat/bundlelist/detail?id='+id+'&type=1';
	            window.location.href=url;
	        });
	        mui.init({
	                pullRefresh: {
	                    container: '#pullrefresh',
	                    up : {
	                        //height:50,//可选.默认50.触发上拉加载拖动距离
	                        //auto:true,//可选,默认false.自动上拉加载一次
	                        contentrefresh : "",//可选，正在加载状态时，上拉加载控件上显示的标题内容
	                        contentnomore:'没有更多数据了',//可选，请求完毕若没有更多数据时显示的提醒内容；
	                        callback :pullupRefresh //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
	                    }
	                }
	            });

	            function pullupRefresh() {
	            	if(type==1){
	            		setTimeout(function() {
		                    page += 1;
		                    getlist();
		                }, 100);
		        	}else{
		        		setTimeout(function() {
		                    page += 1;
		                    getpackagelist();
		                }, 100);
		        	}
	                
	            }
	            
	            function course_copyright() {
                  $('.grazy-copyright').remove();
	                if($(window).innerHeight() - $('.course-list').innerHeight() - 90 < 0) {
	                    var str1 = '<div class="grazy-copyright"><i>格子匠 GRAZY.CN 技术支持</i></div>';
	                    $('#order').after(str1);

	                } else {
	                    var str2 = '<div class="grazy-copyright bottom-fixed"><i>格子匠 GRAZY.CN 技术支持</i></div>';
	                    $('.list-content').after(str2);
	                }
	              }
	            function package_copyright() {
                  $('.grazy-copyright').remove();
	                if($(window).innerHeight() - $('.bundle-showbox-div').innerHeight() - 90 < 0) {
	                    var str1 = '<div class="grazy-copyright"><i>格子匠 GRAZY.CN 技术支持</i></div>';
	                    $('.bundle-showbox-div').after(str1);
	                } else {
	                    var str2 = '<div class="grazy-copyright bottom-fixed" ><i>格子匠 GRAZY.CN 技术支持</i></div>';
	                    $('.list-content').after(str2);
	                }
	              }
	            
	            
	});
	 
	</script>
	<script src="/public/mobile/js/bindmobile.js"></script>

</html>