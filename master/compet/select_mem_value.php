<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<?
$idx = trim(sqlfilter($_REQUEST['member_idx']));

$sql = "SELECT user_name,cell,email FROM member_info a where 1=1 and idx = '".$idx."' and del_yn='N'";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
	error_frame("회원정보가 없습니다.");
}

$row = mysqli_fetch_array($query);

$user_name = $row['user_name'];
$cell = $row['cell'];
$email = $row['email'];
?>

<script>
	$("#member_name", parent.document).val("<?=$user_name?>");
	$("#member_cell", parent.document).val("<?=$cell?>");
	$("#member_email", parent.document).val("<?=$email?>");
</script>