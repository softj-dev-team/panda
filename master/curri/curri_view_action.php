<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
		
	$view_ok = trim(sqlfilter($_REQUEST['view_ok']));
	$align = trim(sqlfilter($_REQUEST['align']));
	$admin_memo = trim(sqlfilter($_REQUEST['admin_memo']));

	$view_admin_id = $_SESSION['admin_coinc_id'];

	$prev_sql = "select idx from curri_info where 1=1 and idx = '".$idx."' and is_del = 'N' ";
	$prev_query = mysqli_query($gconnet,$prev_sql);
	$prev_cnt = mysqli_num_rows($prev_query);
	
	if($prev_cnt == 0){
		error_frame("설정을 변경할 프로그램이 존재하지 않습니다.");
		exit;
	}
	
	if($view_ok == "Y"){
		$query2 = " update curri_info set ";
		$query2 .= " view_ok = '".$view_ok."', ";
		$query2 .= " view_admin_id = '".$view_admin_id."', ";
		$query2 .= " vdate = now() ";
		$query2 .= " where idx = '".$idx."' and view_ok = 'N'";
		$result2 = mysqli_query($gconnet,$query2);
	} elseif($view_ok == "N"){
		$query2 = " update curri_info set ";
		$query2 .= " view_ok = '".$view_ok."', ";
		$query2 .= " view_admin_id = NULL, ";
		$query2 .= " vdate = NULL ";
		$query2 .= " where idx = '".$idx."'";
		$result2 = mysqli_query($gconnet,$query2);
	}

	$query = " update curri_info set ";
	$query .= " align = '".$align."', ";
	$query .= " admin_memo = '".$admin_memo."' ";
	$query .= " where idx = '".$idx."' ";
	$result = mysqli_query($gconnet,$query);
		
	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 처리 되었습니다.');
	parent.location.href =  "curri_view.php?idx=<?=$idx?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
