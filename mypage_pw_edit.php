<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
$member_idx = $_SESSION['member_coinc_idx'];
$my_member_row = get_member_data($_SESSION['member_coinc_idx']);
extract($_REQUEST);


if (md5($now_pw) != $my_member_row['user_pwd']) {
    echo "<script>alert('현재 비밀번호가 일치하지 않습니다.');history.back();</script>";
} else {
    if (trim($new_pw) != trim($new_pw_confirm)) {
        echo "<script>alert('새 비밀번호가 일치하지 않습니다.');history.back();</script>";
    } else {
        $pw = md5($new_pw);
        $query = "UPDATE member_info SET user_pwd='$pw' WHERE idx=$idx";
        $result = mysqli_query($gconnet, $query);
        echo "<script>alert('변경이 완료 되었습니다.');location.href='./mypage03.php';</script>";
    }
}
