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

	$target_param = "member_idx=".$member_idx."&date_s=".$date_s."&date_e=".$date_e."&s_sect1=".$s_sect1."&s_sect2=".$s_sect2."&s_protype=".urlencode($s_protype_arr)."&s_salemtd=".$s_salemtd."&s_salests=".$s_salests."&keyword=".$keyword."&s_cnt=".$s_cnt."&s_order=".$s_order;

	$where = " and is_del='N' and member_idx_sale='".$member_idx."' and order_num in (select order_num from order_member where 1 and is_del='N' and orderstat='com')";
	
	if($date_s){ 
		$where .= " and substring(date_req,1,10) >= '".$date_s."'";
	}
	if($date_e){ 
		$where .= " and substring(date_req,1,10) <= '".$date_e."'";
	}
	if($s_salests){
		$where .= " and calc_status = '".$s_salests."'";
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

	$query = "select *,(select product_title from product_info where 1 and is_del='N' and idx=product_sale_history_calc.product_idx) as product_title,(select user_nick from member_info where 1 and del_yn='N' and idx=product_sale_history_calc.member_idx_sale) as user_sale,(select orderstat from order_member where 1 and is_del='N' and order_num=product_sale_history_calc.order_num) as orderstat,(select payment_date from order_member where 1 and is_del='N' and order_num=product_sale_history_calc.order_num) as payment_date,(select pay_sect_1 from order_member where 1 and is_del='N' and order_num=product_sale_history_calc.order_num) as pay_sect_1,(select price_total_usd from order_member where 1 and is_del='N' and order_num=product_sale_history_calc.order_num) as price_total_usd,(select price_total_won from order_member where 1 and is_del='N' and order_num=product_sale_history_calc.order_num) as price_total_won,(select cancel_date from order_member where 1 and is_del='N' and order_num=product_sale_history_calc.order_num) as cancel_date,(select sale_method from product_sale_history where 1 and is_del='N' and order_num=product_sale_history_calc.order_num and product_idx=product_sale_history_calc.product_idx) as sale_method,(select idx from product_sale_history where 1 and is_del='N' and order_num=product_sale_history_calc.order_num and product_idx=product_sale_history_calc.product_idx) as sale_idx from product_sale_history_calc where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
	//echo "query = ".$query."<br>";
	$result = mysqli_query($gconnet,$query);

	$query_cnt = "select idx from product_sale_history_calc where 1 ".$where;
	$result_cnt = mysqli_query($gconnet,$query_cnt);
	$num = mysqli_num_rows($result_cnt);

	$iTotalSubCnt = $num;
	$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
	
	<div class="list_tit" style="margin-top:10px;">
		<h3>정산이력</h3>
	</div>
	
	<form name="inner_s_calc" id="inner_s_calc">
	<input type="hidden" name="s_order" id="s_order" value="<?=$s_order?>"/>
	<input type="hidden" name="member_idx" id="member_idx" value="<?=$member_idx?>"/>
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
					<th scope="row">정산요청일</th>
					<td colspan="5">
						<input type="text" autocomplete="off" readonly name="date_s" id="date_s" style="width:10%;" class="datepicker" value="<?=$date_s?>"> ~ <input type="text" autocomplete="off" readonly name="date_e" id="date_e" style="width:10%;" class="datepicker" value="<?=$date_e?>">
					</td>
				</tr>
				<tr>
					<th scope="row">정산구분</th>
					<td colspan="5">
					<? foreach ($arr_calc_status as $key=>$val) {
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
		</table>
		<!-- 검색창 종료 -->

		<div class="align_r mt20">
			<button class="btn_search" type="button" onclick="sch_mem_view_calc();">검색</button>
		</div>

		<ul class="list_tab" style="height:20px;">
		</ul>

		<div class="search_wrap">
			<!-- 목록 옵션 시작 -->
			<div class="result">
				<p class="txt">검색결과 총 <span><?=number_format($num)?></span>건</p>
				<div class="btn_wrap">
					<select id="s_cnt" name="s_cnt" onchange="sch_mem_view_calc();">
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
					<col style="width:15%;">
					<col style="width:15%">
					<col style="width:10%;">
					<col style="width:10%;">
					<col style="width:10%;">
					<col style="width:10%;">
					<col style="width:15%;">
					<col style="width:10%;">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">순번</th>
						<th scope="col">작품명</th>
						<th scope="col">결제일</th>
						<th scope="col">결제수단</th>
						<th scope="col">결제금액</th>
						<th scope="col">구매방식</th>
						<th scope="col">정산액</th>
						<th scope="col">정산요청일</th>
						<th scope="col">정산여부</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="9" height="40">등록된 정산이 없습니다.</strong></td>
					</tr>
				<? } ?>
				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				?>
					<tr>
						<td><?=$listnum?></td>
						<td><a href="javascript:go_calc_view('<?=$row['sale_idx']?>');"><?=$row['product_title']?></a></td>
						<td><a href="javascript:go_calc_view('<?=$row['sale_idx']?>');"><?=substr($row['payment_date'],0,10)?></a></td>
						<td><a href="javascript:go_calc_view('<?=$row['sale_idx']?>');"><?=get_payment_method($row['pay_sect_1'])?></a></td>
						<td><a href="javascript:go_calc_view('<?=$row['sale_idx']?>');">
						<?if($row['price_total_won']){?>
							₩ <?=number_format($row['price_total_won'])?>
						<?}?>
						<?if($row['price_total_usd']){?>
							 &nbsp; USD $ <?=$row['price_total_usd']?>
						<?}?>
						</a></td>
						<td><a href="javascript:go_calc_view('<?=$row['sale_idx']?>');"><?=$arr_sale_method[$row['sale_method']]?></a></td>
						<td><a href="javascript:go_calc_view('<?=$row['sale_idx']?>');"><?=number_format($row['mny_calc'])?></a></td>
						<td><a href="javascript:go_calc_view('<?=$row['sale_idx']?>');"><?=substr($row['date_req'],0,10)?></a></td>
						<td><a href="javascript:go_calc_view('<?=$row['sale_idx']?>');">
						<?if($row['calc_yn'] == "N"){?>
							<font style="color:#f96666;">정산요청</font>
						<?}elseif($row['calc_yn'] == "Y"){?>
							<font style="color:#2c558e;">정산완료</font>
						<?}?>
						</a></td>
					</tr>
				<?}?>
			</table>

			<div class="pagination mt0">
			<?
				$target_link = "inner_member_view_calc.php";
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

	function go_calc_view(idx){
		//location.href = 
		window.open("popup_sale_calc_view.php?idx="+idx+"","calcview", "top=100,left=100,scrollbars=yes,resizable=no,width=1080,height=600");
	}
</script>