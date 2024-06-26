<?
include "../../pro_inc/include_default.php"; // 공통함수 인클루드 
include "../include/admin_header.php"; // 관리자페이지 헤더
check_admin_frame(); // 관리자 로그인여부 확인

$order_num = trim(sqlfilter($_REQUEST['order_num']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

$v_sect =  trim(sqlfilter($_REQUEST['v_sect']));
$v_cate =  trim(sqlfilter($_REQUEST['v_cate']));
$s_date =  trim(sqlfilter($_REQUEST['s_date']));
$e_date =  trim(sqlfilter($_REQUEST['e_date']));
$s_pay_type =  trim(sqlfilter($_REQUEST['s_pay_type']));
$s_pay_sect =  trim(sqlfilter($_REQUEST['s_pay_sect']));
$s_receipt_ok =  trim(sqlfilter($_REQUEST['s_receipt_ok']));
$s_taxbill_ok =  trim(sqlfilter($_REQUEST['s_taxbill_ok']));
$s_group = sqlfilter($_REQUEST['s_group']); // 입점업체

$scDept = trim(sqlfilter($_REQUEST['scDept']));			// 본부장
$scServer	= trim(sqlfilter($_REQUEST['scServer']));			// 학원구분
$s_mem_sect =  trim(sqlfilter($_REQUEST['s_mem_sect'])); // 주문자 구분

$sc_local = trim(sqlfilter($_REQUEST['sc_local']));			// 지역검색

################## 파라미터 조합 #####################
$total_param = 'field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&v_cate='.$v_cate.'&s_date='.$s_date.'&e_date='.$e_date.'&s_pay_type='.$s_pay_type.'&s_pay_sect='.$s_pay_sect.'&s_receipt_ok='.$s_receipt_ok.'&s_taxbill_ok='.$s_taxbill_ok.'&scDept='.$scDept.'&scServer='.$scServer.'&s_mem_sect='.$s_mem_sect.'&sc_local='.$sc_local.'&s_group='.$s_group;

$query = "select orderstat, delvstat from ".NS."order_member where order_num = '".$order_num."'";
if(!$result = mysqli_query($GLOBALS['gconnet'],$query)){
	error_frame("데이터베이스를 불러올 수 없습니다.");
}
if(!$row = mysqli_fetch_array($result)){
	error_frame("주문내역이 존재하지 않습니다.");
}
if($row["orderstat"] == "pre"){
	error_frame("주문취소를 먼저 진행해주세요.");
}
if($row["orderstat"] == "com"){
	if($row["delvstat"] != "d_com" && $row["delvstat"] != "d_conf"){
		error_frame("현재 배송중인 상품입니다. 배송완료 후 삭제가능합니다.");
	}
}

$result1 = mysqli_query($GLOBALS['gconnet'],"delete from ".NS."order_member where order_num = '".$order_num."' "); // 주문자 정보 
$result2 = mysqli_query($GLOBALS['gconnet'],"delete from ".NS."order_product where order_num = '".$order_num."' "); //   주문상품 
$result3 = mysqli_query($GLOBALS['gconnet'],"delete from ".NS."order_product_opt where order_num = '".$order_num."' "); //   주문상품 옵션정보
$result4 = mysqli_query($GLOBALS['gconnet'],"delete from ".NS."order_product_return where order_num = '".$order_num."' "); //   주문상품 환불요청
//$result5 = mysqli_query($GLOBALS['gconnet'],"delete from ".NS."member_point where order_num = '".$order_num."' "); //  캐쉬

?>

<script type="text/javascript">
	alert("정상적으로 삭제 되었습니다.");
	//window.parent.document.location.href = window.parent.document.URL;
	parent.location.href =  "order_list.php?<?=$total_param?>";
</script>