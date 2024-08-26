<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$pm_pwd = md5(sqlfilter($_REQUEST['lms_pass']));

	$sql_prev = "select * from member_info where 1 and idx='".$_SESSION['manage_coinc_idx']."'";
	$result_prev = mysqli_query($gconnet,$sql_prev);
	$row_prev = mysqli_fetch_array($result_prev);

	if(trim($pm_pwd) != trim($row_prev['user_pwd'])){
		error_frame("관리자 비밀번호가 일치하지 않습니다.");
	}
	
	$panalty_mode = trim(sqlfilter($_REQUEST['panalty_mode']));

	if($panalty_mode == "Y"){ // 패널티 적용 
		$panalty_type = trim(sqlfilter($_REQUEST['panalty_type']));
		$panalty_memo = trim(sqlfilter($_REQUEST['panalty_memo']));
		
		$s_date = date("Y-m-d");
		$e_date = date("Y-m-d", strtotime("+".$panalty_type." day", strtotime($s_date)));

		$query = "insert into member_panalty_info set"; 
		$query .= " member_idx = '".$member_idx."', ";
		$query .= " panalty_type = '".$panalty_type."', ";
		$query .= " panalty_memo = '".$panalty_memo."', ";
		$query .= " s_date = '".$s_date."', ";
		$query .= " e_date = '".$e_date."', ";
		$query .= " admin_idx = '".$_SESSION['manage_coinc_idx']."', ";
		$query .= " wdate = now() ";
		//echo $query;
		$result = mysqli_query($gconnet,$query);

		$query2 = "update member_info set"; 
		$query2 .= " login_ok = 'N'";
		$query2 .= " where 1 and idx='".$member_idx."'";
		$result2 = mysqli_query($gconnet,$query2);

		if($panalty_type == "36500"){
			$panalty_type_str = $arr_panalty_type[$panalty_type];
		} else {
			$panalty_type_str = "총 ".$arr_panalty_type[$panalty_type];
		}
	} elseif($panalty_mode == "N"){ // 패널티 해제
		$query2 = "update member_info set"; 
		$query2 .= " login_ok = 'Y'";
		$query2 .= " where 1 and idx='".$member_idx."'";
		$result2 = mysqli_query($gconnet,$query2);
	}
?>
	<script>
	<?if($panalty_mode == "Y"){ // 패널티 적용 ?>
		parent.set_panalty_close();
		$("#panalty_com_period", parent.document).html("<?=$panalty_type_str?>");
		parent.set_panalty_complete_open();
	<?} elseif($panalty_mode == "N"){ // 패널티 해제?>
		parent.set_panalty_clear_close();
		parent.set_panalty_clear_complete();
	<?}?>
	</script>