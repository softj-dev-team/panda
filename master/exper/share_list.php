<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$v_sect = urldecode(sqlfilter($_REQUEST['v_sect']));
$s_group = trim(sqlfilter($_REQUEST['s_group']));

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = urldecode(sqlfilter($_REQUEST['keyword']));

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));
$s_sect3 = trim(sqlfilter($_REQUEST['s_sect3']));
$s_sect4 = trim(sqlfilter($_REQUEST['s_sect4']));

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&v_sect='.urlencode($v_sect).'&s_group='.$s_group.'&field='.$field.'&keyword='.$keyword.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_sect3='.$s_sect3.'&s_sect4='.$s_sect4;

if(!$pageNo){
	$pageNo = 1;
}

$where = " and share_sect='exp' and is_del='N'";

if($s_sect2){
	$where .= " and share_type = '".$s_sect2."' ";
}

if($field == "exp_title"){
	$where .= " and share_idx in (select idx from exp_info where 1 and exp_title like '%".$keyword."%')";
} elseif($field == "member"){
	$where .= " and member_idx in (select idx from member_info where 1 and user_name like '%".$keyword."%')";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";

$query = "select *,(select exp_title from exp_info where 1 and idx=share_history.share_idx) as exp_title,(select user_name from member_info where 1 and idx=share_history.member_idx) as user_name FROM share_history where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx FROM share_history where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;	

?>
<SCRIPT LANGUAGE="JavaScript">
<!--

	function view_pic(ref) {
			ref = ref;
			var window_left = (screen.width-1024) / 2;
			var window_top = (screen.height-768) / 2;
			window.open(ref, "pic_window", 'width=600,height=400,status=no,scrollbars=yes,top=' + window_top + ', left=' + window_left +'');
	}

	function go_view(no){
		location.href = "mainban_view.php?idx="+no+"&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "mainban_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "mainban_write.php?<?=$total_param?>";
	}
	
	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}

	function go_align(no,mode,align){
		_fra_admin.location.href = "align_reset.php?idx="+no+"&mode="+mode+"&align="+align+"&<?=$total_param?>&tbn=main_display_set&ret_url=/Shop/yonex_master/sitecon/mainban_list.php";
	}

	function go_modify(frm_name) {
		var check = chkFrm(frm_name);
		if(check) {
			document.forms[frm_name].submit();
		} else {
			return;
		}
	}

	function go_delete(no){
		if(confirm('정말 삭제 하시겠습니까?')){
			if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "mainban_delete_action.php?idx="+no+"&<?=$total_param?>";
			}
		}
	}

	
//-->
</SCRIPT>

<!-- content -->
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/exper_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>체험관리</li>
						<li>체험 공유내역</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>공유내역 리스트</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" method="post" action="answer_list.php">
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
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
							<th scope="row">조건검색</th>
							<td colspan="5">
								<select name="s_sect2" size="1" style="vertical-align:middle;width:15%;" >
									<option value="">공유채널별 검색</option>
									<option value="fab" <?=$s_sect2=="fab"?"selected":""?>>페이스북</option>
									<option value="ins" <?=$s_sect2=="ins"?"selected":""?>>인스타그램</option>
									<option value="kat" <?=$s_sect2=="kat"?"selected":""?>>카카오톡</option>
									<option value="kas" <?=$s_sect2=="kas"?"selected":""?>>카카오스토리</option>
									<option value="twt" <?=$s_sect2=="twt"?"selected":""?>>트위터</option>
								</select>

								<select name="field" size="1" style="vertical-align:middle;width:20%;">
									<option value="">검색기준</option>
									<option value="exp_title" <?=$field=="exp_title"?"selected":""?>>체험제목</option>
									<option value="member" <?=$field=="member"?"selected":""?>>닉네임</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:40%;" value="<?=$keyword?>">
							</td>
						</tr>
				</form>
				</table>
				<!-- 검색창 종료 -->

				<div class="align_r mt20">
						<button class="btn_search" onclick="s_mem.submit();">검색</button>
						<!--<button class="btn_down" onclick="order_excel_frm.submit();">엑셀다운로드</button>-->
					</div>
					<ul class="list_tab" style="height:20px;">
						<!--<li class="on"><a href="#">월단위 결과</a></li>
						<li><a href="#">월단위 결과</a></li>
						<li><a href="#">월단위 결과</a></li>-->
					</ul>
					<div class="search_wrap">
					<!-- 목록 옵션 시작 -->
						<div class="result">
							<p class="txt">검색결과 총 <span><?=$num?></span>건</p>
							<div class="btn_wrap">
								<!--<select id="s_cnt_set" onchange="go_cnt_set(this)">
									<option value="10" <?=$s_cnt=="10"?"selected":""?>>10개보기</option>
									<option value="20" <?=$s_cnt=="20"?"selected":""?>>20개보기</option>
									<option value="30" <?=$s_cnt=="30"?"selected":""?>>30개보기</option>
									<option value="40" <?=$s_cnt=="40"?"selected":""?>>40개보기</option>
								</select>
								<select id="s_order_set" onchange="go_order_set(this)">
									<option value="1" <?=$s_order=="1"?"selected":""?>>회원가입일 최신순</option>
									<option value="2" <?=$s_order=="2"?"selected":""?>>회원가입일 오래된순</option>
									<option value="3" <?=$s_order=="3"?"selected":""?>>회원명 올림차순</option>
									<option value="4" <?=$s_order=="4"?"selected":""?>>회원명 내림차순</option>
								</select>
								<button class="btn_del" onclick="go_tot_del();"><span>선택삭제</span></button>-->
							</div>
						</div>
					<!-- 목록 옵션 종료 -->

				<!-- 리스트 시작 -->
			<table class="search_list">
				<thead>
					<tr>
						<th width="5%">번호</th>
						<th width="20%">체험제목</th>
						<th width="15%">공유한 닉네임</th>
						<th width="12%">공유매체</th>
						<th width="33%">공유 URL</th>
						<th width="15%">공유 일시</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="12" height="40"><strong>공유 내역이 없습니다.</strong></td>
					</tr>
				<? } ?>

				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);

					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

					if($row[share_type] == "fab"){
						$ans_ok = "페이스북";
					} elseif($row[share_type] == "ins"){
						$ans_ok = "인스타그램";
					} elseif($row[share_type] == "kat"){
						$ans_ok = "카카오톡";
					} elseif($row[share_type] == "kas"){
						$ans_ok = "카카오스토리";
					} elseif($row[share_type] == "twt"){
						$ans_ok = "트위터";
					}
				
				?>
				<tr>
					<td><?=$listnum?></td>
					<td><?=$row['exp_title']?></td>
					<td><?=$row['user_name']?></td>
					<td><?=$ans_ok?></td>
					<td><a href="<?=$row['share_url']?>" target="_blank"><?=$row['share_url']?></a></td>
					<td><?=$row['wdate']?></td>
				</tr>
				<?}?>	
			
			</tbody>
			</table>
			
				<div class="pagination mt0">
					<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
				</div>
			
			</div>
				
	<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>