<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:47:"./template/mulit/wechat/testitembank/index.html";i:1518064648;s:40:"./template/mulit/wechat/common/menu.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>题库</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/quiz-public.css" />
		<script src="/public/mobile/js/jquery-3.2.1.min.js"></script>
     	<script src="/public/mobile/js/mui.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/globla.js"></script>
	</head>

	<body> 
  <div class="wrap">
    <div class="content-wrapper">
    	<?php if(empty($bank_testlist) && empty($course_testlist)): ?>
    	<div class="empty-state">
		        <img src="/public/mobile/img/icon/folder.png" alt="empty">
		        <p>小编正在努力上传习题..</p>
	      	</div>
    	<?php endif; if(!empty($bank_testlist)): ?>
		<div class="quiz-bank">
	        <div class="caption">
	          <h2>题库练习</h2>
	          <a href="<?php echo url('/wechat/testitembank/bank_more'); ?>" class="more tikumore">更多</a>
	        </div>
	        <?php if(is_array($bank_testlist[0]) || $bank_testlist[0] instanceof \think\Collection || $bank_testlist[0] instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($bank_testlist[0]) ? array_slice($bank_testlist[0],0,3, true) : $bank_testlist[0]->slice(0,3, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$bank): $mod = ($i % 2 );++$i;?>
	        <div class="cate-box">
	          <div class="quiz-box">
	            <div class="info">
	              <h6 class="caption"><?php echo $bank['name']; ?></h6>
	              <p>
	              		<?php if($bank['is_finish']): ?>
	              		已完成
	              		<?php else: ?>
	              		<span class="count"><?php echo $bank['list_count']; ?></span>题
	              		<?php endif; ?>
	              </p>
	            </div>
	            <a href="" data-id="<?php echo $bank['id']; ?>" data-cid="<?php echo $bank['course_id']; ?>"  data-prv="<?php echo $bank['prvi_id']; ?>"  class="quiz-btn">开始做题</a>
	          </div>
	        </div>
	        <?php endforeach; endif; else: echo "" ;endif; ?>
	   </div>
	   <?php endif; if(!empty($course_testlist)): ?>
		<div class="afterclass-quiz" <?php if(empty($bank_testlist)): ?>style="margin-top:0;"<?php endif; ?>>
			<div class="caption">
	          <h2>课后练习</h2>
	          <a href="<?php echo url('/wechat/testitembank/course_more'); ?>" class="more kehoumore">更多</a>
	        </div>
	        <?php if(is_array($course_testlist) || $course_testlist instanceof \think\Collection || $course_testlist instanceof \think\Paginator): $i = 0;$__LIST__ = is_array($course_testlist) ? array_slice($course_testlist,0,3, true) : $course_testlist->slice(0,3, true); if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?>
	        <div class="cate-box">
	          <h4 class="caption"><?php echo $list[0]['course_name']; ?></h4>
		          <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$kehoulist): $mod = ($i % 2 );++$i;?>
		          <div class="quiz-box">
		            <div class="info">
		              <h6 class="caption"><?php echo $kehoulist['name']; ?></h6>
		              <p>
		                	<?php if($kehoulist['is_finish']): ?>
		              		已完成
		              		<?php else: ?>
		              		<span class="count"><?php echo $kehoulist['list_count']; ?></span>题
		              		<?php endif; ?>
		              </p>
		            </div>
		            <a href="" data-id="<?php echo $kehoulist['id']; ?>" data-cid="<?php echo $kehoulist['course_id']; ?>" data-prv="<?php echo $kehoulist['prvi_id']; ?>" class="quiz-btn">开始做题</a>
		          </div>
		          <?php endforeach; endif; else: echo "" ;endif; ?>
	        </div>
	        <?php endforeach; endif; else: echo "" ;endif; ?>
		</div>
		<?php endif; ?>		 
    </div> 
    <div class="buy-dialog" id="BuyDialog" style="z-index: 999">
    	<div class="alert-box">
			<h1 class="fs-20 fc-8">提示</h1>
			<p class="fs-16 fc-6">请先购买该课程</p>
			<div class="buy-dialog-btn">
				<a class="Cancel">取消</a>
				<a id="onBridgeReady" class="onBridgeReady">购买</a>
			</div>
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


    <script>
        var uid = '<?php echo $userinfo['uid']; ?>';
        var isbind = '<?php echo $userinfo['is_bind']; ?>';
    $(function () {

    	mui("body").on('tap', '.quiz-btn', function (event) {
    		id = $(this).attr('data-id');
    		prv = $(this).attr('data-prv');
    		cid = $(this).attr('data-cid');
 			if(prv==0){
 				if(cid !=0){
 	  				var url = "<?php echo url('/wechat/course/detail'); ?>?cid="+cid;
 	  			}else{
 	  				var url = "<?php echo url('/wechat/index/index'); ?>";
 	  			}
 				$('.onBridgeReady').attr('href',url);
 				$('.content-wrapper, .menuNav').addClass('blur');
 				$('.buy-dialog').addClass('flex-box');
 				return;
 			}
			var url = "<?php echo url('wechat/testitembank/do_testitem'); ?>";
			window.location.href=url+'?bank_id='+id;
		});
    	mui("body").on('tap', '.onBridgeReady', function (event) {
    		$(this).addClass('bg-color')
    		url = $(this).attr('href');
			window.location.href=url;
		});
    	mui("body").on('tap', '.tikumore', function (event) {
			var url = "<?php echo url('/wechat/testitembank/bank_more'); ?>";
			window.location.href=url;
		});
    	mui("body").on('tap', '.kehoumore', function (event) {
			var url = "<?php echo url('/wechat/testitembank/course_more'); ?>";
			window.location.href=url;
		});
    	 $('.buy-dialog .Cancel').on('click', function () {
    	 	$(this).addClass('bg-color')
             $('.content-wrapper, .menuNav').removeClass('blur');
             $('.buy-dialog').removeClass('flex-box');
           })
    });
    </script>
  <script src="/public/mobile/js/bindmobile.js"></script>

	</body>
</html>