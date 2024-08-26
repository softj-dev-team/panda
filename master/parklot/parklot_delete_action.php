<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$field = trim(sqlfilter($_REQUEST['field']));
	$keyword = sqlfilter($_REQUEST['keyword']);
	$v_sect = sqlfilter($_REQUEST['v_sect']); 
	$s_gubun = sqlfilter($_REQUEST['s_gubun']); 
	$s_level = sqlfilter($_REQUEST['s_level']); 
	$s_gender = sqlfilter($_REQUEST['s_gender']); 
	$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
	$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
	################## 파라미터 조합 #####################
	$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2;

	$parklot_sect_str = "";

	/* 주차장 사진 삭제
	$file_sql = "select file_chg from parklot_info where 1=1 and idx = '".$idx."' ";
	$file_query = mysqli_query($gconnet,$file_sql);
	$file_row = mysqli_fetch_array($file_query);
	$file_old_name1 = $file_row['file_chg'];
	$file_old_name2 = $file_row['file_chg2'];
	
	$bbs = "hotel";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	$_P_DIR_FILE2 = $_P_DIR_FILE."img_thumb/";
	//$_P_DIR_FILE3 = $_P_DIR_FILE."img_thumb2/";
	
	if($file_old_name1){
		unlink($_P_DIR_FILE.$file_old_name1); // 
		unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제
	}

	if($file_old_name2){
		unlink($_P_DIR_FILE.$file_old_name2); // 
		unlink($_P_DIR_FILE2.$file_old_name2); // 원본 작은 섬네일 파일 삭제
	}
	
	$file2_sql = "select file_chg,file_chg2,file_chg3,file_chg4 from parklot_info_detail where 1 and parklot_idx = '".$idx."' ";
	$file2_query = mysqli_query($gconnet,$file2_sql);
	$file2_row = mysqli_fetch_array($file2_query);
	$file2_old_name1 = $file2_row['file_chg'];
	$file2_old_name2 = $file2_row['file_chg2'];
	$file2_old_name3 = $file2_row['file_chg3'];
	$file2_old_name4 = $file2_row['file_chg4'];

	if($file2_old_name1){
		unlink($_P_DIR_FILE.$file2_old_name1); 
		unlink($_P_DIR_FILE2.$file2_old_name1);
		unlink($_P_DIR_FILE3.$file2_old_name1);
	}
	if($file2_old_name2){
		unlink($_P_DIR_FILE.$file2_old_name2); 
		unlink($_P_DIR_FILE2.$file2_old_name2);
		unlink($_P_DIR_FILE3.$file2_old_name2);
	}
	if($file2_old_name3){
		unlink($_P_DIR_FILE.$file2_old_name3); 
		unlink($_P_DIR_FILE2.$file2_old_name3);
		unlink($_P_DIR_FILE3.$file2_old_name3);
	}
	if($file2_old_name4){
		unlink($_P_DIR_FILE.$file2_old_name4); 
		unlink($_P_DIR_FILE2.$file2_old_name4);
		unlink($_P_DIR_FILE3.$file2_old_name4);
	}*/

	// 주차장 정보 삭제 
	$query = "update parklot_info set is_del='Y'"; 
	$query .= " where idx = '".$idx."' ";
	$result = mysqli_query($gconnet,$query);

	$query2 = " update parklot_public_time set ";
	$query2 .= " is_del = 'Y' ";
	$query2 .= " where parklot_idx = '".$idx."' ";
	$result2 =  mysqli_query($gconnet,$query2);

	/* 상세 정보 삭제 
	$query_dt = " delete from parklot_info_detail "; 
	$query_dt .= " where parklot_idx = '".$idx."' ";
	$result_dt = mysqli_query($gconnet,$query_dt);
	
	// 포인트 적립/누적 히스토리 삭제 
	$query_point = " delete from parklot_point "; 
	$query_point .= " where parklot_idx = '".$idx."' ";
	$result_point = mysqli_query($gconnet,$query_point);

	// 상세 정보 삭제 
	$query_dt = " delete from parklot_product_info "; 
	$query_dt .= " where parklot_idx = '".$idx."' ";
	$result_dt = mysqli_query($gconnet,$query_dt);

	// 상세 정보 삭제 
	$query_dt = " delete from parklot_company_info "; 
	$query_dt .= " where parklot_idx = '".$idx."' ";
	$result_dt = mysqli_query($gconnet,$query_dt);*/

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$parklot_sect_str?> 정보삭제가 정상적으로 완료 되었습니다.');
	parent.location.href =  "parklot_list.php?<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$parklot_sect_str?> 정보삭제중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>