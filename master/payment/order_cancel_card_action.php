<?
include "../../pro_inc/include_default.php"; // 공통함수 인클루드 
include "../include/admin_header.php"; // 관리자페이지 헤더
check_admin_frame(); // 관리자 로그인여부 확인

$order_num = trim(sqlfilter($_REQUEST['order_num']));
$total_param = trim(sqlfilter($_REQUEST['total_param']));
$order_result = trim(sqlfilter($_REQUEST['order_result']));
$cancel_memo = trim(sqlfilter($_REQUEST['cancel_memo']));
$cancel_sect_1 = trim(sqlfilter($_REQUEST['cancel_sect_1']));

if(!$order_num){
	error_frame("주문번호가 없습니다.");
	exit;
}
/*
if(!$cancel_memo){
	error_frame("주문취소 사유를 입력해 주세요.");
	exit;
}
*/
$where .= " and order_num='".$order_num."' "; 

$sql = "select * from ".NS."order_member where 1=1 ".$where;
$query = mysqli_query($GLOBALS['gconnet'],$sql);

if(mysqli_num_rows($query) == 0){
	error_frame("취소할 결제내역이 없습니다.");
	exit;
}

$row = mysqli_fetch_array($query);

	$delv_stat_str = get_delivery_status($row["delvstat"]);

	if(Trim($row['delvstat']) == "d_ing" || Trim($row['delvstat']) == "d_com" || Trim($row['delvstat']) == "d_conf"){
		error_frame("배송상태가 ".$delv_stat_str." 이라 취소하실수 없습니다.");
		exit;
	}

	$cancel_sect = "A"; // 주문취소 신청자 구분
	$cancel_date = date("Y-m-d H:i:s");
	$cancel_admin = $session_admin_id;

	$query = " update ".NS."order_member set "; 
	$query .= " cancel_memo = '".$cancel_memo."', ";
	$query .= " cancel_sect_1 = '".$cancel_sect_1."', ";
	$query .= " cancel_admin = '".$cancel_admin."' ";
	$query .= " where order_num = '".$order_num."' ";
	$result = mysqli_query($GLOBALS['gconnet'],$query);

	$Smode = "3001"; // 결제수단을 카드로 고정
	$MxID = "130404800001"; // 카드결제 가맹점 ID 고정

?>

<SCRIPT src="https://pg.mcash.co.kr/dlp/js/npgIF.js"></SCRIPT> <!-- 필수 -->

<script type="text/javascript">
<!--
function reqPayment() {
	//setTemp(); // 예제 테스트를 위한 함수 (*_tmp->*)   
	var form = document.payform;
	PAY_REQUEST(form);
}
//-->
</script>

<body onload="reqPayment();" >

	<form method="post" name="payform" id="payform">
					
					<!-- 결제 결과의 REDIRPATH 페이지 전송을 위한 parameter 시작 (수정하지 말것) -->
					   <input type="hidden" name="ReplyCode" value="">
						<input type="hidden" name="ReplyMessage" value="">
						<input type="hidden" name="AppSmode" value="">
						<input type="hidden" name="AppCSmode" value="">
						<input type="hidden" name="CcCode" value="">
						<input type="hidden" name="AppInstallment" value="">
						<input type="hidden" name="TxNO" value="">
					<!-- 결제 결과의 REDIRPATH 페이지 전송을 위한 parameter 끝 -->

					<!-- 취소 요청 parameter를 다음과 같이 설정합니다. -->
    <input type="hidden" name="MxID" value="<?=$MxID?>"> <!-- 계약후 발급받은 서비스 ID 숫자 12자리 -->
    <input type="hidden" name="MxIssueNO" value="<?=$row['order_num']?>"> <!-- 거래 번호(결제 시 사용한 값, 취소 대상) -->
    <input type="hidden" name="MxIssueDate" value="<?=$row['ApprTm']?>"> <!-- 거래 일자(결제 시 사용한 값, 취소 대상) -->
    <input type="hidden" name="Amount" value="<?=$row['price_total']?>"> <!-- 거래 금액(결제 시 사용한 값, 취소 대상 or 부분 취소 금액) -->    

    <input type="hidden" name="Currency" value="KRW"> <!-- 화폐 구분(결제 시 사용한 값, 취소 대상) -->
    <input type="hidden" name="CcMode" value="11"> <!-- 거래 모드(신용카드-'00':데모,'11':실거래 | 기타거래-'10':실거래) -->
    <input type="hidden" name="Smode" value="0000"> <!-- 결제 수단 구분('0000' : 결제취소) -->
    <input type="hidden" name="CSmode" value="<?=$Smode?>"> <!-- 결제 구분 수단 코드(결제 시 사용한 값, 취소 대상) -->

     <input type="hidden" name="URL" value="gaborak.com"> <!-- 가맹점 서버 URL('http://' 제외, 예:'www.test.com') -->
    <input type="hidden" name="DBPATH" value="/kmpay_card/shop_cancel_admin_dbpath.php"> <!-- 결과 저장 파일 경로(예:'/mall/dbpath.jsp') -->
    <input type="hidden" name="REDIRPATH" value="/kmpay_card/shop_cancel_admin_redirpath.php"> <!-- 결과 화면 파일 경로(예:'/mall/redirpath.jsp') -->
    <input type="hidden" name="connectionType" value="http"> <!-- 가맹점 서버 프로토콜(http, https) -->

</form>

