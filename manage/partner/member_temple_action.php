<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage_pop.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?
$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
$type = trim(sqlfilter($_REQUEST['type']));

$temple_idx = $_REQUEST['temple_idx'];

$sql_name = "select idx,member_idx,temple_title from temple_info where 1 and idx = '".$temple_idx."' ";
$query_name = mysqli_query($gconnet,$sql_name);
$row_name = mysqli_fetch_array($query_name);
$temple_title = $row_name[temple_title];

$query_in = "insert into member_temple_add set"; 
$query_in .= " member_shop_idx = '".$member_idx."', ";
$query_in .= " temple_idx = '".$row_name['idx']."', ";
$query_in .= " member_temple_idx = '".$row_name['member_idx']."', ";
$query_in .= " wdate = now() ";
$result_in = mysqli_query($gconnet,$query_in);
?>
<script type="text/javascript">
<!--
	opener.temple_request_list();
	self.close();
//-->
</script>