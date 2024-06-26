<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
	$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
	$cate_code2 = trim(sqlfilter($_REQUEST['cate_code2']));
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));

	$sect3_sql = "select cate_code3,cate_name3 from product_cate where 1  and cate_level = '3' and is_del='N' and cate_code1='".$cate_code1."' ";
	if($cate_code2){
		$sect3_sql .= " and cate_code2='".$cate_code2."' ";
	}
	$sect3_sql .= " order by cate_align desc";

	//echo $sect3_sql."<br>";

	$sect3_result = mysqli_query($gconnet,$sect3_sql);
	for ($k=0; $k<mysqli_num_rows($sect3_result); $k++){
		$row3 = mysqli_fetch_array($sect3_result);

		$sect3_check_sql = "select idx from member_category_set where 1 and cate_level = '3' and cate_code1='".$cate_code1."' and cate_code2='".$cate_code2."' and cate_code3='".$row3[cate_code3]."' and member_idx = '".$member_idx."' ";
		$sect3_check_result = mysqli_query($gconnet,$sect3_check_sql);
		if(mysqli_num_rows($sect3_check_result)>0){
			$check_sect3 = "checked";
		} else {
			$check_sect3 = "";
		}
?>
	<input type="checkbox" name="pro_cate3[]" required="no"  message="소분류" value="<?=$row3[cate_code3]?>" <?=$check_sect3?>> <?=$row3[cate_name3]?> &nbsp; 
<?}?>
