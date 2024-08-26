<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	//echo '<pre>';print_r($_POST);echo '</pre>'; 

	$bbs_code = "curri_info";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";
	$tmp_FILE = $_P_DIR_FILE;
	
	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
	$curri_type = trim(sqlfilter($_REQUEST['curri_type']));
	$attach_count_1 = trim(sqlfilter($_REQUEST['attach_count_1']));
	
	if($curri_type == "CG0006" || $curri_type == "CG0007" || $curri_type == "CG0008"){
		if($attach_count_1 > 16){
			error_frame("소주제 추가는 16개 까지만 가능합니다.");
		}
	} else {
		if($attach_count_1 > 10){
			error_frame("소주제 추가는 10개 까지만 가능합니다.");
		}
	}
	
	$curri_title = trim(sqlfilter($_REQUEST['curri_title']));
	$align = trim(sqlfilter($_REQUEST['align']));
	$view_ok = trim(sqlfilter($_REQUEST['view_ok']));
	$curri_detail = trim(sqlfilter($_REQUEST['curri_detail']));
		
	$query = " insert into curri_info set ";
	$query .= " curri_type = '".$curri_type."', ";
	$query .= " curri_title = '".$curri_title."', ";
	$query .= " curri_detail = '".$curri_detail."', ";
	$query .= " view_ok = '".$view_ok."', ";
	$query .= " align = '".$align."', ";
	$query .= " wdate = now() ";
	$result = mysqli_query($gconnet,$query);

	$sql_pre2 = "select idx from curri_info where 1 order by idx desc limit 0,1"; 
	$result_pre2  = mysqli_query($gconnet,$sql_pre2);
	$mem_row2 = mysqli_fetch_array($result_pre2);
	$curri_info_idx = $mem_row2[idx]; 
		
	##### 사진 업로드 시작 ####
	$board_tbname = "curri_info";
	
	$board_code = "sphoto"; // 이미지
	for($file_i=0; $file_i<1; $file_i++){ 
		if ($_FILES['photo1_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
			$file_o = $_FILES['photo1_'.$file_i]['name']; 
			$i_width = "500";
			$i_height = "354";
			$i_width2 = "";
			$i_height2 = "";
			/*$i_width3 = "370";
			$i_height3 = "130";*/
			//$watermark_sect = "imgw";
			$watermark_sect = "";
			$file_c = uploadFileThumb_1($_FILES, "photo1_".$file_i, $_FILES['photo1_'.$file_i], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$i_width3,$i_height3,$watermark_sect);
			
			$query_file = " insert into board_file set "; 
			$query_file .= " board_tbname = '".$board_tbname."', ";
			$query_file .= " board_code = '".$board_code."', ";
			$query_file .= " board_idx = '".$curri_info_idx."', ";
			$query_file .= " file_org = '".$file_o."', ";
			$query_file .= " file_chg = '".$file_c."' ";
			$result_file = mysqli_query($gconnet,$query_file);
		} // 파일이 있다면 업로드한다 종료
	}
	##### 사진 업로드 종료 ####
	
	$_P_DIR_FILE = $_SERVER["DOCUMENT_ROOT"]."/upload_file/";

	$bbs_code = "curri_lecture_info";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";
	$tmp_FILE = $_P_DIR_FILE;

	##### 듣기문항 소주제 등록 시작 ####
	for($file_i=0; $file_i<$attach_count_1; $file_i++){ // 소주제 설정된 갯수만큼 루프 시작
		
		$lecture_title = trim(sqlfilter($_REQUEST['lecture_title_'.$file_i]));
		$lecture_correct = trim(sqlfilter($_REQUEST['lecture_correct_'.$file_i]));
		$lecture_correct_kor = trim(sqlfilter($_REQUEST['lecture_correct_kor_'.$file_i]));
		$lecture_hint = trim(sqlfilter($_REQUEST['lecture_hint_'.$file_i]));

		$cate_code_sql = "select idx from curri_lecture_info where 1 and curri_info_idx = '".$curri_info_idx."' and is_del='N'";
		$cate_code_query = mysqli_query($gconnet,$cate_code_sql);
		$cate_code_num = mysqli_num_rows($cate_code_query);
		$cate_code_num = $cate_code_num+1;
		$align = $cate_code_num;
		
		$query_lecture = " insert into curri_lecture_info set ";
		$query_lecture .= " curri_info_idx = '".$curri_info_idx."', ";
		$query_lecture .= " lecture_title = '".$lecture_title."', ";
		$query_lecture .= " lecture_correct = '".$lecture_correct."', ";
		$query_lecture .= " lecture_correct_kor = '".$lecture_correct_kor."', ";
		$query_lecture .= " lecture_hint = '".$lecture_hint."', ";
		$query_lecture .= " align = '".$align."', ";
		$query_lecture .= " wdate = now() ";
		$result_lecture = mysqli_query($gconnet,$query_lecture);

		$sql_pre3 = "select idx from curri_lecture_info where 1 order by idx desc limit 0,1"; 
		$result_pre3  = mysqli_query($gconnet,$sql_pre3);
		$mem_row3 = mysqli_fetch_array($result_pre3);
		$curri_lecture_idx = $mem_row3[idx];
		
		##### 소주제 섬네일 업로드 시작 ####
		$board_tbname = "curri_lecture_info";
		$board_code = "photo";

		$sql_file = "select idx from board_file where 1=1 and board_tbname='".$board_tbname."' and board_code = '".$board_code."' and board_idx='".$curri_lecture_idx."' order by idx asc";
		$query_file = mysqli_query($gconnet,$sql_file);
		$cnt_file = mysqli_num_rows($query_file);

		if($cnt_file < 1){
			$cnt_file = 1;
		}

		for($file_i2=0; $file_i2<$cnt_file; $file_i2++){ // 설정된 갯수만큼 루프 시작
		
			$file_idx = trim(sqlfilter($_REQUEST['pfile2_idx_'.$file_i.'_'.$file_i2])); // 기존 첨부파일 DB PK 값.
			$file_old_name = trim(sqlfilter($_REQUEST['pfile2_old_name_'.$file_i.'_'.$file_i2])); // 원본 서버파일 이름
			$file_old_org = trim(sqlfilter($_REQUEST['pfile2_old_org_'.$file_i.'_'.$file_i2]));	// 원본 오리지널 파일 이름
			$del_org = trim(sqlfilter($_REQUEST['pdel2_org_'.$file_i.'_'.$file_i2]));	// 원본 파일 삭제여부

			if ($_FILES['photo2_'.$file_i.'_'.$file_i2]['size']>0){ // 파일이 있다면 업로드한다 시작
				if($file_old_name){
					unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
					unlink($tmp_FILE."img_thumb/".$file_old_name); // 섬네일 파일 삭제
				}
				$file_o = $_FILES['photo2_'.$file_i.'_'.$file_i2]['name']; 
				$i_width = "240";
				$i_height = "170";
				$i_width2 = "";
				$i_height2 = "";
				/*$i_width3 = "370";
				$i_height3 = "130";*/
				//$watermark_sect = "imgw";
				$watermark_sect = "";
				$file_c = uploadFileThumb_1($_FILES, 'photo2_'.$file_i.'_'.$file_i2, $_FILES['photo2_'.$file_i.'_'.$file_i2], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$i_width3,$i_height3,$watermark_sect);
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
						unlink($tmp_FILE."img_thumb/".$file_old_name); // 섬네일 파일 삭제
					}
					$file_c = "";
					$file_o = "";
				}
			} //  파일이 없을때 종료 

			if($file_idx){ // 기존에 첨부파일 DB 에 있던 값
				if ($file_o && $file_c){ // 파일이 있으면 업데이트, 없으면 삭제 
					$query_file = " update board_file set "; 
					$query_file .= " file_org = '".$file_o."', ";
					$query_file .= " file_chg = '".$file_c."', ";
					$query_file .= " file_content = '".$file_content."' ";
					$query_file .= " where 1=1 and idx = '".$file_idx."' ";
				} else {
					$query_file = " delete from board_file "; 
					$query_file .= " where 1=1 and idx = '".$file_idx."' ";
				}
				$result_file = mysqli_query($gconnet,$query_file);
			} else { // 기존에 첨부파일 DB 에 없던 값 
				$board_idx = $curri_lecture_idx;
				if ($_FILES['photo2_'.$file_i.'_'.$file_i2]['size']>0){ // 업로드 파일이 있으면 인서트 
					$query_file = " insert into board_file set "; 
					$query_file .= " board_tbname = '".$board_tbname."', ";
					$query_file .= " board_code = '".$board_code."', ";
					$query_file .= " board_idx = '".$board_idx."', ";
					$query_file .= " file_org = '".$file_o."', ";
					$query_file .= " file_chg = '".$file_c."', ";
					$query_file .= " file_content = '".$file_content."' ";
					$result_file = mysqli_query($gconnet,$query_file);
				} else { 
					$query_file = "";
				}
			} // 기존에 첨부파일 DB 에 있었는지 없었는지 모두 종료 
		} // 설정된 갯수만큼 루프 종료
		##### 소주제 섬네일 업로드 종료 ####

		##### 소주제 음원 업로드 시작 ####
		$board_tbname = "curri_lecture_info";
		$board_code = "movie";

		$sql_file = "select idx from board_file where 1=1 and board_tbname='".$board_tbname."' and board_code = '".$board_code."' and board_idx='".$curri_lecture_idx."' order by idx asc";
		$query_file = mysqli_query($gconnet,$sql_file);
		$cnt_file = mysqli_num_rows($query_file);

		if($cnt_file < 1){
			$cnt_file = 1;
		}

		for($file_i2=0; $file_i2<$cnt_file; $file_i2++){ // 설정된 갯수만큼 루프 시작
		
			$file_idx = trim(sqlfilter($_REQUEST['pfile3_idx_'.$file_i.'_'.$file_i2])); // 기존 첨부파일 DB PK 값.
			$file_old_name = trim(sqlfilter($_REQUEST['pfile3_old_name_'.$file_i.'_'.$file_i2])); // 원본 서버파일 이름
			$file_old_org = trim(sqlfilter($_REQUEST['pfile3_old_org_'.$file_i.'_'.$file_i2]));	// 원본 오리지널 파일 이름
			$del_org = trim(sqlfilter($_REQUEST['pdel3_org_'.$file_i.'_'.$file_i2]));	// 원본 파일 삭제여부

			if ($_FILES['photo3_'.$file_i.'_'.$file_i2]['size']>0){ // 파일이 있다면 업로드한다 시작
				if($file_old_name){
					unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
				}
				$file_o = $_FILES['photo3_'.$file_i.'_'.$file_i2]['name']; 
				$file_c = uploadFile($_FILES, 'photo3_'.$file_i.'_'.$file_i2, $_FILES['photo3_'.$file_i.'_'.$file_i2], $_P_DIR_FILE);
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
					}
					$file_c = "";
					$file_o = "";
				}
			} //  파일이 없을때 종료 

			if($file_idx){ // 기존에 첨부파일 DB 에 있던 값
				if ($file_o && $file_c){ // 파일이 있으면 업데이트, 없으면 삭제 
					$query_file = " update board_file set "; 
					$query_file .= " file_org = '".$file_o."', ";
					$query_file .= " file_chg = '".$file_c."', ";
					$query_file .= " file_content = '".$file_content."' ";
					$query_file .= " where 1=1 and idx = '".$file_idx."' ";
				} else {
					$query_file = " delete from board_file "; 
					$query_file .= " where 1=1 and idx = '".$file_idx."' ";
				}
				$result_file = mysqli_query($gconnet,$query_file);
			} else { // 기존에 첨부파일 DB 에 없던 값 
				$board_idx = $curri_lecture_idx;
				if ($_FILES['photo3_'.$file_i.'_'.$file_i2]['size']>0){ // 업로드 파일이 있으면 인서트 
					$query_file = " insert into board_file set "; 
					$query_file .= " board_tbname = '".$board_tbname."', ";
					$query_file .= " board_code = '".$board_code."', ";
					$query_file .= " board_idx = '".$board_idx."', ";
					$query_file .= " file_org = '".$file_o."', ";
					$query_file .= " file_chg = '".$file_c."', ";
					$query_file .= " file_content = '".$file_content."' ";
					$result_file = mysqli_query($gconnet,$query_file);
				} else { 
					$query_file = "";
				}
			} // 기존에 첨부파일 DB 에 있었는지 없었는지 모두 종료 
		} // 설정된 갯수만큼 루프 종료
		##### 소주제 음원 업로드 종료 ####

	} // 소주제 설정된 갯수만큼 루프 시작
	
	##### 듣기문항 소주제 등록 종료 ####
	
	//exit;

	if($result){
		error_frame_go("정상적으로 등록 되었습니다.","curri_view.php?bmenu=".$bmenu."&smenu=".$smenu."&v_sect=".$curri_type."&idx=".$curri_info_idx."");
	} else {
		error_frame("오류가 발생했습니다.");
	}
?>
