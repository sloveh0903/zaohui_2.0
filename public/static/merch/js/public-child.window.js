
var obj = {
   myParent:window.parent.document,
   indexBar:0
}
$(".modal-header>.close",obj.myParent).click(function(){
   $(".modal-bms",obj.myParent).hide();
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
//删除课程,章节确认提示隐藏
$(".course-delete-modal .btn-primary",obj.myParent).on("click",function(){
      $(".course-delete-modal",obj.myParent).hide();
   })
//是否显示隐藏操作提示框
function isShowOperateTip(str){
   $(".operate-tip",obj.myParent).html(str).show().delay(1000).hide(0);
}
// 上架下架切换
$("span.switch").on("click",function(){
   var $this =  $(this),
       strUp ="课程已上架",
       strDown = "课程已下架",
       dataToggle = $this.attr("data-toggle");
   if( !dataToggle ){
      $this.attr("data-toggle",0);
      if( $this.hasClass("switch-down") ){
         $this.removeClass("switch-down");
         $this.addClass("switch-up");
         isShowOperateTip(strUp);
      }else{
         $this.toggleClass("switch-up");
         $this.addClass("switch-down");
         isShowOperateTip(strDown);
      } 
   }else{
      if( 0 == parseFloat(dataToggle) ){
         $this.attr("data-toggle",1);
         $this.removeClass("switch-up");
         $this.addClass("switch-down");
         isShowOperateTip(strDown);
      }else if(1==parseFloat(dataToggle) ){
         $this.attr("data-toggle",0);
         $this.removeClass("switch-down");
         $this.addClass("switch-up");
         isShowOperateTip(strUp);
      }
   }
})
//精华切换
$("span.jinghua").on("click",function(){
   var $this =  $(this),
       strNo = "课程已取消轮播",
       strOK = "课程已加入轮播",
       dataToggle = $this.attr("data-toggle");
   if( !dataToggle ){
      $this.attr("data-toggle",0);
      if( $this.hasClass("jinghua-no") ){
         $this.removeClass("jinghua-no");
         $this.addClass("jinghua-ok")
         isShowOperateTip(strOK);
      }else{
         $this.toggleClass("jinghua-ok");
         $this.addClass("jinghua-no");
         isShowOperateTip(strNo);
      } 
   }else{
      if( 0 == parseFloat(dataToggle) ){
         $this.attr("data-toggle",1);
         $this.removeClass("jinghua-ok");
         $this.addClass("jinghua-no");
         isShowOperateTip(strNo);
         
      }else if(1==parseFloat(dataToggle) ){
         $this.attr("data-toggle",0);
         $this.removeClass("jinghua-no");
         $this.addClass("jinghua-ok");
         isShowOperateTip(strOK);
      }
   }
})
//面包屑导航链接
   $(".nav-crumb-ul>li>i").on("click",function(){
      var iframe = $("#iframe",obj.myParent),
          name = $(this).attr("data-name");
      switch(name){
         case "courseManage":
            iframe.attr("src","courseManage.html")
            break; 
         case "addVideo":
            iframe.attr("src","addVideo.html")
            break;
         case "editCourse":
            iframe.attr("src","editCourse.html")
            break;
         case "addCourse":
            iframe.attr("src","addCourse.html")
            break;      
      }    
   })
//编辑课程与视频管理切换
$(".editCourse-nav-ul>li>i").click(function(){
   var iframe = $("#iframe",obj.myParent),
       name = $(this).attr("data-name");
   switch(name){
      case "editCourse":
         iframe.attr("src","editCourse.html")
         break;
      case "videoManage":
         iframe.attr("src","videoManage.html")
         break;
   }    
})
