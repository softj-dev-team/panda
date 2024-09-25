<?php
include "./common/head.php";
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login.php"; // 공통함수 인클루드

$member_idx = $_SESSION['member_coinc_idx'];
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_date = isset($_REQUEST['s_date']) ? $_REQUEST['s_date'] : '';
$e_date = isset($_REQUEST['e_date']) ? $_REQUEST['e_date'] : '';
if (!$pageNo) {
    $pageNo = 1;
}

$pageScale = 10; // 페이지당 10 개씩
$start = ($pageNo - 1) * $pageScale;

################## 파라미터 조합 #####################
$total_param = 'field=' . $field . '&keyword=' . $keyword;

$where = " AND a.member_idx = '$member_idx' AND a.transmit_type = 'send' AND a.is_del = 'N'";
//$where .= " AND (CASE WHEN a.reserv_yn = 'Y' THEN CONCAT(a.reserv_date, ' ', a.reserv_time, ':', a.reserv_minute) <= '" . date("Y-m-d H:i") . "' ELSE a.idx > 0 END)";

if ($keyword) {
    $where .= " AND (a.sms_content LIKE '%$keyword%' OR a.sms_title LIKE '%$keyword%' OR a.cell_send LIKE '%$keyword%' OR sc.cell LIKE '%$keyword%')";
}

$order_by = " ORDER BY a.idx DESC";

    // 현재 날짜와 입력된 날짜에 따른 유동적인 범위 계산
    $currentDate = new DateTime();  // 현재 날짜
    $startDate = isset($_REQUEST['s_date']) ? new DateTime($_REQUEST['s_date']) : (clone $currentDate)->modify('-1 months');
    $endDate = isset($_REQUEST['e_date']) ? new DateTime($_REQUEST['e_date']) : $currentDate;

    // 종료 날짜는 다음 달의 시작으로 설정 (월을 포함시키기 위해)
    $endDate->modify('last day of this month');

    // module_type 값에 따라 다른 테이블과 조인
    $dynamic_table = '';
    $module_type_query = ''; // LG, JUD1, JUD2에 따른 조인 구문을 동적으로 생성



    $tables = [];
    $interval = new DateInterval('P1M');  // 1개월 간격
    while ($startDate->format('Ym') <= $endDate->format('Ym')) {
        $tables[] = "TBL_SEND_LOG_" . $startDate->format('Ym');  // 각 달의 테이블 이름 생성
        $startDate->add($interval);  // 1개월씩 더하기
    }
    $logQueries = [];
    foreach ($tables as $table) {
        $logQueries[] = "
            SELECT fetc1, frsltstat 
            FROM $table WHERE finsertdate <= '" . $endDate->format('Y-m-d') . "'         
        ";
    }
    // 쿼리들을 UNION으로 결합
    $dynamic_table = implode(" UNION ALL ", $logQueries);
    $module_type_query = "LEFT JOIN ($dynamic_table) log_table ON log_table.fetc1 = sc.idx AND a.module_type = 'LG'";



// 전체 쿼리 작성
$query = "
    SELECT a.idx, a.sms_title, a.sms_content, a.wdate, a.send_type, a.sms_type, a.module_type, a.cell_send, sc.cell,
           CONCAT(a.reserv_date, ' ', a.reserv_time, ':', a.reserv_minute) AS reserv,
           b.file_chg, 
           COUNT(sc.idx) AS receive_cnt_tot
           
    FROM sms_save a
    JOIN sms_save_cell sc ON sc.save_idx = a.idx
    LEFT JOIN board_file b ON b.board_idx = a.idx AND b.board_tbname = 'sms_save' AND b.board_code = 'mms'
    
    where 1 
        $where
    GROUP BY a.idx
    ORDER BY a.idx DESC
    LIMIT $start, $pageScale
";

// 쿼리 실행 및 결과 처리
$result = mysqli_query($gconnet, $query);

// 카운트 쿼리 최적화
$query_cnt = "SELECT COUNT(*) AS cnt FROM sms_save a WHERE 1 " . $where;
$result_cnt = mysqli_query($gconnet, $query_cnt);
$num = mysqli_fetch_assoc($result_cnt)['cnt'];

$iTotalSubCnt = $num;
$totalpage = ceil($iTotalSubCnt / $pageScale);
?>

<body>

    <!--header-->
    <div><? include "./common/header.php"; ?></div>

    <!--content-->


    <section class="sub sub_min">
        <div class="sub_title">
            <h2>전송결과</h2>
        </div>
        <div class="flex-just-start">
            <div class="sendContentBox">
                <h3>발송문자</h3>
                <div class="sendContentBoxBody">
                    <img src="/upload_file/sms/img_thumb/1695455815-KSSIW.png">
                    <p></p>
                    <span class="byteCount"></span>
                </div>
            </div>
            <div class="sendContentDetailBox">
                <table>
                    <thead>

                    </thead>
                    <tbody>
                    <tr><th>발송방법</th><td class="rowDataSmsType"></td></tr>
                    <tr><th>제목</th><td class="rowDataTitle"></td></tr>
                    <tr><th>선차감금액</th><td ><span class="rowDataUsePoint">00.00</span> 원</td></tr>
                    <tr><th>실사용금액</th><td ><span class="rowDataUseSumPoint">00.00</span> 원</td></tr>
                    <tr><th rowspan="4">발송내역</th><td>발송 시도건수 <span class="rowDataTotSendCnt">0</span> 건</td></tr>
                    <tr><td>발송 성공 <span class="rowDataSuccesSendCnt">0</span> 건</td></tr>
                    <tr><td>발송 실패 <span class="rowDataFaileTotSendCnt">0</span> 건</td></tr>
                    <tr><td>발송 대기 <span class="rowDataMoreTotSendCnt">0</span> 건</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

    </section>
    <section class="sub sub_min">


        <div class="adress_btn">
        </div>


        <div class="tab_btn_are">

            <form name="s_mem" id="s_mem" method="post" action="send.php" class="flex-just-start">
                <div class="btn ">
                    <span>기간선택 </span>
                    <input type="text" style="width:120px;" class="datepicker" id="s_date" name="s_date" value="">
                    <span> ~ </span>
                    <input type="text" style="width:120px;" class="datepicker" id="e_date" name="e_date" value="">
                    <a href="javascript:s_mem.submit();" class="blue">조회</a>
                </div>
                <div class="input_tab">
                    <input type="text" name="keyword" id="keyword" value="<?= $keyword ?>">
                    <a href="javascript:s_mem.submit();">
                        <img src="images/search.png">
                    </a>
                </div>
            </form>

            <div class="btn">
<!--                <a href="./send_success_down.php">성공내역 엑셀다운로드</a>-->
<!--                <a href="./send_fail_down.php">실패내역 엑셀다운로드</a>-->
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
                        <col style="width:15%;">
                        <col style="width:10%">
                        <col style="width:10%">
                        <col style="width:10%">
                        <col style="width:6%">

                    </colgroup>
                    <tr>
                        <th class="check"><input type="checkbox" onclick="javascript:CheckAll()"></th>
                        <th>등록일시</th>
                        <th>구분</th>
                        <th>발신번호</th>
                        <th>수신번호</th>
                        <th>총건수</th>

                    </tr>
                    <?php
                    while ($row = mysqli_fetch_array($result)) {
                        $listnum = $iTotalSubCnt - (($pageNo - 1) * $pageScale) - $i;

                        // 전송 유형 및 구분 설정
                        $view_ok = ($row['send_type'] == 'gen') ? "문자" :
                            (($row['send_type'] == 'adv') ? "광고문자" :
                                (($row['send_type'] == 'elc') ? "선거문자" :
                                    (($row['send_type'] == 'pht') ? "포토문자" :
                                        "3사테스트")));

                        $section = ($row['sms_type'] == 'sms') ? "단문" :
                            (($row['sms_type'] == 'lms') ? "장문" : "이미지문자");

                        ?>
                        <tr class="sendResultDataRow" data-id="<?= $row['idx'] ?>">
                            <td class="check"><input type="checkbox" name="send_idx[]" value="<?= $row['idx'] ?>"></td>
                            <td><span ><?= $row['wdate'] ?></span></td>
                            <td><?= $view_ok ?> (<?= $section ?>)</td>
                            <td><?= $row['cell_send'] ?></td>
                            <td><?= $row['cell'] ?></td>
                            <td><?= number_format($row['receive_cnt_tot']) ?></td>

                        </tr>
                    <?php } ?>
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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.8.18/themes/base/jquery-ui.css" type="text/css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/ui/1.8.18/jquery-ui.min.js"></script>

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
        $(function() {
            var today = new Date();
            var oneMonthAgo = new Date();
            oneMonthAgo.setMonth(today.getMonth() - 1);
            // 날짜 형식을 YYYY-MM-DD로 변환하는 함수
            function formatDate(date) {
                var day = ("0" + date.getDate()).slice(-2);
                var month = ("0" + (date.getMonth() + 1)).slice(-2);
                return date.getFullYear() + "-" + month + "-" + day;
            }
            // Datepicker 초기화 및 날짜 기본값 설정
            $("#s_date").datepicker({
                dateFormat: 'yy-mm-dd'
            }).val(formatDate(oneMonthAgo)); // 한 달 전 날짜 기본값

            $("#e_date").datepicker({
                dateFormat: 'yy-mm-dd'
            }).val(formatDate(today)); // 오늘 날짜 기본값
            $(".datepicker").datepicker({
                changeYear:true,
                changeMonth:true,
                minDate: '-90y',
                yearRange: 'c-90:c',
                dateFormat:'yy-mm-dd',
                showMonthAfterYear:true,
                constrainInput: true,
                dayNamesMin: ['일','월', '화', '수', '목', '금', '토' ],
                monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
            });
        });
    </script>

</body>

</html>