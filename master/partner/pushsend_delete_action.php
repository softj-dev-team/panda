<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
$mail_key = trim(sqlfilter($_REQUEST['mail_key']));
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$mail_gubun = sqlfilter($_REQUEST['mail_gubun']);
$s_level = sqlfilter($_REQUEST['s_level']); // 회원 등급별 검색
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 정회원,우수회원,셀러회원 등 검색
$v_sect = sqlfilter($_REQUEST['v_sect']); // 회원, 제휴회원 구분
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별 검색
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&v_sect='.$v_sect.'&s_gender='.$s_gender.'&mail_gubun='.$mail_gubun;

$result1 = mysqli_query($gconnet,"delete from board_content where bbs_sect = '".$mail_key."' and bbs_code='push'");
$result2 = mysqli_query($gconnet,"delete from send_msg_member where mail_key = '".$mail_key."'");

if($result1){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('삭제되었습니다.');
		parent.location.href = "push_send_list.php?<?=$total_param?>";
	//-->
	</SCRIPT>
	<?
}else{
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?
}
?>