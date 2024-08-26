<? include "../../pro_inc/include_default.php"; // ERP 와 독립적인 DB 커넥션

$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 회원, 지점
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 일반, VIP
$s_level = sqlfilter($_REQUEST['s_level']); // 회원등급
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); // 로그인 구분
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); // 추천인 (지점) 별
$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 

$where = " and memout_yn != 'Y' and memout_yn != 'S' and del_yn='N'";

if($s_gubun == "NOR"){
	$member_sect_str = "일반";
} elseif($s_gubun == "SPE"){
	$member_sect_str = "VVIP 멤버십";
}

if(!$s_order){
	$s_order = 1; 
}

if($v_sect){
	$where .= " and member_type = '".$v_sect."'";
}

if($s_gubun){
	if($s_gubun == "NOR2"){
		$where .= " and ipin_code = 'cell' "; // 휴대폰 인증회원
	} else {
		$where .= " and member_gubun = '".$s_gubun."' ";
	}
}

if($s_level){
	//$where .= " and user_level = '".$s_level."' ";
	$where .= " and nation = '".$s_level."' ";
}

if($s_gender){
	$where .= " and gender = '".$s_gender."' ";
}

if($s_sect1){
	if($s_sect1 == "no" || $s_sect1 == "com"){
		$where .= " and pay_status = '".$s_sect1."'";
	} else {
		$where .= " and payment_type = '".$s_sect1."'";
	}
}

if($s_sect2){
	$where .= " and chuchun_idx = '".$s_sect2."' ";
}

if ($field && $keyword){
	$where .= " and ".$field." like '%".$keyword."%'";
}

$pageScale = $s_cnt;  
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

if($s_order == 1){
	$order_by = " order by wdate desc ";
} elseif($s_order == 2){
	$order_by = " order by wdate asc ";
} elseif($s_order == 3){
	$order_by = " order by user_name asc ";
} elseif($s_order == 4){
	$order_by = " order by user_name desc ";
}

$query = "select *,(select s_date from membership_auth where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as s_date,(select e_date from membership_auth where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as e_date from member_info a where 1 ".$where.$order_by;
//echo $query; //exit;
$result = mysqli_query($gconnet,$query);

if($s_sect2){
	$b_name_sql = "select com_name from member_info where 1 and idx='".$s_sect2."'";
	$b_name_query = mysqli_query($GLOBALS['gconnet'],$b_name_sql);
	$b_name_row = mysqli_fetch_array($b_name_query);
	$b_name = $b_name_row['com_name']." 추천인별 ";
}

$pay_str = "회원리스트";

if($b_name){
	$pay_str =  $pay_str."_".$b_name;
} else {
	$pay_str =  $pay_str;
}

$pay_str =  iconv("UTF-8","EUC-KR",$pay_str);

$filename = $pay_str."_".date("Y-m-d").".xls";
//$filename = iconv("UTF-8","EUC-KR",$filename);
//if($_SERVER['REMOTE_ADDR'] != "121.167.147.150"){	
Header( "Content-type: application/vnd.ms-excel" ); 
Header( "Content-Disposition: attachment; filename=".$filename ); 
Header( "Content-Description: PHP4 Generated Data" );
?>

<head>
			<meta http-equiv=Content-Type content="text/html; charset=ks_c_5601-1987">
		</head>
		

		<table border width="100%">
		<tr bgcolor=#CCCCCC align="center">
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","아이디")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","성명")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","휴대전화")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","이메일")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","이용멤버쉽")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","적용기간")?></strong></font></td>
			<td><font color="#669900"><strong><?=iconv("UTF-8","EUC-KR","등록일시")?></strong></font></td>
		</tr>
		<? if(mysqli_num_rows($result)==0) { ?>
				<tr>
					<td colspan="20" height="40"><strong><?=$pay_str?> <?=iconv("UTF-8","EUC-KR","내역이 없습니다.")?></strong></td>
				</tr>
		<? } ?>
		<?
		for ($ikm=0; $ikm<mysqli_num_rows($result); $ikm++){
				$row = mysqli_fetch_array($result);
		?>
			<tr bgcolor=#ffffff align="center" height="22">
				<td><?=$row['user_id']?></td>
				<td><?=iconv("UTF-8","EUC-KR",$row['user_name'])?></td>
				<td><?=$row['cell']?></td>
				<td><?=$row['email']?></td>
				<td>
					<?if($row['pay_status'] == "com"){?>
						<?=iconv("UTF-8","EUC-KR","종료")?>
					<?}elseif($row['pay_status'] == "no"){?>
						<?=iconv("UTF-8","EUC-KR","없음")?>
					<?}else{?>
						<?=iconv("UTF-8","EUC-KR",get_code_value("cate_name1","cate_code1",$row['payment_type']))?>
					<?}?>
				</td>
				<td>
				<?if($row['pay_status'] == "com" || $row['pay_status'] == "no"){?>
				<?}else{?>
					<?=$row['s_date']?> ~ <?=$row['e_date']?>
				<?}?>
				</td>
				<td><?=$row['wdate']?></td>
			</tr>
	   <?}?>
		</table>