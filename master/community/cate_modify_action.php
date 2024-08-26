<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>

<?
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
	$v_step = trim(sqlfilter($_REQUEST['v_step']));
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	$field = trim(sqlfilter($_REQUEST['field']));
	$keyword = sqlfilter($_REQUEST['keyword']);
	
	$cate_level = trim(sqlfilter($_REQUEST['cate_level']));
	$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
	$cate_name1 = trim(sqlfilter($_REQUEST['cate_name1']));
	$cate_code2 = trim(sqlfilter($_REQUEST['cate_code2']));
	$cate_name2 = trim(sqlfilter($_REQUEST['cate_name2']));
	$cate_code3 = trim(sqlfilter($_REQUEST['cate_code3']));
	$cate_name3 = trim(sqlfilter($_REQUEST['cate_name3']));
	$cate_code4 = trim(sqlfilter($_REQUEST['cate_code4']));
	$cate_name4 = trim(sqlfilter($_REQUEST['cate_name4']));
	$cate_align = trim(sqlfilter($_REQUEST['cate_align']));
	$is_del = trim(sqlfilter($_REQUEST['is_del']));

	$head_ok = trim(sqlfilter($_REQUEST['head_ok']));
	$aca_ok = trim(sqlfilter($_REQUEST['aca_ok']));
	$stu_ok = trim(sqlfilter($_REQUEST['stu_ok']));
	$nonmem_ok = trim(sqlfilter($_REQUEST['nonmem_ok']));

	if($cate_level == 1){
		
		$cate_str = "카테고리";

		$query = " update board_cate set "; 
		$query .= " cate_name1 = '".$cate_name1."', ";
		$query .= " cate_align = '".$cate_align."', ";
		$query .= " is_del = '".$is_del."' ";
		$query .= " where idx = '".$idx."' ";

	} elseif($cate_level == 2){
		$cate_str = "중분류";

		$query = " update board_cate set "; 
		$query .= " cate_name2 = '".$cate_name2."', ";
		$query .= " cate_align = '".$cate_align."', ";
		$query .= " is_del = '".$is_del."' ";
		$query .= " where idx = '".$idx."' ";

	} elseif($cate_level == 3){
		$cate_str = "소분류";

		$query = " update board_cate set "; 
		$query .= " cate_name3 = '".$cate_name3."', ";
		$query .= " cate_align = '".$cate_align."', ";
		$query .= " is_del = '".$is_del."' ";
		$query .= " where idx = '".$idx."' ";
	} 
	
	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$cate_str?> 수정이 정상적으로 완료 되었습니다.');
	parent.location.href =  "cate_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&cate_code1=<?=$cate_code1?>&cate_code2=<?=$cate_code2?>&cate_code3=<?=$cate_code3?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$cate_str?> 수정중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
