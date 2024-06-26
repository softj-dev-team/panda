<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	
	$member_idx = $_REQUEST['member_idx'];
	
	for($k=0; $k<sizeof($member_idx); $k++){

		$query = " update member_info set ";
		$query .= " del_yn = 'Y' ";
		$query .= " where idx = '".$member_idx[$k]."' ";
		$result =  mysqli_query($gconnet,$query);
		
	}
	
?>
<script type="text/javascript">
<!--	
	alert('정상적으로 삭제 되었습니다.');
	parent.location.href =  "member_list.php?pageNo=<?=$pageNo?>&<?=$total_param?>";
	//-->
</script>
