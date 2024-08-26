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

if(!turnoff_autocommit()){
	error_frame("반품처리가 실패했습니다.");
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
$save_point = $row_pre["save_point"];
$use_point = $row_pre["pay_refund"];

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
		if($save_canceled_res["ERROR_CODE"] != "E0000"){
			$cancelFlag = true;
			$message = "적립된 포인트 취소가 실패했습니다.";
		}
	}

	if(!$cancelFlag && $use_apv_no){
		$use_canceled_res = meta_cancel_used_point(get_cancel_used_data($user_id, $order_num, $use_apv_no));
		if($use_canceled_res["ERROR_CODE"] != "E0000"){
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

	/* INIescrow_denyconfirm.php
 *
 * 배송 등록 변경  요청을 처리한다.
 * 코드에 대한 자세한 설명은 매뉴얼을 참조하십시오.
 * <주의> 등록자의 세션을 반드시 체크하도록하여 부정등록를 방지하여 주십시요.
 *  
 * http://www.inicis.com
 * Copyright (C) 2006 Inicis Co., Ltd. All rights reserved.
 */


	/**************************
	 * 1. 라이브러리 인클루드 *
	 **************************/
	require(INIPAY_HOME."/libs/INILib.php");
	
	
	/***************************************
	 * 2. INIpay50 클래스의 인스턴스 생성 *
	 ***************************************/
	$iniescrow = new INIpay50;
	
	/*********************
	 * 3. 지불 정보 설정 *
	 *********************/
	$iniescrow->SetField("inipayhome", INIPAY_HOME);      // 이니페이 홈디렉터리(상점수정 필요)
	$iniescrow->SetField("tid",$row_pre["DealNo"]); // 거래아이디
	$iniescrow->SetField("mid",INIPAY_EMID); // 상점아이디
    /**************************************************************************************************
     * admin 은 키패스워드 변수명입니다. 수정하시면 안됩니다. 1111의 부분만 수정해서 사용하시기 바랍니다.
     * 키패스워드는 상점관리자 페이지(https://iniweb.inicis.com)의 비밀번호가 아닙니다. 주의해 주시기 바랍니다.
     * 키패스워드는 숫자 4자리로만 구성됩니다. 이 값은 키파일 발급시 결정됩니다.
     * 키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.
     **************************************************************************************************/
	$iniescrow->SetField("admin","1111"); // 키패스워드(상점아이디에 따라 변경)
  	$iniescrow->SetField("type", "escrow"); 				                    // 고정 (절대 수정 불가)
	$iniescrow->SetField("escrowtype", "dcnf"); 				                    // 고정 (절대 수정 불가)
	$iniescrow->SetField("dcnf_name",iconv("UTF-8", "EUC-KR", $repay_name));
	$iniescrow->SetField("debug","true"); // 로그모드("true"로 설정하면 상세한 로그가 생성됨)

	/*********************
	 * 3. 거절확인 요청 *
	 *********************/
	$iniescrow->startAction();
	
	
	/**********************
	 * 4. 거절확인  결과 *
	 **********************/
	 
	 $tid          = $iniescrow->GetResult("tid"); 					// 거래번호
	 $resultCode   = $iniescrow->GetResult("ResultCode");		// 결과코드 ("00"이면 지불 성공)
	 $resultMsg    = $iniescrow->GetResult("ResultMsg");    // 결과내용 (지불결과에 대한 설명)
	 $resultDate   = $iniescrow->GetResult("DCNF_Date");    // 처리 날짜
	 $resultTime   = $iniescrow->GetResult("DCNF_Time");    // 처리 시각

	 if($resultCode != "00"){
		error_frame("주문이 환불처리되었지만 결제모듈 구매거절확인이 실패했습니다. \\n상점관리자에서 직접 구매거절확인 진행하시기 바랍니다. \\n".iconv("EUC-KR", "UTF-8", $resultMsg)); 
	}

?>

<script type="text/javascript">
	alert("반품처리가 정상적으로 완료 되었습니다.");
	window.parent.document.location.replace("order_list.php?v_sect=recom");
	//parent.location.href =  "order_cancel_list.php?bmenu=4&smenu=3";
</script>