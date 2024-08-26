<?
include "../../pro_inc/include_default.php"; // 공통함수 인클루드 
include "../include/admin_header.php"; // 관리자페이지 헤더
check_admin_frame(); // 관리자 로그인여부 확인

$order_num = trim(sqlfilter($_REQUEST['order_num']));
$total_param = trim(sqlfilter($_REQUEST['total_param']));
$mile_sect = trim(sqlfilter($_REQUEST["mile_sect"]));
$refund_ok_cnt = trim(sqlfilter($_REQUEST['refund_ok_cnt']));
$refund_com_point = trim(sqlfilter($_REQUEST['refund_com_point']));
$head_return_memo = trim(sqlfilter($_REQUEST['head_return_memo']));
$repay_name = trim(sqlfilter($_REQUEST['repay_name']));

if(!$order_num){
	error_frame("주문번호가 없습니다.");
}

$sql_pre = "select * from ".NS."order_member where order_num='".$order_num."' and orderstat in ('pre', 'com', 'reing') ";
$query_pre = mysqli_query($GLOBALS['gconnet'],$sql_pre);
if(!$row_pre = mysqli_fetch_array($query_pre)){
	error_frame("반품처리할 결제내역이 없습니다.");
}

if(!increase_cancelled_product_stock($order_num)){
	error_frame("반품처리가 실패했습니다.");
}

$member_idx = $row_pre[member_idx];
$pay_method = $row_pre[pay_sect_1];
$pay_total = $row_pre[price_total];
$ApprNo = $row_pre[ApprNo];
$ApprTm = $row_pre[ApprTm];
$DealNo = $row_pre[DealNo];
$ES_SENDNO = $row_pre[ES_SENDNO];
$origin_order_status = $row_pre[orderstat];
$pay_type = $row_pre["pay_type"];
$use_apv_no = $row_pre["use_apv_no"];
$save_apv_no = $row_pre["save_apv_no"];
$user_id = $row_pre["user_id"];

$query = " update ".NS."order_member set "; 
$query .= " orderstat = 'recom', ";
$query .= " head_return_memo = '".$head_return_memo."', ";
$query .= " repay_done_date = now(), ";
$query .= " repay_name = '".$repay_name."' ";
$query .= " where order_num = '".$order_num."' ";
if(!$result = mysqli_query($GLOBALS['gconnet'],$query)){
	error_frame("반품처리가 실패했습니다.");
}

$cancelFlag = false;

if($user_id){
	if($save_apv_no){
		$save_canceled_res = meta_cancel_saved_point(get_cancel_saved_data($user_id, $order_num, $save_apv_no));
		if($save_canceled_res["ERROR_CD"] != "E0000"){
			$cancelFlag = true;
			$message = "적립된 포인트 취소가 실패했습니다.";
		}
	}

	if(!$cancelFlag && $use_apv_no){
		$use_canceled_res = meta_cancel_used_point(get_cancel_used_data($user_id, $order_num, $use_apv_no));
		if($use_canceled_res["ERROR_CD"] != "E0000"){
			$cancelFlag = true;
			$message = "사용된 포인트 취소가 실패했습니다.";
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
?>

<script type="text/javascript">
	alert("반품처리가 정상적으로 완료 되었습니다.");
	window.parent.document.location.replace("order_list.php?v_sect=recom");
</script>