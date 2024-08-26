<?
	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
?>
<!-- 관리자 계정만 있으면 통과시킨다 -->
<form name="frm_login" method="post" action="/manage/login/login.php" target='_top'>
	<input type="hidden" name="reurl_go" value="<?=$_SERVER[SCRIPT_NAME]?>?<?=$_SERVER[QUERY_STRING]?>">
</form>
<?
if(!$_SESSION['manage_coinc_id']){
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	//alert('로그인이 필요한 페이지입니다.');
	frm_login.submit();
		
//-->
</SCRIPT>	
<?
exit;
}
?>