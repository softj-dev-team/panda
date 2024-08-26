<?php
ob_start();
$SESS_TABLE="sessionsmysql";

$SESS_DBHOST = "localhost";		/* 호스트명 */
$SESS_DBNAME = "popupstudy2";		/* DB */
$SESS_DBUSER = "popupstudy2";		/* user */
$SESS_DBPASS = "popup5819";		/* password */

$SESS_DBH = "";
$SESS_LIFE = get_cfg_var("session.gc_maxlifetime");

$reurl_ft_go = trim($_REQUEST['reurl_ft_go']);
//echo $pops_id; exit;
//==========================
// function: sess_open();
//==========================
function sess_open($save_path, $session_name) {
	
	global $SESS_DBHOST, $SESS_DBNAME, $SESS_DBUSER, $SESS_DBPASS;

	if (!$SESS_DBH = mysql_connect($SESS_DBHOST, $SESS_DBUSER, $SESS_DBPASS)) {
		die;
	}

	if (!mysql_select_db($SESS_DBNAME)) {
		die;
	}

	return true;
}

//==========================
// function: sess_close();
//==========================
function sess_close() {
	return true;
}

//==========================
// function: sess_read()
//==========================
function sess_read($key) {
	global $SESS_LIFE,$SESS_TABLE;
	//$ADR=md5($_SERVER['REMOTE_ADDR']); 

	$ADR=$_SERVER['REMOTE_ADDR']; 
	$pops_id = trim($_REQUEST['pops_id']);
	$value = $pops_id;

	$sql_pre2 = "select idx from member_info where user_id = '".$value."' order by idx desc limit 0,1"; 
	$result_pre2  = mysql_query($sql_pre2);
	$mem_row2 = mysql_fetch_array($result_pre2);
	$pops_idx = $mem_row2['idx']; 

	// 나중에 추가한 부분 - 시간이 지난 세션키는 삭제 한다. 
	$qry1 = "DELETE FROM ".$SESS_TABLE." WHERE value = '".$pops_id."' and member_idx = '".$pops_idx."' AND expiry < " . time();
	//echo $qry1; exit;
	$qid1 = mysql_query($qry1);

	$qry = "SELECT value,expiry FROM ".$SESS_TABLE." WHERE value = '".$pops_id."' and member_idx = '".$pops_idx."' and uip != '".$ADR."' AND expiry > " . time();
	$qid = mysql_query($qry);
	$row = mysql_fetch_array($qid);
	
	if($value == $row['value']){
		//error_frame("이미 로그인되어 있습니다.");
		//exit;
	} 

	return "";// return false;
}

//==========================
// function: sess_write()
//==========================
function sess_write($key, $val) {
// $_SESSION["pwd"]만 사용해도 시간을 늘어 난다. update 문 
// session.gc_maxlifetime=1000 시간만큼 늘게 해서 저장한다. +1000

	global $SESS_LIFE,$SESS_TABLE;

	$expiry = time() + $SESS_LIFE;
	
	/*
	echo "<p>";
	echo date("Y-m-d H:i:s",time())."<p>";
	echo date("Y-m-d H:i:s",$expiry)."<p>";
	exit;
	*/
	$pops_id = trim($_REQUEST['pops_id']);
	//$value = addslashes($val);
	$value = $pops_id;
	//$ADR=md5($_SERVER['REMOTE_ADDR']); 
	$ADR=$_SERVER['REMOTE_ADDR']; 

	$sql_pre2 = "select idx from member_info where user_id = '".$value."' order by idx desc limit 0,1"; 
	$result_pre2  = mysql_query($sql_pre2);
	$mem_row2 = mysql_fetch_array($result_pre2);
	$pops_idx = $mem_row2['idx']; 

	$qry = "INSERT INTO ".$SESS_TABLE." VALUES ('','".$key."', ".$expiry.", '".$pops_idx."', '".$value."','".$ADR."')";
	//echo "<p>".$qry; 
	//exit;
	$qid = mysql_query($qry);
	
	$connect_ip = $_SERVER['REMOTE_ADDR'];
	
	$logout_date = date("Y-m-d H:i:s", $expiry);

	$query = " insert into member_login_stat set ";
	$query .= " member_idx = '".$pops_idx."', ";
	$query .= " user_id = '".$pops_id."', ";
	$query .= " sess_start = now(), ";
	$query .= " sess_end = '".$logout_date."', ";
	$query .= " connect_ip = '".$connect_ip."' ";
	$result = mysql_query($query);

	$prev_sql1 = "select idx from member_login_stat where user_id='".$pops_id."' and member_idx = '".$pops_idx."' order by idx desc limit 0,1 ";
	$prev_result1 = mysql_query($prev_sql1);
	$prev_row = mysql_fetch_array($prev_result1);

	$prev_sql3 = "update member_login_stat set sess_end = '".$logout_date."' where idx='".$prev_row[idx]."' ";
	//echo $row['expiry']; exit;
	$prev_result3 = mysql_query($prev_sql3);

	if (!$qid) {
		// print " a ";
		$qry = "UPDATE ".$SESS_TABLE." SET expiry = ".$expiry.", member_idx = '".$pops_idx."' , value = '".$value."' WHERE uip='".$ADR."' AND sesskey = '".$key."' AND expiry > " . time();
		
		$qid = mysql_query($qry);
	}

	return $qid;
}

//==========================
// function: sess_destroy()
//==========================
function sess_destroy($key) {
	global $SESS_TABLE;

	$qry = "DELETE FROM ".$SESS_TABLE." WHERE sesskey = '".$key."' ";
	$qid = mysql_query($qry);

	return $qid;
}

//==========================
// function: sess_gc()
// 세션 중에 세션 지속시간을 넘긴 것들은 쓰레기로 여기고 삭제 
//==========================
function sess_gc($maxlifetime) {
	global $SESS_TABLE;

	$qry = "DELETE FROM ".$SESS_TABLE." WHERE expiry < " . time();
	$qid = mysql_query($qry);

	return mysql_affected_rows();
}

session_set_save_handler(
	"sess_open",
	"sess_close",
	"sess_read",
	"sess_write",
	"sess_destroy",
	"sess_gc");

session_start();
?>
