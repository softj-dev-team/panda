<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
		
	$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // 일반, 경매
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
	$keyword = trim(sqlfilter($_REQUEST['keyword']));
	
	$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
	$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 

	$total_param = "bmenu=".$bmenu."&smenu=".$smenu."&v_sect=".$v_sect."&date_s=".$date_s."&date_e=".$date_e."&s_sect1=".$s_sect1."&s_sect2=".$s_sect2."&s_protype=".urlencode($s_protype_arr)."&s_salemtd=".$s_salemtd."&s_salests=".$s_salests."&keyword=".$keyword."&s_cnt=".$s_cnt."&s_order=".$s_order;

	if(!$v_sect){
		$v_sect = "general";
	}

	$where = " and is_del='N' and order_num in (select order_num from order_member where 1 and is_del='N' and orderstat='can')";
	
	if($date_s){ 
		$where .= " and order_num in (select order_num from order_member where 1 and is_del='N' and orderstat='can' and substring(cancel_date,1,10) >= '".$date_s."')";
	}
	if($date_e){ 
		$where .= " and order_num in (select order_num from order_member where 1 and is_del='N' and orderstat='can' and substring(cancel_date,1,10) <= '".$date_e."')";
	}
	if($s_salests){
		$where .= " and sale_status = '".$s_salests."'";
	}

	//if ($field && $keyword){
	if ($keyword){
		$where .= " and (member_idx_sale in (select idx from member_info where 1 and del_yn='N' and user_nick like '%".$keyword."%') or member_idx_buy in (select idx from member_info where 1 and del_yn='N' and user_nick like '%".$keyword."%'))";
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
		$order_by = " order by idx desc";
	}

	$query = "select *,(select product_title from product_info where 1 and is_del='N' and idx=product_sale_history.product_idx) as product_title,(select user_nick from member_info where 1 and del_yn='N' and idx=product_sale_history.member_idx_sale) as user_sale,(select user_nick from member_info where 1 and del_yn='N' and idx=product_sale_history.member_idx_buy) as user_buy,(select orderstat from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as orderstat,(select payment_date from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as payment_date,(select pay_sect_1 from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as pay_sect_1,(select price_total_usd from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as price_total_usd,(select price_total_won from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as price_total_won,(select cancel_date from order_member where 1 and is_del='N' and order_num=product_sale_history.order_num) as cancel_date from product_sale_history where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
	//echo "query = ".$query."<br>";
	$result = mysqli_query($gconnet,$query);

	$query_cnt = "select idx from product_sale_history where 1 ".$where;
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
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/sale_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>판매/정산 관리</li>
						<li>판매 취소관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>판매취소 리스트</h3>
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
						<input type="hidden" name="v_sect" id="v_sect" value="<?=$v_sect?>"/>
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
							<th scope="row">조회기간</th>
							<td colspan="5">
								<input type="text" autocomplete="off" readonly name="date_s" id="date_s" style="width:10%;" class="datepicker" value="<?=$date_s?>"> ~ <input type="text" autocomplete="off" readonly name="date_e" id="date_e" style="width:10%;" class="datepicker" value="<?=$date_e?>">
							</td>
						</tr>
						<tr>
							<th scope="row">판매자/구매자</th>
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
								<col style="width:5%;">
								<col style="width:14%;">
								<col style="width:9%">
								<col style="width:9%;">
								<col style="width:9%;">
								<col style="width:9%;">
								<col style="width:9%;">
								<col style="width:9%;">
								<col style="width:9%;">
								<col style="width:9%;">
								<col style="width:9%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">No</th>
									<th scope="col">작품명</th>
									<th scope="col">판매자</th>
									<th scope="col">판매방식</th>
									<th scope="col">구매자</th>
									<th scope="col">결제일</th>
									<th scope="col">결제수단</th>
									<th scope="col">결제금액</th>
									<th scope="col">상태</th>
									<th scope="col">취소일</th>
									<th scope="col">취소구분</th>
								</tr>
							</thead>
							<tbody>
							<? if($num==0) { ?>
								<tr>
									<td colspan="10" height="40">판매취소 이력이 없습니다.</strong></td>
								</tr>
							<? } ?>
							<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);
								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
							?>
								<tr>
									<td><?=$listnum?></td>
									<td><a href="javascript:go_sale_view('<?=$row['idx']?>');"><?=$row['product_title']?></a></td>
									<td><a href="javascript:go_sale_view('<?=$row['idx']?>');"><?=$row['user_sale']?></a></td>
									<td><a href="javascript:go_sale_view('<?=$row['idx']?>');"><?=$arr_sale_method[$row['sale_method']]?></a></td>
									<td><a href="javascript:go_sale_view('<?=$row['idx']?>');"><?=$row['user_buy']?></a></td>
									<td><a href="javascript:go_sale_view('<?=$row['idx']?>');"><?=substr($row['payment_date'],0,10)?></a></td>
									<td><a href="javascript:go_sale_view('<?=$row['idx']?>');"><?=get_payment_method($row['pay_sect_1'])?></a></td>
									<td><a href="javascript:go_sale_view('<?=$row['idx']?>');">
									<?if($row['price_total_won']){?>
										₩ <?=number_format($row['price_total_won'])?>
									<?}?>
									<?if($row['price_total_usd']){?>
										 &nbsp; USD $ <?=$row['price_total_usd']?>
									<?}?>
									</a></td>
									<td><a href="javascript:go_sale_view('<?=$row['idx']?>');"><?=get_order_status($row['orderstat'])?></a></td>
									<td><a href="javascript:go_sale_view('<?=$row['idx']?>');"><?=substr($row['cancel_date'],0,10)?></a></td>
									<td><a href="javascript:go_sale_view('<?=$row['idx']?>');">카드취소</a></td>
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
	
	function go_sale_view(idx){
		//location.href = 
		window.open("../member/popup_sale_history_view.php?idx="+idx+"","saleview", "top=100,left=100,scrollbars=yes,resizable=no,width=1080,height=600");
	}

</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
