<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
if(!$_AUTH_WRITE && !$_AUTH_REPLY){
	error_frame("삭제 권한이 없습니다.");
	exit;
}

$board_idx = trim(sqlfilter($_REQUEST['board_idx']));
$idx = trim(sqlfilter($_REQUEST['idx']));
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = sqlfilter($_REQUEST['smenu']);
$bbs_code = trim(sqlfilter($_REQUEST['bbs_code']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_cate_code = trim(sqlfilter($_REQUEST['s_cate_code'])); // 게시판 카테고리 코드
$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // 게시판 분류

$dsect1 = trim(sqlfilter($_REQUEST['dsect1'])); // 커뮤니티 인가 그 외 게시판인가
$dsect2 = trim(sqlfilter($_REQUEST['dsect2'])); // 본문글인가 댓글인가

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&s_cate_code='.$s_cate_code.'&v_sect='.$v_sect.'&pageNo='.$pageNo.'&dsect1='.$dsect1.'&dsect2='.$dsect2;


	$is_del = "Y"; // 삭제표시

	$del_sect = "AD"; // 게시판 관리자가 숨기기
	$del_str = "댓글 숨기기";
	
	$query = " update board_comment set "; 
	$query .= " is_del = '".$is_del."', ";
	$query .= " del_sect = '".$del_sect."' ";
	$query .= " where idx = '".$idx."' and board_idx = '".$board_idx."' ";

	//echo $query; exit;

	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('<?=$del_str?> 가 정상적으로 완료 되었습니다.');
		//parent.location.href =  "view.php?idx=<?=$idx?>&<?=$total_param?>";
		window.parent.document.location.href = window.parent.document.URL;
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('<?=$del_str?> 중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>