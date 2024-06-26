<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$v_sect = trim(sqlfilter($_REQUEST['v_sect']));
	$file_o = trim(sqlfilter($_REQUEST['file_o']));
	$file_c = trim(sqlfilter($_REQUEST['file_c']));
	$del_photo = trim(sqlfilter($_REQUEST['del_photo']));
	
	if($del_photo == "Y"){ // 사진 완전 삭제 시작
		$bbs = "member";
		$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
		$_P_DIR_FILE2 = $_P_DIR_FILE."img_thumb/";
	
		$file_old_name1 = $file_c;
		if($file_old_name1){
			unlink($_P_DIR_FILE.$file_old_name1); // 
			unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제

			$file_o = "";
			$file_c = "";
		}
	} // 사진 완전 삭제 종료

	$gender = trim(sqlfilter($_REQUEST['gender']));
	$birthday_year = trim(sqlfilter($_REQUEST['birthday_year']));
	$birthday_month = trim(sqlfilter($_REQUEST['birthday_month']));
	$birthday_day = trim(sqlfilter($_REQUEST['birthday_day']));
	$birthday = $birthday_year;
	if($birthday_month){
		$birthday .= "-".$birthday_month;
	}
	if($birthday_day){
		$birthday .= "-".$birthday_day;
	}
	$birthday_tp = "양력";
	$local = trim(sqlfilter($_REQUEST['local']));
	$married = trim(sqlfilter($_REQUEST['married']));
	$child = trim(sqlfilter($_REQUEST['child']));
	$animal = trim(sqlfilter($_REQUEST['animal']));
	/*$cell1 = trim(sqlfilter($_REQUEST['cell1']));
	$cell2 = trim(sqlfilter($_REQUEST['cell2']));
	$cell3 = trim(sqlfilter($_REQUEST['cell3']));
	$cell = $cell1."-".$cell2."-".$cell3;*/
	$cell = trim(sqlfilter($_REQUEST['cell']));
	$sns = trim(sqlfilter($_REQUEST['sns']));

	$prev_sql = "select idx,user_level from member_info where 1=1 and idx = '".$idx."' and memout_yn = 'N' ";
	$prev_query = mysqli_query($gconnet,$prev_sql);
	$prev_cnt = mysqli_num_rows($prev_query);
	
	if($prev_cnt == 0){
		error_frame("프로필을 등록할 회원이 존재하지 않습니다.");
		exit;
	}

	$prev_row = mysqli_fetch_array($prev_query);
	
	$query = " update member_info set ";
	$query .= " file_org = '".$file_o."', ";
	$query .= " file_chg = '".$file_c."', ";
	$query .= " gender = '".$gender."', ";
	$query .= " birthday = '".$birthday."', ";
	$query .= " birthday_tp = '".$birthday_tp."', ";
	$query .= " local = '".$local."', ";
	$query .= " married = '".$married."', ";
	$query .= " child = '".$child."', ";
	$query .= " animal = '".$animal."', ";
	$query .= " cell = '".$cell."', ";
	$query .= " sns = '".$sns."' ";
	$query .= " where idx = '".$idx."' ";
	$result = mysqli_query($gconnet,$query);
		
	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 처리 되었습니다.');
	parent.location.href =  "member_view.php?idx=<?=$idx?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
