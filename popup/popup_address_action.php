<?
	include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 
	include $_SERVER['DOCUMENT_ROOT']."/pro_inc/check_login_popup.php"; // 로그인 체크

	$addr_idx = trim(sqlfilter($_REQUEST['addr_idx']));

	$mem_coupon_sql = "select * from member_address_set where idx='".$addr_idx."' ";
	//echo $mem_coupon_sql; exit;
	$mem_coupon_result = mysqli_query($gconnet,$mem_coupon_sql);
	$mem_coupon_row = mysqli_fetch_array($mem_coupon_result);
	$zip_code1 = $mem_coupon_row[post];
	$addr1 = $mem_coupon_row[addr1];
	$addr2 = $mem_coupon_row[addr2];
	$cell = $mem_coupon_row[cell];
	$tel = $mem_coupon_row[tel];
	$user_name = $mem_coupon_row[user_name];

	$member_cell_arr = explode("-",$cell);
?>
	<script type="text/javascript">
	<!--
		opener.document.frm.receive_name.value = "<?=$user_name?>";
		opener.document.frm.delivery_cellphone1.value = "<?=$member_cell_arr[0]?>";
		opener.document.frm.delivery_cellphone2.value = "<?=$member_cell_arr[1]?>";
		opener.document.frm.delivery_cellphone3.value = "<?=$member_cell_arr[2]?>";
		opener.document.frm.delivery_telephone.value = "<?=$tel?>";
		opener.document.frm.zip_code1.value = "<?=$zip_code1?>";
		opener.document.frm.member_address.value = "<?=$addr1?>";
		opener.document.frm.member_address2.value = "<?=$addr2?>";
		self.close();
	-->
	</script>