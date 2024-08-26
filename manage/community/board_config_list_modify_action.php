<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));

	$board_align = trim(sqlfilter($_REQUEST['board_align']));
	$is_del = trim(sqlfilter($_REQUEST['is_del']));

	$query = " update board_config set "; 
	$query .= " board_align = '".$board_align."', ";
	$query .= " is_del = '".$is_del."' ";
	$query .= " where 1=1 and idx = '".$idx."' ";
		
	//echo $query; exit;
	
	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('게시판 설정 수정이 정상적으로 완료 되었습니다.');
	parent.location.href =  "board_config_list.php?<?=$total_param?>&pageNo=<?=$pageNo?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('게시판 설정 수정중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
?>