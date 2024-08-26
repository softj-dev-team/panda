<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
	$v_step = trim(sqlfilter($_REQUEST['v_step']));
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$field = trim(sqlfilter($_REQUEST['field']));
	$keyword = sqlfilter($_REQUEST['keyword']);
	
	$type = trim(sqlfilter($_REQUEST['type']));
	$cate_level = trim(sqlfilter($_REQUEST['cate_level']));
	$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
	$cate_name1 = trim(sqlfilter($_REQUEST['cate_name1']));
	$cate_desc1 = trim(sqlfilter($_REQUEST['cate_desc1']));
	$cate_code2 = trim(sqlfilter($_REQUEST['cate_code2']));
	$cate_name2 = trim(sqlfilter($_REQUEST['cate_name2']));
	$cate_code3 = trim(sqlfilter($_REQUEST['cate_code3']));
	$cate_name3 = trim(sqlfilter($_REQUEST['cate_name3']));
	$cate_code4 = trim(sqlfilter($_REQUEST['cate_code4']));
	$cate_name4 = trim(sqlfilter($_REQUEST['cate_name4']));
	$is_del = trim(sqlfilter($_REQUEST['is_del']));
	$cate_align = trim(sqlfilter($_REQUEST['cate_align']));
	
	$cate_code_sql = "select idx from common_code where 1";
	$cate_code_query = mysqli_query($gconnet,$cate_code_sql);
	$cate_code_num = mysqli_num_rows($cate_code_query);
	$cate_code_num = $cate_code_num+1;
	
	if(!$cate_align){
		$cate_align = $cate_code_num;
	}

	if($cate_code_num < 10){
		$cate_code_ran = "CG000".$cate_code_num;
	} elseif($cate_code_num >= 10 && $cate_code_num < 100){
		$cate_code_ran = "CG00".$cate_code_num;
	} elseif($cate_code_num >= 100 && $cate_code_num < 1000){
		$cate_code_ran = "CG0".$cate_code_num;
	} elseif($cate_code_num >= 1000){
		$cate_code_ran = "CG".$cate_code_num;
	}

	$wdate = date("Y-m-d H:i:s");
	
	$bbs = "cate_banner";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE.$bbs."/";

	if ($_FILES['file1']['size']>0){
		$file_o = $_FILES['file1']['name']; 
		$i_width = "255";
		$i_height = "251";
		$file_c = uploadFileThumb_1($_FILES, "file1", $_FILES['file1'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2);
	}

	if($cate_level == 1){

		$cate_code1 = $cate_code_ran;
				
		$query = " insert into common_code set "; 
		$query .= " type = '".$type."', ";
		$query .= " cate_level = '".$cate_level."', ";
		$query .= " cate_code1 = '".$cate_code1."', ";
		$query .= " cate_name1 = '".$cate_name1."', ";
		$query .= " cate_desc1 = '".$cate_desc1."', ";
		$query .= " cate_code2 = '".$cate_code2."', ";
		$query .= " cate_name2 = '".$cate_name2."', ";
		$query .= " cate_code3 = '".$cate_code3."', ";
		$query .= " cate_name3 = '".$cate_name3."', ";
		$query .= " cate_code4 = '".$cate_code4."', ";
		$query .= " cate_name4 = '".$cate_name4."', ";
		$query .= " cate_align = '".$cate_align."', ";
		$query .= " wdate = now() ";

		$result = mysqli_query($gconnet,$query);
		
		//exit;

		if($result){
			error_frame_go("등록이 정상적으로 완료 되었습니다.","cate_list.php?bmenu=".$bmenu."&smenu=".$smenu."&v_step=".$v_step."");
		} else {
			error_frame("오류가 발생했습니다.");
		}

	} elseif($cate_level == 2){

		$cate_code2 = $cate_code_ran;
				
		$query = " insert into common_code set "; 
		$query .= " type = '".$type."', ";
		$query .= " cate_level = '".$cate_level."', ";
		$query .= " cate_code1 = '".$cate_code1."', ";
		$query .= " cate_name1 = '".$cate_name1."', ";
		$query .= " cate_code2 = '".$cate_code2."', ";
		$query .= " cate_name2 = '".$cate_name2."', ";
		$query .= " cate_code3 = '".$cate_code3."', ";
		$query .= " cate_name3 = '".$cate_name3."', ";
		$query .= " cate_code4 = '".$cate_code4."', ";
		$query .= " cate_name4 = '".$cate_name4."', ";
		$query .= " cate_align = '".$cate_align."', ";
		$query .= " wdate = now() ";

		$result = mysqli_query($gconnet,$query);
		
		if($result){
			error_frame_go("등록이 정상적으로 완료 되었습니다.","cate_list.php?bmenu=".$bmenu."&smenu=".$smenu."&v_step=2&pageNo=".$pageNo."&field=".$field."&keyword=".$keyword."&cate_code1=".$cate_code1."");
		} else {
			error_frame("오류가 발생했습니다.");
		}

	} elseif($cate_level == 3){

		$cate_code3 = $cate_code_ran;
		
		$query = " insert into common_code set "; 
		$query .= " type = '".$type."', ";
		$query .= " cate_level = '".$cate_level."', ";
		$query .= " cate_code1 = '".$cate_code1."', ";
		$query .= " cate_name1 = '".$cate_name1."', ";
		$query .= " cate_code2 = '".$cate_code2."', ";
		$query .= " cate_name2 = '".$cate_name2."', ";
		$query .= " cate_code3 = '".$cate_code3."', ";
		$query .= " cate_name3 = '".$cate_name3."', ";
		$query .= " cate_code4 = '".$cate_code4."', ";
		$query .= " cate_name4 = '".$cate_name4."', ";
		$query .= " cate_align = '".$cate_align."', ";
		$query .= " wdate = now() ";
		
		//echo $query; 

		$result = mysqli_query($gconnet,$query);
		
		if($result){
			error_frame_go("등록이 정상적으로 완료 되었습니다.","cate_list.php?bmenu=".$bmenu."&smenu=".$smenu."&v_step=3&pageNo=".$pageNo."&field=".$field."&keyword=".$keyword."&cate_code1=".$cate_code1."&cate_code2=".$cate_code2."");
		} else {
			error_frame("오류가 발생했습니다.");
		}

	} elseif($cate_level == 4){
		
		$cate_code4 = $cate_code_ran;

		$query = " insert into common_code set "; 
		$query .= " type = '".$type."', ";
		$query .= " cate_level = '".$cate_level."', ";
		$query .= " cate_code1 = '".$cate_code1."', ";
		$query .= " cate_name1 = '".$cate_name1."', ";
		$query .= " cate_code2 = '".$cate_code2."', ";
		$query .= " cate_name2 = '".$cate_name2."', ";
		$query .= " cate_code3 = '".$cate_code3."', ";
		$query .= " cate_name3 = '".$cate_name3."', ";
		$query .= " cate_code4 = '".$cate_code4."', ";
		$query .= " cate_name4 = '".$cate_name4."', ";
		$query .= " cate_align = '".$cate_align."', ";
		$query .= " wdate = now() ";

		$result = mysqli_query($gconnet,$query);
		
		if($result){
			error_frame_go("등록이 정상적으로 완료 되었습니다.","cate_list.php?bmenu=".$bmenu."&smenu=".$smenu."&v_step=3&pageNo=".$pageNo."&field=".$field."&keyword=".$keyword."&cate_code1=".$cate_code1."&cate_code2=".$cate_code2."&cate_code3=".$cate_code3."");
		} else {
			error_frame("오류가 발생했습니다.");
		}

	} 
?>
	
