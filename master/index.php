<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<script type="text/javascript">
	<!--
	<? if($_SESSION['admin_exhib_idx']){?>
		location.href="./member/member_list.php?bmenu=1&smenu=1&v_sect=SEL";
	<?}else{?>
		location.href="./login/login.php";
	<?}?>
	//-->
</script>
