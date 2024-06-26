<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
$member_idx = $_SESSION['member_coinc_idx'];
$my_member_row = get_member_data($_SESSION['member_coinc_idx']);
extract($_REQUEST);


if (md5($pw) != $my_member_row['user_pwd']) {
    echo "<script>alert('비밀번호가 일치하지 않습니다.');history.back();</script>";
} else {
    $query = "UPDATE member_info SET del_yn='Y' WHERE idx=$idx";
    $result = mysqli_query($gconnet, $query);
    echo "<script>alert('회원 탈퇴 처리 되었습니다.');location.href='./logout_action.php'</script>";
}
