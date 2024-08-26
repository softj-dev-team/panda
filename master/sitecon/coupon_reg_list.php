<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));

$coupon_idx = trim(sqlfilter($_REQUEST['coupon_idx']));  
$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 
################## 파라미터 조합 #####################
$total_param = 'idx='.$idx.'&s_cnt='.$s_cnt.'&s_order='.$s_order;

//print_r($_REQUEST);

if(!$pageNo){
	$pageNo = 1;
}

$where = " and c.is_del='N' and coupon_idx='".$coupon_idx."'";

if(!$s_cnt){
	$s_cnt = 10; // 기본목록 10개
}

if(!$s_order){
	$s_order = 1; 
}

$pageScale = $s_cnt;  
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

if($s_order == 1){
	$order_by = " order by c.idx desc";
} elseif($s_order == 2){
	$order_by = " order by CAST(size_m as DECIMAL(10,5)) desc";
} elseif($s_order == 3){
	$order_by = " order by CAST(b_price_1 as DECIMAL(10,5)) desc";
} 

$query = "select a.level_name,b.idx,b.user_id,b.user_name,c.coupon_sect,c.expire_date,c.wdate,c.mdate from member_level_set a INNER JOIN member_info b ON a.level_code = b.user_level INNER JOIN member_coupon c ON b.idx = c.member_idx where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo $query."<br>"; //exit;
$result = mysqli_query($gconnet,$query);

$query_cnt = "select c.idx from member_level_set a INNER JOIN member_info b ON a.level_code = b.user_level INNER JOIN member_coupon c ON b.idx = c.member_idx where 1 ".$where;
//echo $query_cnt;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
			
				<div class="list_tit" style="text-align:left;">
					<h3>쿠폰 다운받은 회원리스트</h3>
				</div>
					
				<div class="result" style="text-align:left;">
					<p class="txt">검색결과 총 <span><?=$num?></span>건</p>
					<div class="btn_wrap">
							<!--<select id="s_cnt_set" onchange="go_cnt_set(this)">
								<option value="10" <?=$s_cnt=="10"?"selected":""?>>10개보기</option>
								<option value="20" <?=$s_cnt=="20"?"selected":""?>>20개보기</option>
								<option value="30" <?=$s_cnt=="30"?"selected":""?>>30개보기</option>
								<option value="40" <?=$s_cnt=="40"?"selected":""?>>40개보기</option>
							</select>
							<select id="s_order_set" onchange="go_order_set(this)">
								<option value="1" <?=$s_order=="1"?"selected":""?>>회원가입일 최신순</option>
								<option value="2" <?=$s_order=="2"?"selected":""?>>회원가입일 오래된순</option>
								<option value="3" <?=$s_order=="3"?"selected":""?>>회원명 올림차순</option>
								<option value="4" <?=$s_order=="4"?"selected":""?>>회원명 내림차순</option>
							</select>
							<button class="btn_del" onclick="go_tot_del();"><span>선택삭제</span></button>-->
					  </div>
				   </div>

					<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:20%;">
								<col style="width:15%;">
								<col style="width:15%">
								<col style="width:15%">
								<col style="width:15%;">
								<col style="width:15%;">
							</colgroup>
							<thead>
								<tr>
									<th>번호</th>
									<th>이메일</th>
									<th>성 명</th>
									<th>쿠폰상태</th>
									<th>수령일시</th>
									<th>쿠폰만료일</th>
									<th>사용일시</th>
								</tr>
							</thead>
							<tbody>
					<? if($num==0) { ?>
						<tr>
							<td colspan="10" height="40">데이터가 없습니다.</strong></td>
						</tr>
					<? } ?>
					<?
						for ($i=0; $i<mysqli_num_rows($result); $i++){
							$row = mysqli_fetch_array($result);
							$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

							if($row['coupon_sect'] == "A"){
								$coupon_sect = "<font style='color:blue;'>수령</font>";
							} elseif($row['coupon_sect'] == "M"){
								$coupon_sect = "<font style='color:red;'>사용</font>";
							} elseif($row['coupon_sect'] == "C"){
								$coupon_sect = "<font style='color:gray;'>기간만료</font>";
							}
					?>
						<tr>
							<td><?=$listnum?></td>
							<td><?=$row['user_id']?></td>
							<td><?=$row['user_name']?></td>
							<td><?=$coupon_sect?></td>
							<td><?=$row['wdate']?></td>
							<td><?=$row['expire_date']?></td>
							<td><?=$row['mdate']?></td>
						</tr>
					<?}?>	
						</tbody>
						</table>

						<div class="pagination mt0">
						<?
							$target_link = "coupon_reg_list.php";
							$target_id = "coupon_reg_area";
							$target_param = $total_param;
							include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging_ajax.php";	
						?>
						</div>
