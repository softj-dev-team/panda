<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$member_idx = $_SESSION['manage_coinc_idx'];
	
	$def_short_fee = trim(sqlfilter($_REQUEST['def_short_fee']));
	$def_long_fee = trim(sqlfilter($_REQUEST['def_long_fee']));
	$def_img_fee = trim(sqlfilter($_REQUEST['def_img_fee']));
	$denie_num = trim(sqlfilter($_REQUEST['denie_num']));
	
	$sql_prev = "select a.idx from sms_configure a where 1 and member_idx='".$member_idx."' and is_del='N'";
	$query_prev = mysqli_query($gconnet,$sql_prev);
	
	if(mysqli_num_rows($query_prev) == 0){ // 등록
		$query = "insert into sms_configure set";
		$query .= " member_idx = '".$member_idx."', ";
		$query .= " def_short_fee = '".$def_short_fee."', ";
		$query .= " def_long_fee = '".$def_long_fee."', ";
		$query .= " def_img_fee = '".$def_img_fee."', ";
		$query .= " denie_num = '".$denie_num."', ";
						
		$query .= " wdate = now()";
		
		//echo $query;
		
		$result = mysqli_query($gconnet,$query);
		
		$board_idx = mysqli_insert_id($gconnet);
	} else { // 수정
		$row_prev = mysqli_fetch_array($query_prev);
		
		$query = "update sms_configure set";
		$query .= " def_short_fee = '".$def_short_fee."', ";
		$query .= " def_long_fee = '".$def_long_fee."', ";
		$query .= " def_img_fee = '".$def_img_fee."', ";
		$query .= " denie_num = '".$denie_num."', ";
				
		$query .= " mdate = now()";
		$query .= " where 1 and member_idx='".$member_idx."' and is_del='N'";
		
		//echo $query;
		
		$result = mysqli_query($gconnet,$query);
		
		$board_idx = $row_prev['idx'];
	}
		
	error_frame_reload("설정 저장이 완료 되었습니다.");
?>