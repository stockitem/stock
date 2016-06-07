<div class="top">
	<div class="top1">
		<div class="tit"><a title="回到首页" href=".">复盘宝</a></div>
		<div class="en">FU PAN BAO</div>
	</div>
	<?php if($_SESSION['username']!='admin'){?>
		<div class="add">
			<div class="add-new1">+ 新增</div>
			<div class="add-new2"><p><a href="memo_add.php">新增备忘</a></p><a href="remind_add.php"><p>新增提醒</a></p></div>
		</div>
	<?php }?>
	<div class="top2">
		<?php if($_SESSION['username']=='admin'){?>
		<a href="admin_add.php">新增会员</a>&nbsp;&nbsp;&nbsp;
		<?php }?>
		<a href="?act=logout">退出</a>&nbsp;&nbsp;&nbsp;<?php echo $_SESSION['username'];?>
		<div class="head-pic"><img id="head" src="Images/pic.jpg" width="41" height="41"></div>
	</div>
</div>
<script type="text/javascript">
	$(".add-new1").click( function () {
		$(".add-new2").toggle();
	});
</script>