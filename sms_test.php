<? include "./common/head.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login.php"; // 공통함수 인클루드 
?>
<?
$send_type = trim(sqlfilter($_REQUEST['send_type']));
$member_idx = $_SESSION['member_coinc_idx'];
$my_member_row = get_member_data($_SESSION['member_coinc_idx']);


$query_group = "select *,(select count(idx) from address_group_num where 1 and address_group_num.group_idx=address_group.idx) as group_cnt from address_group where 1 and member_idx='" . $member_idx . "' order by idx desc";
$result_group = mysqli_query($gconnet, $query_group);

if ($my_member_row['master_ok'] == "N") {
    echo "<script>alert('관리자의 승인 후에 이용이 가능합니다. 관리자 또는 고객센터에 연락 주세요.');history.back();</script>";
}

if ($my_member_row['member_gubun'] == "3") {
    echo "<script>alert('휴면회원은 이용이 불가능합니다. 관리자 또는 고객센터에 연락 주세요.');history.back();</script>";
}
/*
if ($my_member_row['member_gubun'] == "2" && $_REQUEST['send_type'] != "adv") {
    echo "<script>alert('광고문자 회원은 광고문자만 이용 가능합니다.');history.back();</script>";
}
*/


?>

<body>

    <!--header-->
    <div><? include "./common/header.php"; ?></div>

    <!--content-->



    <section class="sub">
        <div class="sub_title">
            <h2>통신사 3사 테스트</h2>
            <a href="#layer1" class="btn btn-example">
                발송가능건수 확인
            </a>
        </div>

        <form name="sms_frm" id="sms_frm" action="sms_action.php" method="post" target="_fra" enctype="multipart/form-data">
            <input type="hidden" id="send_type" name="send_type" value="<?= $send_type ?>">
            <input type="hidden" id="sms_type" name="sms_type" value="sms">
            <input type="hidden" id="transmit_type" name="transmit_type">
            <input type="hidden" name="division_yn" value="N">
            <input type="hidden" name="reserv_yn" value="N">
            <input type="hidden" name="receive_cell_num_arr[]" value="01047592106">
            <input type="hidden" name="receive_cell_num_arr[]" value="01044382106">
            <input type="hidden" name="receive_cell_num_arr[]" value="01055072105">

            <div class="sms_flex">
                <div class="sms">
                    <h2>
                        <p id="sms_title">단문</p>
                        <div class="byte">
                            <span id="test_cnt"><b>0</b></span><span>/90 byte</span>
                        </div>

                    </h2>
                    <div class="sms_form" id="area_sms_form">
                        <p class="top_text">90byte(한글45자) 초과시 자동으로 장문 전환</p>
                        <textarea id="sms" name="sms_content" required="yes" message="발송내용" onkeyup="sms_text_count();" style="<?= $_REQUEST['send_type'] == "adv" ? "height:407px;" : "" ?>"></textarea>
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
                    </div>
                </div>

                <div class="tlb">
                    <table>
                        <?php if ($_REQUEST['send_type'] == "adv") { ?>
                            <tr>
                                <th>업체명(상호)</th>
                                <td><input type="text" id="adv_company" name="adv_company" required="no" message="(광고)옆에 삽입될 업체명 또는 상호 입력" onkeyup="sms_text_count();"></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <th>제목</th>
                            <td><input type="text" id="sms_title" name="sms_title" required="no" message="제목"></td>
                        </tr>
                        <?
                        $call_num_arr_before = json_decode($my_member_row['call_num'], true);
                        $use_yn_arr = json_decode($my_member_row['use_yn'], true);
                        $call_num_arr = array();
                        for ($i_num = 0; $i_num < sizeof($use_yn_arr); $i_num++) {
                            if ($use_yn_arr[$i_num] == "Y") {
                                array_push($call_num_arr, $call_num_arr_before[$i_num]);
                            }
                        }
                        ?>
                        <tr>
                            <th>발신번호</th>
                            <td>
                                <?php if (sizeof($call_num_arr) == 1) { ?>
                                    <input type="text" id="cell_send" name="cell_send" value="<?= $call_num_arr[0] ?>" required="yes" message="발신번호" readonly>
                                <?php } else { ?>
                                    <select id="cell_send" name="cell_send" required="yes" message="발신번호">
                                        <option value="">선택</option>
                                        <? for ($i_num = 0; $i_num < sizeof($call_num_arr); $i_num++) { ?>
                                            <option value="<?= $call_num_arr[$i_num] ?>"><?= $call_num_arr[$i_num] ?></option>
                                        <? } ?>
                                    </select>
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </div>

            </div>

        </form>


        <div class="btn_pry">
            <a href="javascript:go_msg_send();" class="btn02 btn">전송하기</a>
        </div>


        <div class="point_pop">
            <h2>
                <span><img src="images/popup/point.svg"></span>
                불법스팸안내
            </h2>
            <ul class="list_ul">
                <li>불법스팸을 발송하는 경우 문자 발송이 곧바로 중지되며 발송금액 및 충전금액은 환불되지 않습니다</li>

            </ul>

            <div class="emt30"></div>


            <h2>
                <span><img src="images/popup/point.svg"></span>
                불법스팸이란
            </h2>
            <ul class="list_ul">
                <li>도박, 불법대출, 음란성인물, 불법의약품 등의 내용을 보내는것을 말합니다.
                </li>

            </ul>

            <div class="emt30"></div>


            <h2>
                <span><img src="images/popup/point.svg"></span>
                알아두세요!
            </h2>
            <ul class="list_ul">
                <li>수신번호 추가 시 중복번호, 형식에 맞지 않는 번호는 자동 제거됩니다.</li>
                <li>발신번호는 사전에 등록된 번호중에서만 이용자의 선택이가능며,<br>
                    거짓으로 표시된 발신번호로 전송하는 경우 "변작번호로 판별되어 관련 법령에 따라 문자 발송 차단" 이 됨을 알려드립니다.</li>
                <li>발송되는 모든 번호는 중복 체크되며, 중복된 번호는 자동적으로 제거 됩니다.</li>

            </ul>

        </div>



        <div class="goods">

            <ul class="tabs">
                <li class="tab-link current" data-tab="tab-1" id="sample_btn_1" onclick="sms_type_change('sms');">단문문자 <!--일반형--></li>
                <li class="tab-link" data-tab="tab-1" id="sample_btn_2" onclick="sms_type_change('lms');">장문문자 <!--일반형--></li>
                <!--<li class="tab-link" data-tab="tab-3">단문문자 기업형</li>-->
            </ul>

            <div id="tab-1" class="tab-content current">
                <div class="tab_sub">
                    <a href="javascript:sms_sample_list();" id="sample_cate_all" class="sample_cate_btn atv">전체</a>
                    <?
                    $sub_sql = "select cate_code1,cate_name1 from common_code where 1 and type='smsmenu' and cate_level = '1' and is_del='N' and del_ok='N' order by cate_align desc";
                    $sub_query = mysqli_query($gconnet, $sub_sql);

                    $sub_k = 0;
                    for ($sub_i = 0; $sub_i < mysqli_num_rows($sub_query); $sub_i++) {
                        $sub_row = mysqli_fetch_array($sub_query);
                        $sub_k = $sub_k + 1;
                    ?>
                        <a href="javascript:sms_sample_list_cate('<?= $sub_row['cate_code1'] ?>');" id="sample_cate_<?= $sub_row['cate_code1'] ?>" class="sample_cate_btn"><?= $sub_row['cate_name1'] ?></a>
                    <? } ?>
                </div>

                <span id="sample_list_sms">
                    <!-- inner_sample_sms.php 에서 불러옴 -->
                </span>
            </div>

            <input type="hidden" id="sample_sms_type" value="sms" />
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


    <!--메세지 보관함-->


    <div id="layer2" class="pop-layer">
        <div class="pop-container">
            <div class="popcontent">
                <div class="poptitle">
                    <h2>
                        메세지 보관함
                    </h2>
                    <a href="javascript:;" class="btn-layerClose close" id="btn_layerClose">
                        <img src="images/popup/close.svg">
                    </a>
                </div>

                <ul class="tabs">
                    <li class="tab-link current" data-tab="tab-4">장문, 단문</li>
                    <!--<li class="tab-link" data-tab="tab-5">이미지</li>-->
                </ul>

                <div id="tab-4" class="tab-content current">
                    <!-- inner_sms_save_list.php 에서 불러옴 -->
                </div>

                <!--<div id="tab-5" class="tab-content">
        
              <div class="point_pop samll">
 
                <ul class="list_ul">
                    <li>저장된 메세지를 클릭하면 입력창에 제목과 내용이 입려됩니다.</li>

                </ul>

        </div>
        
        <div class="tab_btn_are">
            <div class="btn">
                <a href="#">전체선택</a>
                <a href="#">삭제</a>
            </div>
            <div class="input_tab">
                <input type="text">
                <a href="#none">
                    <img src="images/search.png">
                </a>
            </div>
        </div>
        
        <div class="sample">
            <div class="sample_box">
                <input type="checkbox">
                <img src="images/sample.png">
            </div>
            <div class="sample_box">
                <input type="checkbox">
                <img src="images/sample.png">
            </div>
                   

            

        
        </div>
        
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
        
        

	</div>-->


            </div>
        </div>
    </div>



    <!--발송건수확인-->


    <div id="layer3" class="pop-layer">
        <div class="pop-container">
            <div class="popcontent">
                <div class="poptitle">
                    <h2>
                        받는사람 추가하기
                    </h2>
                    <a href="#" class="btn-layerClose close">
                        <img src="images/popup/close.svg">
                    </a>
                </div>

                <ul class="tabs wide">
                    <li class="tab-link current" data-tab="tab-6">주소록 불러오기</li>
                    <li class="tab-link" data-tab="tab-7">직접 붙여넣기</li>
                    <!--<li class="tab-link" data-tab="tab-8">엑셀 붙여넣기</li>-->
                </ul>

                <div id="tab-6" class="tab-content current">

                    <div class="point_pop samll">

                        <ul class="list_ul">
                            <li>수정 및 변경은 주소록 메뉴에서 가능합니다.</li>

                        </ul>

                    </div>

                    <div class="tab_btn_are">
                        <div class="btn">
                            <a href="#" style="background: #666; color: #fff">그룹</a>
                            <!--<a href="#">개인</a>-->
                        </div>
                        <div class="input_tab">
                            <input type="text">
                            <a href="#none">
                                <img src="images/search.png">
                            </a>
                        </div>
                    </div>


                    <div class="tlb center">
                        <table>
                            <thead>
                                <tr>
                                    <th class="check"><input type="checkbox"></th>
                                    <th>그룹명</th>
                                    <th>그룹 인원수</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < mysqli_num_rows($result_group); $i++) { // 대분류 루프 시작
                                    $row_group = mysqli_fetch_array($result_group); ?>
                                    <tr>
                                        <td class="check"><input type="checkbox" name="check_group" value="<?= $row_group["idx"] ?>"></td>
                                        <td><?= $row_group["group_name"] ?></td>
                                        <td><?= $row_group["group_cnt"] ?>명</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>


                </div>

                <div id="tab-7" class="tab-content">

                    <textarea placeholder="입력방법 : 01000000001,01000000002" class="top25 h200" id="text_add_val"></textarea>

                    <div class="point_pop">
                        <h2>
                            <span><img src="images/popup/point.svg"></span>
                            알림
                        </h2>
                        <ul class="list_ul">
                            <li>최대 50,000개까지 등록할 수 있습니다.</li>
                            <li>핸드폰 번호는 엔터(Enter)또는 콤마(,)로 구분하여 입력해야 합니다.</li>
                        </ul>

                    </div>

                </div>

                <!--
                <div id="tab-8" class="tab-content current">

                    <div class="tab_btn_are">
                        <div class="btn">
                            <a href="#" style="background: #666; color: #fff">그룹</a>
                            <a href="#">개인</a>
                        </div>
                        <div class="input_tab">
                            <input type="text">
                            <a href="#none">
                                <img src="images/search.png">
                            </a>
                        </div>
                    </div>


                    <div class="tlb center xcel">
                        <table>
                            <tr>
                                <th>1</th>
                                <th>이름(선택)</th>
                                <th>전화번호(+필수)</th>
                            </tr>
                            <tr>
                                <th>2</th>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>3</th>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>4</th>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <th>5</th>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </div>


                    <div class="point_pop">
                        <h2>
                            <span><img src="images/popup/point.svg"></span>
                            알림
                        </h2>
                        <ul class="list_ul">
                            <li>최대 50,000개까지 등록할 수 있습니다.</li>
                            <li>이름, 전화번호 순으로 등록해 주세요.</li>
                        </ul>

                    </div>


                </div>
                    -->



                <div class="btn_are_pop">
                    <a href="#" class="btn-layerClose btn btn02" id="text_add_btn">
                        추가
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

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>
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

        $(function() {
            $(".datepicker").datepicker({
                changeYear: true,
                changeMonth: true,
                minDate: '-90y',
                yearRange: 'c-90:c',
                dateFormat: 'yy-mm-dd',
                showMonthAfterYear: true,
                constrainInput: true,
                dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
                monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월']
            });
        });

        var add_text_count = <?= $_REQUEST['send_type'] == "adv" ? "28" : "0" ?>;

        function sms_text_count() {
            var company_count = getStringLength($("#adv_company").val());
            if (company_count >= 0) {
                var txt = "(광고)\n" + $("#adv_company").val();
                $("#adv_title").text(txt);
            }
            var text_length = (getStringLength($("#sms").val()) + company_count + add_text_count);
            $('#test_cnt').html("<b>" + text_length + "</b>");
            if (text_length > 90) {
                //$("#sms").val($("#sms").val().substring(0, 90));
                //$('#test_cnt').html("(90 / 90)");
                $("#sms_title").text("장문");
            } else {
                $("#sms_title").text("단문");
            }
        }

        function getStringLength(str) {
            var retCode = 0;
            var strLength = 0;
            if (str == null || str == undefined) return 0;
            for (i = 0; i < str.length; i++) {
                var code = str.charCodeAt(i);
                var ch = str.substr(i, 1).toUpperCase();
                code = parseInt(code);
                if ((ch < "0" || ch > "9") && (ch < "A" || ch > "Z") && ((code > 255) || (code < 0))) {
                    strLength = strLength + 2;
                } else {
                    strLength = strLength + 1;
                }
            }
            return parseInt(strLength);
        }


        $(document).ready(function() {
            $('ul.tabs li').click(function() {
                var tab_id = $(this).attr('data-tab');

                $('ul.tabs li').removeClass('current');
                $('.tab-content').removeClass('current');

                $(this).addClass('current');
                $("#" + tab_id).addClass('current');
            });

            sms_save_list();
            sms_sample_list();
        });

        function go_sendinfo_view() {
            $("#layer1").show();
        }

        function go_msg_save() {
            $("#transmit_type").val("save");
            sms_frm.submit();
        }

        function go_msg_send() {
            var check = chkFrm('sms_frm');
            if (check) {
                $("#transmit_type").val("send");
                sms_frm.submit();
            } else {
                false;
            }
        }

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
            //get_data("inner_sms_set_form.php", "area_sms_form", "sms_idx=" + idx + "");
            $.ajax({
                type: "GET",
                url: "inner_sms_set_form.php",
                data: {
                    sms_idx: idx,
                },
                success: function(data) {
                    console.log(data);
                    $("#sms").html(data);
                    sms_text_count();
                    //$("#layer2").hide();
                }
            });
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

        function add_receive_dan() {
            var cell_receive_dan = $("#cell_receive_dan").val();

            if (cell_receive_dan == "") {
                alert("추가할 번호를 입력해 주세요.");
                return;
            } else {
                // 숫자만 포함되도록 정규표현식을 사용하여 검사합니다.
                var numericPattern = /^[0-9]+$/;
                if (!numericPattern.test(cell_receive_dan)) {
                    alert("숫자만 입력해 주세요.");
                    return;
                }

                // 자리수 확인
                if (cell_receive_dan.length == 10 || cell_receive_dan.length == 11) {} else {
                    //alert("변작번호로 판별되어 관련 법령에 따라 문자 발송이 차단됩니다.");
                    alert("자리수가 맞지 않습니다.");
                    return;
                }

                // "011" 또는 "017" 로 시작하는지 확인
                if (cell_receive_dan.startsWith("010") || cell_receive_dan.startsWith("017")) {} else {
                    alert("변작번호로 판별되어 관련 법령에 따라 문자 발송이 차단됩니다.");
                    return;
                }

                let html = `
                    <div class="box">
                        <input type="checkbox" name="receive_cell_num" value="${$('#cell_receive_dan').val()}" required="no" message="수신번호">
                        <span data-hp="${$('#cell_receive_dan').val()}">${$('#cell_receive_dan').val()}</span>
						<input type="hidden" name="receive_cell_num_arr[]" value="${$('#cell_receive_dan').val()}">
				    </div>
				`
                $('#cell_receive_list').append(html);
                $("#cell_receive_dan").val("");
                deleteDuplicate();
                $('#cell_receive_cnt').html($('#cell_receive_list').find('div').length);

            }
        }

        function sms_type_change(stype) {
            if (stype == "sms") {
                $('#sample_btn_1').addClass("current");
                $('#sample_btn_2').removeClass("current");
            } else if (stype == "lms") {
                $('#sample_btn_2').addClass("current");
                $('#sample_btn_1').removeClass("current");
            }
            $('#sample_sms_type').val(stype);
            sms_sample_list();
        }

        function sms_sample_list() {
            $('.sample_cate_btn').removeClass("atv");
            $('#sample_cate_all').addClass("atv");
            var sample_sms_type = $('#sample_sms_type').val();

            get_data("inner_sample_sms.php", "sample_list_sms", "send_type=<?= $send_type ?>&sms_type=" + sample_sms_type + "&sms_category=&keyword=&target_id=sample_list_sms");
        }

        function sms_sample_list_cate(cate) {
            $('.sample_cate_btn').removeClass("atv");
            $('#sample_cate_' + cate + '').addClass("atv");
            var sample_sms_type = $('#sample_sms_type').val();

            get_data("inner_sample_sms.php", "sample_list_sms", "send_type=<?= $send_type ?>&sms_type=" + sample_sms_type + "&sms_category=" + cate + "&keyword=&target_id=sample_list_sms");
        }

        function go_sample_save(frm_name) {
            if (confirm('선택하신 문자를 저장 하시겠습니까?')) {
                document.forms[frm_name].submit();
            }
        }

        /* 파일붙여넣기 & 엑셀붙여넣기 시작 */
        // 파일붙여넣기
        function getTextFile() {
            $('#text_file').click();
        }
        // 엑셀에 붙여넣기
        function getExcelFile() {
            $('#excel_file').click();
        }

        // 텍스트 불러오기
        $("#text_file").on('change', function() {
            $('#cell_receive_list').html('');
            $('#cell_receive_cnt').text('0');
            let ext = $("#text_file").val().split(".").pop().toLowerCase();
            if ($.inArray(ext, ["txt"]) == -1) {
                alert("텍스트 파일만 첨부 가능합니다.");
                $("#text_file").val("");
                return false;
            } else {
                readText(async function(result) {
                    //alert(result);
                    let list = result.split((/,| |\r\n/));

                    if (list.length > 300000) {
                        alert('최대 300,000개까지 등록할 수 있습니다.');
                    } else {
                        //list = await rejectHpCheck(list, 0);
                        list = await checkDuplicateText(list);
                        list.forEach(item => {
                            if (item != '') {
                                let html = `
                                <div class="box">
                                	<input type="checkbox" name="receive_cell_num" value="${item}" required="no" message="수신번호">
                                    <span data-hp="${item}">${item}</span>
									<input type="hidden" name="receive_cell_num_arr[]" value="${item}">
                                </div>
							`
                                $('#cell_receive_list').append(html);
                            }
                        });
                        deleteDuplicate();
                        $('#cell_receive_cnt').text($('#cell_receive_list').find('div').length);
                    }
                });
            }
            $(this).val('');
        });

        $("#text_add_btn").click(async function() {
            if ($("#tab-7").hasClass('current')) {

                let list = $("#text_add_val").val().replaceAll('-', '').split((/,| |\r\n/));
                console.log(list);
                if (list.length > 300000) {
                    alert('최대 300,000개까지 등록할 수 있습니다.');
                } else {
                    //list = await rejectHpCheck(list, 0);
                    list = await checkDuplicateText(list);
                    list.forEach(item => {
                        if (item != '') {
                            let html = `
                                <div class="box">
                                	<input type="checkbox" name="receive_cell_num" value="${item}" required="no" message="수신번호">
                                    <span data-hp="${item}">${item}</span>
									<input type="hidden" name="receive_cell_num_arr[]" value="${item}">
                                </div>
							`
                            $('#cell_receive_list').append(html);
                        }
                    });
                    deleteDuplicate();
                    $('#cell_receive_cnt').text($('#cell_receive_list').find('div').length);




                }
            } else if ($("#tab-6").hasClass('current')) {
                const arr = [];
                // 체크한 항목만 취득
                var check_group = $("input[name='check_group']:checked");
                $(check_group).each(function() {
                    arr.push($(this).val());
                });
                if (arr.length == 0) {
                    alert('그룹을 선택해주세요.');
                    return false;
                } else {
                    var list_test = [];
                    $.ajax({
                        url: "./address_get.php",
                        type: "GET",
                        data: {
                            group_idx: arr.join(","),
                        },
                        async: true,
                        dataType: "json",
                        success: function(data) {
                            data.forEach(item => {
                                let html = `
                                <div class="box">
                                	<input type="checkbox" name="receive_cell_num" value="${item.receive_num}" required="no" message="수신번호">
                                    <span data-hp="${item.receive_num}">${item.receive_num}</span>
									<input type="hidden" name="receive_cell_num_arr[]" value="${item.receive_num}">
                                </div>
							`
                                $('#cell_receive_list').append(html);
                            });
                            deleteDuplicate();
                            $('#cell_receive_cnt').html($('#cell_receive_list').find('div').length);
                        },
                    });
                }

            }

        });

        // 엑셀 불러오기
        $("#excel_file").on('change', function() {
            $('#cell_receive_list').html('');
            $('#cell_receive_cnt').text('0');
            let ext = $("#excel_file").val().split(".").pop().toLowerCase();
            if ($.inArray(ext, ["xls", "xlsx"]) == -1) {
                alert("엑셀 파일만 첨부 가능합니다.");
                $("#excel_file").val("");
                return false;
            } else {
                readExcel(async function(result) {
                    // 타이틀 체크
                    if (Object.keys(result[0]).includes('NAME') && Object.keys(result[0]).includes('HP')) {
                        if (result.length > 300000) {
                            alert('최대 300,000개까지 등록할 수 있습니다.');
                        } else {
                            //result = await rejectHpCheck(result, 1);
                            result = await checkDuplicateExcel(result);
                            result.forEach(item => {
                                //if(isHpFormat(item.HP)){
                                let html = `
                                  	<div class="box">
										<input type="checkbox" name="receive_cell_num" value="${item.HP}" required="no" message="수신번호">
										<span data-hp="${item.HP}">${item.HP} (${item.NAME})</span>
										<input type="hidden" name="receive_cell_num_arr[]" value="${item.HP}">
									</div>
                                `
                                $('#cell_receive_list').append(html);
                                /*}else{
                                    alert('전화번호 양식이 옳바르지 않은 번호가 존재합니다.\n다시 입력 후 등록해주세요.');
                                    $('#abDiv').html('');
                                }*/
                            });
                            deleteDuplicate();
                            $('#cell_receive_cnt').text($('#cell_receive_list').find('div').length);

                        }

                    } else {
                        alert('엑셀 양식을 참고해주세요.\n헤더는 [이름 = NAME, 번호 = HP]이 되어야합니다.');
                    }
                });
            }
            $(this).val('');
        });
        /* 파일붙여넣기 & 엑셀붙여넣기 끝 */

        // 전체 선택 버튼
        function allSelectBtn() {
            if ($('input[name=receive_cell_num]').length > $('input[name=receive_cell_num]:checked').length) {
                $('input[name=receive_cell_num]').prop('checked', true);
            } else {
                $('input[name=receive_cell_num]').prop('checked', false);
            }
        }

        // 연락처 삭제 버튼
        function abDelete() {
            $('input[name=receive_cell_num]:checked').each(function(idx, el) {
                let parentIndex = $(this).parent().index();
                $('#cell_receive_list').find('div').eq(parentIndex).remove();
            });
            $('#cell_receive_cnt').text($('#cell_receive_list').find('div').length);
        }

        async function checkDuplicateExcel(result_arr) {
            const newArray = result_arr.filter((item, i) => {
                console.log(item);
                return (
                    result_arr.findIndex((item2, j) => {
                        return item.HP === item2.HP;
                    }) === i
                );
            });
            return newArray;
        }

        async function checkDuplicateText(result_arr) {
            const set = new Set(result_arr);
            console.log(set);
            const uniqueArr = [...set];
            return uniqueArr;
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
    </script>

</body>

</html>