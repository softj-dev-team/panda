<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));

	$date_s = trim(sqlfilter($_REQUEST['date_s'])); 
	$date_e = trim(sqlfilter($_REQUEST['date_e']));
	$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
	$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
	$s_protype_arr = urldecode($_REQUEST['s_protype']);
	$s_salemtd = trim(sqlfilter($_REQUEST['s_salemtd'])); 
	$s_salests = trim(sqlfilter($_REQUEST['s_salests']));
	$field = "product_title";
	$keyword = trim(sqlfilter($_REQUEST['keyword']));
	
	$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
	$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 

	$total_param = "bmenu=".$bmenu."&smenu=".$smenu."&date_s=".$date_s."&date_e=".$date_e."&s_sect1=".$s_sect1."&s_sect2=".$s_sect2."&s_protype=".urlencode($s_protype_arr)."&s_salemtd=".$s_salemtd."&s_salests=".$s_salests."&keyword=".$keyword."&s_cnt=".$s_cnt."&s_order=".$s_order;

	$query = "update product_info set";
	$query .= " is_del = 'Y', ";
	$query .= " mdate = now() ";
	$query .= " where 1 and idx='".$idx."'";
	$result = mysqli_query($gconnet,$query);

	$query_sale = "update product_info_sale set";
	$query_sale .= " is_del = 'Y', ";
	$query_sale .= " date_cancel = now() ";
	$query_sale .= " where 1 and product_idx='".$idx."'";
	$result_sale = mysqli_query($gconnet,$query_sale);

	$query_sale_history = "update product_sale_history set";
	$query_sale_history .= " is_del = 'Y' ";
	$query_sale_history .= " where 1 and product_idx='".$idx."'";
	$result_sale_history = mysqli_query($gconnet,$query_sale_history);

	$query_sale_history_calc = "update product_sale_history_calc set";
	$query_sale_history_calc .= " is_del = 'Y' ";
	$query_sale_history_calc .= " where 1 and product_idx='".$idx."'";
	$result_sale_history_calc = mysqli_query($gconnet,$query_sale_history_calc);

	error_frame_go("삭제되었습니다.","product_list.php?".$total_param."");

?>