<?php if (!defined('THINK_PATH')) exit(); /*a:6:{s:59:"C:\php\zaohui_2.0/application/admin\view\course\create.html";i:1519891268;s:59:"C:\php\zaohui_2.0/application/admin\view\common\header.html";i:1519891268;s:58:"C:\php\zaohui_2.0/application/admin\view\common\admin.html";i:1519891268;s:65:"C:\php\zaohui_2.0/application/admin\view\course\course_basic.html";i:1520905983;s:66:"C:\php\zaohui_2.0/application/admin\view\course\course_finish.html";i:1519891268;s:60:"C:\php\zaohui_2.0/application/admin\view\common\version.html";i:1519891268;}*/ ?>
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
<style type="text/css">
    .mincode_img{
        position: fixed;
        top: 50%;
        left: 50%;
        background-color: #fff;
        width:250px;
        height: 250px;
        -webkit-transform: translateX(-50%) translateY(-50%);
        border:solid 1px #292B3;
        padding: 23px;
    }
    .mincode_img img{

    }
</style>
<body>
<div class="article_manage">
	<div class="right-side-header clearfix">
	        <span>知识管理</span>
	        

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
   <div class="right_side_content">
   		<div class="add_article_main ">	
   			<div class="breadcrumb">
	              <a href='<?php echo url("course/index"); ?>'>课程列表</a>
	              /
	              <span>添加课程</span>
	         </div>
	        <div class="add_article_step">
	            <div class="step-liucheng">
	                <ul class="step-liucheng-ul clearFloat">
	                    <li <?php if($step==1): ?> class="active" <?php endif; ?>><i>1</i>添加课程</li>
	                    <li <?php if($step==2): ?> class="active" <?php endif; ?>><i>2</i>添加视频</li>
	                    <li <?php if($step==3): ?> class="active" <?php endif; ?>><i>3</i>pc介绍</li>
	                    <li <?php if($step==4): ?> class="active" <?php endif; ?>><i>4</i>完成</li>
	                </ul>
	            </div>
	        </div>
	        <div class="articleInfo_fill">
	            <?php if($step==1): ?>
	            <style>
    .layui-disabled, .layui-disabled:hover{
        color: #999 !important
    }
    .layui-layer-page .layui-layer-content{
        overflow: visible !important;
    }
</style>
<script type="text/javascript" charset="utf-8" src="/public/webueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/public/webueditor/ueditor.all.min.js"> </script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script type="text/javascript" charset="utf-8" src="/public/webueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript">

    //实例化编辑器

    //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例

    UE.getEditor('cdcontent',{initialFrameHeight:400});

</script>
<section class="panel panel-padding">
    <form id="form1" class="layui-form layui-form-pane" action="<?php echo url('step_change'); ?>">
        <input type="hidden" name="step" id="step" value=1>
        <input type="hidden" name="cid" id="cid" value=<?php echo $cid; ?>>
        <div class="layui-form-item">
            <label class="layui-form-label">课程名称*</label>
            <div class="layui-input-block">
                <input type="text" name="title" required jq-verify="required" jq-error="请输入课程名称" placeholder="标题字数不超过30个字" value="<?php echo $course['title']; ?>" autocomplete="off" class="layui-input ">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">价格*</label>
            <div class="layui-input-block">
                <input type="text" name="price" required jq-verify="required|price" jq-error="请输入价格" placeholder="￥" autocomplete="off" value="<?php echo $course['price']; ?>" autocomplete="off" class="layui-input ">
            </div>
        </div>
        <!--新增insur_price及-->
        <div class="layui-form-item">
            <label class="layui-form-label">insur价格*</label>
            <div class="layui-input-block">
                <input type="text" name="insur_price" required jq-verify="required|insur_price" jq-error="请输入价格" placeholder="￥" autocomplete="off" value="<?php echo $course['insur_price']; ?>" autocomplete="off" class="layui-input ">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">insur开关*</label>
            <div class="layui-input-inline">
                <select name="insur_switch" jq-verify="required" data-val="1" lay-filter="verify">
                    <option value="1">开启</option>
                    <option value="0">关闭</option>
                </select>
            </div>
        </div>
	    <div class="layui-form-item">
            <label class="layui-form-label">虚拟人数</label>
            <div class="layui-input-block">
                <input type="text" name="virtual_amount" jq-error="请输入虚拟人数" placeholder="为0或为空则显示真实人数" autocomplete="off" value="<?php echo $course['virtual_amount']; ?>" autocomplete="off" class="layui-input ">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">选择分类*</label>
            <div class="layui-input-inline">
                <select name="pid" jq-verify="required" jq-error="请选择分类" lay-filter="verify">
                    <option value=""></option>
                    <?php if(!empty($cate)): if(is_array($cate) || $cate instanceof \think\Collection || $cate instanceof \think\Paginator): if( count($cate)==0 ) : echo "" ;else: foreach($cate as $key=>$vo): ?>
                    <option <?php if($vo['disabled'] == 'disabled'): ?>disabled="disabled"<?php endif; ?> value="<?php echo $vo['id']; ?>" 
                    <?php if($course['pid'] == $vo['id']): ?>selected<?php endif; ?>><?php if($vo['lvl']==2): ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php endif; ?><?php echo $vo['cate_name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </select>
                <div class="upload-info" style="position:absolute;top:8px;color: hsla(228,20%,20%,.6);font-size: 12px;width:200px;left:280px;;"><span style="width: 200px;">没有可选择的分类，去</span>
                    <span class="span_add btn modal-catch" style='margin-right: 0px;float: unset;font-size: 12px;color: #00B6F2;opacity: 1;background-color: white;' data-params='{"content":".add-subcat","act":"<?php echo url("course/add_cate"); ?>", "title":"添加分类","type":"1"}'>添加</span>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">证书图标*</label>
            <div class="layui-input-block">
                <input type="file" name="file" class="layui-upload-file">
                <input type="hidden" value="<?php echo $course['diploma']; ?>" jq-verify="required"  name="diploma" <?php if($course['diploma']): ?>jq-verify="required"<?php endif; ?> jq-error="请上传图片" error-id="smalls-error">
                <p class="upload-info">图片尺寸：120*120 支持格式：JPG PNG</p>
                <p id="smalls-error" class="error" style="margin-left: 300px;"></p>
            </div>
            <?php if($course['face']): ?>
            <div class="layui-input-block">
                <div class="imgbox">
                    <img src="<?php echo $course['diploma']; ?>"  name="diploma" alt="" class="img-thumbnail" style="width:60px;height:60px;">
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">课程图标*</label>
            <div class="layui-input-block">
                <input type="file" name="file" class="layui-upload-file">
                <input type="hidden" value="<?php echo $course['face']; ?>" jq-verify="required"  name="face" <?php if($course['face']): ?>jq-verify="required"<?php endif; ?> jq-error="请上传图片" error-id="small-error">
                <p class="upload-info">图片尺寸：120*120 支持格式：JPG PNG</p>
                <p id="small-error" class="error" style="margin-left: 300px;"></p>
            </div>
            <?php if($course['face']): ?>
            <div class="layui-input-block">
                <div class="imgbox">
                    <img src="<?php echo $course['face']; ?>"  name="face" alt="" class="img-thumbnail" style="width:60px;height:60px;">
                </div>
            </div>
            <?php endif; ?>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">课程主题图*</label>
            <div class="layui-input-block">
                <input type="file" name="file" class="layui-upload-file">
                <input type="hidden" value="<?php echo $course['banner']; ?>" jq-verify="required" name="banner" <?php if($course['banner']): ?>jq-verify="required" <?php endif; ?> jq-error="请上传图片" error-id="big-error">
                <p class="upload-info">图片尺寸：750*420 支持格式：JPG PNG</p>
                <p id="big-error" class="error" style="margin-left: 300px;"></p>
            </div>
            <?php if($course['banner']): ?>
            <div class="layui-input-block">
                <div class="imgbox">
                    <img src="<?php echo $course['banner']; ?>" name="banner" class="img-thumbnail" style="width:60px;height:60px;">
                </div>
            </div>
            <?php endif; ?>
        </div>



        <div class="layui-form-item ">
            <label class="layui-form-label">课程描述*</label>
            <div class="layui-input-block">
                <textarea name="desc" jq-verify="required" placeholder="请输入课程描述" class="layui-textarea"><?php echo $course['desc']; ?></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">课程介绍</label>
            <div class="layui-input-block">
                <textarea name="content"  jq-verify="content" id="cdcontent"><?php echo $course['content']; ?></textarea>
            </div>
        </div>
        <div class="course_right_footer" style="position: fixed;z-index:99999;width: 100%;bottom:0;left:0">
            <div class="layui-form-item" style="margin-bottom: 0;">
                <div class="layui-input-block" style="margin-left:0;">
                    <button class="layui-btn" jq-submit lay-filter="submit">下一步</button>
                </div>
            </div>
        </div>
    </form>
</section>

<div class="add-subcat">
    <form id="form1" class="layui-form layui-form-pane" action='<?php echo url("course/add_cate"); ?>'>
        <div class="layui-form-item">
            <label class="layui-form-label">分类名称</label>
            <div class="layui-input-inline">
                <input type="text" name="cate_name" required jq-verify="required" jq-error="请输入分类名称" placeholder="请输入分类名称" autocomplete="off" class="layui-input ">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">顶级分类</label>
            <div class="layui-input-inline">
                <select name="pid"  jq-error="请输入分类" lay-filter="verify">
                    <option value=""></option>
                    <?php if(!empty($topcate_list)): if(is_array($topcate_list) || $topcate_list instanceof \think\Collection || $topcate_list instanceof \think\Paginator): if( count($topcate_list)==0 ) : echo "" ;else: foreach($topcate_list as $key=>$vo): ?>
                    <option value="<?php echo $vo['id']; ?>"><?php echo $vo['cate_name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-inline">
                <button class="layui-btn" jq-submit jq-filter="submit">确定</button>
            </div>
        </div>
    </form>
</div>
	            <?php elseif($step==4): ?>
	            <div class="right-side-content mt30 ">
    <div class="stepFinish-header">
        <img src="/public/static/merch/images/finish_ok@2x30.png">
        <span>您已成功添加了课程</span>
    </div>
    <div class="stepFinish-body mt30">
        <ul class="addCourse-finish-ul">
            <li class="clearFloat" data-name="course-addr">
                <i>课程地址：</i>
                <div>
                    <i id="href"><?php echo $url; ?></i><span class="copy-addr">复制</span>
                    <p>可复制到公众号的自定义菜单栏等位置</p>
                </div>
            </li>
            <li class="clearFloat" data-name="course-cord">
                <i>课程二维码：</i>
                <div>
                    <img src="<?php echo $qrcode; ?>">
                    <span>使用微信扫一扫预览</span>
                </div>
            </li>
        </ul>
        <div style="clear: both;"></div>
    </div>

    <div class="stepFinish-footer">
        <span class="btn-bg-primary course-manage">课程管理</span>
        <span class="btn-border-primary btn-continue">继续添加课程</span>
    </div>
</div>
</div>
<!-- <input type="button" id="button" name="button" value="复制" onclick="copyContact()"/>  -->
<span id="copy"></span>
</div>
<script src="/public/static/merch/js/jquery-1.11.0.min.js"></script>
<script src="/public/static/merch/js/public-child.window.js"></script>
<script>
    //课程管理
    $(".stepFinish-footer>.course-manage").click(function (e) {
        var src = "<?php echo url('course/index'); ?>";
        $("#iframe", obj.myParent).attr("src", src);
    })
    //继续添加课程
    $(".stepFinish-footer>.btn-continue").click(function (e) {
        var src = "<?php echo url('course/create'); ?>";
        $("#iframe", obj.myParent).attr("src", src);
    })
    // 复制课程地址
    $(".copy-addr").on("click", function () {
        var str = "课程地址复制成功";
        isShowOperateTip(str);
        copyArticle();
    })
    function copyArticle(event) {
        var range = document.createRange();
        range.selectNode(document.getElementById("href"));
        var selection = window.getSelection();
        if (selection.rangeCount > 0) selection.removeAllRanges();
        selection.addRange(range);
        document.execCommand('copy');
    }
</script>    
  
	            <?php endif; ?>
	        </div>
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
<script>
    layui.use('course');
</script>
</body>
</html>