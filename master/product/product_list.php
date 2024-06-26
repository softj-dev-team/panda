<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));

	$date_s = trim(sqlfilter($_REQUEST['date_s'])); 
	$date_e = trim(sqlfilter($_REQUEST['date_e']));
	$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
	$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 

	if($_POST['s_protype']){ 
		$s_protype = $_POST['s_protype'];
		for($si=0; $si<sizeof($s_protype); $si++){
			if($si == sizeof($s_protype)-1){
				$s_protype_arr .= $s_protype[$si];
			} else {
				$s_protype_arr .= $s_protype[$si].",";
			}
		}
	} else { 
		if($_GET['s_protype']){
			$s_protype_arr = urldecode($_GET['s_protype']);
			$s_protype = explode(",",$s_protype_arr);
		} 
	}

	$s_salemtd = trim(sqlfilter($_REQUEST['s_salemtd'])); 
	$s_salests = trim(sqlfilter($_REQUEST['s_salests']));
	$field = "product_title";
	$keyword = trim(sqlfilter($_REQUEST['keyword']));
	
	$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
	$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 

	$total_param = "bmenu=".$bmenu."&smenu=".$smenu."&date_s=".$date_s."&date_e=".$date_e."&s_sect1=".$s_sect1."&s_sect2=".$s_sect2."&s_protype=".urlencode($s_protype_arr)."&s_salemtd=".$s_salemtd."&s_salests=".$s_salests."&keyword=".$keyword."&s_cnt=".$s_cnt."&s_order=".$s_order;

	$where = " and is_del='N'";
	
	if($date_s){
		$where .= " and wdate >= '".$date_s."'";
	}
	if($date_e){
		$where .= " and wdate <= '".$date_e."'";
	}
	
	if($s_sect1){
		$where .= " and cate_code1 = '".$s_sect1."'";
	}
	if($s_sect2){
		$where .= " and cate_code2 = '".$s_sect2."'";
	}
	
	if(!empty($s_protype)){ // 작품유형 시작 
		$where .= " AND (";
		for($si=0; $si<sizeof($s_protype); $si++){
			/*if($si == sizeof($s_amark)-1){
				$where .= " JSON_CONTAINS(product_type,'\"".$s_protype[$si]."\"','$') >= 1";
			} else {
				$where .= " JSON_CONTAINS(product_type,'\"".$s_protype[$si]."\"','$') >= 1 or";
			}*/
			if($si == sizeof($s_protype)-1){
				$where .= " product_type = '".$s_protype[$si]."'";
			} else {
				$where .= " product_type = '".$s_protype[$si]."' or";
			}
		}
		$where .= ")";
	} // 작품유형 종료 

	if($s_salemtd){
		$where .= " and idx in (select product_idx from product_info_sale where 1 and sale_method = '".$s_salemtd."' and is_del='N')";
	}
	if($s_salests){ // 판매상태 시작
		$where .= " and idx in (select product_idx from product_info_sale where 1 and resale_yn = '".$s_salests."' and is_del='N')";
	} // 판매상태 종료
	if ($field && $keyword){
		$where .= " and ".$field." like '%".$keyword."%'";
	}

	if(!$pageNo){
		$pageNo = 1;
	}
	if(!$s_cnt){
		$s_cnt = 10; // 기본목록 10개
	}
	if(!$s_order){
		$s_order = 1; 
	}

	$pageScale = $s_cnt;  
	$start = ($pageNo-1)*$pageScale;

	$StarRowNum = (($pageNo-1) * $pageScale);
	$EndRowNum = $pageScale;
	
	if($s_order == 1){
		$order_by = " order by align desc";
	}

	$query = "select *,(select cate_name1 from common_code where 1 and del_ok='N' and type='menu' and cate_level='1' and cate_code1=product_info.cate_code1) as cate_name1,(select cate_name2 from common_code where 1 and del_ok='N' and type='menu' and cate_level='2' and cate_code2=product_info.cate_code2) as cate_name2,(select sale_method from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_method,(select resale_yn from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as resale_yn,(select sale_auth_yn from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_auth_yn,(select sale_price from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_price,(select sale_cnt from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_cnt,(select sale_ok from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_ok,(select sale_cancel_memo from product_info_sale where 1 and is_del='N' and product_idx=product_info.idx and member_idx=product_info.member_idx) as sale_cancel_memo,(select user_nick from member_info where 1 and del_yn='N' and idx=product_info.member_idx) as user_nick,(select email from member_info where 1 and del_yn='N' and idx=product_info.member_idx) as user_email from product_info where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
	//echo $query;
	$result = mysqli_query($gconnet,$query);

	$query_cnt = "select idx from product_info where 1 ".$where;
	$result_cnt = mysqli_query($gconnet,$query_cnt);
	$num = mysqli_num_rows($result_cnt);

	$iTotalSubCnt = $num;
	$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
<script type="text/javascript">
<!--	 
	function go_view(no){
		//location.href = "product_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
		window.open("../member/popup_product_view.php?idx="+no+"","proview", "top=100,left=100,scrollbars=yes,resizable=no,width=1080,height=600");
	}
	
	function go_list(){
		location.href = "product_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "product_write.php?<?=$total_param?>";
	}

	function go_modify(no){
		location.href = "product_modify.php?idx="+no+"&<?=$total_param?>";
	}

	function go_delete(no){
		if(confirm('작품을 삭제하시면 판매정보와 거래중인 내역도 모두 삭제됩니다. 정말 삭제하시겠습니까?')){	
			_fra_admin.location.href = "product_delete_action.php?idx="+no+"&<?=$total_param?>";
		}
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
	var check = chkFrm('frm');
	if(check) {
		if(confirm('선택하신 회원을 삭제 하시겠습니까?')){
			if(confirm('삭제하신 회원정보는 복구할 수 없습니다. 정말 삭제 하시겠습니까?')){
				frm.action = "product_list_action_del.php";
				frm.submit();
			}
		}
	} else {
		false;
	}
}

function go_tot_stop() {
	var check = chkFrm('frm');
	if(check) {
		if(confirm('선택하신 회원의 로그인을 정지 상태로 설정하시겠습니까?')){
			frm.action = "product_list_action_stop.php";
			frm.submit();
		}
	} else {
		false;
	}
}

function go_tot_start() {
	var check = chkFrm('frm');
	if(check) {
		if(confirm('선택하신 회원의 로그인을 활성화 상태로 설정하시겠습니까?')){
			frm.action = "product_list_action_start.php";
			frm.submit();
		}
	} else {
		false;
	}
}

	function cate_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		//alert(tmp);
		_fra_admin.location.href="cate_select_1.php?cate_code1="+tmp+"&fm=s_mem&fname=s_sect1";
	}

	 function cate_sel_2(z){
		var ktmp = document.s_mem.s_gender.value;
		if(z){
			var tmp = z.options[z.selectedIndex].value; 
			_fra_admin.location.href="cate_select_2.php?cate_code1="+ktmp+"&cate_code2="+tmp+"&fm=s_mem&fname=s_sect2";
		} else {
			_fra_admin2.location.href="cate_select_2.php?cate_code1="+ktmp+"&fm=s_mem&fname=s_sect2";
		}
	}
	
//-->
</script>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/product_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>작품 등록 관리</li>
						<li>작품 등록관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>작품 리스트</h3>
				<?//if($s_gubun == "NOR"){?>
					<button class="btn_add" onclick="go_regist();" style="width:15%;"><span>작품 등록</span></button>
				<?//}?>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>">
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
							<th scope="row">기간</th>
							<td colspan="2">
								<input type="text" autocomplete="off" readonly name="date_s" id="date_s" style="width:25%;" class="datepicker" value="<?=$date_s?>"> ~ <input type="text" autocomplete="off" readonly name="date_e" id="date_e" style="width:25%;" class="datepicker" value="<?=$date_e?>">
							</td>
							<th scope="row">카테고리</th>
							<td colspan="2">
								<select name="s_sect1" id="s_sect1" style="vertical-align:middle;width:45%;" onchange="product_menu_sel_1(this)">
									<option value="">대분류</option>
								<?
								$sect1_sql = "select cate_code1,cate_name1 from common_code where 1 and is_del='N' and del_ok='N' and type='menu' and cate_level='1' order by cate_align desc";
								$sect1_result = mysqli_query($gconnet,$sect1_sql);
									for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
										$row1 = mysqli_fetch_array($sect1_result);
								?>
									<option value="<?=$row1['cate_code1']?>" <?=$s_sect1==$row1['cate_code1']?"selected":""?>><?=$row1['cate_name1']?></option>
								<?}?>
								</select>
								&nbsp;
								<select name="s_sect2" id="s_sect2" style="vertical-align:middle;width:45%;">
									<option value="">중분류</option>
								<?
								$sect1_sql = "select cate_code2,cate_name2 from common_code where 1 and is_del='N' and del_ok='N' and type='menu' and cate_level='2' and cate_code1='".$s_sect1."' order by cate_align desc";
								$sect1_result = mysqli_query($gconnet,$sect1_sql);
									for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
										$row1 = mysqli_fetch_array($sect1_result);
								?>
									<option value="<?=$row1['cate_code2']?>" <?=$s_sect2==$row1['cate_code2']?"selected":""?>><?=$row1['cate_name2']?></option>
								<?}?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">작품유형</th>
							<td colspan="5">
							<? foreach ($arr_product_type as $key=>$val) {
									if(in_array($key, $s_protype)){
										$check = "checked";
									} else {
										$check = "";
									}
								?>
								<input type="checkbox" id="s_protype_<?=$key?>" name="s_protype[]" <?=$check?> value="<?=$key?>"/> <?=$val?> &nbsp; 
							<?}?>
							</td>
						</tr>
						<tr>
							<th scope="row">판매방식</th>
							<td colspan="2">
							<? foreach ($arr_sale_method as $key=>$val) {
									if($s_salemtd == $key){
										$check = "checked";
									} else {
										$check = "";
									}
								?>
								<input type="radio" id="s_salemtd_<?=$key?>" name="s_salemtd" <?=$check?> value="<?=$key?>"/> <?=$val?> &nbsp; 
							<?}?>
							</td>
							<th scope="row">재판매 방식</th>
							<td colspan="2">
								<input type="radio" id="s_salests_1" name="s_salests" value="Y" <?=$s_salests=="Y"?"checked":""?>/> 재판매 &nbsp; 
								<input type="radio" id="s_salests_2" name="s_salests" value="N" <?=$s_salests=="N"?"checked":""?>/> 해당없음
							</td>
						</tr>
						<tr>
							<th scope="row">작품명</th>
							<td colspan="5">
								<input type="text" title="검색" name="keyword" id="keyword" style="width:30%"  value="<?=$keyword?>">
							</td>
						</tr>
				</form>
				</table>
				<!-- 검색창 종료 -->
		
			<!-- 엑셀 출력을 위한 전송 폼 시작 -->
			<form name="order_excel_frm" id="order_excel_frm" method="post" action="product_excel_list.php">
			<input type="hidden" name="field" value="<?=$field?>"/>
			<input type="hidden" name="keyword" value="<?=htmlspecialchars($keyword)?>"/>
			<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
			<input type="hidden" name="s_gubun" value="<?=$s_gubun?>"/>
			<input type="hidden" name="s_level" value="<?=$s_level?>"/>
			<input type="hidden" name="s_gender" value="<?=$s_gender?>"/>
			<input type="hidden" name="s_sect1" value="<?=$s_sect1?>"/>
			<input type="hidden" name="s_sect2" value="<?=$s_sect2?>"/>
			<input type="hidden" name="s_cnt" value="<?=$s_cnt?>"/>
			<input type="hidden" name="s_order" value="<?=$s_order?>"/>
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
							<p class="txt">총 <span><?=number_format($num)?></span>건</p>
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
				
				<form method="post" name="frm" target="_fra_admin" id="frm">
					<input type="hidden" name="pageNo" value="<?=$pageNo?>" />
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>

						<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:4%;">
								<col style="width:10%;">
								<col style="width:16%">
								<col style="width:17%;">
								<col style="width:8%;">
								<col style="width:8%;">
								<col style="width:6%;">
								<col style="width:9%;">
								<col style="width:4%;">
								<col style="width:8%;">
								<col style="width:10%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">순번</th>
									<th scope="col">닉네임 (이메일)</th>
									<th scope="col">작품명</th>
									<th scope="col">카테고리</th>
									<th scope="col">작품유형</th>
									<th scope="col">등록/수정일</th>
									<th scope="col">판매방식</th>
									<th scope="col">판매권리</th>
									<th scope="col">재판매</th>
									<th scope="col">판매가</th>
									<th scope="col">관리</th>
								</tr>
							</thead>
							<tbody>
							<? if($num==0) { ?>
								<tr>
									<td colspan="10" height="40">등록된 작품이 없습니다.</strong></td>
								</tr>
							<? } ?>
							<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);
								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
							?>
								<tr>
									<td><?=$listnum?></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=$row['user_nick']?> (<?=$row['user_email']?>)</a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=$row['product_title']?></a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');">
										<?=$row['cate_name1']?><?if($row['cate_name2']){?> > <?=$row['cate_name2']?> <?}?>
									</a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=$arr_product_type[$row['product_type']]?></a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=substr($row['mdate'],0,10)?></a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?=$arr_sale_method[$row['sale_method']]?></a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');">소유권<?if($row['sale_auth_yn'] == "Y"){?>,저작권<?}?></a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');"><?if($row['resale_yn'] == "Y"){?>O<?}?></a></td>
									<td><a href="javascript:go_view('<?=$row['idx']?>');">$<?=$row['sale_price']?></a></td>
									<td>
										<a href="javascript:go_modify('<?=$row['idx']?>');" class="btn_blue">수정</a>
										<a href="javascript:go_delete('<?=$row['idx']?>');" class="btn_red">삭제</a>
									</td>
								</tr>
							<?}?>
						</table>

						<input type="hidden" name="product_idx_arr" value="<?=$product_idx?>"/>
					</form>
						
						<!--<div style="text-align:right;margin-top:10px;padding-right:10px;">
							<a href="javascript:go_tot_del();" class="btn_red">선택삭제</a>
							<a href="javascript:go_tot_stop();" class="btn_green">선택 정지</a>
							<a href="javascript:go_tot_start();" class="btn_blue">선택 활성화</a>
						</div>-->

						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>
					</div>
				</div>
		<!-- content 종료 -->
	</div>
</div>

<script>
	
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
	
	function product_menu_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="/pro_inc/product_menu_select_1.php?cate_code1="+tmp+"&fm=s_mem&fname=s_sect2";
	}

	function product_menu_sel_2(z){
		var cate_code1 = $("#s_sect1").val();
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="/pro_inc/product_menu_select_2.php?cate_code1="+cate_code1+"&cate_code2="+tmp+"&fm=s_mem&fname=v_sect";
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
