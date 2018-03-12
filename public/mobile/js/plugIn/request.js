define(function() {
	return function(mui) {
		return function send(url, success, type, data, err) {
			mui.ajax({
				"url": url,
				"type": type == undefined ? "get" : type,
				"dataType": "text/json",
				"data": data == undefined ? {} : data,
				"success": success,
				"error": err == undefined ? function(data) {
					console.log(data)
				} : err
			});
		}
	}
});