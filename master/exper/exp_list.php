<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 파트너
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 게시 시작일
$s_level = sqlfilter($_REQUEST['s_level']); // 게시 종료일
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order;

$where = " and is_del = 'N' ";
$member_sect_str = "체험";

if(!$pageNo){
	$pageNo = 1;
}

if(!$s_order){
	$s_order = 1;
}

if($v_sect){
	$where .= " and member_idx = '".$v_sect."' ";
}

if($s_gubun){
	$where .= " and s_date >= '".$s_gubun."' ";
}

if($s_level){
	$where .= " and e_date <= '".$s_level."' ";
}

if($s_gender){
	$where .= " and time_yn = '".$s_gender."' ";
}

if($s_sect1){
	$where .= " and cate_code1 = '".$s_sect1."' ";
}

if($s_sect2){
	$where .= " and view_ok = '".$s_sect2."' ";
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$query_cnt = "select idx from exp_info where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$pageScale = 20; // 페이지당 20 개씩 
//$pageScale = $num;
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

if($s_order == 1){ // 책장 올림차순 
	$order_by = " order by idx desc "; 
} elseif($s_order == 2){ // 체험명 올림차순 
	$order_by = " order by exp_title asc "; 
} elseif($s_order == 3){ // 체험명 내림차순 
	$order_by = " order by exp_title desc "; 
} elseif($s_order == 4){ // 최신 등록순 
	$order_by = " order by idx desc "; 
}

$query = "select *,(select com_name from member_info where 1 and member_type = 'PAT' and idx=exp_info.member_idx) as com_name,(select cate_name1 from viva_cate where 1 and set_code='exper' and cate_level = '1' and cate_code1=exp_info.cate_code1) as cate_name1 from exp_info where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
<script type="text/javascript">
<!--	 
	function go_view(no){
		location.href = "exp_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "exp_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "exp_write.php?<?=$total_param?>";
	}

	function go_regist_add(no){
		location.href = "exp_write_add.php?exp_idx="+no+"&<?=$total_param?>";
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

	function cate_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		//alert(tmp);
		_fra_admin.location.href="cate_select_3.php?cate_code1="+tmp+"&fm=frm&fname=gugun";
	}

var check  = 0;                                                                            //체크 여부 확인
function CheckAll(){                
	var boolchk;                                                                              //boolean형 변수 
	var chk = document.getElementsByName("product_idx[]")                 //체크박스의 name값
		
		if(check){ check=0; boolchk = false; }else{ check=1; boolchk = true; }    //체크여부에 따른 true/false 설정
			
			for(i=0; i<chk.length;i++){                                                                    
				chk[i].checked = boolchk;                                                                //체크되어 있을경우 설정변경
			}

}

function go_tot_del() {
link_sect_txt2.style.display = 'none';
var check = chkFrm('frm');
	if(check) {
		if(confirm('선택하신 체험를 삭제 하시겠습니까?')){
			if(confirm('삭제하신 체험는 복구할 수 없습니다. 정말 삭제 하시겠습니까?')){
				frm.action = "exp_list_action_del.php";
				frm.submit();
			}
		}
	} else {
		false;
	}
}

function go_tot_send() {
link_sect_txt2.style.display = '';
var check = chkFrm('frm');
	if(check) {
		if ($("#c_booktable").val() == ""){
			alert('이동할 책장을 선택해주세요.');
			return;	
		}
		var c_booktable_chg = $("#c_booktable").val();
		$("#c_booktable_frm").val(c_booktable_chg);
		frm.action = "exp_list_action_change.php";
		frm.submit();
	} else {
		false;
	}
}

function go_tot_excel() {
link_sect_txt2.style.display = 'none';
//var check = chkFrm('frm');
	//if(check) {
		frm.action = "exp_list_action_excel.php";
		frm.submit();
	/*} else {
		false;
	}*/
}
//-->
</script>
<body>
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
						<li>등록된 체험 리스트</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>체험 리스트</h3>
					<button class="btn_add" onclick="go_regist();" style="width:100px;"><span>체험등록</span></button>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" method="post" action="exp_list.php">
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
							<th scope="row">카테고리</th>
							<td colspan="2">
							<select name="s_sect1" size="1" onchange="s_mem.submit();" style="vertical-align:middle;width:20%;">
								<option value="">카테고리 선택</option>
							<?
							$sect3_sql = "select cate_code1,cate_name1 from viva_cate where 1 and set_code='exper' and cate_level = '1' and is_del='N' order by cate_align desc";
							$sect3_result = mysqli_query($gconnet,$sect3_sql);
							for ($i=0; $i<mysqli_num_rows($sect3_result); $i++){
								$row3 = mysqli_fetch_array($sect3_result);
							?>
								<option value="<?=$row3[cate_code1]?>" <?=$row3[cate_code1]==$s_sect1?"selected":""?>><?=$row3[cate_name1]?></option>
							<?}?>
							</select>
							</td>
							<th scope="row">시간설정 노출</th>
							<td colspan="2">
								<select name="s_gender" size="1" style="vertical-align:middle;" >
									<option value="">전체</option>
									<option value="Y" <?=$s_gender=="Y"?"selected":""?>>설정된 시간에만 노출</option>
									<option value="N" <?=$s_gender=="N"?"selected":""?>>시간 관계없이 노출</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">파트너</th>
							<td colspan="2">
								<select name="v_sect" size="1" style="vertical-align:middle;" >
									<option value="">선택하세요</option>
								<?
								$sub_sql = "select idx,com_name from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and member_type='PAT'";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
								?>
									<option value="<?=$sub_row[idx]?>" <?=$v_sect==$sub_row[idx]?"selected":""?>><?=$sub_row[com_name]?></option>
								<?}?>		
								</select>
							</td>
							<th scope="row">체험모집 기간</th>
							<td colspan="2">
								<input type="text" name="s_gubun" style="width:40%;" id="s_gubun" onClick="new CalendarFrame.Calendar(this)" value="<?=$s_gubun?>" readonly> ~ <input type="text" name="s_level" style="width:40%;" id="s_level" onClick="new CalendarFrame.Calendar(this)" value="<?=$s_level?>" readonly>
							</td>
						</tr>
						<tr>
							<th scope="row">승인여부</th>
							<td colspan="2">
								<select name="s_sect2" size="1" style="vertical-align:middle;" >
									<option value="">전체</option>
									<option value="Y" <?=$s_sect2=="Y"?"selected":""?>>승인</option>
									<option value="N" <?=$s_sect2=="N"?"selected":""?>>미승인</option>
								</select>
							</td>
							<th scope="row">조건검색</th>
							<td colspan="2">
							<!-- <select name="s_gubun" size="1" style="vertical-align:middle;" onchange="cate_sel_1(this)"> -->
							<!--<select name="s_gubun" size="1" style="vertical-align:middle;width:10%;" >
									<option value="">지역선택</option>
								<?
								$sub_sql = "select sido from zipcode where 1 group by sido order by sido asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
							?>
								<option value="<?=$sub_row[sido]?>" <?=$s_gubun==$sub_row[sido]?"selected":""?>><?=$sub_row[sido]?></option>
							<?}?>	
								</select>-->

								<select name="field" size="1" style="vertical-align:middle;width:40%;">
									<option value="">검색기준</option>
									<option value="exp_title" <?=$field=="exp_title"?"selected":""?>>체험제목</option>
									<option value="exp_content" <?=$field=="exp_content"?"selected":""?>>체험내용</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:50%;" value="<?=$keyword?>">
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
				<form method="post" name="frm" target="_fra_admin" id="frm">
					<input type="hidden" name="pageNo" value="<?=$pageNo?>" />
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>

					<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
					<input type="hidden" name="s_gubun" value="<?=$s_gubun?>"/>
					<input type="hidden" name="s_sect1" value="<?=$s_sect1?>"/>
					<input type="hidden" name="s_sect2" value="<?=$s_sect2?>"/>
					<input type="hidden" name="field" value="<?=$field?>"/>
					<input type="hidden" name="keyword" value="<?=$keyword?>"/>

					<input type="hidden" name="c_booktable" id="c_booktable_frm" value=""/>

					<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<!--<col style="width:4%;">-->
								<col style="width:5%;">
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:15%;">
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:10%;">
							</colgroup>
							<thead>
								<tr>
									<!--<th scope="col"><input type="checkbox" id="" name="checkNum" onclick="javascript:CheckAll()"></th>-->
									<th scope="col">No</th>
									<th scope="col">카테고리</th>
									<th scope="col">대표이미지</th>
									<th scope="col">파트너</th>
									<th scope="col">체험제목</th>
									<th scope="col">체험시작일</th>
									<th scope="col">체험종료일</th>
									<th scope="col">체험수량</th>
									<th scope="col">승인여부</th>
									<th scope="col">등록일</th>
								</tr>
							</thead>
							<tbody>
						<? if($num==0) { ?>
							<tr>
								<td colspan="10" height="40">등록된 체험이 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);

								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

								if($row[view_ok] == "Y"){
									$view_ok = "<font style='color:blue;'>승인</font>";
								}elseif($row[view_ok] == "N"){
									$view_ok = "<font style='color:red;'>미승인</font>";
								}
						?>
						<tr>
							<!--<td><input type="checkbox" name="product_idx[]" id="product_idx[]" value="<?=$row["idx"]?>" required="yes"  message="체험"/></td>-->
							<td><?=$listnum?></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[cate_name1]?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');">
							<?if($row[file_chg]){?>
								<img src="<?=get_exp_image($row[idx],"file_chg","3")?>" style="max-width:90%;">
							<?}?>
							</a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[com_name]?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[exp_title]?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[s_date]?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[e_date]?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=number_format($row[set_click_cnt])?> 명</a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$view_ok?></a></td>
							<td><?=substr($row[wdate],0,10)?></td>
						</tr>
					<?}?>	
						</tbody>
						</table>
					</form>
						
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
