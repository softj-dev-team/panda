<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/yonex_master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

	<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	
	$main_type = trim(sqlfilter($_REQUEST['main_type']));
	$view_ok = trim(sqlfilter($_REQUEST['view_ok']));
	$align = trim(sqlfilter($_REQUEST['align']));
			
	$query = " update main_display_set set ";
	$query .= " main_type = '".$main_type."', ";
	$query .= " view_ok = '".$view_ok."', ";
	$query .= " align = '".$align."' ";
	$query .= " where idx = '".$idx."' ";

	$result = mysqli_query($gconnet,$query);

	//echo $query; exit;

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 완료 되었습니다.');
	parent.location.href =  "mainban_list.php?<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
