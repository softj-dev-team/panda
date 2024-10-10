<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더
?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/master/include/check_login_frame.php"; // 관리자 로그인여부 확인
?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$total_param = trim(sqlfilter($_REQUEST['total_param']));
$s_gubun = trim(sqlfilter($_REQUEST['s_gubun']));

$member_sect_str = "의뢰자";

$user_id = trim(sqlfilter($_REQUEST['member_id']));
if ($_REQUEST['member_password']) {
	$user_pwd = trim(sqlfilter($_REQUEST['member_password']));
	$user_pwd = md5($user_pwd);
}
$user_name = trim(sqlfilter($_REQUEST['member_name']));
$company_name = trim(sqlfilter($_REQUEST['company_name']));
$company_yn = isset($_REQUEST['company_yn'])??'';
$tel1 = trim(sqlfilter($_REQUEST['tel1']));
$tel2 = trim(sqlfilter($_REQUEST['tel2']));
$tel3 = trim(sqlfilter($_REQUEST['tel3']));
$tel = $tel1 . "-" . $tel2 . "-" . $tel3;
/*$cell1 = trim(sqlfilter($_REQUEST['cell1']));
	$cell2 = trim(sqlfilter($_REQUEST['cell2']));
	$cell3 = trim(sqlfilter($_REQUEST['cell3']));
	$cell = $cell1."-".$cell2."-".$cell3;*/
$cell = trim(sqlfilter($_REQUEST['cell']));
$cell = str_replace("-", "", $cell);

$bank_name = trim(sqlfilter($_REQUEST['bank_name']));
$bank_num = trim(sqlfilter($_REQUEST['bank_num']));
$user_nick = trim(sqlfilter($_REQUEST['user_nick']));
$email = trim(sqlfilter($_REQUEST['member_email']));
//$email = $user_id;
$partner_id = trim(sqlfilter($_REQUEST['partner_id']));
$master_ok = trim(sqlfilter($_REQUEST['master_ok']));
$member_gubun = trim(sqlfilter($_REQUEST['member_gubun']));

$post = trim(sqlfilter($_REQUEST['zip_code1']));
$addr1 = trim(sqlfilter($_REQUEST['member_address']));
$addr2 = trim(sqlfilter($_REQUEST['member_address2']));
$m_channel = trim(sqlfilter($_REQUEST['m_channel']));
$m_intro = trim(sqlfilter($_REQUEST['m_intro']));
$m_purpose = trim(sqlfilter($_REQUEST['m_purpose']));

$birthday = trim(sqlfilter($_REQUEST['birthday']));
$sms_module_type = trim(sqlfilter($_REQUEST['sms_module_type']));
$lms_module_type = trim(sqlfilter($_REQUEST['lms_module_type']));
$mms_module_type = trim(sqlfilter($_REQUEST['mms_module_type']));

$mail_ok = "Y"; // 이메일 수신 허용

$sql_pre1 = "select idx from member_info where user_id = '" . $user_id . "' and idx != '" . $idx . "' and del_yn='N'"; // 회원테이블 아이디 중복여부 체크
$result_pre1  = mysqli_query($gconnet, $sql_pre1);
if (mysqli_num_rows($result_pre1) > 0) {
	error_frame("입력하신 아이디는 이미 사용중입니다. 다시 확인하시고 입력해 주세요.");
}

if ($user_nick) { // 닉네임을 입력했을때 
	$sql_pre4 = "select idx from member_info where user_nick = '" . $user_nick . "' and idx != '" . $idx . "' and del_yn='N'";
	$result_pre4  = mysqli_query($gconnet, $sql_pre4);
	if (mysqli_num_rows($result_pre4) > 0) {
		error_frame("입력하신 닉네임은 이미 사용중입니다. 다시 확인하시고 입력해 주세요.");
	}
} // 닉네임을 입력했을때 종료

if ($email) { // 이메일을 입력했을때 
	$sql_pre4 = "select idx from member_info where email = '" . $email . "' and idx != '" . $idx . "' and del_yn='N'";
	$result_pre4  = mysqli_query($gconnet, $sql_pre4);
	if (mysqli_num_rows($result_pre4) > 0) {
		error_frame("입력하신 이메일은 이미 사용중입니다. 다시 확인하시고 입력해 주세요.");
	}
} // 이메일을 입력했을때 종료

if ($cell) { // 휴대전화 입력했을때 
	$sql_pre3 = "select idx from member_info where cell = '" . $cell . "' and idx != '" . $idx . "' and del_yn='N'";
	$result_pre3  = mysqli_query($gconnet, $sql_pre3);
	if (mysqli_num_rows($result_pre3) > 0) {
		//error_frame("입력하신 휴대전화는 이미 사용중입니다. 다시 확인하시고 입력해 주세요.");
	}
} // 휴대전화 입력했을때 종료

if ($partner_id) {
	$partner_sql = "select idx from member_info_company where 1 and is_del='N' and member_idx in (select idx from member_info where 1 and del_yn='N' and  memout_yn not in ('Y','S') and user_id='" . $partner_id . "')"; // 가맹점 아이디로 pk 추출  
	$partner_query = mysqli_query($gconnet, $partner_sql);
	$partner_row = mysqli_fetch_array($partner_query);
	$partner_idx = $partner_row['idx'];
}

$query = " update member_info set ";
$query .= " master_ok = '" . $master_ok . "', ";
$query .= " member_gubun = '" . $member_gubun . "', ";
$query .= " user_id = '" . $user_id . "', ";
if ($_REQUEST['member_password']) {
	$query .= " user_pwd = '" . $user_pwd . "', ";
}
$query .= " user_name = '" . $user_name . "', ";
$query .= " company_name = '" . $company_name . "', ";
$query .= " company_yn = '" . $company_yn . "', ";
$query .= " tel = '" . $tel . "', ";
$query .= " cell = '" . $cell . "', ";
$query .= " birthday = '" . $birthday . "', ";
$query .= " user_nick = '" . $user_nick . "', ";
$query .= " email = '" . $email . "', ";
$query .= " partner_idx = '" . $partner_idx . "', ";
$query .= " post = '" . $post . "', ";
$query .= " sms_module_type = '" . $sms_module_type . "', ";
$query .= " lms_module_type = '" . $lms_module_type . "', ";
$query .= " mms_module_type = '" . $mms_module_type . "', ";
$query .= " addr1 = '" . $addr1 . "', ";
$query .= " addr2 = '" . $addr2 . "' ";
$query .= " where 1 and idx = '" . $idx . "' ";
var_dump($query);
$result = mysqli_query($gconnet, $query);

//echo $query; exit;

$member_idx = $idx;

################ 추가정보 입력 시작 ##############

$run_code = trim(sqlfilter($_REQUEST['run_code']));
$mb_short_fee = trim(sqlfilter($_REQUEST['mb_short_fee']));
$mb_long_fee = trim(sqlfilter($_REQUEST['mb_long_fee']));
$mb_img_fee = trim(sqlfilter($_REQUEST['mb_img_fee']));
$mb_kko_fee = trim(sqlfilter($_REQUEST['mb_kko_fee']));
$mb_short_cnt = trim(sqlfilter($_REQUEST['mb_short_cnt']));
$mb_long_cnt = trim(sqlfilter($_REQUEST['mb_long_cnt']));
$mb_img_cnt = trim(sqlfilter($_REQUEST['mb_img_cnt']));

$call_num = json_encode($_REQUEST['call_num'], JSON_UNESCAPED_UNICODE);
$call_memo = json_encode($_REQUEST['call_memo'], JSON_UNESCAPED_UNICODE);
$use_yn = json_encode($_REQUEST['use_yn'], JSON_UNESCAPED_UNICODE);
$auth_method=json_encode($_REQUEST['auth_method'], JSON_UNESCAPED_UNICODE);
$prev_sql = "select idx from member_info_sendinfo where 1 and member_idx='" . $member_idx . "' and is_del='N'";
$prev_query = mysqli_query($gconnet, $prev_sql);
$prev_cnt = mysqli_num_rows($prev_query);

if ($prev_cnt == 0) {
	$query_add = "insert into member_info_sendinfo set";
	$query_add .= " member_idx = '" . $member_idx . "', ";
	$query_add .= " run_code = '" . $run_code . "', ";
	$query_add .= " auth_yn = '" . $auth_yn . "', ";
	$query_add .= " auth_gubun = '" . $auth_gubun . "', ";
	$query_add .= " mb_short_fee = '" . $mb_short_fee . "', ";
	$query_add .= " mb_long_fee = '" . $mb_long_fee . "', ";
	$query_add .= " mb_img_fee = '" . $mb_img_fee . "', ";
    $query_add .= " mb_kko_fee = '" . $mb_kko_fee . "', ";
	$query_add .= " mb_short_cnt = '" . $mb_short_cnt . "', ";
	$query_add .= " mb_long_cnt = '" . $mb_long_cnt . "', ";
	$query_add .= " mb_img_cnt = '" . $mb_img_cnt . "', ";
	$query_add .= " call_num = '" . $call_num . "', ";
	$query_add .= " call_memo = '" . $call_memo . "', ";
    $query_add .= " auth_method = '" . $auth_method . "', ";
	$query_add .= " use_yn = '" . $use_yn . "', ";
	$query_add .= " wdate = now() ";
	//echo $query_add;
	$result_add = mysqli_query($gconnet, $query_add);

	$sendinfo_idx = mysqli_insert_id($gconnet);
} else {
	$prev_row = mysqli_fetch_array($prev_query);
	$sendinfo_idx = $prev_row['idx'];

	$query_add = "update member_info_sendinfo set";
	$query_add .= " run_code = '" . $run_code . "', ";
	$query_add .= " auth_yn = '" . $auth_yn . "', ";
	$query_add .= " auth_gubun = '" . $auth_gubun . "', ";
	$query_add .= " mb_short_fee = '" . $mb_short_fee . "', ";
	$query_add .= " mb_long_fee = '" . $mb_long_fee . "', ";
	$query_add .= " mb_img_fee = '" . $mb_img_fee . "', ";
    $query_add .= " mb_kko_fee = '" . $mb_kko_fee . "', ";
	$query_add .= " mb_short_cnt = '" . $mb_short_cnt . "', ";
	$query_add .= " mb_long_cnt = '" . $mb_long_cnt . "', ";
	$query_add .= " mb_img_cnt = '" . $mb_img_cnt . "', ";
	$query_add .= " call_num = '" . $call_num . "', ";
	$query_add .= " call_memo = '" . $call_memo . "', ";
    $query_add .= " auth_method = '" . $auth_method . "', ";
	$query_add .= " use_yn = '" . $use_yn . "', ";
	$query_add .= " wdate = now() ";
	$query_add .= " where 1 and member_idx='" . $member_idx . "' and is_del='N'";
	$result_add = mysqli_query($gconnet, $query_add);
}
################ 추가정보 입력 종료 ##############

################# 첨부파일 업로드 시작 #######################
$bbs = "certi";
$_P_DIR_FILE = $_P_DIR_FILE . $bbs . "/";
$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE . $bbs . "/";

$board_tbname = "member_info_sendinfo";
$board_code = "commu_certi";

$sql_file = "select idx from board_file where 1 and board_tbname='" . $board_tbname . "' and board_code='" . $board_code . "' and board_idx='" . $sendinfo_idx . "'";
$query_file = mysqli_query($gconnet, $sql_file);
$cnt_file = mysqli_num_rows($query_file);

if ($cnt_file < 1) {
	$cnt_file = 1;
}

for ($file_i = 0; $file_i < $cnt_file; $file_i++) { // 설정된 갯수만큼 루프 시작

	$file_idx = trim(sqlfilter($_REQUEST['file_idx_' . $file_i])); // 기존 첨부파일 DB PK 값.
	$file_old_name = trim(sqlfilter($_REQUEST['file_old_name_' . $file_i])); // 원본 서버파일 이름
	$file_old_org = trim(sqlfilter($_REQUEST['file_old_org_' . $file_i]));	// 원본 오리지널 파일 이름
	$del_org = trim(sqlfilter($_REQUEST['del_org_' . $file_i]));	// 원본 파일 삭제여부

	if ($_FILES['file_' . $file_i]['size'] > 0) { // 파일이 있다면 업로드한다 시작

		if ($file_old_name) {
			unlink($_P_DIR_FILE . $file_old_name); // 원본파일 삭제
		}

		/*if($bbs_code == "event"){
				$file_o = $_FILES['file_'.$file_i]['name']; 
				$i_width = "280";
				$i_height = "184";
				$file_c = uploadFileThumb_1($_FILES, "file_".$file_i, $_FILES['file_'.$file_i], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$watermark_sect);
			} else {*/
		$file_o = $_FILES['file_' . $file_i]['name'];
		$file_c = uploadFile($_FILES, "file_" . $file_i, $_FILES['file_' . $file_i], $_P_DIR_FILE); // 파일 업로드후 변형된 파일이름 리턴.
		//}

	} else { // 파일이 있다면 업로드한다 종료 , 파일이 없을때 시작 

		if ($file_old_name && $file_old_org) {
			$file_c = $file_old_name;
			$file_o = $file_old_org;
		} else {
			$file_c = "";
			$file_o = "";
		}

		if ($del_org == "Y") {
			if ($file_old_name) {
				unlink($_P_DIR_FILE . $file_old_name); // 원본파일 삭제
			}
			$file_c = "";
			$file_o = "";
		}
	} //  파일이 없을때 종료 

	if ($file_idx) { // 기존에 첨부파일 DB 에 있던 값

		if ($file_o && $file_c) { // 파일이 있으면 업데이트, 없으면 삭제 
			$query_file = " update board_file set ";
			$query_file .= " file_org = '" . $file_o . "', ";
			$query_file .= " file_chg = '" . $file_c . "' ";
			$query_file .= " where 1=1 and idx = '" . $file_idx . "' ";
		} else {
			$query_file = " delete from board_file ";
			$query_file .= " where 1=1 and idx = '" . $file_idx . "' ";
		}
		$result_file = mysqli_query($gconnet, $query_file);
	} else { // 기존에 첨부파일 DB 에 없던 값 

		if ($_FILES['file_' . $file_i]['size'] > 0) { // 업로드 파일이 있으면 인서트 

			$query_file = " insert into board_file set ";
			$query_file .= " board_tbname = '" . $board_tbname . "', ";
			$query_file .= " board_code = '" . $board_code . "', ";
			$query_file .= " board_idx = '" . $sendinfo_idx . "', ";
			$query_file .= " file_org = '" . $file_o . "', ";
			$query_file .= " file_chg = '" . $file_c . "' ";

			$result_file = mysqli_query($gconnet, $query_file);
		}
	} // 기존에 첨부파일 DB 에 있었는지 없었는지 모두 종료 

} // 설정된 갯수만큼 루프 종료
################# 첨부파일 업로드 종료 #######################

if ($result) {
?>
	<SCRIPT LANGUAGE="JavaScript">
		<!--
		alert('정보수정이 정상적으로 완료 되었습니다.');
		parent.location.href = "member_view.php?idx=<?= $idx ?>&<?= $total_param ?>";
		//
		-->
	</SCRIPT>
<? } else { ?>
	<SCRIPT LANGUAGE="JavaScript">
		<!--
		alert('정보수정중 오류가 발생했습니다.');
		//
		-->
	</SCRIPT>
<? } ?>