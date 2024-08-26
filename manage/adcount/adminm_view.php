<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_group = sqlfilter($_REQUEST['s_group']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_group='.$s_group.'&pageNo='.$pageNo;

$sql = "SELECT * FROM member_info where 1 and idx = '".$idx."' and member_type='AD' and del_yn='N'";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<script type="text/javascript">
	<!--
	alert('해당하는 관리자 정보가 없습니다.');
	location.href =  "adminm_list.php?<?=$total_param?>";
	//-->
</script>
<?
exit;
}

$row = mysqli_fetch_array($query);

if($row['member_gubun'] == "MAIN"){
	$s_gubun_str = "관리자";
} elseif($row['member_gubun'] == "SUB"){
	$s_gubun_str = "운영자";
} 

$query_cnt = "select idx from member_info where 1 and member_type='AD' and del_yn='N' and member_gubun='MAIN'";
$result_cnt = mysqli_query($gconnet,$query_cnt);
?>
<!-- content -->
<script type="text/javascript">
<!--
function go_view(no){
		location.href = "adminm_view.php?idx="+no+"&<?=$total_param?>";
}
	
function go_modify(no){
		location.href = "adminm_modify.php?idx="+no+"&<?=$total_param?>";
}

function go_delete(no){
	if(confirm('정말 삭제 하시겠습니까?')){

		if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "adminm_delete_action.php?idx="+no+"&<?=$total_param?>";
		}
	}
}

function go_list(){
		location.href = "adminm_list.php?<?=$total_param?>";
}

function go_submit() {
	var check = chkFrm('frm');
	if(check) {
		frm.submit();
	} else {
		false;
	}
}
//-->		
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/adcount_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>환경설정</li>
						<li><?=$s_gubun_str?> 계정 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$s_gubun_str?> 계정 보기</h3>
				</div>
				<div class="write">
				
					<table>
						<caption>관리자 정보 보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
					
					<!--<tr>
						<th >관리레벨</th>
						<td colspan="3"><?=$level?></td>
				   </tr>-->
							
					<tr>
						<th ><?=$s_gubun_str?> ID</th>
						<td ><?=$row[user_id]?></td>
						<th ><?=$s_gubun_str?> 성명</th>
						<td ><?=$row[user_name]?></td>
					</tr>
					<!--<tr>
						<th >연락처</th>
						<td ><?=$row[cell]?></td>
						<th >E-mail</th>
						<td ><?=$row[email]?></td>
					</tr>-->

					<?if($row['member_gubun'] == "SUB"){
						$sub_sql = "select * from admin_account_auth where 1 and admin_idx='".$row['idx']."'";
						$sub_query = mysqli_query($gconnet,$sub_sql);
						for($k=0; $k<mysqli_num_rows($sub_query); $k++){
							$sub_row = mysqli_fetch_array($sub_query);
						?>
							<tr>
								<th >관리지역 (<?=$k+1?>)</th>
								<td colspan="3">
								<?=get_data_colname("code_bjd","bjd_code",$sub_row['sido'],"k_name")?> > <?=get_data_colname("code_bjd","bjd_code",$sub_row['gugun'],"k_name")?>
								</td>
							</tr>
						<?}?>
					<?}?>
								
					<tr>
						<th >등록일</th>
						<td colspan="3"><?=$row[wdate]?></td>
					</tr>

					</table>
					
					<div class="write_btn align_r">
						<!-- 목록 -->
						<a href="javascript:go_list();" class="btn_gray">목록</a>
						<!-- 수정 -->
						<a href="javascript:go_modify('<?=$row[idx]?>');" class="btn_blue">수정하기</a>
				
						<?if($row['member_gubun'] == "MAIN"){?>
							<?if(mysqli_num_rows($result_cnt) > 1){?>
								<!-- 삭제 -->
								<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제</a>
							<?}?>
						<?} elseif($row['member_gubun'] == "SUB"){?>
							<!-- 삭제 -->
							<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제</a>
						<?}?>
					</div>
				</div>
			</div>
		</div>
		<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>