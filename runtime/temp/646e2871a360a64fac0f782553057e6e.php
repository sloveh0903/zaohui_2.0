<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:42:"./template/mulit/pc/mulitcourse/index.html";i:1518153801;s:36:"./template/mulit/pc/common/head.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
   <title>课程</title>
   <link rel="stylesheet" href="/public/mulitpc/css/common.css">
   <link rel="stylesheet" href="/public/mulitpc/css/course.css">
   <!--[if lt IE 9]> 您的IE浏览器版本太低，请升级浏览器<![endif]-->
</head>
<body>
  <!-- header -->
  <!-- 微信登录start -->
<?php 
$request = request();
$controller = $request->controller();
$api_url = $_SERVER['SERVER_NAME'].'/api/config/index';
$curl_data = json_decode(doCurlGetRequest($api_url),true);
$config_data = $curl_data['data']['config'];
$backurl = urlencode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
$redirect_uri = urlencode("http://".$_SERVER['HTTP_HOST']."/api/wechat/scanlogin?backurl=".$backurl);
$curl_data = apiget('mulitcourse/index');
$course_data = $curl_data['data']['course'];
if(session('uid')){
    apipost('statistics/postStaticadd',['uid'=>session('uid')]);
}
$is_register = session('is_register')?session('is_register'):0;
$integral = session('integral')?session('integral'):0;
 ?>
<script src="/public/mulitpc/js/jquery-1.11.0.min.js"></script>
<div class="weixin_QRcord_alert_outside displayNone">
    <div class="weixin_QRcord_alert">
        <div class="delete_div">
            <a href="javascript:void(0)" class="a_close">
                <img src="/public/pc/images/delete@2x.png" alt="关闭">
            </a>
        </div>
        <div class="weixin_QRcord_top mt20">
            <img src="<?php echo $config_data['logo']; ?>" alt="logo图片" class="logo_img pt0">
        </div>
        <div class="weixin_QRcord_div" id="login_container">
            <!--<img src="/public/pc/images/weixin_QRcord@2x.png" alt="微信二维码">-->
        </div>
        <div class="" style="display: none">
            <span class="font14 opacity60" style="margin-top: 10px;display: block;">使用手机微信扫码登录</span>
        </div>
        <span class=" use_AccountLogin" style="margin-top:10px;display: none">使用账号登录</span>
    </div>
</div>
<?php if(session('audit') == 'no'): ?>
<div class="wrap" style="display: flex;">
    <div class="empty_box">
        <div class="empty_close"><img src="/public/pc/images/close.png"/></div>
        <div class="em_content">
            <img src="/public/pc/images/Group 4850@2x.png" alt="empty">
            <p>您的账户已被管理员禁用，请联系老师开通！</p>
        </div>

    </div>
</div>
<?php endif; if(session('is_bind') == 'no'): ?>
<div class="binddialog" style="display:flex;">
    <div class="bind-box">
        <i class="cross"></i>
        <h4 class="bind-caption">手机号码绑定</h4>
        <div class="validate-box">
            <input type="text" class="phonenum" placeholder="请输入11位有效手机号码">
            <input type="text" class="validatenum" placeholder="请输入验证码">
            <input type="button" value="发送验证码" class="getvalidate">
            <p class="error-alert"></p>
        </div>
        <i class="confirm">确定</i>
    </div>
</div>
<?php endif; ?>
<!-- 微信登录end -->
<!-- 账号登录 start -->
<div class="account_alert_outside displayNone">
    <div class="weixin_QRcord_alert">
        <div class="delete_div">
            <a href="javascript:void(0)" class="a_close">
                <img src="/public/pc/images/delete@2x.png" alt="关闭">
            </a>
        </div>
        <div class="weixin_QRcord_top mt20">
            <img src="<?php echo $config_data['logo']; ?>" alt="logo图片" class="logo_img pt0">
        </div>
        <div class="accountLogin_info">
            <input type="type" name="phoneNum" placeholder="手机号码" id="phoneNum">
            <input type="password" name="phonePassword" class="phonePassword mt20" placeholder="密码" id="phonePassword">

            <div class="mt10 textLeft">
                <i class="rightFloat showErrorWord" id="showErrorWord"></i>

                <div class="clearfix"></div>
            </div>
            <a href="javascript:void(0)" class="a_accountLogin">登录</a>

            <div class="mt10 font13 opacity40 color292B33 textLeft">首次登录请先使用微信扫码登录</div>
        </div>
        <span class="use_weixinLogin">使用微信登录</span>
    </div>
</div>
<!-- 账号登录 end -->


    <div class="youren_top boxShadowBottom">
        <a href="javascript:void(0)">
            <div class="logo_div leftFloat relative">
                <a href="<?php echo url('/'); ?>"><img src="<?php echo $config_data['logo']; ?>" alt="logo图片" class="logo_img"></a>
            </div>
        </a>

        <div class="youren_top_navDiv leftFloat">

            <ul class="youren_top_navul">
                <li><a href="<?php echo url('/'); ?>" <?php if(($controller == 'Index')): ?>class="default_navA" <?php endif; ?>>首页</a>
                </li>
<!--                 <li name="study_flatform"> -->
<!--                     <a href="<?php echo url('mulitcourse/index'); ?>" <?php if((isset($course_introduce))): ?>class="default_navA" <?php endif; ?>>课程介绍</a> -->
<!--                     <div class="course_introduce displayNone"> -->
<!--                         <ul class="course_introduce_ul"> -->
<!--                             <?php if(is_array($course_data) || $course_data instanceof \think\Collection || $course_data instanceof \think\Paginator): $i = 0; $__LIST__ = $course_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?> -->
<!--                             <li onclick="link(<?php echo $data['cid']; ?>)"> -->
<!--                                 <div class="div_href"> -->
<!--                                     <img src="<?php echo $data['face']; ?>" alt="课程老师头像"> -->

<!--                                     <p> -->
<!--                                         <i class="dan"><?php echo $data['title']; ?></i> -->
<!--                                     </p> -->

<!--                                     <div class="clearfix"></div> -->
<!--                                 </div> -->
<!--                             </li> -->
<!--                             <?php endforeach; endif; else: echo "" ;endif; ?> -->
<!--                         </ul> -->
<!--                     </div> -->
<!--                 </li> -->
                <li><a href="<?php echo url('mulitcourse/index'); ?>" <?php if(($controller == 'Mulitcourse')): ?>class="default_navA" <?php endif; ?>>课程</a>
                </li>
                 <?php 
				$is_showask = db('show_switch')->where(['id'=>1])->value('is_showask');
				 if($is_showask==1): ?>
                <li><a href="<?php echo url('ask/index'); ?>" <?php if(($controller == 'Ask')): ?>class="default_navA" <?php endif; ?>>问答</a>
                </li>
                <?php endif; ?>
                
<!--                 <li><a href="<?php echo url('article/index'); ?>" <?php if(($controller == 'Article')): ?>class="default_navA" <?php endif; ?>>阅读</a> -->
<!--                 </li> -->
            </ul>
        </div>
        <?php if(session('uid') && session('is_bind') == 'yes'): ?>
        <a href="javascript:void(0)" class="">
            <div class="youren_top_arrowDownImg rightFloat basicTop relative pl34 drop_menu_a">
                <img src="/public/pc/images/arrowDown_gray@2x.png" alt="向下箭头">
            </div>
            <div class="youren_top_myselfImg rightFloat basicTop relative  ml10">
                <a href='<?php echo url("member/index"); ?>'><img src="<?php echo session('face') ?>"
                                                      alt="<?php echo session('nickname') ?>"></a>
            </div>
            <div class="youren_top_name basicTop rightFloat ">
                <a href='<?php echo url("member/index"); ?>'><span class="opacity80"><?php echo session('nickname') ?></span></a>
            </div>
        </a>
        <a href='<?php echo url("member/message"); ?>' class="a_message">
            <div class="youren_top_messageDiv rightFloat relative">
                <span class="youren_top_messageSpan opacity40"><?php echo session('msg_num') ?></span>
            </div>
            <div class="youren_top_messageImg rightFloat relative">
                <img src="/public/pc/images/myMessage_gray@2x.png" alt="消息铃铛">
            </div>
        </a>
        <?php else: ?>
        <div class="rightFloat basicTop loginDiv">
            <a href="javascript:void(0)" class="a_login">登录</a>
        </div>
        <div class="clearfix"></div>
        <?php endif; ?>


        <!-- 下拉隐藏菜单 start -->
        <div class="youren_top_dropMenu_div displayNone">
            <ul class="youren_top_dropMenu_ul">
                <li>
                    <a href='<?php echo url("member/index"); ?>'>
                        <img src="/public/mulitpc/images/myStudy_gray@2x.png" alt="学习" data-name="study">
                        <i class="">学习</i>
                    </a>
                </li>
                <li>
                    <a href='<?php echo url("member/order"); ?>'>
                        <img src="/public/mulitpc/images/myOrder_gray@2x.png" alt="订单" data-name="order">
                        <i class="">订单</i>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)" id="loginout">
                        <img src="/public/mulitpc/images/loginout_gray@2x.png" alt="退出" data-name="outloign">
                        <i class="">退出</i>
                    </a>
                </li>
            </ul>
         </div>
         <!-- 下拉隐藏菜单 end --> 
         <div class="clearfix"></div>
      </div>

      <?php if(!session('uid') || empty(session('uid'))): ?>
      <script src="https://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js"></script>
      <script type="text/javascript">
      var redirect_uri = "<?php echo $redirect_uri; ?>";
      var appid = "<?php echo $config_data['wx_open_appid']; ?>";
      var obj = new WxLogin({
         id:"login_container", 
         appid: appid, 
         scope: "snsapi_login", 
         redirect_uri: redirect_uri,
         href:"https://api.fuwangdian.com/public/pc/css/qrcode.css"
      });

        var int = setInterval(function () {
            obj = false;
        }, 2000)
    </script>
    <?php endif; ?>
    
    <script type="text/javascript">
   
        function link(cid) {
            var url = "<?php echo url('mulitcourse/index'); ?>" + '?cid=' + cid;
            window.location.href = url
        }
        $(document).on("click",".empty_close",function(){
            $(".wrap").hide();
            $.ajax({
                    url: host + "user/ClearAudit",
                    data: {},
                    dataType: 'json',
                    type: 'POST',
                    success: function (res) {

                    }
                }
            );
        })
        var personalObj = {};

        //点击登录按钮
        $(".a_accountLogin").click(function () {
            //个人账号
            var phoneNum, showErrorWord, txtUserName;
            phoneNum = document.getElementById("phoneNum");    //文本框
            showErrorWord = document.getElementById("showErrorWord");  //span提示
            txtUserName = phoneNum.value;
            if (txtUserName != "") {
                if (/^[0-9a-zA-Z_]{1,}$/.test(txtUserName) == false) {
                    //showErrorWord.innerHTML = "没有此账号";
                    $('#showErrorWord').html('没有此账号');
                    console.log('没有此账号');
                    return false;
                }
                var mobileValid = /^(0|86|17951)?(13[0-9]|15[0-9]|17[0678]|18[0-9]|14[57])[0-9]{8}$/;
                if (!mobileValid.test(txtUserName)) {
                    showErrorWord.innerHTML = "请输入有效的手机号码";
                    console.log('请输入有效的手机号码')
                    return false;
                }
                personalObj.phoneNum = txtUserName;
                showErrorWord.innerHTML = "";
            } else {
                showErrorWord.innerHTML = "请输入手机号码";
                return false;
            }

            var phonePassword, showErrorWord, txtPassword;
            phonePassword = document.getElementById("phonePassword");    //文本框
            showErrorWord = document.getElementById("showErrorWord");  //span提示
            txtPassword = phonePassword.value;
            if (txtPassword != "") {
                if (txtPassword.length < 6) {
                    showErrorWord.innerHTML = "密码长度6-16位";
                    return false;
                }
                var reg = /^[\w]{6,16}$/;
                if (reg.test(txtPassword) == false) {
                    showErrorWord.innerHTML = "请输入正确的密码";
                    return false;
                }
                personalObj.passwordNum = txtPassword;
                showErrorWord.innerHTML = "";
            } else {
                showErrorWord.innerHTML = "请输入密码";
                return false;
            }


            //比较后台的用户名和密码 ajax调用
            var txtUserName = $('#phoneNum').val();
            var txtPassword = $('#phonePassword').val();
            $.ajax({
                        url: host + "user/login",
                        data: {mobile: txtUserName, password: txtPassword},
                        dataType: 'json',
                        type: 'POST',
                        success: function (res) {
                            if (res.code == 1) {
                                location.reload();
                            } else {
                                var showErrorWord = document.getElementById("showErrorWord");  //span提示
                                showErrorWord.innerHTML = res.message;
                                return false;
                            }
                        }
                    }
            );
        });
        // 绑定手机号弹窗 及倒计时
        var timer = null;
        $('.binddialog .cross').on('click', function () {
            $('.binddialog').hide();
            $('.phonenum').text('');
            $('.validatenum').text('');
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
            $.ajax({
                    url: host + "user/setbind",
                    data: {},
                    dataType: 'json',
                    type: 'POST',
                    success: function (res) {

                    }
                }
            );

        });
        function countDown(_this) {
            var timeLimit = 60;
            var that = _this;
            _this.disabled = true;
            $(_this).css({'border-color': '#eee', 'color' : 'hsla(228, 20%, 20%, .4)'});
            timer = setInterval(function () {
                if(timeLimit > 0) {
                    timeLimit--;
                    that.value = timeLimit + '再次发送';
                } else {
                    clearInterval(timer);
                    timer = null;
                    $(that).css({'border-color': '#00B6F2', color: '#00B6F2'});
                    that.value = '发送验证码';
                    that.disabled = false;
                }
            },1000)
        }
        $('.getvalidate').on('click', function () {
            var _this = this;
            var phoneNumber = $('.phonenum').val();
            var mobileValid = /^(0|86|17951)?(13[0-9]|15[0-9]|17[0678]|18[0-9]|14[57])[0-9]{8}$/;
            if (!mobileValid.test(phoneNumber)) {
                $('.binddialog .error-alert').text('请输入有效的手机号码');
                $('.phonenum').val('');
                return false;
            }
            $.ajax({
                url: host + 'user/sendcode',
                data: {
                    uid: "<?php echo session('user_id'); ?>",
                    phone: phoneNumber
                },
                type: 'POST', //GET
                async: true,    //或false,是否异步
                timeout: 5000,    //超时时间
                dataType: 'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                success: function (data, textStatus, jqXHR) {
                    if (data.code == 1) {
                        countDown(_this);
                        codeNumber = data.data;
                        var str = phoneNumber.substr(3, 4);
                        $('.binddialog .error-alert').text("验证码已免费发送到" + phoneNumber.replace(str, "****") + "");
                    } else {
                        $('.binddialog .error-alert').text(data.message);
                        return false;
                    }
                }
            });

        });
        $('.binddialog .confirm').on('click', function () {
            var phoneNumber = $('.phonenum').val();
            var validateCode = $('.validatenum').val();
            $.ajax({
                url: host + '/user/Checksms',
                data: {
                    uid: "<?php echo session('user_id'); ?>",
                    phone: phoneNumber,
                    code: validateCode
                },
                type: 'POST', //GET
                async: true,    //或false,是否异步
                timeout: 5000,    //超时时间
                dataType: 'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                success: function (data, textStatus, jqXHR) {
                    if (data.code == 1) {
                        $('.binddialog').hide();
                        $('.phonenum').text('');
                        $('.validatenum').text('');
                        if (timer) {
                            clearInterval(timer);
                            timer = null;
                        }
                        $.ajax({
                                url: host + "user/BindSuccess",
                                data: {uid: "<?php echo session('user_id'); ?>"},
                                dataType: 'json',
                                type: 'POST',
                                success: function (res) {
                                    location.reload();
                                }
                            }
                        );
                    } else {
                        $('.binddialog .error-alert').text(data.message);
                        return false;
                    }
                }
            });
        });


    </script>
   <script src="/public/jqadmin/js/layui/layui.js"></script>
     <script>
     var is_register = "<?php echo $is_register; ?>";
     var integral ="<?php echo $integral; ?>";
     var tmpTag = 'https:' == document.location.protocol ? false : true;
     if(tmpTag){
         var protocol = 'http';
     }else{
         var protocol = 'https';
     }
     var host = protocol+'://' + window.location.host + '/api/';
     var layer;
     layui.use('layer', function(){
         layer = layui.layer;
     });
     setTimeout(function(){
    	 $.get(host+'index/registerseession',{is_register:0},function(res){
    		 if(res.code==1&&res.data.is_register==1){
    			 alert_str  = '注册积分+'+res.data.integral;
    			 if(res.data.integral){
    				 $.get(host+'index/unsetregisterseession',{integral:res.data.integral,is_register:res.data.is_register},function(res){
        				 if(res.code==1){
        					 layer.msg(alert_str);
        				 }
        			 }); 
    			 }
    			 
    		 }
    	 });
    },1000);
    
     </script>


  <!-- course-main -->
  <div class="content-page course-main">
    <div class="left-nav">
      <div class="nav-item">
        <h4 class="catelv1-caption <?php if($topid==0): ?> active <?php endif; ?>" data-pid='0'  data-topid='0'>全部课程</h4>
      </div>
      <?php if(is_array($top_course_cate_list) || $top_course_cate_list instanceof \think\Collection || $top_course_cate_list instanceof \think\Paginator): $i = 0; $__LIST__ = $top_course_cate_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$top): $mod = ($i % 2 );++$i;?>
      <div class="nav-item" >
        <h4 class="catelv1-caption <?php if(!empty($top['child_list'])): ?> slide <?php endif; if($pid==$top['id'] || $topid==$top['id']): ?> active <?php endif; ?> " data-topid='<?php echo $top['id']; ?>'  data-pid='0'><?php echo $top['cate_name']; ?></h4>
        <?php if(!empty($top['child_list'])): ?>
         <ul class="catelv2-list" <?php if($pid==$top['id']||$topid==$top['id']): ?> style="display:block;" <?php endif; ?>  >
         <?php if(is_array($top['child_list']) || $top['child_list'] instanceof \think\Collection || $top['child_list'] instanceof \think\Paginator): $i = 0; $__LIST__ = $top['child_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub): $mod = ($i % 2 );++$i;?>
          <li class="catelv2-item <?php if($pid==$sub['id']): ?> active <?php endif; ?>" data-pid='<?php echo $sub['id']; ?>' data-topid='<?php echo $top['id']; ?>'><?php echo $sub['cate_name']; ?></li>
          <?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <?php endif; ?>
      </div>
      <?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    <div class="right-content">
      <div class="content-box">
      
      </div>
      <div  class='jqpage'>
      <div  class='jqpagediv'>
          <div class="page_div3 paging" onselectstart="return false">
          </div>
      </div>
  </div>
    </div>
  </div>
 
  <!-- footer -->
  <div class="footer">
    <p><?php echo $config_data['copyright']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
	  <!--<a href="http://www.grazy.cn/" target="_blank">格子匠技术支持 GRAZY.CN</a>-->
	</p>
  </div>
<script src="/public/mulitpc/js/bootstrap.min.js"></script>
<script src="/public/mulitpc/js/public_PC.js"></script>
<script src="/public/mulitpc/js/jquery.paging.js"></script>
<script>
var pid = "<?php echo $topid; ?>";
var child_id = "<?php echo $pid; ?>";
var limits = 4;
    $(function () {
    	var tmpTag = 'https:' == document.location.protocol ? false : true;
    	if(tmpTag){
    		var protocol = 'http';
    	}else{
    		var protocol = 'https';
    	}
        var host = protocol+'://' + window.location.host + '/api/';
        changepage(host,pid,child_id);
        function changepage(host,pid,child_id){
        	//自定义皮肤，精简模式一
        	$(".page_div3").html('');
            var page4 = $(".page_div3").paging({
                submitStyle:"ajax",
                ajaxSubmitType: "get",
                url:host + 'course/mulitindex',
                ajaxData:{child_id:child_id,pid:pid},
                ajaxSubmitBack: function (data) {
                	
                	var currentpage = data.currentPage;
                	
               		changecourselist(data.courselist);
               		if(currentpage==1&&data.courselist.length<16)
               		{
                		$('.jqpagediv').hide();
               		}
                },
            });
        }
        function changecourselist(courselist){
        	var temphtml ='';
        	if(courselist.length==0){
        		temphtml =temphtml+'<div class="nocourse"><div><img src="/public/mulitpc/images/nocourse.png"></div><span>小编正努力上传课程中..</span></div>';
        		$('.jqpagediv').hide();
        	}else{
        		$('.jqpagediv').show();
        		for (var i = 0; i < courselist.length; i++) {
        			if(courselist[i]['price']==0.00){
						var price_html = '免费';
					}else{
						var price_html = '￥'+courselist[i]['price'];
					}
      		   		temphtml =temphtml+'<a href='+'<?php echo url("mulitcourse/detail"); ?>?cid='+courselist[i]['cid']+' class="course-showbox-item">'+
      				 		' <img src="'+courselist[i]['banner']+'" alt="course-img" class="course-img">'+
      				 		'<h4 class="course-caption">'+courselist[i]['title']+'</h4>'+
      						'<p class="course-desc">'+courselist[i]['desc']+'</p>'+
      						'<p class="course-price">'+price_html+'</p>'+
      						'</a>';
      	  		}
        		
        	}
       		$('.content-box').html(temphtml);
        }
        //一级栏目
        $('.catelv1-caption').on('click', function () {
            /*if(!$(this).hasClass('active')) {
              $(this)
              .parent()
              .siblings()
              .find('.catelv1-caption')
              .removeClass('active')
              .next()
              .hide();
              $(this)
              .addClass('active')
              .next('.catelv2-list')
              .show();
            } else {
              $(this).
              removeClass('active')
              .next('.catelv2-list')
              .hide();
            }*/
            if(!$(this).hasClass('active')) {
                $(this)
                    .parent()
                    .siblings()
                    .find('.catelv1-caption')
                    .removeClass('active expanded')
                    .next()
                    .hide();
                $(this)
                    .addClass('active');
            }
            $(this)
                .toggleClass('expanded')
                .next('.catelv2-list')
                .toggle()
                .children('li').removeClass('active');
             var topid = $(this).attr('data-topid');
	         var pid = $(this).attr('data-pid');
	         changepage(host,topid,pid);
          });
        //二级栏目 点击跳转
         $('.catelv2-item').click(function() {
        	 $(this).siblings().removeClass('active');
        	 if(!$(this).hasClass('active')) {
                 $(this)
                 .parent()
                 .siblings()
                 .find('.catelv1-caption')
                 .removeClass('active')
                 .next()
                 .hide();
                 $(this)
                 .addClass('active');
               } else {
                 $(this).
                 removeClass('active');
               }

        	 var topid = $(this).attr('data-topid');
	         var pid = $(this).attr('data-pid');
	         changepage(host,topid,pid);
		});
    });

</script>

</body>
</html>