<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
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
		
	$exp_align_sql = "select idx from exp_info where 1 ";
	$exp_align_query = mysqli_query($gconnet,$exp_align_sql);
	$exp_align_num = mysqli_num_rows($exp_align_query);
	$exp_align = $exp_align_num+1;
		
	$bbs = "expinfo";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE.$bbs."/";

	################ 사진 이미지 업로드 ##############
	if ($_FILES['file1']['size']>0){
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
	}

	if ($_FILES['file2']['size']>0){
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
	}

	if ($_FILES['file3']['size']>0){
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
	}

	if ($_FILES['file4']['size']>0){
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
	}

	if ($_FILES['file5']['size']>0){
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
	}
	
	$exp_mem_sect = "Y"; // 관리자 입력여부.
	$regist_admin_id = $_SESSION['admin_coinc_id']; 
	
	$query = " insert into exp_info set "; 
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
	$query .= " align = '".$exp_align."', ";
	$query .= " exp_mem_sect = '".$exp_mem_sect."', ";
	$query .= " regist_admin_id = '".$regist_admin_id."', ";
	$query .= " wdate = now() ";
	$result = mysqli_query($gconnet,$query);

	//echo $query;

	$exp_idx = mysqli_insert_id();

	if($exp_idx == 0){
		$exp_idx_sql = "select idx from exp_info where 1 order by idx desc limit 0,1";
		$exp_idx_query = mysqli_query($gconnet,$exp_idx_sql);
		$exp_idx_row = mysqli_fetch_array($exp_idx_query);
		$exp_idx = $exp_idx_row['idx'];
	}

	if($result){
	?>
	<script type="text/javascript">
	<!--
	alert('등록이 정상적으로 완료 되었습니다.');
	parent.location.href =  "exp_view.php?idx=<?=$exp_idx?>&bmenu=4&smenu=1";
	//-->
	</script>
	<?}else{?>
	<script type="text/javascript">
	<!--
	alert('등록중 오류가 발생했습니다.');
	//-->
	</script>
	<?}?>
