<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
<?
$proidx = trim(sqlfilter($_REQUEST['proidx']));
$type = trim(sqlfilter($_REQUEST['type']));

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 체험주
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 게시 시작일
$s_level = sqlfilter($_REQUEST['s_level']); // 게시 종료일
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); 
################## 파라미터 조합 #####################
$total_param = 'proidx='.$proidx.'&type='.$type.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order;

$where = " and is_del = 'N' and view_ok='Y'";
$member_sect_str = "체험";

if(!$pageNo){
	$pageNo = 1;
}

if(!$s_order){
	$s_order = 1;
}

if($v_sect){
	$where .= " and member_idx = '".$v_sect."' ";
}

if($s_gubun){
	$where .= " and s_date >= '".$s_gubun."' ";
}

if($s_level){
	$where .= " and e_date <= '".$s_level."' ";
}

if($s_gender){
	$where .= " and time_yn = '".$s_gender."' ";
}

if($s_sect1){
	$where .= " and cate_code1 = '".$s_sect1."' ";
}

if($s_sect2){
	$where .= " and view_ok = '".$s_sect2."' ";
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$query_cnt = "select idx from exp_info where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$pageScale = 10; // 페이지당 20 개씩 
//$pageScale = $num;
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

if($s_order == 1){ // 책장 올림차순 
	$order_by = " order by idx desc "; 
} elseif($s_order == 2){ // 체험명 올림차순 
	$order_by = " order by exp_title asc "; 
} elseif($s_order == 3){ // 체험명 내림차순 
	$order_by = " order by exp_title desc "; 
} elseif($s_order == 4){ // 최신 등록순 
	$order_by = " order by idx desc "; 
}

$query = "select *,(select com_name from member_info where 1 and member_type = 'PAT' and idx=exp_info.member_idx) as com_name,(select cate_name1 from viva_cate where 1 and set_code='exper' and cate_level = '1' and cate_code1=exp_info.cate_code1) as cate_name1 from exp_info where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_popup.php"; // 관리자 로그인여부 확인?>

<script type="text/javascript">

	function view_pic(ref) {
			ref = ref;
			var window_left = (screen.width-1024) / 2;
			var window_top = (screen.height-768) / 2;
			window.open(ref, "pic_window", 'width=600,height=400,status=no,scrollbars=yes,top=' + window_top + ', left=' + window_left +'');
	}

function go_submit() {
		var check = chkFrm('frm2');
		if(check) {
		frm2.submit();
		} else {
			false;
		}
	}

	function cate_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		//alert(tmp);
		_fra_admin.location.href="cate_select_1.php?cate_code1="+tmp+"&fm=s_mem&fname=s_sect2";
	}

	 function cate_sel_2(z){
		var ktmp = document.s_mem.s_sect1.value; 
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="cate_select_2.php?cate_code1="+ktmp+"&cate_code2="+tmp+"&fm=s_mem&fname=s_sect3";
	}

	function cate_sel_3(z){
		var ktmp = document.s_mem.s_sect1.value; 
		var ktmp2 = document.s_mem.s_sect2.value; 
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="cate_select_3.php?cate_code1="+ktmp+"&cate_code2="+ktmp2+"&cate_code3="+tmp+"&fm=s_mem&fname=s_sect4";
	}

</script>

<!-- content -->
<body>
		<!-- content 시작 -->
		<div class="content" style="position:relative; padding:0 10px 0 10px;">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>체험관리</li>
						<li>메인화면 체험배치</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>메인에 배치할 체험선택</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="main_product.php">
						<input type="hidden" name="proidx" value="<?=$proidx?>"/>
						<input type="hidden" name="type" value="<?=$type?>"/>
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
							<th scope="row">카테고리</th>
							<td colspan="2">
							<select name="s_sect1" size="1" onchange="s_mem.submit();" style="vertical-align:middle;width:80%;">
								<option value="">카테고리 선택</option>
							<?
							$sect3_sql = "select cate_code1,cate_name1 from viva_cate where 1 and set_code='exper' and cate_level = '1' and is_del='N' order by cate_align desc";
							$sect3_result = mysqli_query($gconnet,$sect3_sql);
							for ($i=0; $i<mysqli_num_rows($sect3_result); $i++){
								$row3 = mysqli_fetch_array($sect3_result);
							?>
								<option value="<?=$row3[cate_code1]?>" <?=$row3[cate_code1]==$s_sect1?"selected":""?>><?=$row3[cate_name1]?></option>
							<?}?>
							</select>
							</td>
							<th scope="row">파트너</th>
							<td colspan="2">
								<select name="v_sect" size="1" style="vertical-align:middle;" >
									<option value="">선택하세요</option>
								<?
								$sub_sql = "select idx,com_name from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and member_type='PAT'";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
								?>
									<option value="<?=$sub_row[idx]?>" <?=$v_sect==$sub_row[idx]?"selected":""?>><?=$sub_row[com_name]?></option>
								<?}?>		
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">체험모집 기간</th>
							<td colspan="2">
								<input type="text" name="s_gubun" style="width:40%;" id="s_gubun" onClick="new CalendarFrame.Calendar(this)" value="<?=$s_gubun?>" readonly> ~ <input type="text" name="s_level" style="width:40%;" id="s_level" onClick="new CalendarFrame.Calendar(this)" value="<?=$s_level?>" readonly>
							</td>
							<th scope="row">시간설정 노출</th>
							<td colspan="2">
								<select name="s_gender" size="1" style="vertical-align:middle;" >
									<option value="">전체</option>
									<option value="Y" <?=$s_gender=="Y"?"selected":""?>>설정된 시간에만 노출</option>
									<option value="N" <?=$s_gender=="N"?"selected":""?>>시간 관계없이 노출</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">조건검색</th>
							<td colspan="5">
							<!-- <select name="s_gubun" size="1" style="vertical-align:middle;" onchange="cate_sel_1(this)"> -->
							<!--<select name="s_gubun" size="1" style="vertical-align:middle;width:10%;" >
									<option value="">지역선택</option>
								<?
								$sub_sql = "select sido from zipcode where 1 group by sido order by sido asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
							?>
								<option value="<?=$sub_row[sido]?>" <?=$s_gubun==$sub_row[sido]?"selected":""?>><?=$sub_row[sido]?></option>
							<?}?>	
								</select>-->

								<select name="field" size="1" style="vertical-align:middle;width:40%;">
									<option value="">검색기준</option>
									<option value="exp_title" <?=$field=="exp_title"?"selected":""?>>체험제목</option>
									<option value="exp_content" <?=$field=="exp_content"?"selected":""?>>체험내용</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:50%;" value="<?=$keyword?>">
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
				
					<table class="search_list">
							<colgroup>
								<col style="width:5%;">
								<col style="width:15%;">
								<col style="width:20%;">
								<col style="width:25%;">
								<col style="width:12%;">
								<col style="width:13%;">
								<col style="width:10%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">선택</th>
									<th scope="col">카테고리</th>
									<th scope="col">대표이미지</th>
									<th scope="col">체험제목</th>
									<th scope="col">체험시작일</th>
									<th scope="col">체험종료일</th>
									<th scope="col">체험수량</th>
								</tr>
							</thead>
				<tbody>
			<form action="main_pro_action.php" method="post" name="frm2" id="frm2" >
				<input type="hidden" name="type" value="<?=$type?>"/>
				<input type="hidden" name="product_idx" value=""/>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>승인된 체험이 없습니다.</strong></td>
					</tr>
				<? } ?>

				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				?>
					<tr>
						<td><input type="radio" name="product_idx" value="<?=$row[idx]?>" required="yes"  message="체험" <?if($row[idx] == $proidx){?>checked<?}?>></td>
						<td><?=$row[cate_name1]?></td>
						<td>
						<?if($row[file_chg]){?>
							<img src="<?=get_exp_image($row[idx],"file_chg","3")?>" style="max-width:90%;">
						<?}?>
						</td>
						<td><?=$row[exp_title]?></td>
						<td><?=$row[s_date]?></td>
						<td><?=$row[e_date]?></td>
						<td><?=number_format($row[set_click_cnt])?> 명</td>
					</tr>
				<?}?>	
			</form>
			</tbody>
			</table>

			<div style="text-align:right;padding-right:10px;padding-top:10px;"><a href="javascript:go_submit();" class="btn_blue">선택한 체험으로 설정</a></div>

			<div class="pagination mt0">
				<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
			</div>
		</div>			
	</div>
	<!-- content 종료 -->
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>

 	