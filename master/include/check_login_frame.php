<?
	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
?>
<!-- 관리자 계정만 있으면 통과시킨다 -->
<?
if(!$_SESSION['admin_coinc_id']){
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	alert('먼저 관리자로 로그인해 주십시오.');
	
//-->
</SCRIPT>	
<?
exit;
}
?>