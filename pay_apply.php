<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 

$member_idx = $_SESSION['member_coinc_idx'];


$query = "SELECT * FROM member_info WHERE idx = $member_idx";
//echo "<br><br>쿼리 = ".$query."<br><Br>";
$result = mysqli_query($gconnet, $query);
$row = mysqli_fetch_assoc($result);



// 거래등록 응답 값
$approvalKey    = $_POST["approvalKey"]; // 거래등록키
$traceNo        = $_POST["traceNo"]; // 추적번호
$PayUrl         = $_POST["PayUrl"]; // 거래등록 PAY URL
// 인증시 필요한 결제수단 세팅 값
$pay_method     = $_POST["pay_method"]; // 결제수단
$actionResult   = $_POST["actionResult"];
$van_code       = $_POST["van_code"];
// 가맹점 리턴 URL
$Ret_URL        = $_POST["Ret_URL"];

/* kcp와 통신후 kcp 서버에서 전송되는 결제 요청 정보 */
$req_tx          = $_POST["req_tx"]; // 요청 종류
$res_cd          = $_POST["res_cd"]; // 응답 코드
$site_cd         = $_POST["site_cd"]; // 사이트 코드
$tran_cd         = $_POST["tran_cd"]; // 트랜잭션 코드
$ordr_idxx       = $_POST["ordr_idxx"]; // 쇼핑몰 주문번호
$good_name       = $_POST["good_name"]; // 상품명
$good_mny        = $_POST["good_mny"]; // 결제 총금액
$buyr_name       = $_POST["buyr_name"]; // 주문자명
$buyr_tel1       = $_POST["buyr_tel1"]; // 주문자 전화번호
$buyr_tel2       = $_POST["buyr_tel2"]; // 주문자 핸드폰 번호
$buyr_mail       = $_POST["buyr_mail"]; // 주문자 E-mail 주소
$use_pay_method  = $_POST["use_pay_method"]; // 결제 방법
$enc_info        = $_POST["enc_info"]; // 암호화 정보
$enc_data        = $_POST["enc_data"]; // 암호화 데이터
$cash_yn         = $_POST["cash_yn"];
$cash_tr_code    = $_POST["cash_tr_code"];
/* 기타 파라메터 추가 부분 - Start - */
$param_opt_1    = $_POST["param_opt_1"]; // 기타 파라메터 추가 부분
$param_opt_2    = $_POST["param_opt_2"]; // 기타 파라메터 추가 부분
$param_opt_3    = $_POST["param_opt_3"]; // 기타 파라메터 추가 부분
/* 기타 파라메터 추가 부분 - End -   */


//var_dump($_SERVER['HTTP_USER_AGENT']);
?>

<? include "./common/head.php"; ?>



<body onload="chk_pay();">

    <div><? include "./common/header.php"; ?></div>

    <section class="sub">
        <div class="wrap container">

            <!-- 주문정보 입력 form : order_info -->
            <form name="order_info" method="post">
                <!-- contents -->
                <div id="skipCont" class="contents">
                    <!-- 주문정보 -->
                    <h2 class="title-type-3">최종 주문정보 확인</h2>
                    <ul class="list-type-1">
                        <!-- 주문번호(ordr_idxx) -->
                        <li>
                            <div class="left">
                                <p class="title">주문번호</p>
                            </div>
                            <div class="right">
                                <div class="ipt-type-1 pc-wd-2">
                                    <input type="text" class="form-control" name="ordr_idxx" value="<?= $ordr_idxx ?>" maxlength="40" readonly />
                                </div>
                            </div>
                        </li>
                        <!-- 상품명(good_name) -->
                        <li>
                            <div class="left">
                                <p class="title">상품명</p>
                            </div>
                            <div class="right">
                                <div class="ipt-type-1 pc-wd-2">
                                    <input type="text" class="form-control" name="good_name" value="<?= $good_name ?>" readonly />
                                </div>
                            </div>
                        </li>
                        <!-- 결제금액(good_mny) - ※ 필수 : 값 설정시 ,(콤마)를 제외한 숫자만 입력하여 주십시오. -->
                        <li>
                            <div class="left">
                                <p class="title">상품금액</p>
                            </div>
                            <div class="right">
                                <div class="ipt-type-1 gap-2 pc-wd-2">
                                    <input type="text" class="form-control" name="good_mny" value="<?= $good_mny ?>" maxlength="9" readonly />
                                </div>
                            </div>
                        </li>
                        <!-- 주문자명(buyr_name) -->
                        <li>
                            <div class="left">
                                <p class="title">주문자명</p>
                            </div>
                            <div class="right">
                                <div class="ipt-type-1 pc-wd-2">
                                    <input type="text" class="form-control" name="buyr_name" value="<?= $row['user_name'] ?>" />
                                </div>
                            </div>
                        </li>
                        <!-- 주문자 연락처1(buyr_tel1) -->
                        <li>
                            <div class="left">
                                <p class="title">전화번호</p>
                            </div>
                            <div class="right">
                                <div class="ipt-type-1 pc-wd-2">
                                    <input type="text" class="form-control" name="buyr_tel1" value="<?= $row['cell'] ?>" />
                                </div>
                            </div>
                        </li>
                        <!-- 휴대폰번호(buyr_tel2) -->
                        <li>
                            <div class="left">
                                <p class="title">휴대폰번호</p>
                            </div>
                            <div class="right">
                                <div class="ipt-type-1 pc-wd-2">
                                    <input type="text" class="form-control" name="buyr_tel2" value="<?= $row['cell'] ?>" />
                                </div>
                            </div>
                        </li>
                        <!-- 주문자 E-mail(buyr_mail) -->
                        <li>
                            <div class="left">
                                <p class="title">이메일</p>
                            </div>
                            <div class="right">
                                <div class="ipt-type-1 pc-wd-2">
                                    <input type="text" class="form-control" name="buyr_mail" value="<?= $row['email'] ?>" />
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="header btn_pry">
                        <a href="#" onclick="call_pay_form();" class="btn02 btn btn-type-2 pc-wd-3 btn btn-primary w-100 mt-3"><span>결제요청</span></a>
                    </div>
                </div>
                <!-- //contents -->

                <!-- 공통정보 -->
                <input type="hidden" name="req_tx" value="pay" /> <!-- 요청 구분 -->
                <input type="hidden" name="shop_name" value="판다문자" /> <!-- 사이트 이름 -->
                <input type="hidden" name="site_cd" value="<?= $site_cd ?>" /> <!-- 사이트 코드 -->
                <input type="hidden" name="currency" value="410" /> <!-- 통화 코드 -->
                <!-- 인증시 필요한 파라미터(변경불가)-->
                <input type="hidden" name="escw_used" value="N" />
                <input type="hidden" name="pay_method" value="<?= $pay_method ?>" />
                <input type="hidden" name="ActionResult" value="<?= $actionResult ?>" />
                <input type="hidden" name="van_code" value="<?= $van_code ?>" />
                <!-- 신용카드 설정 -->
                <input type="hidden" name="quotaopt" value="12" /> <!-- 최대 할부개월수 -->
                <!-- 가상계좌 설정 -->
                <input type="hidden" name="ipgm_date" value="" />
                <!-- 리턴 URL (kcp와 통신후 결제를 요청할 수 있는 암호화 데이터를 전송 받을 가맹점의 주문페이지 URL) -->
                <input type="hidden" name="Ret_URL" value="<?= $Ret_URL ?>" />
                <!-- 화면 크기조정 -->
                <input type="hidden" name="tablet_size" value="1.0 " />
                <!-- 추가 파라미터 ( 가맹점에서 별도의 값전달시 param_opt 를 사용하여 값 전달 ) -->
                <input type="hidden" name="param_opt_1" value="" />
                <input type="hidden" name="param_opt_2" value="" />
                <input type="hidden" name="param_opt_3" value="" />
                <!-- 거래등록 응답값 -->
                <input type="hidden" name="approval_key" id="approval" value="<?= $approvalKey ?>" />
                <input type="hidden" name="traceNo" value="<?= $traceNo ?>" />
                <input type="hidden" name="PayUrl" value="<?= $PayUrl ?>" />
                <input type="hidden" name="encoding_trans" value="" />
                <input type="hidden" name="AppUrl" value="">
                <!-- 인증창 호출 시 한글깨질 경우 encoding 처리 추가 (**인코딩 네임은 대문자) -->


            </form>
        </div>
        <form name="pay_form" method="post" action="./pay_complete.php">
            <input type="hidden" name="req_tx" value="<?= $req_tx ?>" /> <!-- 요청 구분          -->
            <input type="hidden" name="res_cd" value="<?= $res_cd ?>" /> <!-- 결과 코드          -->
            <input type="hidden" name="site_cd" value="<?= $site_cd ?>" /> <!-- 사이트 코드      -->
            <input type="hidden" name="tran_cd" value="<?= $tran_cd ?>" /> <!-- 트랜잭션 코드      -->
            <input type="hidden" name="ordr_idxx" value="<?= $ordr_idxx ?>" /> <!-- 주문번호           -->
            <input type="hidden" name="good_mny" value="<?= $good_mny ?>" /> <!-- 휴대폰 결제금액    -->
            <input type="hidden" name="good_name" value="<?= $good_name ?>" /> <!-- 상품명             -->
            <input type="hidden" name="buyr_name" value="<?= $buyr_name ?>" /> <!-- 주문자명           -->
            <input type="hidden" name="buyr_tel2" value="<?= $buyr_tel2 ?>" /> <!-- 주문자 휴대폰번호  -->
            <input type="hidden" name="buyr_mail" value="<?= $buyr_mail ?>" /> <!-- 주문자 E-mail      -->
            <input type="hidden" name="enc_info" value="<?= $enc_info ?>" />
            <input type="hidden" name="enc_data" value="<?= $enc_data ?>" />
            <input type="hidden" name="use_pay_method" value="<?= $use_pay_method ?>" />
            <input type="hidden" name="cash_yn" value="<?= $cash_yn ?>" /> <!-- 현금영수증 등록여부-->
            <input type="hidden" name="cash_tr_code" value="<?= $cash_tr_code ?>" />
            <!-- 추가 파라미터 -->
            <input type="hidden" name="param_opt_1" value="<?= $param_opt_1 ?>" />
            <input type="hidden" name="param_opt_2" value="<?= $param_opt_2 ?>" />
            <input type="hidden" name="param_opt_3" value="<?= $param_opt_3 ?>" />
        </form>
    </section>
    <script type="text/javascript">
        /* kcp web 결제창 호츨 (변경불가) */
        function call_pay_form() {
            var v_frm = document.order_info;
            var PayUrl = v_frm.PayUrl.value;
            // 인코딩 방식에 따른 변경 -- Start
            if (v_frm.encoding_trans == undefined) {
                v_frm.action = PayUrl;
            } else {
                // encoding_trans "UTF-8" 인 경우
                if (v_frm.encoding_trans.value == "UTF-8") {
                    v_frm.action = PayUrl.substring(0, PayUrl.lastIndexOf("/")) + "/jsp/encodingFilter/encodingFilter.jsp";
                    v_frm.PayUrl.value = PayUrl;
                } else {
                    v_frm.action = PayUrl;
                }
            }

            if (v_frm.Ret_URL.value == "") {
                /* Ret_URL값은 현 페이지의 URL 입니다. */
                alert("연동시 Ret_URL을 반드시 설정하셔야 됩니다.");
                return false;
            } else {
                v_frm.submit();
            }
        }

        /* kcp 통신을 통해 받은 암호화 정보 체크 후 결제 요청 (변경불가) */
        function chk_pay() {
            self.name = "tar_opener";
            var pay_form = document.pay_form;

            if (pay_form.res_cd.value != "") {
                if (pay_form.res_cd.value != "0000") {
                    if (pay_form.res_cd.value == "3001") {
                        alert("사용자가 취소하였습니다.");
                    }
                    pay_form.res_cd.value = "";
                    location.href = "./pay.php";
                }
            }
            if (pay_form.enc_info.value)
                pay_form.submit();
        }
    </script>

</body>

</html>