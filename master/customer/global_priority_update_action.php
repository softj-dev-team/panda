<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더
include $_SERVER["DOCUMENT_ROOT"] . "/master/include/check_login.php"; // 관리자 로그인여부 확인

$total_param = trim(sqlfilter($_REQUEST['total_param']));
$reurl = $_REQUEST['reurl'];
$tb_name = trim(sqlfilter($_REQUEST['target_storage']));
$target_idx = trim(sqlfilter($_REQUEST['target_idx']));
$method_type = trim(sqlfilter($_REQUEST['method_type']));
$method_type = strtolower($method_type);

$query = " SELECT priority FROM $tb_name WHERE idx='$target_idx' LIMIT 1 ";
$result = mysqli_query($gconnet, $query);
if(mysqli_num_rows($result) == 0){
	error_back("카테고리 데이터를 찾을 수 없습니다.");
	exit;
}

$priority = mysqli_fetch_array($result)['priority'];

$orderParams = " idx != $target_idx ";

switch($method_type){
	case 'up':
		$orderParams .= " AND priority < $priority ORDER BY priority DESC LIMIT 1 ";
		break;
	case 'down':
		$orderParams .= " AND priority > $priority ORDER BY priority ASC LIMIT 1 ";
		break;
	default:
		error_back("메서드를 식별할 수 없습니다.");
		break;
}

$query = " SELECT idx, priority FROM $tb_name WHERE " . $orderParams;
$result = mysqli_query($gconnet, $query);
if(mysqli_num_rows($result) == 0){
	error_back("해당 카테고리는 우선순위를 교체할 수 없습니다.");
	exit;
}

$row = mysqli_fetch_array($result);

$result = mysqli_multi_query($gconnet, implode('', [
	" UPDATE $tb_name SET priority = '$priority' WHERE idx = '" . $row['idx'] . "';",
	" UPDATE $tb_name SET priority = '" . $row['priority'] . "' WHERE idx = '$target_idx';"
]));

if($result){
	error_go("우선순위를 성공적으로 변경했습니다.", $reurl);
}else{
	//error_back("우선순위 변경 도중에 알 수 없는 오류가 발생했습니다.");
	error_go("오류가 발생했습니다.", $reurl);
}