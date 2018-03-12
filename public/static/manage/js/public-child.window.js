
var obj = {
   myParent:window.parent.document,
}
$(".modal-header>.close",obj.myParent).click(function(){
   $(".modal",obj.myParent).hide();
})
//点击用户头像显示隐藏菜单
$(".user-self").click(function(){
   var $usersetul = $(this).next(".user-set-ul"),
       display = $usersetul.css("display");
   if("none"==display){
      $usersetul.slideDown();
   }else{
      $usersetul.slideUp();
   }    
})

