<?php include "../inc/header.php" ?>
<?
define("MCASH_INC_DIR", $_SERVER["DOCUMENT_ROOT"]."/mob_can_test/inc/");
//include ( MCASH_INC_DIR."mcash_apprv_define.php" );
//include ( MCASH_INC_DIR."mcash_apprv_lib.php" );*/

include MCASH_INC_DIR."mcash_apprv_define.php";
include MCASH_INC_DIR."mcash_apprv_lib.php";

/***************************************************
 ***             결제 기본 정보 세팅             ***
 ***************************************************/
//필수정보 
	
	$gMode 							= $_POST["mode"];
	$gSvcId 						= $_POST["svcId"];
	$gSiteUrl 					= $_POST["siteUrl"];
	$gTradeId 					= $_POST["tradeId"];
	$gMobilId 					= $_POST["mobilId"];
	$gPrdtPrice 				= $_POST["prdtPrice"];
	$gParticialCnclYn 	= $_POST["particialCnclYn"];
	
	$order_num = trim(sqlfilter($_REQUEST['order_num']));
	$cart_idx = trim(sqlfilter($_REQUEST['cart_idx']));
	$cancel_sect_1 = trim(sqlfilter($_REQUEST['cancel_sect_1']));
	$cancel_memo = trim(sqlfilter($_REQUEST['cancel_memo']));
	$order_result = "can";
		
	$result  = McashApprv();
	
	$ResultCd 	= $gResultCd;
	$ResultMsg 	= $gResultMsg;
	$SvcId			= $gSvcId;
	$TradeId 		= $gTradeId;
	$MobilId 		= $gMobilId;
	$PrdtPrice 	= $gPrdtPrice;

	if($ResultCd == "0000"){
		$query_pro = " update order_product set "; 
		$query_pro .= " orderstat = '".$order_result."', ";
		$query_pro .= " cancel_memo = '".$cancel_memo."', ";
		$query_pro .= " cancel_sect = '".$cancel_sect_1."', ";
		if($_SESSION['admin_ganaf_id']){
			$query_pro .= " cancel_admin = '".$_SESSION['admin_ganaf_id']."', ";
		}
		$query_pro .= " cancel_date = now() ";
		$query_pro .= " where order_num = '".$order_num."' and idx='".$cart_idx."'";
		$result_pro = mysqli_query($gconnet,$query_pro);

		$query = " update order_member set "; 
		$query .= " orderstat = '".$order_result."', ";
		$query .= " cancel_memo = '".$cancel_memo."', ";
		$query .= " cancel_sect = '".$cancel_sect_1."', ";
		if($_SESSION['admin_ganaf_id']){
			$query .= " cancel_admin = '".$_SESSION['admin_ganaf_id']."', ";
		}
		$query .= " cancel_date = now() ";
		$query .= " where order_num = '".$order_num."' ";
		$result = mysqli_query($gconnet,$query);
		
		$mem_sql = "select order_name,order_cell from order_member where order_num = '".$order_num."'";
		$mem_result = mysqli_query($gconnet,$mem_sql);
		$mem_row = mysqli_fetch_array($mem_result);
		
		$type = 0; // 0 : SMS , 5 : LMS
		$c_sms = common_sms($type,$mem_row[order_cell],"".$mem_row[order_name]." 님 ".$order_num." 주문이 취소처리 되었습니다.\r\n-친절한인선생-"); // 문자도 보낸다
	?>
		<script>
			alert("취소요청이 완료 되었습니다.");
			opener.document.location.reload();
			self.close();
		</script>
	<?
	} else {
	?>
		<script>
			alert("결제취소에 실패 하셨습니다.");
			opener.document.location.reload();
			self.close();
		</script>
<?}?>