<?php
include 'include/common.inc.php';
check_login();
if ($_SESSION['username']=='admin') {
	header('Location:admin_list.php');
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户首页</title>
<link rel="stylesheet" media="screen and (max-width:1025px)" href="styles/style_small.css" type="text/css" />
<link rel="stylesheet" media="screen and (min-width:1025px)" href="styles/style.css" type="text/css"  />
<script type="text/javascript" src="Scripts/jquery-1.7.2.js"></script>
<script type="text/javascript" src="layer/layer.js"></script>
<style type="text/css">
/*滚动条*/
::-webkit-scrollbar  
{  
    width: 1px;  
    height: 1px;  
    background-color: #F5F5F5;  
}  
  
/*定义滚动条轨道 内阴影+圆角*/  
::-webkit-scrollbar-track  
{  
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);  
    border-radius: 10px;  
    background-color: #F5F5F5;  
}  
  
/*定义滑块 内阴影+圆角*/  
::-webkit-scrollbar-thumb  
{  
    border-radius: 15px;  
    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);  
    background-color: #555;  
} 
/*滚动条*/
</style>
</head>

<body>
<?php include 'header.php';?>

<?php 
$arr=get_remind();
if(count($arr)>0){
?>
<div class="remind">
	<ul class="maquee">
		<?php
		for($i=0;$i<count($arr);$i++){
		?>		
		<li><?php echo $arr[$i];?>&nbsp;&nbsp;&nbsp;<img width="15" height="15" src="Images/cha.png"></li>
		<?php }?>
	</ul>
</div>
<?php }?>
<div class="index-con">
	<div class="index-left">
		<div class="index-left-top">
			<div class="left-text index-left-live">全部股票</div>
			<div class="left-text ml20">我的股票</div>
			<div class="left-text ml20">分享的股票</div>
		</div>
		<div class="index-left-ser">
			<form name="" action="?" method="get">
				<input class="index-ser-input" name="k" type="text" placeholder="搜索股票信息等">
				<input name="infotype" type="hidden" value="all">
				<img id="index_ser" src="Images/ser.png">
			</form>	
		</div>
		<div class="index-left-tit">
			<div class="tit1 ml20">股票代码</div>
			<div class="tit1 ml250">股票名称</div>
		</div>
		<span id="codelist">
		<?php
		$date=date('Y-m-d');
		$sql="end_date>='$date'";//显示在有效期内的
		$arr_id=array();
		$dosql->Execute("SELECT memo_id FROM `#@__share` WHERE to_uid=$uid ORDER BY id DESC");
		while($row = $dosql->GetArray()){
			$arr_id[]=$row['memo_id'];
		}
		if (!empty($arr_id)) {
			$str_id=implode(",",$arr_id);
			$sql.=" and (uid=$uid or id in (".$str_id."))";
		}else{
			$sql.=" and uid=$uid";
		}
		$t=$dosql->GetOne("SELECT count(*) as t FROM `#@__memo` WHERE $sql");
		if($t['t']>0){
		$dosql->Execute("SELECT id,stock_code,stock_name FROM `#@__memo` WHERE $sql group by stock_code ORDER BY id DESC");
		while($row = $dosql->GetArray()){
		?>
		<div data="all" class="index-left-list">
			<div class="index-list-con w57 ml20"><?php echo $row['stock_code'];?></div>
			<div class="index-list-con ml250"><?php echo $row['stock_name'];?></div>
		</div>
		<?php }}else{?>
		<div class="index-left-list white">暂无相关信息！</div>
		<?php }?>
		</span>
	</div>
	<div class="index-right">
		<div class="index-right-memo">
			<div class="index-right-top">
				<div class="index-right-live">备忘列表</div>
				<div class="right-text"><a href="memo_list.php">全部备忘 > </a></div>
			</div>
			<span id="index_memo">
			<?php
			$color=array('','red','yellow','blue');
			$plan=array('','买入','卖出');
			$status=array('空仓','持仓');
			$_date=date('Y-m-d');
			$t=$dosql->GetOne("SELECT count(*) as t FROM `#@__memo` WHERE uid=$uid and end_date>='$_date'");
			if($t['t']>0){
			$dosql->Execute("SELECT * FROM `#@__memo` WHERE uid=$uid and end_date>='$_date' ORDER BY importance ASC,plan ASC LIMIT 3");
			while($row = $dosql->GetArray()){
				$is_over=strtotime($row['end_date'])<strtotime(date('Y-m-d')) ? 'hui' : 'liang' ;
			?>
			<div id="<?php echo 'memo_'.$row['id'];?>" class="memo-list ml20 pd50">
				<div class="memo-list1">
					<div class="list-shu shu-<?php echo $color[$row['importance']];?>"></div>
					<div class="memo-list-tit memo-<?php echo $is_over;?>">
					<?php echo $row['stock_code'];?>&nbsp;&nbsp;&nbsp;<?php echo $row['stock_name'];?>&nbsp;<span class="memo-share"></span>
					</div>
					<?php echo get_keywords($row['keywords']);?>
					<div class="memo-edit-<?php echo $is_over;?> mr40"><a href="memo_update.php?from=index&id=<?php echo $row['id'];?>">编辑</a>&nbsp;&nbsp;&nbsp;<a onclick="delmemo(<?php echo $row['id'];?>,'self');" href="javascript:;">删除</a>&nbsp;&nbsp;&nbsp;<a href="javascript:share(<?php echo $row['id'];?>);">分享</a></div>
				</div>
				<div class="memo-list2"><span class="memo-list2-tit1"></span><span class="memo-list2-tit2 memo-<?php echo $is_over;?>"><?php echo $row['content'];?></span></div>
				<div class="memo-list2"><span class="memo-list2-tit1">目前状态：</span><span class="memo-list2-tit2 memo-<?php echo $is_over;?>"><?php echo $status[$row['status']];?>中，成本<?php echo $row['cost'];?>元/<?php echo $row['code_num'];?>股</span></div>
				<div class="memo-list2"><span class="memo-list2-tit1">计划：</span><span class="memo-list2-tit2 memo-<?php echo $is_over;?>"><?php echo $row['plan'];?> <?php echo $plan[$row['plan_status']];?></span></div>
				<div class="memo-list3 mr40"><?php echo $row['start_date'];?>至<?php echo $row['end_date'];?></div>
			</div>
			<?php }}else{?>
			<div class="memo-list ml20 pd50 he">暂无备忘记录！</div>
			<?php }?>
			</span>
		</div>
		<div class="index-right-news">
			<div class="index-news-title">
				<div class="index-news-tit fl index-news-tit-live">公告</div>
				<div class="sh"></div>
				<div class="index-news-tit fl ml20">新闻</div>
				<div class="sh"></div>
				<div class="index-news-tit fl ml20">互动</div>
			</div>
			<div class="index-news-con">
				<?php 
				$arr_news=get_news(0);
				$news=$arr_news['data'];
				for($i=0;$i<count($news);$i++){
				?>
				<div class="index-news-list <?php if($i%2==1){echo "ml20";}?>">
					<div class="index-news-con-tit"><a target="_blank" href="<?php echo $news[$i]['url'];?>"><?php echo $news[$i]['title'];?></a></div>
					<div class="index-news-content"><?php echo $news[$i]['kw'];?></div>
					<div class="index-news-date"><?php echo $news[$i]['date'];?></div>
				</div>
				<?php }?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
 //    i=0;
	// function autoScroll(obj){  
	// 	var len=$(".maquee li").length;
	// 	var j=i+1;
	// 	var px=39+j;
	// 	if (j==len) {
	// 		i=0;
	// 	}else{
	// 		var mar="-"+px;
	// 	}
	// 	$(obj).find("li").eq(i).animate({  
	// 		marginTop : mar+"px"  
	// 	},500,function(){  
	// 		$(this).css({marginTop : "0px"}).find("li:first").appendTo(this);  
	// 	})  
	// 	i++;
	// }   
	function autoScroll(obj){  
		$(obj).find("ul").animate({  
			//marginTop : "-39px"  
		},500,function(){  
			$(this).css({marginTop : "0px"}).find("li:first").appendTo(this);  
		}) 
	}  
	setInterval('autoScroll(".remind")',4000); 
	
	$(".remind img").click(function(){
		$(".remind").slideUp("slow");
	});
	$(".index-left-list").live('click',function(){
		$(".index-left-list").removeClass('bgi');
		$(this).addClass('bgi');
		var stock_code=$(this).children().eq(0).html();
		var infotype=$(this).attr("data");
		$.ajax({
		   type: "POST",
		   url: "data.php",
		   data: "type=indexswitch&infotype="+infotype+"&stock_code="+stock_code,
		   success: function(msg){
		      $("#index_memo").html(msg);
		   }
		});
	});
	$(".index-news-tit").click( function () { 
		$(".index-news-tit").removeClass("index-news-tit-live");
		$(this).addClass("index-news-tit-live");
		var index=$(this).index();
		var category='';
		if (index==0) {
			category='0';
		}else if(index==2){
			category='1';
		}else if(index==4){
			category='2';
		}
		$.ajax({
		   type: "POST",
		   url: "data.php",
		   data: "type=getnews&category="+category,
		   success: function(msg){
		      $(".index-news-con").html(msg);
		   }
		});
	});
	
	$(".left-text").click( function () { 
		$(".left-text").removeClass("index-left-live");
		$(this).addClass("index-left-live");
		var index=$(this).index();
		var arr=[];
		arr=['all','self','share'];
		var type=arr[index];
		$("input[name=infotype]").val(type);
		$.ajax({
		   type: "POST",
		   url: "data.php",
		   data: "type="+type,
		   success: function(msg){
		   	   if (msg=='') {
		   	   	  $("#codelist").html('<div class="index-left-list white">暂无相关信息！</div>');
		   	   }else{
		   	   	  $("#codelist").html(msg);
		   	   }
		   }
		});
	});
	$("#index_ser").click(function(){
		var k=$("input[name=k]").val();
		var type=$("input[name=infotype]").val();
		$.ajax({
		   type: "POST",
		   url: "data.php",
		   data: "type="+type+"&k="+k,
		   success: function(msg){
		       if (msg=='') {
		   	   	  $("#codelist").html('<div class="index-left-list white">暂无相关信息！</div>');
		   	   }else{
		   	   	  $("#codelist").html(msg);
		   	   }
		   }
		});
	});

	function  _index(type) {
		$.ajax({
		   type: "POST",
		   url: "data.php",
		   data: "type="+type,
		   success: function(msg){
		   	   if (msg=='') {
		   	   	  $("#codelist").html('<div class="index-left-list white">暂无相关信息！</div>');
		   	   }else{
		   	   	  $("#codelist").html(msg);
		   	   }
		   }
		});
	}
	function delmemo (id,share) {
		if (confirm('确定要删除吗？')) {
			$.ajax({
			   type: "POST",
			   url: "data.php",
			   data: "type=delmemo&id="+id+"&share="+share,
			   success: function(msg){
			       $("#memo_"+id).remove();
			       _index(share);
			   }
			});
		}
	}
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
