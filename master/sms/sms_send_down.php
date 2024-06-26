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

$s_date_1 = trim(sqlfilter($_REQUEST['s_date_1']));
$s_date_2 = trim(sqlfilter($_REQUEST['s_date_2']));
$s_cell_num = trim(sqlfilter($_REQUEST['s_cell_num']));
$s_cell_num = str_replace("-", "", $s_cell_num);

$s_cate = trim(sqlfilter($_REQUEST['s_cate']));
if ($s_cate) {
    $s_sect3 = "";
    $s_sect4 = "";
}

################## 파라미터 조합 #####################
$total_param = 'bmenu=' . $bmenu . '&smenu=' . $smenu . '&v_sect=' . urlencode($v_sect) . '&s_group=' . $s_group . '&field=' . $field . '&keyword=' . $keyword . '&s_sect1=' . $s_sect1 . '&s_sect2=' . $s_sect2 . '&s_sect3=' . $s_sect3 . '&s_sect4=' . $s_sect4 . '&s_cate=' . $s_cate . '&s_date_1=' . $s_date_1 . '&s_date_2=' . $s_date_2 . '&s_cell_num=' . $s_cell_num;

if (!$pageNo) {
    $pageNo = 1;
}



$where = " and transmit_type='send' and a.is_del='N' and (case when reserv_yn = 'Y' then CONCAT(reserv_date,' ',reserv_time,':',reserv_minute) <= '" . date("Y-m-d H:i") . "' else a.idx > 0 end)";

if ($s_cate == "d") { // 당일 
    $where .= " and substring(a.wdate,1,10) = '" . date("Y-m-d") . "' ";
    $s_sect3 = date("Y-m-d");
    $s_sect4 = date("Y-m-d");
} elseif ($s_cate == "1") { // 하루전 
    $s_date = date("Y-m-d");
    $e_date = date("Y-m-d", strtotime("-1 day", strtotime($s_date)));
    $where .= " and substring(a.wdate,1,10) >= '" . $e_date . "' ";
    $s_sect3 = $e_date;
    $s_sect4 = $e_date;
} elseif ($s_cate == "7") { // 이틀전 
    $s_date = date("Y-m-d");
    $e_date = date("Y-m-d", strtotime("-7 day", strtotime($s_date)));
    $where .= " and substring(a.wdate,1,10) >= '" . $e_date . "' ";
    $s_sect3 = $e_date;
    $s_sect4 = $e_date;
} elseif ($s_cate == "30") { // 3일전 
    $s_date = date("Y-m-d");
    $e_date = date("Y-m-d", strtotime("-30 day", strtotime($s_date)));
    $where .= " and substring(a.wdate,1,10) >= '" . $e_date . "' ";
    $s_sect3 = $e_date;
    $s_sect4 = $e_date;
} elseif ($s_cate == "1m") { // 11일 누적 
    $s_date = date("Y-m-d");
    $e_date = date("Y-m-d", strtotime("-1 month", strtotime($s_date)));
    $where .= " and substring(a.wdate,1,10) >= '" . $e_date . "' ";
    $s_sect3 = $e_date;
    $s_sect4 = $s_date;
} elseif ($s_cate == "3m") { // 11일 누적 
    $s_date = date("Y-m-d");
    $e_date = date("Y-m-d", strtotime("-3 month", strtotime($s_date)));
    $where .= " and substring(a.wdate,1,10) >= '" . $e_date . "' ";
    $s_sect3 = $e_date;
    $s_sect4 = $s_date;
} elseif ($s_cate == "6m") { // 11일 누적 
    $s_date = date("Y-m-d");
    $e_date = date("Y-m-d", strtotime("-6 month", strtotime($s_date)));
    $where .= " and substring(a.wdate,1,10) >= '" . $e_date . "' ";
    $s_sect3 = $e_date;
    $s_sect4 = $s_date;
}

if ($s_cell_num) {
    $where .= " and a.cell_send = '" . $s_cell_num . "' ";
}

if ($s_date_1) { // 가입시작일
    $where .= " and substring(a.wdate,1,10) >= '" . $s_date_1 . "'";
}
if ($s_date_2) { // 가입종료일
    $where .= " and substring(a.wdate,1,10) <= '" . $s_date_2 . "'";
}

if ($v_sect) {
    $where .= " and a.send_type = '" . $v_sect . "' ";
}
if ($s_sect2) {
    $where .= " and a.sms_type = '" . $s_sect2 . "' ";
}
if ($s_group) {
    $where .= " and user_name = '" . $s_group . "' ";
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

$order_by = " order by a.idx desc ";

$query = "select a.*,(select cate_name1 from common_code where 1 and type='smsmenu' and cate_level = '1' and del_ok='N' and cate_code1=a.sms_category) as cate_name,
(select file_chg from board_file where 1 and board_tbname='sms_save' and board_code='mms' and board_idx=a.idx order by idx asc limit 0,1) as file_chg,
CONCAT(reserv_date,' ',reserv_time,':',reserv_minute) as reserv, member_info.user_name, member_info.user_id
from sms_save a INNER JOIN member_info ON a.member_idx = member_info.idx where 1 " . $where . $order_by;

//echo "<br><br>쿼리 = " . $query . "<br><Br>";

$result = mysqli_query($gconnet, $query);

$query_cnt = "select a.idx FROM sms_save a INNER JOIN member_info ON a.member_idx = member_info.idx where 1 " . $where;
$result_cnt = mysqli_query($gconnet, $query_cnt);
$num = mysqli_num_rows($result_cnt);


$header = array(
    "등록일시" => "string",
    "채널" => "string",
    "발송회원(ID)" => "string",
    "발신번호" => "string",
    "구분" => "string",
    "제목" => "string",
    "내용" => "string",
    "IP" => "string",
    "선차감금액" => "string",
    "실사용금액" => "string",
    "총건수" => "string",
    "성공" => "string",
    "실패" => "string",
    "잔여" => "string"
);

$data = array();

for ($i = 0; $i < mysqli_num_rows($result); $i++) { // 대분류 루프 시작
    $row = mysqli_fetch_array($result);

    if ($row['send_type'] == "gen") {
        $view_ok = "문자";
    } elseif ($row['send_type'] == "adv") {
        $view_ok = "광고문자";
    } elseif ($row['send_type'] == "elc") {
        $view_ok = "선거문자";
    } elseif ($row['send_type'] == "pht") {
        $view_ok = "포토문자";
    } elseif ($row['send_type'] == "test") {
        $view_ok = "테스트문자";
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

    $sql_sub_point = "select chg_mile from member_point where 1 and board_idx='" . $row['idx'] . "'";
    $query_sub_point = mysqli_query($gconnet, $sql_sub_point);
    $row['chg_mile'] = mysqli_fetch_array($query_sub_point)['chg_mile'];
    $filedValues = array(
        preg_replace('/[\"]/', '""', $row['wdate']),
        preg_replace('/[\"]/', '""', $row['module_type'] == "LG" ? "LGHV" : $row['module_type']),
        preg_replace('/[\"]/', '""', $row['user_name'] . "(" . $row['user_id'] . ")"),
        preg_replace('/[\"]/', '""', $row['cell_send']),
        preg_replace('/[\"]/', '""', $section),
        preg_replace('/[\"]/', '""', $row['sms_title']),
        preg_replace('/[\"]/', '""', $row['sms_content']),
        preg_replace('/[\"]/', '""', $row['send_ip']),
        preg_replace('/[\"]/', '""', number_format($row['chg_mile'] / $row['receive_cnt_tot'] * $row['receive_cnt_suc'])),
        preg_replace('/[\"]/', '""', number_format($row['chg_mile'] / $row['receive_cnt_tot'] * ($row['receive_cnt_suc'] - $row['receive_cnt_fail']))),
        preg_replace('/[\"]/', '""', number_format($row['receive_cnt_tot'])),
        preg_replace('/[\"]/', '""', number_format($row['receive_cnt_suc'])),
        preg_replace('/[\"]/', '""', number_format($row['receive_cnt_fail'])),
        preg_replace('/[\"]/', '""', number_format(($row['receive_cnt_tot'] - $row['receive_cnt_suc'] - $row['receive_cnt_fail']))),
    );
    array_push($data, $filedValues);
}

$file_date = date("YmdHis");
$filename = "회원별_발송_내역_" . $file_date . ".xlsx";
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
