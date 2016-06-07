<?php 
include 'include/common.inc.php';
check_login();
$id=intval($_GET['id']);
$r=$dosql->GetOne("select * from `#@__memo` where id=$id");
$from=$_GET['from'];
$share=$_GET['share'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>我的备忘</title>
<link rel="stylesheet" media="screen and (max-width:1025px)" href="styles/style_small.css" type="text/css" />
<link rel="stylesheet" media="screen and (min-width:1025px)" href="styles/style.css" type="text/css"  />
<script type="text/javascript" src="Scripts/jquery-1.7.2.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<script type="text/javascript" src="rili/WdatePicker.js"></script>
<script charset="utf-8" src="kindeditor/kindeditor.js"></script>
<script charset="utf-8" src="kindeditor/lang/zh_CN.js"></script>
</head>

<body>
<?php include 'header.php';?>
<form name="form" action="?" method="post" onsubmit="return chk();">
<div class="add-info">

	<div class="add-list list1">
		<div class="add-remind-left">编辑备忘</div>
		<div class="shu"></div>
		<div class="add-remind-right">
			<input onclick="history.go(-1);" class="add-return" name="" type="button" value="〈 返回">
			<input class="add-save" name="" type="submit" value="保存">
			<input class="add-cancel" name="" type="reset" value="重置">
		</div>
	</div>

	<div class="add-list m30">
		<div class="add-text">股票代码</div>
		<div class="add-input"><input id="stock_code" name="stock_code" type="text" value="<?php echo $r['stock_code'];?>"></div>
	</div>
	<div class="add-list m30">
		<div class="add-text">关键字</div>
		<div class="add-input"><input id="keywords" name="keywords" type="text" value="<?php echo str_replace(";",",",$r['keywords']);?>"></div>
	</div>
	<div class="add-list m30">
		<div class="add-text">起始时间</div>
		<div class="add-input l30" id="s"><input value="<?php echo $r['start_date'];?>" onFocus="WdatePicker({minDate:'%y-%M-%d',maxDate:'#F{$dp.$D(\'end_date\')}'})" readonly id="start_date" name="start_date" type="text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input onclick="cur()" name="" type="radio">&nbsp;当前日期</div>
	</div>
	<div class="add-list m30" id="end">
		<div class="add-text">截止日期</div>
		<div class="add-input" id="e"><input value="<?php echo $r['end_date'];?>" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'start_date\')}'})" readonly id="end_date" name="end_date" type="text"></div>
	</div>
	<!-- <div class="add-list m30">
		<div class="add-text">重复频次</div>
		<div class="add-input ml50 l30"><label><input name="repeat_time" type="radio" value="week"> 每周</label> &nbsp;&nbsp;&nbsp;<label> <input name="repeat_time" type="radio" value="month"> 每月 </label>&nbsp;&nbsp;&nbsp;<label><input name="repeat_time" type="radio" value="day"> 每日 </label>&nbsp;&nbsp;&nbsp;<label><input checked name="repeat_time" type="radio" value="no"> 不重复</label></div>
	</div> -->
	<script>
		var editor;
		KindEditor.ready(function(K) {
			editor = K.create('textarea[name="content"]', {
				resizeType : 1,
				allowPreviewEmoticons : false,
				allowImageUpload : false,
				items : [
					'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
					'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
					'insertunorderedlist', '|', 'emoticons', 'link']
			});
		});
	</script>
	<div class="add-list m30">
		<div class="add-text">备忘内容</div>
		<div class="add-input ml50"><textarea id="content" name="content"><?php echo $r['content'];?></textarea></div>
	</div>
	<div class="add-list m150">
		<div class="add-text">重要度</div>
		<div class="add-input">
			<?php if($r['importance']==1){?>
			<div id="dui" class="red">√</div>
		<?php }else if($r['importance']==2){?>
		<div id="dui" class="yellow">√</div>
		<?php }else{?>
		<div id="dui" class="blue">√</div>
		<?php }?>
			<div class="shu ml25"></div>
			<div onclick="im('red',1)" class="red" title="重要"></div>
			<div onclick="im('yellow',2)" class="yellow" title="关注"></div>
			<div onclick="im('blue',3)" class="blue" title="一般"></div>
		</div>
		<input name="importance" type="hidden" value="<?php echo $r['importance'];?>">
	</div>
	<div class="add-list m30">
		<div class="add-text">目前状态</div>
		<div class="add-input2 ml50"><label><input <?php if($r['status']=='0'){echo "checked";}?> name="status" type="radio" value="0"> 空仓 </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input <?php if($r['status']=='1'){echo "checked";}?> name="status" type="radio" value="1"> 持仓 </label><input value="<?php echo $r['code_num'];?>" id="code_num" name="code_num" type="text">&nbsp;&nbsp;股，成本&nbsp;&nbsp;<input id="cost" name="cost" type="text" value="<?php echo $r['cost'];?>">&nbsp;&nbsp;元</div>
	</div>
	<div class="add-list m30 mb40">
		<div class="add-text">交易计划</div>
		<div class="add-input"><input value="<?php echo $r['plan'];?>" onFocus="WdatePicker({minDate:'%y-%M-{%d}'})" readonly id="plan" name="plan" type="text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<select name="plan_status"><option <?php if($r['plan_status']==1){echo "selected";}?> value="1">买入</option><option <?php if($r['plan_status']==2){echo "selected";}?> value="2">卖出</option></select></div>
	</div>
</div>
<input name="act" type="hidden" value="memoupdate">
<input name="id" type="hidden" value="<?php echo $id;?>">
<input name="from" type="hidden" value="<?php echo $from;?>">
<input name="share" type="hidden" value="<?php echo $share;?>">
</form>
<script type="text/javascript">
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
		$("#start_date").val(date);
	}
	$("input[name=repeat_time]").click( function () {
		var start_str='<input onclick="WdatePicker();" readonly id="start_date" name="start_date" type="text">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input onclick="cur()" name="" type="radio">&nbsp;当前日期';
		var end_str='<input onclick="WdatePicker();" readonly id="end_date" name="end_date" type="text">';
	    if ($(this).val()=='day') {
	    	$("#end").hide();
	    	$("#s").html(start_str);
	    }else if ($(this).val()=='week' || $(this).val()=='month') {
	    	$("#end").show();
	    	var option='';
	    	if ($(this).val()=='week') {
	    		option='<option value="1">周一</option><option value="2">周二</option><option value="3">周三</option><option value="4">周四</option><option value="5">周五</option><option value="6">周六</option><option value="0">周日</option>';
	    	}else{
	    		for(var i=1;i<=31;i++){
	    			option+='<option>'+i+'号</option>';
	    		}
	    	}
	    	start_str='<select class="ml30" id="start_date" name="start_date">'+option+'</select>';
	    	end_str='<select class="ml30" id="end_date" name="end_date">'+option+'</select>';
	    	$("#s").html(start_str);
	    	$("#e").html(end_str);
	    }else if($(this).val()=='no'){
	    	$("#end").show();
	    	$("#s").html(start_str);
	    	$("#e").html(end_str);
	    }
	});
	function format_time(time){
		var arr=time.split('-');
		var t=new Date(arr[0],arr[1],arr[2]); 
		return t.getTime();
	}
	function chk(){
		if (document.form.stock_code.value=='') {
			layer.alert('股票代码不能为空！');
			return false;
		}
		if ($("#start_date").val()=='') {
			layer.alert('请选择起始时间！');
			return false;
		}
		if ($("#end_date").val()=='') {
			layer.alert('请选择截止日期！');
			return false;
		}
		// var content=document.getElementById("content").value;
		// if (content.length==0 || content==null) {
		// 	layer.alert('请填写备忘内容！');
		// 	return false;
		// }
		if ($("input[name=status]:checked").val()===undefined) {
			layer.alert('请选择状态！');
			return false;
		}
		if (document.form.code_num.value=='') {
			layer.alert('请填写股票数量！');
			return false;
		}
		if (document.form.cost.value=='') {
			layer.alert('请填写成本！');
			return false;
		}
		if (document.form.plan.value=='') {
			layer.alert('请选择交易计划！');
			return false;
		}
	}
</script>
</body>
</html>
