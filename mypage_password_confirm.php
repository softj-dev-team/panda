<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
$member_idx = $_SESSION['member_coinc_idx'];
$my_member_row = get_member_data($_SESSION['member_coinc_idx']);

if (md5($_REQUEST['pw']) == $my_member_row['user_pwd']) {
    echo "<script>location.href='./mypage02.php'</script>";
} else {
    echo "<script>alert('비밀번호가 일치하지 않습니다.');history.back();</script>";
}
