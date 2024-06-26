<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드 
?>
<?
error_reporting(E_ALL);
ini_set("display_errors", 1);
$member_idx = $_SESSION['member_coinc_idx'];
$list = $_REQUEST['list'];
$group_idx = $_REQUEST['group_idx'];

$list = json_decode($list, true);

for ($i = 0; $i < sizeof($list); $i++) {
	$list[$i]["number"] = str_replace("-", "", $list[$i]["number"]);
}

for ($i = 0; $i < sizeof($list); $i++) {

	$query_cnt = "select count(*) as cnt from address_group_num 
	where 1 and member_idx = '" . $member_idx . "' and receive_num = '" . $list[$i]["number"] . "' and group_idx=" . $group_idx . "";
	$result_cnt = mysqli_query($gconnet, $query_cnt);

	$row_cnt = mysqli_fetch_array($result_cnt);

	if ($row_cnt['cnt'] > 0) {
		continue;
	}
	$query = "insert address_group_num set";
	$query .= " group_idx = '" . $group_idx . "', ";
	$query .= " member_idx = '" . $member_idx . "', ";
	$query .= " receive_num = '" . $list[$i]["number"] . "', ";
	$query .= " receive_name = '" . $list[$i]["name"] . "', ";
	$query .= " wdate = now() ";

	echo $query;
	$result = mysqli_query($gconnet, $query);
	var_dump($result);
}

//echo "연락처가 추가 되었습니다.";
//error_frame_reload("연락처가 추가 되었습니다.");*/
?>