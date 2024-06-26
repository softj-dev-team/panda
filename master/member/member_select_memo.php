<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_level = sqlfilter($_REQUEST['s_level']); // 회원 등급별 검색
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별 검색

$where = " and memout_yn != 'Y' and member_type = 'PATIN' ";

if($s_level){
	$where .= " and user_level = '".$s_level."' ";
}

if($s_gender){
	$where .= " and member_gubun = '".$s_gender."' ";
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$order_by = " order by idx desc ";

$query = "select * from member_info where 1=1 ".$where.$order_by;
$result = mysqli_query($gconnet,$query);

for ($i=0; $i<mysqli_num_rows($result); $i++){
	$row = mysqli_fetch_array($result);

	if($row['member_gubun'] == "NOR"){
		$user_name = $row[user_name];
	} elseif($row['member_gubun'] == "SPE"){
		$user_name = $row[com_name];
	}
?>
	<tr>
		<td class="chk"><input type="checkbox" id="c2" name="chk1" onclick="num_chk(this.value, '');" value="<?=$row[user_id]?>"></td>
		<td class="name"><label for="c2"><?=$user_name?><br>(<?=$row[user_id]?>)</label></td>
		<td class="date"><label for="c2"><?=$row[user_id]?></label></td>
		<td class="phn"><label for="c2"><?=substr($row[wdate],0,10)?></label></td>
	</tr>
<?
}
?>