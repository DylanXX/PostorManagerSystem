<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel='shortcut icon' type='image/x-icon' href='../favicon.ico' />

    <title>邮件管理系统</title>

    <link href="/public/css/metro.css" rel="stylesheet">
    <link href="/public/css/metro-icons.css" rel="stylesheet">

    <script src="/public/script/jquery-2.1.4.min.js"></script>
    <script src="/public/script/metro.js"></script>
    <script src="/public/script/public.js"></script>
	<script src="/public/script/select2.min.js"></script>
    <style>
		.wizard2 .step {
		  width: 100%;
		  height: 100%;
		  display: block;
		  float: left;
		  position: relative;
		  z-index: 1;
		  padding: 0 0 3rem;
		}
        html, body {
            height: 100%;
        }
        .page-content {
            padding-top: 3.125rem;
            min-height: 100%;
            height: 100%;
        }
        .table .input-control.checkbox {
            line-height: 1;
            min-height: 0;
            height: auto;
        }

        @media screen and (max-width: 800px){
            #cell-sidebar {
                flex-basis: 52px;
            }
        }
    </style>
	
	<script>
		//dialo and swizard
		function respondDialog(id,title,text){
			var dialog = $(id).data('dialog');
			$(id).children().children()[0].innerHTML=text;
			dialog.open();
		}
		function showDialog(id){
			var dialog = $(id).data('dialog');
			dialog.open();
		}
		function closeDialog(id){
			//questionSub();
			var dialog = $(id).data('dialog');
			dialog.close();
		}
		$(function(){
			$("#carousel").carousel();
		});
	   </script>
    <script>
        $(function(){
            $(window).on('resize', function(){
                if ($(this).width() <= 800) {
                    $(".sidebar").addClass('compact');
                } else {
                    $(".sidebar").removeClass('compact');
                }
            });
        });

        function pushMessage(t){
            var mes = '通知|你已经选择了全部订单';
            $.Notify({
                caption: mes.split("|")[0],
                content: mes.split("|")[1],
                type: 'success'
            });
        }
        
        $(function(){
            $('.sidebar').on('click', 'li', function(){
                if (!$(this).hasClass('active')) {
                    $('.sidebar li').removeClass('active');
                    $(this).addClass('active');
                }
            })
        })
    </script>
    <script>
	function loadChecked(){
		var tlogin=stringToJson(getCookie("login"));
		$.post(
			"/index.php/get/getChecked",
			tlogin,
			function(data){
				data = eval('('+data+')');
				$("#OrderTable").html("");//清空info内容
				$.each(data.orders, function(i, item){
					$("#OrderTable").append(
							"<tr>"+
							"<td>"+
							"<label class='input-control checkbox small-check no-margin'>"+
								"<input type='checkbox' id='checkBox_"+i+"''>"+
								"<span class='check'></span>"+
							"</label>"+
							"</td>"+
								"<td>" + item.orderid + "</td>"+
								"<td>" + item.exporttime + "</td>"+
								"<td>" + item.orderinfo + "</td>"+
								"<td>" + item.postorid + "</td>"+
							"</tr>");
				});
			}
		);
	}
	function loadUnchecked(){
		var tlogin=stringToJson(getCookie("login"));
		$.post(
			"/index.php/get/getUnchecked",
			tlogin,
			function(data){
				data = eval('('+data+')');
				$("#OrderTable").html("");//清空info内容
				$.each(data.orders, function(i, item) {
					$("#OrderTable").append(
							"<tr>"+
							"<td>"+
							"<label class='input-control checkbox small-check no-margin'>"+
								"<input type='checkbox' class='uncheck' id='checkBox_"+i+"''>"+
								"<span class='check'></span>"+
							"</label>"+
							"</td>"+
								"<td>" + item.orderid + "</td>"+
								"<td>" + item.importtime + "</td>"+
								"<td>" + item.positionid    +"</td>"+	
								"<td>" + item.orderinfo + "</td>"+
								"<td>" + item.postorid + "</td>"+
							"</tr>");
				});
			}
		);
	}
	function selectCheckBox(t){
		var mes = '通知|你已经选择了全部订单|你已经取消了全部订单';
		$.Notify({
			caption: mes.split("|")[0],
			content: mes.split("|")[(t=='true'?1:2)],
			type:(t=='true'?'info':'success')
		});
		var param=document.getElementsByClassName('uncheck');
		$.each(param, function(i, item) {
			item.checked=(t=='true'?true:false);
		});
  	 }
	function delayOrders(){
		var mes = '通知|你选择的订单已被延期处理';
		$.Notify({
		  caption: mes.split("|")[0],
		  content: mes.split("|")[1],
		  type:'warning'
		});
		var list= new Array();
		var param=document.getElementsByClassName('uncheck');
		$.each(param, function(i, item) {
			if(item.checked==true){
				list.push(item.value);
			}
		});
		if(list.length==0)return false;
			$.ajax({
				type: "post",
				url: "/index.php/import/checkList",
				data: {'checkOrder':list,'token':stringToJson(getCookie("login"))},
				dataType: "json",
				success:
					function(data){turnReceived();}
		});
 	 }
	 function msg(mes){
		$.Notify({
		  caption: mes.split("|")[0],
		  content: mes.split("|")[1],
		  type:'sucess'
		});
	}
	$(document).ready(function(){
		if(getCookie("login")=="") window.location="/index.php/login/login?respond=1";
		var tlogin=stringToJson(getCookie("login"));
		if(tlogin!=""){
			$.post(
				"/index.php/get/getUsrName", 
				tlogin,
				function(data){
					json = eval('('+data+')');
					$("#username").append(json.name);
				});
		}else{
			window.location="/index.php/login/login?respond=1";
		}
		sidebarRefresh(tlogin);
	});
	</script>
	<script>
		function sidebarRefresh(tlogin){
			//检测邮件数目使用
			if(tlogin!=""){
				$.post(
				"/index.php/get/getOrderNumber", 
				tlogin,
				function(data){
					json = eval('('+data+')');
					$("#checkedCount").text(json.checkedCount);
					$("#uncheckCount").text(json.uncheckedCount);
					var allCount=json.checkedCount+json.uncheckedCount;
					$("#allCount").text(allCount);
				});
			}
		}
		//切换到尚未收件列表
		function turnUnreceived(){
			$("#main").load("/index.php/get/load?page=TurnUnchecked",function(){loadUnchecked();});
		}
		//切换到设置页面
		function changeToConfig(){
			$("#main").load("/index.php/get/load?page=ChangeToConfig");
		}
		//切换到已经收件列表
		function turnReceived(){
			$("#main").load("/index.php/get/load?page=TurnChecked",function(){loadChecked();});
		}
		//切换到垃圾箱
		function turnBin(){
			$("#main").load("/index.php/get/load?page=TurnBin");
		}
		//切换到全部收件列表
		function turnAll(){
			$("#main").load("/index.php/get/load?page=TurnAll");
		}
		//登出
		function loginOut(){
			var tlogin=stringToJson(getCookie("login"));
			if(tlogin!=""){
				$.post(
					"/index.php/login/loginOut", 
					tlogin,
					function(data){
						json = eval('('+data+')');
						if(json.status==1){
							setCookie("login",null,-1);
							window.location="/index.php/login/login?respond=1";
						}else{
							alert('没有成功登出');
						}
					});
			}else{
				window.location="/index.php/login/login?respond=1";
			}
			
		}
	</script>
</head>
<body class="bg-white" >
	
	<!--  -->
    <div class="app-bar fixed-top darcula" data-role="appbar">
        <a class="app-bar-element branding">快件管理系统</a>
        <span class="app-bar-divider"></span>
        <ul class="app-bar-menu">
            <li><a href=""><span class="mif-home icon"></span></a></li>
            <li><a href="">设置</a></li>
            <li><a href="">app下载</a></li>
            <li>
                <a href="" class="dropdown-toggle">帮助</a>
                <ul class="d-menu" data-role="dropdown">
                    <li><a href="">联系管理员</a></li>
                    <li><a href="">技术支持</a></li>
                    <li class="divider"></li>
                    <li><a href="">关于</a></li>
                </ul>
            </li>
        </ul>

        <div class="app-bar-element place-right">
            <span class="dropdown-toggle" id="username"><span class="mif-cog"></span></span>
            <div class="app-bar-drop-container padding10 place-right no-margin-top block-shadow" data-role="dropdown" data-no-close="true" style="width: 220px">
                <h2 class="text-light">个人中心</h2>
                <ul class="unstyled-list">
                    <li><a href="#page=showDialog" class="fg-white fg-hover-yellow" onclick="showDialog('#dialog');">更改信息</a></li>
                    <li><a href="" class="fg-white fg-hover-yellow">通知</a></li>
                    <li onclick="loginOut();"><a class="fg-white fg-hover-yellow">登出</a></li>
                </ul>
            </div>
        </div>
    </div>
				
	
	<script>
		$(function(){
			$("#tab-control").tabControl();
		});
	</script>
	<!-- 内容 -->
    <div class="page-content">
		<div class="flex-grid no-responsive-future" style="height: 100%;">
			<div class="row" style="height: 100%;">
				<div class="cell size-x200" id="cell-sidebar" style="background-color: #71b1d1;height: 100%;">
					<ul class="sidebar">
						<li><a href="#" class="active">
							<span class="mif-apps icon"></span>
							<span class="title" onclick="">快件管理</span>
							<span class="title">在下面的选项中操作快件</span>
						</a></li>
						<li><a href="#" onclick="turnReceived()">
							<span class="mif-drafts icon"></span>
							<span class="title">已取快件</span>
							<span class="counter"  id = "checkedCount"  >0</span>
						</a></li>
						<li  onclick="turnUnreceived()"><a href="#">
							<span class="mif-mail icon"></span>
							<span class="title">未取快件</span>
							<span class="counter" id = "uncheckCount" >0</span>
						</a></li>
						<li><a href="#" onclick="turnAll()">
							<span class="mif-cloud icon"></span>
							<span class="title">全部快件</span>
							<span class="counter" id="allCount" >0</span>
						</a></li>
						<li><a href="#" onclick="turnBin()">
							<span class="mif-bin icon"></span>
							<span class="title">垃圾箱</span>
							<span id="binCount" class="counter">0</span>
						</a></li>
					</ul>
				</div>
				<div  id ="main" class="cell auto-size padding20 bg-white">
				
					
				</div>
			</div>
		</div>
		
		
		<!-- 提示栏 -->
		<div  data-role="dialog" id="dialog" class="padding20 container auto-size" data-windows-style="true" data-height="80%" data-width="100%" data-overlay="true" data-overlay-color="op-dark"  data-close-button="true" data-overlay="true">
			<div class="carousel" id="carousel" style="height:100%"  	data-duration="200" data-controls="false" data-stop="false" data-auto="false" data-height="100%">
				<div class="slide padding20">
					<h2> 你的信息</h2>
					<hr></hr>
					<div class="listview-outlook" data-role="listview">
						<div class="list-group collapsed">
							<span class="list-group-toggle">用户名</span>
							<div class="list-group-content">
								<div class="list">
									<div class="list-content">
										<span class="title">更改用户名</span>
										<span class="mif-chevron-right fg-green"></span>
									</div>
								</div>
							</div>
						</div>
						<div class="list">
							<div class="list-content">
								<span class="list-title">你上一次的登陆IP为：</span>
								
							</div>
						</div>
						<div class="list">
							<div class="list-content">
								<span class="list-title">你上一次的登陆日期为：</span>
								
							</div>
						</div>
						<div class="list">
							<div class="list-content">
								<span class="list-title">你现在的登陆有效期为：</span>
								<div class="input-control" data-role="select">
									<select>
										<option>12小时</option>
										<option>24小时</option>
										<option>1天</option>
										<option>2天</option>
										<option>15天</option>
										<option>30天</option>
									</select>
								</div>
								<input type="button" value="保存">
							</div>
						</div>
					</div>
				</div>
			
				<div class="slide padding20">
					<h2> 安全</h2>
					<hr></hr>
					<div class="margin15 padding20">
						<div class="listview-outlook" data-role="listview">
							<div class="list-group collapsed">
								<span class="list-group-toggle">密码</span>
								<div class="list-group-content">
									<div class="list">
										<div class="list-content">
											<span class="list-title">还记得原密码</span>
										</div>
									</div>
									<div class="list">
										<div class="list-content">
											<span class="list-title">密码重置邮件</span>
										</div>
									</div>
									<div class="list">
										<div class="list-content">
											<span class="list-title">手机验证重置密码</span>
										</div>
									</div>
								</div>
							</div>
							<hr  class="bg-red"></hr>
							<div class="list">
								<div class="list-content" onclick="msg('通知|重新发送成功')">
									<span class="list-title" >邮箱尚未激活(点击以重新发送验证邮件)</span>
								</div>
							</div>
							
							<div class="list">
								<div class="list-content">
									<span class="list-title">手机尚未激活</span>
									<input type="button" value="点击以发送验证短信"   onclick="msg('通知|重新发送成功')"><br/>
									<input type="text">
									<input type="button" value="验证"   onclick="msg('通知|重新发送成功')">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>
		

    </div>
</body>
</html>