<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$bbs_code = "compet_info";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";

	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$member_name = trim(sqlfilter($_REQUEST['member_name']));
	$member_email = trim(sqlfilter($_REQUEST['member_email']));
	$member_cell = trim(sqlfilter($_REQUEST['member_cell']));
	$com_name = trim(sqlfilter($_REQUEST['com_name']));
	$com_info = trim(sqlfilter($_REQUEST['com_info']));
	$compet_title = trim(sqlfilter($_REQUEST['compet_title']));
	$compet_sdate = trim(sqlfilter($_REQUEST['compet_sdate']));
	$compet_detail = trim(sqlfilter($_REQUEST['compet_detail']));
	$com_package = trim(sqlfilter($_REQUEST['com_package']));
	$compet_first_price = trim(sqlfilter($_REQUEST['compet_first_price']));
	$compet_second_ok = trim(sqlfilter($_REQUEST['compet_second_ok']));
	$compet_second_price = trim(sqlfilter($_REQUEST['compet_second_price']));
	$compet_third_price = trim(sqlfilter($_REQUEST['compet_third_price']));
	
	$com_period = trim(sqlfilter($_REQUEST['com_period']));
	$temp_date = str_replace("-","",$compet_sdate);

	/*if($com_period == "CG0011"){ // 1 주일
		$compet_edate = date("Y-m-d", strtotime("+1 week", strtotime($temp_date)));    
	} elseif($com_period == "CG0012"){ // 2 주일
		$compet_edate = date("Y-m-d", strtotime("+2 week", strtotime($temp_date)));    
	} elseif($com_period == "CG0013"){ // 4 주일
		$compet_edate = date("Y-m-d", strtotime("+4 week", strtotime($temp_date)));    
	}*/

	$compet_edate = trim(sqlfilter($_REQUEST['compet_edate']));
	
	$query = " update compet_info set "; 
	$query .= " member_idx = '".$member_idx."', ";
	$query .= " member_name = '".$member_name."', ";
	$query .= " member_email = '".$member_email."', ";
	$query .= " member_cell = '".$member_cell."', ";
	$query .= " com_name = '".$com_name."', ";
	$query .= " com_info = '".$com_info."', ";
	$query .= " compet_title = '".$compet_title."', ";
	$query .= " compet_sdate = '".$compet_sdate."', ";
	$query .= " compet_edate = '".$compet_edate."', ";
	$query .= " compet_detail = '".$compet_detail."', ";
	$query .= " compet_first_price = '".$compet_first_price."', ";
	$query .= " compet_second_ok = '".$compet_second_ok."', ";
	$query .= " compet_second_price = '".$compet_second_price."', ";
	$query .= " compet_third_price = '".$compet_third_price."', ";
	$query .= " com_package = '".$com_package."', ";
	$query .= " mdate = now() ";
	$query .= " where 1 and idx='".$idx."' ";
	$result = mysqli_query($gconnet,$query);

	$compet_info_idx = $idx; 
		
	##### 자료 업로드 시작 ####
	$board_tbname = "compet_info";
	$board_code = "docu";

	$sql_file = "select idx from board_file where 1 and board_tbname='".$board_tbname."' and board_code = '".$board_code."' and board_idx='".$compet_info_idx."' ";
	$query_file = mysqli_query($gconnet,$sql_file);
	$cnt_file = mysqli_num_rows($query_file);

	if($cnt_file < 6){
		$cnt_file = 6;
	}

	for($file_i=0; $file_i<$cnt_file; $file_i++){ // 설정된 갯수만큼 루프 시작
		
		$file_idx = trim(sqlfilter($_REQUEST['dfile_idx_'.$file_i])); // 기존 첨부파일 DB PK 값.
		$file_old_name = trim(sqlfilter($_REQUEST['dfile_old_name_'.$file_i])); // 원본 서버파일 이름
		$file_old_org = trim(sqlfilter($_REQUEST['dfile_old_org_'.$file_i]));	// 원본 오리지널 파일 이름
		$del_org = trim(sqlfilter($_REQUEST['ddel_org_'.$file_i]));	// 원본 파일 삭제여부

		if ($_FILES['docu_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
			if($file_old_name){
				unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
			}
			$file_o = $_FILES['docu_'.$file_i]['name']; 
			$file_c = uploadFile($_FILES, "docu_".$file_i, $_FILES['docu_'.$file_i], $_P_DIR_FILE); 
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
			if ($_FILES['docu_'.$file_i]['size']>0){ // 업로드 파일이 있으면 인서트 
				$query_file = " insert into board_file set "; 
				$query_file .= " board_tbname = '".$board_tbname."', ";
				$query_file .= " board_code = '".$board_code."', ";
				$query_file .= " board_idx = '".$compet_info_idx."', ";
				$query_file .= " file_org = '".$file_o."', ";
				$query_file .= " file_chg = '".$file_c."' ";
				$result_file = mysqli_query($gconnet,$query_file);
			} 
		} // 기존에 첨부파일 DB 에 있었는지 없었는지 모두 종료 
	} // 설정된 갯수만큼 루프 종료
	##### 자료 업로드 종료 ####

	$tmp_sql = "delete from compet_option_info where 1 and compet_idx = '".$compet_info_idx."'";
	$tmp_query = mysqli_query($gconnet,$tmp_sql);
	
	######### 업종 입력 시작 ###########
	if($_REQUEST['com_section']){
		$com_section = trim(sqlfilter($_REQUEST['com_section']));

		$query_cat = " insert into compet_option_info set "; 
		$query_cat .= " member_idx = '".$member_idx."', ";
		$query_cat .= " compet_idx = '".$compet_info_idx."', ";
		$query_cat .= " option_type = 'section', ";
		$query_cat .= " option_code = '".$com_section."', ";
		$query_cat .= " option_name = '".get_code_value("cate_name1","cate_code1",$com_section)."', ";
		$query_cat .= " option_price = '".get_code_value("cate_desc1","cate_code1",$com_section)."', ";
		$query_cat .= " wdate = now() ";
		$result_cat = mysqli_query($gconnet,$query_cat);
	}

	######### 기본옵션 입력 시작 ###########
	if($_REQUEST['com_basic']){
		$com_basic = trim(sqlfilter($_REQUEST['com_basic']));

		$query_cat = " insert into compet_option_info set "; 
		$query_cat .= " member_idx = '".$member_idx."', ";
		$query_cat .= " compet_idx = '".$compet_info_idx."', ";
		$query_cat .= " option_type = 'basic', ";
		$query_cat .= " option_code = '".$com_basic."', ";
		$query_cat .= " option_name = '".get_code_value("cate_name1","cate_code1",$com_basic)."', ";
		$query_cat .= " option_price = '".get_code_value("cate_desc1","cate_code1",$com_basic)."', ";
		$query_cat .= " wdate = now() ";
		$result_cat = mysqli_query($gconnet,$query_cat);
	}

	######### 진행기간 입력 시작 ###########
	if($_REQUEST['com_period']){
		$com_period = trim(sqlfilter($_REQUEST['com_period']));

		$query_cat = " insert into compet_option_info set "; 
		$query_cat .= " member_idx = '".$member_idx."', ";
		$query_cat .= " compet_idx = '".$compet_info_idx."', ";
		$query_cat .= " option_type = 'period', ";
		$query_cat .= " option_code = '".$com_period."', ";
		$query_cat .= " option_name = '".get_code_value("cate_name1","cate_code1",$com_period)."', ";
		$query_cat .= " option_price = '".get_code_value("cate_desc1","cate_code1",$com_period)."', ";
		$query_cat .= " wdate = now() ";
		$result_cat = mysqli_query($gconnet,$query_cat);
	}

	######### 노출옵션 입력 시작 ###########
	if($_REQUEST['com_display']){
		$com_display_arr = $_REQUEST['com_display'];
		
		for($k=0; $k<sizeof($com_display_arr); $k++){
			$com_display = trim($com_display_arr[$k]);

			$query_cat = " insert into compet_option_info set "; 
			$query_cat .= " member_idx = '".$member_idx."', ";
			$query_cat .= " compet_idx = '".$compet_info_idx."', ";
			$query_cat .= " option_type = 'display', ";
			$query_cat .= " option_code = '".$com_display."', ";
			$query_cat .= " option_name = '".get_code_value("cate_name1","cate_code1",$com_display)."', ";
			$query_cat .= " option_price = '".get_code_value("cate_desc1","cate_code1",$com_display)."', ";
			$query_cat .= " wdate = now() ";
			$result_cat = mysqli_query($gconnet,$query_cat);
		}
	}
	
	//exit;

	if($result){
		error_frame_go("정상적으로 수정 되었습니다.","compet_view.php?idx=".$idx."&".$total_param."");
	} else {
		error_frame("오류가 발생했습니다.");
	}
?>
