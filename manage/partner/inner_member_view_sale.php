<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
	/*echo "<xmp>";
		print_r($_REQUEST);
	echo "</xmp>";*/

	/*echo "<xmp>";
		print_r($_FILES);
	echo "</xmp>";*/
	//exit;

	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));

	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$field = "product_title";
	
	$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // 일반, 경매
	$date_s = trim(sqlfilter($_REQUEST['date_s'])); 
	$date_e = trim(sqlfilter($_REQUEST['date_e']));
	$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
	$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 

	if($_POST['s_protype']){ 
		$s_protype = $_POST['s_protype'];
		for($si=0; $si<sizeof($s_protype); $si++){
			if($si == sizeof($s_protype)-1){
				$s_protype_arr .= $s_protype[$si];
			} else {
				$s_protype_arr .= $s_protype[$si].",";
			}
		}
	} else { 
		if($_GET['s_protype']){
			$s_protype_arr = urldecode($_GET['s_protype']);
			$s_protype = explode(",",$s_protype_arr);
		} 
	}

	$s_salemtd = trim(sqlfilter($_REQUEST['s_salemtd'])); 
	$s_salests = trim(sqlfilter($_REQUEST['s_salests']));
	$keyword = trim(sqlfilter($_REQUEST['keyword']));
	
	$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
	$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 

	$target_param = "member_idx=".$member_idx."&v_sect=".$v_sect."&date_s=".$date_s."&date_e=".$date_e."&s_sect1=".$s_sect1."&s_sect2=".$s_sect2."&s_protype=".urlencode($s_protype_arr)."&s_salemtd=".$s_salemtd."&s_salests=".$s_salests."&keyword=".$keyword."&s_cnt=".$s_cnt."&s_order=".$s_order;

	if(!$v_sect){
		$v_sect = "general";
	}

	$where = " and is_del='N' and member_idx_sale='".$member_idx."'";
	
	if($v_sect == "general"){
		$where .= " and sale_method='1'";
	} elseif($v_sect == "auction"){
		$where .= " and sale_method='2'";
	}

	if($date_s){ 
		$where .= " and substring(wdate,1,10) >= '".$date_s."'";
	}
	if($date_e){ 
		$where .= " and substring(wdate,1,10) <= '".$date_e."'";
	}
	if($s_salests){
		$where .= " and sale_status = '".$s_salests."'";
	}
	if ($field && $keyword){
		$where .= " and product_idx in (select idx from product_info where 1 and is_del='N' and ".$field." like '%".$keyword."%')";
	}

	if(!$pageNo){
		$pageNo = 1;
	}

	$s_cnt = 5; // 기본목록 5개

	$pageScale = $s_cnt;  
	$start = ($pageNo-1)*$pageScale;

	$StarRowNum = (($pageNo-1) * $pageScale);
	$EndRowNum = $pageScale;

	$order_by = " order by idx desc";

	$query = "select *,(select product_title from product_info where 1 and is_del='N' and idx=product_sale_history.product_idx) as product_title,(select user_nick from member_info where 1 and del_yn='N' and idx=product_sale_history.member_idx_buy) as user_buy,(select orderstat from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as orderstat,(select payment_date from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as payment_date,(select pay_sect_1 from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as pay_sect_1,(select price_total_usd from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as price_total_usd,(select price_total_won from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as price_total_won,(select cancel_date from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as cancel_date from product_sale_history where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
	//echo "query = ".$query."<br>";
	$result = mysqli_query($gconnet,$query);

	$query_cnt = "select idx from product_sale_history where 1 ".$where;
	$result_cnt = mysqli_query($gconnet,$query_cnt);
	$num = mysqli_num_rows($result_cnt);

	$iTotalSubCnt = $num;
	$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
	
	<div class="list_tit" style="margin-top:10px;">
		<h3>판매이력</h3>
	</div>

	<ul class="list_tab" style="margin-top:10px;margin-bottom:10px;padding-left:10px;">
		<li id="member_view_sale_1" class="list_tab_tab_tab<?if($v_sect == "general"){?> on<?}?>">
			<a href="javascript:member_tab_sale('general');">일반 판매이력</a>
		</li>
		<li id="member_view_sale_2" class="list_tab_tab_tab<?if($v_sect == "auction"){?> on<?}?>">
			<a href="javascript:member_tab_sale('auction');">경매 판매이력</a>
		</li>
	</ul>
	
	<form name="inner_s_sale" id="inner_s_sale">
	<input type="hidden" name="s_order" id="s_order" value="<?=$s_order?>"/>
	<input type="hidden" name="member_idx" id="member_idx" value="<?=$member_idx?>"/>
	<input type="hidden" name="v_sect" id="v_sect" value="<?=$v_sect?>"/>
	<div class="list">
		<!-- 검색창 시작 -->
		<table class="search">
				<caption>검색</caption>
				<colgroup>
					<col style="width:14%;">
					<col style="width:20%;">
					<col style="width:13%;">
					<col style="width:20%;">
					<col style="width:13%;">
					<col style="width:20%;">
				</colgroup>
				<tr>
					<th scope="row">조회기간</th>
					<td colspan="5">
						<input type="text" autocomplete="off" readonly name="date_s" id="date_s" style="width:10%;" class="datepicker" value="<?=$date_s?>"> ~ <input type="text" autocomplete="off" readonly name="date_e" id="date_e" style="width:10%;" class="datepicker" value="<?=$date_e?>">
					</td>
				</tr>
			<?if($v_sect == "general"){?>
				<tr>
					<th scope="row">판매상태</th>
					<td colspan="5">
					<? foreach ($arr_sale_status2_gen as $key=>$val) {
							if($s_salests == $key){
								$check = "checked";
							} else {
								$check = "";
							}
						?>
						<input type="radio" id="s_salests_<?=$key?>" name="s_salests" <?=$check?> value="<?=$key?>"/> <?=$val?> &nbsp; 
					<?}?>
					</td>
				</tr>
			<?}elseif($v_sect == "auction"){?>
				<tr>
					<th scope="row">경매단계</th>
					<td colspan="5">
					<? foreach ($arr_sale_status2_auc as $key=>$val) {
							if($s_salests == $key){
								$check = "checked";
							} else {
								$check = "";
							}
						?>
						<input type="radio" id="s_salests_<?=$key?>" name="s_salests" <?=$check?> value="<?=$key?>"/> <?=$val?> &nbsp; 
					<?}?>
					</td>
				</tr>
			<?}?>
				<tr>
					<th scope="row">작품명</th>
					<td colspan="5">
						<input type="text" title="검색" name="keyword" id="keyword" style="width:30%"  value="<?=$keyword?>">
					</td>
				</tr>
		</table>
		<!-- 검색창 종료 -->

		<div class="align_r mt20">
			<button class="btn_search" type="button" onclick="sch_mem_view_sale();">검색</button>
		</div>

		<ul class="list_tab" style="height:20px;">
		</ul>

		<div class="search_wrap">
			<!-- 목록 옵션 시작 -->
			<div class="result">
				<p class="txt">일반판매 총 <span><?=number_format($num)?></span>건</p>
				<div class="btn_wrap">
					<select id="s_cnt" name="s_cnt" onchange="sch_mem_view_sale();">
						<option value="10" <?=$s_cnt=="10"?"selected":""?>>10개보기</option>
						<option value="30" <?=$s_cnt=="30"?"selected":""?>>30개보기</option>
						<option value="50" <?=$s_cnt=="50"?"selected":""?>>50개보기</option>
						<option value="100" <?=$s_cnt=="100"?"selected":""?>>100개보기</option>
					</select>
				</div>
			</div>
			<!-- 목록 옵션 종료 -->
	</form>
		 
			<table class="search_list">
				<caption>검색결과</caption>
				<colgroup>
					<col style="width:5%;">
					<col style="width:20%;">
					<col style="width:10%">
					<col style="width:10%;">
					<col style="width:10%;">
					<col style="width:10%;">
					<col style="width:10%;">
					<col style="width:10%;">
					<col style="width:15%;">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">No</th>
						<th scope="col">작품명</th>
						<th scope="col">구매자</th>
						<th scope="col"><?if($v_sect == "general"){?>판매단계<?}elseif($v_sect == "auction"){?>경매단계<?}?></th>
						<th scope="col">결제일</th>
						<th scope="col">결제수단</th>
						<th scope="col">결제금액</th>
						<th scope="col">결제상태</th>
						<th scope="col">취소일</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40">판매이력이 없습니다.</strong></td>
					</tr>
				<? } ?>
				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				?>
					<tr>
						<td><?=$listnum?></td>
						<td><a href="javascript:go_sale_view('<?=$row['idx']?>');"><?=$row['product_title']?></a></td>
						<td><a href="javascript:go_sale_view('<?=$row['idx']?>');"><?=$row['user_buy']?></a></td>
						<td><a href="javascript:go_sale_view('<?=$row['idx']?>');">
						<?if($v_sect == "general"){?>
							<?=$arr_sale_status2_gen[$row['sale_status']]?>
						<?}elseif($v_sect == "auction"){?>
							<?=$arr_sale_status2_auc[$row['sale_status']]?>
						<?}?></a></td>
						<td><a href="javascript:go_sale_view('<?=$row['idx']?>');"><?=substr($row['payment_date'],0,10)?></a></td>
						<td><a href="javascript:go_sale_view('<?=$row['idx']?>');"><?=get_payment_method($row['pay_sect_1'])?></a></td>
						<td><a href="javascript:go_sale_view('<?=$row['idx']?>');">
						<?if($row['price_total_won']){?>
							₩ <?=number_format($row['price_total_won'])?>
						<?}?>
						<?if($row['price_total_usd']){?>
							 &nbsp; USD $ <?=$row['price_total_usd']?>
						<?}?>
						</a></td>
						<td><a href="javascript:go_sale_view('<?=$row['idx']?>');"><?=get_order_status($row['orderstat'])?></a></td>
						<td><a href="javascript:go_sale_view('<?=$row['idx']?>');"><?=substr($row['cancel_date'],0,10)?></a></td>
					</tr>
				<?}?>
			</table>

			<div class="pagination mt0">
			<?
				$target_link = "inner_member_view_sale.php";
				$target_id = "area_member_info";
				include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging_ajax.php";	
			?>
			</div>

		</div>
	</div>

<script>
	$(function() {
		$(".datepicker").datepicker({
			changeYear:true,
			changeMonth:true,
			minDate: '-90y',
			yearRange: 'c-90:c',
			dateFormat:'yy-mm-dd',
			showMonthAfterYear:true,
			constrainInput: true,
			dayNamesMin: ['일','월', '화', '수', '목', '금', '토' ],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
		});
	});

	function go_sale_view(idx){
		//location.href = 
		<?if($v_sect == "general"){?>
			window.open("popup_sale_offer_view.php?idx="+idx+"","saleview", "top=100,left=100,scrollbars=yes,resizable=no,width=1080,height=600");
		<?}elseif($v_sect == "auction"){?>
			window.open("popup_sale_auction_view.php?idx="+idx+"","saleview", "top=100,left=100,scrollbars=yes,resizable=no,width=1080,height=600");
		<?}?>
	}
</script>