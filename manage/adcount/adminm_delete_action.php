<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_group = sqlfilter($_REQUEST['s_group']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_group='.$s_group.'&pageNo='.$pageNo;

	$query = " delete from member_info "; 
	$query .= " where idx = '".$idx."' and member_type='AD' ";

	//echo $query;

	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<script type="text/javascript">
	<!--
	alert('운영자 삭제가 정상적으로 완료 되었습니다.');
	parent.location.href =  "adminm_list.php?<?=$total_param?>";
	//-->
	</script>
	<?}else{?>
	<script type="text/javascript">
	<!--
	alert('운영자 삭제중 오류가 발생했습니다.');
	//-->
	</script>
	<?}?>