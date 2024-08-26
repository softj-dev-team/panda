<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$point_sect = trim(sqlfilter($_REQUEST['point_sect']));
		
	$status = trim(sqlfilter($_REQUEST['status']));
	if($status == "2"){
		$sdate = trim(sqlfilter($_REQUEST['sdate']));
	} else {
		$sdate = "";
	}
	if($status == "3"){
		$memo_reject = trim(sqlfilter($_REQUEST['memo_reject']));
	} else {
		$memo_reject = "";
	}
	
	$ad_sect_idx = $_SESSION['manage_coinc_idx'];
	$ad_sect_id = $_SESSION['manage_coinc_id'];
	$ad_sect_name = $_SESSION['manage_coinc_name'];

	$prev_sql = "select * from member_point_change where 1 and idx = '".$idx."'";
	$prev_query = mysqli_query($gconnet,$prev_sql);
	$prev_cnt = mysqli_num_rows($prev_query);
	
	if($prev_cnt == 0){
		error_frame("답변 설정할 내용이 없습니다.");
		exit;
	}

	$prev_row = mysqli_fetch_array($prev_query);

	$query = "update member_point_change set";
	$query .= " status = '".$status."', ";
	$query .= " sdate = '".$sdate."', ";
	$query .= " memo_reject = '".$memo_reject."', ";
	$query .= " ad_sect_idx = '".$ad_sect_idx."', ";
	$query .= " ad_sect_id = '".$ad_sect_id."', ";
	$query .= " ad_sect_name = '".$ad_sect_name."', ";
	$query .= " mdate = now() ";
	$query .= " where 1 and idx = '".$idx."'";
	$result = mysqli_query($gconnet,$query);

	if($status == "3"){ // 거절이면 포인트를 돌려준다 시작 
		coin_plus_minus($point_sect,$prev_row['member_idx'],"A",$prev_row['chg_mile'],"상품권 전환신청 거절되어 반환","","","");
	} // 거절이면 포인트를 돌려준다 종료 
	
	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('정상적으로 완료 되었습니다.');
		parent.location.href =  "point_change_view.php?idx=<?=$idx?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
