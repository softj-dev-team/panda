<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
	$type = trim(sqlfilter($_REQUEST['type']));
	
	$ad_url = trim(sqlfilter($_REQUEST['ad_url']));
	$sdate = trim(sqlfilter($_REQUEST['sdate']));
	$edate = trim(sqlfilter($_REQUEST['edate']));
	$title = trim(sqlfilter($_REQUEST['title']));
	$content = trim(sqlfilter($_REQUEST['content']));

	$query = "insert into advertising_info set"; 
	$query .= " type = '".$type."', ";
	$query .= " ad_url = '".$ad_url."', ";
	$query .= " sdate = '".$sdate."', ";
	$query .= " edate = '".$edate."', ";
	$query .= " title = '".$title."', ";
	$query .= " content = '".$content."', ";
	$query .= " admin_idx = '".$_SESSION['admin_coinc_idx']."', ";
	$query .= " wdate = now() ";
	//echo $query;
	$result = mysqli_query($gconnet,$query);

	error_frame_go("등록되었습니다.","advert_list.php?bmenu=".$bmenu."&smenu=".$smenu."");
?>