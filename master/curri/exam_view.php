<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?
	$lecture_idx = trim(sqlfilter($_REQUEST['lecture_idx']));
	$total_param = 'lecture_idx='.$lecture_idx;
	
	$sql = "select *,(select file_chg from board_file where 1 and board_tbname='curri_lecture_info' and board_code='photo' and board_idx=curri_lecture_info.idx order by idx asc limit 0,1) as file_photo,(select file_org from board_file where 1 and board_tbname='curri_lecture_info' and board_code='photo' and board_idx=curri_lecture_info.idx order by idx asc limit 0,1) as file_photo_org,(select file_chg from board_file where 1 and board_tbname='curri_lecture_info' and board_code='movie' and board_idx=curri_lecture_info.idx order by idx asc limit 0,1) as file_movie,(select file_org from board_file where 1 and board_tbname='curri_lecture_info' and board_code='movie' and board_idx=curri_lecture_info.idx order by idx asc limit 0,1) as file_movie_org,(select curri_title from curri_info where 1 and idx=curri_lecture_info.curri_info_idx) as curri_title from curri_lecture_info where 1 and is_del='N' and idx='".$lecture_idx."'";

	//echo $sql."<br>";
	$query = mysqli_query($gconnet,$sql);

	if(mysqli_num_rows($query) == 0){
		error_popup("등록된 소주제가 없습니다.");
	}

	$row = mysqli_fetch_array($query);
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
					<h3>등록된 소주제정보 상세보기</h3>
				</div>
				
			<!-- 내용 시작 -->	
				<div class="write">
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
								<?=$row['lecture_title']?>
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
								<img src="<?=$_P_DIR_WEB_FILE?>curri_lecture_info/img_thumb/<?=$row_file_lect['file_chg']?>" style="max-width:90%;">
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
								<a href="/pro_inc/download_file.php?nm=<?=$row_file_lect['file_chg']?>&on=<?=$row_file_lect['file_org']?>&dir=curri_lecture_info"><?=$row_file_lect['file_org']?></a>
							</td>
						</tr>
						<?} // 음원 루프 종료 ?>

						<tr>
						<th scope="row">정답 스크립트</th>
						<td colspan="3">
							<?=nl2br($row['lecture_correct'])?>
						</td>
						</tr>
						<tr>
						<th scope="row">정답 스크립트 (한글)</th>
						<td colspan="3">
							<?=nl2br($row['lecture_correct_kor'])?>
						</td>
						</tr>
						<tr>
						<th scope="row">힌트창 문구</th>
						<td colspan="3">
							<?=nl2br($row['lecture_hint'])?>
						</td>
						</tr>
					<tr>
						<th scope="row">조회수</th>
						<td>
							<?=number_format(get_vcnt("curri_lecture_info","",$row['idx']))?> 회</td>
						</td>
						<th scope="row">등록일시</th>
						<td>
							<?=$row[wdate]?>
						</td>
					</tr>
					<?if($row[mdate]){?>
						<tr>
							<th scope="row">마지막 수정일시</th>
							<td colspan="3">
								<?=$row[mdate]?>
							</td>
						</tr>
					<?}?>
					</table>

					<div class="write_btn align_r">
						<a href="javascript:self.close();" class="btn_gray">닫기</a>
						<a href="javascript:go_modify('<?=$row[idx]?>');" class="btn_green">정보수정</a>
						<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제하기</a>	
					</div>

				</div>
			<!-- 내용 종료 -->	
			
									
	</div>
	<!-- content 종료 -->
<script type="text/javascript">
<!-- 
	function go_modify(no){
		location.href = "exam_modify.php?idx="+no+"&<?=$total_param?>";
	}

	function go_delete(no){
		if(confirm('소주제 정보를 삭제하시면 다시는 복구가 불가능 합니다. 정말 삭제 하시겠습니까?')){
			if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "exam_delete_action.php?idx="+no+"&<?=$total_param?>";
			}
		}
	}
//-->
</script>

	<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
 	