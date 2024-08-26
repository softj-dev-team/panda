<?php
session_start();
if (!isset($_SESSION['admin_check'])) {
	unset($_SESSION['admin_check']);
	session_destroy(); // 세션 아이디의 삭제
	echo "<script>alert('잘못된 접근입니다.');location.href='./index.php';</script>";
} else if ($_SESSION['admin_check'] != "check") {
	unset($_SESSION['admin_check']);
	session_destroy(); // 세션 아이디의 삭제
	echo "<script>alert('잘못된 접근입니다.');location.href='./index.php';</script>";
}


include("./config.php");
