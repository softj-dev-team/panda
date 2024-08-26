<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드 
?>
<?
$member_idx = $_SESSION['member_coinc_idx'];
$list = $_REQUEST['list'];
$group_idx = $_REQUEST['group_idx'];

for ($i = 0; $i < sizeof($list); $i++) {
	$list[$i] = str_replace("-", "", $list[$i]);
}

$list = array_unique($list);

for ($i = 0; $i < sizeof($list); $i++) {

	$query_cnt = "select count(*) as cnt from address_group_num 
	where 1 and member_idx = '" . $member_idx . "' and receive_num = '" . $list[$i] . "' and group_idx='" . $group_idx . "'";
	$result_cnt = mysqli_query($gconnet, $query_cnt);

	$row_cnt = mysqli_fetch_array($result_cnt);

	if ($row_cnt['cnt'] > 0) {
		continue;
	}
	$query = "insert address_group_num set";
	$query .= " group_idx = '" . $group_idx . "', ";
	$query .= " member_idx = '" . $member_idx . "', ";
	$query .= " receive_num = '" . $list[$i] . "', ";
	$query .= " wdate = now() ";

	//echo $query;
	$result = mysqli_query($gconnet, $query);
}
//error_frame_reload("연락처가 추가 되었습니다.");
?>