<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
$total_param = trim(sqlfilter($_REQUEST['total_param']));
$point_sect = trim(sqlfilter($_REQUEST['point_sect']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));

if($point_sect == "point"){
	$point_str = "포인트";
} elseif($point_sect == "refund"){
	$point_str = "포인트";
} elseif($point_sect == "stamp"){
	$point_str = "G 스탬프";
} elseif($point_sect == "badp"){
	$point_str = "패널티";
} elseif($point_sect == "mp"){
	$point_str = "매너포인트";
} 

$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
$mile_pre = trim(sqlfilter($_REQUEST['mile_pre']));
$chg_mile = trim(sqlfilter($_REQUEST['chg_mile']));
$mile_title = trim(sqlfilter($_REQUEST['mile_title']));
$mile_sect = trim(sqlfilter($_REQUEST['mile_sect']));

	if($mile_sect == "A"){
		$cur_mile = $mile_pre + $chg_mile;
	} elseif($mile_sect == "M"){
		$cur_mile = $mile_pre - $chg_mile;
	}

	if($cur_mile < 0 ){
		$cur_mile = 0;
	}

	$ad_sect = $_SESSION['admin_homest_id'];
	$wdate = date("Y-m-d H:i:s");

	/*$query_mile = "insert into member_point (order_num,ServerGbn,UserGbn,CustSeq,pay_price,mile_title,mile_sect,mile_pre,chg_mile,cur_mile,point_sect,ad_sect,wdate) values (N'".$order_num."',N'".$ServerGbn."',N'".$UserGbn."',N'".$CustSeq."',N'".$pay_price."',N'".$mile_title."',N'".$mile_sect."',N'".$mile_pre."',N'".$chg_mile."',N'".$cur_mile."',N'".$point_sect."',N'".$ad_sect."',N'".$wdate."')";*/
	
	//echo $query;

	$query_mile = " insert into member_point set "; 
	$query_mile .= " order_num = '".$order_num."', ";
	$query_mile .= " member_idx = '".$member_idx."', ";
	$query_mile .= " pay_price = '".$pay_price."', ";
	$query_mile .= " mile_title = '".$mile_title."', ";
	$query_mile .= " mile_sect = '".$mile_sect."', ";
	$query_mile .= " mile_pre = '".$mile_pre."', ";
	$query_mile .= " chg_mile = '".$chg_mile."', ";
	$query_mile .= " cur_mile = '".$cur_mile."', ";
	$query_mile .= " point_sect = '".$point_sect."', ";
	$query_mile .= " ad_sect = '".$ad_sect."', ";
	$query_mile .= " wdate = now() ";
	
	$result_mile = mysqli_query($gconnet,$query_mile);
		
	if($result_mile){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$point_str?> 설정이 정상적으로 완료 되었습니다.');
	parent.location.href =  "member_point_list.php?<?=$total_param?>&pageNo=<?=$pageNo?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>