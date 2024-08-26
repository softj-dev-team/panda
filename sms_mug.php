<? include "./common/head.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login.php"; // 공통함수 인클루드 
?>
<?
$send_type = trim(sqlfilter($_REQUEST['send_type']));

$my_member_row = get_member_data($_SESSION['member_coinc_idx']);

$call_num_arr = json_decode($my_member_row['call_num'], true);

if ($my_member_row['master_ok'] == "N") {
    echo "<script>alert('관리자의 승인 후에 이용이 가능합니다. 관리자 또는 고객센터에 연락 주세요.');history.back();</script>";
}

if ($my_member_row['member_gubun'] == "3") {
    echo "<script>alert('휴면회원은 이용이 불가능합니다. 관리자 또는 고객센터에 연락 주세요.');history.back();</script>";
}

if ($my_member_row['member_gubun'] == "2" && $_REQUEST['send_type'] != "adv") {
    echo "<script>alert('광고문자 회원은 광고문자만 이용 가능합니다.');history.back();</script>";
}

$query_filter = "select filtering_text from filtering where key_name='filtering'";
$result_filter = mysqli_query($gconnet, $query_filter);
$filtering_list = mysqli_fetch_array($result_filter);
$filteringArray = explode(",", $filtering_list['filtering_text']);

?>

<body>

    <!--header-->
    <div><? include "./common/header.php"; ?></div>

    <!--content-->



    <section class="sub">
        <div class="sub_title">
            <h2>머지문자 보내기</h2>
            <a href="#layer1" class="btn btn-example">
                발송가능건수 확인
            </a>
        </div>

        <form name="sms_frm" id="sms_frm" action="sms_action.php" method="post" target="_fra" enctype="multipart/form-data">
            <input type="hidden" id="send_type" name="send_type" value="<?= $send_type ?>">
            <input type="hidden" id="sms_type" name="sms_type" value="sms">
            <input type="hidden" id="transmit_type" name="transmit_type">
            <div class="sms_flex">
                <div class="sms">
                    <h2>
                        문자

                        <div class="byte">
                            <span id="test_cnt">0</span><span>/90 byte</span>
                        </div>

                    </h2>

                    <div class="sms_form" id="area_sms_form">
                        <p class="top_text">90byte(한글45자) 초과시 자동으로 장문 전환</p>
                        <?php if ($_REQUEST['send_type'] == "adv") { ?>
                            <p style="padding:10px">(광고)</p>
                        <?php } ?>
                        <textarea id="sms" name="sms_content" required="yes" message="발송내용" onkeyup="sms_text_count();" style="<?= $_REQUEST['send_type'] == "adv" ? "height:407px;" : "" ?>"></textarea>
                        <?php if ($_REQUEST['send_type'] != "gen") { ?>
                            <a href="javascript:;" class="sms080">무료거부 <?= $inc_sms_denie_num ?></a>
                        <?php } ?>
                    </div>
                    <div class="sms_btn">
                        <span href="#none" class="tksms">특수문자 입력
                            <span class="tkbox">
                                <a href="javascript:insertatcaret('sms','♥');sms_text_count();">♥</a>
                                <a href="javascript:insertatcaret('sms','♡');sms_text_count();">♡</a>
                                <a href="javascript:insertatcaret('sms','★');sms_text_count();">★</a>
                                <a href="javascript:insertatcaret('sms','☆');sms_text_count();">☆</a>
                                <a href="javascript:insertatcaret('sms','▶');sms_text_count();">▶</a>
                                <a href="javascript:insertatcaret('sms','▷');sms_text_count();">▷</a>
                                <a href="javascript:insertatcaret('sms','●');sms_text_count();">●</a>
                                <a href="javascript:insertatcaret('sms','■');sms_text_count();">■</a>
                                <a href="javascript:insertatcaret('sms','▲');sms_text_count();">▲</a>
                                <a href="javascript:insertatcaret('sms','▒');sms_text_count();">▒</a>
                                <a href="javascript:insertatcaret('sms','♨');sms_text_count();">♨</a>
                                <a href="javascript:insertatcaret('sms','™');sms_text_count();">™</a>
                                <a href="javascript:insertatcaret('sms','♪');sms_text_count();">♪</a>
                                <a href="javascript:insertatcaret('sms','♬');sms_text_count();">♬</a>
                                <a href="javascript:insertatcaret('sms','☜');sms_text_count();">☜</a>
                                <a href="javascript:insertatcaret('sms','☞');sms_text_count();">☞</a>
                                <a href="javascript:insertatcaret('sms','♂');sms_text_count();">♂</a>
                                <a href="javascript:insertatcaret('sms','♀');sms_text_count();">♀</a>
                                <a href="javascript:insertatcaret('sms','◆');sms_text_count();">◆</a>
                                <a href="javascript:insertatcaret('sms','◇');sms_text_count();">◇</a>
                                <a href="javascript:insertatcaret('sms','♣');sms_text_count();">♣</a>
                                <a href="javascript:insertatcaret('sms','♧');sms_text_count();">♧</a>
                                <a href="javascript:insertatcaret('sms','☎');sms_text_count();">☎</a>
                                <a href="javascript:insertatcaret('sms','◀');sms_text_count();">◀</a>
                                <a href="javascript:insertatcaret('sms','◁');sms_text_count();">◁</a>
                                <a href="javascript:insertatcaret('sms','○');sms_text_count();">○</a>
                                <a href="javascript:insertatcaret('sms','□');sms_text_count();">□</a>
                                <a href="javascript:insertatcaret('sms','▼');sms_text_count();">▼</a>
                                <a href="javascript:insertatcaret('sms','∑');sms_text_count();">∑</a>
                                <a href="javascript:insertatcaret('sms','㉿');sms_text_count();">㉿</a>
                                <a href="javascript:insertatcaret('sms','◈');sms_text_count();">◈</a>
                                <a href="javascript:insertatcaret('sms','▣');sms_text_count();">▣</a>
                                <a href="javascript:insertatcaret('sms','『');sms_text_count();">『</a>
                                <a href="javascript:insertatcaret('sms','』');sms_text_count();">』</a>
                                <a href="javascript:insertatcaret('sms','☜');sms_text_count();">☜</a>
                                <a href="javascript:insertatcaret('sms','♬');sms_text_count();">♬</a>
                                <a href="javascript:insertatcaret('sms','⌒');sms_text_count();">⌒</a>
                                <a href="javascript:insertatcaret('sms','¸');sms_text_count();">¸</a>
                                <a href="javascript:insertatcaret('sms','˛');sms_text_count();">˛</a>
                                <a href="javascript:insertatcaret('sms','∽');sms_text_count();">∽</a>
                                <a href="javascript:insertatcaret('sms','з');sms_text_count();">з</a>
                                <a href="javascript:insertatcaret('sms','§');sms_text_count();">§</a>
                                <a href="javascript:insertatcaret('sms','⊙');sms_text_count();">⊙</a>
                                <a href="javascript:insertatcaret('sms','※');sms_text_count();">※</a>
                                <a href="javascript:insertatcaret('sms','∴');sms_text_count();">∴</a>
                                <a href="javascript:insertatcaret('sms','¤');sms_text_count();">¤</a>
                                <a href="javascript:insertatcaret('sms','∂');sms_text_count();">∂</a>
                                <a href="javascript:insertatcaret('sms','▩');sms_text_count();">▩</a>
                            </span>

                        </span>
                        <a href="javascript:alert('무료 수신거부 서비스는 필수입니다.');">080수신거부</a>

                    </div>
                </div>

                <div class="mug">
                    <a href="#none">[*n*]변경</a>
                    <a href="#none">[*1*]변경</a>
                    <a href="#none">[*2*]변경</a>
                    <a href="#none">[*3*]변경</a>
                    <a href="#none">[*4*]변경</a>

                </div>


                <div class="sms">
                    <h2>
                        미리보기

                        <div class="byte">
                            <span id="test_cnt">0</span><span>/90 byte</span>
                        </div>

                    </h2>
                    <div class="sms_form">

                        <textarea maxlength="90" id="sms"></textarea>

                    </div>
                    <div class="sms_btn">

                        <a href="#none" class="w100">미리보기</a>

                    </div>
                </div>



            </div>



            <div class="tlb">
                <table>
                    <tr>
                        <th>제목</th>
                        <td><input type="text" id="sms_title" name="sms_title" required="no" message="제목"></td>
                        <th>분할전송</th>
                        <td>
                            <div class="tlb_flex_sms">
                                <input type="radio" style="width:25px;height:25px;" id="division_yn_1" name="division_yn" value="Y" required="no" message="분할전송"><span>사용</span>
                                <input type="radio" style="width:25px;height:25px;" id="division_yn_2" name="division_yn" value="N" required="no" message="분할전송"><span>미사용</span>
                                <input type="text" class="half" id="division_cnt" name="division_cnt" value="" required="no" message="분할전송"><span>건씩</span>
                                <input type="text" class="half" id="division_min" name="division_min" value="" required="no" message="분할전송"><span>분간격</span>
                            </div>
                        </td>
                    </tr>
                    <?
                    $call_num_arr = json_decode($my_member_row['call_num'], true);
                    $use_yn_arr = json_decode($my_member_row['use_yn'], true);
                    $call_num_cnt = sizeof($call_num_arr);
                    if ($call_num_cnt < 1) {
                        $call_num_cnt = 1;
                    }
                    ?>
                    <tr>
                        <th>발신번호</th>
                        <td>
                            <!--
								<input type="text" id="cell_send" name="cell_send" value="<?= $call_num_arr[0] ?>" required="yes" message="발신번호">
                            -->
                            <select id="cell_send" name="cell_send" required="yes" message="발신번호">
                                <option value="">선택</option>
                                <? for ($i_num = 0; $i_num < $call_num_cnt; $i_num++) { ?>
                                    <? if ($use_yn_arr[$i_num] == "Y") { ?>
                                        <option value="<?= $call_num_arr[$i_num] ?>"><?= $call_num_arr[$i_num] ?></option>
                                    <? } ?>
                                <? } ?>
                            </select>
                        </td>
                        <th>전송시간</th>
                        <td>
                            <div class="tlb_flex_sms">
                                <input type="radio" style="width:25px;height:25px;" id="reserv_yn_1" name="reserv_yn" value="N" required="yes" message="전송시간"><span>즉시</span>
                                <input type="radio" style="width:25px;height:25px;" id="reserv_yn_2" name="reserv_yn" value="Y" required="yes" message="전송시간"><span>예약</span>
                            </div>

                            <div class="tlb_flex_sms">
                                <input type="time" class="half" id="reserv_date" name="reserv_date" value="" required="no" message="예약날자">
                                <input type="text" class="half" id="reserv_time" name="reserv_time" value="" required="no" message="예약시간"><span>시</span>
                                <input type="text" class="half" id="reserv_minute" name="reserv_minute" value="" required="no" message="예약분"><span>분</span>
                            </div>
                        </td>
                    </tr>

                </table>
            </div>

        </form>

        <div class="btn_pry">
            <a href="javascript:;" class="btn02 btn">전송하기</a>
        </div>



        <div class="tlb center xcel">
            <table>
                <tr>
                    <th>1</th>
                    <th>핸드폰</th>
                    <th>이름</th>
                    <th>[*1*]</th>
                    <th>[*2*]</th>
                    <th>[*3*]</th>
                    <th>[*4*]</th>
                </tr>
                <tr>
                    <th>1</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>2</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>3</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>4</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <th>5</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>


        <div class="point_pop">
            <h2>
                <span><img src="images/popup/point.svg"></span>
                알아두세요!
            </h2>
            <ul class="list_ul">
                <li>수신번호 추가 시 중복번호, 형식에 맞지 않는 번호는 자동 제거됩니다.</li>
                <li>080수신거부 기본, 문자데이 블랙리스트 (선택 :정통부신고번호 고객센터문의) 자동 거부 되며, 신규 회원 가입이 필요합니다.</li>

            </ul>

        </div>

    </section>

    <!--발송건수확인-->

    <div id="layer1" class="pop-layer">
        <div class="pop-container samll">
            <div class="popcontent">
                <div class="poptitle">
                    <h2>
                        발송가능건수
                    </h2>
                    <a href="#" class="btn-layerClose close">
                        <img src="images/popup/close.svg">
                    </a>
                </div>

                <div class="pop_flex">
                    <ul>
                        <li>
                            <p class="left"><b>단문문자</b><span>단가 <?= number_format($my_member_row['mb_short_fee']) ?>원</span></p>
                            <p class="right"><b><?= floor($my_member_row['current_point'] / $my_member_row['mb_short_fee']) ?></b>건</p>
                        </li>
                        <li>
                            <p class="left"><b>장문문자</b><span>단가 <?= number_format($my_member_row['mb_long_fee']) ?>원</span></p>
                            <p class="right"><b><?= floor($my_member_row['current_point'] / $my_member_row['mb_long_fee']) ?></b>건</p>
                        </li>
                        <li>
                            <p class="left"><b>이미지문자</b><span>단가 <?= number_format($my_member_row['mb_img_fee']) ?>원</span></p>
                            <p class="right"><b><?= floor($my_member_row['current_point'] / $my_member_row['mb_img_fee']) ?></b>건</p>
                        </li>
                    </ul>
                </div>

                <!--div class="btn_are_pop">
                <a href="#" class="btn-layerClose btn">
                    닫기
                </a>
            </div-->

            </div>
        </div>
    </div>

    <!--footer-->
    <div><? include "./common/footer.php"; ?></div>


    <script>
        /*$(document).ready(function() {
        $('#sms').on('keyup', function() {
            $('#test_cnt').html(""+$(this).val().length+"");
 
            if($(this).val().length > 100) {
                $(this).val($(this).val().substring(0, 90));
                $('#test_cnt').html("(90 / 90)");
            }
        });
    });*/

        function sms_text_count() {
            $('#test_cnt').html("" + $("#sms").val().length + "");
            if ($("#sms").val().length > 90) {
                //$("#sms").val($("#sms").val().substring(0, 90));
                $('#test_cnt').html("(90 / 90)");
            }
        }

        $(document).ready(function() {
            $('ul.tabs li').click(function() {
                var tab_id = $(this).attr('data-tab');

                $('ul.tabs li').removeClass('current');
                $('.tab-content').removeClass('current');

                $(this).addClass('current');
                $("#" + tab_id).addClass('current');
            })
        })

        function go_sendinfo_view() {
            $("#layer1").show();
        }

        function go_msg_save() {
            $("#transmit_type").val("save");
            sms_frm.submit();
        }

        function go_msg_send() {
            var ban_ = ban();
            if (ban_) {
                var check = chkFrm('sms_frm');
                if (check) {
                    $("#transmit_type").val("send");
                    sms_frm.submit();
                } else {
                    false;
                }
            }
        }

        $(document).ready(function() {
            sms_save_list();
        });

        function sms_save_list() {
            get_data("inner_sms_save_list.php", "tab-4", "send_type=<?= $send_type ?>&sms_type=sms&sms_category=&keyword=&target_id=tab-4");
        }

        function sms_save_list_find() {
            var keyword = $("#pop_sms_keyword").val();

            if (keyword == "") {
                alert("검색어를 입력해 주세요.");
                return;
            }

            get_data("inner_sms_save_list.php", "tab-4", "send_type=<?= $send_type ?>&sms_type=sms&sms_category=&keyword=" + keyword + "&target_id=tab-4");
        }

        function set_sms_form(idx) {
            get_data("inner_sms_set_form.php", "area_sms_form", "sms_idx=" + idx + "");
            $("#btn_layerClose").trigger("click");
        }

        var check = 0;

        function CheckAll() {
            var boolchk;
            var chk = document.getElementsByName("save_idx[]")
            if (check) {
                check = 0;
                boolchk = false;
            } else {
                check = 1;
                boolchk = true;
            }
            for (i = 0; i < chk.length; i++) {
                chk[i].checked = boolchk;
            }
        }

        function go_tot_del() {
            var check = chkFrm('sms_save_frm');
            if (check) {
                if (confirm('선택하신 문자를 삭제 하시겠습니까?')) {
                    sms_save_frm.action = "sms_action_list_del.php";
                    sms_save_frm.submit();
                }
            } else {
                false;
            }
        }

        function deleteDuplicate() {
            var boxes = $('#cell_receive_list .box');

            // 중복된 data-hp 값을 찾기 위한 빈 객체를 생성합니다.
            var seen = {};

            // 각 .box 요소를 순회하면서 data-hp 값을 확인합니다.
            boxes.each(function() {
                var dataHp = $(this).find('span').data('hp');

                // 이미 동일한 data-hp 값을 가진 요소가 있는 경우 .box를 제거합니다.
                if (seen[dataHp]) {
                    $(this).remove();
                } else {
                    // 중복되지 않은 경우, seen 객체에 해당 data-hp 값을 추가합니다.
                    seen[dataHp] = true;
                }
            });
        }

        function ban() {
            var titleContents = $("#sms").val();
            var ban_word_list = [];
            var word_list = <?= json_encode($filteringArray) ?>;
            for (var i = 0; i < word_list.length; i++) {
                if (titleContents.indexOf(word_list[i]) > -1) {
                    if (ban_word_list.indexOf('"' + word_list[i] + '"') < 0) {
                        ban_word_list.push('"' + word_list[i] + '"');
                    }
                }
            }

            if (ban_word_list.length > 0) {
                alert("입력하신 제목과 내용에 금칙어인 " + ban_word_list.join(", ") + "를 포함하고 있습니다.");
                return false;
            } else {
                return true;
            }
        }
    </script>

</body>

</html>