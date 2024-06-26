<?php include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?php include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<?php include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?php
$total_param =  trim(sqlfilter($_REQUEST['total_param']));
$pageNo =  trim(sqlfilter($_REQUEST['pageNo']));
$idx = trim(sqlfilter($_REQUEST['idx']));

	$view_ok = "S";
	$view_admin_id = $_SESSION['admin_gosm_id']; 
	$send_admin_id = $_SESSION['admin_gosm_id']; 
	$send_money = trim(sqlfilter($_REQUEST['send_money']));

	$query = " update sil_change_money set "; 
	$query .= " view_ok = '".$view_ok."', ";
	$query .= " view_admin_id = '".$view_admin_id."', ";
	$query .= " send_admin_id = '".$send_admin_id."', ";
	$query .= " send_money = '".$send_money."', ";
	$query .= " mdate = now(), ";
	$query .= " vdate = now() ";
	$query .= " where idx = '".$idx."'";
	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<script type="text/javascript">
	<!--
	alert('정상적으로 완료 되었습니다.');
	parent.location.href="calcurate_complete_list.php?bmenu=6&smenu=2";
	</script>
	<?php }else{?>
	<script type="text/javascript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</script>
	<?php }?>
