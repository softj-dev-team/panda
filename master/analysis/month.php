<?php
include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드
include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더
include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인
$field = trim(sqlfilter($_REQUEST['field']));
$currentDate = new DateTime();  // 현재 날짜
$keyword = sqlfilter($_REQUEST['keyword']);

$where='';
if (!empty($field) && $field === "user_id" && !empty($keyword)) {
    // 필드와 키워드가 정상적이면 쿼리 조건을 추가
    $where .= " AND member_info." . mysqli_real_escape_string($GLOBALS['gconnet'], $field) . " LIKE '%" . mysqli_real_escape_string($GLOBALS['gconnet'], $keyword) . "%'";
}

$startDate = isset($_REQUEST['s_date']) ? new DateTime($_REQUEST['s_date'])  : (clone $currentDate)->modify('-1 months');;
$endDate = isset($_REQUEST['e_date']) ? new DateTime($_REQUEST['e_date'])  : $currentDate;
if ($startDate && $endDate) {
    $startDateFormatted = $startDate->format('Y-m-d H:i:s');
    $endDateFormatted = $endDate->format('Y-m-d 23:59:59');

    $where .= "and sms_save.wdate BETWEEN '$startDateFormatted' AND '$endDateFormatted'";
}
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
            SELECT fetc1, frsltstat, fsenddate
            FROM $table WHERE finsertdate <= '" . $endDate->format('Y-m-d') . "'         
        ";
}
// 쿼리들을 UNION으로 결합
$dynamic_table = implode(" UNION ALL ", $logQueries);
///$dynamic_table ON sms_save_cell.idx = $dynamic_table.fetc1
$module_type_query = "JOIN ($dynamic_table) log_table ON sms_save_cell.idx = log_table.fetc1";

$query = "
        SELECT
            DATE_FORMAT(log_table.fsenddate, '%Y-%m') AS send_month,        
            sms_save.module_type,
            COUNT(sms_save_cell.idx) AS total_send_cnt,
            SUM(
                    CASE
                        WHEN sms_save.module_type = 'LG' AND log_table.frsltstat = '06' THEN 1
                        WHEN sms_save.module_type = 'JUD1' AND jud1_table.RSTATE = 0 THEN 1
                        WHEN sms_save.module_type = 'JUD2' AND jud2_table.RSTATE = 0 THEN 1
                        ELSE 0
                        END
            ) AS success_send_cnt,
            SUM(
                    CASE
                        WHEN sms_save.module_type = 'LG' AND log_table.frsltstat != '06' THEN 1
                        WHEN sms_save.module_type = 'JUD1' AND jud1_table.RSTATE != 0 THEN 1
                        WHEN sms_save.module_type = 'JUD2' AND jud2_table.RSTATE != 0 THEN 1
                        ELSE 0
                        END
            ) AS fail_send_cnt
    
            FROM
                sms_save
                    JOIN
                sms_save_cell ON sms_save.idx = sms_save_cell.save_idx
                $module_type_query
                    JOIN
                member_info ON member_info.idx = sms_save.member_idx
                    LEFT JOIN (
                    SELECT S_ETC1, RSTATE
                    FROM SMS_BACKUP_AGENT_JUD1
                ) jud1_table ON jud1_table.S_ETC1 = sms_save_cell.idx AND sms_save.module_type = 'JUD1'
            
                    LEFT JOIN (
                    SELECT S_ETC1, RSTATE
                    FROM SMS_BACKUP_AGENT_JUD2
                ) jud2_table ON jud2_table.S_ETC1 = sms_save_cell.idx AND sms_save.module_type = 'JUD2'
            WHERE 1
            $where
            GROUP BY
                send_month , sms_save.module_type
            ORDER BY
                send_month desc,sms_save.module_type desc
    ";
    $result = mysqli_query($gconnet, $query);

    // 결과 데이터를 배열로 저장
    $statistics = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $statistics[] = $row;
    }
?>
<div id="wrap" class="skin_type01">
    <?php include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
    <div class="sub_wrap">
        <?php include $_SERVER["DOCUMENT_ROOT"] . "/master/include/point_left.php"; // 좌측메뉴
        ?>
        <!-- content 시작 -->
        <div class="container clearfix">
            <div class="content">
                <a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
                <div class="navi">
                    <ul class="clearfix">
                        <li>HOME</li>
                        <li>통계</li>
                        <li>일 별 통계</li>
                    </ul>
                </div>
                <div class="list_tit">
                    <h3>일 별 통계</h3>
                    <!--					<button class="btn_add" onclick="go_regist();" style="width:150px;"><span>샘플문자 등록</span></button>-->
                </div>
                <div class="list">
                    <!-- 검색창 시작 -->

                    <table class="search">
                        <form name="s_mem" id="s_mem" method="post" action="<?= basename($_SERVER['PHP_SELF']) ?>">
                            <input type="hidden" name="bmenu" value="<?= $bmenu ?>" />
                            <input type="hidden" name="smenu" value="<?= $smenu ?>" />
                            <input type="hidden" name="s_cnt" id="s_cnt" value="<?= $s_cnt ?>" />
                            <input type="hidden" name="s_order" id="s_order" value="<?= $s_order ?>" />
                            <caption>검색</caption>
                            <colgroup>
                                <col style="width:14%;">
                                <col style="width:20%;">
                                <col style="width:13%;">
                                <col style="width:20%;">
                                <col style="width:13%;">
                                <col style="width:20%;">
                            </colgroup>
                            <tr>
                                <th scope="row">발송일시</th>
                                <td colspan="2">
                                    <input type="text" autocomplete="off" readonly name="s_date" style="width:40%;" class="datepicker" value="<?= $s_date ?>"> ~
                                    <input type="text" autocomplete="off" readonly name="e_date" style="width:40%;" class="datepicker" value="<?= $e_date ?>">
                                </td>

                                <th scope="row">조건검색</th>
                                <td colspan="2">
                                    <select name="field" size="1" style="vertical-align:middle;width:40%;">
                                        <option value="user_id">아이디</option>
                                    </select>
                                    <input type="text" title="검색" name="keyword" id="keyword" style="width:50%;" value="<?= $keyword ?>">
                                </td>
                            </tr>
                        </form>
                    </table>

                    <div class="align_r mt20">
                        <button class="btn_search" onclick="s_mem.submit();">검색</button>
                        <!--<button class="btn_down" onclick="order_excel_frm.submit();">엑셀다운로드</button>-->
                    </div>

                    <!-- 리스트 시작 -->
                    <div class="search_wrap">

                        <table class="search_list" id="kakaoSendListTable">
                            <thead>
                            <tr>
                                <th>발송월</th>
                                <th>전송모듈</th>
                                <th>발송 총 건</th>
                                <th>발송 성공 건</th>
                                <th>발송 실패 건</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($statistics)) : ?>
                                <?php foreach ($statistics as $stat) : ?>
                                    <tr>
                                        <td><?= $stat['send_month'] ?></td>
                                        <td><?= $stat['module_type'] ?></td>
                                        <td><?= $stat['total_send_cnt'] ?></td>
                                        <td><?= $stat['success_send_cnt'] ?></td>
                                        <td><?= $stat['fail_send_cnt'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5">No statistics available</td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>

                    </div>

                </div>

            </div>
            <!-- content 종료 -->
        </div>
    </div>
    <script>
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
            $("input[name=s_date]").datepicker({
                dateFormat: 'yy-mm-dd'
            }).val(formatDate(oneMonthAgo)); // 한 달 전 날짜 기본값

            $("input[name=e_date]").datepicker({
                dateFormat: 'yy-mm-dd'
            }).val(formatDate(today)); // 오늘 날짜 기본값
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
    </script>

    <? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_bottom_admin_tail.php"; ?>
    </body>

    </html>