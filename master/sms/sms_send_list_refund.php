<?php
//include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드
//include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_admin.php"; // 전송내역페이지 헤더
//include $_SERVER["DOCUMENT_ROOT"] . "/master/include/check_login.php"; // 전송내역 로그인여부 확인

$gconnet = mysqli_connect("localhost", "asssahcom9", "soulvocal7!!", "asssahcom9");
//mysqli_query($gconnet, "set names UTF8");
$query = "SELECT * FROM sms_save WHERE refund_yn='N' AND is_del='N' ORDER BY wdate DESC LIMIT 0, 100";

//echo "<br><br>쿼리 = " . $query . "<br><Br>";

$result = mysqli_query($gconnet, $query);
for ($i = 0; $i < mysqli_num_rows($result); $i++) { // 대분류 루프 시작
    $row = mysqli_fetch_assoc($result);

    $sql_sub_1 = "select idx from sms_save_cell where 1 and is_del='N' and save_idx='" . $row['idx'] . "'";
    $query_sub_1 = mysqli_query($gconnet, $sql_sub_1);
    $row['receive_cnt_tot'] = mysqli_num_rows($query_sub_1);
    if ($row['module_type'] == "LG") {
        $table_join = "JOIN TBL_SEND_LOG_" . str_replace("-", "", substr($row['wdate'], 0, 7))." log ON sc.idx=log.fetc1";
        $sql_sub_2 = "select sc.idx 
                        from sms_save_cell sc $table_join 
                        where sc.is_del='N' 
                        and sc.save_idx='" . $row['idx'] . "' 
                        and log.frsltstat='06' ";
        $query_sub_2 = mysqli_query($gconnet, $sql_sub_2);
        $row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);

        $sql_sub_3 = "select sc.idx 
                        from sms_save_cell sc $table_join 
                        where sc.is_del='N' 
                        and sc.save_idx='" . $row['idx'] . "' 
                        and log.frsltstat!='06' ";
        $query_sub_3 = mysqli_query($gconnet, $sql_sub_3);
        $row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
    } else if ($row['module_type'] == "JUD1") {
        $sql_sub_2 = "select sc.idx from sms_save_cell sc join SMS_BACKUP_AGENT_JUD1 log on sc.idx=log.S_ETC1
                        where 1 and sc.is_del='N' and sc.save_idx='" . $row['idx'] . "' 
                        and log.RSTATE=0)";
        $query_sub_2 = mysqli_query($gconnet, $sql_sub_2);
        $row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);

        $sql_sub_3 = "select sc.idx from sms_save_cell sc join SMS_BACKUP_AGENT_JUD1 log on sc.idx=log.S_ETC1
                        where 1 and sc.is_del='N' and sc.save_idx='" . $row['idx'] . "' 
                        and log.RSTATE!=0)";
        $query_sub_3 = mysqli_query($gconnet, $sql_sub_3);
        $row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
    } else if ($row['module_type'] == "JUD2") {
        $sql_sub_2 = "select sc.idx from sms_save_cell sc join SMS_BACKUP_AGENT_JUD2 log on sc.idx=log.S_ETC1
                        where 1 and sc.is_del='N' and sc.save_idx='" . $row['idx'] . "' 
                        and log.RSTATE=0)";
        $query_sub_2 = mysqli_query($gconnet, $sql_sub_2);
        $row['receive_cnt_suc'] = mysqli_num_rows($query_sub_2);

        $sql_sub_3 = "select sc.idx from sms_save_cell sc join SMS_BACKUP_AGENT_JUD2 log on sc.idx=log.S_ETC1
                        where 1 and sc.is_del='N' and sc.save_idx='" . $row['idx'] . "' 
                        and log.RSTATE!=0)";
        $query_sub_3 = mysqli_query($gconnet, $sql_sub_3);
        $row['receive_cnt_fail'] = mysqli_num_rows($query_sub_3);
    }

    $sql_sub_point = "select chg_mile from member_point where 1 and board_idx='" . $row['idx'] . "'";
    $query_sub_point = mysqli_query($gconnet, $sql_sub_point);
    $row['chg_mile'] = mysqli_fetch_array($query_sub_point)['chg_mile'];

    if ($row['receive_cnt_tot'] != 0) {
        $before = floatval($row['chg_mile']);
        $after = $row['chg_mile'] / $row['receive_cnt_tot'] * $row['receive_cnt_suc'];
        if ($before != 0 && is_nan($after) == false && $before != $after) {
            var_dump($row['idx']);
            echo "<br>";
            var_dump($before);
            echo "<br>";
            var_dump($after);
            echo "<br>";
            var_dump($before - $after);
            echo "<br>";
            echo "<br>";
            if ($before - $after > 0) {
                $point_sect = "smspay"; // sms 충전 
                $mile_title = "실패건 환불"; // 포인트 차감 내역
                $mile_sect = "A"; // 포인트  종류 = A : 적립, P : 대기, M : 차감
                $contents_idx = coin_plus_minus_ex($point_sect, $row['member_idx'], $mile_sect, $before - $after, $mile_title, "", "", "", "sms_save", $row['sms_type'], $row['idx']);
                $sql = "UPDATE sms_save SET refund_yn ='Y' WHERE idx = " . $row['idx'];
                $query = mysqli_query($gconnet, $sql);
            }
        }
    }
    //var_dump($row);
}

function mem_current_point_ex($member_idx, $point_sect = "")
{
    global $gconnet;
    if (!$point_sect) {
        $point_sect = "refund"; // 적립금 
    }

    $sql_sub1 = "select cur_mile from member_point where member_idx='" . $member_idx . "' and point_sect='" . $point_sect . "' and mile_sect != 'P' order by idx desc limit 0,1 ";

    $query_sub1 = mysqli_query($gconnet, $sql_sub1);

    if (mysqli_num_rows($query_sub1) == 0) {
        $mile_pre = 0;
    } else {
        $row_sub1 = mysqli_fetch_array($query_sub1);
        $mile_pre = $row_sub1['cur_mile'];
    }

    return $mile_pre;
}

########## 회원의 현재 포인트로 순위추출 
function mem_point_ranking_ex($member_idx, $point_sect = "")
{
    global $gconnet;
    if (!$point_sect) {
        $point_sect = "refund"; // 적립금 
    }

    $mem_current_point = mem_current_point_ex($member_idx, $point_sect);

    $sql_sub1 = "select idx,(select cast(cur_mile as unsigned) from member_point where member_idx=member_info.idx and point_sect='" . $point_sect . "' and mile_sect != 'P' order by idx desc limit 0,1) as cur_coin from member_info where 1 and member_type = 'GEN' and memout_yn != 'Y' and memout_yn != 'S' and (select cast(cur_mile as unsigned) as cur_coin from member_point where member_idx=member_info.idx and point_sect='" . $point_sect . "' and mile_sect != 'P' order by idx desc limit 0,1) >= '" . $mem_current_point . "' and idx != '" . $member_idx . "'";
    $query_sub1 = mysqli_query($gconnet, $sql_sub1);

    $mile_pre = mysqli_num_rows($query_sub1) + 1;
    return $mile_pre;
}

######## 포인트 적립/차감 
function coin_plus_minus_ex($point_sect, $member_idx, $mile_sect, $chg_mile, $mile_title, $order_num = "", $pay_price = "", $ad_sect = "", $board_tbname = "", $board_code = "", $board_idx = "")
{
    global $gconnet;
    //echo "변동되는 값 = ".$chg_mile."<br>";

    if ($chg_mile > 0) {
        $mile_pre = mem_current_point_ex($member_idx, $point_sect); // 현재 적립금 금액

        if ($mile_sect == "A") {
            $cur_mile = $mile_pre + $chg_mile;
        } elseif ($mile_sect == "M") {
            $cur_mile = $mile_pre - $chg_mile;
        }

        if ($cur_mile < 0) {
            $cur_mile = 0;
        }

        $query_mile = " insert into member_point set ";
        $query_mile .= " order_num = '" . $order_num . "', ";
        $query_mile .= " member_idx = '" . $member_idx . "', ";
        $query_mile .= " pay_price = '" . $pay_price . "', ";
        $query_mile .= " mile_title = '" . $mile_title . "', ";
        $query_mile .= " mile_sect = '" . $mile_sect . "', ";
        $query_mile .= " mile_pre = '" . $mile_pre . "', ";
        $query_mile .= " chg_mile = '" . $chg_mile . "', ";
        $query_mile .= " cur_mile = '" . $cur_mile . "', ";
        $query_mile .= " point_sect = '" . $point_sect . "', ";
        $query_mile .= " board_tbname = '" . $board_tbname . "', ";
        $query_mile .= " board_code = '" . $board_code . "', ";
        $query_mile .= " board_idx = '" . $board_idx . "', ";
        $query_mile .= " ad_sect = '" . $ad_sect . "', ";
        $query_mile .= " wdate = now() ";
        //echo $query_mile."<br>";
        $result_mile = mysqli_query($gconnet, $query_mile);

        $contents_idx = mysqli_insert_id($gconnet);
    } else {
        $contents_idx = "";
    }

    return $contents_idx;
}
