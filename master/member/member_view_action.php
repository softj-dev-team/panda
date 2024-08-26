<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$v_sect = trim(sqlfilter($_REQUEST['v_sect']));
	
	$user_level = trim(sqlfilter($_REQUEST['user_level']));
	$login_ok = trim(sqlfilter($_REQUEST['login_ok']));
	$master_ok = trim(sqlfilter($_REQUEST['master_ok']));
	$admin_memo = trim(sqlfilter($_REQUEST['admin_memo']));

	$ad_sect = $_SESSION['admin_coinc_id'];

	$prev_sql = "select idx,user_level from member_info where 1 and idx = '".$idx."' and del_yn = 'N'";
	$prev_query = mysqli_query($gconnet,$prev_sql);
	$prev_cnt = mysqli_num_rows($prev_query);
	
	if($prev_cnt == 0){
		error_frame("설정을 변경할 회원이 존재하지 않습니다.");
		exit;
	}

	$prev_row = mysqli_fetch_array($prev_query);
	$chuchun_idx7 = $prev_row[idx]; 

	/*if($prev_row['user_level'] != $user_level){ // 회원 레벨을 변경하고자 할때
		
		$set_section = "level";
		
		$query_change_level = " insert into member_set_change set "; 
		$query_change_level .= " member_idx = '".$idx."', ";
		$query_change_level .= " set_section = '".$set_section."', ";
		$query_change_level .= " prev_set = '".$prev_row['user_level']."', ";
		$query_change_level .= " change_set = '".$user_level."', ";
		$query_change_level .= " Interc_sdate = '".$Interc_sdate."', ";
		$query_change_level .= " Interc_period = '".$Interc_period."', ";
		$query_change_level .= " Interc_edate = '".$Interc_edate."', ";
		$query_change_level .= " admin_memo = '".$admin_memo."', ";
		$query_change_level .= " ad_sect = '".$ad_sect."', ";
		$query_change_level .= " wdate = now() ";
		$result_change_level = mysqli_query($gconnet,$query_change_level);

	}*/

	$query = " update member_info set ";
	$query .= " admin_memo = '".$admin_memo."', ";
	//$query .= " master_ok = '".$master_ok."', ";
	//$query .= " user_level = '".$user_level."', ";
	$query .= " login_ok = '".$login_ok."' ";
	$query .= " where idx = '".$idx."' ";
	$result = mysqli_query($gconnet,$query);
		
	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 처리 되었습니다.');
	parent.location.href =  "member_view.php?idx=<?=$idx?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
