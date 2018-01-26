<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en" style="overflow-x:hidden;overflow-y:auto;">
<head>
<meta charset="utf-8"/>
<title>后台登录</title>
<meta name="author" content="DeathGhost" />
<link rel="stylesheet" type="text/css" href="/Pub/home/login/css/style.css" />
<link href="/favicon.ico" type="image/ico" rel="shortcut icon" />
<style>
body{height:100%;background:#16a085;overflow:hidden;}
canvas{z-index:-1;position:absolute;}
</style>
<script src="/Pub/home/login/js/jquery.js"></script>
<script src="/Pub/home/login/js/Particleground.js"></script>
<script>
$(document).ready(function() {
  //粒子背景特效
  $('body').particleground({
    dotColor: '#5cbdaa',
    lineColor: '#5cbdaa'
  });
});
</script>
</head>
<body>
<dl class="admin_login">
 <dt>
  <strong>员工项目管理系统</strong>
  <em>Management System</em>
 </dt>
<form action="/admin.php/Login/login_do" method="post">
 <dd class="user_icon">
  <input type="text" placeholder="账号" onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}" name="phone" class="login_txtbx"/>
 </dd>
 <dd class="pwd_icon">
  <input type="password" placeholder="密码" name="password" class="login_txtbx"/>
 </dd>
 <!-- <dd class="val_icon"> -->
  <!-- <div class="checkcode"> -->
    <!-- <input type="text" id="J_codetext" placeholder="验证码" maxlength="4" class="login_txtbx"> -->
    <!-- <canvas class="J_codeimg" id="myCanvas" onclick="createCode()">对不起，您的浏览器不支持canvas，请下载最新版浏览器!</canvas> -->
  <!-- </div> -->
  <!-- <input type="button" value="验证码核验" class="ver_btn" onClick="validate();"> -->
 <!-- </dd> -->
 <dd>
  <input type="submit" value="立即登录" class="submit_btn"/>
 </dd>
</form>
 <dd>
  <p>2017 &copy; 鼎赞网络科技 版权所有</p>
  <p>员工项目管理系统</p>
 </dd>
</dl>
</body>
</html>