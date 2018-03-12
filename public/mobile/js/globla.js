var host = 'http://' + window.location.host + '/api/';
var nodata = '<div id="null"><img src="/public/mobile/img/icon/null.png"><p class="font-color-8">暂无数据</p> </div>';

function GetQueryString(name)
{
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
}
//黑色弹框
function toastBox(){
//	alert('kk')
	$('.toast-box').removeClass('limit')
	$('.toast-main').fadeIn(1500)
	$('.toast-box').removeClass('limit').delay(1000).fadeOut (1500);
}
