<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$bbs_code = "compet_regist_info";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";
	
	$compet_idx = trim(sqlfilter($_REQUEST['compet_idx']));
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$member_name = trim(sqlfilter($_REQUEST['member_name']));
	$work_title = trim(sqlfilter($_REQUEST['work_title']));
	$stock_ok = trim(sqlfilter($_REQUEST['stock_ok']));
	$work_detail = trim(sqlfilter($_REQUEST['work_detail']));
	
	$max_query = "select max(align) as max from compet_regist_info where 1 ";
	$max_result = mysqli_query($gconnet,$max_query);
	$max_row = mysqli_fetch_array($max_result);
	if ($max_row['max']){
		$align = $max_row['max']+1;
	} else{
		$align = 1;
	}
		
	$query = " insert into compet_regist_info set "; 
	$query .= " compet_idx = '".$compet_idx."', ";
	$query .= " member_idx = '".$member_idx."', ";
	$query .= " member_name = '".$member_name."', ";
	$query .= " work_title = '".$work_title."', ";
	$query .= " stock_ok = '".$stock_ok."', ";
	$query .= " work_detail = '".$work_detail."', ";
	$query .= " align = '".$align."', ";
	$query .= " wdate = now() ";
	$result = mysqli_query($gconnet,$query);

	$sql_pre2 = "select idx from compet_regist_info where 1 order by idx desc limit 0,1"; 
	$result_pre2  = mysqli_query($gconnet,$sql_pre2);
	$mem_row2 = mysqli_fetch_array($result_pre2);
	$compet_regist_info_idx = $mem_row2[idx]; 
		
	##### 미리보기 이미지 시작 ####
	$board_tbname = "compet_regist_info";
	$board_code = "list";
	for($file_i=0; $file_i<4; $file_i++){ 
		if ($_FILES['photo_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
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

			$query_file = " insert into board_file set "; 
			$query_file .= " board_tbname = '".$board_tbname."', ";
			$query_file .= " board_code = '".$board_code."', ";
			$query_file .= " board_idx = '".$compet_regist_info_idx."', ";
			$query_file .= " file_org = '".$file_o."', ";
			$query_file .= " file_chg = '".$file_c."' ";
			$result_file = mysqli_query($gconnet,$query_file);
		} // 파일이 있다면 업로드한다 종료
	}
	##### 미리보기 이미지 종료 ####

	##### 상세작품 이미지 시작 ####
	$board_tbname = "compet_regist_info";
	$board_code = "detail";

	 for($file_i=0; $file_i<4; $file_i++){ 
		if ($_FILES['addphoto_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
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

			$query_file = " insert into board_file set "; 
			$query_file .= " board_tbname = '".$board_tbname."', ";
			$query_file .= " board_code = '".$board_code."', ";
			$query_file .= " board_idx = '".$compet_regist_info_idx."', ";
			$query_file .= " file_org = '".$file_o."', ";
			$query_file .= " file_chg = '".$file_c."' ";
			$result_file = mysqli_query($gconnet,$query_file);
		} 
	} 
	##### 상세작품 이미지 종료 ####

	//exit;

	$query_comp = " update compet_info set "; 
	$query_comp .= " rcnt = rcnt+1 ";
	$query_comp .= " where 1 and idx = '".$compet_idx."'";
	$result_comp = mysqli_query($gconnet,$query_comp); // 참가자 수 증가

	if($result){
		error_frame_go("정상적으로 등록 되었습니다.","regist_list.php?bmenu=5&smenu=1");
	} else {
		error_frame("오류가 발생했습니다.");
	}
?>
