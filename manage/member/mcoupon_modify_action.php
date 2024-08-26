<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));

	$rcnt_sql = "select idx from member_coupon where 1 and coupon_idx='".$idx."' and is_del='N'";
	$rcnt_query = mysqli_query($gconnet,$rcnt_sql);
	$rcnt = mysqli_num_rows($rcnt_query);

	if($rcnt > 0){
		error_frame("다운받은 회원이 존재하기 때문에 수정할 수 없습니다.");
	}
	
	$coupon_num = trim(sqlfilter($_REQUEST['coupon_num']));
	$member_sect = trim(sqlfilter($_REQUEST['member_sect']));
	$member_level = trim(sqlfilter($_REQUEST['member_level']));
	$coupon_title = trim(sqlfilter($_REQUEST['coupon_title']));
	$dis_type = trim(sqlfilter($_REQUEST['dis_type']));
	$coupon_price = trim(sqlfilter($_REQUEST['coupon_price']));
	$coupon_per = trim(sqlfilter($_REQUEST['coupon_per']));
	$expire_date = trim(sqlfilter($_REQUEST['expire_date']));

	$coupon_sect = trim(sqlfilter($_REQUEST['coupon_sect']));
	$expire_date_auto = trim(sqlfilter($_REQUEST['expire_date_auto']));

	/*if($coupon_sect == "auto"){
	
		$sql_pre = "select idx from member_coupon_set where coupon_sect = 'auto' and idx != '".$idx."' ";
		$result_pre  = mysqli_query($gconnet,$sql_pre);
			
			if(mysqli_num_rows($result_pre) > 0) {
			?>
			<SCRIPT LANGUAGE="JavaScript">
			<!--	
			alert('회원가입 자동발행 쿠폰은 이미 생성되어 있습니다.\n\n생성된 쿠폰의 설정변경을 원하시면 생성된 쿠폰확인 메뉴를 통하여 기존의 회원가입 자동발행 쿠폰의 값을 변경해 주십시오.');
			//-->
			</SCRIPT>
			<?
			exit;
			}
	}*/

	$ad_sect_idx = $_SESSION['manage_coinc_idx'];
	$ad_sect_id = $_SESSION['manage_coinc_id'];
	$ad_sect_name = $_SESSION['manage_coinc_name'];
		
	$query = " update member_coupon_set set "; 
	$query .= " coupon_sect = '".$coupon_sect."', ";
	$query .= " coupon_num = '".$coupon_num."', ";
	$query .= " coupon_title = '".$coupon_title."', ";
	$query .= " member_sect = '".$member_sect."', ";
	$query .= " member_level = '".$member_level."', ";
	$query .= " dis_type = '".$dis_type."', ";
	$query .= " coupon_per = '".$coupon_per."', ";
	$query .= " coupon_price = '".$coupon_price."', ";
	$query .= " expire_date = '".$expire_date."', ";
	$query .= " expire_date_auto = '".$expire_date_auto."', ";
	$query .= " ad_sect_idx = '".$ad_sect_idx."', ";
	$query .= " ad_sect_id = '".$ad_sect_id."', ";
	$query .= " ad_sect_name = '".$ad_sect_name."' ";
	$query .= " where idx = '".$idx."' ";
	//echo $query;
	$result = mysqli_query($gconnet,$query);

	/*$query2 = " update member_coupon set "; 
	$query2 .= " coupon_title = '".$coupon_title."', ";
	$query2 .= " dis_type = '".$dis_type."', ";
	$query2 .= " coupon_per = '".$coupon_per."', ";
	$query2 .= " coupon_price = '".$coupon_price."', ";
	$query2 .= " expire_date = '".$expire_date."' ";
	$query2 .= " where  coupon_idx = '".$idx."' and coupon_sect = 'A' ";
	//echo $query2;
	$result2 = mysqli_query($gconnet,$query2);*/

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('생성된 쿠폰의 수정이 정상적으로 완료 되었습니다.');
	parent.location.href =  "mcoupon_view.php?idx=<?=$idx?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('회원일괄쿠폰 정보수정중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>