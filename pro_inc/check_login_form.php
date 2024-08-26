<?
if(!$_SESSION['member_coinc_idx']){
?>
<form name="frm_login" method="post" action="/mobile/sub/login.php" target='_top'>
<input type="hidden" name="reurl_ft_go" value="<?=$_SERVER[SCRIPT_NAME]?>?<?=$_SERVER[QUERY_STRING]?>">
</form>
<script type="text/javascript">
<!--
	alert('please login first');
	frm_login.submit();
//-->
</script>	
<?
exit;
} 
?>