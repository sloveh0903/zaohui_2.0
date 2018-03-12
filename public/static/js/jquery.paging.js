
//版本1.4.7
//作者：聂未

; (function ($, window, document) {

    $.fn.extend({
        paging: function (options) {

            //默认参数
            var defaults = {
                total: 0,   //全部页数
                showPage: 0,  //显示的页数
                height: 30,    //高
                width: 14,      //宽
                jumpInputWidth: 60,   //跳转输入框的宽
                fontFamily: "微软雅黑",  //字体
                fontSize: 13,    //字体大小
                currentFontSize: 13,    //当前选中页的字体大小
                fontWeight: 400,   //字体宽度
                currentFontWeight: 400,   //当前选中页的字体粗细
                centerFontColor: "rgba(0,0,0,.4)",  //中间字体颜色
                otherFontColor: "rgba(0,0,0,.4)",   //两边字体颜色
                centerFontHoverColor: "#fff",  //中间鼠标悬浮的字体颜色
                otherFontHoverColor: "#fff",  //两边鼠标悬浮的字体颜色
                currentFontColor: "#fff",    //当前选中页的字体颜色
                jumpFontColor: "#000",          //跳转内部标签的字体颜色
                jumpFontHoverColor: "red",      // 跳转内部标签的悬浮字体颜色  
                currentPage: 1,             //当前页
                centerBgColor: "#fff",   //中间页数按钮的底色
                centerHoverBgColor: "#24B6F7",    //中间鼠标悬浮的的底色
                otherBgColor: "#fff",    //两边按钮的底色
                otherHoverBgColor: "#24B6F7",     //两边鼠标悬浮的背景颜色
                jumpBgColor: "#b6ff00",           //跳转a标签按钮的背景色
                jumpHoverBgColor: "",       //跳转a标签背景的悬浮背景色
                currentBgColor: "#24B6F7",      //当前选中页的底色
                centerBorder: "0px solid #24B6F7",           //中间按钮的边框
                otherBorder: "0px solid #24B6F7",          //两边按钮的边框
                centerHoverBorder: "",               //中间按钮悬浮边框
                otherHoverBorder: "",                 //两边按钮悬浮边框
                jumpBorder: "0px solid #24B6F7",            //跳转内标签的边框
                jumpHoverBorder: "",                   //跳转内标签的悬浮边框
                currentBorder: "",          //当前选中页的边框
                showJump: false,               //是否显示跳转功能
                transition: "all .2s",       //效果延迟
                gapWidth: 3,      //间隙宽度
                cormer: 2,   //按钮的边角曲度
                beforeBtnString: "上一页",  //上一页按钮内显示的文字
                nextBtnString: "下一页",   //下一页按钮内显示的文字
                firstBtnString: "",   //首页按钮内显示的内容
                lastBtnString: "",   //尾页按钮内显示的内容
                jumpBtnString: "跳转",    //跳转按钮的显示内容
                submitStyle: "href",   //点击按钮后的提交方式，有a标签的href提交和ajax异步提交两种选择,滚动显示方式只支持ajax方式
                ajaxSubmitType: "post",      //注明ajax是通过get方式访问还是post方式访问
                idParameter: "page",               //传到后台的当前页的id的参数名，这个传值会自动添加在href或ajax的url末尾
                url: "/Home/List/",               //需要提交的目标控制器，如"/Home/List/"或"/Home/List?name='张三'&password='123456'"
                limit: 5000,                       //设置滚动显示方式的极限值，大于则自动切换无滚动模式
                animation: true,             //是否是滚动动画方式呈现  false为精简方式呈现   页数大于limit时无论怎么设置自动默认为false 
                ajaxData: {},                     //ajax方式传值时的附加传值,要传的参数放在这里面,页面参数只要指定idParamemeter就好，会自动添加,该参数只有在ajax方式下有用
                ajaxDataType: "json",             //发生ajax请求时,dataType参数值,如跨域时为'jsonp'
                jsonpCBName: "jsonp_cb",         //跨域请求时的回调函数名,ajaxDataType为jsonp时有效
                firstAjaxSubmit: true,        //表示初始化时是否自动请求一次，用以获得total,currentPage等参数
                beforeAjaxSubmit: function () { },     //ajax模式下，在发出请求之前的操作函数,重绘插件可使用此参数
                ajaxSubmitBack: function (data) { },         //用于ajax返回的数据的操作,回调函数,data为服务器返回数据
                isNewDraw: false,                  //插件是否是由重绘函数再次生成
                simpleType: 0                    // 精简模式可选0和1两种
            }

            //将默认的参数对象和传进来的参数对象合并在一起
            var opts = $.extend(defaults, options);

            /*********************定义全API方法开始*************************/
            //全局API对象
            PageAPI = {};

            //API 获取当前页
            PageAPI.GetCurrentPage = function () {
                return opts.currentPage;
            }

            //API 获取总页数
            PageAPI.GetTotalPage = function () {
                return opts.total;
            }

            //API 向ajaxData增加或替换参数,传入json对象
            PageAPI.AddAjaxData = function (json) {
                opts.submitStyle == "ajax" && (opts.ajaxData = $.extend(false, opts.ajaxData, json));
            }

            //API 完全替换ajaxData附带传参对象
            PageAPI.ReplaceAjaxData = function (json) {
                opts.submitStyle == "ajax" && (opts.ajaxData = json);
            }

            //API  获取当前ajaxData附带传参对象
            PageAPI.GetAjaxData = function (json) {
                if (opts.submitStyle == "ajax")
                    return opts.ajaxData;
            }

            //API 更改请求url
            PageAPI.SetRequestUrl = function (url) {
                opts.url = url;
                if (opts.submitStyle == "href")
                    setHref();
            }

            //隐藏首页和上一页标签
            PageAPI.HiddenFirst = function (type) {

               if (Number(type) == 0)
                   setHidden(".otherBtns",0);
                   //this.find(".otherBtns").eq(0).hide();
               else
                   setShow(".otherBtns",0);
                   //this.find(".otherBtns").eq(0).show();
            }

            //隐藏下一页和尾页标签
            PageAPI.HiddenLast = function (type) {
               if (Number(type) == 0)
                   setHidden(".otherBtns",1);
                   //this.find(".otherBtns").eq(1).hide();
               else
                   setShow(".otherBtns",1);
                   //this.find(".otherBtns").eq(1).show();
            }

            //API 插件外主动执行请求
            PageAPI.AjaxSubmit = function () {
                if (opts.submitStyle == "ajax") {

                    //请求预处理函数
                    opts.beforeAjaxSubmit();

                    var ajax_param = {
                        url: opts.url,
                        type: opts.ajaxSubmitType,
                        data: opts.ajaxData,
                        dataType: opts.ajaxDataType,
                        success: function (data) {
                            if (!data.total || !data.currentPage) {
                                data.total = 1;
                                data.currentPage = 1;
                            }
                            draw(data.total, data.currentPage);
                            opts.ajaxSubmitBack(data);
                        }
                    };

                    opts.ajaxDataType == "jsonp" && (ajax_param['jsonpCallback'] = opts.jsonpCBName);

                    //执行主动请求
                    $.ajax(ajax_param);
                }
            }

            //API 表示当前插件已经初始化结束
            PageAPI.IsDone = function () {
                return true;
            }

            /*********************定义全API方法结束*************************/

            //ajax模式为total未指定值时,通过请求赋值
            if (opts.firstAjaxSubmit && Number(opts.total) <= 0 && opts.submitStyle == "ajax") {

                //将页码idParameter添加到ajaxData末尾一起发送
                opts.ajaxData[opts.idParameter] = opts.currentPage;

                //预提交处理函数
                opts.beforeAjaxSubmit();

                var ajax_param = {
                    url: opts.url,
                    type: opts.ajaxSubmitType,
                    data: opts.ajaxData,
                    async: false,
                    dataType: opts.ajaxDataType,
                    success: function (json) {
                        json.total != null && (opts.total = Number(json.total));
                        json.currentPage != null && (opts.currentPage = Number(json.currentPage));
                        opts.ajaxSubmitBack(json);
                        opts.firstAjaxSubmit = false;
                    }
                };

                opts.ajaxDataType == "jsonp" && (ajax_param['jsonpCallback'] = opts.jsonpCBName);

                //第一次请求
                $.ajax(ajax_param);
            }

            //为参数调整格式
            opts.total = parseInt(opts.total, 10);
            opts.showPage = parseInt(opts.showPage, 10);
            opts.currentPage = parseInt(opts.currentPage, 10);
            opts.height = parseInt(opts.height, 10);
            opts.width = parseInt(opts.width, 10);

            //为其增加一个class名
            this.addClass("paging");

            //目标元素命名
            var $this = this;

            function setHidden(node,child) {
                $this.find(node).eq(child).hide();
            }

            function setShow(node,child) {
                $this.find(node).eq(child).show();
            }

            //防止页数过多造成的卡顿
            if (opts.total > opts.limit)
                opts.animation = false;

            //浏览器兼容
            //$("body").css({ "padding": 0, "margin": 0 });

            //return this.each(function () {

            var first = 1;   //中间显示页的起始位置，初始为1
            var middle = Math.ceil(opts.showPage / 2);     //向上取整，获得中间数，用以后面计算出需要滚动的页数
            var last = opts.showPage;  //中间显示页的终止位置，初始为显示页的长度
            var slideBool = true;   //用于防止在动画过程中发生的点击情况  true为可以点击
            var initial = true;             //表示是否是首次呈现，需要初始化否
            var jumpClick = 0;       //用来判断是否点击了跳转按钮,用于精简模式
            var oldShowPage = opts.showPage;  //因为opts.showPage会随total而改变,而重绘需要再一次使用原showPage参数,故在此保存
            var oldHeight = opts.height;  //因为opts.height会随total而改变,而重绘需要再一次使用原height参数,故在此保存
            var poleClick = 0;         //不为0时代表点击了首页或尾页按钮

            //获取href访问方式时的连接字符
            function getConnect() {
                var connect = "";
                if (opts.url.indexOf("?") == -1)
                    connect = "?";
                else
                    connect = "&";
                return connect;
            }

            //设置禁止在ie下选中该div下的文本
            $this.attr({ "onselectstart": "return false" });

            //初始化两侧按钮
            $this.append('<div class="otherBtns"><a value="首页">' + opts.firstBtnString + '</a><a value="上一页">' + opts.beforeBtnString + '</a></div><div class="centerBtns"><ul></ul></div><div class="otherBtns"><a value="下一页">' + opts.nextBtnString + '</a><a value="尾页">' + opts.lastBtnString + '</a></div><div class="jumpBtns"><input type="text" class="jump_input" data="" /><a class="jump_a" data="0">' + opts.jumpBtnString + '</a></div>');

            //设置跳转div是否隐藏
            if (!opts.showJump) {
                $this.find(".jumpBtns").hide();
            }

            //将两侧内容为空的按钮删除掉
            $this.find(".otherBtns a").each(function (index, element) {
                if (!$(element).text().replace(/\s/g, ""))
                    $(element).remove();
            });

            //显示页超出总页数，则等于总页数
            if (opts.showPage > opts.total)
                opts.showPage = opts.total;

            //初始化中间页码按钮
            if (opts.animation) {  //滚动方式
                for (var i = 1; i <= opts.total; i++) {
                    $this.find(".centerBtns ul").append('<li><a value=' + i + '>' + i + '</a></li>');
                }
            }
            else {           //无滚动方式
                for (var i = 1; i <= opts.showPage; i++) {
                    if (i == opts.showPage - 1 && opts.showPage < opts.total) {
                        if (opts.simpleType == 1)
                            $this.find(".centerBtns ul").append('<li><a value=' + i + '>' + i + '</a></li>');
                        else
                            $this.find(".centerBtns ul").append('<li><a value=' + i + '>····</a></li>');
                    }
                    else if (i == opts.showPage) {
                        if (opts.simpleType == 1)
                            $this.find(".centerBtns ul").append('<li><a value=' + i + '>' + i + '</a></li>');
                        else
                            $this.find(".centerBtns ul").append('<li><a value=' + opts.total + '>' + opts.total + '</a></li>');
                    }
                    else {
                        $this.find(".centerBtns ul").append('<li><a value=' + i + '>' + i + '</a></li>');
                    }
                }
            }

            //设置总高度
            $this.css({
                "font-size": opts.fontSize + "px",
                "font-family": opts.fontFamily,
                "font-weight": opts.fontWeight
            });

            //设置所有a标签的共有属性
            $this.find("a").css({
                "padding": "0 " + opts.width + "px",
                "float": "left",
                "display": "block",
                "height": opts.height + "px",
                "line-height": opts.height + "px",
                "text-align": "center",
                "cursor": "pointer",
                "border-radius": opts.cormer + "px",
                "text-decoration": "none",
                "user-select": "none",
                "-ms-user-select": "none",
                "transition": opts.transition,
                "-moz-transition": "none",
                "-webkit-transition": opts.transition,
                "-o-transition": opts.transition
            });

            //去除火狐浏览器的transition样式
            if (/mozilla/.test(navigator.userAgent.toLowerCase()) && !/(compatible|webkit)/.test(navigator.userAgent.toLowerCase())) {
                $this.find("a").css("transition", "none");
            }

            //以下是中间div内的a标签的设置

            $this.find(".centerBtns a").css({
                "background-color": opts.centerBgColor,
                "color": opts.centerFontColor,
                "border": opts.centerBorder,
                "width": opts.simpleType == 1 ? opts.total.toString().length * 7 + "px" : parseInt($this.find(".centerBtns a").last().css("width"), 10) + "px"
            }).hover(function () {
                if (!!opts.centerFontHoverColor.replace(/\s/g, "") && $(this).attr("value") != opts.currentPage)
                    $(this).css({ "color": opts.centerFontHoverColor });
                if (!!opts.centerHoverBgColor.replace(/\s/g, "") && $(this).attr("value") != opts.currentPage)
                    $(this).css({ "background-color": opts.centerHoverBgColor });
                if (!!opts.centerHoverBorder.replace(/\s/g, "") && $(this).attr("value") != opts.currentPage)
                    $(this).css({ "border": opts.centerHoverBorder });
            }, function () {
                if ($(this).attr("value") != opts.currentPage && !!opts.centerFontColor.replace(/\s/g, ""))
                    $(this).css({ "color": opts.centerFontColor });
                if ($(this).attr("value") != opts.currentPage && !!opts.centerBgColor.replace(/\s/g, ""))
                    $(this).css({ "background-color": opts.centerBgColor });
                if (!!opts.centerBorder.replace(/\s/g, "") && $(this).attr("value") != opts.currentPage)
                    $(this).css({ "border": opts.centerBorder });
            }).each(function (index, element) {

                ////设置当前页的样式
                if (parseInt($(element).attr("value"), 10) == opts.currentPage) {
                    if (!!opts.currentFontColor.replace(/\s/g, ""))
                        $(element).css({ "color": opts.currentFontColor.replace(/\s/g, "") });
                    if (!!opts.currentBgColor.replace(/\s/g, ""))
                        $(element).css({ "background-color": opts.currentBgColor });
                    if (!!opts.currentBorder.replace(/\s/g, ""))
                        $(element).css({ "border": opts.currentBorder });
                    if (!!opts.currentFontSize)
                        $(element).css({ "font-size": opts.currentFontSize + "px" });
                    if (!!opts.currentFontWeight)
                        $(element).css({ "font-weight": opts.currentFontWeight });
                }

                //如果访问方式是href.并且是滚动模式
                if (opts.submitStyle == "href" && opts.animation) {
                    $(element).prop({ "href": opts.url + getConnect() + opts.idParameter + "=" + $(element).attr("value") });
                }

                //注册点击事件
                $(element).click(function () {

                    //首页和末页左右两侧按钮的cursor
                    if ((opts.isNewDraw && opts.currentPage == 1) || ($(element).attr("value") == "1" && opts.total > 1 && opts.submitStyle == "ajax" && !opts.isNewDraw) || (opts.currentPage == 1 && opts.submitStyle == "href")) {
                        $this.find(".otherBtns a").each(function (index, item_other) {
                            if ($(item_other).attr("value") == "首页" || $(item_other).attr("value") == "上一页") {
                                $(item_other).css({ "cursor": "not-allowed" });
                            }
                            else {
                                $(item_other).css({ "cursor": "pointer" });
                            }
                        });
                    }
                    else if ((opts.isNewDraw && opts.currentPage == opts.total) || ($(element).attr("value") == opts.total && opts.total != 1 && opts.submitStyle == "ajax" && !opts.isNewDraw) || (opts.currentPage == opts.total && opts.submitStyle == "href")) {
                        $this.find(".otherBtns a").each(function (index, item_other) {
                            if ($(item_other).attr("value") == "尾页" || $(item_other).attr("value") == "下一页") {
                                $(item_other).css({ "cursor": "not-allowed" });
                            }
                            else {
                                $(item_other).css({ "cursor": "pointer" });
                            }
                        });
                    }
                    else if ((opts.simpleType == 1 && $this.find(".jumpBtns>input").val() == opts.total && parseInt($this.find(".jump_a").attr("data")) < jumpClick) || (opts.simpleType == 1 && poleClick == opts.total)) {
                        $this.find(".otherBtns a").each(function (index, item_other) {
                            if ($(item_other).attr("value") == "尾页" || $(item_other).attr("value") == "下一页") {
                                $(item_other).css({ "cursor": "not-allowed" });
                            }
                            else {
                                $(item_other).css({ "cursor": "pointer" });
                            }
                        });
                    }
                    else if ((opts.simpleType == 1 && $this.find(".jumpBtns>input").val() == 1 && parseInt($this.find(".jump_a").attr("data")) < jumpClick) || (opts.simpleType == 1 && poleClick == 1)) {
                        $this.find(".otherBtns a").each(function (index, item_other) {
                            if ($(item_other).attr("value") == "首页" || $(item_other).attr("value") == "上一页") {
                                $(item_other).css({ "cursor": "not-allowed" });
                            }
                            else {
                                $(item_other).css({ "cursor": "pointer" });
                            }
                        });
                    }
                    else if (opts.total != 0 && opts.total != 1) {
                        $this.find(".otherBtns a").css({ "cursor": "pointer" });
                    }

                    //重绘插件正常化
                    opts.isNewDraw = false;

                    //中间页码
                    if (!opts.animation) {         //非滚动方式的显示
                        var clickPage;//点击页码
                        if (initial)
                            clickPage = opts.currentPage;
                        else if (poleClick != 0) {
                            clickPage = poleClick;
                            poleClick = 0;
                        }
                        else if (parseInt($this.find(".jump_a").attr("data")) < jumpClick) {
                            clickPage = parseInt($this.find(".jumpBtns>input").val());
                            $this.find(".jump_a").attr({ "data": jumpClick });
                        }
                        else
                            clickPage = parseInt($(element).attr("value"), 10);
                        //var clickPage = parseInt($(element).attr("value"), 10);  //点击页码
                        var refresh = clickPage - middle;    //判断是否刷新的先决条件

                        //满足条件就刷新分页
                        if (refresh > 0 && opts.showPage < opts.total) {
                            var newClickPage;
                            if (opts.showPage % 2 != 0)
                                newClickPage = opts.total - (middle - 1);
                            else
                                newClickPage = opts.total - middle;

                            for (var i = 0; i < opts.showPage; i++) {
                                if (clickPage < newClickPage) {
                                    switch (i) {
                                        case 0:
                                            if (opts.simpleType == 1) {
                                                var pageIndex = clickPage - (middle - i - 1);
                                                $this.find(".centerBtns a").eq(i).text(pageIndex);
                                                $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                                if (opts.submitStyle == "href" && initial)
                                                    $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                            }
                                            else {
                                                $this.find(".centerBtns a").eq(i).text(1);
                                                $this.find(".centerBtns a").eq(i).attr({ "value": 1 });
                                                if (opts.submitStyle == "href" && initial)
                                                    $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + 1 });
                                            }
                                            break;
                                        case 1:
                                            if (opts.simpleType == 1) {
                                                var pageIndex = clickPage - (middle - i - 1);
                                                $this.find(".centerBtns a").eq(i).text(pageIndex);
                                                $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                                if (opts.submitStyle == "href" && initial)
                                                    $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                            }
                                            else {
                                                $this.find(".centerBtns a").eq(i).text("····");
                                                var pageIndex = clickPage - (middle - i - 1);
                                                $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                                if (opts.submitStyle == "href" && initial)
                                                    $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                            }
                                            break;
                                        case (opts.showPage - 1):
                                            if (opts.simpleType == 1) {
                                                var pageIndex = clickPage - (middle - i - 1);
                                                $this.find(".centerBtns a").eq(i).text(pageIndex);
                                                $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                                if (opts.submitStyle == "href" && initial)
                                                    $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                            }
                                            else {
                                                $this.find(".centerBtns a").eq(i).text(opts.total);
                                                $this.find(".centerBtns a").eq(i).attr({ "value": opts.total });
                                                if (opts.submitStyle == "href" && initial)
                                                    $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + opts.total });
                                            }
                                            break;
                                        case (opts.showPage - 2):
                                            if (opts.simpleType == 1) {
                                                var pageIndex = clickPage - (middle - i - 1);
                                                $this.find(".centerBtns a").eq(i).text(pageIndex);
                                                $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                                if (opts.submitStyle == "href" && initial)
                                                    $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                            }
                                            else {
                                                $this.find(".centerBtns a").eq(i).text("····");
                                                var pageIndex = clickPage - (middle - i - 1);
                                                $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                                if (opts.submitStyle == "href" && initial)
                                                    $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                            }
                                            break;
                                        default:
                                            var pageIndex = clickPage - (middle - i - 1);
                                            $this.find(".centerBtns a").eq(i).text(pageIndex);
                                            $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                            if (opts.submitStyle == "href" && initial)
                                                $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                            break;
                                    }
                                }
                                else {
                                    switch (i) {
                                        case 0:
                                            if (opts.simpleType == 1) {
                                                var pageIndex = newClickPage - (middle - i - 1);
                                                $this.find(".centerBtns a").eq(i).text(pageIndex);
                                                $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                                if (opts.submitStyle == "href" && initial)
                                                    $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                            }
                                            else {
                                                $this.find(".centerBtns a").eq(i).text(1);
                                                $this.find(".centerBtns a").eq(i).attr({ "value": 1 });
                                                if (opts.submitStyle == "href" && initial)
                                                    $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + 1 });
                                            }
                                            break;
                                        case 1:
                                            if (opts.simpleType == 1) {
                                                var pageIndex = newClickPage - (middle - i - 1);
                                                $this.find(".centerBtns a").eq(i).text(pageIndex);
                                                $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                                if (opts.submitStyle == "href" && initial)
                                                    $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                            }
                                            else {
                                                $this.find(".centerBtns a").eq(i).text("····");
                                                var pageIndex = newClickPage - (middle - i - 1);
                                                $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                                if (opts.submitStyle == "href" && initial)
                                                    $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                            }
                                            break;
                                        case (opts.showPage - 1):
                                            if (opts.simpleType == 1) {
                                                var pageIndex = newClickPage - (middle - i - 1);
                                                $this.find(".centerBtns a").eq(i).text(pageIndex);
                                                $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                                if (opts.submitStyle == "href" && initial)
                                                    $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                            }
                                            else {
                                                $this.find(".centerBtns a").eq(i).text(opts.total);
                                                $this.find(".centerBtns a").eq(i).attr({ "value": opts.total });
                                                if (opts.submitStyle == "href" && initial)
                                                    $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + opts.total });
                                            }
                                            break;
                                        default:
                                            var pageIndex = newClickPage - (middle - i - 1);
                                            $this.find(".centerBtns a").eq(i).text(pageIndex);
                                            $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                            if (opts.submitStyle == "href" && initial)
                                                $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                            break;
                                    }
                                }
                            }
                        }
                        else {
                            for (var i = 0; i < opts.showPage; i++) {
                                if (i == opts.showPage - 1) {
                                    if (opts.simpleType == 1) {
                                        var pageIndex = i + 1;
                                        $this.find(".centerBtns a").eq(i).text(pageIndex);
                                        $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                        if (opts.submitStyle == "href" && initial)
                                            $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                    }
                                    else {
                                        $this.find(".centerBtns a").eq(i).text(opts.total);
                                        $this.find(".centerBtns a").eq(i).attr({ "value": opts.total });
                                        if (opts.submitStyle == "href" && initial)
                                            $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + opts.total });
                                    }
                                }
                                else if (i == opts.showPage - 2 && opts.showPage < opts.total) {
                                    if (opts.simpleType == 1) {
                                        var pageIndex = i + 1;
                                        $this.find(".centerBtns a").eq(i).text(pageIndex);
                                        $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                        if (opts.submitStyle == "href" && initial)
                                            $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                    }
                                    else {
                                        $this.find(".centerBtns a").eq(i).text("····");
                                        var pageIndex = i + 1;
                                        $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                        if (opts.submitStyle == "href" && initial)
                                            $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                    }
                                }
                                else {
                                    var pageIndex = i + 1;
                                    $this.find(".centerBtns a").eq(i).text(pageIndex);
                                    $this.find(".centerBtns a").eq(i).attr({ "value": pageIndex });
                                    if (opts.submitStyle == "href" && initial)
                                        $this.find(".centerBtns a").eq(i).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + pageIndex });
                                }
                            }
                        }

                        //设置选中效果
                        $this.find(".centerBtns a").each(function (suffix, item) {
                            if (parseInt($(item).attr("value"), 10) == clickPage) {
                                if (!!opts.currentBgColor.replace(/\s/g, ""))
                                    $(item).css({ "background-color": opts.currentBgColor }).parent().siblings().children().css({ "background-color": opts.centerBgColor.replace(/\s/g, "") });
                                if (!!opts.currentFontColor.replace(/\s/g, ""))
                                    $(item).css({ "color": opts.currentFontColor }).parent().siblings().children().css({ "color": opts.centerFontColor.replace(/\s/g, "") });
                                if (!!opts.currentBorder.replace(/\s/g, ""))
                                    $(item).css({ "border": opts.currentBorder }).parent().siblings().children().css({ "border": opts.centerBorder });
                                if (!!opts.currentFontSize)
                                    $(item).css({ "font-size": opts.currentFontSize + "px" }).parent().siblings().children().css({ "font-size": opts.fontSize + "px" });
                                if (!!opts.fontWeight)
                                    $(item).css({ "font-weight": opts.currentFontWeight }).parent().siblings().children().css({ "font-weight": opts.fontWeight });
                            }
                        });

                        if (clickPage != opts.currentPage || opts.submitStyle == "ajax") {   //避免第一次加载时就请求

                            //设置当前页码
                            opts.currentPage = clickPage;

                            //重绘时禁止反复请求
                            if (!opts.firstAjaxSubmit && initial) {
                                opts.firstAjaxSubmit = true;
                                initial = false;
                                return;
                            }

                            //将页码idParameter添加到ajaxData末尾一起发送
                            opts.ajaxData[opts.idParameter] = opts.currentPage;

                            //预提交处理函数
                            opts.beforeAjaxSubmit();

                            var ajax_param = {
                                url: opts.url,
                                type: opts.ajaxSubmitType,
                                data: opts.ajaxData,
                                dataType: opts.ajaxDataType,
                                success: function (data) {
                                    if (!data.total || !data.currentPage) {
                                        data.total = 1;
                                        data.currentPage = 1;
                                    }
                                    draw(data.total, data.currentPage); //保持和后台参数同步
                                    opts.ajaxSubmitBack(data);
                                }
                            };

                            opts.ajaxDataType == "jsonp" && (ajax_param['jsonpCallback'] = opts.jsonpCBName);

                            //异步访问后台的代码
                            //if (opts.submitStyle == "ajax") {
                            $.ajax(ajax_param);
                            //}
                        }

                    }
                    else if (slideBool && opts.animation) {              //slideBool变量防止多次滚动   滚动方式
                        var left = -parseInt($this.find("ul").css("left").replace(/\D/g, ""), 10); //获得当前ul的left坐标
                        var liWidth = parseInt($this.find("ul li").eq(0).css("width").replace(/\D/g, ""), 10);   //单个li的宽度
                        clickPage = parseInt($(element).attr("value"), 10);  //点击页码
                        var slidePage = clickPage - middle;   //需要滑动的页数，正负表示左右方向

                        //动画
                        if (slidePage > 0 && last < opts.total) {   //判断是否符合滚动规则 往右

                            var beforeLast = last;

                            //设置参数
                            last += slidePage;
                            if (last > opts.total) {       //判断末尾移动长度大于的情况
                                last = opts.total;
                                slidePage = opts.total - beforeLast;
                            }
                            first += slidePage;
                            middle += slidePage;

                            //获得滚动宽度
                            var slideWidth = slidePage * liWidth + (opts.gapWidth * 2) * slidePage;

                            //动画
                            slideBool = false; //禁止按钮点击
                            $this.find("ul").animate({ "left": left - slideWidth + "px" }, 300, function () {
                                slideBool = true;  //开启按钮点击
                            });
                        }
                        else if (slidePage < 0 && first > 1) {    //判断是否符合滚动规则  往左

                            var beforeFirst = first;

                            //设置参数
                            first += slidePage;
                            if (first < 1) {       //判断开头移动长度少于的情况
                                first = 1;
                                slidePage = 1 - beforeFirst;
                            }
                            last += slidePage;
                            middle += slidePage;

                            //获得滚动宽度
                            var slideWidth = slidePage * liWidth + (opts.gapWidth * 2) * slidePage;

                            //动画
                            slideBool = false;  //禁止按钮点击
                            $this.find("ul").animate({ "left": left - slideWidth + "px" }, 300, function () {
                                slideBool = true;  //开启按钮点击
                            });
                        }

                        //设置点击选中效果

                        if (!!opts.currentBgColor.replace(/\s/g, ""))
                            $(element).css({ "background-color": opts.currentBgColor }).parent().siblings().children().css({ "background-color": opts.centerBgColor.replace(/\s/g, "") });
                        if (!!opts.currentFontColor.replace(/\s/g, ""))
                            $(element).css({ "color": opts.currentFontColor }).parent().siblings().children().css({ "color": opts.centerFontColor.replace(/\s/g, "") });
                        if (!!opts.currentBorder.replace(/\s/g, ""))
                            $(element).css({ "border": opts.currentBorder }).parent().siblings().children().css({ "border": opts.centerBorder });
                        if (!!opts.currentFontSize)
                            $(element).css({ "font-size": opts.currentFontSize + "px" }).parent().siblings().children().css({ "font-size": opts.fontSize + "px" });
                        if (!!opts.fontWeight)
                            $(element).css({ "font-weight": opts.currentFontWeight }).parent().siblings().children().css({ "font-weight": opts.fontWeight });


                        if (clickPage != opts.currentPage || opts.submitStyle == "ajax") {    //避免第一次加载时就请求

                            //设置当前页码
                            opts.currentPage = clickPage;

                            //重绘时禁止反复请求
                            if (!opts.firstAjaxSubmit && initial) {
                                opts.firstAjaxSubmit = true;
                                initial = false;
                                return;
                            }

                            //将页码idParameter添加到ajaxData末尾一起发送
                            opts.ajaxData[opts.idParameter] = opts.currentPage;

                            //预提交处理函数
                            opts.beforeAjaxSubmit();

                            var ajax_param = {
                                url: opts.url,
                                type: opts.ajaxSubmitType,
                                data: opts.ajaxData,
                                dataType: opts.ajaxDataType,
                                success: function (data) {
                                    if (!data.total || !data.currentPage) {
                                        data.total = 1;
                                        data.currentPage = 1;
                                    }
                                    draw(data.total, data.currentPage);  //保持和后台参数同步
                                    opts.ajaxSubmitBack(data);
                                }
                            };

                            opts.ajaxDataType == "jsonp" && (ajax_param['jsonpCallback'] = opts.jsonpCBName);

                            //异步访问后台的代码
                            // if (opts.submitStyle == "ajax") {
                            $.ajax(ajax_param);
                            // }
                        }

                    }
                });

            });

            //两边的a标签相关属性

            $this.find(".otherBtns a").css({
                "background-color": opts.otherBgColor,
                "margin": "0 " + opts.gapWidth + "px",
                "color": opts.otherFontColor,
                "border": opts.otherBorder
            }).hover(function () {
                if (!!opts.otherFontHoverColor.replace(/\s/g, ""))
                    $(this).css({ "color": opts.otherFontHoverColor });
                if (!!opts.otherHoverBgColor.replace(/\s/g, ""))
                    $(this).css({ "background-color": opts.otherHoverBgColor });
                if (!!opts.otherHoverBorder.replace(/\s/g, ""))
                    $(this).css({ "border": opts.otherHoverBorder });
            }, function () {
                if (!!opts.otherFontColor.replace(/\s/g, ""))
                    $(this).css({ "color": opts.otherFontColor });
                if (!!opts.otherBgColor.replace(/\s/g, ""))
                    $(this).css({ "background-color": opts.otherBgColor });
                if (!!opts.otherBorder.replace(/\s/g, ""))
                    $(this).css({ "border": opts.otherBorder });
            });

            //设置ul相关属性
            $this.find("ul").css({
                "position": "absolute",
                "height": "inherit",
                "list-style": "none",
                "left": "0",
                "top": "0",
                "margin": "0",
                "padding": "0"
            });

            //设置li相关属性
            $this.find("ul li").css({
                "display": "block",
                "float": "left",
                "height": "inherit",
                "overflow": "hidden",
                "margin": "0 " + opts.gapWidth + "px"
            });

            //设置内部三个div的共有样式
            $this.find("div").css({
                "float": "left",
                "height": "inherit"
            });

            //获得中间div的长度和ul的长度并设置
            var centerWidth = 0;
            var centerAllWidth = 0;
            if (opts.total > 0) {
                centerWidth = parseInt($this.find("ul li").eq(0).css("width").replace(/\D/g, ""), 10) * opts.showPage;     //中间div的总宽度
                centerAllWidth = parseInt($this.find("ul li").eq(0).css("width").replace(/\D/g, ""), 10) * $this.find("ul li").length;     //中间ul的总宽度
            }

            if (!!opts.gapWidth) {
                centerWidth += (opts.showPage + 1) * (opts.gapWidth * 2) - (opts.gapWidth * 2);
                centerAllWidth += (opts.total + 1) * (opts.gapWidth * 2) - (opts.gapWidth * 2);
            }

            //设置中间div宽度和其它样式
            $this.find(".centerBtns").css({
                "position": "relative",
                "overflow": "hidden",
                "width": centerWidth + "px"
            });

            //设置中间div内ul的宽度
            $this.find(".centerBtns ul").css({
                "width": centerAllWidth + "px"
            });

            //设置跳转div内的标签的属性
            $this.find(".jumpBtns>input").css({
                "width": opts.jumpInputWidth + "px",
                "float": "left",
                "height": opts.height + "px",
                "outline": "none",
                "text-align": "center",
                "border-radius": opts.cormer + "px",
                "border": opts.jumpBorder,
                "margin": "0 " + opts.gapWidth + "px",
                "color": opts.jumpFontColor,
                "line-height": opts.height + "px"
            });
            $this.find(".jumpBtns a").css({
                "background-color": opts.jumpBgColor,
                "margin": "0 " + opts.gapWidth + "px",
                "color": opts.jumpFontColor,
                "border": opts.jumpBorder
            }).hover(function () {
                if (!!opts.jumpFontHoverColor.replace(/\s/g, ""))
                    $(this).css({ "color": opts.jumpFontHoverColor });
                if (!!opts.jumpHoverBgColor.replace(/\s/g, ""))
                    $(this).css({ "background-color": opts.jumpHoverBgColor });
                if (!!opts.jumpHoverBorder.replace(/\s/g, ""))
                    $(this).css({ "border": opts.jumpHoverBorder });
            }, function () {
                if (!!opts.jumpFontColor.replace(/\s/g, ""))
                    $(this).css({ "color": opts.jumpFontColor });
                if (!!opts.jumpBgColor.replace(/\s/g, ""))
                    $(this).css({ "background-color": opts.jumpBgColor });
                if (!!opts.jumpBorder.replace(/\s/g, ""))
                    $(this).css({ "border": opts.jumpBorder });
            });

            //根据a标签是否有边框，设置最外部div的高度,ul和li等会继承这个高度
            if (!!opts.centerBorder.replace(/\s/g, "") && opts.centerBorder != "none")
                opts.height += parseInt(opts.centerBorder.split(" ")[0].replace(/\D/g, ""), 10) * 2;
            else if (!!opts.otherBorder.replace(/\s/g, "") && opts.otherBorder != "none")
                opts.height += parseInt(opts.otherBorder.split(" ")[0].replace(/\D/g, ""), 10) * 2;

            $this.css({ "height": opts.height + "px" });

            //注册两边按钮的事件
            $this.find(".otherBtns a").each(function (index, element) {
                $(element).click(function () {
                    if (opts.animation) {           //因为a标签可能比较多，故不用下面的遍历查找方法
                        if ($(element).attr("value") == "首页" && opts.currentPage != 1) {
                            if (opts.submitStyle == "href")
                                window.location.href = $this.find(".centerBtns a").first().prop("href");
                            else
                                $this.find(".centerBtns a").first().trigger("click");
                        }
                        else if ($(element).attr("value") == "上一页" && opts.currentPage != 1) {
                            if (opts.submitStyle == "href")
                                window.location.href = $this.find(".centerBtns a").eq(opts.currentPage - 2).prop("href");
                            else
                                $this.find(".centerBtns a").eq(opts.currentPage - 2).trigger("click");
                        }
                        else if ($(element).attr("value") == "下一页" && opts.currentPage != opts.total) {
                            if (opts.submitStyle == "href")
                                window.location.href = $this.find(".centerBtns a").eq(opts.currentPage).prop("href");
                            else
                                $this.find(".centerBtns a").eq(opts.currentPage).trigger("click");
                        }
                        else if ($(element).attr("value") == "尾页" && opts.currentPage != opts.total) {
                            if (opts.submitStyle == "href")
                                window.location.href = $this.find(".centerBtns a").last().prop("href");
                            else
                                $this.find(".centerBtns a").last().trigger("click");
                        }
                    }
                    else {                             //a标签比较少
                        if ($(element).attr("value") == "首页" && opts.currentPage != 1) {
                            if (opts.submitStyle == "href") {
                                if (opts.simpleType == 1)
                                    window.location.href = opts.url + getConnect() + opts.idParameter + "=1";
                                else
                                    window.location.href = $this.find(".centerBtns a").first().prop("href");
                            }
                            else {
                                if (opts.simpleType == 1) {
                                    poleClick = 1;
                                    $this.find(".centerBtns a").eq(1).trigger("click");
                                }
                                else
                                    $this.find(".centerBtns a").first().trigger("click");
                            }
                        }
                        else if ($(element).attr("value") == "尾页" && opts.currentPage != opts.total) {
                            if (opts.submitStyle == "href") {
                                if (opts.simpleType == 1)
                                    window.location.href = opts.url + getConnect() + opts.idParameter + "=" + opts.total;
                                else
                                    window.location.href = $this.find(".centerBtns a").last().prop("href");
                            }
                            else {
                                if (opts.simpleType == 1) {
                                    poleClick = opts.total;
                                    $this.find(".centerBtns a").eq(1).trigger("click");
                                }
                                else
                                    $this.find(".centerBtns a").last().trigger("click");
                            }
                        }
                        else if ($(element).attr("value") == "上一页" && opts.currentPage != 1) {
                            var entity = $this.find(".centerBtns a");
                            for (var item in entity) {
                                if (parseInt(entity.eq(item).attr("value"), 10) == (parseInt(opts.currentPage, 10) - 1)) {
                                    if (opts.submitStyle == "href")
                                        window.location.href = entity.eq(item).prop("href");
                                    else
                                        entity.eq(item).trigger("click");
                                    break;
                                }
                            }
                        }
                        else if ($(element).attr("value") == "下一页" && opts.currentPage != opts.total) {
                            var entity = $this.find(".centerBtns a");
                            for (var item in entity) {
                                if (parseInt(entity.eq(item).attr("value"), 10) == (parseInt(opts.currentPage, 10) + 1)) {
                                    if (opts.submitStyle == "href")
                                        window.location.href = entity.eq(item).prop("href");
                                    else
                                        entity.eq(item).trigger("click");
                                    break;
                                }
                            }
                        }
                    }
                });
            });

            //注册跳转div内的标签事件
            $this.find(".jumpBtns>input").on("keyup", function () {
                if (/^[1-9]\d*$/.test($(this).val()) || $(this).val() == "") {
                    if ($(this).val() != "" && parseInt($(this).val()) <= opts.total) {
                        $(this).attr({ "data": $(this).val() });
                    }
                    else if ($(this).val() == "") {
                        $(this).attr({ "data": $(this).val() });
                    }
                    else {
                        $(this).val($(this).attr("data"));
                    }
                }
                else {
                    $(this).val($(this).attr("data"));
                }
            });
            $this.find(".jumpBtns>a").click(function () {
                if (!/^[1-9]\d*$/.test($this.find(".jumpBtns>input").val()) || parseInt($this.find(".jumpBtns>input").val()) > opts.total) {
                    $this.find(".jumpBtns>input").val($this.find(".jumpBtns>input").attr("data"));
                    return;
                }
                if (opts.animation) {
                    $this.find(".centerBtns a").eq(parseInt($this.find(".jumpBtns>input").val()) - 1).trigger("click");
                }
                else {
                    if (opts.submitStyle == "href") {
                        var _connect = "";
                        if (opts.url.indexOf("?") == -1)
                            _connect = "?";
                        else
                            _connect = "&";
                        window.location.href = opts.url + _connect + opts.idParameter + "=" + $this.find(".jumpBtns>input").val();
                    }
                    else if ($this.find(".jumpBtns>input").val() == opts.total) {
                        if (opts.simpleType == 1) {
                            jumpClick++;
                            $this.find(".centerBtns a").eq(1).trigger("click");
                        }
                        else
                            $this.find(".centerBtns a").last().trigger("click");
                    }
                    else if ($this.find(".jumpBtns>input").val() == "1") {
                        if (opts.simpleType == 1) {
                            jumpClick++;
                            $this.find(".centerBtns a").eq(1).trigger("click");
                        }
                        else
                            $this.find(".centerBtns a").first().trigger("click");
                    }
                    else if (!!$this.find(".jumpBtns>input").val()) {
                        jumpClick++;
                        $this.find(".centerBtns a").eq(1).trigger("click");
                    }
                }
            });

            //两侧按钮在特殊情况的cursor
            if (opts.total <= 1) {
                $this.find(".otherBtns a").css({ "cursor": "not-allowed" });
            }

            //href模式下设置所有a标签的href
            function setHref() {
                if (opts.submitStyle == "href") {
                    $this.find(".centerBtns a").each(function (i, e) {
                        $(this).attr({ "href": opts.url + getConnect() + opts.idParameter + "=" + $(this).attr("value") });
                    });
                }
            }

            //根据参数同步插件显示
            function draw(total, currentPage) {
                if (opts.total != Number(total) || opts.currentPage != Number(currentPage)) {
                    $this.empty();
                    opts.total = Number(total);
                    opts.currentPage = Number(currentPage);
                    opts.firstAjaxSubmit = false;
                    opts.isNewDraw = true;
                    opts.showPage = oldShowPage;
                    opts.height = oldHeight;
                    $this = $this.paging(opts);
                }
            }

            //初始化显示
            if (opts.animation)
                $this.find(".centerBtns a").eq(opts.currentPage - 1).trigger("click");   //初始化后自动展示到当前页，方式为滚动
            else {
                $this.find(".centerBtns a").first().trigger("click");            //初始化后自动展示到当前页，方式为精简
                initial = false;
            }

            return PageAPI;

            /////////////////////////代码内部

            //});
        }
    });
})(jQuery, window, document);