<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$order_num = trim(sqlfilter($_REQUEST['order_num']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$admin_bigo = trim(sqlfilter($_REQUEST['admin_bigo']));

	//$repaystat = trim(sqlfilter($_REQUEST['repaystat']));
	
	$query = " update order_member set "; 
	$query .= " admin_bigo = '".$admin_bigo."' ";
	//$query .= " repaystat = '".$repaystat."' ";
	$query .= " where order_num = '".$order_num."' ";
	
	$result = mysqli_query($GLOBALS['gconnet'],$query);

	//exit;
?>

<script type="text/javascript">
	alert("관리자 비고메모 입력이 정상적으로 처리 되었습니다.");
	window.parent.document.location.href = window.parent.document.URL;
</script>