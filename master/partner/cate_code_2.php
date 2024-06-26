<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
	$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));

	$sect2_sql = "select cate_code2,cate_name2 from product_cate where 1  and cate_level = '2' and is_del='N' and cate_code1='".$cate_code1."' order by cate_align desc";
	$sect2_result = mysqli_query($gconnet,$sect2_sql);

	//echo $sect2_sql."<br>";

	for ($k=0; $k<mysqli_num_rows($sect2_result); $k++){
		$row2 = mysqli_fetch_array($sect2_result);

		$sect2_check_sql = "select idx from member_category_set where 1 and cate_level = '2' and cate_code1='".$cate_code1."' and cate_code2='".$row2[cate_code2]."' and member_idx = '".$member_idx."' ";
		$sect2_check_result = mysqli_query($gconnet,$sect2_check_sql);
		if(mysqli_num_rows($sect2_check_result)>0){
			$check_sect2 = "checked";
		} else {
			$check_sect2 = "";
		}
?>
	<input type="radio" name="pro_cate2" required="no"  message="중분류" value="<?=$row2[cate_code2]?>" onclick="cate_code_2('<?=$row2[cate_code2]?>')" <?=$check_sect2?>> <?=$row2[cate_name2]?> &nbsp; 
<?}?>
