<?
if(!$_SESSION['member_coinc_idx']){
?>
<script type="text/javascript">
/*$(document).ready(function(){
	$(".lostart").click();
});*/
	alert("먼저 로그인 해주세요.");
	location.href="/";
</script>
<?
} 
?>