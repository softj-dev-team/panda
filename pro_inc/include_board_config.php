<?
	if($_REQUEST['bbs_code']){
		$bbs_code = trim(sqlfilter($_REQUEST['bbs_code']));
	} elseif($_REQUEST['board_code']){
		$bbs_code = trim(sqlfilter($_REQUEST['board_code']));
	} else {
		$bbs_code = $bbs_code;
	}

	if($bbs_code){ // 게시판 코드값이 넘어왔을때만 시작 

		$_include_config_board_sql = "select * from board_config where 1=1 and board_code = '".$bbs_code."' ";
		$_include_config_board_query = mysqli_query($gconnet,$_include_config_board_sql);
		$_include_config_board_cnt = mysqli_num_rows($_include_config_board_query);

		if($_include_config_board_cnt == 0){
			error_back("존재하지 않는 게시판입니다.");
			exit;
		}

		$_include_config_board_row = mysqli_fetch_array($_include_config_board_query);
		
		$_include_board_board_code = $_include_config_board_row['board_code']; // 게시판 코드
		$_include_board_board_title = $_include_config_board_row['board_title']; // 게시판 명
		$_include_board_file1_chg = $_include_config_file1_chg['file1_chg']; // 타이틀 이미지 명
		$_include_board_file2_chg = $_include_config_file2_chg['file2_chg']; // 서브타이틀 이미지 명
		$_include_board_cate1 = $_include_config_board_row['cate1']; // 게시판 카테고리 코드
		$_include_board_info = $_include_config_board_row['board_info']; // 게시판 설명
		$_include_board_principle = $_include_config_board_row['board_principle']; // 게시판 운영원칙
		$_include_board_master = $_include_config_board_row['board_master_idx']; // 게시판 관리자
		$_include_board_list_auth = $_include_config_board_row['list_auth']; // 글 목록보기 권한
		$_include_board_view_auth = $_include_config_board_row['view_auth']; // 글 내용보기 권한
		$_include_board_write_auth = $_include_config_board_row['write_auth']; // 본문글 작성 권한
		$_include_board_reply_auth = $_include_config_board_row['reply_auth']; // 덧글 작성 권한
		$_include_board_is_comment = $_include_config_board_row['is_comment']; // 한줄 댓글쓰기 가능여부
		$_include_board_is_notice = $_include_config_board_row['is_notice']; // 리스트 공지사항 가능여부
		$_include_board_entry_age = $_include_config_board_row['entry_age']; // 나이별 참여제한
		$_include_board_entry_gender = $_include_config_board_row['entry_gender']; // 성별 참여제한
		$_include_board_board_cate = $_include_config_board_row['board_cate']; // 게시판 형태
		$_include_board_file_cnt = $_include_config_board_row['file_cnt']; // 첨부파일 갯수 
		$_include_board_close_ok = $_include_config_board_row['close_ok']; // 폐쇄형 게시판인지 여부를 파악한다. 폐쇄형은 Y

		if(!$_include_board_file_cnt){
			$_include_board_file_cnt = 0;
		}

		/*echo $_include_board_is_notice."<br>";
		echo $_include_config_board_row['is_notice']."<br>";*/

		//echo $_include_board_write_auth;
		//echo $_include_board_close_ok."<br>";
		##################### 게시판 권한 부여파트 시작 ####################

		if($_SESSION['admin_coinc_idx']){ // 관리자로 로그인 했을때 시작 
			
			$_AUTH_LIST = TRUE; // 접근권한 부여 
			$_AUTH_VIEW = TRUE; // 보기권한 부여 
			$_AUTH_WRITE = TRUE; // 본문쓰기,수정,삭제권한 부여 
			$_AUTH_REPLY = TRUE; // 덧글쓰기,삭제권한 부여 
		
		} else { // 관리자로 로그인 했을때 종료, 관리자 아닐때 시작
				
		if($_include_board_close_ok == "Y"){ // 폐쇄형 게시판일때 시작 
			/*
			if(!$_SESSION['member_coinc_idx']){ // 폐쇄형 게시판에 회원으로 로그인 하지 않았을때
				//echo "관리자";
				$_AUTH_LIST = FALSE; // 접근권한 부여 
				$_AUTH_VIEW = FALSE; // 보기권한 부여 
				$_AUTH_WRITE = FALSE; // 본문쓰기,수정,삭제권한 부여 
				$_AUTH_REPLY = FALSE; // 덧글쓰기,삭제권한 부여 
			} else { // 폐쇄형 게시판에 회원으로 로그인 했을때 
				
				if($_SESSION['member_coinc_idx'] == $_include_board_master){ // 해당 게시판의 관리자이다 시작
				
					$_AUTH_LIST = TRUE; // 접근권한 부여 
					$_AUTH_VIEW = TRUE; // 보기권한 부여 
						
					if($_include_board_write_auth == "AD"){
						$_AUTH_WRITE = FALSE; // 본문쓰기,수정,삭제권한 미부여 
					} else {
						$_AUTH_WRITE = TRUE; // 본문쓰기,수정,삭제권한 부여 
					}
						
					if($_include_board_reply_auth == "AD"){
						$_AUTH_REPLY = FALSE; // 덧글쓰기,삭제권한 미부여 
					} else {
						$_AUTH_REPLY = TRUE; // 덧글쓰기,삭제권한 부여 
					}

				} else { // 해당 게시판의 관리자이다 종료, 게시판 관리자 아닐때 시작
					
					$_include_config_board_close_sql = "select idx from closeboard_accept_regist where 1=1 and board_code = '".$bbs_code."' and member_idx = '".$_SESSION['member_coinc_idx']."' and regist_stat = 'Y' ";
					//echo $_include_config_board_close_sql;
					$_include_config_board_close_query = mysqli_query($gconnet,$_include_config_board_close_sql);
					$_include_config_board_close_cnt = mysqli_num_rows($_include_config_board_close_query);

					if($_include_config_board_close_cnt > 0){ // 본 게시판에 사용승인을 받았을때
						$_AUTH_LIST = TRUE; // 접근권한 부여 
						$_AUTH_VIEW = TRUE; // 보기권한 부여 
						$_AUTH_WRITE = TRUE; // 본문쓰기,수정,삭제권한 부여 
						$_AUTH_REPLY = TRUE; // 덧글쓰기,삭제권한 부여 
					} else { // 본 게시판에 사용승인을 못 받았을때
						//echo "?";
						$_AUTH_LIST = TRUE; // 접근권한 부여 
						$_AUTH_VIEW = FALSE; // 보기권한 부여 
						$_AUTH_WRITE = FALSE; // 본문쓰기,수정,삭제권한 부여 
						$_AUTH_REPLY = FALSE; // 덧글쓰기,삭제권한 부여 
					} // 본 게시판에 사용승인 여부 종료
				
				} // 해당 게시판의 관리자인지 아닌지 모두 종료 

			} // 폐쇄형 게시판에 회원 로그인 여부 모두 종료
			*/
		} elseif($_include_board_board_code == "partboard"){ // 폐쇄형 게시판일때 종료, 제휴회원 소식일때 시작 

			if(!$_SESSION['member_coinc_idx']){ // 제휴회원 소식에 회원으로 로그인 하지 않았을때
				$_AUTH_LIST = FALSE; // 접근권한 부여 
				$_AUTH_VIEW = FALSE; // 보기권한 부여 
				$_AUTH_WRITE = FALSE; // 본문쓰기,수정,삭제권한 부여 
				$_AUTH_REPLY = FALSE; // 덧글쓰기,삭제권한 부여 
			} else { // 제휴회원 소식에 회원으로 로그인 했을때 
				
				if($_SESSION['member_coinc_idx'] == $_include_board_master){ // 해당 게시판의 관리자이다 시작
				
					$_AUTH_LIST = TRUE; // 접근권한 부여 
					$_AUTH_VIEW = TRUE; // 보기권한 부여 
						
					if($_include_board_write_auth == "AD"){
						$_AUTH_WRITE = FALSE; // 본문쓰기,수정,삭제권한 미부여 
					} else {
						$_AUTH_WRITE = TRUE; // 본문쓰기,수정,삭제권한 부여 
					}
						
					if($_include_board_reply_auth == "AD"){
						$_AUTH_REPLY = FALSE; // 덧글쓰기,삭제권한 미부여 
					} else {
						$_AUTH_REPLY = TRUE; // 덧글쓰기,삭제권한 부여 
					}

				} else { // 해당 게시판의 관리자이다 종료, 게시판 관리자 아닐때 시작
					
					if($_SESSION['member_coinc_sect'] == "PAT"){ // 제휴회원으로 로그인 되어 있다
						$_AUTH_LIST = TRUE; // 접근권한 부여 
						$_AUTH_VIEW = TRUE; // 보기권한 부여 
						$_AUTH_WRITE = TRUE; // 본문쓰기,수정,삭제권한 부여 
						$_AUTH_REPLY = TRUE; // 덧글쓰기,삭제권한 부여 
					} else { // 제휴회원으로 로그인 되어 있지 않다.
						$_AUTH_LIST = TRUE; // 접근권한 부여 
						$_AUTH_VIEW = FALSE; // 보기권한 부여 
						$_AUTH_WRITE = FALSE; // 본문쓰기,수정,삭제권한 부여 
						$_AUTH_REPLY = FALSE; // 덧글쓰기,삭제권한 부여 
					} // 제휴회원 로그인 여부 종료
				
				} // 해당 게시판의 관리자인지 아닌지 모두 종료 

			} // 제휴회원 소식에 회원 로그인 여부 모두 종료

		} else { // 폐쇄형 게시판이 아닐때 및 제휴회원 게시판이 아닐때 시작   

			if($_SESSION['member_coinc_idx']){ // 회원으로 로그인 했을때 시작
			
					if($_SESSION['member_coinc_idx'] == $_include_board_master){ // 해당 게시판의 관리자이다 시작
					
						$_AUTH_LIST = TRUE; // 접근권한 부여 
						$_AUTH_VIEW = TRUE; // 보기권한 부여 
						
						if($_include_board_write_auth == "AD"){
							$_AUTH_WRITE = FALSE; // 본문쓰기,수정,삭제권한 미부여 
						} else {
							$_AUTH_WRITE = TRUE; // 본문쓰기,수정,삭제권한 부여 
						}
						
						if($_include_board_reply_auth == "AD"){
							$_AUTH_REPLY = FALSE; // 덧글쓰기,삭제권한 미부여 
						} else {
							$_AUTH_REPLY = TRUE; // 덧글쓰기,삭제권한 부여 
						}

					} else { // 해당 게시판의 관리자이다 종료, 게시판 관리자 아닐때 시작
				
						if($_include_board_list_auth == "NM"){ // 목록보기 비회원 시작
							$_AUTH_LIST = TRUE; // 접근권한 부여 
						} else {  // 목록보기 비회원 종료, 목록보기 회원 시작 

							$_include_board_member_level_sql_1 = "select level_gijun from member_level_set where 1=1 and level_code = '".$_include_config_board_row['list_auth']."' ";   
							$_include_board_member_level_query_1 = mysqli_query($gconnet,$_include_board_member_level_sql_1);
							$_include_board_member_level_row_1 = mysqli_fetch_array($_include_board_member_level_query_1);
							$_include_board_list_level_gijun_1 = $_include_board_member_level_row_1['level_gijun'];

							$_include_board_member_level_sql_2 = "select level_gijun from member_level_set where 1=1 and level_code = '".$_SESSION['member_coinc_level']."' ";   
							$_include_board_member_level_query_2 = mysqli_query($gconnet,$_include_board_member_level_sql_2);
							$_include_board_member_level_row_2 = mysqli_fetch_array($_include_board_member_level_query_2);
							$_include_board_list_level_gijun_2 = $_include_board_member_level_row_2['level_gijun'];

							//echo $_include_board_member_level_sql_1." : ".$_include_board_member_level_sql_2; exit;
							
							if($_include_board_list_level_gijun_2 >= $_include_board_list_level_gijun_1){
								$_AUTH_LIST = TRUE; // 접근권한 부여 
								//echo $_include_board_list_level_gijun_1." : ".$_include_board_list_level_gijun_2; exit;
							} else {
								$_AUTH_LIST = FALSE; // 접근권한 미부여
							}

							if($_include_board_entry_age){ // 나이제한이 있을때
								//echo $_include_board_entry_age."<br>";
								if($_SESSION['member_coinc_age'] >= $_include_board_entry_age){
									$_AUTH_LIST = TRUE; // 접근권한 부여 
								} else {
									$_AUTH_LIST = FALSE; // 접근권한 미부여
								}	
							} // 나이제한이 있을때 종료

							if($_include_board_entry_gender){ // 성별제한이 있을때
								//echo $_include_board_entry_gender."<br>";
								if($_SESSION['member_coinc_gender'] == $_include_board_entry_gender){
									$_AUTH_LIST = TRUE; // 접근권한 부여 
								} else {
									$_AUTH_LIST = FALSE; // 접근권한 미부여
								}	
							} // 성별제한이 있을때 종료
								
								//echo $_AUTH_LIST; exit;

						}  // 목록보기 비회원 , 회원 여부 모두 종료 

						if($_include_board_view_auth == "NM"){ // 본문보기 비회원 시작
							$_AUTH_VIEW = TRUE; // 접근권한 부여 
						} else {  // 본문보기 비회원 종료, 본문보기 회원 시작 

							$_include_board_member_level_sql_1 = "select level_gijun from member_level_set where 1=1 and level_code = '".$_include_config_board_row['view_auth']."' ";   
							$_include_board_member_level_query_1 = mysqli_query($gconnet,$_include_board_member_level_sql_1);
							$_include_board_member_level_row_1 = mysqli_fetch_array($_include_board_member_level_query_1);
							$_include_board_list_level_gijun_1 = $_include_board_member_level_row_1['level_gijun'];

							$_include_board_member_level_sql_2 = "select level_gijun from member_level_set where 1=1 and level_code = '".$_SESSION['member_coinc_level']."' ";   
							$_include_board_member_level_query_2 = mysqli_query($gconnet,$_include_board_member_level_sql_2);
							$_include_board_member_level_row_2 = mysqli_fetch_array($_include_board_member_level_query_2);
							$_include_board_list_level_gijun_2 = $_include_board_member_level_row_2['level_gijun'];

							if($_include_board_list_level_gijun_2 >= $_include_board_list_level_gijun_1){
								$_AUTH_VIEW = TRUE; // 접근권한 부여 
							} else {
								$_AUTH_VIEW = FALSE; // 접근권한 미부여
							}

							if($_include_board_entry_age){ // 나이제한이 있을때
								if($_SESSION['member_coinc_age'] >= $_include_board_entry_age){
									$_AUTH_VIEW = TRUE; // 접근권한 부여 
								} else {
									$_AUTH_VIEW = FALSE; // 접근권한 미부여
								}	
							} // 나이제한이 있을때 종료

							if($_include_board_entry_gender){ // 성별제한이 있을때
								if($_SESSION['member_coinc_gender'] == $_include_board_entry_gender){
									$_AUTH_VIEW = TRUE; // 접근권한 부여 
								} else {
									$_AUTH_VIEW = FALSE; // 접근권한 미부여
								}	
							} // 성별제한이 있을때 종료

						}  // 본문보기 비회원 , 회원 여부 모두 종료
						
						if($_include_board_write_auth == "AD"){ // 본문쓰기 총관리자 시작
							$_AUTH_WRITE = FALSE; // 작성권한 미부여
						} elseif($_include_board_write_auth == "BA"){ // 본문쓰기 게시판 관리자 시작
							$_AUTH_WRITE = FALSE; // 작성권한 미부여
						} elseif($_include_board_write_auth == "NM"){ // 본문쓰기 비회원 시작
							$_AUTH_WRITE = TRUE; // 작성권한 부여 
						} else {  // 본문쓰기 비회원 종료, 본문쓰기 회원 시작 

							$_include_board_member_level_sql_1 = "select level_gijun from member_level_set where 1=1 and level_code = '".$_include_config_board_row['write_auth']."' ";   
							$_include_board_member_level_query_1 = mysqli_query($gconnet,$_include_board_member_level_sql_1);
							$_include_board_member_level_row_1 = mysqli_fetch_array($_include_board_member_level_query_1);
							$_include_board_list_level_gijun_1 = $_include_board_member_level_row_1['level_gijun'];

							$_include_board_member_level_sql_2 = "select level_gijun from member_level_set where 1=1 and level_code = '".$_SESSION['member_coinc_level']."' ";   
							$_include_board_member_level_query_2 = mysqli_query($gconnet,$_include_board_member_level_sql_2);
							$_include_board_member_level_row_2 = mysqli_fetch_array($_include_board_member_level_query_2);
							$_include_board_list_level_gijun_2 = $_include_board_member_level_row_2['level_gijun'];

							if($_include_board_list_level_gijun_2 >= $_include_board_list_level_gijun_1){
								$_AUTH_WRITE = TRUE; // 작성권한 부여 
							} else {
								$_AUTH_WRITE = FALSE; // 작성권한 미부여
							}

							if($_include_board_entry_age){ // 나이제한이 있을때
								if($_SESSION['member_coinc_age'] >= $_include_board_entry_age){
									$_AUTH_WRITE = TRUE; // 접근권한 부여 
								} else {
									$_AUTH_WRITE = FALSE; // 접근권한 미부여
								}	
							} // 나이제한이 있을때 종료

							if($_include_board_entry_gender){ // 성별제한이 있을때
								if($_SESSION['member_coinc_gender'] == $_include_board_entry_gender){
									$_AUTH_WRITE = TRUE; // 접근권한 부여 
								} else {
									$_AUTH_WRITE = FALSE; // 접근권한 미부여
								}	
							} // 성별제한이 있을때 종료

						}  // 본문쓰기 비회원 , 회원 여부 모두 종료

						if($_include_board_reply_auth == "AD"){ // 덧글쓰기 총관리자 시작
							$_AUTH_REPLY = FALSE; // 작성권한 미부여
						} elseif($_include_board_reply_auth == "BA"){ // 덧글쓰기 게시판 관리자 시작
							$_AUTH_REPLY = FALSE; // 작성권한 미부여
						} elseif($_include_board_reply_auth == "NM"){ // 덧글쓰기 비회원 시작
							$_AUTH_REPLY = TRUE; // 작성권한 부여 
						} else {  // 덧글쓰기 비회원 종료, 덧글쓰기 회원 시작 

							$_include_board_member_level_sql_1 = "select level_gijun from member_level_set where 1=1 and level_code = '".$_include_config_board_row['reply_auth']."' ";   
							$_include_board_member_level_query_1 = mysqli_query($gconnet,$_include_board_member_level_sql_1);
							$_include_board_member_level_row_1 = mysqli_fetch_array($_include_board_member_level_query_1);
							$_include_board_list_level_gijun_1 = $_include_board_member_level_row_1['level_gijun'];

							$_include_board_member_level_sql_2 = "select level_gijun from member_level_set where 1=1 and level_code = '".$_SESSION['member_coinc_level']."' ";   
							$_include_board_member_level_query_2 = mysqli_query($gconnet,$_include_board_member_level_sql_2);
							$_include_board_member_level_row_2 = mysqli_fetch_array($_include_board_member_level_query_2);
							$_include_board_list_level_gijun_2 = $_include_board_member_level_row_2['level_gijun'];

							if($_include_board_list_level_gijun_2 >= $_include_board_list_level_gijun_1){
								$_AUTH_REPLY = TRUE; // 작성권한 부여 
							} else {
								$_AUTH_REPLY = FALSE; // 작성권한 미부여
							}

							if($_include_board_entry_age){ // 나이제한이 있을때
								if($_SESSION['member_coinc_age'] >= $_include_board_entry_age){
									$_AUTH_REPLY = TRUE; // 접근권한 부여 
								} else {
									$_AUTH_REPLY = FALSE; // 접근권한 미부여
								}	
							} // 나이제한이 있을때 종료

							if($_include_board_entry_gender){ // 성별제한이 있을때
								if($_SESSION['member_coinc_gender'] == $_include_board_entry_gender){
									$_AUTH_REPLY = TRUE; // 접근권한 부여 
								} else {
									$_AUTH_REPLY = FALSE; // 접근권한 미부여
								}	
							} // 성별제한이 있을때 종료

						}  // 덧글쓰기 비회원 , 회원 여부 모두 종료

					} // 해당 게시판의 관리자 여부 모두 종료 

			} else {  // 회원으로 로그인 했을때 종료, 회원 로그인 아닐때 시작 
				
					if($_include_board_list_auth == "NM"){ // 목록보기 비회원 시작
						$_AUTH_LIST = TRUE; // 접근권한 부여 
					} else {
						$_AUTH_LIST = FALSE; // 접근권한 미부여
					}

					if($_include_board_view_auth == "NM"){ // 본문보기 비회원 시작
						$_AUTH_VIEW = TRUE; // 접근권한 부여 
					} else {
						$_AUTH_VIEW = FALSE; // 접근권한 미부여
					}

					if($_include_board_write_auth == "NM"){ // 본문쓰기 비회원 시작
						$_AUTH_WRITE = TRUE; // 작성권한 부여 
					} else {
						$_AUTH_WRITE = FALSE; // 작성권한 미부여
					}

					if($_include_board_reply_auth == "NM"){ // 덧글쓰기 비회원 시작
						$_AUTH_REPLY = TRUE; // 작성권한 부여 
					} else {
						$_AUTH_REPLY = FALSE; // 작성권한 미부여
					}

		     } // 회원으로 로그인 여부 모두 종료 
		
		  } // 폐쇄형 게시판이 아닐때 종료 

	} // 관리자 로그인여부 모두 종료
		
	##################### 게시판 권한 부여파트 종료 ####################
		
} // 게시판 코드값이 넘어왔을때만 종료
?>