<?
include "../../pro_inc/include_default.php"; // 공통함수 인클루드 
include "../include/admin_header.php"; // 관리자페이지 헤더
check_admin_frame(); // 관리자 로그인여부 확인

$order_product_idx = trim(sqlfilter($_REQUEST['order_product_idx']));
$order_product_stat = trim(sqlfilter($_REQUEST['order_product_stat']));

$query_pro = " update ".NS."order_product set "; 
$query_pro .= " orderstat = '".$order_product_stat."', ";
if($order_product_stat == "reing"){
	$query_pro .= " cancel_ing_date = now() ";
} elseif($order_product_stat == "recom"){
	$query_pro .= " cancel_date = now() ";
}
$query_pro .= " where idx = '".$order_product_idx."' ";

if(!$result_pro = mysqli_query($GLOBALS['gconnet'],$query_pro)){
	error_frame("오류가 발생했습니다.");
}
?>
<script type="text/javascript">
	window.parent.document.location.reload();
</script>
