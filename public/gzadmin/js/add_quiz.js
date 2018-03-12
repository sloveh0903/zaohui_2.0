$(function () {
	console.log(111);
	var count_filter_Arr = [0,1];
	//错误提醒
	  $('.jq-error').css('display','none');
  // 切换题型
  var ue0 = UE.getEditor('container0',{initialFrameHeight:200,initialFrameWidth:600,autoHeightEnabled:false,toolbars: [[
      'fullscreen', 'source', 'undo', 'redo', 'bold', 'italic', 'underline', 'fontsize', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'insertimage','preview', 'kityformula'
      ]]});
  var ue1 = UE.getEditor('container1',{initialFrameHeight:200,initialFrameWidth:600,autoHeightEnabled:false,toolbars: [[
      'fullscreen', 'source', 'undo', 'redo', 'bold', 'italic', 'underline', 'fontsize', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'insertimage','preview', 'kityformula'
      ]]});
  var editor_count = 1;
  $('.editor-container').each(function (i, e) {
    addEditor($(e));
  })
  //addEditor();
  function addEditor (obj) {
	  editor_count++;
	  count_filter_Arr.push(editor_count);
    var editor_tempStr = `<script id="container${editor_count}" name="content[]" type="text/plain"></script>`,
    editor_initStr = 
    `
    <script type="text/javascript">
      var ue${editor_count} = UE.getEditor('container${editor_count}',{initialFrameHeight:100,initialFrameWidth:600,autoHeightEnabled:false,toolbars: [[
      'fullscreen', 'source', 'undo', 'redo', 'bold', 'italic', 'underline', 'fontsize', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'insertimage','preview', 'kityformula'
      ]]});
    </script>
    `;
    $(editor_tempStr).appendTo(obj);
    $(editor_initStr).appendTo($('body'));
    
    //console.log(editor_count, editor_tempStr, editor_initStr)
  }
  $('.tab').on('click', function () {
    $(this)
    .addClass('checked')
    .siblings()
    .removeClass('checked');
    $
    if ($('.tab.multi').hasClass('checked')) {
      $('.choose-answer, .choose-option').show().siblings('.judge-answer, .fill-option').hide();
      $('.option-letter').addClass('multi-option');
      $('.option-letter').show();
      clean_option();
	  $("#type").val(2);//多选
    } else if ($('.tab.judge').hasClass('checked')) {
      $('.judge-answer').show().siblings('.choose-answer, .answer-option, .fill-option').hide();
      $('.option-letter').removeClass('multi-option');
      $('.option-letter').show();
      clean_option();
	  $("#type").val(3);//判断
    } else if ($('.tab.single').hasClass('checked')) {
      $('.choose-answer, .answer-option').show().siblings('.judge-answer, .fill-option').hide();
      $('.option-letter').removeClass('multi-option');
      $('.option-letter').show();
      $("#type").val(1);//单选
	  clean_option();
    } else {
    	clean_option();
  	  $("#type").val(4);//填空
      $('.fill-option').show().siblings('.judge-answer, .choose-answer, .choose-option').hide();
      $('.option-letter').hide();
    }
  });
  //清除正确答案（跳转tab）
  function clean_option(){
  	$("#correct_option").val('');
  	$("li.choose-answer").find("i.option-letter").removeClass('selected');
  }
  //做题难度
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
  $('.correct-answer').on('click', '.option-letter', function () {
    if (!$('.multi').hasClass('checked')) {
      $(this)
      .addClass('selected')
      .siblings()
      .removeClass('selected');
    } else {
      $(this).toggleClass('selected');
    }
    var correct_letter = $("li.correct-answer").find("i.option-letter").filter(".selected").text();
    $("#correct_option").val(correct_letter);
  });
  // 添加选项
  var filterArr = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
  $('.add_option-btn').on('click', function () {
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
//提交继续添加
  $('.submit-more').on('click', function () { 
  	$("#back_url").val(1);
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
	  	if(type==1 ||type==2){
	  		if(count_filter_Arr.length<2){
	  	  		$('#optionchecked').css('display','');
	  	  		$('#optionchecked').html('选项不能小于两个选项');
	  	  		return;
	  	  	}else{
	  	  		$('#optionchecked').css('display','none');
	  	  		$('#optionchecked').html('');
	  	  	}
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
		if($('#correct_option').val()==''){
			$('#correctoptionchecked').css('display','');
	  	  	$('#correctoptionchecked').html('正确答案必须填写');
	  	  return;
		}else{
			$('#correctoptionchecked').css('display','none');
	  	  	$('#correctoptionchecked').html('');
		}
	  	}
	  	if(type==3){//判断题
	  		var judz_str = '{"A":"正确","B":"错误"}';
	  		$('#option_json_arr').val(judz_str);
	  		console.log($('#correct_option').val());
	  		if($('#correct_option').val()==''){
	  			$('#correctoptionchecked').css('display','');
		  	  	$('#correctoptionchecked').html('正确答案必须填写');
		  	    return;
	  		}else{
	  			$('#correctoptionchecked').css('display','none');
		  	  	$('#correctoptionchecked').html('');
	  		}
	  	}
//	  	var container1=UE.getEditor('container1').getContent();//题目解析
//	  	if(container1==''){
//	  		$('#parsechecked').css('display','');
//	  		$('#parsechecked').html('解析不能为空');
//	  		return;
//	  	}else{
//	  		$('#parsechecked').css('display','none');
//	  		$('#parsechecked').html('');
//	  	}
	    for(var uei=0;uei<count_filter_Arr.length;uei++){
	  	  var ue_str = 'ue'+count_filter_Arr[uei];
	  		  eval(ue_str).getKfContent(function(content){
	  			
	            })
	    }
	    setTimeout(final_sumbmit,1000);
      //submit_all();
  }
  function final_sumbmit(){
	  console.log('final');
  	var container0=UE.getEditor('container0').getContent();//题目标题
  	$("#name").val(container0);
  	var type = $("#type").val();
  	if(type==1 ||type==2){
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
  	if(type==3){//判断题
  		var judz_str = '{"A":"正确","B":"错误"}';
  		$('#option_json_arr').val(judz_str);
  	}
  	var container1=UE.getEditor('container1').getContent();//题目解析
  	$("#parse").val(container1);
    $("#form1").submit();
  	
  }
})