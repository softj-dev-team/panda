<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/check_login.php"; // 관리자 로그인여부 확인
?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$v_sect = trim(sqlfilter($_REQUEST['v_sect']));
$s_group = trim(sqlfilter($_REQUEST['s_group']));

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = urldecode(sqlfilter($_REQUEST['keyword']));

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));
$s_sect3 = trim(sqlfilter($_REQUEST['s_sect3']));
$s_sect4 = trim(sqlfilter($_REQUEST['s_sect4']));

################## 파라미터 조합 #####################
$total_param = 'bmenu=' . $bmenu . '&smenu=' . $smenu . '&v_sect=' . $v_sect . '&s_group=' . $s_group . '&field=' . $field . '&keyword=' . $keyword . '&s_sect1=' . $s_sect1 . '&s_sect2=' . $s_sect2 . '&s_sect3=' . $s_sect3 . '&s_sect4=' . $s_sect4 . '&pageNo=' . $pageNo;

$sql = "SELECT * FROM mainban_info where 1=1 and idx = '" . $idx . "' ";
$query = mysqli_query($gconnet, $sql);

if (mysqli_num_rows($query) == 0) {
?>
	<SCRIPT LANGUAGE="JavaScript">
		<!--
		alert('해당하는 메인화면 배너설정 내용이 없습니다.');
		location.href = "mainban_list.php?<?= $total_param ?>";
		//
		-->
	</SCRIPT>
<?
	exit;
}

$row = mysqli_fetch_array($query);

if ($row[pro_idx]) {

	$sql_name = "select pro_name from product_info where 1=1 and idx = '" . $row[pro_idx] . "' ";
	$query_name = mysqli_query($gconnet, $sql_name);
	$row_name = mysqli_fetch_array($query_name);
	$pro_name = $row_name[pro_name];
}

$bbs_code = "main_banner";

if ($s_sect1 == "pc") {
	$sect_title = "PC";
} elseif ($s_sect1 == "mobile") {
	$sect_title = "앱";
}
?>

<script language="JavaScript">
	function go_submit() {
		var check = chkFrm('frm');
		if (check) {
			/*if($("#link_sect_2").prop("checked") == true) {// 링크 주소 별도입력
				if($("#link_url").val() == ""){
					alert("링크주소를 입력해 주세요.");
					return;
				}
			}*/
			frm.submit();
		} else {
			false;
		}
	}

	function go_view(no) {
		location.href = "mainban_view.php?idx=" + no + "&<?= $total_param ?>";
	}

	function go_list() {
		location.href = "mainban_list.php?<?= $total_param ?>";
	}

	function main_product_pop() {
		//location.href = 
		window.open("main_product.php?proidx=<?= $row[pro_idx] ?>", "pro_pro_view", "top=100,left=100,scrollbars=yes,resizable=no,width=910,height=500");
	}

	function link_ck() {
		/*if (document.frm.link_sect.link_sect_1.checked) { // 개별 상품 링크
			link_sect_txt1.style.display = '';
			link_sect_txt2.style.display = 'none';
		} else*/
		if ($("#link_sect_2").prop("checked") == true) { // 링크 주소 별도입력
			$("#link_sect_txt2").hide();
			$("#link_sect_txt3").show();
		} else if ($("#link_sect_3").prop("checked") == true) { // 링크없음
			$("#link_sect_txt2").hide();
			$("#link_sect_txt3").hide();
		}
	}

	function Display_1(form) {

		var target1 = document.all['banner_size_txt1'];

		if (form.main_sect.value == "topsch_right") {
			target1.innerText = "가로 : 140 픽셀, 세로 : 54 픽셀";
		} else if (form.main_sect.value == "flash_right") {
			target1.innerText = "가로 : 190 픽셀, 세로 : 260 픽셀";
		} else if (form.main_sect.value == "new_left") {
			target1.innerText = "가로 : 181 픽셀, 세로 : 176 픽셀";
		} else if (form.main_sect.value == "new_right") {
			target1.innerText = "가로 : 181 픽셀, 세로 : 203 픽셀";
		} else if (form.main_sect.value == "new_down") {
			target1.innerText = "가로 : 313 픽셀, 세로 : 103 픽셀";
		}

	}

	function section_ck() {
		if ($("#section_1").prop("checked") == true) {
			$("#link_sect_txt1").hide();
			$("#link_sect_txt2").hide();
			$("#link_sect_txt3").hide();
			$("#link_sect_txt4").hide();
			$("#link_sect_txt5").show();
		} else if ($("#section_2").prop("checked") == true) {
			$("#link_sect_txt1").show();
			//$("#link_sect_txt2").show();
			$("#link_sect_txt3").show();
			$("#link_sect_txt4").show();
			$("#link_sect_txt5").hide();
		}
	}
</script>

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
					<div class="navi">
						<ul class="clearfix">
							<li>HOME</li>
							<li>사이트 관리</li>
							<li>배너 보기</li>
						</ul>
					</div>
					<div class="list_tit">
						<h3>배너 보기</h3>
					</div>
					<div class="write">

						<form name="frm" action="mainban_modify_action.php" target="_fra_admin" method="post" enctype="multipart/form-data">
							<input type="hidden" name="total_param" value="<?= $total_param ?>" />
							<input type="hidden" name="idx" value="<?= $idx ?>" />
							<input type="hidden" name="pro_name" value="<?= $row[pro_name] ?>" />
							<input type="hidden" name="pro_idx" value="<?= $row[pro_idx] ?>" />
							<input type="hidden" name="s_sect1" value="<?= $s_sect1 ?>" />

							<input type="hidden" name="section" value="img" />

							<table class="t_view">
								<colgroup>
									<col width="10%" />
									<col width="40%" />
									<col width="10%" />
									<col width="40%" />
								</colgroup>

								<!--<tr>
						<th >배너구분</th>
						<td colspan="3">
							<input type="radio" name="section" id="section_1" onclick="section_ck();" required="yes"  message="배너구분" value="movie" <?= $row['section'] == "movie" ? "checked" : "" ?>>동영상 &nbsp; <input type="radio" name="section" id="section_2" onclick="section_ck();" required="yes"  message="배너구분" value="img" <?= $row['section'] == "img" ? "checked" : "" ?>>이미지
					</tr>-->

								<tr>
									<th>배너위치</th>
									<td colspan="3">
										<select name="main_sect" size="1" style="vertical-align:middle;" required="yes" message="배너위치" onchange="cate_sel_1(this)">
											<option value="">선택하세요</option>
											<option value="메인화면 상단롤링" <?= $row['main_sect'] == "메인화면 상단롤링" ? "selected" : "" ?>>메인화면 상단롤링</option>
											<option value="메인화면 중단아이콘" <?= $row['main_sect'] == "메인화면 중단아이콘" ? "selected" : "" ?>>메인화면 중단아이콘</option>
											<option value="메인화면 가격표시" <?= $row['main_sect'] == "메인화면 가격표시" ? "selected" : "" ?>>메인화면 가격표시</option>
											<option value="사이드바" <?= $row['main_sect'] == "사이드바" ? "selected" : "" ?>>사이드바</option>
										</select>
									</td>
								</tr>

								<? if ($row[section] == "img") { ?>
									<tr id="link_sect_txt1" style="display:<?= $row['section'] == "movie" ? "none" : "" ?>;">
										<th>링크유형</th>
										<td colspan="3">
											<!--<input type="radio" name="link_sect" id="link_sect_1" onclick="link_ck();" required="no"  message="링크유형" value="P"> 개별 상품에 대한 링크 &nbsp; --><input type="radio" name="link_sect" id="link_sect_2" onclick="link_ck();" required="no" message="링크유형" value="U" <?= $row['link_sect'] == "U" ? "checked" : "" ?>> 링크 URL 주소 입력 &nbsp; <input type="radio" name="link_sect" id="link_sect_3" onclick="link_ck();" required="no" message="링크유형" value="N" <?= $row['link_sect'] == "N" ? "checked" : "" ?>> 링크없음
										</td>
									</tr>

									<tr id="link_sect_txt2" style="display:<?= $row[link_sect] == "P" ? "" : "none" ?>;">
										<th>상품선택</th>
										<td colspan="3"><span id="pro_name_txt"><?= $pro_name ?></span>&nbsp;<a href="javascript:main_product_pop();" class="btn_blue2">상품찾기</a></td>
									</tr>

									<tr id="link_sect_txt3" style="display:<?= $row[link_sect] == "U" ? "" : "none" ?>;">
										<th>링크 URL 주소</th>
										<td colspan="3"><input type="text" style="width:50%;" name="link_url" id="link_url" required="no" message="링크주소" value="<?= $row[link_url] ?>"> &nbsp; <input type="radio" name="link_target" id="link_target" <?= $row[link_target] == "_self" ? "checked" : "" ?> required="no" message="링크타겟" value="_self"> 현재창 &nbsp; <input type="radio" name="link_target" id="link_target" <?= $row[link_target] == "_blank" ? "checked" : "" ?> required="no" message="링크타겟" value="_blank"> 새창</td>
									</tr>

									<tr id="link_sect_txt4" style="display:<?= $row['section'] == "movie" ? "none" : "" ?>;">
										<th>배너 이미지</th>
										<td colspan="3">
											<? if ($row['file_c']) { ?>
												기존파일 : <a href="/pro_inc/download_file.php?nm=<?= $row['file_c'] ?>&on=<?= $row['file_o'] ?>&dir=main_banner"><?= $row['file_o'] ?></a>
												(기존파일 삭제 : <input type="checkbox" name="del_org1" value="Y">)
											<? } ?>
											<input type="hidden" name="file_old_name1" value="<?= $row[file_c] ?>" />
											<input type="hidden" name="file_old_org1" value="<?= $row[file_o] ?>" />
											<br>
											<input type="file" id="inputPhoto" name="file1" style="width:50%;" required="no" message="배너 이미지" />&nbsp;<span id="banner_size_txt1" style="color:blue;">
												<? if ($row['main_sect'] == "메인화면 상단롤링") { ?>
													추천사이즈 : 가로 1920 픽셀, 세로 483 픽셀
												<? } elseif ($row['main_sect'] == "메인화면 중단아이콘") { ?>
													추천사이즈 : 가로 105 픽셀, 세로 110 픽셀
												<? } elseif ($row['main_sect'] == "메인화면 가격표시") { ?>
													추천사이즈 : 가로 1053 픽셀, 세로 237 픽셀
												<? } ?>
											</span>
										</td>
									</tr>
								<? } elseif ($row[section] == "movie") { ?>
									<tr id="link_sect_txt5" style="display:<?= $row['section'] == "movie" ? "" : "none" ?>;">
										<th>유튜브 재생코드</th>
										<td colspan="3"><input type="text" style="width:50%;" name="m_link_url" id="m_link_url" required="no" message="링크주소" value="<?= $row[link_url] ?>"> * 해당 유튜브 "공유" 버튼 클릭시 나타나는 URL</td>
									</tr>
								<? } ?>

								<tr>
									<th>사용여부</th>
									<td><input type="radio" name="view_ok" required="yes" message="사용여부" value="Y" <?= $row['view_ok'] == "Y" ? "checked" : "" ?>> 사용함 <input type="radio" name="view_ok" required="yes" message="사용여부" value="N" <?= $row['view_ok'] == "N" ? "checked" : "" ?>> 사용안함 </td>
									<th> 정렬순서</th>
									<td><input type="text" style="width:20%;" name="align" required="yes" message="정렬순서" is_num="yes" value="<?= $row[align] ?>"> * 숫자만 입력가능, 높은 숫자 우선으로 정렬됨.</td>
								</tr>

								<!--<tr>
						<th>배너 타이틀</th>
						<td width="*" colspan="3">
							<input type="text" style="width:60%;" name="main_title" id="main_title" required="yes"  message="배너 타이틀" value="<?= $row['main_title'] ?>">
						</td>
					</tr>-->

								<tr>
									<th>배너 텍스트</th>
									<td width="*" colspan="3">
										<textarea style="width:90%;height:80px;" name="main_memo" id="main_memo" required="yes" message="메인 텍스트"><?= $row['main_memo'] ?></textarea>
									</td>
								</tr>

							</table>
						</form>

						<div class="write_btn align_r">
							<a href="javascript:go_view('<?= $row[idx] ?>');" class="btn_gray">취소하기</a>
							<a href="javascript:go_submit();" class="btn_blue">수정하기</a>
						</div>

					</div>
				</div>
			</div>
			<!-- content 종료 -->
		</div>
	</div>

	<script>
		function cate_sel_1(z) {
			var tmp = z.options[z.selectedIndex].value;
			if (tmp == "메인화면 상단롤링") {
				var size_txt = "추천사이즈 : 가로 1920 픽셀, 세로 483 픽셀";
			} else if (tmp == "메인화면 중단아이콘") {
				var size_txt = "추천사이즈 : 가로 105 픽셀, 세로 110 픽셀";
			} else if (tmp == "메인화면 가격표시") {
				var size_txt = "추천사이즈 : 가로 1053 픽셀, 세로 237 픽셀";
			}
			$("#banner_size_txt1").html(size_txt);
		}
	</script>

	<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_bottom_admin_tail.php"; ?>
</body>

</html>