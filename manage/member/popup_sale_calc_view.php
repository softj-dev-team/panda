<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage_pop.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));

	$sql_basic = "select *,(select product_title from product_info where 1 and is_del='N' and idx=product_sale_history_calc.product_idx) as product_title,(select user_nick from member_info where 1 and del_yn='N' and idx=product_sale_history_calc.member_idx_sale) as user_sale,(select email from member_info where 1 and del_yn='N' and idx=product_sale_history_calc.member_idx_sale) as user_sale_email,(select orderstat from order_member where 1 and is_del='N' and order_num=product_sale_history_calc.order_num) as orderstat,(select payment_date from order_member where 1 and is_del='N' and order_num=product_sale_history_calc.order_num) as payment_date,(select pay_sect_1 from order_member where 1 and is_del='N' and order_num=product_sale_history_calc.order_num) as pay_sect_1,(select price_total_usd from order_member where 1 and is_del='N' and order_num=product_sale_history_calc.order_num) as price_total_usd,(select price_total_won from order_member where 1 and is_del='N' and order_num=product_sale_history_calc.order_num) as price_total_won,(select cancel_date from order_member where 1 and is_del='N' and order_num=product_sale_history_calc.order_num) as cancel_date,(select sale_method from product_sale_history where 1 and is_del='N' and order_num=product_sale_history_calc.order_num and product_idx=product_sale_history_calc.product_idx) as sale_method from product_sale_history_calc where 1 and is_del='N' and order_num=(select order_num from product_sale_history where 1 and idx='".$idx."' and is_del='N')";
	//echo $sql_basic; exit;
	$query_basic = mysqli_query($gconnet,$sql_basic);

	if(mysqli_num_rows($query_basic) == 0){
		error_popup("정산이력이 없습니다.");
	}

	$row_basic = mysqli_fetch_array($query_basic);

	$sql_bank = "select * from member_bank_info where 1 and is_del='N' and member_idx='".$row_basic['member_idx_sale']."' order by idx desc limit 0,1";
	$query_bank = mysqli_query($gconnet,$sql_bank);
	$row_bank = mysqli_fetch_array($query_bank);
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
						<li>정산내역</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>정산내역 보기</h3>
				</div>

				<ul class="list_tab" style="margin-top:10px;margin-bottom:10px;padding-left:10px;">
				<?if($row_basic['sale_method'] == "2"){ // 옥션?>
					<li>
						<a href="popup_sale_auction_view.php?idx=<?=$idx?>">경매입찰 보기</a>
					</li>
				<?}?>
					<li>
						<a href="popup_sale_offer_view.php?idx=<?=$idx?>">offer 보기</a>
					</li>
					<li>
						<a href="popup_sale_history_view.php?idx=<?=$idx?>">판매내역 보기</a>
					</li>
					<li class="on">
						<a href="popup_sale_calc_view.php?idx=<?=$idx?>">정산내역 보기</a>
					</li>
				</ul>
			
				<!-- 판매상품정보 시작 -->
				<div class="write">
					<p class="tit">정산정보</p>
					<table>
							<caption>콘텐츠 정보</caption>
							<colgroup>
								<col style="width:15%">
								<col style="width:35%">
								<col style="width:15%">
								<col style="width:35%">
							</colgroup>
							<tr>
								<th scope="row">정산상태</th>
								<td>
									<?if($row_basic['calc_yn'] == "N"){?>
										<font style="color:#f96666;">정산요청</font>
									<?}elseif($row_basic['calc_yn'] == "Y"){?>
										<font style="color:#2c558e;">정산완료</font>
									<?}?>
								</td>
								<th scope="row">정산요청일</th>
								<td>
									<?=$row_basic['date_req']?>
									&nbsp; <a href="javascript:set_member_bank();" class="btn_green">정산 계좌 보기</a>
								</td>
							</tr>
							<tr>
								<th scope="row">수수료</th>
								<td>
									<?=number_format($row_basic['mny_minus_1'])?>
								</td>
								<th scope="row">정산차감액</th>
								<td>
									<?=number_format($row_basic['mny_minus_2'])?>
								</td>
							</tr>
							<tr>
								<th scope="row">최종 정산액</th>
								<td colspan="3">
									<?=number_format($row_basic['mny_calc'])?>
								</td>
							</tr>
						</table>

						<p class="tit">정산처리</p>
						<table>
						<form name="set_frm" id="set_frm" action="popup_sale_calc_view_action.php" target="_fra_admin" method="post">
							<input type="hidden" name="idx" id="idx" value="<?=$idx?>"/>
							<caption>콘텐츠 정보</caption>
							<colgroup>
								<col style="width:15%">
								<col style="width:35%">
								<col style="width:15%">
								<col style="width:35%">
							</colgroup>
							<tr>
								<th scope="row">정산처리</th>
								<td colspan="3">
									<select name="calc_yn" id="calc_yn" required="yes" message="정산처리">
										<option value="">선택하세요</option>
										<option value="Y" <?=$row_basic['calc_yn']=="Y"?"selected":""?>>정산 처리 완료</option>
										<option value="N" <?=$row_basic['calc_yn']=="N"?"selected":""?>>정산 대기</option>
									</select>
								</td>
							</tr>
						<?if($row_basic['calc_yn']=="Y"){?>
							<tr>
								<th scope="row">정산처리일</th>
								<td colspan="3">
									<?=$row_basic['date_com']?>
								</td>
							</tr>
						<?}?>
						</form>
						</table>
						
						<!-- 모달팝업 배경레이어 시작 -->
							<div id="modal_auth_mark_back" style="width:100%;height:100%;position:absolute;left:0;top:0;display:none;background:rgba(255, 255, 255, 0.25);"></div>
						<!-- 모달팝업 배경레이어 종료 -->

						<!-- 계좌번호 팝업 시작 -->
						<div id="modal_member_bank" style="display:none; position:fixed; top:10%; left:10%; width:80%; max-width:700px; background:#fff;z-index:2000; border-radius:5px; padding:25px 15px 15px 15px;">
							<div class="list_tit">
								<h3>계좌번호</h3>
							</div>
							<table>
							<caption>게시글 등록</caption>
							<colgroup>
								<col style="width:25%;">
								<col style="width:75%;">
							</colgroup>
							<?if(mysqli_num_rows($query_bank) == 0){?>
								<tr>
									<td colspan="2" style="text-align:center;">
										계좌번호가 등록되지 않았습니다
									</td>
								</tr>
							<?}else{?>
								<tr>
									<td colspan="2" style="text-align:center;">
										<?=$row_basic['user_sale']?> (<?=$row_basic['user_sale_email']?>) 님의 계좌번호
									</td>
								</tr>
								<tr>
									<th scope="row">은행</th>
									<td><?=$row_bank['bank_name']?></td>
								</tr>
								<tr>
									<th scope="row">계좌번호</th>
									<td><?=$row_bank['bank_num']?></td>
								</tr>
								<tr>
									<th scope="row">예금주</th>
									<td><?=$row_bank['bank_owner']?></td>
								</tr>
							<?}?>
							</table>
							<div class="write_btn align_r mt35">
								<a href="javascript:set_member_bank_close();" class="btn_blue">확인</a>
							</div>
						</div>
					<!-- 계좌번호 팝업 종료 -->

					</div>	
				<!-- 판매상품정보 종료 -->
			
				<div class="write" style="margin-top:-20px;">
					<div class="write_btn align_r">
						<a href="javascript:go_set_submit();" class="btn_blue">저장</a>
						<a href="javascript:self.close();" class="btn_gray">닫기</a>
					</div>
				</div>

			</div>
		<!-- content 종료 -->
	</div>
</div>

<script>
	function go_set_submit() {
		var check = chkFrm('set_frm');
		if(check) {
			set_frm.submit();
		} else {
			false;
		}
	}

	function set_member_bank(){
		$("#modal_auth_mark_back").show();
		$("#modal_member_bank").show();
	}
	
	function set_member_bank_close(){
		$("#modal_auth_mark_back").hide();
		$("#modal_member_bank").hide();
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>

</body>
</html>