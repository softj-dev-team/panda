<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드

$idx = isset($_REQUEST['idx']) ? $_REQUEST['idx'] : '';
// 전체 쿼리 작성
$query ="select wdate from sms_save where idx=$idx ";
// 쿼리 실행 및 결과 처리
$result = mysqli_query($gconnet, $query);
// 결과가 있을 경우 처리
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result); // 결과를 연관 배열로 가져옴
    $wdate = $row['wdate']; // wdate 값을 변수에 주입
} else {
    // 결과가 없는 경우 처리
    $wdate = null; // 혹은 기본값 설정
}
// wdate에서 연월(YYYYMM)을 추출하여 테이블 이름을 생성
$yearMonth = date('Ym', strtotime($wdate));
$tableName = "TBL_SEND_LOG_" . $yearMonth;  // 테이블 이름 예: TBL_SEND_LOG_202409
$query = "
    SELECT log_table.ffilepath,a.idx, a.sms_title, a.sms_content, length(a.sms_content) as content_length, a.wdate, a.send_type, a.sms_type, a.module_type, a.cell_send, sc.cell,
           CONCAT(a.reserv_date, ' ', a.reserv_time, ':', a.reserv_minute) AS reserv,
           b.file_chg, 
           COUNT(sc.idx) AS receive_cnt_tot,
           SUM(
               CASE 
                   WHEN a.module_type = 'LG' AND log_table.frsltstat = '06' THEN 1
                   WHEN a.module_type = 'JUD1' AND jud1_table.RSTATE = 0 THEN 1
                   WHEN a.module_type = 'JUD2' AND jud2_table.RSTATE = 0 THEN 1
                   ELSE 0 
               END
           ) AS receive_cnt_suc,
           SUM(
               CASE 
                   WHEN a.module_type = 'LG' AND log_table.frsltstat != '06' THEN 1
                   WHEN a.module_type = 'JUD1' AND jud1_table.RSTATE != 0 THEN 1
                   WHEN a.module_type = 'JUD2' AND jud2_table.RSTATE != 0 THEN 1
                   ELSE 0 
               END
           ) AS receive_cnt_fail,
        (SUM(
               CASE 
                    WHEN a.module_type = 'LG' AND log_table.frsltstat = '06' THEN 1
                   WHEN a.module_type = 'JUD1' AND jud1_table.RSTATE = 0 THEN 1
                   WHEN a.module_type = 'JUD2' AND jud2_table.RSTATE = 0 THEN 1
                   ELSE 0 
               END
           ) * mi.mb_short_fee) AS fail_sms_cost,
         (SUM(
               CASE 
                   WHEN a.module_type = 'LG' AND log_table.frsltstat = '06' THEN 1
                   WHEN a.module_type = 'JUD1' AND jud1_table.RSTATE = 0 THEN 1
                   WHEN a.module_type = 'JUD2' AND jud2_table.RSTATE = 0 THEN 1
                   ELSE 0 
               END
           ) * mi.mb_long_fee) AS fail_lms_cost,
        (SUM(
               CASE 
                   WHEN a.module_type = 'LG' AND log_table.frsltstat = '06' THEN 1
                   WHEN a.module_type = 'JUD1' AND jud1_table.RSTATE = 0 THEN 1
                   WHEN a.module_type = 'JUD2' AND jud2_table.RSTATE = 0 THEN 1
                   ELSE 0 
               END
           ) * mi.mb_img_fee) AS fail_mms_cost,
          -- 비용 계산: 먼저 receive_cnt_tot을 계산한 후 그 값을 사용
       (COUNT(sc.idx) * mi.mb_short_fee) AS sms_cost,  -- sms 비용 계산
       (COUNT(sc.idx) * mi.mb_long_fee) AS lms_cost,   -- lms 비용 계산
       (COUNT(sc.idx) * mi.mb_img_fee) AS mms_cost    -- mms 비용 계산
    FROM sms_save a
    JOIN sms_save_cell sc ON sc.save_idx = a.idx
    LEFT JOIN board_file b ON b.board_idx = a.idx AND b.board_tbname = 'sms_save' AND b.board_code = 'mms'
      left join (
           SELECT fetc1, frsltstat, ffilepath
            FROM $tableName
      ) log_table ON log_table.fetc1 = sc.idx AND a.module_type = 'LG'
        
     LEFT JOIN (
        SELECT S_ETC1, RSTATE
        FROM SMS_BACKUP_AGENT_JUD1
    ) jud1_table ON jud1_table.S_ETC1 = sc.idx AND a.module_type = 'JUD1'

    LEFT JOIN (
        SELECT S_ETC1, RSTATE
        FROM SMS_BACKUP_AGENT_JUD2
    ) jud2_table ON jud2_table.S_ETC1 = sc.idx AND a.module_type = 'JUD2'
    LEFT JOIN member_info_sendinfo mi ON mi.member_idx = a.member_idx
    where a.idx=$idx        
    GROUP BY a.idx
    ORDER BY a.idx DESC  
";

// 쿼리 실행 및 결과 처리
$result = mysqli_query($gconnet, $query);
// 결과 가져오기
$rows = array();
while ($r = mysqli_fetch_assoc($result)) {
    $rows[] = $r;
}
echo json_encode($rows);
?>