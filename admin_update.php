<?php 
include 'include/common.inc.php';
check_login();
chk_admin();
$uid=intval($_GET['uid']);
$r=$dosql->GetOne("select * from `#@__user` where id=$uid");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>修改会员</title>
<link rel="stylesheet" media="screen and (max-width:1025px)" href="styles/style_small.css" type="text/css" />
<link rel="stylesheet" media="screen and (min-width:1025px)" href="styles/style.css" type="text/css"  />
<script type="text/javascript" src="Scripts/jquery-1.7.2.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<script type="text/javascript" src="rili/WdatePicker.js"></script>
</head>

<body>
<?php include 'header.php';?>
<form name="form" action="?" method="post" onsubmit="return chk();">
<div class="add-info">

	<div class="add-list list1">
		<div class="add-remind-left">修改会员</div>
		<div class="shu"></div>
		<div class="add-remind-right">
			<input onclick="history.go(-1);" class="add-return" name="" type="button" value="〈 返回">
			<input class="add-save" name="" type="submit" value="保存">
			<input class="add-cancel" name="" type="reset" value="重置">
		</div>
	</div>

	<div class="add-list m30">
		<div class="add-text">用户名</div>
		<div class="add-input user"><?php echo $r['username'];?></div>
	</div>
	<div class="add-list m30">
		<div class="add-text">原密码</div>
		<div class="add-input"><input id="old_pwd" name="old_pwd" type="password"></div>
	</div>
	<div class="add-list m30">
		<div class="add-text">新密码</div>
		<div class="add-input"><input id="new_pwd" name="new_pwd" type="password"></div>
	</div>
	<div class="add-list m30">
		<div class="add-text">确认密码</div>
		<div class="add-input"><input id="new_pwd2" name="new_pwd2" type="password"></div>
	</div>
</div>
<input name="act" type="hidden" value="adminupdate">
<input name="uid" type="hidden" value="<?php echo $uid;?>">
</form>
<script type="text/javascript">
	function chk(){
		if (document.form.old_pwd.value=='') {
			layer.alert('原密码不能为空！');
			return false;
		}
		if (document.form.new_pwd.value=='') {
			layer.alert('新密码不能为空！');
			return false;
		}
		if (document.form.new_pwd.value!=document.form.new_pwd2.value) {
			layer.alert('两次密码输入不同！');
			return false;
		}
	}
</script>
</body>
</html>
