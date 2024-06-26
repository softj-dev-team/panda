<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include "../inc/header.php" ?>
	<?
	$order_num =  trim(sqlfilter($_REQUEST['order_num']));
	$cart_idx =  trim(sqlfilter($_REQUEST['cart_idx']));
		
	$sql = "select *,(select price_total from order_member where 1 and order_num=order_product.order_num) as price_total,(select pay_sect_1 from order_member where 1 and order_num=order_product.order_num) as pay_sect_1,(select ApprNo from order_member where 1 and order_num=order_product.order_num) as ApprNo,(select order_name from order_member where 1 and order_num=order_product.order_num) as order_name,(select user_id from order_member where 1 and order_num=order_product.order_num) as user_id,(select order_date from order_member where 1 and order_num=order_product.order_num) as order_date,(select cate_name1 from paper_info where 1 and cate_type=order_product.pro_type and paper_type='paper' and  cate_code1=order_product.paper_cover_1 and cate_level = '1') as paper_name_1,(select cate_name2 from paper_info where 1 and cate_type=order_product.pro_type and paper_type='paper' and  cate_code2=order_product.paper_cover_2 and cate_level = '2') as paper_name_2,(select cate_name3 from paper_info where 1 and cate_type=order_product.pro_type and paper_type='paper' and  cate_code3=order_product.paper_cover_3 and cate_level = '3') as paper_name_3 from order_product where 1 and idx='".$cart_idx."' and order_num='".$order_num."' and orderstat in ('com','pre')";
	$query = mysqli_query($gconnet,$sql);
		
	if(mysqli_num_rows($query) == 0){
		error_popup("결제취소할 주문내역이 없습니다.");
	}

	$row = mysqli_fetch_array($query);
	
	if (Trim($row['delvstat'])=="d_ing"){
		$delv_stat_str = "배송중";
	} elseif (Trim($row['delvstat'])=="d_com"){
		$delv_stat_str = "배송완료";
	} elseif (Trim($row['delvstat'])=="d_conf"){
		$delv_stat_str = "고객수령 확인";
	} else {
		$delv_stat_str = "배송준비";
	}

	if(Trim($row['delvstat']) == "d_ing" || Trim($row['delvstat']) == "d_com" || Trim($row['delvstat']) == "d_conf"){
		error_popup($delv_stat_str."인 주문은 취소하실수 없습니다.");
	}

	/*if($row['pay_sect_1'] == "pay_virt" || $row['pay_sect_1'] == "bank_iche" || $row['pay_sect_1'] == "dsend"){ // 가상계좌, 계좌이체, 무통장 입금의 경우 시작 
		$sql_make1 = "select idx from order_product where 1 and order_num = '".$order_num."' and makestat = 'I'";
		$query_make1 = mysqli_query($gconnet,$sql_make1);
		if(mysqli_num_rows($query_make1) > 1){
			error_popup("이미 인쇄중인 주문건이 있어 취소하실수 없습니다.");
		}
		$sql_make2 = "select idx from order_product where 1 and order_num = '".$order_num."' and makestat = 'Y'";
		$query_make2 = mysqli_query($gconnet,$sql_make2);
		if(mysqli_num_rows($query_make2) > 1){
			error_popup("이미 인쇄완료된 주문건이 있어 취소하실수 없습니다.");
		}
	} // 가상계좌, 계좌이체, 무통장 입금의 경우 종료*/

	if($row[makestat] == "I"){
		error_popup("인쇄중인 주문은 취소하실 수 없습니다.");
	}elseif($row[makestat] == "Y"){
		error_popup("재단,후가공,포장중인 주문은 취소하실 수 없습니다.");
	}elseif($row[sian_ok] == "Y" && $row[sian_conf] == "R"){
		error_popup("시안확인요청 주문은 취소하실 수 없습니다.");
	}elseif($row[sian_ok] == "Y" && $row[sian_conf] == "Y"){
		error_popup("시안확인완료 주문은 취소하실 수 없습니다.");
	}
	
	if($_SERVER['REMOTE_ADDR'] == "121.167.147.150"){
		$payment_total_price = 1000; // 테스트용
	} else {
		$payment_total_price = $row['payment_total_price'];
	}

	$sql_pro = "select idx from order_product where 1 and order_num = '".$order_num."'";
	$query_pro = mysqli_query($gconnet,$sql_pro);

	$mode 						= "30";			// 고정값
	$svcId 						= "180312051480"; // 상점 ID
	$siteUrl 					= "www.teacherin.co.kr"; // 상점 URL
	$tradeId 					= $row['order_num']; // 상점 주문번호
	$mobilId 					= $row['ApprNo']; // 모빌리언스 주문번호
	$prdtPrice 				= $payment_total_price;

	if(mysqli_num_rows($query_pro) > 1){
		$particialCnclYn	= "Y";
	} else {
		$particialCnclYn	= "N";
	}

	?>
<script type="text/javascript">
<!--
function go_submit() {
	var check = chkFrm('frm');
	if(check) {
		if(confirm('주문내용을 취소 하시겠습니까?')){
			if(confirm('한번 취소한 주문은 다시 복구하실수 없습니다.\n\n정말 취소 하시겠습니까?')){
				document.getElementById("display_pay_button").style.display = "none" ;
				//openwin = window.open( "proc_win.html", "proc_win", "width=449, height=209, top=300, left=300" );
				var maskHeight = $(document).height();  
				var maskWidth = $(window).width();
				var top_height = maskHeight - 300;
				$('#loading_layer').css({'width':maskWidth,'height':maskHeight}); 
				$('#loading_layer_in').css({'margin-top':top_height});  
				$("#loading_layer").css("display","");

				frm.submit();
			}
		}
	} else {
		false;
	}
}

//-->
</script>
</head>
<body>
<div id="loading_layer" style="display:none;position:absolute;left:0px;top:0px;z-index:1000;">
	<div style="margin-top:0px;" id="loading_layer_in"><center><img src="/images/img-loading.gif" style="border:0"></center></div>
</div>
	<div class="popup_wrap">
		<p class="popup_title">주문취소</p>
		<div class="table_wrap" style="width:100%;">
		<?if($_SERVER['REMOTE_ADDR'] == "121.167.147.150"){?>
			<?if($row['pay_sect_1'] == "card_isp"){?>
				<form action="/mob_can_test/cn_cancel_result.php" method="post" name="frm" id="frm" target="_self">
			<?}elseif($row['pay_sect_1'] == "bank_iche"){?>
				<form action="/mob_can_test/step2_cancel.php" method="post" name="frm" id="frm" target="_self">
			<?}else{?>
				<form action="popup_cancel_nopg_action.php" method="post" name="frm" id="frm" target="_self">
			<?}?>
		<?}else{?>
			<?if($row['pay_sect_1'] == "card_isp"){?>
				<form action="popup_cancel_action.php" method="post" name="frm" id="frm" target="_self">
			<?}elseif($row['pay_sect_1'] == "bank_iche"){?>
				<form action="popup_cancel_bank_action.php" method="post" name="frm" id="frm" target="_self">
			<?}else{?>
				<form action="popup_cancel_nopg_action.php" method="post" name="frm" id="frm" target="_self">
			<?}?>
		<?}?>

			<input type="hidden" name="order_num" value="<?=$order_num?>">
			<input type="hidden" name="cart_idx" value="<?=$cart_idx?>">
	
	<?if($row['pay_sect_1'] == "card_isp"){?>
			<input type="hidden" name="mode" id="mode" value="<?echo $mode?>">
			<input type="hidden" name="svcId" id="svcId" value="<?echo $svcId?>">
			<input type="hidden" name="siteUrl" id="siteUrl" value="<?echo $siteUrl?>">
			<input type="hidden" name="tradeId" id="tradeId" value="<?echo $tradeId?>">
			<input type="hidden" name="mobilId" id="mobilId" value="<?echo $mobilId?>">
			<input type="hidden" name="prdtPrice" id="prdtPrice" value="<?echo $prdtPrice?>">
			<input type="hidden" name="particialCnclYn" id="particialCnclYn" value="<?echo $particialCnclYn?>">
	<?}elseif($row['pay_sect_1'] == "bank_iche"){?>
			<input type="hidden" name="can_cnt" value="1">
			<input type="hidden" name="CashGb1" value="RA">
			<input type="hidden" name="RecKey1"	value="teacherin.co.kr">
			<input type="hidden" name="SvcId1"	 value="180312051492">
			<input type="hidden" name="MchtTradeId1" value="<?=$row['order_num']?>">
			<input type="hidden" name="PrdtPrice1" value="<?=$row['price_total']?>">
			<input type="hidden" name="MobilId1" value="<?=$row['ApprNo']?>">
	<? } ?>

			<table class="table_l" style="width:90%;">
			<caption>고객</caption>
				<colgroup>
					<col style="width:33%;">
					<col style="width:67%;">
				</colgroup>
				<tr>
					<th scope="row">취소유형</th>
					<td>
					<select  name="cancel_sect_1" id="cancel_sect_1" required="yes" message="취소사유">
						<option value="">취소사유선택</option>
						<option value="사이즈교환">사이즈교환</option>
						<option value="색상교환">색상교환</option>
						<option value="다른제품으로교환">다른제품으로 교환</option>
						<option value="단순변심">단순변심</option>
						<option value="상품품절, 재고부족">상품품절, 재고부족</option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">상세사유</th>
				<td style="padding-top:10px;">
					<textarea placeholder="취소 상세사유를 입력하세요!" name="cancel_memo" id="cancel_memo" required="yes" message="취소사유" style="width:100%;height:100px;"></textarea>
				</td>
			</tr>
	<?if($row['orderstat'] == "com"){?>
		<?if($row['pay_sect_1'] == "pay_virt" || $row['pay_sect_1'] == "dsend"){?>
			<tr>
				<th scope="row">환불수취 계좌번호</th>
				<td >
					<input type="text"  placeholder="환불수취 계좌번호" name="refund_account" size="40" maxlength="40" required="yes" message="환불수취 계좌번호"/>
				</td>
			</tr>
			<tr>
				<th scope="row">환불수취 계좌주명</th>
				<td >
					<input type="text"  placeholder="환불수취 계좌주명" name="refund_nm" size="20" maxlength="20" required="yes" message="환불수취 계좌주명"/>
				</td>
			</tr>
			<tr>
				<th scope="row">환불수취 은행</th>
				<td >
					<select name='refund_bank_code' required="yes" message="환불수취 은행">
						     <option value="">환불수취 은행</option>
							  <option value="39">경남은행</option>
							  <option value="34">광주은행</option>
							  <option value="04">국민은행</option>
							  <option value="03">기업은행</option>
							  <option value="11">농협</option>
							  <option value="31">대구은행</option>
							  <option value="32">부산은행</option>
							  <option value="45">새마을금고</option>
							  <option value="07">수협</option>
							  <option value="88">신한은행</option>
							  <option value="48">신협</option>
							  <option value="05">외환은행</option>
							  <option value="20">우리은행</option>
							  <option value="71">우체국</option>
							  <option value="35">제주은행</option>
							  <option value="81">하나은행</option>
							  <option value="27">한국시티은행</option>
							  <option value="23">SC제일은행</option>
							  <option value="02">산업은행</option>
							  <option value="37">전북은행</option>
                    </select>
				</td>
			</tr>
		<?}?>
	<?}?>
			</table>
		</form>
		</div>

		<div class="pop_btn" id="display_pay_button" style="text-align:right;margin-top:10px;padding-right:50px;">
			<button type="button" class="btn_red" onclick="go_submit();" style="display:inline-block; vertical-align:middle; border:0; padding:0 30px; font-size:15px; color:#fff; height:44px; margin:0;background-color:#d02139; margin-right:10px;">취소요청</button>
			<button type="button" class="btn_black" onclick="self.close();" style="display:inline-block; vertical-align:middle; border:0; padding:0 30px; font-size:15px; color:#fff; height:44px; margin:0;background-color:#666666; margin-right:10px;">창닫기</button>
		</div>

	</div>
</body>
