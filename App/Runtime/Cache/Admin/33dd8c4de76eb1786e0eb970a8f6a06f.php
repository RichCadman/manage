<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
	<meta name="author" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="format-detection" content="telephone=no, email=no" />
	<meta name="renderer" content="webkit">
	<meta name="description" content="" />
	<meta name="keywords" content="" />

	<title>完善信息</title>
	<link rel="stylesheet" type="text/css" href="/Pub/admin/css/base-v1.3.css">
	<link rel="stylesheet" type="text/css" href="/Pub/admin/css/style.css"></head>
<body class="customer">
	<form action="/admin.php/Love/add_customer_do/openid/<?php echo ($info); ?>" method="post">
		<div class="group">
			<label>公司或个人名称：</label>
			<input type="text" name="title"></div>
		<div class="group">
			<label>手机号：</label>
			<input type="number" name="phone"></div>
		<input class="btn" type="button" name="" value="确认">
	</form>
</body>
</html>