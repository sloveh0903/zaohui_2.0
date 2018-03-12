$(function(){
	console.log(112);
	$('.banner-nav-list').on('click','.banner-nav-item', function (e) {
		  e.stopPropagation();console.log(111);
		  var topid  = $(this).attr('data-topid');
		  var url = "index/mulitcourse/index/";
	       window.location.href=url+'?topid='+topid;
	});
    var i=0;
    var banner_obj=$('.banner ul.banner_img>li')
    console.log(banner_obj)
    var banner_len =banner_obj.length-1;
    $banner_time=setInterval(autoPlay,4000);
    //定时器
    function autoPlay(){
        if(i>=banner_len){
            i=0;
        }else{
            i++;
        }
        $(banner_obj).eq(i).addClass('focus').siblings().removeClass('focus');
        $('.ol_box>li').eq(i).addClass('cur').siblings().removeClass('cur');
    }

    function banner_timer(){
        clearInterval($banner_time);//每次调用timer时清楚前一个计时器
        $banner_time=setInterval(autoPlay,4000);
    }
    //向左滑
    function sub(){
        clearInterval($banner_time);
        //$time=null;
        //alert(i)
        if(i==banner_len){
            i=0
        }else{
            i--
            if(i==-banner_len-1){
                i=0
            }
        }
        $(banner_obj).eq(i).addClass('focus').siblings().removeClass('focus');
        $('.ol_box>li').eq(i).addClass('cur').siblings().removeClass('cur');
        setTimeout(banner_timer,1000)
    }
    //向右滑
    function add(){
        clearInterval($banner_time);
        //alert(i)
        if(i==banner_len){
            i=0
        }else{
            i++
        }
        $(banner_obj).eq(i).addClass('focus').siblings().removeClass('focus');
        $('.ol_box>li').eq(i).addClass('cur').siblings().removeClass('cur');
        setTimeout(banner_timer,1000)
    }

    //小圆条点击事件，点击暂停计时器后再重新启动
    $('.ol_box>li').click(function(){
        clearInterval($banner_time)
        //console.log("hh")
        var x=$(this).index();
        i=x;
        $('.ol_box>li').eq(x).addClass('cur').siblings().removeClass('cur');
        $(banner_obj).eq(x).addClass('focus').siblings().removeClass('focus');
        setTimeout(banner_timer,1000)
    })
    //鼠标经过箭头出现
    $(".banner_center").mouseenter(function(){
        $('.banner_arrow').css("display","flex")
    })
    //鼠标离开箭头消失
    $(".banner_center").mouseleave(function(){
        $('.banner_arrow').hide()
    })
    //点击箭头图片切换
    //点击左边
    $(document).on("click",".arrow_l",function(){
        sub()
    })
    //点击右边
    $(document).on("click",".arrow_r",function(){
        add()
    })
    //单分类，左边导航消失，轮播图尺寸变大
    var banner_duo =$(".banner-nav").css("display")
    if(banner_duo=="none"){
        $(".banner").addClass("banner_dan")
    }

  $('.banner-nav-item').on('mouseover', function () {
    $(this).siblings('li').find('.banner-navlist-lv2').hide();
    $(this).find('.banner-navlist-lv2').show();
  }).on('mouseout', function () {
   $(this).find('.banner-navlist-lv2').hide();
  })
   //首页修改后的小程序,微信显示隐藏
  $(".xcx-img").hover(
    function(){
        $(this).next(".xiaochengxu_div").show();
    },
    function(){
        $(this).next(".xiaochengxu_div").hide();
    } 
  )
  $(".wx-img").hover(
    function(){
      $(this).next(".weixin_div").show();
    },
    function(){
      $(this).next(".weixin_div").hide();
    } 
  )
})