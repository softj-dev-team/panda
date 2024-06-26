<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
if(!$_AUTH_WRITE && !$_AUTH_REPLY){
	error_frame("삭제취소 권한이 없습니다.");
	exit;
}

$idx = trim(sqlfilter($_REQUEST['idx']));
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

	/*$file_sql = "select file_c,file_c2 from board_content where 1=1 and idx = '".$idx."' and bbs_code = '".$bbs_code."' ";
	$file_query = mysqli_query($gconnet,$file_sql);
	$file_row = mysqli_fetch_array($file_query);
	$file_old_name1 = $file_row['file_c'];
	$file_old_name2 = $file_row['file_c2'];

	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";
	$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE.$bbs_code."/";
	
	if($file_old_name1){
		unlink($_P_DIR_FILE.$file_old_name1); // 
	}

	if($file_old_name2){
		unlink($_P_DIR_FILE.$file_old_name2); // 
	}

	$query = " delete from board_content "; 
	$query .= " where idx = '".$idx."' and bbs_code = '".$bbs_code."' "; */
	
	$is_del = ""; // 삭제표시
	$del_sect = ""; // 관리자가 삭제함

	$query = " update board_content set "; 
	$query .= " is_del = '".$is_del."', ";
	$query .= " del_sect = '".$del_sect."' ";
	$query .= " where idx = '".$idx."' and bbs_code='".$bbs_code."' ";

	//echo $query;

	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('숨김해제가 정상적으로 완료 되었습니다.');
		parent.location.href =  "board_view.php?idx=<?=$idx?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('숨김해제중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>