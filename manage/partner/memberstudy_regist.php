<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage_pop.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$total_param = 'member_idx='.$member_idx;
?>
<body>
		<!-- content 시작 -->
		<div class="content" style="position:relative; padding:0 10px 0 10px;">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>회원관리</li>
					</ul>
				</div>

				<div class="list_tit">
					<h3>학습 추가</h3>
				</div>
				
			<!-- 내용 시작 -->	
				<div class="write">

				<form name="frm" action="memberstudy_regist_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="member_idx" id="member_idx" value="<?=$member_idx?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<table>
						<caption>대주제정보 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th scope="row">학습구분</th>
							<td colspan="3">
								<select name="curri_type" required="yes" message="학습구분" size="1" style="vertical-align:middle;width:40%;" onchange="cate_sel_1(this);">
									<option value="">선택하세요</option>
								<?
								$master_cleft_sql = "select cate_code1,cate_name1 from common_code where 1 and type='menu' and cate_level = '1' and is_del='N' and del_ok='N' order by cate_align desc"; 
								$master_cleft_query = mysqli_query($gconnet,$master_cleft_sql);
				
								$master_cleft_k = 0;
								for($master_cleft_i=0; $master_cleft_i<mysqli_num_rows($master_cleft_query); $master_cleft_i++){
									$master_cleft_row = mysqli_fetch_array($master_cleft_query);
									$master_cleft_k = $master_cleft_k+1;
								?>
									<option value="<?=$master_cleft_row['cate_code1']?>"><?=$master_cleft_row['cate_name1']?></option>
								<?}?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">학습명</th>
							<td colspan="3">
								<select name="curri_info_idx" required="yes" message="대주제" size="1" style="vertical-align:middle;width:40%;" onchange="cate_sel_2(this);">
									<option value="">대주제 선택</option>
								</select> &nbsp; 
								<select name="lecture_info_idx" required="yes" message="소주제" size="1" style="vertical-align:middle;width:40%;" >
									<option value="">소주제 선택</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">목표</th>
							<td>
								<select name="per_target" required="yes" message="목표율" size="1" style="vertical-align:middle;width:40%;" >
									<option value="">선택하세요</option>
									<option value="10">10 %</option>
									<option value="20">20 %</option>
									<option value="30">30 %</option>
									<option value="40">40 %</option>
									<option value="50">50 %</option>
									<option value="60">60 %</option>
									<option value="70">70 %</option>
									<option value="80">80 %</option>
									<option value="90">90 %</option>
									<option value="100">100 %</option>
								</select>
							</td>
							<th scope="row">성공률</th>
							<td>
								<select name="per_success" required="yes" message="성공률" size="1" style="vertical-align:middle;width:40%;" >
									<option value="">선택하세요</option>
									<option value="10">10 %</option>
									<option value="20">20 %</option>
									<option value="30">30 %</option>
									<option value="40">40 %</option>
									<option value="50">50 %</option>
									<option value="60">60 %</option>
									<option value="70">70 %</option>
									<option value="80">80 %</option>
									<option value="90">90 %</option>
									<option value="100">100 %</option>
								</select>
							</td>
						</tr>
						
					</table>

					<div class="write_btn align_r">
						<a href="javascript:self.close();" class="btn_gray">닫기</a>
						<a href="javascript:go_submit();" class="btn_blue">추가하기</a>
					</div>
				
				</form>
				</div>
			<!-- 내용 종료 -->	
			
									
	</div>
	<!-- content 종료 -->
<script type="text/javascript">
$(function() {
	$(".datepicker").datepicker({
		changeYear:true,
		changeMonth:true,
		minDate: '-90y',
		yearRange: 'c-90:c',
		dateFormat:'yy-mm-dd',
		showMonthAfterYear:true,
		constrainInput: true,
		dayNamesMin: ['일','월', '화', '수', '목', '금', '토' ],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
	});
});	

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}

function cate_sel_1(z){
	var tmp = z.options[z.selectedIndex].value; 
	_fra_admin.location.href="/pro_inc/curri_select.php?curri_type="+tmp+"&fm=frm&fname=curri_info_idx";
}

function cate_sel_2(z){
	var tmp = z.options[z.selectedIndex].value; 
	_fra_admin.location.href="/pro_inc/lecture_select.php?curri_info_idx="+tmp+"&fm=frm&fname=lecture_info_idx";
}

</script>

	<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
 	