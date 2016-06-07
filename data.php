<?php
include 'include/common.inc.php';
$type=$_POST['type'];
$str='';
if ($type=='all') {
	$k=$_POST['k'];
	$date=date('Y-m-d');
	$sql="end_date>='$date'";
	if(!empty($k)) $sql.=" and stock_code like '%".$k."%'";
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
	$dosql->Execute("SELECT id,stock_code,stock_name FROM `#@__memo` WHERE $sql group by stock_code ORDER BY id DESC");
	while($row = $dosql->GetArray()){
		$str.='<div data="all" class="index-left-list">
			<div class="index-list-con w57 ml20">'.$row['stock_code'].'</div>
			<div class="index-list-con ml250">'.$row['stock_name'].'</div>
		</div>';
	}
	exit($str);
}else if ($type=='self') {
	$k=$_POST['k'];
	$date=date('Y-m-d');
	$sql="uid=$uid and end_date>='$date'";
	if(!empty($k)) $sql.=" and stock_code like '%".$k."%'";
	$dosql->Execute("SELECT id,stock_code,stock_name FROM `#@__memo` WHERE $sql group by stock_code ORDER BY id DESC");
	while($row = $dosql->GetArray()){
		$str.='<div data="self" class="index-left-list">
			<div class="index-list-con w57 ml20">'.$row['stock_code'].'</div>
			<div class="index-list-con ml250">'.$row['stock_name'].'</div>
		</div>';
	}
	exit($str);
}else if($type=='share'){
	$k=$_POST['k'];
	$date=date('Y-m-d');
	$dosql->Execute("SELECT memo_id FROM `#@__share` WHERE to_uid=$uid ORDER BY id DESC");
	while($row = $dosql->GetArray()){
		$arr_id[]=$row['memo_id'];
	}
	if (!empty($arr_id)) {
		$sql="end_date>='$date'";
		if(!empty($k)) $sql.=" and stock_code like '%".$k."%'";
		$str_id=implode(",",$arr_id);
		$sql.=" and id in (".$str_id.")";
		$dosql->Execute("SELECT id,stock_code,stock_name FROM `#@__memo` WHERE $sql group by stock_code ORDER BY id DESC");
		while($row = $dosql->GetArray()){
			$str.='<div data="share" class="index-left-list">
				<div class="index-list-con w57 ml20">'.$row['stock_code'].'</div>
				<div class="index-list-con ml250">'.$row['stock_name'].'</div>
			</div>';
		}
	}
	exit($str);
}else if($type=='indexswitch'){
	$color=array('','red','yellow','blue');
	$plan=array('','买入','卖出');
	$status=array('空仓','持仓');
	$stock_code=$_POST['stock_code'];
	$share=$_POST['infotype'];
	$str='';
	$date=date('Y-m-d');
	$sql="end_date>='$date'";//不能是过期的
	if ($share=='self') {//自己的
		$sql.=" and uid=$uid and stock_code='$stock_code'";
	}else if($share=='share'){//别人分享的
		$arr=get_share($uid,$stock_code);
		$she=$arr[1];
		$_str=implode(",",$arr[0]);
		$sql.=" and id in ($_str)";
	}else{//所有的
		$arr=get_share($uid,$stock_code);
		if (!empty($arr)) {
			$she=$arr[1];
			$_str=implode(",",$arr[0]);
			$sql.=" and ((uid=$uid and stock_code='$stock_code') or id in ($_str))";
		}else{
			$sql.=" and uid=$uid and stock_code='$stock_code'";
		}
	}
	$dosql->Execute("SELECT * FROM `#@__memo` WHERE $sql ORDER BY importance ASC,plan ASC LIMIT 3");
	while($row = $dosql->GetArray()){
		//$is_over=strtotime($row['end_date'])<strtotime(date('Y-m-d')) ? 'hui' : 'liang' ;
		$is_over='liang';
		if ($share=='self') {
			$share_str='';
		}elseif ($share=='share') {
			$share_str='(来自'.$she[$row['id']].'的分享)' ;
		}else{
			$share_str=empty($she[$row['id']]) ? '' : '(来自'.$she[$row['id']].'的分享)' ;
		}
		$_share=empty($share_str) ? 'self' : 'share' ;
		$str.='<div id="memo_'.$row['id'].'" class="memo-list ml20 pd50">
					<div class="memo-list1">
						<div class="list-shu shu-'.$color[$row['importance']].'"></div>
						<div class="memo-list-tit memo-'.$is_over.'">
						'.$row['stock_code'].'&nbsp;&nbsp;&nbsp;'.$row['stock_name'].'&nbsp;<span class="memo-share">'.$share_str.'</span>
						</div>
						'.get_keywords($row['keywords']).'
						<div class="memo-edit-'.$is_over.' mr40"><a href="memo_update.php?from=index&share='.$_share.'&id='.$row['id'].'">编辑</a>&nbsp;&nbsp;&nbsp;<a onclick="delmemo('.$row['id'].',\''.$_share.'\')" href="javascript:;">删除</a>&nbsp;&nbsp;&nbsp;<a href="javascript:share('.$row['id'].');">分享</a></div>
					</div>
					<div class="memo-list2"><span class="memo-list2-tit1"></span><span class="memo-list2-tit2 memo-'.$is_over.'">'.$row['content'].'</span></div>
					<div class="memo-list2"><span class="memo-list2-tit1">目前状态：</span><span class="memo-list2-tit2 memo-'.$is_over.'">'.$status[$row['status']].'中，成本'.$row['cost'].'元/'.$row['code_num'].'股</span></div>
					<div class="memo-list2"><span class="memo-list2-tit1">计划：</span><span class="memo-list2-tit2 memo-'.$is_over.'">'.$row['plan'].' '.$plan[$row['plan_status']].'</span></div>
					<div class="memo-list3 mr40">'.$row['start_date'].'至'.$row['end_date'].'</div>
				</div>';
	}
	exit($str);
}else if($type=='delmemo'){
	$share=$_POST['share'];
	$id=$_POST['id'];
	if ($share=='share') {
		$res=$dosql->ExecNoneQuery("delete from `#@__share` where memo_id=$id and to_uid=$uid");
	}else{
		$res=$dosql->ExecNoneQuery("delete from `#@__memo` where id=$id and uid=$uid");
	}
	exit($res);
}else if($type=='getnews'){
	$category=$_POST['category'];
	$ar=get_news($category);
	$arr=$ar['data'];
	$str='';
	for ($i=0; $i < count($arr); $i++) { 
		$ml= $i%2==1 ? "ml20" : '' ;
		$str.='<div class="index-news-list '.$ml.'">
					<div class="index-news-con-tit"><a target="_blank" href="'.$arr[$i]['url'].'">'.$arr[$i]['title'].'</a></div>
					<div class="index-news-content">'.$arr[$i]['kw'].'</div>
					<div class="index-news-date">'.$arr[$i]['date'].'</div>
				</div>';
	}
	exit($str);
}else if($type=='addshare') {
	$id=$_POST['id'];
	$r=$dosql->GetOne("SELECT stock_code FROM `#@__memo` WHERE id=$id");
	$stock_code=$r['stock_code'];
	$user=$_POST['user'];
	$arr=explode(",",$user);
	for ($i=0; $i < count($arr); $i++) { 
		$arr2[]="'".$arr[$i]."'";
	}
	$user2=implode(",",$arr2);
	$_r=$dosql->GetOne("select count(*) as t from `#@__user` where username in ($user2)");
	if($_r['t']!=count($arr)) exit('输入的用户名有误！');
	$date=date('Y-m-d H:i:s');
	$sql="insert into `#@__share` (from_uid,from_user,to_uid,to_user,memo_id,stock_code,create_date) values ";
	$sql_arr=array();
	for ($i=0; $i < count($arr); $i++) { 
		$to_uid=get_userid($arr[$i]);
		$to_user=$arr[$i];
		if(!is_share($to_uid,$id)){//这条未分享过才会分享
			$sql_arr[]="('$uid','$username','$to_uid','$to_user','$id','$stock_code','$date')";
		}
	}
	if(empty($sql_arr)) exit('分享成功！');
	$sql.=@implode(",",$sql_arr);
	if($dosql->ExecNoneQuery($sql)){
		exit('分享成功！');
	}else{
		exit('分享失败！');
	}
}else if($type=='filter'){
	$k=$_POST['k'];//搜索关键字
	$start=$_POST['start'];
	$end=$_POST['end'];
	$importance=$_POST['importance'];
	$from=$_POST['from'];
	$page=$_POST['page'];
	$f=!empty($_POST['f']) ? $_POST['f'] : '' ;
	$arr=get_memo_list($start,$end,$importance,$from,$page,$k,$f);
	if(empty($arr)) exit('');
	$str='';
	$color=array('','red','yellow','blue');
	$plan=array('','买入','卖出');
	$status=array('空仓','持仓');
	for ($i=0; $i < count($arr); $i++) { 
		$is_over=strtotime($arr[$i]['end_date'])<strtotime(date('Y-m-d')) ? 'hui' : 'liang' ;
		$share=!empty($arr[$i]['is_share']) ? 'share' : 'self' ;
		$str.= '<div class="memo-list">
			<div class="memo-list1">
				<div class="list-shu shu-'.$color[$arr[$i]['importance']].'"></div>
				<div class="memo-list-tit memo-'.$is_over.'">
				'.$arr[$i]['stock_code'].'&nbsp;&nbsp;&nbsp;'.$arr[$i]['stock_name'].'&nbsp;&nbsp;&nbsp;<span class="memo-share">'.$arr[$i]['is_share'].'</span>
				</div>
				'.get_keywords($arr[$i]['keywords']).'
				<div class="memo-edit-'.$is_over.'"><a href="memo_update.php?id='.$arr[$i]['id'].'&share='.$share.'">编辑</a>&nbsp;&nbsp;&nbsp;<a onclick="return confirm(\'确定要删除吗？\');" href="?act=memodel&share='.$share.'&id='.$arr[$i]['id'].'">删除</a>&nbsp;&nbsp;&nbsp;<a href="javascript:share('.$arr[$i]['id'].');">分享</a></div>
			</div>
			<div class="memo-list2"><span class="memo-list2-tit1"></span><span class="memo-list2-tit2 memo-'.$is_over.'">'.$arr[$i]['content'].'</span></div>
			<div class="memo-list2"><span class="memo-list2-tit1">目前状态：</span><span class="memo-list2-tit2 memo-'.$is_over.'">'.$status[$arr[$i]['status']].'中，成本'.$arr[$i]['cost'].'元/'.$arr[$i]['code_num'].'股</span></div>
			<div class="memo-list2"><span class="memo-list2-tit1">计划：</span><span class="memo-list2-tit2 memo-'.$is_over.'">'.$arr[$i]['plan'].' '.$plan[$arr[$i]['plan_status']].'</span></div>
			<div class="memo-list3">'.$arr[$i]['start_date'].'至'.$arr[$i]['end_date'].'</div>
		</div>';
	}
	exit($str);
}
?>