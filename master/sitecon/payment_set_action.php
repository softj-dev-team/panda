<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
$total_param = trim(sqlfilter($_REQUEST['total_param']));

$payment_v1 = trim(sqlfilter($_REQUEST['payment_v1']));
$payment_v2 = trim(sqlfilter($_REQUEST['payment_v2']));
$payment_v3 = trim(sqlfilter($_REQUEST['payment_v3']));
$payment_vip_1 = trim(sqlfilter($_REQUEST['payment_vip_1']));
$payment_vip_2 = trim(sqlfilter($_REQUEST['payment_vip_2']));
$payment_vip_3 = trim(sqlfilter($_REQUEST['payment_vip_3']));

	$query = " insert into member_payment_set set "; 
	$query .= " payment_v1 = '".$payment_v1."',";
	$query .= " payment_v2 = '".$payment_v2."',";
	$query .= " payment_v3 = '".$payment_v3."',";
	$query .= " payment_vip_1 = '".$payment_vip_1."',";
	$query .= " payment_vip_2 = '".$payment_vip_2."',";
	$query .= " payment_vip_3 = '".$payment_vip_3."',";
	$query .= " wdate = now() ";
	//echo $query; 
   	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('설정이 정상적으로 완료 되었습니다.');
	parent.location.href =  "member_payment_set.php?<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>