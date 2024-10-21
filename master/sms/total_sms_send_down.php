<?php
//ob_start();
header('Content-Type: text/html; charset=UTF-8');
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
include $_SERVER["DOCUMENT_ROOT"] . "/master/include/xlsx_writer.php";


$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$v_sect = urldecode(sqlfilter($_REQUEST['v_sect']));
$s_group = trim(sqlfilter($_REQUEST['s_group']));

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = urldecode(sqlfilter($_REQUEST['keyword']));

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));
$s_sect3 = trim(sqlfilter($_REQUEST['s_sect3']));
$s_sect4 = trim(sqlfilter($_REQUEST['s_sect4']));

$s_cate = trim(sqlfilter($_REQUEST['s_cate']));
if ($s_cate) {
    $s_sect3 = "";
    $s_sect4 = "";
}

################## 파라미터 조합 #####################
$total_param = 'bmenu=' . $bmenu . '&smenu=' . $smenu . '&v_sect=' . urlencode($v_sect) . '&s_group=' . $s_group . '&field=' . $field . '&keyword=' . $keyword . '&s_sect1=' . $s_sect1 . '&s_sect2=' . $s_sect2 . '&s_sect3=' . $s_sect3 . '&s_sect4=' . $s_sect4 . '&s_cate=' . $s_cate;

if (!$pageNo) {
    $pageNo = 1;
}

$where = " and transmit_type='send' and sms_save_cell.is_del='N' and (case when reserv_yn = 'Y' then CONCAT(reserv_date,' ',reserv_time,':',reserv_minute) <= '" . date("Y-m-d H:i") . "' else sms_save_cell.idx > 0 end)";

if ($s_cate == "d") { // 당일 
    $where .= " and substring(sms_save_cell.wdate,1,10) = '" . date("Y-m-d") . "' ";
    $s_sect3 = date("Y-m-d");
    $s_sect4 = date("Y-m-d");
} elseif ($s_cate == "1") { // 하루전 
    $s_date = date("Y-m-d");
    $e_date = date("Y-m-d", strtotime("-1 day", strtotime($s_date)));
    $where .= " and substring(sms_save_cell.wdate,1,10) >= '" . $e_date . "' ";
    $s_sect3 = $e_date;
    $s_sect4 = $e_date;
} elseif ($s_cate == "7") { // 이틀전 
    $s_date = date("Y-m-d");
    $e_date = date("Y-m-d", strtotime("-7 day", strtotime($s_date)));
    $where .= " and substring(sms_save_cell.wdate,1,10) >= '" . $e_date . "' ";
    $s_sect3 = $e_date;
    $s_sect4 = $e_date;
} elseif ($s_cate == "30") { // 3일전 
    $s_date = date("Y-m-d");
    $e_date = date("Y-m-d", strtotime("-30 day", strtotime($s_date)));
    $where .= " and substring(sms_save_cell.wdate,1,10) >= '" . $e_date . "' ";
    $s_sect3 = $e_date;
    $s_sect4 = $e_date;
} elseif ($s_cate == "1m") { // 11일 누적 
    $s_date = date("Y-m-d");
    $e_date = date("Y-m-d", strtotime("-1 month", strtotime($s_date)));
    $where .= " and substring(sms_save_cell.wdate,1,10) >= '" . $e_date . "' ";
    $s_sect3 = $e_date;
    $s_sect4 = $s_date;
} elseif ($s_cate == "3m") { // 11일 누적 
    $s_date = date("Y-m-d");
    $e_date = date("Y-m-d", strtotime("-3 month", strtotime($s_date)));
    $where .= " and substring(sms_save_cell.wdate,1,10) >= '" . $e_date . "' ";
    $s_sect3 = $e_date;
    $s_sect4 = $s_date;
} elseif ($s_cate == "6m") { // 11일 누적 
    $s_date = date("Y-m-d");
    $e_date = date("Y-m-d", strtotime("-6 month", strtotime($s_date)));
    $where .= " and substring(sms_save_cell.wdate,1,10) >= '" . $e_date . "' ";
    $s_sect3 = $e_date;
    $s_sect4 = $s_date;
}

if ($v_sect) {
    $where .= " and sms_save_cell.cell = '" . str_replace("-", "", $v_sect) . "' ";
}
if ($s_sect2) {
    $where .= " and a.sms_type = '" . $s_sect2 . "' ";
}
if ($s_group) {
    $where .= " and a.member_idx = '" . $s_group . "' ";
}

/*if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}*/

if ($keyword) {
    $where .= " and (a.sms_content like '%" . $keyword . "%' or a.sms_title like '%" . $keyword . "%')";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo - 1) * $pageScale;

$StarRowNum = (($pageNo - 1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by sms_save_cell.idx desc ";

// 쿼리 최적화: 필요한 필드만 선택하여 조회
$query = "SELECT sms_save_cell.idx, sms_save_cell.wdate, sms_save_cell.cell, sms_save_cell.module_type, a.cell_send 
          FROM sms_save_cell 
          INNER JOIN sms_save a ON sms_save_cell.save_idx = a.idx 
          WHERE sms_save_cell.is_del = 'N' " . $where . $order_by;
$result = mysqli_query($gconnet, $query);

$query_cnt = "SELECT COUNT(*) as cnt FROM sms_save_cell INNER JOIN sms_save a ON sms_save_cell.save_idx = a.idx WHERE 1 " . $where;
$result_cnt = mysqli_query($gconnet, $query_cnt);
$num = mysqli_fetch_assoc($result_cnt)['cnt'];

$header = array(
    "전송일시" => "string",
    "발신번호" => "string",
    "수신번호" => "string",
    "통신사" => "string",
    "발송여부" => "string",
);

$data = array();
// 통신사 정보를 조회하는 함수
function getTelecomCompany($gconnet, $row) {
    if ($row['module_type'] == "LG") {
        $str = strtotime($row['wdate']);
        $date = date("Ym", $str);
        $sql_module = "SELECT fmobilecomp FROM TBL_SEND_LOG_$date WHERE fetc1='" . $row['idx'] . "'";
        $query_module = mysqli_query($gconnet, $sql_module);
        $module_row = mysqli_fetch_assoc($query_module);
        return $module_row['fmobilecomp'];
    } elseif ($row['module_type'] == "JUD1" || $row['module_type'] == "JUD2") {
        $sql_module = "SELECT TELECOM FROM SMS_BACKUP_AGENT_" . $row['module_type'] . " WHERE S_ETC1='" . $row['idx'] . "'";
        $query_module = mysqli_query($gconnet, $sql_module);
        $module_row = mysqli_fetch_assoc($query_module);
        return $module_row['TELECOM'];
    }
    return "";
}
// 발송 상태를 조회하는 함수
function getSendStatus($gconnet, $row) {
    if ($row['module_type'] == "LG") {
        $sql_sub_2 = "SELECT idx FROM sms_save_cell WHERE is_del='N' AND idx='" . $row['idx'] . "' AND idx IN (SELECT fetc1 FROM TBL_SEND_LOG_" . str_replace("-", "", substr($row['wdate'], 0, 7)) . " WHERE frsltstat='06')";
        $query_sub_2 = mysqli_query($gconnet, $sql_sub_2);
        $receive_cnt_suc = mysqli_num_rows($query_sub_2);

        $sql_sub_3 = "SELECT idx FROM sms_save_cell WHERE is_del='N' AND idx='" . $row['idx'] . "' AND idx IN (SELECT fetc1 FROM TBL_SEND_LOG_" . str_replace("-", "", substr($row['wdate'], 0, 7)) . " WHERE frsltstat='07')";
        $query_sub_3 = mysqli_query($gconnet, $sql_sub_3);
        $receive_cnt_fail = mysqli_num_rows($query_sub_3);
    } elseif ($row['module_type'] == "JUD1" || $row['module_type'] == "JUD2") {
        $sql_sub_2 = "SELECT idx FROM sms_save_cell WHERE is_del='N' AND idx='" . $row['idx'] . "' AND idx IN (SELECT S_ETC1 FROM SMS_BACKUP_AGENT_" . $row['module_type'] . " WHERE RSTATE=0)";
        $query_sub_2 = mysqli_query($gconnet, $sql_sub_2);
        $receive_cnt_suc = mysqli_num_rows($query_sub_2);

        $sql_sub_3 = "SELECT idx FROM sms_save_cell WHERE is_del='N' AND idx='" . $row['idx'] . "' AND idx IN (SELECT S_ETC1 FROM SMS_BACKUP_AGENT_" . $row['module_type'] . " WHERE RSTATE!=0)";
        $query_sub_3 = mysqli_query($gconnet, $sql_sub_3);
        $receive_cnt_fail = mysqli_num_rows($query_sub_3);
    }

    if ($receive_cnt_suc > 0) {
        return "성공";
    } elseif ($receive_cnt_fail > 0) {
        return "실패";
    } else {
        return "잔여";
    }
}
// 데이터 수집
while ($row = mysqli_fetch_assoc($result)) {
    $comp = getTelecomCompany($gconnet, $row);
    $is_send = getSendStatus($gconnet, $row);

    $filedValues = array(
        preg_replace('/[\"]/', '""', $row['wdate']),
        preg_replace('/[\"]/', '""', $row['cell_send']),
        preg_replace('/[\"]/', '""', $row['cell']),
        preg_replace('/[\"]/', '""', $comp),
        preg_replace('/[\"]/', '""', $is_send)
    );
    array_push($data, $filedValues);
}

$file_date = date("YmdHis");
$filename = "전체_발송내역_" . $file_date . ".xlsx";
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
