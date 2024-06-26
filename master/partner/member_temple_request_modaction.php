<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$mode = trim(sqlfilter($_REQUEST['mode']));

	$apply_ok = trim(sqlfilter($_REQUEST['apply_ok']));
	
	$prev_sql = "select idx from member_temple_add where 1 and idx = '".$idx."'";
	$prev_query = mysqli_query($gconnet,$prev_sql);
	$prev_cnt = mysqli_num_rows($prev_query);
	
	if($prev_cnt == 0){
		error_frame("신청내역이 존재하지 않습니다.");
		exit;
	}

	$query = "update member_temple_add set";
	$query .= " apply_ok = '".$apply_ok."', ";
	if($apply_ok == "Y" || $apply_ok == "N"){
		$query .= " appdate = now(), ";
	}
	$query .= " admin_idx = '".$_SESSION['admin_coinc_idx']."', ";
	$query .= " admin_id = '".$_SESSION['admin_coinc_id']."', ";
	$query .= " admin_name = '".$_SESSION['admin_coinc_name']."' ";
	$query .= " where idx = '".$idx."' ";
	$result = mysqli_query($gconnet,$query);
		
	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('정상적으로 처리 되었습니다.');
		<?if($mode == "list"){?>
			parent.location.reload();
		<?}else{?>
			parent.temple_request_list();
		<?}?>
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
