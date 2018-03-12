<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/member/detail.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>后台管理系统</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <!-- load css -->
    <link rel="stylesheet" type="text/css" href="/public/jqadmin/css/bootstrap.min.css?v=v3.3.7" media="all">
    <link rel="stylesheet" type="text/css" href="/public/jqadmin/css/font/iconfont.css?v=1.0.0" media="all">
    <link rel="stylesheet" type="text/css" href="/public/jqadmin/css/layui.css?v=1.0.9" media="all">
    <link rel="stylesheet" type="text/css" href="/public/jqadmin/css/main.css?v1.3.1" media="all">
    <link rel="stylesheet" href="/public/gzadmin/css/all.css">
    <link rel="stylesheet" href="/public/gzadmin/css/main.css">
    
</head>
<link rel="stylesheet" href="/public/gzadmin/css/member_detail.css">
<style>
.bp-top {
  margin-top: 18px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
      -ms-flex-pack: justify;
          justify-content: space-between;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
}
.bp-top .bpdot-icon {
  display: inline-block;
  vertical-align: middle;
  width: 8px;
  height: 8px;
  background: url("/public/pc/images/bp-dot.png") no-repeat center / cover;
  margin-right: 5px;
}
.bp-top .bp-info {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
}
.bp-top .bp-remain {
  margin-right: 30px;
}
.bp-top .bp-remain,
.bp-top .bp-total {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
}
.bp-top .bp-remain > span:nth-child(2), 
.bp-top .bp-total > span:nth-child(2) {
  font-size: 14px;
  line-height: 14px;
  color: rgba(41, 43, 51, .4);
  margin-right: 2px;
}
.bp-top .bp-remain > span:nth-child(3),
.bp-top .bp-total > span:nth-child(3) {
  font-size: 16px;
  line-height: 16px;
  color: rgba(41, 43, 51, .8);
}
.bp-top .bp-filter {
  width: 170px;
  height: 40px;
  border: 1px solid #eee;
  border-radius: 3px;
  position: relative;
  cursor: pointer;
}
.bp-top .bp-filter .bp-filtershow,
.bp-top .bp-filter .bp-filteritem {
  padding: 8px 5px 8px 15px;
}
.bp-top .bp-filter .bp-filtershow {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
      -ms-flex-pack: justify;
          justify-content: space-between;
}
.bp-filter .bp-filtershow p {
  font-size: 14px;
  line-height: 24px;
  color: rgba(41, 43, 51, .8);
} 
.bp-filter .bp-filtershow .bp-filtericon {
  width: 24px;
  height: 24px;
  background: url("/public/pc/images/zhedie_down@2x24.png") no-repeat center / cover;
}
.bp-filter .bp-filtershow.open .bp-filtericon {
  background-image: url("/public/pc/images/zhedie_up@2x24.png");
}
.bp-top .bp-filter .bp-filterslide {
  position: absolute;
  z-index: 9;
  width: 100%;
  top: 45px;
  left: 0;
  padding-top: 5px;
  background-color: #fff;
  -webkit-box-shadow: 0 3px 6px rgba(0, 0, 0, .06);
          box-shadow: 0 3px 6px rgba(0, 0, 0, .06);
  border: 1px solid #eee;
  border-radius: 3px;
  display: none;
}
.bp-filter .bp-filterslide .bp-filteritem {
  width: 100%;
  height: 40px;
  cursor: pointer;
}
.bp-filter .bp-filterslide .bp-filteritem:hover {
  background-color: #f5f5f5
}
.bp-filter .bp-filterslide .bp-filteritem p {
  font-size: 14px;
  line-height: 24px;
  color: rgba(41, 43, 51, .5);
}
</style>
    <body>
    <div class="article_manage">
      <!-- 9.13 替换右侧头部 -->
      <div class="right-side-header clearfix">
        <span>用户管理</span>
           

<div class="user-box">
  <span class="user-self">
    <i><?php echo session('rolename'); ?>(<?php echo session('admin_username'); ?>)</i>
    <img class="img_myself" src="/public/image/logo.png" alt="自身头像">
  </span>
  <ul class="user-set-ul">
   	<li><i class="modal-catch" data-params='{"content":".edit_pswd","act":"<?php echo url("user/edit_password"); ?>", "title":"修改密码","type":"1"}'>修改密码</i></li>
    <li><a href='<?php echo url("login/loginOut"); ?>'  target="_blank"><i>退出</i></a></li>
  </ul>
</div>
<div class="success_tip displayNone">已完成</div>
        
        </div>
        <div class="success_tip displayNone">已完成</div>
      </div>
      <!-- 替换右侧头部 结束 -->
      <!--内容开始-->
      <div class="right_side_content"> 
        <!--标题-->
        <div class="system_guanli_div">
            <ul class="system_guanli_ul user_edit">
                <li><a href="javascript:void(0)" class="active" data-src="user_list">用户管理</a><span>&nbsp;/&nbsp;详情</span></li>
            </ul>
        </div>
        <!--用户信息详情-->
        <div class="user_details">
            <!--头像昵称-->
            <div class="person">
                <img src="<?php echo $userinfo['face']; ?>" alt=""/>
                <div class="nikname_box">
                    <p class="nikname"><?php echo $userinfo['nickname']; if($vip == 1 || $vip == 2): ?><span>VIP</span>
                    </p>
                    <?php endif; if($vip == 2): ?>
                    <p class="vip_state">终身会员</p>
                    <?php endif; if($vip == 1): ?>
                    <p class="vip_state">会员 <?php echo date('Y-m-d',$userinfo['expire_time']) ?>到期</p>
                    <?php endif; ?>
                </div>
            </div>
            <!--用户信息-->
            <div class="person_details">
                <div class="key">
                    <span>性别</span>
                    <span>手机号</span>
                    <span>创建时间</span>

                </div>
                <div class="value1" style="margin:0px;">
                    <p><?php echo $userinfo['sex']; ?></p>
                    <p><?php if($userinfo['mobile'] == ''): ?> 无 <?php else: ?> <?php echo $userinfo['mobile']; endif; ?></p>
                    <p><?php echo $userinfo['create_time']; ?></p>
                </div>
            </div> 
            <!--购买信息-->
            <div class="buy_details">
                <ul>
                    <li class="cour_price">
                        <p>￥<?php echo $order_sum; ?></p>
                        <span>总额</span>
                    </li>
                    <li class="jifen">
                        <p><?php echo $userinfo['now_integral']; ?></p>
                        <span>积分</span>
                    </li>
                    <li class="buy_course">
                        <p><a href="<?php echo url('/admin/order/index'); ?>?uid=<?php echo $userinfo['uid']; ?>"><?php echo $order_count; ?></a></p>
                        <span><a href="<?php echo url('/admin/order/index'); ?>?uid=<?php echo $userinfo['uid']; ?>">购买课程</a></span>
                    </li>
                </ul>
            </div>
        </div>
        <!--用户管理选项卡-->
          <div class="system_guanli_div user_div" style="margin: 20px;">
              <ul class="system_guanli_ul user_edit_ul">
                <li><a href="<?php echo url('member/detail',['uid'=>$uid]); ?>" class="<?php if($type == 1): ?>active<?php endif; ?>" data-src="">学习记录</a></li>
                <li><a href="<?php echo url('member/detail',['uid'=>$uid,'type'=>2]); ?>"  class="<?php if($type == 2): ?>active<?php endif; ?>" data-src="">做题记录</a></li>
              	<li><a href="<?php echo url('member/detail',['uid'=>$uid,'type'=>3]); ?>"  class="<?php if($type == 3): ?>active<?php endif; ?>" data-src="">积分明细</a></li>
              </ul>
          </div>
        <div class="user_right_main ">
            
            <!--选项卡详情--> 
            <ul class="tab_main">
                <?php if($type == 1): ?>
                <!--学习记录-->
                <li  class="study cur">
                    <table class="user_table">
                        <thead>
                            <tr>
                                <th class="course_width">课程</th>
                                <th class="num_width">已观看/视频数量</th>
                                <th class="progress_width">学习进度</th>
                                <th class="time_width">报名时间</th>
                                <th class="last_width">最后学习时间</th>
                                <th class="">操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                        <tr>
                            <td class="course_width" title="<?php echo $item['title']; ?>" style="cursor:pointer"><?php echo $item['title']; ?></td>
                            <td class="num_width"><?php echo $item['study_video_count']; ?> / <?php echo $item['count_video']; ?></td>
                            <td class="progress_width"><?php echo $item['percent']; ?>%</td>
                            <td class="time_width"><?php echo $item['order_time']; ?></td>
                            <td class="last_width"><?php echo $item['update_time']; ?></td>
                            <td class="video_detail" data-cid="<?php echo $item['cid']; ?>" data-title="<?php echo $item['title']; ?>" style="cursor:pointer">详情</td>
                        </tr>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>                    
                    </table>
                </li>
                <?php elseif($type==2): ?>
                <!--做题记录-->
                <li  class="quiz" style="display: block">
                    <table  class="user_table">
                        <thead>
                            <tr>
                                <th class="quiz_time_w">做题时间</th>
                                <th class="name_w">题库名称</th>
                                <th class="about_w">关联课程</th>
                                <th class="quiz_num_w">做题数</th>
                                <th class="false_w">错题数</th>
                                <th class="true_w">正确率</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                            <tr>
                                <td class="quiz_time_w"><?php  echo date('Y-m-d H:i:s',$item['update_time'])  ?></td>
                                <td class="name_w"><?php echo $item['name']; ?></td>
                                <td class="about_w"><?php echo $item['course_name']; ?></td>
                                <td class="quiz_num_w"><?php echo $item['recore_count']; ?></td>
                                <td class="false_w"><?php echo $item['recore_wrong']; ?></td>
                                <td class="true_w"><?php echo $item['proportion']; ?>%</td>
                            </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                </li>
                <?php else: ?>
                <!--积分明细-->
                <li  class="quiz" style="display: block">
                <div class="bp-top">
			        <div class="bp-info">
			          <div class="bp-remain">
			            <i class="bpdot-icon"></i>
			            <span>剩余积分</span>
			            <span class="bp-remain-num"><?php echo $userinfo['now_integral']; ?></span>
			          </div>
			          <div class="bp-total">
			            <i class="bpdot-icon"></i>
			            <span>总积分</span>
			            <span class="bp-total-num"><?php echo $userinfo['integral_count']; ?></span>
			          </div>
			        </div>
			        <div class="bp-filter">
			          <div class="bp-filtershow" >
			            <p><?php if($seach_id==1): ?>获取<?php elseif($seach_id==2): ?>使用<?php else: ?>全部<?php endif; ?></p>
			            <i class="bp-filtericon"></i>
			          </div>
			          <div class="bp-filterslide" >
			            <div class="bp-filteritem" data-select-id="0">
			              <p>全部</p>
			            </div>
			            <div class="bp-filteritem" data-select-id="1">
			              <p>获取</p>
			            </div>
			            <div class="bp-filteritem" data-select-id="2">
			              <p>使用</p>
			            </div>
			          </div>
			        </div>
			      </div>
			      <table  class="user_table">
                        <thead>
                            <tr>
                                <th class="quiz_time_w">积分来源</th>
                                <th class="name_w">积分变化</th>
                                <th class="quiz_time_w">时间</th>
                                <th class="quiz_time_w">备注</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): $mod = ($i % 2 );++$i;?>
                            <tr>
                                <td class="quiz_time_w"><?php echo $item['config_name']; ?></td>
                                <td class="name_w"><?php if($item['integral']>=0): ?>+<?php endif; ?><?php echo $item['integral']; ?></td>
                                <td class="quiz_time_w"><?php  echo date('Y-m-d H:i:s',$item['create_time'])  ?></td>
                                <td class="quiz_time_w"><?php echo $item['remark']; ?></td>
                            </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                        </tbody>
                    </table>
                </li>
                <?php endif; ?>
            </ul>
      
            <div class="mt100 mb20 page" >
                <ul  class="course_pager_ul">
                    <?php echo $page_lists->render(); ?>
                </ul>
                <div class="clearfix"></div>
            </div> 
        </div>

      </div>
      <!-- 内容结束-->
   </div>
   <!--         弹出视频学习情况 -->
	        <div class="user_data_list">
		    	<div class="data_list_content">
		    		<img class="data_list_close" src="/public/gzadmin/images/close@2x.png" alt="关闭"/>
		    		<h3></h3>
		    		<input type='hidden' class="cid" name='cid' value=''>
		    		<input type='hidden' class="allpage" name='allpage' value=''>
		    		<table class="data_list_table user_table">
		    			<thead>
		    				<tr>
		    					<th width="150px">章节名称</th>
		    					<th width="150px">视频名称</th>
		    					<th width="100px">观看状态</th>
		    					<th width="150px">最后观看时间</th>
		    				</tr>
		    			</thead>
		    			<tbody>
		    				
		    			</tbody>
		    		</table>
		    		<div class="mt20 mb0 page" >
			            <ul  class="video_pager_ul">
			               
			            </ul>
			            <div class="clearfix"></div>
			        </div>  
		    	</div>
		    	
	    	</div>
   <script type="text/javascript" src="/public/jqadmin/js/layui/layui.js"></script>
<script>
    layui.config({
        base: '/public/jqadmin/js/',
        version: "1.3.1"
    }).extend({
        elem: 'jqmodules/elem',
        tabmenu: 'jqmodules/tabmenu',
        jqmenu: 'jqmodules/jqmenu',
        ajax: 'jqmodules/ajax',
        dtable: 'jqmodules/dtable',
        jqdate: 'jqmodules/jqdate',
        modal: 'jqmodules/modal',
        tags: 'jqmodules/tags',
        jqform: 'jqmodules/jqform',
        echarts: 'lib/echarts',
        webuploader: 'lib/webuploader'
    })
</script>
	<script src="/public/gzadmin/js/jquery-1.11.0.min.js"></script>
	<script src="/public/gzadmin/js/public_PC.js"></script>

<div class="edit_pswd" style="display: none">
    <form id="form1" class="layui-form layui-form-pane" action="<?php echo url('user/edit_password'); ?>">
        <input type="hidden" name="id" value="<?php echo session('admin_uid'); ?>">
        <div class="layui-form-item">
            <label class="layui-form-label">原密码</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="password" name="passwd" placeholder="密码"  jq-verify="pass" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">新密码</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="password" name="newpasswd" placeholder="新密码"  jq-verify="pass" >
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">确认密码</label>
            <div class="layui-input-inline">
                <input class="layui-input" type="password" name="morepasswd" placeholder="确认密码"  jq-verify="pass" >
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" jq-submit lay-filter="submit">立即提交</button>
            </div>
        </div>
    </form>
</div>

<script>
//点击用户头像显示隐藏菜单
$(".user-self").click(function(e){
	e.stopPropagation();
   var $usersetul = $(this).next(".user-set-ul"),
       display = $usersetul.css("display");
   if("none"==display){
      $usersetul.slideDown();
   }else{
      $usersetul.slideUp();
   }    
});
$('body').on('click', function () {
	if($('.user-set-ul').css('display') == 'block') {
		$(".user-set-ul").slideUp();
	}
})
</script>
   <script type="text/javascript">
   var uid = "<?php echo $uid; ?>";
//         $(function(){
			$("body").on("click",function(){
				$('.bp-filterslide').hide();
			});
			$('.bp-filtershow').on("click",function(e){
				e.stopPropagation();
				$('.bp-filterslide').show();
			});
			$('.bp-filteritem').on("click",function(e){
				var seach_id = $(this).attr('data-select-id');
				var seach_url = "<?php echo url('member/detail'); ?>?uid=<?php echo $uid; ?>&type=<?php echo $type; ?>&seach_id="+seach_id;
				console.log(seach_url);
				window.location.href = seach_url;
			});
        	$(".video_detail").on("click",function(){
        		$('.user_data_list .user_table tbody').html('');
        		$(".user_data_list").css("display","flex");
        		var cid = $(this).attr('data-cid');
        		$('.cid').val(cid);
        		var title = "”"+$(this).attr("data-title")+"“"+'学习详情';
        		$(".data_list_content h3").html(title);
				console.log(cid);
				console.log(uid);
        		page = 1;
        		var nextpage = page+1;
        		var allpage = ' <li data-page="1"><a href="javascript:void(0)">上一页</a></li>'+
        					  '<li data-page="'+nextpage+'"><a href="javascript:void(0)">下一页</a></li>';
        		$(".video_pager_ul").html(allpage);
        		changelist(cid,uid,1);
        	});
        	$(".video_pager_ul").on("click","li",function(){
        		console.log('start');
        		var cid = $('.cid').val();	
                var page = $(this).attr("data-page");
                var allpage =$('.allpage').val();	
                if(page !=0 && page<=allpage){
                	changelist(cid,uid,page);
                	var prepage = parseInt(page)-1;
                	var nextpage = parseInt(page)+1;
                	var allpage = ' <li data-page="'+prepage+'"><a href="javascript:void(0)">上一页</a></li>'+
        			  '<li data-page="'+nextpage+'"><a href="javascript:void(0)">下一页</a></li>';
        			$(".video_pager_ul").html(allpage);
                }
        		
        		
        	})
        	//点击close按钮弹框消失
        	$(".user_data_list").on("click",".data_list_close",function(){
        		$(".user_data_list").hide()
        	})

        	function changelist(cid,uid,page){
        		 var returndata = {};
        			$.get("/admin/member/videodetail?cid="+cid+"&uid="+uid+"&page="+page, function(result){
        				console.log(result);
        				var bodyhtml = '';
        				var data = result.data.lists;
        				for(var i=0;i<data.length;i++){
        					bodyhtml = bodyhtml+'<tr>'+
        					'<td width="150px">'+
        					data[i].cate_name+
        					'</td>'+
        					'<td width="150px" >'+data[i].title+'</td>'+
        					'<td width="100px">'+data[i].status_text+'</td>'+
        					'<td width="150px">'+data[i].update_date+'</td>'+
        					'</tr>';
        					
        				} 
        			   $('.user_data_list .user_table tbody').html(bodyhtml);
        			   var allpage = result.data.allpage;
        			   $('.allpage').val(allpage);
        			  // var pagehtml = '<i>上一页</i><i>下一页</i>';
        			   //$('.page_bt').html(pagehtml);
        			});
        			
        	    }
//         })
    </script>
    </body>
</html>
