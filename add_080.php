<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<?
$T_ID = $_REQUEST['T_ID'];
$T_TIME = $_REQUEST['T_TIME'];
$MENU_NAME = $_REQUEST['MENU_NAME'];
$ANI = $_REQUEST['ANI'];
$DTMF_CNT = $_REQUEST['DTMF_CNT'];
$DTMF_1 = $_REQUEST['DTMF_1'];

$query_cnt = "select count(*) as cnt, idx from spam_080_group 
where 1 and group_num = '" . $MENU_NAME	. "'";
$result_cnt = mysqli_query($gconnet, $query_cnt);

$row_cnt = mysqli_fetch_array($result_cnt);

if ($row_cnt["cnt"] == "0") {
	$query = "insert spam_080_group set";
	$query .= " group_num = '" . $MENU_NAME . "', ";
	$query .= " wdate = now() ";
	$result = mysqli_query($gconnet, $query);
	$group_idx = mysqli_insert_id($gconnet);
} else {
	$group_idx = $row_cnt['idx'];
}


if ($DTMF_CNT == "0") {
	$query_cnt = "select count(*) as cnt from spam_080 
	where 1 and group_idx = " . $group_idx . " and cell_num = '" . $ANI . "' ";
	$result_cnt = mysqli_query($gconnet, $query_cnt);

	$row_cnt = mysqli_fetch_array($result_cnt);

	if ($row_cnt["cnt"] == 0) {
		$query = "insert spam_080 set";
		$query .= " group_idx = " . $group_idx . ", ";
		$query .= " cell_num = '" . $ANI . "', ";
		$query .= " T_ID = '" . $T_ID . "', ";
		$query .= " T_TIME = '" . date("Y-m-d H:i:s", strtotime($T_TIME)) . "', ";
		$query .= " MENU_NAME = '" . $MENU_NAME . "', ";
		$query .= " ANI = '" . $ANI . "', ";
		$query .= " DTMF_CNT = '" . $DTMF_CNT . "', ";
		$query .= " wdate = now() ";
		$result = mysqli_query($gconnet, $query);
	}
} else {
	$query_cnt = "select count(*) as cnt from spam_080 
	where 1 and group_idx = '" . $group_idx . "' and cell_num = '" . $DTMF_1 . "' ";
	$result_cnt = mysqli_query($gconnet, $query_cnt);

	$row_cnt = mysqli_fetch_array($result_cnt);

	if ($row_cnt["cnt"] == 0) {
		$query = "insert spam_080 set";
		$query .= " group_idx = " . $group_idx . ", ";
		$query .= " cell_num = '" . $DTMF_1 . "', ";
		$query .= " T_ID = '" . $T_ID . "', ";
		$query .= " T_TIME = '" . date("Y-m-d H:i:s", strtotime($T_TIME)) . "', ";
		$query .= " MENU_NAME = '" . $MENU_NAME . "', ";
		$query .= " ANI = '" . $ANI . "', ";
		$query .= " DTMF_CNT = '" . $DTMF_CNT . "', ";
		$query .= " DTMF_1 = '" . $DTMF_1 . "', ";
		$query .= " wdate = now() ";
		$result = mysqli_query($gconnet, $query);
	}
}

echo "<html>
<body>
<form name='frm' method='post'>
 <input type='hidden' name='T_ID' value='" . $T_ID . "'>
 <input type='hidden' name='T_TIME' value='" . date("YmdHis") . "'>
 <input type='hidden' name='RESULT' value='0'>
 <input type='hidden' name='MENU_NAME' value='" . $MENU_NAME . "'>
 <input type='hidden' name='ACTION_TYPE' value='3'>
 <input type='hidden' name='NEXT_MENU' value=''>
 <input type='hidden' name='MENT_FLAG' value='0'>
 <input type='hidden' name='MENT_CNT' value='1'>
 <input type='hidden' name='MENT_1' value='F_succ'>
</form>
</body>
</html>";


?>