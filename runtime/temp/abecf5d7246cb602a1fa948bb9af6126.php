<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:45:"./template/mulit/wechat/bundlelist/index.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
  <title>套餐</title>
  <link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
  <link rel="stylesheet" href="/public/mobile/css/global.css" />
  <link rel="stylesheet" href="/public/mobile/css/bundle-more.css" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <ul class="bundle-list" style="padding-top:20px"> 
  </ul>
  <div class="grazy-copyright">
    <i>格子匠 GRAZY.CN 技术支持</i>
  </div>
  <script src="/public/mobile/js/jquery-3.2.1.min.js"></script>
  <script src="/public/mobile/js/mui.min.js"></script>
  <script>
    $(function () {
      //获取列表
      $.ajax({
          url:"<?php echo url('/api/package/all'); ?>",
          type:"post",
          data:{},
          success: function (data) {
            //console.log(data);
        	  if(data.code == 1){
              var listhtml = '';
              var lists = data.data;
              //console.log(lists);
              for(var i =0;i<lists.length;i++){
            	  var banner = lists[i].banner;
            	  if(banner){
            		  listhtml = listhtml+'<li data-id="'+lists[i].id+'"><div class="bundle-showbox">';
                      listhtml = listhtml+'<div class="'+banner+'"><img src="'+banner+'" alt=""></div>';
                      var bannerlist = lists[i].banner_color;
                      for(var j=0;j<bannerlist.length;j++){
                        var num = j+2;
                        var imgstr = 'img'+num;
                        var img_main_color = bannerlist[j];
                        listhtml = listhtml+'<div class="'+imgstr+'" style="background:'+img_main_color+'"></div>';
                      }
                      listhtml = listhtml+'</div>'+
                      '<div class="bundle-content"><h1>'+lists[i].title+'</h1><i class="bundle-price">'+lists[i].price+'</i></div>'+
                      '</li>';
              		  }
            	  }
              $(".bundle-list").html(listhtml);
              setTimeout(function(){
                //alert(window.innerHeight);alert($('.bundle-list').height());
                if(window.innerHeight - $('.bundle-list').height() - 50 > 0) {
                      $('.grazy_copyright').addClass('bottom-fixed');
                  } else {
                    $('.grazy_copyright').removeClass('bottom-fixed');
                  }
              },500);
            }
          }
      });

      //点击跳转
      $(".bundle-list").on("click","li",function(){
  		var id = $(this).attr('data-id');
  		//console.log(id);
  		var url = '/wechat/bundlelist/detail?id='+id;
  		window.location.href=url;
  	})

    });
      
  </script>
</body>
</html>