<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$calc_yn = trim(sqlfilter($_REQUEST['calc_yn']));

	$query = "update product_sale_history_calc set";
	if($calc_yn == "Y"){
		$query .= " calc_status = '1',";
	} elseif($calc_yn == "N"){
		$query .= " calc_status = '2',";
	}
	$query .= " calc_yn = '".$calc_yn."',";
	if($calc_yn == "Y"){
		$query .= " date_com = now()";
	} elseif($calc_yn == "N"){
		$query .= " date_com = NULL";
	}
	$query .= " where 1 and order_num=(select order_num from product_sale_history where 1 and idx='".$idx."' and is_del='N')";
	$result = mysqli_query($gconnet,$query);

	//frame_go("popup_sale_calc_view.php?idx=".$idx."");
?>
	<script>
		parent.location.href="popup_sale_calc_view.php?idx=<?=$idx?>";
		parent.opener.member_tab_calc();
	</script>