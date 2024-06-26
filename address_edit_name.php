<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드 
?>
<?
$idx = $_REQUEST['idx'];
$name = $_REQUEST['name'];

$query = "update address_group_num set";
$query .= " receive_name = '" . $name . "', ";
$query .= " mdate = now() ";
$query .= " where idx = '" . $idx . "'";
//echo $query;
$result = mysqli_query($gconnet, $query);
$result_['result'] = "success";
echo json_encode($result_);




?>