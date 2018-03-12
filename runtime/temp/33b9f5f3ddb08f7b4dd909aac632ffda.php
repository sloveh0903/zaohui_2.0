<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:46:"./template/mulit/wechat/bundlelist/detail.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
  <link rel="stylesheet" href="/public/mobile/css/global.css" />
  <link rel="stylesheet" href="/public/mobile/css/bundle-detail.css">
  <title><?php echo $title; ?></title>
</head>
<body>
  <ul class="course-list">
   
  </ul>
  <div class="grazy-copyright hastab">
    <i>格子匠 GRAZY.CN 技术支持</i>
  </div>
  <div class="buy-status"  data-id="">
  </div>
<!--   <i class="purchase-btn" data-id=""> -->
    
<!--   </i> -->
  <script src="/public/mobile/js/jquery-3.2.1.min.js"></script>
  <script src="/public/mobile/js/mui.min.js"></script>
  <script>
  $(function () {
	  var id = "<?php echo $id; ?>";
	  var uid = "<?php echo $uid; ?>";
	  var type = "<?php echo $type; ?>";
	  var checkbuy = 0;
	  var checkvip = 0;
  //获取列表
    $.ajax({
         url:"<?php echo url('/api/package/detail'); ?>",
         type:"post",
         data:{id:id,uid:uid,type:type},
         success: function (data) {
             //console.log(data);
      	   if(data.code == 1){
      		   var listhtml = '';
      		   var lists = data.data.course_arr;
      		   checkbuy = data.data.checkbuy;
      		   checkvip = data.data.checkvip;
      		   openvip = data.data.openvip;
      		   //console.log(checkvip);
      		   //console.log(lists);
      		   var listhtml = '';
      		   for(var i=0;i<lists.length;i++){
      			  listhtml  = listhtml+'<li data-id="'+lists[i].cid+'"><div class="teacher-box"><img src="'+lists[i].face+'" /></div>'+
      			  '<div class="course-content"><h1>'+lists[i].title+'</h1><span>'+lists[i].desc+'</span><i>'+lists[i].studynum+'人在学</i></div>'+
      			  '<span class="course-price">￥'+lists[i].price+'</span>'+
      			  '</li>'; 
      		   }
      		   var id = data.data.id;
      		   var price = data.data.price;
      		   $('.buy-status').attr('data-id',id);
               $(".course-list").html(listhtml);
               //是否购买
               var pricehtml = '';
               if(checkvip==1){
            	    pricehtml = pricehtml+'<p class="view" id="have-buy"><span>VIP</span>会员免费看</p>';
          		   
               }else{
            	   if(openvip==1){
            		   pricehtml = pricehtml+'<p class="buy_vip" id="buy-vip" href="">购买<span>VIP</span></p>';
            	   }
            	   if(checkbuy==1){
                	   pricehtml = pricehtml+'<p class="already_buy" id="have-buy">已购买</p>';
                   }else{
                	   pricehtml = pricehtml+'<p class="buy_price" id="buy">套餐促销价￥'+price+'</p>';
                   }
               }
               $('.buy-status').html(pricehtml);
               if(window.innerHeight - $('.course-list').innerHeight() - 100 > 0) {
                   $('.grazy-copyright').addClass('bottom-fixed');
                 } else {
                   $('.grazy-copyright').removeClass('bottom-fixed');
                 }
             }
         }
     });
  
  //点击跳转
    $(".course-list").on("click","li",function(){
		var id = $(this).attr('data-id');
		//console.log(id);
		var url = '/wechat/course/detail?cid='+id;
		window.location.href=url;
	})
	//点击购买vip
	$(".buy-status").on("click",'.buy_vip',function(e){
		e.stopPropagation(); 
		var url = '/wechat/course/card';
		window.location.href=url;
	})
	//点击购买
    $(".buy-status").on("click",function(){
		var id = $(this).attr('data-id');
		 if(checkbuy==1){
			 return false;
		 }
		 if(checkvip==1){
			 return false;
		 }
		 
		var url = '/wechat/ordersubmit/index?package_id='+id+'&order_type=package';
		window.location.href=url;
	})
  });
  </script>
</body>
</html>