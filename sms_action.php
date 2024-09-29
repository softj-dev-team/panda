<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드

header('Content-Type: application/json'); // JSON 응답
set_error_handler("exception_error_handler"); // 오류를 예외로 처리

function normalizeNewlines($string) {
    // Windows 스타일 줄바꿈 \r\n을 Unix 스타일 \n으로 통일
    return str_replace("\r\n", "\n", $string);
}
$send_type = trim(sqlfilter($_REQUEST['send_type']));
$sms_type = trim(sqlfilter($_REQUEST['sms_type']));
$sms_category = trim(sqlfilter($_REQUEST['sms_category']));
$transmit_type = trim(sqlfilter($_REQUEST['transmit_type']));
$act_mode = trim(sqlfilter($_REQUEST['act_mode']));
$file_idx_cp = trim(sqlfilter($_REQUEST['file_idx_cp']));

$member_idx = $_SESSION['member_coinc_idx'];
$sms_title = trim(sqlfilter($_REQUEST['sms_title']));
//$sms_content = trim(sqlfilter($_REQUEST['sms_content']));
$sms_content = normalizeNewlines($_REQUEST['sms_content']);
//$sms_content_ori = normalizeNewlines($_REQUEST['sms_content']);
$division_yn = trim(sqlfilter($_REQUEST['division_yn']));
$division_cnt = trim(sqlfilter($_REQUEST['division_cnt']));
$division_min = trim(sqlfilter($_REQUEST['division_min']));
$reserv_yn = trim(sqlfilter($_REQUEST['reserv_yn']));
$reserv_date = trim(sqlfilter($_REQUEST['reserv_date']));
$reserv_time = trim(sqlfilter($_REQUEST['reserv_time']));
$reserv_minute = trim(sqlfilter($_REQUEST['reserv_minute']));
$cell_send = trim(sqlfilter($_REQUEST['cell_send']));
$adv_company = trim(sqlfilter($_REQUEST['adv_company']));

$my_member_row = get_member_data($_SESSION['member_coinc_idx']);

if ($send_type == "adv" && $transmit_type == "send") {
	$sms_content = "(광고)" . $adv_company . "\n" . $sms_content . "\n무료거부 " . $inc_sms_denie_num;
}

$sms_content_length = mb_strwidth($sms_content, "UTF-8");
$sms_content_strlen = strlen($sms_content_ori);
/*echo "sms_type = ".$sms_type."<br>";
	echo "sms_title = ".$sms_title."<br>";
	echo "sms_content_length = ".$sms_content_length."<br>";*/

$module_type = "";

if ($sms_type == "mms") {
	$module_type = $my_member_row['mms_module_type'];
} else {
    if ($sms_type == "sms") {
        $module_type = $my_member_row['sms_module_type'];

        if ($sms_content_length > 90) {
            $sms_type = "lms";
            $module_type = $my_member_row['lms_module_type'];
//            $sms_title = mb_substr($sms_content, 0, 20);
        }
    }
}


//echo "sms_type 2 = ".$sms_type."<br>";
//exit;

if ($transmit_type == "save") {
	$transmit_msg = "문자내용 저장";
} elseif ($transmit_type == "send") {
	$transmit_msg = "문자전송 등록";
}

// 필요한 변수 초기화
$bbs_code = "sms";
$_P_DIR_FILE = $_P_DIR_FILE . $bbs_code . "/";
$_P_DIR_FILE2 = $_P_DIR_FILE . "img_thumb/";
$board_tbname = "sms_save";
$board_code = "mms";
$file_c = "";

// 스팸 번호 확인 함수
function is_spam_number($cell, $gconnet) {
    $query_spam = "SELECT * FROM spam_list WHERE cell_num = '$cell'";
    $result_spam = mysqli_query($gconnet, $query_spam);

    if (mysqli_num_rows($result_spam) > 0) {
        return true;
    }

    $query_spam_080 = "SELECT * FROM spam_080 WHERE cell_num = '$cell'";
    $result_spam_080 = mysqli_query($gconnet, $query_spam_080);

    return mysqli_num_rows($result_spam_080) > 0;
}

// 문자 수신자 정보 저장 함수
function save_sms_cell($gconnet, $save_idx, $cell, $module_type) {
    $query_sub = "INSERT INTO sms_save_cell (save_idx, cell, module_type, wdate) VALUES ('$save_idx', '$cell', '$module_type', NOW())";
    mysqli_query($gconnet, $query_sub);

    return mysqli_insert_id($gconnet); // 삽입된 ID 반환
}

// 문자 전송 시간 계산 함수
function get_send_time($base_time, $division_yn, $division_min, $division_cnt, $i) {
    if ($division_yn == 'Y') {
        $send_time = clone $base_time;
        $send_time->modify("+" . ($division_min * ceil(($i + 1) / $division_cnt)) . " minutes");
        return $send_time->format('Y-m-d H:i:s');
    }
    return $base_time->format('Y-m-d H:i:s');
}

// 문자 전송 SQL 쿼리 생성 함수
function build_sms_send_query($my_member_row, $sms_type, $sms_content, $sms_title, $fsenddate, $cell, $cell_send, $save_cell_idx, $file_c) {
    if ($sms_type == "sms") {
        $fmsgtype = "0";
        return sprintf(
            "INSERT INTO TBL_SEND_TRAN (fmsgtype, fmessage, fsenddate, fdestine, fcallback, fetc1, fetc4) VALUES ('%s', '%s', '%s', '%s', '%s', '%d', '301230126')",
            $fmsgtype, $sms_content, $fsenddate, $cell, $cell_send, $save_cell_idx
        );
    } elseif ($sms_type == "lms") {
        $fmsgtype = "1";
        return sprintf(
            "INSERT INTO SMS_MAIN_AGENT_JUD1 (MSG_TYPE, S_MSG, S_TEXT, REQUESTDT, RESERVE, RESERVETIME, S_PHONE, S_CALLBACK, S_ETC1, S_ETC6) VALUES ('%s', '%s', '%s', NOW(), 'Y', '%s', '%s', '%d', '301230126')",
            $fmsgtype, $sms_title, $sms_content, $fsenddate, $cell, $cell_send, $save_cell_idx
        );
    } elseif ($sms_type == "mms") {
        $fmsgtype = "3";
        return sprintf(
            "INSERT INTO TBL_SEND_TRAN (fmsgtype, fsubject, fmessage, fsenddate, fdestine, fcallback, ffilepath, fetc1, fetc4) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '301230126')",
            $fmsgtype, $sms_title, $sms_content, $fsenddate, $cell, $cell_send, $_P_DIR_FILE2 . $file_c, $save_cell_idx
        );
    }
}

// 포인트 차감 처리 함수
function deduct_points($my_member_row, $sms_type_txt, $total_send_mny, $receive_cell_num_arr, $member_idx, $save_idx) {
    if ($total_send_mny) {
        $point_sect = "smspay"; // sms 충전
        $mile_title = $sms_type_txt . " " . sizeof($receive_cell_num_arr) . " 건 발송"; // 포인트 차감 내역
        $mile_sect = "M"; // 포인트  종류 = A : 적립, P : 대기, M : 차감
        coin_plus_minus($point_sect, $member_idx, $mile_sect, $total_send_mny, $mile_title, "", "", "", "sms_save", $sms_type, $save_idx);
    }
}
try {
    if ($transmit_type == "send") {
        if (!isset($_REQUEST['receive_cell_num_arr']) || empty($_REQUEST['receive_cell_num_arr'])) {
            throw new Exception('수신자 목록이 비어 있습니다.');
        }

        $receive_cell_num_arr = $_REQUEST['receive_cell_num_arr'];
        if ($sms_type == "sms") {
            $total_send_mny = $my_member_row['mb_short_fee'] * count($receive_cell_num_arr);
        } elseif ($sms_type == "lms") {
            $total_send_mny = $my_member_row['mb_long_fee'] * count($receive_cell_num_arr);
        } elseif ($sms_type == "mms") {
            $total_send_mny = $my_member_row['mb_img_fee'] * count($receive_cell_num_arr);
        } else {
            throw new Exception('유효하지 않은 SMS 유형입니다.');
        }

        // 포인트가 부족한 경우
        if ($total_send_mny > $my_member_row['current_point']) {
            echo json_encode([
                'status' => 'error',
                'message' => '회원님의 현재 충전잔액이 부족합니다. 잔액을 충전해주세요.'
            ]);
            exit;
        }
        // 쿼리 작성
        $query = sprintf(
            "INSERT INTO sms_save SET 
        send_type = '%s',
        sms_type = '%s',
        sms_category = '%s',
        transmit_type = '%s',
        member_idx = '%d',
        sms_title = '%s',
        sms_content = '%s',
        division_yn = '%s',
        division_cnt = %s,
        division_min = %s,
        reserv_yn = '%s',
        reserv_date = '%s',
        reserv_time = '%s',
        reserv_minute = '%s',
        cell_send = '%s',
        module_type = '%s',
        send_ip = '%s',
        wdate = now()",
            $send_type,
            $sms_type,
            $sms_category,
            $transmit_type,
            $member_idx,
            $sms_title,
            $sms_content,
            $division_yn,
            process_division_value($division_cnt),
            process_division_value($division_min),
            $reserv_yn,
            $reserv_date,
            $reserv_time,
            $reserv_minute,
            $cell_send,
            $module_type,
            $_SERVER['REMOTE_ADDR']
        );

        // 쿼리 실행
        $result = mysqli_query($gconnet, $query);

        // 쿼리 실행 성공 여부 확인
        if (!$result) {
            // 쿼리 실행 실패 시 예외 발생
            throw new Exception("쿼리 실행 중 오류가 발생했습니다: " . mysqli_error($gconnet));
        }

        $save_idx = mysqli_insert_id($gconnet);

        // 파일 업로드 여부 확인
        if (isset($_FILES['file_add']) && $_FILES['file_add']['size'] > 0) {
            // 파일 정보와 이미지 크기 설정
            $file_o = $_FILES['file_add']['name'];
            $i_width = 320;
            $i_height = 480;

            // 파일 업로드 처리
            $file_c = uploadFileThumb_1($_FILES, "file_add", $_FILES['file_add'], $_P_DIR_FILE, $i_width, $i_height, $i_width, $i_height, $watermark_sect);

            // 파일 정보 저장 쿼리
            $query_file = sprintf(
                "INSERT INTO board_file (board_tbname, board_code, board_idx, member_idx, file_org, file_chg) 
            VALUES ('%s', '%s', '%d', '%d', '%s', '%s')",
                $board_tbname,
                $board_code,
                $save_idx,
                $member_idx,
                $file_o,
                $file_c
            );

            // 쿼리 실행
            if (!mysqli_query($gconnet, $query_file)) {
                throw new Exception("파일 업로드 쿼리 실행 중 오류가 발생했습니다: " . mysqli_error($gconnet));
            }
        } elseif ($file_idx_cp) {
            // 기존 발송 리스트에서 파일을 복사하는 경우
            $sql_prev = sprintf("SELECT * FROM board_file WHERE idx='%d'", $file_idx_cp);
            $result_prev = mysqli_query($gconnet, $sql_prev);

            if (mysqli_num_rows($result_prev) > 0) {
                $row_prev = mysqli_fetch_assoc($result_prev);
                $file_o = $row_prev['file_org'];
                $file_c = $row_prev['file_chg'];

                // 기존 파일 정보로 새로운 파일 정보 삽입
                $query_file = sprintf(
                    "INSERT INTO board_file (board_tbname, board_code, board_idx, member_idx, file_org, file_chg) 
                VALUES ('%s', '%s', '%d', '%d', '%s', '%s')",
                    $row_prev['board_tbname'],
                    $row_prev['board_code'],
                    $save_idx,
                    $member_idx,
                    $file_o,
                    $file_c
                );

                if (!mysqli_query($gconnet, $query_file)) {
                    throw new Exception("이전 파일 복사 쿼리 실행 중 오류가 발생했습니다: " . mysqli_error($gconnet));
                }
            } else {
                throw new Exception("이전 파일 정보를 찾을 수 없습니다.");
            }
        }

        if ($transmit_type == "send") {
            $receive_cell_num_arr = $_REQUEST['receive_cell_num_arr'];
            $base_time = new DateTime(); // 현재 시간

            // 수신자 목록을 순회하면서 문자 전송
            foreach ($receive_cell_num_arr as $cell) {

                // 광고 전송 시 스팸 필터링 처리
                if ($send_type == "adv") {
                    if (is_spam_number($cell, $gconnet)) {
                        continue; // 스팸 목록에 있으면 전송하지 않음
                    }
                }

                // 문자 전송을 위한 세부 정보 저장
                $save_cell_idx = save_sms_cell($gconnet, $save_idx, $cell, $module_type);

                // 예약 전송 여부 처리
                if ($reserv_yn == "Y") {
                    $base_time = new DateTime("$reserv_date $reserv_time:$reserv_minute:00");
                } else {
                    $base_time = new DateTime();
                }

                // 분할 전송 처리
                $fsenddate = get_send_time($base_time, $division_yn, $division_min, $division_cnt, $i);

                // SMS 전송 쿼리 실행
                $sql_sms_send = build_sms_send_query($my_member_row, $sms_type, $sms_content, $sms_title, $fsenddate, $cell, $cell_send, $save_cell_idx, $file_c);
                if (!mysqli_query($gconnet, $sql_sms_send)) {
                    throw new Exception("문자 전송 중 오류가 발생했습니다: " . mysqli_error($gconnet));
                }
            }

            // 포인트 차감 처리
            deduct_points($my_member_row, $sms_type_txt, $total_send_mny, $receive_cell_num_arr, $member_idx, $save_idx);
        }

        // 성공적인 문자 전송 처리
        echo json_encode([
            'status' => 'success',
            'message' => '문자 전송이 완료되었습니다.'
        ]);
        exit;
    }
}catch (Exception $e) {
    // 예외 발생 시 에러 메시지와 로그 기록
    error_log("Error: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());

    // 클라이언트에 JSON 형식으로 에러 메시지 전송
    echo json_encode([
        'status' => 'error',
        'message' => '오류가 발생했습니다: ' . $e->getMessage()
    ]);
    exit;
}
?>