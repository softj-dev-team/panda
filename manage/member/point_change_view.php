<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));

$point_sect = sqlfilter($_REQUEST['point_sect']);
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_level = sqlfilter($_REQUEST['s_level']); 
$s_gubun = sqlfilter($_REQUEST['s_gubun']);  // 내용확인 여부
$site_sect = sqlfilter($_REQUEST['site_sect']); //  Contact Us / 광고문의 구분
$s_gender = sqlfilter($_REQUEST['s_gender']); // 답변완료 여부
$s_gender2 = sqlfilter($_REQUEST['s_gender2']); // 접수상태
$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&point_sect='.$point_sect.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&site_sect='.$site_sect.'&s_gender='.$s_gender.'&s_gender2='.$s_gender2.'&s_cnt='.$s_cnt.'&s_order='.$s_order;

$sql = "SELECT *,(select email from member_info where 1 and del_yn='N' and member_type in ('GEN','PAT') and idx=member_point_change.member_idx) as email,(select user_name from member_info where 1 and del_yn='N' and member_type in ('GEN','PAT') and idx=member_point_change.member_idx) as user_name FROM member_point_change where 1 and idx = '".$idx."' and del_yn='N'";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('해당하는 내용이 없습니다.');
	location.href =  "point_change_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

if($row['status'] == "1"){
	$view_ok = "<font style='color:black'><b>접수중</b></font>";
} elseif ($row['status']=="2"){
	$view_ok = "<font style='color:blue'><b>승인</b></font>";
} elseif ($row['status']=="3"){
	$view_ok = "<font style='color:red'><b>거절</b></font>";
} 
?>

<!-- content -->
<script type="text/javascript">
<!--
function go_view(no){
		location.href = "point_change_view.php?idx="+no+"&<?=$total_param?>";
}

function go_delete(no){
	if(confirm('정보를 삭제하시면 다시는 복구가 불가능 합니다. 정말 삭제 하시겠습니까?')){
		//if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "point_change_delete_action.php?idx="+no+"&<?=$total_param?>";
		//}
	}
}

function go_list(){
	location.href = "point_change_list.php?<?=$total_param?>";
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
						<li>상품권 전환 관리</li>
					</ul>
				</div>
			
				<div class="list_tit">
					<h3>상품권 전환 신청내역 상세보기</h3>
				</div>
				
				<div class="write">
					<table>
						<colgroup>
							<col width="15%" />
							<col width="35%" />
							<col width="15%" />
							<col width="35%" />
						</colgroup>
							<tr>
								<th >신청자 이메일</th>
								<td ><?=$row['email']?></td>
								<th >신청자 이름</th>
								<td ><?=$row['user_name']?></td>
							</tr>
							<tr>
								<th >신청금액</th>
								<td ><?=number_format($row['chg_mile'])?></td>
								<th >신청매수</th>
								<td ><?=number_format($row['chg_cnt'])?></td>
							</tr>
							<tr>
								<th >상품권 전송받을 <br>휴대폰번호</th>
								<td ><?=$row['chg_cell']?></td>
								<th >신청일시</th>
								<td ><?=$row['wdate']?></td>
							</tr>
							<tr>
								<th >신청상태</th>
								<td colspan="3"><?=$view_ok?></td>
							</tr>
						<?if ($row['status']=="2"){?>
							<tr>
								<th >승인일시</th>
								<td ><?=$row['mdate']?></td>
								<th >상품권 지급일자</th>
								<td ><?=substr($row['sdate'],0,10)?></td>
							</tr>
						<?}elseif ($row['status']=="3"){?>
							<tr>
								<th >거절일시</th>
								<td ><?=$row['mdate']?></td>
								<th >거절사유</th>
								<td ><?=$row['memo_reject']?></td>
							</tr>
						<?}?>

						<?if ($row['status']=="2" || $row['status']=="3"){?>
							<tr>
								<th >처리신청 관리자 아이디</th>
								<td ><?=$row['ad_sect_id']?></td>
								<th >처리신청 관리자 이름</th>
								<td ><?=$row['ad_sect_name']?></td>
							</tr>
						<?}?>
					</table>
					
				<?if ($row['status']=="1"){ // 접수중일때만 시작 ?>
					<p class="tit">신청처리</p>
					<table>
						<colgroup>
							<col width="10%" />
							<col width="40%" />
							<col width="10%" />
							<col width="40%" />
						</colgroup>
			
						<form name="frm" action="point_change_view_action.php" target="_fra_admin" method="post" >
							<input type="hidden" name="idx" value="<?=$idx?>"/>
							<input type="hidden" name="total_param" value="<?=$total_param?>"/>
							<input type="hidden" name="point_sect" value="<?=$point_sect?>"/>
							<tr>
								<th>신청상태 변경</th>
								<td colspan="3">
									<select name="status" id="status" size="1" required="yes"  message="신청상태" style="width:30%;vertical-align:middle;" onchange="show_area_status(this)">
										<option value="">선택하세요</option>
										<option value="1" <?=$row['status']=="1"?"selected":""?>>접수중</option>
										<option value="2" <?=$row['status']=="2"?"selected":""?>>승인</option>
										<option value="3" <?=$row['status']=="3"?"selected":""?>>거절</option>
									</select>
								</td>
							</tr>
							<tr id="area_status_1" style="display:<?=$row['status']=="2"?"":"none"?>">
								<th>상품권 지급일자</th>
								<td colspan="3">
									<input type="text" autocomplete="off" readonly name="sdate" id="sdate" style="width:10%;" class="datepicker" value="<?=substr($row['sdate'],0,10)?>">
								</td>
							</tr>
							<tr id="area_status_2" style="display:<?=$row['status']=="3"?"":"none"?>">
								<th>거절사유</th>
								<td colspan="3">
									<!--<textarea style="width:90%;height:50px;" name="admin_memo" required="yes"  message="관리자 답변" value=""><?=$row[admin_memo]?></textarea>-->
									<input type="text" name="memo_reject" id="memo_reject" style="width:50%;" value="<?=$row['memo_reject']?>">
								</td>
							</tr>
			
						</form>
					</table>

					<div style="padding-top:10px;padding-bottom:10px;text-align:right;padding-right:10px;"><a href="javascript:go_submit();" class="btn_green">답변하기/신청상태 변경</a></div>
				<?}  // 접수중일때만 종료?>

					<div class="write_btn align_r mt35">
						<!-- 목록 -->
						<a href="javascript:go_list();" class="btn_blue">목록</a>
						<!-- 삭제 -->
						<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제</a>	
					</div>
				</div>
			
			</div>
		</div>
	</div>
</div>
<!-- //content -->

<script>
	$(function() {
		$(".datepicker").datepicker({
			changeYear:true,
			changeMonth:true,
			minDate: '-90y',
			yearRange: 'c-90:c',
			dateFormat:'yy-mm-dd',
			showMonthAfterYear:true,
			constrainInput: true,
			dayNamesMin: ['일','월', '화', '수', '목', '금', '토' ],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
		});
	});

	function show_area_status(z){
		var tmp = z.options[z.selectedIndex].value; 
		if(tmp == "2"){
			$("#area_status_1").show();
			$("#area_status_2").hide();
		} else if(tmp == "3"){
			$("#area_status_1").hide();
			$("#area_status_2").show();
		}
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>