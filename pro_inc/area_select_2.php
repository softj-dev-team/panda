<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<?
$fm = trim(sqlfilter($_REQUEST['fm']));
$fname = trim(sqlfilter($_REQUEST['fname']));
$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
$selkey_g = trim(sqlfilter($_REQUEST['selkey_g']));

$query = "select bjd_code,k_name from code_bjd where 1 and filter='EMD' and del_yn='N' and pre_code='".$cate_code1."' order by idx asc"; 
//echo $query."<br>";
$result = mysqli_query($gconnet,$query);
?>
<script> 
 parent.<?=$fm?>.<?=$fname?>.length = <?=mysqli_num_rows($result)?>+1; 
 parent.<?=$fm?>.<?=$fname?>.options[0].text = '읍/면/동'; 
 parent.<?=$fm?>.<?=$fname?>.options[0].value = ''; 
<?      
	for($i=0; $i<mysqli_num_rows($result); $i++){
		$row = mysqli_fetch_array($result);
?> 
   parent.<?=$fm?>.<?=$fname?>.options[<?=$i?>+1].text = "<?=$row['k_name']?>"; 
   parent.<?=$fm?>.<?=$fname?>.options[<?=$i?>+1].value = "<?=$row['bjd_code']?>";  
<?}?>
</script> 
