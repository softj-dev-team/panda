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

$query = "select sms_save_cell.*, a.cell_send from sms_save_cell INNER JOIN sms_save a ON sms_save_cell.save_idx = a.idx where 1 " . $where . $order_by;

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
