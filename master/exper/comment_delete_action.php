<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$comment_idx = trim(sqlfilter($_REQUEST['comment_idx']));

$board_tbname = "board_content";
$bbs_code = trim(sqlfilter($_REQUEST['bbs_code']));
$s_cate_code = trim(sqlfilter($_REQUEST['s_cate_code'])); // 게시판 카테고리 코드
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = sqlfilter($_REQUEST['smenu']);
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // 게시판 분류
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&v_sect='.$v_sect.'&s_cate_code='.$s_cate_code.'&pageNo='.$pageNo;

	// 첨부한 이미지 삭제
	$file_sql = "select file_chg from board_comment where 1=1 and idx = '".$comment_idx."' ";
	$file_query = mysqli_query($gconnet,$file_sql);
	$file_row = mysqli_fetch_array($file_query);
	$file_old_name1 = $file_row['file_chg'];
		
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";
	$_P_DIR_FILE2 = $_P_DIR_FILE."img_thumb/";
		
	if($file_old_name1){
		unlink($_P_DIR_FILE.$file_old_name1); // 
		unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제
	}

	$query = " delete from board_comment "; 
	$query .= " where idx = '".$comment_idx."' and board_tbname = '".$board_tbname."' and board_code = '".$bbs_code."' and board_idx = '".$idx."' ";

	//echo $query;

	$result = mysqli_query($gconnet,$query);
	
	//exit;

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('한줄댓글 삭제가 정상적으로 완료 되었습니다.');
	parent.location.href =  "board_view.php?idx=<?=$idx?>&bbs_code=<?=$bbs_code?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('한줄댓글 삭제중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>