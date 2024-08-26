<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$exp_info_idx = trim(sqlfilter($_REQUEST['exp_info_idx']));
	$total_param = trim(sqlfilter($_REQUEST['total_param']));
	$tafter_add_count_1 = trim(sqlfilter($_REQUEST['tafter_add_count_1']));
	$member_idx =  trim(sqlfilter($_REQUEST['member_idx']));

	$exp_o_money =  trim(sqlfilter($_REQUEST['exp_o_money']));
	//$exp_d_money =  trim(sqlfilter($_REQUEST['exp_d_money']));
	$regi_content =  trim(sqlfilter($_REQUEST['regi_content']));
	
	$query = " update exp_info set "; 
	$query .= " exp_o_money = '".$exp_o_money."', ";
	//$query .= " exp_d_money = '".$exp_d_money."', ";
	$query .= " regi_content = '".$regi_content."' ";
	$query .= " where 1 and idx = '".$exp_info_idx."'";
	$result = mysqli_query($gconnet,$query);

	//echo $query."<br>";

	################# 옵션정보 시작 #######################
	for($file_i=0; $file_i<$tafter_add_count_1; $file_i++){ // 갯수만큼 루프 시작
		//echo "번호 = ".$file_i."<br>";
		//echo "옵션 = ".$_REQUEST['option_title_'.$file_i.'']."<br>";
		
		$file_idx = trim(sqlfilter($_REQUEST['option_idx_'.$file_i.''])); // 기존 옵션 PK 값.
		$option_title = trim(sqlfilter($_REQUEST['option_title_'.$file_i.''])); // 옵션명
		$del_org = trim(sqlfilter($_REQUEST['option_del_'.$file_i.'']));	// 삭제여부

		if(!$file_idx){
			if($option_title){
				$ad2_align_sql = "select idx from exp_option_info where 1 and exp_info_idx = '".$exp_info_idx."'";
				$ad2_align_query = mysqli_query($gconnet,$ad2_align_sql);
				$ad2_align_num = mysqli_num_rows($ad2_align_query);
				$ad2_align = $ad2_align_num+1;
			
				$query_add2 = " insert into exp_option_info set "; 
				$query_add2 .= " exp_info_idx = '".$exp_info_idx."', ";
				$query_add2 .= " opt_title = '".$option_title."', ";
				$query_add2 .= " member_idx = '".$member_idx."', ";
				$query_add2 .= " align = '".$ad2_align."', ";
				$query_add2 .= " wdate = now() ";
				$result_add2 = mysqli_query($gconnet,$query_add2);
			}
		} else {
			if($del_org == "Y"){
				$query_add2 = " update exp_option_info set "; 
				$query_add2 .= " is_del = 'Y' ";
				$query_add2 .= " where 1 and idx = '".$file_idx."' ";
				$result_add2 = mysqli_query($gconnet,$query_add2);
			} else {
				$query_add2 = " update exp_option_info set "; 
				$query_add2 .= " opt_title = '".$option_title."' ";
				$query_add2 .= " where 1 and idx = '".$file_idx."' ";
				$result_add2 = mysqli_query($gconnet,$query_add2);
			}
		}
			//$option_title = "";
			//echo $query_add2."<br>";
	} // 갯수만큼 루프 종료
	################# 보기정보 종료 #######################
	
	//exit;
?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('설정이 정상적으로 완료 되었습니다.');
	parent.location.href =  "exp_view.php?idx=<?=$exp_info_idx?>&<?=$total_param?>";
	//-->
	</SCRIPT>