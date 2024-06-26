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

$where = " and main_sect = 'adv' and is_del='N'";

if($s_sect4){
	$where .= " and main_type = '".$s_sect4."' ";
}

if($s_group){
	$where .= " and view_ok = '".$s_group."' ";
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by align desc ";

//$query = "select *,(select file_c from product_info where 1 and idx=main_display_set.pro_idx) as file_c,(select pro_name from product_info where 1 and idx=main_display_set.pro_idx) as pro_name,(select product_memo_dan from product_info where 1 and idx=main_display_set.pro_idx) as product_memo_dan FROM main_display_set inner join product_info on main_display_set.pro_idx=product_info.idx where 1 and product_info.use_ok='Y'".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

$query = "select *,(select ad_url from ad_info where 1 and idx=main_display_set.pro_idx) as ad_url,(select file_chg from ad_info where 1 and idx=main_display_set.pro_idx) as ad_file_chg,(select ad_title from ad_info where 1 and idx=main_display_set.pro_idx) as ad_title,(select cate_code1 from ad_info where 1 and idx=main_display_set.pro_idx) as cate_code1 FROM main_display_set where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx FROM main_display_set where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;	

if($v_sect == "Time Sale"){
	$sect_title = "타임세일";
} else {
	if($s_sect1 == "pc"){
		$sect_title = "PC";
	} elseif($s_sect1 == "mobile"){
		$sect_title = "모바일";
	}
}
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
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/advert_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>프로그램관리</li>
						<li>메인화면 프로그램배치</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>메인화면에 배치된 프로그램리스트</h3>
					<button class="btn_add" onclick="go_regist();" style="width:100px;"><span>배치설정</span></button>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" method="post" action="mainban_list.php">
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
							<th scope="row">메인위치</th>
							<td colspan="2">
								<select name="s_sect4" size="1" style="vertical-align:middle;" >
									<option value="">선택하세요</option>
									<option value="시간노출 TOP" <?=$s_sect4=="시간노출 TOP"?"selected":""?>>시간노출 TOP</option>
									<option value="상단롤링" <?=$s_sect4=="상단롤링"?"selected":""?>>상단롤링</option>
									<option value="중단" <?=$s_sect4=="중단"?"selected":""?>>중단</option>
									<option value="BEST 띠배너" <?=$s_sect4=="BEST 띠배너"?"selected":""?>>BEST 띠배너</option>
									<option value="BEST 중단롤링" <?=$s_sect4=="BEST 중단롤링"?"selected":""?>>BEST 중단롤링</option>
									<!--<option value="BEST 하단" <?=$s_sect4=="BEST 하단"?"selected":""?>>BEST 하단</option>-->
								</select>
							</td>
							<th scope="row">설정여부</th>
							<td colspan="2">
								<select name="s_group" size="1" style="vertical-align:middle;" >
									<option value="">설정여부</option>
									<option value="Y" <?=$s_group=="Y"?"selected":""?>>설정함</option>
									<option value="N" <?=$s_group=="N"?"selected":""?>>설정해제</option>
								</select>
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
		<p style="text-align:right;padding-right:10px;padding-bottom:5px;"><font style="color:red;">* 정렬순서는 숫자만 입력 가능, 높은 숫자를 우선으로 정렬됨</font></p>
			<table class="search_list">
				<thead>
					<tr>
					<?if($v_sect == "Time Sale"){?>
						<th width="5%">번호</th>
						<th width="10%">상품이미지</th>
						<th width="10%">상품명</th>
						<th width="10%">메인구분</th>
						<th width="9%">시작일시</th>
						<th width="9%">종료일시</th>
						<th width="8%">회원가</th>
						<th width="8%">비회원가</th>
						<!--<th width="8%">오픈여부</th>-->
						<th width="10%">등록일시</th>
						<th width="7%">설정여부</th>
						<th width="8%">정렬순서</th>
						<th width="6%">수정</th>
					<?}else{?>
						<th width="5%">번호</th>
						<?if($s_sect1 == "pc"){?>
							<th width="10%">메인구분</th>
							<th width="13%">메인위치</th>
							<th width="10%">상품이미지</th>
						<?}else{?>
							<!--<th width="10%">메인구분</th>-->
							<th width="10%">메인위치</th>
							<th width="10%">프로그램이미지</th>
						<?}?>
						<th width="10%">프로그램 카테고리</th>
						<th width="20%">프로그램명</th>
						<th width="10%">등록일시</th>
						<th width="10%">설정여부</th>
						<th width="10%">정렬순서</th>
						<th width="8%">수정</th>
						<th width="7%">삭제</th>
					<?}?>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="12" height="40"><strong>설정된 프로그램가 없습니다.</strong></td>
					</tr>
				<? } ?>

				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);

					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

					if($row[view_ok] == "Y"){
						$view_ok = "사용함";
					} elseif($row[view_ok] == "N"){
						$view_ok = "사용안함";
					}
					
					if($row[timesale_type] == "Y"){
						$timesale_type = "오픈";
					} elseif($row[timesale_type] == "N"){
						$timesale_type = "오픈예정";
					} else {
						$timesale_type = "";
					}

					$sql_sub_notice_2 = "select cate_name1 from viva_cate where 1 and set_code='advert' and cate_level = '1' and cate_code1='".$row[cate_code1]."'";
					//echo $sql_sub_notice_2."<br>";
					$query_sub_notice_2 = mysqli_query($gconnet,$sql_sub_notice_2);
					$row_sub_notice_2 = mysqli_fetch_array($query_sub_notice_2);
					$ad_cate_name1 = $row_sub_notice_2['cate_name1'];

				?>

				<form name="frm_modify_<?=$i?>" method="post" action="mainban_modify_list_action.php"  target="_fra_admin">
				<input type="hidden" name="idx" value="<?=$row[idx]?>"/>
				<input type="hidden" name="total_param" value="<?=$total_param?>"/>

					<tr>
					<?if($v_sect == "Time Sale"){?>
						<td><?=$listnum?></td>
						<td style="text-align:center;padding-top:5px;padding-bottom:5px;">
						<?if($row[file_c] != "" && $row[file_c] != " "){?>
							<img src="<?=$_P_DIR_WEB_FILE?>product/img_thumb/<?=$row[file_c]?>" border="0" style="max-width:90%;">
						<?}?>
						</td>
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[pro_name]?></a></td>
						<td ><a href="javascript:go_view('<?=$row[idx]?>');"><?=$main_type?></a></td>
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[start_date]?></a></td>
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[end_date]?></a></td>
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=number_format($row[sale_price_mem])?> 원</a></td>
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=number_format($row[sale_price_non])?> 원</a></td>
						<!--<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$timesale_type?></a></td>-->
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=substr($row[wdate],0,10)?></a></td>
						<td >
						<select name="view_ok" size="1" style="vertical-align:middle;" required="yes" message="설정여부">
						<option value="">선택하세요</option>
						<option value="Y" <?=$row[view_ok]=="Y"?"selected":""?>>설정함</option>
						<option value="N" <?=$row[view_ok]=="N"?"selected":""?>>설정해제</option>
						</select>
						</td>
						<td><input type="text" style="width:40%;" name="align" value="<?=$row[align]?>" required="yes" message="정렬순서" is_num="yes"></td>
						<td><a href="javascript:go_modify('frm_modify_<?=$i?>');" class="btn_blue2">수정</a></td>
					<?}else{?>
						<td><?=$listnum?></td>
						<td ><!--<a href="javascript:go_view('<?=$row[idx]?>');">--><?//=$row[main_type]?><!--</a>-->
						<select name="main_type" size="1" style="vertical-align:middle;" required="yes" message="메인위치">
							<option value="">선택하세요</option>
							<option value="시간노출 TOP" <?=$row[main_type]=="시간노출 TOP"?"selected":""?>>시간노출 TOP</option>
							<option value="상단롤링" <?=$row[main_type]=="상단롤링"?"selected":""?>>상단롤링</option>
							<option value="중단" <?=$row[main_type]=="중단"?"selected":""?>>중단</option>
							<option value="BEST 띠배너" <?=$row[main_type]=="BEST 띠배너"?"selected":""?>>BEST 띠배너</option>
							<option value="BEST 중단롤링" <?=$row[main_type]=="BEST 중단롤링"?"selected":""?>>BEST 중단롤링</option>
							<!--<option value="BEST 하단" <?=$row[main_type]=="BEST 하단"?"selected":""?>>BEST 하단</option>-->
						</select>
						</td>
						<td><!--<a href="javascript:go_view('<?=$row[idx]?>');">-->
						<?if($row[ad_file_chg]){?>
							<img src="<?=get_ad_image($row[pro_idx],"3")?>" style="max-width:90%;">
						<?}?>
						<!--</a>--></td>
						<td><!--<a href="javascript:go_view('<?=$row[idx]?>');">--><?=$ad_cate_name1?><!--</a>--></td>
						<td><!--<a href="javascript:go_view('<?=$row[idx]?>');">--><?=$row[ad_title]?><!--</a>--></td>
						<td><!--<a href="javascript:go_view('<?=$row[idx]?>');">--><?=substr($row[wdate],0,10)?><!--</a>--></td>
						<td>
							<select name="view_ok" size="1" style="vertical-align:middle;" required="yes" message="설정여부">
								<option value="">선택하세요</option>
								<option value="Y" <?=$row[view_ok]=="Y"?"selected":""?>>설정함</option>
								<option value="N" <?=$row[view_ok]=="N"?"selected":""?>>설정해제</option>
							</select>
						</td>
						<td><input type="text" style="width:40%;" name="align" value="<?=$row[align]?>" required="yes" message="정렬순서" is_num="yes"></td>
						<td><a href="javascript:go_modify('frm_modify_<?=$i?>');" class="btn_blue">수정</a></td>
						<td><a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제</a></td>
					<?}?>
					</tr>

				</form>

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