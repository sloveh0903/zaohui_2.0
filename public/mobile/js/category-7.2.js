$(function () {
  // 平衡左右区域高度
  $('.content-wrapper').innerHeight($(window).innerHeight() - 90);
  $(".content-left").css('height', $('.content-wrapper').innerHeight());
  $(".content-right").css('height', $('.content-wrapper').innerHeight());
  setTimeout(function () {
	  var myScroll_l = new IScroll('#wrapper-l', {});
	  var myScroll_r = new IScroll('#wrapper-r', {});
  }, 1000)
  // 左区域点击效果
  $('.content-left-item').on('click', function () {
    $(this).parent().find('a').removeClass('focused');
    $(this).find('a').addClass('focused');
  });
  // 未绑定手机弹出遮罩
  var isBind = false;
//  if (!isBind) {
//    $('.dialog').css('display', 'flex');
//    $('.wrap').addClass('blur');
//  }
//  $('.dialog .cancel').on('click', function () {
//    $(this).parents('.dialog').hide().siblings('.wrap').removeClass('blur');
//  })
})