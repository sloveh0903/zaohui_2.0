<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:44:"./template/mulit/wechat/index/show_more.html";i:1518166838;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>全部课程</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/show-more.css" />
		<script src="/public/mobile/js/jquery-3.2.1.min.js"></script>
		<script src="/public/mobile/js/mui.min.js"></script>
		<script src="/public/mobile/js/globla.js"></script>
	</head>
	
<body>
<!--下拉刷新容器-->
<div id="pullrefresh" class="mui-content mui-scroll-wrapper">
    <div class="mui-scroll">
        <!--数据列表-->
      <div class="search-box">
        <a class="search" href="">
           <span class="search-icon">
          </span>
           <span class="search-text">搜索...</span>
        </a>
      </div>
      <div class="mui-row list-content">
        <ul class="mui-table-view course-list" id="course">
        </ul>
      </div>

  </div>
</div>
<script type="text/javascript">
var page = 1;
var size = 10;
var uid = '<?php echo $userinfo['uid']; ?>'; //用户id
var isbind = '<?php echo $userinfo['is_bind']; ?>';

  mui.init
  ({
    pullRefresh: 
    {
      container: '#pullrefresh',
      // down: {
      //   callback: pulldownRefresh
      // },
      up: {
        contentrefresh: '正在加载...',
        callback: pullupRefresh
      }
    }
  });
      
  /*
   * 下拉刷新具体业务实现
   */
  // function pulldownRefresh() 
  // {
  //   setTimeout(function() 
  //   {
  //     mui('#pullrefresh').pullRefresh().endPulldownToRefresh(); //refresh completed
  //   }, 1500);
  // }
      
  /*
   * 上拉加载具体业务实现
   */
  function pullupRefresh() {
    setTimeout(function() {
     	// mui('#pullrefresh').pullRefresh().endPullupToRefresh((++count > 2)); //参数为true代表没有更多数据了。
	    page++; //翻下一页
      data = {
        page: page,
        size: size,
        uid:uid
      };
      toList(data); //具体取数据的方法
      }, 1500);
  }
  data = {
    page: page,
    size: size,
    uid:uid
  };
  toList(data);
  function toList(data) {
    $.ajax({
      url: host + '/index/index',
      data: data,
      type: 'GET', //GET
      async: true, //或false,是否异步
      timeout: 5000, //超时时间
      dataType: 'json', //返回的数据格式：json/xml/html/script/jsonp/text
      success: function(result, textStatus, jqXHR) {
        if(result.code == 1) {
          var is_rebate = result.data.is_rebate;
          var rebate_status = result.data.rebate_status;
          var adv = "";
          var advitem = "";
          var indicator = "";
          var list = "";
          //是否推广员
          if(is_rebate == '1' && rebate_status == '1'){
            $.each(result.data.course, function(j, course) {
              if(course.price==0.00){
                var price_html = '免费';
              }else{
                var price_html = '￥'+course.price;
              }
              list += '<li data-id="' + course.cid + '"><div class="teacher-box"><img src="' + course.face + '" /></div><div class="course-content"> <h1>' + course.title + '</h1> <span class="course_desc">' + course.desc + '</span> <i style="color:#FF2D55">推广佣金:' + course.share_price + '元</i> </div> <span class="course-price">' + price_html + '</span></li>';
            });
          }else{
            $.each(result.data.course, function(j, course) {
              if(course.price==0.00){
                var price_html = '免费';
              }else{
                var price_html = '￥'+course.price;
              }
              list += '<li data-id="' + course.cid + '"><div class="teacher-box"><img src="' + course.face + '" /></div><div class="course-content"> <h1>' + course.title + '</h1> <span class="course_desc">' + course.desc + '</span> <i>' + course.study_count + '人在学</i> </div> <span class="course-price">' + price_html + '</span></li>';
            });
          }	
          if(data.page == 1) {
            $(".advitem").html(advitem);

          }
          if(result.data.course.length > 0){
            $("#course").append(list);
          }else{
            mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据了。
          }
        }
        mui('#pullrefresh').pullRefresh().endPullupToRefresh();
        //算底部高度
        if(data.page == 1){
            calcPos();
        }
      }
    });
	}
      function calcPos() {
        if($(window).innerHeight() - $('.list-content').innerHeight() - 90 < 0) {
            var str1 = '<div class="grazy-copyright"><i>格子匠 GRAZY.CN 技术支持</i></div>';
            $(str1).appendTo($('.mui-scroll'));

        } else {
            var str2 = '<div class="grazy-copyright bottom-fixed"><i>格子匠 GRAZY.CN 技术支持</i></div>';
            $(str2).appendTo($('.mui-content'));
        }
      }
			mui('#course').on('tap', 'li', function() {
				var cid = $(this).attr('data-id');
				url = "<?php echo url('wechat/course/detail'); ?>";
				window.location.href = url + '?cid=' + cid + '&version=' + '<?php echo $version; ?>';
			});
			mui('.search-box').on('tap', '.search', function() {
				url = "<?php echo url('wechat/search/index'); ?>";
				window.location.href = url;
			});
      
  if (mui.os.plus) {
    mui.plusReady(function() {
      setTimeout(function() {
        mui('#pullrefresh').pullRefresh().pullupLoading();
      }, 1000);

    });
  } else 
  { 
    mui.ready(function() {
      mui('#pullrefresh').pullRefresh().pullupLoading();
    });
  }



</script>
<script src="/public/mobile/js/bindmobile.js"></script>