<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$compet_idx = trim(sqlfilter($_REQUEST['compet_idx']));
	$regist_idx = trim(sqlfilter($_REQUEST['regist_idx']));
	$mode = trim(sqlfilter($_REQUEST['mode']));
	$estimate_idx = trim(sqlfilter($_REQUEST['estimate_idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));

	$txt_creative = trim(sqlfilter($_REQUEST['txt_creative']));
	$point_creative = trim(sqlfilter($_REQUEST['point_creative']));
	$txt_product = trim(sqlfilter($_REQUEST['txt_product']));
	$point_product = trim(sqlfilter($_REQUEST['point_product']));
	$txt_art = trim(sqlfilter($_REQUEST['txt_art']));
	$point_art = trim(sqlfilter($_REQUEST['point_art']));
	$txt_complete = trim(sqlfilter($_REQUEST['txt_complete']));
	$point_complete = trim(sqlfilter($_REQUEST['point_complete']));

	$max_query = "select max(align) as max from compet_regist_estimate_info where 1 ";
	$max_result = mysqli_query($gconnet,$max_query);
	$max_row = mysqli_fetch_array($max_result);
	if ($max_row['max']){
		$align = $max_row['max']+1;
	} else{
		$align = 1;
	}

	$admin_idx = $_SESSION['admin_coinc_idx'];
	$admin_name = $_SESSION['admin_coinc_name'];

	if($mode == "regist"){
		$query = " insert into compet_regist_estimate_info set "; 
		$query .= " compet_idx = '".$compet_idx."', ";
		$query .= " regist_idx = '".$regist_idx."', ";
		$query .= " txt_creative = '".$txt_creative."', ";
		$query .= " point_creative = '".$point_creative."', ";
		$query .= " txt_product = '".$txt_product."', ";
		$query .= " point_product = '".$point_product."', ";
		$query .= " txt_art = '".$txt_art."', ";
		$query .= " point_art = '".$point_art."', ";
		$query .= " txt_complete = '".$txt_complete."', ";
		$query .= " point_complete = '".$point_complete."', ";
		$query .= " align = '".$align."', ";
		$query .= " admin_idx = '".$admin_idx."', ";
		$query .= " admin_name = '".$admin_name."', ";
		$query .= " wdate = now() ";
		$result = mysqli_query($gconnet,$query);
	} elseif($mode == "update"){
		$query = " update compet_regist_estimate_info set "; 
		$query .= " txt_creative = '".$txt_creative."', ";
		$query .= " point_creative = '".$point_creative."', ";
		$query .= " txt_product = '".$txt_product."', ";
		$query .= " point_product = '".$point_product."', ";
		$query .= " txt_art = '".$txt_art."', ";
		$query .= " point_art = '".$point_art."', ";
		$query .= " txt_complete = '".$txt_complete."', ";
		$query .= " point_complete = '".$point_complete."', ";
		$query .= " admin_idx = '".$admin_idx."', ";
		$query .= " admin_name = '".$admin_name."', ";
		$query .= " mdate = now() ";
		$query .= " where 1 and idx='".$estimate_idx."'";
		$result = mysqli_query($gconnet,$query);
	} elseif($mode == "delete"){
		$query = " update compet_regist_estimate_info set "; 
		$query .= " is_del = 'Y', ";
		$query .= " admin_idx = '".$admin_idx."', ";
		$query .= " admin_name = '".$admin_name."', ";
		$query .= " mdate = now() ";
		$query .= " where 1 and idx='".$estimate_idx."'";
		$result = mysqli_query($gconnet,$query);
	}

	if($result){
		error_frame_go("정상적으로 처리 되었습니다.","regist_view.php?idx=".$regist_idx."&".$total_param."");
	} else {
		error_frame("오류가 발생했습니다.");
	}
?>