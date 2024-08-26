<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
	$total_param = trim(sqlfilter($_REQUEST['total_param']));

	$user_id = trim(sqlfilter($_REQUEST['user_id']));
	$user_pwd = trim(sqlfilter($_REQUEST['user_pwd']));
	$user_pwd = md5($user_pwd);
	$user_name = trim(sqlfilter($_REQUEST['user_name']));
	$user_level = trim(sqlfilter($_REQUEST['user_level']));
	
	$cell1 = trim(sqlfilter($_REQUEST['cell1']));
	$cell2 = trim(sqlfilter($_REQUEST['cell2']));
	$cell3 = trim(sqlfilter($_REQUEST['cell3']));
	
	$cell = $cell1;
	if($cell2){
	$cell .= "-".$cell2;
	}
	if($cell3){
	$cell .= "-".$cell3;
	}

	$email1 = trim(sqlfilter($_REQUEST['email1']));
	$email2 = trim(sqlfilter($_REQUEST['email2']));

	$email = $email1;
	if($email2){
	$email .= "@".$email2;
	}

	$ma_idx = trim(sqlfilter($_REQUEST['ma_idx']));

	//$wdate = date("Y-m-d H:i:s");
		
	$sql_pre = "select idx from member_info where user_id = '".$user_id."' ";
	$result_pre  = mysqli_query($gconnet,$sql_pre);
	if(mysqli_num_rows($result_pre) > 0) {
	?>
	<script type="text/javascript">
	<!--	
		alert('입력하신 아이디는 이미 사용중입니다.\n다시 입력해 주세요.');
	//-->
	</script>
	<?
		exit;
	}

	$query = " insert into member_info set "; 
	$query .= " user_level = '".$user_level."', ";
	$query .= " member_type = 'AD', ";
	//$query .= " section = '".$section."', ";
	$query .= " user_id = '".$user_id."', ";
	$query .= " user_nick = '에스맘', ";
	$query .= " user_pwd = '".$user_pwd."', ";
	$query .= " user_name = '".$user_name."', ";
	$query .= " cell = '".$cell."', ";
	$query .= " email = '".$email."', ";
	$query .= " wdate = now() ";
	
	/*$query = "insert into member_info (user_id, user_pwd, user_name, user_level,cell,email,file1_org,file1_chg,wdate) values (N'".$user_id."',N'".$user_pwd."',N'".$user_name."',N'".$user_level."',N'".$cell."',N'".$email."',N'".$file1_org."',N'".$file1_chg."',N'".$wdate."')";*/
	
	//echo $query;
	
	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<script type="text/javascript">
	<!--
	alert('운영자 등록이 정상적으로 완료 되었습니다.');
	parent.location.href =  "adminm_list.php?<?=$total_param?>";
	//-->
	</script>
	<?}else{?>
	<script type="text/javascript">
	<!--
	alert('운영자 등록중 오류가 발생했습니다.');
	//-->
	</script>
	<?}?>
