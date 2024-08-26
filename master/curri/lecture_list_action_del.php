<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	
	$lecture_idx = $_REQUEST['lecture_idx'];
	
	for($k=0; $k<sizeof($lecture_idx); $k++){
		$query = "update curri_lecture_info set";
		$query .= " is_del = 'Y' ";
		$query .= " where 1 and idx = '".$lecture_idx[$k]."'";
		$result =  mysqli_query($gconnet,$query);
	}
?>
	<script type="text/javascript">
	<!-- 
		alert('삭제 되었습니다.');
		parent.exam_list_movie_list();
	//-->
	</script>

