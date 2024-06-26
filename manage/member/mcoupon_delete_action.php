<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));

$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = urldecode(sqlfilter($_REQUEST['keyword']));

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2;

$rcnt_sql = "select idx from member_coupon where 1 and coupon_idx='".$idx."' and is_del='N'";
$rcnt_query = mysqli_query($gconnet,$rcnt_sql);
$rcnt = mysqli_num_rows($rcnt_query);

if($rcnt > 0){
	error_frame("다운받은 회원이 존재하기 때문에 삭제할 수 없습니다.");
}

$result1 = mysqli_query($gconnet,"update member_coupon_set set is_del='Y' where 1 and idx = '".$idx."'");
//$result2 = mysqli_query($gconnet,"delete from member_coupon where coupon_idx = '".$idx."' and coupon_sect = 'A'");

if($result1){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('삭제되었습니다.');
		parent.location.href = "mcoupon_list.php?<?=$total_param?>";
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