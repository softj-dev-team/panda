<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$order_num = trim(sqlfilter($_REQUEST['order_num']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$payment_date = trim(sqlfilter($_REQUEST['payment_date']));

	$sql_pre = "select member_idx,order_num,orderstat,pay_refund,price_total from order_member where order_num = '".$order_num."' and orderstat in ('pre')";
	//echo $sql_pre;
	$query_pre = mysqli_query($gconnet,$sql_pre);
	
	if(mysqli_num_rows($query_pre) > 0){ // 조건에 해당하는 주문이 있을경우 시작 
		
		$row_pre = mysqli_fetch_array($query_pre);

		$member_idx = $row_pre[member_idx];
		$order_num = $row_pre[order_num];
		$orderstat = $row_pre[orderstat];
		$use_refund = $row_pre['pay_refund'];
		$price_total = $row_pre['price_total'];

		$order_stat = "com";
		
		$query = " update order_member set "; 
		$query .= " orderstat = '".$order_stat."', ";
		$query .= " payment_date = '".$payment_date."' ";
		$query .= " where order_num = '".$order_num."' ";
		$result = mysqli_query($gconnet,$query);

		$query2 = " update compet_info set "; 
		$query2 .= " pstatus = '".$order_stat."', ";
		$query2 .= " wdate = '".$payment_date."' ";
		$query2 .= " where order_num = '".$order_num."' ";
		$result2 = mysqli_query($gconnet,$query2);

		//echo $query;

	} // 조건에 해당하는 주문이 있을경우 종료
?>
<script type="text/javascript">
	alert("정상적으로 처리 되었습니다.");
	window.parent.document.location.href = window.parent.document.URL;
	//parent.location.href =  "order_list.php?bmenu=4&smenu=1&v_sect=com";
</script>