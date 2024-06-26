<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/check_login_frame.php"; // 관리자 로그인여부 확인
?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));

$filtering = trim(sqlfilter($_REQUEST['filtering']));


$query = "update filtering set";
$query .= " filtering_text = '" . $filtering . "' where key_name = 'filtering'";
$result = mysqli_query($gconnet, $query);

if ($result) {
?>
	<SCRIPT LANGUAGE="JavaScript">
		<!--
		alert('등록이 정상적으로 완료 되었습니다.');
		parent.location.href = "sms_filtering_write.php?bmenu=<?= $bmenu ?>&smenu=<?= $smenu ?>";
		//
		-->
	</SCRIPT>
<? } else { ?>
	<SCRIPT LANGUAGE="JavaScript">
		<!--
		alert('등록중 오류가 발생했습니다.');
		//
		-->
	</SCRIPT>
<? } ?>