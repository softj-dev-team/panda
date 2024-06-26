<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$is_del = trim(sqlfilter($_REQUEST['is_del']));

	$sdate = trim(sqlfilter($_REQUEST['sdate']));
	$edate = trim(sqlfilter($_REQUEST['edate']));
	$view_ok = trim(sqlfilter($_REQUEST['view_ok']));
	$align = trim(sqlfilter($_REQUEST['align']));

	$query = "update main_select_info set"; 
	$query .= " sdate = '".$sdate."', ";
	$query .= " edate = '".$edate."', ";
	$query .= " view_ok = '".$view_ok."', ";
	//$query .= " align = '".$align."', ";
	$query .= " is_del = '".$is_del."', ";
	$query .= " mdate = now() ";
	$query .= " where 1 and idx='".$idx."'";
	$result = mysqli_query($gconnet,$query);

	if($is_del == "Y"){
		$is_del_txt = "삭제";
	} else {
		$is_del_txt = "수정";
	}

	error_frame_go($is_del_txt." 되었습니다.","main_select_top_list.php?".$total_param."&pageNo=".$pageNo);

?>