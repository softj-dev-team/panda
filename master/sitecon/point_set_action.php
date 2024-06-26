<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$code = trim(sqlfilter($_REQUEST['code']));
$point_sect = sqlfilter($_REQUEST['point_sect']);

if($point_sect == "cash"){
	$point_str = "캐쉬";
} elseif($point_sect == "refund"){
	$point_str = "포인트";
} elseif($point_sect == "ap"){
	$point_str = "활동포인트";
} elseif($point_sect == "mp"){
	$point_str = "매너포인트";
} 

$member_in_gen = trim(sqlfilter($_REQUEST['member_in_gen']));
$member_chuchun_recev = trim(sqlfilter($_REQUEST['member_chuchun_recev']));
$member_chuchun_send = trim(sqlfilter($_REQUEST['member_chuchun_send']));
$member_special_add = trim(sqlfilter($_REQUEST['member_special_add']));
$member_login_gen = trim(sqlfilter($_REQUEST['member_login_gen']));
$payment_mile_gen = trim(sqlfilter($_REQUEST['payment_mile_gen']));
$review_mile = trim(sqlfilter($_REQUEST['review_mile']));

$add_point_1 =  trim(sqlfilter($_REQUEST['add_point_1']));
$add_point_2 =  trim(sqlfilter($_REQUEST['add_point_2']));
$add_point_3 =  trim(sqlfilter($_REQUEST['add_point_3']));
$add_point_4 =  trim(sqlfilter($_REQUEST['add_point_4']));
$add_point_5 =  trim(sqlfilter($_REQUEST['add_point_5']));
$add_point_6 =  trim(sqlfilter($_REQUEST['add_point_6']));
$add_point_6_1 =  trim(sqlfilter($_REQUEST['add_point_6_1']));

$minus_point_1 =  trim(sqlfilter($_REQUEST['minus_point_1']));
$minus_point_2 =  trim(sqlfilter($_REQUEST['minus_point_2']));
$minus_point_3 =  trim(sqlfilter($_REQUEST['minus_point_3']));
$minus_point_4 =  trim(sqlfilter($_REQUEST['minus_point_4']));

$wdate = date("Y-m-d H:i:s");

	/*$query = "insert into member_point_set (member_in_gen, member_login_gen, payment_mile_gen, review_mile,wdate) values (N'".$member_in_gen."',N'".$member_login_gen."',N'".$payment_mile_gen."',N'".$review_mile."',N'".$wdate."')";*/

	$query = " insert into member_point_set set "; 
	$query .= " coin_type = 'member', ";
	$query .= " member_in_gen = '".$member_in_gen."', ";
	$query .= " member_chuchun_recev = '".$member_chuchun_recev."', ";
	$query .= " member_chuchun_send = '".$member_chuchun_send."', ";
	$query .= " member_special_add = '".$member_special_add."', ";
	$query .= " member_login_gen = '".$member_login_gen."', ";
	$query .= " payment_mile_gen = '".$payment_mile_gen."', ";
	$query .= " review_mile = '".$review_mile."', ";

	$query .= " add_point_1 = '".$add_point_1."', ";
	$query .= " add_point_2 = '".$add_point_2."', ";
	$query .= " add_point_3 = '".$add_point_3."', ";
	$query .= " add_point_4 = '".$add_point_4."', ";
	$query .= " add_point_5 = '".$add_point_5."', ";
	$query .= " add_point_6 = '".$add_point_6."', ";
	$query .= " add_point_6_1 = '".$add_point_6_1."', ";

	$query .= " minus_point_1 = '".$minus_point_1."', ";
	$query .= " minus_point_2 = '".$minus_point_2."', ";
	$query .= " minus_point_3 = '".$minus_point_3."', ";
	$query .= " minus_point_4 = '".$minus_point_4."', ";

	$query .= " point_sect = '".$point_sect."', ";
	$query .= " wdate = now() ";
   	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$point_str?> 지급 설정이 정상적으로 완료 되었습니다.');
	parent.location.href =  "point_set.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&point_sect=<?=$point_sect?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$point_str?> 지급 설정중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
