$(function () {
  //评论提交按钮事件
  var commentSubmitObj ={};  //提交数据  
  $(".a_manyidu_submit").click(function(){
    var textareaValue  = $(".user_comment textarea").val();
    commentSubmitObj.textareaValue =  textareaValue;
    if("" != textareaValue && commentSubmitObj.starNum != undefined ){
        console.log("提交");
        console.log( commentSubmitObj );
        $(".comment_finish_alert").show();
        $(".course_manyidu").hide(3000);
    }
  })
  //播放视频时提示 先登录再购买
  $(".video_word_list_ul li div span a,.video_word_list_ul li div img").click(function(){
    $(".weixin_QRcord_alert_outside").show();
    $(".courseBuy_tip").show();
    // loginAfter();
  })
  initHeight();
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
  // v7.2 点击全部课程出现筛选弹窗,点击提问按钮出现提示弹窗
  $('.main-top .filter').on('click', function () {
    $('body').css('overflow', 'hidden');
    $('.dialog').show();
    $('.filter-box').show().siblings().hide();
  });
  $('.tiwen-btn').on('click', function (e) {
    if(!isBuy) {
      $('body').css('overflow', 'hidden');
      $('.dialog').css('display', 'flex');
      e.preventDefault();
      $('.alert-box').show().siblings().hide();
    }
  })
  $('.cross').on('click', function () {
    $('.dialog').hide();
    $('body').css('overflow', 'auto');
  })
});
  var personalObj = {};
  //个人账号
  function validatePersonalAccount(){
    var phoneNum,showErrorWord,txtUserName;
    phoneNum = document.getElementById("phoneNum");    //文本框
    showErrorWord = document.getElementById("showErrorWord");  //span提示
    txtUserName = phoneNum.value;
    if(txtUserName!=""){
        if(/^[0-9a-zA-Z_]{1,}$/.test(txtUserName)==false){
            showErrorWord.innerHTML = "没有此账号";
            return false;
        }
        var mobileValid = /^(0|86|17951)?(13[0-9]|15[0-9]|17[0678]|18[0-9]|14[57])[0-9]{8}$/;
        if(!mobileValid.test( txtUserName)) {
            showErrorWord.innerHTML = "请输入有效的手机号码";
            return false;
        }
        personalObj.phoneNum = txtUserName;
        showErrorWord.innerHTML = "";
    }else{
        showErrorWord.innerHTML = "请输入手机号码";
        return false;
    }
    return true;
  }
  //个人密码
  function validatePersonalPassword(){
    var phonePassword,showErrorWord,txtPassword;
    phonePassword = document.getElementById("phonePassword");    //文本框
    showErrorWord = document.getElementById("showErrorWord");  //span提示
    txtPassword = phonePassword.value;
    if(txtPassword!=""){
        if(txtPassword.length<6){
            showErrorWord.innerHTML = "密码长度6-16位";
            return false;
        }
        var reg = /^[\w]{6,16}$/;
        if(reg.test(txtPassword)==false){
            showErrorWord.innerHTML = "请输入正确的密码";
            return false;
        }
        personalObj.passwordNum = txtPassword;
        showErrorWord.innerHTML = "";
    }else{
        showErrorWord.innerHTML = "请输入密码";
        return false;
    }
    return true;
  }
  //提交拦截
  function checkNull(){
    if(!validatePersonalAccount()){
      return false;
    }
    if(!validatePersonalPassword()){
      return false;
    }
    //比较后台的用户名和密码 var personalObj = {} ajax调用
    console.log( personalObj );
    if ( personalObj != null ) {
      window.location.href= "index.html";
    }
    return true;
  }
  $(".a_accountLogin").click(function(){
    checkNull();
  });
  //初始化高度
  function initHeight(){
    var leftHeight  =  $(".bibei_knowledge").outerHeight();
    var rightHeight = $(".video_word_list").outerHeight();
    if( parseFloat(leftHeight ) >= parseFloat(rightHeight) ){
      $(".bibei_knowledge").outerHeight( leftHeight );
    }else{
      $(".bibei_knowledge").outerHeight( rightHeight );
    } 
  }