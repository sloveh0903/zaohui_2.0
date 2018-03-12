<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:83:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/statistics/index.html";i:1518064645;s:80:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/header.html";i:1518064645;s:79:"/home/wwwroot/lamp-2.4/domain/qusu/web/application/admin/view/common/admin.html";i:1518064645;}*/ ?>
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
	           	<li><a href="<?php echo url('index'); ?>" class="active" data-src="data_analyse">交易统计</a></li>
	            <li><a href="<?php echo url('user'); ?>" data-src="data_analyse">用户统计</a></li>
	            <li><a href="<?php echo url('course'); ?>" data-src="data_analyse">课程统计</a></li>
        	</ul>
      </div>
      <div class="data_analyse_content">
        <!--数据分析详情一级模块-->
            <!--选项卡详情-->
		  <ul class="tab_main">
			  <!--交易统计-->
			  <li class="trade_count cur">
				  <!--交易数据-->
				  <div class="trade_count_data bgWhite mb20">
					  <!--数据表头-->
					  <div class="count_header ">
						  <div class="data_header">
							  <h2>交易数据</h2>
							  <p>总收入¥<?php echo $total; ?></p>
						  </div>
						  <!--数据范围-->
						  <div class="data_range trade">
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
								  <input type="text" class="layui-input2" id="date_range1" placeholder="2017/9/01 - 2017/10/30">
								  <span class="arr_down arr2"></span>
							  </div>
						  </div>
					  </div>
					  <!--数据具体模块-->
					  <div class="trader_data_list">
						  <div class="cout_data_list trader_subprice floating">
							  <div class="list_details total"><p>¥<?php echo $tradeInfo['total']; ?></p><span>收入</span></div>
						  </div>
						  <div class="cout_data_list trader_money floating">
							  <div class="list_details onlineTotal">
								  <p>¥<?php echo $tradeInfo['onlineTotal']; ?></p><span>线上收入</span>
							  </div>
							  <div class="list_details offlineTotal">
								  <p>¥<?php echo $tradeInfo['offlineTotal']; ?></p><span>线下收入</span>
							  </div>
						  </div>
						  <div class="cout_data_list trader_num floating">
							  <div class="list_details onlineOrders">
								  <p><?php echo $tradeInfo['onlineOrders']; ?></p><span>线上订单</span>
							  </div>
							  <div class="list_details offlineOrders">
								  <p><?php echo $tradeInfo['offlineOrders']; ?></p><span>线下订单</span>
							  </div>
						  </div>
					  </div>
				  </div>
				  <!--折线图-->
				  <div class="line_chart bgWhite mb20">
					  <!--数据表头-->
					  <div class="count_header  ">
						  <div class="data_header">
							  <h2>收入增长趋势分析</h2>
						  </div>
						  <!--数据范围-->
						  <div class="data_range income">
							  <label for="seven" class="cur">
								  <input type="checkbox" value="7" name="date" class="button" id="seven" checked=""/>
								  <span>最近7天</span>
							  </label>
							  <label for="thirty">
								  <input type="checkbox" value="30" name="date" class="button" id="thirty" checked=""/>
								  <span>最近30天</span>
							  </label>
						  </div>
					  </div>
					  <!--折线图展示-->
					  <div id="line_show"></div>
				  </div>
				  <!--饼状图-->
				  <div class="pie_chart bgWhite mb20">
					  <div class="count_header  ">
						  <div class="data_header">
							  <h2>订单来源构成</h2>
						  </div>
						  <!--数据范围-->
						  <div class="data_range">
							  <div class="layui-inline" >
								  <input type="text" class="layui-input3" id="monthSelect1" >
								  <span class="arr_down arr2"></span>
							  </div>
						  </div>
					  </div>
					  <!--饼状图展示-->
					  <div class="pie_show">
						  <!--饼状图-->
						  <div id="pie_show"></div>
						  <!--图表-->
						  <div class="paie_table">
							  <table >
								  <thead>
								  <tr>
									  <th class="orient_port">来源端口</th>
									  <th class="order_num">付款订单</th>
									  <th class="num_than">较前一月</th>
									  <th class="subPrice">付款金额</th>
									  <th class="price_than">较前一月</th>
								  </tr>
								  </thead>
								  <tbody class="tb">
								  <?php if(is_array($ret_data) || $ret_data instanceof \think\Collection || $ret_data instanceof \think\Paginator): $i = 0; $__LIST__ = $ret_data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
								  <tr>
									  <td class="orient_port"><?php echo $v['title']; ?></td>
									  <td class="order_num"><?php echo $v['order_num']; ?></td>
									  <td class="num_than">
										  <?php if($v['orderpercent'] == '0'): ?>
										  <div class="nun">-</div>
										  <?php else: ?>
										  <div class="num_iterm">
											  <i class="<?php echo $v['orderrate']; ?>"></i>
											  <span><?php echo $v['orderpercent']; ?>%</span>
										  </div>
										  <?php endif; ?>
									  </td>
									  <td class="subPrice">￥<?php echo $v['total']; ?></td>
									  <td class="price_than">
										  <?php if($v['totalpercent'] == '0'): ?>
										  <div class="nun">-</div>
										  <?php else: ?>
										  <div class="num_iterm">
											  <i class="<?php echo $v['totalrate']; ?>"></i>
											  <span><?php echo $v['totalpercent']; ?>%</span>
										  </div>
										  <?php endif; ?>
									  </td>
								  </tr>
								  <?php endforeach; endif; else: echo "" ;endif; ?>
								  </tbody>
							  </table>
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
    $(function(){
        //alert("hh")
        //点击tab选项卡转换内容
        var host = 'http://' + window.location.host + '/api/';
		var date = '<?php echo $date; ?>';
		var incomeinfo = '<?php echo $incomeInfo; ?>';
		var offlineinfo = '<?php echo $offlineInfo; ?>';


        //交易统计日期范围选择器
        laydate.render({
            elem: '#date_range1'
            ,range: "-"
            ,format: 'yyyy/M/d'
            ,theme: '#00B6F2'
			,done:function (value,date,endDate) {
                $.get(host+"order/Trade?daterange="+value, function(result){
                    if(result.code == 1){
                        $('.total').find('p').html('￥'+result.data.total);
                        $('.onlineTotal').find('p').html('￥'+result.data.onlineTotal);
                        $('.offlineTotal').find('p').html('￥'+result.data.offlineTotal);
                        $('.onlineOrders').find('p').html(result.data.onlineOrders);
                        $('.offlineOrders').find('p').html(result.data.offlineOrders);
                    }
				});
                $('.trade').find('label').removeClass('cur');
            }
        });


        //交易统计月选择器
        laydate.render({
            elem: '#monthSelect1'
            ,value: new Date()
            ,type: 'month'
            ,format: 'yyyy/MM'
            ,theme: '#00B6F2'
            ,done: function(value, date, endDate){
                var wechatvalue,miniwechatvalue,pcvalue,importvalue,str;
                $.get(host+"order/OrderSource?date="+value, function(result){
                    if(result.code == 1){
                        $.each(result.data,function(j,item){
                            switch (item.source){
								case 'wechat':
								    wechatvalue = item.order_num;
								    break;
                                case 'miniwechat':
                                    miniwechatvalue = item.order_num;
                                    break;
                                case 'pc':
                                    pcvalue = item.order_num;
                                    break;
                                case 'import':
                                    importvalue = item.order_num;
                                    break;
							}
                            if(item.orderpercent > 0){
								orderpercent = '<div class="num_iterm"><i class="'+item.orderrate+'"></i><span>'+item.orderpercent+'%</span></div>';
							}else{
                                orderpercent = '<div class="nun">-</div>'
							}
                            if(item.totalpercent > 0){
                                totalpercent = '<div class="num_iterm"><i class="'+item.totalrate+'"></i><span>'+item.totalrate+'%</span></div>';
                            }else{
                                totalpercent = '<div class="nun">-</div>'
                            }
                            str = str + '<tr>' +
								'<td class="orient_port">'+item.title+'</td>' +
								'<td class="order_num">'+item.order_num+'</td>' +
								'<td class="num_than">' +
                                orderpercent +
								'</td>' +
								'<td class="subPrice">￥'+item.total+'</td>' +
								'<td class="price_than">' +
                                totalpercent +
								'</td></tr>'
						});
                        $('.tb').html(str);

                        option2 = {
                            series: [
                                {
                                    data:[
                                        {
                                            value:wechatvalue,
                                            name:'微信公众号',
                                            itemStyle: {
                                                normal: {
                                                    color: "#745AF2",
                                                    borderColor: "#ffffff"
                                                }
                                            }
                                        },
                                        {
                                            value:miniwechatvalue,
                                            name:'小程序',
                                            itemStyle: {
                                                normal: {
                                                    color: "#24B6F7",
                                                    borderColor: "#ffffff"
                                                }
                                            }
                                        },
                                        {
                                            value:pcvalue,
                                            name:'PC端',
                                            itemStyle: {
                                                normal: {
                                                    color: "#6897B1",
                                                    borderColor: "#ffffff"
                                                }
                                            }
                                        },
                                        {
                                            value:importvalue,
                                            name:'线下导入',
                                            itemStyle: {
                                                normal: {
                                                    color: "#D8E3E8",
                                                    borderColor: "#ffffff"
                                                }
                                            }
                                        }
                                    ]
                                }

                            ]
                        };
                        myChart2.setOption(option2);

                    }
                });
            }
        });


        $(".trade").on("click","label",function(e){
			if (e.target.nodeName == 'INPUT') {
			    return;
            }
            var state=$(this).children("input[name=date]").prop("checked");
            var value = $(this).children("input[name=date]").val();

            $.get(host+"order/Trade?date="+value, function(result){
                if(result.code == 1){
					$('.total').find('p').html('￥'+result.data.total);
					$('.onlineTotal').find('p').html('￥'+result.data.onlineTotal);
					$('.offlineTotal').find('p').html('￥'+result.data.offlineTotal);
					$('.onlineOrders').find('p').html(result.data.onlineOrders);
					$('.offlineOrders').find('p').html(result.data.offlineOrders);
				}
			});
            if(state = true){
                $(this).addClass("cur");
                $(this).siblings().children("input[name=date]").prop("checked",false);
                $(this).siblings().removeClass("cur")
            }
            e.stopPropagation();
        })


        //时间按钮选择器
        $(".income").on("click","label",function(e){
            if (e.target.nodeName == 'INPUT') {
                return;
            }
            var state=$(this).children("input[name=date]").prop("checked");
            //alert(state)
			console.log('dd');
			var days = $(this).children("input[name=date]").val();
            $.get(host+"order/income?days="+days, function(result){
                if(result.code == 1){
                    option1 = {
                        xAxis: {
                            data:result.data.date.split(',')
                        },
                        yAxis:{
                            max:result.data.max_day
                        },
                        series: [
                            {
                                data:result.data.incomeInfo.split(',')
                            },
                            {
                                data:result.data.offlineInfo.split(',')
                            }
                        ]
                    };
                    myChart1.setOption(option1);
				}
			});

            if(state = true){
                $(this).addClass("cur");
                $(this).siblings().children("input[name=date]").prop("checked",false);
                $(this).siblings().removeClass("cur")
            }
        })




        //交易统计折线图
        var myChart1 = echarts.init(document.getElementById('line_show'));
        //交易统计图环形图
        var myChart2 = echarts.init(document.getElementById('pie_show'));

        var option1 = {
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
                formatter:function(params){
                    return("<p style='font-size:14px;margin:5px 10px'>"+params[0].name+"</p>"
                        +'<p style="font-size:14px;margin:5px 10px;margin-top:-1px;color:#55CE63">线上：'+params[0].value+'</p>'
                        +'<p style="font-size:14px;margin:5px 10px;margin-top:-7px;color:#009EFB">线下：'+params[1].value+'</p>'
                    )
                }
            },
            legend: {
                itemWidth: 6,
                itemHeight: 6,
                itemGap: 20,
                left:"left",
                data:[
                    {name:'线上收入',
                        textStyle:{
                            fontSize:12,
                            fontWeight:'nomal',
                            color: "rgba(41, 43, 51, .4)"
                        },
                        icon:'circle',
                    },
                    {name:'线下收入',
                        textStyle:{
                            fontSize:12,
                            fontWeight:'nomal',
                            color: "rgba(41, 43, 51, .4)"
                        },
                        icon:'circle'
                    }
                ]
            },
            grid: {
                left: '-2%',
                right: '1%',
                bottom: '3%',
                containLabel: true
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
                data:date.split(',')
            },
            yAxis: {
                type: 'value',
                min:0,
                max:<?php echo $max_day; ?>,
                axisLine:{
                    show:false,
                    onZero: true,
                    lineStyle: {
                        color: "rgba(41, 43, 51, .17)"
                    }
                },
                axisTick:{
                    show:false
                },
                splitLine: {
                    lineStyle: {
                        color: 'rgba(41, 43, 51, .17)',
                        type:"dotted"
                    },

                },
                axisLabel:{
                    verticalAlign:"bottom",
                    padding:[-25,-25,10 ,-32]
                }
            },
            series: [//数据
                {
                    name:'线上收入',
                    type:'line',
                    stack: '线上收入总量',
                    smooth: true,
                    symbol:"circle",
                    symbolSize:10,
                    symbolOffset:[0, '-20%'],
                    itemStyle: {
                        normal: {
                            color: "#55CE63",
                            borderColor: "#ffffff"
                        }
                    },
                    lineStyle: {
                        normal: {
                            color: "#55CE63"
                        }
                    },

                    data:incomeinfo.split(',')
                },
                {
                    name:'线下收入',
                    type:'line',
                    stack: '线下收入总量',
                    smooth: true,
                    symbol:"circle",
                    symbolSize:10,
                    symbolOffset:[0, '20%'],
                    itemStyle: {
                        normal: {
                            color: "#009EFB",
                            borderColor: "#ffffff"
                        }
                    },
                    lineStyle: {
                        normal: {
                            color: "#009EFB"
                        }
                    },
                    data:offlineinfo.split(',')
                }
            ]
        };
        myChart1.setOption(option1);

        var option2={
            tooltip: {
                trigger: 'item',
                formatter: "{a} <br/>{b}: {c} ({d}%)",
                padding: 10
            },
            legend:{
                itemWidth:12,
                itemHeight:12,
                itemGap: 12,
                orient:'vertical',
                top:80,
                right:20,
                data:[
                    {
                        name:'微信公众号',
                        icon:'circle',
                    },
                    {
                        name:'小程序',
                        icon:'circle'
                    },
                    {
                        name:'PC端',
                        icon:'circle',
                    },
                    {
                        name:'线下导入',
                        icon:'circle',
                    }
                ]
            },

            series: [
                {
                    name:'订单来源',
                    type:'pie',
                    radius: ['70%', '80%'],
                    avoidLabelOverlap: false,
                    label:{
                        normal: {
                            show: true,
                            position: 'center',
                            formatter:"{img|}" ,
                            rich:{
                                img:{
                                    backgroundColor:{
                                        image:"/public/gzadmin/images/pie_log@2x.png"
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
                    hoverOffset:10,
                    center: ['32%', '50%'],
                    data:[
                        {
                            value:'<?php echo $sourseData['wechat']['order_num']; ?>',
                            name:'微信公众号',
                            itemStyle: {
                                normal: {
                                    color: "#745AF2",
                                    borderColor: "#ffffff"
                                }
                            }
                        },
                        {
                            value:'<?php echo $sourseData['miniwechat']['order_num']; ?>',
                            name:'小程序',
                            itemStyle: {
                                normal: {
                                    color: "#24B6F7",
                                    borderColor: "#ffffff"
                                }
                            }
                        },
                        {
                            value:'<?php echo $sourseData['pc']['order_num']; ?>',
                            name:'PC端',
                            itemStyle: {
                                normal: {
                                    color: "#6897B1",
                                    borderColor: "#ffffff"
                                }
                            }
                        },
                        {
                            value:'<?php echo $sourseData['import']['order_num']; ?>',
                            name:'线下导入',
                            itemStyle: {
                                normal: {
                                    color: "#D8E3E8",
                                    borderColor: "#ffffff"
                                }
                            }
                        }


                    ]
                }
            ]

        };
        myChart2.setOption(option2);


    })
</script>
</html>
