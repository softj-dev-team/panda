<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드

$idx = isset($_REQUEST['idx']) ? $_REQUEST['idx'] : '';

$response = [];
$query_wdate = "SELECT  a.wdate,
                        a.module_type,
                        a.sms_title, 
                        a.sms_content,
                        a.sms_type, 
                        length(a.sms_content) sms_content_length,
                        a.send_type,
                        COUNT(sc.idx) AS receive_cnt_tot,
                        mi.mb_short_fee,
                        mi.mb_long_fee,
                        b.file_chg,
                        mi.mb_img_fee
                FROM sms_save a
                    JOIN sms_save_cell sc ON sc.save_idx = a.idx
                    LEFT JOIN board_file b ON b.board_idx = a.idx
                       and b.board_tbname = 'sms_save' AND b.board_code = 'mms'
                    LEFT JOIN member_info_sendinfo mi ON mi.member_idx = a.member_idx
                          WHERE a.idx = $idx";
$result_wdate = mysqli_query($gconnet, $query_wdate);
$query="SELECT a.* FROM sms_save_cell a where a.save_idx = $idx";
$result = mysqli_query($gconnet, $query);
$row_data = mysqli_fetch_assoc($result);
$response['data']['list'] = $row_data;
if ($result_wdate && mysqli_num_rows($result_wdate) > 0) {

    $row_wdate = mysqli_fetch_assoc($result_wdate);

    $response['data'] = array_merge($response['data'],$row_wdate);
    $wdate = $row_wdate['wdate'];
    $mb_short_fee = $row_wdate['mb_short_fee'];
    $mb_long_fee = $row_wdate['mb_long_fee'];
    $mb_img_fee = $row_wdate['mb_img_fee'];
    $module_type = $row_wdate['module_type'];
} else {
    echo json_encode(["error" => "No data found for sms_save.idx = $idx"]);
    exit;
}
// wdate에서 연월(YYYYMM)을 추출하여 동적 테이블 이름 생성
$yearMonth = date('Ym', strtotime($wdate));
$tableName = "TBL_SEND_LOG_" . $yearMonth;




if ($module_type === 'LG') {
    $query_log_table = "
        SELECT 
            COUNT(*) AS receive_cnt_tot,
            SUM(CASE WHEN frsltstat = '06' THEN 1 ELSE 0 END) AS receive_cnt_suc,
            SUM(CASE WHEN frsltstat != '06' THEN 1 ELSE 0 END) AS receive_cnt_fail,
            (SUM(CASE WHEN frsltstat = '06' THEN 1 ELSE 0 END) * $mb_short_fee) AS success_sms_cost,
            (SUM(CASE WHEN frsltstat = '06' THEN 1 ELSE 0 END) * $mb_long_fee) AS success_lms_cost,
            (SUM(CASE WHEN frsltstat = '06' THEN 1 ELSE 0 END) * $mb_img_fee) AS success_mms_cost,
            (COUNT(*) * $mb_short_fee) as sms_cost,
            (COUNT(*) * $mb_long_fee) as lms_cost,
            (COUNT(*) * $mb_img_fee) as mms_cost
        FROM $tableName
        JOIN sms_save_cell sc ON sc.idx = $tableName.fetc1
        WHERE sc.save_idx = $idx
    ";
    $result_log_table = mysqli_query($gconnet, $query_log_table);
    $log_table_stats = mysqli_fetch_assoc($result_log_table);
    $response['data'] = array_merge($response['data'], [
        'receive_cnt_tot' => $log_table_stats['receive_cnt_tot'],
        'receive_cnt_suc' => $log_table_stats['receive_cnt_suc'],
        'receive_cnt_fail' => $log_table_stats['receive_cnt_fail'],
        'success_sms_cost' => $log_table_stats['success_sms_cost'],
        'success_lms_cost' => $log_table_stats['success_lms_cost'],
        'success_mms_cost' => $log_table_stats['success_mms_cost'],
        'sms_cost' => $log_table_stats['sms_cost'],
        'lms_cost' => $log_table_stats['lms_cost'],
        'mms_cost' => $log_table_stats['mms_cost'],
    ]);

}
if ($module_type === 'JUD1') {
    $query_jud1_table = "
        SELECT 
            COUNT(*) AS receive_cnt_tot,
            SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) AS receive_cnt_suc,
            SUM(CASE WHEN RSTATE != 0 THEN 1 ELSE 0 END) AS receive_cnt_fail,
            (SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) * $mb_short_fee) AS success_sms_cost,
            (SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) * $mb_long_fee) AS success_lms_cost,
            (SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) * $mb_img_fee) AS success_mms_cost,
            (COUNT(*) * $mb_short_fee) as sms_cost,
            (COUNT(*) * $mb_short_fee) as lms_cost,
            (COUNT(*) * $mb_img_fee) as mms_cost
        FROM SMS_BACKUP_AGENT_JUD1
        JOIN sms_save_cell sc ON sc.idx = SMS_BACKUP_AGENT_JUD1.S_ETC1
        WHERE sc.save_idx = $idx
    ";
    $result_jud1_table = mysqli_query($gconnet, $query_jud1_table);
    $jud1_table_stats = mysqli_fetch_assoc($result_jud1_table);

    $response['data'] =array_merge($response['data'], [
        'receive_cnt_tot' => $jud1_table_stats['receive_cnt_tot'],
        'receive_cnt_suc' => $jud1_table_stats['receive_cnt_suc'],
        'receive_cnt_fail' => $jud1_table_stats['receive_cnt_fail'],
        'success_sms_cost' => $jud1_table_stats['success_sms_cost'],
        'success_lms_cost' => $jud1_table_stats['success_lms_cost'],
        'success_mms_cost' => $jud1_table_stats['success_mms_cost'],
        'sms_cost' => $jud1_table_stats['sms_cost'],
        'lms_cost' => $log_table_stats['lms_cost'],
        'mms_cost' => $jud1_table_stats['mms_cost'],
    ]);
}
if ($module_type === 'JUD2') {
    $query_jud2_table = "
        SELECT 
            COUNT(*) AS receive_cnt_tot,
            SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) AS receive_cnt_suc,
            SUM(CASE WHEN RSTATE != 0 THEN 1 ELSE 0 END) AS receive_cnt_fail,
            (SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) * $mb_short_fee) AS success_sms_cost,
            (SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) * $mb_long_fee) AS success_lms_cost,
            (SUM(CASE WHEN RSTATE = 0 THEN 1 ELSE 0 END) * $mb_img_fee) AS success_mms_cost,
            (COUNT(*) * $mb_short_fee) as sms_cost,
            (COUNT(*) * $mb_short_fee) as lms_cost,
            (COUNT(*) * $mb_img_fee) as mms_cost
        FROM SMS_BACKUP_AGENT_JUD2
        JOIN sms_save_cell sc ON sc.idx = SMS_BACKUP_AGENT_JUD2.S_ETC1
        WHERE sc.save_idx = $idx
    ";
    $result_jud2_table = mysqli_query($gconnet, $query_jud2_table);
    $jud2_table_stats = mysqli_fetch_assoc($result_jud2_table);

    $response['data'] = array_merge($response['data'], [
        'receive_cnt_tot' => $jud2_table_stats['receive_cnt_tot'],
        'receive_cnt_suc' => $jud2_table_stats['receive_cnt_suc'],
        'receive_cnt_fail' => $jud2_table_stats['receive_cnt_fail'],
        'success_sms_cost' => $jud2_table_stats['success_sms_cost'],
        'success_lms_cost' => $jud2_table_stats['success_lms_cost'],
        'success_mms_cost' => $jud2_table_stats['success_mms_cost'],
        'sms_cost' => $jud2_table_stats['sms_cost'],
        'lms_cost' => $jud2_table_stats['lms_cost'],
        'mms_cost' => $jud2_table_stats['mms_cost'],
    ]);
}
echo json_encode($response);
?>