<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<?
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	
	$tmp_pass = sprintf('%06d',rand(000000,999999));
	$tmp_pass_in = md5($tmp_pass); // 생성한 임시 비번을 암호화
		
	$sql_pre = "update member_info set user_pwd = '".$tmp_pass_in."' where idx='".$member_idx."'";
	$result_pre  = mysqli_query($gconnet,$sql_pre);
?>
	<script>
		$("#show_pwd_1", parent.document).hide();
		$("#show_pwd_2", parent.document).show();
		$("#show_pwd_3", parent.document).html("<?=$tmp_pass?>");
	</script>