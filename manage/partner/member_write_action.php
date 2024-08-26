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
	$cell = trim(sqlfilter($_REQUEST['cell']));
	$cell = str_replace("-","",$cell);
	$email = trim(sqlfilter($_REQUEST['member_email']));
			
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
	
	$member_type = "PAT"; // 가맹점
	$member_gubun = "1"; 
	$login_ok = "Y"; 
	$ad_mem_sect = "Y"; // 관리자 입력여부. 
	$memout_yn = "N"; // 탈퇴신청시 Y , 디폴트는 N 
	$mail_ok = "Y"; // 이메일 수신 허용
	$master_ok = "Y"; // 승인
	
	$member_level_basic_sql = "select level_code from member_level_set where 1 and level_sect='".$member_type."' and is_del='N' order by idx asc limit 0,1"; // 회원가입시 기본설정 등급코드 추출  
	$member_level_basic_query = mysqli_query($gconnet,$member_level_basic_sql);
	$member_level_basic_row = mysqli_fetch_array($member_level_basic_query);
	$user_level = $member_level_basic_row['level_code'];
		
	$query = "insert into member_info set"; 
	$query .= " member_type = '".$member_type."', ";
	$query .= " member_gubun = '".$member_gubun."', ";
	$query .= " push_key = '".$push_key."', ";
	$query .= " user_id = '".$user_id."', ";
	$query .= " user_pwd = '".$user_pwd."', ";
	$query .= " user_name = '".$user_name."', ";
	$query .= " email = '".$email."', ";
	$query .= " mail_ok = '".$mail_ok."', ";
	$query .= " cell = '".$cell."', ";
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

	########### 담당자 신분증 입력 시작 ############
	$bbs = "partner";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE.$bbs."/";

	$board_tbname = "member_info";
	$board_code = "identi";

	$sql_file = "select idx from board_file where 1 and board_tbname='".$board_tbname."' and board_code='".$board_code."' and board_idx='".$member_idx."'";
	$query_file = mysqli_query($gconnet,$sql_file);
	$cnt_file = mysqli_num_rows($query_file);

	if($cnt_file < 1){
		$cnt_file = 1;
	}

	for($file_i=0; $file_i<$cnt_file; $file_i++){ // 설정된 갯수만큼 루프 시작
		
		$file_idx = trim(sqlfilter($_REQUEST['file2_idx_'.$file_i])); // 기존 첨부파일 DB PK 값.
		$file_old_name = trim(sqlfilter($_REQUEST['file2_old_name_'.$file_i])); // 원본 서버파일 이름
		$file_old_org = trim(sqlfilter($_REQUEST['file2_old_org_'.$file_i]));	// 원본 오리지널 파일 이름
		$del_org = trim(sqlfilter($_REQUEST['del_org2_'.$file_i]));	// 원본 파일 삭제여부

		if ($_FILES['file2_'.$file_i]['size']>0){ // 파일이 있다면 업로드한다 시작
						
			if($file_old_name){
				unlink($_P_DIR_FILE.$file_old_name); // 원본파일 삭제
			}

			/*if($bbs_code == "event"){
				$file_o = $_FILES['file2_'.$file_i]['name']; 
				$i_width = "280";
				$i_height = "184";
				$file_c = uploadFileThumb_1($_FILES, "file2_".$file_i, $_FILES['file2_'.$file_i], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$watermark_sect);
			} else {*/
				$file_o = $_FILES['file2_'.$file_i]['name']; 
				$file_c = uploadFile($_FILES, "file2_".$file_i, $_FILES['file2_'.$file_i], $_P_DIR_FILE); // 파일 업로드후 변형된 파일이름 리턴.
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
				$query_file .= " where 1 and idx = '".$file_idx."' ";
			} else {
				$query_file = " delete from board_file "; 
				$query_file .= " where 1 and idx = '".$file_idx."' ";
			}
			$result_file = mysqli_query($gconnet,$query_file);

		} else { // 기존에 첨부파일 DB 에 없던 값 

			if ($_FILES['file2_'.$file_i]['size']>0){ // 업로드 파일이 있으면 인서트 
			
				$query_file = " insert into board_file set "; 
				$query_file .= " board_tbname = '".$board_tbname."', ";
				$query_file .= " board_code = '".$board_code."', ";
				$query_file .= " board_idx = '".$member_idx."', ";
				$query_file .= " file_org = '".$file_o."', ";
				$query_file .= " file_chg = '".$file_c."' ";
						
				$result_file = mysqli_query($gconnet,$query_file);
			} 

		} // 기존에 첨부파일 DB 에 있었는지 없었는지 모두 종료 
	
	} // 설정된 갯수만큼 루프 종료	
	########### 담당자 신분증 입력 종료 ############

	################ 추가정보 입력 시작 ##############
	
	$com_chair = trim(sqlfilter($_REQUEST['com_chair']));
	$com_name = trim(sqlfilter($_REQUEST['com_name']));
	$com_homep = trim(sqlfilter($_REQUEST['com_homep']));
	$com_num_1 = trim(sqlfilter($_REQUEST['com_num_1']));
	$com_num_2 = trim(sqlfilter($_REQUEST['com_num_2']));
	$com_uptae = trim(sqlfilter($_REQUEST['com_uptae']));
	$com_jong = trim(sqlfilter($_REQUEST['com_jong']));
	$com_tel_1 = trim(sqlfilter($_REQUEST['com_tel_1']));
	$com_fax = trim(sqlfilter($_REQUEST['com_fax']));
	$com_zip = trim(sqlfilter($_REQUEST['com_zip_code1']));
	$com_addr1 = trim(sqlfilter($_REQUEST['com_member_address']));
	$com_addr2 = trim(sqlfilter($_REQUEST['com_member_address2'])); 

	$query_add = "insert into member_info_company set"; 
	$query_add .= " member_idx = '".$member_idx."', ";
	$query_add .= " com_chair = '".$com_chair."', ";
	$query_add .= " com_name = '".$com_name."', ";
	$query_add .= " com_homep = '".$com_homep."', ";
	$query_add .= " com_num_1 = '".$com_num_1."', ";
	$query_add .= " com_num_2 = '".$com_num_2."', ";
	$query_add .= " com_uptae = '".$com_uptae."', ";
	$query_add .= " com_jong = '".$com_jong."', ";
	$query_add .= " com_tel_1 = '".$com_tel_1."', ";
	$query_add .= " com_fax = '".$com_fax."', ";
	$query_add .= " com_zip = '".$com_zip."', ";
	$query_add .= " com_addr1 = '".$com_addr1."', ";
	$query_add .= " com_addr2 = '".$com_addr2."', ";
	$query_add .= " status_date_1 = now(), ";
	$query_add .= " wdate = now() ";
	//echo $query_add;
	$result_add = mysqli_query($gconnet,$query_add);

	$company_idx = mysqli_insert_id($gconnet);
	################ 추가정보 입력 종료 ##############
	
	################# 사업자등록증 업로드 시작 #######################
	$board_tbname = "member_info_company";
	$board_code = "com_num_1";

	$sql_file = "select idx from board_file where 1 and board_tbname='".$board_tbname."' and board_code='".$board_code."' and board_idx='".$company_idx."'";
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
				$query_file .= " where 1 and idx = '".$file_idx."' ";
			} else {
				$query_file = " delete from board_file "; 
				$query_file .= " where 1 and idx = '".$file_idx."' ";
			}
			$result_file = mysqli_query($gconnet,$query_file);

		} else { // 기존에 첨부파일 DB 에 없던 값 

			if ($_FILES['file_'.$file_i]['size']>0){ // 업로드 파일이 있으면 인서트 
			
				$query_file = " insert into board_file set "; 
				$query_file .= " board_tbname = '".$board_tbname."', ";
				$query_file .= " board_code = '".$board_code."', ";
				$query_file .= " board_idx = '".$company_idx."', ";
				$query_file .= " file_org = '".$file_o."', ";
				$query_file .= " file_chg = '".$file_c."' ";
						
				$result_file = mysqli_query($gconnet,$query_file);
			} 

		} // 기존에 첨부파일 DB 에 있었는지 없었는지 모두 종료 
	
	} // 설정된 갯수만큼 루프 종료
	################# 사업자등록증 업로드 종료 #######################

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
