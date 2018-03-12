
$('.goto').on('click', function () {

    var myParent = window.parent.document;  //父级窗口
    var src = $(this).attr("data-src");
    var iframe = myParent.getElementById("iframe");
    console.log(src);
    $.get('/admin/index/checkRule', {action:src}, function(str){
        if(str.code == "0"){
            layer.msg(str.msg);
        }else{

            iframe.src = src;
        }
    });

});

$('.leftmenu').on('click', function () {
    $('.leftmenu').removeClass('active');
    $(this).addClass('active');
    var src = $(this).attr("data-src");
    var iframe  = document.getElementById("iframe");
    iframe.src = src;
});
$(".tag_group>img.img_myself").on("click",function(){
    var display = $(".exits_ul").css("display");
    if("none" ==display){
        $(".exits_ul").show();
    }else{
        $(".exits_ul").hide();
    }
});