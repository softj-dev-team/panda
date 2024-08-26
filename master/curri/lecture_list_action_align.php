<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	
	$lecture_idx = $_REQUEST['lecture_idx_arr'];
	$lecture_idx_arr = explode(",",$lecture_idx);
 	
	for($k=0; $k<sizeof($lecture_idx_arr); $k++){
		$up_lecture_idx = trim($lecture_idx_arr[$k]);
		$align = trim(sqlfilter($_REQUEST['align_'.$up_lecture_idx.'']));

		$query = "update curri_lecture_info set";
		$query .= " align = '".$align."' ";
		$query .= " where 1 and idx = '".$up_lecture_idx."'";
		$result =  mysqli_query($gconnet,$query);

		//echo "sql = ".$query."<br>";
	}

?>
	<script type="text/javascript">
	<!-- 
		alert('순서적용이 정상적으로 처리 되었습니다.');
		parent.exam_list_movie_list();
	//-->
	</script>

