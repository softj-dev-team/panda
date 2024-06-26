<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
$v_sect = trim(sqlfilter($_REQUEST['v_sect']));
$mode = trim(sqlfilter($_REQUEST['mode']));
$msg_key = trim(sqlfilter($_REQUEST['msg_key']));
$send_sect = trim(sqlfilter($_REQUEST['send_sect']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));

$msg_method = trim(sqlfilter($_REQUEST['msg_method']));
$msg_gubun = trim(sqlfilter($_REQUEST['msg_gubun']));

$toemsg = trim(sqlfilter($_REQUEST['toemsg']));

$user_sect = trim(sqlfilter($_REQUEST['user_sect']));
$user_gubun = trim(sqlfilter($_REQUEST['user_gubun']));
$user_level = trim(sqlfilter($_REQUEST['user_level']));
$user_gender = trim(sqlfilter($_REQUEST['user_gender']));
$s_field = trim(sqlfilter($_REQUEST['s_field']));
$s_keyword = trim(sqlfilter($_REQUEST['s_keyword']));

$txt_receiver = $_REQUEST['txt_receiver'];
$fromname = trim(sqlfilter($_REQUEST['fromname']));
$fromemail = trim(sqlfilter($_REQUEST['fromemail']));
$subject = trim(sqlfilter($_REQUEST['subject']));
$content = trim(sqlfilter($_REQUEST['content']));

$member_idx = trim(sqlfilter($_SESSION['admin_coinc_idx']));								//user_id
$view_idx = trim(sqlfilter($_SESSION['admin_coinc_idx']));	 //view_id
$tour_homepage = trim(sqlfilter($_REQUEST['tour_homepage']));

	$max_query = "select max(ref) as max from board_content where 1=1 ";
	$max_result = mysqli_query($gconnet,$max_query);
	$max_row = mysqli_fetch_array($max_result);
	if ($max_row['max']){
		$max = $max_row['max']+1;
	} else{
		$max = 1;
	}
	
	$step = 0;
	$depth = 0;

	$user_id = $_SESSION['admin_coinc_id'];
	$view_id = $_SESSION['admin_coinc_id'];

	$query = " insert into board_content set "; 
	$query .= " member_idx = '".$member_idx."', ";
	$query .= " view_idx = '".$view_idx."', ";
	$query .= " user_id = '".$user_id."', ";
	$query .= " view_id = '".$view_id."', ";
	$query .= " bbs_code = 'push', ";
	$query .= " bbs_sect = '".$msg_key."', ";
	$query .= " ref = '".$max."', ";
	$query .= " step = '".$step."', ";
	$query .= " depth = '".$depth."', ";
	$query .= " subject = '".$subject."', ";
	$query .= " writer = '".$_SESSION['admin_coinc_name']."', ";
	$query .= " content = '".$content."', ";
	$query .= " auth_url = '".$tour_homepage."', ";
	$query .= " write_time = now() ";
	
	//echo $query; exit;
	
	$result = mysqli_query($gconnet,$query);
	
	$txt_receiver_arr = explode(",",$txt_receiver);

	for($k=0; $k<sizeof($txt_receiver_arr); $k++){
		$sql_pre = "select idx,push_key from member_info where 1 and user_id='".$txt_receiver_arr[$k]."' ";
		$result_pre = mysqli_query($gconnet,$sql_pre);
		$row_pre = mysqli_fetch_array($result_pre);
					
		$query_sub2 = " insert into send_msg_member set "; 
		$query_sub2 .= " mail_key = '".$msg_key."', ";
		$query_sub2 .= " member_idx = '".$row_pre[idx]."', ";
		$query_sub2 .= " mail_ok = 'Y' ";
		$result_sub2 = mysqli_query($gconnet,$query_sub2);
		
		if($row_pre['push_key']){
			send_fcm($subject,$content,$tour_homepage,$row_pre['push_key']); // 푸쉬발송
		}

	}

//exit;
?>

	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 발송 되었습니다.');
	parent.location.href =  "push_send_list.php?bmenu=1&smenu=5&mail_gubun=push";
	//-->
	</SCRIPT>