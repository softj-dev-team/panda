<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<? 
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
	$v_step = trim(sqlfilter($_REQUEST['v_step']));
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$field = trim(sqlfilter($_REQUEST['field']));
	$keyword = sqlfilter($_REQUEST['keyword']);
	
	$cate_level = trim(sqlfilter($_REQUEST['cate_level']));
	$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
	$cate_name1 = trim(sqlfilter($_REQUEST['cate_name1']));
	$cate_code2 = trim(sqlfilter($_REQUEST['cate_code2']));
	$cate_name2 = trim(sqlfilter($_REQUEST['cate_name2']));
	$cate_code3 = trim(sqlfilter($_REQUEST['cate_code3']));
	$cate_name3 = trim(sqlfilter($_REQUEST['cate_name3']));
	$cate_code4 = trim(sqlfilter($_REQUEST['cate_code4']));
	$cate_name4 = trim(sqlfilter($_REQUEST['cate_name4']));
	$is_del = trim(sqlfilter($_REQUEST['is_del']));
	$cate_align = trim(sqlfilter($_REQUEST['cate_align']));

	$file_old_name1 = trim(sqlfilter($_POST['file_old_name1']));		//file_old_name1
	$file_old_org1 = trim(sqlfilter($_POST['file_old_org1']));			//file_old_org1
	$del_org1 = $_POST['del_org1'];											//del_org
		
	$bbs = "cate_banner";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE.$bbs."/";

	if ($_FILES['file1']['size']>0){
		if($file_old_name1){
		unlink($_P_DIR_FILE.$file_old_name1); // 원본파일 삭제
		unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제
		}
		$file_o = $_FILES['file1']['name']; 
		$i_width = "84";
		$i_height = "74";
		$file_c = uploadFileThumb_1($_FILES, "file1", $_FILES['file1'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2);
	} else {
		if($file_old_name1 && $file_old_org1){
			$file_c = $file_old_name1;
			$file_o = $file_old_org1;
		}
		if($del_org1 == "Y"){
			unlink($_P_DIR_FILE.$file_old_name1); // 원본파일 삭제
			unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제
			$file_o = "";
			$file_c = "";
		}
	}

	if($cate_level == 1){
		$cate_str = "카테고리";

		$query = " update viva_cate set "; 
		$query .= " cate_name1 = '".$cate_name1."', ";
		$query .= " file_o = '".$file_o."', ";
		$query .= " file_c = '".$file_c."', ";
		$query .= " is_del = '".$is_del."', ";
		$query .= " cate_align = '".$cate_align."' ";
		$query .= " where idx = '".$idx."' ";

	} elseif($cate_level == 2){
		$cate_str = "카테고리";

		$query = " update viva_cate set "; 
		$query .= " cate_name2 = '".$cate_name2."', ";
		$query .= " is_del = '".$is_del."', ";
		$query .= " cate_align = '".$cate_align."' ";
		$query .= " where idx = '".$idx."' ";

	} elseif($cate_level == 3){
		$cate_str = "카테고리";

		$query = " update viva_cate set "; 
		$query .= " cate_name3 = '".$cate_name3."', ";
		$query .= " is_del = '".$is_del."', ";
		$query .= " cate_align = '".$cate_align."' ";
		$query .= " where idx = '".$idx."' ";
	} 
	
	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$cate_str?> 수정이 정상적으로 완료 되었습니다.');
	parent.location.href =  "hash_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&cate_code1=<?=$cate_code1?>&cate_code2=<?=$cate_code2?>&cate_code3=<?=$cate_code3?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$cate_str?> 수정중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
