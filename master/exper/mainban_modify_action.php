<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/yonex_master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pro_name = trim(sqlfilter($_REQUEST['pro_name']));
$pro_idx = trim(sqlfilter($_REQUEST['pro_idx']));
$pro_code = goodcd($pro_idx);

$total_param = trim(sqlfilter($_REQUEST['total_param']));
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));

	$main_sect = trim(sqlfilter($_REQUEST['main_sect']));
	$main_type = trim(sqlfilter($_REQUEST['main_type']));
	$main_memo = trim(sqlfilter($_REQUEST['main_memo']));
	
	$start_date = trim(sqlfilter($_REQUEST['start_date']));
	$start_hour = trim(sqlfilter($_REQUEST['start_hour']));
	$start_minute = trim(sqlfilter($_REQUEST['start_minute']));
	$start_time = $start_hour.":".$start_minute;
	$start_date = $start_date." ".$start_time;

	$end_date = trim(sqlfilter($_REQUEST['end_date']));
	$end_hour = trim(sqlfilter($_REQUEST['end_hour']));
	$end_minute = trim(sqlfilter($_REQUEST['end_minute']));
	$end_time = $end_hour.":".$end_minute;
	$end_date = $end_date." ".$end_time;
	
	$sale_price_mem = trim(sqlfilter($_REQUEST['sale_price_mem']));
	$sale_price_non = trim(sqlfilter($_REQUEST['sale_price_non']));
	$timesale_type = trim(sqlfilter($_REQUEST['timesale_type']));

	$view_ok = trim(sqlfilter($_REQUEST['view_ok']));
	$align = trim(sqlfilter($_REQUEST['align']));
	
	$wdate = date("Y-m-d H:i:s");
		
	$query = " update main_display_set set "; 
	$query .= " main_sect = '".$main_sect."', ";
	$query .= " main_type = '".$main_type."', ";
	$query .= " pro_idx = '".$pro_idx."', ";
	$query .= " pro_code = '".$pro_code."', ";
	$query .= " main_memo = '".$main_memo."', ";
	$query .= " view_ok = '".$view_ok."', ";
	$query .= " start_date = '".$start_date."', ";
	$query .= " end_date = '".$end_date."', ";
	$query .= " sale_price_mem = '".$sale_price_mem."', ";
	$query .= " sale_price_non = '".$sale_price_non."', ";
	$query .= " timesale_type = '".$timesale_type."', ";
	$query .= " align = '".$align."' ";
	//$query .= " wdate = '".$wdate."' ";	
	$query .= " where idx = '".$idx."' ";

	//echo $query;

	$result = mysqli_query($gconnet,$query);
	//exit;
	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 설정이 수정 되었습니다.');
	parent.location.href =  "mainban_view.php?idx=<?=$idx?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>