<?
include "../../pro_inc/include_default.php"; // 공통함수 인클루드 
include "../include/admin_header.php"; // 관리자페이지 헤더
//check_admin_frame(); // 관리자 로그인여부 확인

$order_num = trim(sqlfilter($_REQUEST['order_num']));
$total_param = trim(sqlfilter($_REQUEST['total_param']));

	$send_name = trim(sqlfilter($_REQUEST['send_name']));
	$send_zipcode =  trim(sqlfilter($_REQUEST['send_zipcode']));
	$send_addr1 =  trim(sqlfilter($_REQUEST['send_addr1']));
	$send_addr2 =  trim(sqlfilter($_REQUEST['send_addr2']));
	$send_tel1 = trim(sqlfilter($_REQUEST['send_tel1']));
	$send_tel2 = trim(sqlfilter($_REQUEST['send_tel2']));
	$send_tel3 = trim(sqlfilter($_REQUEST['send_tel3']));
	$send_tel = $send_tel1."-".$send_tel2."-".$send_tel3;
	$send_cell1 = trim(sqlfilter($_REQUEST['send_cell1']));
	$send_cell2 = trim(sqlfilter($_REQUEST['send_cell2']));
	$send_cell3 = trim(sqlfilter($_REQUEST['send_cell3']));
	$send_cell = $send_cell1."-".$send_cell2."-".$send_cell3;
	$order_memo = trim(sqlfilter($_REQUEST['order_memo']));
	
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

if(is_delv_now($order_num) == "Y"){
	error_frame("이미 배송중인 상품이 있어 배송지를 수정할 수 없습니다.");
}

		$query = "update ".NS."order_member set 
				send_name = '".$send_name."', 
				send_zipcode = '".$send_zipcode."', 
				send_addr1 = '".$send_addr1."', 
				send_addr2 = '".$send_addr2."', 
				send_tel = '".$send_tel."', 
				send_cell = '".$send_cell."', 
				send_email = '".$send_email."', 
				order_memo = '".$order_memo."' 
			where order_num = '".$order_num."'";
		
			if(!$result = mysqli_query($GLOBALS['gconnet'],$query)){
				error_frame("배송지 수정이 실패했습니다.");
			}

?>
<script type="text/javascript">
	alert("배송지 수정이 정상적으로 처리 되었습니다.");
	window.parent.document.location.reload();
</script>
