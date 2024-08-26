<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 가맹점, 지점
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 일반, VIP
$s_level = sqlfilter($_REQUEST['s_level']); // 가맹점등급
$s_gender = sqlfilter($_REQUEST['s_gender']); // 성별
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); // 로그인 구분
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); // 추천인 (지점) 별
$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_cnt='.$s_cnt.'&s_order='.$s_order;

$where = " and a.is_del='N' and a.contents_tbname='member_point' and a.member_idx in (select idx from member_info where 1 and memout_yn not in ('Y','S') and del_yn='N' and member_type in ('PAT'))";

if(!$pageNo){
	$pageNo = 1;
}

if(!$s_cnt){
	$s_cnt = 10; // 기본목록 10개
}

if(!$s_order){
	$s_order = 1; 
}

if($v_sect == "2"){ 
	$where .= " and orderstat = 'com'";
} elseif($v_sect == "1"){ 
	$where .= " and orderstat = 'pre'";
}

if($s_gender){ 
	$where .= " and member_gubun = '".$s_gender."'";
}
if($s_gubun){ // 가입시작일
	$where .= " and substring(order_date,1,10) >= '".$s_gubun."'";
}
if($s_level){ // 가입종료일
	$where .= " and substring(order_date,1,10) <= '".$s_level."'";
}

if ($field && $keyword){
	if($field == "com_name"){
		$where .= " and member_idx in (select member_idx from member_info_company where 1 and is_del='N' and ".$field." like '%".$keyword."%')";
	} else {
		$where .= " and ".$field." like '%".$keyword."%'";
	}
}

$pageScale = $s_cnt;  
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

if($s_order == 1){
	$order_by = " order by order_date desc ";
} elseif($s_order == 2){
	$order_by = " order by wdate asc ";
} elseif($s_order == 3){
	$order_by = " order by user_name asc ";
} elseif($s_order == 4){
	$order_by = " order by user_name desc ";
}

$query = "select *,(select com_name from member_info_company where 1 and is_del='N' and member_idx=a.member_idx) as com_name,(select com_homep from member_info_company where 1 and is_del='N' and member_idx=a.member_idx) as com_homep from order_member a where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
//echo "<br><br>쿼리 = ".$query."<br><Br>";
$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from order_member a where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;

?>
<script type="text/javascript">
<!--	 
	function go_view(no){
		location.href = "member_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "member_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "member_write.php?<?=$total_param?>";
	}

	function go_regist_add(no){
		location.href = "member_write_add.php?member_idx="+no+"&<?=$total_param?>";
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
	var chk = document.getElementsByName("order_idx[]")                 //체크박스의 name값
		
		if(check){ check=0; boolchk = false; }else{ check=1; boolchk = true; }    //체크여부에 따른 true/false 설정
			
			for(i=0; i<chk.length;i++){                                                                    
				chk[i].checked = boolchk;                                                                //체크되어 있을경우 설정변경
			}

}

function go_tot_del() {
	var check = chkFrm('frm');
	if(check) {
		if(confirm('선택하신 충전내역을 삭제 하시겠습니까?')){
			if(confirm('삭제하신 충전내역은 복구할 수 없습니다. 정말 삭제 하시겠습니까?')){
				frm.action = "member_payment_action_del.php";
				frm.submit();
			}
		}
	} else {
		false;
	}
}

function go_payment_complete(order_num){
	if(confirm('선택하신 결제신청의 입금상태를 입금완료로 변경하시겠습니까?')){
		if(confirm('입금완료 변경후에는 이전 상태로 복구할 수 없습니다. 정말 변경 하시겠습니까?')){
			_fra_admin.location.href = "member_payment_action_paycom.php?order_num="+order_num+"";
		}
	}
}

function go_tot_stop() {
	var check = chkFrm('frm');
	if(check) {
		if(confirm('선택하신 가맹점의 로그인을 정지 상태로 설정하시겠습니까?')){
			frm.action = "member_list_action_stop.php";
			frm.submit();
		}
	} else {
		false;
	}
}

function go_tot_start() {
	var check = chkFrm('frm');
	if(check) {
		if(confirm('선택하신 가맹점의 로그인을 활성화 상태로 설정하시겠습니까?')){
			frm.action = "member_list_action_start.php";
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
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/partner_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>가맹점관리</li>
						<li>충전관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>충전신청 리스트</h3>
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
							<th scope="row">요청일시</th>
							<td colspan="5">
								<input type="text" autocomplete="off" readonly name="s_gubun" id="s_gubun" style="width:20%;" class="datepicker"  value="<?=$s_gubun?>"> ~ <input type="text" autocomplete="off" readonly name="s_level" id="s_level" style="width:20%;" class="datepicker" value="<?=$s_level?>">
							</td>
						</tr>
						<tr>
							<th scope="row">승인여부</th>
							<td colspan="2">
								<input type="radio" name="v_sect" id="v_sect_1" value="" <?=$v_sect==""?"checked":""?>> 전체
								<input type="radio" name="v_sect" id="v_sect_2" value="2" <?=$v_sect=="2"?"checked":""?>> 승인
								<input type="radio" name="v_sect" id="v_sect_3" value="1" <?=$v_sect=="1"?"checked":""?>> 미승인
							</td>
							<th scope="row">조건검색</th>
							<td colspan="2">
								<select name="field" size="1" style="vertical-align:middle;width:40%;">
									<option value="">검색기준</option>
									<option value="user_id" <?=$field=="user_id"?"selected":""?>>아이디</option>
									<option value="order_name" <?=$field=="order_name"?"selected":""?>>담당자명</option>
									<option value="com_name" <?=$field=="com_name"?"selected":""?>>회사명</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:50%;" value="<?=$keyword?>">
							</td>
						</tr>
					</form>
				</table>
				<!-- 검색창 종료 -->
		
			<!-- 엑셀 출력을 위한 전송 폼 시작 -->
			<form name="order_excel_frm" id="order_excel_frm" method="post" action="member_excel_list.php">
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
							<p class="txt">검색결과 총 <span><?=$num?></span>건</p>
							<div class="btn_wrap">
								<select id="s_cnt_set" onchange="go_cnt_set(this)">
									<option value="10" <?=$s_cnt=="10"?"selected":""?>>10개보기</option>
									<option value="30" <?=$s_cnt=="30"?"selected":""?>>30개보기</option>
									<option value="50" <?=$s_cnt=="50"?"selected":""?>>50개보기</option>
									<option value="100" <?=$s_cnt=="100"?"selected":""?>>100개보기</option>
								</select>
								<!--<select id="s_order_set" onchange="go_order_set(this)">
									<option value="1" <?=$s_order=="1"?"selected":""?>>가맹점가입일 최신순</option>
									<option value="2" <?=$s_order=="2"?"selected":""?>>가맹점가입일 오래된순</option>
									<option value="3" <?=$s_order=="3"?"selected":""?>>가맹점명 올림차순</option>
									<option value="4" <?=$s_order=="4"?"selected":""?>>가맹점명 내림차순</option>
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
								<col style="width:4%;">

								<col style="width:8%;">
								<col style="width:9.5%;">
								<col style="width:9.5%;">
								<col style="width:9.5%;">
								<col style="width:9.5%;">

								<col style="width:9.5%;">
								<col style="width:9.5%;">
								<col style="width:8%;">
								<col style="width:9.5%;">
								<col style="width:9.5%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col"><input type="checkbox" id="" name="checkNum" onclick="javascript:CheckAll()"></th>
									<th scope="col">번호</th>

									<th scope="col">상태</th>
									<th scope="col">가맹점 ID</th>
									<th scope="col">회원명</th>
									<th scope="col">회사명</th>
									<th scope="col">개별도메인</th>
									
									<th scope="col">결제방식</th>
									<th scope="col">입금자명</th>
									<th scope="col">충천액</th>
									<th scope="col">요청일시</th>
									<th scope="col">승인일시</th>
								</tr>
							</thead>
							<tbody>
						<? if($num==0) { ?>
							<tr>
								<td colspan="12" height="40">충천신청한 가맹점이 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);

								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

								if($i == mysqli_num_rows($result)-1){
									$member_idx .= $row['idx'];
								} else {
									$member_idx .= $row['idx'].",";
								}

						?>
						<tr>
							<td><input type="checkbox" name="order_idx[]" id="order_idx[]" value="<?=$row["idx"]?>" required="yes" message="충전내역"/></td>
							<td><?=$listnum?></td>
							
							<td><!--<a href="javascript:go_view('<?=$row['idx']?>');">--><?=get_order_status($row['orderstat'])?><!--</a>--></td>
							<td><!--<a href="javascript:go_view('<?=$row['idx']?>');">--><?=$row['user_id']?><!--</a>--></td>
							<td><!--<a href="javascript:go_view('<?=$row['idx']?>');">--><?=$row['order_name']?><!--</a>--></td>
							<td><!--<a href="javascript:go_view('<?=$row['idx']?>');">--><?=$row['com_name']?><!--</a>--></td>
							<td><!--<a href="javascript:go_view('<?=$row['idx']?>');">--><?=$row['com_homep']?><!--</a>--></td>
							
							<td><!--<a href="javascript:go_view('<?=$row['idx']?>');">--><?=get_payment_method($row['pay_sect_1'])?><!--</a>--></td>
							<td><!--<a href="javascript:go_view('<?=$row['idx']?>');">--><?=$row['pay_bank_depositor']?><!--</a>--></td>
							<td><!--<a href="javascript:go_view('<?=$row['idx']?>');">--><?=number_format($row['price_total'])?><!--</a>--></td>
							<td><!--<a href="javascript:go_view('<?=$row['idx']?>');">--><?=$row['order_date']?><!--</a>--></td>
							<td>
							<?if($row['orderstat'] == "pre"){?>
								<a href="javascript:go_payment_complete('<?=$row['order_num']?>');" class="btn_green">입금완료</a>
							<?}else{?>
								<!--<a href="javascript:go_view('<?=$row['idx']?>');">--><?=$row['payment_date']?><!--</a>-->
							<?}?>
							</td>
						</tr>
					<?}?>	
						</tbody>
						</table>

						<input type="hidden" name="member_idx_arr" value="<?=$member_idx?>"/>
					</form>
						
						<div style="text-align:right;margin-top:10px;padding-right:10px;">
							<a href="javascript:go_tot_del();" class="btn_red">선택삭제</a>
							<!--<a href="javascript:go_tot_stop();" class="btn_green">선택 정지</a>
							<a href="javascript:go_tot_start();" class="btn_blue">선택 활성화</a>-->
						</div>

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

</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
