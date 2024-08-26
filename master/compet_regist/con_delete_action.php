<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
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
	################## 파라미터 조합 #####################
	$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&site_sect='.$site_sect.'&s_gender='.$s_gender;
		
	$query = "update compet_regist_price_info set is_del='Y' where idx = '".$idx."'"; 
	//$query = "delete from site_contact_add where 1 and idx = '".$idx."'"; 
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