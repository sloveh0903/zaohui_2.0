	var tmpTag = 'https:' == document.location.protocol ? false : true;
	if(tmpTag){
		var protocol = 'http';
	}else{
		var protocol = 'https';
	}
    var host = protocol+'://' + window.location.host + '/api/';

   //学习平台
   // $(".youren_top_navul li a[name='study_flatform']").on("mouseenter",function(){
   //    $(".study_flatform_div").slideDown();
   // })
   // $(".study_flatform_div").on("mouseleave",function(){
   //    $(".study_flatform_div").slideUp();
   // })
   $(".youren_top_navul li[name='study_flatform']").hover(
       function(){
            $(this).find("div.course_introduce").show();
       },
       function(){
            $(this).find("div.course_introduce").hide();
       }
   )
  //头部下拉菜单隐藏、显示
   $(".drop_menu_a").click(function(e){
      var youren_top_dropMenu_div  = $(".youren_top_dropMenu_div")
      var cssDisplay = youren_top_dropMenu_div.css("display");
      if("none" == cssDisplay ){
         youren_top_dropMenu_div.show();
      }else{
         youren_top_dropMenu_div.hide();
      }
      e.stopPropagation(); //取消事件冒泡；
   });

   $(document).bind("click",function(e){
      var target = $(e.target);
      var thisParent = target.closest(".drop_menu_a");
      if( !thisParent.is(".drop_menu_a")){
         $(".youren_top_dropMenu_div").hide();
      }else{
         
      }

   })
   //头部下拉菜单a元素鼠标穿过事件
   $(".youren_top_dropMenu_ul li a").hover(
      function(){
         var img  = $(this).find("img");
         var name = img.attr("data-name");
          img.next("i").addClass("color00B6F2");
         switch( name ){
            case "study":
               img.attr("src","/public/pc/images/myStudy_blue@2x.png");
               break;
            case "order":
               img.attr("src","/public/pc/images/myOrder_blue@2x.png");
               break;
			case "outloign":
               img.attr("src","/public/pc/images/loginout_blue@2x.png");
               break;
           case "bindphone":
               img.attr("src","/public/pc/images/myinfo_blue@2x.png");
               break;
           case "password":
               img.attr("src","/public/pc/images/mypsw_blue@2x.png");
               break;
         }
      },
      function(){
         var img  = $(this).find("img");
         var name = img.attr("data-name");
          img.next("i").removeClass("color00B6F2");
         switch( name ){
            case "study":
               img.attr("src","/public/pc/images/myStudy_gray@2x.png");
               break;
            case "order":
               img.attr("src","/public/pc/images/myOrder_gray@2x.png");
               break;
			case "outloign":
               img.attr("src","/public/pc/images/loginout_gray@2x.png");
               break;
           case "bindphone":
               img.attr("src","/public/pc/images/myinfo_gray@2x.png");
               break;
           case "password":
               img.attr("src","/public/pc/images/mypsw_gray@2x.png");
               break;
         }
      }
   )
   //头部向下箭头hover事件
   $(".youren_top_arrowDownImg img").hover(
      function(){
         $(this).attr("src","/public/pc/images/arrowDown_blue@2x.png");
      },
      function(){
         $(this).attr("src","/public/pc/images/arrowDown_gray@2x.png");
      }
   )
   //头部消息 铃铛hover事件
   $(".a_message").hover(
      function(){
         $(this).find(".youren_top_messageImg img").attr("src","/public/pc/images/myMessage_blue@2x.png");
         $(this).find(".youren_top_messageDiv span").addClass("color00B6F2");
      },
      function(){
         $(this).find(".youren_top_messageImg img").attr("src","/public/pc/images/myMessage_gray@2x.png");
          $(this).find(".youren_top_messageDiv span").removeClass("color00B6F2");
      }
   )

   //退出登录
   $('#loginout').click(function(){
		$.ajax({ 
			url: host+"user/loginout", 
			data:{},
			dataType:'json',
			type:'POST', 
			success: function(){
				location.reload();
			}}
		);
   })   



      //使用账号登陆切换
      $(".use_AccountLogin").click(function(){
         $(".weixin_QRcord_alert_outside").hide();
         $(".account_alert_outside").show();
      })
      //使用微信登陆切换
      $(".use_weixinLogin").click(function(){
         $(".account_alert_outside").hide();
         $(".weixin_QRcord_alert_outside").show();
      })
      //微信二维码弹框出现。
      $(".a_login").click(function(){
         $(".weixin_QRcord_alert_outside").show();
      })
      //弹框关闭
      $(".a_close").click(function(){
         $(".weixin_QRcord_alert_outside").hide();
         $(".account_alert_outside").hide();
      })
   
// 个人中心 签到
$('.signup-box').on('click', function () {
  var signup = true;
  console.log(1);
  if(!signup) {
    return;
  }
  $(this).html('已签到').addClass('signed');
  // todo: 签到弹窗
})
  


      
   


 