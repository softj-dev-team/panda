<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$mode = trim(sqlfilter($_REQUEST['mode']));
	
	$parklot_idx = $_REQUEST['parklot_idx'];
	
	for($k=0; $k<sizeof($parklot_idx); $k++){

		$query = " update parklot_info set ";
		$query .= " is_del = 'Y' ";
		$query .= " where idx = '".$parklot_idx[$k]."' ";
		$result =  mysqli_query($gconnet,$query);

		$query2 = " update parklot_public_time set ";
		$query2 .= " is_del = 'Y' ";
		$query2 .= " where parklot_idx = '".$parklot_idx[$k]."' ";
		$result2 =  mysqli_query($gconnet,$query2);
		
	}
	
?>
<script type="text/javascript">
<!--	
	alert('정상적으로 삭제 되었습니다.');
	//parent.location.href =  "parklot_list.php?pageNo=<?=$pageNo?>&<?=$total_param?>";
	parent.location.href =  "parklot_list.php?<?=$total_param?>";
//-->
</script>
