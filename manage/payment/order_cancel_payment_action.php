<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
require_once($_SERVER["DOCUMENT_ROOT"].'/stdpay/libs/INIStdPayUtil.php');
require_once($_SERVER["DOCUMENT_ROOT"].'/stdpay/libs/HttpClient.php');

	$order_num = trim(sqlfilter($_REQUEST['order_num']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	
	$cancel_payment = trim(sqlfilter($_REQUEST['cancel_payment']));
	$cancel_date = trim(sqlfilter($_REQUEST['cancel_date']));
	$cancel_date = $cancel_date." ".date("H:i:s");
	
	$payment_sql = "select *,(select idx from compet_info where 1 and order_num=order_member.order_num) as compet_idx,(select com_package from compet_info where 1 and order_num=order_member.order_num) as com_package,(select compet_first_price from compet_info where 1 and order_num=order_member.order_num) as compet_first_price,(select compet_second_price from compet_info where 1 and order_num=order_member.order_num) as compet_second_price,(select compet_third_price from compet_info where 1 and order_num=order_member.order_num) as compet_third_price from order_member where 1 and order_num = '".$order_num."' and orderstat not in ('can')";
	
	$payment_query = mysqli_query($gconnet,$payment_sql);
	
	if(mysqli_num_rows($payment_query) == 0){
		error_frame("환불신청할 공모전이 없습니다.");
	}

	$payment_row = mysqli_fetch_array($payment_query);

	$payment_ticket_sql = "select idx from compet_regist_info where 1 and is_del = 'N' and compet_idx='".$payment_row['compet_idx']."'";
	$payment_ticket_query = mysqli_query($gconnet,$payment_ticket_sql);
	$payment_ticket_cnt = mysqli_num_rows($payment_ticket_query);

	if($payment_ticket_cnt > 0){
		error_frame("참가한 응모작이 있기 때문에 환불신청이 불가합니다.");
	}

	if($payment_row['com_package'] == "silver"){
		error_frame("실버 패키지로 등록한 공모전은 환불신청이 불가합니다.");
	} elseif($payment_row['com_package'] == "bronze"){
		error_frame("브론즈 패키지로 등록한 공모전은 환불신청이 불가합니다.");
	} 

	################ 취소 쿼리 및 연동 시작 ###############
	
	if($payment_row['pay_sect_1'] == "refund"){ // 적립금 결제일때
			
			/*$query = " update order_member set "; 
			$query .= " orderstat = 'can', "; // 취소 
			$query .= " cancel_payment = '0', ";
			$query .= " cancel_date = '".$cancel_date."', ";
			$query .= " cancel_sect = 'A' "; // 고객이 취소 ( 관리자 취소시 A )
			$query .= " where 1 and order_num='".$payment_row['order_num']."'";
			//$can_result = mysqli_query($gconnet,$query);

			$query2 = " update ad_info_regist set "; 
			$query2 .= " order_stat = 'can', ";
			$query2 .= " mdate = '".$cancel_date."' ";
			$query2 .= " where 1 and order_num='".$payment_row['order_num']."'";
			//$can_result2 = mysqli_query($gconnet,$query2);

			$query_cal = " update ticket_payment_info set "; 
			$query_cal .= " orderstat = 'can', ";
			$query_cal .= " cancel_payment = '0', ";
			$query_cal .= " cancel_date = '".$cancel_date."', ";
			$query_cal .= " cancel_sect = 'A' "; // 고객이 취소 ( 관리자 취소시 A )
			$query_cal .= " where 1 and idx='".$payment_row['idx']."' ";
			$result_cal = mysqli_query($gconnet,$query_cal);

			if($chg_mile){ // 결제시 사용한 적립금 있으면 원상복구 시작 
				$point_sect_1 = "refund"; // 적립금
				$chg_mile_1 = $chg_mile;
				$mile_title_1 = "티켓환불 사용복구"; 
				$mile_sect_1 = "A"; // 적립금 종류 = A : 적립, P : 대기, M : 차감
				coin_plus_minus($point_sect_1,$payment_row['member_idx'],$mile_sect_1,$chg_mile_1,$mile_title_1,$payment_row['order_num'],"","");
			}  // 결제시 사용한 적립금 있으면 원상복구 종료 

			if($chg_mile_2){ // 결제시 적립된 적립금 있으면 원상복구 시작 
				$point_sect_2 = "refund"; // 적립금
				$chg_mile_2 = $chg_mile_2;
				$mile_title_2 = "티켓환불 적립복구"; 
				$mile_sect_2 = "M"; // 적립금 종류 = A : 적립, P : 대기, M : 차감
				coin_plus_minus($point_sect_2,$payment_row['member_idx'],$mile_sect_2,$chg_mile_2,$mile_title_2,$payment_row['order_num'],"","");
			}  // 결제시 적립된 적립금 있으면 원상복구 종료 
			*/
		} else { // 적립금 아닐때 

			########## 이니시스 연동 시작 #######
			
			$imp_uid = $payment_row['ApprNo'];
			$merchant_uid = $payment_row['order_num'];
			
			########## 이니시스 연동 종료 #######

			$query = " update order_member set "; 
			$query .= " orderstat = 'can', "; // 취소 
			$query .= " cancel_payment = '".$cancel_payment."', ";
			$query .= " cancel_date = '".$cancel_date."', ";
			$query .= " cancel_sect = 'A' "; // 고객이 취소 ( 관리자 취소시 A )
			$query .= " where 1 and order_num='".$payment_row['order_num']."'";
			$can_result = mysqli_query($gconnet,$query);

			$query2 = " update compet_info set "; 
			$query2 .= " pstatus = 'can', ";
			$query2 .= " mdate = '".$cancel_date."' ";
			$query2 .= " where 1 and order_num='".$payment_row['order_num']."'";
			$can_result2 = mysqli_query($gconnet,$query2);

			if($payment_row['coupon_idx']){ // 결제시 사용한 할인쿠폰 원상복구 시작 
				$query3 = " update coupon_use_info set "; 
				$query3 .= " is_del = 'Y', ";
				$query3 .= " mdate = '".$cancel_date."' ";
				$query3 .= " where 1 and coupon_num='".$payment_row['coupon_idx']."' and member_idx='".$payment_row['member_idx']."'";
				$can_result3 = mysqli_query($gconnet,$query3);
			}  // 결제시 사용한 할인쿠폰 원상복구 종료 

	} // 적립금 아닐때 종료 

	################ 취소 쿼리 및 연동 종료 ###############
	
	//exit;
?>

<script type="text/javascript">
	alert("정상적으로 처리 되었습니다.");
	window.parent.document.location.href = window.parent.document.URL;
	//parent.location.href =  "order_list.php?bmenu=4&smenu=1&v_sect=com";
</script>