<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드 
?>
<?
$member_idx = $_SESSION['member_coinc_idx'];

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$total_param = trim(sqlfilter($_REQUEST['total_param']));

$send_idx = $_REQUEST['send_idx'];

for ($k = 0; $k < sizeof($send_idx); $k++) {

	$query = "update sms_save set";
	$query .= " is_del = 'Y' ";
	$query .= " where 1 and member_idx = '" . $member_idx . "' and idx = '" . $send_idx[$k] . "' ";
	$result =  mysqli_query($gconnet, $query);

	$query_cnt = "SELECT * FROM sms_save_cell WHERE save_idx = '" . $send_idx[$k] . "'";
	$result_cnt = mysqli_query($gconnet, $query_cnt);

	while ($row_cnt = mysqli_fetch_array($result_cnt)) {
		if ($row_cnt['module_type'] == "LG") {
			$query = "DELETE FROM TBL_SEND_TRAN WHERE fetc1 = '" . $row_cnt['idx'] . "'";
			$result =  mysqli_query($gconnet, $query);
		} else if ($row_cnt['module_type'] == "JUD1") {
			$query = "DELETE FROM SMS_MAIN_AGENT_JUD1 WHERE S_ETC1 = '" . $row_cnt['idx'] . "'";
			$result =  mysqli_query($gconnet, $query);
		} else if ($row_cnt['module_type'] == "JUD2") {
			$query = "DELETE FROM SMS_MAIN_AGENT_JUD2 WHERE S_ETC1 = '" . $row_cnt['idx'] . "'";
			$result =  mysqli_query($gconnet, $query);
		}
	}
}

error_frame_reload("삭제 되었습니다.");

?>