<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	
	$order_idx = $_REQUEST['order_idx'];
	
	for($k=0; $k<sizeof($order_idx); $k++){

		$query = " update order_member set ";
		$query .= " is_del = 'Y' ";
		$query .= " where idx = '".$order_idx[$k]."' ";
		$result =  mysqli_query($gconnet,$query);
		
	}
	
	error_frame_reload("정상적으로 삭제 되었습니다.");
	
?>
<!--<script type="text/javascript">
<!--	
	alert('정상적으로 삭제 되었습니다.');
	parent.location.href =  "member_payment.php?pageNo=<?=$pageNo?>&<?=$total_param?>";
	//
</script>-->
