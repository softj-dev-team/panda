<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_level = sqlfilter($_REQUEST['s_level']); // 회원 등급별 검색
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별 검색

$where .= " and memout_yn != 'Y' and memout_yn != 'S' and member_type = 'GEN'";

if($s_level){
	//$where .= " and user_level = '".$s_level."' ";
	$where .= " and nation = '".$s_level."' ";
}

if($s_gender){
	$where .= " and gender = '".$s_gender."' ";
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$order_by = " order by idx desc ";

$query = "select * from member_info where 1=1 ".$where.$order_by;
//echo "쿼리 = ".$query;
$result = mysqli_query($gconnet,$query);

for ($i=0; $i<mysqli_num_rows($result); $i++){
	$row = mysqli_fetch_array($result);
?>
	<tr>
		<td class="chk"  style="width:100px;"><input type="checkbox" id="c2" name="chk1" onclick="num_chk(this.value, '');" value="<?=$row[user_id]?>"> <?=$row[user_name]?></td>
		<td class="name"  style="width:150px;"><label for="c2"><?=$row[user_id]?></label></td>
		<td class="date"  style="width:150px;"><label for="c2"><?=$row[nation]?></label></td>
		<td class="phn"  style="width:150px;"><label for="c2"><?=$row[scipe_id]?></label></td>
	</tr>
<?
}
?>