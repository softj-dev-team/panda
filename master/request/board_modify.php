<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$orgin_idx = trim(sqlfilter($_REQUEST['orgin_idx']));

$s_cate_code = trim(sqlfilter($_REQUEST['s_cate_code'])); // 게시판 카테고리 코드
$bbs_code = trim(sqlfilter($_REQUEST['bbs_code'])); // 게시판 코드
$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // 게시판 분류
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); // 지역 시,도
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); // 지역 구,군
$s_level = sqlfilter($_REQUEST['s_level']); // 회원 계급별 검색
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별 검색

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&v_sect='.$v_sect.'&s_cate_code='.$s_cate_code.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_gender='.$s_gender.'&s_level='.$s_level.'&pageNo='.$pageNo;

if($orgin_idx){
	$sql = "SELECT * FROM board_content where 1=1 and idx = '".$idx."' ";
} else {
	$sql = "SELECT * FROM board_content where 1=1 and idx = '".$idx."' and bbs_code='".$bbs_code."'";
}
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
$cell_arr = explode("-",$row['1vs1_cell']);

if($orgin_idx){
	$sql_answer = "select subject from board_content where idx = '".$orgin_idx."' ";
	$query_answer = mysqli_query($gconnet,$sql_answer);
	$answer_row = mysqli_fetch_array($query_answer);
	$answer_title = $answer_row[subject];
}

$bbs_str = get_request_name($v_sect);
?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/request_left.php"; // 좌측메뉴?>
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>의뢰내용 관리</li>
						<li><?=$bbs_str?></li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$bbs_str?> 수정</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="write">
							
			<form name="frm" action="board_modify_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
			<input type="hidden" name="idx" value="<?=$idx?>"/>
			<input type="hidden" name="orgin_idx" value="<?=$orgin_idx?>"/>
			<input type="hidden" name="bbs_code" value="<?=$row['bbs_code']?>"/>
			<input type="hidden" name="bbs_sect" id="bbs_sect" value="<?=$row['bbs_sect']?>"/>
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
				
				<tr>
						<th scope="row">키워드</th>
						<td colspan="3">
					<?
						$board_cate_query = "select cate_code1,cate_name1 from common_code where 1 and type='request' and cate_level = '1' and is_del='N' order by cate_align desc"; 
						$board_cate_result = mysqli_query($gconnet,$board_cate_query);
						for ($catei=0; $catei<mysqli_num_rows($board_cate_result); $catei++){
							$board_cate_row = mysqli_fetch_array($board_cate_result);
					?>
							<input type="checkbox" name="board_cate_<?=($catei+1)?>" id="board_cate_<?=fnzero(($catei+1))?>" value="<?=$board_cate_row['cate_code1']?>" required="no" message="키워드" <?if(is_board_info_add($row['idx'],"cate",$board_cate_row['cate_code1']) == "Y"){?>checked<?}?>> <?=$board_cate_row['cate_name1']?>
					<?}?>
						</td>
					</tr>
					<tr>
						<th>등록회원</th>
						<td colspan="3">
							<select name="member_idx" size="1" style="vertical-align:middle;" required="yes" message="등록회원">
								<option value="">선택하세요</option>
								<?
								$sub_sql = "select idx,user_name from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and del_yn='N' and member_type in ('PAT','GEN') order by user_name asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
								?>
									<option value="<?=$sub_row[idx]?>" <?=$row[member_idx]==$sub_row[idx]?"selected":""?>><?=$sub_row[user_name]?></option>
								<?}?>		
							</select>
						</td>
					</tr>
				<?if($orgin_idx){?>
					<input type="hidden" name="subject" value="<?=$row[subject]?>"/>
				<?}else{?>
					<tr>
						<th >제 목</th>
						<td colspan="3"><input type="text" style="width:50%;" name="subject" required="yes"  message="제 목" value="<?=$row[subject]?>"></td>
					</tr>
				<?}?>
					<tr>
						<th>가용예산</th>
						<td>
							<input type="text" style="width:40%;" name="forecast_pay" required="no" is_num="yes" message="가용예산" value="<?=$row[forecast_pay]?>"> 원 
						</td>
						<th>예상기간</th>
						<td>
							<input type="text" style="width:40%;" name="forecast_period" required="no" is_num="yes" message="예상기간" value="<?=$row[forecast_period]?>"> 개월 미만 
						</td>
					</tr>
					<tr>
						<th>내 용</th>
						<td colspan="3">
							<textarea placeholder="내용" name="ir2" id="editor"><?=stripslashes($row[content])?></textarea>
						</td>
					</tr>
								
				<?if(!$orgin_idx){?>
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
							<th >대표이미지 <?//=$k_file?></th>
							<td colspan="3">
								<input type="file" style="width:400px;" required="no" message="첨부파일" name="file_<?=$i_file?>"> (최적화 사이즈 : 가로 300, 세로 235 픽셀)
								<?if($row_file['file_chg']){?>
									기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=<?=$row['bbs_code']?>"><?=$row_file['file_org']?></a>
									(기존파일 삭제 : <input type="checkbox" name="del_org_<?=$i_file?>" value="Y">)
								<?} else{ ?>
									<input type="hidden" name="del_org_<?=$i_file?>" value="">
								<?}?>
							</td>
						</tr>
					
					<?}?>
				<?}?>											
			</table>
			</form>

					<div class="write_btn align_r mt35">
						<button class="btn_modify" onclick="go_submit();">수정하기</button>
						<a href="javascript:go_list();" class="btn_list">취소</a>
						<!--<button class="btn_del">취소</button>-->
					</div>

				</div>
			</div>
		</div>

<script type="text/javascript" src="/smarteditor2/js/HuskyEZCreator.js" charset="utf-8"></script>
<script language="JavaScript"> 

	var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "editor",
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
	});

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			oEditors.getById["editor"].exec("UPDATE_CONTENTS_FIELD", []);
			frm.submit();
		} else {
			false;
		}
	}
	
	function go_list(){
		location.href = "board_view.php?idx=<?=$idx?>&<?=$total_param?>";
	}

	function cate_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		//alert(tmp);
		_fra_admin.location.href="../partner/cate_select_1.php?cate_code1="+tmp+"&fm=frm&fname=gugun";
	}
	
</script>


<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>

	