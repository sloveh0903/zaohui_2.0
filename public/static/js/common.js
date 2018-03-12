
	var apiurl = getBaseUrl()+'/api/'; //apiurl
	var host = getBaseUrl(); //域名地址
	//签名生成
	function sign(myObject){
		var appkey = 'RVyvcIkmDdjXXjAaZ4zHhqaXgTkeVCzw'; //加密串
		var arr=[];
		for(var a in myObject){
			arr[a] = myObject[a];
		}
		var stringA = '';
		for(var tmp in arr){
			stringA+= tmp+'='+arr[tmp]+'&';
		}
		stringSignTemp = stringA+'key='+appkey;
		var md5str = md5(stringSignTemp);
		var sign = md5str.toUpperCase();
		return sign;
	}

	//返回当前url地址
	function getBaseUrl(){
	//protocol 属性是一个可读可写的字符串，可设置或返回当前 URL 的协议,所有主要浏览器都支持 protocol 属性
		var ishttps = 'https:' == document.location.protocol ? true: false;
		var url = window.location.host;
		  if(ishttps){
			url = 'https://' + url;
		}else{
			url = 'http://' + url;
		}
		return url;
	}
	
	/**  
	 * ajax post提交  
	 * @param url  
	 * @param param  
	 * @param datat 为html,json,text  
	 * @param callback回调函数  
	 * @return  
	 */
	function sendAjax(url, param, success, type) {
		if(!type){
			type='get';
		}
		var token = sign(param);
		param.token = token;
		$.ajax({
			type: type,
			url: apiurl+url,
			data: param,
			dataType: 'json',
			success: function(d){
//				if(d.code == 1){
//					location.reload(); 					
////				}else if(d.code == -1){
////					//alert(d.message); 
////					return false;
////				}
				success(d);
			},
		});
	}
	//获取url参数
	function getQueryString(name) { 
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i"); 
		var r = window.location.search.substr(1).match(reg); 
		if (r != null) return unescape(r[2]); 
		return null; 
	} 

