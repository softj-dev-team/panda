<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
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

if(!$pageNo){
	$pageNo = 1;
}
if(!$s_cnt){
	$s_cnt = 10; // 기본목록 10개
}

if(!$s_order){
	$s_order = 1; 
}

$where = " and point_sect='".$point_sect."' and del_yn='N'";

if($s_gender2){ 
	$where .= " and status='".$s_gender2."'";
}

if ($field && $keyword){
	if($field == "chg_cell"){
		$where .= " and ".$field." like '%".$keyword."%'";
	} else {
		$where .= " and member_idx in (select idx from member_info where 1 and del_yn='N' and member_type in ('GEN','PAT') and ".$field." like '%".$keyword."%')";
	}
}	

$pageScale = $s_cnt; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

if($s_order == "1"){
	$order_by = " order by idx desc ";
}

$query = "select *,(select email from member_info where 1 and del_yn='N' and member_type in ('GEN','PAT') and idx=member_point_change.member_idx) as email,(select user_name from member_info where 1 and del_yn='N' and member_type in ('GEN','PAT') and idx=member_point_change.member_idx) as user_name from member_point_change where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from member_point_change where 1=1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_view(no){
		location.href = "point_change_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "point_change_list.php?<?=$total_param?>";
	}

	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}

	function go_cnt_set(z){
		var tmp = z.options[z.selectedIndex].value; 
		$("#s_cnt").val(tmp);
		$("#s_mem").submit();
	}

	function go_order_set(z){
		var tmp = z.options[z.selectedIndex].value; 
		$("#s_order").val(tmp);
		$("#s_mem").submit();
	}
	
//-->
</SCRIPT>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/sitecon_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사이트 설정</li>
						<li>상품권 전환 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>상품권 전환신청 리스트</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>">
					<input type="hidden" name="mode" value="ser">
					<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
					<input type="hidden" name="smenu" value="<?=$smenu?>"/>
					<input type="hidden" name="point_sect" value="<?=$point_sect?>"/>
					<input type="hidden" name="s_cnt" id="s_cnt" value="<?=$s_cnt?>"/>
					<input type="hidden" name="s_order" id="s_order" value="<?=$s_order?>"/>
						<caption>검색</caption>
						<colgroup>
							<col style="width:14%;">
							<col style="width:20%;">
							<col style="width:13%;">
							<col style="width:20%;">
							<col style="width:13%;">
							<col style="width:20%;">
						</colgroup>
						<tr>
							<th scope="row">신청상태</th>
							<td colspan="2">
								<select name="s_gender2" size="1" style="width:50%;vertical-align:middle;" >
									<option value="">신청상태</option>
									<option value="1" <?=$s_gender2=="1"?"selected":""?>>접수중</option>
									<option value="2" <?=$s_gender2=="2"?"selected":""?>>승인</option>
									<option value="3" <?=$s_gender2=="3"?"selected":""?>>거절</option>
								</select>
							</td>
							<th scope="row">조건검색</th>
							<td colspan="2">
								<select name="field" size="1" style="width:45%;vertical-align:middle;">
									<option value="">검색기준</option>
									<option value="user_id" <?=$field=="user_id"?"selected":""?>>신청자 이메일</option>
									<option value="user_name" <?=$field=="user_name"?"selected":""?>>신청자 이름</option>
									<option value="chg_cell" <?=$field=="chg_cell"?"selected":""?>>전송받을 휴대폰번호</option>
								</select>
								<input type="text" name="keyword" id="keyword" style="width:50%;" value="<?=$keyword?>" >
							</td>
						</tr>
				</form>
				</table>
				<!-- 검색창 종료 -->

					<div class="align_r mt20">
						<button class="btn_search" onclick="s_mem.submit();">검색</button>
					</div>
					<ul class="list_tab" style="height:20px;">
						
					</ul>
					<div class="search_wrap">
						<!-- 목록 옵션 시작 -->
						<div class="result">
							<p class="txt">검색결과 : <span><?=number_format($num)?></span></p>
							<div class="btn_wrap">
								<select id="s_cnt_set" onchange="go_cnt_set(this)">
									<option value="10" <?=$s_cnt=="10"?"selected":""?>>10개보기</option>
									<option value="30" <?=$s_cnt=="30"?"selected":""?>>30개보기</option>
									<option value="50" <?=$s_cnt=="50"?"selected":""?>>50개보기</option>
									<option value="100" <?=$s_cnt=="100"?"selected":""?>>100개보기</option>
								</select>
								<!--<select id="s_order_set" onchange="go_order_set(this)">
									<option value="1" <?=$s_order=="1"?"selected":""?>>회원가입일 최신순</option>
									<option value="2" <?=$s_order=="2"?"selected":""?>>회원가입일 오래된순</option>
									<option value="3" <?=$s_order=="3"?"selected":""?>>회원명 올림차순</option>
									<option value="4" <?=$s_order=="4"?"selected":""?>>회원명 내림차순</option>
								</select>
								<button class="btn_del" onclick="go_tot_del();"><span>선택삭제</span></button>-->
							</div>
						</div>
					  <!-- 목록 옵션 종료 -->

						<table class="search_list">
							<thead>
								<tr>
									<th width="5%">번호</th>
									<th width="10%">신청자 이메일</th>
									<th width="8%">신청자 이름</th>
									<th width="8%">신청금액</th>
									<th width="7%">신청매수</th> 
									<th width="15%">신청일시</th>
									<th width="7%">신청상태</th> 
									<th width="15%">승인/거절일자</th>
									<th width="10%">지급일자</th>
									<th width="15%">거절사유</th>
								</tr>
							</thead>
							<tbody>
							<? if($num==0) { ?>
								<tr>
									<td colspan="10" height="40"><strong>등록된 상품권 전환신청이 없습니다.</strong></td>
								</tr>
							<? } ?>

						<?
						for ($i=0; $i<mysqli_num_rows($result); $i++){
							$row = mysqli_fetch_array($result);

							$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
							
							if($row['status'] == "1"){
								$view_ok = "<font style='color:black'><b>접수중</b></font>";
							} elseif ($row['status']=="2"){
								$view_ok = "<font style='color:blue'><b>승인</b></font>";
							} elseif ($row['status']=="3"){
								$view_ok = "<font style='color:red'><b>거절</b></font>";
							} 
						?>
						
								<tr>
									<td><?=$listnum?></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=$row['email']?></a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=$row['user_name']?></a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=number_format($row['chg_mile'])?></a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=number_format($row['chg_cnt'])?></a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=$row['wdate']?></a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=$view_ok?></a></td>	
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=$row['mdate']?></a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=substr($row['sdate'],0,10)?></a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=$row['memo_reject']?></a></td>	
								</tr>
						<?}?>	
						
						</tbody>
					</table>
				
					<div class="pagination mt0">
						<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
					</div>
				
			</div>
	</div>
	<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>

	