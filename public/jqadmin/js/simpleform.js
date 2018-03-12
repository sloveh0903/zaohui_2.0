/*
 * @Author: Paco
 * @Date:   2017-02-15
 * @lastmodify 2017-02-24
 * +----------------------------------------------------------------------
 * | jqadmin [ jq酷打造的一款懒人后台模板 ]
 * | Copyright (c) 2017 http://jqadmin.jqcool.net All rights reserved.
 * | Licensed ( http://jqadmin.jqcool.net/licenses/ )
 * | Author: Paco <admin@jqcool.net>
 * +----------------------------------------------------------------------
 */

layui.define(['jquery', 'jqform', 'element', 'layedit'], function(exports) {
    var $ = layui.jquery,
        layedit = layui.layedit,
        form = layui.jqform;
    form.set({
        "blur": true,
        "form": "#form1"
    }).init();

    //主要是为了异步提交富文本框内容
    form.verify({
        username: function(value) {
            if (!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)) {
                return '文章标题不能有特殊字符';
            }
            if (/(^\_)|(\__)|(\_+$)/.test(value)) {
                return '文章标题首尾不能出现下划线\'_\'';
            }
            if (/^\d+\d+\d$/.test(value)) {
                return '文章标题不能全为数字';
            }
        },
        pass: [
            /^[\S]{6,12}$/, '密码必须6到12位，且不能出现空格'
        ]
    });

    //富文本框
    layedit.set({
        uploadImage: {
            url: '/php/upload.php'
        }
    });
    var editIndex = layedit.build('conntent');
    exports('simpleform', {

    });
});