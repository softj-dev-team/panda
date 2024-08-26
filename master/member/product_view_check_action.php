<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?
	$sale_member_idx = $_POST['sale_member_idx'];
	for($si=0; $si<sizeof($sale_member_idx); $si++){
		if($si == sizeof($sale_member_idx)-1){
			$sale_member_idx_arr .= $sale_member_idx[$si];
		} else {
			$sale_member_idx_arr .= $sale_member_idx[$si].",";
		}
	}
?>

	<script>
		$("#cancel_mode", parent.document).val("all");
		$("#cancel_member", parent.document).val("<?=$sale_member_idx_arr?>");
		parent.cancel_2_close();
		parent.cancel_3_open();
	</script>