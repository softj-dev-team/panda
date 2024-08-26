<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$member_idx = $_SESSION['admin_coinc_idx'];
	
	$sns_kakao = trim(sqlfilter($_REQUEST['sns_kakao']));
	$sns_teleg = trim(sqlfilter($_REQUEST['sns_teleg']));
	
	$bank_name = trim(sqlfilter($_REQUEST['bank_name']));
	$bank_num = trim(sqlfilter($_REQUEST['bank_num']));
	$bank_owner = trim(sqlfilter($_REQUEST['bank_owner']));
	
	$conf_tel_2 = trim(sqlfilter($_REQUEST['conf_tel_2']));
	$conf_time_s = trim(sqlfilter($_REQUEST['conf_time_s']));
	$conf_time_e = trim(sqlfilter($_REQUEST['conf_time_e']));
	$conf_time_s2 = trim(sqlfilter($_REQUEST['conf_time_s2']));
	$conf_time_e2 = trim(sqlfilter($_REQUEST['conf_time_e2']));
	$conf_fax = trim(sqlfilter($_REQUEST['conf_fax']));
	$conf_email_1 = trim(sqlfilter($_REQUEST['conf_email_1']));
	
	$conf_comname = trim(sqlfilter($_REQUEST['conf_comname']));
	$conf_comowner = trim(sqlfilter($_REQUEST['conf_comowner']));
	$conf_manager = trim(sqlfilter($_REQUEST['conf_manager']));
	$conf_comnum_1 = trim(sqlfilter($_REQUEST['conf_comnum_1']));
	$conf_comnum_2 = trim(sqlfilter($_REQUEST['conf_comnum_2']));
	$conf_addr = trim(sqlfilter($_REQUEST['conf_addr']));
	$conf_tel_1 = trim(sqlfilter($_REQUEST['conf_tel_1']));
	$conf_email_2 = trim(sqlfilter($_REQUEST['conf_email_2']));
	
	$sql_prev = "select a.idx from site_configure a where 1 and member_idx='".$member_idx."' and is_del='N'";
	$query_prev = mysqli_query($gconnet,$sql_prev);
	
	if(mysqli_num_rows($query_prev) == 0){ // 등록
		$query = "insert into site_configure set";
		$query .= " member_idx = '".$member_idx."', ";
		$query .= " sns_kakao = '".$sns_kakao."', ";
		$query .= " sns_teleg = '".$sns_teleg."', ";
		
		$query .= " bank_name = '".$bank_name."', ";
		$query .= " bank_num = '".$bank_num."', ";
		$query .= " bank_owner = '".$bank_owner."', ";
		
		$query .= " conf_tel_2 = '".$conf_tel_2."', ";
		$query .= " conf_time_s = '".$conf_time_s."', ";
		$query .= " conf_time_e = '".$conf_time_e."', ";
		$query .= " conf_time_s2 = '".$conf_time_s2."', ";
		$query .= " conf_time_e2 = '".$conf_time_e2."', ";
		$query .= " conf_fax = '".$conf_fax."', ";
		$query .= " conf_email_1 = '".$conf_email_1."', ";
		
		$query .= " conf_comname = '".$conf_comname."', ";
		$query .= " conf_comowner = '".$conf_comowner."', ";
		$query .= " conf_manager = '".$conf_manager."', ";
		$query .= " conf_comnum_1 = '".$conf_comnum_1."', ";
		$query .= " conf_comnum_2 = '".$conf_comnum_2."', ";
		$query .= " conf_addr = '".$conf_addr."', ";
		$query .= " conf_tel_1 = '".$conf_tel_1."', ";
		$query .= " conf_email_2 = '".$conf_email_2."', ";
				
		$query .= " wdate = now()";
		
		//echo $query;
		
		$result = mysqli_query($gconnet,$query);
		
		$board_idx = mysqli_insert_id($gconnet);
	} else { // 수정
		$row_prev = mysqli_fetch_array($query_prev);
		
		$query = "update site_configure set";
		$query .= " sns_kakao = '".$sns_kakao."', ";
		$query .= " sns_teleg = '".$sns_teleg."', ";
		
		$query .= " bank_name = '".$bank_name."', ";
		$query .= " bank_num = '".$bank_num."', ";
		$query .= " bank_owner = '".$bank_owner."', ";
		
		$query .= " conf_tel_2 = '".$conf_tel_2."', ";
		$query .= " conf_time_s = '".$conf_time_s."', ";
		$query .= " conf_time_e = '".$conf_time_e."', ";
		$query .= " conf_time_s2 = '".$conf_time_s2."', ";
		$query .= " conf_time_e2 = '".$conf_time_e2."', ";
		$query .= " conf_fax = '".$conf_fax."', ";
		$query .= " conf_email_1 = '".$conf_email_1."', ";
		
		$query .= " conf_comname = '".$conf_comname."', ";
		$query .= " conf_comowner = '".$conf_comowner."', ";
		$query .= " conf_manager = '".$conf_manager."', ";
		$query .= " conf_comnum_1 = '".$conf_comnum_1."', ";
		$query .= " conf_comnum_2 = '".$conf_comnum_2."', ";
		$query .= " conf_addr = '".$conf_addr."', ";
		$query .= " conf_tel_1 = '".$conf_tel_1."', ";
		$query .= " conf_email_2 = '".$conf_email_2."', ";
				
		$query .= " mdate = now()";
		$query .= " where 1 and member_idx='".$member_idx."' and is_del='N'";
		
		//echo $query;
		
		$result = mysqli_query($gconnet,$query);
		
		$board_idx = $row_prev['idx'];
	}
	
	$bbs_code = "siteconf";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";
	$_P_DIR_FILE2 = $_P_DIR_FILE."img_thumb/";
	$board_tbname = "site_configure";
	$board_code = "logo";

	$file_idx = trim(sqlfilter($_REQUEST['file_idx'])); // 기존 첨부파일 DB PK 값.
	$file_old_name = trim(sqlfilter($_REQUEST['file_old_name'])); // 원본 서버파일 이름
	$file_old_org = trim(sqlfilter($_REQUEST['file_old_org']));	// 원본 오리지널 파일 이름
	$del_org = trim(sqlfilter($_REQUEST['del_org']));	// 원본 파일 삭제여부

	if ($_FILES['file_logo']['size']>0){ // 파일이 있다면 업로드한다 시작
		if($file_old_name){
			unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
			unlink($_P_DIR_FILE2.$file_old_name); // 섬네일 삭제
		}
		$file_o = $_FILES['file_logo']['name']; 
		$i_width = "159";
		$i_height = "34";
		$file_c = uploadFileThumb_1($_FILES, "file_logo", $_FILES['file_logo'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$watermark_sect);		
	} else { // 파일이 있다면 업로드한다 종료 , 파일이 없을때 시작 
		if($file_old_name && $file_old_org){
			$file_c = $file_old_name;
			$file_o = $file_old_org;
		} else {
			$file_c = "";
			$file_o = "";
		}
		if($del_org == "Y"){
			if($file_old_name){
				unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
				unlink($_P_DIR_FILE2.$file_old_name); // 섬네일 삭제
			}
			$file_c = "";
			$file_o = "";
		}
	} //  파일이 없을때 종료 
	if($file_idx){ // 기존에 첨부파일 DB 에 있던 값
		if ($file_o && $file_c){ // 파일이 있으면 업데이트, 없으면 삭제 
			$query_file = " update board_file set "; 
			$query_file .= " file_org = '".$file_o."', ";
			$query_file .= " file_chg = '".$file_c."' ";
			$query_file .= " where 1=1 and idx = '".$file_idx."' ";
		} else {
			$query_file = " delete from board_file "; 
			$query_file .= " where 1=1 and idx = '".$file_idx."' ";
		}
		$result_file = mysqli_query($gconnet,$query_file);
	} else { // 기존에 첨부파일 DB 에 없던 값 
		if ($_FILES['file_logo']['size']>0){ // 업로드 파일이 있으면 인서트 
			$query_file = " insert into board_file set "; 
			$query_file .= " board_tbname = '".$board_tbname."', ";
			$query_file .= " board_code = '".$board_code."', ";
			$query_file .= " board_idx = '".$board_idx."', ";
			$query_file .= " file_org = '".$file_o."', ";
			$query_file .= " file_chg = '".$file_c."' ";
			$result_file = mysqli_query($gconnet,$query_file);
		} 
	} // 기존에 첨부파일 DB 에 있었는지 없었는지 모두 종료 
	
	error_frame_reload("설정 저장이 완료 되었습니다.");
?>