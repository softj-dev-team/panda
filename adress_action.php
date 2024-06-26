<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/check_login_frame.php"; // 공통함수 인클루드 ?>
<?
	$member_idx = $_SESSION['member_coinc_idx'];
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
		
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$mode = trim(sqlfilter($_REQUEST['mode']));
	$group_name = trim(sqlfilter($_REQUEST['group_name']));
	
	if($mode == "ins"){
		
		$query = "insert into address_group set"; 
		$query .= "	member_idx = '".$member_idx."', ";
		$query .= " group_name = '".$group_name."', ";
		$query .= " wdate = now() ";
		//echo $query;
		$result = mysqli_query($gconnet,$query);
		
		error_frame_go("그룹이 등록 되었습니다.","adress.php");
		
	} elseif($mode == "udt"){
		
		$query = "update address_group set"; 
		$query .= " group_name = '".$group_name."', ";
		$query .= " mdate = now() ";
		$query .= " where 1 and member_idx = '".$member_idx."' and idx='".$idx."'";
		//echo $query;
		$result = mysqli_query($gconnet,$query);
		
		error_frame_reload("그룹명이 수정 되었습니다.");
		
	} elseif($mode == "totdel"){
				
		$adr_idx = $_REQUEST['adr_idx'];
		
		for($k=0; $k<sizeof($adr_idx); $k++){

			$query = "update address_group set";
			$query .= " is_del = 'Y' ";
			$query .= " where 1 and member_idx = '".$member_idx."' and idx = '".$adr_idx[$k]."' ";
			$result =  mysqli_query($gconnet,$query);
			
		}
		
		error_frame_go("그룹이 삭제 되었습니다.","adress.php");
	
	}
?>