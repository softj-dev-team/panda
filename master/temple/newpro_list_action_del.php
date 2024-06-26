<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	
	$temple_idx = $_REQUEST['temple_idx'];
	
	for($k=0; $k<sizeof($temple_idx); $k++){
		$query = "delete from temple_info_new_list";
		$query .= " where 1 and idx = '".$temple_idx[$k]."'";
		$result =  mysqli_query($gconnet,$query);
	}
	
	error_frame_reload("정상적으로 삭제 되었습니다.");
?>

