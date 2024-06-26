<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_group='.$s_group;

if(!$pageNo){
	$pageNo = 1;
}

$where .= "  ";

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

/*$query =	" SELECT * ";
$query = $query." FROM ( ";
$query = $query." SELECT	ROW_NUMBER() OVER(ORDER BY idx DESC) AS rowNumber ";
$query = $query.",	idx,subject, startdt, enddt,is_use,wdate ";
$query = $query." FROM popup_div WITH(NOLOCK) ";
$query = $query." WHERE 1=1  ".$where;
$query = $query."	) AS S ";
$query = $query." WHERE S.rowNumber BETWEEN ".$StarRowNum." AND ".$EndRowNum." ;";*/

$order_by = " order by idx desc ";

$query = "select * from popup_div where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from popup_div where 1=1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_view(no){
		location.href = "popup_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "popup_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "popup_write.php?<?=$total_param?>";
	}
	
	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
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
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사이트 관리</li>
						<li>팝업 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>팝업 리스트</h3>
					<button class="btn_add" onclick="go_regist();" style="width:150px;"><span>팝업 등록</span></button>
				</div>
				<div class="list">
					<!-- 검색창 시작 -->
					<table class="search">
					<form name="s_mem" method="post" action="popup_list.php">
						<input type="hidden" name="mode" value="ser">
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
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
							<th scope="row">조건검색</th>
							<td colspan="5">
								<select name="field" size="1" style="vertical-align:middle;width:20%;">
									<option value="">검색기준</option>
									<option value="subject" <?=$field=="subject"?"selected":""?>>팝업제목</option>
									<option value="content" <?=$field=="content"?"selected":""?>>팝업내용</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:40%;" value="<?=$keyword?>">
							</td>
						</tr>
				</form>
				</table>
				<!-- 검색창 종료 -->
				<div class="align_r mt20">
					<!--<button class="btn_down">엑셀다운로드</button>-->
					<button class="btn_search" onclick="s_mem.submit();">검색</button>
				</div>
				<ul class="list_tab" style="height:20px;">
					
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
				<thead>
					<tr>
						<th width="5%">번호</th>
						<th width="10%">사용여부</th>
						<th width="40%">제 목</th>
						<th width="15%">팝업시작일</th>
						<th width="15%">팝업종료일</th>
						<th width="15%">등록일</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>등록된 팝업이 없습니다.</strong></td>
					</tr>
				<? } ?>

				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);

					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

					if($row[is_use] == "Y"){	
						$is_use = "<font style='color:blue;'>사용중</font>";
					} elseif($row[is_use] == "N"){
						$is_use = "<font style='color:red;'>사용안함</font>";
					}
				?>
					<tr>
						<td><?=$listnum?></td>
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$is_use?></a></td>
						<td style="text-align:left;padding-left:10px;"><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[subject]?></a></td>
						<td><?=$row[startdt]?></td>						
						<td><?=$row[enddt]?></td>		
						<td><?=substr($row[wdate],0,10)?></td>
					</tr>
				<?}?>	
			
			</tbody>
			</table>
			
			<!-- 페이징 시작 -->
			<div class="pagination mt0">
				<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
			</div>
			<!-- 페이징 종료 -->
		</div>
	</div>
		<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>