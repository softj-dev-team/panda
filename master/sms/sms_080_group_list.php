<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_admin.php"; // 샘플문자페이지 헤더
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/check_login.php"; // 샘플문자 로그인여부 확인
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

$where = "";

if ($v_sect) {
	$where .= " and a.group_num = '" . str_replace("-", "", $v_sect) . "' ";
}
if ($s_sect2) {
	$where .= " and a.sms_type = '" . $s_sect2 . "' ";
}
if ($s_group) {
	$where .= " and a.sms_category = '" . $s_group . "' ";
}
if ($field && $keyword) {
	$where .= "and " . $field . " like '%" . $keyword . "%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo - 1) * $pageScale;

$StarRowNum = (($pageNo - 1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";

$query = "SELECT * FROM spam_080_group a where 1=1 " . $where . $order_by . " limit " . $StarRowNum . " , " . $EndRowNum;

//echo "<br><br>쿼리 = " . $query . "<br><Br>";

$result = mysqli_query($gconnet, $query);

$query_cnt = "SELECT * FROM spam_080_group a where 1 " . $where;
$result_cnt = mysqli_query($gconnet, $query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1) / $pageScale  + 1;

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
		location.href = "sms_sample_view.php?idx=" + no + "&<?= $total_param ?>";
	}

	function go_list() {
		location.href = "sms_sample_list.php?<?= $total_param ?>";
	}

	function go_regist() {
		location.href = "sms_080_group_write.php?<?= $total_param ?>";
	}

	function go_search() {
		if (!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!");
			exit;
		}
		frm_page.submit();
	}

	function go_align(no, mode, align) {
		_fra_admin.location.href = "align_reset.php?idx=" + no + "&mode=" + mode + "&align=" + align + "&<?= $total_param ?>&tbn=sms_save&ret_url=/Shop/manage/sitecon/sms_sample_list.php";
	}

	function go_modify(frm_name) {
		var check = chkFrm(frm_name);
		if (check) {
			document.forms[frm_name].submit();
		} else {
			return;
		}
	}

	function go_tot_del() {
		var check = chkFrm('frm');
		if (check) {
			if (confirm('선택하신 발송결과를 삭제 하시겠습니까?')) {
				frm.action = "sms_080_group_delete.php";
				frm.submit();
			}
		} else {
			false;
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
			<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/sms_left.php"; // 좌측메뉴
			?>
			<!-- content 시작 -->
			<div class="container clearfix">
				<div class="content">
					<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
					<div class="navi">
						<ul class="clearfix">
							<li>HOME</li>
							<li>문자관리</li>
							<li>080 번호 관리</li>
						</ul>
					</div>
					<div class="list_tit">
						<h3>080 번호 리스트</h3>
						<button class="btn_add" onclick="go_regist();" style="width:150px;"><span>080번호 등록</span></button>
					</div>
					<div class="list">
						<!-- 검색창 시작 -->
						<table class="search">
							<form name="s_mem" id="s_mem" method="post" action="sms_080_group_list.php">
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
									<th scope="row">080번호</th>
									<td colspan="5">
										<input type="text" name="v_sect" value="<?= $v_sect ?>">
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
							<div class="result">
								<p class="txt">검색결과 총 <span><?= $num ?></span>건</p>
								<div class="btn_wrap">
									<!--<select id="s_cnt_set" onchange="go_cnt_set(this)">
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
								</select>-->
									<button class="btn_del" onclick="go_tot_del();"><span>선택삭제</span></button>
								</div>
							</div>
							<!-- 목록 옵션 종료 -->
							<form method="post" name="frm" id="frm" target="_fra_admin">
								<input type="hidden" name="pageNo" value="<?= $pageNo ?>" />
								<input type="hidden" name="total_param" value="<?= $total_param ?>" />
								<table class="search_list">
									<thead>
										<tr>
											<th class="check"><input type="checkbox" onclick="javascript:CheckAll()"></th>
											<th width="30%">080번호</th>
											<th width="30%">등록일시</th>
										</tr>
									</thead>
									<tbody>
										<?
										for ($i = 0; $i < mysqli_num_rows($result); $i++) {
											$row = mysqli_fetch_array($result);
											$listnum	= $iTotalSubCnt - (($pageNo - 1) * $pageScale) - $i;

										?>
											<tr>
												<td class="check"><input type="checkbox" name="send_idx[]" id="send_idx[]" value="<?= $row["idx"] ?>" required="yes" message="전송결과"></td>
												<td><?= $row['group_num'] ?></td>
												<td><?= $row['wdate'] ?></td>
											</tr>
										<? } ?>

									</tbody>
								</table>
							</form>
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