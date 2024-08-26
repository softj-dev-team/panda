<? include "./common/head.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login.php"; // 공통함수 인클루드 
?>
<?
$member_idx = $_SESSION['member_coinc_idx'];

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));

$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

################## 파라미터 조합 #####################
$total_param = 'field=' . $field . '&keyword=' . $keyword;

if (!$pageNo) {
    $pageNo = 1;
}

$where = " and member_idx='" . $member_idx . "' and transmit_type='send' and is_del='N' and (case when reserv_yn = 'Y' then CONCAT(reserv_date,' ',reserv_time,':',reserv_minute) <= '" . date("Y-m-d H:i") . "' else idx > 0 end)";

if ($keyword) {
    $where .= " and (sms_content like '%" . $keyword . "%' or sms_title like '%" . $keyword . "%')";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo - 1) * $pageScale;

$StarRowNum = (($pageNo - 1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";

$query = "select *,(select file_chg from board_file where 1 and board_tbname='sms_save' and board_code='mms' and board_idx=a.idx order by idx asc limit 0,1) as file_chg,CONCAT(reserv_date,' ',reserv_time,':',reserv_minute) as reserv from sms_save a where 1 " . $where . $order_by . " limit " . $StarRowNum . " , " . $EndRowNum;
//echo $query;
$result = mysqli_query($gconnet, $query);

$query_cnt = "select idx from sms_save a where 1 " . $where;
$result_cnt = mysqli_query($gconnet, $query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage    = ($iTotalSubCnt - 1) / $pageScale  + 1;
?>

<body>

    <!--header-->
    <div><? include "./common/header.php"; ?></div>

    <!--content-->



    <section class="sub">
        <div class="sub_title">
            <h2>전송결과</h2>

        </div>

        <div class="adress_btn">
        </div>


        <div class="tab_btn_are">

            <form name="s_mem" id="s_mem" method="post" action="send.php">
                <div class="input_tab">
                    <input type="text" name="keyword" id="keyword" value="<?= $keyword ?>">
                    <a href="javascript:s_mem.submit();">
                        <img src="images/search.png">
                    </a>
                </div>
            </form>

            <div class="btn">
                <a href="./send_success_down.php">성공내역 엑셀다운로드</a>
                <a href="./send_fail_down.php">실패내역 엑셀다운로드</a>
                <a href="javascript:go_tot_del();">내역삭제</a>
            </div>

        </div>

        <form method="post" name="frm" id="frm" target="_fra">
            <input type="hidden" name="pageNo" value="<?= $pageNo ?>" />
            <input type="hidden" name="total_param" value="<?= $total_param ?>" />
            <div class="tlb center border">
                <table>
                    <colgroup>
                        <col style="width:4%;">
                        <col style="width:10%;">
                        <col style="width:6%">
                        <col style="width:20%">
                        <col style="width:10%">
                        <col style="width:25%">
                        <col style="width:7%">
                        <col style="width:6%">
                        <col style="width:6%">
                        <col style="width:6%">
                    </colgroup>
                    <tr>
                        <th class="check"><input type="checkbox" onclick="javascript:CheckAll()"></th>
                        <th>등록일시</th>
                        <th>구분</th>
                        <th>제목</th>
                        <th>이미지</th>
                        <th>내용</th>
                        <th>총건수</th>
                        <th>성공</th>
                        <th>실패</th>
                        <th>잔여</th>
                        <!--<th>결과</th>
                    <th>비고</th>-->
                    </tr>
                    <?
                    for ($i = 0; $i < mysqli_num_rows($result); $i++) { // 대분류 루프 시작
                        $row = mysqli_fetch_array($result);

                        $listnum    = $iTotalSubCnt - (($pageNo - 1) * $pageScale) - $i;

                        if ($row['send_type'] == "gen") {
                            $view_ok = "문자";
                        } elseif ($row['send_type'] == "adv") {
                            $view_ok = "광고문자";
                        } elseif ($row['send_type'] == "elc") {
                            $view_ok = "선거문자";
                        } elseif ($row['send_type'] == "pht") {
                            $view_ok = "포토문자";
                        } elseif ($row['send_type'] == "test") {
                            $view_ok = "3사테스트";
                        }

                        if ($row['sms_type'] == "sms") {
                            $section = "단문";
                        } elseif ($row['sms_type'] == "lms") {
                            $section = "장문";
                        } elseif ($row['sms_type'] == "mms") {
                            $section = "이미지문자";
                        }

                        $sql_sub_1 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx='" . $row['idx'] . "'";
                        $query_sub_1 = mysqli_query($gconnet, $sql_sub_1);
                        $row['receive_cnt_tot'] = mysqli_num_rows($query_sub_1);

                        if ($row['module_type'] == "LG") {

                            $sql_sub_2 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx='" . $row['idx'] . "' and idx in (select fetc1 from TBL_SEND_LOG_" . str_replace("-", "", substr($row['wdate'], 0, 7)) . " where 1 and frsltstat='06')";
                            $query_sub_2 = mysqli_query($gconnet, $sql_sub_2);
                            $row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);

                            $sql_sub_3 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx='" . $row['idx'] . "' and idx in (select fetc1 from TBL_SEND_LOG_" . str_replace("-", "", substr($row['wdate'], 0, 7)) . " where 1 and frsltstat='07')";
                            $query_sub_3 = mysqli_query($gconnet, $sql_sub_3);
                            $row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
                        } else if ($row['module_type'] == "JUD1") {
                            $sql_sub_2 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx='" . $row['idx'] . "' and idx in (select S_ETC1 from SMS_BACKUP_AGENT_JUD1 where 1 and RSTATE=0)";
                            $query_sub_2 = mysqli_query($gconnet, $sql_sub_2);
                            $row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);

                            $sql_sub_3 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx='" . $row['idx'] . "' and idx in (select S_ETC1 from SMS_BACKUP_AGENT_JUD1 where 1 and RSTATE!=0)";
                            $query_sub_3 = mysqli_query($gconnet, $sql_sub_3);
                            $row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
                        } else if ($row['module_type'] == "JUD2") {
                            $sql_sub_2 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx='" . $row['idx'] . "' and idx in (select S_ETC1 from SMS_BACKUP_AGENT_JUD2 where 1 and RSTATE=0)";
                            $query_sub_2 = mysqli_query($gconnet, $sql_sub_2);
                            $row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);

                            $sql_sub_3 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx='" . $row['idx'] . "' and idx in (select S_ETC1 from SMS_BACKUP_AGENT_JUD1 where 1 and RSTATE=!0)";
                            $query_sub_3 = mysqli_query($gconnet, $sql_sub_3);
                            $row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
                        }

                    ?>
                        <tr>
                            <td class="check"><input type="checkbox" name="send_idx[]" id="send_idx[]" value="<?= $row["idx"] ?>" required="yes" message="전송결과"></td>
                            <td><?= $row['wdate'] ?></td>
                            <td><?= $view_ok ?><br />(<?= $section ?>)</td>
                            <td><?= $row['sms_title'] ?></td>
                            <td>
                                <? if ($row['file_chg']) { ?>
                                    <img src="<?= $_P_DIR_WEB_FILE ?>sms/img_thumb/<?= $row['file_chg'] ?>" style="max-width:60%;">
                                <? } ?>
                            </td>
                            <td style="text-align:left;    word-break: break-all;"><?= $row['sms_content'] ?></td>
                            <td><?= number_format($row['receive_cnt_tot']) ?></td>
                            <td><?php
                                if ($row['send_type'] == "test") {
                                    $sql_test = "select idx from sms_save_cell where 1 and is_del='N' and save_idx='" . $row['idx'] . "' and idx in (select fetc1 from TBL_SEND_LOG_" . str_replace("-", "", substr($row['wdate'], 0, 7)) . " where 1 and frsltstat='06')";
                                    $query_test = mysqli_query($gconnet, $sql_test);
                                    for ($j = 0; $j < mysqli_num_rows($query_test); $j++) { // 대분류 루프 시작
                                        $test_data = mysqli_fetch_array($query_test);
                                        $sql_test_2 = "select fdestine from TBL_SEND_LOG_" . str_replace("-", "", substr($row['wdate'], 0, 7)) . " where 1 and fetc1='" . $test_data['idx'] . "' and frsltstat='06'";
                                        $query_test_2 = mysqli_query($gconnet, $sql_test_2);
                                        $test_data_2 = mysqli_fetch_array($query_test_2);
                                        if ($test_data_2['fdestine'] == "01055072105") {
                                            echo "LG<br>";
                                        } else if ($test_data_2['fdestine'] == "01044382106") {
                                            echo "KT<br>";
                                        } else if ($test_data_2['fdestine'] == "01047592106") {
                                            echo "SK<br>";
                                        }
                                    }
                                } else {
                                    echo number_format($row['receive_cnt_suc']);
                                }
                                ?></td>
                            <td><?php if ($row['send_type'] == "test") {
                                    $sql_test = "select idx from sms_save_cell where 1 and is_del='N' and save_idx='" . $row['idx'] . "' and idx in (select fetc1 from TBL_SEND_LOG_" . str_replace("-", "", substr($row['wdate'], 0, 7)) . " where 1 and frsltstat='07')";
                                    $query_test = mysqli_query($gconnet, $sql_test);
                                    for ($j = 0; $j < mysqli_num_rows($query_test); $j++) { // 대분류 루프 시작
                                        $test_data = mysqli_fetch_array($query_test);
                                        $sql_test_2 = "select fdestine from TBL_SEND_LOG_" . str_replace("-", "", substr($row['wdate'], 0, 7)) . " where 1 and fetc1='" . $test_data['idx'] . "' and frsltstat='07'";
                                        $query_test_2 = mysqli_query($gconnet, $sql_test_2);
                                        $test_data_2 = mysqli_fetch_array($query_test_2);
                                        if ($test_data_2['fdestine'] == "01055072105") {
                                            echo "LG<br>";
                                        } else if ($test_data_2['fdestine'] == "01044382106") {
                                            echo "KT<br>";
                                        } else if ($test_data_2['fdestine'] == "01047592106") {
                                            echo "SK<br>";
                                        }
                                    }
                                } else {
                                    echo number_format($row['receive_cnt_fail']);
                                } ?></td>
                            <td><?php
                                if ($row['send_type'] == "test") {
                                    $sql_test = "select idx from sms_save_cell where 1 and is_del='N' and save_idx='" . $row['idx'] . "' and idx in (select fetc1 from TBL_SEND_TRAN where 1 and frsltstat='00')";
                                    $query_test = mysqli_query($gconnet, $sql_test);
                                    for ($j = 0; $j < mysqli_num_rows($query_test); $j++) { // 대분류 루프 시작
                                        $test_data = mysqli_fetch_array($query_test);
                                        $sql_test_2 = "select fdestine from TBL_SEND_TRAN where 1 and fetc1='" . $test_data['idx'] . "' and frsltstat='00'";
                                        $query_test_2 = mysqli_query($gconnet, $sql_test_2);
                                        $test_data_2 = mysqli_fetch_array($query_test_2);
                                        if ($test_data_2['fdestine'] == "01055072105") {
                                            echo "LG<br>";
                                        } else if ($test_data_2['fdestine'] == "01044382106") {
                                            echo "KT<br>";
                                        } else if ($test_data_2['fdestine'] == "01047592106") {
                                            echo "SK<br>";
                                        }
                                    }
                                } else {
                                    echo number_format(($row['receive_cnt_tot'] - $row['receive_cnt_suc'] - $row['receive_cnt_fail']));
                                }
                                ?></td>
                            <!--<td>전송</td>
                    <td>비고</td>-->
                        </tr>
                    <? } ?>
                </table>
            </div>
        </form>

        <div class="pagenation">
            <? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/paging_front.php"; ?>
        </div>

        <div class="point_pop">
            <h2>
                <span><img src="images/popup/point.svg"></span>
                알아두세요!
            </h2>
            <ul class="list_ul">
                <li>전송실패건 환불은 전송시도가 완료된 시점에 처리됩니다.</li>
                <li>전송결과는 6개월간 보관되며, 기간이 경과한 데이터는 정기적으로 삭제됩니다.</li>
                <li>전송결과는 성공이지만 문자를 수신하지 못하는 경우<a href="#none" style="color:#FE443E; text-decoration: underline; display: inline-block; margin-left: 5px; font-weight: bold">통신사별 스팸차단안내</a></li>

            </ul>

        </div>




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

        var check = 0;

        function CheckAll() {
            var boolchk;
            var chk = document.getElementsByName("send_idx[]")
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
            var check = chkFrm('frm');
            if (check) {
                if (confirm('선택하신 발송결과를 삭제 하시겠습니까?')) {
                    frm.action = "send_action_delete.php";
                    frm.submit();
                }
            } else {
                false;
            }
        }
    </script>

</body>

</html>