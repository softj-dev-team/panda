<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
$member_idx = $_SESSION['member_coinc_idx'];
$my_member_row = get_member_data($_SESSION['member_coinc_idx']);
extract($_REQUEST);

$query = "select * from member_info_sendinfo where member_idx = $member_idx";
//echo $query;
$result = mysqli_query($gconnet, $query);
$result_info = mysqli_fetch_assoc($result);
$result_code = array();

$call_num = json_decode($result_info["call_num"], true);
$call_memo = json_decode($result_info["call_memo"], true);
$use_yn = json_decode($result_info["use_yn"], true);
$auth_method = json_decode($result_info["auth_method"], true);

if ($my_member_row["cert_ci"] == $user_ci) {
    if (in_array($phone_no, $call_num)) {
        // 전화번호 중복
        $result_code['result_code'] = "8888";
    } else {
        array_push($call_num, $phone_no);
        array_push($call_memo, "");
        array_push($use_yn, "N");
        array_push($auth_method, "kcp");

        $call_num_encode = json_encode($call_num);
        $call_memo_encode = json_encode($call_memo);
        $use_yn_encode = json_encode($use_yn);
        $auth_method_encode = json_encode($auth_method);

        $query = "UPDATE member_info_sendinfo SET call_num='$call_num_encode', call_memo='$call_memo_encode', use_yn='$use_yn_encode', auth_method='$auth_method_encode' WHERE member_idx=$member_idx";
        $result = mysqli_query($gconnet, $query);

        //성공 처리
        $result_code['result_code'] = "9999";
    }
} else {
    // 다른 사람 명의인 경우
    $result_code['result_code'] = "7777";
}

echo json_encode($result_code);
