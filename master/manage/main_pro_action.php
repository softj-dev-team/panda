<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin_pop.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_popup.php"; // 관리자 로그인여부 확인?>
<?
/*$product_idx = $_REQUEST['product_idx'];
$product_idx_arr = explode("|",$product_idx);

$CompanySeq = trim($product_idx_arr[0]);
$ItemSeq = trim($product_idx_arr[1]);*/

$type = trim(sqlfilter($_REQUEST['type']));
$product_idx = $_REQUEST['product_idx'];
$sdate = trim(sqlfilter($_REQUEST['sdate']));
$edate = trim(sqlfilter($_REQUEST['edate']));

for($i=0; $i<sizeof($product_idx); $i++){
	$target_idx = $product_idx[$i];

	$cate_code_sql = "select max(align) as align from main_select_info where 1 and is_del='N'";
	$cate_code_query = mysqli_query($gconnet,$cate_code_sql);
	$cate_code_row = mysqli_fetch_array($cate_code_query);
	if(!$cate_code_row['align']){
		$align = 1;
	} else {
		$align = $cate_code_row['align']+1;
	}

	$query = "insert into main_select_info set"; 
	$query .= " type = '".$type."', ";
	$query .= " target_idx = '".$target_idx."', ";
	$query .= " sdate = '".$sdate."', ";
	$query .= " edate = '".$edate."', ";
	$query .= " align = '".$align."', ";
	$query .= " wdate = now() ";
	$result = mysqli_query($gconnet,$query);
}
?>
<script type="text/javascript">
<!--
	alert("등록되었습니다.");
	parent.opener.location.reload();
	parent.self.close();
//-->
</script>