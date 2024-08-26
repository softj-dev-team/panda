<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	
	$memberstudy_idx = $_REQUEST['memberstudy_idx'];
	
	for($k=0; $k<sizeof($memberstudy_idx); $k++){
		$query = "update memberstudy_auth set";
		$query .= " is_del = 'Y',mdate=now() ";
		$query .= " where 1 and idx = '".$memberstudy_idx[$k]."'";
		$result =  mysqli_query($gconnet,$query);
	}

?>
	<script type="text/javascript">
	<!-- 
		alert('학습정보가 삭제 되었습니다.');
		parent.memberstudy_list();
	//-->
	</script>

