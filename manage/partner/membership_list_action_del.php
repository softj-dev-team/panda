<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	
	$membership_idx = $_REQUEST['membership_idx'];
	
	for($k=0; $k<sizeof($membership_idx); $k++){
		$query = "update membership_auth set";
		$query .= " is_del = 'Y',mdate=now() ";
		$query .= " where 1 and idx = '".$membership_idx[$k]."'";
		$result =  mysqli_query($gconnet,$query);
	}

	$last_membership_sql = "select * from membership_auth where 1 and member_idx='".$member_idx."' and is_del='N' order by idx desc limit 0,1";
	$last_membership_query = mysqli_query($gconnet,$last_membership_sql);
	if(mysqli_num_rows($last_membership_query) == 0){
		$query_mem = "update member_info set";
		$query_mem .= " payment_type='',pay_status='no' ";
		$query_mem .= " where 1 and idx = '".$member_idx."'";
		$result_mem =  mysqli_query($gconnet,$query_mem);
	}
	$last_membership_row = mysqli_fetch_array($last_membership_query);
	
	$payment_type = $last_membership_row['payment_type'];
	if($last_membership_row['pay_status'] == "can"){
		$pay_status = "no";
	} else {
		$pay_status = $last_membership_row['pay_status'];
	}

	$query_mem = "update member_info set";
	$query_mem .= " payment_type='".$payment_type."',pay_status='".$pay_status."' ";
	$query_mem .= " where 1 and idx = '".$member_idx."'";
	$result_mem =  mysqli_query($gconnet,$query_mem);
?>
	<script type="text/javascript">
	<!-- 
		alert('결제정보가 삭제 되었습니다.');
		parent.membership_list();
	//-->
	</script>

