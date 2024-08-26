<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$lecture_idx = trim(sqlfilter($_REQUEST['lecture_idx']));
	$total_param = 'lecture_idx='.$lecture_idx;
	
	$sql = "select * from curri_lecture_info where 1 and is_del='N' and idx='".$lecture_idx."'";

	//echo $sql."<br>";
	$query = mysqli_query($gconnet,$sql);

	if(mysqli_num_rows($query) == 0){
		error_popup("등록된 소주제가 없습니다.");
	}

	$row = mysqli_fetch_array($query);

	$i_file = 0;
?>
<body>
		<!-- content 시작 -->
		<div class="content" style="position:relative; padding:0 10px 0 10px;">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>등록된 소주제정보</li>
					</ul>
				</div>

				<div class="list_tit">
					<h3>등록된 소주제정보 수정</h3>
				</div>
				
			<!-- 내용 시작 -->	
				<div class="write">

				<form name="frm" action="exam_modify_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="idx" id="curri_idx" value="<?=$idx?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<input type="hidden" name="attach_count_1" id="attach_count_1" value="1"/>

					<table>
						<caption>대주제정보 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th scope="row">소주제 제목</th>
							<td colspan="3">
								<input type="text" style="width:60%;" id="lecture_title_<?=$i_file?>" name="lecture_title_<?=$i_file?>" required="yes" message="소주제 제목" value="<?=$row['lecture_title']?>">
							</td>
						</tr>
						<?
						$sql_file_lect = "select idx,file_org,file_chg from board_file where 1 and board_tbname='curri_lecture_info' and board_code='photo' and board_idx='".$row['idx']."' order by idx asc ";
						$query_file_lect = mysqli_query($gconnet,$sql_file_lect);
						$cnt_file_lect = mysqli_num_rows($query_file_lect);

						if($cnt_file_lect < 1){
							$cnt_file_lect = 1;
						}
						
						for($i_file_lect=0; $i_file_lect<$cnt_file_lect; $i_file_lect++){ // 소주제 이미지 루프 시작 
							$row_file_lect = mysqli_fetch_array($query_file_lect);
							$k_file_lect = $i_file_lect+1;
						?>
						<tr>
							<th scope="row">섬네일 이미지</th>
							<td colspan="3">
								<input type="hidden" name="pfile2_idx_<?=$i_file?>_<?=$i_file_lect?>" value="<?=$row_file_lect['idx']?>" />
								<input type="hidden" name="pfile2_old_name_<?=$i_file?>_<?=$i_file_lect?>" value="<?=$row_file_lect['file_chg']?>" />
								<input type="hidden" name="pfile2_old_org_<?=$i_file?>_<?=$i_file_lect?>" value="<?=$row_file_lect['file_org']?>" />

								<input type="file" style="width:400px;" required="no" message="섬네일 이미지" name="photo2_<?=$i_file?>_<?=$i_file_lect?>"> (최적화 사이즈 : 가로 240, 세로 170 픽셀)
								<?if($row_file_lect['file_chg']){?>
									<br>기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file_lect['file_chg']?>&on=<?=$row_file_lect['file_org']?>&dir=curri_lecture_info"><?=$row_file_lect['file_org']?></a>
									(기존파일 삭제 : <input type="checkbox" name="pdel2_org_<?=$i_file?>_<?=$i_file_lect?>" value="Y">)
								<?}?>
							</td>
						</tr>
						<?} // 소주제 이미지 루프 종료 ?>

						<?
						$sql_file_lect = "select idx,file_org,file_chg from board_file where 1 and board_tbname='curri_lecture_info' and board_code='movie' and board_idx='".$row['idx']."' order by idx asc ";
						$query_file_lect = mysqli_query($gconnet,$sql_file_lect);
						$cnt_file_lect = mysqli_num_rows($query_file_lect);

						if($cnt_file_lect < 1){
							$cnt_file_lect = 1;
						}
						
						for($i_file_lect=0; $i_file_lect<$cnt_file_lect; $i_file_lect++){ // 음원 루프 시작 
							$row_file_lect = mysqli_fetch_array($query_file_lect);
							$k_file_lect = $i_file_lect+1;
						?>
						<tr>
							<th scope="row">음원</th>
							<td colspan="3">
								<input type="hidden" name="pfile3_idx_<?=$i_file?>_<?=$i_file_lect?>" value="<?=$row_file_lect['idx']?>" />
								<input type="hidden" name="pfile3_old_name_<?=$i_file?>_<?=$i_file_lect?>" value="<?=$row_file_lect['file_chg']?>" />
								<input type="hidden" name="pfile3_old_org_<?=$i_file?>_<?=$i_file_lect?>" value="<?=$row_file_lect['file_org']?>" />

								<input type="file" style="width:400px;" required="no" message="음원" name="photo3_<?=$i_file?>_<?=$i_file_lect?>">
								<?if($row_file_lect['file_chg']){?>
									<br>기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file_lect['file_chg']?>&on=<?=$row_file_lect['file_org']?>&dir=curri_lecture_info"><?=$row_file_lect['file_org']?></a>
									(기존파일 삭제 : <input type="checkbox" name="pdel3_org_<?=$i_file?>_<?=$i_file_lect?>" value="Y">)
								<?}?>
							</td>
						</tr>
						<?} // 음원 루프 종료 ?>

						<tr>
						<th scope="row">정답 스크립트</th>
						<td colspan="3">
							<textarea style="width:90%;height:100px;" name="lecture_correct_<?=$i_file?>" id="lecture_correct_<?=$i_file?>" required="yes" message="정답 스크립트" value=""><?=$row['lecture_correct']?></textarea>
						</td>
						</tr>
						<tr>
						<th scope="row">정답 스크립트 (한글)</th>
						<td colspan="3">
							<textarea style="width:90%;height:100px;" name="lecture_correct_kor_<?=$i_file?>" id="lecture_correct_kor_<?=$i_file?>" required="yes" message="정답 스크립트 (한글)" value=""><?=$row['lecture_correct_kor']?></textarea>
						</td>
						</tr>
						<tr>
						<th scope="row">힌트창 문구</th>
						<td colspan="3">
							<textarea style="width:90%;height:100px;" name="lecture_hint_<?=$i_file?>" id="lecture_hint_<?=$i_file?>" required="yes" message="힌트창 문구" value=""><?=$row['lecture_hint']?></textarea>
						</td>
						</tr>
					</table>

					<div class="write_btn align_r">
						<a href="javascript:self.close();" class="btn_gray">닫기</a>
						<a href="javascript:go_submit();" class="btn_blue">정보수정</a>
					</div>
				
				</form>
				</div>
			<!-- 내용 종료 -->	
			
									
	</div>
	<!-- content 종료 -->
<script type="text/javascript" src="/smarteditor2/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript">
	
	/*var oEditors_1 = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors_1,
		elPlaceHolder: "editor_1",
		sSkinURI: "/smarteditor2/SmartEditor2Skin.html",	
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){
				//alert("아싸!");	
			}
		}, //boolean
		fOnAppLoad : function(){
			//예제 코드
			//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
		},
		fCreator: "createSEditor2"
	});*/

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			//oEditors_1.getById["editor_1"].exec("UPDATE_CONTENTS_FIELD", []);
			frm.submit();
		} else {
			false;
		}
	}

</script>

	<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
 	