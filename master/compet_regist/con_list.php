<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 공모전주
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 게시 시작일
$s_level = sqlfilter($_REQUEST['s_level']); // 게시 종료일
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect_chg = $_REQUEST['s_sect_chg'];
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect_chg='.$s_sect_chg.'&s_sect2='.$s_sect2.'&s_order='.$s_order;

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));

if(!$pageNo){
	$pageNo = 1;
}

$where = " and is_del='N' and estimate_date != '' and estimate_date is not null"; 

if ($field && $keyword){
	if($field == "compet_title"){
		$where .= " and compet_idx in (select idx from compet_info where 1 and ".$field." like '%".$keyword."%')";
	} elseif($field == "work_title"){
		$where .= " and regist_idx in (select idx from compet_regist_info where 1 and ".$field." like '%".$keyword."%')";
	} elseif($field == "req_name"){
		$where .= " and compet_idx in (select idx from compet_info where 1 and member_idx in (select idx from member_info where 1 and user_nick like '%".$keyword."%'))";
	} else {
		$where .= " and ".$field." like '%".$keyword."%'";
	}
}

$query_cnt = "select idx from compet_regist_price_info where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$pageScale = 10; // 페이지당 10 개씩 
//$pageScale = $num;
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by estimate_date desc"; 

$query = "select *,(select user_nick from member_info where 1 and idx=(select member_idx from compet_info where 1 and idx=compet_regist_price_info.compet_idx)) as req_name,(select member_type from member_info where 1 and idx=(select member_idx from compet_info where 1 and idx=compet_regist_price_info.compet_idx)) as req_type,(select member_idx from compet_info where 1 and idx=compet_regist_price_info.compet_idx) as req_idx,(select compet_title from compet_info where 1 and idx=compet_regist_price_info.compet_idx) as compet_title,(select work_title from compet_regist_info where 1 and idx=compet_regist_price_info.regist_idx) as work_title from compet_regist_price_info where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;

$member_sect_str = "고객후기";

?>
<script type="text/javascript">
<!--	 
	function go_view(no){
		location.href = "con_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "con_list.php?<?=$total_param?>";
	}

	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	} 
	
//-->
</script>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"].""."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"].""."/master/include/regist_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>참가작 관리</li>
						<li>고객후기 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>고객후기 리스트</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="con_list.php">
						<input type="hidden" name="mode" value="ser">
						<input type="hidden" name="site_sect" value="<?=$site_sect?>"/>
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
									<option value="">검색기준</option>
									<option value="compet_title" <?=$field=="compet_title"?"selected":""?>>공모전 명</option>
									<option value="work_title" <?=$field=="work_title"?"selected":""?>>참가작 제목</option>
									<option value="req_name" <?=$field=="req_name"?"selected":""?>>작성자</option>
									<option value="estimate_txt" <?=$field=="estimate_txt"?"selected":""?>>후기내용</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:40%;" value="<?=$keyword?>">
							</td>
						</tr>
				</form>
				</table>
				<!-- 검색창 종료 -->

			<!-- 엑셀 출력을 위한 전송 폼 시작 -->
			<form name="order_excel_frm" id="order_excel_frm" method="post" action="member_excel_list.php">
			<input type="hidden" name="mode" value="ser">
			<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
			<input type="hidden" name="s_gubun" value="<?=$s_gubun?>"/>
			<input type="hidden" name="s_sect1" value="<?=$s_sect1?>"/>
			<input type="hidden" name="s_sect2" value="<?=$s_sect2?>"/>
			<input type="hidden" name="s_level" value="<?=$s_level?>"/>
			<input type="hidden" name="field" value="<?=$field?>"/>
			<input type="hidden" name="keyword" value="<?=htmlspecialchars($keyword)?>"/>
			</form>
			<!-- 엑셀 출력을 위한 전송 폼 종료 -->

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
				
				<form method="post" name="frm" target="_fra_admin" id="frm">
					<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<input type="hidden" name="pageNo" value="<?=$pageNo?>" />
						<table class="search_list">
							<caption>검색결과</caption>
							<thead>
								<tr>
									<th width="5%">번호</th>
									<th width="15%">공모전 명</th>
									<th width="15%">참가작 제목</th>
									<th width="10%">작성자</th>
									<th width="10%">선정등수</th>
									<th width="35%">후기내용</th>
									<th width="10%">등록일</th>
								</tr>
							</thead>
							<tbody>
						<? if($num==0) { ?>
							<tr>
								<td colspan="10" height="40">등록된 평가글이 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);

								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
														
						?>
						<tr>
							<td><?=$listnum?></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[compet_title]?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[work_title]?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[req_name]?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[select_champ_depth]?> 위</a></td>
							<td style="text-align:left;padding-left:10px;">
								<a href="javascript:go_view('<?=$row[idx]?>');"><?=string_cut2($row[estimate_txt],60)?></a>
							</td>
							<td><?=substr($row[estimate_date],0,10)?></td>
						</tr>
					<?}?>	
						</tbody>
						</table>
					</form>
						<!--<div class="table_btn align_l mt20 pl20">
							<button>선택 가입승인</button>
							<button>선택 탈퇴처리</button>
						</div>-->
						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/paging.php";?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>