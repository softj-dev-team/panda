<?php

include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php";

$target_idx = trim(sqlfilter($_REQUEST['target_idx']));
$keyword = trim(sqlfilter($_REQUEST['keyword']));

$query = " UPDATE board_category SET ";
$query .= " subject = '$keyword' ";
$query .= " WHERE idx='$target_idx' ";
$result = mysqli_query($gconnet, $query);

if($result){
	echo json_encode(['code' => 1, 'message' => '성공적으로 카테고리를 수정했습니다.']);
}else{
	echo json_encode(['code' => -1, 'message' => '오류가 발생했습니다.']);
}