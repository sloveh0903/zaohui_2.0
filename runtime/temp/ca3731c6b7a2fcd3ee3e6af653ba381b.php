<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:59:"C:\php\zaohui_2.0/application/admin\view\chapter\index.html";i:1519891268;s:59:"C:\php\zaohui_2.0/application/admin\view\common\header.html";i:1519891268;s:58:"C:\php\zaohui_2.0/application/admin\view\common\admin.html";i:1519891268;s:60:"C:\php\zaohui_2.0/application/admin\view\common\version.html";i:1519891268;}*/ ?>
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
<link rel="stylesheet" href="/public/qiniu/dist/video.css">
<style>
    .float_div1{
        right: 30px;top:40px;    position: absolute;
        z-index: 9;
        width: 320px;
        height: 80px;
        display: none;
    }
    .float_div1.float_div_right{left: -275px;}
    .float_div1.float_div_right .float_box span{left: 290px;}
    .float_div1 .float_box {position: relative;width: 320px;}
    .float_div1 .float_box span{position: absolute;top:-17px;right: 20px; display: block;width:18px ;height:18px ;background: url(/public/gzadmin/images/float_arrow@2x.png) no-repeat center; background-position-y: 7px;}
    .float_div1 .float_box p, .float_last_div .float_box p{margin-top: 10px; width:320px;border: 1px solid rgba(41, 43, 51, .1);background: #FFF9E4;border-radius: 5px;padding: 15px;font-size:14px;color:rgba(41, 43, 51,.6);clear: both;}
</style>
<body>
<div class="article_manage right-side-main">
    <div class="right-side-header clearfix">
	        <span>课程管理</span>
	        

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
    <div class="right_side_content ">
        <?php if($type == "create"): ?>
        <div class="add_article_step">
        	<div class="breadcrumb">
              <a href='<?php echo url("course/index"); ?>'>课程列表</a>
              /
              <span>添加课程</span>
            </div>
            <div class="step-liucheng">
                <ul class="step-liucheng-ul clearFloat">
                    <li ><i>1</i>课程简介</li>
                    <li class="active"><i>2</i>课程视频</li>
                    <li ><i>3</i>pc版介绍</li>
                    <li><i>4</i>完成</li>
                </ul>
            </div>
        </div>
        <?php else: ?>
	    <div class="add_article_step">
            <ul class="editCourse-nav-ul clearFloat" style="margin-top: 20px;">
                <li data-src='<?php echo url("course/edit_course",["cid"=>$cid]); ?>' class="goto"><i data-name="edit_course">课程简介</i></li>
                <li class="goto active" data-src='<?php echo url("chapter/index",["cid"=>$cid,"type"=>"edit"]); ?>'><i data-name="edit_course_video">课程视频</i></li>
                <li class="goto" data-src='<?php echo url("course/introduce",["cid"=>$cid,"type"=>"update"]); ?>'><i data-name="edit_pc_production">pc版介绍</i></li>
            </ul>

        </div>
        <?php endif; ?>
        <div class="right-side-content mt20 ">
            <div class="addVideo-body mt30 mb15">
                <div class="addVideo-header">
                    <span class="jianyi" style="font-size: 14px;color: rgba(41,43,51,.4);float: right;line-height:48px;height: 33px;"><font color="red">上传视频建议</font> <a href="http://www.grazy.cn/help?i=210" target="_blank"><img src="/public/gzadmin/images/videotag.png" width="20" height="20"></a> </span>
                    <div class="float_div1">
                        <div class="float_box">
                            <span></span>
                            <p><strong>上传视频建议</strong><br><br>
                                1.上传视频格式建议为MP4，其他格式会增加转码费；<br>
                                2.视频大小建议不超过1G，视频越大，流量费等费用会更高；<br>
                                3.视频导出时编码格式请选择AVC(H264)；<br>
                                4.视频导出时建议更改比特率/码率，比特率/码率不要太高，太高会影响视频观看的流畅度，访问端网速不好的情况下视频会出现卡顿的效果；
                            </p>
                        </div>
                    </div>
                    <span class="btn-border-primary btn-addChapter">新增章节</span>
                </div>
                <div class="addVideo-content mt20">
                    <ul class="chapter-ul">
                        <?php foreach($chapter as $item): ?>
                        <li class="chapter">
                            <div class="chapter-heading">
                                <h5><?php echo $item['cate_name']; ?></h5>
                                <div class="chapter-curr-operate">
                                    <img class="chapter-up" data-id="<?php echo $item['id']; ?>"
                                         src="/public/static/merch/images/chapter_up@2x20.png">
                                    <img class="chapter-down" data-id="<?php echo $item['id']; ?>"
                                         src="/public/static/merch/images/chapter_down@2x20.png">
                                    <span class="upload-video" id="pupload<?php echo $item['id']; ?>" data-id="<?php echo $item['id']; ?>">上传视频</span>
                                    <span class="edit-chapter" data-id="<?php echo $item['id']; ?>" data-title="<?php echo $item['cate_name']; ?>">编辑</span>
                                    <span class="delete-chapter" data-id="<?php echo $item['id']; ?>">删除</span>
                                </div>
                            </div>
                            <ul class="video-ul">

                                <?php foreach($item['video'] as $v): if(!$v){ continue; } ?>
                                <li class="video">
                                    <div class="video-heading">
                                        <h5 <?php if($v['audit'] == 0): ?>class="hidden-title"<?php endif; ?>><?php echo $v['title']; ?></h5>
                                        <div class="video-curr-operate">
                                            <?php if($v['free']): ?>
                                            <i data-status="on" class="setfree active" data-id="<?php echo $v['id']; ?>"></i>
                                            <?php else: ?>
                                            <i data-status="off" class="setfree" data-id="<?php echo $v['id']; ?>"></i>
                                            <?php endif; ?>
                                            <font style="font-size: 14px;color:rgba(41,43,51,.3);;margin:0 6px 0 10px">试看</font>
                                            <img class="video-up" data-id="<?php echo $v['id']; ?>"
                                                 src="/public/static/merch/images/video_up@2x20.png">
                                            <img class="video-down" data-id="<?php echo $v['id']; ?>"
                                                 src="/public/static/merch/images/video_down@2x20.png">
                                            <span class="hidden-video" data-audit="<?php echo $v['audit']; ?>" data-id="<?php echo $v['id']; ?>"><?php if($v['audit'] == 0): ?>显示<?php else: ?>隐藏<?php endif; ?></span>
                                            <span class="move-video" data-id="<?php echo $v['id']; ?>">移动</span>
                                            <span class="edit-video" data-id="<?php echo $v['id']; ?>" data-title="<?php echo $v['title']; ?>">编辑</span>
                                            <span class="delete-video" data-id="<?php echo $v['id']; ?>">删除</span>
                                        </div>
                                    </div>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                 <?php if($type == "create"): ?>
                <div class="course_right_footer" style="position: fixed;width: 100%;bottom:0;left:0;z-index:2;">
                    <form id="form1" class="layui-form layui-form-pane" action="<?php echo url('course/step_change'); ?>" method='post'>
                        <input type="hidden" name="step" id ="step" value=2>
                        <input type="hidden" name="cid" id ="cid" value=<?php echo $id; ?>>

                        <div class="layui-form-item" style="margin-bottom: 0;">
                            <div class="layui-input-block" style="margin-left:0;">
                                <a href='<?php echo url("course/create"); ?>?cid=<?php echo $id; ?>&step=1' style="line-height: 30px;font-size: 14px;color:#00B6F2;margin-right: 20px;">上一步</a>
                                <button class="layui-btn" jq-submit lay-filter="submit">下一步</button>
                            </div>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="modal-bms course-move-modal" style="display: none;left: 0;top:0">
        <div class="modal-dialog-bms">
            <div class="modal-header">
                <h5>课程移动</h5>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body mt20">
                <select name="chapter_id">
                    <option value="0">请选择章节</option>
                    <?php foreach($chapter as $item): ?>
                    <option value="<?php echo $item['id']; ?>"><?php echo $item['cate_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="modal-footer mt40">
                <span class="btn-primary">保存</span>
            </div>
        </div>
    </div>

    <!-- 新增章节 -->
    <div class="modal-bms add-chapter-modal" style="top:0;left: 0;">
        <div class="modal-dialog-bms">
            <div class="modal-header">
                <h5>新增章节</h5>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body mt20">
                <input class="add-chapter-input add-charset-title" type="text" name="" placeholder="请输入章节名称">
            </div>
            <div class="modal-footer mt40">
                <span class="btn-primary">保存</span>
            </div>
        </div>
    </div>
    <input type="hidden" id="domain" value="<?php echo $domain; ?>">
    <input type="hidden" id="uptoken_url" value="<?php echo $token; ?>">

    <div class="modal-bms chapter-delete-confirm-modal" >
        <div class="modal-dialog-bms">
            <div class="modal-header">
                <h5>删除提示</h5>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body mt20">
                <p class="course-delete-text">是否确认删除?</p>
            </div>
            <div class="modal-footer mt40">
                <span class="btn-danger">确定</span>
            </div>
        </div>
    </div>
    <!-- 课程管理删除提示1 -->
    <div class="modal-bms course-delete-modal" >
        <div class="modal-dialog-bms">
            <div class="modal-header">
                <h5>删除提示</h5>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body mt20">
                <p class="course-delete-text">该章节内含有视频，请先清除视频后<br>再行删除。</p>
            </div>
            <div class="modal-footer mt40">
                <span class="btn-primary">确定</span>
            </div>
        </div>
    </div>
    <!-- 课程管理删除提示2 -->
    <div class="modal-bms course-delete-confirm-modal" >
        <div class="modal-dialog-bms">
            <div class="modal-header">
                <h5>删除提示</h5>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body mt20">
                <p class="course-delete-text">是否确认删除?</p>
            </div>
            <div class="modal-footer mt40">
                <span class="btn-danger">确定</span>
            </div>
        </div>
    </div>

    <!-- 编辑章节 -->
    <div class="modal-bms edit-chapter-modal" >
        <div class="modal-dialog-bms">
            <div class="modal-header">
                <h5>章节名称编辑</h5>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body mt20">
                <input class="add-chapter-input" type="text" name=""  placeholder="请输入章节名称">
            </div>
            <div class="modal-footer mt40">
                <span class="btn-primary">保存</span>
            </div>
        </div>
    </div>
    <!-- 编辑视频 -->
    <div class="modal-bms edit-video-modal" >
        <div class="modal-dialog-bms">
            <div class="modal-header">
                <h5>视频名称编辑</h5>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body mt20">
                <input class="add-chapter-input" type="text" name="" value="">
            </div>
            <div class="modal-footer mt40">
                <span class="btn-primary">保存</span>
            </div>
        </div>
    </div>
    <div class="success_tip"></div>
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
<script type="text/javascript" src="/public/qiniu/dist/moxie.js"></script>
<script type="text/javascript" src="/public/qiniu/dist/plupload.dev.js"></script>
<!-- <script type="text/javascript" src="bower_components/plupload/js/plupload.full.min.js"></script> -->
<script type="text/javascript" src="/public/qiniu/dist/zh_CN.js"></script>
<script type="text/javascript" src="/public/static/js/base64.js"></script>
<script type="text/javascript" src="/public/qiniu/dist/ui.js"></script>
<script type="text/javascript" src="/public/qiniu/dist/qiniu.js"></script>
<script type="text/javascript" src="/public/qiniu/dist/highlight.js"></script>
<script type="text/javascript">hljs.initHighlightingOnLoad();</script>
<script>
    function isShowOperateTip(str){
        $(".success_tip").html(str).show().delay(1500).hide(0);
    }
    var cid = '<?php echo $id; ?>';
    $(".addVideo-header").on("mouseover",".jianyi", function(){
        $(".float_div1").show()
    })
    $(".addVideo-header").on("mouseleave",".jianyi", function(){
        $(".float_div1").hide()
    })
    //上一步
    $(".prev-step").click(function () {
        var src = "<?php echo url('course/create'); ?>";
        $("#iframe", obj.myParent).attr("src", src);
    })
    //新增章节
    $(".btn-addChapter").click(function () {
        $(".add-chapter-modal").css('display','flex');
    });
    $(".chapter-ul .video-ul").on("click",".setfree",function(){
        var status = $(this).attr("data-status");
        var that = $(this);
        var id = $(this).attr("data-id");
        var free = 0;
        if( "off" == status ){
            free = 1;
        }else if("on"==status){
            free = 0;
        }
        $.ajax({
            url:"<?php echo url('/admin/video/set_free'); ?>",
            type:"post",
            data:{
                id: id,
                free:free
            },
            success: function (data) {
                if(data.status == "200"){
                    if( 1 == free ){
                        that.addClass("active");
                        that.attr("data-status","on");
                    }else if("on"==status){
                        that.removeClass("active");
                        that.attr("data-status","off");
                    }
                }
            }
        });

    });

    //新增章节确认
    // $(".add-chapter-modal .btn-primary",obj.myParent).on("click",function(){

    //    $(".add-chapter-modal",obj.myParent).hide();
    //    //...
    // })
    //章节删除提示1
    $(".delete-chapter").on("click", function () {
        //判断章节内是否有视频来选择打开对话框

        var id = $(this).attr('data-id');

        $.ajax({
            url:"<?php echo url('/admin/chapter/checkVideo'); ?>",
            type:"post",
            data:{id: id},
            success: function (data) {
                if(data.data.flag){
                    $('.course-delete-modal').css('display','flex');
                }else{
                    $(".chapter-delete-confirm-modal .btn-danger").attr('data-id', id);
                    $(".chapter-delete-confirm-modal").css('display','flex');
                	//alert(data.message);
                }
            }
        });
    });

    $(".chapter-delete-confirm-modal").on("click",".btn-danger", function () {
        //判断章节内是否有视频来选择打开对话框

        var id = $(this).attr('data-id');
        $.ajax({
            url:"<?php echo url('/admin/chapter/delete'); ?>",
            type:"post",
            data:{id: id},
            success: function (data) {
                if(data.code == 1){
                    window.location.reload();
                }else{
                    $('.course-delete-modal').css('display','flex');
                    //alert(data.message);
                }
            }
        });
    });


    $('.course-delete-modal').on('click','.btn-primary',function () {
        $('.modal-bms').hide();
    })
    //删除视频
    $(".right-side-main").on('click','.delete-video',function () {
        var id = $(this).attr('data-id');
        $(".course-delete-confirm-modal .btn-danger").attr('data-id', id);
        $(".course-delete-confirm-modal").css('display','flex');
    });
    //删除视频确认
    $(".course-delete-confirm-modal").on("click",".btn-danger", function () {
        var id = $(this).attr('data-id');
        $(".course-delete-confirm-modal").hide();
        $.ajax({
            url:"<?php echo url('/admin/video/delete'); ?>",
            type:"post",
            data:{id: id},
            success: function (data) {
                if(data.code == 1){
                    window.location.reload();
                }
            }
        });
    })
    //编辑章节
    $(".right-side-main").on("click",".edit-chapter", function () {
        $(".edit-chapter-modal").css('display','flex');
        var title = $(this).attr('data-title');
        var id = $(this).attr('data-id');
        $('.edit-chapter-modal .add-chapter-input').val(title);
        $('.edit-chapter-modal .add-chapter-input').attr('data-id', id);
    })
    //编辑章节确认
    $(".edit-chapter-modal .btn-primary").on("click", function () {
        $(".edit-chapter-modal").hide();
        //...
    })
    //编辑视频
    $(".right-side-main").on("click",".edit-video", function () {
        var id = $(this).attr('data-id');
        var title = $(this).attr('data-title');
        $(".edit-video-modal .add-chapter-input").val(title);
        $(".edit-video-modal .add-chapter-input").attr('data-id', id);
        $(".edit-video-modal").css('display','flex');
    })
    //编辑章节确认
    $(".edit-video-modal .btn-primary").on("click", function () {
        $(".edit-video-modal").hide();
        var title = $('.edit-video-modal .add-chapter-input').val();
        if (title == '') {
            isShowOperateTip('请输入视频名称');
            return false;
        }
        var dataid = $('.edit-video-modal .add-chapter-input').attr('data-id');
        var param = {
            title: title,
            id: dataid
        }
        $.ajax({
            url:"<?php echo url('/admin/video/update'); ?>",
            type:"post",
            data:param,
            success: function (data) {
                if(data.code == 1){
                    window.location.reload();
                }
            }
        });
        //...
    })
    //保存
    $(".addVideo-footer>.btn-success").on('click',function () {
        var src = "<?php echo url('course/stepfinish',['id'=>$id]); ?>";
        $("#iframe", obj.myParent).attr("src", src);
    })
    //保存并上架
    $(".addVideo-footer>.btn-info").on('click',function () {
    	 window.location.reload();
//         $.ajax({
//             url:"<?php echo url('/api/course/update'); ?>",
//             type:"post",
//             data:{id: id, audit: 1},
//             success: function (data) {
//                 if(data.code == 1){
//                     window.location.reload();
//                 }
//             }
//         });
    })

    $(".right-side-main").on('click','.hidden-video',function () {
        var id = $(this).attr('data-id');
        var audit = $(this).attr('data-audit');
        if(audit == '0'){
            $(this).html('隐藏');
            $(this).attr('data-audit','1');
            $(this).parent().prev().removeClass('hidden-title');
            setaudit = 1;
        }else{
            $(this).html('显示');
            $(this).attr('data-audit','0');
            $(this).parent().prev().addClass('hidden-title');
            setaudit = 0;
        }
        var param = {
            id: id,
            audit: setaudit
        };
        $.ajax({
            url:"<?php echo url('/admin/video/setAudit'); ?>",
            type:"get",
            data:param,
            success: function (data) {
                //layer.msg(data.msg);
                isShowOperateTip(data.msg);
            }
        });
    })

    //移动章节
    $(".right-side-main").on('click','.move-video',function () {
        var id = $(this).attr('data-id');
        $('.course-move-modal .btn-primary').attr('data-id', id);
        $('.course-move-modal').css('display','flex');;
    })

    $('.course-move-modal .btn-primary').on('click',function () {
        var id = $('.course-move-modal .btn-primary').attr('data-id');
        //var cid = $(this).attr('data-cid');
        var tid = $('.course-move-modal select').val();
        if (tid == 0) {
            isShowOperateTip('请选择章节');
        } else {
            var param = {
                id: id,
                tid: tid,
            }
            $.ajax({
                url:"<?php echo url('/admin/video/update'); ?>",
                type:"post",
                data:param,
                success: function (data) {
                    if(data.code == 1){
                        window.location.reload();
                    }
                }
            });
        }
    })

    $('.close').on('click',function () {
        $('.modal-bms').hide();
    })

    //添加章节
    $('.add-chapter-modal .btn-primary').on('click',function () {
        var title = $('.add-charset-title').val();
        var param = {
            title: title,
            cid: cid,
        }
        $('.add-chapter-modal .charset-title').val('');
        $.ajax({
            url:"<?php echo url('/admin/chapter/create'); ?>",
            type:"post",
            data:param,
            success: function (data) {
                if(data.code == 1){
                    window.location.reload();
                }
            }
        });
    })

    //编辑章节
    $('.edit-chapter-modal .btn-primary').click(function () {
        var title = $('.edit-chapter-modal .add-chapter-input').val();
        if (title == '') {
            isShowOperateTip('请输入章节名称');
            return false;
        }
        var dataid = $('.edit-chapter-modal .add-chapter-input').attr('data-id');
        var param = {
            title: title,
            id: dataid
        };
        $.ajax({
            url:"<?php echo url('/admin/chapter/update'); ?>",
            type:"post",
            data:param,
            success: function (data) {
                if(data.code == 1){
                    window.location.reload();
                }
            }
        });
    })

    //章节上移排序
    $(".right-side-main").on('click','.chapter-up',function () {
        var id = $(this).attr('data-id');
        var that = $(this);
        var updown = 'up';
        $.ajax({
            url:"<?php echo url('/admin/chapter/moveUpDown'); ?>",
            type:"post",
            data:{cid:cid,id: id, updown: updown},
            success: function (data) {
                if(data.code == 1){
                    setUp(that,'chapter');
                    //window.location.reload();
                }
            }
        });
    })

    //章节下移排序
    $(".right-side-main").on('click','.chapter-down',function () {
        var id = $(this).attr('data-id');
        var updown = 'down';
        var that = $(this);
        $.ajax({
            url:"<?php echo url('/admin/chapter/moveUpDown'); ?>",
            type:"post",
            data:{cid:cid,id: id, updown: updown},
            success: function (data) {
                if(data.code == 1){
                    setDown(that,'chapter');
                    //window.location.reload();
                }
            }
        });
    })
    function setUp(t,bar) {
        if($(t).parents('.'+ bar).prev('.'+bar).html() != undefined){
            var obj = $(t).parents('.'+bar).clone(true);
            $(t).parents('.'+bar).prev().before(obj);
            $(t).parents('.'+bar).remove();
        }else{
            isShowOperateTip('亲，现在已是最上了哦');
        }

    }
    function setDown(t,bar) {
        if($(t).parents('.'+bar).next('.'+bar).html() != undefined){
            var obj = $(t).parents('.'+bar).clone(true);
            $(t).parents('.'+bar).next().after(obj);
            $(t).parents('.'+bar).remove();
        }else{
            isShowOperateTip('亲，现在已是最下了哦');
        }

    }

    //视频上移排序
    $(".right-side-main").on('click','.video-up',function () {
        var id = $(this).attr('data-id');
        var that = $(this);
        //var cid = $(this).attr('data-cid');
        var updown = 'up';
        $.ajax({
            url:"<?php echo url('/admin/video/moveUpDown'); ?>",
            type:"post",
            data:{cid:cid,id: id, updown: updown},
            success: function (data) {
                setUp(that,'video');
                //window.location.reload();
            }
        });

    })

    //视频下移排序
    $(".right-side-main").on('click','.video-down',function () {
        var id = $(this).attr('data-id');
        var that = $(this);
        //var cid = $(this).attr('data-cid');
        var updown = 'down';
        $.ajax({
            url:"<?php echo url('/admin/video/moveUpDown'); ?>",
            type:"post",
            data:{cid:cid,id: id, updown: updown},
            success: function (data) {
                if(data.code == 1){
                    setDown(that,'video');
                    //window.location.reload();
                }
            }
        });
    });
</script>
<script>
    function getfilename(upFileName) {
        var index1 = upFileName.lastIndexOf(".");
        var index2 = upFileName.length;
        var suffix = upFileName.substring(index1 + 1, index2);//后缀名
        var suffix2 = upFileName.substring(0, index1);//前缀名
        person = new Object();
        person.firstname = suffix2;
        person.lastname = suffix;
        return person;
    }

    $(function () {
        var chapter_id = 5;
        $(".upload-video").each(function() {
            self = $(this);
            fsUploadProgress = self.parent().parent().next('.video-ul');
            var uploader = Qiniu.uploader({
                disable_statistics_report: false,
                runtimes: 'html5,flash,html4',
                /*browse_button: 'pickfiles',*/
                browse_button: self.attr('id'),
                /*                container: 'container',
                 drop_element: 'container',
                max_file_size: '1024mb',*/
                flash_swf_url: 'bower_components/plupload/js/Moxie.swf',
                dragdrop: true,
                chunk_size: '4mb',
                multi_selection: !(moxie.core.utils.Env.OS.toLowerCase() === "ios"),
                uptoken: $('#uptoken_url').val(),
                filters: {
                    prevent_duplicates: false,
                    mime_types: [
                        {title: "Video files", extensions: "avi,mp4,wmv,mpg,mov,flv,mkv,mpeg"}
                    ]
                },
                domain: $('#domain').val(),
                get_new_uptoken: false,
                auto_start: true,
                log_level: 5,
                unique_names: true,
                init: {
                        'BeforeChunkUpload': function(up, file) {
                            console.log("before chunk upload:", file.name);
                        },
                        'FilesAdded': function(up, files) {
                            var fsUploadProgress = $('#'+up.getOption('browse_button')['0'].id).parent().parent().next('.video-ul');
                            plupload.each(files, function (file) {
                                var progress = new FileProgress(file,
                                    fsUploadProgress);
                                progress.setStatus("等待...");
                                progress.bindUploadCancel(up);
                            });
                        },
                        'BeforeUpload': function(up, file) {
                        },
                        'UploadProgress': function(up, file) {
                            var fsUploadProgress = $('#'+this.getOption('browse_button')['0'].id).parent().parent().next('.video-ul');
                            var progress = new FileProgress(file, fsUploadProgress);
                            var chunk_size = plupload.parseSize(this.getOption(
                                'chunk_size'));
                            progress.setProgress(file.percent + "%", file.speed,
                                chunk_size);
                        },
                        'UploadComplete': function() {
                            $('#success').show();
                        },
                        'FileUploaded': function(up, file, info) {

                            var chapter_id = $('#'+up.getOption('browse_button')['0'].id).attr('data-id');
                            var fsUploadProgress = $('#'+up.getOption('browse_button')['0'].id).parent().parent().next('.video-ul');
                            var progress = new FileProgress(file, fsUploadProgress);
                            var domain = up.getOption('domain');
                            info = eval(info);
                            var res = info.response;
                            var json = eval('(' + res + ')');
                            var sourceLink = domain + '/'+json.key; //获取上传成功后的文件的Url
                            var filename = getfilename(file.name);
                            var title = filename.firstname;
                            var data = {
                                'domain':domain,
                                'key': json.key,
                                'title': title,
                                'video_path': sourceLink,
                                'chapter_id': chapter_id, //章节id
                                'course_id': '<?php echo $id; ?>' //课程id
                            };
                            $.ajax({
                                url:"<?php echo url('/admin/video/create'); ?>",
                                type:"post",
                                data:data,
                                success: function (data) {
                                    if(data.code == 1){
                                        video_data = data.data;
                                        progress.setComplete(up, info.response,video_data);
                                    }
                                }
                            });


                        },
                        'Error': function(up, err, errTip) {
                            $('table').show();
                            var progress = new FileProgress(err.file, 'fsUploadProgress');
                            progress.setError();
                            progress.setStatus(errTip);
                        }
                    }

            });
        });

        var getRotate = function (url) {
            if (!url) {
                return 0;
            }
            var arr = url.split('/');
            for (var i = 0, len = arr.length; i < len; i++) {
                if (arr[i] === 'rotate') {
                    return parseInt(arr[i + 1], 10);
                }
            }
            return 0;
        };

        $('#myModal-img .modal-body-footer').find('a').on('click', function () {
            var img = $('#myModal-img').find('.modal-body img');
            var key = img.data('key');
            var oldUrl = img.attr('src');
            var originHeight = parseInt(img.data('h'), 10);
            var fopArr = [];
            var rotate = getRotate(oldUrl);
            if (!$(this).hasClass('no-disable-click')) {
                $(this).addClass('disabled').siblings().removeClass('disabled');
                if ($(this).data('imagemogr') !== 'no-rotate') {
                    fopArr.push({
                        'fop': 'imageMogr2',
                        'auto-orient': true,
                        'strip': true,
                        'rotate': rotate,
                        'format': 'png'
                    });
                }
            } else {
                $(this).siblings().removeClass('disabled');
                var imageMogr = $(this).data('imagemogr');
                if (imageMogr === 'left') {
                    rotate = rotate - 90 < 0 ? rotate + 270 : rotate - 90;
                } else if (imageMogr === 'right') {
                    rotate = rotate + 90 > 360 ? rotate - 270 : rotate + 90;
                }
                fopArr.push({
                    'fop': 'imageMogr2',
                    'auto-orient': true,
                    'strip': true,
                    'rotate': rotate,
                    'format': 'png'
                });
            }

            $('#myModal-img .modal-body-footer').find('a.disabled').each(
                function () {

                    var watermark = $(this).data('watermark');
                    var imageView = $(this).data('imageview');
                    var imageMogr = $(this).data('imagemogr');

                    if (watermark) {
                        fopArr.push({
                            fop: 'watermark',
                            mode: 1,
                            image: 'http://www.b1.qiniudn.com/images/logo-2.png',
                            dissolve: 100,
                            gravity: watermark,
                            dx: 100,
                            dy: 100
                        });
                    }

                    if (imageView) {
                        var height;
                        switch (imageView) {
                            case 'large':
                                height = originHeight;
                                break;
                            case 'middle':
                                height = originHeight * 0.5;
                                break;
                            case 'small':
                                height = originHeight * 0.1;
                                break;
                            default:
                                height = originHeight;
                                break;
                        }
                        fopArr.push({
                            fop: 'imageView2',
                            mode: 3,
                            h: parseInt(height, 10),
                            q: 100,
                            format: 'png'
                        });
                    }

                    if (imageMogr === 'no-rotate') {
                        fopArr.push({
                            'fop': 'imageMogr2',
                            'auto-orient': true,
                            'strip': true,
                            'rotate': 0,
                            'format': 'png'
                        });
                    }
                });

            var newUrl = Qiniu.pipeline(fopArr, key);

            var newImg = new Image();
            img.attr('src', 'images/loading.gif');
            newImg.onload = function () {
                img.attr('src', newUrl);
                img.parent('a').attr('href', newUrl);
            };
            newImg.src = newUrl;
            return false;
        });

    });

</script>
</body>
</html>
