<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드 
?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$total_param = trim(sqlfilter($_REQUEST['total_param']));

$send_idx = $_REQUEST['send_idx'];

for ($k = 0; $k < sizeof($send_idx); $k++) {
	$query = "delete from spam_list ";
	$query .= " where idx = " . $send_idx[$k];
	$result =  mysqli_query($gconnet, $query);
}

error_frame_reload("삭제 되었습니다.");

?>