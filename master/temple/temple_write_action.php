<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$bbs_code = "temple_info";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";
	
	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
	$attach_count_1 = trim(sqlfilter($_REQUEST['attach_count_1']));
	$temple_hash_id = trim($_REQUEST['temple_hash_id']);
	
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$temple_layout = trim(sqlfilter($_REQUEST['temple_layout']));
	$temple_title = trim(sqlfilter($_REQUEST['temple_title']));
	$addr1 = trim(sqlfilter($_REQUEST['member_address']));
	$addr2 = trim(sqlfilter($_REQUEST['member_address2']));
	$temple_url = trim(sqlfilter($_REQUEST['temple_url']));

	$sql_pre1 = "select idx from temple_info where 1 and temple_url = '".$temple_url."'"; 
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
			
	$max_query = "select max(align) as max from temple_info where 1 ";
	$max_result = mysqli_query($gconnet,$max_query);
	$max_row = mysqli_fetch_array($max_result);
	if ($max_row['max']){
		$align = $max_row['max']+1;
	} else{
		$align = 1;
	}

	$query = " insert into temple_info set ";
	$query .= " member_idx = '".$member_idx."', ";
	$query .= " temple_layout = '".$temple_layout."', ";
	$query .= " temple_title = '".$temple_title."', ";
	$query .= " addr1 = '".$addr1."', ";
	$query .= " addr2 = '".$addr2."', ";
	$query .= " temple_url = '".$temple_url."', ";
	$query .= " map_x = '".$map_x."', ";
	$query .= " map_y = '".$map_y."', ";
	$query .= " align = '".$align."', ";
	$query .= " admin_idx = '".$_SESSION['admin_coinc_idx']."', "; // 등록한 관리자 pk
	$query .= " admin_name = '".$_SESSION['admin_coinc_name']."', "; // 등록한 관리자 pk
	$query .= " wdate = now() ";
	$result = mysqli_query($gconnet,$query);

	$sql_pre2 = "select idx from temple_info where 1 order by idx desc limit 0,1"; 
	$result_pre2  = mysqli_query($gconnet,$sql_pre2);
	$mem_row2 = mysqli_fetch_array($result_pre2);
	$temple_info_idx = $mem_row2[idx]; 

	##### 템플 최초생성시 사찰소개 게시글 생성 #####

	$max_query2 = "select max(ref) as max2 from board_content";
	$max_result2 = mysqli_query($gconnet,$max_query2);
	$max_row2 = mysqli_fetch_array($max_result2);
	if ($max_row2['max2']){
		$max2 = $max_row2['max2']+1;
	} else{
		$max2 = 1;
	}

	//$content = "<h1 style=\'margin: 0px; padding: 0px; border: 0px none; font-size: 2rem; font-weight: 500; color: rgb(54, 54, 54); text-align: center; font-family: &quot;Noto Sans KR&quot;, sans-serif; background-color: rgb(248, 248, 248);\'>사찰 소개</h1><span style=\'margin: 15px 0px 0px; padding: 0px; border: 0px none; display: inline-block; width: 2px; height: 48px; background-color: rgb(255, 159, 1); color: rgb(0, 0, 0); font-family: &quot;Noto Sans KR&quot;, sans-serif; font-size: medium; text-align: center;\'></span><span style=\'margin: 15px 0px 0px; padding: 0px; border: 0px none; display: inline-block; width: 2px; height: 48px; background-color: rgb(255, 159, 1); color: rgb(0, 0, 0); font-family: &quot;Noto Sans KR&quot;, sans-serif; font-size: medium; text-align: center;\'></span><h2 style=\'margin: -6px 0px 0px; padding: 0px; border: 0px none; font-size: 1.313rem; color: rgb(255, 255, 255); font-weight: 500; background-color: rgb(255, 159, 1); height: 55px; line-height: 55px; border-radius: 30px; box-shadow: rgba(0, 0, 0, 0.3) 0px 5px 8px; font-family: &quot;Noto Sans KR&quot;, sans-serif; text-align: center;\'>사찰 소개</h2><br><br><br><br>이곳에 소개내용을 입력해주세요";


	$ip = $_SERVER['REMOTE_ADDR'];	


	$query2 = " insert into board_content set ";
	$query2 .= " member_idx = '".$member_idx."', ";
	$query2 .= " view_idx = '".$member_idx."', ";
	$query2 .= " user_id = '관리자', ";
	$query2 .= " view_id = '관리자', ";
	$query2 .= " bbs_code = 'temple1', ";
	$query2 .= " bbs_sect = '".$temple_info_idx."', ";
	$query2 .= " ref = '".$max2."', ";
	$query2 .= " subject = '사찰 소개', ";
	$query2 .= " writer = '관리자', ";
	//$query2 .= " content = '".$content."', ";
	$query2 .= " ip = '".$ip."', ";
	$query2 .= " write_time = now(), ";
	$query2 .= " is_html = 'Y', "; 
	$query2 .= " is_del = 'N', "; 
	$query2 .= " auth_url = 'Y' "; 
	$result2 = mysqli_query($gconnet,$query2);

		
	##### 섬네일 업로드 시작 ####
	$board_tbname = "temple_info";
	$board_code = "photo";
	for($file_i=0; $file_i<1; $file_i++){ 
		if ($_FILES['photo_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
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

			$query_file = " insert into board_file set "; 
			$query_file .= " board_tbname = '".$board_tbname."', ";
			$query_file .= " board_code = '".$board_code."', ";
			$query_file .= " board_idx = '".$temple_info_idx."', ";
			$query_file .= " file_org = '".$file_o."', ";
			$query_file .= " file_chg = '".$file_c."' ";
			$result_file = mysqli_query($gconnet,$query_file);
		} // 파일이 있다면 업로드한다 종료
	}
	##### 섬네일 업로드 종료 ####

	##### 로고 업로드 시작 ####
	$board_tbname = "temple_info";
	$board_code = "logo";
	for($file_i=0; $file_i<1; $file_i++){ 
		if ($_FILES['logphoto_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
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

			$query_file = " insert into board_file set "; 
			$query_file .= " board_tbname = '".$board_tbname."', ";
			$query_file .= " board_code = '".$board_code."', ";
			$query_file .= " board_idx = '".$temple_info_idx."', ";
			$query_file .= " file_org = '".$file_o."', ";
			$query_file .= " file_chg = '".$file_c."' ";
			$result_file = mysqli_query($gconnet,$query_file);
		} // 파일이 있다면 업로드한다 종료
	}
	##### 로고 업로드 종료 ####

	##### 배경 업로드 시작 ####
	$board_tbname = "temple_info";
	$board_code = "sphoto";
	for($file_i=0; $file_i<1; $file_i++){ 
		if ($_FILES['addphoto_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
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

			$query_file = " insert into board_file set "; 
			$query_file .= " board_tbname = '".$board_tbname."', ";
			$query_file .= " board_code = '".$board_code."', ";
			$query_file .= " board_idx = '".$temple_info_idx."', ";
			$query_file .= " file_org = '".$file_o."', ";
			$query_file .= " file_chg = '".$file_c."' ";
			$result_file = mysqli_query($gconnet,$query_file);
		} // 파일이 있다면 업로드한다 종료
	}
	##### 배경 업로드 종료 ####

	######### 대표자/담당자 입력 시작 ###########
	for($catei=0; $catei<$attach_count_1; $catei++){
		$catek = $catei;
		$cate_type = "mem";
		$tag_value_1 = trim(sqlfilter($_REQUEST['tag_value_1_'.$catek.'']));
		$tag_value_2 = trim(sqlfilter($_REQUEST['tag_value_2_'.$catek.'']));
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
			$query_cat .= " tag_value_2 = '".$tag_value_2."', ";
			$query_cat .= " align = '".$align."', ";
			$query_cat .= " wdate = now() ";
			//echo $query_cat."<br>";
			$result_cat = mysqli_query($gconnet,$query_cat);
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
	
	//exit;
	if($result){
		error_frame_go("정상적으로 등록 되었습니다.","temple_list.php?bmenu=".$bmenu."&smenu=".$smenu."");
	} else {
		error_frame("오류가 발생했습니다.");
	}
?>
