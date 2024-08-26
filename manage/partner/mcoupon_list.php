<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = urldecode(sqlfilter($_REQUEST['keyword']));

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2;

if(!$pageNo){
	$pageNo = 1;
}

$where = " and is_del='N'";

if($s_sect1){
	$where .= " and dis_type = '".$s_sect1."' ";
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";

$query = "select * from member_coupon_set where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from member_coupon_set where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;

?>
<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_view(no){
		location.href = "mcoupon_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "mcoupon_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>";
	}

	function go_regist(){
		location.href = "mcoupon_write.php?bmenu=<?=$bmenu?>&smenu=5";
	}
	
	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}

	 function area_sel(z){
			
		var tmp = z.options[z.selectedIndex].value; 
		tmp2 = encodeURI(tmp);
		
		_fra_admin.location.href="area2_select.php?sido="+tmp2;
			
	}
	
//-->
</SCRIPT>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/partner_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>회원관리</li>
						<li>쿠폰발급</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>발급된 쿠폰 리스트</h3>
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
							<th scope="row">쿠폰종류</th>
							<td colspan="2">
								<select name="s_sect1" id="s_sect1" size="1" style="vertical-align:middle;width:60%;">
									<option value="">쿠폰종류</option>
									<option value="1" <?=$s_sect1=="1"?"selected":""?>>정액쿠폰</option>
									<option value="2" <?=$s_sect1=="2"?"selected":""?>>정률쿠폰</option>
								</select>
							</td>
							<th scope="row">조건검색</th>
							<td colspan="2">
								<select name="field" id="field" size="1" style="vertical-align:middle;width:40%;">
									<option value="">검색기준</option>
									<option value="coupon_num" <?=$field=="coupon_num"?"selected":""?>>쿠폰번호</option>
									<option value="coupon_title" <?=$field=="coupon_title"?"selected":""?>>쿠폰 간략설명</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:50%;" value="<?=$keyword?>">
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
				
				<table class="search_list" style="margin-top:10px;">
				<thead>
					<tr>
						<th width="5%">번호</th>
						<!--<th width="10%">쿠폰종류</th>-->
						<th width="10%">쿠폰번호</th>
						<th width="30%">쿠폰 간략설명</th>
						<th width="10%">할인종류</th>
						<th width="10%">할인혜택</th>
						<th width="10%">쿠폰만료일</th>
						<th width="15%">생성일시</th>
						<th width="10%">발급받은 회원수</th>
					</tr>
				</thead>
				<tbody>
				<? if(mysqli_num_rows($result)==0) { ?>
				<tr>
					<td colspan="10" height="40"><strong>생성된 쿠폰이 없습니다.</strong></td>
				</tr>
			<? } ?>
			<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

					$rcnt_sql = "select idx from member_coupon where 1 and coupon_idx='".$row['idx']."' and is_del='N'";
					$rcnt_query = mysqli_query($gconnet,$rcnt_sql);
					$rcnt = mysqli_num_rows($rcnt_query);					
			?>
					<tr>
						<td ><a href="javascript:go_view('<?=$row['idx']?>');"><?=$listnum?></a></td>
						<!--<td><a href="javascript:go_view('<?=$row[idx]?>');">
						<?if($row[coupon_sect] == "auto"){?>
							회원가입 자동발행쿠폰
						<?}elseif($row[coupon_sect] == "normal"){?>
							회원조회 일반쿠폰
						<?}?>
						</a></td>-->
						
						<td >
							<a href="javascript:go_view('<?=$row['idx']?>');"><?=$row['coupon_num']?></a>
						</td>
						<td >
							<a href="javascript:go_view('<?=$row['idx']?>');"><?=string_cut2(stripslashes($row['coupon_title']),26)?></a>
						</td>

						<td><a href="javascript:go_view('<?=$row['idx']?>');">
						<?if($row['dis_type'] == "1"){?>
							정액쿠폰
						<?}elseif($row['dis_type'] == "2"){?>
							정률쿠폰
						<?}?>
						</a></td>

						<td><a href="javascript:go_view('<?=$row['idx']?>');">
						<?if($row['dis_type'] == "1"){?>
							<?=number_format($row['coupon_price'],0)?> 원 할인
						<?}elseif($row['dis_type'] == "2"){?>
							<?=number_format($row['coupon_per'],0)?> % 할인
						<?}?>
						</a></td>

						<td><a href="javascript:go_view('<?=$row['idx']?>');">
						<?if($row['coupon_sect'] == "auto"){?>
							가입일부터 <?=$row['expire_date_auto']?> 일간
						<?}elseif($row['coupon_sect'] == "normal"){?>
							<?=$row['expire_date']?>
						<?}?>
						</a></td>
						<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=substr($row['wdate'],0,10)?></a></td>
						<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=number_format($rcnt)?></a></td>
					</tr>
					<? } ?>
				</tbody>
			</table>
			
			<!--<div class="table_btn align_l mt20 pl20">
							<button>선택 가입승인</button>
							<button>선택 탈퇴처리</button>
						</div>-->
						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>
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