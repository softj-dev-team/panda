<? include $_SERVER[DOCUMENT_ROOT]."/pro_inc/include_default.php"; ?>
<? {include "../inc/head.php";} ?>
<?
$reurl_ft_go = trim(sqlfilter($_REQUEST['reurl_ft_go']));
$pops_idx = trim(sqlfilter($_REQUEST['pops_idx']));
$pops_pass = trim(sqlfilter($_REQUEST['pops_pass']));

$sql = "select idx,user_id,user_name,organ_name,organ_code,user_sect,user_gubun,user_level,birthday,email from member_info where idx='".$pops_idx."' ";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

########### 나이 계산 시작 ###########
//생일 입력
$myBirthDate    = strtotime($row['birthday']);

//만나이
$birthDate1     = date( 'Ymd', $myBirthDate );
$nowDate1       = date('Ymd');
$age1           = floor(($nowDate1 - $birthDate1) / 10000);
 
//한국 나이
/*$birthDate2     = date( 'Y', $myBirthDate );
$nowDate2       = date('Y');
$age2           = $nowDate2 - $birthDate2 + 1 ;*/

//출력
//echo '만 나이 : '.$age1;
//echo '한국 나이: '.$age2;
########### 나이 계산 종료 ###########

//exit;

	############## 1 달전에 로그인된 기록들은 지운다 #############
	$calcu_date =  date("Y-m-d",strtotime("-1 month")); // 1 개월전

	$sql_pre4 = "delete from mem_login_count where logdate <= '".$calcu_date."'  ";
	//$query_pre4 = mysql_query($sql_pre4);

	############## 1 달전에 로그인된 기록들은 지운다 종료 #############
	
	$bis_sdate = date("Y-m-d");
	$sql_login_pre1 = "select idx from mem_login_count where member_idx = '".$row['idx']."' and  logdate = '".$bis_sdate."' ";
	$query_login_pre1 = mysql_query($sql_login_pre1);
	
	if(mysql_num_rows($query_login_pre1) == 0 ){ // 금일 처음으로 로그인 함 
	
		$query_login = " insert into mem_login_count set "; 
		$query_login .= " member_idx = '".$row['idx']."', ";
		$query_login .= " login_count_num = '1', ";
		$query_login .= " logdate = '".$bis_sdate."' ";

	} else { // 금일 로그인 기록있음

		$query_login = " update mem_login_count set "; 
		$query_login .= " login_count_num = login_count_num+1 ";
		$query_login .= " where member_idx = '".$row['idx']."' and logdate = '".$bis_sdate."' ";

	} // 금일 처음으로 로그인 및 로그인 기록있음 종료 

	$result_login = mysql_query($query_login);


$_SESSION['member_pops_idx'] = $row['idx'];
$_SESSION['member_pops_id'] = $row['user_id'];
$_SESSION['member_pops_name'] = $row['user_name'];
$_SESSION['member_pops_password'] = $pops_pass;
$_SESSION['member_pops_ocode'] = $row['organ_code']; // 단체코드
$_SESSION['member_pops_oname'] = $row['organ_name']; // 단체명
$_SESSION['member_pops_sect'] = $row['user_sect'];
$_SESSION['member_pops_gubun'] = $row['user_gubun'];
$_SESSION['member_pops_level'] = $row['user_level'];
$_SESSION['member_pops_age'] = $age1; // 만 나이
$_SESSION['member_pops_email'] = $row['email']; // 이메일


?>

<script type="text/javascript">
<!--
function go_opener_url(recv) {
	opener.location.href =  recv;
	self.close();
}

go_opener_url('http://popupstudy2.cafe24.com/');
//-->
</script>