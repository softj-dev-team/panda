<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
	$mode = trim(sqlfilter($_REQUEST['mode']));
	$upidx = trim(sqlfilter($_REQUEST['upidx']));
	$m_intro = trim(sqlfilter($_REQUEST['m_intro']));

	$bbs_code = "delv_guide";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";

	if(!$m_intro){
		error_frame("내용을 입력하세요.");
	}

	if ($mode == "write"){
		$query = " insert into delv_guide set "; 
		$query .= " cate_code1 = '".$cate_code1."', ";
		$query .= " content = '".$m_intro."', ";
		$query .= " wdate = now() ";
		//echo $query; exit;
		$result = mysqli_query($gconnet,$query);

		$sql_pre2 = "select idx from delv_guide where 1 order by idx desc limit 0,1"; 
		$result_pre2  = mysqli_query($gconnet,$sql_pre2);
		$mem_row2 = mysqli_fetch_array($result_pre2);
		$ad_info_idx = $mem_row2[idx]; 

		##### 사진 업로드 시작 ####
		$board_tbname = "delv_guide";
		$board_code = $cate_code1;
		for($file_i=0; $file_i<1; $file_i++){ 
			if ($_FILES['photo_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
				$file_o = $_FILES['photo_'.$file_i]['name']; 
				$i_width = "1920";
				$i_height = "620";
				$i_width2 = "";
				$i_height2 = "";
				//$watermark_sect = "imgw";
				$watermark_sect = "";
				//$file_c = uploadFileThumb_1($_FILES, "photo_".$file_i, $_FILES['photo_'.$file_i], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$i_width3,$i_height3,$watermark_sect);

				$file_c = uploadFile($_FILES, "photo_".$file_i, $_FILES['photo_'.$file_i], $_P_DIR_FILE);

				$query_file = " insert into board_file set "; 
				$query_file .= " board_tbname = '".$board_tbname."', ";
				$query_file .= " board_code = '".$board_code."', ";
				$query_file .= " board_idx = '".$ad_info_idx."', ";
				$query_file .= " file_org = '".$file_o."', ";
				$query_file .= " file_chg = '".$file_c."' ";
				$result_file = mysqli_query($gconnet,$query_file);
			} // 파일이 있다면 업로드한다 종료
		}
		##### 사진 업로드 종료 ####

	} elseif ($mode == "update"){
		$query = " update delv_guide set "; 
		$query .= " content = '".$m_intro."', ";
		$query .= " wdate = now() ";
		$query .= " where 1 and idx='".$upidx."'";
		//echo $query; exit;
		$result = mysqli_query($gconnet,$query);

		##### 사진 업로드 시작 ####
		$board_tbname = "delv_guide";
		$board_code = $cate_code1;

		$sql_file = "select idx from board_file where 1=1 and board_tbname='".$board_tbname."' and board_code = '".$board_code."' and board_idx='".$upidx."' ";
		$query_file = mysqli_query($gconnet,$sql_file);
		$cnt_file = mysqli_num_rows($query_file);

		if($cnt_file < 1){
			$cnt_file =	1;
		}

		for($file_i=0; $file_i<$cnt_file; $file_i++){ // 설정된 갯수만큼 루프 시작
		
			$file_idx = trim(sqlfilter($_REQUEST['pfile_idx_'.$file_i])); // 기존 첨부파일 DB PK 값.
			$file_old_name = trim(sqlfilter($_REQUEST['pfile_old_name_'.$file_i])); // 원본 서버파일 이름
			$file_old_org = trim(sqlfilter($_REQUEST['pfile_old_org_'.$file_i]));	// 원본 오리지널 파일 이름
			$del_org = trim(sqlfilter($_REQUEST['pdel_org_'.$file_i]));	// 원본 파일 삭제여부

			if ($_FILES['photo_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
						
				if($file_old_name){
					unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
				}

				$file_o = $_FILES['photo_'.$file_i]['name']; 
				$i_width = "1920";
				$i_height = "620";
				$i_width2 = "";
				$i_height2 = "";
				//$watermark_sect = "imgw";
				$watermark_sect = "";
				//$file_c = uploadFileThumb_1($_FILES, "photo_".$file_i, $_FILES['photo_'.$file_i], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$i_width3,$i_height3,$watermark_sect);

				$file_c = uploadFile($_FILES, "photo_".$file_i, $_FILES['photo_'.$file_i], $_P_DIR_FILE);

				//echo $file_o." / ".$file_c."<br>";
			
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
					$query_file .= " file_chg = '".$file_c."' ";
					$query_file .= " where 1=1 and idx = '".$file_idx."' ";
				} else {
					$query_file = " delete from board_file "; 
					$query_file .= " where 1=1 and idx = '".$file_idx."' ";
				}

				$result_file = mysqli_query($gconnet,$query_file);

			} else { // 기존에 첨부파일 DB 에 없던 값 

				$board_idx = $upidx;
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
			//echo $query_file."<br>";
		} // 설정된 갯수만큼 루프 종료
		##### 사진 업로드 종료 ####
	}

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 완료 되었습니다.');
	parent.location.reload();
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
