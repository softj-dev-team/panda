<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$sql_prev = "select a.*,(select idx from board_file where 1 and board_tbname='site_configure' and board_code='logo' and board_idx=a.idx order by idx asc limit 0,1) as file_idx,(select file_chg from board_file where 1 and board_tbname='site_configure' and board_code='logo' and board_idx=a.idx order by idx asc limit 0,1) as file_chg,(select file_org from board_file where 1 and board_tbname='site_configure' and board_code='logo' and board_idx=a.idx order by idx asc limit 0,1) as file_org from site_configure a where 1 and member_idx='".$_SESSION['admin_coinc_idx']."' and is_del='N'";
$query_prev = mysqli_query($gconnet,$sql_prev);

if(mysqli_num_rows($query_prev) > 0){
	$row_prev = mysqli_fetch_array($query_prev);
	
	$sns_kakao = $row_prev['sns_kakao'];
	$sns_teleg = $row_prev['sns_teleg'];
	
	$bank_name = $row_prev['bank_name'];
	$bank_num = $row_prev['bank_num'];
	$bank_owner = $row_prev['bank_owner'];
	
	$conf_tel_2 = $row_prev['conf_tel_2'];
	$conf_time_s = $row_prev['conf_time_s'];
	$conf_time_e = $row_prev['conf_time_e'];
	$conf_time_s2 = $row_prev['conf_time_s2'];
	$conf_time_e2 = $row_prev['conf_time_e2'];
	$conf_fax = $row_prev['conf_fax'];
	$conf_email_1 = $row_prev['conf_email_1'];
	
	$conf_comname = $row_prev['conf_comname'];
	$conf_comowner = $row_prev['conf_comowner'];
	$conf_manager = $row_prev['conf_manager'];
	$conf_comnum_1 = $row_prev['conf_comnum_1'];
	$conf_comnum_2 = $row_prev['conf_comnum_2'];
	$conf_addr = $row_prev['conf_addr'];
	$conf_tel_1 = $row_prev['conf_tel_1'];
	$conf_email_2 = $row_prev['conf_email_2'];
	
	$file_idx = $row_prev['file_idx'];
	$file_chg = $row_prev['file_chg'];
	$file_org = $row_prev['file_org'];
}

?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/adcount_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>환경설정</li>
						<li>사이트 환경설정</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>사이트 환경설정</h3>
				</div>
				
				<form name="frm" action="site_configure_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
				<div class="write">
					<p class="tit">로고관리</p>
					<table>
						<caption>관리자 정보 등록</caption>
						<colgroup>
							<col style="width:20%">
							<col style="width:30%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th>로고파일</th>
							<td colspan="3">
								<input type="file" style="width:30%;" required="no" message="로고파일" name="file_logo" id="file_logo" accept="image/*"> (최적 사이즈 : 가로 159 픽셀, 세로 34 픽셀)
								<?if($file_idx){?>
									<input type="hidden" name="file_idx" id="file_idx" value="<?=$file_idx?>" />
									<input type="hidden" name="file_old_name" id="file_old_name" value="<?=$file_chg?>" />
									<input type="hidden" name="file_old_org" id="file_old_org" value="<?=$file_org?>" />
									
									<div style="margin-top:10px;">
										<a href="/pro_inc/download_file.php?nm=<?=$file_chg?>&on=<?=$file_org?>&dir=siteconf"><img src="<?=$_P_DIR_WEB_FILE?>siteconf/img_thumb/<?=$file_chg?>" style="max-width:90%;"></a>
									</div>
									<div style="margin-top:5px;">
										<input type="checkbox" name="del_org" id="del_org" value="Y"> 삭제시 체크
									</div>
								<?}?>	
							</td>
						</tr>
					</table>
					
					<p class="tit">SNS 설정</p>
					<table>
						<caption>관리자 정보 등록</caption>
						<colgroup>
							<col style="width:20%">
							<col style="width:30%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th>카카오 바로가기 URL</th>
							<td colspan="3">
								<input type="text" style="width:70%;" name="sns_kakao" id="sns_kakao" required="no"  message="카카오 바로가기 URL" value="<?=$sns_kakao?>">
							</td>
						</tr>
						<tr>
							<th>텔레그램 바로가기 URL</th>
							<td colspan="3">
								<input type="text" style="width:70%;" name="sns_teleg" id="sns_teleg" required="no" message="텔레그램 바로가기 URL" value="<?=$sns_teleg?>">
							</td>
						</tr>
					</table>
					
					<p class="tit">무통장결제 설정</p>
					<table>
						<caption>관리자 정보 등록</caption>
						<colgroup>
							<col style="width:20%">
							<col style="width:30%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th>무통장결제 입금은행</th>
							<td colspan="3">
								<input type="text" style="width:50%;" name="bank_name" id="bank_name" required="no" message="무통장결제 입금은행" value="<?=$bank_name?>">
							</td>
						</tr>
						<tr>
							<th>무통장결제 입금계좌</th>
							<td colspan="3">
								<input type="text" style="width:50%;" name="bank_num" id="bank_num" required="no" message="무통장결제 입금계좌" value="<?=$bank_num?>">
							</td>
						</tr>
						<tr>
							<th>무통장결제 예금주</th>
							<td colspan="3">
								<input type="text" style="width:30%;" name="bank_owner" id="bank_owner" required="no" message="무통장결제 예금주" value="<?=$bank_owner?>">
							</td>
						</tr>
					</table>
					
					<p class="tit">고객센터</p>
					<table>
						<caption>관리자 정보 등록</caption>
						<colgroup>
							<col style="width:20%">
							<col style="width:30%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th>고객센터 전화번호</th>
							<td colspan="3">
								<input type="text" style="width:30%;" name="conf_tel_2" id="conf_tel_2" required="no" message="고객센터 전화번호" value="<?=$conf_tel_2?>">
							</td>
						</tr>
						<tr>
							<th>평일 영업시간</th>
							<td>
								<input type="text" style="width:40%;" name="conf_time_s" id="conf_time_s" required="no" message="평일 영업시간" value="<?=$conf_time_s?>"> ~ <input type="text" style="width:40%;" name="conf_time_e" id="conf_time_e" required="no" message="평일 영업시간" value="<?=$conf_time_e?>">
							</td>
							<th>주말 영업시간</th>
							<td>
								<input type="text" style="width:40%;" name="conf_time_s2" id="conf_time_s2" required="no" message="주말 영업시간" value="<?=$conf_time_s2?>"> ~ <input type="text" style="width:40%;" name="conf_time_e2" id="conf_time_e2" required="no" message="주말 영업시간" value="<?=$conf_time_e2?>">
							</td>
						</tr>
						<tr>
							<th>고객센터 팩스번호</th>
							<td>
								<input type="text" style="width:80%;" name="conf_fax" id="conf_fax" required="no" message="고객센터 팩스번호" value="<?=$conf_fax?>">
							</td>
							<th>고객센터 이메일</th>
							<td>
								<input type="text" style="width:90%;" name="conf_email_1" id="conf_email_1" required="no" message="고객센터 이메일" is_email="yes" value="<?=$conf_email_1?>">
							</td>
						</tr>
					</table>
					
					<p class="tit">카피라이트</p>
					<table>
						<caption>관리자 정보 등록</caption>
						<colgroup>
							<col style="width:20%">
							<col style="width:30%">
							<col style="width:20%">
							<col style="width:30%">
						</colgroup>
						<tr>
							<th>회사명</th>
							<td colspan="3">
								<input type="text" style="width:30%;" name="conf_comname" id="conf_comname" required="no" message="회사명" value="<?=$conf_comname?>">
							</td>
						</tr>
						<tr>
							<th>대표자명</th>
							<td>
								<input type="text" style="width:80%;" name="conf_comowner" id="conf_comowner" required="no" message="대표자명" value="<?=$conf_comowner?>">
							</td>
							<th>개인정보 보호담당자</th>
							<td>
								<input type="text" style="width:90%;" name="conf_manager" id="conf_manager" required="no" message="개인정보 보호담당자" value="<?=$conf_manager?>">
							</td>
						</tr>
						<tr>
							<th>사업자 등록번호</th>
							<td>
								<input type="text" style="width:80%;" name="conf_comnum_1" id="conf_comnum_1" required="no" message="사업자 등록번호" value="<?=$conf_comnum_1?>">
							</td>
							<th>통신판매업 신고번호</th>
							<td>
								<input type="text" style="width:90%;" name="conf_comnum_2" id="conf_comnum_2" required="no" message="통신판매업 신고번호" value="<?=$conf_comnum_2?>">
							</td>
						</tr>
						<tr>
							<th>회사주소</th>
							<td colspan="3">
								<input type="text" style="width:70%;" name="conf_addr" id="conf_addr" required="no" message="회사주소" value="<?=$conf_addr?>">
							</td>
						</tr>
						<tr>
							<th>대표전화</th>
							<td>
								<input type="text" style="width:80%;" name="conf_tel_1" id="conf_tel_1" required="no" message="대표전화" value="<?=$conf_tel_1?>">
							</td>
							<th>이메일</th>
							<td>
								<input type="text" style="width:90%;" name="conf_email_2" id="conf_email_2" required="no" message="이메일" is_email="yes" value="<?=$conf_email_2?>">
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