<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$prev_reply_ok = trim(sqlfilter($_REQUEST['prev_reply_ok']));
	
	$reply_ok = trim(sqlfilter($_REQUEST['reply_ok']));
	$admin_memo = trim(sqlfilter($_REQUEST['admin_memo']));

	$ad_sect_id = $_SESSION['admin_coinc_id'];
	$ad_sect_name = $_SESSION['admin_coinc_name'];

	$prev_sql = "select idx from site_contact_add where 1 and idx = '".$idx."'";
	$prev_query = mysqli_query($gconnet,$prev_sql);
	$prev_cnt = mysqli_num_rows($prev_query);
	
	if($prev_cnt == 0){
		error_frame("설정할 내용이 없습니다.");
		exit;
	}

	if(($reply_ok == "Y" || $reply_ok == "N") && $prev_reply_ok != $reply_ok){
		$query1 = " update site_contact_add set ";
		$query1 .= " reply_ok = '".$reply_ok."', ";
		$query1 .= " replydate = now(), ";
		$query1 .= " ad_sect_id = '".$ad_sect_id."', ";
		$query1 .= " ad_sect_name = '".$ad_sect_name."' ";
		$query1 .= " where idx = '".$idx."'";
		$result1 = mysqli_query($gconnet,$query1);
	}

	$query2 = " update site_contact_add set ";
	$query2 .= " admin_memo = '".$admin_memo."' ";
	$query2 .= " where idx = '".$idx."' ";
	$result2 = mysqli_query($gconnet,$query2);
	
	if($result2){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 완료 되었습니다.');
	parent.location.href =  "con_view.php?idx=<?=$idx?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
