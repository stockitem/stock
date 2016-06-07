<?php
//公共函数

function get_news($category){
	global $dosql;
	$uid=$_SESSION['uid'];
	$dosql->Execute("SELECT * FROM `#@__memo` WHERE uid=$uid ORDER BY id ASC");
	while($row = $dosql->GetArray()){
		$arr[]=$row['stock_code'].';'.$row['stock_name'].';'.$row['keywords'];
	}
	$keywords=!empty($arr) ? implode(";",$arr) : '' ;
	$data=array('a'=>'aa');
	$keywords=urlencode($keywords);
	//$url='http://localhost:8000/news_api/get_news/?format=json&kw='.$keywords.'&page=1&limit=10&category='.$category;
	//$json=sendPostRequst($url,$data);
	$json='{"total_pages":3,"data":[{"kw":"\u5173\u952e\u8bcd","source":"\u65b0\u6d6a\u65b0\u95fb","url":"http://www.baidu.com","title":"\u4eca\u5929","date":"2016-01-01 18:50:02"},{"kw":"\u5173\u952e\u8bcd","source":"\u65b0\u6d6a\u65b0\u95fb","url":"http://www.baidu.com","title":"\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u59291","date":"2016-01-01 18:50:02"},{"kw":"\u5173\u952e\u8bcd","source":"\u65b0\u6d6a\u65b0\u95fb","url":"http://www.baidu.com","title":"\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u59292","date":"2016-01-02 18:50:03"},{"kw":"\u5173\u952e\u8bcd","source":"\u65b0\u6d6a\u65b0\u95fb","url":"http://www.baidu.com","title":"\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u59293","date":"2016-01-04 18:50:06"},{"kw":"\u5173\u952e\u8bcd","source":"\u65b0\u6d6a\u65b0\u95fb","url":"http://www.baidu.com","title":"\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u59294","date":"2016-01-01 18:50:02"},{"kw":"\u5173\u952e\u8bcd","source":"\u65b0\u6d6a\u65b0\u95fb","url":"http://www.baidu.com","title":"\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u59295","date":"2016-01-01 18:50:02"},{"kw":"\u5173\u952e\u8bcd","source":"\u65b0\u6d6a\u65b0\u95fb","url":"http://www.baidu.com","title":"\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u59296","date":"2016-01-01 18:50:02"},{"kw":"\u5173\u952e\u8bcd","source":"\u65b0\u6d6a\u65b0\u95fb","url":"http://www.baidu.com","title":"\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u59297","date":"2016-01-01 18:50:02"},{"kw":"\u5173\u952e\u8bcd","source":"\u65b0\u6d6a\u65b0\u95fb","url":"http://www.baidu.com","title":"\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u59298","date":"2016-01-01 18:50:02"},{"kw":"\u5173\u952e\u8bcd","source":"\u65b0\u6d6a\u65b0\u95fb","url":"http://www.baidu.com","title":"\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u5929\u4eca\u59299","date":"2016-01-01 18:50:02"}]}';
	return !empty($json) ? json_decode($json,true) : array() ;
}

function curl_post($url,$data){
	$curlPost = http_build_query($data);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

function sendPostRequst($url, $data) {
	//$data=array('foo'=>'bar','name'=>'asdasd');
	$postdata = http_build_query ( $data );
	$opts = array (
		'http' => array (
			'method' => 'POST',
			'header' => 'Content-type:application/x-www-form-urlencoded',
			'content' => $postdata 
		) 
	);
	$context = stream_context_create ( $opts );
	$result = file_get_contents ( $url, false, $context );
	return $result;
}

function check_login(){
	if (empty($_SESSION['uid']) || empty($_SESSION['username'])) {
		header("Location:login.php");
		exit;
	}
}

function layer_alert($con,$url){
	if ($url=='-1') {
		echo "<script>alert('".$con."');history.go(-1);</script>";
	}else{
		echo "<script>alert('".$con."');location.href='".$url."';</script>";
	}
    exit;
}

function l($url){
	echo "<script>location.href='".$url."';</script>";
    exit;
}

//检查是否是管理员
function chk_admin(){
	if ($_SESSION['username']!='admin') {
		layer_alert('无权限操作！','-1');
	}
}

function ecimplode_arr($str){
	if (!empty($str)) {
		$str2=str_replace('，',',',$str);
		$arr=explode(",", $str2);
	}else{
		$arr=array();
	}
	return $arr;
}

function ecimplode($str){
	$string='';
	if(!empty($str)){
		$str2=str_replace('，',',',$str);
		$string=str_replace(',',';',$str2);
	}
	return $string;
}

function get_allusers($id){
	global $dosql;
	$uid=$_SESSION['uid'];
	$r=$dosql->GetOne("select uid from `#@__memo` where id=$id");
	$_uid=$r['uid'];
	$arr=array();
	$dosql->Execute("SELECT to_uid FROM `#@__share` WHERE memo_id=$id ORDER BY id ASC");
	while($row = $dosql->GetArray()){
		$arr[]=$row['to_uid'];
	}
	$dosql->Execute("SELECT * FROM `#@__user` WHERE id!=$uid and id!=$_uid ORDER BY id ASC");//自己不能分享给自己，别人分享的不能再分享回去
	while($row = $dosql->GetArray()){
		$userid=$row['id'];
		if(!in_array($userid, $arr)){
			$arr[]=$row['username'];
		}
	}
	return $arr;	
}

function get_userid($user){
	global $dosql;
	$r=$dosql->GetOne("select id from `#@__user` where username='$user'");
	return $r['id'];
}

function is_share($to_uid,$memo_id){
	global $dosql;
	$r=$dosql->GetOne("select id from `#@__share` where to_uid=$to_uid and memo_id=$memo_id");
	return empty($r) ? false : true ;
}

function handle_arr($arr){
	if (!empty($arr)) {
		for ($i=0; $i < count($arr); $i++) { 
			$ar[]="'".$arr[$i]."'";
		}
		return implode(",",$ar);
	}else{
		return '';
	}
}

//处理stock_code,忽略后面的.SZ
function stock_handle($stock_code){
	$code1=$stock_code.'.SZ';
	$code2=$stock_code.'.SH';
	$_code1=get_codename($code1);
	$_code2=get_codename($code2);
	return !empty($_code1) ? $_code1 : $_code2 ;
}

function get_codename($code){
	if(empty($code)) return '';
	$code_name='';
	require_once 'Classes/PHPExcel/IOFactory.php';
	$reader = PHPExcel_IOFactory::createReader('Excel2007'); //设置以Excel5格式(Excel97-2003工作簿)
	$PHPExcel = $reader->load("excel/1.xlsx"); // 载入excel文件
	$sheet = $PHPExcel->getSheet(0); // 读取第一個工作表
	$highestRow = $sheet->getHighestRow(); // 取得总行数
	$highestColumm = $sheet->getHighestColumn(); // 取得总列数
	/** 循环读取每个单元格的数据 */
	for ($row = 1; $row <= $highestRow; $row++){//行数是以第1行开始
	    for ($column = 'A'; $column <= $highestColumm; $column++) {//列数是以A列开始
	    	$dataset[] = $sheet->getCell($column.$row)->getValue();
	    }
	}
	$arr=array_chunk($dataset, 2);
	for ($i=0; $i < count($arr); $i++) { 
		if ($code==$arr[$i][0]) {
			$code_name=$arr[$i][1];
			break;
		}
	}
	return $code_name;
}

function get_share($uid,$stock_code){
	global $dosql;
	$arr=array();
	$i=0;
	$dosql->Execute("SELECT stock_code,memo_id,from_user FROM `#@__share` WHERE to_uid=$uid and stock_code='$stock_code' ORDER BY id ASC");
	while($row = $dosql->GetArray()){
		$arr[0][$i]=$row['memo_id'];
		$arr[1][$row['memo_id']]=$row['from_user'];
		$i++;
	}
	return $arr;
}

function get_keywords($keywords){
	$str='';
	if (!empty($keywords)) {
		$arr=explode(";",$keywords);
		for ($i=0; $i < count($arr); $i++) { 
			$str.='<div class="memo-list-mark">'.$arr[$i].'</div>';
		}
	}
	return $str;
}

function get_memo_list($start,$end,$importance,$from,$page,$k,$f){
	global $dosql;
	$uid=$_SESSION['uid'];
	$arr=array();
	$sql="1=1";
	if (!empty($start)) {
		$sql.=" and start_date>='$start'";
	}
	if (!empty($end)) {
		$sql.=" and end_date<='$end'";
	}
	if (!empty($importance) && $importance!='undefined' && $importance!='all') {
		$sql.=" and importance='$importance'";
	}
	if (!empty($k)) {
		$sql.=" and stock_code like '%".$k."%'";
	}
	$dosql->Execute("SELECT from_uid,memo_id FROM `#@__share` WHERE to_uid=$uid ORDER BY id DESC");
	while($row = $dosql->GetArray()){
		$arr_id[]=$row['memo_id'];
	}
	$page=$f=='ajax' ? 1 : $page ;
	$limit=($page-1)*4;
	if($from=='self'){//自己上传的
		$sql.=" and uid=$uid";
	}else if($from=='share'){//别人分享给我的
		if (!empty($arr_id)) {
			$str_id=implode(",",$arr_id);
			$sql.=" and id in (".$str_id.")";
		}else{
			$sql.=" and id<0";
		}
	}else{//二者都有
		if (!empty($arr_id)) {
			$str_id=implode(",",$arr_id);
			$sql.=" and (uid=$uid or id in (".$str_id."))";
		}else{
			$sql.=" and uid=$uid";
		}
	}
	$dosql->Execute("SELECT * FROM `#@__memo` WHERE $sql ORDER BY end_date DESC,importance ASC,plan ASC limit $limit,4");
	while($row = $dosql->GetArray()){
		$row['is_share']=is_share2($from,$row['id'],$uid);
		$arr[]=$row;
	}
	return $arr;
}

function is_share2($from,$memo_id,$uid){
	global $dosql;
	if ($from=='self') {
		$str='';
	}else{
		$r=$dosql->GetOne("select from_user from `#@__share` where to_uid=$uid and memo_id=$memo_id");
		$str=!empty($r) ? '(来自'.$r['from_user'].'的分享)' : '' ;
	}
	return $str;
}

function get_remind(){
	global $dosql;
	$uid=$_SESSION['uid'];
	$arr=array();
	$date=date('Y-m-d');
	$dosql->Execute("SELECT * FROM `#@__remind` WHERE uid=$uid ORDER BY importance ASC,id DESC");
	while($row = $dosql->GetArray()){
		$day_num=$row['advance_num'];
		$fu="-".$day_num;
		$dep_date=$row['dep_date'];
		$date2=date('Y-m-d',strtotime("$fu day $dep_date"));

		if ($row['repeat_time']=='no') {//不重复--如果设置了提前多少个交易日，则是从触发日期起往前推n天，从那天起一直到触发日期每天都提醒
			if ($day_num>0) {
				if (strtotime($date)>=strtotime($date2) && strtotime($date)<=strtotime($dep_date)) {
					$arr[]=$row['content'];
				}
			}else{
				if ($date==$dep_date) {
					$arr[]=$row['content'];
				}
			}
		}elseif ($row['repeat_time']=='day') {//每日--触发日期之后每天都重复
			if (strtotime($date)>=strtotime($dep_date)) {
				$arr[]=$row['content'];
			}
		}elseif ($row['repeat_time']=='week') {//每周
			if ($day_num>=6) {//大于等于6就天天提醒
				$arr[]=$row['content'];
			}else{
				$w_date=date("w",strtotime($date));
				$w_dep_date=date("w",strtotime($dep_date));
				$w_num=$w_dep_date-$day_num>0 ? $w_dep_date-$day_num : $w_dep_date-$day_num+7;
				if ($w_num<$w_dep_date) {
					if ($w_date<=$w_dep_date && $w_date>=$w_num) {
						$arr[]=$row['content'];
					}
				}else{
					if ($w_date<=$w_dep_date || $w_date>=$w_num) {
						$arr[]=$row['content'];
					}
				}
			}
		}elseif ($row['repeat_time']=='month') {//每月
			if ($day_num>=27) {//大于27天就天天提醒
				$arr[]=$row['content'];
			}else{
				$d_date=date("d",strtotime($date));
				$d_dep_date=date("d",strtotime($dep_date));
				$d_num=$d_dep_date-$day_num>0 ? $d_dep_date-$day_num : $d_dep_date-$day_num+30 ;
				if ($d_num<$d_dep_date) {
					if ($d_date<=$d_dep_date && $d_date>=$d_num) {
						$arr[]=$row['content'];
					}
				}else{
					if ($d_date<=$d_dep_date || $d_date>=$d_num) {
						$arr[]=$row['content'];
					}
				}
			}
		}
	}
	return $arr;
}
?>