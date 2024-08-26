<?php

include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 

$idx = $_REQUEST['idx'];

$query = "select * from purchase_info where idx=$idx";
//echo "<br><br>쿼리 = ".$query."<br><Br>";
$result = mysqli_query($gconnet, $query);
$row = mysqli_fetch_assoc($result);

//var_dump($row);

/*
     ==========================================================================
          거래등록 API URL
     --------------------------------------------------------------------------
     */



//$target_URL = "https://stg-spl.kcp.co.kr/std/tradeReg/register"; //개발환경
$target_URL = "https://spl.kcp.co.kr/std/tradeReg/register"; //운영환경
/* 
    ==========================================================================
    요청 정보                                                          
    --------------------------------------------------------------------------
    */
$site_cd            = "AJSFA"; // 사이트코드
// 인증서정보(직렬화)
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
//$ordr_idxx          = "PAYSMS_" . $row['idx']; // 주문번호
$ordr_idxx         = $_REQUEST['ordr_idxx']; // 주문번호
$good_mny           = $row['price']; // 결제 금액
$good_name          = "판다문자 충전"; // 상품명
if ($row['purchase_type'] == "card") {
    $pay_method = "CARD";
} else if ($row['purchase_type'] == "acnt") {
    $pay_method = "BANK";
} else if ($row['purchase_type'] == "vcnt") {
    $pay_method = "VCNT";
}

$Ret_URL            = "http://pandasms.co.kr/pay_complete.php"; // 리턴 URL
/* ============================================================================== */
$actionResult       = $pay_method; // pay_method에 매칭되는 값 (인증창 호출 시 필요)
$van_code           = ""; // (포인트,상품권 인증창 호출 시 필요)

$data = array(
    "site_cd"        => $site_cd,
    "kcp_cert_info"  => $kcp_cert_info,
    "ordr_idxx"      => $ordr_idxx,
    "good_mny"       => $good_mny,
    "good_name"      => $good_name,
    "pay_method"     => $pay_method,
    "Ret_URL"        => $Ret_URL,
    "escw_used"      => "N",
    "user_agent"     => ""
);

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

/* 
    ==========================================================================
    거래등록 응답정보                                                               
    --------------------------------------------------------------------------
    */
$res_cd      = ""; // 응답코드
$res_msg     = ""; // 응답메세지
$approvalKey = ""; // 거래등록키
$traceNo     = ""; // 추적번호
$PayUrl      = ""; // 거래등록 PAY URL

// RES JSON DATA Parsing
$json_res = json_decode($res_data, true);

//var_dump($res_data);

$res_cd      = $json_res["Code"];
$res_msg     = $json_res["Message"];
$approvalKey = $json_res["approvalKey"];
$traceNo     = $json_res["traceNo"];
$PayUrl      = $json_res["PayUrl"];

curl_close($ch);

?>

<!DOCTYPE>
<html>

<head>
    <title>*** NHN KCP API SAMPLE ***</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <script type="text/javascript">
        function goReq() {
            <?
            // 거래등록 처리 정상
            if ($res_cd == "0000") {
            ?>
                //alert("거래등록 성공");
                document.form_trade_reg.action = "pay_apply.php";
                document.form_trade_reg.submit();
            <?
            }

            // 거래등록 처리 실패, 여기(샘플)에서는 trade_reg page로 리턴 합니다.
            else {
            ?>
                alert("에러 코드 : <?= $res_cd ?>, 에러 메세지 : <?= $res_msg ?>");
                location.href = "./pay.php";
            <?
            }
            ?>
        }
    </script>
</head>

<body onload="goReq();">
    <div class="wrap">
        <!--  거래등록 form : form_trade_reg -->
        <form name="form_trade_reg" method="post">
            <input type="hidden" name="site_cd" value="<?= $site_cd ?>" /> <!-- 사이트 코드 -->
            <input type="hidden" name="res_cd" value="<?= $res_cd ?>" /> <!-- 사이트 코드 -->
            <input type="hidden" name="ordr_idxx" value="<?= $ordr_idxx ?>" /><!-- 주문번호     -->
            <input type="hidden" name="good_mny" value="<?= $good_mny ?>" /> <!-- 결제금액     -->
            <input type="hidden" name="good_name" value="<?= $good_name ?>" /><!-- 상품명        -->
            <!-- 인증시 필요한 파라미터(변경불가)-->
            <input type="hidden" name="pay_method" value="<?= $pay_method ?>" />
            <input type="hidden" name="ActionResult" value="<?= $actionResult ?>" />
            <input type="hidden" name="van_code" value="<?= $van_code ?>" />
            <!-- 리턴 URL (kcp와 통신후 결제를 요청할 수 있는 암호화 데이터를 전송 받을 가맹점의 주문페이지 URL) -->
            <input type="hidden" name="Ret_URL" value="<?= $Ret_URL ?>" />
            <!-- 거래등록 응답 값 -->
            <input type="hidden" name="approvalKey" value="<?= $approvalKey ?>" />
            <input type="hidden" name="traceNo" value="<?= $traceNo ?>" />
            <input type="hidden" name="PayUrl" value="<?= $PayUrl ?>" />
        </form>
    </div>
    <!--//wrap-->
</body>

</html>