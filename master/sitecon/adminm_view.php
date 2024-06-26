<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_group = sqlfilter($_REQUEST['s_group']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_group='.$s_group.'&pageNo='.$pageNo;

$sql = "SELECT * FROM member_info where 1=1 and idx = '".$idx."' and member_type='AD'";
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

					If ($row[user_level] == "004") { 
						$level = "전체관리자";
					} elseIf ($row[user_level] == "005") { 
						$level = "육아용품 관리자";
					} elseIf ($row[user_level] == "3") { 
						$level = "협력업체";
					}  elseIf ($row[user_level] == "4") { 
						$level = "상담관리자";
					}

$query_cnt = "select idx from member_info where 1 and member_type='AD'";
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
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/sitecon_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사이트 관리</li>
						<li>관리자 정보 보기</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>관리자 정보 보기</h3>
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
						<th >운영자 ID</th>
						<td ><?=$row[user_id]?></td>
						<th >운영자 성명</th>
						<td ><?=$row[user_name]?></td>
					</tr>
					<tr>
						<th >연락처</th>
						<td ><?=$row[cell]?></td>
						<th >E-mail</th>
						<td ><?=$row[email]?></td>
					</tr>
								
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
					<?if(mysqli_num_rows($result_cnt) > 1){?>
						<?if($row['idx'] != "1"){?>
							<!-- 삭제 -->
							<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제</a>
						<?}?>
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