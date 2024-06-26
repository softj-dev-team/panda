<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
	$idx = trim(sqlfilter($_REQUEST['idx']));

	$sql = "SELECT * FROM member_info where 1 and idx = '".$idx."' and del_yn='N'";
	$query = mysqli_query($gconnet,$sql);

	$row = mysqli_fetch_array($query);

	$birthday_arr = explode("-",$row[birthday]);

	if($row[gender] == "M"){
		$gender = "남성";
	} elseif($row[gender] == "F"){
		$gender = "여성";
	} else {
		$gender = "";
	}

	if($row[ipin_code] == "cell"){
		$member_gubun = "인증회원";
	} else {
		if($row[member_gubun] == "NOR"){
			$member_gubun = "기본회원";
		} elseif($row[member_gubun] == "DET"){
			$member_gubun = "상세회원";
		} 
	}
?>
	<div class="list_tit" style="margin-top:10px;">
			<h3><?=$member_sect_str?> 회원정보 상세보기</h3>
		</div>
		<div class="write">
			<p class="tit">기본 정보</p>
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
					<td>
						<?=$row[user_id]?>
					</td>
					<th scope="row"> 성 명</th>
					<td>
						<?=$row[user_name]?>
					</td>
				</tr>
				<!--<tr>
					<th scope="row">이미지</th>
					<td colspan="3">
					<?if($row[file_chg]){?>
						<img src="<?=get_member_photo($row[idx])?>" style="max-width:90%;">
					<?}?>
					</td>
				</tr>
				<tr>
					<th scope="row">생년월일</th>
					<td>
						<?=$row[birthday]?>
					</td>
					<th scope="row">성 별</th>
					<td>
						<?=$gender?>
					</td>
				</tr>-->

				<tr>
					<!--<th scope="row"> 일반전화</th>
					<td>
						<?=$row[tel]?>
					</td>-->
					<th scope="row"> 닉네임</th>
					<td>
						<?=$row[user_nick]?>
					</td>
					<th scope="row"> 이메일</th>
					<td>
						<?=$row[email]?>
					</td>
				</tr>

				<!--<tr>
					<th scope="row">주소</th>
					<td colspan="3">
						[<?=$row[post]?>] <?=$row[addr1]?> &nbsp; <?=$row[addr2]?> 
					</td>
				</tr>-->

				<!--<tr>
					<th scope="row">은행</th>
					<td>
						<?//=get_bank_name_code($row[bank_name])?>
					</td>
					<th scope="row">계좌번호</th>
					<td>
						<?=$row[bank_num]?>
					</td>
				</tr>
				<tr>
					<th scope="row">명</th>
					<td colspan="3">
						<?=$row[user_nick]?>
					</td>
				</tr>-->

				<!--<tr>
					<th scope="row">생년월일</th>
					<td>
						<?=$row[birthday]?>
					</td>
				</tr>-->

				<!--<tr>
					<th scope="row">자기소개</th>
					<td colspan="3">
						<?=$row['bisut_m_channel']?>
					</td>
				</tr>
				<tr>
					<th scope="row">이력 및 경력</th>
					<td colspan="3"><?=nl2br($row['bisut_m_intro'])?></td>
				</tr>
				<tr>
					<th scope="row">운영하고자 하는 핏, 운영 목적 등</th>
					<td colspan="3"><?=nl2br($row['bisut_m_purpose'])?></td>
				</tr>
			<?
				for($i_file=0; $i_file<mysqli_num_rows($query_file_1); $i_file++){
					$row_file = mysqli_fetch_array($query_file_1);
			?>
				<tr>
					<th scope="row">관련 자격증빙</th>
					<td colspan="3"><a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=fitmaker"><?=$row_file['file_org']?></a></td>
				</tr>
			<?}?>
			<?if($row[member_gubun] == "SPE"){?>
				<tr>
					<th scope="row">VVIP 가입기간</th>
					<td>
						<?=$payment_sect_str?>
					</td>
					<th scope="row">VVIP 종료일</th>
					<td>
						<?=substr($row[payment_period],0,10)?>
					</td>
				</tr>
			<?}?>-->

				<!--<tr>
					<th scope="row"> 이용 멤버쉽</th>
					<td>
						<?if($row['pay_status'] == "com"){?>
							종료
						<?}elseif($row['pay_status'] == "no"){?>
							없음
						<?}else{?>
							<?=get_code_value("cate_name1","cate_code1",$row['payment_type'])?>
						<?}?>
					</td>
					<th scope="row"> 적용기간</th>
					<td>
						<?if($row['pay_status'] == "com" || $row['pay_status'] == "no"){?>
						<?}else{?>
							<?=$row['s_date']?> ~ <?=$row['e_date']?>
						<?}?>
					</td>
				</tr>-->

				<tr>
					<th scope="row">등록일시</th>
					<td colspan="3">
						<?=$row[wdate]?>
					</td>
				</tr>
			</table>

		<div class="write_btn align_r">
			<a href="javascript:go_list();" class="btn_gray">목록보기</a>
			<a href="javascript:go_modify('<?=$row[idx]?>');" class="btn_green">정보수정</a>
			<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제하기</a>	
		</div>
		
		<p class="tit">승인 및 관리자 메모</p>
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
				<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
				<!--<tr>
				<th scope="row">승인여부</th>
				<td colspan="3">
					<select name="master_ok" required="yes" message="승인여부" size="1" style="vertical-align:middle;" >
						<option value="">선택하세요</option>
						<option value="Y" <?=$row[master_ok]=="Y"?"selected":""?>>승인</option>
						<option value="N" <?=$row[master_ok]=="N"?"selected":""?>>미승인</option>
					</select>
				</td>
				</tr>-->							
				<tr>
				<th scope="row">로그인 여부</th>
				<td colspan="3">
					<select name="login_ok" required="yes" message="로그인 승인여부" size="1" style="vertical-align:middle;" >
						<option value="">선택하세요</option>
						<option value="Y" <?=$row[login_ok]=="Y"?"selected":""?>>로그인 가능</option>
						<option value="N" <?=$row[login_ok]=="N"?"selected":""?>>로그인 차단</option>
					</select>
				</td>
				</tr>										
				<!--<tr>
				<th >회원등급 변경</th>
				<td colspan="3">
					<select name="user_level" required="yes" message="회원등급" size="1" style="vertical-align:middle;" >
					<option value="">선택하세요</option>
					<?
						$sub_sql = "select idx,level_code,level_name from member_level_set where 1=1 and is_del = 'N' order by level_align asc";
						$sub_query = mysqli_query($gconnet,$sub_sql);
						$sub_cnt = mysqli_num_rows($sub_query);

						for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
							$sub_row = mysqli_fetch_array($sub_query);
					?>
						<option value="<?=$sub_row[level_code]?>" <?=$row[user_level]==$sub_row[level_code]?"selected":""?>><?=$sub_row[level_name]?></option>
					<?}?>
					</select>
				</td>
				</tr>-->
				<input type="hidden" name="user_level" value="<?=$row[user_level]?>"/>
				<tr>
				<th scope="row">관리자 메모</th>
				<td colspan="3">
					<textarea style="width:90%;height:50px;" name="admin_memo" required="no"  message="관리자 메모사항" value=""><?=$row[admin_memo]?></textarea>
				</td>
				</tr>
			</form>
		</table>
		<div style="margin-top:-20px;margin-bottom:20px;text-align:right;padding-right:10px;"><a href="javascript:go_set_submit();" class="btn_blue">설정변경</a></div>

		<!--<div class="list_tit">
			<h3>멤버십 결제 정보</h3>
		</div>
		 소주제 리스트 시작 
		<div class="search_wrap" id="membership_list_area">
			<!-- membership_list.php 에서 불러옴 
		</div>
		<!-- 소주제 리스트 종료 	

		<div class="list_tit">
			<h3>학습 정보</h3>
		</div>
		<!-- 소주제 리스트 시작 
		<div class="search_wrap" id="memberstudy_list_area">
			<!-- memberstudy_list.php 에서 불러옴 
		</div>
		<!-- 소주제 리스트 종료 -->	

	</div>