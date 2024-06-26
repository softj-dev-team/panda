<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$v_sect = trim(sqlfilter($_REQUEST['v_sect']));
$s_group = trim(sqlfilter($_REQUEST['s_group']));

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = urldecode(sqlfilter($_REQUEST['keyword']));

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));
$s_sect3 = trim(sqlfilter($_REQUEST['s_sect3']));
$s_sect4 = trim(sqlfilter($_REQUEST['s_sect4']));

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&v_sect='.$v_sect.'&s_group='.$s_group.'&field='.$field.'&keyword='.$keyword.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_sect3='.$s_sect3.'&s_sect4='.$s_sect4;

/*$parent_sql = "select file_c from mainban_info where idx='".$idx."' ";
//echo $parent_sql; exit;
$parent_query = mysqli_query($gconnet,$parent_sql);
$parent_row = mysqli_fetch_array($parent_query);
$photo_file_s1 = $parent_row[file_c];

$bbs = "main_banner";
$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
$_P_DIR_FILE2 = $_P_DIR_FILE."img_thumb/";
$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE.$bbs."/";

if($photo_file_s1){ // 사진파일이 있을경우 
	unlink($_P_DIR_FILE.$photo_file_s1); // 원본파일 삭제
	unlink($_P_DIR_FILE2.$photo_file_s1); // 원본 작은 섬네일 파일 삭제
}*/

$result1 = mysqli_query($gconnet,"update sms_save set is_del='Y' where idx = '".$idx."'");

if($result1){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('삭제되었습니다.');
		parent.location.href = "sms_sample_list.php?<?=$total_param?>";
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