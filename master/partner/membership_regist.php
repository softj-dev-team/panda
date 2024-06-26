<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
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
					<h3>멤버십 추가</h3>
				</div>
				
			<!-- 내용 시작 -->	
				<div class="write">

				<form name="frm" action="membership_regist_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
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
							<th scope="row">결제일 (추가일)</th>
							<td colspan="3">
								<?=date("Y-m-d")?>
							</td>
						</tr>
						<tr>
							<th scope="row">이용 멤버쉽</th>
							<td colspan="3">
								<select name="payment_type" required="yes" message="이용 멤버쉽" size="1" style="vertical-align:middle;width:40%;" >
									<option value="">선택하세요</option>
								<?
								$master_cleft_sql = "select cate_code1,cate_name1 from common_code where 1 and type='payment' and cate_level = '1' and is_del='N' and del_ok='N' order by cate_align desc"; 
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
							<th scope="row">시작일</th>
							<td>
								<input type="text" style="width:60%;" id="s_date" name="s_date" required="yes" message="시작일" class="datepicker">
							</td>
							<th scope="row">종료일</th>
							<td>
								<input type="text" style="width:60%;" id="e_date" name="e_date" required="yes" message="종료일" class="datepicker">
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
		yearRange: 'c-90:c+20',
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

</script>

	<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
 	