<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
$member_idx = $_SESSION['member_coinc_idx'];
$my_member_row = get_member_data($_SESSION['member_coinc_idx']);
extract($_REQUEST);

$query = "select * from member_info_sendinfo where member_idx = $member_idx";
//echo $query;
$result = mysqli_query($gconnet, $query);
$result_info = mysqli_fetch_assoc($result);


$call_num = json_decode($result_info["call_num"], true);
$call_memo = json_decode($result_info["call_memo"], true);
$use_yn = json_decode($result_info["use_yn"], true);
$auth_method = json_decode($result_info["auth_method"], true);

//var_dump($checked_array);

for ($i = 0; $i < sizeof($checked_array); $i++) {
    unset($call_num[$checked_array[$i]]);
    unset($call_memo[$checked_array[$i]]);
    unset($use_yn[$checked_array[$i]]);
    unset($auth_method[$checked_array[$i]]);
}

$call_num = array_values($call_num);
$call_memo = array_values($call_memo);
$use_yn = array_values($use_yn);
$auth_method = array_values($auth_method);

$call_num_encode = json_encode($call_num);
$call_memo_encode = json_encode($call_memo);
$use_yn_encode = json_encode($use_yn);
$auth_method_encode = json_encode($auth_method);


$query = "UPDATE member_info_sendinfo SET call_num='$call_num_encode', call_memo='$call_memo_encode', use_yn='$use_yn_encode', auth_method='$auth_method_encode' WHERE member_idx=$member_idx";
$result = mysqli_query($gconnet, $query);

$result_code = array();
$result_code['result_code'] = "9999";




echo json_encode($result_code);
