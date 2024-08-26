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
	error_frame("취소가 실패했습니다.");
}

$sql_pre = "select * from ".NS."order_member where order_num='".$order_num."' ";
$query_pre = mysqli_query($GLOBALS['gconnet'],$sql_pre);
if(!$row_pre = mysqli_fetch_array($query_pre)){
	error_frame("변경할 결제내역이 없습니다.");
}

$member_idx = $row_pre[member_idx];
$pay_method = $row_pre[pay_sect_1];
$pay_total = $row_pre[price_total];
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
	error_frame("취소가 실패했습니다.");
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
/* INIcancel.php
 *
 * 이미 승인된 지불을 취소한다.
 * 은행계좌 이체 , 무통장입금은 이 모듈을 통해 취소 불가능.
 *  [은행계좌이체는 상점정산 조회페이지 (https://iniweb.inicis.com)를 통해 취소 환불 가능하며, 무통장입금은 취소 기능이 없습니다.]  
 *  
 * Date : 2007/09
 * Author : ts@inicis.com
 * Project : INIpay V5.0 for PHP
 * 
 * http://www.inicis.com
 * Copyright (C) 2007 Inicis, Co. All rights reserved.
 */


	/**************************
	 * 1. 라이브러리 인클루드 *
	 **************************/
	require(INIPAY_HOME."/libs/INILib.php");
	
	/***************************************
	 * 2. INIpay41 클래스의 인스턴스 생성 *
	 ***************************************/
	$inipay = new INIpay50;
	
	/*********************
	 * 3. 취소 정보 설정 *
	 *********************/
  	$inipay->SetField("inipayhome", INIPAY_HOME); // 이니페이 홈디렉터리(상점수정 필요)
  	$inipay->SetField("type", "cancel");                            // 고정 (절대 수정 불가)
  	$inipay->SetField("debug", "true");                             // 로그모드("true"로 설정하면 상세로그가 생성됨.)
	if($escrow == "Y"){
		$inipay->SetField("mid", INIPAY_EMID);                                 // 상점아이디
	}else{
		$inipay->SetField("mid", INIPAY_MID);                                 // 상점아이디
	}
 	/**************************************************************************************************
     * admin 은 키패스워드 변수명입니다. 수정하시면 안됩니다. 1111의 부분만 수정해서 사용하시기 바랍니다.
     * 키패스워드는 상점관리자 페이지(https://iniweb.inicis.com)의 비밀번호가 아닙니다. 주의해 주시기 바랍니다.
     * 키패스워드는 숫자 4자리로만 구성됩니다. 이 값은 키파일 발급시 결정됩니다.
     * 키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.
     **************************************************************************************************/
	$inipay->SetField("admin", "1111");                            
	$inipay->SetField("tid", $DealNo);                                 // 취소할 거래의 거래아이디
	$inipay->SetField("cancelmsg", iconv("UTF-8", "EUC-KR", $cancel_memo));                           // 취소사유

	/****************
	 * 4. 취소 요청 *
	 ****************/
	$inipay->startAction();
	
	
	/****************************************************************
	 * 5. 취소 결과                                           	*
	 *                                                        	*
	 * 결과코드 : $inipay->getResult('ResultCode') ("00"이면 취소 성공)  	*
	 * 결과내용 : $inipay->getResult('ResultMsg') (취소결과에 대한 설명) 	*
	 * 취소날짜 : $inipay->getResult('CancelDate') (YYYYMMDD)          	*
	 * 취소시각 : $inipay->getResult('CancelTime') (HHMMSS)            	*
	 * 현금영수증 취소 승인번호 : $inipay->getResult('CSHR_CancelNum')    *
	 * (현금영수증 발급 취소시에만 리턴됨)                          * 
	 ****************************************************************/

	if($inipay->getResult('ResultCode') != "00"){
		if($is_admin){
			error_frame(iconv("EUC-KR", "UTF-8", $inipay->getResult('ResultMsg'))."\\n주문은 취소되었지만 결제모듈 결제취소가 실패했습니다. \\n상점관리자에서 직접 결제취소하시기 바랍니다.");
		}else{
			error_frame(iconv("EUC-KR", "UTF-8", $inipay->getResult('ResultMsg'))."\\n주문은 취소되었지만 결제모듈 결제취소가 실패했습니다. \\n관리자에게 문의바랍니다.");
		}
	}
}

if($is_admin){
?>
<script type="text/javascript">
	alert("결제취소로 상태변경이 정상적으로 처리 되었습니다.");
	window.parent.document.location.replace("order_list.php?v_sect=<?=$order_result?>");
</script>
<?
}else{
?>
<script type="text/javascript">
	alert("결제취소로 상태변경이 정상적으로 처리 되었습니다.");
	window.parent.document.location.reload();
</script>
<?
}
