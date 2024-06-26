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
	
	$code_level = trim(sqlfilter($_REQUEST['code_level']));
	$code_code1 = trim(sqlfilter($_REQUEST['code_code1']));
	$code_code2 = trim(sqlfilter($_REQUEST['code_code2']));
	$code_code3 = trim(sqlfilter($_REQUEST['code_code3']));
	
	if($code_level == 1){
		
		$code_str = "카테고리";

		$query = " delete from system_code where 1=1 "; 
		$query .= " and code_code1 = '".$code_code1."' "; // 대분류 코드에 해당하는 대,중,소 카테고리 모두 삭제
		
	} elseif($code_level == 2){
		$code_str = "중분류";

		$query = " delete from system_code where 1=1 "; 
		$query .= " and code_code1 = '".$code_code1."' and code_code2 = '".$code_code2."' "; // 중분류 코드에 해당하는 중,소 카테고리 모두 삭제

	} elseif($code_level == 3){
		$code_str = "소분류";

		$query = " delete from system_code where 1=1 "; 
		$query .= " and code_code1 = '".$code_code1."' and code_code2 = '".$code_code2."' and code_code3 = '".$code_code3."' "; // 소분류 코드에 해당하는 모두 삭제
	} 
	
	//echo $query; exit;

	$result = mysqli_query($gconnet,$query);
	
	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$code_str?> 삭제가 정상적으로 완료 되었습니다.');
	parent.location.href =  "code_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&code_code1=<?=$code_code1?>&code_code2=<?=$code_code2?>&code_code3=<?=$code_code3?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$code_str?> 삭제중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
