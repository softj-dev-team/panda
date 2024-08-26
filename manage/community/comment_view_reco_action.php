<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
if(!$_AUTH_VIEW){
	error_back("본문보기 권한이 없습니다.");
	exit;
}

$board_idx = trim(sqlfilter($_REQUEST['board_idx']));
$comment_idx = trim(sqlfilter($_REQUEST['comment_idx']));
$bbs_code = trim(sqlfilter($_REQUEST['bbs_code']));

$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = sqlfilter($_REQUEST['smenu']);
$bbs_code = trim(sqlfilter($_REQUEST['bbs_code']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$pageNo_sub = trim(sqlfilter($_REQUEST['pageNo_sub']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_cate_code = trim(sqlfilter($_REQUEST['s_cate_code'])); // 게시판 카테고리 코드
$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // 게시판 분류
################## 파라미터 조합 #####################

$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&s_cate_code='.$s_cate_code.'&v_sect='.$v_sect.'&pageNo='.$pageNo.'&pageNo_sub='.$pageNo_sub;

$sql = "SELECT idx,board_code,member_idx FROM board_comment where 1=1 and board_tbname = 'board_content' and board_code = '".$bbs_code."' and board_idx='".$board_idx."' and idx='".$comment_idx."' ";
$query = mysqli_query($gconnet,$sql);

//echo $sql; exit;

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('해당하는 댓글이 없습니다.');
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

if($_SESSION['member_homest_idx'] == $row['member_idx']){
	error_frame("본인이 추천하실 수 없습니다.");
	exit;
} else {  // 작성자 본인이 추천하는것이 아닐때 시작
		
	$sql_prev = "select idx from board_reco_cnt where 1=1 and board_tbname='board_comment' and board_code = '".$row['board_code']."' and board_idx='".$row['idx']."' and member_idx = '".$_SESSION['member_homest_idx']."' ";
	$query_prev = mysqli_query($gconnet,$sql_prev);
	$cnt_prev = mysqli_num_rows($query_prev);

	if($cnt_prev == 0){ // 기존에 추천한 내용이 없을때 추천수 증가시킨다 시작 
			
			$query_reco_cnt = " insert into board_reco_cnt set "; 
			$query_reco_cnt .= " board_tbname = 'board_comment', ";
			$query_reco_cnt .= " board_code = '".$row['board_code']."', ";
			$query_reco_cnt .= " board_idx = '".$row['idx']."', ";
			$query_reco_cnt .= " member_idx = '".$_SESSION['member_homest_idx']."', ";
			$query_reco_cnt .= " reco = '1' ";
			$result_reco_cnt = mysqli_query($gconnet,$query_reco_cnt);

			$sql_cnt = "update board_comment set reco=reco+1 where 1=1 and idx = '".$row['idx']."'";
			$query_cnt = mysqli_query($gconnet,$sql_cnt);
	
	} else { // 기존에 추천한 내용이 없을때 추천수 증가시킨다 종료 
		error_frame("추천은 한 게시물당 한번만 하실 수 있습니다.");
		exit;
	}

}  // 작성자 본인이 열람하는것이 아닐때 종료
?>

<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('게시물 추천이 정상적으로 완료 되었습니다.');
		parent.location.href =  "board_view.php?idx=<?=$board_idx?>&<?=$total_param?>";
	//-->
</SCRIPT>
