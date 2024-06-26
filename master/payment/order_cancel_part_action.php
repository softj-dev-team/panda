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
$order_idx = $_REQUEST['order_idx'];

if(!$_REQUEST['order_idx']){
	error_frame("결제취소할 상품이 없습니다.");
}

if(!$order_num){
	error_frame("주문번호가 없습니다.");
}

for($k=0; $k<sizeof($order_idx); $k++){
	if($k == sizeof($order_idx)-1){
		$order_idx_arr .= $order_idx[$k];
	} else {
		$order_idx_arr .= $order_idx[$k].",";
	}
}

$order_product_where = " and idx in (".$order_idx_arr.") ";
$order_product_not_where = " and idx not in (".$order_idx_arr.") ";

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
$vbank_payment = $row_pre["vbank_payment"];

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
$sql_product3 = "select idx,delvstat from order_product where 1 and order_num = '".$order_num."' and delvstat in ('d_pre','d_ing','d_com','d_conf','d_deny') ".$order_product_where;
$query_product3 = mysqli_query($GLOBALS['gconnet'],$sql_product3);
if(mysqli_num_rows($query_product3) > 0){
	$row_product3 = mysqli_fetch_array($query_product3);
	error_frame("배송상태가 ".get_delivery_status($row_product3["delvstat"])." 인 상품이 있어 결제취소가 불가합니다. 화면을 새로고침 하신 다음 다시 취소할 상품을 선택해주세요.");
}


$sql_product4 = "select idx,price_dan,p_cnt,product_sale_point,product_idx from order_product where 1 and order_num = '".$order_num."' ".$order_product_where;
//echo $sql_product4."<br>";
$query_product4 = mysqli_query($GLOBALS['gconnet'],$sql_product4);
	
$total_reing_pay = 0;
for($pro_i=0; $pro_i<mysqli_num_rows($query_product4); $pro_i++){ 
	$row_product4 = mysqli_fetch_array($query_product4);
	$reing_pay = $row_product4['price_dan']*$row_product4['p_cnt'];
	//$product_chg_cnt = trim(sqlfilter($_REQUEST['chg_cnt_'.$row_product4[idx].'']));
	//$reing_pay = $row_product4['price_dan']*$product_chg_cnt;
	$total_reing_pay = $total_reing_pay + $reing_pay;

	$sql_product5 = "update product_info set pro_cnt = pro_cnt + ".$row_product4['p_cnt']." where idx = ".$row_product4['product_idx'];
	$query_product5 = mysqli_query($GLOBALS['gconnet'],$sql_product5);
}

$sql_product5 = "select idx from order_product where 1 and order_num = '".$order_num."' and orderstat in ('pre','com') delvstat not in ('d_ing','d_com','d_conf','d_deny') ".$order_product_not_where;
$query_product5 = mysqli_query($GLOBALS['gconnet'],$sql_product5);
if(mysqli_num_rows($query_product5) == 0){
	$total_reing_pay = $total_reing_pay + $row_pre["price_delivery"]; // 배송비까지 모두 취소
}

//echo "취소금액 = ".$total_reing_pay."<br>"; exit;

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

$cancelFlag = false;

/*if($user_id){
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
}*/

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

if($origin_order_status == "com"){ // 결제성공, 카드 시작
	
	if($pay_type != "onyref"){ // 전액 포인트 결제가 아닐때
		if($pay_method == "card_isp"){ // 카드결제일때
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, 'https://www.jangbogoshop.com/payd/BillCancel.php?TID='.$DealNo.'&AMOUNT='.$total_reing_pay.'&CANCELTYPE=P&CANCELDESC='.$cancel_sect_1.''); 
			curl_setopt($ch, CURLOPT_HEADER, 0); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0'); 
			$can_result_arr = curl_exec($ch); 
			curl_close($ch);
			//echo "can = ".$content;
			$can_result_arr2 = explode("|",$can_result_arr);
			$can_result = $can_result_arr2[0];
			$can_result_msg = $can_result_arr2[1];
		} else { // 카드결제가 아닌것은 루틴 추가 시작

		} // 카드결제가 아닌것은 루틴추가 종료
	} else { // 전액 포인트 결제일때 
		$can_result = "0000"; 
		$can_result_msg = "전액 포인트 결제입니다.";
	} // 전액 포인트결제인가 여부 종료 
		
	if($can_result == "0000"){ // PG 결제취소 성공 시작

		############ 결제취소전문 생성 시작 ############
		$query_js_pro = "select * from order_product where order_num = '".$order_num."' ".$order_product_where;
		$result_js_pro = mysqli_query($gconnet,$query_js_pro);
		if(mysqli_num_rows($result_js_pro)>0){ // 상품이 있을때 시작 
			 $j_array_pro = '","goods_list":[';
			for($result_js_i=0; $result_js_i<mysqli_num_rows($result_js_pro); $result_js_i++){ // 상품숫자만큼 루프 시작
				$result_js_row = mysqli_fetch_array($result_js_pro);
				$price_dan = 	$result_js_row["price_dan"];

					$sql_opt1 = "select opt_idx from order_product_opt where 1=1 and order_num='".$order_num."' and order_product_idx='".$result_js_row[idx]."' and product_idx = '".$result_js_row[product_idx]."' ";
					$query_opt1 = mysqli_query($gconnet,$sql_opt1);
				
					for ($i2=0; $i2<mysqli_num_rows($query_opt1); $i2++){ // 선택한 옵션에 따른 루프 시작
						$row_opt1 = mysqli_fetch_array($query_opt1);
						
						$sql_opt2 = "select idx,opt_title,opt_name,opt_sect,opt_price from product_opt where idx = '".$row_opt1[opt_idx]."' ";
						$query_opt2 = mysqli_query($gconnet,$sql_opt2);
							
						for ($k=0; $k<mysqli_num_rows($query_opt2); $k++){  // 선택한 옵션의 항목에 따른 루프 시작 
								$row_opt2 = mysqli_fetch_array($query_opt2);

								if($row_opt2[opt_sect] == "A"){
									$price_dan = $price_dan+$row_opt2[opt_price];
								} elseif($row_opt2[opt_sect] == "M"){
									$price_dan = $price_dan-$row_opt2[opt_price];
								}
						} // 선택한 옵션의 항목에 따른 루프 종료
					} //  // 선택한 옵션에 따른 루프 종료
					$goods_amt = $price_dan*$result_js_row[p_cnt];
					//$cost = product_cost($result_js_row[product_idx]);
					//$ad_point = product_ad_point($result_js_row[product_idx],$member[cmd001][biz_type]);
					$pro_name = $result_js_row[p_name];
					/*$pro_name = str_replace("[","",$pro_name);
					$pro_name = str_replace("]","",$pro_name);
					$pro_name = str_replace("(","",$pro_name);
					$pro_name = str_replace(")","",$pro_name);*/
					$pro_name = str_replace(array("\r\n","\r","\n"),'',$pro_name); 
					$pro_name = urlencode($pro_name);
	
					if($result_js_i == mysqli_num_rows($result_js_pro)-1){
						$j_array_pro .= '{"goods_code":"'.$result_js_row[pro_code].'","goods_price":"'.$price_dan.'","goods_cnt":"'.$result_js_row[p_cnt].'"}';
					} else {
						$j_array_pro .= '{"goods_code":"'.$result_js_row[pro_code].'","goods_price":"'.$price_dan.'","goods_cnt":"'.$result_js_row[p_cnt].'"},';
					}
			 } // 상품숫자만큼 루프 종료
		  $j_array_pro .= ']';
		} // 상품이 있을때 종료
 
		$j_array = array('cmd'=>'cmd012','user_code'=>''.$member_idx.'','total_payment'=>''.$pay_total.'','cancel_payment'=>''.$total_reing_pay.'','bill_code'=>''.$order_num.''.$j_array_pro);
		$j_array = array_map('htmlentities',$j_array);
		$j_json = html_entity_decode(json_encode($j_array,JSON_UNESCAPED_UNICODE));
		$json = get_curl_json_post('http://115.68.20.89:10100/main?param='.$j_json.'','');
		$decode = json_decode($json, true);
		//echo "원본 = ".$json."<br>";
		$app_pay = $decode['cmd012']['result'];
		//echo "결과 = ".$app_pay."<br>";
		############ 결제취소전문 생성 종료 ############

		$app_pay = 1;

		if($app_pay == 1){ // 결제취소 전문 성공
			$query_pro = " update ".NS."order_product set "; 
			$query_pro .= " orderstat = '".$order_result."', ";
			$query_pro .= " cancel_payment = '".$total_reing_pay."', ";
			$query_pro .= " cancel_memo = '".$cancel_memo."', ";
			$query_pro .= " cancel_sect_1 = '".$cancel_sect_1."', ";
			$query_pro .= " cancel_sect = '".$cancel_sect."', ";
			$query_pro .= " cancel_admin = '".$cancel_admin."', ";
			$query_pro .= " cancel_date = now() ";
			$query_pro .= " where 1 and order_num = '".$order_num."' ".$order_product_where;
			if(!$result_pro = mysqli_query($GLOBALS['gconnet'],$query_pro)){
				error_frame("취소가 실패했습니다.");
			}

			$query_3 = "select idx from order_product where 1 and order_num='".$order_num."' ";
			$result_3 = mysqli_query($GLOBALS['gconnet'],$query_3);
			$total_op_cnt = mysqli_num_rows($result_3);

			$query_4 = "select idx from order_product where 1 and order_num='".$order_num."' and orderstat = '".$order_result."'";
			$result_4 = mysqli_query($GLOBALS['gconnet'],$query_4);
			$stat_op_cnt = mysqli_num_rows($result_4);
			
			if($stat_op_cnt == $total_op_cnt){
				$query = " update ".NS."order_member set "; 
				$query .= " orderstat = '".$order_result."', ";
				$query .= " cancel_payment = '".$total_reing_pay."', ";
				$query .= " cancel_memo = '".$cancel_memo."', ";
				$query .= " cancel_sect_1 = '".$cancel_sect_1."', ";
				$query .= " cancel_sect = '".$cancel_sect."', ";
				$query .= " cancel_admin = '".$cancel_admin."', ";
				$query .= " cancel_date = now() ";
				$query .= " where order_num = '".$order_num."' ";
				if(!$result = mysqli_query($GLOBALS['gconnet'],$query)){
					error_frame("취소가 실패했습니다.");
				}
			}

		} else { // 결제취소 전문 실패
			error_frame("취소가 실패했습니다.");
		} // 결제취소 전문 종료
	} else {  // PG 결제취소 실패 시작 
		if($is_admin){
			error_frame($can_result_msg."\\n상점관리자에서 직접 결제취소하시기 바랍니다.");
		}else{
			error_frame($can_result_msg."\\n관리자에게 문의바랍니다.");
		}
	}  // PG 결제취소 실패 종료 

} // 결제성공, 카드 종료

//exit;

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
	window.parent.document.location.reload();
	//parent.location.href =  "order_list.php?<?=$total_param?>";
</script>
<?
}
?>