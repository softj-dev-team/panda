<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$keyword = sqlfilter($_REQUEST['keyword']);
	$s_uid = sqlfilter($_REQUEST['s_uid']); // 아이디
	$s_uname = sqlfilter($_REQUEST['s_uname']); // 성명
	$cr_cate = sqlfilter($_REQUEST['cr_cate']); // 구분
	$cr_s_date = sqlfilter($_REQUEST['cr_s_date']); // 기간1
	$cr_e_date = sqlfilter($_REQUEST['cr_e_date']); // 기간2
	$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
	$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 
	################## 파라미터 조합 #####################
	$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&keyword='.$keyword.'&s_uid='.$s_uid.'&s_uname='.$s_uname.'&cr_s_date='.$cr_s_date.'&cr_e_date='.$cr_e_date.'&cr_cate='.$cr_cate.'&s_cnt='.$s_cnt.'&s_order='.$s_order;

	$query = "update declaration_info set"; 
	$query .= " is_del = 'Y', ";
	$query .= " admin_idx = '".$_SESSION['admin_coinc_idx']."', ";
	$query .= " mdate = now() ";
	$query .= " where 1 and idx='".$idx."'";
	//echo $query;
	$result = mysqli_query($gconnet,$query);

	error_frame_go("삭제되었습니다.","decla_list.php?".$total_param."");
?>