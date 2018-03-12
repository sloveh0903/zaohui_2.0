<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:44:"./template/mulit/wechat/course/category.html";i:1518064648;s:40:"./template/mulit/wechat/common/menu.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>课程</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/multiglobal.css" />
	</head>
	<body class="bg_fff">
	  <div class="wrap">
	    <div class="search-box">
        <a class="search" href="">
            <span class="search-icon">
          </span>
          <span class="search-text">搜索...</span>
        </a>
      </div>
	    <div class="content-wrapper">
	      <div class="content-left" id="wrapper-l">
	        <ul class="content-left-list">
	        <?php if(is_array($catlists) || $catlists instanceof \think\Collection || $catlists instanceof \think\Paginator): if( count($catlists)==0 ) : echo "" ;else: foreach($catlists as $key=>$vo): ?>
	          <li class="content-left-item <?php if($vo['id'] == $id): ?> focused <?php endif; ?>" >
              <i data-id="<?php echo $vo['id']; ?>"><?php echo $vo['cate_name']; ?></i>
              <b class="dots"></b>
	          </li>
	         <?php endforeach; endif; else: echo "" ;endif; ?> 
	        </ul>
	      </div>
	      <div class="content-right" id="wrapper-r" style="position: relative;">
	      </div>
      </div>
    

	    <script src="/public/mobile/js/jquery-3.2.1.min.js"></script>
	    <script src="/public/mobile/js/mui.min.js"></script> 
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
	  <script src="/public/pc/js/public_PC.js"></script>
	  <script src="/public/mobile/js/iscroll.js"></script> 
<!-- 	  <script src="/public/mobile/js/category-7.2.js"></script>  -->
	  <script type="text/javascript">
      var uid = '<?php echo $userinfo['uid']; ?>';
      var isbind = '<?php echo $userinfo['is_bind']; ?>';
      var id = '<?php echo $id; ?>';
      get_content_right(id);
      $('.content-wrapper').innerHeight($(window).innerHeight() - 97);
      $(".content-left").css('height', $('.content-wrapper').innerHeight());
      $(".content-right").css('height', $('.content-wrapper').innerHeight());
      setTimeout(function () {
        var myScroll_l = new IScroll('#wrapper-l', {});
        var myScroll_r = new IScroll('#wrapper-r', {});
      }, 1500)
	    mui('.content-right').on('tap', '.course-box' , function () {
        var cid = $(this).attr('data-id');
        url = "<?php echo url('wechat/course/detail'); ?>";
        window.location.href = url + '?cid=' + cid + '&version' + '<?php echo $version; ?>';
	    });

      mui('.search-box').on('tap', '.search', function() {
        url = "<?php echo url('wechat/search/index'); ?>";
        window.location.href = url;
      });
	  
	
      mui('.content-left').on('tap', '.content-left-item', function() {
        var id = $(this).find('i').attr('data-id');
        get_content_right(id);
        $('.content-left-item').removeClass('focused');
        $(this).addClass('focused');
      });
	  
				
	 
function get_content_right(id){
        $.get(host + "course/wxmulitindex?id=" + id, function (result) {
          var topcourse = result.topcourse;//console.log(topcourse);
          var childcate = result.childcate;//console.log(childcate);
          var havecourse = result.havecourse;
          //console.log(havecourse);
          var allhtml = '<ul class="classul">';
          if(havecourse==0){
            allhtml  = allhtml +'<li class="uploading-li">'+'<img class="uploading-img" src="/public/mobile/img/icon/purchase-empty.png"><h2 class="uploading-text fs-14 fc-3">小编正努力上传课程中..</h2></li>';
          }
          else{
            if(topcourse.length>0){
              allhtml  = allhtml +'<li class="content-right-item topcourse-box">'
              for(var i=0;i<topcourse.length;i++){
                if(topcourse[i].price==0.00){
                  var price_html = '免费'; 
                }else{
                  var price_html = '￥'+topcourse[i].price;
                }
                var tophtml = ''+'<div class="course-box" data-id="'+topcourse[i].cid+'">'+'<div class="img-box">'+'<img src="'+topcourse[i].face+'" alt="head">'+'</div>'+
                    '<div class="desc-box"><h4>'+topcourse[i].title+'</h4><div class="count"><span>'+topcourse[i].study_count+'</span>人在学</div>'+
                    '<span class="price">'+price_html+'</span></div></div>';		
              allhtml  += tophtml;
              }
              allhtml  += '</li>';
            }
            if(childcate.length>0){
              for(var j=0;j<childcate.length;j++){
                var courselist = childcate[j].child_course_list;
                if(courselist){
                  var allhtml = allhtml+'<li class="content-right-item">'+'<h3 class="subtitle">'+childcate[j].cate_name+'</h3>';
                  var childhtml = '';
                    for(var z=0;z<courselist.length;z++){
                      if(courselist[z].price==0.00){
                        var price_html = '免费';
                      }else{
                        var price_html = '￥'+courselist[z].price;
                      }
                      var childhtml = childhtml+'<div class="course-box" data-id="'+courselist[z].cid+'">'+'<div class="img-box">'+'<img src="'+courselist[z].face+'" alt="head">'+'</div>'+
                        '<div class="desc-box"><h4>'+courselist[z].title+'</h4><div class="count"><span>'+courselist[z].study_count+'</span>人在学</div>'+
                        '<span class="price">'+price_html+'</span></div></div>';
                    }
                    allhtml  = allhtml +childhtml+'</li>';
                  }
                }
            }
          }
          allhtml = allhtml+'</ul>';
          $('.content-right').html(allhtml);
          setTimeout(function () {
            var myScroll_r = new IScroll('#wrapper-r', {});
          }, 0)
          if($('.content-right-item').length == 1){
            $('.content-right-item').css('box-shadow', 'none');
          }
        }); 
	    }	  </script>
	  <script src="/public/mobile/js/bindmobile.js"></script>
	</body>
</html>