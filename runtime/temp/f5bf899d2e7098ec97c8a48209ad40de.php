<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:41:"./template/mulit/wechat/member/jifen.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>积分明细</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/member.css" />		
		<script src="/public/mobile/js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/mui.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/globla.js"></script>
	</head>
	<body>	
		<!--积分概况开始-->
		<div class="jifen-top">
			<div class="jifen-outline">
				<div class="jifen-odd">
					<p id="jifen-odd-num"></p>
					<span>剩余积分</span>
				</div>
				<div class="jifen-total">
					<p></p>
					<span>总积分</span>
				</div>				
			</div>
		</div>
		<!--积分概况结束-->
		<!--积分详情开始-->
		<div class="jifen-header">
			<p>积分纪录</p>
			<div class="popover-box">
				<div class="classify-btn">
					<span class="classify-cur"><?php if($type==1): ?>获取<?php elseif($type==2): ?>使用<?php else: ?>全部<?php endif; ?></span>
					<img src="/public/mobile/img/icon/jifen_arrow@2x.png" alt="下拉箭头"/>
				</div>
				<!--浮框-->
				<div class="fs-16 classify-box">
					<div class="classify-name cur"data-type='0'>
						<i>全部</i>
					</div>
					<div class="classify-name"data-type='1'>
						<i>获取</i>
					</div>
					<div class="classify-name"data-type='2'>
						<i>使用</i>
					</div>
				</div>		
			</div>
		</div>
		<div id="pullrefresh" class="mui-content mui-scroll-wrapper"style="margin-top: 203px;background: transparent;">
	    	<div class="mui-scroll">
		    	<div class="order_list_div">
		    		<ul class="order_list_ul jifen-list-ul">
		    			
		    		</ul>
		    		
		    	</div>
	    	<!--积分详情结束-->
			</div>
		</div>
		<!--空状态-->
		<div class="emptypage-wrapper" style="top: 203px; display:none;">
			<div class="empty-box" style="margin-top: -50px;"> 
				<img class="empty-img" src="/public/mobile/img/icon/purchase-empty.png">
				<p class="empty-text">还没有...</p>
			</div>
		</div>
	</body>
	<script type="text/javascript">
	$(function(){
		var uid = '<?php echo $userinfo['uid']; ?>'; //用户id
	    var isbind = '<?php echo $userinfo['is_bind']; ?>';
	    var token = '<?php echo $userinfo['token']; ?>';
	    var type = '<?php echo $type; ?>';
	    var page = 1;
	    var size = 10;
	    var uid = '<?php echo $userinfo['uid']; ?>'; //用户id
	    var isbind = '<?php echo $userinfo['is_bind']; ?>';

      mui.init
      ({
        pullRefresh: 
        {
          container: '#pullrefresh',
          up: {
            contentrefresh: '正在加载...',
            callback: pullupRefresh
          }
        }
      });
      function pullupRefresh() {
        setTimeout(function() {
    	    page++; //翻下一页
          data = {
            page: page,
            size: size,
            uid:uid,
            type:type
          };
          toList(data); //具体取数据的方法
          }, 1500);
      }
      data = {
        page: page,
        size: size,
        uid:uid,
        type:type
      };
      toList(data);
      function toList(data) {
        $.ajax({
          url:host + 'integral/detail/',
          data: data,
          type: 'GET', //GET
          async: true, //或false,是否异步
          timeout: 5000, //超时时间
          dataType: 'json', //返回的数据格式：json/xml/html/script/jsonp/text
          success: function(result, textStatus, jqXHR) {
            if(result.code == 1) {
            	console.log(result);
            	var list = '';
	        	integral_count = result.data.integral_count;
	        	now_integral = result.data.now_integral;
	        	$('.jifen-odd p').html(integral_count);
	        	$('.jifen-total p').html(now_integral);   
	        	 $.each(result.data.detail, function(j, detail) {
	        		 list +='<li>'+
	        		 '<div class="iterm-left">'+
	        		 '<p>'+detail.config_name+'</p>'+
	        		 '<span>'+detail.create_time+'</span>'+
	        		 '</div>';
	        		 list += '<div class="font-size-16 iterm-right">';
	        		 if(detail.integral>=0){
	        			 list += '<p class="jifen-num jifen-add">+'+detail.integral+'</p>';
	        		 }else{
	        			 list += '<p class="jifen-num jifen-reduce">'+detail.integral+'</p>';
	        		 }
	        		 list += '</div>';
	        		 list += '</li>';
	        	 });
              if(result.data.detail.length > 0){
                $(".jifen-list-ul").append(list);
              }else{
            	  if(data.page==1){
            		  $('.emptypage-wrapper').css('display','flex');
            	  }
                mui('#pullrefresh').pullRefresh().endPullupToRefresh(true); //参数为true代表没有更多数据了。
              }
              
            }
            mui('#pullrefresh').pullRefresh().endPullupToRefresh();
          }
        });
    	}
          
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
      //筛选条件
      	//分类弹框
		$('.popover-box').on('click','.classify-btn',function(e){
			e.stopPropagation();
			$('.classify-box').fadeIn()
		})  
      $('.classify-box').on('tap', '.classify-name', function() {
			var type = $(this).attr('data-type');
			url = "<?php echo url('wechat/member/jifen'); ?>";
			window.location.href = url + '?type=' + type;
			$('.classify-box').fadeOut()
		});	
		//点击弹框以外的地方弹框消失
		mui('body').on('tap','#pullrefresh',function(e){
			if(e.target.className!='classify-name'){
				$(".classify-box").fadeOut();
			}
		})
		
		var obj=$('#jifen-odd-num');
		var num=parseInt($(obj).text());
		if(num>9999){
			$(obj).addClass('big-num')
		}
				
	})

	</script>
	
</html>