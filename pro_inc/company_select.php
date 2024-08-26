<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
$type = trim(sqlfilter($_REQUEST['type']));
$skey_arr1 = trim(sqlfilter($_REQUEST['skey']));
$skey_arr2 = explode(" ",$skey_arr1);	
$skey = $skey_arr2[0];

	$where = " and memout_yn != 'Y' and memout_yn != 'S' and del_yn='N'";

	if($type){
		$where .= " and member_type = '".$type."' ";
	}
	
	if ($skey){
		//$where .= " and book_title like '%".$skey."%'";
		$s_skey = str_replace(" ","",$skey);
		$where .= " and (replace(com_name,' ','') like '%".$s_skey."%' or replace(member_code,' ','') like '%".$s_skey."%')";
	}

	$query = "select idx,com_name,member_code from member_info where 1 ".$where." order by com_name asc";
	//echo $query."<br>";
	$result = mysqli_query($gconnet,$query);
	$cnt = mysqli_num_rows($result);
?>
<table>
<?
	for ($ikm=0; $ikm<$cnt; $ikm++){ // 카테고리 해당되는 도서 루프 시작 
		$row = mysqli_fetch_array($result);
?>
	<tr><td style="text-align:left;padding-left:10px;height:25px;"><a href="javascript:search_com_rst('<?=$type?>','<?=$row[idx]?>','<?=$row[com_name]?>','<?=$row[member_code]?>');"><?=$row[com_name]?> (<?=$row[member_code]?>)</a></td></tr>
<?}?>
</table>
