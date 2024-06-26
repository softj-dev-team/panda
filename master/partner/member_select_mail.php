<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_level = sqlfilter($_REQUEST['s_level']); // 회원 등급별 검색
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별 검색

$where = " and memout_yn != 'Y' and memout_yn != 'S' and member_type='GEN'";

if($s_level){
	$where .= " and user_level = '".$s_level."' ";
}

if($s_gender){
	//$where .= " and member_gubun = '".$s_gender."' ";
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$order_by = " order by idx desc ";

$query = "select * from member_info where 1=1 ".$where.$order_by;
//echo $query;
$result = mysqli_query($gconnet,$query);

for ($i=0; $i<mysqli_num_rows($result); $i++){
	$row = mysqli_fetch_array($result);
	$user_name = $row[user_name];
?>
	<?if($s_gender == "push"){?>
	<tr>
		<td style="width:200px;"><input type="checkbox" id="c2_<?=$i?>" name="chk1" onclick="num_chk(this.value, '');" value="<?=$row[user_id]?>">&nbsp;<label for="c2_<?=$i?>"><?=$row[user_id]?></label></td>
		<td style="width:100px;"><label for="c2_<?=$i?>"><?=$row[user_name]?></label></td>
		<td style="width:150px;"><label for="c2_<?=$i?>"><?=$row[email]?></label></td>
	</tr>
	<?}else{?>
	<tr>
		<td style="width:110px;"><input type="checkbox" id="c2_<?=$i?>" name="chk1" onclick="num_chk(this.value, '');" value="<?=$row[email]?>">&nbsp;<label for="c2_<?=$i?>"><?=$user_name?><br>(<?=$row[user_id]?>)</label></td>
		<td style="width:100px;"><label for="c2_<?=$i?>"><?=substr($row[wdate],0,10)?></label></td>
		<td style="width:200px;"><label for="c2_<?=$i?>"><?=$row[email]?></label></td>
	</tr>
	<?}?>
<?
}
?>