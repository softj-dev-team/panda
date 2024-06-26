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
$s_level = sqlfilter($_REQUEST['s_level']); // 회원 계급별 검색
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 정회원,우수회원,셀러회원 등 검색
$v_sect = sqlfilter($_REQUEST['v_sect']); // 일반회원, 제휴회원 구분
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별 검색
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_level='.$s_level.'&s_gubun='.$s_gubun.'&v_sect='.$v_sect.'&s_gender='.$s_gender.'&pageNo='.$pageNo;

$sql = "SELECT * FROM member_info_out where 1=1 and idx = '".$idx."' ";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('탈퇴처리 완료된 회원이 없습니다.');
	location.href =  "member_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

if($row[gender] == "M"){
	$gender = "남성";
} elseif($row[gender] == "F"){
	$gender = "여성";
} 

if($row[user_gubun] == "GEN_M"){
	$user_gubun = "정회원";
} elseif($row[user_gubun] == "GEN_S"){
	$user_gubun = "우수회원";
} elseif($row[user_gubun] == "GEN_V"){
	$user_gubun = "VIP 회원";
}  elseif($row[user_gubun] == "PAT_B"){
	$user_gubun = "게시판운영 회원";
}  elseif($row[user_gubun] == "PAT_S"){
	$user_gubun = "셀러 회원";
}  elseif($row[user_gubun] == "PAT_SS"){
	$user_gubun = "파워셀러 회원";
}  else {
	$user_gubun = "";
}

if($row[user_sect] == "GEN"){
	$member_sect_str = "일반회원";
} elseif($row[user_sect] == "PAT"){
	$member_sect_str = "제휴회원";
}

$member_level_sql = "select level_name from member_level_set where 1=1 and level_code = '".$row[user_level]."' ";   
$member_level_query = mysqli_query($gconnet,$member_level_sql);
$member_level_row = mysqli_fetch_array($member_level_query);
$user_level_str = $member_level_row['level_name'];
?>
<!-- content -->
<script type="text/javascript">
<!--

function go_delete(no){
	if(confirm('정말 삭제 하시겠습니까?')){

		if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "memout_delete_action.php?idx="+no+"&<?=$total_param?>";
		}
	}
}

function go_list(){
		location.href = "member_out_done.php?<?=$total_param?>";
}

//-->		
</script>

<section id="content">
	<div class="inner">
		<h3>탈퇴처리 완료된 회원정보 상세보기</h3>
		<div class="cont">
			<table class="t_view">
				<colgroup>
					<col width="10%" />
					<col width="40%" />
					<col width="10%" />
					<col width="40%" />
				</colgroup>
				<!--
					<tr>
						<th >회원종류</th>
						<td  colspan="3"><?=$user_gubun?></td>
					</tr>
				-->
					<?//if($row[user_gubun] == "NOR"){?>
						<tr>
							<th >아이디 (ID)</th>
							<td ><?=$row[user_id]?></td>
							<th >성 명</th>
							<td ><?=$row[user_name]?></td>
						</tr>
						<!--<tr>
							<th >주민등록번호</th>
							<td  colspan="3"><?=$row[ssn_1]?>-******* </td>
						</tr>-->

						<tr>
							<th >탈퇴시 회원종류</th>
							<td ><?=$member_sect_str?></td>
							<th >탈퇴시 회원구분</th>
							<td ><?=$user_gubun?></td>
						</tr>

						
						<tr>
							<th >탈퇴시 회원계급</th>
							<td ><?=$user_level_str?></td>
							<th >성 별</th>
							<td ><?=$gender?></td>
						</tr>
						
					<?//} elseif($row[user_gubun] == "COM"){?>
					<!--<tr>
						<th >아이디 (ID)</th>
						<td ><?=$row[user_id]?></td>
						<th >업체명</th>
						<td ><?=$row[com_name]?></td>
					</tr>
					<tr>
						<th >사업자 등록번호</th>
						<td  colspan="3"><?=$row[com_num]?></td>
					</tr>
					<tr>
						<th >대표자명</th>
						<td ><?=$row[username]?></td>
						<th >대표자 휴대전화</th>
						<td ><?=$row[cell]?></td>
					</tr>-->
				<?//}?>
					
					<tr>
						<th >불편했던 점</th>
						<td colspan="3"><?=$row[memout_sect]?></td>
					</tr>
				  
				  <tr>
						<th >마지막 한마디</th>
						<td colspan="3" style="padding-top:10px;padding-bottom:10px;"><?=nl2br($row[memout_memo])?></td>
				  </tr>
				  
				  <tr>
						<th >회원등록 일시</th>
						<td colspan="3"><?=$row[wdate]?></td>
				  </tr>
				  
				  <tr>
						<th >탈퇴신청 일시</th>
						<td colspan="3"><?=$row[out_sin_date]?></td>
				  </tr>
				  
				  <tr>
						<th >탈퇴처리 일시</th>
						<td colspan="3"><?=$row[outdate]?></td>
				  </tr>

			</table>

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