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
	$s_level = sqlfilter($_REQUEST['s_level']); 
	$s_gubun = sqlfilter($_REQUEST['s_gubun']);  // 내용확인 여부
	$site_sect = sqlfilter($_REQUEST['site_sect']); //  Contact Us / 광고문의 구분
	$s_gender = sqlfilter($_REQUEST['s_gender']); // 답변완료 여부
	$s_gender2 = sqlfilter($_REQUEST['s_gender2']); // 접수상태
	################## 파라미터 조합 #####################
	$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&site_sect='.$site_sect.'&s_gender='.$s_gender.'&s_gender2='.$s_gender2;

	// 회원 사진 삭제
	$file_sql = "select file_chg from pat_member_ad where 1=1 and idx = '".$idx."' ";
	$file_query = mysqli_query($gconnet,$file_sql);
	$file_row = mysqli_fetch_array($file_query);
	$file_old_name1 = $file_row['file_chg'];
		
	$bbs = "member_detail";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	$_P_DIR_FILE2 = $_P_DIR_WEB_FILE.$bbs."/img_thumb/";
	
	if($file_old_name1){
		unlink($_P_DIR_FILE.$file_old_name1); // 
		unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제
	}
	
	// 회원 정보 삭제 
	$query = " delete from pat_member_ad "; 
	$query .= " where idx = '".$idx."' ";
	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 삭제 되었습니다.');
	parent.location.href =  "con_list.php?<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('삭제 중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>