<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$v_sect = trim(sqlfilter($_REQUEST['v_sect']));
	
	$prev_sql = "delete from member_category_set where 1=1 and member_idx = '".$member_idx."'";
	$prev_query = mysqli_query($gconnet,$prev_sql);
	
	######### 카테고리 입력 시작 ###########
	for($catei=0; $catei<sizeof($inc_mem_cate_value_arr); $catei++){
		$catek = $catei+1;
		$cate_type = "cate";
		$tag_value = trim(sqlfilter($_REQUEST['mem_cate_'.$catek.'']));
		if($tag_value){
			$query_cat = " insert into member_category_set set "; 
			$query_cat .= " member_idx = '".$member_idx."', ";
			$query_cat .= " cate_type = '".$cate_type."', ";
			$query_cat .= " tag_value = '".$tag_value."', ";
			$query_cat .= " wdate = now() ";
			$result_cat = mysqli_query($gconnet,$query_cat);
		}
	}

	######### 해시태그 입력 시작 ###########
	for($catei=0; $catei<3; $catei++){
		$catek = $catei+1;
		$cate_type = "hast";
		$tag_value = trim(sqlfilter($_REQUEST['mem_hash_'.$catek.'']));
		if($tag_value){
			$query_has = " insert into member_category_set set "; 
			$query_has .= " member_idx = '".$member_idx."', ";
			$query_has .= " cate_type = '".$cate_type."', ";
			$query_has .= " tag_value = '".$tag_value."', ";
			$query_has .= " wdate = now() ";
			$result_has = mysqli_query($gconnet,$query_has);
		}
	}
		
	if($result_cat || $result_has){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('정상적으로 처리 되었습니다.');
	parent.location.href =  "member_view.php?idx=<?=$member_idx?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
