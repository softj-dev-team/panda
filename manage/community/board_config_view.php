<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>

<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/admin_top.php"; // 관리자페이지 상단메뉴?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/community_left.php"; // 게시판관리 좌측메뉴?>

<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_sect1 = sqlfilter($_REQUEST['s_sect1']);
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&pageNo='.$pageNo;

$sql = "SELECT * FROM board_config where 1=1 and idx = '".$idx."' ";
$query = mysqli_query($gconnet,$sql);

//echo $sql; exit;

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('해당하는 게시판이 없습니다.');
	location.href =  "board_config_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

$sql_sub1 = "select cate_name1 from board_cate where cate_code1='".$row[cate1]."' and cate_level='1' ";
$query_sub1 = mysqli_query($gconnet,$sql_sub1);
$row_sub1 = mysqli_fetch_array($query_sub1);
$pro_sect1 = $row_sub1[cate_name1];

if($row[list_auth] == "NM"){
	$list_auth = "비회원까지 모두 가능";
} else {
	$member_level_sql = "select level_name from member_level_set where 1=1 and level_code = '".$row[list_auth]."' ";   
	$member_level_query = mysqli_query($gconnet,$member_level_sql);
	$member_level_row = mysqli_fetch_array($member_level_query);
	$list_auth = $member_level_row['level_name']." 회원 이상 가능";
}

if($row[view_auth] == "NM"){
	$view_auth = "비회원까지 모두 가능";
} else {
	$member_level_sql = "select level_name from member_level_set where 1=1 and level_code = '".$row[view_auth]."' ";   
	$member_level_query = mysqli_query($gconnet,$member_level_sql);
	$member_level_row = mysqli_fetch_array($member_level_query);
	$view_auth = $member_level_row['level_name']." 회원 이상 가능";
}

if($row[write_auth] == "AD"){
	$write_auth = "총 관리자만 가능";
} elseif($row[write_auth] == "BA"){
	$write_auth = "게시판 관리자 가능";
} elseif($row[write_auth] == "NM"){
	$write_auth = "비회원까지 모두 가능";
} else {
	$member_level_sql = "select level_name from member_level_set where 1=1 and level_code = '".$row[write_auth]."' ";   
	$member_level_query = mysqli_query($gconnet,$member_level_sql);
	$member_level_row = mysqli_fetch_array($member_level_query);
	$write_auth = $member_level_row['level_name']." 회원 이상 가능";
}

if($row[reply_auth] == "AD"){
	$reply_auth = "총 관리자만 가능";
} elseif($row[reply_auth] == "BA"){
	$reply_auth = "게시판 관리자 가능";
} elseif($row[reply_auth] == "NM"){
	$reply_auth = "비회원까지 모두 가능";
} else {
	$member_level_sql = "select level_name from member_level_set where 1=1 and level_code = '".$row[reply_auth]."' ";   
	$member_level_query = mysqli_query($gconnet,$member_level_sql);
	$member_level_row = mysqli_fetch_array($member_level_query);
	$reply_auth = $member_level_row['level_name']." 회원 이상 가능";
}

switch ($row[is_comment]) {

	case "Y" : 
		$is_comment = "한줄댓글 가능";
	break;

	case "N" : 
		$is_comment = "한줄댓글 불가능";
	break;
	
}

switch ($row[is_notice]) {

	case "Y" : 
		$is_notice = "리스트 공지사항 가능";
	break;

	case "N" : 
		$is_notice = "리스트 공지사항 불가능";
	break;
	
}

switch ($row[board_cate]) {

	case "normal" : 
		$board_cate = "일반형 게시판";
	break;

	case "pds" : 
		$board_cate = "자료실형 (리스트에 첨부파일 아이콘)";
	break;

	case "faq" : 
		$board_cate = "FAQ 게시판";
	break;
	
}

				if($row[close_ok] == "Y"){
						$close_ok = "과금형";
					} else {
						$close_ok = "일반형";
					}
?>
<!-- content -->
<script type="text/javascript">
<!--
function go_view(no){
		location.href = "board_config_view.php?idx="+no+"&<?=$total_param?>";
}
	
function go_modify(no){
		location.href = "board_config_modify.php?idx="+no+"&<?=$total_param?>";
}

function go_reply(no){
		location.href = "board_config_reply.php?idx="+no+"&<?=$total_param?>";
}

function go_delete(no){
	if(confirm('정말 삭제 하시겠습니까?')){

		//if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "board_config_delete_action.php?idx="+no+"&<?=$total_param?>";
		//}
	}
}

function go_list(){
	location.href = "board_config_list.php?<?=$total_param?>";
}

function go_submit() {
	var check = chkFrm('frm');
	if(check) {
		frm.submit();
	} else {
		false;
	}
}

function comment_delete(no){
	if(confirm('해당 댓글을 삭제하시겠습니까?')){
		_fra_admin.location.href = "delete_comment_action.php?view_no=<?=$idx?>&idx="+no+"&<?=$total_param?>";
	}
}
//-->		
</script>

<section id="content">
	<div class="inner">
		<h3><?=$row[board_title]?> 게시판 설정내용 상세보기</h3>
		<div class="cont">

		<div style="padding-top:10px;padding-bottom:10px;"><font style="color:blue;"><b>게시판 생성 정보</b></font></div> 

			<table class="t_view">
				<colgroup>
					<col width="10%" />
					<col width="40%" />
					<col width="10%" />
					<col width="40%" />
				</colgroup>
				
					<tr>
						<th >게시판 카테고리</th>
						<td ><?=$pro_sect1?></td>
						<th >게시판 코드</th>
						<td ><?=$row[board_code]?></td>
					</tr>

					<tr>
						<th >게시판 명</th>
						<td ><?=$row[board_title]?> &nbsp; <a href="board_list.php?s_cate_code=<?=$row[cate1]?>&bbs_code=<?=$row[board_code]?>" class="btn_blue2">바로가기</a></td>
						<th >게시판 형태</th>
						<td ><?=$close_ok?></td>
					</tr>
					<tr>
						<th >게시판 설명</th>
						<td colspan="3">
							<?=nl2br($row[board_info])?> 
						</td>
					</tr>
					<tr>
						<th >게시판 운영원칙</th>
						<td colspan="3">
							<?=nl2br($row[board_principle])?> 
						</td>
					</tr>
				<!--
					<tr>
						<th >타이틀 이미지</th>
						<td >
						<?if($row['file1_chg']){?>
								<a href="/pro_inc/download_file.php?nm=<?=$row['file1_chg']?>&on=<?=$row['file1_org']?>&dir=board_config"><?=$row['file1_org']?></a>
						<?}?>
						</td>
						<th >서브타이틀 이미지</th>
						<td >
						<?if($row['file2_chg']){?>
								<a href="/pro_inc/download_file.php?nm=<?=$row['file2_chg']?>&on=<?=$row['file2_org']?>&dir=board_config"><?=$row['file2_org']?></a>
						<?}?>
						</td>
					</tr>
				-->										
			<?	if($row[close_ok] == "N"){?>		
					<tr>
						<th >리스트 보기권한</th>
						<td ><?=$list_auth?></td>
						<th >상세내용 보기권한</th>
						<td ><?=$view_auth?></td>
					</tr>

					<tr>
						<th >본문쓰기권한</th>
						<td ><?=$write_auth?></td>
						<th >답글쓰기권한</th>
						<td ><?=$reply_auth?></td>
					</tr>

					<tr>
						<th >나이별 참여제한</th>
						<td >
							<?if($row[entry_age]){?>
								만 <?=$row[entry_age]?> 세 이상만 참여가능
							<?} else { ?>
								없음 
							<?}?>
						</td>
						<th >성별 참여제한</th>
						<td >
							<?if($row[entry_gender] == "M"){?>
								남성회원만 참여가능
							<?} elseif($row[entry_gender] == "F"){?>
								여성회원만 참여가능
							<?} else { ?>
								없음 
							<?}?>
						</td>
					</tr>
			<?}?>
					<tr>
						<th >한줄댓글 기능</th>
						<td ><?=$is_comment?></td>
						<th >리스트 공지사항 기능</th>
						<td ><?=$is_notice?></td>
					</tr>

					

					<!--<tr>
						<th >게시판 형태</th>
						<td ><?=$board_cate?></td>
						<th >첨부파일 갯수</th>
						<td >한번에 <?=$row[file_cnt]?> 개 까지 등록가능</td>
					</tr>-->

					<tr>
						<th >첨부파일 갯수</th>
						<td colspan="3">한번에 <?=$row[file_cnt]?> 개 까지 등록가능</td>
					</tr>

			</table>
		
		<?if($row[board_master_idx] > 0){ // 게시판 관리자 정보가 있을때 시작
				
				$sql_sub1 = "select user_id,user_name,user_nick,user_gubun,user_level,gender FROM member_info where 1=1 and idx='".$row[board_master_idx]."' and memout_yn = 'N' ";
				$query_sub1 = mysqli_query($gconnet,$sql_sub1);
				$cnt_sub1 = mysqli_num_rows($query_sub1);

		?>

			<?if($cnt_sub1 > 0){

					$row_sub1 = mysqli_fetch_array($query_sub1);

					$user_id = $row_sub1[user_id];
					$user_name = $row_sub1[user_name];

					if($row_sub1[user_gubun] == "PAT_B"){
						$user_gubun = "게시판운영 제휴회원";
					} elseif($row_sub1[user_gubun] == "PAT_S"){
						$user_gubun = "셀러 제휴회원";
					} elseif($row_sub1[user_gubun] == "PAT_SS"){
						$user_gubun = "파워셀러 제휴회원";
					}

					if($row_sub1[gender] == "M"){
						$gender = "남성";
					} elseif($row_sub1[gender] == "F"){
						$gender = "여성";	
					} 

					$level_sql = "select level_name from member_level_set where 1=1 and level_code = '".$row_sub1[user_level]."' ";   
					$level_query = mysqli_query($gconnet,$level_sql);
					$level_row = mysqli_fetch_array($level_query);
					$level_str = $level_row['level_name'];
				?>
			
				<div style="padding-top:10px;padding-bottom:10px;"><font style="color:blue;"><b>게시판 관리자 정보</b></font></div> 
			
				<table class="t_view">
					<colgroup>
						<col width="10%" />
						<col width="40%" />
						<col width="10%" />
						<col width="40%" />
					</colgroup>
				
						<tr>
							<th >게시판 관리자 아이디</th>
							<td ><?=$user_id?></td>
							<th >게시판 관리자 성명</th>
							<td ><?=$user_name?></td>
						</tr>
						<tr>
							<th >게시판 관리자 성별</th>
							<td ><?=$gender?></td>
							<th >게시판 관리자 닉네임</th>
							<td ><?=$row_sub1[user_nick]?></td>
						</tr>
						<tr>
							<th >게시판 관리자 구분</th>
							<td ><?=$user_gubun?></td>
							<th >게시판 관리자 계급</th>
							<td ><?=$level_str?></td>
						</tr>

					</table>

			<?}?>

		<? } // 게시판 관리자 정보가 있을때 종료 ?>

			<div class="align_c margin_t20">
				<!-- 목록 -->
				<a href="javascript:go_list();" class="btn_blue2">목록</a>
				<!-- 수정 -->
				<a href="javascript:go_modify('<?=$row[idx]?>');" class="btn_blue2">수정하기</a>
				<!-- 삭제 -->
				<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_blue2">삭제</a>	
			</div>
		</div>
	</div>
</section>
<!-- //content -->

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>