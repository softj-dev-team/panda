<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
$member_idx = $_SESSION['member_coinc_idx'];
$my_member_row = get_member_data($_SESSION['member_coinc_idx']);
extract($_REQUEST);

$query = "select * from member_info_sendinfo where member_idx = $member_idx";
//echo $query;
$result = mysqli_query($gconnet, $query);
$result_info = mysqli_fetch_assoc($result);

$call_memo = json_decode($result_info["call_memo"], true);

$idx = (int)$memo_idx;
$call_memo[$idx] = $memo_val;

$call_memo_encode = json_encode($call_memo);


$query = "UPDATE member_info_sendinfo SET call_memo='$call_memo_encode' WHERE member_idx=$member_idx";
$result = mysqli_query($gconnet, $query);

$result_code = array();
$result_code['result_code'] = "9999";

echo json_encode($result_code);
