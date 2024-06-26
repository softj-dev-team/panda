<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 관리자페이지 상단메뉴?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/patin_left.php"; // 사이트설정 좌측메뉴?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_level = sqlfilter($_REQUEST['s_level']); 
$s_gubun = sqlfilter($_REQUEST['s_gubun']);  // 내용확인 여부
$site_sect = sqlfilter($_REQUEST['site_sect']); //  Contact Us / 광고문의 구분
$s_gender = sqlfilter($_REQUEST['s_gender']); // 답변완료 여부
$s_gender2 = sqlfilter($_REQUEST['s_gender2']); // 접수상태
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&site_sect='.$site_sect.'&s_gender='.$s_gender.'&s_gender2='.$s_gender2;

$query_read = " update pat_member_ad set "; 
$query_read .= " read_ok = 'Y', ";
$query_read .= " readdate = now() ";
$query_read .= " where idx = '".$idx."' and read_ok = 'N' and member_type = 'PATIN'";
$result_read = mysqli_query($gconnet,$query_read);

$sql = "SELECT * FROM pat_member_ad where 1=1 and idx = '".$idx."' and member_type = 'PATIN'";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('해당하는 내용이 없습니다.');
	location.href =  "con_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

if($site_sect){
	$member_sect_str = $site_sect;
} else {
	$member_sect_str = "제휴문의 및 상담신청";
}

if($row[read_ok] == "Y"){
	$read_ok = "<font style='color:blue;'>내용확인</font>";
}elseif($row[read_ok] == "N"){
	$read_ok = "<font style='color:red;'>확인전</font>";
}

if($row[reply_ok] == "Y"){
	$reply_ok = "<font style='color:blue;'>답변완료</font>";
}elseif($row[reply_ok] == "N"){
	$reply_ok = "<font style='color:red;'>답변전</font>";
}

if($row[view_ok] == "P"){
	$view_ok = "<font style='color:black'><b>접수</b></font>";
} elseif ($row[view_ok]=="S"){
	$view_ok = "<font style='color:green'><b>심사중</b></font>";
} elseif ($row[view_ok]=="Y"){
	$view_ok = "<font style='color:blue'><b>승인 (전시중)</b></font>";
} elseif ($row[view_ok]=="N"){
	$view_ok = "<font style='color:red'><b>거부</b></font>";
}
?>

<!-- content -->
<script type="text/javascript">
<!--
function go_view(no){
		location.href = "con_view.php?idx="+no+"&<?=$total_param?>";
}

function go_delete(no){
	if(confirm('정보를 삭제하시면 다시는 복구가 불가능 합니다. 정말 삭제 하시겠습니까?')){
		if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "con_delete_action.php?idx="+no+"&<?=$total_param?>";
		}
	}
}

function go_list(){
	location.href = "con_list.php?<?=$total_param?>";
}

function go_submit() {
<?if($row[reply_ok] == "N"){?>
	if(confirm('답변내용을 저장하시면 질문하신 회원에게 답변내용이 메일로 발송됩니다.')){
		if(confirm('메일로 발송된 답변은 수정이나 삭제가 불가합니다. 정말 저장 하시겠습니까?')){
<?}?>
	var check = chkFrm('frm');
	if(check) {
		frm.submit();
	} else {
		false;
	}
<?if($row[reply_ok] == "N"){?>
	}
}
<?}?>
}
//-->		
</script>

<section id="content">
	<div class="inner">
		<h3><?=$member_sect_str?> 신청내용 상세보기</h3>
		<div class="cont">

			<table class="t_view">
				<colgroup>
					<col width="10%" />
					<col width="40%" />
					<col width="10%" />
					<col width="40%" />
				</colgroup>
					<tr>
						<th >업체명</th>
						<td colspan="3"><?=$row[com_name]?>&nbsp;<a href="member_view.php?idx=<?=$row[member_idx]?>" target="_blank" class="btn_blue2_big">신청자 정보보기</a></td>
					</tr>
					<tr>
						<th >위치</th>
						<td ><?=$row[ad_location]?></td>
						<th >집행기간</th>
						<td ><?=$row[ad_period]?> 개월</td>
					</tr>
					<tr>
						<th >광고문구</th>
						<td colspan="3"><?=$row[ad_yakinfo]?></td>
					</tr>
					<tr>
						<th >담당자 이름</th>
						<td ><?=$row[dam_name]?></td>
						<th >담당자 연락처</th>
						<td ><?=$row[dam_cell]?></td>
					</tr>
					<tr>
						<th >담당자 이메일</th>
						<td ><?=$row[dam_email]?></td>
						<th >홈페이지</th>
						<td ><?=$row[homepage]?></td>
					</tr>
					<tr>
						<th >남기실글</th>
						<td colspan="3"><?=nl2br($row[ad_memo])?></td>
					</tr>
					<tr>
						<th >로고 이미지</th>
						<td colspan="3">
						<?if($row[file_chg]){?>
							<img src="<?=$_P_DIR_WEB_FILE?>member_detail/img_thumb/<?=$row[file_chg]?>" style="border:0;">
						<?}?>
						</td>
					</tr>												
					<tr>
						<th >등록일시</th>
						<td colspan="3"><?=$row[wdate]?></td>
					</tr>
					<tr>
						<th >내용 확인여부</th>
						<td colspan="3"><?=$read_ok?></td>
					</tr>
					<tr>
						<th >내용 확인일시</th>
						<td colspan="3"><?=$row[readdate]?></td>
					</tr>
					<tr>
						<th >답변여부</th>
						<td colspan="3"><?=$reply_ok?></td>
					</tr>
					<tr>
						<th >답변일시</th>
						<td colspan="3"><?=$row[replydate]?></td>
					</tr>
					<tr>
						<th >신청상태</th>
						<td colspan="3"><?=$view_ok?></td>
					</tr>
				<?if($row[reply_ok] == "Y"){?>
					<tr>
						<th >답변한 관리자</th>
						<td ><?=$row[ad_sect_name]?></td>
						<th >관리자 아이디</th>
						<td ><?=$row[ad_sect_id]?></td>
					</tr>
				<?}?>
			</table>
					
				<h3 style="margin-top:10px;">관리자 답변</h3>
					<table class="t_view">
						<colgroup>
						<col width="10%" />
						<col width="40%" />
						<col width="10%" />
						<col width="40%" />
					</colgroup>
			
						<form name="frm" action="con_view_action.php" target="_fra_admin" method="post" >
							<input type="hidden" name="idx" value="<?=$idx?>"/>
							<input type="hidden" name="total_param" value="<?=$total_param?>"/>
							<input type="hidden" name="site_sect" value="<?=$site_sect?>"/>
							<input type="hidden" name="prev_reply_ok" value="<?=$row[reply_ok]?>"/>
							<tr>
							<th >신청상태 변경</th>
							<td colspan="3">
								<select name="view_ok" size="1" required="yes"  message="신청상태" style="vertical-align:middle;" >
									<option value="">선택하세요</option>
									<option value="P" <?=$row[view_ok]=="P"?"selected":""?>>접수</option>
									<option value="S" <?=$row[view_ok]=="S"?"selected":""?>>심사중</option>
									<option value="N" <?=$row[view_ok]=="N"?"selected":""?>>거부</option>
									<option value="Y" <?=$row[view_ok]=="Y"?"selected":""?>>승인 (전시중)</option>
								</select>
							</td>
							</tr>
							<tr>
							<th >관리자 답변</th>
							<td colspan="3">
								<textarea style="width:90%;height:50px;" name="admin_memo" required="yes"  message="관리자 답변" value=""><?=$row[admin_memo]?></textarea>
							</td>
							</tr>
			
						</form>
					</table>

					<div style="padding-top:10px;padding-bottom:10px;text-align:right;padding-right:10px;"><a href="javascript:go_submit();" class="btn_blue2_big">답변하기/신청상태 변경</a></div>
						
			<div class="align_c margin_t20">
				<!-- 목록 -->
				<a href="javascript:go_list();" class="btn_blue2">목록</a>
				<!-- 삭제 -->
				<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_blue2">삭제</a>	
			</div>

		</div>
	</div>
</section>
<!-- //content -->

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>