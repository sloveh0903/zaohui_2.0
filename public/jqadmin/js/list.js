/*
 * @Author: Paco
 * @Date:   2017-02-07
 * +----------------------------------------------------------------------
 * | jqadmin [ jq酷打造的一款懒人后台模板 ]
 * | Copyright (c) 2017 http://jqadmin.jqcool.net All rights reserved.
 * | Licensed ( http://jqadmin.jqcool.net/licenses/ )
 * | Author: Paco <admin@jqcool.net>
 * +----------------------------------------------------------------------
 */

layui.define(['jquery', 'dtable', 'jqdate', 'jqform', 'upload'], function(exports) {
    var $ = layui.jquery,
        list = layui.dtable,
        laydate = layui.laydate,
        form = layui.jqform,
        oneList = new list();
    form.set({
        "change": true,
        "form": "#form1,#form2"
    }).init();
    oneList.init('list-tpl');

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


    $('#btn-delete-all').on('click', function(){
        // getIds(table对象,获取input为id的属性)
        console.log('aaaa');
        var ids = getIds($('#dateTable'),'data-id');
        /*if(ids == null || ids == ''){
         layer.msg('未选择');
         }else{
         // layer.msg(ids);
         deleteAll(ids,'http://vip-admin.com/admin/log/delete.html','http://vip-admin.com/admin/log/index.html','http://vip-admin.com/admin/log/index.html');
         }*/
    });

    exports('list', {});
});