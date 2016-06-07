<?php
include 'include/common.inc.php';
if ($_SESSION['uid']) {
	header("Location:index.php");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户登录</title>
<link rel="stylesheet" media="screen and (max-width:1025px)" href="styles/style_small.css" type="text/css" />
<link rel="stylesheet" media="screen and (min-width:1025px)" href="styles/style.css" type="text/css"  />
</head>

<body>
<div class="login-header">
	<div class="login-top">
		<div class="login-top1"><div class="text1">复盘宝</div> <div class="text-en">FU  PAN  BAO</div></div>
		<div class="login-top2"><span class="text2">登录</span></div>
	</div>
	<div class="login">
		<form name="" action="?" method="post">
			<input class="login-input" required name="username" type="text" placeholder="用户名">
			<input class="login-input2" required name="password" type="password" placeholder="密码">
			<input class="login-submit" name="sub" type="submit" value="登录">
			<input name="act" type="hidden" value="login">
		</form>
	</div>
</div>
<div class="login-footer">
	©2015-2016 fupanbao.com,All Rights Reserved.
</div>
</body>
</html>
