<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?
/*$product_idx = $_REQUEST['product_idx'];
$product_idx_arr = explode("|",$product_idx);

$CompanySeq = trim($product_idx_arr[0]);
$ItemSeq = trim($product_idx_arr[1]);*/

$product_idx = $_REQUEST['product_idx'];
$type = trim(sqlfilter($_REQUEST['type']));

$sql_name = "select idx,exp_title,file_chg from exp_info where 1=1 and idx = '".$product_idx."' ";
$query_name = mysqli_query($gconnet,$sql_name);
$row_name = mysqli_fetch_array($query_name);
$pro_name = $row_name[exp_title];
$file_c = $row_name[file_chg];
?>
<script type="text/javascript">
<!--
	var target1 = opener.document.all['pro_name_txt'];
	opener.document.frm.pro_name.value = "";
	opener.document.frm.pro_idx.value = "";
	opener.document.frm.pro_idx.value = "<?=$product_idx?>";
	opener.document.frm.pro_name.value = "<?=$pro_name?>";
	$("#pro_name_txt", opener.document).html("<img src='<?=$_P_DIR_WEB_FILE?>expinfo/img_thumb/<?=$file_c?>' border='0'>&nbsp;<?=$pro_name?>");
	self.close();
//-->
</script>