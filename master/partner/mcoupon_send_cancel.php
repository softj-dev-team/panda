<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/yonex_master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$idx = trim(sqlfilter($_REQUEST['idx']));

$result1 = mysqli_query($gconnet,"delete from member_coupon_set where idx = '".$idx."'");

if($result1){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('생성한 쿠폰이 취소 되었습니다.');
		parent.location.href = "mcoupon_write.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>";
	//-->
	</SCRIPT>
	<?
}else{
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?
}
?>