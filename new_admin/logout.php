<?php
header('Content-Type: text/html; charset=UTF-8');session_start();
unset($_SESSION['admin_check']);
unset($_SESSION['manager_authority']);
unset($_SESSION['manager_authority_info']);
session_destroy(); // 세션 아이디의 삭제
echo "<script>location.href='./index.php';</script>";
