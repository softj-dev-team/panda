<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
	
	$mem_all = trim(sqlfilter($_REQUEST['mem_all']));
	$member_idx = trim($_REQUEST['member_idx']);
	$delmem = trim($_REQUEST['delmem']);

	$msg_title = trim(sqlfilter($_REQUEST['msg_title']));
	$board_tbname = trim(sqlfilter($_REQUEST['board_tbname']));
	$board_code = trim(sqlfilter($_REQUEST['board_code']));
	$board_idx = trim(sqlfilter($_REQUEST['board_idx']));

	if(!$msg_title){
		//error_frame("강연 혹은 더클레버스 둘 중 하나를 선택하세요.");
	}

	if($mem_all == "Y"){
	} else {
		if(!$member_idx){
			error_frame("발송 대상자를 선택하세요.");
		}
	}

	$msg_content = trim(sqlfilter($_REQUEST['msg_content']));

	$sql_cnt = "SELECT idx FROM member_info a where 1 and member_type in ('GEN','PAT') and memout_yn not in ('Y','S') and del_yn='N'";
	if($member_idx){
		$sql_cnt .= " and idx in (".$member_idx.")";
	}
	if($delmem){
		$sql_cnt .= " and idx not in (".$delmem.")";
	}
	$query_cnt = mysqli_query($gconnet,$sql_cnt);
	
	if(mysqli_num_rows($query_cnt) == 0){
		error_frame("대상자가 없습니다.");
	}
	
	//$msg_cate = "manual";
	$msg_cate = trim(sqlfilter($_REQUEST['msg_cate']));
	$msg_type = "push";
	$msg_memo = "푸시발송";

	$query = " insert into send_msg set "; 
	$query .= " msg_cate = '".$msg_cate."', ";
	$query .= " msg_type = '".$msg_type."', ";
	$query .= " msg_title = '".$msg_title."', ";
	$query .= " msg_memo = '".$msg_memo."', ";
	$query .= " msg_content = '".$msg_content."', ";
	$query .= " mem_all = '".$mem_all."', ";
	$query .= " board_tbname = '".$board_tbname."', ";
	$query .= " board_code = '".$board_code."', ";
	$query .= " board_idx = '".$board_idx."', ";
	$query .= " admin_idx = '".$_SESSION['admin_coinc_idx']."', ";
	$query .= " wdate = now() ";
	//echo $query;
	$result = mysqli_query($gconnet,$query);

	$msg_idx = mysqli_insert_id($gconnet);

	/*$member_idx_arr = explode(",",$member_idx);
	for($j=0; $j<sizeof($member_idx_arr); $j++){
		$member_idx = $member_idx_arr[$j];*/

	for($i=0; $i<mysqli_num_rows($query_cnt); $i++){
		$row = mysqli_fetch_array($query_cnt);
		$member_idx = $row['idx'];

		$query2 = " insert into send_msg_member set "; 
		$query2 .= " msg_idx = '".$msg_idx."', ";
		$query2 .= " member_idx = '".$member_idx."', ";
		$query2 .= " wdate = now() ";
		//echo $query2;
		$result2 = mysqli_query($gconnet,$query2);
	}

	error_frame_go("발송되었습니다.","msg_send_list_manual.php?bmenu=".$bmenu."&smenu=".$smenu."");
?>