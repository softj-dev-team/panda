<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$lecture_idx = trim(sqlfilter($_REQUEST['lecture_idx']));
	$total_param = 'lecture_idx='.$lecture_idx;
	
	$query = " update curri_lecture_info set ";
	$query .= " is_del = 'Y' ";
	$query .= " where idx = '".$idx."'";
	$result = mysqli_query($gconnet,$query);
	
if($result){
?>
	<script type="text/javascript">
	<!-- 
		alert('삭제 되었습니다.');
		parent.opener.exam_list_movie_list();
		parent.self.close();
	//-->
	</script>
<?}else{?>
	<script type="text/javascript">
	<!-- 
		alert('오류가 발생했습니다.');
	//-->
	</script>
<?}?>