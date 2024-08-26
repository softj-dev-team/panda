<?php include "../inc/header.php" ?>
<?
	$order_num = trim(sqlfilter($_REQUEST['order_num']));
	$cart_idx = trim(sqlfilter($_REQUEST['cart_idx']));
	$cancel_sect_1 = trim(sqlfilter($_REQUEST['cancel_sect_1']));
	$cancel_memo = trim(sqlfilter($_REQUEST['cancel_memo']));

	$refund_account = trim(sqlfilter($_REQUEST['refund_account']));
	$refund_nm = trim(sqlfilter($_REQUEST['refund_nm']));
	$refund_bank_code = trim(sqlfilter($_REQUEST['refund_bank_code']));

	$order_result = "can1";
	
	$query_pro = " update order_product set "; 
	$query_pro .= " orderstat = '".$order_result."', ";
	$query_pro .= " cancel_memo = '".$cancel_memo."', ";
	$query_pro .= " cancel_sect = '".$cancel_sect_1."', ";
	//$query_pro .= " cancel_admin = '".$_SESSION['admin_ganaf_id']."', ";

	$query_pro .= " refund_account = '".$refund_account."', ";
	$query_pro .= " refund_nm = '".$refund_nm."', ";
	$query_pro .= " refund_bank_code = '".$refund_bank_code."', ";

	$query_pro .= " repay_ing_date = now() ";
	$query_pro .= " where order_num = '".$order_num."'";
	$result_pro = mysqli_query($gconnet,$query_pro);


	$query = " update order_member set "; 
	$query .= " orderstat = '".$order_result."', ";
	$query .= " cancel_memo = '".$cancel_memo."', ";
	$query .= " cancel_sect = '".$cancel_sect_1."', ";
	//$query .= " cancel_admin = '".$_SESSION['admin_ganaf_id']."', ";

	$query .= " refund_account = '".$refund_account."', ";
	$query .= " refund_nm = '".$refund_nm."', ";
	$query .= " refund_bank_code = '".$refund_bank_code."', ";

	$query .= " repay_ing_date = now() ";
	$query .= " where order_num = '".$order_num."' ";
	$result = mysqli_query($gconnet,$query);

	$mem_sql = "select order_name,order_cell from order_member where order_num = '".$order_num."'";
	$mem_result = mysqli_query($gconnet,$mem_sql);
	$mem_row = mysqli_fetch_array($mem_result);
		
	$type = 0; // 0 : SMS , 5 : LMS
	$c_sms = common_sms($type,$mem_row[order_cell],"".$mem_row[order_name]." 님 ".$order_num." 주문이 취소처리 되었습니다.\r\n-친절한인선생-"); // 문자도 보낸다
?>
		<script>
			alert("결제 취소 되었습니다.");
			opener.document.location.reload();
			self.close();
		</script>
	