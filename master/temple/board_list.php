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

$where .= " and is_del='N' and (p_no='' or p_no is null)";

if ($v_sect){
	$where .= "and a.bbs_sect = '".$v_sect."'";
}

if($bbs_code){
	$where .= " and a.bbs_code = '".$bbs_code."' "; // 선택한 게시판에 해당하는 내용만 추출한다
} elseif($s_cate_code) {
	
	$sc_board_sql = "select board_code from board_config where 1=1 and cate1 = '".$s_cate_code."' ";
	$sc_board_query = mysqli_query($gconnet,$sc_board_sql);
	$sc_board_cnt = mysqli_num_rows($sc_board_query);

	for ($sc_board_j=0; $sc_board_j<$sc_board_cnt; $sc_board_j++){
		$sc_board_row = mysqli_fetch_array($sc_board_query);

			if($sc_board_j == $sc_board_cnt-1){
				$sc_board_where .= "'".$sc_board_row['board_code']."'";
			} else {
				$sc_board_where .= "'".$sc_board_row['board_code']."',";
			}

	}

	if($sc_board_cnt > 0){
		$where .= " and a.bbs_code in (".$sc_board_where.") and is_del='N'"; // 해당 카테고리로 만들어진 게시판이 있을 경우 카테고리에 해당하는 게시판 코드를 추출하여 in query 로 내용을 추출한다.
	} else {
		$where .= " and a.idx = '0' "; // 해당 카테고리로 만들어진 게시판이 없을 경우 글 리스트를 추출하지 않는다.
	}

}

if($s_sect1){
	$where .= " and a.sido = '".$s_sect1."' ";
}

if($s_sect2){
	$where .= " and a.gugun = '".$s_sect2."' ";
}

if($s_gender){
	$where .= " and b.gender = '".$s_gender."' ";
}

if($s_level){
	$where .= " and b.user_level = '".$s_level."' ";
}

if ($field && $keyword){
	if($field == "subtent"){
		$where .= " and (a.subject like '%".$keyword."%' or a.content like '%".$keyword."%')";
	}elseif($field == "ad_title"){
		$where .= " and product_idx in (select idx from ad_info where 1 and ad_title like '%".$keyword."%')";
	} else {
		$where .= " and ".$field." like '%".$keyword."%'";
	}
}

$pageScale = 20; // 페이지당 20 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " ORDER BY a.ref desc, a.step asc, a.depth asc ";

$query = "select *,(select ad_url from ad_info where 1 and idx=a.product_idx) as ad_url,(select ad_title from ad_info where 1 and idx=a.product_idx) as ad_title,(select tag_value from ad_info_add where 1 and cate_type='cate' and ad_info_idx=a.product_idx order by idx desc limit 0,1) as cate_code1 from board_content a where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
$query_cnt = "select a.idx from board_content a where 1 ".$where;

//echo $query;

$result = mysqli_query($gconnet,$query);
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale;

if($s_cate_code) {
	$sql_sub1 = "select cate_name1 from board_cate where cate_code1='".$s_cate_code."' and cate_level='1' ";
	$query_sub1 = mysqli_query($gconnet,$sql_sub1);
	$row_sub1 = mysqli_fetch_array($query_sub1);
	$bbs_cate_name = $row_sub1['cate_name1'];
}

if($bbs_code){
	$bbs_str = $_include_board_board_title;
} elseif($s_cate_code) {
	$bbs_str = $bbs_cate_name." 카테고리에 해당하는 ";
}
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_view(no,bcode){
		location.href = "board_view.php?idx="+no+"&bbs_code="+bcode+"&pageNo=<?=$pageNo?>&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&field=<?=$field?>&keyword=<?=$keyword?>&s_cate_code=<?=$s_cate_code?>&v_sect=<?=$v_sect?>&s_sect1=<?=$s_sect1?>&s_sect2=<?=$s_sect2?>&s_gender=<?=$s_gender?>&s_level=<?=$s_level?>";
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
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/advert_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>프로그램관리</li>
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
							<th scope="row">조건검색</th>
							<td colspan="5">
								<select name="field" size="1" style="vertical-align:middle;width:20%;">
									<option value="ad_title" <?=$field=="ad_title"?"selected":""?>>핏 제목</option>
									<!--<option value="a.subject" <?=$field=="a.subject"?"selected":""?>>제목</option>-->
									<option value="a.content" <?=$field=="a.content"?"selected":""?>>후기내용</option>
									<!--<option value="subtent" <?=$field=="subtent"?"selected":""?>>제목+내용</option>-->
								<?if($_include_board_write_auth != "AD"){?>	
									<option value="a.writer" <?=$field=="a.writer"?"selected":""?>>작성자</option>
								<?}?>
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
					<?if($bbs_code == "adreview"){?>
						<th width="10%">핏 커버사진</th>
						<th width="20%">핏 제목</th>
						<!--<th width="10%">카테고리</th>
						<th width="15%">프로그램영상</th>-->
						<th width="35%">후기내용</th>
						<th width="10%">작성자</th>
						<th width="10%">평점</th>
						<th width="10%">작성일</th>
					<?}elseif($bbs_code == "adidea"){?>
						<th width="15%">프로그램이미지</th>
						<th width="35%">프로그램명</th>
						<th width="10%">카테고리</th>
						<!--<th width="15%">프로그램영상</th>-->
						<th width="15%">작성자</th>
						<th width="15%">작성일</th>
					<?}?>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>등록된 게시물이 없습니다.</strong></td>
					</tr>
				<? } ?>
			<?
			for ($i=0; $i<mysqli_num_rows($result); $i++){
				$row = mysqli_fetch_array($result);

				$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				$reg_time3 = to_time(substr($row[write_time],0,10));
				
				$sql_sub_notice_2 = "select cate_name1 from viva_cate where 1 and set_code='memcat' and cate_level = '1' and cate_code1='".$row[cate_code1]."'";
				//echo $sql_sub_notice_2."<br>";
				$query_sub_notice_2 = mysqli_query($gconnet,$sql_sub_notice_2);
				$row_sub_notice_2 = mysqli_fetch_array($query_sub_notice_2);
				$ad_cate_name1 = $row_sub_notice_2['cate_name1'];

			?>

						<tr>
							<td><?=$listnum?></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>','<?=$row['bbs_code']?>');">
								<img src="<?=get_ad_image($row['product_idx'],"2")?>" style="max-width:90%;">
							</a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>','<?=$row['bbs_code']?>');"><?=$row[ad_title]?></a></td>
							<!--<td><a href="javascript:go_view('<?=$row[idx]?>','<?=$row['bbs_code']?>');"><?=$ad_cate_name1?></a></td>-->
							<td><a href="javascript:go_view('<?=$row[idx]?>','<?=$row['bbs_code']?>');">
								<?=string_cut2(strip_tags(stripslashes($row[content])),200)?>
							</a></td>
							<!--<td><iframe  src="https://www.youtube.com/embed/<?=str_replace("https://youtu.be/","",$row[ad_url])?>" style="width:90%;" frameborder="0" allowfullscreen></iframe></td>-->
							<td><a href="javascript:go_view('<?=$row[idx]?>','<?=$row['bbs_code']?>');"><?=$row[writer]?></a></td>
						<?if($bbs_code == "adreview"){?>	
							<td>
								<?for($k=0; $k<$row[after_point]; $k++){?>
									<img src="/img/icon/view_list_star_on.gif" width="13" height="13" />
								<?}?>
								<?
									$all_point = 5; // 만점은 5 점 
									$j_roop = $all_point-$row[after_point];
									for($j=0; $j<$j_roop; $j++){
								?>
									<img src="/img/icon/view_list_star_off.gif" width="13" height="13" />
								<?}?>
							</td>
						<?}?>
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