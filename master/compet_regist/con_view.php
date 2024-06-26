<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_level = sqlfilter($_REQUEST['s_level']); 
$s_gubun = sqlfilter($_REQUEST['s_gubun']);  // 내용확인 여부
$site_sect = sqlfilter($_REQUEST['site_sect']); //  Contact Us / 광고문의 구분
$s_gender = sqlfilter($_REQUEST['s_gender']); // 답변완료 여부

$sql = "select *,(select user_nick from member_info where 1 and idx=(select member_idx from compet_info where 1 and idx=compet_regist_price_info.compet_idx)) as req_name,(select member_type from member_info where 1 and idx=(select member_idx from compet_info where 1 and idx=compet_regist_price_info.compet_idx)) as req_type,(select member_idx from compet_info where 1 and idx=compet_regist_price_info.compet_idx) as req_idx,(select compet_title from compet_info where 1 and idx=compet_regist_price_info.compet_idx) as compet_title,(select work_title from compet_regist_info where 1 and idx=compet_regist_price_info.regist_idx) as work_title from compet_regist_price_info where 1 and idx = '".$idx."' and is_del='N'";
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

$member_sect_str = "고객후기";

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&site_sect='.$site_sect.'&s_gender='.$s_gender.'&pageNo='.$pageNo;

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
	<? include $_SERVER["DOCUMENT_ROOT"].""."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"].""."/master/include/regist_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>참가작 관리</li>
						<li>고객후기 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>고객후기 상세보기</h3>
				</div>
				<div class="write">
					<p class="tit">고객후기 내용 상세보기</p>
					<table>
						<caption>회원 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
					<tr>
						<th>공모전 명</th>
						<td colspan="3"><?=$row[compet_title]?></td>
					</tr>
					<tr>
						<th>참가작 제목</th>
						<td colspan="3"><?=$row[work_title]?></td>
					</tr>
					<tr>
						<th>작성자</th>
						<td colspan="3"><?=$row[req_name]?></td>
					</tr>
					<tr>
						<th>선정등수</th>
						<td><?=$row[select_champ_depth]?> 위</td>
						<th>작성일시</th>
						<td><?=$row[estimate_date]?></td>
					</tr>
					<tr>
						<th>후기내용</th>
						<td colspan="3">
							<?=nl2br($row[estimate_txt])?>
						</td>
					</tr>
					
					<!--<tr>
						<th >내용 확인여부</th>
						<td colspan="3"><?=$read_ok?></td>
					</tr>
					<tr>
						<th >내용 확인일시</th>
						<td colspan="3"><?=$row[readdate]?></td>
					</tr>
					<tr>
						<th>회신상태</th>
						<td colspan="3"><?=$reply_ok?></td>
					</tr>
				<?if($row[reply_ok] == "Y"){?>
					<tr>
						<th>회신일시</th>
						<td colspan="3"><?=$row[replydate]?></td>
					</tr>
					<tr>
						<th>회신한 관리자</th>
						<td><?=$row[ad_sect_name]?></td>
						<th>관리자 아이디</th>
						<td><?=$row[ad_sect_id]?></td>
					</tr>
				<?}?>-->
					</table>

				<div class="write_btn align_r">
					<a href="javascript:go_list();" class="btn_gray">목록보기</a>
					<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제하기</a>	
				</div>

					<!--<p class="tit">회신여부 및 관리자 메모</p>
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
							<input type="hidden" name="prev_reply_ok" value="<?=$row[reply_ok]?>"/>
							<tr>
								<th scope="row">회신상태</th>
								<td colspan="3">
									<select name="reply_ok" size="1" style="vertical-align:middle;" >
										<option value="">선택하세요</option>
										<option value="Y" <?=$row[reply_ok]=="Y"?"selected":""?>>회신완료</option>
										<option value="N" <?=$row[reply_ok]=="N"?"selected":""?>>회신전</option>
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
					<div style="margin-top:-20px;margin-bottom:20px;text-align:right;padding-right:10px;"><a href="javascript:go_submit();" class="btn_blue">상태변경 및 메모저장</a></div> -->

				</div>
			</div>
		</div>
		<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>