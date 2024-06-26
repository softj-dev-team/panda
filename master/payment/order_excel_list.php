<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드

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
$s_mem_sect =  trim(sqlfilter($_REQUEST['s_mem_sect'])); // 주문자 구분
$s_group = sqlfilter($_REQUEST['s_group']); // 입점업체

if($v_sect == "com"){
	$pay_str = "입금완료";
	$select_date = "payment_date";
} elseif($v_sect == "pre"){
	$pay_str = "입금대기";
	$select_date = "order_date";
} elseif($v_sect == "reing"){
	$pay_str = "취소신청";
	$select_date = "cancel_ing_date";
} elseif($v_sect == "can"){
	$pay_str = "취소완료";
	$select_date = "cancel_date";
}

//$where = " and is_del='N'";

if($v_sect){
	$where .= " and a.orderstat ='".$v_sect."'";
}

if(!$pageNo){
	$pageNo = 1;
}

$today = get_local_datetime(date("Y-m-d H:i:s"), "Y-m-d");
$point_cur_date = get_gtc_datetime($today." 23:59:59", "Y-m-d H:i:s");
if($s_cate){
	if($s_cate == "today"){ // 오늘
		$where .= " and a.".$select_date." >= '".date("Y-m-d 00:00:00")."' and a.".$select_date." <= '".date("Y-m-d 23:59:59")."' ";

	} elseif($s_cate == "1week"){ // 1 주일전
		$calcu_date =  get_local_datetime(date("Y-m-d 00:00:00",strtotime("-1 week")), "Y-m-d");
		$calcu_date = get_gtc_datetime($calcu_date." 00:00:00", "Y-m-d H:i:s");
		$where .= " and a.".$select_date." >= '".$calcu_date."' and a.".$select_date." <= '".$point_cur_date."' ";

	} elseif($s_cate == "2week"){ // 2 주일전
		$calcu_date =  get_local_datetime(date("Y-m-d 00:00:00",strtotime("-2 week")), "Y-m-d");
		$calcu_date = get_gtc_datetime($calcu_date." 00:00:00", "Y-m-d H:i:s");
		$where .= " and a.".$select_date." >= '".$calcu_date."' and a.".$select_date." <= '".$point_cur_date."' ";

	} elseif($s_cate == "1month"){ // 1 달전
		$calcu_date =  get_local_datetime(date("Y-m-d 00:00:00",strtotime("-1 month")), "Y-m-d");
		$calcu_date = get_gtc_datetime($calcu_date." 00:00:00", "Y-m-d H:i:s");
		$where .= " and a.".$select_date." >= '".$calcu_date."' and a.".$select_date." <= '".$point_cur_date."' ";

	} elseif($s_cate == "3month"){ // 3 달전
		$calcu_date =  get_local_datetime(date("Y-m-d 00:00:00",strtotime("-3 month")), "Y-m-d");
		$calcu_date = get_gtc_datetime($calcu_date." 00:00:00", "Y-m-d H:i:s");
		$where .= " and a.".$select_date." >= '".$calcu_date."' and a.".$select_date." <= '".$point_cur_date."' ";

	} elseif($s_cate == "6month"){ // 6 달전
		$calcu_date =  get_local_datetime(date("Y-m-d 00:00:00",strtotime("-6 month")), "Y-m-d");
		$calcu_date = get_gtc_datetime($calcu_date." 00:00:00", "Y-m-d H:i:s");
		$where .= " and a.".$select_date." >= '".$calcu_date."' and a.".$select_date." <= '".$point_cur_date."' ";

	}  elseif($s_cate == "6month"){ // 12 달전
		$calcu_date =  get_local_datetime(date("Y-m-d 00:00:00",strtotime("-12 month")), "Y-m-d");
		$calcu_date = get_gtc_datetime($calcu_date." 00:00:00", "Y-m-d H:i:s");
		$where .= " and a.".$select_date." >= '".$calcu_date."' and a.".$select_date." <= '".$point_cur_date."' ";
	}

}else{
	if($s_date){
		$where .= " and a.".$select_date." >= '".get_gtc_datetime($s_date." 00:00:00", "Y-m-d H:i:s")."' ";
	}
	if($e_date){
		$where .= " and a.".$select_date." <= '".get_gtc_datetime($e_date." 23:59:59", "Y-m-d H:i:s")."' ";
	}
}

if($s_pay_type){
	$where .= " and a.pay_sect_1 = '".$s_pay_type."'";
	//$where .= " and order_num in (select order_num from order_member where 1 and pay_sect_1 = '".$s_pay_type."')";
}

if($v_cate){
	//$where .= " and order_num in (select order_num from ticket_payment_info where 1 and ad_info_idx in (select idx from ad_info where 1 and member_idx='".$v_cate."'))";
	//$where .= " and ad_info_idx in (select idx from ad_info where 1 and member_idx='".$v_cate."')";
	$where .= " and member_idx='".$v_cate."'";
}

if ($field && $keyword){
	if($field == "pro_name"){ // 공모전 제목
		$where .= " and order_num in (select order_num from compet_info where 1 and compet_title like '%".$keyword."%')";
		//$where .= " and ad_info_idx in (select idx from ad_info where 1 and ad_title like '%".$keyword."%')";
	}elseif($field == "ticket_name"){ // 티켓명
		//$where .= " and order_num in (select order_num from ticket_payment_info where 1 and ticket_idx in (select idx from ad_info_ticket where 1 and ticket_name like '%".$keyword."%'))";
		$where .= " and ticket_idx in (select idx from ad_info_ticket where 1 and ticket_name like '%".$keyword."%')";
	} else {
		$where .= " and ".$field." like '%".$keyword."%'";
	}
}


$order_by = " order by a.idx desc ";

$query = "select a.*,(select user_id from member_info where 1 and idx=a.member_idx) as buy_id,(select user_name from member_info where 1 and idx=a.member_idx) as buy_name,(select s_date from membership_auth where 1 and order_num=a.order_num) as s_date,(select e_date from membership_auth where 1 and order_num=a.order_num) as e_date from order_member a where 1 ".$where.$order_by;

$result = mysqli_query($gconnet,$query);

$pay_str =  iconv("UTF-8","EUC-KR",$pay_str);

$filename = $pay_str."_".date("Y-m-d").".xls";
//$filename = iconv("UTF-8","EUC-KR",$filename);
//if($_SERVER['REMOTE_ADDR'] != "121.167.147.150"){	
Header( "Content-type: application/vnd.ms-excel" ); 
Header( "Content-Disposition: attachment; filename=".$filename ); 
Header( "Content-Description: PHP4 Generated Data" );
//}
?>

		<head>
			<meta http-equiv=Content-Type content="text/html; charset=ks_c_5601-1987">
		</head>
		
		<table border width="100%">
		<tr align="center">
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","회원 아이디")?></strong></font></td>
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","회원명")?></strong></font></td>
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","멤버십 이름")?></strong></font></td>
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","멤버십 시작일")?></strong></font></td>
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","멤버십 종료일")?></strong></font></td>
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","전체금액")?></strong></font></td>
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","결제금액")?></strong></font></td>
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","결제수단")?></strong></font></td>
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","요청일시")?></strong></font></td>
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","입금상태")?></strong></font></td>
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","입금일자")?></strong></font></td>
			<!--<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","환불요청금액")?></strong></font></td>
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","환불요청일자")?></strong></font></td>
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","환불금액")?></strong></font></td>
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","환불일자")?></strong></font></td>-->
			<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","관리자 메모")?></strong></font></td>
		</tr>
			<? if(mysqli_num_rows($result)==0) { ?>
				<tr>
					<td colspan="8" height="40"><strong><?=iconv("UTF-8","EUC-KR","내역이 없습니다.")?></strong></td>
				</tr>
			<? } ?>
			<?
				for ($ikm=0; $ikm<mysqli_num_rows($result); $ikm++){
					$row = mysqli_fetch_array($result);
					
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $ikm;
			
			?>

				<tr bgcolor=#ffffff align="center" height="22">
					<td><?=$row['user_id']?></td>
					<td><?=iconv("UTF-8","EUC-KR",$row['order_name'])?></td>
					<td><?=iconv("UTF-8","EUC-KR",$row['payment_str'])?></td>
					<td><?=$row['s_date']?></td>
					<td><?=$row['e_date']?></td>
					<td><?=number_format($row['price_total_org'])?> <?=iconv("UTF-8","EUC-KR","원")?></td>
					<td><?=number_format($row['price_total'])?> <?=iconv("UTF-8","EUC-KR","원")?></td>
					<td><?=iconv("UTF-8","EUC-KR",get_payment_method($row['pay_sect_1']))?></td>
					<td><?=$row['order_date']?></td>
					<td><?=iconv("UTF-8","EUC-KR",get_order_status($row['orderstat']))?></td>
					<td><?=substr($row['payment_date'],0,10)?></td>
					<!--<td><?=number_format($row['cancel_ing_payment'])?> <?=iconv("UTF-8","EUC-KR","원")?></td>
					<td><?=$row['cancel_ing_date']?></td>
					<td><?=number_format($row['cancel_payment'])?> <?=iconv("UTF-8","EUC-KR","원")?></td>
					<td><?=$row['cancel_date']?></td>-->
					<td><?=iconv("UTF-8","EUC-KR",$row['admin_bigo'])?></td>
				</tr>
			<?
					$total_price_total_org = $total_price_total_org+$row['price_total_org'];
					$total_pay_refund = $total_pay_refund+$row['pay_refund'];
					$total_price_total = $total_price_total+$row['price_total'];
					$total_cancel_ing_payment = $total_cancel_ing_payment+$row[cancel_ing_payment];
					$total_cancel_payment = $total_cancel_payment+$row[cancel_payment];
				} // 루프 종료 
			?>		

			<tr bgcolor=#ffffff align="center" height="22">
				<td bgcolor=#CCCCCC><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","총 계")?></strong></font></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td><?=number_format($total_price_total_org)?> <?=iconv("UTF-8","EUC-KR","원")?></td>
				<td><?=number_format($total_price_total)?> <?=iconv("UTF-8","EUC-KR","원")?></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
		</table>