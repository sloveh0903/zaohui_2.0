<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/usercard/index.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;s:81:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/version.html";i:1518064645;}*/ ?>
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
<body>
<div class="article_manage mCustomScrollbar">
    <div class="right-side-header clearfix">
	        <span>VIP卡管理</span>
	        

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

            <div class="system_guanli_div">
                <ul class="system_guanli_ul vip-edit-ul">
                    <li><a href="javascript:void(0)" class="active" data-src="course_list">VIP卡管理</a></li>
                </ul>
            </div>

            <p class="vip-slogan">VIP会员可以观看所有课程视频</p>
            <div class="vip_edit_main">
                <div class="vip_card_box">
                    <?php if($flag['mouth']['msg'] == 'uncreate'): ?>
                    <div class="vip_card_add">
                        <img src="/public/gzadmin/images/add-icon.png" alt="add-icon">
                        <p class="btn modal-catch" data-params='{"content":".add-subcat","act":"<?php echo url("addCard"); ?>", "title":"添加月卡","type":"1","data":"type=mouth"}'>添加月卡</p>
                    </div>
                    <?php else: ?>
                    <div class="vip_card_item <?php if($flag['mouth']['msg'] == 'closed'): ?>withdrawed<?php else: ?>grounded<?php endif; ?>">
                        <p class="price">￥<?php echo $flag['mouth']['price']; ?></p>
                        <div class="desc-box">
                            <p class="name">月卡</p>
                            <p class="info">一个月内可观看所有课程</p>
                            <p class="withdraw-info"><span>月卡</span>&nbsp;&nbsp;已下架</p>
                        </div>
                        <div class="mask">
                            <div class="option-box modal-catch i_edit" data-params='{"content": ".edit-subcat","act":"<?php echo url("editCard"); ?>","title":"编辑月卡","data":"id=<?php echo $flag['mouth']['id']; ?>&price=<?php echo $flag['mouth']['price']; ?>","type":"1"}'>
                                <i class="editing"></i>
                                <p>编辑</p>
                            </div>
                            <?php if($flag['mouth']['msg'] == 'closed'): ?>
                            <div class="option-box ajax" data-params='{"url": "<?php echo url("setOpen"); ?>","data":"id=<?php echo $flag['mouth']['id']; ?>","confirm":"true"}'>
                                <i class="withdraw"></i>
                                <p>上架</p>
                            </div>
                            <?php else: ?>
                            <div class="option-box ajax" data-params='{"url": "<?php echo url("setClosed"); ?>","data":"id=<?php echo $flag['mouth']['id']; ?>","confirm":"true"}'>
                                <i class="withdraw"></i>
                                <p>下架</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="vip_card_box">
                    <?php if($flag['season']['msg'] == 'uncreate'): ?>
                    <div class="vip_card_add">
                        <img src="/public/gzadmin/images/add-icon.png" alt="add-icon">
                        <p class="btn modal-catch" data-params='{"content":".add-subcat","act":"<?php echo url("addCard"); ?>", "title":"添加季卡","type":"1","data":"type=season"}'>添加季卡</p>
                    </div>
                    <?php else: ?>
                    <div class="vip_card_item <?php if($flag['season']['msg'] == 'closed'): ?>withdrawed<?php else: ?>grounded<?php endif; ?>">
                        <p class="price">￥<?php echo $flag['season']['price']; ?></p>
                        <div class="desc-box">
                            <p class="name">季卡</p>
                            <p class="info">三个月内可观看所有课程</p>
                            <p class="withdraw-info"><span>季卡</span>&nbsp;&nbsp;已下架</p>
                        </div>
                        <div class="mask">
                            <div class="option-box modal-catch i_edit"  data-params='{"content": ".edit-subcat","act":"<?php echo url("editCard"); ?>","title":"编辑季卡","data":"id=<?php echo $flag['season']['id']; ?>&price=<?php echo $flag['season']['price']; ?>","type":"1"}'>
                                <i class="editing"></i>
                                <p>编辑</p>
                            </div>
                            <?php if($flag['season']['msg'] == 'closed'): ?>
                            <div class="option-box ajax" data-params='{"url": "<?php echo url("setOpen"); ?>","data":"id=<?php echo $flag['season']['id']; ?>","confirm":"true"}'>
                                <i class="withdraw"></i>
                                <p>上架</p>
                            </div>
                            <?php else: ?>
                            <div class="option-box ajax" data-params='{"url": "<?php echo url("setClosed"); ?>","data":"id=<?php echo $flag['season']['id']; ?>","confirm":"true"}'>
                                <i class="withdraw"></i>
                                <p>下架</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="vip_card_box">
                    <?php if($flag['year']['msg'] == 'uncreate'): ?>
                    <div class="vip_card_add">
                        <img src="/public/gzadmin/images/add-icon.png" alt="add-icon">
                        <p class="btn modal-catch" data-params='{"content":".add-subcat","act":"<?php echo url("addCard"); ?>", "title":"添加年卡","type":"1","data":"type=year"}'>添加年卡</p>
                    </div>
                    <?php else: ?>
                    <div class="vip_card_item <?php if($flag['year']['msg'] == 'closed'): ?>withdrawed<?php else: ?>grounded<?php endif; ?>">
                        <p class="price">￥<?php echo $flag['year']['price']; ?></p>
                        <div class="desc-box">
                            <p class="name">年卡</p>
                            <p class="info">一年内可观看所有课程</p>
                            <p class="withdraw-info"><span>年卡</span>&nbsp;&nbsp;已下架</p>
                        </div>
                        <div class="mask">
                            <div class="option-box modal-catch i_edit" data-params='{"content": ".edit-subcat","act":"<?php echo url("editCard"); ?>","title":"编辑年卡","data":"id=<?php echo $flag['year']['id']; ?>&price=<?php echo $flag['year']['price']; ?>","type":"1"}'>
                                <i class="editing"></i>
                                <p>编辑</p>
                            </div>
                            <?php if($flag['year']['msg'] == 'closed'): ?>
                            <div class="option-box ajax" data-params='{"url": "<?php echo url("setOpen"); ?>","data":"id=<?php echo $flag['year']['id']; ?>","confirm":"true"}'>
                                <i class="withdraw"></i>
                                <p>上架</p>
                            </div>
                            <?php else: ?>
                            <div class="option-box ajax" data-params='{"url": "<?php echo url("setClosed"); ?>","data":"id=<?php echo $flag['year']['id']; ?>","confirm":"true"}'>
                                <i class="withdraw"></i>
                                <p>下架</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="vip_card_box">
                    <?php if($flag['life']['msg'] == 'uncreate'): ?>
                    <div class="vip_card_add">
                        <img src="/public/gzadmin/images/add-icon.png" alt="add-icon">
                        <p class="btn modal-catch" data-params='{"content":".add-subcat","act":"<?php echo url("addCard"); ?>", "title":"添加终身卡","type":"1","data":"type=life"}'>添加终身卡</p>
                    </div>
                    <?php else: ?>
                    <div class="vip_card_item <?php if($flag['life']['msg'] == 'closed'): ?>withdrawed<?php else: ?>grounded<?php endif; ?>">
                        <p class="price">￥<?php echo $flag['life']['price']; ?></p>
                        <div class="desc-box">
                            <p class="name">终身卡</p>
                            <p class="info">可永久观看所有课程</p>
                            <p class="withdraw-info"><span>终身卡</span>&nbsp;&nbsp;已下架</p>
                        </div>
                        <div class="mask">
                            <div class="option-box modal-catch i_edit" data-params='{"content": ".edit-subcat","act":"<?php echo url("editCard"); ?>","title":"编辑终身卡","data":"id=<?php echo $flag['life']['id']; ?>&price=<?php echo $flag['life']['price']; ?>","type":"1"}'>
                                <i class="editing"></i>
                                <p>编辑</p>
                            </div>
                            <?php if($flag['life']['msg'] == 'closed'): ?>
                            <div class="option-box ajax" data-params='{"url": "<?php echo url("setOpen"); ?>","data":"id=<?php echo $flag['life']['id']; ?>","confirm":"true"}'>
                                <i class="withdraw"></i>
                                <p>上架</p>
                            </div>
                            <?php else: ?>
                            <div class="option-box ajax" data-params='{"url": "<?php echo url("setClosed"); ?>","data":"id=<?php echo $flag['life']['id']; ?>","confirm":"true"}'>
                                <i class="withdraw"></i>
                                <p>下架</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="vip_desc_box">
                <p>会员卡说明 *</p>
                <form id="form1" class="layui-form layui-form-pane" action='<?php echo url("addExplain"); ?>'>
                <div class="text-area">
                    <textarea name="explain" class="layui-textarea" cols="30" rows="10"><?php echo $explain; ?></textarea>
                    <div class="submit-btn layui-btn" jq-submit jq-filter="submit">立即提交</div>
                </div>
                </form>
            </div>
	 </div>
</div>
<div class="add-subcat">
    <form id="form1" class="layui-form layui-form-pane" action='<?php echo url("addCard"); ?>'>
        <div class="layui-form-item">
            <label class="layui-form-label">价格</label>
            <div class="layui-input-inline">
                <input type="hidden" name="type">
                <input type="text" name="price" required jq-verify="required|price" jq-error="请输入会员卡价格" placeholder="请输入会员卡价格" autocomplete="off" class="layui-input ">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" jq-submit jq-filter="submit">确定</button>
            </div>
        </div>
    </form>
</div>
<div class="edit-subcat" style="display: none">
    <form id="form2" class="layui-form layui-form-pane" action='<?php echo url("editCard"); ?>'>
        <div class="layui-form-item">
            <label class="layui-form-label">价格</label>
            <div class="layui-input-inline">
                <input type="hidden" name="id">
                <input type="text" name="price" required jq-verify="required|price" jq-error="请输入会员卡价格" placeholder="请输入会员卡价格" autocomplete="off" class="layui-input ">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" jq-submit jq-filter="submit">确定</button>
            </div>
        </div>
    </form>
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
    layui.use('myform');
</script>
</body>
</html>