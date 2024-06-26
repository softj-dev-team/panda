<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<?
	$mode = trim(sqlfilter($_REQUEST['mode']));
	$cancel_product = trim(sqlfilter($_REQUEST['cancel_product']));
	$pm_pwd = md5(sqlfilter($_REQUEST['lms_pass']));
	$sale_cancel_memo = trim(sqlfilter($_REQUEST['sale_cancel_memo']));
	
	$sql_prev = "select * from member_info where 1 and idx='".$_SESSION['manage_coinc_idx']."'";
	$result_prev = mysqli_query($gconnet,$sql_prev);
	$row_prev = mysqli_fetch_array($result_prev);

	if(trim($pm_pwd) != trim($row_prev['user_pwd'])){
		error_frame("관리자 비밀번호가 일치하지 않습니다.");
	}

	if($mode == "resell"){ // 판매재개 
		
		$query = "update product_info_sale set"; 
		$query .= " sale_ok = '1',";
		$query .= " date_resell = now(),";
		$query .= " admin_resell = '".$_SESSION['manage_coinc_idx']."'";
		$query .= " where 1 and product_idx='".$cancel_product."' and sale_ok = '3' and is_del='N'";
		$result = mysqli_query($gconnet,$query);
	?>
		<script>
			parent.cancel_5_close();
			parent.cancel_6_open();
		</script>
	<?
		exit;
	} elseif($mode == "one"){ // 판매중인게 한건일때 
		
		$cancel_member = trim(sqlfilter($_REQUEST['cancel_member']));

		$query = "update product_info_sale set"; 
		$query .= " sale_ok = '3',";
		$query .= " sale_cancel_memo = '".$sale_cancel_memo."',";
		$query .= " date_cancel = now(),";
		$query .= " admin_cancel = '".$_SESSION['manage_coinc_idx']."'";
		$query .= " where 1 and product_idx='".$cancel_product."' and member_idx='".$cancel_member."' and sale_ok = '1' and is_del='N'";
		$result = mysqli_query($gconnet,$query);

	} elseif($mode == "all"){ // 판매중인게 여러건일때 
		
		$cancel_member_arr = explode(",",$_REQUEST['cancel_member']);
		
		for($i=0; $i<sizeof($cancel_member_arr); $i++){
			$cancel_member = $cancel_member_arr[$i];

			$query = "update product_info_sale set"; 
			$query .= " sale_ok = '3',";
			$query .= " sale_cancel_memo = '".$sale_cancel_memo."',";
			$query .= " date_cancel = now(),";
			$query .= " admin_cancel = '".$_SESSION['manage_coinc_idx']."'";
			$query .= " where 1 and product_idx='".$cancel_product."' and member_idx='".$cancel_member."' and sale_ok = '1' and is_del='N'";
			$result = mysqli_query($gconnet,$query);

		}

	} // 판매중인 건수 종료 
?>

	<script>
		parent.cancel_3_close();
		parent.cancel_4_open();
	</script>