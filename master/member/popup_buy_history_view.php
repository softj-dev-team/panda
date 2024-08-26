<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));

	$sql_basic = "select *,(select product_title from product_info where 1 and is_del='N' and idx=product_sale_history.product_idx) as product_title,(select user_nick from member_info where 1 and del_yn='N' and idx=product_sale_history.member_idx_sale) as user_sale,(select orderstat from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as orderstat,(select payment_date from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as payment_date,(select pay_sect_1 from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as pay_sect_1,(select price_total_usd from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as price_total_usd,(select price_total_won from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as price_total_won,(select cancel_date from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as cancel_date,(select nation from member_info where 1 and del_yn='N' and idx=product_sale_history.member_idx_buy) as buy_nation,(select auc_sdate from product_info_sale where 1 and is_del='N' and idx=product_sale_history.sale_idx) as auc_sdate,(select auc_edate from product_info_sale where 1 and is_del='N' and idx=product_sale_history.sale_idx) as auc_edate,(select count(member_idx) from product_info_sale_auction where
	1 and is_del='N' and sale_idx=product_sale_history.sale_idx) as auction_mem_cnt,(select sale_price_won from product_info_sale_auction where 1 and is_del='N' 
	and sale_idx=product_sale_history.sale_idx and auction_yn='Y') as sale_price_won,(select sale_price_usd from product_info_sale_auction where 1 and is_del='N' 
	and sale_idx=product_sale_history.sale_idx and auction_yn='Y') as sale_price_usd from product_sale_history where 1 and idx='".$idx."' and is_del='N'";
	//echo $sql; exit;
	$query_basic = mysqli_query($gconnet,$sql_basic);

	if(mysqli_num_rows($query_basic) == 0){
		error_popup("구매이력이 없습니다.");
	}

	$row_basic = mysqli_fetch_array($query_basic);
?>
<body>
		<!-- content 시작 -->
		<div class="content" style="position:relative; padding:0 10px 0 10px;">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>

				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>회원관리</li>
						<li>구매이력</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>구매내역 보기</h3>
				</div>

				<ul class="list_tab" style="margin-top:10px;margin-bottom:10px;padding-left:10px;">
				<?if($row_basic['sale_method'] == "2"){ // 옥션?>
					<li>
						<a href="popup_buy_auction_view.php?idx=<?=$idx?>">경매참여 보기</a>
					</li>
				<?}?>
					<li>
						<a href="popup_buy_offer_view.php?idx=<?=$idx?>">offer 보기</a>
					</li>
					<li class="on">
						<a href="popup_buy_history_view.php?idx=<?=$idx?>">구매내역 보기</a>
					</li>
				</ul>
			
				<!-- 판매상품정보 시작 -->
				<div class="write">
					<p class="tit">판매상품 정보</p>
					<table>
							<caption>콘텐츠 정보</caption>
							<colgroup>
								<col style="width:15%">
								<col style="width:35%">
								<col style="width:15%">
								<col style="width:35%">
							</colgroup>
							<tr>
								<th scope="row">판매방식</th>
								<td>
									<?=$arr_sale_method[$row_basic['sale_method']]?>
								</td>
								<th scope="row">상품명</th>
								<td>
									<?=$row_basic['product_title']?>
								</td>
							</tr>
							<tr>
								<th scope="row">상품권리</th>
								<td>
									소유권<?if($row_basic['sale_auth_yn'] == "Y"){?>,저작권<?}?>
								</td>
								<th scope="row">판매가격</th>
								<td>
							<?if($row_basic['sale_method'] == "2"){ // 경매시작 ?>
								<?if($row_basic['sale_status'] == "2" || $row_basic['sale_status'] == "4"){ // 낙찰 혹은 결제일때 ?>
									<?if($row_basic['sale_price_won']){?>
										₩ <?=number_format($row_basic['sale_price_won'])?>
									<?}?>
									<?if($row_basic['sale_price_usd']){?>
										 &nbsp; USD $ <?=$row_basic['sale_price_usd']?>
									<?}?>
								<?}else{?>
									$<?=$row_basic['sale_price']?>
								<?}?>
							<?}else{?>
									$<?=$row_basic['sale_price']?>
							<?}?>
								</td>
							</tr>
						</table>
					
					<?if($row_basic['sale_method'] == "2"){ // 경매시작 ?>
						<p class="tit">경매정보</p>
						<table>
							<caption>콘텐츠 정보</caption>
							<colgroup>
								<col style="width:15%">
								<col style="width:35%">
								<col style="width:15%">
								<col style="width:35%">
							</colgroup>
							<tr>
								<th scope="row">경매상태</th>
								<td>
									<?=$arr_sale_status2_auc[$row_basic['sale_status']]?>
								</td>
								<th scope="row">참여자</th>
								<td>
									<?=$row_basic['auction_mem_cnt']?>
								</td>
							</tr>
							<tr>
								<th scope="row">경매기간</th>
								<td colspan="3">
									<?=$row_basic['auc_sdate']?> ~ <?=$row_basic['auc_edate']?>
								</td>
							</tr>
						</table>
					<?} // 경매종료 ?>

						<p class="tit">구매결제 정보</p>
						<table>
							<caption>콘텐츠 정보</caption>
							<colgroup>
								<col style="width:15%">
								<col style="width:35%">
								<col style="width:15%">
								<col style="width:35%">
							</colgroup>
							<tr>
								<th scope="row">판매자</th>
								<td>
									<?=$row_basic['user_sale']?>
								</td>
								<th scope="row">결제금액</th>
								<td>
									<?if($row_basic['price_total_won']){?>
										₩ <?=number_format($row_basic['price_total_won'])?>
									<?}?>
									<?if($row_basic['price_total_usd']){?>
										 &nbsp; USD $ <?=$row_basic['price_total_usd']?>
									<?}?>
								</td>
							</tr>
							<tr>
								<th scope="row">결제상태</th>
								<td>
									<?=get_order_status($row_basic['orderstat'])?>
								</td>
								<th scope="row">결제수단</th>
								<td>
									<?=get_payment_method($row_basic['pay_sect_1'])?>
								</td>
							</tr>
							<tr>
								<th scope="row">결제국가</th>
								<td>
									<?=$row_basic['buy_nation']?>
								</td>
								<th scope="row">결제일시</th>
								<td>
									<?=$row_basic['payment_date']?>
								</td>
							</tr>
						</table>

					<?if($row_basic['orderstat'] == "can"){?>
						<p class="tit">결제취소 정보</p>
						<table>
							<caption>콘텐츠 정보</caption>
							<colgroup>
								<col style="width:15%">
								<col style="width:35%">
								<col style="width:15%">
								<col style="width:35%">
							</colgroup>
							<tr>
								<th scope="row">취소상태</th>
								<td>
									<?=get_order_status($row_basic['orderstat'])?>
								</td>
								<th scope="row">취소일자</th>
								<td>
									<?=$row_basic['cancel_date']?>
								</td>
							</tr>
						</table>
					<?}?>

					</div>	
				<!-- 판매상품정보 종료 -->
			
				<div class="write" style="margin-top:-20px;">
					<div class="write_btn align_r">
					<?if($row_basic['orderstat'] == "com"){?>
						<a href="javascript:;" class="btn_red">카드결제 취소</a>
					<?}?>
						<a href="javascript:self.close();" class="btn_gray">닫기</a>
					</div>
				</div>

			</div>
		<!-- content 종료 -->
	</div>
</div>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>

</body>
</html>