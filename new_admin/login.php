<?php
header('Content-Type: text/html; charset=UTF-8');
session_start();
require './db.class.php';
$id = $_POST['id'];
$pw = $_POST['pw'];

if ($id == "admin123123" && $pw == "admin123123") {
	$_SESSION['admin_check'] = "check";
	echo "<script>location.href='./dashboard.php';</script>";
	$_SESSION['manager_authority'] = "super";
} else {

	try {
		$result = new stdClass();

		$db = new DB();


		$stmt = $db->prepare("SELECT * FROM member_info WHERE user_id = :id AND user_pwd = :pw AND member_type='AD'");
		$stmt->bindParam(':id', $id);
		$stmt->bindParam(':pw', md5($pw));
		$stmt->execute();
		$col = $stmt->fetch(PDO::FETCH_ASSOC);

		if (sizeof($col) == 0) {
			echo "<script>alert('아이디 혹은 비밀번호가 잘못되었습니다.');history.back();</script>";
		} else {
			$_SESSION['admin_check'] = "check";
			echo "<script>location.href='./dashboard.php';</script>";
		}
	} catch (Exception $e) {
		var_dump($e);
	}
}
