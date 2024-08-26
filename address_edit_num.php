<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드 
?>
<?
$idx = $_REQUEST['idx'];
$num = $_REQUEST['num'];


$list = array_unique($list);

$query_cnt = "select count(*) as cnt from address_group_num where receive_num = '" . $num . "'";
$result_cnt = mysqli_query($gconnet, $query_cnt);

$row_cnt = mysqli_fetch_array($result_cnt);

if ($row_cnt['cnt'] > 0) {
	$result_['result'] = "duplicate";
	echo json_encode($result_);
} else {
	$query = "update address_group_num set";
	$query .= " receive_num = '" . $num . "', ";
	$query .= " mdate = now() ";
	$query .= " where idx = '" . $idx . "'";
	//echo $query;
	$result = mysqli_query($gconnet, $query);
	$result_['result'] = "success";
	echo json_encode($result_);
}



?>