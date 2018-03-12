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

	//交易统计与用户统计
		//时间选择器
	
		
		
		
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
		var myChart1 = echarts.init(document.getElementById('line_show'));//初始化图表
		//交易统计图环形图
		var myChart2 = echarts.init(document.getElementById('pie_show'));//初始化图表
		//用户统计图折线图myChart3在切换时调用。13行
		//用户统计环形图1
		var myChart4 = echarts.init(document.getElementById('pie_show_user'));//初始化图表
		//用户统计环形图2
		var myChart5 = echarts.init(document.getElementById('pie_show_user2'));//初始化图表
		
		
        	// 指定图表的配置项和数据
        var option1 = {
		    textStyle:{//全局的字体样式
              	fontSize:12,
                fontWeight:'nomal',
                color: "rgba(41, 43, 51, .4)"
            	},
		    tooltip: {
		        trigger: 'axis',//提示框的触发方式，axis坐标轴触发，主要在柱状图，折线图等会使用类目轴的图表中使用。item数据项图形触发，主要在散点图，饼图等无类目轴的图表中使用。
		        axisPointer: {//提示线的样式
		            type: 'line',
		            lineStyle:{
		            	color:"#E9F1F3",
						width:1,
		       
		            }		            
		        },
		        //更改提示框
	            formatter:function(params){//params和echarts的series是关联的,
	            	//seriesName：系列名称(legend中的图标加名字)
					//value：当前数据值
					//name：数据名，类目名（上述柱状图中表示当前横坐标数据名）
	            	
	            		return("<p style='font-size:14px;margin:5px 10px'>"+params[0].name+"</p>"
	            			+'<p style="font-size:14px;margin:5px 10px;margin-top:-1px">线上：'+params[0].value+'</p>'
	            			+'<p style="font-size:14px;margin:5px 10px;margin-top:-7px">线下：'+params[1].value+'</p>'
	            		)
	            }
		    },
		    legend: {//图例样式
		    	itemWidth: 6,
        		itemHeight: 6,//图例icon的大小
        		itemGap: 20,//多个图例之间的距离
		    	left:"left",//浮动到左端，还有center，right
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
		        	icon:'circle',//图例的icon样式 'circle', 'rect', 'roundRect', 'triangle', 'diamond', 'pin', 'arrow'，还可以引入图片
		        	}
		        ]
		    },
		    grid: {//图表相对于外部盒子的位置
		        left: '-2%',
		        right: '1%',
		        bottom: '3%',
		        containLabel: true,//是否包含坐标轴的刻度标签。		        
		    },
		    
		    xAxis: {
		        type: 'category',
		        axisLine:{//坐标轴线的操作
			        show:false,
	                onZero: false,//X 轴或者 Y 轴的轴线是否在另一个轴的 0 刻度上，只有在另一个轴为数值轴且包含 0 刻度时有效。
	                boundaryGap: true,//true，这时候刻度只是作为分隔线，标签和数据点都会在两个刻度之间的带(band)中间。
	                lineStyle: {
	                    color: "rgba(41, 43, 51, .17)",
	                    type:"dotted"
	                	}
            	},
            	axisTick:{//刻度是否显示
            		show:false
            	},
		        data:['2017/06/05','2017/06/06','2017/06/07','2017/06/08','2017/06/09','2017/06/10','2017/06/11']
		    },
		    yAxis: {
		        type: 'value',
		        min:0,
		        max:50,
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
            	splitLine: {//坐标系中分割线操作
				    lineStyle: {
				        // 使用深浅的间隔色
				        color: 'rgba(41, 43, 51, .17)',
				        type:"dotted"
				    },
			
				},
				axisLabel:{//刻度值的操作
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
			                color: "#55CE63",  // 会设置点和线的颜色，所以需要下面定制 line
			                borderColor: "#ffffff"  // 点边线的颜色
			            }
			        },
			        lineStyle: {
			            normal: {
			                color: "#55CE63"   // 线条颜色
			            }
			        },
			       
		            data:[12, 13, 10, 13, 9, 23, 21]
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
			                color: "#009EFB",  // 会设置点和线的颜色，所以需要下面定制 line
			                borderColor: "#ffffff"  // 点边线的颜色
			            }
			        },
			        lineStyle: {
			            normal: {
			                color: "#009EFB"   // 线条颜色
			            }
			        },
		            data:[22, 18, 45, 23, 29, 3, 31]
		        }
		    ]
		};
        myChart1.setOption(option1);
				
		//交易统计环形图		
		var option2={         	
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
			            name:'访问来源',
			            type:'pie',
			            radius: ['70%', '80%'],//前面数值改变环的粗细，后面改变整个圆的大小			            
			            avoidLabelOverlap: false,//是否启用防止标签重叠策略，默认开启，在标签拥挤重叠的情况下会挪动各个标签的位置，防止标签间的重叠。如果不需要开启该策略，例如圆环图这个例子中需要强制所有标签放在中心位置，可以将该值设为 false。
			            label:{//饼图图形上的文本标签，可用于说明图形的一些数据信息，比如值，名称等，鼠标经过出现说明
			                normal: {
			                    show: false,
			                    position: 'center',

			                },
			                emphasis: {//鼠标经过圆环中心出现字体样式修改
			                    show: true,
			                    textStyle: {
			                        fontSize: '30',
			                        color:"black"
			                    }
			                }
			            },
			            labelLine: {//标签的视觉引导线样式
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
			                	value:335,
			                	name:'微信公众号',
			                	itemStyle: {//环的样式设置
						            normal: {
						                color: "#745AF2", 
						                borderColor: "#ffffff"  
						            }
				       			}
			                },
			                {
			                	value:535,
			                	name:'小程序',
			                	itemStyle: {
						            normal: {
						                color: "#24B6F7", 
						                borderColor: "#ffffff" 
						            }
				       			}
			                },
			                {
			                	value:135,
			                	name:'PC端',
			                	itemStyle: {
						            normal: {
						                color: "#6897B1", 
						                borderColor: "#ffffff"  
						            }
				       			}
			                },
			                {
			                	value:235,
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
				
		//用户统计折线图		
		var option3={
			textStyle:{//全局的字体样式
              	fontSize:12,
                fontWeight:'nomal',
                color: "rgba(41, 43, 51, .4)"
            	},
		    tooltip: {
		        trigger: 'axis',//提示框的触发方式，axis坐标轴触发，主要在柱状图，折线图等会使用类目轴的图表中使用。item数据项图形触发，主要在散点图，饼图等无类目轴的图表中使用。
		        axisPointer: {//提示线的样式
		            type: 'line',
		            lineStyle:{
		            	color:"#E9F1F3",
						width:1,
		       
		            }		            
		        },
		        //更改提示框
	            formatter:function(params){//params和echarts的series是关联的,
	            	//seriesName：系列名称(legend中的图标加名字)
					//value：当前数据值
					//name：数据名，类目名（上述柱状图中表示当前横坐标数据名）
	            	
	            		return("<p style='font-size:14px;margin:5px 10px'>"+params[0].name+"</p>"
	            			+'<p style="font-size:14px;margin:5px 10px;margin-top:-1px">新增：'+params[0].value+'</p>'
	            			+'<p style="font-size:14px;margin:5px 10px;margin-top:-7px">付费：'+params[1].value+'</p>'
	            		)
	            }
		    },
		    legend: {//图例样式
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
		    grid: {//图表相对于外部盒子的位置
		        left: '-2%',
		        right: '1%',
		        bottom: '3%',
		        containLabel: true,//是否包含坐标轴的刻度标签。		        
		    },
		    
		    xAxis: {
		        type: 'category',
		        axisLine:{//坐标轴线的操作
			        show:false,
	                onZero: false,//X 轴或者 Y 轴的轴线是否在另一个轴的 0 刻度上，只有在另一个轴为数值轴且包含 0 刻度时有效。
	                boundaryGap: true,//true，这时候刻度只是作为分隔线，标签和数据点都会在两个刻度之间的带(band)中间。
	                lineStyle: {
	                    color: "rgba(41, 43, 51, .17)",
	                    type:"dotted"
	                	}
            	},
            	axisTick:{//刻度是否显示
            		show:false
            	},
		        data:['2017/06/05','2017/06/06','2017/06/07','2017/06/08','2017/06/09','2017/06/10','2017/06/11']
		    },
		    yAxis: {
		        type: 'value',
		        min:0,
		        max:50,
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
            	splitLine: {//坐标系中分割线操作
				    lineStyle: {
				        // 使用深浅的间隔色
				        color: 'rgba(41, 43, 51, .17)',
				        type:"dotted"
				    },
			
				},
				axisLabel:{//刻度值的操作
					verticalAlign:"bottom",
					padding:[-25,-25,10 ,-32]
				}
		    },
		    series: [//数据
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
			       
		            data:[12, 13, 10, 13, 9, 23, 21]
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
		            data:[22, 18, 45, 23, 29, 3, 31]
		        }
		    ]
		};
		
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
			            radius: ['70%', '80%'],//前面数值改变环的粗细，后面改变整个圆的大小			            
			            avoidLabelOverlap: false,//是否启用防止标签重叠策略，默认开启，在标签拥挤重叠的情况下会挪动各个标签的位置，防止标签间的重叠。如果不需要开启该策略，例如圆环图这个例子中需要强制所有标签放在中心位置，可以将该值设为 false。
			            label:{//饼图图形上的文本标签，可用于说明图形的一些数据信息，比如值，名称等，鼠标经过出现说明
			                normal: {
			                    show: false,
			                    position: 'center',

			                },
			                emphasis: {//鼠标经过圆环中心出现字体样式修改
			                    show: true,
			                    textStyle: {
			                        fontSize: '30',
			                        color:"black"
			                    }
			                }
			            },
			            labelLine: {//标签的视觉引导线样式
			                normal: {
			                    show: false,
			                }
			            },
			            itemStyle: {
			                emphasis: {
			                    //shadowBlur: 2,
//			                    shadowOffsetX: 0,
//			                    shadowColor: 'rgba(0, 0, 0, 0.5)',
//			                    borderWidth:8
			                }
			            },
			            clockwise:false,
			            startAngle:45,
			            hoverOffset:10,
			            center: ['30%', '50%'],
			            data:[
			                {
			                	value:335,
			                	name:'微信公众号',
			                	itemStyle: {//环的样式设置
						            normal: {
						                color: "#745AF2", 
						                borderColor: "#ffffff"  
						            }
				       			}
			                },
			                {
			                	value:535,
			                	name:'小程序',
			                	itemStyle: {
						            normal: {
						                color: "#24B6F7", 
						                borderColor: "#ffffff" 
						            }
				       			}
			                },
			                {
			                	value:135,
			                	name:'PC端',
			                	itemStyle: {
						            normal: {
						                color: "#6897B1", 
						                borderColor: "#ffffff"  
						            }
				       			}
			                },
			                {
			                	value:235,
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
		myChart4.setOption(option4);
		
		//用户统计环形图2		
		var option5={         	
				tooltip: {
			        trigger: 'item',
			        formatter: "{b}: {c} ({d}%)",
			        padding: 10
			   },			    
			    series: [
			        {
			            name:'用户构成',
			            type:'pie',
			            radius: ['70%', '80%'],//前面数值改变环的粗细，后面改变整个圆的大小			            
			            avoidLabelOverlap: false,//是否启用防止标签重叠策略，默认开启，在标签拥挤重叠的情况下会挪动各个标签的位置，防止标签间的重叠。如果不需要开启该策略，例如圆环图这个例子中需要强制所有标签放在中心位置，可以将该值设为 false。
			            label:{//饼图图形上的文本标签，可用于说明图形的一些数据信息，比如值，名称等，鼠标经过出现说明
			                normal: {
			                    textStyle: {
			                        color: 'rgba(41, 43, 51, 1)'
			                    },
//								formatter:"{b}\n\n{c}",
								formatter: [
						            '{b}',
						            '{c|{c}}'
						        ].join('\n'),
						
						        rich: {
						            c: {
						                color: 'rgba(41, 43, 51, .4)',
						               	lineHeight: 20
						            }						            						            
						        }

			                },
			                
			            },
			            labelLine: {//标签的视觉引导线样式
			                normal: {
			                    lineStyle: {
			                        color: '	rgba(104, 151, 177, .4)'
			                    },
			                    smooth: 0.2,
			                    length: 10,
			                    length2: 30
			                }
			            },
			            
			            clockwise:false,
			            startAngle:45,
			            hoverAnimation:false,			            
			            center: ['50%', '50%'],
			            data:[
			                {
			                	value:1312,
			                	name:'免费用户',
			                	itemStyle: {//环的样式设置
						            normal: {
						                color: "#745AF2", 
						                borderColor: "#ffffff"  
						            }
				       			}
			                },
			                {
			                	value:600,
			                	name:'其他',
			                	itemStyle: {
						            normal: {
						                color:"#55CE63", 
						                borderColor: "#ffffff" 
						            }
				       			},
				       			label:{
					                normal: {
					                	show:false
					                }
				               	},
				               	labelLine:{
					                normal: {
					                	show:false
					                },
					                emphasis:{
					                	show:false
					                }
				                }
			                },
			                {
			                	value:1312,
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
			
})