<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드 
?>
<?
$member_idx = $_SESSION['member_coinc_idx'];
$group_idx = $_REQUEST['group_idx'];
$query = "select * from address_group_num WHERE member_idx ='" . $member_idx . "' and group_idx IN (" . $group_idx . ") order by idx desc";
$result = mysqli_query($gconnet, $query);
//var_dump($query);
$rows = array();
while ($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}
echo json_encode($rows);

?>