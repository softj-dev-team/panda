<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<?
$fm = trim(sqlfilter($_REQUEST['fm']));
$fname = trim(sqlfilter($_REQUEST['fname']));
$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
$cate_code2 = trim(sqlfilter($_REQUEST['cate_code2']));

$query = "select cate_code3,cate_name3 from product_cate where 1 and cate_level = '3' and is_del='N' and cate_code1='".$cate_code1."'";
if($cate_code2){
	$query .= " and cate_code2='".$cate_code2."' ";
}
$sect3_sql .= " order by cate_align desc";

//echo $query;
$result = mysqli_query($gconnet,$query);
?>
<script> 
 parent.<?=$fm?>.<?=$fname?>.length = <?=mysqli_num_rows($result)?>+1; 
 parent.<?=$fm?>.<?=$fname?>.options[0].text = '소분류'; 
 parent.<?=$fm?>.<?=$fname?>.options[0].value = ''; 

<?      
	for($i=0; $i<mysqli_num_rows($result); $i++){
		$row = mysqli_fetch_array($result);
?> 
   parent.<?=$fm?>.<?=$fname?>.options[<?=$i?>+1].text = "<?=$row[cate_name3]?>"; 
   parent.<?=$fm?>.<?=$fname?>.options[<?=$i?>+1].value = "<?=$row[cate_code3]?>"; 
<?}?>
</script> 
