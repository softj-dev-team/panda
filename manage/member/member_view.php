<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); 
$s_gubun = sqlfilter($_REQUEST['s_gubun']); 
$s_level = sqlfilter($_REQUEST['s_level']); 
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 

$sql = "SELECT *,(select logdate from mem_login_count where 1 and member_idx=a.idx order by idx desc limit 0,1) as last_login,(select cur_mile from member_point where 1 and point_sect='smspay' and mile_sect != 'P' and member_idx=a.idx order by idx desc limit 0,1) as current_point,(select com_name from member_info_company where 1 and is_del='N' and idx=a.partner_idx order by idx desc limit 0,1) as com_name,(select mb_short_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_short_fee,(select mb_long_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_long_fee,(select mb_img_fee from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_img_fee,(select mb_short_cnt from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_short_cnt,(select mb_long_cnt from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_long_cnt,(select mb_img_cnt from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as mb_img_cnt,(select call_num from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as call_num,(select call_memo from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as call_memo,(select use_yn from member_info_sendinfo where 1 and is_del='N' and member_idx=a.idx order by idx desc limit 0,1) as use_yn FROM member_info a where 1 and idx = '".$idx."' and del_yn='N'";

//echo $sql; exit;
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('등록된 회원이 없습니다.');
	location.href =  "member_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_cnt='.$s_cnt.'&s_order='.$s_order.'&pageNo='.$pageNo;

$sql_bank = "select * from member_bank_info where 1 and is_del='N' and member_idx='".$row['idx']."' order by idx desc limit 0,1";
$query_bank = mysqli_query($gconnet,$sql_bank);
$row_bank = mysqli_fetch_array($query_bank);

if($row['master_ok'] == "Y"){
	//$master_ok = "<font style='color:blue;'>정상</font>";
	$master_ok = "<font style='color:blue;'>승인</font>";
}elseif($row['master_ok'] == "N"){
	//$master_ok = "<font style='color:red;'>패널티 / ".$arr_panalty_type[$row['panalty_type']]."</font>";
	$master_ok = "<font style='color:red;'>미승인</font>";
}

if($row['member_gubun'] == "1"){
	$member_gubun = "일반회원";
}elseif($row['member_gubun'] == "2"){
	$member_gubun = "광고회원";
}elseif($row['member_gubun'] == "3"){
	$member_gubun = "휴면회원";
}

if($row['gender'] == "M"){
	$gender = "남성";
} elseif($row['gender'] == "F"){
	$gender = "여성";
} else {
	$gender = "";
}
?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/member_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>회원관리</li>
						<li>회원정보 보기</li>
					</ul>
				</div>
				
				<!--<ul class="list_tab">
					<li class="list_tab_tab" id="member_view_tab_1">
						<a href="javascript:member_tab_product();">등록작품현황</a>
					</li>
					<li class="list_tab_tab" id="member_view_tab_2">
						<a href="javascript:member_tab_sale('general');">판매이력</a>
					</li>
					<li class="list_tab_tab" id="member_view_tab_3">
						<a href="javascript:member_tab_buy('general');">구매이력</a>
					</li>
					<li class="list_tab_tab" id="member_view_tab_4">
						<a href="javascript:member_tab_calc();">정산이력</a>
					</li>
					<li class="list_tab_tab" id="member_view_tab_5">
						<a href="javascript:member_tab_basic();">개인정보</a>
					</li>
				</ul>-->

				<div class="list_tit">
					<h3>회원정보</h3>
				</div>
				
				<div class="write">
					<p class="tit">기본정보</p>
					<table>
						<caption>회원 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th scope="row">아이디</th>
							<td><?=$row['user_id']?></td>
							<th scope="row">이름</th>
							<td><?=$row['user_name']?></td>
						</tr>
						<tr>
							<th scope="row">휴대전화</th>
							<td><?=$row['cell']?></td>
							<th scope="row">이메일</th>
							<td><?=$row['email']?></td>
						</tr>
						<tr>
							<th scope="row">주소</th>
							<td colspan="3">[<?=$row['post']?>] <?=$row['addr1']?> &nbsp; <?=$row['addr2']?></td>
						</tr>
						<tr>
							<th scope="row">회원구분</th>
							<td><?=$member_gubun?></td>
							<th scope="row">가맹점</th>
							<td><?=$row['com_name']?></td>
						</tr>
						<tr>
							<th scope="row">포인트</th>
							<td><?=number_format($row['current_point'])?></td>
							<th scope="row">승인여부</th>
							<td><?=$master_ok?></td>
						</tr>
						<tr>
							<th scope="row">등록일</th>
							<td><?=$row['wdate']?></td>
							<th scope="row">마지막 로그인</th>
							<td><?=$row['last_login']?></td>
						</tr>	
					</table>

					<p class="tit">추가정보</p>
					<table>
					<caption>게시글 등록</caption>
					<colgroup>
						<col style="width:15%;">
						<col style="width:35%;">
						<col style="width:15%;">
						<col style="width:35%;">
					</colgroup>
					<tr>
						<th scope="row"> 단가설정</th>
						<td>
							SMS : <?=$row['mb_short_fee']?> 원
							<br>LMS : <?=$row['mb_long_fee']?> 원
							<br>MMS : <?=$row['mb_img_fee']?> 원
						</td>
						<th scope="row">통신가입 증명원</th>
						<td>
						<?
							$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='member_info_sendinfo' and board_code='commu_certi' and board_idx='".$row['sendinfo_idx']."' order by idx asc";
							$query_file = mysqli_query($gconnet,$sql_file);
							$cnt_file = mysqli_num_rows($query_file);

							if($cnt_file < 1){
								$cnt_file = 1;
							}
									
							for($i_file=0; $i_file<$cnt_file; $i_file++){
								$row_file = mysqli_fetch_array($query_file);
								$k_file = $i_file+1;
						?>
								<div <?if($i_file > 0){?>style="margin-top:10px;"<?}?>>
									<a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=certi"><?=$row_file['file_org']?></a>
								</div>
						<?}?>
						</td>
					</tr>
					<!--<tr>
						<th scope="row"> 건수설정</th>
						<td>
							SMS : <input type="text" id="mb_short_cnt" name="mb_short_cnt" required="yes" message="sms 건수" is_num="yes" size="5" value="10">&nbsp;
							<br>LMS : <input type="text" id="mb_long_cnt" name="mb_long_cnt" required="yes" message="lms 건수" is_num="yes" size="5" value="20">&nbsp;
							<br>MMS : <input type="text" id="mb_img_cnt" name="mb_img_cnt" required="yes" message="mms 건수" is_num="yes" size="5" value="30">&nbsp;
						</td>
					</tr>-->
					<tr>
						<th scope="row"> 발신정보</th>
						<td colspan="3">
					<?
						$call_num_arr = json_decode($row['call_num'], true);
						$call_memo_arr = json_decode($row['call_memo'], true);
						$use_yn_arr = json_decode($row['use_yn'], true);

						$call_num_cnt = sizeof($call_num_arr);
						if($call_num_cnt < 1){
							$call_num_cnt = 1;
						}

						for($i_num=0; $i_num<$call_num_cnt; $i_num++){

							if($use_yn_arr[$i_num] == "Y"){
								$use_yn = "사용가능";
							} else {
								$use_yn = "사용불가";
							}
					?>
							<div <?if($i_num > 0){?>style="margin-top:10px;"<?}?>>
								<span class="marr5 mnw50 dib">발신번호 : </span> <?=$call_num_arr[$i_num]?>
								<span class="marr5 marl20">메모 : </span> <?=$call_memo_arr[$i_num]?>
								<span class="marr5 marl20">상태 : </span> <?=$use_yn?>
							</div>
					<?}?>										
						</td>
					</tr>
					</table>

					<p class="tit">회원상태 및 메모</p>
					<table>
					<colgroup>
						<col width="15%" />
						<col width="35%" />
						<col width="15%" />
						<col width="35%" />
					</colgroup>
			
						<form name="set_frm" id="set_frm" action="member_view_action.php" target="_fra_admin" method="post" >
							<input type="hidden" name="idx" value="<?=$idx?>"/>
							<input type="hidden" name="total_param" value="<?=$total_param?>"/>
							<tr>
							<th scope="row">회원상태</th>
							<td colspan="3">
								<select name="login_ok" required="yes" message="활성화 여부" size="1" style="vertical-align:middle;" >
									<option value="">선택하세요</option>
									<option value="Y" <?=$row[login_ok]=="Y"?"selected":""?>>활성화</option>
									<option value="N" <?=$row[login_ok]=="N"?"selected":""?>>비활성화</option>
								</select>
							</td>
							</tr>
							<input type="hidden" name="user_level" value="<?=$row[user_level]?>"/>
							<tr>
							<th scope="row">메모</th>
							<td colspan="3">
								<textarea style="width:90%;height:100px;" name="admin_memo" required="no"  message="PM메모" value=""><?=$row[admin_memo]?></textarea>
							</td>
							</tr>
						</form>

					</table>
					<div style="margin-top:-20px;margin-bottom:20px;text-align:right;padding-right:10px;"><a href="javascript:go_set_submit();" class="btn_blue">설정변경</a></div>

					<div style="text-align:right;padding-right:10px;margin-top:-25px;">
						<a href="javascript:go_list();" class="btn_blue">이전</a>
						<!--<a href="javascript:set_mark_open();" class="btn_green">비밀번호 초기화</a>-->
						<?if($row['memout_yn'] == "Y" || $row['memout_yn'] == "S"){?>
						<?}else{?>
							<a href="javascript:go_modify('<?=$row['idx']?>');" class="btn_green">정보수정</a>
							<a href="javascript:go_delete('<?=$row['idx']?>');" class="btn_red">삭제하기</a>	
						<?}?>
					</div>
					
					<!-- 모달팝업 배경레이어 시작 -->
						<div id="modal_auth_mark_back" style="width:100%;height:100%;position:absolute;left:0;top:0;display:none;background:rgba(255, 255, 255, 0.25);"></div>
					<!-- 모달팝업 배경레이어 종료 -->

					<!-- 비밀번호 초기화 팝업 시작 -->
						<div id="modal_auth_mark" style="display:none; position:fixed; top:40%; left:30%; width:80%; max-width:700px; background:#fff;z-index:2000; border-radius:5px; padding:25px 15px 15px 15px;">
							<div class="list_tit">
								<h3>비밀번호 초기화</h3>
							</div>
							<table>
							<caption>게시글 등록</caption>
							<colgroup>
								<col style="width:25%;">
								<col style="width:75%;">
							</colgroup>
							<tr>
								<td colspan="2" style="text-align:center;">
									기존 비밀번호를 초기화합니다.
								</td>
							</tr>
							<tr id="show_pwd_1">
								<td colspan="2" style="text-align:center;">
									<a href="javascript:set_pwd_reset();" class="btn_blue">초기화</a>
								</td>
							</tr>
							<tr id="show_pwd_2" style="display:none;">
								<th scope="row">초기화 비밀번호</th>
								<td id="show_pwd_3" style="text-align:center;"></td>
							</tr>
							</table>
							<div class="write_btn align_r mt35">
								<a href="javascript:set_mark_close();" class="btn_list">닫기</a>
							</div>
						</div>
					<!-- 비밀번호 초기화 팝업 종료 -->

					<!-- 패널티부여 팝업 시작 -->
						<div id="modal_auth_panalty" style="display:none; position:fixed; top:40%; left:30%; width:80%; max-width:700px; background:#fff;z-index:2000; border-radius:5px; padding:25px 15px 15px 15px;">
						<form name="frm_panalty" id="frm_panalty" action="member_panalty_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
							<input type="hidden" name="member_idx" id="member_idx" value="<?=$row['idx']?>"/>
							<input type="hidden" name="panalty_mode" id="panalty_mode" value="Y"/>
							<div class="list_tit">
								<h3>패널티 부여</h3>
							</div>
							<table>
							<caption>게시글 등록</caption>
							<colgroup>
								<col style="width:25%;">
								<col style="width:75%;">
							</colgroup>
							<tr>
								<td colspan="2" style="text-align:center;">
									페널티 부여시 서비스 로그인을 차단하게 됩니다.
									<br>로그인 차단 시 기존 판매중인 사항들 이용에 제약이 있을 수 있습니다.
								</td>
							</tr>
							<tr>
								<th scope="row">이용 제한하기</th>
								<td>
									<select name="panalty_type" id="panalty_type" style="width:50%;" required="yes" message="이용제한">
										<option value="">선택하세요</option>
									<? foreach ($arr_panalty_type as $key=>$val) {?>
										<option value="<?=$key?>"><?=$val?></option>
									<?}?>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">패널티 사유</th>
								<td><textarea style="width:90%;height:80px;" name="panalty_memo" required="yes" message="패널티 사유" value=""></textarea></td>
							</tr>
							<tr id="show_panalty_pwd_1" style="display:none;">
								<th scope="row" colspan="2">관리자 패스워드를 입력하세요</th>
							</tr>
							<tr id="show_panalty_pwd_2" style="display:none;">
								<th scope="row">패스워드 입력</th>
								<td><input type="password" name="lms_pass" id="lms_pass" required="yes" message="관리자 패스워드" style="width:80%;"></td>
							</tr>
							</table>
							</form>
							<div class="write_btn align_r mt35">
								<a href="javascript:set_panalty_pwd();" class="btn_green" id="btn_panalty_1">예, 페널티를 부여합니다</a>
								<a href="javascript:go_submit_panalty();" class="btn_blue" id="btn_panalty_2" style="display:none;">확인</a>
								<a href="javascript:set_panalty_close();" class="btn_gray" id="btn_panalty_3">아니오</a>
							</div>
						</div>
					<!-- 패널티부여 팝업 종료 -->

					<!-- 패널티부여 완료 팝업 시작 -->
						<div id="modal_auth_panalty_com" style="display:none; position:fixed; top:40%; left:30%; width:80%; max-width:700px; background:#fff;z-index:2000; border-radius:5px; padding:25px 15px 15px 15px;">
							<div class="list_tit">
								<h3>패널티 적용 완료</h3>
							</div>
							<table>
							<caption>게시글 등록</caption>
							<colgroup>
								<col style="width:25%;">
								<col style="width:75%;">
							</colgroup>
							<tr>
								<td colspan="2" style="text-align:center;">
									<?=$row['user_nick']?> (<?=$row['email']?>) 에게 
									<br><span id="panalty_com_period">총 15일</span>의 서비스 이용금지 페널티를 적용하였습니다.
								</td>
							</tr>
							</table>
							<div class="write_btn align_r mt35">
								<a href="javascript:set_panalty_complete_close();" class="btn_blue">확인</a>
							</div>
						</div>
					<!-- 패널티부여 팝업 종료 -->

					<!-- 패널티부여 히스토리 팝업 시작 -->
						<div id="modal_auth_panalty_history" style="display:none; position:fixed; top:40%; left:30%; width:80%; max-width:900px; background:#fff;z-index:2000; border-radius:5px; padding:25px 15px 15px 15px;">
							<div class="list_tit">
								<h3><?=$row['user_nick']?> (<?=$row['email']?>) 패널티 이력</h3>
							</div>
							<span id="area_panalty_history">
								<!-- 패널티부여 히스토리 ajax 로 불러옴 -->
							</span>
							<div class="write_btn align_r mt35">
								<a href="javascript:set_panalty_history_close();" class="btn_gray">닫기</a>
							</div>
						</div>
					<!-- 패널티부여 히스토리 종료 -->

					<!-- 패널티해제 팝업 시작 -->
						<div id="modal_auth_panalty_clear" style="display:none; position:fixed; top:40%; left:30%; width:80%; max-width:700px; background:#fff;z-index:2000; border-radius:5px; padding:25px 15px 15px 15px;">
						<form name="frm_panalty_clear" id="frm_panalty_clear" action="member_panalty_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
							<input type="hidden" name="member_idx" id="member_idx" value="<?=$row['idx']?>"/>
							<input type="hidden" name="panalty_mode" id="panalty_mode" value="N"/>
							<div class="list_tit">
								<h3>패널티 해제</h3>
							</div>
							<table>
							<caption>게시글 등록</caption>
							<colgroup>
								<col style="width:25%;">
								<col style="width:75%;">
							</colgroup>
							<tr>
								<td colspan="2" style="text-align:center;">
									<?=$row['user_nick']?> (<?=$row['email']?>) 에게 적용된  
									<br><?=$arr_panalty_type[$row['panalty_type']]?>의 서비스 이용금지 페널티를 해제하시겠습니까?
								</td>
							</tr>
							<tr id="show_panalty_pwd_3" style="display:none;">
								<th scope="row" colspan="2">관리자 패스워드를 입력하세요</th>
							</tr>
							<tr id="show_panalty_pwd_4" style="display:none;">
								<th scope="row">패스워드 입력</th>
								<td><input type="password" name="lms_pass" id="lms_pass" required="yes" message="관리자 패스워드" style="width:80%;"></td>
							</tr>
							</table>
							</form>
							<div class="write_btn align_r mt35">
								<a href="javascript:set_panalty_clear_pwd();" class="btn_green" id="btn_panalty_4">예, 페널티를 해제합니다</a>
								<a href="javascript:go_submit_panalty_clear();" class="btn_blue" id="btn_panalty_5" style="display:none;">확인</a>
								<a href="javascript:set_panalty_clear_close();" class="btn_gray" id="btn_panalty_6">아니오</a>
							</div>
						</div>
					<!-- 패널티해제 팝업 종료 -->

					<!-- 패널티해제 완료 팝업 시작 -->
						<div id="modal_auth_panalty_clear_com" style="display:none; position:fixed; top:40%; left:30%; width:80%; max-width:700px; background:#fff;z-index:2000; border-radius:5px; padding:25px 15px 15px 15px;">
							<div class="list_tit">
								<h3>패널티 해제 완료</h3>
							</div>
							<table>
							<caption>게시글 등록</caption>
							<colgroup>
								<col style="width:25%;">
								<col style="width:75%;">
							</colgroup>
							<tr>
								<td colspan="2" style="text-align:center;">
									<?=$row['user_nick']?> (<?=$row['email']?>) 의 페널티를 해제하였습니다.
								</td>
							</tr>
							</table>
							<div class="write_btn align_r mt35">
								<a href="javascript:set_panalty_clear_complete_close();" class="btn_blue">확인</a>
							</div>
						</div>
					<!-- 패널티해제 완료 팝업 종료 -->

					<!-- 계좌번호 팝업 시작 -->
						<div id="modal_member_bank" style="display:none; position:fixed; top:40%; left:30%; width:80%; max-width:700px; background:#fff;z-index:2000; border-radius:5px; padding:25px 15px 15px 15px;">
							<div class="list_tit">
								<h3>계좌번호</h3>
							</div>
							<table>
							<caption>게시글 등록</caption>
							<colgroup>
								<col style="width:25%;">
								<col style="width:75%;">
							</colgroup>
							<?if(mysqli_num_rows($query_bank) == 0){?>
								<tr>
									<td colspan="2" style="text-align:center;">
										계좌번호가 등록되지 않았습니다
									</td>
								</tr>
							<?}else{?>
								<tr>
									<td colspan="2" style="text-align:center;">
										<?=$row['user_nick']?> (<?=$row['email']?>) 님의 계좌번호
									</td>
								</tr>
								<tr>
									<th scope="row">은행</th>
									<td><?=$row_bank['bank_name']?></td>
								</tr>
								<tr>
									<th scope="row">계좌번호</th>
									<td><?=$row_bank['bank_num']?></td>
								</tr>
								<tr>
									<th scope="row">예금주</th>
									<td><?=$row_bank['bank_owner']?></td>
								</tr>
							<?}?>
							</table>
							<div class="write_btn align_r mt35">
								<a href="javascript:set_member_bank_close();" class="btn_blue">확인</a>
							</div>
						</div>
					<!-- 계좌번호 팝업 종료 -->

				</div>

				<span id="area_member_info">
					<!-- 탭 메뉴별 ajax 로 불러옴 -->
				</span>
				
			</div>
		</div>
		<!-- content 종료 -->
	</div>
</div>

<script type="text/javascript">
<!-- 
	
	<?if($row['memout_yn'] == "Y" || $row['memout_yn'] == "S"){?>
		function go_list(){
			location.href = "member_list_out.php?<?=$total_param?>";
		}
	<?}else{?>
		function go_list(){
			location.href = "member_list.php?<?=$total_param?>";
		}
	<?}?>

	function go_modify(no){
		location.href = "member_modify.php?idx="+no+"&<?=$total_param?>";
	}

	function go_delete(no){
		if(confirm('회원 정보를 삭제하시면 다시는 복구가 불가능 합니다. 정말 삭제 하시겠습니까?')){
			if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "member_delete_action.php?idx="+no+"&<?=$total_param?>";
			}
		}
	}

	function go_memout_com(no){
		if(confirm('정말 탈퇴처리 하시겠습니까?')){
			//if(confirm('탈퇴한 회원의 포인트 등 은 복구할수 없도록 영구 삭제 됩니다. 그래도 탈퇴처리 하시겠습니까?')){	
			if(confirm('탈퇴한 회원은 복구할수 없도록 영구 삭제 됩니다. 그래도 탈퇴처리 하시겠습니까?')){	
				_fra_admin.location.href = "member_out_action.php?idx="+no+"&mode=outcom&o_sect=one&<?=$total_param?>&re_url=member_out_done.php";
			}
		}
	}

	function go_memout_can(no){
		if(confirm('탈퇴신청을 취소처리 하시겠습니까?')){
			_fra_admin.location.href = "member_out_action.php?idx="+no+"&mode=outcan&o_sect=one&<?=$total_param?>&re_url=member_view.php";
		}
	}

	function go_set_submit() {
		var check = chkFrm('set_frm');
		if(check) {
			set_frm.submit();
		} else {
			false;
		}
	}

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}

	function upload_member(){
		$("#membership_photo").click();
	}

	function upload_photo() {
		var frm = document.forms["frm_photo"];
		frm.submit();
	}

	function upload_photo_callback(photo1,photo2) {

		if (photo2 != "" && photo2 != "false") {
			var frm_photo = document.forms["frm_photo"];
			frm_photo.elements["membership_photo_org"].value = photo2;

			//var frm = document.forms["frm"];
			//frm.elements["membership_photo"].value = photo;

			//$("#member_noimg").attr("src","/upload_file/member/img_thumb/" + encodeURIComponent(photo2)).addClass("circle_div");
			$("#member_noimg").attr("src","/upload_file/member/img_thumb/"+photo2);

			var frm_main = document.forms["frm_profile"];
			frm_main.elements["file_o"].value = photo1;
			frm_main.elements["file_c"].value = photo2;
		}
	}

	function unlink_photo(){
		var frm_photo = document.forms["frm_photo"];
		$.ajax({
			url : "action_unlink_photo.php",
			type : "post",
			dataType : "text",
			data : {"membership_photo_org" : frm_photo.elements["membership_photo_org"].value},
			async : true,
			timeout : 9000,
			success : function(data){
				$("#member_noimg").attr("src","<?=get_member_photo($idx,$row['member_type'])?>");
				var frm_main = document.forms["frm_profile"];
				frm_main.elements["file_o"].value = "";
				frm_main.elements["file_c"].value = "";
			}
		});
	}

//-->
</script>

<script>
	function set_mark_open(){
		$("#modal_auth_mark_back").show();
		$("#modal_auth_mark").show();
	}

	function set_mark_close(){
		$("#modal_auth_mark_back").hide();
		$("#modal_auth_mark").hide();
	}

	function set_pwd_reset(){
		if(confirm('비밀번호를 초기화 하시겠습니까?')){
			_fra_admin.location.href = "member_pwd_reset_action.php?member_idx=<?=$row['idx']?>";
		}
	}

	$(document).ready(function() {
		//member_tab_product();
	});

	function member_tab_product(){
		$(".list_tab_tab").removeClass("on");
		$("#member_view_tab_1").addClass("on");
		//get_data("inner_member_view_product.php","area_member_info","member_idx=<?=$row['idx']?>");
	}
	
	function sch_mem_view_product(){
		var formData = $("#inner_s_product").serialize();
			$.ajax({
			type : "POST",
			url : "inner_member_view_product.php",
			cache : false,
			data : formData,
			success : sch_mem_view_product_suc,
			error : sch_mem_view_product_err
		});
	}

	function sch_mem_view_product_suc(json, status){
		//$("#area_member_info").html($.trim(json));
	}

	function sch_mem_view_product_err(data, status){
		 //alert("error");
	}

	function member_tab_sale(sect){
		$(".list_tab_tab").removeClass("on");
		$("#member_view_tab_2").addClass("on");
		//get_data("inner_member_view_sale.php","area_member_info","member_idx=<?=$row['idx']?>&v_sect="+sect+"");
	}

	function sch_mem_view_sale(){
		var formData = $("#inner_s_sale").serialize();
			$.ajax({
			type : "POST",
			url : "inner_member_view_sale.php",
			cache : false,
			data : formData,
			success : sch_mem_view_sale_suc,
			error : sch_mem_view_sale_err
		});
	}

	function sch_mem_view_sale_suc(json, status){
		//$("#area_member_info").html($.trim(json));
	}

	function sch_mem_view_sale_err(data, status){
		 //alert("error");
	}

	function member_tab_buy(sect){
		$(".list_tab_tab").removeClass("on");
		$("#member_view_tab_3").addClass("on");
		//get_data("inner_member_view_buy.php","area_member_info","member_idx=<?=$row['idx']?>&v_sect="+sect+"");
	}

	function sch_mem_view_buy(){
		var formData = $("#inner_s_buy").serialize();
			$.ajax({
			type : "POST",
			url : "inner_member_view_buy.php",
			cache : false,
			data : formData,
			success : sch_mem_view_buy_suc,
			error : sch_mem_view_buy_err
		});
	}

	function sch_mem_view_buy_suc(json, status){
		//$("#area_member_info").html($.trim(json));
	}

	function sch_mem_view_buy_err(data, status){
		 //alert("error");
	}

	function member_tab_calc(){
		$(".list_tab_tab").removeClass("on");
		$("#member_view_tab_4").addClass("on");
		//get_data("inner_member_view_calc.php","area_member_info","member_idx=<?=$row['idx']?>");
	}
	
	function sch_mem_view_calc(){
		var formData = $("#inner_s_calc").serialize();
			$.ajax({
			type : "POST",
			url : "inner_member_view_calc.php",
			cache : false,
			data : formData,
			success : sch_mem_view_calc_suc,
			error : sch_mem_view_calc_err
		});
	}

	function sch_mem_view_calc_suc(json, status){
		//$("#area_member_info").html($.trim(json));
	}

	function sch_mem_view_calc_err(data, status){
		 //alert("error");
	}

	function member_tab_basic(){
		$(".list_tab_tab").removeClass("on");
		$("#member_view_tab_5").addClass("on");
		//get_data("inner_member_view_basic.php","area_member_info","member_idx=<?=$row['idx']?>");
	}

	function set_panalty_open(){
		$("#modal_auth_mark_back").show();
		$("#modal_auth_panalty").show();
	}

	function set_panalty_close(){
		$("#modal_auth_mark_back").hide();
		$("#modal_auth_panalty").hide();
	}

	function set_panalty_pwd(){
		$("#show_panalty_pwd_1").show();
		$("#show_panalty_pwd_2").show();
		$("#btn_panalty_1").hide();
		$("#btn_panalty_2").show();
	}

	function go_submit_panalty() {
		var check = chkFrm('frm_panalty');
		if(check) {
			frm_panalty.submit();
		} else {
			false;
		}
	}

	function set_panalty_complete_open(){
		$("#modal_auth_mark_back").show();
		$("#modal_auth_panalty_com").show();
	}

	function set_panalty_complete_close(){
		$("#modal_auth_mark_back").hide();
		$("#modal_auth_panalty_com").hide();
		//member_tab_basic();
	}

	function set_panalty_history(){
		$("#modal_auth_mark_back").show();
		//get_data("inner_member_view_panalty_history.php","area_panalty_history","member_idx=<?=$row['idx']?>");
		$("#modal_auth_panalty_history").show();
	}

	function set_panalty_history_close(){
		$("#modal_auth_mark_back").hide();
		$("#modal_auth_panalty_history").hide();
	}
	
	function set_panalty_clear_open(){
		$("#modal_auth_mark_back").show();
		$("#modal_auth_panalty_clear").show();
	}

	function set_panalty_clear_pwd(){
		$("#show_panalty_pwd_3").show();
		$("#show_panalty_pwd_4").show();
		$("#btn_panalty_4").hide();
		$("#btn_panalty_5").show();
	}

	function go_submit_panalty_clear() {
		var check = chkFrm('frm_panalty_clear');
		if(check) {
			frm_panalty_clear.submit();
		} else {
			false;
		}
	}

	function set_panalty_clear_close(){
		$("#modal_auth_mark_back").hide();
		$("#modal_auth_panalty_clear").hide();
	}

	function set_panalty_clear_complete(){
		$("#modal_auth_mark_back").show();
		$("#modal_auth_panalty_clear_com").show();
	}

	function set_panalty_clear_complete_close(){
		$("#modal_auth_mark_back").hide();
		$("#modal_auth_panalty_clear_com").hide();
		//member_tab_basic();
	}

	function set_member_bank(){
		$("#modal_auth_mark_back").show();
		$("#modal_member_bank").show();
	}
	
	function set_member_bank_close(){
		$("#modal_auth_mark_back").hide();
		$("#modal_member_bank").hide();
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>