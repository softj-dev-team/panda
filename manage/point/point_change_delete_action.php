<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	
	$point_sect = sqlfilter($_REQUEST['point_sect']);
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$field = trim(sqlfilter($_REQUEST['field']));
	$keyword = sqlfilter($_REQUEST['keyword']);
	$s_level = sqlfilter($_REQUEST['s_level']); 
	$s_gubun = sqlfilter($_REQUEST['s_gubun']);  // 내용확인 여부
	$site_sect = sqlfilter($_REQUEST['site_sect']); //  Contact Us / 광고문의 구분
	$s_gender = sqlfilter($_REQUEST['s_gender']); // 답변완료 여부
	$s_gender2 = sqlfilter($_REQUEST['s_gender2']); // 접수상태
	$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
	$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 
	################## 파라미터 조합 #####################
	$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&point_sect='.$point_sect.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&site_sect='.$site_sect.'&s_gender='.$s_gender.'&s_gender2='.$s_gender2.'&s_cnt='.$s_cnt.'&s_order='.$s_order;

	// 회원 정보 삭제 
	$query = "update member_point_change set del_yn='Y' where 1 and idx = '".$idx."'"; 
	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 삭제 되었습니다.');
	parent.location.href =  "point_change_list.php?<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('삭제 중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>