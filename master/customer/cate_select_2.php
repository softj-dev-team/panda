<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
$fm = trim(sqlfilter($_REQUEST['fm']));
$fname = trim(sqlfilter($_REQUEST['fname']));
$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
?>
<script> 
<? 
	$query = "select idx,sing_title from member_sing where 1 and member_idx='".$cate_code1."' and is_del='N' order by sing_align desc"; 
	$result = mysqli_query($gconnet,$query);
 ?> 
 parent.<?=$fm?>.<?=$fname?>.length = <?=mysqli_num_rows($result)?>+1; 
 parent.<?=$fm?>.<?=$fname?>.options[0].text = '곡 선택'; 
 parent.<?=$fm?>.<?=$fname?>.options[0].value = ''; 

<?      
	for($i=0; $i<mysqli_num_rows($result); $i++){
		$row = mysqli_fetch_array($result);
?> 
   parent.<?=$fm?>.<?=$fname?>.options[<?=$i?>+1].text = '<?=$row[sing_title]?>'; 
   parent.<?=$fm?>.<?=$fname?>.options[<?=$i?>+1].value = '<?=$row[sing_title]?>'; 
<?}?>
</script> 