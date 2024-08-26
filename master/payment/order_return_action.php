<?
include "../../pro_inc/include_default.php"; // 공통함수 인클루드 
include "../include/admin_header.php"; // 관리자페이지 헤더
check_admin_frame(); // 관리자 로그인여부 확인

$order_num = trim(sqlfilter($_REQUEST['order_num']));
$total_param = trim(sqlfilter($_REQUEST['total_param']));

$return_idx = trim(sqlfilter($_REQUEST['return_idx']));
$p_ok_cnt = trim(sqlfilter($_REQUEST['p_ok_cnt']));
$head_ok = trim(sqlfilter($_REQUEST['head_ok']));

if(!$order_num){
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
alert("주문번호가 없습니다.");
//-->
</script>
<?
exit;
}

$where .= " and order_num = '".$order_num."' and orderstat in ('reing') ";

$pre_sql = "select idx from ".NS."order_member where 1=1 ".$where;
$pre_query = mysqli_query($GLOBALS['gconnet'],$pre_sql);
	
if(mysqli_num_rows($pre_query) == 0){
?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
		alert('해당하는 주문내역이 없습니다.');
	//-->
	</SCRIPT>
<?
exit;
}

	$query2 = " update ".NS."order_product_return set "; 
	$query2 .= " head_ok = '".$head_ok."', ";
	$query2 .= " p_ok_cnt = '".$p_ok_cnt."' ";
	$query2 .= " where idx = '".$return_idx."' ";
	$result2 = mysqli_query($GLOBALS['gconnet'],$query2);

?>

<script type="text/javascript">
	alert("반품정보 수정이 정상적으로 처리 되었습니다.");
	window.parent.document.location.reload();
	//parent.location.href =  "order_cancel_list.php?bmenu=4&smenu=3";
</script>