<? include "./common/head.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/check_login.php"; // 공통함수 인클루드 ?>
<?php
	$member_idx = $_SESSION['member_coinc_idx'];
	$my_member_row = get_member_data($_SESSION['member_coinc_idx']);
?>

<body>

    <!--header-->
    <div><? include "./common/header.php"; ?></div>

    <!--content-->



    <section class="sub">
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
                    <td><?=$my_member_row['mb_short_fee']?><span>원</span></td>
                    <td><?=$my_member_row['mb_long_fee']?><span>원</span></td>
                    <td><?=$my_member_row['mb_img_fee']?><span>원</span></td>
                </tr>
            </table>
        </div>

        <div class="emt30"></div>

        <!--
        <div class="paybox"> 
            <ul>
                <li>
                    <h2><b>?=$inc_confg_bank_num?> (?=$inc_confg_bank_name?>)</b></h2>
                    <h2>예금주 :  ?=$inc_confg_bank_owner?></h2>
                </li>
                <li>
                    <div class="tlb_flex_sms">
                                <span>결제금액 :</span>
                                <input type="text" class="half"><span>최저금액은 10,000원 입니다.</span>

                    </div>
                    <div class="tlb_flex_sms">
                                <span>입금자명 :</span>
                                <input type="text" class="half"><span>무통장 입금 시 입금자명에 아이디를 적으시면 충전과정이 빠르게 처리됩니다.</span>

                    </div>
                
                </li>
            
            </ul>
        </div>
-->

        <form action="./pay_update.php" method="post">



            <div class="sub_title nomargin">
                <h2>결제 방식</h2>
            </div>
            <div class="emt20"></div>
            <div class="pay_select">
                <select name="purchase_type">
                    <option value="card">신용카드</option>
                    <option value="acnt">계좌이체</option>
                    <option value="vcnt">가상계좌</option>
                </select>
            </div>
            
            
            <div class="emt30"></div>


            <div class="sub_title nomargin">
                <h2>결제 금액</h2>

            </div>
            <div class="emt20"></div>

            <div>
                <ul id="pay_price" class="pay_price_flex">
                    <li><input type="radio" class="input_radio" value="5000" name="price"> 5,000원</li>
                    <li><input type="radio" class="input_radio" value="10000" name="price"> 10,000원</li>
                    <li><input type="radio" class="input_radio" value="50000" name="price"> 50,000원</li>
                    <li><input type="radio" class="input_radio" value="300000" name="price"> 300,000원</li>
                    <li><input type="radio" class="input_radio" value="-1" name="price"> 기타</li>
                    <li><input type="text" name="price_other" id="price_other" placeholder="기타금액(원)"></li>
                </ul>

            </div>







            <div class="btn_pry">
                <?php if ($member_idx == NULL) { ?>
                    <a class="btn02 btn" href="#" type="submit">로그인 시 충전 가능</a>
                <?php } else { ?>
                    <button class="btn02 btn" type="submit">충전요청하기</button>
                <?php } ?>
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

        $('input[name="price"]').change(function() {
            $('input[name="price"]').each(function() {
                var value = $(this).val();
                var checked = $(this).prop('checked');
                if (checked) {
                    if (value == -1) {
                        $("#price_other").show();
                    } else {
                        $("#price_other").hide();
                    }
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
    </script>

</body>

</html>