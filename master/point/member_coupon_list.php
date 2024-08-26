<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = urldecode(sqlfilter($_REQUEST['keyword']));

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword;

if(!$pageNo){
	$pageNo = 1;
}

$where = " and b.del_yn='N' and c.is_del='N'";

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$pageScale = 20; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$where .= " group by b.idx  ";
$order_by = " order by c.idx desc ";

$query = "select a.level_name,b.* from member_level_set a INNER JOIN member_info b ON a.level_code = b.user_level INNER JOIN member_coupon c ON b.idx = c.member_idx where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select b.idx from member_level_set a INNER JOIN member_info b ON a.level_code = b.user_level INNER JOIN member_coupon c ON b.idx = c.member_idx where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_list(){
		location.href = "member_coupon_list.php?<?=$total_param?>";
	}
	
	function go_modify(frm_name) {
		var check = chkFrm(frm_name);
		if(check) {
			document.forms[frm_name].submit();
		} else {
			return;
		}
	}
	
	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}
	
	function go_coupon_pop(no){
		//location.href = 
		window.open("member_coupon_history.php?mem_idx="+no+"","couponview", "top=100,left=100,scrollbars=yes,resizable=no,width=1080,height=600");
	}
//-->
</SCRIPT>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/point_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>포인트 관리</li>
						<li>쿠폰 내역 조회</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>쿠폰 내역 조회</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="mcoupon_list.php">
						<input type="hidden" name="mode" value="ser">
				<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
				<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
				<input type="hidden" name="smenu" value="<?=$smenu?>"/>
				<input type="hidden" name="s_group" value="<?=$s_group?>"/>
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
								<select name="field" size="1" style="vertical-align:middle;width:20%;">
									<option value="">검색기준</option>
									<option value="b.user_id" <?=$field=="b.user_id"?"selected":""?>>이메일</option>
									<option value="b.user_name" <?=$field=="b.user_name"?"selected":""?>>이름</option>
									<option value="b.cell" <?=$field=="b.cell"?"selected":""?>>연락처</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:40%;" value="<?=$keyword?>">
							</td>
						</tr>
				</form>
				</table>
				<!-- 검색창 종료 -->

				<!-- 엑셀 출력을 위한 전송 폼 시작 -->
			<form name="order_excel_frm" id="order_excel_frm" method="post" action="member_excel_list.php">
			<input type="hidden" name="mode" value="ser">
			<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
			<input type="hidden" name="s_gubun" value="<?=$s_gubun?>"/>
			<input type="hidden" name="s_sect1" value="<?=$s_sect1?>"/>
			<input type="hidden" name="s_sect2" value="<?=$s_sect2?>"/>
			<input type="hidden" name="s_level" value="<?=$s_level?>"/>
			<input type="hidden" name="field" value="<?=$field?>"/>
			<input type="hidden" name="keyword" value="<?=htmlspecialchars($keyword)?>"/>
			</form>
			<!-- 엑셀 출력을 위한 전송 폼 종료 -->

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
							<p class="txt">쿠폰을 발급받은 총 <?=$num?> 명의 회원이 있습니다. </p>
							<div class="btn_wrap">
								<b>아이디나 이름을 클릭하시면 해당 회원의 해당 회원의 쿠폰 히스토리가 나옵니다.</b>
							</div>
						</div>
					<!-- 목록 옵션 종료 -->

				<table class="search_list" style="margin-top:10px;">
				<thead>
					<tr>
						<th width="10%">번호</th>
						<th width="30%">이메일</th>
						<th width="20%">이름</th>
						<th width="20%">연락처</th>
						<th width="10%">발급받은 쿠폰</th>
						<th width="10%">사용한 쿠폰</th>
					</tr>
				</thead>
				<tbody>
				<? if(mysqli_num_rows($result)==0) { ?>
				<tr>
					<td colspan="10" height="40"><strong>쿠폰이 발급된 회원이 없습니다.</strong></td>
				</tr>
			<? } ?>
			<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

					$sql_pre1 = "select idx from member_coupon where member_idx='".$row[idx]."' and coupon_sect in ('A') ";	
					$query_pre1 = mysqli_query($gconnet,$sql_pre1);
					$cnt_pre1 = mysqli_num_rows($query_pre1);

					$sql_pre2 = "select idx from member_coupon where member_idx='".$row[idx]."' and coupon_sect in ('M','C') ";	
					$query_pre2 = mysqli_query($gconnet,$sql_pre2);
					$cnt_pre2 = mysqli_num_rows($query_pre2);
				?>
								
					<tr>
						<td><?=$listnum?></td>
						<td><a href="javascript:go_coupon_pop('<?=$row['idx']?>')"><?=$row['user_id']?></a></td>
						<td><a href="javascript:go_coupon_pop('<?=$row['idx']?>')"><?=$row['user_name']?></a></td>
						<td><?=$row['cell']?></td>
						<td><?=number_format($cnt_pre1,0)?> 장</td>
						<td><?=number_format($cnt_pre2,0)?> 장</td>
					</tr>
				
				<? } ?>
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
