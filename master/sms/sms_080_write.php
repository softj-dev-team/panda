<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/check_login.php"; // 관리자 로그인여부 확인
?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));

$query = "SELECT * FROM spam_080_group";

$result = mysqli_query($gconnet, $query);
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

	function go_list() {
		location.href = "sms_080_list.php?<?= $total_param ?>";
	}
</script>

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
							<li>080 차단번호 관리</li>
							<li>080 차단번호 등록</li>
						</ul>
					</div>
					<div class="list_tit">
						<h3>080 차단번호 등록</h3>
					</div>
					<div class="write">

						<form name="frm" action="sms_080_write_action.php" target="_fra_admin" method="post" enctype="multipart/form-data">
							<input type="hidden" name="bmenu" value="<?= $bmenu ?>" />
							<input type="hidden" name="smenu" value="<?= $smenu ?>" />

							<table>
								<tr>
									<th>080번호</th>
									<td>
										<select name="group_idx">
											<?
											for ($i = 0; $i < mysqli_num_rows($result); $i++) {
												$row = mysqli_fetch_array($result);
											?>
												<option value="<?= $row['idx'] ?>"><?= $row['group_num'] ?></option>
											<? } ?>
										</select>
									</td>
								</tr>
								<tr>
									<th>차단번호</th>
									<td>
										<input type="text" name="cell_num">
									</td>
								</tr>

							</table>
						</form>

						<div class="write_btn align_r">
							<a href="javascript:go_list();" class="btn_gray">목록보기</a>
							<a href="javascript:go_submit();" class="btn_blue">등록하기</a>
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