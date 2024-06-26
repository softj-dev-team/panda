<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	/*echo "<xmp>";
		print_r($_REQUEST);
	echo "</xmp>";*/

	/*echo "<xmp>";
		print_r($_FILES);
	echo "</xmp>";*/
	
	//exit;

	$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
	$smenu = trim(sqlfilter($_REQUEST['smenu']));
	
	$user_id = trim(sqlfilter($_REQUEST['member_id']));
	$user_pwd = trim(sqlfilter($_REQUEST['member_password']));
	$user_pwd = md5($user_pwd);
	$user_name = trim(sqlfilter($_REQUEST['member_name']));
	/*$cell1 = trim(sqlfilter($_REQUEST['cell1']));
	$cell2 = trim(sqlfilter($_REQUEST['cell2']));
	$cell3 = trim(sqlfilter($_REQUEST['cell3']));
	if($cell2){
		$cell .= "-".$cell2;
	}
	if($cell3){
		$cell .= "-".$cell3;
	}*/
	$cell = trim(sqlfilter($_REQUEST['cell']));
	$cell = str_replace("-","",$cell);
	$post = trim(sqlfilter($_REQUEST['zip_code1']));
	$addr1 = trim(sqlfilter($_REQUEST['member_address']));
	$addr2 = trim(sqlfilter($_REQUEST['member_address2']));
	$email = trim(sqlfilter($_REQUEST['member_email']));
	//$email = $user_id;
	$partner_id = trim(sqlfilter($_REQUEST['partner_id']));
	$master_ok = trim(sqlfilter($_REQUEST['master_ok']));
	$member_gubun = trim(sqlfilter($_REQUEST['member_gubun']));

	$user_nick = trim(sqlfilter($_REQUEST['member_nick']));
	//$user_name = $user_nick;
	$birthday = trim(sqlfilter($_REQUEST['birthday']));
	$gender = trim(sqlfilter($_REQUEST['gender']));
	/*$birthday_year = trim(sqlfilter($_REQUEST['birthday_year']));
	$birthday_month = trim(sqlfilter($_REQUEST['birthday_month']));
	$birthday_day = trim(sqlfilter($_REQUEST['birthday_day']));
	$birthday = $birthday_year."-".$birthday_month."-".$birthday_day;*/
	$tel1 = trim(sqlfilter($_REQUEST['tel1']));
	$tel2 = trim(sqlfilter($_REQUEST['tel2']));
	$tel3 = trim(sqlfilter($_REQUEST['tel3']));
	$tel = $tel1."-".$tel2."-".$tel3;
	$bank_name = trim(sqlfilter($_REQUEST['bank_name']));
	$bank_num = trim(sqlfilter($_REQUEST['bank_num']));
	$mail_ok = trim(sqlfilter($_REQUEST['mail_ok']));
		
	$m_channel = trim(sqlfilter($_REQUEST['m_channel']));
	$recom_name = trim(sqlfilter($_REQUEST['recom_name']));
	$m_intro = trim(sqlfilter($_REQUEST['m_intro']));
			
	$sql_pre1 = "select idx from member_info where 1 and user_id = '".$user_id."' and del_yn='N'"; // 회원테이블 아이디 중복여부 체크
	$result_pre1 = mysqli_query($gconnet,$sql_pre1);
	if(mysqli_num_rows($result_pre1) > 0) {
		error_frame("입력하신 아이디는 이미 사용중입니다. 다시 확인하시고 입력해 주세요.");
	}

	if($user_nick){ // 닉네임을 입력했을때 
		$sql_pre4 = "select idx from member_info where 1 and user_nick = '".$user_nick."' and del_yn='N'";
		$result_pre4  = mysqli_query($gconnet,$sql_pre4);
		if(mysqli_num_rows($result_pre4) > 0) {
			error_frame("입력하신 닉네임은 이미 사용중입니다. 다시 확인하시고 입력해 주세요.");
		}
	} // 닉네임을 입력했을때 종료

	if($email){ // 이메일을 입력했을때 
		$sql_pre4 = "select idx from member_info where 1 and email = '".$email."' and del_yn='N'";
		$result_pre4  = mysqli_query($gconnet,$sql_pre4);
		if(mysqli_num_rows($result_pre4) > 0) {
			error_frame("입력하신 이메일은 이미 사용중입니다. 다시 확인하시고 입력해 주세요.");
		}
	} // 이메일을 입력했을때 종료
	
	if($cell){ // 휴대전화 입력했을때 
		$sql_pre3 = "select idx from member_info where 1 and cell = '".$cell."' and del_yn='N'";
		//echo "sql_pre3 = ".$sql_pre3."<br>";
		$result_pre3  = mysqli_query($gconnet,$sql_pre3);
		if(mysqli_num_rows($result_pre3) > 0) {
			error_frame("입력하신 휴대전화는 이미 사용중입니다. 다시 확인하시고 입력해 주세요.");
		}
	} // 휴대전화 입력했을때 종료

	if($recom_name){ // 추천인 입력했을때 
		$sql_pre3 = "select idx from member_info where 1 and user_id = '".$recom_name."' and memout_yn != 'Y' and memout_yn != 'S' and del_yn='N'";
		$result_pre3  = mysqli_query($gconnet,$sql_pre3);
		if(mysqli_num_rows($result_pre3) == 0) {
			error_frame("입력하신 추천자와 일치하는 데이터가 없습니다. 다시 확인하시고 입력해 주세요.");
		} else {
			$row_pre3  = mysqli_fetch_array($result_pre3);
			$chuchun_idx = $row_pre3['idx'];
		}
	} else { // 추천인 아이디를 입력했을때 종료
		$chuchun_idx = 0;
	}
	
	$member_type = "GEN"; // 회원

	$login_ok = "Y"; 
	$ad_mem_sect = "Y"; // 관리자 입력여부. 
	$memout_yn = "N"; // 탈퇴신청시 Y , 디폴트는 N 
	$mail_ok = "Y"; // 이메일 수신 허용
	
	$member_level_basic_sql = "select level_code from member_level_set where 1 and level_sect='".$member_type."' and is_del='N' order by idx asc limit 0,1"; // 회원가입시 기본설정 등급코드 추출  
	$member_level_basic_query = mysqli_query($gconnet,$member_level_basic_sql);
	$member_level_basic_row = mysqli_fetch_array($member_level_basic_query);
	$user_level = $member_level_basic_row['level_code'];
	
	if($partner_id){
		$partner_sql = "select idx from member_info_company where 1 and is_del='N' and member_idx in (select idx from member_info where 1 and del_yn='N' and  memout_yn not in ('Y','S') and user_id='".$partner_id."')"; // 가맹점 아이디로 pk 추출  
		$partner_query = mysqli_query($gconnet,$partner_sql);
		$partner_row = mysqli_fetch_array($partner_query);
		$partner_idx = $partner_row['idx'];
	}
	
	$query = "insert into member_info set"; 
	$query .= " member_type = '".$member_type."', ";
	$query .= " member_gubun = '".$member_gubun."', ";
	$query .= " push_key = '".$push_key."', ";
	$query .= " user_id = '".$user_id."', ";
	$query .= " user_pwd = '".$user_pwd."', ";
	$query .= " user_name = '".$user_name."', ";
	$query .= " birthday = '".$birthday."', ";
	$query .= " birthday_tp = '".$birthday_tp."', ";
	$query .= " gender = '".$gender."', ";
	$query .= " email = '".$email."', ";
	$query .= " partner_idx = '".$partner_idx."', ";
	$query .= " mail_ok = '".$mail_ok."', ";
	$query .= " chuchun_id = '".$recom_name."', ";
	$query .= " tel = '".$tel."', ";
	$query .= " cell = '".$cell."', ";
	$query .= " user_nick = '".$user_nick."', ";
	$query .= " post = '".$post."', ";
	$query .= " addr1 = '".$addr1."', ";
	$query .= " addr2 = '".$addr2."', ";	
	$query .= " user_level = '".$user_level."', ";
	$query .= " login_ok = '".$login_ok."', ";
	$query .= " master_ok = '".$master_ok."', ";
	$query .= " ad_mem_sect = '".$ad_mem_sect."', ";
	$query .= " memout_yn = '".$memout_yn."', ";
	$query .= " memout_sect = '".$memout_sect."', ";
	$query .= " memout_memo = '".$memout_memo."', ";
	$query .= " wdate = now() ";
	//echo $query;
	$result = mysqli_query($gconnet,$query);

	$member_idx = mysqli_insert_id($gconnet);

	################ 추가정보 입력 시작 ##############
	
	$run_code = trim(sqlfilter($_REQUEST['run_code']));
	$mb_short_fee = trim(sqlfilter($_REQUEST['mb_short_fee']));
	$mb_long_fee = trim(sqlfilter($_REQUEST['mb_long_fee']));
	$mb_img_fee = trim(sqlfilter($_REQUEST['mb_img_fee']));
	$mb_short_cnt = trim(sqlfilter($_REQUEST['mb_short_cnt']));
	$mb_long_cnt = trim(sqlfilter($_REQUEST['mb_long_cnt']));
	$mb_img_cnt = trim(sqlfilter($_REQUEST['mb_img_cnt']));
	
	$call_num = json_encode($_REQUEST['call_num'], JSON_UNESCAPED_UNICODE);
	$call_memo = json_encode($_REQUEST['call_memo'], JSON_UNESCAPED_UNICODE);
	$use_yn = json_encode($_REQUEST['use_yn'], JSON_UNESCAPED_UNICODE);

	$query_add = "insert into member_info_sendinfo set"; 
	$query_add .= " member_idx = '".$member_idx."', ";
	$query_add .= " run_code = '".$run_code."', ";
	$query_add .= " auth_yn = '".$auth_yn."', ";
	$query_add .= " auth_gubun = '".$auth_gubun."', ";
	$query_add .= " mb_short_fee = '".$mb_short_fee."', ";
	$query_add .= " mb_long_fee = '".$mb_long_fee."', ";
	$query_add .= " mb_img_fee = '".$mb_img_fee."', ";
	$query_add .= " mb_short_cnt = '".$mb_short_cnt."', ";
	$query_add .= " mb_long_cnt = '".$mb_long_cnt."', ";
	$query_add .= " mb_img_cnt = '".$mb_img_cnt."', ";
	$query_add .= " call_num = '".$call_num."', ";
	$query_add .= " call_memo = '".$call_memo."', ";
	$query_add .= " use_yn = '".$use_yn."', ";
	$query_add .= " wdate = now() ";
	//echo $query_add;
	$result_add = mysqli_query($gconnet,$query_add);

	$sendinfo_idx = mysqli_insert_id($gconnet);
	################ 추가정보 입력 종료 ##############
	
	################# 첨부파일 업로드 시작 #######################
	$bbs = "certi";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE.$bbs."/";

	$board_tbname = "member_info_sendinfo";
	$board_code = "commu_certi";

	$sql_file = "select idx from board_file where 1 and board_tbname='".$board_tbname."' and board_code='".$board_code."' and board_idx='".$sendinfo_idx."'";
	$query_file = mysqli_query($gconnet,$sql_file);
	$cnt_file = mysqli_num_rows($query_file);

	if($cnt_file < 1){
		$cnt_file = 1;
	}

	for($file_i=0; $file_i<$cnt_file; $file_i++){ // 설정된 갯수만큼 루프 시작
		
		$file_idx = trim(sqlfilter($_REQUEST['file_idx_'.$file_i])); // 기존 첨부파일 DB PK 값.
		$file_old_name = trim(sqlfilter($_REQUEST['file_old_name_'.$file_i])); // 원본 서버파일 이름
		$file_old_org = trim(sqlfilter($_REQUEST['file_old_org_'.$file_i]));	// 원본 오리지널 파일 이름
		$del_org = trim(sqlfilter($_REQUEST['del_org_'.$file_i]));	// 원본 파일 삭제여부

		if ($_FILES['file_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
						
			if($file_old_name){
				unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
			}

			/*if($bbs_code == "event"){
				$file_o = $_FILES['file_'.$file_i]['name']; 
				$i_width = "280";
				$i_height = "184";
				$file_c = uploadFileThumb_1($_FILES, "file_".$file_i, $_FILES['file_'.$file_i], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$watermark_sect);
			} else {*/
				$file_o = $_FILES['file_'.$file_i]['name']; 
				$file_c = uploadFile($_FILES, "file_".$file_i, $_FILES['file_'.$file_i], $_P_DIR_FILE); // 파일 업로드후 변형된 파일이름 리턴.
			//}
			
		} else { // 파일이 있다면 업로드한다 종료 , 파일이 없을때 시작 
			
			if($file_old_name && $file_old_org){
				$file_c = $file_old_name;
				$file_o = $file_old_org;
			} else {
				$file_c = "";
				$file_o = "";
			}

			if($del_org == "Y"){
				if($file_old_name){
					unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
				}
				$file_c = "";
				$file_o = "";
			}

		} //  파일이 없을때 종료 

		if($file_idx){ // 기존에 첨부파일 DB 에 있던 값
			
			if ($file_o && $file_c){ // 파일이 있으면 업데이트, 없으면 삭제 
				$query_file = " update board_file set "; 
				$query_file .= " file_org = '".$file_o."', ";
				$query_file .= " file_chg = '".$file_c."' ";
				$query_file .= " where 1=1 and idx = '".$file_idx."' ";
			} else {
				$query_file = " delete from board_file "; 
				$query_file .= " where 1=1 and idx = '".$file_idx."' ";
			}
			$result_file = mysqli_query($gconnet,$query_file);

		} else { // 기존에 첨부파일 DB 에 없던 값 

			if ($_FILES['file_'.$file_i]['size']>0){ // 업로드 파일이 있으면 인서트 
			
				$query_file = " insert into board_file set "; 
				$query_file .= " board_tbname = '".$board_tbname."', ";
				$query_file .= " board_code = '".$board_code."', ";
				$query_file .= " board_idx = '".$sendinfo_idx."', ";
				$query_file .= " file_org = '".$file_o."', ";
				$query_file .= " file_chg = '".$file_c."' ";
						
				$result_file = mysqli_query($gconnet,$query_file);
			} 

		} // 기존에 첨부파일 DB 에 있었는지 없었는지 모두 종료 
	
	} // 설정된 갯수만큼 루프 종료
	################# 첨부파일 업로드 종료 #######################

	$point_sect = "refund"; // 포인트 

	########### 회원가입시 포인트  적립시작 #################
	$sql_member_in = "select member_in_gen from member_point_set where 1 and point_sect='".$point_sect."' and coin_type='member' order by idx desc limit 0,1"; // 포인트  설정 테이블에서 회원가입시의 설정값을 추출한다.
	$result_member_in = mysqli_query($gconnet,$sql_member_in);

	if(mysqli_num_rows($result_member_in)==0) {
		$chg_mile = 0; 
	} else {
		$row_member_in = mysqli_fetch_array($result_member_in); 
		$chg_mile = $row_member_in[member_in_gen];
	}

	$mile_title = "회원가입 포인트 적립"; // 포인트  적립 내역
	$mile_sect = "A"; // 포인트  종류 = A : 적립, P : 대기, M : 차감
	coin_plus_minus($point_sect,$member_idx,$mile_sect,$chg_mile,$mile_title,"","","");

	if($chuchun_idx){ // 추천인 아이디를 입력했을때 
		
		###########  추천받은사람 포인트  적립시작 #################
		$sql_member_chu = "select member_chuchun_recev from member_point_set where 1=1 and point_sect='".$point_sect."' and coin_type='member' order by idx desc limit 0,1 "; // 포인트  설정 테이블에서 추천받은 사람의 설정값을 추출한다.
		$result_member_chu = mysqli_query($gconnet,$sql_member_chu);
		if(mysqli_num_rows($result_member_chu)==0) {
			$chg_mile2 = 0; 
		} else {
			$row_member_chu = mysqli_fetch_array($result_member_chu); 
			$chg_mile2 = $row_member_chu[member_chuchun_recev];
		}

		$mile_title2 = $prev_row['user_id']." 님 추천으로 포인트  적립"; // 포인트  적립 내역
		$mile_sect2 = "A"; // 포인트  종류 = A : 적립, P : 대기, M : 차감
		coin_plus_minus($point_sect,$chuchun_idx,$mile_sect2,$chg_mile2,$mile_title2,"","","");
		###########  추천받은사람 포인트  적립종료 #################

		###########  추천한사람 포인트  적립시작 #################
		$sql_member_in2 = "select member_chuchun_send from member_point_set where 1=1 and point_sect='".$point_sect."' and coin_type='member' order by idx desc limit 0,1 "; // 포인트  설정 테이블에서 회원가입시의 설정값을 추출한다.
		$result_member_in2 = mysqli_query($gconnet,$sql_member_in2);

		if(mysqli_num_rows($result_member_in2)==0) {
			$chg_mile3 = 0; 
		} else {
			$row_member_in2 = mysqli_fetch_array($result_member_in2); 
			$chg_mile3 = $row_member_in2[member_chuchun_send]; // 회원가입 추천아이디 입력에 따른 포인트 
		}

		$mile_title3 = $prev_row['chuchun_id']." 님 추천하신 포인트  적립"; // 포인트  적립 내역
		$mile_sect3 = "A"; // 포인트  종류 = A : 적립, P : 대기, M : 차감
		coin_plus_minus($point_sect,$member_idx,$mile_sect3,$chg_mile3,$mile_title3,"","","");
		###########  추천한사람 포인트  적립종료 #################

	} // 추천인 아이디를 입력했을때 종료

	########### 회원가입시 포인트  적립종료 #################

	if($result){
	?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('등록이 정상적으로 완료 되었습니다.');
	//parent.location.href =  "member_list.php?<?=$total_param?>";
	parent.location.href =  "member_view.php?idx=<?=$member_idx?>&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>";
	//-->
	</SCRIPT>
	<?}else{?>
	<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('등록중 오류가 발생했습니다.');
	//-->
	</SCRIPT>
	<?}?>
