<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:44:"./template/mulit/wechat/ask/mulitanswer.html";i:1518064648;s:41:"./template/mulit/wechat/common/share.html";i:1518064648;}*/ ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
		<title>提问</title>
		<link rel="stylesheet" href="/public/mobile/css/mui.min.css" />
		<link rel="stylesheet" href="/public/mobile/css/global.css" />
		<link rel="stylesheet" href="/public/mobile/css/question.css" />
		
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
		<div class="mui-row text-area">
			<textarea placeholder="问题描述"></textarea>
	    </div>
	    <div class="mui-row operation">
	      <div class="z_photo" id="zfile">
	        <div class="z_file">
	          <input type="file" name="file" id="file" value="" accept="image/*" multiple onchange="imgChange('z_photo','z_file');" />
	        </div>
	      </div>
	      <div class="other">
	        <p>最多上传3张图片</p>
	        <div class="input-box">
				<label for="checkbox-id" class="anonymous">匿名</label>
				<input name="checkbox" type="checkbox" id="checkbox-id" >
	        </div>
	      </div>
	    </div>
	
		<div class="q-btn">
			<a>确认</a>
		</div>
		<!--遮罩层-->
		<div class="buy-dialog" id="BuyDialog" style="z-index: 999">
	    	<div class="alert-box">
		    	<h1 class="fs-20 fc-8">提示</h1>
				<p class="fs-16 fc-6">确定要删除这张图片吗？</p>
				<div class="buy-dialog-btn">
					<a class="Cancel">取消</a>
					<a id="onBridgeReady" class="onBridgeReady">确定</a>
				</div>
	    	</div>			
		</div>
		
		<div class="desc-pop">
			<p>
				
			</p>
		</div>
		<div class="mask-loading">
			<img src="/public/mobile/img/icon/zhuan.png" class="rotate">
		</div>
		<input type="hidden" id="imgArr" />
		<script src="/public/mobile/js/jquery-3.2.1.min.js"></script>
		<script src="/public/mobile/js/mui.min.js"></script>
		<script src="/public/mobile/js/globla.js"></script>
		<script type="text/javascript">
			//console.log($('#checkbox-id').prop('checked'))
			//复选框
			mui('.input-box').on('tap','label',function(e){
				var state =$(this).siblings().prop('checked')
				console.log(state)
				if(state==false){
					$(this).addClass('checked')
				}else{
					$(this).removeClass('checked')
				}
			})
			var cid = GetQueryString('cid');
            var uid = '<?php echo $userinfo['uid']; ?>';
            var isbind = '<?php echo $userinfo['is_bind']; ?>';
			var token = '<?php echo $userinfo['token']; ?>';
			
			var upload_arr = [];
			var upload_thumb = [];
			var imgnum = 0;

            (function(doc, win) {
                var docEl = doc.documentElement,
                    resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
                    recalc = function() {
                        var clientWidth = docEl.clientWidth;
                        if(!clientWidth) return;
                        if(clientWidth >= 640) {
                            docEl.style.fontSize = '100px';
                        } else {
                            docEl.style.fontSize = 100 * (clientWidth / 640) + 'px';
                        }
                    };
                if(!doc.addEventListener) return;
                win.addEventListener(resizeEvt, recalc, false);
                doc.addEventListener('DOMContentLoaded', recalc, false);
            })(document, window);


            function imgChange(obj1, obj2) {
                $('.mask-loading').css('display','flex');
				if(imgnum >= 2){
					$('.z_file').css('display','none');
				}
				//获取点击的文本框
				var file = document.getElementById("file");
				var zfile = document.getElementById("zfile");
				//存放图片的父级元素
				var imgContainer = document.getElementsByClassName(obj1)[0];
				//获取的图片文件
				var fileList = file.files;
				
				//文本框的父级元素
				var input = document.getElementsByClassName(obj2)[0];
				var imgArr = [];

				//遍历获取到得图片文件
				for(var i = 0; i < fileList.length; i++) {
					var imgUrl = window.URL.createObjectURL(file.files[i]);
					imgArr.push(imgUrl);
					var img = document.createElement("img");
					img.setAttribute("src", imgArr[i]);
					var imgAdd = document.createElement("div");
					
					
					var reader = new FileReader();  
					reader.readAsDataURL(file.files[i]);  
					reader.onload = function(e){ // reader onload start  
						// ajax 上传图片  
						//console.log(e.target.result)  
						 $.post(host+'ask/uploadfile', { img: e.target.result},function(ret){  
						//console.log(ret);
							if(ret.img == ""){
								mui.alert('图片上传失败');return;
							}else{
								//console.log(ret.img);

								upload_arr.push(ret.img);
								upload_thumb.push( ret.thumb );
                                var cross = document.createElement('i');
								imgAdd.setAttribute("class", "z_addImg");
								imgAdd.setAttribute("url", imgArr[i]);
								imgAdd.appendChild(img);
                                imgAdd.appendChild(cross);
								zfile.insertBefore(imgAdd, zfile.childNodes[0]);
								imgnum = imgnum + 1;
								$('.mask-loading').css('display','none');
                                countDetect();
                                imgRemove();
							}
						},'json'); 
						
						
					}

				};
			};

            function imgRemove() {
                var imgList = document.getElementsByClassName("z_addImg");
                var mask = document.getElementsByClassName("buy-dialog")[0];
                var cancel = document.getElementsByClassName("Cancel")[0];
                var sure = document.getElementsByClassName("onBridgeReady")[0];
                for(var j = 0; j < imgList.length; j++) {
                    imgList[j].index = j;
                    imgList[j].onclick = function() {
                        var t = this;
                        mask.style.display = "flex";
                        cancel.onclick = function() {
                            mask.style.display = "none";
                        };
                        sure.onclick = function() {
                            mask.style.display = "none";
                            t.style.display = "none";
                            countDetect();
                        };
                    }
                };
            };

            function countDetect () {
                var imgList = document.getElementsByClassName("z_addImg");
                var divArr = [...new Set(imgList)];
                var filterArr = divArr.filter((item, index) => {
                        return item.style.display != 'none';
            });
                var count = filterArr.length;
                var zfile = document.getElementsByClassName('z_file')[0];
                if(count >= 3) {
                    zfile.style.display = 'none';
                } else {
                    zfile.style.display = 'block';
                }
            }

			mui("body").on('tap','.q-btn', function (event) {
				var content = $('textarea').val();
				if(content == ""){
					//mui.alert('请填写问题描述');return;
				}
				if($('#checkbox-id').is(':checked')) { 
					var anonymous = 1;
					$('label.anonymous').addClass('checked')
				}else{
					var anonymous = 0;
					$('label.anonymous').removeClass('checked')
				}
				
				//console.log(imgpath);
				var imgpath = '';
				var imgthumb = '';
				for(var i=0;i<upload_arr.length;i++){
					if(upload_arr[i] != ''){
						imgpath += upload_arr[i]+',';
					}
					
				}
				for (var i = 0; i < upload_thumb.length; i++) {
			          if (upload_thumb[i] != '') {
			              imgthumb += upload_thumb[i] + ',';
			          }
			      }
				$('.q-btn').hide();
				$.ajax({
					url : host+"ask/submit",
					type : 'post',
					data : {
						cid:cid,
						uid:uid,
						content:content,
						title:content,
						anonymous:anonymous,
						imgpath:imgpath,
						imgthumb:imgthumb,
						token:token
					},
					success : function(data){
						$('.q-btn').show();
						if(data.code == -1){
							mui.alert(data.message);
							//location.reload();
							var url = "<?php echo url('wechat/ask/mulitindex'); ?>";
							window.location.href=url;
						}else if(data.code != 1){
							mui.alert(data.message);
						}else if(data.code == 1){
							if(data.data.integral_code==1){
								var alert_str = data.data.msg;
							}else{
								var alert_str = '提交成功';
							}
							//mui.toast(alert_str);
							//mui.alert('提交成功');
							$('textarea').val("");
							var url = "<?php echo url('wechat/ask/mulitindex'); ?>?msg="+alert_str;
							window.location.href=url;
						}
				  　}  
				})
				
			});
//		})	
		</script>
		<script src="/public/mobile/js/bindmobile.js"></script>
	</body>

</html>