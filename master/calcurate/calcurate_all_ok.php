<?php include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?php include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<?php include $_SERVER["DOCUMENT_ROOT"].""."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?php

$calc_num = trim(sqlfilter($_REQUEST['calc_num']));
$total_price_total = trim(sqlfilter($_REQUEST['total_price_total']));
$calc_idx_arr = trim($_REQUEST['calc_idx_arr']);
$total_param =  trim(sqlfilter($_REQUEST['total_param']));
$pageNo =  trim(sqlfilter($_REQUEST['pageNo']));
$calc_per = trim(sqlfilter($_REQUEST['calc_per']));
$total_calc_total = trim(sqlfilter($_REQUEST['calc_total']));

$calc_idx_arr2 = explode(",",$calc_idx_arr);
for($k=0; $k<sizeof($calc_idx_arr2); $k++){
	$calc_idx = $calc_idx_arr2[$k];
	$admin_bigo = trim(sqlfilter($_REQUEST['admin_bigo_'.$calc_idx.'']));
	$dan_price_total = trim(sqlfilter($_REQUEST['dan_price_total_'.$calc_idx.'']));

	$calc_total = $dan_price_total * (1-$calc_per / 100);

	//echo $dan_price_total."<br>";

	$query = " update order_calcurate_info set "; 
	$query .= " calc_num = '".$calc_num."', ";
	$query .= " calc_stat = 'Y', ";
	$query .= " calc_per = '".$calc_per."', ";
	$query .= " calc_total = '".$calc_total."', ";
	$query .= " admin_bigo = '".$admin_bigo."', ";
	$query .= " calc_date = now() ";
	$query .= " where idx = '".$calc_idx."' ";
	
	//echo $query."<br>";
	$result = mysqli_query($gconnet,$query);

}

	$result_query = " insert into order_calcurate_result set "; 
	$result_query .= " calc_num = '".$calc_num."', ";
	$result_query .= " price_total = '".$total_price_total."', ";
	$result_query .= " calc_per = '".$calc_per."', ";
	$result_query .= " calc_total = '".$total_calc_total."', ";
	$result_query .= " calc_date = now() ";
	//echo $result_query."<br>";
	$result2 = mysqli_query($gconnet,$result_query);

	if($result){
	?>
	<script type="text/javascript">
	<!--
	alert('정상적으로 완료 되었습니다.');
	parent.location.href="calcurate_list.php?<?=$total_param?>&pageNo=<?=$pageNo?>";
	</script>
	<?php }else{?>
	<script type="text/javascript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</script>
	<?php }?>
