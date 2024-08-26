<?php 
include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php";  // ERP 와 독립적인 DB 커넥션
set_time_limit(0);

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
$s_mem_sect =  trim(sqlfilter($_REQUEST['s_mem_sect'])); // 주문자 구분
$s_group = sqlfilter($_REQUEST['s_group']); // 입점업체
$cafe_name = sqlfilter($_REQUEST['cafe_name']); // 입점업체

$where = " and is_del='N' and view_ok='S'";

if($s_date || $e_date){ // 범위지정으로 시작일, 혹은 종료일
	$s_cal_date = $s_date." 00:00:00";
	$e_cal_date = $e_date." 23:59:59";

	if($s_date){
		$where .= " and vdate >= '".$s_cal_date."'";
		$v_s_date = $s_date;
	}

	if($e_date){
		$where .= " and vdate <= '".$e_cal_date."'";
		$v_e_date = $e_date;
	}

}

if($s_group){
	$where .= " and partner_idx='".$s_group."'";
}

if ($field && $keyword){
	$where .= " and ".$field." like '%".$keyword."%'";
}

$pageScale = 100; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by wdate asc ";

if($s_group){
	$query = "select *,(select user_name from member_info where 1 and member_type = 'PAT' and idx=sil_change_money.partner_idx) as user_name,(select user_id from member_info where 1 and member_type = 'PAT' and idx=sil_change_money.partner_idx) as user_id from sil_change_money where 1 ".$where.$order_by;
	$result = mysqli_query($gconnet,$query);
}

$pay_str = "환급완료 송금";

if($b_name){
	$pay_str =  $pay_str."_".$b_name;
} else {
	$pay_str =  $pay_str;
}

$pay_str =  iconv("UTF-8","EUC-KR",$pay_str);

$filename = $pay_str."_".date("Y-m-d").".xls";

Header( "Content-type: application/vnd.ms-excel" ); 
Header( "Content-Disposition: attachment; filename=".$filename ); 
Header( "Content-Description: PHP4 Generated Data" );
?>
		<head>
			<meta http-equiv=Content-Type content="text/html; charset=ks_c_5601-1987">
		</head>
		
		<table border width="100%">
		<tr align="center">
			<td bgcolor="#CCCCCC"><font color="#669900"><strong><?php echo iconv("UTF-8","EUC-KR","신청일시")?></strong></font></td>
			<td bgcolor="#CCCCCC"><font color="#669900"><strong><?php echo iconv("UTF-8","EUC-KR","히얼업 이름")?></strong></font></td>
			<td bgcolor="#CCCCCC"><font color="#669900"><strong><?php echo iconv("UTF-8","EUC-KR","히얼업 아이디")?></strong></font></td>
			<td bgcolor="#CCCCCC"><font color="#669900"><strong><?php echo iconv("UTF-8","EUC-KR","아티스트 회신율")?></strong></font></td>
			<td bgcolor="#CCCCCC"><font color="#669900"><strong><?php echo iconv("UTF-8","EUC-KR","신청실링")?></strong></font></td>
			<td bgcolor="#CCCCCC"><font color="#669900"><strong><?php echo iconv("UTF-8","EUC-KR","환급은행")?></strong></font></td>
			<td bgcolor="#CCCCCC"><font color="#669900"><strong><?php echo iconv("UTF-8","EUC-KR","환급계좌")?></strong></font></td>
			<td bgcolor="#CCCCCC"><font color="#669900"><strong><?php echo iconv("UTF-8","EUC-KR","예금주")?></strong></font></td>
			<td bgcolor="#CCCCCC"><font color="#669900"><strong><?php echo iconv("UTF-8","EUC-KR","송금금액")?></strong></font></td>
			<td bgcolor="#CCCCCC"><font color="#669900"><strong><?php echo iconv("UTF-8","EUC-KR","송금일자")?></strong></font></td>
			<td bgcolor="#CCCCCC"><font color="#669900"><strong><?php echo iconv("UTF-8","EUC-KR","송금처리 관리자 ID")?></strong></font></td>
		</tr>
		<?php if(!$s_group){?>
				<tr>
					<td colspan="8" height="40"><strong><?php echo iconv("UTF-8","EUC-KR","히얼업을 선택하여 검색해 주세요.")?></strong></td>
				</tr>
		<?php } else {?>
			<?php
				for ($ikm=0; $ikm<mysqli_num_rows($result); $ikm++){
					$row = mysqli_fetch_array($result);
					
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $ikm;
					
					if($row[calc_stat] == "Y"){
						$calc_stat_str = "정산완료";
					} elseif($row[calc_stat] == "N"){
						$calc_stat_str = "미정산";
					}
				?>	
					<tr bgcolor="#ffffff" align="center" height="22">
						<td><?php echo $row[wdate]?></td>
						<td><?php echo iconv("UTF-8","EUC-KR",$row[user_name])?></td>
						<td><?php echo $row[user_id]?></td>
						<td><?=number_format(get_support_reply_cnt($row['partner_idx'],$row[wdate]))?>/<?=number_format(get_support_info_cnt($row['partner_idx'],$row[wdate]))?> (<?=get_support_reply_per($row['partner_idx'],$row[wdate])?>%)</td>
						<td><?php echo number_format($row[total_sil_cnt])?> <?php echo iconv("UTF-8","EUC-KR","개")?></td>
						<td><?php echo iconv("UTF-8","EUC-KR",get_bank_name($row[bank_code]))?></td>
						<td style='mso-number-format:"\@";'><?php echo $row[refund_account]?></td>
						<td><?php echo iconv("UTF-8","EUC-KR",$row[refund_nm])?></td>
						<td><?php echo number_format($row[send_money])?> <?php echo iconv("UTF-8","EUC-KR","원")?></td>
						<td><?php echo $row[vdate]?></td>
						<td><?php echo $row[send_admin_id]?></td>
					</tr>
			<?php
					//if($row[calc_stat] == "N"){
						$total_price_total = $total_price_total+$row[total_sil_cnt];
						$total_send_m = $total_send_m+$row[send_money];
					//}
				} // 루프 종료 
			?>	
			
				<tr bgcolor="#ffffff" align="center" height="22">
					<td style="height:29px; text-align:center; border:1px solid #cdcdcd; border-right:0 none; background:#eee"><?php echo iconv("UTF-8","EUC-KR","총 계")?></td>
					<td style='mso-number-format:"\@";'></td>
					<td style='mso-number-format:"\@";'></td>
					<td style='mso-number-format:"\@";'></td>
					<td style='mso-number-format:"\@";'><?php echo number_format($total_price_total)?> <?php echo iconv("UTF-8","EUC-KR","개")?></td>
					<td style='mso-number-format:"\@";'></td>
					<td style='mso-number-format:"\@";'></td>
					<td style='mso-number-format:"\@";'></td>
					<td style='mso-number-format:"\@";'><?php echo number_format($total_send_m)?> <?php echo iconv("UTF-8","EUC-KR","원")?></td>
					<td style='mso-number-format:"\@";'></td>
					<td style='mso-number-format:"\@";'></td>
				</tr>
			<?php }?>
			</table>