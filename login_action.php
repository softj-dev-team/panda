<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 
?>
<?
$pm_id = trim(sqlfilter($_REQUEST['lms_id']));
$pm_pwd = md5(sqlfilter($_REQUEST['lms_pass']));
$reurl_go = trim(sqlfilter($_REQUEST['reurl_go']));
$push_key = trim($_REQUEST['push_key']);

if ($pm_id != null && $pm_pwd != null) { // 일반 로그인 시작 

	$sql = "select * from member_info where 1 and user_id='" . $pm_id . "' and member_type in ('GEN','PAT') and del_yn='N'";
	// echo $sql;
	$result = mysqli_query($gconnet, $sql);
	//echo "매칭갯수 = ".mysqli_num_rows($result); exit;

	//회원로그인 
	if (mysqli_num_rows($result) > 0) {

		$row = mysqli_fetch_array($result);

		//echo "입력한 비번 = ".$_REQUEST['lms_pass']." : 암호화 시킨 비번 = ".$pm_pwd." : DB 의 비번 = ".$row['user_pwd'];

		//if($_SERVER['REMOTE_ADDR'] == "59.5.188.44"){
		//} else {
		if (trim($pm_pwd) != trim($row['user_pwd'])) {
			error_popup("비밀번호가 일치하지 않습니다. 다시 확인하시고 로그인 해주세요! ");
		}
		//}

		if ($row['memout_yn'] == "S") {
			error_popup("요청하신 탈퇴신청건을 접수/처리 중입니다. 궁금하신 내용은 관리자에게 문의해 주세요.");
		}

		if ($row['memout_yn'] == "Y") {
			error_popup("탈퇴한 회원입니다. 궁금하신 내용은 관리자에게 문의해 주세요.");
		}

		if ($row['login_ok'] == "N") {
			error_popup("회원님은 현재 로그인 하실 수 없습니다. 관리자에게 문의해 주세요.");
		}

		if ($row['master_ok'] == "N") {
			error_popup("관리자의 승인 후에 로그인이 가능합니다. 관리자 또는 고객센터에 연락 주세요.");
		}

		########### 나이 계산 시작 ###########
		//생일 입력
		$myBirthDate    = strtotime($row['birthday']);

		if ($row['birthday']) {
			//만나이
			$birthDate1     = date('Ymd', $myBirthDate);
			$nowDate1       = date('Ymd');
			$age1           = floor(($nowDate1 - $birthDate1) / 10000);
		} else {
			$age1 = "";
		}

		############## 1 달전에 로그인된 기록들은 지운다 #############
		$calcu_date =  date("Y-m-d", strtotime("-1 month")); // 1 개월전

		$sql_pre4 = "delete from mem_login_count where logdate <= '" . $calcu_date . "'  ";
		$query_pre4 = mysqli_query($gconnet, $sql_pre4);
		############## 1 달전에 로그인된 기록들은 지운다 종료 #############

		$bis_sdate = date("Y-m-d");
		$sql_login_pre1 = "select idx from mem_login_count where member_idx = '" . $row['idx'] . "' and  logdate = '" . $bis_sdate . "'  ";
		$query_login_pre1 = mysqli_query($gconnet, $sql_login_pre1);

		if (mysqli_num_rows($query_login_pre1) == 0) { // 금일 처음으로 로그인 함 
			$query_login = " insert into mem_login_count set ";
			$query_login .= " member_idx = '" . $row['idx'] . "', ";
			$query_login .= " login_count_num = '1', ";
			$query_login .= " logdate = '" . $bis_sdate . "' ";
			############################### 로그인시 적립금 지급 시작 ############################
			$point_sect_1 = "refund"; // 적립금
			$sql_setting_1 = "select member_login_gen from member_point_set where 1=1 and point_sect='" . $point_sect_1 . "' and coin_type='member' order by idx desc limit 0,1 "; // 적립금 설정 테이블에서 일일 로그인시 적립금 설정값을 추출한다.
			$result_setting_1 = mysqli_query($gconnet, $sql_setting_1);
			if (mysqli_num_rows($result_setting_1) == 0) {
				$chg_mile_1 = 0;
			} else {
				$row_setting_1 = mysqli_fetch_array($result_setting_1);
				$chg_mile_1 = $row_setting_1['member_login_gen'];
			}

			$mile_title_1 = $bis_sdate . " 로그인"; // 적립금 적립 내역
			$mile_sect_1 = "A"; // 적립금 종류 = A : 적립, P : 대기, M : 차감
			coin_plus_minus($point_sect_1, $row['idx'], $mile_sect_1, $chg_mile_1, $mile_title_1, "", "", "");
			############################### 로그인시 적립금 지급 종료 ############################
		} else { // 금일 로그인 기록있음
			$query_login = " update mem_login_count set ";
			$query_login .= " login_count_num = login_count_num+1 ";
			$query_login .= " where member_idx = '" . $row['idx'] . "' and logdate = '" . $bis_sdate . "' ";
		} // 금일 처음으로 로그인 및 로그인 기록있음 종료 
		$result_login = mysqli_query($gconnet, $query_login);

		$query_login = " update member_info set ";
		$query_login .= " logindate = now(), login_ip = '" . $_SERVER['REMOTE_ADDR'] . "'";
		$query_login .= " where idx = " . $row['idx'];
		$result_login = mysqli_query($gconnet, $query_login);

		var_dump($query_login);

		if ($_REQUEST['push_key']) {
			$push_in_sql = "update member_info set push_key='" . $push_key . "' where 1 and idx='" . $row['idx'] . "'";
			$push_in_result = mysqli_query($gconnet, $push_in_sql);
		}

		$_SESSION['member_coinc_id'] = $pm_id;
		$_SESSION['member_coinc_idx'] = $row['idx'];
		$_SESSION['member_coinc_name'] = $row['user_name'];
		$_SESSION['member_coinc_age'] = $age1;
		$_SESSION['member_coinc_password'] = $pm_pwd;

		if (!$reurl_go) {
			$reurl_go = "/";
		}

		frame_go($reurl_go);
	} else {
		error_popup("일치하는 계정이 없습니다. 다시 확인하시고 로그인 해주세요! ");
	}
} // 일반 로그인 종료

//sns 로그인 시작 
//2022-0923 deep sns 로그인 정보 
$sns_code = trim(sqlfilter($_REQUEST['sns_code']));
$member_code = trim(sqlfilter($_REQUEST['member_code']));
$email = trim(sqlfilter($_REQUEST['user_email']));
$name = trim(sqlfilter($_REQUEST['user_name']));

//sns_code / id 값이 있을 경우에만 실행
if ($sns_code != null && $member_code != null) {
	//member_code > emd_id
	//$sns_sql = "select * from member_info where 1 and emd_code='".$sns_code."' and emd_id='".$member_code."' and member_type='GEN' and del_yn='N'";
	$sns_sql = "select * from member_info where 1 and email='" . $email . "' and member_type in ('GEN','PAT') and del_yn='N'";
	$sns_result = mysqli_query($gconnet, $sns_sql);

	if (mysqli_num_rows($sns_result) > 0) {
		$row = mysqli_fetch_array($sns_result);

		if ($row['memout_yn'] == "S") {
			error_back("요청하신 탈퇴신청건을 접수/처리 중입니다. 궁금하신 내용은 관리자에게 문의해 주세요.");
		}

		if ($row['memout_yn'] == "Y") {
			error_back("탈퇴한 회원입니다. 궁금하신 내용은 관리자에게 문의해 주세요.");
		}

		if ($row['login_ok'] == "N") {
			error_back("회원님은 현재 로그인 하실 수 없습니다. 궁금하신 내용은 관리자에게 문의해 주세요.");
		}

		########### 나이 계산 시작 ###########
		//생일 입력
		$myBirthDate    = strtotime($row['birthday']);

		if ($row['birthday']) {
			//만나이
			$birthDate1     = date('Ymd', $myBirthDate);
			$nowDate1       = date('Ymd');
			$age1           = floor(($nowDate1 - $birthDate1) / 10000);
		} else {
			$age1 = "";
		}

		############## 1 달전에 로그인된 기록들은 지운다 #############
		$calcu_date =  date("Y-m-d", strtotime("-1 month")); // 1 개월전

		$sql_pre4 = "delete from mem_login_count where logdate <= '" . $calcu_date . "'  ";
		$query_pre4 = mysqli_query($gconnet, $sql_pre4);
		############## 1 달전에 로그인된 기록들은 지운다 종료 #############

		$bis_sdate = date("Y-m-d");
		$sql_login_pre1 = "select idx from mem_login_count where member_idx = '" . $row['idx'] . "' and  logdate = '" . $bis_sdate . "'  ";
		$query_login_pre1 = mysqli_query($gconnet, $sql_login_pre1);

		if (mysqli_num_rows($query_login_pre1) == 0) { // 금일 처음으로 로그인 함 
			$query_login = " insert into mem_login_count set ";
			$query_login .= " member_idx = '" . $row['idx'] . "', ";
			$query_login .= " login_count_num = '1', ";
			$query_login .= " logdate = '" . $bis_sdate . "' ";
			############################### 로그인시 적립금 지급 시작 ############################
			$point_sect_1 = "refund"; // 적립금
			$sql_setting_1 = "select member_login_gen from member_point_set where 1=1 and point_sect='" . $point_sect_1 . "' and coin_type='member' order by idx desc limit 0,1 "; // 적립금 설정 테이블에서 일일 로그인시 적립금 설정값을 추출한다.
			$result_setting_1 = mysqli_query($gconnet, $sql_setting_1);
			if (mysqli_num_rows($result_setting_1) == 0) {
				$chg_mile_1 = 0;
			} else {
				$row_setting_1 = mysqli_fetch_array($result_setting_1);
				$chg_mile_1 = $row_setting_1['member_login_gen'];
			}

			$mile_title_1 = $bis_sdate . " 로그인"; // 적립금 적립 내역
			$mile_sect_1 = "A"; // 적립금 종류 = A : 적립, P : 대기, M : 차감
			coin_plus_minus($point_sect_1, $row['idx'], $mile_sect_1, $chg_mile_1, $mile_title_1, "", "", "");
			############################### 로그인시 적립금 지급 종료 ############################
		} else { // 금일 로그인 기록있음
			$query_login = " update mem_login_count set ";
			$query_login .= " login_count_num = login_count_num+1 ";
			$query_login .= " where member_idx = '" . $row['idx'] . "' and logdate = '" . $bis_sdate . "' ";
		} // 금일 처음으로 로그인 및 로그인 기록있음 종료 
		$result_login = mysqli_query($gconnet, $query_login);

		$query_login = " update member_info set ";
		$query_login .= " logindate = now(), login_ip = '" . $_SERVER['REMOTE_ADDR'] . "'";
		$query_login .= " where idx = " . $row['idx'];
		$result_login = mysqli_query($gconnet, $query_login);

		if ($_REQUEST['push_key']) {
			$push_in_sql = "update member_info set push_key='" . $push_key . "' where 1 and idx='" . $row['idx'] . "'";
			$push_in_result = mysqli_query($gconnet, $push_in_sql);
		}

		$_SESSION['member_coinc_id'] = $row['user_id'];
		$_SESSION['member_coinc_idx'] = $row['idx'];
		$_SESSION['member_coinc_name'] = $row['user_name'];
		$_SESSION['member_coinc_age'] = $age1;
		$_SESSION['member_coinc_password'] = $sns_code;

		if (!$reurl_go) {
			$reurl_go = "/";
		}

		frame_go($reurl_go);
	} else {
		//$url = "/member/kakao_form.php?sns_code=".$sns_code."&member_code=".$member_code."&name=".$name."&email=".$email."";
		$url = "/member/signup.php?sns_code=" . $sns_code . "&sns_id=" . $member_code . "&name=" . $name . "&email=" . $email . "";
		error_frame_go("일치하는 계정이 없습니다. 회원가입페이지로 이동합니다.", $url);
	}
}
//sns 로그인 끝 
?>

