/*
 * @Author: Paco
 * @Date:   2017-02-15
 * +----------------------------------------------------------------------
 * | jqadmin [ jq酷打造的一款懒人后台模板 ]
 * | Copyright (c) 2017 http://jqadmin.jqcool.net All rights reserved.
 * | Licensed ( http://jqadmin.jqcool.net/licenses/ )
 * | Author: Paco <admin@jqcool.net>
 * +----------------------------------------------------------------------
 */

layui.define(['jquery', 'tags', 'layedit', 'jqform', 'upload'], function(exports) {
    var $ = layui.jquery,
        layedit = layui.layedit,
        box = "",
        form = layui.jqform,
        tags = layui.tags;
    form.set({
        "blur": true,
        "form": "#form1"
    }).init();



    //自定义
    form.verify({
        username: function(value) {
            if (!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)) {
                return '标题不能有特殊字符';
            }
            if (/(^\_)|(\__)|(\_+$)/.test(value)) {
                return '标题首尾不能出现下划线\'_\'';
            }
            if (/^\d+\d+$/.test(value)) {
                return '标题不能全为数字';
            }
        },
        pass: [
            /^[\S]{6,12}$/, '密码必须6到12位，且不能出现空格'
        ],
        content: function(value) {
            layedit.sync(editIndex);
            return;
        }
    });
    tags.init();

    //上传文件设置
    layui.upload({
        url: '/admin/Upload/uploadFile'
        ,title:'文件上传'
        ,elem: '#upload-excel' //指定原始元素，默认直接查找class="layui-upload-file"
        ,method: 'post' //上传接口的http类型
        ,ext: 'xls|xlsx|csv'
        ,type:'file'
        ,before: function(input) {
            box = $(input).parent('form').parent('div').parent('.layui-input-block');
        },
        success: function(res) {
            console.log(res);
            if (res.status == 1) {
                box.find('input[type=hidden]').val(res.file_path);
                box.next('div').find('p').html('上传成功')
            } else {
                box.next('div').find('p').html('上传失败...')
            }
        }
    });



    exports('excel', {});
});