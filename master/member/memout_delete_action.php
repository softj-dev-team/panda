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
	$s_level = sqlfilter($_REQUEST['s_level']); // 회원 계급별 검색
	$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 정회원,우수회원,셀러회원 등 검색
	$v_sect = sqlfilter($_REQUEST['v_sect']); // 일반회원, 제휴회원 구분
	$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별 검색
	################## 파라미터 조합 #####################
	$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&v_sect='.$v_sect.'&s_gender='.$s_gender;

	$query = " delete from member_info_out "; 
	$query .= " where idx = '".$idx."' ";

	//echo $query;

	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('탈퇴처리 완료된 회원정보삭제가 정상적으로 완료 되었습니다.');
	parent.location.href =  "member_out_done.php?<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$member_sect_str?>정보삭제중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>