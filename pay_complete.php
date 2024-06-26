<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 

var_dump($_REQUEST);

$string = $_REQUEST['ordr_idxx'];
preg_match('/(\d+$)/', $string, $matches);
$number = $matches[0];

$target_URL = "https://spl.kcp.co.kr/gw/enc/v1/payment"; // 승인요청 개발서버
$kcp_cert_info      = "-----BEGIN CERTIFICATE-----
MIIDjDCCAnSgAwIBAgIHBzAATl/LwDANBgkqhkiG9w0BAQsFADBzMQswCQYDVQQG
EwJLUjEOMAwGA1UECAwFU2VvdWwxEDAOBgNVBAcMB0d1cm8tZ3UxFTATBgNVBAoM
DE5ITktDUCBDb3JwLjETMBEGA1UECwwKSVQgQ2VudGVyLjEWMBQGA1UEAwwNc3Bs
LmtjcC5jby5rcjAeFw0yMzEwMjcwNjEwMzlaFw0yODEwMjUwNjEwMzlaMHsxCzAJ
BgNVBAYTAktSMQ4wDAYDVQQIDAVTZW91bDEQMA4GA1UEBwwHR3Vyby1ndTEWMBQG
A1UECgwNTkhOIEtDUCBDb3JwLjEXMBUGA1UECwwOUEdXRUJERVYgVGVhbS4xGTAX
BgNVBAMMEDIwMjMxMDI3MTAwMDY0MTUwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAw
ggEKAoIBAQCBjicXyuozshTv4XOVQ31s3eaiAYOBqwmI3qep4d1vFru3/U9Mco2A
Ej13Ktzdj3adqrD1o3uv3L1hacNhaiuSXnZs21GesE/GCU1iSpRUJCc+h1hfKE49
IN1W6OCdnCHiPgFpRWsu7xH2F6+J4gh6761LWcSu9bGx/MXlBdWZ1DtOmnrRZYI4
0cSOoeS8kp41NaQ/1ibyYCukgkhpn48XYYuaj7UlqNH98GakYi0kBi1tl87DgV7E
Kun+4VLzwmkq5QxiGd32htIR4BDx1cc28hOFdzgnDE49XX6BEdiNwpj2ffTB1woi
lc6T3OoF0nm+dHQF/wK6/s6cZSjZhSfnAgMBAAGjHTAbMA4GA1UdDwEB/wQEAwIH
gDAJBgNVHRMEAjAAMA0GCSqGSIb3DQEBCwUAA4IBAQCE9JBihVN0FcuYPqtKRTR1
0fYvlh+vjD+6sQy7/+kI4cCPBgXKW4IZDp3ES1AWdzxZ/CRf/xxgfPZJjue7guyK
u1KyunW5NOE6BVUs99V4P5mH0KuH2fltnquahbRsQUYzToTPGLNq2hdQn2FzEGQl
tLWUEXZthubHiEDheY3UzntT8u8mIfrylGxw32xDNkGJ33VjTvrQiiCMaFzNkapa
vBlQMJQt2Sfk3KaO8/TRGs1LWui8Wcyc81byHVv/ziOC9S4lZU6x212P4WKlM4mM
nj39mAYleWfofiVCIZ+2DRiVCreFSQiRY7+GOpFiQz7FclUUq/Dn6cGRdeaKQONf
-----END CERTIFICATE-----";
$data = [
    'tran_cd'        => $_REQUEST['tran_cd'],
    'site_cd'        => $_REQUEST['site_cd'],
    'kcp_cert_info'  => $kcp_cert_info,
    'enc_data'       => $_REQUEST['enc_data'],
    'enc_info'       => $_REQUEST['enc_info'],
    'ordr_mony'      => $_REQUEST['good_mny']
];

$req_data = json_encode($data);

$header_data = array("Content-Type: application/json", "charset=utf-8");

// API REQ
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $target_URL);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $req_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// API RES
$res_data  = curl_exec($ch);

// RES JSON DATA Parsing
$json_res = json_decode($res_data, true);

$res_cd = $json_res["res_cd"];
$res_msg = $json_res["res_msg"];

curl_close($ch);
echo "<br/><br/>";
var_dump($res_data);

//echo $number; // 출력 결과: 2

if ($res_cd == "0000") {
    if ($json_res["pay_method"] != "PAVC") {
        $tno = $json_res["tno"];
        $query = "UPDATE purchase_info SET is_purchase='Y', ordr_idxx='$string', tno='$tno' WHERE idx=$number";
        $result = mysqli_query($gconnet, $query);

        $member_idx = $_SESSION['member_coinc_idx'];
        $price_total = $_REQUEST['good_mny'];
        $point_sect = "smspay"; // sms 충전 
        $mile_title = $price_total . " 충전"; // 포인트  적립 내역
        $mile_sect = "A"; // 포인트  종류 = A : 적립, P : 대기, M : 차감
        $order_num = $string;
        $contents_idx = coin_plus_minus($point_sect, $member_idx, $mile_sect, $price_total, $mile_title, $order_num, $price_total, "", "", "", "");

        echo "<script>alert('결제가 성공적으로 이루어졌습니다.');location.href='./pay.php';</script>";
    } else {
        $tno = $json_res["tno"];
        $bankname = $json_res["bankname"];
        $account = $json_res["account"];
        $depositor = $json_res["depositor"];
        $va_name = $json_res["va_name"];
        $query = "UPDATE purchase_info SET ordr_idxx='$string', tno='$tno', bankname='$bankname', account='$account', depositor='$depositor', va_name='$va_name'  WHERE idx=$number";
        $result = mysqli_query($gconnet, $query);
        echo "<script>alert('가상계좌 번호로 최종 입금해주셔야 충전이 완료됩니다.');location.href='./pay_vcnt.php?idx=$number';</script>";
    }
} else {
    echo "<script>alert('결제에 실패했습니다. 다시 시도해주세요.');location.href='./pay.php';</script>";
}
