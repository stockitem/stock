<?php
include 'include/common.inc.php';
check_login();
$start=!empty($_GET['start']) ? $_GET['start'] : '' ;
$end=!empty($_GET['end']) ? $_GET['end'] : '' ;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>备忘列表</title>
<link rel="stylesheet" media="screen and (max-width:1025px)" href="styles/style_small.css" type="text/css" />
<link rel="stylesheet" media="screen and (min-width:1025px)" href="styles/style.css" type="text/css"  />
<script type="text/javascript" src="Scripts/jquery-1.7.2.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<script type="text/javascript" src="rili/WdatePicker.js"></script>
<script type="text/javascript" src="Scripts/scrollpagination.js"></script>
<script type="text/javascript">
	function filter(v,type){
		var k=$("#k").val();
		var start=type=='start' ? v : $("#start").val() ;
		var end=type=='end' ? v : $("#end").val() ;
		var importance=$("input[name='importance']:checked").val();
		var from=$("input[name='from']:checked").val();
		//num=0;//每次对num全局变量进行重新赋值为0
		$("#page").html('2');
		$.ajax({
		   type: "POST",
		   url: "data.php",
		   data: "type=filter&f=ajax&importance="+importance+"&start="+start+"&end="+end+"&from="+from+"&page=1&k="+k,
		   success: function(msg){
		   		if (msg!='') {
		   			$("#memo").html(msg);
		   		}else{
		   			var message='<div style="margin-left:45%;margin-top:30px;">暂无相关信息！</div>';
		   			$("#memo").html(message);
		   		}
		   }
		});
	}
</script>
</head>

<body onload="filter()">
<?php include 'header.php';?>
<div class="add-info2">
	<div class="add-list list1">
		<div class="add-remind-left">备忘列表</div>
		<div class="shu"></div>
		<div class="add-remind-right s">
			<form name="" action="?" method="get">
			<input onclick="history.go(-1);" class="add-return" name="" type="button" value="〈 返回">
			<input class="ser" name="k" id="k" placeholder="搜索备忘信息" type="text" value="<?php echo $_GET['k'];?>">
			<a onclick="filter()" href="javascript:;"><img class="input-image" src="Images/ser.png"></a>
			</form>
		</div>
	</div>
	<div class="filter">
		<div class="filter-list">
			<div class="filter-text">时间</div>
			<div class="filter-input">
				<input id="start" onFocus="WdatePicker({onpicking:function(dp){filter(dp.cal.getNewDateStr(),'start');},maxDate:'#F{$dp.$D(\'end\')}'})" name="start" type="text" readonly> - <input onFocus="WdatePicker({onpicking:function(dp){filter(dp.cal.getNewDateStr(),'end');},minDate:'#F{$dp.$D(\'start\')}'})" id="end" name="end" type="text" readonly>
			</div>
		</div>
		<div class="filter-list">
			<div class="filter-text">重要度</div>
			<div class="filter-check"><label><input checked onclick="filter(this.value)" name="importance" type="radio" value="all"> 全部</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input onclick="filter(this.value)" name="importance" type="radio" value="1"> 重要</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input onclick="filter(this.value)" name="importance" type="radio" value="2"> 关注</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input onclick="filter(this.value)" name="importance" type="radio" value="3"> 一般</label></div>
		</div>
		<div class="filter-list">
			<div class="filter-text">来源</div>
			<div class="filter-check-ml20"><label><input checked onclick="filter(this.value)" name="from" type="radio" value="all">全部</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input onclick="filter(this.value)" name="from" type="radio" value="self"> 自荐</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><input onclick="filter(this.value)" name="from" type="radio" value="share"> 分享</label></div>
		</div> 
	</div>
	<!-- 刚开始显示四个 然后滚到底自动加载 -->
	<div id="memo" class="memo-list-con">
		
	</div>
</div>
<div id="loading"></div>
<div id="nomoreresults"></div>
<div id="page" style="display:none;">2</div>
<script type="text/javascript">
var stop=true;
$(window).scroll(function(){
    totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
    if($(document).height() <= totalheight){
        if(stop==true){
            stop=false;
            $.post("data.php", {k:$("#k").val(),type:'filter',start:$("#start").val(),end:$("#end").val(),importance:$("input[name='importance']:checked").val(),from:$("input[name='from']:checked").val(),page:$("#page").html()},function(v){
                //$("#Loading").before(txt);
                var num=$("#page").html();
                num=parseInt(num)+parseInt(1);
                $("#page").html(num);
                if(v!='') $('#memo').append(v);
                stop=true;
            });
        }
    }
});


 //      var num=0;
	//   $(function(){
	// 	$('#memo').scrollPagination({
	// 		'contentPage': 'data.php',
	// 		'contentData': {k:'<?php echo $_GET["k"];?>',type:'filter',start:function(){return $("#start").val();},end:function(){return $("#end").val();},importance:function(){return $("input[name='importance']:checked").val();},from:function(){return $("input[name='from']:checked").val();},page:function(){return num;}}, 
	// 		'scrollTarget': $(window), 
	// 		'heightOffset': 10, 
	// 		'beforeLoad': function(){ 
	// 			$('#loading').fadeIn();	
	// 			num++;
	// 		},
	// 		'afterLoad': function(elementsLoaded){ 
	// 			 $('#loading').fadeOut();
	// 			 var i = 0;
	// 			 $(elementsLoaded).fadeInWithDelay();
	// 			 if ($('#memo').children().size() > 100){ 
	// 			 	$('#nomoreresults').fadeIn();
	// 				$('#memo').stopScrollPagination();
	// 			 }
	// 		}
	// 	});
		
	// 	$.fn.fadeInWithDelay = function(){
	// 		var delay = 0;
	// 		return this.each(function(){
	// 			$(this).delay(delay).animate({opacity:1}, 200);
	// 			delay += 100;
	// 		});
	// 	};
			   
	// });

	  function share(id){
		layer.open({
		  title:'分享给其他用户',
		  type: 2,
		  area: ['300px', '280px'],
		  fix: false, //不固定
		  maxmin: true,
		  content: 'layer_share.php?id='+id
		});
	}
</script>
</body>
</html>
