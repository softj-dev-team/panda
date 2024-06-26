<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
/*if(!$_AUTH_LIST){
	error_back("게시판 접근권한이 없습니다.");
	exit;
}*/

$s_cate_code = trim(sqlfilter($_REQUEST['s_cate_code'])); // 게시판 카테고리 코드
$bbs_code = trim(sqlfilter($_REQUEST['bbs_code'])); // 게시판 코드
$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // 게시판 분류
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); // 지역 시,도
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); // 지역 구,군
$s_level = sqlfilter($_REQUEST['s_level']); // 회원 계급별 검색
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별 검색

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&v_sect='.$v_sect.'&s_cate_code='.$s_cate_code.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_gender='.$s_gender.'&s_level='.$s_level;

if(!$pageNo){
	$pageNo = 1;
}

$where = " and a.is_del='N' and a.bbs_code = '".$bbs_code."'"; 

if ($v_sect){
	$where .= " and a.bbs_sect = '".$v_sect."'";
}

if($s_sect1){
	$where .= " and idx in (select board_idx from board_info_add where 1 and tag_value = '".$s_gubun."' and cate_type='request')";
	$cate_where = " and tag_value = '".$s_sect1."'";
}
if($s_sect2){
	$where .= " and a.member_idx = '".$s_sect2."' ";
}

if ($field && $keyword){
	if($field == "subtent"){
		$where .= "and (a.subject like '%".$keyword."%' or a.content like '%".$keyword."%')";
	} else {
		$where .= "and ".$field." like '%".$keyword."%'";
	}
}

$pageScale = 20; // 페이지당 20 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " ORDER BY a.ref desc, a.step asc, a.depth asc ";

$query = "select *,(select user_name from member_info where 1 and idx=a.member_idx) as user_name,(select cate_name1 from common_code where 1 and type='request' and cate_level = '1' and cate_code1=(select tag_value from board_info_add where 1 and cate_type='cate' ".$cate_where." and board_idx=a.idx order by idx desc limit 0,1)) as cate_name1,(select file_chg from board_file where 1 and board_tbname='board_content' and board_code='request' and board_idx=a.idx order by idx asc limit 0,1) as file_chg from board_content a where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo $query."<br>";
$query_cnt = "select idx from board_content a where 1=1 ".$where;

$result = mysqli_query($gconnet,$query);
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale;

$bbs_str = get_request_name($v_sect);
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_view(no){
		location.href = "board_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "board_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "board_write.php?<?=$total_param?>";
	}
	
	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}

	function cate_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		//alert(tmp);
		_fra_admin.location.href="../partner/cate_select_1.php?cate_code1="+tmp+"&fm=s_mem&fname=s_sect2";
	}
	
//-->
</SCRIPT>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/request_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>의뢰내용 관리</li>
						<li><?=$bbs_str?></li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$bbs_str?> 리스트</h3>
					<button class="btn_add" onclick="go_regist();"><span>등록하기</span></button>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="board_list.php">
						<input type="hidden" name="mode" value="ser">
						<input type="hidden" name="s_cate_code" value="<?=$s_cate_code?>"/>
						<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
						<input type="hidden" name="bbs_code" value="<?=$bbs_code?>"/>
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
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
							<th scope="row">키워드</th>
							<td colspan="2">
							<select name="s_sect1" size="1" style="vertical-align:middle;width:80%;">
								<option value="">키워드 선택</option>
							<?
							$sect3_sql = "select cate_code1,cate_name1 from common_code where 1 and type='request' and cate_level = '1' and is_del='N' order by cate_align desc";
							$sect3_result = mysqli_query($gconnet,$sect3_sql);
							for ($i=0; $i<mysqli_num_rows($sect3_result); $i++){
								$row3 = mysqli_fetch_array($sect3_result);
							?>
								<option value="<?=$row3[cate_code1]?>" <?=$row3[cate_code1]==$s_sect1?"selected":""?>><?=$row3[cate_name1]?></option>
							<?}?>
							</select>
							</td>
							<th scope="row">등록회원</th>
							<td colspan="2">
								<select name="s_sect2" size="1" style="vertical-align:middle;width:50%;" >
									<option value="">선택하세요</option>
								<?
								$sub_sql = "select idx,user_name from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and del_yn='N' and member_type in ('PAT','GEN') order by user_name asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
								?>
									<option value="<?=$sub_row[idx]?>" <?=$s_sect2==$sub_row[idx]?"selected":""?>><?=$sub_row[user_name]?></option>
								<?}?>		
								</select> 
							</td>
						</tr>
						<tr>
							<th scope="row">조건검색</th>
							<td colspan="5">
								<select name="field" size="1" style="vertical-align:middle;width:20%;">
									<option value="">검색기준</option>
									<option value="a.subject" <?=$field=="a.subject"?"selected":""?>>제목</option>
									<option value="a.content" <?=$field=="a.content"?"selected":""?>>내용</option>
									<option value="subtent" <?=$field=="subtent"?"selected":""?>>제목+내용</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:40%;" value="<?=$keyword?>">
								</td>
						</tr>
				</form>
				</table>
				<!-- 검색창 종료 -->
					<div class="align_r mt20">
						<!--<button class="btn_down">엑셀다운로드</button>-->
						<button class="btn_search" onclick="s_mem.submit();">검색</button>
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
							<!--<div class="btn_wrap">
								<select id="s_cnt_set" onchange="go_cnt_set(this)">
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
								<button class="btn_del" onclick="go_tot_del();"><span>선택삭제</span></button>
							</div>-->
						</div>
					<!-- 목록 옵션 종료 -->
			
			<table class="search_list">
				<thead>
					<tr>
						<th width="5%">번호</th>
						<th width="10%">대표이미지</th>
						<th width="10%">등록회원</th>
						<th width="10%">가용예산</th>
						<th width="10%">예상기간</th>
						<th width="25%">제목</th>
						<th width="10%">키워드</th>
						<th width="10%">조회수</th>
						<th width="10%">등록일</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>등록된 의뢰내용이 없습니다.</strong></td>
					</tr>
				<? } ?>
			<?
			for ($i=0; $i<mysqli_num_rows($result); $i++){
				$row = mysqli_fetch_array($result);

				$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				$reg_time3 = to_time(substr($row[write_time],0,10));
			?>
				<tr>
					<td><?=$listnum?></td>
					<td><a href="javascript:go_view('<?=$row[idx]?>');">
					<?if($row[file_chg]){?>
						<img src="<?=$_P_DIR_WEB_FILE?>request/img_thumb/<?=$row['file_chg']?>" style="max-width:90%;">
					<?}?>
					</a></td>
					<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row['user_name']?></a></td>
					<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=number_format($row['forecast_pay'])?> 원</a></td>
					<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=number_format($row['forecast_period'])?> 개월 미만</a></td>
					<td style="text-align:left;padding-left:10px;"><a href="javascript:go_view('<?=$row[idx]?>');">
						<?=string_cut2(stripslashes($row[subject]),40)?> <?=now_date($reg_time3)?>
					</a></td>
					<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row['cate_name1']?></a></td>
					<td><?=$row[cnt]?></td>
					<td><?=substr($row[write_time],0,10)?></td>
				</tr>
				<?}?>	
			
			</tbody>
			</table>
	
	<?// } // 카테고리로 생성된 게시판이 있다면 종료 ?>
	
			<!-- //Goods List -->
						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>

					</div>
				</div>
			</div>
		</div>
	<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>