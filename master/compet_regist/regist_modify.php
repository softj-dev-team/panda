<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 공모전주
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 게시 시작일
$s_level = sqlfilter($_REQUEST['s_level']); // 게시 종료일
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order.'&pageNo='.$pageNo;

$sql = "SELECT * FROM compet_regist_info where 1 and idx = '".$idx."' and is_del = 'N'";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('등록된 공모전이 없습니다.');
	location.href =  "regist_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);
$bbs_code = "compet_regist_info";
//if($row['member_idx'] != $_SESSION['manage_coinc_idx']) {
?>
<!--<SCRIPT LANGUAGE="JavaScript">
	
	alert('등록된 공모전가 없습니다.');
	location.href =  "regist_list.php?<?=$total_param?>";
	//
</SCRIPT>-->
<?
//exit;
//}
?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/regist_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>참가작 관리</li>
						<li>참가작 수정</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>참가작 수정</h3>
				</div>
				<div class="write">

				<form name="frm" action="regist_modify_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="idx" id="regist_idx" value="<?=$idx?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<table>
						<caption>공모전정보 수정</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
					<tr>
						<th>디자이너 회원</th>
						<td colspan="3">
							<select name="member_idx" size="1" style="vertical-align:middle;" required="yes" message="디자이너 회원" onchange="select_mem_value(this)">
								<option value="">디자이너 회원 선택</option>
								<?
								$sub_sql = "select idx,user_name,user_id from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and member_type='GEN' order by user_name asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
								?>
									<option value="<?=$sub_row[idx]?>" <?=$row['member_idx']==$sub_row[idx]?"selected":""?>><?=$sub_row[user_name]?></option>
								<?}?>		
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row">디자이너 닉네임</th>
						<td colspan="3">
							<input type="text" style="width:30%;" name="member_name" id="member_name" required="yes" message="디자이너 닉네임" value="<?=$row['member_name']?>">
						</td>
					</tr>
					<tr>
						<th scope="row">작품 제목</th>
						<td colspan="3"><input type="text" style="width:70%;" name="work_title" id="work_title" required="yes" message="작품 제목" value="<?=$row['work_title']?>"></td>
					</tr>
					<tr>
						<th scope="row">스톡컨텐츠 여부</th>
						<td colspan="3">
							<input type="radio" name="stock_ok" value="Y" required="yes"  message="스톡컨텐츠 여부" id="stock_ok_0" <?=$row['stock_ok']=="Y"?"checked":""?>> <label for="stock_ok_0">스톡컨텐츠를 사용 하였습니다.</label>
							<input type="radio" name="stock_ok" value="N" required="yes"  message="스톡컨텐츠 여부" id="stock_ok_1" <?=$row['stock_ok']=="N"?"checked":""?>> <label for="stock_ok_1">스톡컨텐츠를 사용하지 않았습니다.</label>
						</td>
					</tr>
					<tr>
						<th scope="row">작품설명</th>
						<td colspan="3">
							<textarea style="width:90%;height:80px;" name="work_detail" id="work_detail" required="yes" message="작품설명" value=""><?=$row['work_detail']?></textarea>
						</td>
					</tr>
					<?
						$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='compet_regist_info' and board_code='list' and board_idx='".$row['idx']."' order by idx asc ";
						$query_file = mysqli_query($gconnet,$sql_file);
						$cnt_file = mysqli_num_rows($query_file);

						if($cnt_file < 1){
							$cnt_file = 1;
						}
						
						for($i_file=0; $i_file<$cnt_file; $i_file++){
							$row_file = mysqli_fetch_array($query_file);
							$k_file = $i_file+1;
					?>
						
						<input type="hidden" name="pfile_idx_<?=$i_file?>" value="<?=$row_file['idx']?>" />
						<input type="hidden" name="pfile_old_name_<?=$i_file?>" value="<?=$row_file['file_chg']?>" />
						<input type="hidden" name="pfile_old_org_<?=$i_file?>" value="<?=$row_file['file_org']?>" />
						
						<tr>
							<th>미리보기 이미지</th>
							<td colspan="3">
								<input type="file" style="width:400px;" required="no" message="첨부파일" name="photo_<?=$i_file?>">  * 정사각형 섬네일 이미지. 권장사이즈 600*600px 
								<?if($row_file['file_chg']){?>
									<br>기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=compet_regist_info"><?=$row_file['file_org']?></a>
									(기존파일 삭제 : <input type="checkbox" name="pdel_org_<?=$i_file?>" value="Y">)
								<?} else{ ?>
									<input type="hidden" name="pdel_org_<?=$i_file?>" value="">
								<?}?>
							</td>
						</tr>
					<?}?>	
					<?
						$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='compet_regist_info' and board_code='detail' and board_idx='".$row['idx']."' order by idx asc ";
						$query_file = mysqli_query($gconnet,$sql_file);
						$cnt_file = mysqli_num_rows($query_file);

						if($cnt_file < 1){
							$cnt_file = 1;
						}
						
						for($i_file=0; $i_file<$cnt_file; $i_file++){
							$row_file = mysqli_fetch_array($query_file);
							$k_file = $i_file+1;
					?>
						
						<input type="hidden" name="addpfile_idx_<?=$i_file?>" value="<?=$row_file['idx']?>" />
						<input type="hidden" name="addpfile_old_name_<?=$i_file?>" value="<?=$row_file['file_chg']?>" />
						<input type="hidden" name="addpfile_old_org_<?=$i_file?>" value="<?=$row_file['file_org']?>" />
						<tr>
							<th>상세작품 이미지</th>
							<td colspan="3">
								<input type="file" style="width:400px;" required="no" message="첨부파일" name="addphoto_<?=$i_file?>"> * RGB 형식의 JPG 파일. 가로 1024px, 세로 자유.
								<?if($row_file['file_chg']){?>
									<br>기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=compet_regist_info"><?=$row_file['file_org']?></a>
									(기존파일 삭제 : <input type="checkbox" name="addpdel_org_<?=$i_file?>" value="Y">)
								<?} else{ ?>
									<input type="hidden" name="pdel_org_<?=$i_file?>" value="">
								<?}?>
							</td>
						</tr>
					<?}?>	
					</table>
					</form>
					<div class="write_btn align_r">
						<a href="javascript:go_list();" class="btn_list">취소</a>
						<button class="btn_modify" type="button" onclick="go_submit();">정보수정</button>
					</div>
				</div>
			<!-- content 종료 -->
	</div>
</div>

<script type="text/javascript">

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}

	function go_list(){
		location.href = "regist_view.php?idx=<?=$idx?>&<?=$total_param?>";
	}

function select_mem_value(z){
	var tmp = z.options[z.selectedIndex].value; 
	//alert(tmp);
	_fra_admin.location.href="select_mem_value.php?member_idx="+tmp+"";
}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>