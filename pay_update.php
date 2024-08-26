<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 

extract($_REQUEST);

var_dump($_REQUEST);

$price_val = 0;
if ($price == -1) {
    $price_val = $price_other;
} else {
    $price_val = $price;
}

$query = "INSERT purchase_info SET purchase_type = '$purchase_type', price = $price_val";
$result = mysqli_query($gconnet, $query);
$row = mysqli_fetch_array($result);

$lastInsertId = $gconnet->insert_id;

//var_dump($lastInsertId);

$ordr_idxx = make_order_num("order_member") . "_" . $lastInsertId;


$userAgent = $_SERVER['HTTP_USER_AGENT'];

if (strpos($userAgent, 'Mobile') !== false || strpos($userAgent, 'iPhone') !== false) {
    echo "<script>location.href='./pay_purchase.php?idx=" . $lastInsertId . "&ordr_idxx=" . $ordr_idxx . "';</script>";
} else {
    echo "<script>location.href='./pay_request.php?idx=" . $lastInsertId . "&ordr_idxx=" . $ordr_idxx . "';</script>";
}
