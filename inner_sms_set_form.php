<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<?
$idx = trim(sqlfilter($_REQUEST['sms_idx']));

$where = " and is_del='N' and idx='" . $idx . "'";

$query = "select * from sms_save a where 1 " . $where;
//echo "<br><br>쿼리 = ".$query."<br><Br>";
$result = mysqli_query($gconnet, $query);
$row = mysqli_fetch_array($result);

echo $row['sms_content'];
?>