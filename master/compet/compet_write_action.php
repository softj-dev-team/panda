<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$bbs_code = "compet_info";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";
	$pstatus = "com"; // 등록완료
	
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

	$max_query = "select max(align) as max from compet_info where 1 ";
	$max_result = mysqli_query($gconnet,$max_query);
	$max_row = mysqli_fetch_array($max_result);
	if ($max_row['max']){
		$align = $max_row['max']+1;
	} else{
		$align = 1;
	}
	
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
	
	$query = " insert into compet_info set "; 
	$query .= " pstatus = '".$pstatus."', ";
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
	$query .= " align = '".$align."', ";
	$query .= " wdate = now() ";
	$result = mysqli_query($gconnet,$query);

	$sql_pre2 = "select idx from compet_info where 1 order by idx desc limit 0,1"; 
	$result_pre2  = mysqli_query($gconnet,$sql_pre2);
	$mem_row2 = mysqli_fetch_array($result_pre2);
	$compet_info_idx = $mem_row2[idx]; 
		
	##### 자료 업로드 시작 ####
	$board_tbname = "compet_info";
	$board_code = "docu";
	for($file_i=0; $file_i<4; $file_i++){ 
		if ($_FILES['docu_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
			$file_o = $_FILES['docu_'.$file_i]['name']; 
			$file_c = uploadFile($_FILES, "docu_".$file_i, $_FILES['docu_'.$file_i], $_P_DIR_FILE);

			$query_file = " insert into board_file set "; 
			$query_file .= " board_tbname = '".$board_tbname."', ";
			$query_file .= " board_code = '".$board_code."', ";
			$query_file .= " board_idx = '".$compet_info_idx."', ";
			$query_file .= " file_org = '".$file_o."', ";
			$query_file .= " file_chg = '".$file_c."' ";
			$result_file = mysqli_query($gconnet,$query_file);
		} // 파일이 있다면 업로드한다 종료
	}
	##### 자료 업로드 종료 ####

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
		error_frame_go("정상적으로 등록 되었습니다.","compet_list.php?bmenu=3&smenu=1");
	} else {
		error_frame("오류가 발생했습니다.");
	}
?>
