<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 

extract($_REQUEST);

var_dump($_REQUEST);

$price_val = $price_other;

$query = "INSERT purchase_info SET purchase_type = '$purchase_type', price = $price_val";
$result = mysqli_query($gconnet, $query);
$row = mysqli_fetch_array($result);

$lastInsertId = $gconnet->insert_id;

var_dump($lastInsertId);


echo "<script>location.href='./pay_request.php?idx=" . $lastInsertId . "';</script>";
