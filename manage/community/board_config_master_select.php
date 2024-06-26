<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
	$board_master_id = trim(sqlfilter($_REQUEST['board_master_id']));

	$sql_sub1 = "select idx,user_id,user_name,user_gubun,user_level,gender FROM member_info where 1=1 and user_id='".$board_master_id."' and user_sect = 'PAT' and memout_yn = 'N' ";
	$query_sub1 = mysqli_query($gconnet,$sql_sub1);
	$row_sub1 = mysqli_fetch_array($query_sub1);
	$cnt_sub1 = mysqli_num_rows($query_sub1);

	if($cnt_sub1 == 0){
	?>
	<script type="text/javascript">
	<!--
		alert("입력하신 아이디에 해당하는 회원이 없습니다.");
	//-->
	</script>
	<?
	exit;
	}
	
	$member_idx = $row_sub1[idx];
	$user_id = $row_sub1[user_id];
	$user_name = $row_sub1[user_name];
	
	if($row_sub1[user_gubun] == "PAT_B"){
		$user_gubun = "게시판운영 제휴회원";
	} elseif($row_sub1[user_gubun] == "PAT_S"){
		$user_gubun = "셀러 제휴회원";
	} elseif($row_sub1[user_gubun] == "PAT_SS"){
		$user_gubun = "파워셀러 제휴회원";
	}

	if($row_sub1[gender] == "M"){
		$gender = "남성";
	} elseif($row_sub1[gender] == "F"){
		$gender = "여성";	
	} 

	$selected_mem_info = "회원 아이디 : ".$user_id." , 회원성명 : ".$user_name." , 회원구분 : ".$user_gubun." , 회원성별 : ".$gender.".";
?>
	<script type="text/javascript">
	<!--
	var target1 = parent.document.all['bmaster_name_txt'];
	parent.document.frm.board_master_idx.value = "";
	parent.document.frm.board_master_idx.value = "<?=$member_idx?>";
	target1.innerText = "<?=$selected_mem_info?>";
	//-->
	</script>