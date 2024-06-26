<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/check_login.php"; // 관리자 로그인여부 확인
?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));

$query = "SELECT * FROM filtering where key_name='filtering'";

$result = mysqli_query($gconnet, $query);
$row = mysqli_fetch_array($result);
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
							<li>필터링 등록</li>
						</ul>
					</div>
					<div class="list_tit">
						<h3>필터링 등록</h3>
					</div>
					<div class="write">

						<form name="frm" action="sms_filtering_write_action.php" target="_fra_admin" method="post" enctype="multipart/form-data">
							<input type="hidden" name="bmenu" value="<?= $bmenu ?>" />
							<input type="hidden" name="smenu" value="<?= $smenu ?>" />
							<table>
								<tr>
									<th>필터링내용(쉼표로 구분, 공백 불가)</th>
								</tr>
								<tr>
									<td>
										<textarea rows="50" name="filtering"><?= $row['filtering_text'] ?></textarea>
									</td>
								</tr>

							</table>
						</form>

						<div class="write_btn align_r">
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