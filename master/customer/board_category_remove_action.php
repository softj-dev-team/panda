<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더
include $_SERVER["DOCUMENT_ROOT"] . "/master/include/check_login.php"; // 관리자 로그인여부 확인

$total_param = trim(sqlfilter($_REQUEST['total_param']));
$target_idx = trim(sqlfilter($_REQUEST['target_idx']));

$pressedQueries = "update board_category set is_del='Y' WHERE idx = '$target_idx'";
$result = mysqli_query($gconnet, $pressedQueries);
if($result){
	error_go("카테고리를 성공적으로 삭제했습니다.", "board_category_list.php?".$total_param);
}else{
	error_go("오류가 발생했습니다.", "board_category_list.php?".$total_param);
}