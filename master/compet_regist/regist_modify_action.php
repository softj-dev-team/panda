<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$bbs_code = "compet_regist_info";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";

	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$member_name = trim(sqlfilter($_REQUEST['member_name']));
	$work_title = trim(sqlfilter($_REQUEST['work_title']));
	$stock_ok = trim(sqlfilter($_REQUEST['stock_ok']));
	$work_detail = trim(sqlfilter($_REQUEST['work_detail']));
	
	$query = " update compet_regist_info set "; 
	$query .= " member_idx = '".$member_idx."', ";
	$query .= " member_name = '".$member_name."', ";
	$query .= " work_title = '".$work_title."', ";
	$query .= " stock_ok = '".$stock_ok."', ";
	$query .= " work_detail = '".$work_detail."', ";
	$query .= " mdate = now() ";
	$query .= " where 1 and idx='".$idx."' ";
	$result = mysqli_query($gconnet,$query);

	$compet_regist_info_idx = $idx; 
		
	##### 미리보기 이미지 업로드 시작 ####
	$board_tbname = "compet_regist_info";
	$board_code = "list";

	$sql_file = "select idx from board_file where 1=1 and board_tbname='".$board_tbname."' and board_code = '".$board_code."' and board_idx='".$compet_regist_info_idx."' order by idx asc";
	$query_file = mysqli_query($gconnet,$sql_file);
	$cnt_file = mysqli_num_rows($query_file);

	if($cnt_file < 1){
		$cnt_file = 1;
	}

	for($file_i=0; $file_i<$cnt_file; $file_i++){ // 설정된 갯수만큼 루프 시작
		
		$file_idx = trim(sqlfilter($_REQUEST['pfile_idx_'.$file_i])); // 기존 첨부파일 DB PK 값.
		$file_old_name = trim(sqlfilter($_REQUEST['pfile_old_name_'.$file_i])); // 원본 서버파일 이름
		$file_old_org = trim(sqlfilter($_REQUEST['pfile_old_org_'.$file_i]));	// 원본 오리지널 파일 이름
		$del_org = trim(sqlfilter($_REQUEST['pdel_org_'.$file_i]));	// 원본 파일 삭제여부

		if ($_FILES['photo_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
						
			if($file_old_name){
				unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
				unlink($_P_DIR_FILE."img_thumb/".$file_old_name); // 섬네일 파일 삭제
				//unlink($_P_DIR_FILE."img_thumb2/".$file_old_name); // 섬네일 파일 삭제
				//unlink($_P_DIR_FILE."img_thumb3/".$file_old_name); // 섬네일 파일 삭제
			}

			$file_o = $_FILES['photo_'.$file_i]['name']; 
			$i_width = "600";
			$i_height = "600";
			$i_width2 = "";
			$i_height2 = "";
			$i_width3 = "";
			$i_height3 = "";
			//$watermark_sect = "imgw";
			$watermark_sect = "";
			$file_c = uploadFileThumb_1($_FILES, "photo_".$file_i, $_FILES['photo_'.$file_i], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$i_width3,$i_height3,$watermark_sect);
			
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
					unlink($_P_DIR_FILE."img_thumb/".$file_old_name); // 섬네일 파일 삭제
					//unlink($_P_DIR_FILE."img_thumb2/".$file_old_name); // 섬네일 파일 삭제
					//unlink($_P_DIR_FILE."img_thumb3/".$file_old_name); // 섬네일 파일 삭제
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

			$board_idx = $compet_regist_info_idx;
			//echo $_FILES['file_'.$file_i]['size']."<br>";
			
			if ($_FILES['photo_'.$file_i]['size']>0){ // 업로드 파일이 있으면 인서트 
			
				$query_file = " insert into board_file set "; 
				$query_file .= " board_tbname = '".$board_tbname."', ";
				$query_file .= " board_code = '".$board_code."', ";
				$query_file .= " board_idx = '".$board_idx."', ";
				$query_file .= " file_org = '".$file_o."', ";
				$query_file .= " file_chg = '".$file_c."' ";
						
				$result_file = mysqli_query($gconnet,$query_file);

			} else { 
				$query_file = "";
			}

		} // 기존에 첨부파일 DB 에 있었는지 없었는지 모두 종료 
	} // 설정된 갯수만큼 루프 종료
	##### 미리보기 이미지 업로드 종료 ####

	##### 상세 작품 업로드 시작 ####
	$board_tbname = "compet_regist_info";
	$board_code = "detail";

	$sql_file = "select idx from board_file where 1=1 and board_tbname='".$board_tbname."' and board_code = '".$board_code."' and board_idx='".$compet_regist_info_idx."' order by idx asc";
	$query_file = mysqli_query($gconnet,$sql_file);
	$cnt_file = mysqli_num_rows($query_file);

	if($cnt_file < 1){
		$cnt_file = 1;
	}

	for($file_i=0; $file_i<$cnt_file; $file_i++){ // 설정된 갯수만큼 루프 시작
		
		$file_idx = trim(sqlfilter($_REQUEST['addpfile_idx_'.$file_i])); // 기존 첨부파일 DB PK 값.
		$file_old_name = trim(sqlfilter($_REQUEST['addpfile_old_name_'.$file_i])); // 원본 서버파일 이름
		$file_old_org = trim(sqlfilter($_REQUEST['addpfile_old_org_'.$file_i]));	// 원본 오리지널 파일 이름
		$del_org = trim(sqlfilter($_REQUEST['addpdel_org_'.$file_i]));	// 원본 파일 삭제여부

		if ($_FILES['addphoto_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
						
			if($file_old_name){
				unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
				unlink($_P_DIR_FILE."img_thumb/".$file_old_name); // 섬네일 파일 삭제
				//unlink($_P_DIR_FILE."img_thumb2/".$file_old_name); // 섬네일 파일 삭제
				//unlink($_P_DIR_FILE."img_thumb3/".$file_old_name); // 섬네일 파일 삭제
			}

			$file_o = $_FILES['addphoto_'.$file_i]['name']; 
			$i_width = "1024";
			$i_height = "";
			$i_width2 = "728";
			$i_height2 = "462";
			$i_width3 = "";
			$i_height3 = "";
			//$watermark_sect = "imgw";
			$watermark_sect = "";
			$file_c = uploadFileThumb_1($_FILES, "addphoto_".$file_i, $_FILES['addphoto_'.$file_i], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$i_width3,$i_height3,$watermark_sect);
			
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
					unlink($_P_DIR_FILE."img_thumb/".$file_old_name); // 섬네일 파일 삭제
					//unlink($_P_DIR_FILE."img_thumb2/".$file_old_name); // 섬네일 파일 삭제
					//unlink($_P_DIR_FILE."img_thumb3/".$file_old_name); // 섬네일 파일 삭제
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

			$board_idx = $compet_regist_info_idx;
			//echo $_FILES['file_'.$file_i]['size']."<br>";
			
			if ($_FILES['addphoto_'.$file_i]['size']>0){ // 업로드 파일이 있으면 인서트 
			
				$query_file = " insert into board_file set "; 
				$query_file .= " board_tbname = '".$board_tbname."', ";
				$query_file .= " board_code = '".$board_code."', ";
				$query_file .= " board_idx = '".$board_idx."', ";
				$query_file .= " file_org = '".$file_o."', ";
				$query_file .= " file_chg = '".$file_c."' ";
						
				$result_file = mysqli_query($gconnet,$query_file);

			} else { 
				$query_file = "";
			}

		} // 기존에 첨부파일 DB 에 있었는지 없었는지 모두 종료 
	} // 설정된 갯수만큼 루프 종료
	##### 상세 작품 업로드 종료 ####
	
	//exit;

	if($result){
		error_frame_go("정상적으로 수정 되었습니다.","regist_view.php?idx=".$idx."&".$total_param."");
	} else {
		error_frame("오류가 발생했습니다.");
	}
?>
