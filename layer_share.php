<?php
include 'include/common.inc.php';
check_login();
$id=intval($_GET['id']);
$arr=get_allusers($id);
$str=implode(",",$arr);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我要分享</title>
<link rel="stylesheet" type="text/css" href="styles/style.css">
<link rel="stylesheet" type="text/css" href="styles/fm.tagator.jquery.css">
<script type="text/javascript" src="Scripts/jquery-1.7.2.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<script type="text/javascript" src="Scripts/fm.tagator.jquery.js"></script>
</head>

<body>
<div class="share">
<form name="sh" action="?" method="post">
	<div class="share-input">
		<!-- value="17素材,JS代码" -->
	    <input id="inputTagator" type="text" name="inputTagator" placeholder="输入用户名" class="inputTagator">
	</div>
	<div class="share-sub">
		<input name="" type="button" value="分享" class="share-submit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="" type="button" value="重置" class="share-cancel">
	</div>
</form>
</div>
<script type="text/javascript">
	$(".share-cancel").click(function(){
		  location.reload();
	});
	$(".share-submit").click(function(){
		  var val=$('#inputTagator').val();
		  var id=<?php echo $id;?>;
		  if(val=='') {
		  	alert('请输入正确用户名！');
		  	return false;
		  }
		  $.ajax({
		   type: "POST",
		   url: "data.php",
		   data: "type=addshare&id="+id+"&user="+val,
		   success: function(msg){
		      alert(msg);
		      window.parent.location.reload();
		   }
		});
	});
	$(function () {
		var str="<?php echo $str;?>";
		var arr=str.split(",");
		if ($('#inputTagator').data('tagator') === undefined) {
			$('#inputTagator').tagator({
				autocomplete: arr
			});
		} else {
			$('#inputTagator').tagator('destroy');
		}
});
</script>
</body>
</html>
