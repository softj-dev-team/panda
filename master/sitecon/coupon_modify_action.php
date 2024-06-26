<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
	$v_step = trim(sqlfilter($_REQUEST['v_step']));
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$field = trim(sqlfilter($_REQUEST['field']));
	$keyword = sqlfilter($_REQUEST['keyword']);
		
	$coupon_name = trim(sqlfilter($_REQUEST['coupon_name']));
	$coupon_type = trim(sqlfilter($_REQUEST['coupon_type']));
	$coupon_point_1 = trim(sqlfilter($_REQUEST['coupon_point_1']));
	$coupon_point_2 = trim(sqlfilter($_REQUEST['coupon_point_2']));
	$end_date = trim(sqlfilter($_REQUEST['end_date']));
	$is_del = trim(sqlfilter($_REQUEST['is_del']));

	if($coupon_type == "m"){
		if(!$coupon_point_1){
			error_frame("할인금액을 입력하세요.");
		}
		$coupon_point = $coupon_point_1;
	} elseif($coupon_type == "p"){
		if(!$coupon_point_2){
			error_frame("할인율을 입력하세요.");
		}
		$coupon_point = $coupon_point_2;
	}

	$query = " update coupon_info set "; 
	$query .= " coupon_name = '".$coupon_name."', ";
	$query .= " coupon_point = '".$coupon_point."', ";
	$query .= " coupon_type = '".$coupon_type."', ";
	$query .= " end_date = '".$end_date."', ";
	$query .= " is_del = '".$is_del."' ";
	$query .= " where 1 and idx = '".$idx."' ";
	$result = mysqli_query($gconnet,$query);
	
	if($result){
		error_frame_go("수정이 정상적으로 완료 되었습니다.","coupon_list.php?bmenu=".$bmenu."&smenu=".$smenu."&v_step=".$v_step."&pageNo=".$pageNo."&field=".$field."&keyword=".$keyword."&cate_code1=".$cate_code1."&cate_code2=".$cate_code2."&cate_code3=".$cate_code3."");
	} else {
		error_frame("오류가 발생했습니다.");
	}

?>
