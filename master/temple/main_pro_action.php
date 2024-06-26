<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?
/*$temple_idx = $_REQUEST['temple_idx'];
$temple_idx_arr = explode("|",$temple_idx);

$CompanySeq = trim($temple_idx_arr[0]);
$ItemSeq = trim($temple_idx_arr[1]);*/

$temple_idx = $_REQUEST['temple_idx'];
$reltype = trim(sqlfilter($_REQUEST['reltype']));

$sql_name = "select idx,temple_title,(select file_chg from board_file where 1 and board_tbname='temple_info' and board_code='photo' and board_idx=temple_info.idx order by idx asc limit 0,1) as file_chg from temple_info where 1=1 and idx = '".$temple_idx."' ";
$query_name = mysqli_query($gconnet,$sql_name);
$row_name = mysqli_fetch_array($query_name);
$pro_name = $row_name[temple_title];
$file_c = $row_name[file_chg];
?>
<script type="text/javascript">
<!--
<?if($reltype == "temple1" || $reltype == "set1" || $reltype == "set2"){?>
	opener.go_relation_list('<?=$reltype?>','<?=$temple_idx?>');
<?}else{?>
	var target1 = opener.document.all['pro_name_txt'];
	opener.document.frm.pro_name.value = "";
	opener.document.frm.pro_idx.value = "";
	opener.document.frm.pro_idx.value = "<?=$temple_idx?>";
	opener.document.frm.pro_name.value = "<?=$pro_name?>";
	$("#pro_name_txt", opener.document).html("<img src='<?=$_P_DIR_WEB_FILE?>temple_info/img_thumb/<?=$row_name['file_chg']?>' style='max-width:10%;'>&nbsp;<?=$pro_name?>");
<?}?>
	self.close();
//-->
</script>