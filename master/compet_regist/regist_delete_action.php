<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$field = trim(sqlfilter($_REQUEST['field']));
	$keyword = sqlfilter($_REQUEST['keyword']);
	$v_sect = sqlfilter($_REQUEST['v_sect']); // 프로그램주
	$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 게시 시작일
	$s_level = sqlfilter($_REQUEST['s_level']); // 게시 종료일
	$s_gender = sqlfilter($_REQUEST['s_gender']); 
	$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
	$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
	$s_order = trim(sqlfilter($_REQUEST['s_order']));
	
	$compet_idx = trim(sqlfilter($_REQUEST['compet_idx']));

	################## 파라미터 조합 #####################
	$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order;

	$sql_pre2 = "select member_idx from compet_regist_info where 1 and idx = '".$idx."' "; 
	$result_pre2  = mysqli_query($gconnet,$sql_pre2);
	$row_pre2 = mysqli_fetch_array($result_pre2);

	if($row_pre2['member_idx'] != $_SESSION['manage_coinc_idx']) {
		//error_frame("직접 등록하신 프로그램만 수정하실 수 있습니다.");
	}

	// 회원 사진 삭제
	$file_sql = "select file_chg from compet_regist_info where 1=1 and idx = '".$idx."' ";
	$file_query = mysqli_query($gconnet,$file_sql);
	$file_row = mysqli_fetch_array($file_query);
	$file_old_name1 = $file_row['file_chg'];
	$file_old_name2 = $file_row['file_chg2'];
	
	$bbs = "adinfo";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	$_P_DIR_FILE2 = $_P_DIR_FILE."img_thumb/";
	$_P_DIR_FILE3 = $_P_DIR_FILE."img_thumb2/";
	$_P_DIR_FILE4 = $_P_DIR_FILE."img_thumb3/";
	//$_P_DIR_FILE3 = $_P_DIR_FILE."img_thumb2/";
	
	/*if($file_old_name1){
		unlink($_P_DIR_FILE.$file_old_name1); // 원본파일 삭제
		unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제
		unlink($_P_DIR_FILE3.$file_old_name1); // 원본 중간 섬네일 파일 삭제
		unlink($_P_DIR_FILE4.$file_old_name1); // 원본 중간 섬네일 파일 삭제
	}

	if($file_old_name2){
		unlink($_P_DIR_FILE.$file_old_name2); // 
		unlink($_P_DIR_FILE2.$file_old_name2); // 원본 작은 섬네일 파일 삭제
	}*/
	
	$query = " update compet_regist_info set ";
	$query .= " is_del = 'Y' ";
	//$query .= " where idx = '".$idx."' and member_idx='".$_SESSION['manage_coinc_idx']."'";
	$query .= " where idx = '".$idx."'";
	$result = mysqli_query($gconnet,$query);

	$query_comp = " update compet_info set "; 
	$query_comp .= " rcnt = rcnt-1 ";
	$query_comp .= " where 1 and idx = '".$compet_idx."'";
	$result_comp = mysqli_query($gconnet,$query_comp); // 참가자 수 감소
	
	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('삭제가 정상적으로 완료 되었습니다.');
	parent.location.href =  "regist_list.php?<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('삭제중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>