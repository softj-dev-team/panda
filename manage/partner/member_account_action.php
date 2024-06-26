<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$v_sect = trim(sqlfilter($_REQUEST['v_sect']));
	$history_idx = trim(sqlfilter($_REQUEST['history_idx']));
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$temple_idx = trim(sqlfilter($_REQUEST['temple_idx']));
	$bank = trim(sqlfilter($_REQUEST['bank']));
	$account = trim(sqlfilter($_REQUEST['account']));
	$name = trim(sqlfilter($_REQUEST['name']));
	$member_sect = trim(sqlfilter($_REQUEST['member_sect']));


	$login_ok = trim(sqlfilter($_REQUEST['login_ok']));
	$master_ok = trim(sqlfilter($_REQUEST['master_ok']));
	$admin_memo = trim(sqlfilter($_REQUEST['admin_memo']));

	$ad_sect = $_SESSION['manage_coinc_id'];


	$prev_sql = "select *,(select user_name from member_info where idx = member_account.member_idx and del_yn = 'N' and member_account.is_del = 'N') as member_name, (select temple_title from temple_info where idx = member_account.temple_idx and  member_account.is_del = 'N') as temple_name from member_account where 1 and is_del='N' ";
	$prev_query = mysqli_query($gconnet,$prev_sql);
	$prev_cnt = mysqli_num_rows($prev_query);
	
	if($prev_cnt == 0){
		error_frame("설정을 변경할 회원이 존재하지 않습니다.");
		exit;
	}

	$query = " update member_account set "; 
	$query .= " member_idx = '".$member_idx."', ";
	$query .= " temple_idx = '".$temple_idx."', ";
	$query .= " member_sect = '".$member_sect."', ";
	$query .= " bank = '".$bank."', ";
	$query .= " account = '".$account."', ";
	$query .= " name = '".$name."', ";
	$query .= " is_ok = '".$master_ok."', ";
	$query .= " mdate =  now() ";
	$query .= " where idx = '".$idx."' ";
	
	//echo $query; exit;
	
	$result = mysqli_query($gconnet,$query);
	

	$query4 = " insert into member_account_history set ";
	$query4 .= " account_idx = '".$idx."', ";
	$query4 .= " member_idx = '".$member_idx."', ";
	$query4 .= " temple_idx = '".$temple_idx."', ";
	$query4 .= " member_sect = '".$member_sect."', ";
	$query4 .= " bank = '".$bank."', ";
	$query4 .= " account = '".$account."', ";
	$query4 .= " name = '".$name."', ";
	$query4 .= " is_ok = '".$master_ok."', ";
	$query4 .= " cancel_reason = '".$admin_memo."', ";
	$query4 .= " wdate =  now() ";
	
	//echo $query; exit;
	
	$result4 = mysqli_query($gconnet,$query4);

		
	if($result4){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 처리 되었습니다.');
	parent.location.href =  "member_account_view.php?idx=<?=$idx?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
