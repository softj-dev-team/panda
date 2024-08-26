<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
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
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order;

$member_sect_str = "지점";

$where = " and temple_idx in (select idx from temple_info where 1 and is_del = 'N')";

if($s_gender){
	$where .= " and apply_ok = '".$s_gender."'";
}
if($v_sect){
	$where .= " and member_shop_idx = '".$v_sect."'";
}

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


if ($field && $keyword){
	$where .= " and temple_idx in (select idx from temple_info where 1 and ".$field." like '%".$keyword."%')";
}

$query_cnt = "select idx from member_temple_add where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$pageScale = 20; // 페이지당 20 개씩 
//$pageScale = $num;
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc "; 

$query = "select *,(select temple_title from temple_info where 1 and idx=member_temple_add.temple_idx) as temple_title,(select user_name from member_info where 1 and idx=member_temple_add.member_shop_idx) as user_name,(select file_chg from board_file where 1 and board_tbname='temple_info' and board_code='photo' and board_idx=member_temple_add.temple_idx order by idx asc limit 0,1) as file_chg from member_temple_add where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/partner_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>지점회원 관리</li>
						<li>지점회원 관리사찰</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>지점회원별 관리사찰 리스트</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" method="post" action="temple_list.php">
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
						<input type="hidden" name="s_gubun" value="<?=$s_gubun?>"/>
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
							<th scope="row">지점회원</th>
							<td colspan="2">
								<select name="v_sect" size="1" style="vertical-align:middle;width:30%;" >
									<option value="">선택하세요</option>
								<?
								$sub_sql = "select idx,user_name from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and del_yn='N' and member_type in ('PAT') and member_gubun='shop' order by user_name asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
								?>
									<option value="<?=$sub_row[idx]?>" <?=$v_sect==$sub_row[idx]?"selected":""?>><?=$sub_row[user_name]?></option>
								<?}?>		
								</select>
							</td>
							<th scope="row">승인여부</th>
							<td colspan="2">
								<select name="s_gender" size="1" style="vertical-align:middle;width:30%;" >
									<option value="">선택하세요</option>
									<option value="I" <?=$s_gender=="I"?"selected":""?>>승인대기</option>
									<option value="Y" <?=$s_gender=="Y"?"selected":""?>>승인</option>
									<option value="N" <?=$s_gender=="N"?"selected":""?>>미승인</option>
								</select>
							</td>
						</tr>
						<!--<tr>
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
							<th scope="row">레이아웃</th>
							<td colspan="2">
								<select name="s_sect2" size="1" style="vertical-align:middle;" >
									<option value="">전체</option>
									<option value="1" <?=$s_sect2=="1"?"selected":""?>><?=get_temple_layout("1")?></option>
									<option value="2" <?=$s_sect2=="2"?"selected":""?>><?=get_temple_layout("2")?></option>
									<option value="3" <?=$s_sect2=="3"?"selected":""?>><?=get_temple_layout("3")?></option>
								</select>
							</td>
						</tr>-->
						<tr>
							<th scope="row">조건검색</th>
							<td colspan="5">
								<select name="field" size="1" style="vertical-align:middle;width:40%;">
									<option value="">검색기준</option>
									<option value="temple_title" <?=$field=="temple_title"?"selected":""?>>사찰명</option>
									<option value="temple_url" <?=$field=="temple_url"?"selected":""?>>홈페이지</option>
									<option value="addr1" <?=$field=="addr1"?"selected":""?>>주소</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:50%;" value="<?=$keyword?>">
							</td>
						</tr>
					</form>
					</table>
				<!-- 검색창 종료 -->

				<div class="align_r mt20">
						<button class="btn_search" onclick="s_mem.submit();">검색</button>
						<!--<button class="btn_down" onclick="order_excel_frm.submit();">엑셀다운로드</button>-->
					</div>
					<ul class="list_tab" style="height:20px;">
						<!--<li class="on"><a href="#">월단위 결과</a></li>
						<li><a href="#">월단위 결과</a></li>
						<li><a href="#">월단위 결과</a></li>-->
					</ul>
					<div class="search_wrap">
					<!-- 목록 옵션 시작 -->
						<div class="result">
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
					<!-- 목록 옵션 종료 -->

				<!-- 리스트 시작 -->
				<form method="post" name="frm" target="_fra_admin" id="frm">
					<input type="hidden" name="pageNo" value="<?=$pageNo?>" />
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>

					<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
					<input type="hidden" name="s_gubun" value="<?=$s_gubun?>"/>
					<input type="hidden" name="s_sect1" value="<?=$s_sect1?>"/>
					<input type="hidden" name="s_sect2" value="<?=$s_sect2?>"/>
					<input type="hidden" name="field" value="<?=$field?>"/>
					<input type="hidden" name="keyword" value="<?=$keyword?>"/>

					<input type="hidden" name="c_booktable" id="c_booktable_frm" value=""/>
					
					<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:20%;">
								<col style="width:15%;">
								<col style="width:10%;">
								<col style="width:15%;">
								<col style="width:15%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">번호</th>
									<th scope="col">지점회원</th>
									<th scope="col">이미지</th>
									<th scope="col">사찰명</th>
									<th scope="col">신청일시</th>
									<th scope="col">신청상태</th>
									<th scope="col">승인(거부)일시</th>
									<th scope="col">상태설정</th>
								</tr>
							</thead>
							<tbody>
						<? if($num==0) { ?>
							<tr>
								<td colspan="10" height="40">등록된 관리 사찰이 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);

								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

								if($row['apply_ok'] == "I"){
									$apply_ok = "승인대기";
								} elseif($row['apply_ok'] == "Y"){
									$apply_ok = "승인";
								} elseif($row['apply_ok'] == "N"){
									$apply_ok = "미승인";
								}
						?>
						<form name="frm_cate1_<?=$i?>" method="post" action="member_temple_request_modaction.php"  target="_fra_admin" enctype="multipart/form-data">
							<input type="hidden" name="idx" value="<?=$row[idx]?>"/>
							<tr>
								<td><?=$listnum?></td>
								<td><a href="../temple/temple_view.php?idx=<?=$row['temple_idx']?>" target="_blank"><?=$row['user_name']?></a></td>
								<td><a href="../temple/temple_view.php?idx=<?=$row['temple_idx']?>" target="_blank">
									<img src="<?=$_P_DIR_WEB_FILE?>temple_info/img_thumb/<?=$row['file_chg']?>" style="max-width:90%;">
								</a></td>
								<td><a href="../temple/temple_view.php?idx=<?=$row['temple_idx']?>" target="_blank"><?=$row['temple_title']?></a></td>
								<td><a href="../temple/temple_view.php?idx=<?=$row['temple_idx']?>" target="_blank"><?=$row['wdate']?></a></td>
								<td><a href="../temple/temple_view.php?idx=<?=$row['temple_idx']?>" target="_blank"><?=$apply_ok?></a></td>
								<td><a href="../temple/temple_view.php?idx=<?=$row['temple_idx']?>" target="_blank"><?=$row['appdate']?></a></td>
								<td>
									<select name="apply_ok" required="yes" message="승인여부" size="1" style="vertical-align:middle;" onchange="go_temple_request_mod(this.value,'<?=$row[idx]?>');">
										<option value="">선택하세요</option>
										<option value="I" <?=$row[apply_ok]=="I"?"selected":""?>>승인대기</option>
										<option value="Y" <?=$row[apply_ok]=="Y"?"selected":""?>>승인</option>
										<option value="N" <?=$row[apply_ok]=="N"?"selected":""?>>미승인</option>
									</select>
								</td>
							</tr>
						</form>
					<?}?>	
						</tbody>
						</table>
					</form>
					
						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>
					</div>
				</div>
		
	<!-- content 종료 -->
	</div>
</div>

<script>
	function go_temple_request_mod(apply_ok,tidx){
		if (apply_ok == ""){
			alert("승인상태를 선택해 주세요.");
			return;
		}
		_fra_admin.location.href="member_temple_request_modaction.php?idx="+tidx+"&apply_ok="+apply_ok+"&mode=list";
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
