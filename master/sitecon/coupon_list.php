<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$v_step = trim(sqlfilter($_REQUEST['v_step']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

if(!$pageNo){
	$pageNo = 1;
}

$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
$cate_code2 = trim(sqlfilter($_REQUEST['cate_code2']));
$cate_code3 = trim(sqlfilter($_REQUEST['cate_code3']));

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&v_sect='.$v_sect.'&cate_code1='.$cate_code1.'&cate_code2='.$cate_code2.'&cate_code3='.$cate_code3.'&field='.$field.'&keyword='.$keyword;

$where .= " and is_del='N'"; 

$pageScale = 20; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc ";
$query = "select * from coupon_info where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from coupon_info where 1=1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>

<script type="text/javascript">
<!--
	function go_delete(frm_name){
		if(confirm('정말로 삭제 하시겠습니까?')){	
			document.forms[frm_name].is_del.value="Y";
			document.forms[frm_name].submit();		
		}
	}

	function go_delete_2(idx,catecode1,catecode2){
		if(confirm('카테고리를 삭제하시면 복구가 불가능하며, 사이트 운영에 버그가 발생할수도 있습니다. \n\n그래도 삭제 하시겠습니까?')){
			if(confirm('중분류 카테고리를 삭제하시면 소속된 소분류 카테고리가 모두 삭제됩니다. \n\n삭제된 내용은 복구되지 않습니다. 정말로 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "cate_delete_action.php?idx="+idx+"&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&cate_code1="+catecode1+"&cate_code2="+catecode2+"&cate_code3=<?=$cate_code3?>&cate_level=2";
			}
		}
	}

	function go_delete_3(idx,catecode1,catecode2,catecode3){
		if(confirm('카테고리를 삭제하시면 복구가 불가능하며, 사이트 운영에 버그가 발생할수도 있습니다. \n\n그래도 삭제 하시겠습니까?')){
			if(confirm('삭제된 내용은 복구되지 않습니다. 정말로 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "cate_delete_action.php?idx="+idx+"&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&cate_code1="+catecode1+"&cate_code2="+catecode2+"&cate_code3="+catecode3+"&cate_level=3";
			}
		}
	}

	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}

	function go_modify(frm_name) {
		var check = chkFrm(frm_name);
		if(check) {
			document.forms[frm_name].submit();
		} else {
			return;
		}
	}

	function go_submit_1() {
		var check = chkFrm('frm_1');
		if(check) {
			frm_1.submit();
		} else {
			return;
		}
	}
	
	function go_list(){
		location.href = "cate_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>";
	}

	function go_middle_regist(rnum) { 
		var dis_t = "cate2_insert_"+rnum;
		document.getElementById(dis_t).style.display = '';
	} 

	function go_middle_cancel(rnum) { 
		var dis_t = "cate2_insert_"+rnum;
		document.getElementById(dis_t).style.display = 'none';
	} 

	function go_low_regist(rnum) { 
		var dis_t = "cate3_insert_"+rnum;
		document.getElementById(dis_t).style.display = '';
	} 

	function go_low_cancel(rnum) { 
		var dis_t = "cate3_insert_"+rnum;
		document.getElementById(dis_t).style.display = 'none';
	} 

	function go_mic_regist(rnum) { 
		var dis_t = "cate4_insert_"+rnum;
		document.getElementById(dis_t).style.display = '';
	} 

	function go_mic_cancel(rnum) { 
		var dis_t = "cate4_insert_"+rnum;
		document.getElementById(dis_t).style.display = 'none';
	} 

function set_cptype_view(id,id2,type){
	if(type == "1"){
		$("#coupon_type_"+id).show();
		$("#coupon_type_"+id2).hide();
	} else if(type == "2"){
		$("#coupon_type_"+id).hide();
		$("#coupon_type_"+id2).show();
	}
}

$(function() {
	$(".datepicker").datepicker({
		changeYear:true,
		changeMonth:true,
		minDate: '-90y',
		yearRange: 'c-90:c',
		dateFormat:'yy-mm-dd',
		showMonthAfterYear:true,
		constrainInput: true,
		dayNamesMin: ['일','월', '화', '수', '목', '금', '토' ],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
	});
});
//-->
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/sitecon_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>설정 관리</li>
						<li>할인코드 발급 관리</li>
					</ul>
				</div>
				<div class="list">
				<!-- 검색창 시작 -->
				<!--<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="member_list.php">
						<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
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
							<th scope="row">승인여부</th>
							<td colspan="2">
									<select name="s_sect2" size="1" style="vertical-align:middle;" >
									<option value="">전체</option>
									<option value="Y" <?=$s_sect2=="Y"?"selected":""?>>승인</option>
									<option value="N" <?=$s_sect2=="N"?"selected":""?>>미승인</option>
								</select>
							</td>
							<th scope="row">로그인가능 여부</th>
							<td colspan="2">
								<select name="s_sect1" size="1" style="vertical-align:middle;" >
									<option value="">전체</option>
									<option value="Y" <?=$s_sect1=="Y"?"selected":""?>>로그인 가능</option>
									<option value="N" <?=$s_sect1=="N"?"selected":""?>>로그인 차단</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">조건검색</th>
							<td colspan="5">
								<select name="field" size="1" style="vertical-align:middle;width:20%;">
									<option value="">검색기준</option>
									<option value="user_id" <?=$field=="user_id"?"selected":""?>>이메일 (ID)</option>
									<option value="com_name" <?=$field=="com_name"?"selected":""?>>회사명</option>
									<option value="tel" <?=$field=="tel"?"selected":""?>>전화번호</option>
									
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
						</tr>
				</form>
				</table>-->
				<!-- 검색창 종료
					<div class="align_r mt20">
						<!--<button class="btn_down">엑셀다운로드</button>
						<button class="btn_search" onclick="s_mem.submit();">검색</button>
					</div> -->
					<!--<ul class="list_tab" style="height:20px;">
						<li class="on"><a href="#">월단위 결과</a></li>
						<li><a href="#">월단위 결과</a></li>
						<li><a href="#">월단위 결과</a></li>
					</ul>-->
					<div class="search_wrap">
					<!-- 리스트 시작 -->
						<div class="list_tit" style="margin-top:0px;">
							<h3>할인코드 발급</h3>
						</div>
											
						<table class="search_list">
							<caption>분류등록</caption>
							<colgroup>
								<col style="width:20%;">
								<col style="width:25%">
								<col style="width:15%;">
								<col style="width:20%;">
								<col style="width:10%;">
								<col style="width:10%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">할인코드번호</th>
									<th scope="col">할인코드명</th>
									<th scope="col">할인타입</th>
									<th scope="col">할인금액</th>
									<th scope="col">만료일자</th>
									<th scope="col">등록</th>
								</tr>
							</thead>
							<tbody>
							<form method="post" action="coupon_write_action.php" name="frm_1" target="_fra_admin" id="frm_1" enctype="multipart/form-data">
								<input type="hidden" name="bmenu" value="<?=$bmenu?>">
								<input type="hidden" name="smenu" value="<?=$smenu?>">
								<tr>
									<td><input type="text" style="width:90%;" name="coupon_num" required="yes" message="할인코드번호" value=""></td>
									<td><input type="text" style="width:90%;" name="coupon_name" required="yes" message="할인코드명" value=""></td>
									<td>
										<input type="radio" name="coupon_type" required="yes" message="할인타입" value="m" onclick="set_cptype_view('area_1','area_2','1');" checked> 정액형 <input type="radio" name="coupon_type" required="yes" message="할인타입" value="p" onclick="set_cptype_view('area_1','area_2','2');"> 정률형
									</td>
									<td>
										<div id="coupon_type_area_1">
											<input type="text" style="width:60%;" name="coupon_point_1" required="no" message="할인금액" is_num="yes" value=""> 원
										</div>
										<div id="coupon_type_area_2" style="display:none;">
											<input type="text" style="width:60%;" name="coupon_point_2" required="no" message="할인금액" value=""> %
										</div>
									</td>
									<td><input type="text" style="width:90%;" class="datepicker" name="end_date" required="yes" message="만료일자" value=""></td>
									<td><a href="javascript:go_submit_1();" class="btn_blue">등록</a></td>						
								</tr>
							</form>
						</tbody>
						</table>

						<div class="list_tit" style="margin-top:20px;">
							<h3>할인코드 관리</h3>
						</div>
						<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:15%;">
								<col style="width:20%">
								<col style="width:15%;">
								<col style="width:15%;">
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:15%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">할인코드번호</th>
									<th scope="col">할인코드명</th>
									<th scope="col">할인타입</th>
									<th scope="col">할인금액</th>
									<th scope="col">만료일자</th>
									<th scope="col">등록일</th>
									<th scope="col">관리</th>
								</tr>
							</thead>
							<tbody>
						<? if($num==0) { ?>
							<tr>
								<td colspan="10" height="40">등록된 할인코드이 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){ // 대분류 루프 시작
								$row = mysqli_fetch_array($result);

								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

						?>
							<form name="frm_cate1_<?=$i?>" method="post" action="coupon_modify_action.php"  target="_fra_admin" enctype="multipart/form-data">
								<input type="hidden" name="idx" value="<?=$row[idx]?>"/>
								<input type="hidden" name="bmenu" value="<?=$bmenu?>">
								<input type="hidden" name="smenu" value="<?=$smenu?>">
								<input type="hidden" name="v_step" value="<?=$v_step?>">
								<input type="hidden" name="pageNo" value="<?=$pageNo?>">
								<input type="hidden" name="field" value="<?=$field?>">
								<input type="hidden" name="keyword" value="<?=$keyword?>">
								<input type="hidden" name="is_del" value="N">
								<tr>
									<td><?=$row['coupon_num']?></td>
									<td><input type="text" style="width:90%;" name="coupon_name" required="yes" message="할인코드명" value="<?=$row['coupon_name']?>"></td>
									
									<td>
										<input type="radio" name="coupon_type" required="yes" message="할인타입" value="m" onclick="set_cptype_view('area_1_<?=$row[idx]?>','area_2_<?=$row[idx]?>','1');" <?=$row['coupon_type']=="m"?"checked":""?>> 정액형 <input type="radio" name="coupon_type" required="yes" message="할인타입" value="p" onclick="set_cptype_view('area_1_<?=$row[idx]?>','area_2_<?=$row[idx]?>','2');" <?=$row['coupon_type']=="p"?"checked":""?>> 정률형
									</td>
									<td>
										<div id="coupon_type_area_1_<?=$row[idx]?>" style="display:<?=$row['coupon_type']=="m"?"":"none"?>;">
											<input type="text" style="width:60%;" name="coupon_point_1" required="no" message="할인금액" is_num="yes" value="<?=$row['coupon_point']?>"> 원
										</div>
										<div id="coupon_type_area_2_<?=$row[idx]?>" style="display:<?=$row['coupon_type']=="p"?"":"none"?>;">
											<input type="text" style="width:60%;" name="coupon_point_2" required="no" message="할인금액" value="<?=$row['coupon_point']?>"> %
										</div>
									</td>

									<td><input type="text" style="width:90%;" class="datepicker" name="end_date" required="yes" message="만료일자" value="<?=$row['end_date']?>"></td>
									<td><?=substr($row[wdate],0,10)?></td>
									<td>
										<a href="javascript:go_modify('frm_cate1_<?=$i?>');" class="btn_green" >수정</a>&nbsp;<a href="javascript:go_delete('frm_cate1_<?=$i?>');" class="btn_red">삭제</a>
									</td>						
								</tr>
							</form>
						
						<?} // 대분류 루프 종료 ?>	
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
