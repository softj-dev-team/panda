<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));

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
	$field = "product_title";
	$keyword = trim(sqlfilter($_REQUEST['keyword']));
	
	$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
	$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 

	$total_param = "bmenu=".$bmenu."&smenu=".$smenu."&date_s=".$date_s."&date_e=".$date_e."&s_sect1=".$s_sect1."&s_sect2=".$s_sect2."&s_protype=".urlencode($s_protype_arr)."&s_salemtd=".$s_salemtd."&s_salests=".$s_salests."&keyword=".$keyword."&s_cnt=".$s_cnt."&s_order=".$s_order;

	$where = " and is_del='N' and idx not in (select target_idx from main_select_info where 1 and type='topprod' and is_del='N') and idx in (select product_idx from product_info_sale where 1 and is_del='N' and public_ok='Y' and member_idx=product_info.member_idx)";
	
	if($date_s){
		$where .= " and wdate >= '".$date_s."'";
	}
	if($date_e){
		$where .= " and wdate <= '".$date_e."'";
	}
	
	if($s_sect1){
		$where .= " and cate_code1 = '".$s_sect1."'";
	}
	if($s_sect2){
		$where .= " and cate_code2 = '".$s_sect2."'";
	}
	
	if(!empty($s_protype)){ // 작품유형 시작 
		$where .= " AND (";
		for($si=0; $si<sizeof($s_protype); $si++){
			/*if($si == sizeof($s_amark)-1){
				$where .= " JSON_CONTAINS(product_type,'\"".$s_protype[$si]."\"','$') >= 1";
			} else {
				$where .= " JSON_CONTAINS(product_type,'\"".$s_protype[$si]."\"','$') >= 1 or";
			}*/
			if($si == sizeof($s_protype)-1){
				$where .= " product_type = '".$s_protype[$si]."'";
			} else {
				$where .= " product_type = '".$s_protype[$si]."' or";
			}
		}
		$where .= ")";
	} // 작품유형 종료 

	if($s_salemtd){
		$where .= " and idx in (select product_idx from product_info_sale where 1 and sale_method = '".$s_salemtd."' and is_del='N')";
	}
	if($s_salests){ // 판매상태 시작
		$where .= " and idx in (select product_idx from product_info_sale where 1 and resale_yn = '".$s_salests."' and is_del='N')";
	} // 판매상태 종료
	if ($field && $keyword){
		$where .= " and ".$field." like '%".$keyword."%'";
	}

	if(!$pageNo){
		$pageNo = 1;
	}
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
		$order_by = " order by align desc";
	}

	$query = "select *,(select cate_name1 from common_code where 1 and del_ok='N' and type='menu' and cate_level='1' and cate_code1=product_info.cate_code1) as cate_name1,(select cate_name2 from common_code where 1 and del_ok='N' and type='menu' and cate_level='2' and cate_code2=product_info.cate_code2) as cate_name2,(select sale_method from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_method,(select resale_yn from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as resale_yn,(select sale_auth_yn from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_auth_yn,(select sale_price from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_price,(select sale_cnt from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_cnt,(select sale_ok from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_ok,(select sale_cancel_memo from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_cancel_memo,(select user_nick from member_info where 1 and del_yn='N' and idx=product_info.member_idx) as user_nick,(select email from member_info where 1 and del_yn='N' and idx=product_info.member_idx) as user_email from product_info where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
	//echo $query;
	$result = mysqli_query($gconnet,$query);

	$query_cnt = "select idx from product_info where 1 ".$where;
	$result_cnt = mysqli_query($gconnet,$query_cnt);
	$num = mysqli_num_rows($result_cnt);

	$iTotalSubCnt = $num;
	$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
<!-- content -->
<body>
		<!-- content 시작 -->
		<div class="content" style="position:relative; padding:0 10px 0 10px;">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>메인 관리</li>
						<li>관리자 선정작품</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>관리자 선정작 선택</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>">
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
						<input type="hidden" name="s_cnt" id="s_cnt" value="<?=$s_cnt?>"/>
						<input type="hidden" name="s_order" id="s_order" value="<?=$s_order?>"/>
						<caption>검색</caption>
						<colgroup>
							<col style="width:20%;">
							<col style="width:14%;">
							<col style="width:13%;">
							<col style="width:20%;">
							<col style="width:13%;">
							<col style="width:20%;">
						</colgroup>
						<tr>
							<th scope="row">기간</th>
							<td colspan="2">
								<input type="text" autocomplete="off" readonly name="date_s" id="date_s" style="width:45%;" class="datepicker" value="<?=$date_s?>"> ~ <input type="text" autocomplete="off" readonly name="date_e" id="date_e" style="width:45%;" class="datepicker" value="<?=$date_e?>">
							</td>
							<th scope="row">카테고리</th>
							<td colspan="2">
								<select name="s_sect1" id="s_sect1" style="vertical-align:middle;width:45%;" onchange="product_menu_sel_1(this)">
									<option value="">대분류</option>
								<?
								$sect1_sql = "select cate_code1,cate_name1 from common_code where 1 and is_del='N' and del_ok='N' and type='menu' and cate_level='1' order by cate_align desc";
								$sect1_result = mysqli_query($gconnet,$sect1_sql);
									for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
										$row1 = mysqli_fetch_array($sect1_result);
								?>
									<option value="<?=$row1['cate_code1']?>" <?=$s_sect1==$row1['cate_code1']?"selected":""?>><?=$row1['cate_name1']?></option>
								<?}?>
								</select>
								&nbsp;
								<select name="s_sect2" id="s_sect2" style="vertical-align:middle;width:45%;">
									<option value="">중분류</option>
								<?
								$sect1_sql = "select cate_code2,cate_name2 from common_code where 1 and is_del='N' and del_ok='N' and type='menu' and cate_level='2' and cate_code1='".$s_sect1."' order by cate_align desc";
								$sect1_result = mysqli_query($gconnet,$sect1_sql);
									for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
										$row1 = mysqli_fetch_array($sect1_result);
								?>
									<option value="<?=$row1['cate_code2']?>" <?=$s_sect2==$row1['cate_code2']?"selected":""?>><?=$row1['cate_name2']?></option>
								<?}?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">작품유형</th>
							<td colspan="5">
							<? foreach ($arr_product_type as $key=>$val) {
									if(in_array($key, $s_protype)){
										$check = "checked";
									} else {
										$check = "";
									}
								?>
								<input type="checkbox" id="s_protype_<?=$key?>" name="s_protype[]" <?=$check?> value="<?=$key?>"/> <?=$val?> &nbsp; 
							<?}?>
							</td>
						</tr>
						<tr>
							<th scope="row">판매방식</th>
							<td colspan="2">
							<? foreach ($arr_sale_method as $key=>$val) {
									if($s_salemtd == $key){
										$check = "checked";
									} else {
										$check = "";
									}
								?>
								<input type="radio" id="s_salemtd_<?=$key?>" name="s_salemtd" <?=$check?> value="<?=$key?>"/> <?=$val?> &nbsp; 
							<?}?>
							</td>
							<th scope="row">재판매 방식</th>
							<td colspan="2">
								<input type="radio" id="s_salests_1" name="s_salests" value="Y" <?=$s_salests=="Y"?"checked":""?>/> 재판매 &nbsp; 
								<input type="radio" id="s_salests_2" name="s_salests" value="N" <?=$s_salests=="N"?"checked":""?>/> 해당없음
							</td>
						</tr>
						<tr>
							<th scope="row">작품명</th>
							<td colspan="5">
								<input type="text" title="검색" name="keyword" id="keyword" style="width:30%"  value="<?=$keyword?>">
							</td>
						</tr>
				</form>
				</table>
				<!-- 검색창 종료 -->

					<div class="align_r mt20">
						<button class="btn_search" onclick="s_mem.submit();">검색</button>
					</div>
					<ul class="list_tab" style="height:20px;">
					
					</ul>
					<div class="search_wrap">
					<!-- 목록 옵션 시작 -->
						<div class="result">
							<p class="txt">검색결과 총 <span><?=$num?></span>건</p>
							<div class="btn_wrap">
								
							</div>
						</div>
					<!-- 목록 옵션 종료 -->
				<form action="main_pro_top_action.php" method="post" name="frm2" id="frm2" target="_fra_admin">
					<input type="hidden" name="type" id="type" value="topprod" />
					<table class="search_list">
							<colgroup>
								<col style="width:5%;">
								<col style="width:10%;">
								<col style="width:16%">
								<col style="width:17%;">
								<col style="width:8%;">
								<col style="width:8%;">
								<col style="width:6%;">
								<col style="width:9%;">
								<col style="width:4%;">
								<col style="width:8%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">선택</th>
									<th scope="col">닉네임 (이메일)</th>
									<th scope="col">작품명</th>
									<th scope="col">카테고리</th>
									<th scope="col">작품유형</th>
									<th scope="col">등록/수정일</th>
									<th scope="col">판매방식</th>
									<th scope="col">판매권리</th>
									<th scope="col">재판매</th>
									<th scope="col">판매가</th>
								</tr>
							</thead>
							<tbody>
							<? if($num==0) { ?>
								<tr>
									<td colspan="10" height="40"><strong>등록된 작품이 없습니다.</strong></td>
								</tr>
							<? } ?>

							<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);
								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
							?>
								<tr>
									<td><input type="radio" name="product_idx" id="product_idx_<?=$row['idx']?>" value="<?=$row['idx']?>" required="yes" message="작품"></td>
									<td><?=$row['user_nick']?> (<?=$row['user_email']?>)</td>
									<td><?=$row['product_title']?></td>
									<td>
										<?=$row['cate_name1']?><?if($row['cate_name2']){?> > <?=$row['cate_name2']?> <?}?>
									</td>
									<td><?=$arr_product_type[$row['product_type']]?></td>
									<td><?=substr($row['mdate'],0,10)?></td>
									<td><?=$arr_sale_method[$row['sale_method']]?></td>
									<td>소유권<?if($row['sale_auth_yn'] == "Y"){?>,저작권<?}?></td>
									<td><?if($row['resale_yn'] == "Y"){?>O<?}?></td>
									<td>$<?=$row['sale_price']?></td>
								</tr>
							<?}?>	
						</tbody>
						</table>
					
					<div style="text-align:right;padding-right:10px;padding-top:10px;">
						<!--노출기간 : <input type="text" autocomplete="off" readonly name="sdate" id="sdate" style="width:10%;" class="datepicker" required="yes" message="노출시작일"> ~ <input type="text" autocomplete="off" readonly name="edate" id="edate" style="width:10%;" class="datepicker" required="yes" message="노출종료일">-->
						<a href="javascript:go_submit();" class="btn_blue">선택한 상품으로 설정</a>
					</div>
					</form>

					<div class="pagination mt0">
						<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
					</div>
			</div>			
	</div>
	<!-- content 종료 -->

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

	var check  = 0;                                                                           
	function CheckAll(){                
		var boolchk;                                                                          
		var chk = document.getElementsByName("product_idx[]")    
		if(check){ check=0; boolchk = false; }else{ check=1; boolchk = true; }    
		for(i=0; i<chk.length;i++){                                                                    
			chk[i].checked = boolchk;                                                          
		}
	}

	function product_menu_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="/pro_inc/product_menu_select_1.php?cate_code1="+tmp+"&fm=s_mem&fname=s_sect2";
	}

	function go_submit() {
		var check = chkFrm('frm2');
		if(check) {
			frm2.submit();
		} else {
			false;
		}
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>

 	