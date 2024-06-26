<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/check_login_frame.php"; // 공통함수 인클루드 ?>
<?
	$member_idx = $_SESSION['member_coinc_idx'];
	$inc_member_row = get_member_data($_SESSION['member_coinc_idx']);
	
	$order_num = trim(sqlfilter($_REQUEST['order_num']));
	$pay_sect_1 = trim(sqlfilter($_REQUEST['purchase_type']));
	$pay_bank_depositor = trim(sqlfilter($_REQUEST['pay_bank_depositor']));
	$price = trim(sqlfilter($_REQUEST['price']));
	if($price == "-1"){
		$price_total_org = trim(sqlfilter($_REQUEST['price_other']));
	} else {
		$price_total_org = $price;
	}
	
	$pay_point = trim(sqlfilter($_REQUEST['pay_point']));
	$pay_refund = trim(sqlfilter($_REQUEST['pay_refund']));
	$coupon_idx = trim(sqlfilter($_REQUEST['coupon_idx']));
	
	$price_total = $price_total_org-$pay_point-$pay_refund;
	
	$point_sect = "smspay"; // sms 충전 
	$mile_title = $price_total." 충전"; // 포인트  적립 내역
	$mile_sect = "P"; // 포인트  종류 = A : 적립, P : 대기, M : 차감
	$contents_idx = coin_plus_minus($point_sect,$member_idx,$mile_sect,$price_total,$mile_title,$order_num,$price_total,"","","","");
		
	$orderstat = "pre";
	$pay_type = "normal"; 
		
	$pay_bank = $inc_confg_bank_name;
	$pay_bank_num = $inc_confg_bank_num;
	$pay_bank_name = $inc_confg_bank_owner;
	
	$query_order = "insert into order_member set"; 
	$query_order .= " contents_tbname = 'member_point', ";
	$query_order .= " contents_idx = '".$contents_idx."', ";
	$query_order .= " order_num = '".$order_num."', ";
	$query_order .= " member_idx = '".$member_idx."', ";
	$query_order .= " user_id = '".$inc_member_row['user_id']."', ";
	$query_order .= " orderstat = '".$orderstat."', ";
	$query_order .= " order_name = '".$inc_member_row['user_name']."', ";
	$query_order .= " order_email = '".$inc_member_row['email']."', ";
	$query_order .= " order_cell = '".$inc_member_row['cell']."', ";
	$query_order .= " pay_type = '".$pay_type."', ";
	$query_order .= " pay_sect_1 = '".$pay_sect_1."', ";
	$query_order .= " pay_bank = '".$pay_bank."', ";
	$query_order .= " pay_bank_num = '".$pay_bank_num."', ";
	$query_order .= " pay_bank_name = '".$pay_bank_name."', ";
	$query_order .= " pay_bank_depositor = '".$pay_bank_depositor."', ";
	
	/*$query_order .= " card_name = '".$card_name."', ";
	$query_order .= " quota = '".$quota."', ";
	$query_order .= " iche_bank_name = '".$iche_bank_name."', ";*/
	
	$query_order .= " price_total_org = '".$price_total_org."', ";
	$query_order .= " pay_point = '".$pay_point."', ";
	$query_order .= " coupon_idx = '".$coupon_idx."', ";
	$query_order .= " pay_refund = '".$pay_refund."', ";
	$query_order .= " price_total = '".$price_total."', ";
	
	/*$query_order .= " ApprNo = '".$ApprNo."', ";
	$query_order .= " ApprTm = '".$ApprTm."', ";
	$query_order .= " DealNo = '".$DealNo."', ";*/
	
	if($orderstat == "com"){
		$query_order .= " order_date = now(), ";
		$query_order .= " payment_date = now() ";
	} else {
		$query_order .= " order_date = now() ";
	}
	//echo $query_order;
	$result_order = mysqli_query($gconnet,$query_order);
	
	error_frame_go("무통장결제가 등록 되었습니다. 안내된 계좌로 입금하시면 충전이 완료됩니다.","/pay02.php");
?>