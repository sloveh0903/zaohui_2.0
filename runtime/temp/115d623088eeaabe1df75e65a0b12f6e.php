<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:87:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/customtemplate/index.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;}*/ ?>
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
<script type="text/javascript" charset="utf-8" src="/public/webueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/public/webueditor/ueditor.all.min.js"> </script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script type="text/javascript" charset="utf-8" src="/public/webueditor/lang/zh-cn/zh-cn.js"></script>
<!--<script type="text/javascript">

    //实例化编辑器

    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例

    UE.getEditor('cdcontent',{initialFrameHeight:400,initialFrameWidth:400,toolbars: [
        ['fullscreen', 'source', 'undo', 'redo', 'bold', 'italic']
    ]});
</script>-->
<body>
   <div class="right-side-header clearfix">
    <span>移动首页自定义</span>
    

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
        
    <div class="success_tip displayNone">已完成</div>
  </div>
  <link rel="stylesheet" href="/public/gzadmin/css/all-7.4.0.css">
<!--   <link rel="stylesheet" href="/public/gzadmin/css/jquery.mCustomScrollbar.min.css"> -->
  <link rel="stylesheet" href="/public/gzadmin/css/custom-template.css">
  <script src="/public/gzadmin/js/jquery-1.11.0.min.js"></script>
<!--   <script type="text/javascript" charset="utf-8" src="/public/gzadmin/js/jquery.mCustomScrollbar.concat.min.js"></script> -->
  <script src="/public/gzadmin/js/public_PC.js"></script>
  <script src="/public/gzadmin/js/placeholder.js"></script>
  <script src="/public/gzadmin/js/custom-template.js"></script>
  <script src="/public/static/js/jquery.paging.js"></script>
    
   <div class="right_side_content" style="padding: 40px 0px;">
<!-- 	   <div class="system_guanli_div"> -->
<!-- 		 	<ul class="system_guanli_ul"> -->
<!-- 		 		<li><a href="javascript:void(0)" class="goto active" data-src='<?php echo url("couponcode/index"); ?>'>公众号首页自定义</a></li> -->
<!-- 		     </ul> -->
<!-- 		</div> -->
	    <div class="template-content">
		    <div class="module-panel">
		      <p class="desc">拖动模块</p>
		      <div class="module-item listcontent" draggable="true">
		        <p>课程</p>
		      </div>
		      <div class="module-item two-line-desc search" draggable="true">
		        <p>添加</p>
		        <p>搜索</p>
		      </div>
		      <div class="module-item border" draggable="true">
		        <p>分割线</p>
		      </div>
		      <div class="module-item text" draggable="true">
		        <p>富文本</p>
		      </div>
		      <div class="module-item bundle" draggable="true">
		        <p>套餐</p>
		      </div>
		    </div>
		    <div class="display-panel">
		    </div>
		    <div class="setting-panel" style='display:none;'>
		
		    </div>
  		</div>
		<div class="operation-panel">
		  <i class="btn save-btn">保存</i>
		  <i class="showqr">店铺二维码</i>
		</div>
		  
  </div>
  <div class="dialog">
		    <div class="table-box">
		    <div class="cross-icon"></div>
		      <div class="row">
		        <p>选择课程</p>
		        <div class="select-box">
		          <div class="placeholder-box">选择分类</div>
		          <div class="slide-box">
		          <div class="slide-item course-link" data-id="0">全部</div>
		          <?php if(is_array($top_pid_arr) || $top_pid_arr instanceof \think\Collection || $top_pid_arr instanceof \think\Paginator): $i = 0; $__LIST__ = $top_pid_arr;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
					 <div class="slide-item course-link" data-id="<?php echo $vo['id']; ?>"><?php echo $vo['cate_name']; ?></div>
					<?php endforeach; endif; else: echo "" ;endif; ?>
		          </div>
		          <i class="slide-icon"></i>
		        </div>
		        <div class="search-box" data-id='' id="search-boxid">
		          <input type="text" placeholder="搜索..." id='search-boxtext'>
		          <i class="search-icon"></i>
		        </div>
		      </div>
		      <div class="table-content">
		      <div id='change_banner_html'></div>
		        <div class="row operation-row">
		          <i class="btn confirm-btn">确认</i>
		          <div class="pager">
						<div class="table-pager-list" style="text-align: center">
                
					      <div class="page_div3 paging" onselectstart="return false"
			                   style="font-size: 13px; font-family: 微软雅黑; font-weight: 400; height: 32px; width : auto;">
			              </div>
					      
					      </div>
		          </div>
		        </div>
		      </div>
		      
		    </div>
		    <div class="link-box">
		      <h4>自定义链接</h4>
		      <input type="text" placeholder="填写跳转自定义链接" class="customlink-input">
		      <p class="validate-alert"></p>
		      <div>
		        <i class="btn confirm-btn">确认</i>
		      </div>
		      <div class="cross-icon"></div>
		    </div>
		    <div class="confirm-box">
		      <h4>温馨提示</h4>
		      <p>确认上架该模板吗</p>
		      <div>
		          <i class="btn confirm-btn" id="final-conform-save">确认</i>
		          <i class="cancel" id="final-cancel-save">取消</i>
		      </div>
		      <div class="cross-icon"></div>
		    </div>
		    <div class="ewm-box">
		      <h4>店铺二维码</h4>
		      <img src="<?php echo $qrcode; ?>" alt="qrcode">
		      <p>使用微信扫一扫进入店铺</p>
		      <div class="cross-icon"></div>
		    </div>
		  </div>
</body>
<script>
var drop_id = "<?php echo $drop_id; ?>";
</script>
</html>

