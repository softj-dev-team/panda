<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
extract($_REQUEST);
$query = "UPDATE member_info SET user_name='$user_name', email='$email' WHERE idx=$idx";
$result = mysqli_query($gconnet, $query);

echo "<script>alert('변경이 완료 되었습니다.');location.href='./mypage.php';</script>";
