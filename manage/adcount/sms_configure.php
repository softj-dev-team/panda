<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$sql_prev = "select a.* from sms_configure a where 1 and member_idx='".$_SESSION['manage_coinc_idx']."' and is_del='N'";
$query_prev = mysqli_query($gconnet,$sql_prev);

if(mysqli_num_rows($query_prev) > 0){
	$row_prev = mysqli_fetch_array($query_prev);
	
	$def_short_fee = $row_prev['def_short_fee'];
	$def_long_fee = $row_prev['def_long_fee'];
	$def_img_fee = $row_prev['def_img_fee'];
	$denie_num = $row_prev['denie_num'];
	
}

?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/adcount_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>환경설정</li>
						<li>SMS 환경설정</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>SMS 환경설정</h3>
				</div>
				
				<form name="frm" action="sms_configure_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
				<div class="write">
					<table>
						<caption>관리자 정보 등록</caption>
						<colgroup>
							<col style="width:20%">
							<col style="width:30%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th>기본 SMS(단문) 발송비용</th>
							<td colspan="3">
								<input type="text" id="def_short_fee" name="def_short_fee" required="yes" message="기본 SMS(단문) 발송비용" is_num="no" style="width:20%;" value="<?=$def_short_fee?>"> 원 (건당)
							</td>
						</tr>
						<tr>
							<th>기본 LMS(장문) 발송비용</th>
							<td colspan="3">
								<input type="text" id="def_long_fee" name="def_long_fee" required="yes" message="기본 LMS(장문) 발송비용" is_num="no" style="width:20%;" value="<?=$def_long_fee?>"> 원 (건당)
							</td>
						</tr>
						<tr>
							<th>기본 MMS(이미지) 발송비용</th>
							<td colspan="3">
								<input type="text" id="def_img_fee" name="def_img_fee" required="yes" message="기본 MMS(이미지) 발송비용" is_num="no" style="width:20%;" value="<?=$def_img_fee?>"> 원 (건당)
							</td>
						</tr>
						<tr>
							<th>080 수신거부 번호</th>
							<td colspan="3">
								<input type="text" id="denie_num" name="denie_num" required="yes" message="080 수신거부 번호" is_num="no" style="width:30%;" value="<?=$denie_num?>">
							</td>
						</tr>
					</table>
					
					<div class="write_btn align_r">
						<a href="javascript:go_submit();" class="btn_blue">설정저장</a>
					</div>
				</div>
				</form>
			</div>
		</div>
		<!-- content 종료 -->
	</div>
</div>

<script>
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}
</script>


<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>