<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	
	$temple_idx = $_REQUEST['temple_idx_arr'];
	$temple_idx_arr = explode(",",$temple_idx);
 	
	for($k=0; $k<sizeof($temple_idx_arr); $k++){
		$up_temple_idx = trim($temple_idx_arr[$k]);
		$align = trim(sqlfilter($_REQUEST['align_'.$up_temple_idx.'']));

		$query = "update temple_info_new_list set";
		$query .= " align = '".$align."' ";
		$query .= " where 1 and idx = '".$up_temple_idx."'";
		$result =  mysqli_query($gconnet,$query);
	}
	
	error_frame_reload("순서적용이 정상적으로 처리 되었습니다.");
?>

