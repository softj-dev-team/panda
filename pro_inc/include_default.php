<?
ini_set("session.cache_expire", 60000); // 세션 유효시간 : 분 
ini_set("session.gc_maxlifetime", 3600000); // 세션 가비지 컬렉션(로그인시 세션지속 시간) : 초 
session_start();
date_default_timezone_set('Asia/Seoul');
//session_cache_limiter("private"); 
header("Pragma: no-cache");
header("Cache-Control: no-cache,must-revalidate");
header('Content-Type: text/html; charset=UTF-8');

error_reporting(E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR  | E_PARSE | E_USER_ERROR | E_USER_WARNING);
ini_set("display_errors", "1");

/* SSL 방식 접속여부 확인 */
if (!isset($_SERVER["HTTPS"])) {
	//header('Location: https://'.$_SERVER["HTTP_HOST"].$_SERVER['REQUEST_URI']);
}

include_once $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/user_function.php"; // PHP 유저 함수 모음
include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/db_conn.php";

if (!isset($_SERVER["HTTPS"])) {
	$inc_fdata_ctype = "http";
	$inc_fdata_domain = "http://" . $_SERVER["HTTP_HOST"] . "";
} else {
	$inc_fdata_ctype = "https";
	$inc_fdata_domain = "https://" . $_SERVER["HTTP_HOST"] . "";
}

$inc_fdata_shopid = "INIpayTest"; // 이니시스 테스트 상점아이디. 발급시 교체
$inc_fdata_shopkey = "SU5JTElURV9UUklQTEVERVNfS0VZU1RS"; // 테스트용. 발급시 교체
$inc_fdata_server = $inc_fdata_ctype . "://stgstdpay.inicis.com/stdjs/INIStdPay.js"; // 테스트

define("NS", "");
define("V_ROOT", "");
define("P_ROOT", $_SERVER["DOCUMENT_ROOT"] . V_ROOT);

// 첨부파일 저장 
$_P_DIR_FILE =  $_SERVER["DOCUMENT_ROOT"] . "/upload_file/"; //게시판,자료 등에서 업로드하는 폴더가 저장되는 경로
$_P_DIR_WEB_FILE = "/upload_file/";

$http_host_arr = explode(".pandasms", $_SERVER["HTTP_HOST"]);
$http_host_1 = str_replace($inc_fdata_domain, "", $http_host_arr[0]);
//echo "http_host_1 = ".$http_host_1."<br>"; // 서브도메인이 없을시는 pandasms.co.kr 혹은 www 가 나온다

if ($http_host_1 == "www" || $http_host_1 == "pandasms.co.kr") {
	$inc_partner_idx = "1";
	$inc_partner_id = "admin";
} else {
	$sql_inc_partner = "select a.member_idx,(select user_id from member_info where 1 and memout_yn not in ('Y','S') and del_yn='N' and member_type in ('PAT') and idx=a.member_idx) as member_id from member_info_company a where 1 and is_del='N' and com_homep='" . $http_host_1 . "'";
	$query_inc_partner = mysqli_query($gconnet, $sql_inc_partner);
	if (mysqli_num_rows($query_inc_partner) > 0) {
		$row_inc_partner = mysqli_fetch_array($query_inc_partner);
		$inc_partner_idx = $row_inc_partner['member_idx'];
		$inc_partner_id = $row_inc_partner['member_id'];
	} else {
		$inc_partner_idx = "1";
		$inc_partner_id = "admin";
	}
}

$inc_fdata_url = $_SERVER['SCRIPT_NAME'] . "?" . $_SERVER['QUERY_STRING'];

$_SITE_TITLE = "판다문자";
$_SITE_ADMIN_TITLE = $_SITE_TITLE . "_관리자";
$_SITE_PARTNER_TITLE = $_SITE_TITLE . "_가맹점 관리자";

if ($inc_partner_idx != "1") {
	$sql_inc_configure = "select a.*,(select file_chg from board_file where 1 and board_tbname='site_configure' and board_code='logo' and board_idx=a.idx order by idx asc limit 0,1) as file_chg from site_configure a where 1 and member_idx='" . $inc_partner_idx . "' and is_del='N'";
	$query_inc_configure = mysqli_query($gconnet, $sql_inc_configure);
	if (mysqli_num_rows($query_inc_configure) == 0) {
		$sql_inc_configure = "select a.*,(select file_chg from board_file where 1 and board_tbname='site_configure' and board_code='logo' and board_idx=a.idx order by idx asc limit 0,1) as file_chg from site_configure a where 1 and member_idx='1' and is_del='N'";
		$query_inc_configure = mysqli_query($gconnet, $sql_inc_configure);
	}

	$sql_sms_configure = "select a.* from sms_configure a where 1 and member_idx='" . $inc_partner_idx . "' and is_del='N'";
	$query_sms_configure = mysqli_query($gconnet, $sql_sms_configure);

	if (mysqli_num_rows($query_sms_configure) == 0) {
		$sql_sms_configure = "select a.* from sms_configure a where 1 and member_idx='1' and is_del='N'";
		$query_sms_configure = mysqli_query($gconnet, $sql_sms_configure);
	}
} else {
	$sql_inc_configure = "select a.*,(select file_chg from board_file where 1 and board_tbname='site_configure' and board_code='logo' and board_idx=a.idx order by idx asc limit 0,1) as file_chg from site_configure a where 1 and member_idx='1' and is_del='N'"; // 본사는 member_idx 가 1. 지점은 도메인에 맞게 변경필요
	$query_inc_configure = mysqli_query($gconnet, $sql_inc_configure);

	$sql_sms_configure = "select a.* from sms_configure a where 1 and member_idx='" . $inc_partner_idx . "' and is_del='N'";
	$query_sms_configure = mysqli_query($gconnet, $sql_sms_configure);
}

if (mysqli_num_rows($query_inc_configure) > 0) {
	$row_inc_configure = mysqli_fetch_array($query_inc_configure);

	$inc_confg_sns_kakao = $row_inc_configure['sns_kakao'];
	$inc_confg_sns_teleg = $row_inc_configure['sns_teleg'];

	$inc_confg_bank_name = $row_inc_configure['bank_name'];
	$inc_confg_bank_num = $row_inc_configure['bank_num'];
	$inc_confg_bank_owner = $row_inc_configure['bank_owner'];

	$inc_confg_conf_tel_2 = $row_inc_configure['conf_tel_2'];
	$inc_confg_conf_time_s = $row_inc_configure['conf_time_s'];
	$inc_confg_conf_time_e = $row_inc_configure['conf_time_e'];
	$inc_confg_conf_time_s2 = $row_inc_configure['conf_time_s2'];
	$inc_confg_conf_time_e2 = $row_inc_configure['conf_time_e2'];
	$inc_confg_conf_fax = $row_inc_configure['conf_fax'];
	$inc_confg_conf_email_1 = $row_inc_configure['conf_email_1'];

	$inc_confg_conf_comname = $row_inc_configure['conf_comname'];
	$inc_confg_conf_comowner = $row_inc_configure['conf_comowner'];
	$inc_confg_conf_manager = $row_inc_configure['conf_manager'];
	$inc_confg_conf_comnum_1 = $row_inc_configure['conf_comnum_1'];
	$inc_confg_conf_comnum_2 = $row_inc_configure['conf_comnum_2'];
	$inc_confg_conf_addr = $row_inc_configure['conf_addr'];
	$inc_confg_conf_tel_1 = $row_inc_configure['conf_tel_1'];
	$inc_confg_conf_email_2 = $row_inc_configure['conf_email_2'];

	$inc_confg_file_chg = $row_inc_configure['file_chg'];
}

if (mysqli_num_rows($query_sms_configure) > 0) {
	$row_sms_configure = mysqli_fetch_array($query_sms_configure);
	$inc_sms_denie_num = $row_sms_configure['denie_num'];
}

$inc_pubyoil_arr = [
	'1' => '월요일', '2' => '화요일', '3' => '수요일', '4' => '목요일', '5' => '금요일', '6' => '토요일', '7' => '일요일'
];
