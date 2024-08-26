<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_admin.php"; // 배너페이지 헤더
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/check_login.php"; // 배너 로그인여부 확인
?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$v_sect = urldecode(sqlfilter($_REQUEST['v_sect']));
$s_group = trim(sqlfilter($_REQUEST['s_group']));

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = urldecode(sqlfilter($_REQUEST['keyword']));

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));
$s_sect3 = trim(sqlfilter($_REQUEST['s_sect3']));
$s_sect4 = trim(sqlfilter($_REQUEST['s_sect4']));

################## 파라미터 조합 #####################
$total_param = 'bmenu=' . $bmenu . '&smenu=' . $smenu . '&v_sect=' . urlencode($v_sect) . '&s_group=' . $s_group . '&field=' . $field . '&keyword=' . $keyword . '&s_sect1=' . $s_sect1 . '&s_sect2=' . $s_sect2 . '&s_sect3=' . $s_sect3 . '&s_sect4=' . $s_sect4;

if (!$pageNo) {
	$pageNo = 1;
}

//$where = " and banner_type='".$s_sect1."' ";

if ($v_sect) {
	$where .= " and main_sect = '" . $v_sect . "' ";
}

if ($s_sect2) {
	$where .= " and section = '" . $s_sect2 . "' ";
}

if ($s_group) {
	$where .= " and view_ok = '" . $s_group . "' ";
}

if ($field && $keyword) {
	$where .= "and " . $field . " like '%" . $keyword . "%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo - 1) * $pageScale;

$StarRowNum = (($pageNo - 1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by align desc ";

$query = "select * from mainban_info where 1=1 " . $where . $order_by . " limit " . $StarRowNum . " , " . $EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet, $query);

$query_cnt = "select idx FROM mainban_info where 1=1 " . $where;
$result_cnt = mysqli_query($gconnet, $query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1) / $pageScale  + 1;

if ($s_sect1 == "pc") {
	$sect_title = "PC";
} elseif ($s_sect1 == "mobile") {
	$sect_title = "앱";
}
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	function view_pic(ref) {
		ref = ref;
		var window_left = (screen.width - 1024) / 2;
		var window_top = (screen.height - 768) / 2;
		window.open(ref, "pic_window", 'width=600,height=400,status=no,scrollbars=yes,top=' + window_top + ', left=' + window_left + '');
	}

	function go_view(no) {
		location.href = "mainban_view.php?idx=" + no + "&<?= $total_param ?>";
	}

	function go_list() {
		location.href = "mainban_list.php?<?= $total_param ?>";
	}

	function go_regist() {
		location.href = "mainban_write.php?<?= $total_param ?>";
	}

	function go_search() {
		if (!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!");
			exit;
		}
		frm_page.submit();
	}

	function go_align(no, mode, align) {
		_fra_admin.location.href = "align_reset.php?idx=" + no + "&mode=" + mode + "&align=" + align + "&<?= $total_param ?>&tbn=mainban_info&ret_url=/Shop/manage/sitecon/mainban_list.php";
	}

	function go_modify(frm_name) {
		var check = chkFrm(frm_name);
		if (check) {
			document.forms[frm_name].submit();
		} else {
			return;
		}
	}

	//
	-->
</SCRIPT>

<body>
	<div id="wrap" class="skin_type01">
		<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/admin_top.php"; // 상단메뉴
		?>
		<div class="sub_wrap">
			<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/sitecon_left.php"; // 좌측메뉴
			?>
			<!-- content 시작 -->
			<div class="container clearfix">
				<div class="content">
					<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
					<div class="navi">
						<ul class="clearfix">
							<li>HOME</li>
							<li>사이트 관리</li>
							<li>배너 리스트</li>
						</ul>
					</div>
					<div class="list_tit">
						<h3>배너 리스트</h3>
						<button class="btn_add" onclick="go_regist();" style="width:150px;"><span>배너 등록</span></button>
					</div>
					<div class="list">
						<!-- 검색창 시작 -->
						<table class="search">
							<form name="s_mem" id="s_mem" method="post" action="mainban_list.php">
								<input type="hidden" name="bmenu" value="<?= $bmenu ?>" />
								<input type="hidden" name="smenu" value="<?= $smenu ?>" />
								<input type="hidden" name="s_cnt" id="s_cnt" value="<?= $s_cnt ?>" />
								<input type="hidden" name="s_order" id="s_order" value="<?= $s_order ?>" />
								<caption>검색</caption>
								<colgroup>
									<col style="width:15%;">
									<col style="width:20%;">
									<col style="width:15%;">
									<col style="width:15%;">
									<col style="width:20%;">
									<col style="width:15%;">
								</colgroup>
								<tr>
									<th scope="row">사용여부</th>
									<td colspan="2">
										<select name="v_sect" size="1" style="vertical-align:middle;width:50%;">
											<option value="">배너위치구분</option>
											<option value="메인화면 상단롤링" <?= $v_sect == "메인화면 상단롤링" ? "selected" : "" ?>>메인화면 상단롤링</option>
											<option value="메인화면 중단아이콘" <?= $v_sect == "메인화면 중단아이콘" ? "selected" : "" ?>>메인화면 중단아이콘</option>
											<option value="메인화면 가격표시" <?= $v_sect == "메인화면 가격표시" ? "selected" : "" ?>>메인화면 가격표시</option>
											<option value="사이드바" <?= $v_sect == "사이드바" ? "selected" : "" ?>>사이드바</option>
										</select>

										<!--<select name="s_sect2" size="1" style="vertical-align:middle;width:20%;" >
									<option value="">배너구분</option>
									<option value="movie" <?= $s_sect2 == "movie" ? "selected" : "" ?>>동영상</option>
									<option value="img" <?= $s_sect2 == "img" ? "selected" : "" ?>>이미지</option>
								</select>-->
										<select name="s_group" size="1" style="vertical-align:middle;width:30%;">
											<option value="">사용여부</option>
											<option value="Y" <?= $s_group == "Y" ? "selected" : "" ?>>사용함</option>
											<option value="N" <?= $s_group == "N" ? "selected" : "" ?>>사용안함</option>
										</select>
									</td>
									<th scope="row">조건검색</th>
									<td colspan="2">
										<select name="field" size="1" style="vertical-align:middle;width:30%;">
											<option value="">검색기준</option>
											<option value="main_title" <?= $field == "main_title" ? "selected" : "" ?>>배너 타이틀</option>
											<option value="main_memo" <?= $field == "main_memo" ? "selected" : "" ?>>메인 텍스트</option>
										</select>
										<input type="text" title="검색" name="keyword" id="keyword" style="width:40%;" value="<?= $keyword ?>">
									</td>
								</tr>

								<!--<tr>
							<th scope="row">기관구분</th>
							<td>
								<select>
									<option>전체</option>
								</select>
							</td>
							<th scope="row">공연장소</th>
							<td>
								<select>
									<option>전체</option>
								</select>
							</td>
							<th scope="row">장르</th>
							<td>
								<select>
									<option>전체</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">공연명</th>
							<td colspan="5">
								<input type="text" title="공연명" placeholder="공연명 입력" style="width:300px">
							</td>
						</tr>-->
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
						<!-- 리스트 시작 -->
						<div class="search_wrap">
							<!-- 목록 옵션 시작 
						<div class="result">
							<p class="txt">검색결과 총 <span><?= $num ?></span>건</p>
							<div class="btn_wrap">
								<select id="s_cnt_set" onchange="go_cnt_set(this)">
									<option value="10" <?= $s_cnt == "10" ? "selected" : "" ?>>10개보기</option>
									<option value="20" <?= $s_cnt == "20" ? "selected" : "" ?>>20개보기</option>
									<option value="30" <?= $s_cnt == "30" ? "selected" : "" ?>>30개보기</option>
									<option value="40" <?= $s_cnt == "40" ? "selected" : "" ?>>40개보기</option>
								</select>
								<select id="s_order_set" onchange="go_order_set(this)">
									<option value="1" <?= $s_order == "1" ? "selected" : "" ?>>등록일 최신순</option>
									<option value="2" <?= $s_order == "2" ? "selected" : "" ?>>등록일 오래된순</option>
									<!--<option value="3" <?= $s_order == "3" ? "selected" : "" ?>>회원명 올림차순</option>
									<option value="4" <?= $s_order == "4" ? "selected" : "" ?>>회원명 내림차순</option>
								</select>
								<button class="btn_del" onclick="go_tot_del();"><span>선택삭제</span></button>
							</div>
						</div>
					<!-- 목록 옵션 종료 -->
							<p style="text-align:right;padding-right:10px;padding-bottom:5px;">
								<font style="color:red;">* 정렬순서는 숫자만 입력 가능, 높은 숫자를 우선으로 정렬됨</font>
							</p>
							<table class="search_list">
								<thead>
									<tr>
										<th width="5%">번호</th>
										<!--<th width="15%">배너구분</th>-->
										<th width="15%">배너</th>
										<th width="16%">배너 텍스트</th>
										<th width="10%">링크유형</th>
										<th width="20%">링크주소</th>
										<th width="7%">사용여부</th>
										<th width="10%">등록일시</th>
										<th width="7%">정렬순서</th>
										<th width="10%">수정</th>
									</tr>
								</thead>
								<tbody>
									<? if ($num == 0) { ?>
										<tr>
											<td colspan="10" height="40"><strong>설정된 배너가 없습니다.</strong></td>
										</tr>
									<? } ?>

									<?
									for ($i = 0; $i < mysqli_num_rows($result); $i++) {
										$row = mysqli_fetch_array($result);

										$listnum	= $iTotalSubCnt - (($pageNo - 1) * $pageScale) - $i;

										if ($row[view_ok] == "Y") {
											$view_ok = "사용함";
										} elseif ($row[view_ok] == "N") {
											$view_ok = "사용안함";
										}

										if ($row[section] == "movie") {
											$section = "동영상";
										} elseif ($row[section] == "img") {
											$section = "이미지";
										}

										if ($row[link_sect] == "P") {
											$link_sect = "상품 정보로 링크";
										} elseif ($row[link_sect] == "U") {
											$link_sect = "별도 URL 링크";
										} elseif ($row[link_sect] == "N") {
											$link_sect = "링크없음";
										}

										if ($row[pro_idx]) {
											$sql_name = "select pro_name from product_info where 1=1 and idx = '" . $row[pro_idx] . "' ";
											$query_name = mysqli_query($gconnet, $sql_name);
											$row_name = mysqli_fetch_array($query_name);
											$pro_name = $row_name[pro_name];
										}

									?>

										<form name="frm_modify_<?= $i ?>" method="post" action="mainban_modify_list_action.php" target="_fra_admin">
											<input type="hidden" name="idx" value="<?= $row[idx] ?>" />
											<input type="hidden" name="total_param" value="<?= $total_param ?>" />

											<tr>
												<td><?= $listnum ?></td>
												<!--<td ><a href="javascript:go_view('<?= $row[idx] ?>');"><? //=$row[main_sect]
																											?><?= $section ?></a></td>-->
												<td style="padding-top:10px;padding-bottom:10px;">
													<? if ($row[section] == "img") { ?>
														<? if ($row[file_c] != "" && $row[file_c] != " ") { ?>
															<a href="javascript:go_view('<?= $row[idx] ?>');"><img src="<?= $_P_DIR_WEB_FILE ?>main_banner/<?= $row[file_c] ?>" border="0" style="max-width:90%;"></a>
														<? } ?>
													<? } elseif ($row[section] == "movie") { ?>
														<iframe src="https://www.youtube.com/embed/<?= str_replace("https://youtu.be/", "", $row['link_url']) ?>" frameborder="0" width="300" height="150" allowfullscreen></iframe>
													<? } ?>
												</td>
												<td><a href="javascript:go_view('<?= $row[idx] ?>');"><?= nl2br($row['main_memo']) ?></a></td>
												<td><? if ($row[section] == "img") { ?><a href="javascript:go_view('<?= $row[idx] ?>');"><?= $link_sect ?></a><? } ?></td>
												<td style="text-align:left;padding-left:10px;">
													<? if ($row[section] == "img") { ?>
														<? if ($row[link_sect] == "P") { ?>
															<?= $pro_name ?>
														<? } elseif ($row[link_sect] == "U") { ?>
															<a href="<?= $row[link_url] ?>" target="_blank"><?= $row[link_url] ?></a>
														<? } ?>
													<? } ?>
												</td>
												<td><a href="javascript:go_view('<?= $row[idx] ?>');"><?= $view_ok ?></a></td>
												<td><a href="javascript:go_view('<?= $row[idx] ?>');"><?= substr($row[wdate], 0, 10) ?></a></td>
												<td><input type="text" style="width:40%;" name="align" value="<?= $row[align] ?>" required="yes" message="정렬순서" is_num="yes"></td>
												<td><a href="javascript:go_modify('frm_modify_<?= $i ?>');" class="btn_blue">수정</a></td>
											</tr>

										</form>

									<? } ?>

								</tbody>
							</table>

							<!-- 페이징 시작 -->
							<div class="pagination mt0">
								<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/paging.php"; ?>
							</div>
							<!-- 페이징 종료 -->
						</div>
					</div>
				</div>
			</div>
			<!-- content 종료 -->
		</div>
	</div>
	<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_bottom_admin_tail.php"; ?>
</body>

</html>