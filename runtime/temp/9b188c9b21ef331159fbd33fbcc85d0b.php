<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:39:"./template/mulit/wechat/ask/detail.html";i:1518064648;s:41:"./template/mulit/wechat/common/share.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>问答详情</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/ask-details.css" />
		<!-- <link rel="stylesheet" href="/public/mobile/js/imgpreview/dialog.css" /> -->
		<!-- <link rel="stylesheet" href="/public/mobile/js/imgpreview/mobile-photo-preview.css" /> -->
		<!-- <link rel="stylesheet" href="/public/pc/css/jquery.fancybox.css"> -->
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
		<div id="pullrefresh" class="mui-scroll-wrapper">
		<div class="mui-scroll">
		<div class="upper-box">
      <ul class="mui-table-view course-list">
        <li class="course-info" data-cid='<?php echo $course_arr['cid']; ?>'>
          <div class="teacher-box">
            <img src="<?php echo $course_arr['face']; ?>" alt="course-logo" class="course-logo"/>
          </div>
          <div class="course-content">
            <h1><?php echo $course_arr['title']; ?></h1>
            <span><?php echo $course_arr['desc']; ?></span>
          </div>
          <span class="course-price">￥<?php echo $course_arr['price']; ?></span>
        </li>
      </ul>

      <div class="qna-box question-box">
        <div class="qna-top">
          <img src="" class="qna-head">
          <div class="qna-topright">
            <p class="name fs-14 fc-8"></p>
            <div class="qna-infobox">
              <span class="create-time fs-12 fc-4"></span>
            </div>
          </div>
        </div>
        <div class="qna-title fs-18 fc-10"></div>
        <div class="pic preview-list clearfix">
        </div>
      </div>
	  </div>
	      <!--更改部分-->
        <div class="lower-box">
          <div class="mui-row qaInfo-list">
            
          </div>		        
        </div>
        <!--end-->
    </div>
   
    </div>
 <!--  贴底回复按钮 start  -->
    <div class="fixed-btn">
      <i class="reply-btn"></i>
      <p class="fs-16">回答</p>
    </div>
		<div class="qainfo-input" style="display: none;">
			<textarea placeholder="回复" id="text"></textarea>
			<a id="submit">发送</a>
		</div>
		<div class="mui-popup-backdrop mui-active" id="backdrop" style="display: none;"></div>
		<div class="toast-box limit">
			<div class="toast-main" id="toast-main"></div>
		</div>
		<script type="text/javascript" src="/public/mobile/js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/mui.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/imgpreview/dialog.min.js"></script>
		<!-- <script type="text/javascript" src="/public/mobile/js/imgpreview/mobile-photo-preview.min.js"></script> -->
		<script type="text/javascript" src="/public/mobile/js/globla.js"></script>
		<!-- <script type="text/javascript" src="/public/pc/js/jquery.fancybox.js?v=2.1.5"></script> -->
		<script type="text/javascript">
		var  msg  = "<?php echo $msg; ?>";
		if(msg){
			document.getElementById('toast-main').innerHTML=msg;
			toastBox();
		}
			$(document).ready(function () {
// 		        $(".pic").on("focusin", function(){
// 		            $('.fancybox').fancybox();
// 		        });
		    });
		  var is_pay  = <?php echo $is_pay; ?>;
			var id = GetQueryString('id');
      var uid = '<?php echo $userinfo['uid']; ?>'; //用户id
      var isbind = '<?php echo $userinfo['is_bind']; ?>';
			var answer_uid = 0;
			var answer_id = 0;
			var page = 1;
			var size = 10;
			//var host = 'https://api.fuwangdian.com/api/';
			toList();
			$('.mui-scroll-wrapper').css('z-index','0');
			mui.init({
                pullRefresh: {
                    container: '#pullrefresh',
                    up : {
                        //height:50,//可选.默认50.触发上拉加载拖动距离
                        //auto:true,//可选,默认false.自动上拉加载一次
                        contentrefresh : "正在加载...",//可选，正在加载状态时，上拉加载控件上显示的标题内容
                        contentnomore:'没有更多数据了',//可选，请求完毕若没有更多数据时显示的提醒内容；
                        callback :pullupRefresh //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
                    }

                }
            });
			function pullupRefresh() {
				//console.log('下拉');
                setTimeout(function() {
                    page++;//翻下一页
					toList();//具体取数据的方法
					mui('#pullrefresh').pullRefresh().endPullupToRefresh();
                }, 100);
            }
			
			function toList(){
					$.get(host+"ask/detail?id="+id+"&uid="+uid+'&page='+page+'&size='+size, function(result){ 
					//console.log(result);
					if(result.code != 1){
						mui.alert('请求数据失败');return;
					}
          console.log(result);
					var course = result.data.course;
					var question = result.data.question;
					var answer = result.data.answer;
					var photopath = question.photopath;
					var photopath_thumb = question.photopath_thumb;
					$('.lower-box .answer-box .answer-count .count').html(question.comments);
					
					//问题详情
					if(question.anonymous == 1){
						question.face = '/public/image/anonymity.png';
						question.nickname = '匿名';
					}
					$('.qna-box .qna-head').attr('src',question.face);
					$('.qna-box .name').html(question.nickname);
					$('.qna-box .create-time').html(question.create_time);
					$('.qna-box .qna-title').html(question.title);
					img_inner = "";
					for(var i=0;i<photopath.length;i++){
						if(photopath_thumb[i] == '1'){
							img_inner+= '<a class="preview"><img src="' + photopath[i] + '" alt="Q-pic" data-src="'+ photopath[i] +'"></a>'

						}else{
							img_inner+= '<a class="preview"><img src="' + photopath_thumb[i] + '" alt="Q-pic" data-src="'+ photopath[i] +'"></a>'
						}
					}
					$('.question-box .pic').html(img_inner);
          
					var answer_inner = '';
					for(var i=0;i<answer.length;i++){
						if(answer[i].has_likes == 0){
							var zan = '<i class="icon-zan zan-i"></i><i class="icon-zaned zan-i" style="display: none;"></i>';
						}else{
							var zan = '<i class="icon-zan zan-i" style="display: none;"></i><i class="icon-zaned zan-i"></i>';
						}
						if(answer[i].uid == 0){
							answer[i].face = '/public/gzadmin/images/admin_img.png';
							answer[i].nickname = '老师回答';
						}
            var count = answer[i].reply_count;
            if(!count) {
              answer[i].reply_count = '暂无回复';
            } else {
              answer[i].reply_count += '条回复'
            }
						answer_inner+= 
            '<div class="answer-item">'+
              '<div class="img-box">'+ 
                '<img src="' + answer[i].face + '" alt="reply-logo" class="reply-logo">'+
              '</div>'+
              '<div class="content-box">'+
                '<div class="con-top">'+
                  '<div class="info">'+ 
                    '<h4 class="fs-14 fc-8">'+answer[i].nickname+'</h4>'+
                    '<p class="reply-date fs-12 fc-4">'+answer[i].create_time+'</p>'+
                  '</div>'+
                  '<div class="favo">'+ 
                    '<div class="zan" data-has-like="'+answer[i].has_likes+'" data-id="'+answer[i].id+'">' +
                      '<span class="zan-num fs-12">'+answer[i].likes+'</span>' + zan +
                    '</div>'+
                  '</div>'+
                '</div>'+
                '<div class="desc con-bottom" data-id="'+answer[i].id+'">'+
                    '<p class="fs-16 fc-10">'+answer[i].content+'</p>'+
                    '<span class="answer-num fs-12">' + answer[i].reply_count + '</span>' +
                '</div>'+
              '</div>'+
            '</div>'
					}
          if(page == 1 && answer.length == 0) {
            answer_inner = '<div class="emptypage-wrapper"><div class="empty-box"><img class="empty-img" src="/public/mobile/img/icon/wenda-empty.png"/><p class="empty-text">暂无回答</p></div></div>'
            $('.lower-box').html(answer_inner);
            $('.qaInfo-list').hide();
          }
					if(page == 1){
						$('.qaInfo-list').html(answer_inner);
					}else{
						$('.qaInfo-list').append(answer_inner);
					}	
				});
			}
			$(function() {
				// $('.preview-list').MobilePhotoPreview({
				// 	trigger: '.preview',
				// });

          // 图片预览
          function showPreviewDialog(imgSrc) {
          var previewImg = new Image(),
          windowWidth = $(window).width(),
          windowHeight = $(window).height();
          previewImg.src = imgSrc;
          previewImg.onload = function () {
            var w = previewImg.naturalWidth,
            h = previewImg.naturalHeight,
            wt = w / windowWidth,
            ht = h / windowHeight;
            if (wt > 1 && ht < 1) {
              previewImg.width = windowWidth;
              previewImg.height = h / wt;
            } else if (ht > 1 && wt > 1) {
              previewImg.width = w / ht;
              previewImg.height = windowHeight;
            } else {
              previewImg.width = w;
              previewImg.height = h;
            }
            var sizeData = {w: previewImg.width, h: previewImg.height};
            var tempStr = 
            `
              <div class="preview-dialog" style="width: ${windowWidth}px; height: ${windowHeight}px">
                <img src="${imgSrc}" style="width: ${sizeData.w}px; height: ${sizeData.h}px"/>
              </div>
            `;
            $('body').append(tempStr).find('.preview-dialog').css('display', 'flex').toggleClass('full-scale');
          }
        }
      


        mui('.pic').on('tap', 'img', function () {
          showPreviewDialog($(this).data('src'));
        })
        mui('body').on('tap', '.preview-dialog', function (e) {
          e.stopPropagation();
          $(this).remove();
        })

				mui("body").on('tap', '.course-info', function (event) {
					var itemcid =  $(this).attr('data-cid');
					var url = "<?php echo url('wechat/course/detail'); ?>?cid="+itemcid;
					window.location.href=url;
					
				})
				
				mui("body").on('tap', '.fixed-btn', function (event) {
					if(is_pay==0){
						mui.alert('未购买课程不能回复');
						//location.reload();
					}else{
						var url = "<?php echo url('wechat/ask/reply'); ?>?id="+id+"&puid=0&pid=0";
						window.location.href=url;
					}
				})
				
					
				})
				mui("body").on('tap', '.mui-icon-forward', function (event) {
				    var cid = $(this).attr('cid');
					var url = "<?php echo url('wechat/course/detail'); ?>";
					window.location.href=url+'?cid='+cid;
				})
				mui(".qainfo-input").on('tap', '#submit', function (event) {
					var content = $('textarea').val();
					$('.qainfo-input').hide();
					if(content == ""){
						return false;
					}
					$("#submit").hide();
					$.post(host+"answer/add",{uid:uid,aid:id,pid:answer_id,puid:answer_uid,content:content,anonymous:0},function(res){ 
						$("#submit").show();
						//console.log(res);
						if(res.code == 1){
							$('textarea').val('');
							mui.alert('回复成功');
							location.reload();
							return;
						}else if(res.code == -1){
							mui.alert(res.message);
							$('.qainfo-input').show();
							return;
						}else{
							$('.qainfo-input').show();
							mui.alert('回复失败');return;
						}
					});
				})
				
				mui("body").on('tap', '#backdrop', function (event) {
					$(".qainfo-input").hide();
					$(".mui-popup-backdrop").hide();
				})
				//点赞
				 mui("body").on('tap', '.icon-zan', function (event){
					var that = this;
					var itemid = $(that).parent().attr('data-id');
					var has_likes = $(that).parent().attr('data-has-like'); 
					var likes = Number($(that).siblings('.zan-num').html());
					if(has_likes == 0) {
						$.post(host+"like/add",{uid:uid,itemid:itemid,typeid:2},function(res){ 
							if(res.code == 1){
								$(that).parent().find(".icon-zan").css('display','none');
								$(that).parent().find(".icon-zaned").css('display','');
								$(that).siblings('.zan-num').html(likes+1);
							} else {
								mui.alert('点赞失败');
							}
						});
					}
				})

			mui('.qaInfo-list').on('tap', '.desc', function (event) {
				var url = "<?php echo url('wechat/ask/discuss'); ?>";
				var id = this.getAttribute("data-id");
				window.location.href=url+'?id='+id;
			});
			mui('.content-box').on('tap', '.desc', function (event) {
				var url = "<?php echo url('wechat/ask/discuss'); ?>";
				var id = this.getAttribute("data-id");
				window.location.href=url+'?id='+id;
			});
			
		</script>
	<script src="/public/mobile/js/bindmobile.js"></script>
</body>
</html>