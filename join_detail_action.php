<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<?
$push_key = trim(sqlfilter($_REQUEST['push_key']));
$sns_code = trim(sqlfilter($_REQUEST['sns_code']));
$member_gubun = trim(sqlfilter($_REQUEST['member_gubun']));
$ipin_code = trim(sqlfilter($_REQUEST['ipin_code']));
$ipin_code_dup = trim(sqlfilter($_REQUEST['ipin_code_dup']));
$birthday = trim(sqlfilter($_REQUEST['birthday']));
$gender = trim(sqlfilter($_REQUEST['gender']));
$user_ci = trim(sqlfilter($_REQUEST['user_ci']));

$user_name = trim(sqlfilter($_REQUEST['member_name']));
$cell = trim(sqlfilter($_REQUEST['cell']));
$cell = str_replace("-", "", $cell);
$user_id = trim(sqlfilter($_REQUEST['member_id']));
$user_pwd = trim(sqlfilter($_REQUEST['member_password']));
$user_pwd = md5($user_pwd);
$email = trim(sqlfilter($_REQUEST['member_email']));

$partner_id = trim(sqlfilter($_REQUEST['partner_id']));

$sql_pre1 = "select idx from member_info where 1 and user_id = '" . $user_id . "' and del_yn='N'"; // 회원테이블 아이디 중복여부 체크
$result_pre1 = mysqli_query($gconnet, $sql_pre1);
if (mysqli_num_rows($result_pre1) > 0) {
	error_frame("입력하신 아이디는 이미 사용중입니다. 다시 확인하시고 입력해 주세요.");
}

if ($email) { // 이메일을 입력했을때 
	$sql_pre4 = "select idx from member_info where 1 and email = '" . $email . "' and del_yn='N'";
	$result_pre4  = mysqli_query($gconnet, $sql_pre4);
	if (mysqli_num_rows($result_pre4) > 0) {
		error_frame("입력하신 이메일은 이미 사용중입니다. 다시 확인하시고 입력해 주세요.");
	}
} // 이메일을 입력했을때 종료

if ($cell) { // 휴대전화 입력했을때 
	$sql_pre3 = "select idx from member_info where 1 and cell = '" . $cell . "' and del_yn='N'";
	//echo "sql_pre3 = ".$sql_pre3."<br>";
	$result_pre3  = mysqli_query($gconnet, $sql_pre3);
	if (mysqli_num_rows($result_pre3) > 0) {
		error_frame("입력하신 휴대전화는 이미 사용중입니다. 다시 확인하시고 입력해 주세요.");
	}
} // 휴대전화 입력했을때 종료

$member_type = "GEN"; // 회원

$login_ok = "Y"; // 최초 가입시 로그인 허용
$master_ok = "N"; // 관리자 등록시 Y, 자가 등록시 N
$ad_mem_sect = "N"; // 관리자 입력여부. 
$memout_yn = "N"; // 탈퇴신청시 Y , 디폴트는 N 
$mail_ok = "Y"; // 이메일 수신 허용

$member_level_basic_sql = "select level_code from member_level_set where 1 and level_sect='" . $member_type . "' and is_del='N' order by idx asc limit 0,1"; // 회원가입시 기본설정 등급코드 추출  
$member_level_basic_query = mysqli_query($gconnet, $member_level_basic_sql);
$member_level_basic_row = mysqli_fetch_array($member_level_basic_query);
$user_level = $member_level_basic_row['level_code'];

if ($partner_id) {
	$partner_sql = "select idx from member_info_company where 1 and is_del='N' and member_idx in (select idx from member_info where 1 and del_yn='N' and  memout_yn not in ('Y','S') and user_id='" . $partner_id . "')"; // 가맹점 아이디로 pk 추출  
	$partner_query = mysqli_query($gconnet, $partner_sql);
	$partner_row = mysqli_fetch_array($partner_query);
	$partner_idx = $partner_row['idx'];
}

$query = "insert into member_info set";
$query .= " ipin_code = '" . $ipin_code . "', ";
$query .= " ipin_code_dup = '" . $ipin_code_dup . "', ";
$query .= " member_type = '" . $member_type . "', ";
$query .= " member_gubun = '" . $member_gubun . "', ";
$query .= " push_key = '" . $push_key . "', ";
$query .= " emd_code = '" . $sns_code . "', ";

$query .= " user_id = '" . $user_id . "', ";
$query .= " user_pwd = '" . $user_pwd . "', ";
$query .= " user_name = '" . $user_name . "', ";
$query .= " email = '" . $email . "', ";
$query .= " partner_idx = '" . $partner_idx . "', ";
$query .= " mail_ok = '" . $mail_ok . "', ";
$query .= " cell = '" . $cell . "', ";
$query .= " birthday = '" . $birthday . "', ";
$query .= " gender = '" . $gender . "', ";

$query .= " user_level = '" . $user_level . "', ";
$query .= " login_ok = '" . $login_ok . "', ";
$query .= " master_ok = '" . $master_ok . "', ";
$query .= " ad_mem_sect = '" . $ad_mem_sect . "', ";
$query .= " memout_yn = '" . $memout_yn . "', ";
$query .= " memout_sect = '" . $memout_sect . "', ";
$query .= " memout_memo = '" . $memout_memo . "', ";
$query .= " cert_ci = '" . $user_ci . "', ";
$query .= " signup_ip = '" . $_SERVER["REMOTE_ADDR"] . "', ";
$query .= " wdate = now() ";
//echo $query;
$result = mysqli_query($gconnet, $query);

$member_idx = mysqli_insert_id($gconnet);



## 발신번호 등록 ##

$call_num = array();
$call_memo = array();
$use_yn = array();
$auth_method = array();

array_push($call_num, $cell);
array_push($call_memo, "");
array_push($use_yn, "Y");
array_push($auth_method, "kcp");

$call_num_encode = json_encode($call_num);
$call_memo_encode = json_encode($call_memo);
$use_yn_encode = json_encode($use_yn);
$auth_method_encode = json_encode($auth_method);

$query = "INSERT INTO member_info_sendinfo SET call_num='$call_num_encode', call_memo='$call_memo_encode', use_yn='$use_yn_encode', auth_method='$auth_method_encode', member_idx=$member_idx";
$result = mysqli_query($gconnet, $query);


$point_sect = "refund"; // 포인트 

########### 회원가입시 포인트  적립시작 #################
$sql_member_in = "select member_in_gen from member_point_set where 1 and point_sect='" . $point_sect . "' and coin_type='member' order by idx desc limit 0,1"; // 포인트  설정 테이블에서 회원가입시의 설정값을 추출한다.
$result_member_in = mysqli_query($gconnet, $sql_member_in);

if (mysqli_num_rows($result_member_in) == 0) {
	$chg_mile = 0;
} else {
	$row_member_in = mysqli_fetch_array($result_member_in);
	$chg_mile = $row_member_in[member_in_gen];
}

$mile_title = "회원가입 포인트 적립"; // 포인트  적립 내역
$mile_sect = "A"; // 포인트  종류 = A : 적립, P : 대기, M : 차감
coin_plus_minus($point_sect, $member_idx, $mile_sect, $chg_mile, $mile_title, "", "", "");

if ($chuchun_idx) { // 추천인 아이디를 입력했을때 

	###########  추천받은사람 포인트  적립시작 #################
	$sql_member_chu = "select member_chuchun_recev from member_point_set where 1=1 and point_sect='" . $point_sect . "' and coin_type='member' order by idx desc limit 0,1 "; // 포인트  설정 테이블에서 추천받은 사람의 설정값을 추출한다.
	$result_member_chu = mysqli_query($gconnet, $sql_member_chu);
	if (mysqli_num_rows($result_member_chu) == 0) {
		$chg_mile2 = 0;
	} else {
		$row_member_chu = mysqli_fetch_array($result_member_chu);
		$chg_mile2 = $row_member_chu[member_chuchun_recev];
	}

	$mile_title2 = $prev_row['user_id'] . " 님 추천으로 포인트  적립"; // 포인트  적립 내역
	$mile_sect2 = "A"; // 포인트  종류 = A : 적립, P : 대기, M : 차감
	coin_plus_minus($point_sect, $chuchun_idx, $mile_sect2, $chg_mile2, $mile_title2, "", "", "");
	###########  추천받은사람 포인트  적립종료 #################

	###########  추천한사람 포인트  적립시작 #################
	$sql_member_in2 = "select member_chuchun_send from member_point_set where 1=1 and point_sect='" . $point_sect . "' and coin_type='member' order by idx desc limit 0,1 "; // 포인트  설정 테이블에서 회원가입시의 설정값을 추출한다.
	$result_member_in2 = mysqli_query($gconnet, $sql_member_in2);

	if (mysqli_num_rows($result_member_in2) == 0) {
		$chg_mile3 = 0;
	} else {
		$row_member_in2 = mysqli_fetch_array($result_member_in2);
		$chg_mile3 = $row_member_in2[member_chuchun_send]; // 회원가입 추천아이디 입력에 따른 포인트 
	}

	$mile_title3 = $prev_row['chuchun_id'] . " 님 추천하신 포인트  적립"; // 포인트  적립 내역
	$mile_sect3 = "A"; // 포인트  종류 = A : 적립, P : 대기, M : 차감
	coin_plus_minus($point_sect, $member_idx, $mile_sect3, $chg_mile3, $mile_title3, "", "", "");
	###########  추천한사람 포인트  적립종료 #################

} // 추천인 아이디를 입력했을때 종료

########### 회원가입시 포인트  적립종료 #################

frame_go("join_ok.php");
?>