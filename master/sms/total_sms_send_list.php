<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_admin.php"; // 전송내역페이지 헤더
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/check_login.php"; // 전송내역 로그인여부 확인
?>
<?
/*
error_reporting(E_ALL);
ini_set("display_errors", 1);*/
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

$save_idx = trim(sqlfilter($_REQUEST['save_idx']));

$s_cate = trim(sqlfilter($_REQUEST['s_cate']));
if ($s_cate) {
	$s_sect3 = "";
	$s_sect4 = "";
}

################## 파라미터 조합 #####################
$total_param = 'bmenu=' . $bmenu . '&smenu=' . $smenu . '&v_sect=' . urlencode($v_sect) . '&s_group=' . $s_group . '&field=' . $field . '&keyword=' . $keyword . '&s_sect1=' . $s_sect1 . '&s_sect2=' . $s_sect2 . '&s_sect3=' . $s_sect3 . '&s_sect4=' . $s_sect4 . '&s_cate=' . $s_cate . '&save_idx=' . $save_idx;

if (!$pageNo) {
	$pageNo = 1;
}

$where = " and transmit_type='send' and sms_save_cell.is_del='N' and (case when reserv_yn = 'Y' then CONCAT(reserv_date,' ',reserv_time,':',reserv_minute) <= '" . date("Y-m-d H:i") . "' else sms_save_cell.idx > 0 end)";

if ($s_cate == "d") { // 당일 
	$where .= " and substring(sms_save_cell.wdate,1,10) = '" . date("Y-m-d") . "' ";
	$s_sect3 = date("Y-m-d");
	$s_sect4 = date("Y-m-d");
} elseif ($s_cate == "1") { // 하루전 
	$s_date = date("Y-m-d");
	$e_date = date("Y-m-d", strtotime("-1 day", strtotime($s_date)));
	$where .= " and substring(sms_save_cell.wdate,1,10) >= '" . $e_date . "' ";
	$s_sect3 = $e_date;
	$s_sect4 = $e_date;
} elseif ($s_cate == "7") { // 이틀전 
	$s_date = date("Y-m-d");
	$e_date = date("Y-m-d", strtotime("-7 day", strtotime($s_date)));
	$where .= " and substring(sms_save_cell.wdate,1,10) >= '" . $e_date . "' ";
	$s_sect3 = $e_date;
	$s_sect4 = $e_date;
} elseif ($s_cate == "30") { // 3일전 
	$s_date = date("Y-m-d");
	$e_date = date("Y-m-d", strtotime("-30 day", strtotime($s_date)));
	$where .= " and substring(sms_save_cell.wdate,1,10) >= '" . $e_date . "' ";
	$s_sect3 = $e_date;
	$s_sect4 = $e_date;
} elseif ($s_cate == "1m") { // 11일 누적 
	$s_date = date("Y-m-d");
	$e_date = date("Y-m-d", strtotime("-1 month", strtotime($s_date)));
	$where .= " and substring(sms_save_cell.wdate,1,10) >= '" . $e_date . "' ";
	$s_sect3 = $e_date;
	$s_sect4 = $s_date;
} elseif ($s_cate == "3m") { // 11일 누적 
	$s_date = date("Y-m-d");
	$e_date = date("Y-m-d", strtotime("-3 month", strtotime($s_date)));
	$where .= " and substring(sms_save_cell.wdate,1,10) >= '" . $e_date . "' ";
	$s_sect3 = $e_date;
	$s_sect4 = $s_date;
} elseif ($s_cate == "6m") { // 11일 누적 
	$s_date = date("Y-m-d");
	$e_date = date("Y-m-d", strtotime("-6 month", strtotime($s_date)));
	$where .= " and substring(sms_save_cell.wdate,1,10) >= '" . $e_date . "' ";
	$s_sect3 = $e_date;
	$s_sect4 = $s_date;
}

if ($v_sect) {
	$where .= " and sms_save_cell.cell = '" . str_replace("-", "", $v_sect) . "' ";
}
if ($s_sect2) {
	$where .= " and a.sms_type = '" . $s_sect2 . "' ";
}
if ($s_group) {
	$where .= " and a.member_idx = '" . $s_group . "' ";
}

/*if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}*/

if ($keyword) {
	$where .= " and (a.sms_content like '%" . $keyword . "%' or a.sms_title like '%" . $keyword . "%')";
}

if ($save_idx) {
	$where .= " and a.idx = " . $save_idx . " ";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo - 1) * $pageScale;

$StarRowNum = (($pageNo - 1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by sms_save_cell.idx desc ";

$query = "select sms_save_cell.*, a.cell_send from sms_save_cell INNER JOIN sms_save a ON sms_save_cell.save_idx = a.idx where 1 " . $where . $order_by . " limit " . $StarRowNum . " , " . $EndRowNum;

//echo "<br><br>쿼리 = " . $query . "<br><Br>";

$result = mysqli_query($gconnet, $query);

$query_cnt = "select sms_save_cell.*, a.cell_send from sms_save_cell INNER JOIN sms_save a ON sms_save_cell.save_idx = a.idx where 1 " . $where;
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
		location.href = "sms_send_view.php?idx=" + no + "&<?= $total_param ?>";
	}

	function go_list() {
		location.href = "sms_send_list.php?<?= $total_param ?>";
	}

	function go_regist() {
		location.href = "sms_send_write.php?<?= $total_param ?>";
	}

	function go_down() {
		location.href = "total_sms_send_down.php?<?= $total_param ?>";
	}


	function go_search() {
		if (!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!");
			exit;
		}
		frm_page.submit();
	}

	function go_align(no, mode, align) {
		_fra_admin.location.href = "align_reset.php?idx=" + no + "&mode=" + mode + "&align=" + align + "&<?= $total_param ?>&tbn=sms_save&ret_url=/Shop/manage/sitecon/sms_send_list.php";
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
							<li>전체 전송내역 리스트</li>
						</ul>
					</div>
					<div class="list_tit">
						<h3>전체 전송내역 리스트</h3>
						<button class="btn_add" onclick="go_down();" style="width:150px;"><span>엑셀 다운로드</span></button>
					</div>
					<div class="list">
						<!-- 검색창 시작 -->
						<table class="search">
							<form name="s_mem" id="s_mem" method="post" action="total_sms_send_list.php">
								<input type="hidden" name="bmenu" value="<?= $bmenu ?>" />
								<input type="hidden" name="smenu" value="<?= $smenu ?>" />
								<input type="hidden" name="s_cnt" id="s_cnt" value="<?= $s_cnt ?>" />
								<input type="hidden" name="s_order" id="s_order" value="<?= $s_order ?>" />

								<input type="hidden" name="s_cate" id="s_cate" value="<?= $s_cate ?>" />

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
									<th scope="row">수신번호</th>
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

						<div style="text-align:left;margin-top:5px;margin-bottom:5px;padding-left:10px;">
							<a href="javascript:set_s_cate('d')" id="btn_date_d" class="date_btn btn_<?= $s_cate == "d" ? "blue" : "green" ?>">오늘</a>&nbsp;
							<a href="javascript:set_s_cate('1')" id="btn_date_1" class="date_btn btn_<?= $s_cate == "1" ? "blue" : "green" ?>">어제</a>&nbsp;
							<a href="javascript:set_s_cate('7')" id="btn_date_7" class="date_btn btn_<?= $s_cate == "7" ? "blue" : "green" ?>">일주일</a>&nbsp;
							<a href="javascript:set_s_cate('30')" id="btn_date_30" class="date_btn btn_<?= $s_cate == "30" ? "blue" : "green" ?>">지난달</a>&nbsp;
							<a href="javascript:set_s_cate('1m')" id="btn_date_1m" class="date_btn btn_<?= $s_cate == "1m" ? "blue" : "green" ?>">1개월</a>&nbsp;
							<a href="javascript:set_s_cate('3m')" id="btn_date_3m" class="date_btn btn_<?= $s_cate == "3m" ? "blue" : "green" ?>">3개월</a>&nbsp;
							<a href="javascript:set_s_cate('6m')" id="btn_date_6m" class="date_btn btn_<?= $s_cate == "6m" ? "blue" : "green" ?>">6개월</a>&nbsp;
							<a href="javascript:set_s_cate('n')" id="btn_date_n" class="date_btn btn_<?= $s_cate == "" ? "blue" : "green" ?>">전체</a>&nbsp;
						</div>

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

							<form method="post" name="frm" id="frm" target="_fra_admin">
								<input type="hidden" name="pageNo" value="<?= $pageNo ?>" />
								<input type="hidden" name="total_param" value="<?= $total_param ?>" />
								<table class="search_list">
									<thead>
										<tr>
											<th>전송일시</th>
											<th>발신번호</th>
											<th>수신번호</th>
											<th>통신사</th>
											<th>모듈</th>
											<th>발송결과코드</th>
											<th>발송여부</th>
										</tr>
									</thead>
									<tbody>
										<? if ($num == 0) { ?>
											<tr>
												<td colspan="5" height="40"><strong>발송내역이 없습니다.</strong></td>
											</tr>
										<? } ?>

										<?
										for ($i = 0; $i < mysqli_num_rows($result); $i++) { // 대분류 루프 시작
											$row = mysqli_fetch_array($result);

											$listnum	= $iTotalSubCnt - (($pageNo - 1) * $pageScale) - $i;

											$comp = "";
											if ($row['module_type'] == "LG") {
												$str = strtotime($row['wdate']);
												$date = date("Ym", $str);
												$sql_module = "select * from TBL_SEND_LOG_$date where fetc1='" . $row['idx'] . "'";

												$query_module = mysqli_query($gconnet, $sql_module);
												$module_row = mysqli_fetch_array($query_module);
												$comp = $module_row['fmobilecomp'];
												$code = $module_row['frsltstat'];
											} else if ($row['module_type'] == "JUD1" || $row['module_type'] == "JUD2") {
												$sql_module = "select * from SMS_BACKUP_AGENT_" . $row['module_type'] . " where S_ETC1='" . $row['idx'] . "'";
												$query_module = mysqli_query($gconnet, $sql_module);
												$module_row = mysqli_fetch_array($query_module);
												$comp = $module_row['TELECOM'];
												$code = $module_row['RSTATE'];
											}

										?>
											<tr>
												<td><?= $row['wdate'] ?></td>
												<td><?= $row['cell_send'] ?></td>
												<td><?= $row['cell'] ?></td>
												<td><?= $comp ?></td>
												<td><?= $row['module_type'] == "LG" ? "LGHV" : $row['module_type'] ?></td>
												<td><?= $code ?></td>
												<td>
													<?php
													if ($row['module_type'] == "LG") {
														$sql_sub_2 = "select idx from sms_save_cell where 1 and is_del='N' and idx='" . $row['idx'] . "' and idx in (select fetc1 from TBL_SEND_LOG_" . str_replace("-", "", substr($row['wdate'], 0, 7)) . " where 1 and frsltstat='06')";
														$query_sub_2 = mysqli_query($gconnet, $sql_sub_2);
														$row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);

														$sql_sub_3 = "select idx from sms_save_cell where 1 and is_del='N' and idx='" . $row['idx'] . "' and idx in (select fetc1 from TBL_SEND_LOG_" . str_replace("-", "", substr($row['wdate'], 0, 7)) . " where 1 and frsltstat='07')";
														$query_sub_3 = mysqli_query($gconnet, $sql_sub_3);
														$row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
													} else if ($row['module_type'] == "JUD1") {
														$sql_sub_2 = "select idx from sms_save_cell where 1 and is_del='N' and idx='" . $row['idx'] . "' and idx in (select S_ETC1 from SMS_BACKUP_AGENT_JUD1 where 1 and RSTATE=0)";
														$query_sub_2 = mysqli_query($gconnet, $sql_sub_2);
														$row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);

														$sql_sub_3 = "select idx from sms_save_cell where 1 and is_del='N' and idx='" . $row['idx'] . "' and idx in (select S_ETC1 from SMS_BACKUP_AGENT_JUD1 where 1 and RSTATE!=0)";
														$query_sub_3 = mysqli_query($gconnet, $sql_sub_3);
														$row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
													} else if ($row['module_type'] == "JUD2") {
														$sql_sub_2 = "select idx from sms_save_cell where 1 and is_del='N' and idx='" . $row['idx'] . "' and idx in (select S_ETC1 from SMS_BACKUP_AGENT_JUD2 where 1 and RSTATE=0)";
														$query_sub_2 = mysqli_query($gconnet, $sql_sub_2);
														$row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);

														$sql_sub_3 = "select idx from sms_save_cell where 1 and is_del='N' and idx='" . $row['idx'] . "' and idx in (select S_ETC1 from SMS_BACKUP_AGENT_JUD1 where 1 and RSTATE=!0)";
														$query_sub_3 = mysqli_query($gconnet, $sql_sub_3);
														$row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
													}

													if ($row['receive_cnt_suc'] > 0) {
														echo "성공";
													} else if ($row['receive_cnt_fail'] > 0) {
														echo "실패";
													} else {
														echo "잔여";
													}
													?>
												</td>
											</tr>
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

	<script>
		var check = 0;

		function CheckAll() {
			var boolchk;
			var chk = document.getElementsByName("send_idx[]")
			if (check) {
				check = 0;
				boolchk = false;
			} else {
				check = 1;
				boolchk = true;
			}
			for (i = 0; i < chk.length; i++) {
				chk[i].checked = boolchk;
			}
		}

		function go_tot_del() {
			var check = chkFrm('frm');
			if (check) {
				if (confirm('선택하신 발송결과를 삭제 하시겠습니까?')) {
					frm.action = "sms_send_action_delete.php";
					frm.submit();
				}
			} else {
				false;
			}
		}

		function set_s_cate(num) {
			$(".date_btn").removeClass("btn_blue");
			$(".date_btn").addClass("btn_green");
			$("#btn_date_" + num + "").removeClass("btn_green");
			$("#btn_date_" + num + "").addClass("btn_blue");
			if (num == "n") {
				$("#s_cate").val("");
			} else {
				$("#s_cate").val(num);
			}

			document.s_mem.submit();
		}
	</script>

	<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_bottom_admin_tail.php"; ?>
</body>

</html>