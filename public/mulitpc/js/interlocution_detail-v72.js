$(function () {
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
  
  // v7.2回复按钮点击展开 点赞按钮点击变蓝
  $('.content-box .reply').on('click', function () {
    $(this).find('.reply-icon').toggleClass('active');
    if (!($(this).find('p').data('content'))) {
      console.log($(this).find('p').data('content'));
      $(this).find('p').data('content', $(this).find('p').html());
      console.log($(this).find('p').data('content'));
    }
    $(this).parent().siblings('.reply-box').toggleClass('flex-box');
    if ($(this).parent().siblings('.reply-box').hasClass('flex-box')) {
      $(this).find('p').html('收起回复');
    } else {
      $(this).find('p').html($(this).find('p').data('content'));
    }
    $(this).parent().siblings('.answer-item').toggleClass('flex-box');
  });
  $('.content-box .favo-icon').on('click', function () {
    $(this).toggleClass('active');
  })
})
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
  
