<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; ?><? include $_SERVER["DOCUMENT_ROOT"]."/plsone_master/inc/check_login3_sub.php"; ?>
<?
	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
	
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$coupon_title = trim(sqlfilter($_REQUEST['coupon_title']));
	$coupon_price = trim(sqlfilter($_REQUEST['coupon_price']));
	$expire_date = trim(sqlfilter($_REQUEST['expire_date']));

	$coupon_sect = "A"; // 쿠폰발급 : A , 쿠폰사용 : M

	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$field = trim(sqlfilter($_REQUEST['field']));
	$keyword = sqlfilter($_REQUEST['keyword']);
		
	$query_mile = " insert into member_coupon set "; 
	$query_mile .= " section = '".$_SESSION['admin_yonex_section']."',";
	$query_mile .= " order_num = '".$order_num."', ";
	$query_mile .= " coupon_idx = '".$coupon_idx."', ";
	$query_mile .= " member_idx = '".$member_idx."', ";
	$query_mile .= " pay_price = '".$pay_price."', ";
	$query_mile .= " coupon_sect = '".$coupon_sect."', ";
	$query_mile .= " coupon_title = '".$coupon_title."', ";
	$query_mile .= " coupon_price = '".$coupon_price."', ";
	$query_mile .= " expire_date = '".$expire_date."', ";
	$query_mile .= " wdate = now() ";
	$result_mile = mysqli_query($gconnet,$query_mile);
		
	if($result_mile){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('회원개별 쿠폰 발급이 정상적으로 완료 되었습니다.');
	parent.location.href =  "member_coupon_list.php?pageNo=<?=$pageNo?>&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&field=<?=$field?>&keyword=<?=urlencode($keyword)?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('회원개별 쿠폰 발급중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>