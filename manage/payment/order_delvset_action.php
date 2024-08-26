<?
include "../../pro_inc/include_default.php"; // 공통함수 인클루드 
include "../include/admin_header.php"; // 관리자페이지 헤더
check_admin_frame(); // 관리자 로그인여부 확인

$order_num = trim(sqlfilter($_REQUEST['order_num']));
$total_param = trim(sqlfilter($_REQUEST['total_param']));
$er_num = trim(sqlfilter($_REQUEST['order_num']));

$delvstat = trim(sqlfilter($_REQUEST['delvstat']));
$delvname = trim(sqlfilter($_REQUEST['delvname']));
$delvcom = trim(sqlfilter($_REQUEST['delvcom']));
$delvnum = trim(sqlfilter($_REQUEST['delvnum']));
$delvlink = trim(sqlfilter($_REQUEST['delvlink']));

if(!$delvstat){
	error_frame("배송상태를 선택해주세요.");
}

//exit;

$query = "select * from ".NS."order_member where order_num = '".$order_num."' and orderstat = 'com' and order_num in (select order_num from order_product where 1 and sales_member_idx='".$_SESSION['session_manage_idx']."')";
if(!$result = mysqli_query($GLOBALS['gconnet'],$query)){
	error_frame("데이터를 불러올 수 없습니다.");
}
if(!$row = mysqli_fetch_array($result)){
	error_frame("데이터가 존재하지 않습니다.");
}

if($delvstat == "d_pre"){
} else {
		if(!$delvname){
			//error_frame("배송등록자를 입력해주세요.");
		}
		if(!$delvcom){
			error_frame("배송업체명을 선택해주세요.");
		}
		if(!$delvnum){
			error_frame("배송번호를 입력해주세요.");
		}
		if(!$delvlink){
			error_frame("배송추적 링크주소를 입력해주세요.");
		}
}

		$query_2 = " update ".NS."order_product set "; 
		$query_2 .= " delvstat = '".$delvstat."', ";
		$query_2 .= " delvcom = '".$delvcom."', ";
		$query_2 .= " delvname = '".$delvname."', ";
		$query_2 .= " delvnum = '".$delvnum."', ";
		$query_2 .= " delvlink = '".$delvlink."' ";
		$query_2 .= " where order_num = '".$order_num."' ";
		//$result = mysqli_query($GLOBALS['gconnet'],$query_2);
	
	if($delvstat == "d_pre"){
		$query = " update ".NS."order_member set "; 
		$query .= " delvstat = '".$delvstat."' ";
		/*$query .= " delvcom = '".$delvcom."', ";
		$query .= " delvname = '".$delvname."', ";
		$query .= " delvnum = '".$delvnum."', ";
		$query .= " delvlink = '".$delvlink."' ";*/
		$query .= " where order_num = '".$order_num."' ";
		$result = mysqli_query($GLOBALS['gconnet'],$query);
	}elseif($delvstat == "d_ing"){
		$query_3 = "select idx from order_product where 1 and order_num='".$order_num."' ";
		$result_3 = mysqli_query($GLOBALS['gconnet'],$query_3);
		$total_op_cnt = mysqli_num_rows($result_3);

		$query_4 = "select idx from order_product where 1 and order_num='".$order_num."' and delvstat in ('d_ing','d_com')";
		$result_4 = mysqli_query($GLOBALS['gconnet'],$query_4);
		$stat_op_cnt = mysqli_num_rows($result_4);

		if($stat_op_cnt < $total_op_cnt){
			$query = " update ".NS."order_member set "; 
			$query .= " delvstat = '".$delvstat."', ";
			$query .= " delvcom = '".$delvcom."', ";
			$query .= " delvname = '".$delvname."', ";
			$query .= " delvnum = '".$delvnum."', ";
			$query .= " delvlink = '".$delvlink."' ";
			$query .= " where order_num = '".$order_num."' ";
			//$result = mysqli_query($GLOBALS['gconnet'],$query);
		} else {
			$query = " update ".NS."order_member set "; 
			$query .= " delvstat = 'd_ing', ";
			$query .= " delvcom = '".$delvcom."', ";
			$query .= " delvname = '".$delvname."', ";
			$query .= " delvnum = '".$delvnum."', ";
			$query .= " delvlink = '".$delvlink."' ";
			$query .= " where order_num = '".$order_num."' ";
			$result = mysqli_query($GLOBALS['gconnet'],$query);
		}
	}elseif($delvstat == "d_com"){
		$query_3 = "select idx from order_product where 1 and order_num='".$order_num."' ";
		$result_3 = mysqli_query($GLOBALS['gconnet'],$query_3);
		$total_op_cnt = mysqli_num_rows($result_3);

		$query_4 = "select idx from order_product where 1 and order_num='".$order_num."' and delvstat in ('d_ing','d_com')";
		$result_4 = mysqli_query($GLOBALS['gconnet'],$query_4);
		$stat_op_cnt = mysqli_num_rows($result_4);

		if($stat_op_cnt < $total_op_cnt){
			$query = " update ".NS."order_member set "; 
			$query .= " delvstat = '".$delvstat."', ";
			$query .= " delvcom = '".$delvcom."', ";
			$query .= " delvname = '".$delvname."', ";
			$query .= " delvnum = '".$delvnum."', ";
			$query .= " delvlink = '".$delvlink."' ";
			$query .= " where order_num = '".$order_num."' ";
			//$result = mysqli_query($GLOBALS['gconnet'],$query);
		} else {
			$query = " update ".NS."order_member set "; 
			$query .= " delvstat = 'd_com', ";
			$query .= " delvcom = '".$delvcom."', ";
			$query .= " delvname = '".$delvname."', ";
			$query .= " delvnum = '".$delvnum."', ";
			$query .= " delvlink = '".$delvlink."' ";
			$query .= " where order_num = '".$order_num."' ";
			$result = mysqli_query($GLOBALS['gconnet'],$query);
		}
	}



if($delvstat == "d_pre"){
?>
<script type="text/javascript">
	alert("배송정보 설정이 정상적으로 처리 되었습니다.");
	window.parent.document.location.replace("order_list.php?v_sect=ready");
</script>
<?
}else if($delvstat == "d_ing"){
?>
<script type="text/javascript">
	alert("배송정보 설정이 정상적으로 처리 되었습니다.");
	window.parent.document.location.replace("order_list.php?v_sect=delivering");
</script>
<?
}else if($delvstat == "d_com"){
?>
<script type="text/javascript">
	alert("배송정보 설정이 정상적으로 처리 되었습니다.");
	window.parent.document.location.replace("order_list.php?v_sect=delivered");
</script>
<?
}else{
?>
<script type="text/javascript">
	alert("배송정보 설정이 정상적으로 처리 되었습니다.");
	window.parent.document.location.reload();
</script>
<?
}