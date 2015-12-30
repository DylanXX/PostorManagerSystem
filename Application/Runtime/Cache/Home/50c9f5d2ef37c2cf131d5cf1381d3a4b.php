<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <title>登陆</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="/public/css/metro.css" rel="stylesheet">
    <link href="/public/css/metro-icons.css" rel="stylesheet">

    <script src="/public/script/jquery-2.1.4.min.js"></script>
    <script src="/public/script/metro.js"></script>
    <script src="/public/script/public.js"></script>
 
    <style>
        .login-form {
            width: 25rem;
            height: 18.75rem;
            position: fixed;
            top: 50%;
            margin-top: -9.375rem;
            left: 50%;
            margin-left: -12.5rem;
            background-color: #ffffff;
            opacity: 0;
            -webkit-transform: scale(.8);
            transform: scale(.8);
        }
    </style>

    <script>
		
        $(function(){
            var form = $(".login-form");

            form.css({
                opacity: 1,
                "-webkit-transform": "scale(1)",
                "transform": "scale(1)",
                "-webkit-transition": ".5s",
                "transition": ".5s"
            });
        });
        </script>
        <script>
    		$(document).ready(function(){
				//若cookie中有数据则代表可以进行cookie登陆操作
				var tlogin=getCookie("login");
				if(tlogin!=""&&tlogin!=null){
					tlogin=stringToJson(tlogin);
					$.post(
						"/index.php/login/tlogin", 
						tlogin,
						function(data){
							json = eval('('+data+')');
							if(json.status==3){
								//return true;
								setCookie('login',json2str(json.cookie), 1);
								window.location="/index.php/login/logined";
							}else{
								if(json.status==1){
									window.location="/index.php/login/logined";
								}else{
									alert('false');
								}
							}
						});
				}
			});
			$(function(){
				$('.login').click(function(){
					var params = $("#login").serialize();
					$.post(
						"/index.php/login/va", 
						params,
						function(data){
							json = eval('('+data+')');
							if(json.status==1){
								//return true;
								setCookie('login',json2str({"name":$('#user_id').prop('value'),"token":json.token}), 1);
								window.location="/index.php/login/logined";
							}else{
								alert('error');
							}
						});
				});
			});
			

    </script>
</head>
<body class="bg-darkTeal">
    <div class="login-form padding20 block-shadow">
        <form id="login" onsubmit="return false;">
            <h1 class="text-light">登录</h1>
            <hr class="thin"/>
            <br />
            <div class="input-control text full-size" data-role="input">
                <label for="user_id">User id:</label>
                <input type="text" name ="usrid" id="user_id"  value="">
                <button class="button helper-button clear"><span class="mif-cross"></span></button>
            </div>
            <br />
            <br />
            <div class="input-control password full-size" data-role="input">
                <label for="user_psw">UserInfo password:</label>
                <input type="password" name ="psw"  id="user_psw"  value="">
                <button class="button helper-button reveal"><span class="mif-looks"></span></button>
            </div>
            <br />
            <br />
            <div class="form-actions">
                <button type="button" class="button primary login">登录</button>
                <button type="button" class="button link">返回</button>
                <button type="button" class="button link">忘记密码</button>
            </div>
        </form>
    </div>
    <div id="ss"></div>
</body>
</html>