<?php include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?php include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
<?php include $_SERVER["DOCUMENT_ROOT"].""."/master/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?php

$l_idx = $_REQUEST['product_idx'];
//$l_idx = 21;
$l_idx_arr = explode("|",$l_idx);
$s_group = trim($l_idx_arr[0]);
$cafe_name = trim($l_idx_arr[1]);
$type = trim(sqlfilter($_REQUEST['type']));

?>
<script type="text/javascript">
<!--
$("#s_group", opener.document).val("<?php echo $s_group?>");
$("#cafe_name", opener.document).val("<?php echo $cafe_name?>");
$("#cafe_name_area", opener.document).html("<?php echo $cafe_name?>");
<?php if($type == "ing"){?>
	opener.location.href="calcurate_list.php?bmenu=1&smenu=1&s_group=<?php echo $s_group?>&cafe_name=<?php echo $cafe_name?>";
<?php }if($type == "com"){?>
	opener.location.href="calcurate_complete_list.php?bmenu=1&smenu=2&s_group=<?php echo $s_group?>&cafe_name=<?php echo $cafe_name?>";
<?php }?>
	self.close();
//-->
</script>