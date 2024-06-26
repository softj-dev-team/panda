<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
$fm = trim(sqlfilter($_REQUEST['fm']));
$fname = trim(sqlfilter($_REQUEST['fname']));
$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));

$query = "select gugun from zipcode where 1 and sido='".$cate_code1."' group by gugun order by gugun asc"; 
$result = mysqli_query($gconnet,$query);
?>
<script> 
 parent.<?=$fm?>.<?=$fname?>.length = <?=mysqli_num_rows($result)?>+2; 
 parent.<?=$fm?>.<?=$fname?>.options[0].text = '시,군,구'; 
 parent.<?=$fm?>.<?=$fname?>.options[0].value = ''; 
 parent.<?=$fm?>.<?=$fname?>.options[1].text = '전체'; 
 parent.<?=$fm?>.<?=$fname?>.options[1].value = '전체'; 
<?      
	for($i=0; $i<mysqli_num_rows($result); $i++){
		$row = mysqli_fetch_array($result);
?> 
   parent.<?=$fm?>.<?=$fname?>.options[<?=$i?>+2].text = '<?=$row[gugun]?>'; 
   parent.<?=$fm?>.<?=$fname?>.options[<?=$i?>+2].value = '<?=$row[gugun]?>'; 
<?}?>
</script> 