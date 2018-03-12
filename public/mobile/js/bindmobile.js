var bindstr = '<div class="binddialog"><div class="bind-box"><h4 class="bind-caption">手机号码绑定</h4><p>需要绑定手机号码才能进入</p><div class="validate-box"><input type="text" class="phonenum" placeholder="请输入11位有效手机号码"><input type="text" class="validatenum" placeholder="请输入验证码"><input type="button" value="发送验证码" class="getvalidate"><p class="error-alert"></p></div><i class="confirm">确定</i></div></div>';

if(isbind == 'no'){
    $('body').append(bindstr);
}

$.ajax({
    url: host + 'user/info',
    data: {
        uid: uid
    },
    type: 'GET', //GET
    async: true,    //或false,是否异步
    timeout: 5000,    //超时时间
    dataType: 'json',    //返回的数据格式：json/xml/html/script/jsonp/text
    success: function (data, textStatus, jqXHR) {
        if(data.data.audit == 0){
            window.location.href = host.replace('api','') + 'wechat/index/disable';
        }
    }
});
$(".concern_top").on('click','.top_close',function(){
    $(".concern_top").hide()
})
// 绑定手机号弹窗 及倒计时
var timer = null;

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
        document.getElementById('toast-main').innerHTML='请输入有效的手机号码';
		toastBox();
        $('.phonenum').val('');
        return false;
    }
    $.ajax({
        url: host + 'user/sendcode',
        data: {
            uid: uid,
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
                //mui.toast("验证码已免费发送到" + phoneNumber.replace(str, "****") + "");
                document.getElementById('toast-main').innerHTML="验证码已免费发送到" + phoneNumber.replace(str, "****") + "";
        		toastBox();
            } else {
                //mui.toast(data.message);
            	document.getElementById('toast-main').innerHTML=data.message;
        		toastBox();
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
            uid: uid,
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
                location.reload();
            } else {
                //mui.toast(data.message);
            	document.getElementById('toast-main').innerHTML=data.message;
        		toastBox();
                return false;
            }
        }
    });

});
setTimeout(function(){
	 $.get(host+'index/registerseession',{is_register:0},function(res){
		 if(res.code==1&&res.data.is_register==1){
			 alert_str  = res.data.msg;
			 if(res.data.integral){
				 $.get(host+'index/unsetregisterseession',{integral:res.data.integral,is_register:res.data.is_register},function(sub){
					 if(sub.code==1){
						 //mui.toast(alert_str);
						//积分文本提示框
						mui.toast('<div class="toast-content"><p class="fs-36">+'+res.data.integral+'</p><p class="fs-14">'+'新用户注册'+'</p></div>');
					 }
				 });
			 }
			 
		 }
	 });
},1000);