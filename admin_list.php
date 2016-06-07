<?php 
include 'include/common.inc.php';
check_login();
chk_admin();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>会员列表</title>
<link rel="stylesheet" media="screen and (max-width:1025px)" href="styles/style_small.css" type="text/css" />
<link rel="stylesheet" media="screen and (min-width:1025px)" href="styles/style.css" type="text/css"  />
<link rel="stylesheet" type="text/css" href="styles/pagecss.css">
<script type="text/javascript" src="Scripts/jquery-1.7.2.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<script type="text/javascript" src="rili/WdatePicker.js"></script>
</head>

<body>
<?php include 'header.php';?>
<div class="add-info">

	<div class="add-list2 list1">
		<div class="add-remind-left">会员列表</div>
		<div class="shu"></div>
		<!-- <div class="add-remind-right">
			<input onclick="history.go(-1);" class="add-return" name="" type="button" value="〈 返回">
		</div> -->
	</div>

	<div class="add-list2 m30">
		<div class="add-text w200 tl">用户名</div>
		<div class="add-text w200 ml15 tl">最后登录时间</div>
		<div class="add-text w200 ml15 tl">注册时间</div>
		<div class="add-text w200 ml15 tl">操作</div>
	</div>
	<?php
	include 'include/page.class.php';
	$t=$dosql->GetOne("select count(*) as total from `#@__user`");
	$page = new Page2($t['total'],10); 
	$first=$page->firstRow;
	$list=$page->listRows;
	if($t['total']>0){
	$dosql->Execute("SELECT * FROM `#@__user` ORDER BY id ASC limit $first,$list");
	while($row = $dosql->GetArray()){
	?>
	<div class="add-list2 m30">
		<div class="add-text w200 tl"><?php echo $row['username'];?><?php if($row['username']=='admin'){echo "[管理员]";}?></div>
		<div class="add-text w200 ml15 tl"><?php echo $row['login_date'];?></div>
		<div class="add-text w200 ml15 tl"><?php echo $row['create_date'];?></div>
		<div class="add-text w200 ml15 tl"><a href="admin_update.php?uid=<?php echo $row['id'];?>">编辑</a>&nbsp;&nbsp;&nbsp;<?php if($row['username']!='admin'){?><a onclick="return confirm('确定要删除吗？')" href="?act=admindel&id=<?php echo $row['id'];?>">删除</a><?php }?></div>
	</div>
	<?php }}else{echo "暂无用户信息！";}?>
	<div class="digg add-list2"><?php echo $page->show();?></div>
</div>
</body>
</html>
