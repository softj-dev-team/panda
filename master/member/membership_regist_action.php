<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));

	$payment_type = trim(sqlfilter($_REQUEST['payment_type']));
	$s_date = trim(sqlfilter($_REQUEST['s_date']));
	$e_date = trim(sqlfilter($_REQUEST['e_date']));
	$pay_status = "ing";

	$curri_sql = "select cate_name1 from common_code where 1 and type='payment' and cate_level = '1' and del_ok='N' and cate_code1='".$payment_type."'";
	$curri_query = mysqli_query($gconnet,$curri_sql);
	$curri_row = mysqli_fetch_array($curri_query);
	$payment_str = $curri_row['cate_name1'];
	
	$query_lecture = " insert into membership_auth set ";
	$query_lecture .= " member_idx = '".$member_idx."', ";
	$query_lecture .= " payment_type = '".$payment_type."', ";
	$query_lecture .= " payment_str = '".$payment_str."', ";
	$query_lecture .= " s_date = '".$s_date."', ";
	$query_lecture .= " e_date = '".$e_date."', ";
	$query_lecture .= " pay_status = '".$pay_status."', ";
	$query_lecture .= " wdate = now() ";
	$result_lecture = mysqli_query($gconnet,$query_lecture);

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

if($result_lecture){
?>
	<script type="text/javascript">
	<!-- 
		alert('추가 되었습니다.');
		parent.opener.membership_list();
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