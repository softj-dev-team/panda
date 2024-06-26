<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	//$_FILES['file_preview'] = reArrayFiles($_FILES['file_preview']);

	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
	
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$parklot_name = trim(sqlfilter($_REQUEST['parklot_name']));
	$sido = trim(sqlfilter($_REQUEST['sido']));
	$gugun = trim(sqlfilter($_REQUEST['gugun']));
	$parklot_zip = trim(sqlfilter($_REQUEST['parklot_zip']));
	$parklot_addr1 = trim(sqlfilter($_REQUEST['parklot_addr1']));
	$parklot_addr2 = trim(sqlfilter($_REQUEST['parklot_addr2']));
		
	$xml_url ="https://maps.googleapis.com/maps/api/geocode/xml?address=".urlencode($parklot_addr1)."&key=AIzaSyAW_TeBhoUnzqIy-WBKLc_71qGAZUPh_T0";
	//echo "xml url test = ".$xml_url."<br>";
	include_once $_SERVER["DOCUMENT_ROOT"]."/pro_inc/snoopy/Snoopy.class.php"; 
	$snoopy = new snoopy;
	$snoopy->fetch($xml_url);
	$xml = simplexml_load_string($snoopy->results) or die ("Error: Cannot create object 3"); 
	$map_x = $xml->result->geometry->location->lat;
	$map_y = $xml->result->geometry->location->lng;
	//echo "map_x = ".$map_x."<br>";
	//echo "map_y = ".$map_y."<br>";
	
	$parklot_cell = trim(sqlfilter($_REQUEST['parklot_cell']));
	$birthday = trim(sqlfilter($_REQUEST['birthday']));
	$car_num = trim(sqlfilter($_REQUEST['car_num']));
	$time_1 = trim(sqlfilter($_REQUEST['time_1']));
	$price_1 = trim(sqlfilter($_REQUEST['price_1']));
	$time_2 = trim(sqlfilter($_REQUEST['time_2']));
	$price_2 = trim(sqlfilter($_REQUEST['price_2']));
	$price_3 = trim(sqlfilter($_REQUEST['price_3']));
	$price_4 = trim(sqlfilter($_REQUEST['price_4']));
	$rule_info = trim(sqlfilter($_REQUEST['rule_info']));
		
	$max_query = "select max(idx) as max from parklot_info where 1";
	$max_result = mysqli_query($gconnet,$max_query);
	$max_row = mysqli_fetch_array($max_result);
	if ($max_row['max']){
		$align = $max_row['max']+1;
	} else{
		$align = 1;
	}

	if($align < 10){
		$parklot_code = "P000".$align;
	} elseif($align >= 10 && $align < 100){
		$parklot_code = "P00".$align;
	} elseif($align >= 100 && $align < 1000){
		$parklot_code = "P0".$align;
	} elseif($align >= 1000){
		$parklot_code = "P".$align;
	}

	$query = "insert into parklot_info set";
	$query .= " member_idx = '".$member_idx."', ";
	$query .= " parklot_code = '".$parklot_code."', ";
	$query .= " parklot_name = '".$parklot_name."', ";
	$query .= " sido = '".$sido."', ";
	$query .= " gugun = '".$gugun."', ";
	$query .= " parklot_zip = '".$parklot_zip."', ";
	$query .= " parklot_addr1 = '".$parklot_addr1."', ";
	$query .= " parklot_addr2 = '".$parklot_addr2."', ";
	$query .= " map_x = '".$map_x."', ";
	$query .= " map_y = '".$map_y."', ";
	$query .= " parklot_cell = '".$parklot_cell."', ";
	$query .= " birthday = '".$birthday."', ";
	$query .= " car_num = '".$car_num."', ";
	$query .= " time_1 = '".$time_1."', ";
	$query .= " price_1 = '".$price_1."', ";
	$query .= " time_2 = '".$time_2."', ";
	$query .= " price_2 = '".$price_2."', ";
	$query .= " price_3 = '".$price_3."', ";
	$query .= " price_4 = '".$price_4."', ";
	$query .= " rule_info = '".$rule_info."', ";
	$query .= " wdate = now(), ";
	$query .= " mdate = now() ";

	//echo "query = ".$query."<br>";
	$result = mysqli_query($gconnet,$query);

	$parklot_idx = mysqli_insert_id($gconnet);

	################# 첨부파일 업로드 시작 #######################
	$bbs_code = "parklot";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";
	$_P_DIR_FILE2 = $_P_DIR_FILE."img_thumb/";
	$board_tbname = "parklot_info";
	$board_code = "image";

	$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='parklot_info' and board_code='image' and board_idx='".$parklot_idx."'";
	$query_file = mysqli_query($gconnet,$sql_file);
	$cnt_file = mysqli_num_rows($query_file);
	
	if($cnt_file < 8){
		$cnt_file = 8;
	}

	for($file_i=0; $file_i<$cnt_file; $file_i++){ // 설정된 갯수만큼 루프 시작
		
		$file_idx = trim(sqlfilter($_REQUEST['file_idx_'.$file_i])); // 기존 첨부파일 DB PK 값.
		$file_old_name = trim(sqlfilter($_REQUEST['file_old_name_'.$file_i])); // 원본 서버파일 이름
		$file_old_org = trim(sqlfilter($_REQUEST['file_old_org_'.$file_i]));	// 원본 오리지널 파일 이름
		$del_org = trim(sqlfilter($_REQUEST['del_org_'.$file_i]));	// 원본 파일 삭제여부

		if ($_FILES['file_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
			if($file_old_name){
				unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
				unlink($_P_DIR_FILE2.$file_old_name); // 섬네일 삭제
			}
			$file_o = $_FILES['file_'.$file_i]['name']; 
			$i_width = "280";
			$i_height = "184";
			$file_c = uploadFileThumb_1($_FILES, "file_".$file_i, $_FILES['file_'.$file_i], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$watermark_sect);			
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
			if ($_FILES['file_'.$file_i]['size']>0){ // 업로드 파일이 있으면 인서트 
				$query_file = " insert into board_file set "; 
				$query_file .= " board_tbname = '".$board_tbname."', ";
				$query_file .= " board_code = '".$board_code."', ";
				$query_file .= " board_idx = '".$parklot_idx."', ";
				$query_file .= " member_idx = '".$member_idx."', ";
				$query_file .= " file_org = '".$file_o."', ";
				$query_file .= " file_chg = '".$file_c."' ";
				$result_file = mysqli_query($gconnet,$query_file);
			} 
		} // 기존에 첨부파일 DB 에 있었는지 없었는지 모두 종료 
	} // 설정된 갯수만큼 루프 종료
	################# 첨부파일 업로드 종료 #######################

	################ 공유시간 설정 시작 #########################
	$sql_file = "select * from parklot_public_time where 1 and is_del='N' parklot_idx='".$parklot_idx."'";
	$query_file = mysqli_query($gconnet,$sql_file);
	$cnt_file = mysqli_num_rows($query_file);

	if($cnt_file < 7){
		$cnt_file = 7;
	}

	for($file_i=0; $file_i<$cnt_file; $file_i++){
		$file_k = $file_i+1;

		$idx = trim(sqlfilter($_REQUEST['time_idx_'.$file_k]));
		
		$week_order = $file_k;
		$pub_time_s_h = trim(sqlfilter($_REQUEST['pub_time_s_h_'.$file_k]));
		$pub_time_s_m = trim(sqlfilter($_REQUEST['pub_time_s_m_'.$file_k])); 
		$pub_time_s = $pub_time_s_h.":".$pub_time_s_m;
		$pub_time_e_h = trim(sqlfilter($_REQUEST['pub_time_e_h_'.$file_k]));
		$pub_time_e_m = trim(sqlfilter($_REQUEST['pub_time_e_m_'.$file_k])); 
		$pub_time_e = $pub_time_e_h.":".$pub_time_e_m;
		$pub_time_yn = trim(sqlfilter($_REQUEST['pub_time_yn_'.$file_k])); 
		if(!$pub_time_yn){
			$pub_time_yn = "N";
		}
			
		if($idx){
			if($del_yn == "Y"){
				$query_sub = "update parklot_public_time set is_del='Y' where 1 and idx='".$idx."'"; 
				$result_sub = mysqli_query($gconnet,$query_sub);
			} else {
				$query_sub = "update parklot_public_time set"; 
				$query_sub .= " pub_time_s = '".$pub_time_s."', ";
				$query_sub .= " pub_time_e = '".$pub_time_e."', ";
				$query_sub .= " pub_time_yn = '".$pub_time_yn."', ";
				$query_sub .= " mdate = now() ";
				$query_sub .= " where 1 and idx='".$idx."'";
				$result_sub = mysqli_query($gconnet,$query_sub);
			}
		} else {
			$query_sub = "insert into parklot_public_time set"; 
			$query_sub .= " member_idx = '".$member_idx."', ";
			$query_sub .= " parklot_idx = '".$parklot_idx."', ";
			$query_sub .= " week_order = '".$week_order."', ";
			$query_sub .= " pub_time_s = '".$pub_time_s."', ";
			$query_sub .= " pub_time_e = '".$pub_time_e."', ";
			$query_sub .= " pub_time_yn = '".$pub_time_yn."', ";
			$query_sub .= " wdate = now(), ";
			$query_sub .= " mdate = now() ";
			$result_sub = mysqli_query($gconnet,$query_sub);
		}

	}

	//exit;

	################ 공유시간 설정 종료 #########################
	
	if($result){
		error_frame_go("등록이 정상적으로 완료 되었습니다.","parklot_view.php?idx=".$parklot_idx."&bmenu=".$bmenu."&smenu=".$smenu."");
	}else{
		error_frame("에러 발생");
	}
?>
