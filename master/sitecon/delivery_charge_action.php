<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$proc_type = trim(sqlfilter($_REQUEST['proc_type']));
$idx = trim(sqlfilter($_REQUEST['idx']));
$address = trim(sqlfilter($_REQUEST['address']));
$charge = trim(sqlfilter($_REQUEST['charge']));

switch($proc_type){
	case "write":
		$query = "insert into delivery_charge(address, charge) values('".$address."', '".$charge."')";
		$result = mysqli_query($gconnet,$query);
	break;

	case "modify":
		$query = "update delivery_charge set address = '".$address."', charge = '".$charge."' where idx = '".$idx."'";
		$result = mysqli_query($gconnet,$query);
	break;

	case "delete":
		$query = "delete from delivery_charge where idx = '".$idx."'";
		$result = mysqli_query($gconnet,$query);
	break;
}


if($result){
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
alert('배송비 설정이 정상적으로 완료 되었습니다.');
parent.location.href =  "delivery_set.php";
//-->
</SCRIPT>
<?}else{?>
<SCRIPT LANGUAGE="JavaScript">
<!--
alert('배송비 설정중 오류가 발생했습니다.');
//-->
</SCRIPT>
<?}?>
