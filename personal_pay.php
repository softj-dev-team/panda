<? include "./common/head.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login.php"; // 공통함수 인클루드 
?>
<?php
$member_idx = $_SESSION['member_coinc_idx'];
$my_member_row = get_member_data($_SESSION['member_coinc_idx']);

$order_num = make_order_num("order_member");
?>

<style>
    ul#pay_price li #price_other {
        display: block;
    }
</style>

<body>

    <!--header-->
    <div><? include "./common/header.php"; ?></div>

    <!--content-->



    <section class="sub">
        <h1 style="font-size:2.4rem;">개인결제</h1>
        <div class="sub_title">

            <h2>문자단가</h2>
        </div>

        <div class="pay">
            <table>
                <tr>
                    <th>SMS-<span>단문</span></th>
                    <th>LMS-<span>장문</span></th>
                    <th>MMS-<span>포토</span></th>
                </tr>
                <tr>
                    <td><?= $my_member_row['mb_short_fee'] ?><span>원</span></td>
                    <td><?= $my_member_row['mb_long_fee'] ?><span>원</span></td>
                    <td><?= $my_member_row['mb_img_fee'] ?><span>원</span></td>
                </tr>
            </table>
        </div>

        <div class="emt30"></div>

        <!--
        
-->

        <!--<form action="./pay_update.php" method="post">-->
        <form name="frm_pay" id="frm_pay" method="post" enctype="multipart/form-data">
            <input type="hidden" name="order_num" id="order_num" value="<?= $order_num ?>">
            <div class="sub_title nomargin">
                <h2>결제 방식</h2>
            </div>
            <div class="emt20"></div>
            <div class="pay_select">
                <select name="purchase_type" id="purchase_type" required="yes" message="결제방식" onchange="pay_sel_1(this)">
                    <option value="card">신용카드</option>
                    <option value="acnt">계좌이체</option>
                    <option value="vcnt">가상계좌</option>
                    <!--
                    <option value="dirbank">무통장</option>
                    -->
                </select>
            </div>

            <div class="paybox" id="no_pg_acount" style="display:none;">
                <ul>
                    <li>
                        <h2><b><?= $inc_confg_bank_num ?> (<?= $inc_confg_bank_name ?>)</b></h2>
                        <h2>예금주 : <?= $inc_confg_bank_owner ?></h2>
                    </li>
                    <li>
                        <div class="tlb_flex_sms">
                            <span>입금자명 :</span>
                            <input type="text" class="half" name="pay_bank_depositor" id="pay_bank_depositor" required="no" message="입금자명"><span>무통장 입금 시 입금자명에 아이디를 적으시면 충전과정이 빠르게 처리됩니다.</span>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="emt30"></div>

            <div class="sub_title nomargin">
                <h2>결제 금액</h2>
            </div>
            <div class="emt20"></div>

            <div>
                <ul id="pay_price" class="pay_price_flex">
                    <li><input type="text" name="price_other" id="price_other" placeholder="금액(원)" required="no" message="금액(원)" is_num="yes"></li>
                </ul>
            </div>

            <div class="btn_pry">
                <button class="btn02 btn" type="button" onclick="go_pay_request();">충전요청하기</button>
            </div>

        </form>

    </section>

    <div id="layer1" class="pop-layer">
        <div class="pop-container">
            <div class="popcontent">
                <div class="poptitle">
                    <h2>
                        연락처 추가
                    </h2>
                    <a href="#" class="btn-layerClose close">
                        <img src="images/popup/close.svg">
                    </a>
                </div>

                <div class="adress_pop">

                    <div class="point_pop">
                        <h2>
                            <span><img src="images/popup/point.svg"></span>
                            주소록 등록 안내
                        </h2>
                        <ul class="number_list">
                            <li>최대 50,000개 까지 등록가능</li>
                            <li>문서파일 [복사], [붙여넣기] 가능</li>
                            <li>핸드폰번호, 이름 순으로 입력</li>
                            <li>입력 예시<br>
                                <img src="images/ex_adress.png" style="margin-top: 8px">
                            </li>
                            <li>문의사항 또는 등록대행을 원하시면 고객센터로 연락주세요.</li>


                        </ul>

                    </div>


                    <div class="adress_go">
                        <h2>그룹명 : 가족</h2>
                        <ul>
                            <li>
                                <input type="text" value="010-8888-1234">
                            </li>
                            <li>
                                <input type="text">
                            </li>
                            <li>
                                <input type="text">
                            </li>
                            <li>
                                <input type="text">
                            </li>
                            <li>
                                <input type="text">
                            </li>
                            <li>
                                <input type="text">
                            </li>
                        </ul>

                        <div class="pagenation">
                            <a href="#none" class="start">
                                <img src="images/pagenation/ll.png">
                            </a>
                            <a href="#none" class="pre">
                                <img src="images/pagenation/l.png">
                            </a>

                            <a href="#" class="atv">1</a>
                            <a href="#" class="">2</a>
                            <a href="#" class="">3</a>
                            <a href="#" class="">4</a>
                            <a href="#" class="">5</a>

                            <a href="#none" class="next">
                                <img src="images/pagenation/r.png">
                            </a>
                            <a href="#none" class="end">
                                <img src="images/pagenation/rr.png">
                            </a>
                        </div>

                        <p>· 이름은 입력하지 않으셔도 됩니다 <span>총<b>8</b>명</span></p>

                    </div>




                </div>

                <div class="btn_are_pop">
                    <a href="#" class=" btn btn02">
                        등록
                    </a>
                    <a href="#" class="btn-layerClose btn">
                        닫기
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!--footer-->
    <div><? include "./common/footer.php"; ?></div>

    <script>
        $(document).ready(function() {
            $('#sms').on('keyup', function() {
                $('#test_cnt').html("" + $(this).val().length + "");

                if ($(this).val().length > 100) {
                    $(this).val($(this).val().substring(0, 90));
                    $('#test_cnt').html("(90 / 90)");
                }
            });


        });

        $(document).ready(function() {

            $('ul.tabs li').click(function() {
                var tab_id = $(this).attr('data-tab');

                $('ul.tabs li').removeClass('current');
                $('.tab-content').removeClass('current');

                $(this).addClass('current');
                $("#" + tab_id).addClass('current');
            })

        })

        function pay_sel_1(z) {
            var tmp = z.options[z.selectedIndex].value;
            if (tmp == "dirbank") {
                $("#no_pg_acount").show();
            } else {
                $("#no_pg_acount").hide();
            }
        }

        function go_pay_request() {
            var check = chkFrm('frm_pay');
            if (check) {
                var price_order = $("#price_other").val();
                if (price_order == "") {
                    alert("결제금액을 입력해 주세요.");
                    return;
                }
                var purchase_type = $("#purchase_type").val();
                var pay_bank_depositor = $("#pay_bank_depositor").val();
                if (purchase_type == "dirbank") {
                    if (pay_bank_depositor == "") {
                        alert("입금자명을 입력해 주세요.");
                        return;
                    }
                    $("#frm_pay").attr("target", "_fra");
                    $("#frm_pay").attr("action", "payment_complete_nopg.php");
                } else {
                    $("#frm_pay").attr("target", "_self");
                    $("#frm_pay").attr("action", "pay_personal_update.php");
                }
                $("#frm_pay").submit();
            } else {
                false;
            }
        }
    </script>

</body>

</html>