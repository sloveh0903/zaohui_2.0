<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:82:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/statistics/user.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;}*/ ?>
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
<link rel="stylesheet" href="/public/gzadmin/css/data_analyse.css">

	<body>
	<div class="article_manage">
      <!-- 9.13 替换右侧头部 -->
      <div class="right-side-header clearfix">
        <span>数据分析</span>
        <div class="user-box">
          

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

      <!--用户管理选项卡-->
      <div class="system_guanli_div data_guanli_div ">
        	<ul class="system_guanli_ul user_edit_ul">
	           	<li><a href="<?php echo url('index'); ?>"  data-src="data_analyse">交易统计</a></li>
	            <li><a href="<?php echo url('user'); ?>"  class="active" data-src="data_analyse">用户统计</a></li>
	            <li><a href="<?php echo url('course'); ?>" data-src="data_analyse">课程统计</a></li>
        	</ul>
      </div>
      <div class="data_analyse_content">
        <!--数据分析详情一级模块-->
            <!--选项卡详情-->
		  <ul class="tab_main">
			  <!--交易统计-->
			  <li class="user_count" style="display: block">
                    <!--用户数据-->
                    <div class="trade_count_data bgWhite mb20">
                        <!--数据表头-->
                        <div class="count_header ">
                            <div class="data_header">
                                <h2>用户数据</h2>
                            </div>
                            <!--数据范围-->
                            <div class="data_range">
                              <label for="tradetoday" class="cur">
                                  <input type="checkbox" name="date" value="today" class="button" id="tradetoday" checked=""/>
                                  <span>今日</span>
                              </label>
                              <label for="tradeseven">
                                  <input type="checkbox" name="date" value="seven" class="button" id="tradeseven" checked=""/>
                                  <span>7天</span>
                              </label>
                              <label for="tradethirty">
                                  <input type="checkbox" name="date" value="thirty" class="button" id="tradethirty" checked=""/>
                                  <span>30天</span>
                              </label>
                              <label for="tradeall">
                                  <input type="checkbox" name="date" value="all" class="button" id="tradeall" checked=""/>
                                  <span>全部</span>
                              </label>
                                <div class="layui-inline" >
                                    <input type="text" class="layui-input2" id="date_range2" placeholder="2017/9/01 - 2017/10/30">
                                    <span class="arr_down arr2"></span>
                                </div>
                            </div>
                        </div>
                        <!--数据具体模块-->
                        <div class="trader_data_list">
                            <div class="cout_data_list all_user_num floating">
                                <div class="list_details"><p><?php echo $user_data['total_user']; ?></p><span>用户数</span></div>
                            </div>
                            <div class="cout_data_list new_user_num floating">
                                <div class="list_details"><p><?php echo $user_data['new_user']; ?></p><span>新增用户</span></div>
                            </div>
                            <div class="cout_data_list paid_user_num floating">
                                <div class="list_details"><p><?php echo $user_data['pay_user']; ?></p><span>付费用户</span></div>
                            </div>
                            <div class="cout_data_list active_user_num floating">
                                <div class="list_details"><p><?php echo $user_data['today_user']; ?></p><span>活跃用户</span></div>
                            </div>
                        </div>
                    </div>  
                    <!--折线图-->
                    <div class="line_chart bgWhite mb20">
                        <!--数据表头-->
                        <div class="count_header  ">
                            <div class="data_header">
                                <h2>用户增长趋势分析</h2>
                            </div>
                            <!--数据范围-->
                            <div class="data_range">
                                <label for="seven" class="cur">
                                    <input type="checkbox" name="date" class="button" id="seven" checked=""/>
                                    <span>最近7天</span>
                                </label>
                                <label for="thirty">
                                    <input type="checkbox" name="date" class="button" id="thirty" checked=""/>
                                    <span>最近30天</span>
                                </label>
                            </div>
                        </div>
                        <!--折线图展示-->
                        <div id="user_line_show"></div>
                    </div>
                    <!--饼状图-->
                    <div class="user_pie_part">
                        <!--//环形图1-->
                        <div class="pie_chart bgWhite mb20">
                            <div class="count_header  ">
                                <div class="data_header">
                                    <h2>用户端口来源</h2>
                                </div>
                                <!--数据范围-->
                                <div class="data_range">
                                    <div class="layui-inline">
                                        <input type="text" class="layui-input3" id="monthSelect2" >
                                        <span class="arr_down arr1"></span>
                                    </div>
                                </div>
                            </div>
                            <!--饼状图展示-->
                            <div class="pie_show_user">                         
                                <!--饼状图-->
                                <div id="pie_show_user"></div>                          
                            </div>
                        </div>
                        <!--环形图2-->
                        <div class="pie_chart bgWhite mb20">
                            <div class="count_header  ">
                                <div class="data_header">
                                    <h2>付费用户和免费用户构成</h2>
                                    <p>用户总数：<?php echo $pay_user['total_user']; ?></p>
                                </div>                              
                            </div>
                            <!--饼状图展示-->
                            <div class="pie_show_user">                         
                                <!--饼状图-->
                                <div id="pie_show_user2"></div>                         
                            </div>
                        </div>
                    </div>
                    
                </li>

		  </ul>

	  </div>
   	</div>
	</body>
	<script type="text/javascript" src="/public/gzadmin/js/jquery-1.11.0.min.js"></script>
	<script type="text/javascript" src="/public/gzadmin/js/echarts.min.js"></script>
	<!--<script type="text/javascript" src="/public/gzadmin/js/data_analyse.js"></script>-->
	<script type="text/javascript" src="/public/gzadmin/js/laydate/laydate.js"></script>
<script>
    var reg_user = '<?php echo $pay_user['reg_user']; ?>'; //注册用户
    var order_user = '<?php echo $pay_user['order_user']; ?>'; //付费用户
    var pc_terminal = '<?php echo $terminal['pc']; ?>'; 
    var h5_terminal = '<?php echo $terminal['h5']; ?>'; 
    var wx_terminal = '<?php echo $terminal['wx']; ?>';
    var inc_user_time = '<?php echo $inc_user_time; ?>'; //用户增长时间
    var inc_user_number = '<?php echo $inc_user_number; ?>'; //注册用户增长数
    var pay_user_number = '<?php echo $pay_user_number; ?>'; //付费用户增长数
    $(function(){
    //alert("hh")
        //点击tab选项卡转换内容
        $(".system_guanli_div").on("click","li",function(){
            $(this).children("a").addClass("active")
            $(this).siblings().children("a").removeClass("active")
            var index= $(this).index()
            //alert(index)
            $(".tab_main>li").eq(index).addClass("cur")
            $(".tab_main>li").eq(index).siblings().removeClass("cur")           
            //切换到用户统计时立即初始化并调用统计表（否则读取不到宽度导致统计表宽度达不到100%）
            var hasclass=$(".user_count").hasClass("cur");
            //alert(hasclass)
            if(hasclass=true){
                var myChart3= echarts.init(document.getElementById('user_line_show'));//初始化图表
                myChart3.setOption(option3)
            }
        })

        //今天用户数据
        $('#tradetoday').click(function(){
            var url = '<?php echo url("getUserdata"); ?>';
            $.get(url,{ type: "json" ,date:'today'}, function(result){
                $('.all_user_num p').text(result.data.total_user);
                $('.new_user_num p').text(result.data.new_user);
                $('.paid_user_num p').text(result.data.pay_user);
                $('.active_user_num p').text(result.data.today_user);
            });
        })

        //七天用户数据
        $('#tradeseven').click(function(){
            var url = '<?php echo url("getUserdata"); ?>';
            $.get(url,{ type: "json" ,date:'seven'}, function(result){
                $('.all_user_num p').text(result.data.total_user);
                $('.new_user_num p').text(result.data.new_user);
                $('.paid_user_num p').text(result.data.pay_user);
                $('.active_user_num p').text(result.data.today_user);
            });
        })

        //三十天用户数据
        $('#tradethirty').click(function(){
            var url = '<?php echo url("getUserdata"); ?>';
            $.get(url,{ type: "json" ,date:'thirty'}, function(result){
                $('.all_user_num p').text(result.data.total_user);
                $('.new_user_num p').text(result.data.new_user);
                $('.paid_user_num p').text(result.data.pay_user);
                $('.active_user_num p').text(result.data.today_user);
            });
        })

        //全部用户数据
        $('#tradeall').click(function(){
            var url = '<?php echo url("getUserdata"); ?>';
            $.get(url,{ type: "json" ,date:'all'}, function(result){
                $('.all_user_num p').text(result.data.total_user);
                $('.new_user_num p').text(result.data.new_user);
                $('.paid_user_num p').text(result.data.pay_user);
                $('.active_user_num p').text(result.data.today_user);
            });
        })

   

          //用户统计日期范围选择器
          laydate.render({
            elem: '#date_range2'
            ,range: "-"
            ,format: 'yyyy/M/d'
            ,theme: '#00B6F2'
            ,done: function(value, date, endDate){
              var url = '<?php echo url("getUserdata"); ?>';
              $.get(url,{ type: "json", date: value }, function(result){
                    $('.all_user_num p').text(result.data.total_user);
                    $('.new_user_num p').text(result.data.new_user);
                    $('.paid_user_num p').text(result.data.pay_user);
                    $('.active_user_num p').text(result.data.today_user);
              });
            }
          });
                
          //用户统计月选择器
          laydate.render({
            elem: '#monthSelect2'
            ,value: new Date()
            ,type: 'month'
            ,format: 'yyyy/MM'
            ,theme: '#00B6F2'
            ,done: function(value, date, endDate){
              var url = '<?php echo url("getTerminal"); ?>';
              $.get(url,{ type: "json", date: value+'/1' }, function(result){
                    option4.series[0].data[0].value=result.data.h5; //公众号来源
                    option4.series[0].data[1].value=result.data.wx; //小程序来源
                    option4.series[0].data[2].value=result.data.pc; //pc来源
                    myChart4.setOption(option4);
              });
            }
          });
                
        //时间按钮选择器
        $(".data_range").on("click","label",function(){
            var state=$(this).children("input[name=date]").prop("checked");
            //alert(state)
            if(state=true){
                $(this).addClass("cur");
                $(this).siblings().children("input[name=date]").prop("checked",false);
                $(this).siblings().removeClass("cur")
            }
        })
    

    //课程统计
        //点击视频统计，视频统计模块出现
        $(".data_table").on("click","a",function(){
            $(".data_guanli_div").hide()
            $(".tab_main").hide()
            $(".video_count").show()
            $(".video_count .system_guanli_div").show()         
        })
        //点击课程统计，回到数据分析一级页面--课程统计
        $(".video_count>.system_guanli_div").on("click","a.active",function(e){
            e.stopPropagation();//阻止冒泡，因为重名（system_guanli_div）,触发a后会再触发一次li（跑一遍tab切换）,此时cur移到0位置，然后trigger再触发li,li触发后会重新刚才的a.active即从0 开始
            $(".video_count").hide()
            $(".data_guanli_div").show()            
            $(".tab_main").show()
            $('.system_guanli_div li').eq(2).trigger('click');//再触发一次li，跑一遍tab切换
        })
        
        //鼠标经过学习人数问号，float_box出现
        $(".course_user_num").on("mouseover",".help", function(){
            $(".float_div").show()
        })
        $(".course_user_num").on("mouseleave",".help", function(){
            $(".float_div").hide()
        })
            //学习用户详情
        //点击学习人数，弹框遮罩层出现
        $(".video_table ").on("click","td.course_user_num",function(){
            $(".user_data_list").css("display","flex")
        })
        //点击close按钮弹框消失
        $(".user_data_list").on("click",".data_list_close",function(){
            $(".user_data_list").hide()
        })
            
            
        //交易统计折线图
        
        //用户统计图折线图myChart3在切换时调用。13行
        //用户统计环形图1
        var myChart3= echarts.init(document.getElementById('user_line_show'));//初始化图表
        var myChart4 = echarts.init(document.getElementById('pie_show_user'));//初始化图表
        //用户统计环形图2
        var myChart5 = echarts.init(document.getElementById('pie_show_user2'));//初始化图表
        
                
        //用户统计折线图       
        var option3={
            textStyle:{
                fontSize:12,
                fontWeight:'nomal',
                color: "rgba(41, 43, 51, .4)"
                },
            tooltip: {
                trigger: 'axis',
                axisPointer: {
                    type: 'line',
                    lineStyle:{
                        color:"#E9F1F3",
                        width:1,
               
                    }                   
                },
                //更改提示框
                formatter:function(params){
                    
                        return("<p style='font-size:14px;margin:5px 10px'>"+params[0].name+"</p>"
                            +'<p style="font-size:14px;margin:5px 10px;margin-top:-1px;color:#55CE63">新增：'+params[0].value+'</p>'
                            +'<p style="font-size:14px;margin:5px 10px;margin-top:-7px;color:#009EFB">付费：'+params[1].value+'</p>'
                        )
                }
            },
            legend: {
                itemWidth: 6,
                itemHeight: 6,//图例icon的大小
                itemGap: 20,//多个图例之间的距离
                left:"left",//浮动到左端，还有center，right
                data:[
                    {name:'新增用户',
                    textStyle:{
                        fontSize:12,
                        fontWeight:'nomal',
                        color: "rgba(41, 43, 51, .4)"
                        },
                    icon:'circle',
                    },
                    {name:'付费用户',
                    textStyle:{
                        fontSize:12,
                        fontWeight:'nomal',
                        color: "rgba(41, 43, 51, .4)"
                        },
                    icon:'circle',//图例的icon样式 'circle', 'rect', 'roundRect', 'triangle', 'diamond', 'pin', 'arrow'，还可以引入图片
                    }
                ]
            },
            grid: {
                left: '-2%',
                right: '1%',
                bottom: '3%',
                containLabel: true,//是否包含坐标轴的刻度标签。              
            },
            
            xAxis: {
                type: 'category',
                axisLine:{
                    show:false,
                    onZero: false,
                    boundaryGap: true,
                    lineStyle: {
                        color: "rgba(41, 43, 51, .17)",
                        type:"dotted"
                        }
                },
                axisTick:{
                    show:false
                },
                data:eval(inc_user_time)
            },
            yAxis: {
                type: 'value',
                min:0,
                max:"<?php echo $max; ?>",
                axisLine:{
                    show:false,//轴线不显示
                    onZero: true,
                    lineStyle: {
                        color: "rgba(41, 43, 51, .17)"//线的样式
                        }
                },
                axisTick:{
                    show:false
                },
                splitLine: {
                    lineStyle: {
                        // 使用深浅的间隔色
                        color: 'rgba(41, 43, 51, .17)',
                        type:"dotted"
                    },
            
                },
                axisLabel:{
                    verticalAlign:"bottom",
                    padding:[-25,-25,10 ,-32]
                }
            },
            series: [
                {
                    name:'新增用户',
                    type:'line',
                    stack: '新增用户总量',
                    smooth: true,
                    symbol:"circle",
                    symbolSize:10,
                    symbolOffset:[0, '-20%'],
                    itemStyle: {
                        normal: {
                            color: "#55CE63",  // 会设置点和线的颜色，所以需要下面定制 line
                            borderColor: "#ffffff"  // 点边线的颜色
                        }
                    },
                    lineStyle: {
                        normal: {
                            color: "#55CE63"   // 线条颜色
                        }
                    },
                   
                    data:eval(inc_user_number)
                },
                {
                    name:'付费用户',
                    type:'line',
                    stack: '付费用户总量',
                    smooth: true,
                    symbol:"circle",
                    symbolSize:10,
                    symbolOffset:[0, '20%'],
                    itemStyle: {
                        normal: {
                            color: "#009EFB",  // 会设置点和线的颜色，所以需要下面定制 line
                            borderColor: "#ffffff"  // 点边线的颜色
                        }
                    },
                    lineStyle: {
                        normal: {
                            color: "#009EFB"   // 线条颜色
                        }
                    },
                    data:eval(pay_user_number)
                }
            ]
        };
        myChart3.setOption(option3);
        //用户统计环形图1      
        var option4={           
                tooltip: {
                    trigger: 'item',
                    formatter: "{a} <br/>{b}: {c} ({d}%)",
                    padding: 10
                },
                legend:{
                    itemWidth:12,
                    itemHeight:12,//图例icon的大小
                    itemGap: 12,//多个图例之间的距离
                    orient:'vertical',//图例列表的布局朝向。'horizontal'
                    top:80,
                    right:20,
                    data:[
                        {name:'微信公众号',                      
                        icon:'circle',                          
                        },
                        {name:'小程序',
                        icon:'circle'
                        },
                        {name:'PC端',
                        icon:'circle',
                        },
                        {name:'线下导入',
                        icon:'circle',
                        }
                    ]
                },
                
                series: [
                    {
                        name:'端口来源',
                        type:'pie',
                        radius: ['70%', '80%'],                  
                        avoidLabelOverlap: false,//是否启用防止标签重叠策略，默认开启，在标签拥挤重叠的情况下会挪动各个标签的位置，防止标签间的重叠。如果不需要开启该策略，例如圆环图这个例子中需要强制所有标签放在中心位置，可以将该值设为 false。
                        label:{
                            normal: {
                                show: true,
                                position: 'center',
                                formatter:"{img|}" ,
                                rich:{
                                    img:{
                                        backgroundColor:{
                                             image:"/public/gzadmin/images/person@2x.png" 
                                        },
                                        width:30,
                                        height:30
                                    }
                                }
                                
                            },
                            emphasis: {
                                show: true,
                                position: 'center',
                                formatter:"{font|{b}}" ,
                                rich:{                                  
                                    font:{
                                        fontSize: '18',
                                        color:"rgba(41, 43, 51, .6)",
                                        height:30,
                                        backgroundColor:"#fff",
                                        
                                    }
                                    
                                }
                            }
                        },
                        labelLine: {
                            normal: {
                                show: false,
                            }
                        },
                        itemStyle: {
                            emphasis: {
                                //shadowBlur: 2,
//                              shadowOffsetX: 0,
//                              shadowColor: 'rgba(0, 0, 0, 0.5)',
//                              borderWidth:8
                            }
                        },
                        clockwise:false,
                        startAngle:45,
                        hoverOffset:10,
                        center: ['30%', '50%'],
                        data:[
                            {
                                value:h5_terminal,
                                name:'微信公众号',
                                itemStyle: {
                                    normal: {
                                        color: "#745AF2", 
                                        borderColor: "#ffffff"  
                                    }
                                }
                            },
                            {
                                value:wx_terminal,
                                name:'小程序',
                                itemStyle: {
                                    normal: {
                                        color: "#24B6F7", 
                                        borderColor: "#ffffff" 
                                    }
                                }
                            },
                            {
                                value:pc_terminal,
                                name:'PC端',
                                itemStyle: {
                                    normal: {
                                        color: "#6897B1", 
                                        borderColor: "#ffffff"  
                                    }
                                }
                            }
                        ]
                    }
                ]

        };
        myChart4.setOption(option4);
        
        //用户统计环形图2      
        var option5={           
                tooltip: {
                    trigger: 'item',
                    formatter: "{b}: {c} ({d}%)",
                    padding: 10
               }, 
                legend:{
                    itemWidth:12,
                    itemHeight:12,//图例icon的大小
                    itemGap: 12,//多个图例之间的距离
                    orient:'vertical',//图例列表的布局朝向。'horizontal'
                    top:80,
                    right:20,
                    data:[
                        {name:'免费用户',                      
                        icon:'circle',                          
                        },
                        {name:'付费用户',
                        icon:'circle'
                        },
                   
                    ]
                },              
                series: [
                    {
                        name:'用户构成',
                        type:'pie',
                        radius: ['70%', '80%'],//前面数值改变环的粗细，后面改变整个圆的大小                      
                        avoidLabelOverlap: false,//是否启用防止标签重叠策略，默认开启，在标签拥挤重叠的情况下会挪动各个标签的位置，防止标签间的重叠。如果不需要开启该策略，例如圆环图这个例子中需要强制所有标签放在中心位置，可以将该值设为 false。
                        label:{
                            normal: {
                                show: true,
                                position: 'center',
                                formatter:"{img|}" ,
                                rich:{
                                    img:{
                                        backgroundColor:{
                                             image:"/public/gzadmin/images/people@2x.png" 
                                        },
                                        width:30,
                                        height:30
                                    }
                                }
                                
                            },
                            emphasis: {
                                show: true,
                                position: 'center',
                                formatter:"{font|{b}}" ,
                                rich:{                                  
                                    font:{
                                        fontSize: '18',
                                        color:"rgba(41, 43, 51, .6)",
                                        height:30,
                                        backgroundColor:"#fff",
                                        
                                    }
                                    
                                }
                            }
                        },
                        labelLine: {
                            normal: {
                                show: false,
                            }
                        },
                        clockwise:false,
                        startAngle:45,
                       // hoverAnimation:false,                       
                        center: ['30%', '50%'],
                        data:[
                            {
                                value:reg_user,
                                name:'免费用户',
                                itemStyle: {
                                    normal: {
                                        color: "#745AF2", 
                                        borderColor: "#ffffff"  
                                    }
                                }
                            },
                            
                            {
                                value:order_user,
                                name:'付费用户',
                                itemStyle: {
                                    normal: {
                                        color:"#24B6F7", 
                                        borderColor: "#ffffff" 
                                    }
                                }
                            },                                                                                                          
                        ]
                    }
                ]
        };
        myChart5.setOption(option5);

        //最近七天
        $('#seven').click(function(){
            $('#thirty').removeClass('cur');
            $(this).addClass('cur');
            var url = '<?php echo url("getIncuser"); ?>';
            $.get(url,{ type: "json", day: 7 }, function(result){
                option3.series[0].data = result.data.inc_user; //新增用户
                option3.series[1].data = result.data.pay_user; //付费用户
                option3.xAxis.data = result.data.date;
                option3.yAxis.max = result.data.max_day;
                
                myChart3.setOption(option3);
            });
        }) 
        
        //最近三十天
        $('#thirty').click(function(){
            $('#seven').removeClass('cur');
            $(this).addClass('cur');
            var url = '<?php echo url("getIncuser"); ?>';
            $.get(url,{ type: "json", day: 30 }, function(result){
                option3.series[0].data = result.data.inc_user; //新增用户
                option3.series[1].data = result.data.pay_user; //付费用户
                option3.xAxis.data = result.data.date;
                option3.yAxis.max = result.data.max_day;
                myChart3.setOption(option3);
            });
        })    
})
</script>
</html>
