<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
$total_param = trim(sqlfilter($_REQUEST['total_param']));
$idx = trim(sqlfilter($_REQUEST['idx']));

$cate1 = trim(sqlfilter($_REQUEST['cate1']));	
$board_title = trim(sqlfilter($_REQUEST['board_title']));	

$board_info = trim(sqlfilter($_REQUEST['board_info']));	
$board_principle = trim(sqlfilter($_REQUEST['board_principle']));	
$board_master_idx = trim(sqlfilter($_REQUEST['board_master_idx']));		

$del_bmaster = trim(sqlfilter($_REQUEST['del_bmaster']));

if($del_bmaster == "Y"){
	$board_master_idx = 0;
}

$list_auth = trim(sqlfilter($_REQUEST['list_auth']));		
$view_auth = trim(sqlfilter($_REQUEST['view_auth']));		
$write_auth = trim(sqlfilter($_REQUEST['write_auth']));		
$reply_auth = trim(sqlfilter($_REQUEST['reply_auth']));		
$is_comment = trim(sqlfilter($_REQUEST['is_comment']));		
$is_notice = trim(sqlfilter($_REQUEST['is_notice']));	

$close_ok = trim(sqlfilter($_REQUEST['close_ok']));	

$entry_age = trim(sqlfilter($_REQUEST['entry_age']));		
$entry_gender = trim(sqlfilter($_REQUEST['entry_gender']));		

$board_cate = trim(sqlfilter($_REQUEST['board_cate']));		
$file_cnt = trim(sqlfilter($_REQUEST['file_cnt']));		
$board_align = trim(sqlfilter($_REQUEST['board_align']));
$is_del = trim(sqlfilter($_REQUEST['is_del']));

if($board_master_idx > 0){ // 게시판 관리자를 입력했을때 시작
			
	$sql_sub1 = "select idx FROM member_info where 1=1 and idx='".$board_master_idx."' and user_sect = 'PAT' and memout_yn = 'N' ";
	$query_sub1 = mysqli_query($gconnet,$sql_sub1);
	$row_sub1 = mysqli_fetch_array($query_sub1);
	$cnt_sub1 = mysqli_num_rows($query_sub1);

	if($cnt_sub1 == 0){
		error_frame("입력하신 게시판 관리자에 해당하는 회원이 없습니다.");
		exit;
	}

} // 게시판 관리자를 입력했을때 종료

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

	$bbs = "board_config";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	//$_P_DIR_FILE2 = $_P_DIR_FILE."img_thumb/";
	//$_P_DIR_FILE3 = $_P_DIR_FILE."img_thumb2/";

	if ($_FILES['file1']['size']>0){ // 첨부파일 업로드

		if($file_old_name1){
			unlink($_P_DIR_FILE.$file_old_name1); // 원본파일 삭제
			//unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제
			//unlink($_P_DIR_FILE3.$file_old_name1); // 원본 중간 섬네일 파일 삭제
		}
		
		$file_o = $_FILES['file1']['name']; 
		/*$i_width = "111";
		$i_height = "147";
		$i_width2 = "321";
		$i_height2 = "425";
		//$watermark_sect = "imgw";
		$watermark_sect = "";
		$file_c = uploadFileThumb_1($_FILES, "file1", $_FILES['file1'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$watermark_sect);*/
		$file_c = uploadFile($_FILES, "file1", $_FILES['file1'], $_P_DIR_FILE); // 파일 업로드후 변형된 파일이름 리턴.
	
	} else {
		
		if($file_old_name1 && $file_old_org1){
			$file_c = $file_old_name1;
			$file_o = $file_old_org1;
		}

		if($del_org1 == "Y"){
			unlink($_P_DIR_FILE.$file_old_name1); // 원본파일 삭제
			//unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제
			//unlink($_P_DIR_FILE3.$file_old_name1); // 원본 중간 섬네일 파일 삭제

			//unlink($_P_DIR_FILE.$file_old_water1); // 원본파일 삭제
			//unlink($_P_DIR_FILE2.$file_old_water1); // 원본 작은 섬네일 파일 삭제
			//unlink($_P_DIR_FILE3.$file_old_water1); // 원본 중간 섬네일 파일 삭제
			$file_o = "";
			$file_c = "";
		}

	}

	if ($_FILES['file2']['size']>0){ // 첨부파일 업로드

		if($file_old_name2){
			unlink($_P_DIR_FILE.$file_old_name2); // 원본파일 삭제
			//unlink($_P_DIR_FILE2.$file_old_name2); // 원본 작은 섬네일 파일 삭제
			//unlink($_P_DIR_FILE3.$file_old_name2); // 원본 중간 섬네일 파일 삭제
		}

		$file_o2 = $_FILES['file2']['name']; // 파일 원래 이름 
		/*$i_width = "111";
		$i_height = "147";
		$i_width2 = "321";
		$i_height2 = "425";
		//$watermark_sect = "imgw";
		$watermark_sect = "";
		$file_c2 = uploadFileThumb_1($_FILES, "file2", $_FILES['file2'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$watermark_sect);*/
		$file_c2 = uploadFile($_FILES, "file2", $_FILES['file2'], $_P_DIR_FILE); // 파일 업로드후 변형된 파일이름 리턴.
	
	} else {
		
		if($file_old_name2 && $file_old_org2){
			$file_c2 = $file_old_name2;
			$file_o2 = $file_old_org2;
		}

		if($del_org2 == "Y"){
			unlink($_P_DIR_FILE.$file_old_name2); // 원본파일 삭제
			//unlink($_P_DIR_FILE2.$file_old_name2); // 원본 작은 섬네일 파일 삭제
			//unlink($_P_DIR_FILE3.$file_old_name2); // 원본 중간 섬네일 파일 삭제

			//unlink($_P_DIR_FILE.$file_old_water2); // 원본파일 삭제
			//unlink($_P_DIR_FILE2.$file_old_water2); // 원본 작은 섬네일 파일 삭제
			//unlink($_P_DIR_FILE3.$file_old_water2); // 원본 중간 섬네일 파일 삭제
			$file_c2 = "";
			$file_o2 = "";
		}

	} 
	
	$query = " update board_config set "; 
	$query .= " board_title = '".$board_title."', ";
	
	$query .= " board_info = '".$board_info."', ";
	$query .= " board_principle = '".$board_principle."', ";
	$query .= " board_master_idx = '".$board_master_idx."', ";

	$query .= " file1_org = '".$file_o."', ";
	$query .= " file1_chg = '".$file_c."', ";
	$query .= " file2_org = '".$file_o2."', ";
	$query .= " file2_chg = '".$file_c2."', ";
	$query .= " cate1 = '".$cate1."', ";
	$query .= " list_auth = '".$list_auth."', ";
	$query .= " view_auth = '".$view_auth."', ";
	$query .= " write_auth = '".$write_auth."', ";
	$query .= " reply_auth = '".$reply_auth."', ";
	$query .= " is_comment = '".$is_comment."', ";
	$query .= " is_notice = '".$is_notice."', ";

	$query .= " entry_age = '".$entry_age."', ";
	$query .= " entry_gender = '".$entry_gender."', ";

	$query .= " board_cate = '".$board_cate."', ";
	$query .= " file_cnt = '".$file_cnt."', ";
	$query .= " board_align = '".$board_align."', ";
	$query .= " is_del = '".$is_del."', ";
	$query .= " close_ok = '".$close_ok."' ";
	$query .= " where idx = '".$idx."' ";

	//echo $query;

	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('게시판 설정수정이 정상적으로 완료 되었습니다.');
	parent.location.href =  "board_config_view.php?idx=<?=$idx?>&bbs_code=<?=$bbs_code?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('게시물 수정중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
