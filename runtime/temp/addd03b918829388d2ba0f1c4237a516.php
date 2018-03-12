<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:43:"./template/mulit/wechat/ask/mulitindex.html";i:1518064648;s:41:"./template/mulit/wechat/common/share.html";i:1518064648;s:40:"./template/mulit/wechat/common/menu.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>问答</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css"/>
		<link rel="stylesheet" href="/public/mobile/css/global.css"/>
		<link rel="stylesheet" href="/public/mobile/css/qna.css"/>
		<!-- <link rel="stylesheet" href="/public/mobile/js/wsiper/swiper.min.css"/> -->
		<script src="/public/mobile/js/compatible.js"></script>
		<script src="/public/mobile/js/jquery-3.2.1.min.js"></script>
		<script src="/public/mobile/js/mui.min.js"></script>
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
		<div class="wrap">
      <div class="qna-nav">
        <i class="filter-icon"></i>
        <p class="catename dan fs-14 fc-6"></p>
        <i class="quest-btn btn hollow">提问</i>
      </div>
      <div id="pullrefresh" class="mui-scroll-wrapper askcontent-wrapper">
				<div class="mui-scroll">
					<div class="content-slide ask-content">
          
					</div>
				</div>
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


    </div>
		<div class="dialog">
      <div class="filter-box mui-scroll-wrapper dialog-scroll-wrapper filter-wrapper" >
        <div class="filter-head">
          <h2 class="filter-all fs-16 subfc">浏览全部</h2>
          <h4 class="fs-18 fc-10">按课程浏览</h4>
          <i class="cancel"></i>
        </div>
        <div class="mui-scroll">
          <div class="filter-body">
            <div class="filter-content">
              <?php if(is_array($topcate_lists) || $topcate_lists instanceof \think\Collection || $topcate_lists instanceof \think\Paginator): $i = 0; $__LIST__ = $topcate_lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$topcate): $mod = ($i % 2 );++$i;?>
                <div class="filter-item">
                  <ul class="cate-list">
                    <li class="catename fc-10 fs-16"><?php echo $topcate['cate_name']; ?></li>
                    <?php if(!empty($topcate['topcourse'])): if(is_array($topcate['topcourse']) || $topcate['topcourse'] instanceof \think\Collection || $topcate['topcourse'] instanceof \think\Paginator): $i = 0; $__LIST__ = $topcate['topcourse'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$topmin): $mod = ($i % 2 );++$i;?>
                      <li class="course-title" data-cid="<?php echo $topmin['cid']; ?>">
                        <i class="book-icon"></i>
                        <p class="fs-16"><?php echo $topmin['title']; ?></p>
                      </li>
                      <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                  </ul>
                  <?php if(!empty($topcate['childcate'])): if(is_array($topcate['childcate']) || $topcate['childcate'] instanceof \think\Collection || $topcate['childcate'] instanceof \think\Paginator): $i = 0; $__LIST__ = $topcate['childcate'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?>
                      <ul class="subcate-list">
                        <li class="subcate-name fs-16 fc-6"><?php echo $sub['cate_name']; ?></li>
                        <?php if(!empty($sub['courselist'])): if(is_array($sub['courselist']) || $sub['courselist'] instanceof \think\Collection || $sub['courselist'] instanceof \think\Paginator): $i = 0; $__LIST__ = $sub['courselist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$min): $mod = ($i % 2 );++$i;?>
                          <li class="course-title fs-16" data-cid="<?php echo $min['cid']; ?>">
                            <i class="book-icon"></i>
                            <p class="fs-16"><?php echo $min['title']; ?></p>
                          </li>
                          <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                      </ul>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </div>
              <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
          </div>
        </div>
          
      </div>
      
      <div class="add-box mui-scroll-wrapper dialog-scroll-wrapper add-wrapper">
        <div class="filter-head question-head">
          <div class="filter-notice">
            <h4>选择课程</h4>
            <p>付费课程需购买后提问</p>
          </div>
          <i class="cancel" style="background: url(../../../../public/mobile/img/icon/cross.png) no-repeat 48px 29px/16px;"></i>
        </div>
        <div class="mui-scroll">
          <div class="filter-body" style="padding-bottom: 70px;">
            <div class="filter-content">
              <?php if(is_array($pay_catelist) || $pay_catelist instanceof \think\Collection || $pay_catelist instanceof \think\Paginator): $i = 0; $__LIST__ = $pay_catelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pay): $mod = ($i % 2 );++$i;?>
                <div class="filter-item">
                  <ul class="cate-list">
                    <li class="catename fc-10 fs-16"><?php echo $pay['toplist']['cate_name']; ?></li>
                    <?php if(!empty($pay['courselist'])): if(is_array($pay['courselist']) || $pay['courselist'] instanceof \think\Collection || $pay['courselist'] instanceof \think\Paginator): $i = 0; $__LIST__ = $pay['courselist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$topmin): $mod = ($i % 2 );++$i;?>
                      <li class="course-title" data-cid="<?php echo $topmin['cid']; ?>">
                        <i class="book-icon"></i>
                        <p class="fs-16"><?php echo $topmin['title']; ?></p>
                      </li>
                      <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                  </ul>
                  <?php if(!empty($pay['childlist'])): if(is_array($pay['childlist']) || $pay['childlist'] instanceof \think\Collection || $pay['childlist'] instanceof \think\Paginator): $i = 0; $__LIST__ = $pay['childlist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?>
                      <ul class="subcate-list">
                        <li class="subcate-name fs-16 fc-6"><?php echo $sub['cate_name']; ?></li>
                        <?php if(!empty($sub['courselist'])): if(is_array($sub['courselist']) || $sub['courselist'] instanceof \think\Collection || $sub['courselist'] instanceof \think\Paginator): $i = 0; $__LIST__ = $sub['courselist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$min): $mod = ($i % 2 );++$i;?>
                          <li class="course-title fs-16" data-cid="<?php echo $min['cid']; ?>">
                            <i class="book-icon"></i>
                            <p class="fs-16"><?php echo $min['title']; ?></p>
                          </li>
                          <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                      </ul>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </div>
              <?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
          </div>

        </div>
        <!-- <div class="filter-content">
          <?php if(is_array($pay_catelist) || $pay_catelist instanceof \think\Collection || $pay_catelist instanceof \think\Paginator): $i = 0; $__LIST__ = $pay_catelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$pay): $mod = ($i % 2 );++$i;?>
              <div class="filter-item">
                <h2 class="toplv-caption"><?php echo $pay['toplist']['cate_name']; ?></h2>
                <ul>
                    <?php if(empty($pay['childlist'])): if(is_array($pay['courselist']) || $pay['courselist'] instanceof \think\Collection || $pay['courselist'] instanceof \think\Paginator): $i = 0; $__LIST__ = $pay['courselist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$topmin): $mod = ($i % 2 );++$i;?>
                      <li class="course-caption" data-cid='<?php echo $topmin['cid']; ?>'><?php echo $topmin['title']; ?></li>
                      <?php endforeach; endif; else: echo "" ;endif; else: if(!empty($pay['courselist'])): if(is_array($pay['courselist']) || $pay['courselist'] instanceof \think\Collection || $pay['courselist'] instanceof \think\Paginator): $i = 0; $__LIST__ = $pay['courselist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$topmin): $mod = ($i % 2 );++$i;?>
                      <li class="course-caption" data-cid='<?php echo $topmin['cid']; ?>'><?php echo $topmin['title']; ?></li>
                      <?php endforeach; endif; else: echo "" ;endif; endif; if(is_array($pay['childlist']) || $pay['childlist'] instanceof \think\Collection || $pay['childlist'] instanceof \think\Paginator): $i = 0; $__LIST__ = $pay['childlist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?>
                        <li class="seclv-caption"><?php echo $sub['cate_name']; ?></li>
                        <?php if(is_array($sub['courselist']) || $sub['courselist'] instanceof \think\Collection || $sub['courselist'] instanceof \think\Paginator): $i = 0; $__LIST__ = $sub['courselist'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$min): $mod = ($i % 2 );++$i;?>
                        <li class="course-caption"  data-cid='<?php echo $min['cid']; ?>'><?php echo $min['title']; ?></li>
                      <?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; endif; ?>
                </ul>
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div> -->
      </div>
      <div class="alert-box">
          <h4>需要购买课程</h4>
          <p>购买课程学习后即可提问</p>
          <div class="operation">
            <i class="cancel">取消</i>
            <a class="buy" href="<?php echo url('/wechat/index/index'); ?>">购买课程</a>
          </div>
      </div>
	  </div>
	  <div class="toast-box limit">
			<div class="toast-main" id="toast-main"></div>
		</div>
    <!-- <script type="text/javascript" src="/public/mobile/js/wsiper/idangerous.swiper.min.js"></script> -->
    <script type="text/javascript">
    var  msg  = "<?php echo $msg; ?>";
	if(msg){
		document.getElementById('toast-main').innerHTML=msg;
		toastBox();
	}
			$(function() {
        $('.filter-box').find('.mui-scroll').css('min-height', $(window).innerHeight() - 155 + 'px');
        $('.add-box').find('.mui-scroll').css('min-height', $(window).innerHeight() - 155 + 'px');
				$("#intNav li").click(function() {
					$(this).addClass("mui-active");
					$(this).siblings().removeClass("mui-active");
        });
        // 弹窗
        var isBuy = <?php echo $is_buy_bool; ?>;
        $('.filter-icon').on('tap', function () {
            $(this).addClass('focus');
            $('.wrap').addClass('blur');
            $('.dialog')
            .css({'display': 'block'})
            .find('.filter-box')
            .show()
            .siblings()
            .hide();
            mui('.filter-wrapper').scroll().scrollTo(0,0,0);
          });
        $('.dialog, .cancel').on('tap', function (e) {
            $('.dialog').hide();
            $('.wrap')
            .removeClass('blur')
          }).children('div').on('tap', function (e) {
            e.stopPropagation();
          });
        $('.quest-btn').on('tap', function (e) {
        	e.stopPropagation();
        	setTimeout(function () {
        		if(isBuy) {
                $('.wrap')
                .addClass('blur')
                .end()
                .find('.dialog')
                .css({'display': 'block'})
                .find('.add-box')
                .show()
                .siblings()
                .hide();
                mui('.add-wrapper').scroll().scrollTo(0,0,0);
              } else {
                $('.wrap')
                .addClass('blur')
                .end()
                .find('.dialog')
                .css({'display': 'flex'})
                .find('.alert-box')
                .css('display', 'flex')
                .siblings()
                .hide();
              }
        	}, 100);
        })
        // 弹窗区域滚动
        mui('.filter-wrapper').scroll({
  	        deceleration: 0.005, //flick 减速系数，系数越大，滚动速度越慢，滚动距离越小，默认值0.0006
            scrollY: true, //是否竖向滚动
            scrollX: false, //是否横向滚动
            bounce: true,
        });
        mui('.add-wrapper').scroll({
  	        deceleration: 0.005, //flick 减速系数，系数越大，滚动速度越慢，滚动距离越小，默认值0.0006
            scrollY: true, //是否竖向滚动
            scrollX: false, //是否横向滚动
            bounce: true,
        });
        // 弹窗滚动区域高度初始化
        // $('.dialog .dialog-scroll-wrapper').css('max-height', $(window).innerHeight() - 155);
      });
		//选择课程
		$(".filter-box .filter-all").on("click", function () {
        var filter_All = true;
        var url = "<?php echo url('wechat/ask/mulitindex'); ?>?cid=0";
			  window.location.href=url;
    });
		$(".filter-box .course-title").on("click", function () {
        var filter_All = false;
			  var that = $(this);  
        var itemid = that.attr("data-cid");
        var url = "<?php echo url('wechat/ask/mulitindex'); ?>?cid="+itemid;
				window.location.href=url;
    });
		//提问
		$(".add-box .course-title").on("click", function () {
        var that = $(this);
        var itemid = that.attr("data-cid");
        var url = "<?php echo url('wechat/ask/mulitanswer'); ?>?cid="+itemid;
				window.location.href=url;
    })
    var page = 1;
    var size = 10;
    var cid= <?php echo $cid; ?>;
    var uid = '<?php echo $userinfo['uid']; ?>';
    var isbind = '<?php echo $userinfo['is_bind']; ?>';
    var filter_All = false;
    $.get(host+"index/index", function(result){
      if(result.code == 0){
        alert('获取数据失败');return;
      }
      
      var course = result.data.course;
      var inner = '';
      
      getask(cid,page,size);

    });
    
			//获取问答
			function getask(cid,page,size){
				
				$.get(host+"ask/index?cid="+cid+'&uid='+uid+'&page='+page+'&size='+size, function(result){
					//console.log(111);
					//console.log(result);
          $('.qna-nav .catename').text(result.data.catename);
					var ask = result.data.ask;
					var inner = '';
					for(var i=0;i<ask.length;i++){
						if(ask[i].comments > 0){
							var answer_innser = '<p class="fs-12 subfc">'+ask[i].comments+'条回答</p>';
						}else{
							var answer_innser = '<p class="fs-12 subfc data-id="'+ask[i].id+'">暂无回答</p>';
						}
						var img_inner = '<div class="img-displaybox">';
						var photopath_thumb = ask[i].photopath_thumb;
						for(var k=0;k<photopath_thumb.length;k++){
							if(photopath_thumb[k]!='1'){
								img_inner += '<img src="' + photopath_thumb[k] + '">';	
							}
						}
						img_inner += '</div>';
						if(ask[i].anonymous == 1){
							ask[i].face = '/public/image/anonymity.png';
							ask[i].nickname = '匿名';
						}
						//精华
            var ask_hot =  '';
            console.log(ask[i]);
						if(ask[i].hot==1){
							ask_hot = '<img src="/public/mobile/img/icon/ess.png" alt="ess" class="ess">';
						}
						inner += '<div class="qna-box" data-id="' + ask[i].id + '">'+
                        '<div class="qna-top">'+
                          '<img src="' + ask[i].face + '" class="hp">' +
                          '<div class="qna-topright">' + 
                            '<div class="name fs-14 fc-8">' + ask[i].nickname + ask_hot +'</div>'+
                            '<div class="qna-infobox">'+ 
                              '<span class="create-time fs-12 fc-4">'+ ask[i].create_time + '</span>'+
                              '<span class="catename fs-12 fc-4">' + ask[i].cate_name + '</span>'+ 
                            '</div>'+
                          '</div>'+
                        '</div>'+
                        '<p class="qna-title fs-18 fc-10">'+ask[i].title+'</p>'+
                        img_inner + answer_innser +
                    '</div>';
					}
					if(page == 1){
            if(ask.length == 0){
              //$('.content-slide').html(nodata);
              var empty_list = '<div class="emptypage-wrapper"><div class="empty-box"> <img class="empty-img" src="/public/mobile/img/icon/wenda-empty.png"> <p class="empty-text">暂无数据</p></div></div>';
              $('.wrap').after(empty_list);
						}else{
              $('.content-slide').html(inner);
						}
						mui('#pullrefresh').pullRefresh().endPulldownToRefresh();
					}else{
            $('.content-slide').append(inner);
						mui('#pullrefresh').pullRefresh().endPullupToRefresh();
					}
					// $('.swiper-container').css('overflow','inherit');
					//$('.swiper-container').append(inner);
				});
			}
			mui.init({
        pullRefresh: {
          container: '#pullrefresh',
          up : {
            //height:50,//可选.默认50.触发上拉加载拖动距离
            //auto:true,//可选,默认false.自动上拉加载一次
            contentrefresh : "",//可选，正在加载状态时，上拉加载控件上显示的标题内容
            contentnomore:'',//可选，请求完毕若没有更多数据时显示的提醒内容；
            callback :pullupRefresh //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
          }
        }
      });
			function pullupRefresh() {
			  console.log('下拉');
        setTimeout(function() {
          page++;//翻下一页
          data={
            page:page,
            size:10
          };
          getask(cid,page,size);
        }, 100);
      }
			mui('.ask-content').on('tap', '.qna-box', function (event) {
				var url = "<?php echo url('wechat/ask/detail'); ?>";
				var id = this.getAttribute("data-id");
				window.location.href=url+'?id='+id;
			});

		</script>
			<script src="/public/mobile/js/bindmobile.js"></script>
	</body>

</html>