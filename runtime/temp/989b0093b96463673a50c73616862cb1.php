<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:43:"./template/mulit/pc/mulitcourse/detail.html";i:1518153855;s:36:"./template/mulit/pc/common/head.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
   <title>课程详情</title>
   <link rel="stylesheet" href="/public/mulitpc/css/common.css">
   <link rel="stylesheet" href="/public/mulitpc/css/coursedetail.css">
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


  <!-- coursedetail-banner -->
  <div class="coursedetail-banner">
    <div class="content-box">
      <div class="content-page">
          <div class="content-left">
            <div class="breadcrumb">
              <a href="<?php echo url('mulitcourse/index'); ?>">课程</a>
              <a href="<?php echo url('mulitcourse/index'); ?>?topid=<?php echo $topid; ?>&pid=0" class="lv1"><?php echo $topname; ?></a>
              <?php if($pname): ?>
              <a href="<?php echo url('mulitcourse/index'); ?>?topid=<?php echo $topid; ?>&pid=<?php echo $pid; ?>" class="lv2"><?php echo $pname; ?></a>
              <?php endif; ?>
              <span href="#"><?php echo $course['title']; ?></span>
            </div>
            <div class="course-shortcut">
              <div class="img-box">
                <img src="<?php echo $course['banner']; ?>">
              </div>
              <div class="info-box">
                <h4 class="course-caption"><?php echo $course['title']; ?></h4>
                <p class="course-desc"><?php echo $course['desc']; ?></p>
                <div class="course-rating">
                  <p class="rating-num"><?php echo $course['score']; ?></p>
                  <div class="rating-stars">
                 
                  </div>
                  <?php if($course['virtual_amount'] == 0): ?>
                  <i class="study-count"><?php echo $study_count; ?>人在学</i>
                  <?php else: ?>
                  <i class="study-count"><?php echo $course['virtual_amount']; ?>人在学</i>
                  <?php endif; ?>
                </div>
                 
              </div>
            </div>
          </div>
          <div class="content-right">
            <div class="qrcode-area">
              <div class="weixin-box">
                <i class="icon weixin-icon"></i>
                <div class="qrcode-box">
                  <img src="/public/mulitpc/images/weixin_QRcord@2x.png" alt="weixin_img">
                  <p>公众号</p>
                </div>
              </div>
              <div class="weapp-box">
                <i class="icon weapp-icon"></i>
                <div class="qrcode-box">
                  <img src="/public/mulitpc/images/weixin_QRcord@2x.png" alt="weapp_img">
                  <p>小程序</p>
                </div>
              </div>
            </div>
            <div class="purchase-area">
            <?php if($course['price']==0.00): if($buy): ?>
	            <i class="purchase-btn btn" id="freestudy" data-cid='0'>立即学习</i>
	            <?php else: ?>
	            <i class="purchase-btn btn" id="freebuy" data-cid='<?php echo $course['cid']; ?>'>立即报名</i>
	            <?php endif; else: ?>
              <p class="price">¥<?php echo $course['price']; ?></p>
                <?php if($vip > 0): ?>
                <i class="vip-btn btn">
                    <i class="vip-icon icon">VIP</i>
                    <p>会员免费看</p>
                </i>
                <?php else: if(!$buy): ?>
                <i class="purchase-btn btn" id="cousebuy" data-cid='<?php echo $course['cid']; ?>'>购买</i>
                <?php else: ?>
                <i class="purchase-btn btn purchased"> 已购买</i>
                <?php endif; endif; endif; ?>
            </div>
          </div>
      </div>
    </div>
  </div>

  <div class="filter-div"></div>
  <!-- coursedetail-main -->
  <div class="coursedetail-main-box">
    <div class="content-page coursedetail-main">
      <div class="content-left">
        <div class="upper">
          <div class="tablist">
            <i class="intro-tab active">课程介绍</i>
            <i class="chapter-tab">课程章节</i>
          </div>
          <?php if($is_testitemshop==1 && !empty($testitem_qrcode)): ?>
          <div class="atOnce_buy" style="margin-right: 30px; background-color: #fff; position: relative;">
           	<span class=" course_testitem">练习</span>
           	<div class='course_testewm'>
           		<img src="<?php echo $testitem_qrcode; ?>" alt="微信二维码" style='width:150px;height:150px;'>
           		<p style="text-align: center;">扫码进入微信端做题</p>
          	</div>
          </div>
          <?php endif; ?>
          <a href="<?php echo url('ask/index'); ?>">答疑</a>
        </div>
        <div class="lower">
          <div class="intro-box" >
            <div class="nav">
              <ul class="navlist">
              	<?php if(is_array($course_introduce) || $course_introduce instanceof \think\Collection || $course_introduce instanceof \think\Paginator): $i = 0; $__LIST__ = $course_introduce;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;?>
                <li class="navitem " data-id='<?php echo $data['id']; ?>' data-cid='<?php echo $cid; ?>'><?php echo $data['title']; ?></li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </ul>
            </div>
            <div class="intro-content">
              
            </div>
          </div>
          <div class="chapter-box">
            <div class="chapter-nav">
              <ul class="chapter-list">
              <?php if(is_array($video_category) || $video_category instanceof \think\Collection || $video_category instanceof \think\Paginator): $i = 0; $__LIST__ = $video_category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$chater): $mod = ($i % 2 );++$i;?>
                <li class="chapter-item" data-chaterid="<?php echo $chater['id']; ?>" title="<?php echo $chater['cate_name']; ?>">
                  	<?php echo $chater['cate_name']; ?>
                </li>
                <?php endforeach; endif; else: echo "" ;endif; ?>
              </ul>
            </div>
            <div class="course-box">
                  <ul class="courselist">
				  	
				  </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="content-right">
      <?php if($iscomment ==0): if($uid && $buy): ?>
<!--            && $speed > 30 -->
            <div class="course_manyidu ">
                <!-- 完成评价蒙板start  -->
                <div class="comment_finish_alert displayNone">
                    <span>已评价!</span>
                </div>
                <!-- 完成评价蒙板end -->
                <div class="manyidu_star" id="startone">
                    <i>课程满意度：</i>
                  <span class=" opacity40">
                     <a href="javascript:void(0)"></a>
                     <a href="javascript:void(0)"></a>
                     <a href="javascript:void(0)"></a>
                     <a href="javascript:void(0)"></a>
                     <a href="javascript:void(0)"></a>
                  </span>

                    <div class="cover_star"></div>
                    <div class="clearfix"></div>
                </div>
                <div class="user_comment">
                    <textarea name="" placeholder="输入您的评价.." maxlength="100" readonly="true"></textarea>
                </div>
                <div class="btn_manyidu_div">
                    <span><i class="comment_strnum">0</i><i>/100</i></span>
                    <a href="javascript:void(0)" class="a_manyidu_submit opacity40"></a>

                    <div class="cover_submit"></div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <?php endif; endif; ?>   
          <div class="user_comment_list">
             <ul class="user_comment_list_ul">
             
                
              
             </ul>
             <div class="textRight">
                <span class="more_comment">更多</span>
             </div>   
          </div>
         <!--  <div class="studentNum">
            <i><?php echo $study_count; ?></i>
            <i>学员</i>
          </div>
          <div class="student_head">
              <?php if(is_array($student_user) || $student_user instanceof \think\Collection || $student_user instanceof \think\Paginator): $i = 0; $__LIST__ = $student_user;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$data): $mod = ($i % 2 );++$i;if(empty($data['face'])): ?>
                <img src="/public/pc/images/no_login@2x.png" alt="">
                <?php else: ?>
                <img src="<?php echo $data['face']; ?>" alt="">
                <?php endif; endforeach; endif; else: echo "" ;endif; ?>
          </div> -->
      </div>
    </div>
  </div>

  <!-- footer -->
  <div class="footer">
    <p><?php echo $config_data['copyright']; ?>&nbsp;&nbsp;&nbsp;&nbsp;
	  <!--<a href="http://www.grazy.cn/" target="_blank">格子匠技术支持 GRAZY.CN</a>-->
	</p>
  </div>

<!-- 评论弹出框分页加滚动条 start -->
<div class="userComment_alertOutside displayNone">
    <div class="userComentPager">
        <div class="userComentPager_top">
            <i>课程评价</i>
            <img src="/public/pc/images/delete@2x.png" alt="关闭" class="img_close">
            <span class="clearfix"></span>
        </div>
        <div class="userCommentPager_content mCustomScrollbar">
            <ul class="user_comment_list_ul userCommentListUl">
            </ul>
        </div>
        <div class="userComentPager_footer">
            <div class="tcdPageCode" style="margin-top:-20px"></div>
        </div>
    </div>
</div>
<!-- 评论弹出框分页加滚动条 end -->
<!-- 购买提示 start -->
<div class="courseBuy_tip displayNone">
    <div class="w1180">
        <i>提醒&nbsp;&nbsp;播放视频需要购买该视频</i>
        <img src="/public/pc/images/close_white@2x40.png" alt="关闭" class="img_close">
        <a href="<?php echo url('mulitcourse/payment',['cid'=>$cid]); ?>">购买</a>
        <span class="clearfix"></span>
    </div>
</div>
<!-- 购买提示 end -->
<script src="/public/mulitpc/js/jquery-1.11.0.min.js"></script>
<script src="/public/mulitpc/js/bootstrap.min.js"></script>
<script src="/public/mulitpc/js/public_PC.js"></script> 
<script src="/public/mulitpc/js/jquery.page.js"></script>
<script src="/public/jqadmin/js/layui/layui.js"></script>
<script>
var cid = "<?php echo $cid; ?>";
var uid = "<?php echo $uid; ?>";
var buy = "<?php echo $buy; ?>";
var qrcode = "<?php echo $qrcode; ?>";
var minqrcode = "<?php echo $minqrcode; ?>";
var page = 1;
var size = 100;
var introdice_id = "<?php echo $introdice_id; ?>";
var chaterid = "<?php echo $chaterid; ?>";
var layer;
var course_price = "<?php echo $course['price']; ?>";
var title = "<?php echo $course['title']; ?>";
layui.use('layer', function(){
    layer = layui.layer;
});
var bg_url = "<?php echo $course['banner']; ?>";
$(function () {
	$('<style>.coursedetail-banner .content-box::after{background-image: url("'+ bg_url +'")}</style>').appendTo('head');
	$('.weixin-box .qrcode-box img').attr('src',qrcode);//公众号
	$('.weapp-box .qrcode-box img').attr('src',minqrcode);//小程序
	
	var tmpTag = 'https:' == document.location.protocol ? false : true;
	if(tmpTag){
		var protocol = 'http';
	}else{
		var protocol = 'https';
	}
    var host = protocol+'://' + window.location.host + '/api/';
    
	if(introdice_id !=0){
		introdice(introdice_id);
		$('.navitem')[0].classList.add('active');
	}
	else{
		
		temphtml ='<div class="nocourse"><div><img src="/public/mulitpc/images/nocontent.png"></div><span>小编正努力上传课程中..</span></div>';
		$('.intro-box').css('justify-content:center;');
		$('.intro-box').html(temphtml);
	}
	if(chaterid !=0){
		chaptervideo(chaterid);
		$('.chapter-item')[0].classList.add('active');
	}
	//显示前三个评论
	function get_index_comment(){
		$.get(host + "comment/index?cid=" + cid + "&page=1&size=3", function (result) {
			var comment_list_ul_html = '';
			if (result.code == 1) {
				var comment = result.data.comment;
				if(comment.length>0){
					for (var i = 0; i < comment.length; i++) {
						var star_inner = '';
		                   var star = comment[i].star;
		                   for(var j=0; j<star;j++){
		                       star_inner = star_inner + '<img src="/public/pc/images/gray_fullStar@2x14.png" alt="评级星星">';
		                   }
		                   var empty_star = '';
		                   if(star < 5){
		                       var empty_length = 5-star;
		                       for(var k=0;k<empty_length;k++){
		                           empty_star = empty_star + '<img src="/public/pc/images/gray_emptyStar@2x14.png" alt="评级星星">';
		                       }
		                   }
		                   star_inner = star_inner+empty_star;
		                   comment_list_ul_html = comment_list_ul_html+
		                   						'<li><img src="' + comment[i].face + '" alt="用户头像" class="userHead_img30">'+
		                   						'<div class="user_comment_detail"><i class="dan">' + comment[i].nickname + '</i>'+
		                   						'<span>'+star_inner+'</span>'+
		                   						'<p class="dot-ellipsis dot-height-50">' + comment[i].content + '</p>'+
		                   						'<p class="teacher_ans">' + comment[i].reply + '</p>'+
		                   						'</div><div class="clearfix"></div>'+
		                   						'</li>';
					}
					$('.user_comment_list_ul').html(comment_list_ul_html);
					$('.textRight').html(' <span class="more_comment">更多</span>');
				}else{
					var empty_html ='<div class="empty-comment"><div><img src="/public/mulitpc/images/empty_comment.png" alt="图片"><p>暂无评价</p></div></div>';
					$('.textRight').html(empty_html);
				}
				
				
			}
			
		 });
	}
	get_index_comment();
	//评价列表
   getcomment();
   function getcomment() {
       $.get(host + "comment/index?cid=" + cid + "&page=" + page + "&size=" + size, function (result) {
           if (result.code == 1) {
               var comment = result.data.comment;
               var inner = "";
               for (var i = 0; i < comment.length; i++) {
                   var star_inner = '';
                   var star = comment[i].star;
                   for(var j=0; j<star;j++){
                       star_inner = star_inner + '<img src="/public/pc/images/gray_fullStar@2x14.png" alt="评级星星">';
                   }
                   var empty_star = '';
                   if(star < 5){
                       var empty_length = 5-star;
                       for(var k=0;k<empty_length;k++){
                           empty_star = empty_star + '<img src="/public/pc/images/gray_emptyStar@2x14.png" alt="评级星星">';
                       }
                   }
                   star_inner = star_inner+empty_star;
                   inner +=
                           '<li>' +
                           '<img src="' + comment[i].face + '" alt="" class="userHead_img30">' +
                           '<div class="user_comment_detail userCommentDetail">' +
                           '<i class="dan">' + comment[i].nickname + '</i>' +
                           '<span>' +star_inner+'</span>' +
                           '<p class="dot-ellipsis dot-height-50">' + comment[i].content + '</p>' +
      						'<p class="teacher_ans">' + comment[i].reply + '</p>'+
                           '</div>' +
                           '<div class="clearfix"></div>' +
                           '</li>';
               }
               $(".userCommentListUl").html(inner);
           }
       });
   }
	//星星评分
    star_inner = '';
    var star_num = Number("<?php echo $course['star']; ?>")
    var showStarSrc = showstar(star_num);
    for (var i = 1; i < showStarSrc.length; i++) {
        star_inner += '<img src="' + showStarSrc[i] + '" alt="评价星星">';
    }
    $('.rating-stars').html(star_inner);

    //星星显示数量
    function showstar(star) {
        var starNum = parseInt(star)
        var numType = starNum % 2
        var stars = starNum / 2
        console.log(numType + starNum)
        var starImg = [];
        for (var i = 1; i < 6; i++) {
            if (i <= stars) {
                starImg[i] = '/public/mulitpc/images/white_fullStar@2x14.png'
            } else {
                if (numType == 1 && i == parseInt(stars) + 1) {
                    starImg[i] = '/public/mulitpc/images/white_halfStar@2x14.png'
                } else {
                    starImg[i] = '/public/mulitpc/images/white_emptyStar@2x14.png'
                }
            }
        }
        return starImg;
    }
    if (uid) {
        loginAfter(); //登陆购买之后调用此方法
    }
    function loginAfter() {
        //如果用户登陆，然后购买之后右边满意度正常效果
        $(".manyidu_star span").removeClass("opacity40");
        $(".user_comment textarea").attr("readonly", false);
        $(".a_manyidu_submit").removeClass("opacity40");
        $(".cover_star").hide();
        $(".cover_submit").hide();
    }
    
  //点击购买
    $("#cousebuy").click(function () {
        if (!uid) {
            $(".weixin_QRcord_alert_outside").show();
            return false;
        }
        var cid = $(this).attr('data-cid');
       	var url = "<?php echo url('mulitcourse/payment'); ?>?cid=" + cid;
        window.location.href = url;  
    })
    //点击立即报名 购买
    $('#freebuy').click(function(){
    	if (!uid) {
            $(".weixin_QRcord_alert_outside").show();
            return;
        }
    	$.post(host+"order/add",{uid:uid,cid:cid,source:'pc',title:title,coupon_code:''},function(res){
            if(res.code == 1){
                if(res.data.pay_status == 1){
                    var url = "<?php echo url('index/mulitcourse/detail'); ?>";
                    window.location.href=url+'?cid='+cid;
                }
            }
        });
    });
    //点击立即学习
    $('#freestudy').click(function(){
    	$('.intro-tab').removeClass('active');
		$('.chapter-tab').addClass('active');
		$('.intro-box').hide();
		$('.chapter-box').css('display','flex');
    });
  //评论提交按钮事件
    var commentSubmitObj = {};  //提交数据
    $(".a_manyidu_submit").click(function () {
        if (!uid) {
            $(".weixin_QRcord_alert_outside").show();
            return false;
        }
        var textareaValue = $(".user_comment textarea").val();
        commentSubmitObj.textareaValue = textareaValue;
        if(commentSubmitObj.starNum != undefined){
        	my_star = commentSubmitObj.starNum;
        }else{
        	my_star =5;
        }
        if ("" != textareaValue) {
            $.post(host + "comment/add", {
                uid: uid,
                cid: cid,
                content: textareaValue,
                star: my_star
            }, function (result) {
                if (result.code == 1) {
                    $(".comment_finish_alert").show();
                    $(".course_manyidu").hide(3000);
                    //location.reload() ;
                    if(result.data.integral_code==1){
    					var alert_str = '提交成功'+'积分+'+result.data.integral;
    				}else{
    					var alert_str = '提交成功';
    				}
            	    layer.msg(alert_str);
                  //重新获取评价列表
                   get_index_comment();
                    getcomment();
                } else if (result.code == -1) {
                    layer.msg(result.message);
                }
            });
        }
    })
    //用户评论点星星
        $(".manyidu_star span a").click(function () {
            var currIndex = $(this).index() + 1;
            $(".manyidu_star span a").removeClass("imghover")
            $(".manyidu_star span a:lt(" + currIndex + ")").addClass("imghover");
            commentSubmitObj.starNum = currIndex;
        })
        
     //播放视频时提示 先登录再购买
        $(".courselist li  a,.courselist li .icon .play-icon").click(function () {
            if (!uid) {
                $(".weixin_QRcord_alert_outside").show();
                return;
            }
            if (!buy) {
                $(".courseBuy_tip").show();
                return;
            }
        })   
    //pc介绍
    $('.navitem').click(function() {
		var id = $(this).attr('data-id');
		introdice(id);
	    
	});
    function introdice(id){
    	$.get(host + "courseintroduce/detail?id="+id, function (result) {
	           if (result.code == 1) {
	              		$('.intro-content').html(result.data);
                    // 课程详情footer样式
                    if($(window).innerHeight() - $('.coursedetail-main-box') - 368 > 0) {
                    $('.footer').css({'position': 'absolute', 'bottom': '20px', 'width': '100%'});
                    } else {
                    $('.footer').css('position', 'static');
                    }
	           }
	       });
    }
    //视频章节选择 

    $('.chapter-item').click(function() {
		var chaterid = $(this).attr('data-chaterid');
		chaptervideo(chaterid);
	});
    //视频点击 观看
    $('.courselist').on('click', '.courseitem', function() {
    	 var that = $(this);
         if (!uid) {
             $(".weixin_QRcord_alert_outside").show();
             return false;
         }
         console.log(course_price);
         var free = $(this).attr('data-free');
       //试看
         if(free == 1){
            if(!buy){
               var url = "<?php echo url('mulitcourse/free'); ?>?id=" + $(this).attr('data-id') + "#" + $(this).attr('data-id');
            }else{
               var url = "<?php echo url('mulitcourse/video'); ?>?id=" + $(this).attr('data-id')+'#'+$(this).attr('data-id');
            }
            window.open(url);
            return;
         }
         var url = "<?php echo url('mulitcourse/video'); ?>?id=" + $(this).attr('data-id')+'#'+$(this).attr('data-id');
         if(course_price>0.00){
        	 if (!buy) {
                 layer.msg("未购买");
                 return false;
              }
         }
         window.open(url);
       
	});
    function chaptervideo(chaterid){
    	$.get(host + "chapter/videolist?id="+chaterid+'&cid='+cid+'&uid='+uid, function (result) {
	           if (result.code == 1) {
	        	   var datali = '';
	        	   var video = result.data;
	        	   for (var i = 0; i < video.length; i++) {
	        		   var numb = i+1;
	        		   var datafree = '';
	        		   var is_study = video[i].is_study;
	        		   if(is_study){var studyhtml = '<i class="icon play-icon played"></i>';}else{var studyhtml = '<i class="icon play-icon"></i>';}
	        		   datali = datali+
	        			   '<li class="courseitem" data-id="' + video[i].id + '"  data-free="' + video[i].free + '">'+
	        			   studyhtml+
	        			   '<a class="course-caption" href="javascript:void(0)">'+numb+'.'+video[i].title;
     			   if(video[i].free==1){
     				    datafree = '<em class="free">试看</em>';
     			   }
	        		   datali = datali + datafree+'</a><div class="course-duration"><i class="icon time-icon"></i><span class="time">'+
	        		   video[i].lenght+'</span>'+'</div></li>';  
	        	   }
	        	   
	              $('.courselist').html(datali);
	              //console.log(datali);
	           }
	       });
    }
    
    //评论更多显示
  $(".user_comment_list").on('click','.more_comment',function(){
      console.log('ddd');
      $(".userComment_alertOutside").show();
  });
    
//关闭弹窗
  $(".img_close").click(function(){
    $(".userComment_alertOutside").hide();
    $(".courseBuy_tip").hide();
  })
  
	//textarea 输入框事件:数字变化
	$(".user_comment textarea").bind("input propertychange",function(){
	var textareaValue_length = $(this).val().length;
	$(".comment_strnum").html( textareaValue_length );
	})
	  // tab栏切换及点击效果 10.13 v7.2
	  $('.tablist > i').on('click', function () {
	    $(this).addClass('active').siblings().removeClass('active');
	    var length = $(this).attr('class').length;
	    var tabCLassname =  $(this).attr('class').substr(0, length - 11) + '-box';
	    $('.' + tabCLassname).css('display', 'flex').siblings().hide();
	  });
	  $('.intro-box .navitem, .chapter-box .chapter-item').on('click', function () {
	    $(this).addClass('active').siblings().removeClass('active');
	  });
	  $('.intro-box .navitem, .chapter-box .chapter-item').on('click', function () {
		    $(this).addClass('active').siblings().removeClass('active');
		  });
		  $('.weixin-box, .weapp-box').on('mouseover', function () {
		    $(this).children('div').show();
		  }).on('mouseout', function () {
		    $(this).children('div').hide();
		  })

})
	 

	
</script>
</body>
</html>