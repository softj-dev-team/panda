<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); 
$s_gubun = sqlfilter($_REQUEST['s_gubun']); 
$s_level = sqlfilter($_REQUEST['s_level']); 
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2;

$where = " and memout_yn != 'Y' and memout_yn != 'S' and member_type = '".$v_sect."' ";
//$where .= " and member_gubun = '".$s_gubun."' ";

if($s_gubun == "NOR"){
	$member_sect_str = "건축가";
} elseif($s_gubun == "SPE"){
	$member_sect_str = "인테리어회사";
}

if(!$pageNo){
	$pageNo = 1;
}

if($s_sect1){
	$where .= " and login_ok = '".$s_sect1."' ";
}

if($s_sect2){
	$where .= " and master_ok = '".$s_sect2."' ";
}

if($s_level){
	$where .= " and brn_member_idx = '".$s_level."' ";
}

if($s_gender){
	$where .= " and gender = '".$s_gender."' ";
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";

$query = "select * from member_info where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from member_info where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
<script type="text/javascript">
<!--	 
	function go_view(no){
		location.href = "member_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "member_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "member_write.php?<?=$total_param?>";
	}

	function go_regist_add(no){
		location.href = "member_write_add.php?member_idx="+no+"&<?=$total_param?>";
	}
	
	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}
	
	function go_excel() {
	var check = chkFrm('order_excel_frm');
		if(check) {
			order_excel_frm.submit();
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
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/member_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>가맹점 관리</li>
						<li>가맹점 리스트</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>가맹점 리스트</h3>
				</div>
				<div class="list">
					<div class="search_wrap">
					<!-- 검색창 시작 -->
					<form name="s_mem" method="post" action="member_list.php">
						<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
						<div class="search_area">
							<select name="s_sect2" size="1" style="vertical-align:middle;" >
								<option value="">승인여부</option>
								<option value="Y" <?=$s_sect2=="Y"?"selected":""?>>승인</option>
								<option value="N" <?=$s_sect2=="N"?"selected":""?>>미승인</option>
							</select>
							&nbsp;&nbsp;
							<select name="s_sect1" size="1" style="vertical-align:middle;" >
								<option value="">로그인여부</option>
								<option value="Y" <?=$s_sect1=="Y"?"selected":""?>>로그인 가능</option>
								<option value="N" <?=$s_sect1=="N"?"selected":""?>>로그인 차단</option>
							</select>
							&nbsp;&nbsp;
							<select name="field" size="1" style="vertical-align:middle;">
								<option value="">검색기준</option>
								<option value="user_id" <?=$field=="user_id"?"selected":""?>>아이디</option>
								<option value="com_name" <?=$field=="com_name"?"selected":""?>>가맹점명</option>
								<option value="presi_name" <?=$field=="presi_name"?"selected":""?>>점주님명</option>
								<option value="cell" <?=$field=="cell"?"selected":""?>>휴대전화</option>
								<option value="email" <?=$field=="email"?"selected":""?>>이메일</option>
							</select>
							<input type="text" title="검색" name="keyword" id="keyword" value="<?=$keyword?>">
							<button onclick="s_mem.submit();">검색</button>
						</div>
						<div class="result">
							<div class="btn_wrap" style="height:36px;">
								<!--<select>
									<option>전체</option>
								</select>-->
							</div>
						</div>
					</form>
					<!-- 검색창 종료 -->
					<!-- 리스트 시작 -->
						<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:10%">
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:20%;">
								<col style="width:7%;">
								<col style="width:8%;">
								<col style="width:10%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">No</th>
									<th scope="col">ID</th>
									<th scope="col">가맹점명</th>
									<th scope="col">점주님명</th>
									<th scope="col">대표전화</th>
									<th scope="col">휴대전화</th>
									<th scope="col">매장주소</th>
									<th scope="col">승인여부</th>
									<th scope="col">로그인여부</th>
									<th scope="col">등록일</th>
								</tr>
							</thead>
							<tbody>
						<? if($num==0) { ?>
							<tr>
								<td colspan="10" height="40">등록된 가맹점이 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);

								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

								if($row[login_ok] == "Y"){
									$login_ok = "<font style='color:blue;'>로그인 가능</font>";
								}elseif($row[login_ok] == "N"){
									$login_ok = "<font style='color:red;'>로그인 차단</font>";
								}

								if($row[master_ok] == "Y"){
									$master_ok = "<font style='color:blue;'>승인</font>";
								}elseif($row[master_ok] == "N"){
									$master_ok = "<font style='color:red;'>미승인</font>";
								}

								$member_level_sql = "select level_name from member_level_set where 1=1 and level_code = '".$row[user_level]."' ";   
								$member_level_query = mysqli_query($gconnet,$member_level_sql);
								$member_level_row = mysqli_fetch_array($member_level_query);
								$user_level_str = $member_level_row['level_name'];

								if($row[gender] == "M"){
									$gender = "남성";
								} elseif($row[gender] == "F"){
									$gender = "여성";
								} else {
									$gender = "";
								}
						?>
						<tr>
							<td><?=$listnum?></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[user_id]?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[com_name]?></a></td>
							<td><?=$row[presi_name]?></td>
							<td><?=$row[com_tel]?></td>
							<td><?=$row[cell]?></td>
							<td><?=$row[addr1]?>&nbsp;<?=$row[addr2]?></td>
							<td><?=$master_ok?></td>
							<td><?=$login_ok?></td>
							<td><?=substr($row[wdate],0,10)?></td>
						</tr>
					<?}?>	
						</tbody>
						</table>
						<!-- 리스트 종료 -->
						<div class="table_btn align_r mt20">
							<a href="javascript:go_regist();">사용자등록</a>
						</div>
						<!-- 페이징 시작 -->
						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>
						<!-- 페이징 종료 -->
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
