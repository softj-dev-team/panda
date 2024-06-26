<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_level = sqlfilter($_REQUEST['s_level']); 
$s_gubun = sqlfilter($_REQUEST['s_gubun']);  // 내용확인 여부
$site_sect = sqlfilter($_REQUEST['site_sect']); //  Contact Us / 광고문의 구분
$s_gender = sqlfilter($_REQUEST['s_gender']); // 답변완료 여부

$query_read = " update board_content set "; 
$query_read .= " cnt = cnt +1, ";
$query_read .= " read_time = now() ";
$query_read .= " where idx = '".$idx."'";
$result_read = mysqli_query($gconnet,$query_read);

$where = " and idx = '".$idx."'and bbs_code = '".$site_sect."'"; 
$order_by = " order by idx desc ";
$sql = "select * from board_content where 1 ".$where.$order_by;
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

if($site_sect == "qna"){
	$member_sect_str = "1:1 문의";
} elseif($site_sect == "tax"){
	$member_sect_str = "세금계산서 신청";
} 

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&site_sect='.$site_sect.'&s_gender='.$s_gender.'&pageNo='.$pageNo;

if($row[cnt] > 0){
	$read_ok = "<font style='color:blue;'>내용확인</font>";
}else if($row[cnt] == 0){
	$read_ok = "<font style='color:red;'>확인전</font>";
}

if($row[re_YN] == "I"){
	$reply_ok = "<font style='color:green;'>신청중</font>";
}elseif($row[re_YN] == "Y"){
	$reply_ok = "<font style='color:blue;'>회신완료</font>";
}elseif($row[re_YN] == "N"){
	$reply_ok = "<font style='color:red;'>회신전</font>";
}
?>

<script type="text/javascript">
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
	//if(confirm('답변내용을 저장하시면 질문하신 회원에게 답변내용이 메일로 발송됩니다.')){
		//if(confirm('메일로 발송된 답변은 수정이나 삭제가 불가합니다. 정말 저장 하시겠습니까?')){
<?}?>
	var check = chkFrm('frm');
	if(check) {
		frm.submit();
	} else {
		false;
	}
<?if($row[reply_ok] == "N"){?>
	//}
//}
<?}?>
}
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"].""."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"].""."/manage/include/customer_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>게시판 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$member_sect_str?> 상세보기</h3>
				</div>
				<div class="write">
					<p class="tit"><?=$member_sect_str?> 내용 상세보기</p>
					<table>
						<caption>회원 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
					<tr>
						<th>작성자</th>
						<td colspan="3"><?=$row[writer]?></td>
					</tr>
				<?if($site_sect == "qna"){?>
					<tr>
						<th>제목</th>
						<td colspan="3"><?=$row[subject]?></td>
					</tr>
					<tr>
						<th>휴대전화</th>
						<td colspan="3"><?=$row['1vs1_cell']?></td>
					</tr>
					<tr>
						<th>이메일</th>
						<td colspan="3"><?=$row[email]?></td>
					</tr>
					<tr>
						<th>문의내용</th>
						<td colspan="3">
							<?=nl2br($row[content])?>
						</td>
					</tr>
				<?} ?>
					<tr>
						<th >등록일시</th>
						<td colspan="3"><?=$row[write_time]?></td>
					</tr>
					<tr>
						<th >내용 확인여부</th>
						<td colspan="3"><?=$read_ok?></td>
					</tr>
					<tr>
						<th >내용 확인일시</th>
						<td colspan="3"><?=$row[read_time]?></td>
					</tr>
					<tr>
						<th>회신상태</th>
						<td colspan="3"><?=$reply_ok?></td>
					</tr>
				<?if($row[re_YN] == "Y"){?>
					<tr>
						<th>회신일시</th>
						<td colspan="3"><?=$row[modify_time]?></td>
					</tr>
					<tr>
						<th>회신한 관리자</th>
						<td><?=$row[ad_sect_name]?></td>
						<th>관리자 아이디</th>
						<td><?=$row[ad_sect_id]?></td>
					</tr>
				<?}?>
					</table>

				<div class="write_btn align_r">
					<a href="javascript:go_list();" class="btn_gray">목록보기</a>
					<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제하기</a>	
				</div>

					<p class="tit">회신여부 및 관리자 메모</p>
					<table>
					<colgroup>
						<col width="15%" />
						<col width="35%" />
						<col width="15%" />
						<col width="35%" />
					</colgroup>
			
						<form name="frm" action="con_view_action.php" target="_fra_admin" method="post" >
							<input type="hidden" name="idx" value="<?=$idx?>"/>
							<input type="hidden" name="total_param" value="<?=$total_param?>"/>
							<input type="hidden" name="member_idx" value="<?=$row[member_idx]?>"/>
							<input type="hidden" name="prev_reply_ok" value="<?=$row[re_YN]?>"/>
							<tr>
								<th scope="row">회신상태</th>
								<td colspan="3">
									<select name="reply_ok" size="1" style="vertical-align:middle;" >
										<option value="">선택하세요</option>
										<option value="Y" <?=$row[re_YN]=="Y"?"selected":""?>>회신완료</option>
										<option value="N" <?=$row[re_YN]=="N"?"selected":""?>>회신전</option>
									</select>
								</td>
							<tr>
							<th>관리자 메모</th>
							<td colspan="3">
								<textarea style="width:90%;height:50px;" name="admin_memo" required="yes"  message="관리자 답변" value=""><?=$row[admin_memo]?></textarea>
							</td>
							</tr>
					</form>

					</table>
					<div style="margin-top:-20px;margin-bottom:20px;text-align:right;padding-right:10px;"><a href="javascript:go_submit();" class="btn_blue">상태변경 및 메모저장</a></div>

				</div>
			</div>
		</div>
		<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>