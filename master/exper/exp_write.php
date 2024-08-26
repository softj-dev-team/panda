<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<script type="text/javascript">
function go_submit() {
	var check = chkFrm('frm');
	if(check) {
		frm.submit();
	} else {
		false;
	}
}

function go_list(){
		location.href = "exp_list.php";
}

function cate_sel_1(z){
	var tmp = z.options[z.selectedIndex].value; 
	//alert(tmp);
	_fra_admin.location.href="cate_select_3.php?cate_code1="+tmp+"&fm=frm&fname=gugun";
}
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/exper_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>체험관리</li>
						<li>체험 등록</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>체험 등록</h3>
				</div>
				<div class="write">

				<form name="frm" action="exp_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="write_method" id="write_method" value="manual"/>
					<table>
						<caption>체험 수동등록</caption>
						<colgroup>
							<col style="width:20%">
							<col style="width:30%">
							<col style="width:20%">
							<col style="width:30%">
						</colgroup>
						<tr>
							<th scope="row">카테고리</th>
							<td colspan="3">
							<select name="cate_code1" size="1" style="vertical-align:middle;" required="yes" message="카테고리">
								<option value="">카테고리 선택</option>
							<?
							$sect3_sql = "select cate_code1,cate_name1 from viva_cate where 1 and set_code='exper' and cate_level = '1' and is_del='N' order by cate_align desc";
							$sect3_result = mysqli_query($gconnet,$sect3_sql);
							for ($i=0; $i<mysqli_num_rows($sect3_result); $i++){
								$row3 = mysqli_fetch_array($sect3_result);
							?>
								<option value="<?=$row3[cate_code1]?>" <?=$row3[cate_code1]==$s_sect1?"selected":""?>><?=$row3[cate_name1]?></option>
							<?}?>
							</select>
							</td>
						</tr>
						<tr>
							<th scope="row">파트너</th>
							<td colspan="3">
							<select name="member_idx" size="1" style="vertical-align:middle;" required="yes" message="파트너">
								<option value="">파트너 선택</option>
								<?
								$sub_sql = "select idx,com_name from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and member_type='PAT' order by com_name asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
								?>
									<option value="<?=$sub_row[idx]?>" <?=$v_sect==$sub_row[idx]?"selected":""?>><?=$sub_row[com_name]?></option>
								<?}?>		
							</select>
							</td>
						</tr>

						<tr>
							<th scope="row">텍스트 색상</th>
							<td colspan="3">
							<select name="text_color" size="1" style="vertical-align:middle;" required="yes" message="텍스트 색상">
								<option value="">색상선택</option>
								<option value="1">화이트</option>
								<option value="2">블랙</option>
								<option value="3">그레이</option>
								<option value="4">레드</option>
							</select> * 이미지 위에 오버랩되는 텍스트의 색상을 설정합니다.
							</td>
						</tr>

						<tr>
							<th scope="row">대표이미지</th>
							<td colspan="3">
								<input type="file" style="width:400px;" required="yes" message="대표이미지" name="file1"> ( 가로 * 세로 600 픽셀 이상의 정사각형 이미지 권장 )
							</td>
						</tr>
						<tr>
							<th scope="row">대표이미지 설명 1</th>
							<td colspan="3">
								 <textarea style="width:90%;height:50px;" name="file_txt" id="file_txt" required="no" message="대표이미지 설명" value=""><?=$row[file_txt]?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">대표이미지 설명 2</th>
							<td colspan="3">
								 <textarea style="width:90%;height:50px;" name="file_txt_a" id="file_txt_a" required="no" message="대표이미지 설명2" value=""><?=$row[file_txt_a]?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 1</th>
							<td colspan="3">
								<input type="file" style="width:400px;" required="no" message="추가 이미지1" name="file2"> 
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 1 설명 1</th>
							<td colspan="3">
								 <textarea style="width:90%;height:50px;" name="file_txt2" id="file_txt2" required="no" message="추가 이미지 1 설명" value=""><?=$row[file_txt2]?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 1 설명 2</th>
							<td colspan="3">
								 <textarea style="width:90%;height:50px;" name="file_txt2_a" id="file_txt2_a" required="no" message="추가 이미지 2 설명" value=""><?=$row[file_txt2_a]?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 2</th>
							<td colspan="3">
								<input type="file" style="width:400px;" required="no" message="추가 이미지2" name="file3"> 
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 2 설명 1</th>
							<td colspan="3">
								 <textarea style="width:90%;height:50px;" name="file_txt3" id="file_txt3" required="no" message="추가 이미지 3 설명" value=""><?=$row[file_txt3]?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 2 설명 2</th>
							<td colspan="3">
								 <textarea style="width:90%;height:50px;" name="file_txt3_a" id="file_txt3_a" required="no" message="추가 이미지 3 설명" value=""><?=$row[file_txt3_a]?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 3</th>
							<td colspan="3">
								<input type="file" style="width:400px;" required="no" message="추가 이미지3" name="file4"> 
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 3 설명 1</th>
							<td colspan="3">
								 <textarea style="width:90%;height:50px;" name="file_txt4" id="file_txt4" required="no" message="추가 이미지 3 설명" value=""><?=$row[file_txt4]?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 3 설명 2</th>
							<td colspan="3">
								 <textarea style="width:90%;height:50px;" name="file_txt4_a" id="file_txt4_a" required="no" message="추가 이미지 3 설명" value=""><?=$row[file_txt4_a]?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 4</th>
							<td colspan="3">
								<input type="file" style="width:400px;" required="no" message="추가 이미지4" name="file5"> 
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 4 설명 1</th>
							<td colspan="3">
								 <textarea style="width:90%;height:50px;" name="file_txt5" id="file_txt5" required="no" message="추가 이미지 4 설명" value=""><?=$row[file_txt5]?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 4 설명 2</th>
							<td colspan="3">
								 <textarea style="width:90%;height:50px;" name="file_txt5_a" id="file_txt5_a" required="no" message="추가 이미지 4 설명" value=""><?=$row[file_txt5_a]?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">체험제목</th>
							<td colspan="3">
								 <input type="text" style="width:50%;" name="exp_title" id="exp_title" required="yes" message="체험제목" >
							</td>
						</tr>
						<tr>
							<th scope="row">상품 상세보기 링크</th>
							<td colspan="3">
								<input type="text" style="width:50%;" name="exp_link" id="exp_link" required="no" message="상품 상세보기 링크" > <span style="color:red;">* http:// 포함하여 입력</span>
							</td>
						</tr>
						<tr>
							<th scope="row">참가자격 SNS 팔로워수</th>
							<td colspan="3">
								 참가자의 연동 SNS 팔로워수가 <input type="text" style="width:10%;" name="exp_limit_cnt" id="exp_limit_cnt" required="yes" message="체험제목" is_num="yes"> 명 미만일시 참여불가
							</td>
						</tr>
						<tr>
							<th scope="row">체험 시작일시</th>
							<td colspan="3">
								 <input type="text" name="s_date" style="width:15%;" id="s_date" onClick="new CalendarFrame.Calendar(this)" value="<?=$s_gubun?>" required="no" message="체험 시작일" readonly> 일 
								 <select name="start_hour" style="width:10%;" required="yes" message="시작시간">
								<option value="">시간</option>
								<?
									$st = 0;
									$ed = 24;
									for($i=$st; $i<$ed; $i++){
										$aph_s = fnzero($i);
								?>
									<option value="<?=$aph_s?>"><?=$aph_s?></option>
								<?}?>
								</select> 시 
								<select name="start_minute" style="width:10%;" required="yes" message="시작분">
								<option value="">분</option>
								<?
									$st = 0;
									$ed = 60;
									for($i=$st; $i<$ed; $i++){
										$k = $i+1;
										$aph_s = fnzero($i);
								?>
									<option value="<?=$aph_s?>"><?=$aph_s?></option>
								<?}?>
								</select> 분
							</td>
						</tr>
						<tr>
							<th scope="row">체험 종료일시</th>
							<td colspan="3">
								<input type="text" name="e_date" style="width:15%;" id="e_date" onClick="new CalendarFrame.Calendar(this)" value="<?=$s_level?>" required="no" message="체험 종료일" readonly> 일
								<select name="end_hour" style="width:10%;" required="yes" message="종료시간">
								<option value="">시간</option>
								<?
									$st = 0;
									$ed = 24;
									for($i=$st; $i<$ed; $i++){
										$aph_s = fnzero($i);
								?>
									<option value="<?=$aph_s?>"><?=$aph_s?></option>
								<?}?>
								</select> 시 
								<select name="end_minute" style="width:10%;" required="yes" message="종료분">
								<option value="">분</option>
								<?
									$st = 0;
									$ed = 60;
									for($i=$st; $i<$ed; $i++){
										$k = $i+1;
										$aph_s = fnzero($i);
								?>
									<option value="<?=$aph_s?>"><?=$aph_s?></option>
								<?}?>
								</select> 분
							</td>
						</tr>
						<tr>
							<th scope="row">체험수량</th>
							<td colspan="3">
								 <input type="text" style="width:10%;" name="set_click_cnt" id="set_click_cnt" required="yes" message="체험수량" is_num="yes"> 명 까지 체험 신청 가능
							</td>
						</tr>

						<tr>
							<th scope="row">시간설정 노출</th>
							<td colspan="3">
								<input type="radio" name="time_yn" value="Y" required="yes"  message="시간설정 노출여부" <?=$row[time_yn]=="Y"?"checked":""?>> 설정된 시간에만 노출 &nbsp; <input type="radio" name="time_yn" value="N" required="yes"  message="시간설정 노출여부" <?=$row[time_yn]=="N"?"checked":""?>> 시간 관계없이 노출 
							</td>
						</tr>
						<tr>
							<th scope="row">체험 노출시간</th>
							<td colspan="3">
								<select name="vs_hour" required="no" message="체험노출 시작 -시">
								<option value="">시간</option>
								<?
								$st=0;
								$ed=24;
								for($i=$st; $i<$ed; $i++){
								?>
									<option value="<?=fnzero($i)?>" <?=$s_time1==fnzero($i)?"selected":""?>><?=$i?> 시</option>
								<?}?>
								</select>
								<select name="vs_min" required="no" message="체험노출 시작 -분">
								<option value="">분</option>
								<?
								$st=0;
								$ed=60;
								for($i=$st; $i<$ed; $i++){
								?>
									<option value="<?=fnzero($i)?>" <?=$s_time1==fnzero($i)?"selected":""?>><?=$i?> 분</option>
								<?}?>
								</select> ~ 
								<select name="ve_hour" required="no" message="체험노출 종료 -시">
								<option value="">시간</option>
								<?
								$st=0;
								$ed=24;
								for($i=$st; $i<$ed; $i++){
								?>
									<option value="<?=fnzero($i)?>" <?=$e_time1==fnzero($i)?"selected":""?>><?=$i?> 시</option>
								<?}?>
								</select>
								<select name="ve_min" required="no" message="체험노출 종료 -분">
								<option value="">분</option>
								<?
								$st=0;
								$ed=60;
								for($i=$st; $i<$ed; $i++){
								?>
									<option value="<?=fnzero($i)?>" <?=$e_time1==fnzero($i)?"selected":""?>><?=$i?> 분</option>
								<?}?>
								</select>
							</td>
						</tr>

						<tr>
							<th scope="row">상세설명</th>
							<td colspan="3">
								 <textarea style="width:90%;height:100px;" name="exp_content" id="exp_content" required="yes" message="상세설명" value=""><?=$row[exp_content]?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">체험신청 금액</th>
							<td>
								 체험 신청 시 <input type="text" style="width:30%;" name="exp_money" id="exp_money" required="yes" message="체험신청 금액" is_num="yes"> 원 결제 <br>(무료 체험 등 결제할 금액이 없는경우 0 입력)
							</td>
							<th scope="row">소멸될 코인</th>
							<td>
								 체험 신청 시 <input type="text" style="width:30%;" name="exp_coin" id="exp_coin" required="yes" message="소멸될 코인" is_num="yes"> 코인 차감 <br>(무료 체험 등 소멸될 코인이 없는경우 0 입력)
							</td>
						</tr>
						
					<tr>
						<th scope="row">배송비</th>
						<td colspan="3">
							<input type="text" style="width:10%;" name="exp_d_money" id="exp_d_money" value="<?=$row['exp_d_money']?>" required="yes" message="배송비" is_num="yes"> 원
						</td>
					</tr>

						<tr>
							<th scope="row">쇼핑하기 링크</th>
							<td colspan="3">
								<input type="text" style="width:50%;" name="exp_shop_link" id="exp_shop_link" required="no" message="체험보기 링크" > <br><span style="color:red;">* http:// 포함하여 입력. 체험등록 파트너의 상품이 어플내에 등록되어 있지 않을때 이동할 주소.</span>
							</td>
						</tr>
					</table>
					</form>
					<div class="write_btn align_r">
						<a href="javascript:frm.reset();" class="btn_list">취소</a>
						<button class="btn_modify" type="button" onclick="go_submit();">등록</button>
					</div>
				</div>
		<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>