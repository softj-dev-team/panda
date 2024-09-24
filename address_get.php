<?php include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드
?>
<?php include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드
?>
<?php
$member_idx = $_SESSION['member_coinc_idx'];
$group_idx = $_REQUEST['group_idx'];
$keyword = $_REQUEST['keyword'];
// 기본 쿼리
$query = "SELECT * FROM address_group_num WHERE member_idx ='" . $member_idx . "' AND group_idx IN (" . $group_idx . ")";

// 키워드가 있으면 receive_name 또는 receive_num에 대해 LIKE 조건 추가
if (!empty($keyword)) {
    $keyword = mysqli_real_escape_string($gconnet, $keyword); // SQL 인젝션 방지
    $query .= " AND (receive_name LIKE '%" . $keyword . "%' OR receive_num LIKE '%" . $keyword . "%')";
}

// 정렬 조건 추가
$query .= " ORDER BY idx DESC";

// 쿼리 실행
$result = mysqli_query($gconnet, $query);

// 결과 가져오기
$rows = array();
while ($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}
echo json_encode($rows);

?>