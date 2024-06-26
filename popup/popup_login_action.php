<? header('Content-Type: text/html; charset=UTF-8'); ?>
<? include $_SERVER[DOCUMENT_ROOT]."/pro_inc/db_conn.php"; ?>
<? include $_SERVER[DOCUMENT_ROOT]."/pro_inc/user_function.php"; ?>
<? {include "../inc/head.php";} ?>
<?
$pops_id = trim(sqlfilter($_REQUEST['pops_id']));
$pops_pass = trim(sqlfilter($_REQUEST['pops_pass']));
$pops_pass = md5($pops_pass);
$reurl_ft_go = trim(sqlfilter($_REQUEST['reurl_ft_go']));
//exit;

$current_date = date("Y-m-d");

$sql = "select idx,user_id,user_name,user_sect,user_gubun,user_level,user_pwd,login_ok,Interc_sdate,Interc_period,Interc_edate,memout_yn from member_info where user_id='".$pops_id."' ";
//echo $sql;
$result = mysql_query($sql);

if(mysql_num_rows($result)>0){
		
	$row = mysql_fetch_array($result);

	if($row['memout_yn'] == "Y"){
		error_back("요청하신 탈퇴신청건을 접수/처리 중입니다. 궁금하신 내용은 관리자에게 문의해 주세요.");
		exit;
	}
	
	if($pops_pass != $row['user_pwd']){
		error_back("비밀번호가 일치하지 않습니다. 다시 확인하시고 로그인 해주세요.");
		exit;
	}

	if($row['login_ok'] == "N"){ 
		error_back(" 회원님은 현재 로그인 하실 수 없습니다. 궁금하신 내용은 관리자에게 문의해 주세요.");
		exit;
	} 

	include "session_mysql.php";
		
	?>

		<form name="frm_login_ing" method="post" action="member_session.php" target='_self'>
			<input type="hidden" name="reurl_ft_go" value="<?=$reurl_ft_go?>">
			<input type="hidden" name="pops_idx" value="<?=$row['idx']?>">
			<input type="hidden" name="pops_pass" value="<?=$_REQUEST['pops_pass']?>">
		</form>

	<script type="text/javascript">
	<!--
	//alert("<?=$row['username']?> 님 환영합니다.");
	frm_login_ing.submit();
	//-->
	</script>
	<?

} else { 
	error_back("일치하는 회원 계정이 없습니다. 다시 확인하시고 로그인 해주세요! ");
	exit;
}
?>