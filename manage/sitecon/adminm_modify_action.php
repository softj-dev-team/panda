<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	//$idx = trim(sqlfilter($_REQUEST['idx']));
	$idx = $_SESSION['manage_coinc_idx'];

	$user_pwd = trim(sqlfilter($_REQUEST['user_pwd']));
	if($user_pwd){
		$user_pwd = md5($user_pwd);
	}
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
	
	if($level != "3"){
		$ma_idx = "";
	}
	
	$query = " update member_info set "; 
	if($_REQUEST['user_pwd']){
		$query .= " user_pwd = '".$user_pwd."', ";
	}
	/*$query .= " user_name = '".$user_name."', ";
	$query .= " user_level = '".$user_level."', ";
	$query .= " cell = '".$cell."', ";
	$query .= " email = '".$email."' ";*/
	$query .= " mdate = now() ";
	$query .= " where idx = '".$idx."' ";

	//echo $query;

	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<script type="text/javascript">
	<!--
	//alert('운영자 수정이 정상적으로 완료 되었습니다.');
	alert('비밀번호 변경이 정상적으로 완료 되었습니다.');
	//parent.location.href =  "adminm_view.php?idx=<?=$idx?>&<?=$total_param?>";
	parent.location.href =  "adminm_modify.php?<?=$total_param?>";
	//-->
	</script>
	<?}else{?>
	<script type="text/javascript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</script>
	<?}?>
