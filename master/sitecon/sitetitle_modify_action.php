<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?	
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$set_title = trim(sqlfilter($_REQUEST['set_title']));
	$set_keyword = trim(sqlfilter($_REQUEST['set_keyword']));
	$mem_gen_payment = trim(sqlfilter($_REQUEST['mem_gen_payment']));

	$wdate = date("Y-m-d H:i:s");
		
	$query = " update sitetitle_set set "; 
	$query .= " set_title = '".$set_title."', ";
	$query .= " set_keyword = '".$set_keyword."', ";
	$query .= " mem_gen_payment = '".$mem_gen_payment."', ";
	$query .= " wdate = '".$wdate."' ";
	$query .= " where idx = '".$idx."' ";
	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('사이트타이틀 설정이 정상적으로 완료 되었습니다.');
	parent.location.href =  "sitetitle_set.php?<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('사이트타이틀 설정중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>