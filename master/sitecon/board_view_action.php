<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$bbs_code = trim(sqlfilter($_REQUEST['bbs_code']));										//bbs_code
	$total_param = trim(sqlfilter($_REQUEST['total_param']));

	$is_use = trim(sqlfilter($_REQUEST['is_use']));
	
	$query = "  update board_content set "; 
	$query .= " pay_bak = '".$pay_bak."' ";
	$query .= " where idx = '".$idx."' and bbs_code='".$bbs_code."' ";

	//echo $query;

	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('원고료 지급 설정이 정상적으로 완료 되었습니다.');
	parent.location.href =  "board_view.php?idx=<?=$idx?>&bbs_code=<?=$bbs_code?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('원고료 지급설정중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
