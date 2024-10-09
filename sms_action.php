<?php
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 로그인 확인

header('Content-Type: application/json'); // JSON 응답
set_error_handler("exception_error_handler"); // 오류를 예외로 처리

function normalizeNewlines($string) {
    // Windows 스타일 줄바꿈 \r\n을 Unix 스타일 \n으로 통일
    return str_replace("\r\n", "\n", $string);
}

// 요청 변수 필터링
$send_type = trim(sqlfilter($_REQUEST['send_type']));
$sms_type = trim(sqlfilter($_REQUEST['sms_type']));
$sms_category = trim(sqlfilter($_REQUEST['sms_category']));
$transmit_type = trim(sqlfilter($_REQUEST['transmit_type']));
$act_mode = trim(sqlfilter($_REQUEST['act_mode']));
$file_idx_cp = trim(sqlfilter($_REQUEST['file_idx_cp']));

$member_idx = $_SESSION['member_coinc_idx'];
$sms_title = trim(sqlfilter($_REQUEST['sms_title']));
$sms_content = normalizeNewlines($_REQUEST['sms_content']);
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

$module_type = "";

if ($sms_type == "mms") {
    $module_type = $my_member_row['mms_module_type'];
} else {
    if ($sms_type == "sms") {
        $module_type = $my_member_row['sms_module_type'];

        if ($sms_content_length > 90) {
            $sms_type = "lms";
            $module_type = $my_member_row['lms_module_type'];
        }
    }
}

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

// 스팸 번호 목록 로드 함수
function load_spam_numbers($gconnet) {
    $spam_numbers = [];

    $query_spam = "SELECT cell_num FROM spam_list";
    $result_spam = mysqli_query($gconnet, $query_spam);
    while ($row = mysqli_fetch_assoc($result_spam)) {
        $spam_numbers[$row['cell_num']] = true;
    }

    $query_spam_080 = "SELECT cell_num FROM spam_080";
    $result_spam_080 = mysqli_query($gconnet, $query_spam_080);
    while ($row = mysqli_fetch_assoc($result_spam_080)) {
        $spam_numbers[$row['cell_num']] = true;
    }

    return $spam_numbers;
}

// 스팸 번호 확인 함수
function is_spam_number($cell, $spam_numbers) {
    return isset($spam_numbers[$cell]);
}

// 문자 전송 시간 계산 함수
function get_send_time($base_time, $division_yn, $division_min, $division_cnt, $i) {
    if ($division_yn == 'Y') {
        $send_time = clone $base_time;
        $send_time->modify("+" . ($division_min * floor($i / $division_cnt)) . " minutes");
        return $send_time->format('Y-m-d H:i:s');
    }
    return $base_time->format('Y-m-d H:i:s');
}

// 포인트 차감 처리 함수
function deduct_points($my_member_row, $sms_type_txt, $total_send_mny, $receive_cell_num_arr, $member_idx, $save_idx) {
    if ($total_send_mny) {
        $point_sect = "smspay"; // sms 충전
        $mile_title = $sms_type_txt . " " . sizeof($receive_cell_num_arr) . " 건 발송"; // 포인트 차감 내역
        $mile_sect = "M"; // 포인트 종류 = A : 적립, P : 대기, M : 차감
        coin_plus_minus($point_sect, $member_idx, $mile_sect, $total_send_mny, $mile_title, "", "", "", "sms_save", $sms_type, $save_idx);
    }
}

// SQL 인젝션 방지를 위한 mysqli_real_escape_string 적용 함수
function escape_string($conn, $string) {
    return mysqli_real_escape_string($conn, $string);
}

try {
    if ($transmit_type == "send") {
        if (!isset($_REQUEST['receive_cell_num_arr']) || empty($_REQUEST['receive_cell_num_arr'])) {
            throw new Exception('수신자 목록이 비어 있습니다.');
        }

        $receive_cell_num_arr = $_REQUEST['receive_cell_num_arr'];
        if ($sms_type == "sms") {
            $total_send_mny = $my_member_row['mb_short_fee'] * count($receive_cell_num_arr);
            $sms_type_txt = "SMS";
        } elseif ($sms_type == "lms") {
            $total_send_mny = $my_member_row['mb_long_fee'] * count($receive_cell_num_arr);
            $sms_type_txt = "LMS";
        } elseif ($sms_type == "mms") {
            $total_send_mny = $my_member_row['mb_img_fee'] * count($receive_cell_num_arr);
            $sms_type_txt = "MMS";
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

        // 쿼리 작성 (SQL 인젝션 방지를 위해 mysqli_real_escape_string 사용)
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
            division_cnt = '%s',
            division_min = '%s',
            reserv_yn = '%s',
            reserv_date = '%s',
            reserv_time = '%s',
            reserv_minute = '%s',
            cell_send = '%s',
            module_type = '%s',
            send_ip = '%s',
            wdate = now()",
            escape_string($gconnet, $send_type),
            escape_string($gconnet, $sms_type),
            escape_string($gconnet, $sms_category),
            escape_string($gconnet, $transmit_type),
            $member_idx,
            escape_string($gconnet, $sms_title),
            escape_string($gconnet, $sms_content),
            escape_string($gconnet, $division_yn),
            escape_string($gconnet, $division_cnt),
            escape_string($gconnet, $division_min),
            escape_string($gconnet, $reserv_yn),
            escape_string($gconnet, $reserv_date),
            escape_string($gconnet, $reserv_time),
            escape_string($gconnet, $reserv_minute),
            escape_string($gconnet, $cell_send),
            escape_string($gconnet, $module_type),
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
                escape_string($gconnet, $file_o),
                escape_string($gconnet, $file_c)
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
                    escape_string($gconnet, $file_o),
                    escape_string($gconnet, $file_c)
                );

                if (!mysqli_query($gconnet, $query_file)) {
                    throw new Exception("이전 파일 복사 쿼리 실행 중 오류가 발생했습니다: " . mysqli_error($gconnet));
                }
            } else {
                throw new Exception("이전 파일 정보를 찾을 수 없습니다.");
            }
        }

        if ($transmit_type == "send") {
            // 스팸 번호 목록을 미리 로드
            $spam_numbers = load_spam_numbers($gconnet);

            // 예약 전송 여부 처리
            if ($reserv_yn == "Y") {
                $base_time = new DateTime("$reserv_date $reserv_time:$reserv_minute:00");
            } else {
                $base_time = new DateTime();
            }

            $cells_data = [];
            $i = 0;

            // 수신자 목록을 순회하면서 데이터 준비
            foreach ($receive_cell_num_arr as $cell) {
                // 광고 전송 시 스팸 필터링 처리
                if ($send_type == "adv" && is_spam_number($cell, $spam_numbers)) {
                    continue; // 스팸 목록에 있으면 전송하지 않음
                }

                // 분할 전송 처리
                $fsenddate = get_send_time($base_time, $division_yn, $division_min, $division_cnt, $i);

                // 수신자 정보를 배열에 저장
                $cells_data[] = [
                    'cell' => $cell,
                    'fsenddate' => $fsenddate
                ];
                $i++;
            }

            if (empty($cells_data)) {
                throw new Exception('유효한 수신자 번호가 없습니다.');
            }

            // 트랜잭션 시작
            mysqli_begin_transaction($gconnet);

            try {
                // SMS 수신자 정보 일괄 저장
                $values = [];
                foreach ($cells_data as $data) {
                    $cell_escaped = escape_string($gconnet, $data['cell']);
                    $values[] = "('$save_idx', '$cell_escaped', '$module_type', NOW())";
                }
                $query_sub = "INSERT INTO sms_save_cell (save_idx, cell, module_type, wdate) VALUES " . implode(',', $values);
                mysqli_query($gconnet, $query_sub);

                // 삽입된 ID들을 가져오기 위해 LAST_INSERT_ID() 사용
                $first_save_cell_idx = mysqli_insert_id($gconnet);

                // 각 수신자별로 save_cell_idx 매핑
                foreach ($cells_data as $index => $data) {
                    $cells_data[$index]['save_cell_idx'] = $first_save_cell_idx + $index;
                }

                // 모듈 타입별로 SMS 전송 데이터를 분리
                $module_sms_data = [];
                $module_fields_str = [];

                foreach ($cells_data as $data) {
                    $cell = escape_string($gconnet, $data['cell']);
                    $save_cell_idx = $data['save_cell_idx'];
                    $fsenddate = $data['fsenddate'];

                    // 메시지 모듈 타입 결정
                    $module_type = $my_member_row["sms_module_type"] ?? $my_member_row["lms_module_type"];

                    // 예약 여부 판단
                    $current_time = new DateTime();
                    $send_time = new DateTime($fsenddate);
                    $is_reserved = ($send_time > $current_time);

                    // 메시지 타입 설정
                    $fmsgtype_map = [
                        'sms' => '0',
                        'lms' => ($module_type == 'LG') ? '2' : '1',
                        'mms' => ($module_type == 'LG') ? '3' : '1',
                    ];
                    $fmsgtype = $fmsgtype_map[$sms_type];

                    // 이미지 파일 경로 설정
                    $ffilepath = '';
                    $file_cnt = 0;
                    if ($sms_type == 'mms' && $file_c) {
                        $ffilepath = $_P_DIR_FILE2 . $file_c;
                        $file_cnt = 1;
                    }

                    // 모듈 타입별 테이블 매핑
                    $module_table_map = [
                        'LG'   => 'TBL_SEND_TRAN',
                        'JUD1' => 'SMS_MAIN_AGENT_JUD1',
                        'JUD2' => 'SMS_MAIN_AGENT_JUD2',
                    ];
                    $table_name = $module_table_map[$module_type];

                    // 공통 필드 및 값 설정
                    $fields = [];
                    $values = [];

                    if ($module_type == 'LG') {
                        // LG 모듈 필드 설정
                        $fields = ['fmsgtype', 'fsenddate', 'fdestine', 'fcallback', 'fetc1', 'fetc4'];
                        $values = [
                            $fmsgtype,
                            $fsenddate,
                            $cell,
                            $cell_send,
                            $save_cell_idx,
                            '301230126'
                        ];

                        if ($sms_type == 'sms') {
                            $fields[] = 'fmessage';
                            $values[] = $sms_content;
                        } else {
                            $fields[] = 'fsubject';
                            $values[] = $sms_title;
                            $fields[] = 'fmessage';
                            $values[] = $sms_content;
                        }

                        if ($sms_type == 'mms' && $ffilepath) {
                            $fields[] = 'ffilepath';
                            $values[] = $ffilepath;
                        }
                    } else {
                        // JUD 모듈 필드 설정
                        $fields = ['MSG_TYPE', 'REQUESTDT', 'S_PHONE', 'S_CALLBACK', 'S_ETC1', 'S_ETC6'];
                        $values = [
                            $fmsgtype,
                            date('Y-m-d H:i:s'),
                            $cell,
                            $cell_send,
                            $save_cell_idx,
                            '301230126'
                        ];

                        if ($is_reserved) {
                            $fields[] = 'RESERVE';
                            $values[] = 'Y';
                            $fields[] = 'RESERVETIME';
                            $values[] = $fsenddate;
                        }

                        if ($sms_type == 'sms') {
                            $fields[] = 'S_MSG';
                            $values[] = $sms_content;
                        } else {
                            $fields[] = 'S_MSG';
                            $values[] = $sms_title;
                            $fields[] = 'S_TEXT';
                            $values[] = $sms_content;
                        }

                        if ($sms_type == 'mms' && $ffilepath) {
                            $fields[] = 'FILE_PATH1';
                            $values[] = $ffilepath;
                            $fields[] = 'FILE_CNT';
                            $values[] = $file_cnt;
                        }
                    }

                    // 모듈 타입별로 필드와 값을 저장
                    $module_key = $module_type;

                    // 필드 문자열 저장 (모든 레코드에서 동일한 필드 구성을 사용하기 위해)
                    if (!isset($module_fields_str[$module_key])) {
                        $module_fields_str[$module_key] = implode(', ', $fields);
                    }

                    // 값들을 placeholders 없이 직접 저장
                    $escaped_values = array_map(function($value) use ($gconnet) {
                        return "'" . escape_string($gconnet, $value) . "'";
                    }, $values);

                    $module_sms_data[$module_key]['table_name'] = $table_name;
                    $module_sms_data[$module_key]['values'][] = '(' . implode(', ', $escaped_values) . ')';
                }

                // 모듈 타입별로 쿼리를 생성 및 실행
                foreach ($module_sms_data as $module_key => $data) {
                    $table_name = $data['table_name'];
                    $fields_str = $module_fields_str[$module_key];
                    $values_str = implode(',', $data['values']);

                    $sms_send_query = "INSERT INTO $table_name ($fields_str) VALUES $values_str";
                    if (!mysqli_query($gconnet, $sms_send_query)) {
                        throw new Exception("문자 전송 중 오류가 발생했습니다: " . mysqli_error($gconnet));
                    }
                }

                // 트랜잭션 커밋
                mysqli_commit($gconnet);
            } catch (Exception $e) {
                // 트랜잭션 롤백
                mysqli_rollback($gconnet);
                throw $e;
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
    }else if($transmit_type=='save'){
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
            division_cnt = '%s',
            division_min = '%s',
            reserv_yn = '%s',
            reserv_date = '%s',
            reserv_time = '%s',
            reserv_minute = '%s',
            cell_send = '%s',
            module_type = '%s',
            send_ip = '%s',
            wdate = now()",
            escape_string($gconnet, $send_type),
            escape_string($gconnet, $sms_type),
            escape_string($gconnet, $sms_category),
            escape_string($gconnet, $transmit_type),
            $member_idx,
            escape_string($gconnet, $sms_title),
            escape_string($gconnet, $sms_content),
            escape_string($gconnet, $division_yn),
            escape_string($gconnet, $division_cnt),
            escape_string($gconnet, $division_min),
            escape_string($gconnet, $reserv_yn),
            escape_string($gconnet, $reserv_date),
            escape_string($gconnet, $reserv_time),
            escape_string($gconnet, $reserv_minute),
            escape_string($gconnet, $cell_send),
            escape_string($gconnet, $module_type),
            $_SERVER['REMOTE_ADDR']
        );
        $result = mysqli_query($gconnet, $query);
        // 쿼리 실행 성공 여부 확인
        if (!$result) {
            // 쿼리 실행 실패 시 예외 발생
            throw new Exception("쿼리 실행 중 오류가 발생했습니다: " . mysqli_error($gconnet));
        }

        mysqli_commit($gconnet);
        // 성공적인 문자 전송 처리
        echo json_encode([
            'status' => 'success',
            'message' => '문자 내용 저장이 완료되었습니다.'
        ]);
        exit;
    }
} catch (Exception $e) {
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
