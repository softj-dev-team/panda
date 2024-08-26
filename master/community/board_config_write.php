<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>

<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/admin_top.php"; // 관리자페이지 상단메뉴?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/community_left.php"; // 게시판관리 좌측메뉴?>
<?
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_sect1='.$s_sect1;

?>

<script language="JavaScript"> 
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {

			if(document.frm.close_ok[0].checked) { // 일반형 게시판 클릭시 시작 

				if(!document.frm.list_auth.value) {
					alert("리스트보기 권한을 설정해 주세요.");
					return;
				}

				if(!document.frm.view_auth.value) {
					alert("본문보기 권한을 설정해 주세요.");
					return;
				}

				if(!document.frm.write_auth.value) {
					alert("본문쓰기 권한을 설정해 주세요.");
					return;
				}

				if(!document.frm.reply_auth.value) {
					alert("덧글쓰기 권한을 설정해 주세요.");
					return;
				}
			
			} else if(document.frm.close_ok[1].checked) { // 일반형 게시판 클릭시 종료, 폐쇄형 게시판 클릭시 시작
				
				if(!document.frm.board_master_id.value) {
					alert("게시판 관리자를 설정해 주세요.");
					return;
				}

			} // 일반형,폐쇄형 모두 종료

			frm.submit();
		} else {
			false;
		}
	}
	
	function go_list(){
		location.href = "board_config_list.php?<?=$total_param?>";
	}

	function bmaster_select(){
		 var ktmp = document.all['board_master_id'].value;
		_fra_admin.location.href="board_config_master_select.php?board_master_id="+ktmp+"";
	}

	function go_auto_select(selected_str){
		if(selected_str != ""){
			document.getElementById("auto_bmaster_view").style.display = "none";
			document.all['board_master_id'].value=selected_str;
			//bmaster_select();
		}
	}

function close_ck() {
	
	if(document.frm.close_ok[0].checked) {
		close_ck_text_1.style.display = "block";
		close_ck_text_2.style.display = "block";
		close_ck_text_3.style.display = "block";
	} else if(document.frm.close_ok[1].checked) {
		close_ck_text_1.style.display = "none";
		close_ck_text_2.style.display = "none";
		close_ck_text_3.style.display = "none";
	} 

}


</script>

<script type="text/javascript" src="/manage/js/search_board_master.js"></script> <!-- 연관검색어 자동입력을 위한 ajax js 인클루드 -->

<!-- content -->
<section id="content">
	<div class="inner">
		<h3>게시판 생성하기</h3>
		<div class="cont">
			
			<form name="frm" action="board_config_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
			<input type="hidden" name="total_param" value="<?=$total_param?>"/>
			<input type="hidden" name="board_master_idx" id="board_master_idx" value=""/>
						
			<table class="t_view">
				<colgroup>
					<col width="10%" />
					<col width="40%"/>
					<col width="10%" />
					<col width="40%"/>
				</colgroup>
		
					<tr>
						<th >게시판 카테고리</th>
						<td >
							<select name="cate1" size="1" style="vertical-align:middle;" required="no"  message="게시판 카테고리">
							<option value="">카테고리 선택</option>
							<?
							$sect1_sql = "select cate_code1,cate_name1 from board_cate where cate_level = '1' and cate_code1 != 'tsys' and is_del != 'Y' order by cate_align desc"; // 고객센터용 게시판 제외
							$sect1_result = mysqli_query($gconnet,$sect1_sql);
								for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
									$row1 = mysqli_fetch_array($sect1_result);
							?>
								<option value="<?=$row1[cate_code1]?>"><?=$row1[cate_name1]?></option>
						<?}?>
						</select>
						</td>
						<th >게시판 코드</th>
						<td ><input type="text" style="width:40%;" name="board_code" onKeyup="checkNumber()" required="yes"  message="게시판 코드" value=""></td>
					</tr>

					<tr>
						<th >게시판 명</th>
						<td colspan="3"><input type="text" style="width:40%;" name="board_title" required="yes"  message="게시판 명" value=""></td>
					</tr>

					<tr>
						<th >게시판 형태</th>
						<td colspan="3"><input type="radio" name="close_ok" onclick="close_ck();" value="N" required="yes"  message="게시판 형태"> 일반형 게시판 &nbsp; <input type="radio" name="close_ok" onclick="close_ck();" value="Y" required="yes"  message="게시판 형태"> 과금형 게시판</td>
					</tr>

					<tr>
						<th >게시판 관리자</th>
						<td colspan="3"> 게시판 관리자로 지정할 회원의 아이디를 입력한 후 검색하세요 &nbsp; 
							<input type="text" style="width:30%;" name="board_master_id" id="board_master_id" required="no" message="게시판 관리자" onKeyup="auto_bmaster_code()" value=""> <a href="javascript:bmaster_select();" class="btn_blue2">검색</a> <span id="bmaster_name_txt"></span>
							<!-- 자동검색 결과 출력 시작 -->
							<div id="auto_bmaster_view" style="display:none;padding-top:0px;padding-left:330px;">
								<span  id="auto_bmaster_list"></span>
							</div>
							<!-- 자동검색 결과 출력 시작 -->
						</td>
					</tr>
					<!--
					<tr id="auto_bmaster_view" style="display:none">
						<td colspan="4" id="auto_bmaster_list"></td>
					</tr>
				-->
					<tr>
						<th >게시판 설명</th>
						<td colspan="3">
							<textarea style="width:90%;height:50px;" name="board_info" required="no"  message="게시판 설명" value=""></textarea>
						</td>
					</tr>
					<tr>
						<th >게시판 운영원칙</th>
						<td colspan="3">
							<textarea style="width:90%;height:50px;" name="board_principle" required="no"  message="게시판 운영원칙" value=""></textarea>
						</td>
					</tr>
				<!--
					<tr>
						<th >타이틀 이미지</th>
						<td ><input type="file" style="width:40%;" required="no" message="첨부파일" name="file1"></td>
						<th >서브타이틀 이미지</th>
						<td ><input type="file" style="width:40%;" required="no" message="첨부파일" name="file2"></td>
					</tr>
				-->	
					<tr id="close_ck_text_1">
						<th >리스트 보기권한</th>
						<td >
							<select name="list_auth" required="no" message="리스트 보기권한" size="1" style="vertical-align:middle;" >
							<option value="">리스트보기 등급선택</option>
							<?
								$sub_sql = "select idx,level_code,level_name from member_level_set where 1=1 and is_del = 'N' order by level_align desc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
							?>
								<option value="<?=$sub_row[level_code]?>" <?=$row[list_auth]==$sub_row[level_code]?"selected":""?>><?=$sub_row[level_name]?></option>
							<?}?>
							<option value="NM" <?=$row[list_auth]=="NM"?"selected":""?>>비회원</option>
							</select>
						</td>
						<th >본문 보기권한</th>
						<td >
							<select name="view_auth" required="no" message="본문 보기권한" size="1" style="vertical-align:middle;" >
							<option value="">본문보기 등급선택</option>
							<?
								$sub_sql = "select idx,level_code,level_name from member_level_set where 1=1 and is_del = 'N' order by level_align desc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
							?>
								<option value="<?=$sub_row[level_code]?>" <?=$row[view_auth]==$sub_row[level_code]?"selected":""?>><?=$sub_row[level_name]?></option>
							<?}?>
							<option value="NM" <?=$row[view_auth]=="NM"?"selected":""?>>비회원</option>
							</select>
						</td>
					</tr>

					<tr id="close_ck_text_2">
						<th >본문 쓰기권한</th>
						<td >
							<select name="write_auth" required="no" message="본문 쓰기권한" size="1" style="vertical-align:middle;" >
							<option value="">본문글쓰기 등급선택</option>
							<option value="AD" <?=$row[write_auth]=="AD"?"selected":""?>>총관리자</option>
							<option value="BA" <?=$row[write_auth]=="BA"?"selected":""?>>게시판 관리자</option>
							<?
								$sub_sql = "select idx,level_code,level_name from member_level_set where 1=1 and is_del = 'N' order by level_align desc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
							?>
								<option value="<?=$sub_row[level_code]?>" <?=$row[write_auth]==$sub_row[level_code]?"selected":""?>><?=$sub_row[level_name]?></option>
							<?}?>
							<option value="NM" <?=$row[write_auth]=="NM"?"selected":""?>>비회원</option>
							</select>
						</td>
						<th >덧글 쓰기권한</th>
						<td >
							<select name="reply_auth" required="no" message="덧글 쓰기권한" size="1" style="vertical-align:middle;" >
							<option value="">덧글글쓰기 등급선택</option>
							<option value="AD" <?=$row[reply_auth]=="AD"?"selected":""?>>총관리자</option>
							<option value="BA" <?=$row[reply_auth]=="BA"?"selected":""?>>게시판 관리자</option>
							<?
								$sub_sql = "select idx,level_code,level_name from member_level_set where 1=1 and is_del = 'N' order by level_align desc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
							?>
								<option value="<?=$sub_row[level_code]?>" <?=$row[reply_auth]==$sub_row[level_code]?"selected":""?>><?=$sub_row[level_name]?></option>
							<?}?>
							<option value="NM" <?=$row[reply_auth]=="NM"?"selected":""?>>비회원</option>
							</select>
						</td>
					</tr>

					<tr id="close_ck_text_3">
						<th >나이별 참여제한</th>
						<td > 만 <input type="text" style="width:10%;" name="entry_age" required="no"  message="나이별 참여제한" is_num="yes" value=""> 세 이상만 참여가능</td>
						<th >성별 참여제한</th>
						<td ><input type="radio" name="entry_gender" value="M" required="no" message="성별 참여제한"> 남성회원만 참여가능 &nbsp; <input type="radio" name="entry_gender" value="F" required="no" message="성별 참여제한"> 여성회원만 참여가능</td>
					</tr>

					<tr>
						<th >한줄댓글 기능</th>
						<td ><input type="radio" name="is_comment" value="Y" required="yes" message="한줄댓글 기능"> 한줄댓글쓰기 가능&nbsp; <input type="radio" name="is_comment" value="N" required="yes" message="한줄댓글 기능"> 한줄댓글쓰기 불가능 </td>
						<th >리스트 공지사항 기능</th>
						<td ><input type="radio" name="is_notice" value="Y" required="yes" message="리스트 공지사항 기능"> 리스트 공지사항 가능&nbsp; <input type="radio" name="is_notice" value="N" required="yes" message="리스트 공지사항 기능"> 리스트 공지사항 불가능 </td>
					</tr>
					
					<!--
					<tr>
						<th >게시판 형태</th>
						<td >
						<select name="board_cate" size="1" style="vertical-align:middle;" required="yes"  message="게시판 형태">
							<option value="">게시판 형태 설정</option>
							<option value="normal">일반형</option>
							<option value="pds">자료실형 (리스트에 첨부파일 아이콘)</option>
							<option value="faq">FAQ 게시판</option>
						</select>
						</td>
						<th >첨부파일 갯수</th>
						<td >한번에 <input type="text" style="width:10%;" name="file_cnt" required="yes"  message="첨부파일 갯수" is_num="yes" value=""> 개 까지 등록가능</td>
					</tr>
				-->

					
				
					<tr>
						<th>첨부파일 갯수</th>
						<td>한번에 <input type="text" style="width:10%;" name="file_cnt" required="yes"  message="첨부파일 갯수" is_num="yes" value=""> 개 까지 등록가능</td>
						<th>게시판 정렬순서</th>
						<td><input type="text" style="width:20%;" name="board_align" required="yes" message="정렬순서" is_num="yes" > 숫자만 입력, 높은숫자 우선</td>
					</tr>
				
			</table>
			</form>

			<div class="align_c margin_t20">
				<!-- 등록 -->
				<a href="javascript:go_submit();" class="btn_blue2">등록</a>
				<!-- 목록 -->
				<a href="javascript:go_list();" class="btn_blue2">취소</a>
			</div>
		</div>
	</div>
</section>
<!-- //content -->
<!--//js-->
<!--footer-->
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>