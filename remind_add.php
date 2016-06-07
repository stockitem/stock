<?php 
include 'include/common.inc.php';
check_login();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的提醒</title>
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
		<div class="add-remind-left">新增提醒</div>
		<div class="shu"></div>
		<div class="add-remind-right">
			<input onclick="history.go(-1);" class="add-return" name="" type="button" value="〈 返回">
			<input class="add-save" name="" type="submit" value="保存">
			<input class="add-cancel" name="" type="reset" value="重置">
		</div>
	</div>
	<div class="add-list m30">
		<div class="add-text">触发日期</div>
		<div class="add-input l30" id="s"><input readonly id="dep_date" onfocus="WdatePicker({minDate:'%y-%M-{%d}'})" name="dep_date" type="text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input onclick="cur()" name="" type="radio">&nbsp;当前日期</label></div>
	</div>
	
	<div class="add-list m30">
		<div class="add-text"></div>
		<div class="add-input2 ml50">提前&nbsp;&nbsp;&nbsp;<input id="advance_num" name="advance_num" type="text">&nbsp;&nbsp;个交易日</div>
	</div>
	<div class="add-list m30">
		<div class="add-text">重复频次</div>
		<div class="add-input ml50 l30"><label><input name="repeat_time" type="radio" value="day"> 每日</label> &nbsp;&nbsp;&nbsp;<label> <input name="repeat_time" type="radio" value="week"> 每周 </label>&nbsp;&nbsp;&nbsp;<label><input name="repeat_time" type="radio" value="month"> 每月 </label>&nbsp;&nbsp;&nbsp;<label><input checked name="repeat_time" type="radio" value="no"> 不重复</label></div>
	</div>
	<div class="add-list m30">
		<div class="add-text">重要度</div>
		<div class="add-input">
			<div id="dui" class="blue">√</div>
			<div class="shu ml25"></div>
			<div onclick="im('red',1)" class="red" title="重要"></div>
			<div onclick="im('yellow',2)" class="yellow" title="关注"></div>
			<div onclick="im('blue',3)" class="blue" title="一般"></div>
		</div>
		<input name="importance" type="hidden" value="3">
	</div>
	<div class="add-list m30">
		<div class="add-text">内容</div>
		<div class="add-input ml50"><textarea id="content" name="content"></textarea></div>
	</div>
	
</div>
<input name="act" type="hidden" value="remindadd">
</form>
<script type="text/javascript">
	$("input[name=repeat_time]").click(function(){
		if($(this).val()=='day'){
			$("#advance_num").attr("disabled",true);
		}else{
			$("#advance_num").attr("disabled",false);
		}
	});
	function im(v,val){
		document.getElementById('dui').className=v;
		document.form.importance.value=val;
	}
	function cur(){
		var _date=new Date();
		var year=_date.getFullYear();
		var month=_date.getMonth();
		month+=1;
		if (month.toString().length==1) {
			month='0'+month;
		}
		var day=_date.getDate();
		var date=year+'-'+month+'-'+day;
		$("#dep_date").val(date);
	}
	function format_time(time){
		var arr=time.split('-');
		var t=new Date(arr[0],arr[1],arr[2]); 
		return t.getTime();
	}
	function is_int (str) {
		if (str==0) {
			return false;
		}
		return str == Math.abs( parseInt( str ) );
	}
	function chk(){
		if (document.form.dep_date.value=='') {
			layer.alert('请选择触发日期！');
			return false;
		}
		var advance_num=document.form.advance_num.value;
		if (!is_int(advance_num)) {
			layer.alert('请输入正确交易日！');
			return false;
		}
		if ($("#content").val()=='') {
			layer.alert('请填写内容！');
			return false;
		}
	}
</script>
</body>
</html>
