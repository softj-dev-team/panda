<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));

	/*$sql_basic = "select *,(select product_title from product_info where 1 and is_del='N' and idx=product_sale_history.product_idx) as product_title,(select user_nick from member_info where 1 and del_yn='N' and idx=product_sale_history.member_idx_buy) as user_buy,(select orderstat from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as orderstat,(select payment_date from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as payment_date,(select pay_sect_1 from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as pay_sect_1,(select price_total_usd from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as price_total_usd,(select price_total_won from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as price_total_won,(select cancel_date from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as cancel_date from product_sale_history where 1 and idx='".$idx."' and is_del='N'";*/

	$sql_basic = "select *,(select product_title from product_info where 1 and is_del='N' and idx=product_info_sale.product_idx) as product_title,(select user_nick from member_info where 1 and del_yn='N' and idx=product_info_sale.member_idx) as user_sale,(select orderstat from order_member where 1 and is_del='N' and order_num=(select order_num from product_sale_history where 1 and is_del='N' and product_idx=product_info_sale.product_idx and member_idx_sale=product_info_sale.member_idx)) as orderstat from product_info_sale where 1 and is_del='N' and product_idx=(select product_idx from product_sale_history where 1 and idx='".$idx."' and is_del='N') and member_idx=(select member_idx_sale from product_sale_history where 1 and idx='".$idx."' and is_del='N')";

	//echo $sql; exit;
	$query_basic = mysqli_query($gconnet,$sql_basic);

	if(mysqli_num_rows($query_basic) == 0){
		error_popup("판매이력이 없습니다.");
	}

	$row_basic = mysqli_fetch_array($query_basic);

	// 오퍼이력 쿼리 시작 
		$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
		
		$total_param = "idx=".$idx;

		$where = " and is_del='N' and sale_idx='".$row_basic['idx']."'";

		if(!$pageNo){
			$pageNo = 1;
		}

		$s_cnt = 5; // 기본목록 5개

		$pageScale = $s_cnt;  
		$start = ($pageNo-1)*$pageScale;

		$StarRowNum = (($pageNo-1) * $pageScale);
		$EndRowNum = $pageScale;

		$order_by = " order by idx asc";

		$query = "select *,(select user_nick from member_info where 1 and del_yn='N' and idx=product_info_sale_offer.member_idx) as user_nick from product_info_sale_offer where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
		//echo $query;
		$result = mysqli_query($gconnet,$query);

		$query_cnt = "select idx from product_info_sale_offer where 1 ".$where;
		$result_cnt = mysqli_query($gconnet,$query_cnt);
		$num = mysqli_num_rows($result_cnt);

		$iTotalSubCnt = $num;
		$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
	// 오퍼이력 쿼리 종료

	$query_last = "select sale_price_usd,sale_price_won from product_info_sale_offer where 1 and offer_yn_sale='Y' and offer_yn_buy='Y' and is_del='N' and sale_idx='".$row_basic['idx']."'";
	$result_last = mysqli_query($gconnet,$query_last);
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
						<li>판매이력</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>offer 이력 보기</h3>
				</div>

				<ul class="list_tab" style="margin-top:10px;margin-bottom:10px;padding-left:10px;">
				<?if($row_basic['sale_method'] == "2"){ // 옥션?>
					<li>
						<a href="popup_sale_auction_view.php?idx=<?=$idx?>">경매입찰 보기</a>
					</li>
				<?}?>
					<li class="on">
						<a href="popup_sale_offer_view.php?idx=<?=$idx?>">offer 보기</a>
					</li>
					<li>
						<a href="popup_sale_history_view.php?idx=<?=$idx?>">판매내역 보기</a>
					</li>
				<?if($row_basic['orderstat'] == "can" || $row_basic['orderstat'] == "reing"){?>
				<?}else{?>
					<li>
						<a href="popup_sale_calc_view.php?idx=<?=$idx?>">정산내역 보기</a>
					</li>
				<?}?>
				</ul>

			 <!-- 기본정보 시작 -->
				<div class="write">
					<table>
							<caption>콘텐츠 정보</caption>
							<colgroup>
								<col style="width:15%">
								<col style="width:35%">
								<col style="width:15%">
								<col style="width:35%">
							</colgroup>
							<tr>
								<td colspan="2" style="background-color:#ffffff;">[<?=$row_basic['product_title']?>] 판매내역 보기</td>
								<th scope="row">판매자</th>
								<td style="background-color:#ffffff;"><?=$row_basic['user_sale']?></td>
							</tr>
					</table>
				</div>	
				<!-- 기본정보 종료 -->
			
			  <!--  오퍼내역 시작 -->
				<div class="list" style="margin-top:-50px;">
					<div class="search_wrap">
						<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:25%;">
								<col style="width:25%">
								<col style="width:20%;">
								<col style="width:25%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">순번</th>
									<th scope="col">구분</th>
									<th scope="col">제안자</th>
									<th scope="col">제안금액</th>
									<th scope="col">제안일시</th>
								</tr>
							</thead>
							<tbody>
						<? if($num==0) { ?>
							<tr>
								<td colspan="10" height="40">offer 내역이 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);
								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
						?>
							<tr>
								<td><?=$listnum?></td>
								<td>
									<?if($row['offer_type'] == "buyer"){?>
										<font style="color:#f96666;">구매자</font>
									<?}elseif($row['offer_type'] == "seller"){?>
										<font style="color:#2c558e;">판매자</font>
									<?}?>
								</td>
								<td><?=$row['user_nick']?></td>
								<td>
								<?if($row['sale_price_won']){?>
									₩ <?=number_format($row['sale_price_won'])?>
								<?}?>
								<?if($row['sale_price_usd']){?>
									 &nbsp; USD $ <?=$row['sale_price_usd']?>
								<?}?>
								</td>
								<td><?=$row['wdate']?></td>
							</tr>
						<?}?>	
						</tbody>
						</table>

						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>
					</div>
				</div>
				<!-- 오퍼내역 종료 -->
			
			<?if(mysqli_num_rows($result_last) > 0){
				$row_last = mysqli_fetch_array($result_last);
				?>
				<!-- 최종금액 시작 -->
				<div class="write">
					<table>
							<caption>콘텐츠 정보</caption>
							<colgroup>
								<col style="width:15%">
								<col style="width:35%">
								<col style="width:15%">
								<col style="width:35%">
							</colgroup>
							<tr>
								<th scope="row">최종금액</th>
								<td colspan="3" style="background-color:#ffffff;">
								<?if($row_last['sale_price_won']){?>
									₩ <?=number_format($row_last['sale_price_won'])?>
								<?}?>
								<?if($row_last['sale_price_usd']){?>
									 &nbsp; USD $ <?=$row_last['sale_price_usd']?>
								<?}?>	
								</td>
							</tr>
						</table>
					</div>	
				<!-- 기본정보 종료 -->
			<?}?>
			
				<div class="write" style="margin-top:-20px;">
					<div class="write_btn align_r">
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