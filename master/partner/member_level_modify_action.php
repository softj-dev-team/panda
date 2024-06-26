<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

	<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	
	$level_name = trim(sqlfilter($_REQUEST['level_name']));
	$level_gijun = trim(sqlfilter($_REQUEST['level_gijun']));
	$level_price = trim(sqlfilter($_REQUEST['level_price']));
	$level_align = trim(sqlfilter($_REQUEST['level_align']));
	$is_del = trim(sqlfilter($_REQUEST['is_del']));
	
	$file_old_name1 = trim(sqlfilter($_REQUEST['file_old_name1']));		//file_old_name1
	$file_old_org1 = trim(sqlfilter($_REQUEST['file_old_org1']));			//file_old_org1
	$del_org1 = $_REQUEST['del_org1'];											//del_

	$bbs = "level";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	$_P_DIR_FILE2 = $_P_DIR_FILE."img_thumb/";

	if ($_FILES['file1']['size']>0){

		if($file_old_name1){
		unlink($_P_DIR_FILE.$file_old_name1); // 원본파일 삭제
		unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제
		
		//unlink($_P_DIR_FILE.$file_old_water1); // 원본파일 삭제
		//unlink($_P_DIR_FILE2.$file_old_water1); // 원본 작은 섬네일 파일 삭제
		}

	$file_o = $_FILES['file1']['name']; 
	$i_width = "10";
	$i_height = "10";
	$i_width2 = "";
	$i_height2 = "";
	//$watermark_sect = "imgw";
	$watermark_sect = "";
	$file_c = uploadFileThumb_1($_FILES, "file1", $_FILES['file1'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$watermark_sect);
	} else {
		
		if($file_old_name1 && $file_old_org1){
			$file_c = $file_old_name1;
			$file_o = $file_old_org1;
		}

		if($del_org1 == "Y"){
			unlink($_P_DIR_FILE.$file_old_name1); // 원본파일 삭제
			unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제
			
			//unlink($_P_DIR_FILE.$file_old_water1); // 원본파일 삭제
			//unlink($_P_DIR_FILE2.$file_old_water1); // 원본 작은 섬네일 파일 삭제
			$file_o = "";
			$file_c = "";
		}

	}

	$query = " update member_level_set set "; 
	$query .= " level_name = '".$level_name."', ";
	$query .= " file_org = '".$file_o."', ";
	$query .= " file_chg = '".$file_c."', ";
	$query .= " level_align = '".$level_align."', ";
	$query .= " level_gijun = '".$level_gijun."', ";
	$query .= " level_price = '".$level_price."', ";
	$query .= " is_del = '".$is_del."' ";
	$query .= " where idx = '".$idx."' ";

	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('회원등급 수정이 정상적으로 완료 되었습니다.');
	parent.location.href =  "member_level_list.php?<?=$total_param?>&pageNo=<?=$pageNo?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('회원등급 수정중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>