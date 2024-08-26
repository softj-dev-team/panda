<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
	
	$send_type = trim(sqlfilter($_REQUEST['send_type']));
	$sms_type = trim(sqlfilter($_REQUEST['sms_type']));
	$sms_category = trim(sqlfilter($_REQUEST['sms_category']));
	$admin_idx = $_SESSION['manage_coinc_idx'];
	$sms_content = trim(sqlfilter($_REQUEST['sms_content']));
	
	$query = "insert into sms_save set"; 
	$query .= " send_type = '".$send_type."', ";
	$query .= " sms_type = '".$sms_type."', ";
	$query .= " sms_category = '".$sms_category."', ";
	$query .= " admin_idx = '".$admin_idx."', ";
	$query .= " sms_content = '".$sms_content."', ";
	$query .= " sample_yn = 'Y', ";
	$query .= " wdate = now() ";	
	$result = mysqli_query($gconnet,$query);
	
	$save_idx = mysqli_insert_id($gconnet);
		
	$bbs_code = "sms";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";
	$_P_DIR_FILE2 = $_P_DIR_FILE."img_thumb/";
	$board_tbname = "sms_save";
	$board_code = "mms";
	
	$file_i = 0;
	$file_idx = trim(sqlfilter($_REQUEST['file_idx_'.$file_i])); // 기존 첨부파일 DB PK 값.
	$file_old_name = trim(sqlfilter($_REQUEST['file_old_name_'.$file_i])); // 원본 서버파일 이름
	$file_old_org = trim(sqlfilter($_REQUEST['file_old_org_'.$file_i]));	// 원본 오리지널 파일 이름
	$del_org = trim(sqlfilter($_REQUEST['del_org_'.$file_i]));	// 원본 파일 삭제여부

	if ($_FILES['file_add_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
		if($file_old_name){
			unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
			unlink($_P_DIR_FILE2.$file_old_name); // 섬네일 파일 삭제
		}
		$file_o = $_FILES['file_add_'.$file_i]['name']; 
		$i_width = "640";
		$i_height = "960";
		$watermark_sect = "";
		$file_c = uploadFileThumb_1($_FILES, "file_add_".$file_i, $_FILES['file_add_'.$file_i], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$watermark_sect);
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
				unlink($_P_DIR_FILE2.$file_old_name);
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
			$query_file .= " where 1 and idx = '".$file_idx."' ";
		} else {
			$query_file = " delete from board_file "; 
			$query_file .= " where 1 and idx = '".$file_idx."' ";
		}
		$result_file = mysqli_query($gconnet,$query_file);
	} else { // 기존에 첨부파일 DB 에 없던 값 
		$board_idx = $save_idx;
		if ($_FILES['file_add_'.$file_i]['size']>0){ // 업로드 파일이 있으면 인서트 
			$query_file = " insert into board_file set "; 
			$query_file .= " board_tbname = '".$board_tbname."', ";
			$query_file .= " board_code = '".$board_code."', ";
			$query_file .= " board_idx = '".$board_idx."', ";
			$query_file .= " file_org = '".$file_o."', ";
			$query_file .= " file_chg = '".$file_c."', ";
			$query_file .= " file_content = '".$file_content."' ";
			$result_file = mysqli_query($gconnet,$query_file);
		} 
	} // 기존에 첨부파일 DB 에 있었는지 없었는지 모두 종료 
	
	//echo $query;
	
	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('등록이 정상적으로 완료 되었습니다.');
	parent.location.href = "sms_sample_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('등록중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>