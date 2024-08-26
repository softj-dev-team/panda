<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage_pop.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));

	$sql_basic = "select *,(select cate_name1 from common_code where 1 and del_ok='N' and type='menu' and cate_level='1' and cate_code1=product_info.cate_code1) as cate_name1,(select cate_name2 from common_code where 1 and del_ok='N' and type='menu' and cate_level='2' and cate_code2=product_info.cate_code2) as cate_name2,(select sale_method from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_method,(select resale_yn from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as resale_yn,(select sale_auth_yn from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_auth_yn,(select sale_price from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_price,(select sale_cnt from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_cnt,(select sale_ok from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_ok,(select sale_cancel_memo from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_cancel_memo,(select date_cancel from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as date_cancel,(select email from member_info where 1 and del_yn='N' and idx=product_info.member_idx) as user_email,(select user_nick from member_info where 1 and del_yn='N' and idx=product_info.member_idx) as user_nick from product_info where 1 and idx='".$idx."' and is_del='N'";
	//echo $sql_basic; exit;
	$query_basic = mysqli_query($gconnet,$sql_basic);

	if(mysqli_num_rows($query_basic) == 0){
		error_popup("등록된 작품이 없습니다.");
	}

	$row_basic = mysqli_fetch_array($query_basic);
	
	// 판매이력 쿼리 시작 
		$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
		
		$total_param = "idx=".$idx;

		$where = " and is_del='N' and product_idx='".$idx."'";

		if(!$pageNo){
			$pageNo = 1;
		}

		$s_cnt = 5; // 기본목록 5개

		$pageScale = $s_cnt;  
		$start = ($pageNo-1)*$pageScale;

		$StarRowNum = (($pageNo-1) * $pageScale);
		$EndRowNum = $pageScale;

		$order_by = " order by idx desc";

		$query = "select *,(select user_nick from member_info where 1 and del_yn='N' and idx=product_sale_history.member_idx_sale) as user_sale,(select user_nick from member_info where 1 and del_yn='N' and idx=product_sale_history.member_idx_buy) as user_buy from product_sale_history where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
		$result = mysqli_query($gconnet,$query);

		$query_cnt = "select idx from product_sale_history where 1 ".$where;
		$result_cnt = mysqli_query($gconnet,$query_cnt);
		$num = mysqli_num_rows($result_cnt);

		$iTotalSubCnt = $num;
		$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
	// 판매이력 쿼리 종료
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
					<h3>작품 이전 이력</h3>
				</div>

				<ul class="list_tab" style="margin-top:10px;margin-bottom:10px;padding-left:10px;">
					<li>
						<a href="popup_product_view.php?idx=<?=$idx?>">콘텐츠 정보</a>
					</li>
					<li class="on">
						<a href="popup_product_sale.php?idx=<?=$idx?>">판매이력</a>
					</li>
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
								<th scope="row">작품명</th>
								<td colspan="3" style="background-color:#ffffff;">
									<span <?if($row_basic['sale_ok'] == "1"){?>style="color:blue;"<?}elseif($row_basic['sale_ok'] == "3"){?>style="color:red;"<?}?>>[<?=$arr_sale_status[$row_basic['sale_ok']]?>]</span> <?=$row_basic['product_title']?>
								</td>
							</tr>
							<tr>
								<th scope="row">등록회원</th>
								<td>
									<?=$row_basic['user_nick']?> ( <?=$row_basic['user_email']?> )
								</td>
								<th scope="row">판매가</th>
								<td>
									$<?=$row_basic['sale_price']?>
								</td>
							</tr>
							<tr>
								<th scope="row">카테고리</th>
								<td>
									<?=$row_basic['cate_name1']?><?if($row_basic['cate_name2']){?> > <?=$row_basic['cate_name2']?> <?}?>
								</td>
								<th scope="row">콘텐츠</th>
								<td>
									<?=$arr_product_type[$row_basic['product_type']]?>
								</td>
							</tr>
							<tr>
								<th scope="row">판매형태</th>
								<td>
									소유권<?if($row_basic['sale_auth_yn'] == "Y"){?>,저작권<?}?>
								</td>
								<th scope="row">재판매여부</th>
								<td>
									<?if($row_basic['resale_yn'] == "Y"){?>O<?}?>
								</td>
							</tr>
							<tr>
								<th scope="row">최초 등록일</th>
								<td>
									<?=$row_basic['wdate']?>
								</td>
								<th scope="row">수정일</th>
								<td>
									<?=$row_basic['mdate']?>
								</td>
							</tr>
						</table>
					</div>	
				<!-- 기본정보 종료 -->
				
				<!-- 거래내역 시작 -->
				<div class="list" style="margin-top:-50px;">
					<div class="search_wrap">
						<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:25%;">
								<col style="width:25%">
								<col style="width:15%;">
								<col style="width:15%;">
								<col style="width:15%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">순번</th>
									<th scope="col">판매자</th>
									<th scope="col">구매자</th>
									<th scope="col">권한</th>
									<th scope="col">금액</th>
									<th scope="col">날짜</th>
								</tr>
							</thead>
							<tbody>
							<? if($num==0) { ?>
							<tr>
								<td colspan="10" height="40">거래내역이 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);
								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
						?>
						<tr>
							<td><?=$listnum?></td>
							<td><?=$row['user_sale']?></td>
							<td><?=$row['user_buy']?></td>
							<td>소유권,<?if($row['sale_auth_yn'] == "Y"){?>저작권<?}?></td>
							<td>$ <?=$row['sale_price']?></td>
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
				<!-- 거래내역 종료 -->
				
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