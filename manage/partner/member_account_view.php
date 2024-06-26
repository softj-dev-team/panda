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

if($s_gubun == "shop"){
	$member_sect_str = "지점";
} elseif($s_gubun == "temple"){
	$member_sect_str = "사찰";
}
$where = " and is_del = 'N' ";

$sql2 =  "select *,(select user_name from member_info where idx = member_account.member_idx and del_yn = 'N' and member_account.is_del = 'N') as member_name, (select temple_title from temple_info where idx = member_account.temple_idx and  member_account.is_del = 'N') as temple_name from member_account where 1 ".$where. " order by idx desc limit 0, 1";

//echo $sql; exit;
$query2 = mysqli_query($gconnet,$sql2);

$sql =  "select *,(select user_name from member_info where idx = member_account_history.member_idx and del_yn = 'N' and member_account_history.is_del = 'N') as member_name, (select temple_title from temple_info where idx = member_account_history.temple_idx and  member_account_history.is_del = 'N') as temple_name from member_account_history where 1 ".$where. " order by idx desc limit 0, 1";

//echo $sql; exit;
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query2) == 0){
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
$row2 = mysqli_fetch_array($query2);
$row = mysqli_fetch_array($query);
$history_idx = $row[idx];

$member_idx = $row[member_idx];
$temple_idx = $row[temple_idx];
$bank = $row[bank];
$account = $row[account];
$name = $row[name];
$member_sect = $row[member_sect];


if($row['memout_yn'] == "S"){
	$smenu = 3;
} elseif($row['memout_yn'] == "Y"){
	$smenu = 5;
} 

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_cnt='.$s_cnt.'&s_order='.$s_order.'&pageNo='.$pageNo;

$total_param_mem = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_cnt='.$s_cnt.'&s_order='.$s_order.'&pageNo='.$pageNo.'&idx='.$idx;

$member_level_sql = "select level_name from member_level_set where 1=1 and level_code = '".$row[user_level]."' ";   
$member_level_query = mysqli_query($gconnet,$member_level_sql);
$member_level_row = mysqli_fetch_array($member_level_query);
$user_level_str = $member_level_row['level_name'];

$birthday_arr = explode("-",$row[birthday]);

if($row[gender] == "M"){
									$gender = "남성";
								} elseif($row[gender] == "F"){
									$gender = "여성";
								} else {
									$gender = "";
								}

$sql_file_1 = "select * from board_file where 1 and board_tbname = 'member_bisut_info' and board_code = 'fitmaker' and board_idx='".$row['bisut_idx']."' order by idx asc";
$query_file_1 = mysqli_query($gconnet,$sql_file_1);
?>

<script type="text/javascript">
function go_view(no){
		location.href = "member_view.php?idx="+no+"&<?=$total_param?>";
}

<?if($row['member_gubun'] == "shop"){?>
	function go_temple_request(){
		member_temple_frm.action = "member_temple_request.php";
		member_temple_frm.submit();
	}
<?}?>

function go_modify(no){
		location.href = "member_modify.php?idx="+no+"&<?=$total_param?>";
}

function go_(no){
		location.href = "member_modify.php?idx="+no+"&<?=$total_param?>";
}

function go_delete(no){
	if(confirm('회원 정보를 삭제하시면 다시는 복구가 불가능 합니다. 정말 삭제 하시겠습니까?')){
		if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "member_delete_action.php?idx="+no+"&<?=$total_param?>";
		}
	}
}

<?if($row['memout_yn'] == "Y"){?>
	function go_list(){
		location.href = "member_account.php?<?=$total_param?>";
	}
<?}else{?>
	function go_list(){
		location.href = "member_account.php?<?=$total_param?>";
	}
<?}?>

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

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}

	function go_submit_bad() {
		if(confirm('정말 불량회원명으로 등록 하시겠습니까?')){
			var check = chkFrm('frm_bad');
			if(check) {
				frm_bad.submit();
			} else {
				false;
			}
		}
	}

	function go_submit_bad2() {
		if(confirm('차단회원으로 설정하시면 해당 회원은 모든 자격이 박탈됩니다. 정말 차단회원 으로 설정 하시겠습니까?')){
			var check = chkFrm('frm_bad');
			if(check) {
				frm_bad.submit();
			} else {
				false;
			}
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
			$("#member_noimg").attr("src","<?=get_member_photo($idx)?>");
			var frm_main = document.forms["frm_profile"];
			frm_main.elements["file_o"].value = "";
			frm_main.elements["file_c"].value = "";
		}
	});
}

function go_submit_profile(){
	var check = chkFrm('frm_profile');
	if(check) {
		frm_profile.submit();
	} else {
		false;
	}
}
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/partner_left.php"; // 좌측메뉴?>

		<form name="member_temple_frm" id="member_temple_frm" target="_self" method="post">
			<input type="hidden" name="total_param_mem" id="total_param_mem" value="<?=$total_param_mem?>"/>
		</form>

		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li><?=$member_sect_str?>회원</li>
					</ul>
				</div>

				<div class="list_tit">
					<h3><?=$member_sect_str?>회원정보 상세보기</h3>
				</div>

				<div class="write">
					<!--<ul class="list_tab" style="margin-bottom:10px;height:50px;">
						<li><a href="">사찰관리 신청</a></li>
						<li><a href="#">관리중인 사찰목록</a></li>
					</ul>-->

					<!--<p class="tit">기본 정보</p>-->
					<table>
						<caption>회원 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
					<?if($s_gubun == "shop"){?>
						<tr>
							<th scope="row">아이디</th>
							<td>
								<?=$row[member_name]?>
							</td>
							<th scope="row"><!--<span class="ast">&#42;</span>--> 성 명</th>
							<td>
								<?=$row[temple_name]?>
							</td>
						</tr>
						<tr>
							<th scope="row"><!--<span class="ast">&#42;</span>--> 소속</th>
							<td>
								<?=$row[com_name]?>
							</td>
							<th scope="row"><!--<span class="ast">&#42;</span>--> 사원번호</th>
							<td>
								<?=$row[com_num]?>
							</td>
						</tr>
					<?} elseif($s_gubun == "temple"){?>
						<tr>
							<th scope="row">사찰명</th>
							<td colspan="3">
								<?=$row[temple_name]?>
							</td>
						</tr>
						<tr>
							<th scope="row"><!--<span class="ast">&#42;</span>--> 신청인</th>
							<td>
								<?=$row[member_name]?>
							</td>
							<th scope="row"><!--<span class="ast">&#42;</span>--> 은행</th>
							<td>
								<?=get_bank_name_code($row[bank])?>
							</td>
						</tr>
						<tr>
							<th scope="row"><!--<span class="ast">&#42;</span>--> 계좌</th>
							<td>
								<?=$row[account]?>
							</td>
							<th scope="row"><!--<span class="ast">&#42;</span>--> 예금주</th>
							<td>
								<?=$row[name]?>
							</td>
						</tr>
					<?}?>

						<tr>
							<th scope="row">등록일시</th>
							<td colspan="3">
								<?=$row2[wdate]?>
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
			
						<form name="frm" action="member_account_action.php" target="_fra_admin" method="post" >
							<input type="hidden" name="idx" value="<?=$idx?>"/>
							<input type="hidden" name="history_idx" value="<?=$history_idx?>"/>
							<input type="hidden" name="member_idx" value="<?=$member_idx?>"/>
							<input type="hidden" name="temple_idx" value="<?=$temple_idx?>"/>
							<input type="hidden" name="bank" value="<?=$bank?>"/>
							<input type="hidden" name="account" value="<?=$account?>"/>
							<input type="hidden" name="name" value="<?=$name?>"/>
							<input type="hidden" name="member_sect" value="<?=$member_sect?>"/>
							<input type="hidden" name="total_param" value="<?=$total_param?>"/>
							<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
							<tr>
							<th scope="row">승인여부</th>
							<td colspan="3">
								<select name="master_ok" required="yes" message="승인여부" size="1" style="vertical-align:middle;" >
									<option value="">선택하세요</option>
									<option value="Y" <?=$row[is_ok]=="Y"?"selected":""?>>승인</option>
									<option value="N" <?=$row[is_ok]=="N"?"selected":""?>>승인 거절</option>
									<option value="I" <?=$row[is_ok]=="I"?"selected":""?>>승인 대기</option>
								</select>
							</td>
							</tr>							
							
							<input type="hidden" name="user_level" value="<?=$row[user_level]?>"/>
							<tr>
							<th scope="row">관리자 메모</th>
							<td colspan="3">
								<textarea style="width:90%;height:50px;" name="admin_memo" required="no"  message="관리자 메모사항" value=""><?=$row[cancel_reason]?></textarea>
							</td>
							</tr>
						</form>
					</table>
					<div style="margin-top:-20px;margin-bottom:20px;text-align:right;padding-right:10px;"><a href="javascript:go_submit();" class="btn_blue">설정변경</a></div>
			
			<?if($row['member_gubun'] == "shop"){ // 지점회원 시작 ?>
					<div class="list_tit">
						<h3>관리사찰 리스트</h3>
					</div>
				<!-- 관리사찰 리스트 시작 -->
					<div class="search_wrap" id="member_temple_request_area">
						<!-- member_temple_request.php 에서 불러옴 -->
					</div>
				<!-- 관리사찰 리스트 종료 -->	
			<?} // 지점회원 종료 ?>

				</div>
		<!-- content 종료 -->
	</div>
</div>

<script>
	function main_product_pop(){
		window.open("member_temple.php?member_idx=<?=$row['idx']?>","mem_temp_view", "top=100,left=100,scrollbars=yes,resizable=no,width=1010,height=500");
	}
	function temple_request_list(){
		get_data("member_temple_request.php","member_temple_request_area","member_idx=<?=$row['idx']?>");
	}

	$(document).ready(function() {
		temple_request_list();
	});

	function go_frm_modify(frm_name) {
		//var check = chkFrm(frm_name);
		//if(check) {
			document.forms[frm_name].submit();
		/*} else {
			return;
		}*/
	}

	function go_temple_request_mod(apply_ok,tidx){
		//alert(apply_ok);
		//alert(tidx);
		if (apply_ok == ""){
			alert("승인상태를 선택해 주세요.");
			return;
		}
		_fra_admin.location.href="member_temple_request_modaction.php?idx="+tidx+"&apply_ok="+apply_ok+"";
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>