<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	
	$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$exp_title = trim(sqlfilter($_REQUEST['exp_title']));
	$exp_link = trim(sqlfilter($_REQUEST['exp_link']));
	$exp_limit_cnt = trim(sqlfilter($_REQUEST['exp_limit_cnt']));
	$text_color = trim(sqlfilter($_REQUEST['text_color']));
	$s_date = trim(sqlfilter($_REQUEST['s_date']));
	$start_hour = trim(sqlfilter($_REQUEST['start_hour']));
	$start_minute = trim(sqlfilter($_REQUEST['start_minute']));
	$s_time = $start_hour.":".$start_minute;
	$e_date = trim(sqlfilter($_REQUEST['e_date']));
	$end_hour = trim(sqlfilter($_REQUEST['end_hour']));
	$end_minute = trim(sqlfilter($_REQUEST['end_minute']));
	$e_time = $end_hour.":".$end_minute;
	$set_click_cnt = trim(sqlfilter($_REQUEST['set_click_cnt']));

	$time_yn = trim(sqlfilter($_REQUEST['time_yn']));
	$vs_hour = trim(sqlfilter($_REQUEST['vs_hour']));
	$vs_min = trim(sqlfilter($_REQUEST['vs_min']));
	$view_stime = $vs_hour.$vs_min;
	$ve_hour = trim(sqlfilter($_REQUEST['ve_hour']));
	$ve_min = trim(sqlfilter($_REQUEST['ve_min']));
	$view_etime = $ve_hour.$ve_min;

	$exp_content = trim(sqlfilter($_REQUEST['exp_content']));
	$exp_money = trim(sqlfilter($_REQUEST['exp_money']));
	$exp_coin = trim(sqlfilter($_REQUEST['exp_coin']));
	$exp_d_money =  trim(sqlfilter($_REQUEST['exp_d_money']));
	$exp_shop_link = trim(sqlfilter($_REQUEST['exp_shop_link']));

	$file_txt = trim(sqlfilter($_REQUEST['file_txt']));
	$file_txt2 = trim(sqlfilter($_REQUEST['file_txt2']));
	$file_txt3 = trim(sqlfilter($_REQUEST['file_txt3']));
	$file_txt4 = trim(sqlfilter($_REQUEST['file_txt4']));
	$file_txt5 = trim(sqlfilter($_REQUEST['file_txt5']));

	$file_txt_a = trim(sqlfilter($_REQUEST['file_txt_a']));
	$file_txt2_a = trim(sqlfilter($_REQUEST['file_txt2_a']));
	$file_txt3_a = trim(sqlfilter($_REQUEST['file_txt3_a']));
	$file_txt4_a = trim(sqlfilter($_REQUEST['file_txt4_a']));
	$file_txt5_a = trim(sqlfilter($_REQUEST['file_txt5_a']));

	$file_old_name1 = trim(sqlfilter($_POST['file_old_name1']));		//file_old_name1
	$file_old_org1 = trim(sqlfilter($_POST['file_old_org1']));			//file_old_org1
	$del_org1 = $_POST['del_org1'];											//del_org

	$image1_name_arr = explode("-",$file_old_name1);
	$image1_type_arr = explode(".",$image1_name_arr[1]);
	$file_old_water1 = $image1_type_arr[0]."_marke_".$image1_name_arr[0].".".$image1_type_arr[1];
	
	$file_old_name2 = trim(sqlfilter($_POST['file_old_name2']));		//file_old_name1
	$file_old_org2 = trim(sqlfilter($_POST['file_old_org2']));			//file_old_org1
	$del_org2 = $_POST['del_org2'];											//del_org

	$image2_name_arr = explode("-",$file_old_name2);
	$image2_type_arr = explode(".",$image2_name_arr[1]);
	$file_old_water2 = $image2_type_arr[0]."_marke_".$image2_name_arr[0].".".$image2_type_arr[1];
	
	$file_old_name3 = trim(sqlfilter($_POST['file_old_name3']));		//file_old_name1
	$file_old_org3 = trim(sqlfilter($_POST['file_old_org3']));			//file_old_org1
	$del_org3 = $_POST['del_org3'];											//del_org

	$image3_name_arr = explode("-",$file_old_name3);
	$image3_type_arr = explode(".",$image3_name_arr[1]);
	$file_old_water3 = $image3_type_arr[0]."_marke_".$image3_name_arr[0].".".$image3_type_arr[1];
	
	$file_old_name4 = trim(sqlfilter($_POST['file_old_name4']));		//file_old_name1
	$file_old_org4 = trim(sqlfilter($_POST['file_old_org4']));			//file_old_org1
	$del_org4 = $_POST['del_org4'];											//del_org

	$image4_name_arr = explode("-",$file_old_name4);
	$image4_type_arr = explode(".",$image4_name_arr[1]);
	$file_old_water4 = $image4_type_arr[0]."_marke_".$image4_name_arr[0].".".$image4_type_arr[1];
	
	$file_old_name5 = trim(sqlfilter($_POST['file_old_name5']));		//file_old_name1
	$file_old_org5 = trim(sqlfilter($_POST['file_old_org5']));			//file_old_org1
	$del_org5 = $_POST['del_org5'];											//del_org

	$image5_name_arr = explode("-",$file_old_name5);
	$image5_type_arr = explode(".",$image5_name_arr[1]);
	$file_old_water5 = $image5_type_arr[0]."_marke_".$image5_name_arr[0].".".$image5_type_arr[1];
	
	$sql_pre2 = "select member_idx from exp_info where 1 and idx = '".$idx."' "; 
	$result_pre2  = mysqli_query($gconnet,$sql_pre2);
	$row_pre2 = mysqli_fetch_array($result_pre2);

	if($row_pre2['member_idx'] != $_SESSION['manage_coinc_idx']) {
		//error_frame("직접 등록하신 체험만 수정하실 수 있습니다.");
	}
	
	$bbs = "expinfo";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	$_P_DIR_FILE2 = $_P_DIR_FILE."img_thumb/";
	//$_P_DIR_FILE3 = $_P_DIR_FILE."img_thumb2/";

	if ($_FILES['file1']['size']>0){

		if($file_old_name1){
		unlink($_P_DIR_FILE.$file_old_name1); // 원본파일 삭제
		unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제
		unlink($_P_DIR_FILE3.$file_old_name1); // 원본 중간 섬네일 파일 삭제

		//unlink($_P_DIR_FILE.$file_old_water1); // 원본파일 삭제
		//unlink($_P_DIR_FILE2.$file_old_water1); // 원본 작은 섬네일 파일 삭제
		//unlink($_P_DIR_FILE3.$file_old_water1); // 원본 중간 섬네일 파일 삭제
		}

	$file_o = $_FILES['file1']['name']; 
	$i_width = "567"; // 탑 배너
	$i_height = "417";
	$i_width2 = "330"; // 슬라이딩 띠 배너
	$i_height2 = "250";
	$i_width3 = "256"; // 정사각형 목록
	$i_height3 = "256";
	//$watermark_sect = "imgw";
	$watermark_sect = "";
	$file_c = uploadFileThumb_1($_FILES, "file1", $_FILES['file1'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$i_width3,$i_height3,$watermark_sect);
	} else {
		
		if($file_old_name1 && $file_old_org1){
			$file_c = $file_old_name1;
			$file_o = $file_old_org1;
		}

		if($del_org1 == "Y"){
			unlink($_P_DIR_FILE.$file_old_name1); // 원본파일 삭제
			unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제
			unlink($_P_DIR_FILE3.$file_old_name1); // 원본 중간 섬네일 파일 삭제

			//unlink($_P_DIR_FILE.$file_old_water1); // 원본파일 삭제
			//unlink($_P_DIR_FILE2.$file_old_water1); // 원본 작은 섬네일 파일 삭제
			//unlink($_P_DIR_FILE3.$file_old_water1); // 원본 중간 섬네일 파일 삭제
			$file_o = "";
			$file_c = "";
		}

	}
	

	if ($_FILES['file2']['size']>0){

		if($file_old_name2){
		unlink($_P_DIR_FILE.$file_old_name2); // 원본파일 삭제
		unlink($_P_DIR_FILE2.$file_old_name2); // 원본 작은 섬네일 파일 삭제
		unlink($_P_DIR_FILE3.$file_old_name2); // 원본 중간 섬네일 파일 삭제

		//unlink($_P_DIR_FILE.$file_old_water2); // 원본파일 삭제
		//unlink($_P_DIR_FILE2.$file_old_water2); // 원본 작은 섬네일 파일 삭제
		//unlink($_P_DIR_FILE3.$file_old_water2); // 원본 중간 섬네일 파일 삭제
		}

	$file_o2 = $_FILES['file2']['name']; 
	$i_width = "567"; // 탑 배너
	$i_height = "417";
	$i_width2 = "330"; // 슬라이딩 띠 배너
	$i_height2 = "250";
	$i_width3 = "256"; // 정사각형 목록
	$i_height3 = "256";
	//$watermark_sect = "imgw";
	$watermark_sect = "";
	$file_c2 = uploadFileThumb_1($_FILES, "file2", $_FILES['file2'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$i_width3,$i_height3,$watermark_sect);
	} else {
		
		if($file_old_name2 && $file_old_org2){
			$file_c2 = $file_old_name2;
			$file_o2 = $file_old_org2;
		}

		if($del_org2 == "Y"){
			unlink($_P_DIR_FILE.$file_old_name2); // 원본파일 삭제
			unlink($_P_DIR_FILE2.$file_old_name2); // 원본 작은 섬네일 파일 삭제
			unlink($_P_DIR_FILE3.$file_old_name2); // 원본 중간 섬네일 파일 삭제

			//unlink($_P_DIR_FILE.$file_old_water2); // 원본파일 삭제
			//unlink($_P_DIR_FILE2.$file_old_water2); // 원본 작은 섬네일 파일 삭제
			//unlink($_P_DIR_FILE3.$file_old_water2); // 원본 중간 섬네일 파일 삭제
			$file_o2 = "";
			$file_c2 = "";
		}

	}

	if ($_FILES['file3']['size']>0){

		if($file_old_name3){
		unlink($_P_DIR_FILE.$file_old_name3); // 원본파일 삭제
		unlink($_P_DIR_FILE2.$file_old_name3); // 원본 작은 섬네일 파일 삭제
		unlink($_P_DIR_FILE3.$file_old_name3); // 원본 중간 섬네일 파일 삭제

		//unlink($_P_DIR_FILE.$file_old_water3); // 원본파일 삭제
		//unlink($_P_DIR_FILE2.$file_old_water3); // 원본 작은 섬네일 파일 삭제
		//unlink($_P_DIR_FILE3.$file_old_water3); // 원본 중간 섬네일 파일 삭제
		}

	$file_o3 = $_FILES['file3']['name']; 
	$i_width = "567"; // 탑 배너
	$i_height = "417";
	$i_width2 = "330"; // 슬라이딩 띠 배너
	$i_height2 = "250";
	$i_width3 = "256"; // 정사각형 목록
	$i_height3 = "256";
	//$watermark_sect = "imgw";
	$watermark_sect = "";
	$file_c3 = uploadFileThumb_1($_FILES, "file3", $_FILES['file3'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$i_width3,$i_height3,$watermark_sect);
	} else {
		
		if($file_old_name3 && $file_old_org3){
			$file_c3 = $file_old_name3;
			$file_o3 = $file_old_org3;
		}

		if($del_org3 == "Y"){
			unlink($_P_DIR_FILE.$file_old_name3); // 원본파일 삭제
			unlink($_P_DIR_FILE2.$file_old_name3); // 원본 작은 섬네일 파일 삭제
			unlink($_P_DIR_FILE3.$file_old_name3); // 원본 중간 섬네일 파일 삭제

			//unlink($_P_DIR_FILE.$file_old_water3); // 원본파일 삭제
			//unlink($_P_DIR_FILE2.$file_old_water3); // 원본 작은 섬네일 파일 삭제
			//unlink($_P_DIR_FILE3.$file_old_water3); // 원본 중간 섬네일 파일 삭제
			$file_o3 = "";
			$file_c3 = "";
		}

	}
	
	if ($_FILES['file4']['size']>0){

		if($file_old_name4){
		unlink($_P_DIR_FILE.$file_old_name4); // 원본파일 삭제
		unlink($_P_DIR_FILE2.$file_old_name4); // 원본 작은 섬네일 파일 삭제
		unlink($_P_DIR_FILE3.$file_old_name4); // 원본 중간 섬네일 파일 삭제

		//unlink($_P_DIR_FILE.$file_old_water4); // 원본파일 삭제
		//unlink($_P_DIR_FILE2.$file_old_water4); // 원본 작은 섬네일 파일 삭제
		//unlink($_P_DIR_FILE3.$file_old_water4); // 원본 중간 섬네일 파일 삭제
		}

	$file_o4 = $_FILES['file4']['name']; 
	$i_width = "567"; // 탑 배너
	$i_height = "417";
	$i_width2 = "330"; // 슬라이딩 띠 배너
	$i_height2 = "250";
	$i_width3 = "256"; // 정사각형 목록
	$i_height3 = "256";
	//$watermark_sect = "imgw";
	$watermark_sect = "";
	$file_c4 = uploadFileThumb_1($_FILES, "file4", $_FILES['file4'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$i_width3,$i_height3,$watermark_sect);
	} else {
		
		if($file_old_name4 && $file_old_org4){
			$file_c4 = $file_old_name4;
			$file_o4 = $file_old_org4;
		}

		if($del_org4 == "Y"){
			unlink($_P_DIR_FILE.$file_old_name4); // 원본파일 삭제
			unlink($_P_DIR_FILE2.$file_old_name4); // 원본 작은 섬네일 파일 삭제
			unlink($_P_DIR_FILE3.$file_old_name4); // 원본 중간 섬네일 파일 삭제

			//unlink($_P_DIR_FILE.$file_old_water4); // 원본파일 삭제
			//unlink($_P_DIR_FILE2.$file_old_water4); // 원본 작은 섬네일 파일 삭제
			//unlink($_P_DIR_FILE3.$file_old_water4); // 원본 중간 섬네일 파일 삭제
			$file_o4 = "";
			$file_c4 = "";
		}

	}

	if ($_FILES['file5']['size']>0){

		if($file_old_name5){
		unlink($_P_DIR_FILE.$file_old_name5); // 원본파일 삭제
		unlink($_P_DIR_FILE2.$file_old_name5); // 원본 작은 섬네일 파일 삭제
		unlink($_P_DIR_FILE3.$file_old_name5); // 원본 중간 섬네일 파일 삭제

		//unlink($_P_DIR_FILE.$file_old_water5); // 원본파일 삭제
		//unlink($_P_DIR_FILE2.$file_old_water5); // 원본 작은 섬네일 파일 삭제
		//unlink($_P_DIR_FILE3.$file_old_water5); // 원본 중간 섬네일 파일 삭제
		}

	$file_o5 = $_FILES['file5']['name']; 
	$i_width = "567"; // 탑 배너
	$i_height = "417";
	$i_width2 = "330"; // 슬라이딩 띠 배너
	$i_height2 = "250";
	$i_width3 = "256"; // 정사각형 목록
	$i_height3 = "256";
	//$watermark_sect = "imgw";
	$watermark_sect = "";
	$file_c5 = uploadFileThumb_1($_FILES, "file5", $_FILES['file5'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$i_width3,$i_height3,$watermark_sect);
	} else {
		
		if($file_old_name5 && $file_old_org5){
			$file_c5 = $file_old_name5;
			$file_o5 = $file_old_org5;
		}

		if($del_org5 == "Y"){
			unlink($_P_DIR_FILE.$file_old_name5); // 원본파일 삭제
			unlink($_P_DIR_FILE2.$file_old_name5); // 원본 작은 섬네일 파일 삭제
			unlink($_P_DIR_FILE3.$file_old_name5); // 원본 중간 섬네일 파일 삭제

			//unlink($_P_DIR_FILE.$file_old_water5); // 원본파일 삭제
			//unlink($_P_DIR_FILE2.$file_old_water5); // 원본 작은 섬네일 파일 삭제
			//unlink($_P_DIR_FILE3.$file_old_water5); // 원본 중간 섬네일 파일 삭제
			$file_o5 = "";
			$file_c5 = "";
		}

	}
	
	$query = " update exp_info set "; 
	$query .= " cate_code1 = '".$cate_code1."', ";
	$query .= " member_idx = '".$member_idx."', ";
	$query .= " exp_title = '".$exp_title."', ";
	$query .= " exp_content = '".$exp_content."', ";
	$query .= " exp_link = '".$exp_link."', ";
	$query .= " text_color = '".$text_color."', ";
	$query .= " file_org = '".$file_o."', ";
	$query .= " file_chg = '".$file_c."', ";
	$query .= " file_txt = '".$file_txt."', ";
	$query .= " file_org2 = '".$file_o2."', ";
	$query .= " file_chg2 = '".$file_c2."', ";
	$query .= " file_txt2 = '".$file_txt2."', ";
	$query .= " file_org3 = '".$file_o3."', ";
	$query .= " file_chg3 = '".$file_c3."', ";
	$query .= " file_txt3 = '".$file_txt3."', ";
	$query .= " file_org4 = '".$file_o4."', ";
	$query .= " file_chg4 = '".$file_c4."', ";
	$query .= " file_txt4 = '".$file_txt4."', ";
	$query .= " file_org5 = '".$file_o5."', ";
	$query .= " file_chg5 = '".$file_c5."', ";
	$query .= " file_txt5 = '".$file_txt5."', ";

	$query .= " file_txt_a = '".$file_txt_a."', ";
	$query .= " file_txt2_a = '".$file_txt2_a."', ";
	$query .= " file_txt3_a = '".$file_txt3_a."', ";
	$query .= " file_txt4_a = '".$file_txt4_a."', ";
	$query .= " file_txt5_a = '".$file_txt5_a."', ";

	$query .= " exp_limit_cnt = '".$exp_limit_cnt."', ";
	$query .= " s_date = '".$s_date."', ";
	$query .= " e_date = '".$e_date."', ";
	$query .= " s_time = '".$s_time."', ";
	$query .= " e_time = '".$e_time."', ";
	$query .= " set_click_cnt = '".$set_click_cnt."', ";

	$query .= " time_yn = '".$time_yn."', ";
	$query .= " view_stime = '".$view_stime."', ";
	$query .= " view_etime = '".$view_etime."', ";

	$query .= " exp_money = '".$exp_money."', ";
	$query .= " exp_coin = '".$exp_coin."', ";
	$query .= " exp_d_money = '".$exp_d_money."', ";
	$query .= " exp_shop_link = '".$exp_shop_link."', ";
	$query .= " mdate = now() ";
	//$query .= " where 1 and idx = '".$idx."' and member_idx='".$member_idx."'";
	$query .= " where 1 and idx = '".$idx."'";
	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('수정이 정상적으로 완료 되었습니다.');
	parent.location.href =  "exp_view.php?idx=<?=$idx?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('수정중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
