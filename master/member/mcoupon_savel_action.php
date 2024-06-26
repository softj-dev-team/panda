<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/yonex_master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
$mem_idx = sqlfilter($_REQUEST['mem_idx']); 
$mode_1 = sqlfilter($_REQUEST['mode_1']); // 정회원,우수회원,셀러회원 등 검색
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별 검색
$s_level = sqlfilter($_REQUEST['s_level']); // 회원 등급별 검색
$v_sect = sqlfilter($_REQUEST['v_sect']); // 생년월일 검색
$s_professor = trim(sqlfilter($_REQUEST['s_professor']));
$s_area1 = urldecode(sqlfilter($_REQUEST['s_area1']));
$s_area2 = urldecode(sqlfilter($_REQUEST['s_area2']));

$txt_receiver = $_REQUEST['txt_receiver'];

############ 생성한 쿠폰정보 ############

$coupon_pkColumn = "idx";
$coupon_value = $mem_idx;
$coupon_tableName = "member_coupon_set";
$coupon_result = MgrGeneralView($coupon_tableName, $coupon_pkColumn, $coupon_value);
$coupon_row = mysqli_fetch_array($coupon_result);

############ 생성한 쿠폰정보 종료 ##########
	
	$txt_receiver_arr = explode(",",$txt_receiver);

	for($k=0; $k<sizeof($txt_receiver_arr); $k++){
		$query = "select * from member_info where 1 and user_id='".$txt_receiver_arr[$k]."' ";
		$result = mysqli_query($gconnet,$query);
		$row = mysqli_fetch_array($result);

		$coupon_sect = "A"; // 쿠폰발급 : A , 쿠폰사용 : M
		$section = $_SESSION['admin_yonex_section'];

		$query_mile = " insert into member_coupon set "; 
		$query_mile .= " section = '".$section."', ";
		$query_mile .= " order_num = '".$order_num."', ";
		$query_mile .= " coupon_idx = '".$mem_idx."', ";
		$query_mile .= " member_idx = '".$row['idx']."', ";
		$query_mile .= " pay_price = '".$pay_price."', ";
		$query_mile .= " coupon_sect = '".$coupon_sect."', ";
		$query_mile .= " coupon_title = '".$coupon_row[coupon_title]."', ";
		$query_mile .= " dis_type = '".$coupon_row[dis_type]."', ";
		$query_mile .= " coupon_per = '".$coupon_row[coupon_per]."', ";
		$query_mile .= " coupon_price = '".$coupon_row[coupon_price]."', ";
		$query_mile .= " expire_date = '".$coupon_row[expire_date]."', ";
		$query_mile .= " wdate = now() ";
		$result_mile = mysqli_query($gconnet,$query_mile);

	} // 쿠폰발급 대상자 루프 종료

?>
	<?
	if($result_mile){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('쿠폰발급이 정상적으로 완료 되었습니다.');
	parent.location.href =  "mcoupon_list.php?bmenu=1&smenu=6";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('쿠폰발급중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>