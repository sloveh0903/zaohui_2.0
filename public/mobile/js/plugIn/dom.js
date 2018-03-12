define(function(){
	return {
		queryDom:function(selector){
			return document.querySelector(selector);
		},
		queryDomArr:function(selector){
			return document.querySelectorAll(selector);
		}
	};
});