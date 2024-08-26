<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));

	$status = trim(sqlfilter($_REQUEST['status']));
	$content_answ = trim(sqlfilter($_REQUEST['content_answ']));
	if($status == "Y"){
		$admin_idx = $_SESSION['admin_coinc_idx'];
	} else {
		$admin_idx = "";
	}
	
	$query = " update declaration_info set ";
	$query .= " status = '".$status."', ";
	$query .= " content_answ = '".$content_answ."', ";
	if($status == "Y"){
		$query .= " addate = now(), ";
	} else {
		$query .= " addate = NULL, ";
	}
	$query .= " admin_idx = '".$admin_idx."' ";
	$query .= " where idx = '".$idx."' ";
	$result = mysqli_query($gconnet,$query);
	
	error_frame_go("답변 설정이 완료되었습니다.","decla_view.php?idx=".$idx."&".$total_param."");
?>
