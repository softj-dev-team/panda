<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/check_login_frame.php"; // 관리자 로그인여부 확인
?>
<?
$order_num = trim(sqlfilter($_REQUEST['order_num']));

$orderstat = "com";

$query_order = "update order_member set";
$query_order .= " orderstat = '" . $orderstat . "', ";
$query_order .= " payment_date = now() ";
$query_order .= " where 1 and order_num='" . $order_num . "' and is_del='N'";
$result_order = mysqli_query($gconnet, $query_order);

$sql_pre_order = "select * from member_point where 1 and order_num='" . $order_num . "' and mile_sect='P' and point_sect='smspay'";
$result_pre_order = mysqli_query($gconnet, $sql_pre_order);
$row_pre_order = mysqli_fetch_array($result_pre_order);

if ($row_pre_order['member_idx']) {
	$point_sect = "smspay"; // sms 충전 
	$mile_title = $row_pre_order['mile_title']; // 포인트  적립 내역
	$mile_sect = "A"; // 포인트  종류 = A : 적립, P : 대기, M : 차감
	$contents_idx = coin_plus_minus($point_sect, $row_pre_order['member_idx'], $mile_sect, $row_pre_order['chg_mile'], $mile_title, $order_num, $row_pre_order['pay_price'], "", "", "", "");
}

error_frame_reload("승인으로 변경 되었습니다.");
?>