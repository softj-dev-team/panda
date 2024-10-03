<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드

$member_idx = $_SESSION['member_coinc_idx'];
$group_idx = $_REQUEST['group_idx'];
$keyword = $_REQUEST['keyword'];

// group_idx가 여러 개일 수 있으므로 배열로 처리
$group_idx_arr = explode(",", $group_idx);
$group_idx_placeholders = implode(",", array_fill(0, count($group_idx_arr), '?')); // Prepared statement용

// 기본 쿼리 - prepared statement 사용
$query = "SELECT * FROM address_group_num WHERE member_idx = ? AND group_idx IN ($group_idx_placeholders)";

// 키워드가 있으면 receive_name 또는 receive_num에 대해 LIKE 조건 추가
if (!empty($keyword)) {
    $keyword = '%' . mysqli_real_escape_string($gconnet, $keyword) . '%'; // SQL 인젝션 방지
    $query .= " AND (receive_name LIKE ? OR receive_num LIKE ?)";
}

// 정렬 조건 추가
$query .= " ORDER BY idx DESC";

// Prepared statement 준비
$stmt = mysqli_prepare($gconnet, $query);

// 바인딩할 변수들을 준비
$bind_params = array_merge([$member_idx], $group_idx_arr);
if (!empty($keyword)) {
    $bind_params[] = $keyword;
    $bind_params[] = $keyword;
}

// prepared statement에 바인딩
mysqli_stmt_bind_param($stmt, str_repeat('s', count($bind_params)), ...$bind_params);

// 쿼리 실행
mysqli_stmt_execute($stmt);

// 결과 가져오기
$result = mysqli_stmt_get_result($stmt);

$rows = array();
while ($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}
echo json_encode($rows);

mysqli_stmt_close($stmt);
?>
