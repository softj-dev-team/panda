<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드

$member_idx = $_SESSION['member_coinc_idx'];
$list = json_decode($_REQUEST['list'], true);
$group_idx = $_REQUEST['group_idx'];

// '-' 제거된 전화번호 배열 생성
$phone_numbers = array_map(function($item) {
    return str_replace("-", "", $item["HP"]);
}, $list);

// 중복된 번호를 한 번에 체크
$phone_placeholders = implode(",", array_fill(0, count($phone_numbers), '?'));  // Prepared Statement에 사용할 플레이스홀더
$query_cnt = "SELECT receive_num FROM address_group_num 
              WHERE member_idx = ? AND group_idx = ? AND receive_num IN ($phone_placeholders)";
$stmt_cnt = mysqli_prepare($gconnet, $query_cnt);

// prepared statement 바인딩
$bind_values = array_merge([$member_idx, $group_idx], $phone_numbers);
mysqli_stmt_bind_param($stmt_cnt, str_repeat('s', count($bind_values)), ...$bind_values);
mysqli_stmt_execute($stmt_cnt);
$result_cnt = mysqli_stmt_get_result($stmt_cnt);

// 중복된 연락처 수집
$existing_phones = [];
while ($row_cnt = mysqli_fetch_assoc($result_cnt)) {
    $existing_phones[] = $row_cnt['receive_num'];
}
mysqli_stmt_close($stmt_cnt);

// 중복되지 않은 연락처만 필터링
$insert_values = [];
foreach ($list as $item) {
    $hp = str_replace("-", "", $item["HP"]);
    if (!in_array($hp, $existing_phones)) {
        $insert_values[] = "('$group_idx', '$member_idx', '$hp', '" . $item['NAME'] . "', NOW())";
    }
}

// 중복되지 않은 데이터만 배치 인서트
if (count($insert_values) > 0) {
    $query = "INSERT INTO address_group_num (group_idx, member_idx, receive_num, receive_name, wdate) VALUES ";
    $query .= implode(", ", $insert_values);
    mysqli_query($gconnet, $query);
}

?>
