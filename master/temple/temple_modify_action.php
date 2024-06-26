<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$bbs_code = "temple_info";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";

	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$attach_count_1 = trim(sqlfilter($_REQUEST['attach_count_1']));
	$temple_hash_id = trim($_REQUEST['temple_hash_id']);
	
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$temple_layout = trim(sqlfilter($_REQUEST['temple_layout']));
	$temple_title = trim(sqlfilter($_REQUEST['temple_title']));
	$addr1 = trim(sqlfilter($_REQUEST['member_address']));
	$addr2 = trim(sqlfilter($_REQUEST['member_address2']));
	$temple_url = trim(sqlfilter($_REQUEST['temple_url']));

	$sql_pre1 = "select idx from temple_info where 1 and temple_url = '".$temple_url."' and idx != '".$idx."'"; 
	$result_pre1  = mysqli_query($gconnet,$sql_pre1);
	if(mysqli_num_rows($result_pre1) > 0) {
		error_frame("입력하신 홈페이지는 이미 사용중입니다. 다시 확인하시고 입력해 주세요.");
	}

	/* 미니홈피 폴더 생성 및 파일복사 시작 */
	$mini_home_url = $_SERVER["DOCUMENT_ROOT"]."/mybuddha/temple_home/".$temple_url;
	if(!is_dir($mini_home_url)){
		mkdir($mini_home_url, 0777); 
		chmod($mini_home_url, 0755);
		
		copy($_SERVER["DOCUMENT_ROOT"]."/mybuddha/_minimall/layout".$temple_layout."/index.php",$mini_home_url."/index.php");  

		/*mkdir($mini_home_url."/community", 0777); 
		chmod($mini_home_url."/community", 0755);
		mkdir($mini_home_url."/company", 0777); 
		chmod($mini_home_url."/company", 0755);
		mkdir($mini_home_url."/main", 0777); 
		chmod($mini_home_url."/main", 0755);
		mkdir($mini_home_url."/mall", 0777); 
		chmod($mini_home_url."/mall", 0755);
		mkdir($mini_home_url."/member", 0777); 
		chmod($mini_home_url."/member", 0755);
		mkdir($mini_home_url."/mypage", 0777); 
		chmod($mini_home_url."/mypage", 0755);
		mkdir($mini_home_url."/news", 0777); 
		chmod($mini_home_url."/news", 0755);

		recursive_copy($_SERVER["DOCUMENT_ROOT"]."/mybuddha/_minimall/layout".$temple_layout."/community",$mini_home_url."/community");
		recursive_copy($_SERVER["DOCUMENT_ROOT"]."/mybuddha/_minimall/layout".$temple_layout."/company",$mini_home_url."/company");
		recursive_copy($_SERVER["DOCUMENT_ROOT"]."/mybuddha/_minimall/layout".$temple_layout."/main",$mini_home_url."/main");
		recursive_copy($_SERVER["DOCUMENT_ROOT"]."/mybuddha/_minimall/layout".$temple_layout."/mall",$mini_home_url."/mall");
		recursive_copy($_SERVER["DOCUMENT_ROOT"]."/mybuddha/_minimall/layout".$temple_layout."/member",$mini_home_url."/member");
		recursive_copy($_SERVER["DOCUMENT_ROOT"]."/mybuddha/_minimall/layout".$temple_layout."/mypage",$mini_home_url."/mypage");
		recursive_copy($_SERVER["DOCUMENT_ROOT"]."/mybuddha/_minimall/layout".$temple_layout."/news",$mini_home_url."/news");*/
	} else {
		copy($_SERVER["DOCUMENT_ROOT"]."/mybuddha/_minimall/layout".$temple_layout."/index.php",$mini_home_url."/index.php");  
	}
	/* 미니홈피 폴더 생성 및 파일복사 종료 */

	$xml_url ="https://maps.googleapis.com/maps/api/geocode/xml?address=".urlencode($addr1)."&key=AIzaSyAW_TeBhoUnzqIy-WBKLc_71qGAZUPh_T0";
	//echo "xml url test = ".$xml_url."<br>";

	include_once $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/snoopy/Snoopy.class.php"; 
	$snoopy = new snoopy;
	$snoopy->fetch($xml_url);
	$xml = simplexml_load_string($snoopy->results) or die ("Error: Cannot create object 3"); 

	$map_x = $xml->result->geometry->location->lat;
	$map_y = $xml->result->geometry->location->lng;
	
	$query = " update temple_info set "; 
	$query .= " member_idx = '".$member_idx."', ";
	$query .= " temple_layout = '".$temple_layout."', ";
	$query .= " temple_title = '".$temple_title."', ";
	$query .= " addr1 = '".$addr1."', ";
	$query .= " addr2 = '".$addr2."', ";
	$query .= " temple_url = '".$temple_url."', ";
	$query .= " map_x = '".$map_x."', ";
	$query .= " map_y = '".$map_y."', ";
	$query .= " mdate = now() ";
	$query .= " where 1 and idx='".$idx."' ";
	$result = mysqli_query($gconnet,$query);

	$temple_info_idx = $idx; 

	##### 템플 최초생성시 사찰소개 게시글 수정 #####

	$query2 = " update board_content set "; 
	$query2 .= " member_idx = '".$member_idx."', ";
	$query2 .= " view_idx = '".$member_idx."', ";
	$query2 .= " modify_time = now() ";
	$query2 .= " where 1 and bbs_sect='".$temple_info_idx."' ";
	$query2 .= " and auth_url ='Y' ";
	$query2 .= " and bbs_code ='temple1' ";
	$result2 = mysqli_query($gconnet,$query2);
		
	##### 섬네일 업로드 시작 ####
	$board_tbname = "temple_info";
	$board_code = "photo";
	
	$sql_file = "select idx from board_file where 1=1 and board_tbname='".$board_tbname."' and board_code = '".$board_code."' and board_idx='".$temple_info_idx."' order by idx asc";
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
				unlink($tmp_FILE."img_thumb/".$file_old_name); // 섬네일 파일 삭제
				//unlink($tmp_FILE."img_thumb2/".$file_old_name); // 섬네일 파일 삭제
				//unlink($tmp_FILE."img_thumb3/".$file_old_name); // 섬네일 파일 삭제
			}
			$file_o = $_FILES['photo_'.$file_i]['name']; 
			$i_width = "310";
			$i_height = "180";
			$i_width2 = "290";
			$i_height2 = "380";
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
					unlink($tmp_FILE."img_thumb/".$file_old_name); // 섬네일 파일 삭제
					//unlink($tmp_FILE."img_thumb2/".$file_old_name); // 섬네일 파일 삭제
					//unlink($tmp_FILE."img_thumb3/".$file_old_name); // 섬네일 파일 삭제
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
			$board_idx = $temple_info_idx;
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
	##### 섬네일 업로드 종료 ####

	##### 로고 업로드 시작 ####
	$board_tbname = "temple_info";
	$board_code = "logo";
	
	$sql_file = "select idx from board_file where 1=1 and board_tbname='".$board_tbname."' and board_code = '".$board_code."' and board_idx='".$temple_info_idx."' order by idx asc";
	$query_file = mysqli_query($gconnet,$sql_file);
	$cnt_file = mysqli_num_rows($query_file);
	if($cnt_file < 1){
		$cnt_file = 1;
	}
	for($file_i=0; $file_i<$cnt_file; $file_i++){ // 설정된 갯수만큼 루프 시작
		$file_idx = trim(sqlfilter($_REQUEST['logopfile_idx_'.$file_i])); // 기존 첨부파일 DB PK 값.
		$file_old_name = trim(sqlfilter($_REQUEST['logopfile_old_name_'.$file_i])); // 원본 서버파일 이름
		$file_old_org = trim(sqlfilter($_REQUEST['logopfile_old_org_'.$file_i]));	// 원본 오리지널 파일 이름
		$del_org = trim(sqlfilter($_REQUEST['logopdel_org_'.$file_i]));	// 원본 파일 삭제여부

		if ($_FILES['logphoto_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
			if($file_old_name){
				unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
				unlink($tmp_FILE."img_thumb/".$file_old_name); // 섬네일 파일 삭제
				//unlink($tmp_FILE."img_thumb2/".$file_old_name); // 섬네일 파일 삭제
				//unlink($tmp_FILE."img_thumb3/".$file_old_name); // 섬네일 파일 삭제
			}
			$file_o = $_FILES['logphoto_'.$file_i]['name']; 
			$i_width = "100";
			$i_height = "77";
			$i_width2 = "";
			$i_height2 = "";
			$i_width3 = "";
			$i_height3 = "";
			//$watermark_sect = "imgw";
			$watermark_sect = "";
			$file_c = uploadFileThumb_1($_FILES, "logphoto_".$file_i, $_FILES['logphoto_'.$file_i], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$i_width3,$i_height3,$watermark_sect);
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
					//unlink($tmp_FILE."img_thumb2/".$file_old_name); // 섬네일 파일 삭제
					//unlink($tmp_FILE."img_thumb3/".$file_old_name); // 섬네일 파일 삭제
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
			$board_idx = $temple_info_idx;
			//echo $_FILES['file_'.$file_i]['size']."<br>";
			if ($_FILES['logphoto_'.$file_i]['size']>0){ // 업로드 파일이 있으면 인서트 
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
	##### 로고 업로드 종료 ####

	##### 배경 업로드 시작 ####
	$board_tbname = "temple_info";
	$board_code = "sphoto";
	
	$sql_file = "select idx from board_file where 1=1 and board_tbname='".$board_tbname."' and board_code = '".$board_code."' and board_idx='".$temple_info_idx."' order by idx asc";
	$query_file = mysqli_query($gconnet,$sql_file);
	$cnt_file = mysqli_num_rows($query_file);
	if($cnt_file < 1){
		$cnt_file = 1;
	}
	for($file_i=0; $file_i<$cnt_file; $file_i++){ // 설정된 갯수만큼 루프 시작
		$file_idx = trim(sqlfilter($_REQUEST['sphotopfile_idx_'.$file_i])); // 기존 첨부파일 DB PK 값.
		$file_old_name = trim(sqlfilter($_REQUEST['sphotopfile_old_name_'.$file_i])); // 원본 서버파일 이름
		$file_old_org = trim(sqlfilter($_REQUEST['sphotopfile_old_org_'.$file_i]));	// 원본 오리지널 파일 이름
		$del_org = trim(sqlfilter($_REQUEST['sphotopdel_org_'.$file_i]));	// 원본 파일 삭제여부

		if ($_FILES['addphoto_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
			if($file_old_name){
				unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
				unlink($tmp_FILE."img_thumb/".$file_old_name); // 섬네일 파일 삭제
				//unlink($tmp_FILE."img_thumb2/".$file_old_name); // 섬네일 파일 삭제
				//unlink($tmp_FILE."img_thumb3/".$file_old_name); // 섬네일 파일 삭제
			}
			$file_o = $_FILES['addphoto_'.$file_i]['name']; 
			$i_width = "1920";
			$i_height = "300";
			$i_width2 = "";
			$i_height2 = "";
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
					unlink($tmp_FILE."img_thumb/".$file_old_name); // 섬네일 파일 삭제
					//unlink($tmp_FILE."img_thumb2/".$file_old_name); // 섬네일 파일 삭제
					//unlink($tmp_FILE."img_thumb3/".$file_old_name); // 섬네일 파일 삭제
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
			$board_idx = $temple_info_idx;
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
	##### 배경 업로드 종료 ####

	########## 사찰위치 삭제 시작 ###########
	$tmp_sql = "delete from temple_info_add where 1 and temple_info_idx = '".$temple_info_idx."' and cate_type in ('mem','hast')";
	$tmp_query = mysqli_query($gconnet,$tmp_sql);
	########## 사찰위치 삭제 종료 ###########

	######### 대표자/담당자 입력 시작 ###########
	for($catei=0; $catei<$attach_count_1; $catei++){
		$catek = $catei;
		$cate_type = "mem";
		$tag_value_1 = trim(sqlfilter($_REQUEST['tag_value_1_'.$catek.'']));
		$tag_value_2 = trim(sqlfilter($_REQUEST['tag_value_2_'.$catek.'']));
		$chk_delete_dam = trim(sqlfilter($_REQUEST['chk_delete_dam_'.$catek.'']));
		if($tag_value_1){
			$max_query = "select max(align) as max from temple_info_add where 1 ";
			$max_result = mysqli_query($gconnet,$max_query);
			$max_row = mysqli_fetch_array($max_result);
			if ($max_row['max']){
				$align = $max_row['max']+1;
			} else{
				$align = 1;
			}
			
			if($chk_delete_dam == "Y"){ // '삭제' 로 체크된 것 
			} else { // '삭제' 로 체크 되지 않은것 시작  
				$query_cat = " insert into temple_info_add set "; 
				$query_cat .= " member_idx = '".$member_idx."', ";
				$query_cat .= " temple_info_idx = '".$temple_info_idx."', ";
				$query_cat .= " cate_type = '".$cate_type."', ";
				$query_cat .= " tag_value_1 = '".$tag_value_1."', ";
				$query_cat .= " tag_value_2 = '".$tag_value_2."', ";
				$query_cat .= " align = '".$align."', ";
				$query_cat .= " wdate = now() ";
				//echo $query_cat."<br>";
				$result_cat = mysqli_query($gconnet,$query_cat);
			} // '삭제' 로 체크 되지 않은것 종료
		}
	}

	######### 사찰/키워드 입력 시작 ###########
	$inc_mem_hash_value_arr = explode("||",$temple_hash_id);
	for($catei=0; $catei<sizeof($inc_mem_hash_value_arr); $catei++){
		$catek = $catei;
		$cate_type = "hast";
		$tag_value_1 = trim($inc_mem_hash_value_arr[$catek]);
		if($tag_value_1){
			$max_query = "select max(align) as max from temple_info_add where 1 ";
			$max_result = mysqli_query($gconnet,$max_query);
			$max_row = mysqli_fetch_array($max_result);
			if ($max_row['max']){
				$align = $max_row['max']+1;
			} else{
				$align = 1;
			}

			$query_cat = " insert into temple_info_add set "; 
			$query_cat .= " member_idx = '".$member_idx."', ";
			$query_cat .= " temple_info_idx = '".$temple_info_idx."', ";
			$query_cat .= " cate_type = '".$cate_type."', ";
			$query_cat .= " tag_value_1 = '".$tag_value_1."', ";
			$query_cat .= " align = '".$align."', ";
			$query_cat .= " wdate = now() ";
			//echo $query_cat."<br>";
			$result_cat = mysqli_query($gconnet,$query_cat);
		}
	}

	if($result){
		error_frame_go("정상적으로 수정 되었습니다.","temple_view.php?idx=".$idx."&".$total_param."");
	} else {
		error_frame("오류가 발생했습니다.");
	}
?>
