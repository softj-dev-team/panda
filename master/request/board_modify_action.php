<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
/*if(!$_AUTH_WRITE && !$_AUTH_REPLY){
	error_frame("수정 권한이 없습니다.");
	exit;
}*/

$idx = trim(sqlfilter($_REQUEST['idx']));
$orgin_idx = trim(sqlfilter($_REQUEST['orgin_idx']));
$bbs_code = trim(sqlfilter($_REQUEST['bbs_code']));										//bbs_code
$bbs_sect = trim(sqlfilter($_REQUEST['bbs_sect']));	
$total_param = trim(sqlfilter($_REQUEST['total_param']));

$is_html = $_REQUEST['is_html'];												//is_html
$passwd = sqlfilter($_REQUEST['passwd']);	

$member_idx = trim(sqlfilter($_REQUEST['member_idx']));								//user_id
$view_idx = trim(sqlfilter($_REQUEST['member_idx']));	 //view_id

$subject = trim(sqlfilter($_REQUEST['subject']));								//제목
$forecast_pay = trim(sqlfilter($_REQUEST['forecast_pay']));	
$forecast_period = trim(sqlfilter($_REQUEST['forecast_period']));	
$content = trim(sqlfilter($_REQUEST['ir2']));										//내용

if ($passwd==""){
	$passwd = md5(sqlfilter($_SESSION['admin_coinc_password']));	//비밀번호
} else {
	$passwd = md5($passwd);	//비밀번호
}

if ($writer==""){
	if($_SESSION['admin_coinc_idx']){ // 관리자 로그인 
	$writer = $_SESSION['admin_coinc_name'];	
	} 
}

if($bbs_code == "event" || $bbs_code == "gallery" || $bbs_code == "movie"){
	if($_FILES['file_0']['size']>0){
		$img_type = getExt($_FILES['file_0']['name']);
		//echo $img_type; exit;
		if($img_type == "jpg" || $img_type == "JPG" || $img_type == "gif" || $img_type == "GIF" || $img_type == "png" || $img_type == "PNG" || $img_type == "bmp" || $img_type == "BMP"){
		} else {
			error_frame("이미지 파일만 업로드하실 수 있습니다. ");
			exit;
		}
	}
}
	
	$query = " update board_content set "; 
	$query .= " subject = '".$subject."', ";
	$query .= " forecast_pay = '".$forecast_pay."', ";
	$query .= " forecast_period = '".$forecast_period."', ";
	if ($writer != ""){
		$query .= " writer = '$writer', "; 
	}
	$query .= " content = '".$content."', ";

	$query .= " bbs_sect = '".$bbs_sect."', ";
	$query .= " bbs_tag = '".$bbs_tag."', ";
	$query .= " scrap_ok = '".$scrap_ok."', ";
	
	$query .= " is_html = '".$is_html."', ";
	//$query .= " email = '".$email."', ";
	$query .= " is_secure = '".$is_secure."', ";
	$query .= " is_popup = '".$is_popup."', ";
	$query .= " 1vs1_cate = '".$cate_1vs1."' ";

	//$query .= " 1vs1_cell = '".$cell_1vs1."' ";
	$query .= " where idx = '".$idx."' and bbs_code='".$bbs_code."' ";

	//echo $query;

	$result = mysqli_query($gconnet,$query);

	################# 첨부파일 업로드 시작 #######################
	
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs_code."/";
	$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE.$bbs_code."/";

	$sql_file = "select idx from board_file where 1=1 and board_tbname='board_content' and board_code = '".$bbs_code."' and board_idx='".$idx."' ";
	$query_file = mysqli_query($gconnet,$sql_file);
	$cnt_file = mysqli_num_rows($query_file);

	if($cnt_file < $_include_board_file_cnt){
		$cnt_file = $_include_board_file_cnt;
	}

	for($file_i=0; $file_i<$cnt_file; $file_i++){ // 설정된 갯수만큼 루프 시작
		
		$file_idx = trim(sqlfilter($_REQUEST['file_idx_'.$file_i])); // 기존 첨부파일 DB PK 값.
		$file_old_name = trim(sqlfilter($_REQUEST['file_old_name_'.$file_i])); // 원본 서버파일 이름
		$file_old_org = trim(sqlfilter($_REQUEST['file_old_org_'.$file_i]));	// 원본 오리지널 파일 이름
		$del_org = trim(sqlfilter($_REQUEST['del_org_'.$file_i]));	// 원본 파일 삭제여부

		if ($_FILES['file_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
						
			if($file_old_name){
				unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
			}

			if($bbs_code == "request"){
				$file_o = $_FILES['file_'.$file_i]['name']; 
				$i_width = "300";
				$i_height = "235";
				$file_c = uploadFileThumb_1($_FILES, "file_".$file_i, $_FILES['file_'.$file_i], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$watermark_sect);
			} else {
				$file_o = $_FILES['file_'.$file_i]['name']; 
				$file_c = uploadFile($_FILES, "file_".$file_i, $_FILES['file_'.$file_i], $_P_DIR_FILE); // 파일 업로드후 변형된 파일이름 리턴.
			}
			
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

			$board_tbname = "board_content";
			$board_code = $bbs_code;
			$board_idx = $idx;

			//echo $_FILES['file_'.$file_i]['size']."<br>";
			
			if ($_FILES['file_'.$file_i]['size']>0){ // 업로드 파일이 있으면 인서트 
			
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

	################# 첨부파일 업로드 종료 #######################
	
	$board_idx = $idx;

	########## 키워드 삭제 시작 ###########
	$tmp_sql = "delete from board_info_add where 1 and board_idx = '".$board_idx."' and cate_type in ('cate')";
	$tmp_query = mysqli_query($gconnet,$tmp_sql);
	########## 키워드 삭제 종료 ###########

	######### 키워드 입력 시작 ###########
	$board_cate_query = "select cate_code1,cate_name1 from common_code where 1 and type='request' and cate_level = '1' and is_del='N' order by cate_align desc"; 
	//echo $board_cate_query."<br>";
	$board_cate_result = mysqli_query($gconnet,$board_cate_query);
	for ($catei=0; $catei<mysqli_num_rows($board_cate_result); $catei++){
		$catek = $catei+1;
		$cate_type = "cate";
		$tag_value = trim(sqlfilter($_REQUEST['board_cate_'.$catek.'']));
		//echo "tag_value = ".$tag_value."<br>";
		if($tag_value){
			$max_query = "select max(align) as max from board_info_add where 1 ";
			$max_result = mysqli_query($gconnet,$max_query);
			$max_row = mysqli_fetch_array($max_result);
			if ($max_row['max']){
				$align = $max_row['max']+1;
			} else{
				$align = 1;
			}

			$query_cat = " insert into board_info_add set "; 
			$query_cat .= " board_idx = '".$board_idx."', ";
			$query_cat .= " cate_type = '".$cate_type."', ";
			$query_cat .= " tag_value = '".$tag_value."', ";
			$query_cat .= " align = '".$align."', ";
			$query_cat .= " wdate = now() ";
			//echo $query_cat."<br>";
			$result_cat = mysqli_query($gconnet,$query_cat);
		}
	}

	//exit;

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('게시물 수정이 정상적으로 완료 되었습니다.');
	<?if($orgin_idx){?>
		parent.location.href =  "board_view.php?idx=<?=$orgin_idx?>&bbs_code=<?=$bbs_code?>&<?=$total_param?>";
	<?}else{?>
		<?if($bbs_code == "gallery"){?>
			parent.location.href =  "board_list.php?&bbs_code=<?=$bbs_code?>&<?=$total_param?>";
		<?}else{?>
			parent.location.href =  "board_view.php?idx=<?=$idx?>&bbs_code=<?=$bbs_code?>&<?=$total_param?>";
		<?}?>
	<?}?>
	//-->
	</SCRIPT>
	<?}else{
		echo $query;
		?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('게시물 수정중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
