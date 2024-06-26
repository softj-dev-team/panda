<?php include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?php include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<?php include $_SERVER["DOCUMENT_ROOT"].""."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?php
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

$v_sect =  trim(sqlfilter($_REQUEST['v_sect']));
$v_cate =  trim(sqlfilter($_REQUEST['v_cate']));
$s_date =  trim(sqlfilter($_REQUEST['s_date']));
$e_date =  trim(sqlfilter($_REQUEST['e_date']));
$s_pay_type =  trim(sqlfilter($_REQUEST['s_pay_type']));
$s_pay_sect =  trim(sqlfilter($_REQUEST['s_pay_sect']));
$s_receipt_ok =  trim(sqlfilter($_REQUEST['s_receipt_ok']));
$s_taxbill_ok =  trim(sqlfilter($_REQUEST['s_taxbill_ok']));
$s_mem_sect =  trim(sqlfilter($_REQUEST['s_mem_sect'])); // 주문자 구분
$s_group = sqlfilter($_REQUEST['s_group']); // 입점업체

################## 파라미터 조합 #####################
$total_param = 'field='.$field.'&keyword='.urlencode($keyword).'&v_sect='.$v_sect.'&v_cate='.$v_cate.'&s_date='.$s_date.'&e_date='.$e_date.'&s_pay_type='.$s_pay_type.'&s_pay_sect='.$s_pay_sect.'&s_receipt_ok='.$s_receipt_ok.'&s_taxbill_ok='.$s_taxbill_ok.'&s_mem_sect='.$s_mem_sect.'&s_group='.$s_group;

	$idx = trim(sqlfilter($_REQUEST['idx']));
	$calc_stat = trim(sqlfilter($_REQUEST['calc_stat']));
	$admin_bigo = trim(sqlfilter($_REQUEST['admin_bigo']));
	
	$query = " update order_calcurate_info set "; 
	$query .= " calc_stat = '".$calc_stat."', ";
	$query .= " admin_bigo = '".$admin_bigo."', ";
	$query .= " calc_total = price_total, ";
	$query .= " calc_date = now() ";
	$query .= " where idx = '".$idx."' ";
	//echo $query;
	$result = mysqli_query($gconnet,$query);

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
