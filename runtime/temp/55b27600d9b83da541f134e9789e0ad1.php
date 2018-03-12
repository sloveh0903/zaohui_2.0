<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:36:"./template/mulit/pc/index/index.html";i:1518153715;s:36:"./template/mulit/pc/common/head.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title><?php echo $config['sitename']; ?></title>
  <meta name="keywords" content="<?php echo $config['seo_keywords']; ?>">
  <meta name="description" content="<?php echo $config['seo_description']; ?>">
  <link rel="stylesheet" type="text/css" href="/public/mulitpc/css/common.css">
  <link rel="stylesheet" type="text/css" href="/public/mulitpc/css/index.css">
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


  <!-- banner -->
  <div class="banner">
    <!--11月21日轮播图开始-->
    <!--多分类-->
    <div class="banner-nav">
      <ul class="banner-nav-list">
        <?php if(is_array($cate) || $cate instanceof \think\Collection || $cate instanceof \think\Paginator): $ki = 0; $__LIST__ = $cate;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ct): $mod = ($ki % 2 );++$ki;if($ki<=6): ?>
        <li class="banner-nav-item" data-topid="<?php echo $ct['id']; ?>">
          <?php if(isset($ct['son'])): if($ki == 6): ?>
          <a href="<?php echo url('mulitcourse/index',['topid'=>$ct['id']]); ?>">...</a>
          <?php else: ?>
          <p><?php echo $ct['cate_name']; ?></p>
          <?php endif; if($ki < 6): ?>
          <span class="arrow-right"></span>
          <ul class="banner-navlist-lv2">
            <?php if(is_array($ct['son']) || $ct['son'] instanceof \think\Collection || $ct['son'] instanceof \think\Paginator): $v = 0; $__LIST__ = $ct['son'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$child): $mod = ($v % 2 );++$v;if($v<=8): ?>
            <li class="banner-navitem-lv2">
              <a href="<?php echo url('mulitcourse/index',['topid'=>$ct['id'],'pid'=>$child['id']]); ?>"><?php echo $child['cate_name']; ?></a>
            </li>
            <?php elseif($v==9): ?>
            <li class="banner-navitem-lv2">
              <a href="<?php echo url('mulitcourse/index',['topid'=>$ct['id'],'pid'=>'']); ?>">...</a>
            </li>
            <?php endif; endforeach; endif; else: echo "" ;endif; ?>
          </ul>
          <?php endif; else: ?>
          <a href="<?php echo url('mulitcourse/index',['topid'=>$ct['id']]); ?>"><?php if($ki == 6): ?>...<?php else: ?><?php echo $ct['cate_name']; endif; ?></a>
          <?php endif; ?>
        </li>
        <?php endif; endforeach; endif; else: echo "" ;endif; ?>

      </ul>
    </div>
    <!--轮播图-->
    <div class="banner_center">
      <!--大屏幕图-->
      <ul class="banner_img">
        <?php if(is_array($adv_item) || $adv_item instanceof \think\Collection || $adv_item instanceof \think\Paginator): $adv_key = 0; $__LIST__ = $adv_item;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$adv): $mod = ($adv_key % 2 );++$adv_key;?>
        <li <?php if($adv_key == 1): ?>class="focus"<?php endif; ?>><a href="<?php echo $adv['link']; ?>" target="_blank"><img src="<?php echo $adv['photopath']; ?>"/></a></li>
        <?php endforeach; endif; else: echo "" ;endif; ?>
      </ul>
      <!--轮播按钮-->
      <ol class="ol_box">
        <?php if(is_array($adv_item) || $adv_item instanceof \think\Collection || $adv_item instanceof \think\Paginator): $vda_key = 0; $__LIST__ = $adv_item;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vda): $mod = ($vda_key % 2 );++$vda_key;?>
        <li <?php if($vda_key == 1): ?>class="cur"<?php endif; ?>><span></span></li>
        <?php endforeach; endif; else: echo "" ;endif; ?>

      </ol>
      <!--左右箭头-->
      <div class="arrow_l banner_arrow" style="display: none;"><img src="/public/pc/images/keyboard_arrow_left@2x.png"/></div>
      <div class="arrow_r banner_arrow" style="display: none;"><img src="/public/pc/images/keyboard_arrow_right@2x.png"/></div>
    </div>
  </div>





  <!-- course-showbox -->
  <div class="content-page course-showbox">
    <div class="course-showbox-top">
      <h2>最新课程</h2>
      <a href="<?php echo url('mulitcourse/index'); ?>">更多</a>
    </div>
    <div class="course-showbox-list">
      <?php if(is_array($course) || $course instanceof \think\Collection || $course instanceof \think\Paginator): $i = 0; $__LIST__ = $course;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ao): $mod = ($i % 2 );++$i;?>
      <a href="<?php echo url('mulitcourse/detail'); ?>?cid=<?php echo $ao['cid']; ?>" class="course-showbox-item">
        <img src="<?php echo $ao['banner']; ?>" alt="<?php echo $ao['title']; ?>" class="course-img">
        <h4 class="course-caption"><?php echo $ao['title']; ?></h4>
        <p class="course-desc"><?php echo $ao['desc']; ?></p>
        <p class="course-price"><?php if($ao['price']==0): ?>免费<?php else: ?>¥<?php echo $ao['price']; endif; ?></p>
      </a>
      <?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
  </div>

  <!-- company-info -->
  <div class="content-page company-info">
    <?php echo $config['introduce']; ?>
  </div>
<!-- <div class="qrcode-area"> -->
<!--       	<span class="span_weixin "> -->
<!--           <img src="/public/mulitpc/images/weixin@2x40.png" alt="微信" name="weixin"> -->
<!--           <div class="xiaochengxu_div weixin_div displayNone"> -->
<!--             <img src="<?php echo $config['wxcode']; ?>" alt="微信二维码"> -->
<!--             <p><?php echo $config['sitename']; ?>公众号</p > -->
<!--           </div> -->
<!--         </span> -->
<!--         <span class="span_xiaochengxu"> -->
<!--           <img src="/public/mulitpc/images/xcx@2x40.png" alt="小程序" name="xiaochengxu"> -->
<!--           <div class="xiaochengxu_div displayNone"> -->
<!--             <img src="<?php echo $config['wxmincode']; ?>" alt="小程序二维码"> -->
<!--             <p><?php echo $config['sitename']; ?>小程序</p > -->
<!--           </div> -->
<!--         </span>        -->
<!--     </div> -->
  <!-- footer -->
<style>
.footer {
    margin: 40px 0 20px;
}
.footer p {
    font-size: 12px;
    color: rgba(0, 0, 0, 0.2);
    text-align: center;
}
</style>
<div class="footer">
   <p><?php echo $config['copyright']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
	 <!--<a href="http://www.grazy.cn/" target="_blank">格子匠技术支持 GRAZY.CN</a>-->
  </p>
   <div class="qrcode-area">
      	<span class="span_weixin ">
      	  <i class="wx-img"></i>
          <!--<img src="./images/weixin@2x40.png" alt="微信" name="weixin">-->
          <div class="xiaochengxu_div weixin_div displayNone" style="display: none;">          	
            <img src="<?php echo $config['wxcode']; ?>" alt="微信二维码">
            <p><?php echo $config['sitename']; ?>公众号</p>
          </div>
        </span>
        <span class="span_xiaochengxu">
          <i class="xcx-img"></i>
          <!--<img src="./images/xcx@2x40.png" alt="小程序" name="xiaochengxu">-->
          <div class="xiaochengxu_div displayNone" style="display: none;">         	
            <img src="<?php echo $config['wxmincode']; ?>" alt="小程序二维码">
            <p><?php echo $config['sitename']; ?>小程序</p>
          </div>
        </span>       
      </div>
</div>

<script src="/public/mulitpc/js/jquery-1.11.0.min.js"></script>
<script src="/public/mulitpc/js/bootstrap.min.js"></script>
<script src="/public/mulitpc/js/index.js"></script>
<script src="/public/mulitpc/js/public_PC.js"></script>
</body>
</html>