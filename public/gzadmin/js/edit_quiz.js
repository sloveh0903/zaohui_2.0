    
$(function () {
	//console.log(1111);
	 var count_filter_Arr = [0,1];
	  var option_count = parseInt($("#option_count").val());
	  var editor_count = 1;
	  var ue0 = UE.getEditor('container0',{initialFrameHeight:200,initialFrameWidth:600,autoHeightEnabled:false,toolbars: [[
	      'fullscreen', 'source', 'undo', 'redo', 'bold', 'italic', 'underline', 'fontsize', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'insertimage','preview', 'kityformula'
	      ]]});
	  var ue1 = UE.getEditor('container1',{initialFrameHeight:200,initialFrameWidth:600,autoHeightEnabled:false,toolbars: [[
	      'fullscreen', 'source', 'undo', 'redo', 'bold', 'italic', 'underline', 'fontsize', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'insertimage','preview', 'kityformula'
	      ]]});
	  $('.editor-container').each(function (i, e) {
		    addEditor($(e));
		  })
	  var option_length = "{$option_length}";
	  if(option_length>=10){
		  $('.add_option-btn').addClass('limit');
	  }
	  //错误提醒
	  $('.jq-error').css('display','none');
	  var type = $("#type").val();
	  //判断题
	  $("#type").val(type);
	  if(type==3){
		  $('.judge-answer').show().siblings('.choose-answer, .answer-option').hide();
		  $('.option-letter').removeClass('multi-option');
	  }else{
		  $('.choose-answer, .answer-option').show().siblings('.judge-answer').hide();
		  $('.option-letter').removeClass('multi-option');
	  }
   $('.simple').on('click',function () {
   	$(this)
       .addClass('checked')
       .siblings()
       .removeClass('checked');
   	$("#level").val(1);//简单
   });
   $('.middle').on('click',function () {
   	$(this)
       .addClass('checked')
       .siblings()
       .removeClass('checked');
   	$("#level").val(2);//一般
   });
   $('.higher').on('click',function () {
   	$(this)
       .addClass('checked')
       .siblings()
       .removeClass('checked');
   	$("#level").val(3);//困难
   });
   // 切换正确答案
   $('.correct-answer').on('click','.option-letter', function () {
   	$("#correct_option").val('');
   	
   	if(type==2){
   		$(this).toggleClass('selected');
   		var correct_letter = $("li.choose-answer").find("i.option-letter").filter(".selected").text();
   	}
   	else if(type==4){
 	  	  $("#type").val(4);//填空
 	      $('.fill-option').show().siblings('.judge-answer, .choose-answer, .choose-option').hide();
 	      //$('.option-letter').hide();
 	      //$('.answer-option').css('display','none');
 	    }
   	else{
   		$(this)
           .addClass('selected')
           .siblings()
           .removeClass('selected');
   		if(type==3){
   			var correct_letter = $("li.judge-answer").find("i.option-letter").filter(".selected").text();
   		}
   		else{
   			var correct_letter = $("li.choose-answer").find("i.option-letter").filter(".selected").text();
   		}
   	}
      $("#correct_option").val(correct_letter);
   });
   function addEditor (obj) {
	  	  editor_count++;
	  	  count_filter_Arr.push(editor_count);
	      var editor_tempStr = `<script id="container${editor_count}" name="option[]" type="text/plain"></script>`,
	      editor_initStr = 
	      `
	      <script type="text/javascript">
	        var ue${editor_count} = UE.getEditor('container${editor_count}',{initialFrameHeight:100,initialFrameWidth:464,autoHeightEnabled:false,toolbars: [[
	        'fullscreen', 'source', 'undo', 'redo', 'bold', 'italic', 'underline', 'fontsize', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'insertimage','preview', 'kityformula'
	        ]]});
	      </script>
	      `;
	      $(editor_tempStr).appendTo(obj);
	      $(editor_initStr).appendTo($('body'));
	    }
	 // 添加选项
	    var filterArr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
	    $('.add_option-btn').on('click', function () {
	    	console.log(filterArr);
	      if ($(this).hasClass('limit')) {
	        return false;
	      }
	      if ($('.tab.fill').hasClass('checked')) {
	        var newFillOption = 
	        '<li>' +
	        '<input type="text" name="fill_option[]">' +
	        '<i class="delete-option">删除</i>' +
	        '</li>';
	        $(newFillOption).appendTo('.fill-option ul');
	      } else {
	        var index = $('.choose-option li').length;
	        var newLetter = filterArr[index];
	        var newSelectOption_Str = 
	        '<li  class="choose-optionbox">' +
	          '<i class="option-letter selected">' + newLetter + '</i>' +
	          '<i class="delete-option">删除</i>' +
	        '</li>';
	        var newSelectOption_Obj = $(newSelectOption_Str);
	        newSelectOption_Obj.appendTo('.choose-option ul');
	        addEditor(newSelectOption_Obj);
	        var newAnswer = '<i class="option-letter">' + newLetter + '</i>';
	        $(newAnswer).appendTo('.choose-answer p');
	        
	        if ($('.choose-option li').length == filterArr.length) {
	          $(this).addClass('limit');
	        } else {
	          $(this).removeClass('limit');
	        }
	      }
	    });
	    $('.answer-option').on('click', '.delete-option', function () {
	      if ($('.tab.fill').hasClass('checked')) {
	        $(this).parent().remove();
	      } else {
	        $('.choose-answer .option-letter')[$(this).parent().index()].remove();
	        $(this).parent().remove();
	        filterArr.forEach(function (value, index) {
	          $('.choose-option li:eq(' + index + ')')
	          .find('input')
	          .attr('name', 'option[' + value + ']')
	          .end()
	          .find('.option-letter')
	          .text(value);
	          $('.choose-answer i:eq(' + index + ')')
	          .text(value);
	        });
	        var target_Id = $(this).siblings('div').attr('id');
	        var target_Count = target_Id.substring(target_Id.length - 1, target_Id.length);
	        var target_Index = count_filter_Arr.indexOf(target_Count);
	        count_filter_Arr.splice(target_Index, 1);
	        if ($('.choose-option li').length < filterArr.length) {
	          $('.add_option-btn').removeClass('limit');
	        }
	      }
	    });
   //提交
   $('.submit-once').on('click', function () { 
	   first_submit();
   });
   function first_submit(){
	   var container0=UE.getEditor('container0').getContent();//题目标题
	  	if(container0==''){
	  		$('#namechecked').css('display','');
	  		$('#namechecked').html('标题不能为空');
	  		return;
	  	}else{
	  		$('#namechecked').css('display','none');
	  		$('#namechecked').html('');
	  	}
  	var type = $("#type").val();
  	var correct_option = $('#correct_option').val();
  	if(type==1 || type==2){
 		for(var choosei=0;choosei<count_filter_Arr.length;choosei++){
 			if(count_filter_Arr[choosei]==0||count_filter_Arr[choosei]==1){
 				
 			}else{
 				var choose_container = count_filter_Arr[choosei];
 	  			var choose_id = 'container'+choose_container;
 	  			var choose_container_content = UE.getEditor(choose_id).getContent();//答案选项
 	  			var choose_option_letter = $('#'+choose_id).siblings('.option-letter').html();
 	  			if(choose_container_content==''){
 	  				$('#optionchecked').css('display','');
 	  	  	  		$('#optionchecked').html(choose_option_letter+'选项不能为空');
 	  	  	  		return;
 	  			}else{
 	  				$('#optionchecked').css('display','none');
 	  	  	  		$('#optionchecked').html('');
 	  			}
 			}
 			
 		}
  	}
  	//判断题
  	if(type==3){
			//正确答案必须填写
    	if(correct_option==''){
    		$('#judge_correct').css('display','');
    		return;
    	}else{
    		$('#judge_correct').css('display','none');
    	}
  	}
  	else if(type==4){
  		
  	}
  	else{
  		var index = $('.answer-option li').length;
  		var is_nothave_content = 0;
  		for(var i =0;i<index;i++){
  			var p_name = $('.answer-option li').eq(i).find('input').val();
  			if(p_name==''){
  				is_nothave_content = is_nothave_content+1;
  			}
  		}	
  		if(is_nothave_content){
  			$('#optionchecked').css('display','');
      		$('#optionchecked').html('选项不能为空');
      		return;
  		}else{
      		$('#optionchecked').css('display','none');
      		$('#optionchecked').html('');
      	}
  		//正确答案必须填写
      	
      	if(correct_option==''){
      		$('#correct_optionchecked').css('display','');
      		return;
      	}else{
      		$('#correct_optionchecked').css('display','none');
      	}
  	}
  	
  	//解析
  	var container1=UE.getEditor('container1').getContent();//题目解析
 	if(container1==''){
 		$('#parsechecked').css('display','');
 		$('#parsechecked').html('解析不能为空');
 		return;
 	}else{
 		$('#parsechecked').css('display','none');
 		$('#parsechecked').html('');
 	}
	  if(type==3 ||type==4){
		  var temparr = [0,1];
	  }
	  for(var uei=0;uei<count_filter_Arr.length;uei++){
	  	  var ue_str = 'ue'+count_filter_Arr[uei];
	  		  eval(ue_str).getKfContent(function(content){
	  			  	//console.log(111);
	            })
	    }
	  setTimeout(submit_all,1000);   
     //submit_all();
 }
   function submit_all(){
	   //console.log(22);
	   var container0=UE.getEditor('container0').getContent();//题目标题
	  	$("#name").val(container0);
	   	var type = $("#type").val();
	   	var correct_option = $('#correct_option').val();
	   	if(type==1 || type==2){
	   		dataObj  =new Object();
	  		for(var choosei=0;choosei<count_filter_Arr.length;choosei++){
	  			if(count_filter_Arr[choosei]==0||count_filter_Arr[choosei]==1){
	  				
	  			}else{
	  				var choose_container = count_filter_Arr[choosei];
	  	  			var choose_id = 'container'+choose_container;
	  	  			var choose_container_content = UE.getEditor(choose_id).getContent();//答案选项
	  	  			var choose_option_letter = $('#'+choose_id).siblings('.option-letter').html();
	  	  			if(choose_option_letter!=undefined){
		  			    dataObj[choose_option_letter]=choose_container_content;
		  			}
	  			}
	  			
	  		}
	  		$('#option_json_arr').val(JSON.stringify(dataObj));
	   	}
   	
   	//解析
   	var container1=UE.getEditor('container1').getContent();//题目解析
  	$("#parse").val(container1);
   	$("#formsave").submit();
   }

 })
 