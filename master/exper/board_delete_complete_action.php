<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
/*if(!$_AUTH_WRITE && !$_AUTH_REPLY){
	error_frame("삭제 권한이 없습니다.");
	exit;
}*/

$idx = trim(sqlfilter($_REQUEST['idx']));
$orgin_idx = trim(sqlfilter($_REQUEST['orgin_idx']));
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = sqlfilter($_REQUEST['smenu']);
$bbs_code = trim(sqlfilter($_REQUEST['bbs_code']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_cate_code = trim(sqlfilter($_REQUEST['s_cate_code'])); // 게시판 카테고리 코드
$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // 게시판 분류
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&s_cate_code='.$s_cate_code.'&v_sect='.$v_sect.'&pageNo='.$pageNo;

	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";
	$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE.$bbs_code."/";

	$sql_file = "select idx,file_org,file_chg from board_file where 1=1 and board_tbname='board_content' and board_code = '".$bbs_code."' and board_idx='".$idx."' order by idx asc ";
	$query_file = mysqli_query($gconnet,$sql_file);
	$cnt_file = mysqli_num_rows($query_file);

	for($i_file=0; $i_file<$cnt_file; $i_file++){
		$row_file = mysqli_fetch_array($query_file);
		$file_old_name = $row_file['file_chg'];

		if($file_old_name){
			unlink($_P_DIR_FILE.$file_old_name); // 완전삭제시 파일도 삭제한다. 
		}
	
	}

	$sql_file2 = "delete from board_file where 1=1 and board_tbname='board_content' and board_code = '".$bbs_code."' and board_idx='".$idx."' ";
	$query_file2 = mysqli_query($gconnet,$sql_file2); // 완전삭제시 첨부파일 테이블에서도 삭제한다.

	$sql_comment = "delete from board_comment where 1=1 and board_tbname='board_content' and board_code = '".$bbs_code."' and board_idx='".$idx."' ";
	$query_comment = mysqli_query($gconnet,$sql_comment); // 완전삭제시 한줄댓글 테이블에서도 삭제한다.

	$sql_cnt = "delete from board_view_cnt where 1=1 and board_tbname='board_content' and board_code = '".$bbs_code."' and board_idx='".$idx."' ";
	$query_cnt = mysqli_query($gconnet,$sql_cnt); // 완전삭제시 조회수 테이블에서도 삭제한다.

	$sql_reco = "delete from board_reco_cnt where 1=1 and board_tbname='board_content' and board_code = '".$bbs_code."' and board_idx='".$idx."' ";
	$query_reco = mysqli_query($gconnet,$sql_reco); // 완전삭제시 추천수 테이블에서도 삭제한다.
	
	$query = " delete from board_content "; 
	$query .= " where idx = '".$idx."' and bbs_code='".$bbs_code."' "; // 게시판 본문저장 테이블에서 완전히 삭제한다.  
	$result = mysqli_query($gconnet,$query); 

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('게시물을 완전히 삭제하였습니다.');
	<?if($orgin_idx){?>
		parent.location.href =  "board_view.php?idx=<?=$orgin_idx?>&<?=$total_param?>";
	<?}else{?>
		parent.location.href =  "board_list.php?<?=$total_param?>";
	<?}?>
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('완전삭제중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>