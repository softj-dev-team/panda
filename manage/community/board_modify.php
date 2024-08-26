<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>

<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/admin_top.php"; // 관리자페이지 상단메뉴?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/community_left.php"; // 게시판관리 좌측메뉴?>

<?
if(!$_AUTH_WRITE && !$_AUTH_REPLY){
	error_back("수정 권한이 없습니다.");
	exit;
}
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$bbs_code = sqlfilter($_REQUEST['bbs_code']);
$s_cate_code = trim(sqlfilter($_REQUEST['s_cate_code'])); // 게시판 카테고리 코드
$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // 게시판 분류
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&s_cate_code='.$s_cate_code.'&v_sect='.$v_sect.'&pageNo='.$pageNo;

$sql = "SELECT * FROM board_content where 1=1 and idx = '".$idx."' and bbs_code='".$bbs_code."' ";
$query = mysqli_query($gconnet,$sql);

//echo $sql; exit;

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('해당하는 게시물이 없습니다.');
	location.href =  "board_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

if($s_cate_code) {
	$sql_sub1 = "select cate_name1 from board_cate where cate_code1='".$s_cate_code."' and cate_level='1' ";
	$query_sub1 = mysqli_query($gconnet,$sql_sub1);
	$row_sub1 = mysqli_fetch_array($query_sub1);
	$bbs_cate_name = $row_sub1['cate_name1'];
}

if($bbs_code){
	$bbs_str = $bbs_cate_name." >> ".$_include_board_board_title;
} elseif($s_cate_code) {
	$bbs_str = $bbs_cate_name." 카테고리에 해당하는 ";
}

?>

<script type="text/javascript" src="/Cheditor/cheditor.js"></script> <!-- CHEditor 관련 스크립트  -->

<script language="JavaScript"> 
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			myeditor.outputBodyHTML(); // fm_post 에 작성한 내용 입력.
			frm.submit();
		} else {
			false;
		}
	}
	
	function go_list(){
		location.href = "board_view.php?idx=<?=$idx?>&<?=$total_param?>";
	}
	
</script>

<!-- content -->
<section id="content">
	<div class="inner">
		<h3><?=$bbs_str?> 게시물내용 수정</h3>
		<div class="cont">
			
			<form name="frm" action="board_modify_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
			<input type="hidden" name="idx" value="<?=$idx?>"/>
			<input type="hidden" name="bbs_code" value="<?=$bbs_code?>"/>
			<input type="hidden" name="total_param" value="<?=$total_param?>"/>

			<input type="hidden" name="is_html" value="Y">
			<input type="hidden" name="passwd" value="<?=$row[passwd]?>">
			
			<table class="t_view">
				<colgroup>
					<col width="10%" />
					<col width="40%" />
					<col width="10%" />
					<col width="40%" />
				</colgroup>
				
				<?//if($_include_board_is_notice == "Y"){ // 리스트 공지사항 보기가 가능한 게시판에 한하여 시작 ?>	
					<!--<tr>
						<th >공지글 지정</th>
						<td ><input type="checkbox" name="is_popup" value="Y" <?if(trim($row[is_popup]) == "Y"){?>checked<?}?>> 공지글로 지정함</td>
					</tr>-->
				<?//}?>

					<tr>
						<th >제 목</th>
						<td colspan="3"><input type="text" style="width:50%;" name="subject" required="yes"  message="게시물제목" value="<?=$row[subject]?>"></td>
					</tr>
				
				<?if($row['step'] == 0){?>
					<tr>
						<th >작성자</th>
						<td ><input type="text" style="width:20%;" name="writer" required="yes"  message="작성자" value="<?=$row[writer]?>"></td>
						<th >분류</th>
						<td >
							<select name="bbs_sect" size="1" style="vertical-align:middle;" required="yes"  message="게시판 분류">
								<option value="">게시판 분류</option>
								<option value="info" <?=$row[bbs_sect]=="info"?"selected":""?>>정보</option>
								<option value="quest" <?=$row[bbs_sect]=="quest"?"selected":""?>>질문</option>
								<option value="free" <?=$row[bbs_sect]=="free"?"selected":""?>>자유</option>
								<option value="comp" <?=$row[bbs_sect]=="comp"?"selected":""?>>불만</option>
								<option value="add" <?=$row[bbs_sect]=="add"?"selected":""?>>홍보</option>
								<option value="notice" <?=$row[bbs_sect]=="notice"?"selected":""?>>공지</option>
							</select>
						</td>
					</tr>
				<?}else{?>
					<input type="hidden" name="bbs_sect" value="<?=$row['bbs_sect']?>">
					<tr>
						<th >작성자</th>
						<td colspan="3"><input type="text" style="width:10%;" name="writer" required="yes"  message="작성자" value="<?=$row[writer]?>"></td>
					</tr>
				<?}?>
															
					<tr>
						<th >내 용</th>
						<td colspan="3">
						<textarea id="fm_post" name="fm_write"><?=stripslashes($row[content])?></textarea>
						<!-- 에디터를 화면에 출력합니다. -->
						<script type="text/javascript">
							var myeditor = new cheditor();              // 에디터 개체를 생성합니다.
							myeditor.config.editorHeight = '300px';     // 에디터 세로폭입니다.
							myeditor.config.editorWidth = '80%';        // 에디터 가로폭입니다.
							myeditor.inputForm = 'fm_post';             // textarea의 id 이름입니다. 주의: name 속성 이름이 아닙니다.
							myeditor.run();                             // 에디터를 실행합니다.
						</script>
						</td>
					</tr>

					<tr>
						<th >Tag</th>
						<td colspan="3"><input type="text" style="width:40%;" name="bbs_tag" required="yes"  message="게시물 Tag" value="<?=$row[bbs_tag]?>"></td>
					</tr>

					<tr>
						<th >스크랩 허용</th>
						<td ><input type="checkbox" name="scrap_ok" required="no"  message="스크랩 허용" value="Y" <?=$row[scrap_ok]=="Y"?"checked":""?>> 허용</td>
						<th >CCL (저작권) 설정</th>
						<td ><input type="checkbox" name="ccl_ok" required="no"  message="CCL (저작권) 설정" value="Y" <?=$row[ccl_ok]=="Y"?"checked":""?>> 설정</td>
					</tr>

					<?
						$sql_file = "select idx,file_org,file_chg from board_file where 1=1 and board_tbname='board_content' and board_code = '".$row['bbs_code']."' and board_idx='".$row['idx']."' order by idx asc ";
						$query_file = mysqli_query($gconnet,$sql_file);
						$cnt_file = mysqli_num_rows($query_file);

						if($cnt_file < $_include_board_file_cnt){
							$cnt_file = $_include_board_file_cnt;
						}
						
						for($i_file=0; $i_file<$cnt_file; $i_file++){
							$row_file = mysqli_fetch_array($query_file);
							$k_file = $i_file+1;
					?>
						
						<input type="hidden" name="file_idx_<?=$i_file?>" value="<?=$row_file['idx']?>" />
						<input type="hidden" name="file_old_name_<?=$i_file?>" value="<?=$row_file['file_chg']?>" />
						<input type="hidden" name="file_old_org_<?=$i_file?>" value="<?=$row_file['file_org']?>" />
						
						<tr>
							<th >첨부파일 <?=$k_file?></th>
							<td colspan="3">
								<input type="file" style="width:400px;" required="no" message="첨부파일" name="file_<?=$i_file?>">
								<?if($row_file['file_chg']){?>
									기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=<?=$row['bbs_code']?>"><?=$row_file['file_org']?></a>
									(기존파일 삭제 : <input type="checkbox" name="del_org_<?=$i_file?>" value="Y">)
								<?} else{ ?>
									<input type="hidden" name="del_org_<?=$i_file?>" value="">
								<?}?>
							</td>
						</tr>
					
					<?}?>
														
			</table>
			</form>

			<div class="align_c margin_t20">
				<!-- 등록 -->
				<a href="javascript:go_submit();" class="btn_blue2">수정</a>
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