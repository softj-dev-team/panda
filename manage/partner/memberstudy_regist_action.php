<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));

	$curri_type = trim(sqlfilter($_REQUEST['curri_type']));
	$curri_info_idx = trim(sqlfilter($_REQUEST['curri_info_idx']));
	$lecture_info_idx = trim(sqlfilter($_REQUEST['lecture_info_idx']));
	$per_target = trim(sqlfilter($_REQUEST['per_target']));
	$per_success = trim(sqlfilter($_REQUEST['per_success']));

	$curri_sql = "select curri_title from curri_info where 1 and idx='".$curri_info_idx."'";
	$curri_query = mysqli_query($gconnet,$curri_sql);
	$curri_row = mysqli_fetch_array($curri_query);
	$curri_title = $curri_row['curri_title'];

	$lecture_sql = "select lecture_title from curri_lecture_info where 1 and idx='".$lecture_info_idx."'";
	$lecture_query = mysqli_query($gconnet,$lecture_sql);
	$lecture_row = mysqli_fetch_array($lecture_query);
	$lecture_title = $lecture_row['lecture_title'];
	
	$query_lecture = " insert into memberstudy_auth set ";
	$query_lecture .= " regist_status = 'com', ";
	$query_lecture .= " member_idx = '".$member_idx."', ";
	$query_lecture .= " curri_type = '".$curri_type."', ";
	$query_lecture .= " curri_info_idx = '".$curri_info_idx."', ";
	$query_lecture .= " curri_title = '".$curri_title."', ";
	$query_lecture .= " lecture_info_idx = '".$lecture_info_idx."', ";
	$query_lecture .= " lecture_title = '".$lecture_title."', ";
	$query_lecture .= " per_target = '".$per_target."', ";
	$query_lecture .= " per_success = '".$per_success."', ";
	$query_lecture .= " wdate = now() ";
	$result_lecture = mysqli_query($gconnet,$query_lecture);

if($result_lecture){
?>
	<script type="text/javascript">
	<!-- 
		alert('추가 되었습니다.');
		parent.opener.memberstudy_list();
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