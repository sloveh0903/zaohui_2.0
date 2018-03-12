<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:39:"./template/mulit/wechat/member/ask.html";i:1518064648;s:41:"./template/mulit/wechat/common/share.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>我的提问</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/member.css" />
		<style type="text/css">
			html,body{background: #fff;}
		</style>
	</head>
    <?php
    $data = $_GET;
    unset($data['code']);
    $data['uname'] = $userinfo['uname'];
    $url_data = '?'.http_build_query($data);
    $link = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['REDIRECT_URL'].$url_data;
    $config = find('Config');
    $logo = $config['logo'];
?>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script type="text/javascript">
            var logo = '<?php echo $logo; ?>';
            if(logo == ''){
                logo = '/public/image/logo@1x.png';
            }
            wx.config({
                debug: false,
                appId: '<?php echo $signPackage["appId"];?>',
                timestamp: '<?php echo $signPackage["timestamp"];?>',
                nonceStr: '<?php echo $signPackage["nonceStr"];?>',
                signature: '<?php echo $signPackage["signature"];?>',
                jsApiList: [
                'onMenuShareTimeline','onMenuShareAppMessage'
                  // 所有要调用的 API 都要加到这个列表中
                ]
             });
             
            wx.ready(function(){
                // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
                //分享给朋友
                wx.onMenuShareAppMessage({
                    title: '<?php echo $config["sitename"]; ?>', // 分享标题
                    desc: '<?php echo $config["introduce"]; ?>', // 分享描述
                    link: '<?php echo $link; ?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    imgUrl: 'http://'+window.location.host+logo, // 分享图标
                    type: '', // 分享类型,music、video或link，不填默认为link
                    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
                    success: function () { 
                        // 用户确认分享后执行的回调函数
                    },
                    cancel: function () { 
                        // 用户取消分享后执行的回调函数
                    }
                });

                //分享到朋友圈
                wx.onMenuShareTimeline({
                    title: "<?php echo $config['sitename']; ?>", // 分享标题
                    link: "<?php echo $link; ?>", // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    imgUrl: 'http://'+window.location.host+logo, // 分享图标
                    success: function () { 
                        // 用户确认分享后执行的回调函数
                    },
                    cancel: function () { 
                        // 用户取消分享后执行的回调函数
                    }
                });
            });

            wx.error(function(res){
                console.log(res)
                // config信息验证失败会执行error函数，如签名过期导致验证失败，具体错误信息可以打开config的debug模式查看，也可以在返回的res参数中查看，对于SPA可以在这里更新签名。
            });


</script>
	<body>
		<div class="mui-row myquestions-list">
            <div id="pullrefresh" class="mui-scroll-wrapper">
                <div class="mui-scroll">
        			<ul id="ask"class="ask-answer-box">
        			</ul>
                </div>
            </div>
		</div>
		<script type="text/javascript" src="/public/mobile/js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/mui.min.js"></script>
		<script type="text/javascript" src="/public/mobile/js/globla.js"></script>
		<script>
            var uid = '<?php echo $userinfo['uid']; ?>';
            var isbind = '<?php echo $userinfo['is_bind']; ?>';
            var page = 1;
            var size = 10;
            getlist();
            function getlist(){
                $.ajax({
                    url:host+'user/ask',
                    data:{
                        uid:uid,
                        page:page,
                        size:size
                    },
                    type:'GET', //GET
                    async:true,    //或false,是否异步
                    timeout:5000,    //超时时间
                    dataType:'json',    //返回的数据格式：json/xml/html/script/jsonp/text
                    success:function(data,textStatus,jqXHR){
                        if(data.code==1){
                            var list = "";
                            var result = data.data;
                            $.each(result.ask,function(j,item){
                                var answer = "";
                                if(item.answer_count > 0){
                                    answer = '<i class="fs-12 fc-4">'+item.answer_count+'条回答</i>'
                                }else{
                                    answer = '<i class="fs-12 fc-4">暂无回答</i>';
                                }
                                list += '<li data-id="'+ item.id +'">' + '<h1 class="fs-16 fc-10">'+item.title+'</h1>' + answer + '</li>';
                            });
                            if(result.ask.length > 0){
                                $("#ask").append(list);
                            }
                            if(page == 1 && result.ask.length == 0){
                                var empty = '<div class="emptypage-wrapper"><div class="empty-box"> <img class="empty-img" src="/public/mobile/img/icon/wenda-empty.png"> <p class="empty-text">还没有提问</p></div></div>';
                                $(".mui-row").html(empty);
                            }

                        }
                        mui('#pullrefresh').pullRefresh().endPullupToRefresh();
                    }
                });
            }
            
            mui('#ask').on('tap','li',function () {
                var id = $(this).attr('data-id');
                url = "<?php echo url('wechat/ask/detail'); ?>";
                window.location.href = url + '?id=' + id;
            });


            mui.init({
                pullRefresh: {
                    container: '#pullrefresh',
                    up : {
                        //height:50,//可选.默认50.触发上拉加载拖动距离
                        //auto:true,//可选,默认false.自动上拉加载一次
                        contentrefresh : "",//可选，正在加载状态时，上拉加载控件上显示的标题内容
                        contentnomore:'',//可选，请求完毕若没有更多数据时显示的提醒内容；
                        callback :pullupRefresh //必选，刷新函数，根据具体业务来编写，比如通过ajax从服务器获取新数据；
                    }

                }
            });

            function pullupRefresh() {
                console.log('下拉');
                setTimeout(function() {
                    page += 1,
                   getlist();
                }, 100);
            }

            mui('.ask-content').on('tap', '.go_detail', function (event) {
                var url = "<?php echo url('wechat/ask/detail'); ?>";
                var id = this.getAttribute("data-id");
                window.location.href=url+'?id='+id;
            });
		</script>
		<script src="/public/mobile/js/bindmobile.js"></script>
	</body>
</html>
