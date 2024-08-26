<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드 
?>
<?
$member_idx = $_SESSION['member_coinc_idx'];
$total_param = trim(sqlfilter($_REQUEST['total_param']));
$adr_idx = $_REQUEST['adr_idx'];

for ($k = 0; $k < sizeof($adr_idx); $k++) {

	$query = "delete from address_group_num ";
	$query .= " where 1 and member_idx = '" . $member_idx . "' and idx = '" . $adr_idx[$k] . "' ";
	$result =  mysqli_query($gconnet, $query);
}

error_frame_go("삭제 되었습니다.", "adress_list.php?" . $total_param);


?>