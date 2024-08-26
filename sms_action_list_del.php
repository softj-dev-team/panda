<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/check_login_frame.php"; // 공통함수 인클루드 ?>
<?
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	
	$save_idx = $_REQUEST['save_idx'];
	
	for($k=0; $k<sizeof($save_idx); $k++){
		$query = "update sms_save set";
		$query .= " is_del = 'Y', ";
		$query .= " ddate = now() ";
		$query .= " where 1 and idx = '".$save_idx[$k]."'";
		$result =  mysqli_query($gconnet,$query);
	}
?>
	<script>
		parent.sms_save_list();
	</script>