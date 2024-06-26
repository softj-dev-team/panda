<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 

$member_idx = $_SESSION['member_coinc_idx'];


$query = "SELECT * FROM member_info WHERE idx = $member_idx";
//echo "<br><br>쿼리 = ".$query."<br><Br>";
$result = mysqli_query($gconnet, $query);
$row_member = mysqli_fetch_assoc($result);

$idx = $_GET['idx'];

$query = "select * from purchase_info where idx=$idx";
//echo "<br><br>쿼리 = ".$query."<br><Br>";
$result = mysqli_query($gconnet, $query);
$row = mysqli_fetch_assoc($result);
//$ordr_idxx          = "PAYSMS_" . $row['idx']; // 주문번호
$ordr_idxx = $_REQUEST['ordr_idxx']; // 주문번호
$good_mny           = $row['price']; // 결제 금액

if ($row['purchase_type'] == "card") {
    $pay_method = "100000000000";
    $pay_method_name = "신용카드";
} else if ($row['purchase_type'] == "acnt") {
    $pay_method = "010000000000";
    $pay_method_name = "계좌이체";
} else if ($row['purchase_type'] == "vcnt") {
    $pay_method = "001000000000";
    $pay_method_name = "가상계좌";
}


?>
<? include "./common/head.php"; ?>
<script type="text/javascript">
    /****************************************************************/
    /* m_Completepayment  설명                                      */
    /****************************************************************/
    /* 인증완료시 재귀 함수                                         */
    /* 해당 함수명은 절대 변경하면 안됩니다.                        */
    /* 해당 함수의 위치는 payplus.js 보다먼저 선언되어여 합니다.    */
    /* Web 방식의 경우 리턴 값이 form 으로 넘어옴                   */
    /****************************************************************/
    function m_Completepayment(FormOrJson, closeEvent) {
        var frm = document.order_info;

        /********************************************************************/
        /* FormOrJson은 가맹점 임의 활용 금지                               */
        /* frm 값에 FormOrJson 값이 설정 됨 frm 값으로 활용 하셔야 됩니다.  */
        /* FormOrJson 값을 활용 하시려면 기술지원팀으로 문의바랍니다.       */
        /********************************************************************/
        GetField(frm, FormOrJson);


        if (frm.res_cd.value == "0000") {
            frm.submit();
            //console.log(frm);
        } else {
            alert("[" + frm.res_cd.value + "] " + frm.res_msg.value);

            closeEvent();
        }
    }
</script>
<!--
            결제창 호출 JS
             개발 : https://testpay.kcp.co.kr/plugin/payplus_web.jsp
             운영 : https://pay.kcp.co.kr/plugin/payplus_web.jsp
    -->
<script type="text/javascript" src="https://pay.kcp.co.kr/plugin/payplus_web.jsp"></script>
<script type="text/javascript">
    /* 표준웹 실행 */
    function jsf__pay(form) {
        try {
            KCP_Pay_Execute(form);
        } catch (e) {
            /* IE 에서 결제 정상종료시 throw로 스크립트 종료 */
        }
    }
</script>

<body>
    <div><? include "./common/header.php"; ?></div>
    <section class="sub">
        <form name="order_info" method="post" action="./pay_complete.php">
            <input type="hidden" name="idx" value="<?= $_GET['idx'] ?>" />
            <div class="wrap">
                <!-- header -->
                <div class="header">
                    <h1 class="title">주문/결제 내역 확인</h1>
                </div>
                <!-- //header -->
                <!-- contents -->
                <div id="skipCont" class="contents">
                    <!-- 주문내역 -->
                    <h2 class="title-type-3">주문내역</h2>
                    <div class="emt20"></div>
                    <ul class="list-type-1">
                        <!-- 주문번호(ordr_idxx) -->
                        <li>
                            <div class="left">
                                <p class="title">주문번호</p>
                            </div>
                            <div class="right">
                                <div class="ipt-type-1 pc-wd-2">
                                    <input type="text" name="ordr_idxx" value="<?= $ordr_idxx ?>" maxlength="40" readonly />
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
                                    <input type="text" name="good_name" value="판다문자 충전" readonly />
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
                                    <input type="text" name="good_mny" value="<?= $good_mny ?>" maxlength="9" readonly />
                                    <span class="txt-price">원</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="line-type-1"></div>
                    <!-- 주문정보 -->
                    <div class="emt20"></div>
                    <h2 class="title-type-3">주문정보</h2>
                    <div class="emt20"></div>
                    <ul class="list-type-1">
                        <!-- 주문자명(buyr_name) -->
                        <li>
                            <div class="left">
                                <p class="title">주문자명</p>
                            </div>
                            <div class="right">
                                <div class="ipt-type-1 pc-wd-2">
                                    <input type="text" name="buyr_name" value="<?= $row_member['user_name'] ?>" />
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
                                    <input type="text" name="buyr_tel2" value="<?= $row_member['cell'] ?>" />
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
                                    <input type="text" name="buyr_mail" value="<?= $row_member['email'] ?>" />
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="line-type-1"></div>
                    <!-- 
                                결제 수단 정보 설정 
                    
                                결제에 필요한 결제 수단 정보를 설정합니다.                               
                                                                                          
                                신용카드 : 100000000000, 계좌이체 : 010000000000, 가상계좌 : 001000000000 
                                포인트   : 000100000000, 휴대폰   : 000010000000, 상품권   : 000000001000
                    
                                위와 같이 설정한 경우 표준웹에서 설정한 결제수단이 표시됩니다.
                                표준웹에서 여러 결제수단을 표시하고 싶으신 경우 설정하시려는 결제
                                수단에 해당하는 위치에 해당하는 값을 1로 변경하여 주십시오.
                                                                                               
                                예) 신용카드, 계좌이체, 가상계좌를 동시에 표시하고자 하는 경우
                    pay_method = "111000000000"
                                 신용카드(100000000000), 계좌이체(010000000000), 가상계좌(001000000000)에
                                 해당하는 값을 모두 더해주면 됩니다.
                                     ※ 필수
                     KCP에 신청된 결제수단으로만 결제가 가능합니다.        
                -->
                    <div class="emt20"></div>
                    <h2 class="title-type-3">결제수단</h2>
                    <div class="emt20"></div>
                    <ul class="list-check-1">
                        <li>
                            <input type="radio" id="radio-2-1" class="input_radio ipt-radio-1" name="pay_method" value="<?= $pay_method ?>" checked />
                            <label for="radio-2-1"><span class="ico-radio"><span></span></span><?= $pay_method_name ?></label>
                        </li>
                    </ul>
                    <div class="header btn_pry">
                        <a href="#" onclick="jsf__pay(document.order_info);" class="btn02 btn btn-type-2 pc-wd-3 btn btn-primary w-100 mt-3"><span>결제요청</span></a>
                    </div>
                </div>
                <!-- //contents -->

                <!-- 가맹점 정보 설정-->
                <input type="hidden" name="site_cd" value="AJSFA" />
                <input type="hidden" name="site_name" value="판다문자" />
                <!--<input type="hidden" name="pay_method" value="" />-->
                <!-- 
                            ※필수 항목
                             표준웹에서 값을 설정하는 부분으로 반드시 포함되어야 합니다.값을 설정하지 마십시오
            -->
                <input type="hidden" name="res_cd" value="" />
                <input type="hidden" name="res_msg" value="" />
                <input type="hidden" name="enc_info" value="" />
                <input type="hidden" name="enc_data" value="" />
                <input type="hidden" name="ret_pay_method" value="" />
                <input type="hidden" name="tran_cd" value="" />
                <input type="hidden" name="use_pay_method" value="" />
                <!-- 주문정보 검증 관련 정보 : 표준웹 에서 설정하는 정보입니다 -->
                <input type="hidden" name="ordr_chk" value="" />
                <!--  현금영수증 관련 정보 : 표준웹 에서 설정하는 정보입니다 -->
                <input type="hidden" name="cash_yn" value="" />
                <input type="hidden" name="cash_tr_code" value="" />
                <input type="hidden" name="cash_id_info" value="" />

                <!-- 
                ====================================================
                                 추가 옵션 정보
                                ※ 옵션 - 결제에 필요한 추가 옵션 정보를 입력 및 설정합니다. 
                ====================================================
            -->

                <!--사용카드 설정 여부 파라미터 입니다.(통합결제창 노출 유무) -->
                <!-- <input type="hidden" name="used_card_YN"        value="Y" /> -->
                <!-- 사용카드 설정 파라미터 입니다. (해당 카드만 결제창에 보이게 설정하는 파라미터입니다. used_card_YN 값이 Y일때 적용됩니다. -->
                <!-- <input type="hidden" name="used_card"        value="CCBC:CCKM:CCSS" /> -->

                <!--
                           신용카드 결제시 OK캐쉬백 적립 여부를 묻는 창을 설정하는 파라미터 입니다
                            포인트 가맹점의 경우에만 창이 보여집니다
            -->
                <!-- <input type="hidden" name="save_ocb"        value="Y" /> -->

                <!-- 고정 할부 개월 수 선택
                value값을 "7" 로 설정했을 경우 => 카드결제시 결제창에 할부 7개월만 선택가능  -->
                <!-- <input type="hidden" name="fix_inst"        value="07" /> -->

                <!-- 무이자 옵션
                    ※ 설정할부    (가맹점 관리자 페이지에 설정 된 무이자 설정을 따른다) - "" 로 설정
                    ※ 일반할부    (KCP 이벤트 이외에 설정 된 모든 무이자 설정을 무시한다) - "N" 로 설정
                    ※ 무자 할부 (가맹점 관리자 페이지에 설정 된 무이자 이벤트 중 원하는 무이자 설정을 세팅한다) - "Y" 로 설정 -->
                <!-- <input type="hidden" name="kcp_noint"       value="" /> -->

                <!-- 무이자 설정
                    ※ 주의 1 : 할부는 결제금액이 50,000 원 이상일 경우에만 가능
                    ※ 주의 2 : 무이자 설정값은 무이자 옵션이 Y일 경우에만 결제 창에 적용
                    예) BC 2,3,6개월, 국민 3,6개월, 삼성 6,9개월 무이자 : CCBC-02:03:06,CCKM-03:06,CCSS-03:06:04 -->
                <!-- <input type="hidden" name="kcp_noint_quota" value="CCBC-02:03:06,CCKM-03:06,CCSS-03:06:09" /> -->


                <!--  해외카드 구분하는 파라미터 입니다.(해외비자, 해외마스터, 해외JCB로 구분하여 표시) -->
                <!-- <input type="hidden" name="used_card_CCXX"        value="Y"/> -->

                <!--  가상계좌 은행 선택 파라미터
                 ※ 해당 은행을 결제창에서 보이게 합니다.(은행코드는 매뉴얼을 참조)  -->
                <!-- <input type="hidden" name="wish_vbank_list" value="05:03:04:07:11:23:26:32:34:81:71" /> -->

                <!--  가상계좌 입금 기한 설정하는 파라미터 - 발급일 + 3일 -->
                <!-- <input type="hidden" name="vcnt_expire_term" value="3"/> -->

                <!-- 가상계좌 입금 시간 설정하는 파라미터
                HHMMSS형식으로 입력하시기 바랍니다
                          설정을 안하시는경우 기본적으로 23시59분59초가 세팅이 됩니다 -->
                <!-- <input type="hidden" name="vcnt_expire_term_time" value="120000" /> -->

                <!-- 포인트 결제시 복합 결제(신용카드+포인트) 여부를 결정할 수 있습니다.- N 일경우 복합결제 사용안함 -->
                <!-- <input type="hidden" name="complex_pnt_yn" value="N" /> -->

                <!-- 현금영수증 등록 창을 출력 여부를 설정하는 파라미터 입니다
                       ※ Y : 현금영수증 등록 창 출력
                       ※ N : 현금영수증 등록 창 출력 안함 
                       ※ 주의 : 현금영수증 사용 시 KCP 상점관리자 페이지에서 현금영수증 사용 동의를 하셔야 합니다 -->
                <!-- <input type="hidden" name="disp_tax_yn"     value="Y" /> -->

                <!--  결제창에 가맹점 사이트의 로고를 표준웹 좌측 상단에 출력하는 파라미터 입니다
                      업체의 로고가 있는 URL을 정확히 입력하셔야 하며, 최대 150 X 50  미만 크기 지원
                      ※ 주의 : 로고 용량이 150 X 50 이상일 경우 site_name 값이 표시됩니다. -->
                <!-- <input type="hidden" name="site_logo"       value="" /> -->

                <!-- 결제창 영문 표시 파라미터 입니다. 영문을 기본으로 사용하시려면 Y로 세팅하시기 바랍니다 -->
                <!-- <input type="hidden" name="eng_flag"      value="Y"> -->

                <!--  KCP는 과세상품과 비과세상품을 동시에 판매하는 업체들의 결제관리에 대한 편의성을 제공해드리고자, 
                    복합과세 전용 사이트코드를 지원해 드리며 총 금액에 대해 복합과세 처리가 가능하도록 제공하고 있습니다
                    복합과세 전용 사이트 코드로 계약하신 가맹점에만 해당이 됩니다
                    상품별이 아니라 금액으로 구분하여 요청하셔야 합니다
                    총결제 금액은 과세금액 + 부과세 + 비과세금액의 합과 같아야 합니다. 
                (good_mny = comm_tax_mny + comm_vat_mny + comm_free_mny) -->
                <!-- <input type="hidden" name="tax_flag"       value="TG03" /> --> <!-- 변경불가     -->
                <!-- <input type="hidden" name="comm_tax_mny"   value=""     /> --> <!-- 과세금액     -->
                <!-- <input type="hidden" name="comm_vat_mny"   value=""     /> --> <!-- 부가세      -->
                <!-- <input type="hidden" name="comm_free_mny"  value=""     /> --> <!-- 비과세 금액 -->

                <!--  skin_indx 값은 스킨을 변경할 수 있는 파라미터이며 총 7가지가 지원됩니다. 
                     변경을 원하시면 1부터 7까지 값을 넣어주시기 바랍니다. -->
                <!-- <input type="hidden" name="skin_indx"      value="1" /> -->
                <!-- 상품코드 설정 파라미터 입니다.(상품권을 따로 구분하여 처리할 수 있는 옵션기능입니다.) -->
                <!-- <input type="hidden" name="good_cd"      value="" /> -->

                <!-- 가맹점에서 관리하는 고객 아이디 설정을 해야 합니다. 상품권 결제 시 반드시 입력하시기 바랍니다. -->
                <!-- <input type="hidden" name="shop_user_id"    value="" /> -->

                <!--  복지포인트 결제시 가맹점에 할당되어진 코드 값을 입력해야합니다. -->
                <!-- <input type="hidden" name="pt_memcorp_cd"   value="" /> -->

                <!--  결제창의 상단문구를 변경할 수 있는 파라미터 입니다. -->
                <!-- <input type="hidden" name="kcp_pay_title"   value="상단문구추가" /> -->
            </div>
        </form>
    </section>
</body>

</html>