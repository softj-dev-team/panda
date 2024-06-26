<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$mode = trim(sqlfilter($_REQUEST['mode']));
	$sect = trim(sqlfilter($_REQUEST['o_sect']));
	$mem_idx = $_REQUEST['mem_idx'];
	$idx = trim(sqlfilter($_REQUEST['idx']));
	
	$re_url = trim(sqlfilter($_REQUEST['re_url']));
	
	if($sect == "totall") { // 선택한것 일괄 처리
		$total_param = trim(sqlfilter($_REQUEST['total_param']));
	} elseif($sect == "one"){ // 개별처리
		$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
		$smenu = trim(sqlfilter($_REQUEST['smenu']));
		$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
		$field = trim(sqlfilter($_REQUEST['field']));
		$keyword = sqlfilter($_REQUEST['keyword']);
		$s_level = sqlfilter($_REQUEST['s_level']);
		$v_sect = sqlfilter($_REQUEST['v_sect']);
		$s_gender = sqlfilter($_REQUEST['s_gender']);
		$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&v_sect='.$v_sect.'&s_gender='.$s_gender.'&pageNo='.$pageNo;
	}

	if($mode == "outcan"){ // 탈퇴신청 취소처리 시작

		$restr = "탈퇴신청 취소처리";
		
			if($sect == "one"){ // 개별처리
			
				$query = " update member_info set "; 
				$query .= " memout_yn = 'N', ";
				$query .= " memout_sect = '', ";
				$query .= " memout_memo = '', ";
				$query .= " out_s_date = '' ";
				$query .= " where idx='".$idx."' ";

			} elseif($sect == "totall") { // 선택한것 일괄 처리

				for($k=0; $k<sizeof($mem_idx); $k++){ // 선택수 루프
				
					$query = " update member_info set "; 
					$query .= " memout_yn = 'N', ";
					$query .= " memout_sect = '', ";
					$query .= " memout_memo = '', ";
					$query .= " out_s_date = '' ";
					$query .= " where idx='".$mem_idx[$k]."' ";

				} // 선택수 루프 종료

			} // 개별 및 일괄처리 모두 종료

	} elseif($mode == "outcom"){ // 탈퇴신청 완료처리 시작 

		$restr = "탈퇴신청 완료처리";

		if($sect == "one"){ // 개별처리
			
				$query = " update member_info set "; 
				$query .= " memout_yn = 'Y', ";
				$query .= " memout_sect = '', ";
				$query .= " memout_memo = '', ";
				$query .= " out_m_date = now() ";
				$query .= " where idx='".$idx."' ";

			} elseif($sect == "totall") { // 선택한것 일괄 처리

				for($k=0; $k<sizeof($mem_idx); $k++){ // 선택수 루프
				
					$query = " update member_info set "; 
					$query .= " memout_yn = 'Y', ";
					$query .= " memout_sect = '', ";
					$query .= " memout_memo = '', ";
					$query .= " out_m_date = now() ";
					$query .= " where idx='".$mem_idx[$k]."' ";

				} // 선택수 루프 종료

			} // 개별 및 일괄처리 모두 종료

	} // 탈퇴신청 완료처리 종료
	
	//echo $query; exit;

	$result = mysqli_query($gconnet,$query);

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$restr?> 가 정상적으로 완료 되었습니다.');
	parent.location.href =  "<?=$re_url?>?idx=<?=$idx?>&<?=$total_param?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('<?=$restr?> 중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>