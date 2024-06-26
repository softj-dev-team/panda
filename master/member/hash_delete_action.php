<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
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
	$cate_code2 = trim(sqlfilter($_REQUEST['cate_code2']));
	$cate_code3 = trim(sqlfilter($_REQUEST['cate_code3']));
	
	if($cate_level == 1){
		
		$cate_str = "카테고리";

		$query = " delete from viva_cate where 1=1 "; 
		$query .= " and cate_code1 = '".$cate_code1."' "; // 대분류 코드에 해당하는 대,중,소 카테고리 모두 삭제
		
	} elseif($cate_level == 2){
		$cate_str = "카테고리";

		$query = " delete from viva_cate where 1=1 "; 
		$query .= " and cate_code1 = '".$cate_code1."' and cate_code2 = '".$cate_code2."' "; // 중분류 코드에 해당하는 중,소 카테고리 모두 삭제

	} elseif($cate_level == 3){
		$cate_str = "카테고리";

		$query = " delete from viva_cate where 1=1 "; 
		$query .= " and cate_code1 = '".$cate_code1."' and cate_code2 = '".$cate_code2."' and cate_code3 = '".$cate_code3."' "; // 소분류 코드에 해당하는 모두 삭제
	} 
	
	//echo $query; exit;

	$result = mysqli_query($gconnet,$query);
	
	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$cate_str?> 삭제가 정상적으로 완료 되었습니다.');
	parent.location.href =  "hash_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&cate_code1=<?=$cate_code1?>&cate_code2=<?=$cate_code2?>&cate_code3=<?=$cate_code3?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$cate_str?> 삭제중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
