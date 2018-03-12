$(function () {
	console.log(111);
	$('body').on('click',function(){
		$('.slide-box').css('display','none');
	});
	localStorage.clear();
	var storage = window.localStorage;  
	var last_div = '';
	var current_div = '';
	var local_id_arr = [];
	var my_local_name = 'update_';
	var store_cousrselist = [];
	var dragTarget = null,
	  passingTarget = null,
	  isTipCreated = false,
	  tipbox = null,
	  isSearchCreated = false,
	  isTextCreated = false,
	  isImgUploaded = true,
	  isFromOutside = true,
	  isFromSetting = false,
	  isFromDisplay = false,
	  isDisplayEmpty = false,
	  isBundleCreated = false,
	  currentY = 0,
	  banner_tempCount = 0,
	  search_str = 
	  `
	  <div class="search-box moveable editable editing" draggable="true" data-type='2'>
	    <a class="search" href="">
	      <span class="search-icon">
	      </span>
          <span class="search-text">搜索...</span>
	    </a>
	    <div class="mask"></div>
	    <div class="editing-box">
	      <div class="edit-btn draggable-btn"></div>
	      <div class="edit-btn delete-btn"></div>
	      <div class="edit-btn moveup-btn"></div>
          <div class="edit-btn movedown-btn"></div>
	    </div>
	  </div>
	  `,
	  border_str = 
	  `
	  <div class="border-box moveable editable editing" draggable="true" data-type='5'>
	    <div class="border-light" id=''></div>
	    <div class="mask"></div>
	    <div class="editing-box">
	      <div class="edit-btn draggable-btn"></div>
	      <div class="edit-btn delete-btn"></div>
	      <div class="edit-btn moveup-btn"></div>
          <div class="edit-btn movedown-btn"></div>
	    </div>
	  </div>
	  `,
	  listcontent_str =
	  `
	  <div class="listcontent-box withtitle moveable editable editing" draggable="true" data-type='3'>
	    <div class="course-box">
	      <p>推荐课程</p>
	      <a href="#">更多</a>
	    </div>
	    <ul class="mui-table-view course-list">
	      
	      
	    </ul>
	    <div class="mask"></div>
	    <div class="editing-box">
	      <div class="edit-btn draggable-btn"></div>
	      <div class="edit-btn delete-btn"></div>
	      <div class="edit-btn moveup-btn"></div>
          <div class="edit-btn movedown-btn"></div>
	    </div>
	  </div>
	  `,
	  text_str = 
	  `
	    <div class="text-box moveable editable editing" data-type='4'>
	      <div class="mask"></div>
	      <div class="editing-box">
	        <div class="edit-btn draggable-btn"></div>
	        <div class="edit-btn delete-btn"></div>
	        <div class="edit-btn moveup-btn"></div>
            <div class="edit-btn movedown-btn"></div>
	      </div>
	    </div>
	  `;
	bundle_str = 
		  `
		  <div class="bundle-box moveable editable withtitle editing" draggable="true" data-type='6'>
		    <div class="bundle-title-box">
		      <p>热门套餐</p>
		      <a href="./bundle-more.html">更多</a>
		    </div>
		    <ul class="bundle-list">
		      
		    </ul>
		    <div class="mask"></div>
		    <div class="editing-box">
		      <div class="edit-btn draggable-btn"></div>
		      <div class="edit-btn delete-btn"></div>
		      <div class="edit-btn moveup-btn"></div>
              <div class="edit-btn movedown-btn"></div>
		    </div>
		  </div>
		  `;
	var host = 'http://' + window.location.host + '/api/';
	$.get(host+"customtemplate/getall",{id:1},function(res){
		if(res.code == 1){
			var data = res.data;
			get_display_html(data);
		}
	});
	//获取本地存储json数据
    function get_storage_local(local_key) {
      var local_name = my_local_name+local_key;
  	  var data = storage.getItem(local_name);
  	  if(data) {
 			  return JSON.parse(data);
  	  } else {
  		  return {};
  	  }
    }
  //清除本地存储json数据
    function clear_storage_local(local_key) {
      var local_name = my_local_name+local_key;
  	  storage.removeItem(local_name);
    }
    //存储  
    function storage_local(local_key,local_json){
    	var local_name = my_local_name+local_key;
    	localString = JSON.stringify(local_json);
  	    storage.setItem(local_name, localString);
    }
  //更新数据
    function update_local_by_item_id(local_key,itemid,new_item) { 
      var local_arr = get_storage_local(local_key);
      local_arr[itemid]  = new_item;
      var local_name = my_local_name+local_key;
      local_String = JSON.stringify(local_arr);
      storage.setItem(local_name,local_String);
    }
    
	//显示区域
	function get_display_html(data){
		var display_html = '<div class="header"><i class="cross-icon"></i><p>知识店铺</p><i class="ellipsis-icon"></i><div class="mask"></div></div>';
		for(var i = 0;i<data.length;i++){
			var local_key = data[i].id;
			var content = data[i].content;
			var local_json = data[i];//存储内容
			local_id_arr.push(local_key);//块id 数组
			if(data[i].type==1){//banner图
				display_html = display_html+'<div class="banner-box editable" data-orderby="'+data[i].orderby+'" data-id="'+data[i].id+'" data-type="'+data[i].type+'">'+
											'<div class="banner-slider">'+
											'<div class="banner-container"><img src="'+content[0].img+'" alt="banner2.png" id="display_banner"></div>'+
											'<div class="banner-indicator"><div class="indicator"></div></div>'+
											'</div>'+
											'<div class="mask"></div>'+
											'<div class="editing-box"><div class="edit-btn draggable-btn"></div>'+
											'<div class="edit-btn delete-btn" data-id="'+data[i].id+'"></div>'+
											'<div class="edit-btn moveup-btn" data-id="'+data[i].id+'"></div>'+
                                            '<div class="edit-btn movedown-btn" data-id="'+data[i].id+'"></div>'+
											'</div>'+
											'</div>';
				
			}
			else if(data[i].type==2){//搜索 
				isSearchCreated = true;
				display_html = display_html+'<div class="search-box moveable editable" draggable="true" data-orderby="'+data[i].orderby+'" data-id="'+data[i].id+'" data-type="'+data[i].type+'">'+
				'<a class="search" href=""><span class="search-icon"> </span><span class="search-text">搜索...</span> </a>'+
				'<div class="mask"></div><div class="editing-box">'+
				'<div class="edit-btn draggable-btn"></div>'+
				'<div class="edit-btn delete-btn" data-id="'+data[i].id+'"></div>'+
				'<div class="edit-btn moveup-btn" data-id="'+data[i].id+'"></div>'+
                '<div class="edit-btn movedown-btn" data-id="'+data[i].id+'"></div>'+
				'</div>'+
				'</div>';
			}
			else if(data[i].type==3){//课程 
				var title = data[i].title;
				var show_more = data[i].show_more;
				var sort=data[i].sort;
				var course_html='';
				var divclass = '';
				if(title){
					divclass = 'withtitle';
					course_html = course_html+'<div class="course-box"><p>'+title+'</p>';
					if(show_more){
						course_html = course_html+'<a href="#">更多</a>';
					}else{
						course_html = course_html+'<div class="course-box-more" style="display:none;"><a href="#">更多</a></div>';
					}
					course_html = course_html+'</div>';
				}else{
					course_html = course_html+'<div class="course-box" style="display:none;"><p></p><a href="#" style="display:none;">更多</a></div>';
				}
				display_html = display_html+'<div class="listcontent-box '+divclass+' moveable editable" draggable="true" data-orderby="'+data[i].orderby+'" data-id="'+data[i].id+'" data-type="'+data[i].type+'">';
				
				
				var course_list = content.course_list;
				var list_html = '<ul class="mui-table-view course-list">';
				if(course_list.length>0){
					for(k=0;k<course_list.length;k++){
						list_html = list_html+'<li><div class="teacher-box"><img src="'+course_list[k].face+'" /></div>'+
						'<div class="content"><p>'+course_list[k].title+'</p>'+
						'<span>'+course_list[k].desc+'</span><i>'+course_list[k].study_count+'学员</i>'+
						'</div>'+
						'<span class="price">'+course_list[k].price+'</span>'+
						'</li>';
					}
				}
				list_html = list_html +'</ul>';
				display_html = display_html+course_html+list_html+'<div class="mask"></div>'+
				'<div class="editing-box"><div class="edit-btn draggable-btn"></div>'+
				'<div class="edit-btn delete-btn" data-id="'+data[i].id+'"></div>'+
				'<div class="edit-btn moveup-btn" data-id="'+data[i].id+'"></div>'+
                '<div class="edit-btn movedown-btn" data-id="'+data[i].id+'"></div>'+
				' </div>'+
				'</div>';
					
			}
			else if(data[i].type==4){//富文本
				isTextCreated = true;
				display_html = display_html+'<div class="text-box moveable editable" draggable="true" data-orderby="'+data[i].orderby+'" data-id="'+data[i].id+'" data-type="'+data[i].type+'">'+data[i].content+
				'<div class="editing-box"><div class="edit-btn draggable-btn"></div>'+
				'<div class="edit-btn delete-btn" data-id="'+data[i].id+'"></div>'+
				'<div class="edit-btn moveup-btn" data-id="'+data[i].id+'"></div>'+
                '<div class="edit-btn movedown-btn" data-id="'+data[i].id+'"></div>'+
				'</div>'+'<div class="mask"></div>'+
				'</div>';
				
			}else if(data[i].type==5){//分割线
				var tempdata = content;
				if(tempdata==1){
					var styleclass = 'border-light';
				}else{
					var styleclass = 'border-heavy';
				}
				display_html = display_html+'<div class="border-box moveable editable" draggable="true" data-orderby="'+data[i].orderby+'" data-id="'+data[i].id+'" data-type="'+data[i].type+'">'+
											'<div class="'+styleclass+'" id="change_border'+data[i].id+'"></div>'+
											'<div class="mask"></div>'+
											'<div class="editing-box"><div class="edit-btn draggable-btn"></div>'+
											'<div class="edit-btn delete-btn" data-id="'+data[i].id+'"></div>'+
											'<div class="edit-btn moveup-btn" data-id="'+data[i].id+'"></div>'+
                                            '<div class="edit-btn movedown-btn" data-id="'+data[i].id+'"></div>'+
											'</div>'+'</div>'; 
			}
			else if(data[i].type==6){//套餐
				var package_list=data[i].package_list;
				isBundleCreated = true;
				var lihtml = '';var ahtml = '';
					if(package_list !=undefined){
						for(var z =0;z<package_list.length;z++){
							  var banner = package_list[z].banner;
							  var banner_list = package_list[z].banner_color;
							  var banner_html = '';
							  banner_html = banner_html+'<div class="img1"><img src="'+banner+'" alt="banner"></div>';
							  for(var zz=0;zz<banner_list.length;zz++){
								  banner_html = banner_html+'<div class="img2" style="background:'+banner_list[zz]+'"></div>';
							  }
							  lihtml  = lihtml+ ` <li>
						        <div class="bundle-showbox">
						          ${banner_html}
						        </div>
						        <div class="content">
						          <h1>${ package_list[z].title}</h1>
						          <i class="price">¥${ package_list[z].price}</i>
						        </div>
						      </li>`;
						  }
						  
						
					}
				  if(data[i].show_more){
					  var ahtml = '<a href="">更多</a>';
				  }else{
					  var ahtml = '<a href="" style="display:none;">更多</a>';
				  }
					var title = data[i].title;
					var divclass = '';
					var stylenone = '';
					if(title){
						divclass = 'withtitle';
					}else{
						stylenone = 'style="display:none;"';
					}
					display_html = display_html+  `
					  <div class="bundle-box moveable editable ${divclass}" draggable="true"  data-orderby="${data[i].orderby}" data-id="${data[i].id}" data-type="${data[i].type}">
				    <div class="bundle-title-box" ${stylenone}>
				      <p>${title}</p>
				      ${ahtml}
				    </div>
				    <ul class="bundle-list">
				      ${lihtml}
				    </ul>
				    <div class="mask"></div>
				    <div class="editing-box">
				      <div class="edit-btn draggable-btn"></div>
				      <div class="edit-btn delete-btn" data-id="${data[i].id}"></div>
				      <div class="edit-btn moveup-btn" data-id="${data[i].id}"></div>
                      <div class="edit-btn movedown-btn" data-id="${data[i].id}"></div>
				    </div>
				  </div>
				  `; 
				}
				  
			storage_local(local_key,local_json);
		}
		
		display_html = display_html+'<div class="grazy_worker"><i>格子匠 GRAZY.CN 技术支持</i>'+
		'</div><div class="menuNav"><ul class="mui-table-view">'+
		'<li><div class="navcontent NavActive"><a href="index.html">'+'<i class="bottom-tab-icon index-icon"></i>'+
		'<span>主页</span></a></div></li>'+
		'<li><div class="navcontent"><a href="interlocution.html">'+'<i class="bottom-tab-icon course-icon"></i>'+
		'<span>课程</span></a></div></li>'+'<li><div class="navcontent">'+
		'<a href="read.html">'+'<i class="bottom-tab-icon quiz-icon"></i>'+'<span>题库</span>'+
		'</a></div></li>'+
		'<li>'+'<div class="navcontent">'+'<a href="member.html">'+'<i class="bottom-tab-icon discuss-icon"></i>'+
		'<span>问答</span></a></div></li>'+
		'<li><div class="navcontent">'+'<a href="member.html"><i class="bottom-tab-icon info-icon"></i><span>我的</span></a></div>'+
		'</li></ul><div class="mask"></div>';
		$('.display-panel').html(display_html);
		if($('.editable').length==1){
			isDisplayEmpty = true;
		}
	}
	//设置banner  setting区域
	function get_banner_setting_html(res){
		data = res.content;
		setting_html = '<div class="banner-setting active" data-id="1">'+
        '<p class="panel-name">轮播图设置</p>'+
        '<div class="setting-box">';
		for(var i = 0;i<data.length;i++){
			var dataid = 0;var datatype = '';
			if(data[i].temp == undefined){temp='';}else{temp=data[i].temp;}
			if(data[i].id != undefined){dataid=data[i].id;}
			if(data[i].type != undefined){datatype=data[i].type;}
			setting_html = setting_html+
		        '<div class="setting-item moveable"  data-id="'+dataid+'" data-type="'+datatype+'"  draggable="true">'+
		          '<div class="img-box">'+
		            '<input type="file" class="banner-uploader"  >'+
		            '<div class="mask">'+'<p style="display: none;">添加图片</p>'+
		            '<input type="hidden" class="hiddenimg" name="hiddenimg" value="'+temp+'">'+
		            '<div class="img-add-box" style="display: block;">'+  
		            '<img src="'+data[i].img+'">'+
		              '</div>'+
		            '</div>'+
		            '</div>'+
		            '<div class="link-box"><p>链接</p>';
		
		if(data[i].link ==''||data[i].link ==' '){
			setting_html = setting_html+
            '<div class="select-box"><div class="placeholder-box">请选择链接页面或地址</div>'+
            '<div class="slide-box" style="display: none;">'+
            '<div class="slide-item course-link">课程</div>'+
            '<div class="slide-item package-link">套餐</div>'+
            '<div class="slide-item custom-vip">VIP购买页面</div>'+
            '<div class="slide-item custom-allcourse">所有课程页</div>'+
            '<div class="slide-item custom-allpackage">所有套餐页</div>'+
            '<div class="slide-item custom-link">自定义链接</div>'+
            '</div>'+
            '<div class="selected-box" style="display: none;">'+'<p class="selected-link">'+data[i].link+'</p>'+
            '<i class="cross-icon"></i>'+
            '</div>'+
            '<i class="slide-icon"></i>'+
            '</div>'+
            '</div>';
		}else{
			setting_html = setting_html+
            '<div class="select-box already-selected"><div class="placeholder-box" style="display: none;">请选择链接页面或地址</div>'+
            '<div class="slide-box" style="display: none;">'+
            '<div class="slide-item course-link">课程</div>'+
            '<div class="slide-item package-link">套餐</div>'+
            '<div class="slide-item custom-vip">VIP购买页面</div>'+
            '<div class="slide-item custom-allcourse">所有课程页</div>'+
            '<div class="slide-item custom-allpackage">所有套餐页</div>'+
            '<div class="slide-item custom-link">自定义链接</div>'+
            '</div>'+
            '<div class="selected-box" style="display: flex;">'+'<p class="selected-link">'+data[i].link+'</p>'+
            '<i class="cross-icon"></i>'+
            '</div>'+
            '<i class="slide-icon"></i>'+
            '</div>'+
            '</div>';
		}
		setting_html = setting_html+
		            '<div class="edit-btn draggable-btn"></div>'+
		            '<div class="edit-btn delete-btn"></div>'+
		            '</div>';  
		}
		setting_html = setting_html+
		'</div>'+
        '<div class="add-btn">'+
        '<i class="add-icon"></i>'+
        '<p>添加轮播图</p>'+
        '</div>'+
        '<div class="info">'+
        '<p>轮播图总共不超过8张</p><p>建议尺寸 750x420像素</p><p>建议格式 png jpg</p>'+
        '</div>'+
        '</div>'
		$('.setting-panel').html(setting_html);
		  banner_tempCount = $('.banner-setting  .setting-item').length;
	}
	//设置课程  setting区域
	function get_course_setting_html(res){
		store_cousrselist = [];
		data = res.content;
		
		var count=0;
		if(res.content.course_id!=undefined){
			var count = res.content.course_id.length;
		}
		if(data){
			var title = res.title;
			var show_more_html = '';
			if(title){
				if(res.show_more==1){
					var show_more_html = 'checked';
				}
			}
			if(data.course_id !=undefined){
				store_cousrselist = data.course_id;
				var count_html = '<span>已选择'+count+'门课程</span>';
			}else{
				var count_html ='' ;
			}
			var sort = res.sort;
			setting_html ='<div class="listcontent-setting">'+'<div class="course-setting">'+'<h4>课程设置</h4>'+
							'<div class="row"><p class="panel-name">排序方式</p><div class="content sort-method">'+
							'<div class="link-box">'+
							'<div class="select-box">';
			if(sort==1){
				setting_html = setting_html+'<div class="placeholder-box" data-sort="1">最新课程(时间从新到旧)</div>';
			}
			else if(sort==2){
				setting_html = setting_html+'<div class="placeholder-box" data-sort="2">最热课程(销量从高到低)</div>';
			}
			else{
				setting_html = setting_html+'<div class="placeholder-box" data-sort="2">推荐课程(默认排序)</div>';
			}
			setting_html = setting_html+
							'<div class="slide-box">'+
							'<div class="slide-item time-sort" data-sort="1">最新课程(时间从新到旧)</div>'+
							'<div class="slide-item sale-sort" data-sort="2">最热课程(销量从高到低)</div>'+
							'<div class="slide-item sale-sort" data-sort="3">推荐课程(默认排序)</div>'+
							'</div>'+
							'<i class="slide-icon"></i>'+
							'</div>'+
							'</div>'+
							'</div>'+
							'</div>'+
							'<div class="row">'+
							'<p class="panel-name" style="display: flex;">选择课程</p>'+
							'<div class="content choose-course" style="display: flex;align-items: center;">'+count_html+
							'<p>&nbsp;&nbsp;选择</p>'+
							'</div>'+
							'</div>'+
							'</div>'+
							'<div class="caption-setting">'+
							'<h4>标题设置</h4>'+
					         '<div class="row">'+
					         '<p class="panel-name">标题名称</p>'+
					         '<div class="content caption-content">'+
					         '<input type="text" class="caption-input" value="'+title+'">'+
					         '</div></div>'+
					         '<p class="info">为空时不显示标题</p>'+
					         '<div class="row">'+
					         '<p class="panel-name">显示更多</p>'+
					         '<div class="content">'+'<div class="checkbox '+show_more_html+'">'+
					         '</div>'+'</div>'+'</div>'+
					         '</div>';	
			$('.setting-panel').html(setting_html);
		}
		
	}
	//设置套餐  setting区域
	function get_bundle_setting_html(res){
		store_cousrselist = [];
		var title = res.title;
		var show_checked = '';
		if(title&&res.show_more==1){
			var show_checked = 'checked';
		} 
		var count=0;
		var count = res.content.package_id.length;
		if(count!=undefined){
			store_cousrselist = res.content.package_id;
			var count_html = '<span>已选择'+count+'个套餐</span>'
		}else{
			var count_html ='' ;
		}
		setting_html=`
		<div class="bundle-setting">
        <div class="course-setting">
          <h4>套餐设置</h4>
          <div class="row">
            <p class="panel-name" style="display: flex;">选择套餐</p>
            <div class="content choose-course" style="display: flex;align-items: center;">
              ${count_html}<p>选择</p>
            </div>
          </div>
        </div>
        <div class="caption-setting">
          <h4>标题设置</h4>
          <div class="row">
            <p class="panel-name">标题名称</p>
            <div class="content caption-content">
              <input type="text" class="caption-input" value="${title}">
            </div>
          </div>
          <p class="info">为空时不显示标题</p>
          <div class="row">
            <p class="panel-name">显示更多</p>
            <div class="content">
              <div class="checkbox ${show_checked}"></div>
            </div>
          </div>
        </div>
      </div>
		`;
		$('.setting-panel').html(setting_html);
	}
	function get_search_setting_html(data){
		//setting_html = search_str;
		//$('.setting-panel').html(setting_html);
	}
	//设置border  setting区域
	function get_border_setting_html(res){
		var id = res.id;
		var data = res.content;
		if(data==1){
			var styleclass = 'light';
		}else{
			var styleclass = 'heavy';
		}
		setting_html ='<div class="border-setting">'+'<h4>分割线设置</h4>'+'<div class="row">'+
						'<p class="panel-name">粗细</p>'+
						'<div class="content line-weight">'+
						'<div class="link-box">'+
						'<div class="select-box">'+
						'<div class="placeholder-box '+ styleclass+'" id="setting_change_border'+id+'"></div>'+'<div class="slide-box">';
		
		setting_html = setting_html +'<div class="slide-item" data-id="'+id+'" data-line="1">'+
		'<div class="light-weight" ></div>'+
		'</div>'+'<div class="slide-item" data-id="'+id+'" data-line="14" >'+
		'<div class="heavy-weight" ></div>'+
		'</div>';
		setting_html = setting_html +'</div>'+
						'<i class="slide-icon"></i>'+
						'</div></div></div></div></div>';
		$('.setting-panel').html(setting_html);
              
	}
	//富文本
	function get_content_setting_html(res){
		data = res.content;
		setting_html = '<div class="ueditor"><textarea name="content" id="cdcontent"></textarea></div>';
		$('.setting-panel').html(setting_html);
        UE.delEditor('cdcontent');
        UE.getEditor('cdcontent',{initialFrameHeight:400,initialFrameWidth:364,autoHeightEnabled:false,toolbars: [
            ['fullscreen','source', '|', 'simpleupload','paragraph', 'fontfamily', 'fontsize','|', 'undo', 'redo', '|',
                'bold', 'italic', 'underline', 'removeformat', 'formatmatch','|', 'forecolor', 'backcolor', 'selectall', '|',
                'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|',
                'link', 'unlink']],initialContent:data});

        
        var ue = UE.getEditor('cdcontent');

        ue.addListener("contentChange",function(){
            var editor=UE.getEditor('cdcontent').getContent();
            editor += '<div class="mask"></div><div class="editing-box"><div class="edit-btn draggable-btn"></div><div class="edit-btn delete-btn" data-id="'+res.id+'"></div><div class="edit-btn moveup-btn" data-id="'+res.id+'"></div><div class="edit-btn movedown-btn" data-id="'+res.id+'"></div> </div>';
            $('.editing').html(editor);
            update_local_by_item_id(res.id,'content',editor);

        });
	}

  // 自定义编辑模板

  // 左侧模块可拖动至展示栏
  
  $('.module-panel').on('dragstart', '.module-item', function (e) {
    $(this).addClass('selected').siblings().removeClass('selected');
    e = e || window.event,
    target = e.target || e.srcElement;
    dragTarget = target;
    isFromOutside = true;
    isFromDisplay = false;
    isFromSetting = false;
  })
  $('.display-panel').on('dragstart', '.moveable', function (e) {
    e = e || window.event,
    target = e.target || e.srcElement;
      dragTarget = target;
      isFromOutside = false;
      isFromDisplay = true;
      isFromSetting = false;
  })
  $('.display-panel').on('dragover', function (e) {
	    if(isFromSetting) {
	        return false;
	      }
    e.preventDefault();
    if(!isTipCreated) {
      tipBox = createTipBox();
    }
    if(isDisplayEmpty) {
        this.insertBefore(tipBox, $('.grazy_worker')[0]);
      }
    if (e.target.nodeName.toLowerCase() == 'div'  && e.target.parentNode.classList.contains('moveable')) {
      passingTarget = e.target.parentNode;
      if (e.originalEvent.pageY - 130  > passingTarget.offsetTop + passingTarget.offsetHeight / 2) {
        this.insertBefore(tipBox, passingTarget.nextElementSibling);
      } else {
        this.insertBefore(tipBox, passingTarget);
      }
    } else {
      return;
    }
  })
  $('.display-panel').on('drop', function (e) {
	  if(isFromSetting) {
	      return;
	    }
    e.preventDefault();
    $('.tip-box').remove();
    $('.editing').removeClass('editing');
    if (isFromOutside) {
    		if(isDisplayEmpty) {
	            if($(dragTarget).hasClass('listcontent')) {
	              $(listcontent_str).insertBefore($('.grazy_worker'));
	            } else if($(dragTarget).hasClass('search') && !isSearchCreated) {
	              $(search_str).insertBefore($('.grazy_worker'));
	              //isSearchCreated = true;
	            } else if($(dragTarget).hasClass('border')) {
	              $(border_str).insertBefore($('.grazy_worker'));
	            } else if($(dragTarget).hasClass('text') && !isTextCreated) {
	              $(text_str).insertBefore($('.grazy_worker'));
	              isTextCreated = true;
	            } else if($(dragTarget).hasClass('bundle') && !isBundleCreated){
	        		  $(bundle_str).insertBefore($('.grazy_worker'));
	        		  isBundleCreated =true;
	        	  
	          }
	            isDisplayEmpty = false;
          }else{
        	  if($(dragTarget).hasClass('listcontent')) {
      	        $(listcontent_str).insertBefore(passingTarget);
      	      } else if($(dragTarget).hasClass('search') && !isSearchCreated) {
      	        $(search_str).insertBefore(passingTarget);
      	        //isSearchCreated = true;
      	      } else if($(dragTarget).hasClass('border')) {
      	        $(border_str).insertBefore(passingTarget);
      	      } else if($(dragTarget).hasClass('text') && !isTextCreated) {
      	        $(text_str).insertBefore(passingTarget);
      	        isTextCreated = true;
      	      } else if($(dragTarget).hasClass('bundle') && !isBundleCreated){
      	    		  $(bundle_str).insertBefore(passingTarget);
      	    		  isBundleCreated =true;
      	      }
      	      
          }
      $('.module-panel').find('.selected').removeClass('selected');
    } else {
      if (e.originalEvent.pageY - 130> passingTarget.offsetTop + passingTarget.offsetHeight / 2) {
    	  this.insertBefore(dragTarget, passingTarget.nextElementSibling);
        
      } else {
    	  this.insertBefore(dragTarget, passingTarget);
      }
      $('.setting-panel').css('top',dragTarget.offsetTop);
    } 
    for(i = 1; i <=$('.editable').length; i++) {
    	var id = 0;
    	var id = $($('.editable')[i-1]).data('id');
    	 $('.editable')[i - 1].dataset.orderby = i;
    	if(id){
    		update_local_by_item_id($($('.editable')[i-1]).data('id'),'orderby',i);
    	}else{
    		drop_id = drop_id+1;
    		$($('.editable')[i-1]).attr('data-id',drop_id);
    		$($('.editable .delete-btn')[i-1]).attr('data-id',drop_id);
    		var dataObj = {};
			dataObj.id = drop_id;
			dataObj.type = $($('.editable')[i-1]).data('type');
			dataObj.orderby = i;
			
    		if($($('.editable')[i-1]).data('type')==2){//搜索
    			dataObj.content = "1";
    			if(isSearchCreated==true){
    				continue;
    			}
    			isSearchCreated = true;
    			//get_search_setting_html(dataObj);
    			//show_setting_panel();
			}else if($($('.editable')[i-1]).data('type')==5){//分割线
				$($('.editable')[i-1]).find('.border-light').attr('id','change_border'+drop_id);
				dataObj.content = "1";
				get_border_setting_html(dataObj);
				show_setting_panel();
			}
			else if($($('.editable')[i-1]).data('type')==3){//课程
				var temp_obj = {'couser_id':{},'couser_list':{}};
				var temp_course = [];
				temp_course = temp_obj;
				dataObj.content = temp_course;
				dataObj.title ="最新课程";
				dataObj.show_more=1;
				dataObj.sort=3;
				get_course_setting_html(dataObj);
				show_setting_panel();
			}
			else if($($('.editable')[i-1]).data('type')==4){//富文本
				dataObj.content = '';
				get_content_setting_html(dataObj);
				show_setting_panel();
			}
			else if($($('.editable')[i-1]).data('type')==6){//套餐
				var temp_obj = {'package_id':{}};
				var temp_course = [];
				temp_course = temp_obj;
				dataObj.content = temp_course;
				dataObj.title ="热门套餐";
				dataObj.show_more=1;
				get_bundle_setting_html(dataObj);
				show_setting_panel()
			}
    		local_id_arr.push(drop_id);//块id 数组
    		storage_local(drop_id,dataObj);

    	}
    }
    
  });
  function show_setting_panel(){
	  $('.setting-panel').show();
	    if($('.editing').hasClass('draggable-btn')) {
	    	$('.setting-panel').css('top', $('.editing').get(0).offsetTop + 40);
    	} else {
    		$('.setting-panel').css('top', $('.editing').get(0).offsetTop + 40);
    	}
  }
  function createTipBox() {
    if (!isTipCreated) {
      var tipBox = document.createElement('div');
      tipBox.innerHTML = '将放置在此位置';
      tipBox.setAttribute('class', 'tip-box');
      isTipCreated = true;
      return tipBox;
    }
  }
  // 轮播图也可以拖拽
  $('.setting-panel').on('dragstart', '.banner-setting .moveable', function (e) {
    e = e || window.event,
    target = e.target || e.srcElement;
      dragTarget = target;
      isFromOutside = false;
      isFromDisplay = false;
      isFromSetting = true;
  })
  $('.setting-panel').on('dragover', '.setting-box', function (e) {
    if(isFromDisplay) {
      return false;
    }
    e.preventDefault();
    if(!isTipCreated) {
      tipBox = createTipBox();
    }
    if ((e.target.nodeName.toLowerCase() == 'div' || e.target.nodeName.toLowerCase() == 'p')) {
      if ($(e.target).parents('.setting-item').hasClass('moveable')) {
        passingTarget = $(e.target).parents('.setting-item')[0];
      } else if ($(e.target).hasClass('moveable')) {
        passingTarget = $(e.target)[0];
      } else {
        return;
      }
      if (e.originalEvent.pageY - 194 > passingTarget.offsetTop + passingTarget.offsetHeight / 2) {
        this.insertBefore(tipBox, passingTarget.nextElementSibling);
      } else {
        this.insertBefore(tipBox, passingTarget);
      }
    }
  
  })
  $('.setting-panel').on('drop', '.setting-box', function (e) {
	  if(isFromDisplay) {
	      return false;
	    }
    e.preventDefault();
    $('.tip-box').remove();
    if (!isFromOutside && isFromSetting) {
      if (e.originalEvent.pageY - 194 > passingTarget.offsetTop + passingTarget.offsetHeight / 2) {
    	  this.insertBefore(dragTarget, passingTarget.nextElementSibling);
        
      } else {
    	  this.insertBefore(dragTarget, passingTarget);
      }
    }
    $('.display-panel .banner-container img').attr("src", $('.banner-setting .setting-item').eq(0).find('img').attr('src') )
  });
  // 展示面板
  /**
   * 显示面板中项目点击出现边框及右上角按钮
   * 设置面板移动到对应右侧
   * 使右侧设置面板与左侧内容对应
   * 将切换前设置的内容存入缓存
   */
  $('.display-panel').on('click', '.editable', function (e) {
	var get_loal_id = $(this).data('id');
	var get_loal_type = $(this).data('type');
    if(current_div==''){
    	current_div = get_loal_id;
    	last_div = get_loal_id;
    }else{
    	last_div = current_div;
    	current_div = get_loal_id;
    }
    if(current_div !=last_div){
    	$('.editable').each(function (index, elem) {
    		if($(elem).data('id') == last_div){
    			if($(elem).data('type')==1){//banner
    				var dataArr = [];
    		    	$('.setting-item').each(function (index, elem) {
    		    		var dataObj = {};
    		    		if($(elem).find('img').attr('src') !='/public/gzadmin/images/banner_empty.png'){
    		    			dataObj.img = $(elem).find('img').attr('src');
        		    		dataObj.link = $(elem).find('.selected-link').text();
        		    		dataObj.temp = $(elem).find('.hiddenimg').val();
        		    		dataObj.id = $(elem).data('id');
        		    		dataObj.type = $(elem).data('type');
        		    		dataArr.push(dataObj);
    		    		}
    		    		
    		    	})
    		    	update_local_by_item_id(last_div,'content',dataArr);
    			}
    		}
    	})
    }
	var mylocal_data = get_storage_local(get_loal_id);
    $(this).addClass('editing').siblings().removeClass('editing');
    var reg = /^[a-zA-Z0-9_-]+box/
    var match = $(this).attr('class').match(reg)[0];
    if(match == 'search-box') {
      $('.setting-panel').hide();
      return;
    }
    $('.setting-panel').show();
    
    if(get_loal_type==1){//banner
    	get_banner_setting_html(mylocal_data);
    }
    else if(get_loal_type==2){//搜索
    	get_search_setting_html(mylocal_data);
    }
    else if(get_loal_type==3){//课程
    	get_course_setting_html(mylocal_data);
    }
    else if(get_loal_type==4){//富文本
    	get_content_setting_html(mylocal_data);
    }
    else if(get_loal_type==5){//分割线
    	get_border_setting_html(mylocal_data);
    }else{//套餐
    	get_bundle_setting_html(mylocal_data);
    }
    
    if($(e.target).hasClass('mask') || $(e.target).hasClass('editing-box')) {
    	$('.setting-panel').css('top', e.target.parentElement.offsetTop + 40);
	} else {
		$('.setting-panel').css('top', e.target.parentElement.parentElement.offsetTop + 40);
	}
   // $('.setting-panel').css('top', e.target.parentElement.offsetTop);
//    var dashIndex = match.indexOf('-');
//    match ='.' + match.substring(0, dashIndex) + '-setting';
//    $(match)[0].dataset.id = $(this).data('id');
//    $(match).show().addClass('active').siblings().removeClass('active').hide();
  })
  // 展示面板中，点击垃圾桶图标，删除本块
  $('.display-panel').on('click', '.delete-btn', function (e) {
  	  e.stopPropagation();
  	$('.setting-panel').hide();
	  var get_loal_id = $(this).data('id');
	  update_local_by_item_id(get_loal_id,'content','');
    if($(this).parents('.editing').hasClass('search-box')) {
      isSearchCreated = false;
    }
    if($(this).parents('.editing').hasClass('text-box')){
      isTextCreated = false;
      UE.getEditor('cdcontent').destroy();
	  $("#cdcontent").remove();
      $('.ueditor').remove();
	}
    if($(this).parents('.editing').hasClass('bundle-box')){
    	isBundleCreated = false;
  	}
    
    $('.setting-panel').empty();
    $(this).parents('.editing').remove();
    for(i = 1; i <= $('.editable').length; i++) {
    	update_local_by_item_id($($('.editable')[i-1]).data('id'),'orderby',i);
      $('.editable')[i - 1].dataset.orderby = i;
    }
    if(!$('.display-panel .moveable').length) {
        isDisplayEmpty = true;
      }
  })

  // 编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板编辑面板
  
  // 编辑面板中，点击盒子出现下拉菜单
  $('.setting-panel').on('click', '.select-box', function (e) {
	  e.stopPropagation();
    $(this).parents('.setting-item').addClass('editing').siblings('.setting-item').removeClass('editing').find('.slide-box').hide();
    $(this).parents('.setting-item').find('.delete-btn').show()
    $(this).parents('.setting-item').siblings('.setting-item').find('.delete-btn').hide()
    $(this).find('.slide-box').toggle();
  })

  $('.setting-panel').on('click', '.selected-box', function (e) {
    e.stopPropagation();
    $(this).parents('.setting-item').addClass('editing').siblings('.setting-item').removeClass('editing')
    $(this).parents('.setting-item').find('.delete-btn').show()
    $(this).parents('.setting-item').siblings('.setting-item').find('.delete-btn').hide()
  })
  

  // 编辑面板中，点击下拉项隐藏下拉菜单
  $('.setting-panel').on('click', '.slide-item', function (e) {
	  e.stopPropagation();
	  $(this).parent().hide();
  })

  // 

  // 轮播图编辑面板中，点击左侧图片框触发input上传图片
  $('.setting-panel').on('click', '.banner-setting .mask,.banner-setting img', function () {
    $(this).siblings('input').trigger('click');
  })

  // 轮播图编辑面板中，点击编辑项出现右上角按钮
  $('.setting-panel').on('click', '.banner-setting .setting-item', function () {
    $(this).addClass('editing').siblings().removeClass('editing').find('.edit-btn').hide();
    $(this).find('.edit-btn').show();
  })

  // 轮播图编辑面板中，点击"添加轮播图"后在编辑面板最后和显示面板的轮播图组件中同时添加一个盒子
  var banner_settingTempStr = 
  `
  <div class="setting-item moveable" draggable="true">
    <div class="img-box">
      <input type="file" class="banner-uploader" >
      <div class="mask">
        <p>添加图片</p>
        <input type="hidden" class="hiddenimg" name="hiddenimg" value="">
        <div class="img-add-box">
          <img src="/public/gzadmin/images/banner_empty.png" alt="course_cover">
        </div>
      </div>
    </div>
    <div class="link-box">
      <p>链接</p>
      <div class="select-box">
        <div class="placeholder-box">请选择链接页面或地址</div>
        <div class="slide-box">
	  		<div class="slide-item course-link">课程</div>
            <div class="slide-item package-link">套餐</div>
            <div class="slide-item custom-vip">VIP购买页面</div>
            <div class="slide-item custom-allcourse">所有课程页</div>
            <div class="slide-item custom-allpackage">所有套餐页</div>
            <div class="slide-item custom-link">自定义链接</div>
        </div>
        <div class="selected-box">
          <p class="selected-link"></p>
          <i class="cross-icon"></i>
        </div>
        <i class="slide-icon"></i>
      </div>
    </div>
    <div class="edit-btn draggable-btn"></div>
    <div class="edit-btn delete-btn"></div>
  </div>
  `,
  banner_displayIndicatorStr = 
  `
    <div class="indicator"></div>
  `;
  $('.setting-panel').on('click', '.banner-setting .add-btn', function () {
	if(!isImgUploaded) {
	  return
	}
    if(banner_tempCount >=8) {
      return false;
    }else{
    	banner_tempCount++;
    }
    $(banner_settingTempStr).appendTo($('.setting-box'));
    $(banner_displayIndicatorStr).appendTo('.banner-indicator');
    isImgUploaded = false;
  });

  // 轮播图编辑面板中，点击"垃圾桶"图标删除该项及展示区轮播图中对应的指示器
  $('.setting-panel').on('click', '.banner-setting .delete-btn', function () {
	 isImgUploaded = true;
	 if(banner_tempCount==1){
		 return ;
	 }else{
		 --banner_tempCount;
	 }
    $(this).parent().remove(); 
    $('.banner-box .indicator').eq($(this).parent().index()).remove();
  })

  //banner图片上传
  $('.setting-panel').on('change', '.banner-setting .banner-uploader', getImage);
  function getImage() {
    var that = this;
    var targetImage = this.files[0];
    var imgUrl = window.URL.createObjectURL(targetImage);
    var showImg = new Image();
    showImg.src = imgUrl;
    var reader = new FileReader();
    reader.readAsDataURL(targetImage);
    var imgFile; 
    reader.onload = function (e) {
    imgFile = e.target.result;
    $(that)
    .siblings('.mask').find('.hiddenimg').val(imgFile);
    }
    showImg.onload = function () {
      $(that)
      .siblings('.mask')
      .find('p')
      .hide()
      .siblings('.img-add-box')
      .show()
      .empty()
      .append(this);
      isImgUploaded = true;
    }
    if($(that).parents('.setting-item').index() == 0) {
      $('#display_banner').attr('src', imgUrl);
    }
    
  }

  // 课程编辑 start
  // 课程编辑栏 点击方框切换是否显示更多
  $(".setting-panel").on('click', '.listcontent-setting .checkbox, .bundle-setting .checkbox', function () {
    $(this).toggleClass('checked');
    var get_loal_id = $('.editing').attr('data-id');
    if($(this).hasClass('checked')) {
    	var show_more = 1;
        if($('.listcontent-box').hasClass('editing')) {
          $('.display-panel .editing .course-box-more').show();
        } else {
        	$('.display-panel .editing .bundle-title-box a').css('display','inline-flex');
        }
      } else {
    	  var show_more = 0;
        if($('.listcontent-box').hasClass('editing')) {
          $('.display-panel .editing .course-box-more').hide();
        } else {
          $('.display-panel .editing .bundle-title-box a').hide();
        }
      }
      update_local_by_item_id(get_loal_id,'show_more',show_more);
    
  })

  // 课程编辑栏 标题编辑
  $(".setting-panel").on('input', '.listcontent-setting .caption-input, .bundle-setting .caption-input', function () {
	  var get_loal_id = $('.editing').attr('data-id');
	  if(!$(this).val()) {
	      $('.display-panel .editing .course-box').hide();
	      $('.display-panel .editing').removeClass('withtitle');
	    } else {
	      if ($('.display-panel .editing .course-box')) {
	        $('.display-panel .editing .course-box').show();
	      } else {
	        $('.display-panel .editing').prePend($(coursebox_str));
	      }
	      $('.display-panel .editing').addClass('withtitle');
	    }
	    if($('.listcontent-box').hasClass('editing')) {
	      $('.display-panel .editing .course-box p').text($(this).val());
	      var title = $(this).val();
	    } else {
	      $('.display-panel .editing .bundle-title-box p').text($(this).val());
	      $('.display-panel .editing .bundle-title-box').css("display","inline-flex");
	      var title = $(this).val();
	    }
	    update_local_by_item_id(get_loal_id,'title',title);
  });

  // 课程编辑栏 下拉项选择后改变占位盒子的显示
  $('.setting-panel').on('click', '.listcontent-setting .slide-item', function () {
	  var get_local_id =  $('.editing').attr('data-id');
	  var sort =  $(this).attr('data-sort'); 
	  $(this).parent().siblings('.placeholder-box').attr('data-sort',sort);
	  update_local_by_item_id(get_local_id,'sort',sort);
	  //更新左边显示面板  
	  $.get(host+"customtemplate/get_one_course",{cid_arr:JSON.stringify(store_cousrselist),sort:sort},function(res){
		  var lihtml = '';
		  for(var y =0;y<res.length;y++){
			  lihtml  = lihtml+'<li><div class="teacher-box"><img src="'+res[y].face+'"></div><div class="content"><p>'+res[y].title+'</p><span>'+res[y].desc+'</span><i>'+res[y].study_count+'学员</i></div><span class="price">'+res[y].price+'</span></li>';
		  	}
		  $('.editing .course-list').html(lihtml);
		  var dataObj = {};
			var course_id  = [...new Set(store_cousrselist)];
			dataObj.course_id = course_id;
			dataObj.course_list = res;
			var id = $('.editing').data('id');
			update_local_by_item_id(id,'content',dataObj);
	  });
    if(store_cousrselist.length> 0) {
      var count = store_cousrselist.length;
      $('.modifying').css('display', 'flex').find('span').remove();
      $('<span>已选择' + count + '门课程</span>').insertBefore($('.modifying p'));
    } else if ($('.dialog .checked').length == 0) {
      $('.modifying').find('span').remove();
    }
	  
    $(this).parent().siblings('.placeholder-box').html($(this).html());
  })

  // 分割线编辑栏 start
  
  // 点击分割线下拉栏目，占位盒子及展示栏对应改变 更新缓存
  $('.setting-panel').on('click', '.border-setting .slide-item', function (e) {
	  e.stopPropagation();
    var get_loal_id = $(this).data('id');
	  var get_loal_line = $(this).data('line');
	  update_local_by_item_id(get_loal_id,'content',get_loal_line);
	  if(get_loal_line==1){var styleclass = 'border-light';var setting_styleclass='placeholder-box light';}
	  else{var styleclass = 'border-heavy';var setting_styleclass='placeholder-box heavy';}
	  var first_id = "#change_border"+get_loal_id;
	  var two_id = "#setting_change_border"+get_loal_id;
	  $(first_id).attr('class',styleclass);
	  $(two_id).attr('class',setting_styleclass);
	  
  })
 //保存
//  $('.operation-panel').on('click','.save-btn',function(){
//	  
//	 
//  });
  
//弹窗 start

  //二维码弹窗出现
  $('.showqr').on('click', function () {
    $('.dialog').css('display', 'flex').find('.ewm-box').show().siblings().hide();
  })

  // 确认弹窗出现  
  $('.save-btn').on('click', function () {
	$('.dialog').css('display', 'flex').find('.confirm-box').show().siblings().hide();
  })
  
  $(".confirm-box").on('click', '#final-cancel-save', function(){
	  $('.dialog').css('display', 'none');
  })
  //保存保存保存保存保存保存保存保存保存保存保存保存保存保存保存保存保存保存
   $(".confirm-box").on('click', '#final-conform-save', function(){
	   $('.dialog').css('display', 'none');
	   if($('.editing').data('type')==1){
			  var dataArr = [];
		    	$('.setting-item').each(function (index, elem) {
		    		var dataObj = {};
		    		if($(elem).find('img').attr('src') !='/public/gzadmin/images/banner_empty.png'){
		    			dataObj.img = $(elem).find('img').attr('src');
			    		dataObj.temp = $(elem).find('.hiddenimg').val();
			    		dataObj.link = $(elem).find('.selected-link').text();
			    		dataObj.id = $(elem).data('id');
			    		dataObj.type = $(elem).data('type');
			    		dataArr.push(dataObj);
		    		}
		    		
		    	})
		    	update_local_by_item_id(last_div,'content',dataArr);
		  }
		  var return_json = [];
		  $.each(local_id_arr,function(key,value){  
			    return_json.push(get_storage_local(value));
			}) 
			
			return_json =JSON.stringify(return_json);
		  //console.log(return_json);
			$.post(host+"customtemplate/save_all",{json_arr:return_json},function(res){
				  location.reload();
			  });
   })
  // 自定义链接及课程关联弹窗出现
  $('.setting-panel').on('click', '.slide-item', function (e) {
	e.stopPropagation();// 
	 //$(this).parents('.select-box').addClass('already-selected');
    if($(this).hasClass('course-link')) {//课程
    	var title='';var pid=0;
    	get_course(title,pid);
      $('.dialog').css('display', 'flex').find('.table-box').addClass('single').removeClass('multi').show().siblings().hide();
      $(this).parents('.select-box').addClass('modifying');
      $('.setting-panel').find('.editing').attr('data-type','course');
      
    } else if ($(this).hasClass('package-link')) {//套餐
    	//todo
    	  $('.dialog .select-box').hide();
	      var title='';
	      get_package(title);
    	  $('.dialog').css('display', 'flex').find('.table-box').addClass('single').removeClass('multi').show().siblings().hide();
          $(this).parents('.select-box').addClass('modifying');
          $('.setting-panel').find('.editing').attr('data-type','package');
          
      }else if ($(this).hasClass('custom-vip')) {//VIP购买页
    	  $('.setting-panel').find('.editing').attr('data-type','vip');
    	  $('.editing').find('.placeholder-box').hide().siblings('.slide-box').hide().siblings('.selected-box').css('display', 'flex').find('.selected-link').text('VIP购买页');
    	  $(this).parents('.select-box').addClass('already-selected');
      }else if ($(this).hasClass('custom-allcourse')) {//所有课程页
    	  $('.setting-panel').find('.editing').attr('data-type','allcourse');
    	  $('.editing').find('.placeholder-box').hide().siblings('.slide-box').hide().siblings('.selected-box').css('display', 'flex').find('.selected-link').text('所有课程页');
    	  $(this).parents('.select-box').addClass('already-selected');
      }else if ($(this).hasClass('custom-allpackage')) {//所有套餐页
    	  $('.setting-panel').find('.editing').attr('data-type','allpackage');
    	  $('.editing').find('.placeholder-box').hide().siblings('.slide-box').hide().siblings('.selected-box').css('display', 'flex').find('.selected-link').text('所有套餐页');
    	  $(this).parents('.select-box').addClass('already-selected');
      }else if ($(this).hasClass('custom-link')) {//自定义链接
      $('.dialog').css('display', 'flex').find('.link-box').show().siblings().hide();
      $(this).parents('.select-box').addClass('modifying');
      
    }
   
  })

  // 自定义链接弹窗中，点击确定，设置面板中改变
  $('.dialog').on('click', '.link-box .confirm-btn', function () {
    if(!$('.customlink-input').val()) {
      $('.validate-alert').text('自定义链接不能为空!');
      return false;
    } 
    else if (!IsURL($('.customlink-input').val())) {
      $('.validate-alert').text('请填入合法自定义链接!');
      return false;
    }
    else {
      $('.validate-alert').text('');
      $('.modifying').addClass('already-selected').find('.placeholder-box').hide().siblings('.slide-box').hide().siblings('.selected-box').css('display', 'flex').find('.selected-link').text($('.customlink-input').val())
      $('.modifying').removeClass('modifying');
      $('.setting-panel').find('.editing').attr('data-type','link');
      $('.dialog').hide();
    }
  })

  // 自定义链接中，已编辑链接后，点击×，还原
  $('.setting-panel').on('click', '.selected-box .cross-icon', function () {
	  $(this).siblings('.selected-link').html(' ');
	  $(this).parents('.setting-item').attr("data-id",'');
    $(this).parents('.select-box').removeClass('already-selected').find('.placeholder-box').show().siblings('.selected-box').hide();
  })


  // url正则验证函数
  function IsURL(str_url){
//    var strRegex = "^((https|http|ftp|rtsp|mms)?://)"
//    + "?(([0-9a-z_!~*'().&=+$%-]+: )?[0-9a-z_!~*'().&=+$%-]+@)?" //ftp的user@ 
//    + "(([0-9]{1,3}\.){3}[0-9]{1,3}" // IP形式的URL- 199.194.52.184 
//    + "|" // 允许IP和DOMAIN（域名）
//    + "([0-9a-z_!~*'()-]+\.)*" // 域名- www. 
//    + "([0-9a-z][0-9a-z-]{0,61})?[0-9a-z]\." // 二级域名 
//    + "[a-z]{2,6})" // first level domain- .com or .museum 
//    + "(:[0-9]{1,4})?" // 端口- :80 
//    + "((/?)|" // a slash isn't required if there is no file name 
//    + "(/[0-9a-z_!~*'().;?:@&=+$,%#-]+)+/?)$"; 
    var stgr  = "^((https|http|ftp|rtsp|mms)?://)";
    var re=new RegExp(stgr); 
    //re.test()
    if (re.test(str_url)){
    return (true); 
    }else{ 
    return (false); 
    }
  }
  //关闭
  $('.dialog').on('click', '.cross-icon, .cencel', function () {
    $('.dialog').hide();
  //title pid 还原
    title = '';
    pid = 0;
    
  })

  // 单选按钮，选择完课程，则改变设置面板显示
  $('.dialog').on('click', '.check-btn', function () {
    $('.check-btn').removeClass('checked');
    $(this).addClass('checked');
    $('.modifying').addClass('already-selected').find('.placeholder-box').hide().siblings('.slide-box').hide().siblings('.selected-box').css('display', 'flex').find('.selected-link').text($(this).parent().siblings('.course-name').find('p').text());
    $('.dialog').hide();
    $('.modifying').removeClass('modifying');
    //todo
    var id = $(this).attr("data-id");
    $('.setting-panel').find('.editing').attr('data-id',id);
  })


  // 多选按钮
  $('.dialog').on('click', '.multi_check', function () {
      $(this).toggleClass('checked');
      store_cousrselist = [...new Set(store_cousrselist)];
    if($(this).attr('id') == 'check_All') {
      $('.multi_check').attr('class', $(this).attr('class'));
      if($('#check_All').hasClass('checked')) {
    	  $(this).parents('.row').nextAll('.row').find('.multi_check').each(function (index, elem) {
    		  store_cousrselist.push($(elem).data('id'))
          })
      } else {
    	  $(this).parents('.row').nextAll('.row').find('.multi_check').each(function (index, elem) {
    		  store_cousrselist.splice(store_cousrselist.indexOf($(elem).data('id')), 1);
          })
      }
      store_cousrselist = [...new Set(store_cousrselist)];
    } else {
    	var id = parseInt($(this).attr('data-id'));
        if($(this).hasClass('checked')){
        	store_cousrselist.push(id);
        }else{
        	store_cousrselist.splice(store_cousrselist.indexOf(id), 1);
        }
    }
    $('.multi_check').each(function (index, elem) {
      if(!elem.classList.contains('checked')) {
        $('#check_All').removeClass('checked');
      }
    })
  })

  //下拉菜单
  $('.dialog').on('click', '.select-box', function (e) {
	  e.stopPropagation();
    $(this).find('.slide-box').toggle();
  })

  // 课程设置点击选择课程，显示多选框弹窗以及确认按钮
  $('.setting-panel').on('click', '.choose-course p', function () {
	  if($('.bundle-box').hasClass('editing')) {
	      $('.dialog .select-box').hide();
	      var title='';
	      get_package(title);
	    } else {
	    	var title='';var pid=0;
	  	    get_course(title,pid);
	        $('.dialog .select-box').css('display', 'flex');
	    }
	  
    $('.dialog').css('display', 'flex').find('.table-box').addClass('multi').removeClass('single').show().siblings().hide();
    $(this).parents('.choose-course').addClass('modifying');
    $('#search-boxtext').val('');
	 $('#search-boxid').attr('data-id','');
	 $('.dialog').find('.placeholder-box').html('选择分类');
  })

  // 多课程关联，确定后前端页面改变
  $('.dialog').on('click', '.multi .confirm-btn', function () {
	  var get_loal_id= $('.editing').attr('data-id');
	  var mylocal_data = get_storage_local(get_loal_id);
	  var sort  = mylocal_data.sort;
	  store_cousrselist = [...new Set(store_cousrselist)];
	  if($('.bundle-box').hasClass('editing')){
		  $.get(host+"customtemplate/get_one_package",{id_arr:JSON.stringify(store_cousrselist)},function(res){
			  var lihtml = '';
			  for(var z =0;z<res.length;z++){
				  var banner = res[z].banner;
				  var banner_list = res[z].banner_color;
				  var banner_html = '';
				  banner_html = banner_html+'<div class="img1"><img src="'+banner+'" alt="banner"></div>';
				  for(var zz=0;zz<banner_list.length;zz++){
					  banner_html = banner_html+'<div class="img2" style="background:'+banner_list[zz]+'"></div>';
				  }
				  lihtml  = lihtml+ ` <li>
			        <div class="bundle-showbox">
			          ${banner_html}
			        </div>
			        <div class="content">
			          <h1>${ res[z].title}</h1>
			          <i class="price">¥${ res[z].price}</i>
			        </div>
			      </li>`;
			  }
			  $('.editing .bundle-list').html(lihtml);
			  var dataObj = {};
				var package_id  = [...new Set(store_cousrselist)];
				dataObj.package_id = package_id;
				var id = $('.editing').data('id');
				update_local_by_item_id(id,'content',dataObj);
		  });
	    if(store_cousrselist.length> 0) {
	      var count = store_cousrselist.length;
	      $('.modifying').css('display', 'flex').find('span').remove();
	      $('<span>已选择' + count + '个套餐</span>').insertBefore($('.modifying p'));
	    } else if ($('.dialog .checked').length == 0) {
	      $('.modifying').find('span').remove();
	    }
	  }else{
		  $.get(host+"customtemplate/get_one_course",{cid_arr:JSON.stringify(store_cousrselist),sort:sort},function(res){
			  var lihtml = '';
			  for(var y =0;y<res.length;y++){
				  lihtml  = lihtml+'<li><div class="teacher-box"><img src="'+res[y].face+'"></div><div class="content"><p>'+res[y].title+'</p><span>'+res[y].desc+'</span><i>'+res[y].study_count+'学员</i></div><span class="price">'+res[y].price+'</span></li>';
			  }
			  $('.editing .course-list').html(lihtml);
			  var dataObj = {};
				var course_id  = [...new Set(store_cousrselist)];
				dataObj.course_id = course_id;
				dataObj.course_list = res;
				var id = $('.editing').data('id');
				update_local_by_item_id(id,'content',dataObj);
		  });
	    if(store_cousrselist.length> 0) {
	      var count = store_cousrselist.length;
	      $('.modifying').css('display', 'flex').find('span').remove();
	      $('<span>已选择' + count + '门课程</span>').insertBefore($('.modifying p'));
	    } else if ($('.dialog .checked').length == 0) {
	      $('.modifying').find('span').remove();
	    }
	  }
	  
	  
    $('.dialog').hide();
    $('.modifying').removeClass('modifying')
  })
  
  $('body').on('dragover', function (e) {
    if(e.target.classList.contains('template-content') || e.target.classList.contains('banner-setting') || e.target.classList.contains('add-btn')) {
      $('.tip-box').remove();
      isTipCreated = false;
    }
  })
 //选择分类
  $('.dialog').on('click','.slide-item', function (e) {
	  e.stopPropagation();
	  $(this).parent().hide();
	  var id = $(this).data('id');
	  $(this).parent().siblings('.placeholder-box').html($(this).html()).parent().siblings('.search-box').attr('data-id', id);
  })
  //点击搜索
  $('.dialog').on('click','.search-icon', function () {
	  if($('.bundle-box').hasClass('editing')){
		  title = $('#search-boxtext').val();
		  get_package(title);
		 
	  }else{
		  pid = $(this).parent().attr('data-id');
		  title = $('#search-boxtext').val();
		  get_course(title,pid);
	  }
	  
  })
  //点击 套餐选择
  function get_package(title){
	  $(".page_div3").html('');
      var page4 = $(".page_div3").paging({
          submitStyle:"ajax",
          ajaxSubmitType: "get",
          url:host + 'customtemplate/getpackage',
          ajaxData:{title:title},
          ajaxSubmitBack: function (data) {
          	var currentpage = data.currentPage;
          	changepackagelist(data.courselist);
     		if(currentpage==1&&data.courselist.length<10)
     		{
     			$('.jqpagediv').hide();
     		}
          },
      });
      function changepackagelist(courselist){
    	  var temphtml ='';
        	var temp_arr = [];
        	if(courselist.length==0){
        		temphtml =temphtml+'<div><span>暂时无数据..</span></div>';
        		$('.jqpagediv').hide();
        	}else{
        		$('.jqpagediv').show();
        		//row
        		$('.row p').html('选择套餐');
        		temphtml = '<div class="row table-caption">'+
  		      				'<div class="course-caption"><p>套餐名称</p></div>'+
  		      				'<div class="price-caption"><p>价格</p></div>'+
  		      				'<div class="choose-caption"><p class="choose-word">选择</p><i class="multi_check" id="check_All"></i></div>'+
  		      				'</div>';
        		if(courselist.length>0){
        			for (var i = 0; i < courselist.length; i++) {
        				var isset = 1;
        				if(store_cousrselist!=[]){
        					var isset = store_cousrselist.indexOf(courselist[i]['id']);//-1不存在
        				}
            			temphtml =temphtml+'<div class="row table-row">'+
            								'<div class="course-name"><p>'+courselist[i]['title']+'</p></div>'+
            								'<div class="price"><p>¥'+courselist[i]['price']+'</p></div>'+
            								'<div class="choose"><i class="btn check-btn" data-id="'+courselist[i]['id']+'"></i><i class="multi_check ';
            			if(isset !=-1){
            				temphtml =temphtml+'checked';
            			}					
            			temphtml =temphtml+'" data-id="'+courselist[i]['id']+'"></i></div>'+
            								'</div>';
          	  	}
        		}
        		
        	}
       		$('#change_banner_html').html(temphtml);
      }
  }
  
  //
  //点击 课程选择 方法
  function get_course(title,pid){
	  $(".page_div3").html('');
      var page4 = $(".page_div3").paging({
          submitStyle:"ajax",
          ajaxSubmitType: "get",
          url:host + 'customtemplate/getcourse',
          ajaxData:{title:title,pid:pid},
          ajaxSubmitBack: function (data) {
          	var currentpage = data.currentPage;
          	changecourselist(data.courselist);
         		
     		if(currentpage==1&&data.courselist.length<10)
     		{
      		$('.jqpagediv').hide();
     		}
          },
      });
      function changecourselist(courselist){
      	var temphtml ='';
      	var temp_arr = [];
      	if(courselist.length==0){
      		temphtml =temphtml+'<div><span>暂时无数据..</span></div>';
      		$('.jqpagediv').hide();
      	}else{
      		$('.jqpagediv').show();
      		temphtml = '<div class="row table-caption">'+
		      				'<div class="course-caption"><p>课程</p></div>'+
		      				'<div class="price-caption"><p>价格</p></div>'+
		      				'<div class="choose-caption"><p class="choose-word">选择</p><i class="multi_check" id="check_All"></i></div>'+
		      				'</div>';
      		if(courselist.length>0){
      			for (var i = 0; i < courselist.length; i++) {
      				var isset = 1;
      				if(store_cousrselist!=[]){
      					var isset = store_cousrselist.indexOf(courselist[i]['cid']);//-1不存在
      				}
          			//temp_arr.push(courselist[i]['cid']);
          			temphtml =temphtml+'<div class="row table-row">'+
          								'<div class="course-name"><p>'+courselist[i]['title']+'</p></div>'+
          								'<div class="price"><p>¥'+courselist[i]['price']+'</p></div>'+
          								'<div class="choose"><i class="btn check-btn" data-id="'+courselist[i]['cid']+'"></i><i class="multi_check ';
          			if(isset !=-1){
          				temphtml =temphtml+'checked';
          			}					
          			temphtml =temphtml+'" data-id="'+courselist[i]['cid']+'"></i></div>'+
          								'</div>';
        	  	}
      		}
      		
      	}
     		$('#change_banner_html').html(temphtml);
      }
  }
  // 下移
  $('.display-panel').on('click', '.movedown-btn', function () {
	  if($(this).parents('.editable').next().hasClass('grazy_worker')){
		  return;
	  }else{
		  if($(this).parents('.moveable').next().hasClass('moveable')) {
			  var local_id = $('.editing').attr('data-id');
			  var orderby =$('.editing').attr('data-orderby');
			  var parent = $(this).parents('.editable');
			  var next_id = parent.next().attr('data-id');
			  var next_orderby = parent.next().attr('data-orderby');
//			  console.log(local_id,orderby);
//			  console.log(next_id,next_orderby);
			  $('.editing').attr('data-orderby',next_orderby);
			  update_local_by_item_id(local_id,'orderby',next_orderby);
			  parent.next().attr('data-orderby',orderby);
			  update_local_by_item_id(next_id,'orderby',orderby);
			  parent.next().after(parent);
		  }
	  }
	  
	  });
//上移
	  $('.display-panel').on('click', '.moveup-btn', function () {
		  if($(this).parents('.editable').prev().hasClass('banner-box')){
			    return;
		  }else{
			  if($(this).parents('.moveable').prev().hasClass('moveable')) {
				  var local_id = $('.editing').attr('data-id');
				  var orderby =$('.editing').attr('data-orderby');
				  var parent = $(this).parents('.editable');
				  var next_id = parent.prev().attr('data-id');
				  var next_orderby =parent.prev().attr('data-orderby');
//				  console.log(local_id,orderby);
//				  console.log(next_id,next_orderby);
				  $('.editing').attr('data-orderby',next_orderby);
				  update_local_by_item_id(local_id,'orderby',next_orderby);
				  parent.prev().attr('data-orderby',orderby);
				  update_local_by_item_id(next_id,'orderby',orderby);
			    parent.prev().before(parent);
			  }
		  }
		  
	  });
})