<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$set_price1 = trim(sqlfilter($_REQUEST['set_price1']));
	$set_price2 = trim(sqlfilter($_REQUEST['set_price2']));
	$set_price3 = trim(sqlfilter($_REQUEST['set_price3']));
	$set_price4 = trim(sqlfilter($_REQUEST['set_price4']));

	$wdate = date("Y-m-d H:i:s");
		
	$query = " update delivery_set set "; 
	$query .= " set_price1 = '".$set_price1."', ";
	$query .= " set_price2 = '".$set_price2."', ";
	$query .= " set_price3 = '".$set_price3."', ";
	$query .= " set_price4 = '".$set_price4."', ";
	$query .= " wdate = '".$wdate."' ";
	$query .= " where idx = '".$idx."' ";
	$result = mysqli_query($gconnet,$query);

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