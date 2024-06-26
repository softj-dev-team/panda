<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?
$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
$type = trim(sqlfilter($_REQUEST['type']));

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 비슷회원
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 게시 시작일
$s_level = sqlfilter($_REQUEST['s_level']); // 게시 종료일
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order.'&member_idx='.$member_idx;

$where = " and is_del = 'N'";

if(!$pageNo){
	$pageNo = 1;
}

if(!$s_order){
	$s_order = 1;
}

if($s_sect1){
	$where .= " and idx in (select temple_info_idx from temple_info_add where 1 and tag_value_1 = '".$s_sect1."' and cate_type='hast')";
}
if($s_sect2){
	//$where .= " and view_ok = '".$s_sect2."'";
	$where .= " and temple_layout = '".$s_sect2."'";
}
if($v_sect){
	$where .= " and member_idx = '".$v_sect."'";
}

if ($field && $keyword){
	$where .= " and ".$field." like '%".$keyword."%'";
}

$query_cnt = "select idx from temple_info where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$pageScale = 20; // 페이지당 20 개씩 
//$pageScale = $num;
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by align desc "; 

$query = "select *,(select user_name from member_info where 1 and idx=temple_info.member_idx) as user_name,(select user_id from member_info where 1 and idx=temple_info.member_idx) as user_id,(select file_chg from board_file where 1 and board_tbname='temple_info' and board_code='photo' and board_idx=temple_info.idx order by idx asc limit 0,1) as file_chg from temple_info where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>

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

	function cate_sel_1(z,level){
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="/pro_inc/cate_select.php?cate_code1="+tmp+"&fm=s_mem&fname=s_sect2&cate_level="+level+"";
	}

</script>

<body>
		<!-- content 시작 -->
		<div class="content" style="position:relative; padding:0 10px 0 10px;">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>지점회원 관리</li>
						<li>지점회원 사찰관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>지점회원 사찰관리 신청</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="main_product.php">
						<input type="hidden" name="member_idx" value="<?=$member_idx?>"/>
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
							<th scope="row">키워드 검색</th>
							<td colspan="2">
							<select name="s_sect1" size="1" style="vertical-align:middle;width:80%;">
								<option value="">선택하세요</option>
							<?
							$sub_sql = "select distinct(tag_value_1) as tag_value_1 from temple_info_add where 1 and cate_type='hast' order by align desc";
							$sub_query = mysqli_query($gconnet,$sub_sql);
							$sub_cnt = mysqli_num_rows($sub_query);

							for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
								$sub_row = mysqli_fetch_array($sub_query);
							?>
								<option value="<?=$sub_row[tag_value_1]?>" <?=$s_sect1==$sub_row[tag_value_1]?"selected":""?>><?=$sub_row[tag_value_1]?></option>
							<?}?>		
							</select>
							</td>
							<th scope="row">사찰회원</th>
							<td colspan="2">
								<select name="v_sect" size="1" style="vertical-align:middle;width:30%;" >
									<option value="">선택하세요</option>
								<?
								$sub_sql = "select idx,user_name from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and del_yn='N' and member_type in ('PAT') and member_gubun='temple' order by user_name asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
								?>
									<option value="<?=$sub_row[idx]?>" <?=$v_sect==$sub_row[idx]?"selected":""?>><?=$sub_row[user_name]?></option>
								<?}?>		
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">조건검색</th>
							<td colspan="5">
								<select name="field" size="1" style="vertical-align:middle;width:20%;">
									<option value="">검색기준</option>
									<option value="temple_title" <?=$field=="temple_title"?"selected":""?>>사찰명</option>
									<option value="addr1" <?=$field=="addr1"?"selected":""?>>주소</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:60%;" value="<?=$keyword?>">
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
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:25%;">
								<col style="width:10%;">
								<col style="width:30%;">
								<col style="width:10%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">선택</th>
									<th scope="col">이미지</th>
									<th scope="col">사찰회원</th>
									<th scope="col">사찰명</th>
									<th scope="col">레이아웃</th>
									<th scope="col">주소</th>
									<th scope="col">등록일</th>
								</tr>
							</thead>
				<tbody>
			<form action="member_temple_action.php" method="post" name="frm2" id="frm2" >
				<input type="hidden" name="type" value="<?=$type?>"/>
				<input type="hidden" name="temple_idx" value=""/>
				<input type="hidden" name="member_idx" value="<?=$member_idx?>"/>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>등록된 사찰이 없습니다.</strong></td>
					</tr>
				<? } ?>

				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				?>
					<tr>
						<td><input type="radio" name="temple_idx" value="<?=$row[idx]?>" required="yes"  message="사찰"></td>
						<td>
							<img src="<?=$_P_DIR_WEB_FILE?>temple_info/img_thumb/<?=$row['file_chg']?>" style="max-width:90%;">
						</td>
						<td><?=$row['user_name']?></td>
						<td><?=$row['temple_title']?></td>
						<td><?=get_temple_layout($row['temple_layout'])?></td>
						<td><?=$row['addr1']?></td>
						<td><?=substr($row[wdate],0,10)?></td>
					</tr>
				<?}?>	
			</form>
			</tbody>
			</table>

			<div style="text-align:right;padding-right:10px;padding-top:10px;">
				<a href="javascript:go_submit();" class="btn_blue">선택한 사찰 신청하기</a>
			</div>

			<div class="pagination mt0">
				<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
			</div>
		</div>			
	</div>
	<!-- content 종료 -->
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
 	