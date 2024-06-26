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

	$query_sub1 = " insert into send_msg set "; 
	$query_sub1 .= " mail_key = '".$msg_key."', ";
	$query_sub1 .= " mail_method = 'email', ";
	$query_sub1 .= " mail_gubun = '".$msg_gubun."', ";
	
	$query_sub1 .= " user_level = '".$user_level."', ";
	$query_sub1 .= " user_sect = '".$user_sect."', ";
	$query_sub1 .= " user_gubun = '".$user_gubun."', ";
	$query_sub1 .= " user_gender = '".$user_gender."', ";
	
	$query_sub1 .= " s_field = '".$s_field."', ";
    $query_sub1 .= " s_keyword = '".$s_keyword."', ";
	$query_sub1 .= " fromname = '".$fromname."', ";
	$query_sub1 .= " fromemail = '".$fromemail."', ";
	$query_sub1 .= " subject = '".$subject."', ";
	$query_sub1 .= " content = '".addslashes($content)."', ";
	$query_sub1 .= " wdate = now() ";
	//echo $query_sub1; 
	$result_sub1 = mysqli_query($gconnet,$query_sub1);

	$search = "/PROGRAM_FCKeditor_UserFiles";
	$replace = "http://".$_SERVER['HTTP_HOST']."/PROGRAM_FCKeditor_UserFiles";
	$m_content = str_replace($search, $replace, $content);
	$m_content = addslashes($m_content);
	
	$txt_receiver_arr = explode(",",$txt_receiver);

	for($k=0; $k<sizeof($txt_receiver_arr); $k++){
		$sql_pre = "select idx,email from member_info where 1 and email='".$txt_receiver_arr[$k]."' ";
		$result_pre = mysqli_query($gconnet,$sql_pre);
		$row_pre = mysqli_fetch_array($result_pre);
		$recv_email = $row_pre[email];
		
		$all_mail = mail_utf($fromemail,$fromname,$recv_email,$subject,$m_content); // 메일을 발송한다.
		
		$query_sub2 = " insert into send_msg_member set "; 
		$query_sub2 .= " mail_key = '".$msg_key."', ";
		$query_sub2 .= " member_idx = '".$row_pre[idx]."', ";
		$query_sub2 .= " mail_ok = 'Y' ";
		$result_sub2 = mysqli_query($gconnet,$query_sub2);
	}

//exit;
?>

	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 발송 되었습니다.');
	parent.location.href =  "mail_send_list.php?bmenu=2&smenu=7&mail_gubun=mail&v_sect=PAT";
	//-->
	</SCRIPT>