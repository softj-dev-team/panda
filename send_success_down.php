<?php
//ob_start();
header('Content-Type: text/html; charset=UTF-8');
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
include $_SERVER["DOCUMENT_ROOT"] . "/master/include/xlsx_writer.php";

$member_idx = $_SESSION['member_coinc_idx'];

$query = "SELECT * FROM sms_save_cell INNER JOIN sms_save ON sms_save_cell.save_idx = sms_save.idx WHERE member_idx = $member_idx order by sms_save_cell.idx desc";

//echo "<br><br>쿼리 = " . $query . "<br><Br>";

$result = mysqli_query($gconnet, $query);

$query_cnt = "select sms_save_cell.*, a.cell_send from sms_save_cell INNER JOIN sms_save a ON sms_save_cell.save_idx = a.idx where 1 " . $where;
$result_cnt = mysqli_query($gconnet, $query_cnt);
$num = mysqli_num_rows($result_cnt);

$header = array(
    "전송일시" => "string",
    "발신번호" => "string",
    "수신번호" => "string",
    "통신사" => "string",
    "발송여부" => "string",
);

$data = array();

for ($i = 0; $i < mysqli_num_rows($result); $i++) { // 대분류 루프 시작
    $row = mysqli_fetch_array($result);

    $comp = "";
    if ($row['module_type'] == "LG") {
        $str = strtotime($row['wdate']);
        $date = date("Ym", $str);
        $sql_module = "select * from TBL_SEND_LOG_$date where fetc1='" . $row['idx'] . "'";

        $query_module = mysqli_query($gconnet, $sql_module);
        $module_row = mysqli_fetch_array($query_module);
        $comp = $module_row['fmobilecomp'];
    } else if ($row['module_type'] == "JUD1" || $row['module_type'] == "JUD2") {
        $sql_module = "select * from SMS_BACKUP_AGENT_" . $row['module_type'] . " where S_ETC1='" . $row['idx'] . "'";
        $query_module = mysqli_query($gconnet, $sql_module);
        $module_row = mysqli_fetch_array($query_module);
        $comp = $module_row['TELECOM'];
    }

    $is_send = "";

    if ($row['module_type'] == "LG") {
        $sql_sub_2 = "select idx from sms_save_cell where 1 and is_del='N' and idx='" . $row['idx'] . "' and idx in (select fetc1 from TBL_SEND_LOG_" . str_replace("-", "", substr($row['wdate'], 0, 7)) . " where 1 and frsltstat='06')";
        $query_sub_2 = mysqli_query($gconnet, $sql_sub_2);
        $row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);

        $sql_sub_3 = "select idx from sms_save_cell where 1 and is_del='N' and idx='" . $row['idx'] . "' and idx in (select fetc1 from TBL_SEND_LOG_" . str_replace("-", "", substr($row['wdate'], 0, 7)) . " where 1 and frsltstat='07')";
        $query_sub_3 = mysqli_query($gconnet, $sql_sub_3);
        $row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
    } else if ($row['module_type'] == "JUD1") {
        $sql_sub_2 = "select idx from sms_save_cell where 1 and is_del='N' and idx='" . $row['idx'] . "' and idx in (select S_ETC1 from SMS_BACKUP_AGENT_JUD1 where 1 and RSTATE=0)";
        $query_sub_2 = mysqli_query($gconnet, $sql_sub_2);
        $row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);

        $sql_sub_3 = "select idx from sms_save_cell where 1 and is_del='N' and idx='" . $row['idx'] . "' and idx in (select S_ETC1 from SMS_BACKUP_AGENT_JUD1 where 1 and RSTATE!=0)";
        $query_sub_3 = mysqli_query($gconnet, $sql_sub_3);
        $row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
    } else if ($row['module_type'] == "JUD2") {
        $sql_sub_2 = "select idx from sms_save_cell where 1 and is_del='N' and idx='" . $row['idx'] . "' and idx in (select S_ETC1 from SMS_BACKUP_AGENT_JUD2 where 1 and RSTATE=0)";
        $query_sub_2 = mysqli_query($gconnet, $sql_sub_2);
        $row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);

        $sql_sub_3 = "select idx from sms_save_cell where 1 and is_del='N' and idx='" . $row['idx'] . "' and idx in (select S_ETC1 from SMS_BACKUP_AGENT_JUD1 where 1 and RSTATE=!0)";
        $query_sub_3 = mysqli_query($gconnet, $sql_sub_3);
        $row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
    }

    if ($row['receive_cnt_suc'] > 0) {
        $is_send = "성공";
    } else if ($row['receive_cnt_fail'] > 0) {
        $is_send = "실패";
    } else {
        $is_send = "잔여";
    }

    if ($row['cell'] == "01055072105") {
        $row['cell'] = "3사테스트 - LG";
    } else if ($row['cell'] == "01044382106") {
        $row['cell'] = "3사테스트 - KT";
    } else if ($row['cell'] == "01047592106") {
        $row['cell'] = "3사테스트 - SK";
    }


    if ($is_send == "성공") {
        $filedValues = array(
            preg_replace('/[\"]/', '""', $row['wdate']),
            preg_replace('/[\"]/', '""', $row['cell_send']),
            preg_replace('/[\"]/', '""', $row['cell']),
            preg_replace('/[\"]/', '""', $comp),
            preg_replace('/[\"]/', '""', $is_send)
        );
        array_push($data, $filedValues);
    }
}

$file_date = date("YmdHis");
$filename = "성공_발송내역_" . $file_date . ".xlsx";
header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header('Content-Transfer-Encoding: binary');
header('Cache-Control: must-revalidate');
header('Pragma: public');

$writer = new XLSXWriter();
$writer->writeSheetHeader('Sheet1', $header);
foreach ($data as $rows) {
    $writer->writeSheetRow('Sheet1', $rows);
}
$writer->writeToStdOut();
