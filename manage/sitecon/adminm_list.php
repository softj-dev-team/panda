<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$s_group = sqlfilter($_REQUEST['s_group']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_group='.$s_group;

if(!$pageNo){
	$pageNo = 1;
}

$where .= " and member_type='AD' ";

if($s_group){
	$where .= " and user_level = '".$s_group."' ";
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale) ;
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";

/*$query =	" SELECT * ";
$query = $query." FROM ( ";
$query = $query." SELECT	ROW_NUMBER() OVER(ORDER BY idx DESC) AS rowNumber ";
$query = $query.",	idx,user_level, user_id, user_name,cell,email,wdate ";
$query = $query." FROM member_info WITH(NOLOCK) ";
$query = $query." WHERE 1=1  ".$where;
$query = $query."	) AS S ";
$query = $query." WHERE S.rowNumber BETWEEN ".$StarRowNum." AND ".$EndRowNum." ;";*/

$query = "select * from member_info where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from member_info where 1=1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>

<script type="text/javascript">
<!--
	function go_view(no){
		location.href = "adminm_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "adminm_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "adminm_write.php?<?=$total_param?>";
	}
	
	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}
	
//-->
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/sitecon_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사이트 관리</li>
						<li>관리자 리스트</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>관리자 리스트</h3>
					<button class="btn_add" onclick="go_regist();" style="width:150px;"><span>관리자 등록</span></button>
				</div>
				<div class="list">
					<!-- 검색창 시작 -->
					<table class="search">
					<form name="s_mem" id="s_mem" method="post" action="adminm_list.php">
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
						<input type="hidden" name="s_cnt" id="s_cnt" value="<?=$s_cnt?>"/>
						<input type="hidden" name="s_order" id="s_order" value="<?=$s_order?>"/>
						<caption>검색</caption>
						<colgroup>
							<col style="width:15%;">
							<col style="width:20%;">
							<col style="width:15%;">
							<col style="width:15%;">
							<col style="width:20%;">
							<col style="width:15%;">
						</colgroup>
						<tr>
							<!--<th scope="row">셀러회원</th>
							<td colspan="2">
							<select name="v_sect" size="1" style="vertical-align:middle;" onchange="s_mem.submit();">
								<option value="">선택</option>
							<?
								$sect1_sql = "select idx,com_name from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and member_type = 'SEL' order by com_name asc";
								$sect1_result = mysqli_query($gconnet,$sect1_sql);
								for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
									$row1 = mysqli_fetch_array($sect1_result);
							?>
								<option value="<?=$row1[idx]?>" <?=$row1[idx]==$v_sect?"selected":""?>><?=$row1[com_name]?></option>
							<?}?>	
							</select>
							</td>-->
							<th scope="row">조건검색</th>
							<td colspan="5">
								<select name="field" size="1" style="vertical-align:middle;width:20%;">
									<option value="">검색기준</option>
									<option value="user_id" <?=$field=="user_id"?"selected":""?>>아이디</option>
									<option value="user_name" <?=$field=="user_name"?"selected":""?>>성 명</option>
									<option value="cell" <?=$field=="cell"?"selected":""?>>연락처</option>
									<option value="email" <?=$field=="email"?"selected":""?>>이메일</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:40%;" value="<?=$keyword?>">
							</td>
						</tr>
						
						<!--<tr>
							<th scope="row">기관구분</th>
							<td>
								<select>
									<option>전체</option>
								</select>
							</td>
							<th scope="row">공연장소</th>
							<td>
								<select>
									<option>전체</option>
								</select>
							</td>
							<th scope="row">장르</th>
							<td>
								<select>
									<option>전체</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">공연명</th>
							<td colspan="5">
								<input type="text" title="공연명" placeholder="공연명 입력" style="width:300px">
							</td>
						</tr>-->
				</form>
				</table>
				<!-- 검색창 종료 -->
				<div class="align_r mt20">
					<!--<button class="btn_down">엑셀다운로드</button>-->
					<button class="btn_search" onclick="s_mem.submit();">검색</button>
				</div>
				<ul class="list_tab" style="height:20px;">
					<!--<li class="on"><a href="#">월단위 결과</a></li>
					<li><a href="#">월단위 결과</a></li>
					<li><a href="#">월단위 결과</a></li>-->
				</ul>
				<!-- 리스트 시작 -->
				<div class="search_wrap">
					<!-- 목록 옵션 시작 
						<div class="result">
							<p class="txt">검색결과 총 <span><?=$num?></span>건</p>
							<div class="btn_wrap">
								<select id="s_cnt_set" onchange="go_cnt_set(this)">
									<option value="10" <?=$s_cnt=="10"?"selected":""?>>10개보기</option>
									<option value="20" <?=$s_cnt=="20"?"selected":""?>>20개보기</option>
									<option value="30" <?=$s_cnt=="30"?"selected":""?>>30개보기</option>
									<option value="40" <?=$s_cnt=="40"?"selected":""?>>40개보기</option>
								</select>
								<select id="s_order_set" onchange="go_order_set(this)">
									<option value="1" <?=$s_order=="1"?"selected":""?>>등록일 최신순</option>
									<option value="2" <?=$s_order=="2"?"selected":""?>>등록일 오래된순</option>
									<!--<option value="3" <?=$s_order=="3"?"selected":""?>>회원명 올림차순</option>
									<option value="4" <?=$s_order=="4"?"selected":""?>>회원명 내림차순</option>
								</select>
								<button class="btn_del" onclick="go_tot_del();"><span>선택삭제</span></button>
							</div>
						</div>
					<!-- 목록 옵션 종료 -->
					<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:15%">
								<col style="width:20%;">
								<col style="width:20%;">
								<col style="width:20%;">
								<col style="width:20%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">No</th>
									<th scope="col">아이디</th>
									<th scope="col">성명</th>
									<th scope="col">연락처</th>
									<th scope="col">이메일</th>
									<th scope="col">등록일</th>
								</tr>
							</thead>
							<tbody>
						<? if($num==0) { ?>
							<tr>
								<td colspan="10" height="40">등록된 관리자가 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);

								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
						?>
						<tr>
							<td><?=$listnum?></td>
							<!--<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$level?></a></td>-->
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[user_id]?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[user_name]?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[cell]?></a></td>						
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[email]?></a></td>		
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[wdate]?></a></td>
						</tr>
					<?}?>	
						</tbody>
						</table>
					</form>
						<!-- 페이징 시작 -->
						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>
						<!-- 페이징 종료 -->

                        <!--<button class="analytics-bt" style="display:inline-block; margin-left:20px; margin-bottom:20px; background-color: #ccc; color: #333;" onclick="window.open('https://analytics.google.com/analytics/web/?hl=ko&pli=1#/realtime/rt-overview/a207864280w286770725p251422148/')">실시간 접속자</button>
                        <button class="analytics-bt" style="display:inline-block; margin-left:20px; margin-bottom:20px;" onclick="window.open('https://analytics.google.com/analytics/web/?hl=ko&pli=1#/report/trafficsources-overview/a207864280w286770725p251422148/')">누적 접속자</button>-->
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