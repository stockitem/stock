<?php
$date=date('Y-m-d H:i:s');
if(!empty($_SESSION['uid'])) $uid=$_SESSION['uid'];
if(!empty($_SESSION['username'])) $username=$_SESSION['username'];
if ($_REQUEST['act']) {
	$act=$_REQUEST['act'];

	//增加会员
	if ($act=='adminadd') {
		chk_admin();
		$username=$_POST['username'];
		$password=$_POST['password'];
		$password2=$_POST['password2'];
		if ($password!=$password2) {
			layer_alert('两次密码输入不同！','admin_add.php');
		}
		$r=$dosql->GetOne("select id from `#@__user` where username='$username'");
		if ($r) {
			layer_alert('用户名重复！','admin_add.php');
		}
		$pwd=md5(md5($password));
		$sql="insert into `#@__user` (username,password,login_date,create_date) values ('$username','$pwd','$date','$date')";
		if ($dosql->ExecNoneQuery($sql)) {
			l('admin_list.php');	
		}
	}

	//编辑会员
	if ($act=='adminupdate') {
		chk_admin();
		$old_pwd=$_POST['old_pwd'];
		$new_pwd=$_POST['new_pwd'];
		$new_pwd2=$_POST['new_pwd2'];
		$uid=$_POST['uid'];
		if ($new_pwd!=$new_pwd2) {
			layer_alert('两次密码输入不同！','-1');
		}
		$r=$dosql->GetOne("select * from `#@__user` where id=$uid");
		if ($r['password']!=md5(md5($old_pwd))) {
			layer_alert('原密码不正确！','-1');	
		}
		$password=md5(md5($new_pwd));
		$ret=$dosql->ExecNoneQuery("update `#@__user` set password='$password' where id=$uid");
		layer_alert('修改密码成功！','admin_list.php');
	}

	//删除会员
	if ($act=='admindel') {
		chk_admin();
		$id=intval($_GET['id']);
		$ret=$dosql->ExecNoneQuery("delete from `#@__user` where id=$id");
		l('admin_list.php');
	}

	//登陆
	if ($act=='login') {
		$username=$_POST['username'];
		$password=$_POST['password'];
		$sql="select * from `#@__user` where username='$username'";
		$r=$dosql->GetOne($sql);
		if (empty($r)) {
			layer_alert('用户名不存在！','login.php');
		}
		if ($r['password']!=md5(md5($password))) {
			layer_alert('密码错误！','login.php');
		}
		$_SESSION['username']=$username;
		$_SESSION['uid']=$r['id'];
		$res=$dosql->ExecNoneQuery("update `#@__user` set login_date='$date' where username='$username'");
		l('index.php');
	}

	//退出
	if ($act=='logout') { 
		unset($_SESSION['username']);
		unset($_SESSION['uid']);
		header("Location:login.php");
	}

	//增加备忘
	if ($act=='memoadd') {
		$stock_code=$_POST['stock_code'];
		$arr=ecimplode_arr($stock_code);
		$keywords=ecimplode($_POST['keywords']);
		$start_date=$_POST['start_date'];
		$end_date=$_POST['end_date'];
		$importance=$_POST['importance'];
		$content=$_POST['content'];
		$status=$_POST['status'];
		$code_num=$_POST['code_num'];
		$cost=$_POST['cost'];
		$plan=$_POST['plan'];
		$plan_status=$_POST['plan_status'];
		if (count($arr)>1) {
			$sql="insert into `#@__memo` (uid,keywords,stock_code,stock_name,start_date,end_date,importance,content,status,code_num,cost,plan,plan_status,create_date) values ";
			for ($i=0; $i < count($arr); $i++) { 
				$s=$arr[$i];
				$sn=stock_handle($s);
				$sql_arr[]="('$uid','$keywords','$s','$sn','$start_date','$end_date','$importance','$content','$status','$code_num','$cost','$plan','$plan_status','$date')";
			}
			$sql.=implode(",",$sql_arr);
		}else{
			$stock_name=stock_handle($stock_code);
			$sql="insert into `#@__memo` (uid,keywords,stock_code,stock_name,start_date,end_date,importance,content,status,code_num,cost,plan,plan_status,create_date) values ('$uid','$keywords','$stock_code','$stock_name','$start_date','$end_date','$importance','$content','$status','$code_num','$cost','$plan','$plan_status','$date')";
		}
		if($dosql->ExecNoneQuery($sql)){
			layer_alert('新增备忘成功！','index.php');
		}
	}

	//修改备忘
	if ($act=='memoupdate') {
		$id=$_POST['id'];
		$share=$_POST['share'];
		$from=$_POST['from'];
		$keywords=ecimplode($_POST['keywords']);
		$stock_code=$_POST['stock_code'];
		$stock_name=stock_handle($stock_code);
		$start_date=$_POST['start_date'];
		$end_date=$_POST['end_date'];
		$importance=$_POST['importance'];
		$content=$_POST['content'];
		$status=$_POST['status'];
		$code_num=$_POST['code_num'];
		$cost=$_POST['cost'];
		$plan=$_POST['plan'];
		$plan_status=$_POST['plan_status'];
		if ($share=='share') {//如果是修改别人分享的要增加一条新的
			$sql="insert into `#@__memo` (uid,stock_code,stock_name,start_date,keywords,end_date,status,importance,content,code_num,cost,plan,plan_status,create_date) values ('$uid','$stock_code','$stock_name','$start_date','$keywords','$end_date','$status','$importance','$content','$code_num','$cost','$plan','$plan_status','$date')";	
		}else{
			$sql="update `#@__memo` set keywords='$keywords',stock_code='$stock_code',stock_name='$stock_name',start_date='$start_date',end_date='$end_date',importance='$importance',content='$content',status='$status',code_num='$code_num',cost='$cost',plan='$plan',plan_status='$plan_status' where id=$id and uid=$uid";
		}
		if($dosql->ExecNoneQuery($sql)){
			if ($from=='index'){
				layer_alert('编辑备忘成功！','index.php');
			}else{
				layer_alert('编辑备忘成功！','memo_list.php');
			}
		}
	}

	//删除备忘
	if ($act=='memodel') {
		$id=intval($_GET['id']);
		$from=$_GET['from'];
		$share=$_GET['share'];
		if ($share=='share') {
			$res=$dosql->ExecNoneQuery("delete from `#@__share` where memo_id=$id and to_uid=$uid");
		}else{
			$res=$dosql->ExecNoneQuery("delete from `#@__memo` where id=$id and uid=$uid");
		}
		if ($from=='index') {
			l('index.php');
		}else{
			l('memo_list.php');
		}
	}

	//增加提醒
	if ($act=='remindadd') {
		$dep_date=$_POST['dep_date'];
		$importance=$_POST['importance'];
		$advance_num=intval($_POST['advance_num']);
		$repeat_time=$_POST['repeat_time'];
		$content=$_POST['content'];
		$sql="insert into `#@__remind` (uid,dep_date,importance,advance_num,repeat_time,content,create_date) values ('$uid','$dep_date','$importance','$advance_num','$repeat_time','$content','$date')";
		if($dosql->ExecNoneQuery($sql)){
			layer_alert('新增提醒成功！','index.php');
		}
	}

}
?>