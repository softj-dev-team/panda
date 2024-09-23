<?php include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드
?>
<?php include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/check_login_frame.php"; // 공통함수 인클루드
?>
<?php
/*echo "<xmp>";
		print_r($_REQUEST);
	echo "</xmp>";
	exit;*/
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
$sms_content = trim(sqlfilter($_REQUEST['sms_content']));
$sms_content = normalizeNewlines($_REQUEST['sms_content']);
$sms_content_ori = normalizeNewlines($_REQUEST['sms_content']);
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
            $sms_title = mb_substr($sms_content, 0, 20);
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

if ($transmit_type == "send") {
	$receive_cell_num_arr = $_REQUEST['receive_cell_num_arr'];

	if ($sms_type == "sms") {
		$total_send_mny = $my_member_row['mb_short_fee'] * sizeof($receive_cell_num_arr);
		$sms_type_txt = "단문";
	} elseif ($sms_type == "lms") {
		$total_send_mny = $my_member_row['mb_long_fee'] * sizeof($receive_cell_num_arr);
		$sms_type_txt = "장문";
	} elseif ($sms_type == "mms") {
		$total_send_mny = $my_member_row['mb_img_fee'] * sizeof($receive_cell_num_arr);
		$sms_type_txt = "이미지";
	}

	if ($total_send_mny > $my_member_row['current_point']) {
		//error_frame("회원님의 현재 충전잔액은 ".number_format($my_member_row['current_point'])." 원이며, 발송에 필요한 금액은 ".number_format($my_member_row['current_point'])." ");
?>
		<script>
			alert("회원님의 현재 충전잔액은 <?= number_format($my_member_row['current_point']) ?> 원이며 \n 발송에 필요한 금액은 <?= number_format($total_send_mny) ?> 원 입니다. \n 잔액을 충전해주세요.");
		</script>
<?
		exit;
	}
}

$query = "insert into sms_save set";
$query .= " send_type = '" . $send_type . "', ";
$query .= " sms_type = '" . $sms_type . "', ";
$query .= " sms_category = '" . $sms_category . "', ";
$query .= " transmit_type = '" . $transmit_type . "', ";
$query .= " member_idx = '" . $member_idx . "', ";
$query .= " sms_title = '" . $sms_title . "', ";
$query .= " sms_content = '" . $sms_content . "', ";
$query .= " division_yn = '" . $division_yn . "', ";
//$query .= " division_cnt = '" . $division_cnt . "', ";
// Check if division_cnt is not empty and is numeric
if (is_numeric($division_cnt) && $division_cnt !== '') {
    $query .= " division_cnt = '" . $division_cnt . "', ";
} else {
    $query .= " division_cnt = null, ";
}
//$query .= " division_min = '" . $division_min . "', ";
if (is_numeric($division_min) && $division_min !== '') {
    $query .= " division_min = '" . $division_min . "', ";
} else {
    $query .= " division_min = null, ";
}
$query .= " reserv_yn = '" . $reserv_yn . "', ";
$query .= " reserv_date = '" . $reserv_date . "', ";
$query .= " reserv_time = '" . $reserv_time . "', ";
$query .= " reserv_minute = '" . $reserv_minute . "', ";
$query .= " cell_send = '" . $cell_send . "', ";
$query .= " module_type = '" . $module_type . "', ";
$query .= " send_ip = '" . $_SERVER["REMOTE_ADDR"] . "', ";
$query .= " wdate = now() ";
//echo $query;
$result = mysqli_query($gconnet, $query);

$save_idx = mysqli_insert_id($gconnet);

$bbs_code = "sms";
$_P_DIR_FILE = $_P_DIR_FILE . $bbs_code . "/";
$_P_DIR_FILE2 = $_P_DIR_FILE . "img_thumb/";
$board_tbname = "sms_save";
$board_code = "mms";
$file_c = "";
if ($_FILES['file_add']['size'] > 0) { // 파일이 있다면 업로드한다 시작
	$file_o = $_FILES['file_add']['name'];
	$i_width = "320";
	$i_height = "480";
	$file_c = uploadFileThumb_1($_FILES, "file_add", $_FILES['file_add'], $_P_DIR_FILE, $i_width, $i_height, $i_width2, $i_height2, $watermark_sect);

	$query_file = "insert into board_file set";
	$query_file .= " board_tbname = '" . $board_tbname . "', ";
	$query_file .= " board_code = '" . $board_code . "', ";
	$query_file .= " board_idx = '" . $save_idx . "', ";
	$query_file .= " member_idx = '" . $member_idx . "', ";
	$query_file .= " file_org = '" . $file_o . "', ";
	$query_file .= " file_chg = '" . $file_c . "' ";
	$result_file = mysqli_query($gconnet, $query_file);
} else {
	if ($file_idx_cp) { // 파일이 없지만 예전 발송 리스트에서 카피할때 
		$sql_prev = "select * from board_file where 1 and idx='" . $file_idx_cp . "'";
		$result_prev = mysqli_query($gconnet, $sql_prev);
		if (mysqli_num_rows($result_prev) > 0) {
			$row_prev = mysqli_fetch_array($result_prev);

			$file_o = $row_prev['file_org'];
			$file_c = $row_prev['file_chg'];

			$query_file = "insert into board_file set";
			$query_file .= " board_tbname = '" . $row_prev['board_tbname'] . "', ";
			$query_file .= " board_code = '" . $row_prev['board_code'] . "', ";
			$query_file .= " board_idx = '" . $save_idx . "', ";
			$query_file .= " member_idx = '" . $member_idx . "', ";
			$query_file .= " file_org = '" . $file_o . "', ";
			$query_file .= " file_chg = '" . $file_c . "' ";
			$result_file = mysqli_query($gconnet, $query_file);
		}
	}
}

########## 문자 전송 테이블에 insert 시작 ##############
if ($transmit_type == "send") {
	$receive_cell_num_arr = $_REQUEST['receive_cell_num_arr'];

	for ($i = 0; $i < sizeof($receive_cell_num_arr); $i++) { // 수신자 루프 시작 
		$cell = $receive_cell_num_arr[$i];

		if ($send_type == "adv") {
			$query_spam = "select * from spam_list where 1 and cell_num='" . $cell . "'";
			$result_spam = mysqli_query($gconnet, $query_spam);

			if (mysqli_num_rows($result_spam) > 0) {
				continue;
			}

			$query_spam_080 = "select * from spam_080 where 1 and cell_num='" . $cell . "'";
			$result_spam_080 = mysqli_query($gconnet, $query_spam_080);

			if (mysqli_num_rows($result_spam_080) > 0) {
				continue;
			}
		}

		$query_sub = "insert into sms_save_cell set";
		$query_sub .= " save_idx = '" . $save_idx . "', ";
		$query_sub .= " cell = '" . $cell . "', ";
		$query_sub .= " module_type = '" . $module_type . "', ";
		$query_sub .= " wdate = now() ";
		$result_sub = mysqli_query($gconnet, $query_sub);

		$save_cell_idx = mysqli_insert_id($gconnet);

		if ($reserv_yn == "Y") {
			$fsenddate = $reserv_date . " " . $reserv_time . ":" . $reserv_minute . ":00";
		}

		if ($sms_type == "sms") { // 단문 
			if ($my_member_row["sms_module_type"] == "LG") {
				$fmsgtype = "0";
				if ($reserv_yn == "Y") {
					$sql_sms_send = "INSERT INTO TBL_SEND_TRAN (fmsgtype, fmessage, fsenddate, fdestine, fcallback,fetc1, fetc4) VALUES ('" . $fmsgtype . "','" . $sms_content . "','" . $fsenddate . "','" . $cell . "','" . $cell_send . "','" . $save_cell_idx . "', '301230126')";
				} else {
					$sql_sms_send = "INSERT INTO TBL_SEND_TRAN (fmsgtype, fmessage, fsenddate, fdestine, fcallback,fetc1, fetc4) VALUES ('" . $fmsgtype . "','" . $sms_content . "',now(),'" . $cell . "','" . $cell_send . "','" . $save_cell_idx . "', '301230126')";
				}
			} else if ($my_member_row["sms_module_type"] == "JUD1") {
				$fmsgtype = "0";
				if ($reserv_yn == "Y") {
					$sql_sms_send = "INSERT INTO SMS_MAIN_AGENT_JUD1 (MSG_TYPE, S_MSG, REQUESTDT, RESERVE, RESERVETIME, S_PHONE, S_CALLBACK ,S_ETC1, S_ETC6) VALUES 
					('" . $fmsgtype . "','" . $sms_content . "',now(), 'Y', '" . $fsenddate . "','" . $cell . "','" . $cell_send . "','" . $save_cell_idx . "', '301230126')";
				} else {
					$sql_sms_send = "INSERT INTO SMS_MAIN_AGENT_JUD1 (MSG_TYPE, S_MSG, REQUESTDT, S_PHONE, S_CALLBACK,S_ETC1, S_ETC6) VALUES ('" . $fmsgtype . "','" . $sms_content . "',now(),'" . $cell . "','" . $cell_send . "','" . $save_cell_idx . "', '301230126')";
				}
			} else if ($my_member_row["sms_module_type"] == "JUD2") {
				$fmsgtype = "0";
				if ($reserv_yn == "Y") {
					$sql_sms_send = "INSERT INTO SMS_MAIN_AGENT_JUD2 (MSG_TYPE, S_MSG, REQUESTDT, RESERVE, RESERVETIME, S_PHONE, S_CALLBACK ,S_ETC1, S_ETC6) VALUES 
					('" . $fmsgtype . "','" . $sms_content . "',now(), 'Y', '" . $fsenddate . "','" . $cell . "','" . $cell_send . "','" . $save_cell_idx . "', '301230126')";
				} else {
					$sql_sms_send = "INSERT INTO SMS_MAIN_AGENT_JUD2 (MSG_TYPE, S_MSG, REQUESTDT, S_PHONE, S_CALLBACK,S_ETC1, S_ETC6) VALUES ('" . $fmsgtype . "','" . $sms_content . "',now(),'" . $cell . "','" . $cell_send . "','" . $save_cell_idx . "', '301230126')";
				}
			}
		} elseif ($sms_type == "lms") { // 장문 

			if ($my_member_row["lms_module_type"] == "LG") {
				$fmsgtype = "2";
				if ($reserv_yn == "Y") {
					$sql_sms_send = "INSERT INTO TBL_SEND_TRAN (fmsgtype, fsubject, fmessage, fsenddate, fdestine, fcallback,fetc1, fetc4) VALUES ('" . $fmsgtype . "','" . $sms_title . "','" . $sms_content . "','" . $fsenddate . "','" . $cell . "','" . $cell_send . "','" . $save_cell_idx . "' , '301230126')";
				} else {
					$sql_sms_send = "INSERT INTO TBL_SEND_TRAN (fmsgtype, fsubject, fmessage, fsenddate, fdestine, fcallback,fetc1, fetc4) VALUES ('" . $fmsgtype . "','" . $sms_title . "','" . $sms_content . "',now(),'" . $cell . "','" . $cell_send . "','" . $save_cell_idx . "' , '301230126')";
				}
			} else if ($my_member_row["lms_module_type"] == "JUD1") {
				$fmsgtype = "1";
				if ($reserv_yn == "Y") {
					$sql_sms_send = "INSERT INTO SMS_MAIN_AGENT_JUD1 (MSG_TYPE, S_MSG, S_TEXT, REQUESTDT, RESERVE, RESERVETIME, S_PHONE, S_CALLBACK ,S_ETC1, S_ETC6) VALUES 
					('" . $fmsgtype . "','" . $sms_title . "','" . $sms_content . "',now(), 'Y', '" . $fsenddate . "','" . $cell . "','" . $cell_send . "','" . $save_cell_idx . "', '301230126')";
				} else {
					$sql_sms_send = "INSERT INTO SMS_MAIN_AGENT_JUD1 (MSG_TYPE, S_MSG, S_TEXT, REQUESTDT, S_PHONE, S_CALLBACK,S_ETC1, S_ETC6) VALUES ('" . $fmsgtype . "','" . $sms_title . "','" . $sms_content . "',now(),'" . $cell . "','" . $cell_send . "','" . $save_cell_idx . "', '301230126')";
				}
			} else if ($my_member_row["lms_module_type"] == "JUD2") {
				$fmsgtype = "1";
				if ($reserv_yn == "Y") {
					$sql_sms_send = "INSERT INTO SMS_MAIN_AGENT_JUD2 (MSG_TYPE, S_MSG, S_TEXT, REQUESTDT, RESERVE, RESERVETIME, S_PHONE, S_CALLBACK ,S_ETC1, S_ETC6) VALUES 
					('" . $fmsgtype . "','" . $sms_title . "','" . $sms_content . "',now(), 'Y', '" . $fsenddate . "','" . $cell . "','" . $cell_send . "','" . $save_cell_idx . "', '301230126')";
				} else {
					$sql_sms_send = "INSERT INTO SMS_MAIN_AGENT_JUD2 (MSG_TYPE, S_MSG, S_TEXT, REQUESTDT, S_PHONE, S_CALLBACK,S_ETC1, S_ETC6) VALUES ('" . $fmsgtype . "','" . $sms_title . "','" . $sms_content . "',now(),'" . $cell . "','" . $cell_send . "','" . $save_cell_idx . "', '301230126')";
				}
			}
		} elseif ($sms_type == "mms") { // 이미지 

			if ($my_member_row["lms_module_type"] == "LG") {
				$fmsgtype = "3";
				$ffilepath = $_P_DIR_FILE2 . $file_c;
				if ($reserv_yn == "Y") {
					$sql_sms_send = "INSERT INTO TBL_SEND_TRAN (fmsgtype, fsubject, fmessage, fsenddate, fdestine, fcallback, ffilepath,fetc1, fetc4) VALUES ('" . $fmsgtype . "','" . $sms_title . "','" . $sms_content . "','" . $fsenddate . "','" . $cell . "','" . $cell_send . "','" . $ffilepath . "','" . $save_cell_idx . "', '301230126')";
				} else {
					$sql_sms_send = "INSERT INTO TBL_SEND_TRAN (fmsgtype, fsubject, fmessage, fsenddate, fdestine, fcallback, ffilepath,fetc1, fetc4) VALUES ('" . $fmsgtype . "','" . $sms_title . "','" . $sms_content . "',now(),'" . $cell . "','" . $cell_send . "','" . $ffilepath . "','" . $save_cell_idx . "', '301230126')";
				}
			} else if ($my_member_row["lms_module_type"] == "JUD1") {
				$fmsgtype = "1";
				$file_cnt = 0;
				if ($file_c) {
					$ffilepath = $_P_DIR_FILE2 . $file_c;
					$file_cnt = 1;
				} else {
					$ffilepath = "";
					$file_cnt = 0;
				}
				if ($reserv_yn == "Y") {
					$sql_sms_send = "INSERT INTO SMS_MAIN_AGENT_JUD1 (MSG_TYPE, S_MSG, S_TEXT, REQUESTDT,RESERVE, RESERVETIME, S_PHONE, S_CALLBACK,FILE_PATH1,S_ETC1, S_ETC6, FILE_CNT) VALUES 
					('" . $fmsgtype . "','" . $sms_title . "','" . $sms_content . "',now(), 'Y', '" . $fsenddate . "','" . $cell . "','" . $cell_send . "','" . $ffilepath . "','" . $save_cell_idx . "', '301230126', " . $file_cnt . ")";
				} else {
					$sql_sms_send = "INSERT INTO SMS_MAIN_AGENT_JUD1 (MSG_TYPE, S_MSG, S_TEXT, REQUESTDT, S_PHONE, S_CALLBACK,FILE_PATH1,S_ETC1, S_ETC6, FILE_CNT) VALUES ('" . $fmsgtype . "','" . $sms_title . "','" . $sms_content . "',now(),'" . $cell . "','" . $cell_send . "','" . $ffilepath . "','" . $save_cell_idx . "', '301230126', " . $file_cnt . ")";
				}
			} else if ($my_member_row["lms_module_type"] == "JUD2") {
				$fmsgtype = "1";
				$file_cnt = 0;
				if ($file_c) {
					$ffilepath = $_P_DIR_FILE2 . $file_c;
					$file_cnt = 1;
				} else {
					$ffilepath = "";
					$file_cnt = 0;
				}
				if ($reserv_yn == "Y") {
					$sql_sms_send = "INSERT INTO SMS_MAIN_AGENT_JUD2 (MSG_TYPE, S_MSG, S_TEXT, REQUESTDT,RESERVE, RESERVETIME, S_PHONE, S_CALLBACK,FILE_PATH1,S_ETC1, S_ETC6, FILE_CNT) VALUES 
					('" . $fmsgtype . "','" . $sms_title . "','" . $sms_content . "',now(), 'Y', '" . $fsenddate . "','" . $cell . "','" . $cell_send . "','" . $ffilepath . "','" . $save_cell_idx . "', '301230126', " . $file_cnt . ")";
				} else {
					$sql_sms_send = "INSERT INTO SMS_MAIN_AGENT_JUD2 (MSG_TYPE, S_MSG, S_TEXT, REQUESTDT, S_PHONE, S_CALLBACK,FILE_PATH1 ,S_ETC1, S_ETC6, FILE_CNT) VALUES ('" . $fmsgtype . "','" . $sms_title . "','" . $sms_content . "',now(),'" . $cell . "','" . $cell_send . "','" . $ffilepath . "','" . $save_cell_idx . "', '301230126' , " . $file_cnt . ")";
				}
			}
		} // 단문, 장문, 이미지 모두 종료 

		$query_sms_send = mysqli_query($gconnet, $sql_sms_send);
	} // 수신자 루프 종료

	######## 포인트 차감 시작 ##########
	if ($total_send_mny) {
		$point_sect = "smspay"; // sms 충전 
		$mile_title = $sms_type_txt . " " . sizeof($receive_cell_num_arr) . " 건 발송"; // 포인트 차감 내역
		$mile_sect = "M"; // 포인트  종류 = A : 적립, P : 대기, M : 차감
		$contents_idx = coin_plus_minus($point_sect, $member_idx, $mile_sect, $total_send_mny, $mile_title, "", "", "", "sms_save", $sms_type, $save_idx);
	}
	######## 포인트 차감 종료 ##########
}
########## 문자 전송 테이블에 insert 종료 ##############

if ($result) {
	if ($act_mode == "inner") {
		error_frame($transmit_msg . " 되었습니다.");
	} else {
		error_frame_reload($transmit_msg . " 되었습니다.");
	}
} else {
    error_log(" failed: " . mysqli_error($gconnet));
	error_frame("일시적으로 오류가 발생했습니다. 잠시뒤 다시 시도해주세요.");
}
?>