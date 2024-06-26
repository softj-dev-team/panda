<?
include "../../pro_inc/include_default.php"; // 공통함수 인클루드 
include "../include/admin_header.php"; // 관리자페이지 헤더
//check_admin_frame(); // 관리자 로그인여부 확인

if($session_admin_idx && strpos($_SERVER["HTTP_REFERER"], "/html/mainnet/master/payment/") !== false ){
	$is_admin = true;
}else{
	$is_admin = false;
}

$order_num = trim(sqlfilter($_REQUEST['order_num']));
$total_param = trim(sqlfilter($_REQUEST['total_param']));
$cancel_memo = trim(sqlfilter($_REQUEST['cancel_memo']));
$cancel_sect_1 = trim(sqlfilter($_REQUEST['cancel_sect_1']));

if(!$order_num){
	error_frame("주문번호가 없습니다.");
}

//트랜잭션시작
if(!turnoff_autocommit()){
	//error_frame("취소가 실패했습니다.");
}

$sql_pre = "select * from ".NS."order_member where order_num='".$order_num."' and orderstat in ('com','pre')";
$query_pre = mysqli_query($GLOBALS['gconnet'],$sql_pre);
if(!$row_pre = mysqli_fetch_array($query_pre)){
	error_frame("변경할 결제내역이 없습니다.");
}

$member_idx = $row_pre[member_idx];
$pay_method = $row_pre[pay_sect_1];
if($_SERVER['REMOTE_ADDR'] == "121.167.147.150" || $_SERVER['REMOTE_ADDR'] == "59.9.37.47"){
	//$pay_total = "1100";
	$pay_total = $row_pre[price_total];
} else {
	$pay_total = $row_pre[price_total];
}
$ApprNo = $row_pre[ApprNo];
$ApprTm = $row_pre[ApprTm];
$DealNo = $row_pre[DealNo];
$ES_SENDNO = $row_pre[ES_SENDNO];
$escrow = $row_pre["escrow"];
$origin_order_status = $row_pre[orderstat];
$pay_type = $row_pre["pay_type"];
$use_apv_no = $row_pre["use_apv_no"];
$save_apv_no = $row_pre["save_apv_no"];
$user_id = $row_pre["user_id"];
$save_point = $row_pre["save_point"];
$use_point = $row_pre["pay_refund"];

if(!$session_admin_idx){
	if($user_id){
		if($user_id != $session_member_id){
			error_frame("권한이 없습니다.");
		}
	}else{
		if($order_num != $session_nonmember_order_num){
			error_frame("권한이 없습니다.");
		}
	}
}

//배송상태 확인
$delv_stat_str = get_delivery_status($row_pre["delvstat"]);
if($row_pre['delvstat']){
	error_frame("배송상태가 ".$delv_stat_str." 이라 취소하실수 없습니다.");
}

if(!increase_cancelled_product_stock($order_num)){
	error_frame("재고 반영이 되지 않아 취소가 실패했습니다.");
}

if($origin_order_status == "pre"){ // 무통장 입금 결제대기 상태
	$order_result = "can1";
} elseif($origin_order_status == "com"){ // 결제완료 상태
	$order_result = "can";
}

if($is_admin){
	$cancel_sect = "A"; // 주문취소 신청자 구분
	$cancel_admin = addslashes($session_admin_id);
}else{
	$cancel_sect = "C";
	$cancel_admin = "";
}

$query = " update ".NS."order_member set "; 
$query .= " orderstat = '".$order_result."', ";
$query .= " cancel_memo = '".$cancel_memo."', ";
$query .= " cancel_sect_1 = '".$cancel_sect_1."', ";
$query .= " cancel_sect = '".$cancel_sect."', ";
$query .= " cancel_admin = '".$cancel_admin."', ";
$query .= " cancel_date = now() ";
$query .= " where order_num = '".$order_num."' ";
if(!$result = mysqli_query($GLOBALS['gconnet'],$query)){
	error_frame("취소가 실패했습니다.");
}

$query_pro = " update ".NS."order_product set "; 
$query_pro .= " orderstat = '".$order_result."' ";
$query_pro .= " where order_num = '".$order_num."' ";
if(!$result_pro = mysqli_query($GLOBALS['gconnet'],$query_pro)){
	error_frame("취소가 실패했습니다.");
}

$cancelFlag = false;

if($user_id){
	if($save_apv_no){
		$save_canceled_res = meta_cancel_saved_point(get_cancel_saved_data($user_id, $order_num, $save_apv_no));
		if($save_canceled_res["ERROR_CD"] != "E0000"){
			$cancelFlag = true;
			$message = "적립된 포인트 취소가 실패했습니다. \\n".$save_canceled_res["ERROR_MSG"];
		}
	}

	if(!$cancelFlag && $use_apv_no){
		$use_canceled_res = meta_cancel_used_point(get_cancel_used_data($user_id, $order_num, $use_apv_no));
		if($use_canceled_res["ERROR_CD"] != "E0000"){
			$cancelFlag = true;
			$message = "사용된 포인트 취소가 실패했습니다. \\n".$use_canceled_res["ERROR_MSG"];
		}
	}
}

//커밋
if(!$cancelFlag){
	if(!mysqli_query($GLOBALS['gconnet'],"COMMIT")){
		$cancelFlag = true;
		$message = "취소가 실패했습니다.";
	}
}

if($cancelFlag){
	error_frame($message);
}

if($origin_order_status == "com" && $pay_method == "card_isp" && $pay_type != "onyref"){
?>
	<form id="can_frm" name="can_frm" method="post" action="/payd/BillCancel.php" target="pay_cancel" accept-charset = "euc-kr">
		<input type="hidden" name="TID" value="<?=$DealNo?>" />
		<input type="hidden" name="AMOUNT" value="<?=$pay_total?>" />
		<input type="hidden" name="CANCELTYPE" value="C" />
		<input type="hidden" name="CANCELDESC" value="<?=$cancel_sect_1?>" />
	</form>
	<script language=javascript>
		function go_cancel() {
			if (can_frm.canHaveHTML) { // detect IE
				document.charset = can_frm.acceptCharset;
			}
			can_frm.submit();
		}
	</script>
	<body onload="go_cancel();">
	<iframe name="pay_cancel" width="600" height="400"></iframe>
<?
	/*if($inipay->getResult('ResultCode') != "00"){
		if($is_admin){
			error_frame(iconv("EUC-KR", "UTF-8", $inipay->getResult('ResultMsg'))."\\n주문은 취소되었지만 결제모듈 결제취소가 실패했습니다. \\n상점관리자에서 직접 결제취소하시기 바랍니다.");
		}else{
			error_frame(iconv("EUC-KR", "UTF-8", $inipay->getResult('ResultMsg'))."\\n주문은 취소되었지만 결제모듈 결제취소가 실패했습니다. \\n관리자에게 문의바랍니다.");
		}
	}*/
}

exit;

if($is_admin){
?>
<script type="text/javascript">
	alert("결제취소로 상태변경이 정상적으로 처리 되었습니다.");
	//window.parent.document.location.replace("order_list.php?v_sect=<?=$order_result?>");
	parent.location.href =  "order_list.php?<?=$total_param?>";
</script>
<?
}else{
?>
<script type="text/javascript">
	alert("결제취소로 상태변경이 정상적으로 처리 되었습니다.");
	//window.parent.document.location.reload();
	parent.location.href =  "order_list.php?<?=$total_param?>";
</script>
<?
}
?>