<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/check_login_frame.php"; // 관리자 로그인여부 확인
?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));

$group_idx = trim(sqlfilter($_REQUEST['group_idx']));
$cell_num = trim(sqlfilter(str_replace("-", "", $_REQUEST['cell_num'])));

$sql_pre1 = "select idx from spam_080 where 1 and cell_num = '" . $cell_num . "' and group_idx=" . $group_idx . ""; // 회원테이블 아이디 중복여부 체크
$result_pre1 = mysqli_query($gconnet, $sql_pre1);
if (mysqli_num_rows($result_pre1) > 0) {
	error_frame("입력하신 번호는 이미 사용중입니다. 다시 확인하시고 입력해 주세요.");
}


$query = "insert into spam_080 set";
$query .= " cell_num = '" . $cell_num . "', ";
$query .= " group_idx = '" . $group_idx . "', ";
$query .= " wdate = now() ";
$result = mysqli_query($gconnet, $query);

$save_idx = mysqli_insert_id($gconnet);

if ($result) {
?>
	<SCRIPT LANGUAGE="JavaScript">
		<!--
		alert('등록이 정상적으로 완료 되었습니다.');
		parent.location.href = "sms_080_list.php?bmenu=<?= $bmenu ?>&smenu=<?= $smenu ?>";
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