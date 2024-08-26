<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 
	
	$mode = trim(sqlfilter($_REQUEST['mode']));
	$mode_sub = trim(sqlfilter($_REQUEST['mode_sub']));
	$idx = trim(sqlfilter($_REQUEST['idx']));

	$sql = "select *,(select order_name from order_member where 1 and order_num=".$mode.".order_num) as order_name,(select user_id from order_member where 1 and order_num=".$mode.".order_num) as user_id,(select order_date from order_member where 1 and order_num=".$mode.".order_num) as order_date,(select pay_sect_1 from order_member where 1 and order_num=".$mode.".order_num) as pay_sect_1,(select deposit_name from order_member where 1 and order_num=".$mode.".order_num) as deposit_name,(select deposit_bank from order_member where 1 and order_num=".$mode.".order_num) as deposit_bank from ".$mode." where 1 and idx='".$idx."'";
	$query = mysqli_query($gconnet,$sql);
	$row = mysqli_fetch_array($query);

					switch ($row['pay_sect_1']){
						case "card_isp" : 
						$pay_sect_str1 = "신용카드";
						break;
						case "bank_iche" : 
						$pay_sect_str1 = "계좌이체";
						break;
						case "pay_virt" : 
						$pay_sect_str1 = "가상계좌";
						break;
						case "dsend" : 
						$pay_sect_str1 = "무통장 입금";
						break;
					}

?>	
	<div class="popup_title">
		<p>결제정보</p>
		<span class="btn_close" onClick="popup_close();"></span>
	</div>
	<div class="popup_con">
		<table>
			<caption>결제정보</caption>
			<colgroup>
				<col style="width:26%;">
				<col style="width:74%;">
			</colgroup>
			<tr>
				<th scope="row">주문번호</th>
				<td>
					<span class="num"><?=$row[order_num]?></span>
				</td>
			</tr>
			<tr>
				<th scope="row">주문일자</th>
				<td>
					<span class="date"><?=substr($row[order_date],0,10)?></span>
				</td>
			</tr>
			<tr>
				<th scope="row">총결제금액</th>
				<td>
					<span class="pay"><?=number_format($row[payment_total_price]+$row[delev_price])?>원 / VAT <?=number_format($row[pro_total_price_vat])?>원 (10%) 포함</span>
				</td>
			</tr>
			<tr>
				<th scope="row">결제수단</th>
				<td>
					<span class="way">
						<?=$pay_sect_str1?>
					<?if($row['pay_sect_1'] == "card_isp"){?>
						<button type="button" class="btn_pay" onclick="kgmob_card_rec('<?=$row[ApprNo]?>','Y');">영수증 출력</button>
					<?}?>
					</span>
				</td>
			</tr>
		<?if($row['pay_sect_1'] == "dsend"){?>
			<!-- 계좌이체 -->
			<tr>
				<th scope="row">입금자명</th>
				<td>
					<span class="name"><?=$row[deposit_name]?></span>
				</td>
			</tr>
			<tr>
				<th scope="row">입금은행</th>
				<td>
					<span class="bank"><?=$row[deposit_bank]?></span>
				</td>
			</tr>
			<!-- //계좌이체 -->
		<?}?>
		</table>
		<div class="pay_btn">
			<button type="button" class="btn_gray">인쇄하기</button>
			<button type="button" onClick="popup_close();" class="btn_white">창 닫기</button>
		</div>
	</div>