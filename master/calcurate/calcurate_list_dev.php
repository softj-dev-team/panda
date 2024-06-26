<?php include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?php include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<?php include $_SERVER["DOCUMENT_ROOT"].""."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?php
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

$v_sect =  trim(sqlfilter($_REQUEST['v_sect']));
$v_cate =  trim(sqlfilter($_REQUEST['v_cate']));
$s_date =  trim(sqlfilter($_REQUEST['s_date']));
$e_date =  trim(sqlfilter($_REQUEST['e_date']));
$s_pay_type =  trim(sqlfilter($_REQUEST['s_pay_type']));
$s_pay_sect =  trim(sqlfilter($_REQUEST['s_pay_sect']));
$s_receipt_ok =  trim(sqlfilter($_REQUEST['s_receipt_ok']));
$s_taxbill_ok =  trim(sqlfilter($_REQUEST['s_taxbill_ok']));
$s_mem_sect =  trim(sqlfilter($_REQUEST['s_mem_sect'])); // 주문자 구분
$s_group = sqlfilter($_REQUEST['s_group']); // 입점업체

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.urlencode($keyword).'&v_sect='.$v_sect.'&v_cate='.$v_cate.'&s_date='.$s_date.'&e_date='.$e_date.'&s_pay_type='.$s_pay_type.'&s_pay_sect='.$s_pay_sect.'&s_receipt_ok='.$s_receipt_ok.'&s_taxbill_ok='.$s_taxbill_ok.'&s_mem_sect='.$s_mem_sect.'&s_group='.$s_group;

if(!$pageNo){
	$pageNo = 1;
}

if($v_sect == "total"){
	$pay_str = "전체";
	$v_sect_date = "결제일자";
	$payment_date_cate = "payment_date";
	$payment_orderby = " order by payment_date desc";
	$where .= "";
} elseif($v_sect == "pre"){
	$pay_str = "현장결제 주문";
	$v_sect_date = "주문일자";
	$payment_date_cate = "order_date";
	$payment_orderby = " order by order_date desc";
	$where .= " and orderstat = 'pre'";
} elseif($v_sect == "com"){
	$pay_str = "선불결제 주문";
	$v_sect_date = "주문일자";
	$payment_date_cate = "payment_date";
	$payment_orderby = " order by payment_date desc";
	$where .= " and orderstat = 'com'";
} elseif($v_sect == "can"){
	$pay_str = "주문취소";
	$v_sect_date = "취소일자";
	$payment_date_cate = "cancel_date";
	$payment_orderby = " order by cancel_date desc";
	$where .= " and orderstat = 'can'";
}

if($s_date || $e_date){ // 범위지정으로 시작일, 혹은 종료일
	$s_cal_date = $s_date." 00:00:00";
	$e_cal_date = $e_date." 23:59:59";
	
	if($s_date){
	$where .= " and ".$payment_date_cate." >= '".$s_cal_date."' ";
	}
	
	if($e_date){
	$where .= " and ".$payment_date_cate." <= '".$e_cal_date."' ";
	}
}

if($s_group){
	$where .= " and member_idx in (select idx from member_info where 1 and chuchun_idx='".$s_group."')";
}

if($v_cate){
	$where .= " and orderstat = '".$v_cate."' ";
}

/*if($s_pay_type){
	if($s_pay_type == "kcard"){
		$where .= " and pay_sect_1 = 'card_isp'";
	} elseif($s_pay_type == "kiche"){
		$where .= " and pay_sect_1 = 'bank_iche'";
	} elseif($s_pay_type == "kvirt"){
		$where .= " and pay_sect_1 = 'pay_virt'";
	} elseif($s_pay_type == "bankw"){
		$where .= " and pay_sect_1 = 'bankw'";
	} else {
		$where .= " and pay_type = '".$s_pay_type."' ";
	}
}*/

if($s_pay_type){
	$where .= " and pay_type = '".$s_pay_type."' ";
}

if($s_receipt_ok){
	$where .= " and receipt_ok = '".$s_receipt_ok."' ";
}

if($s_taxbill_ok){
	$where .= " and taxbill_ok = '".$s_taxbill_ok."' ";
}

if($s_pay_sect == "Y"){
	$where .= " and order_num in (select order_num from lecture_calendar where 1) ";
} elseif($s_pay_sect == "N"){
	$where .= " and order_num not in (select order_num from lecture_calendar where 1) ";
}

/*
if($s_mem_sect == "11"){ // 비회원 주문검색 
	$where .= " and ( user_id IS NULL or user_id = '' ) ";
} elseif($s_mem_sect == "12"){ // 회원 주문검색 
	$where .= " and user_id != '' ";
}*/

if ($field && $keyword){
	if($field == "order_num"){
		$where .= "and order_num like '%".$keyword."%'";
	}else if($field == "order_name"){
		$where .= "and order_name like '%".$keyword."%'";
	}else if($field == "user_id"){
		//$where .= "and user_id like '%".$keyword."%'";
		$where .= "and member_idx in (select idx from member_info where 1 and user_id like '%".$keyword."%')";
	}

}

$pageScale = 20; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = $payment_orderby;

$query = "select *,(select level_name from curri_level_set where 1 and level_code in (select curri_level from lecture_regist where 1 and order_num=order_member.order_num)) as level_name,(select week_type from lecture_regist where 1 and order_num=order_member.order_num order by idx desc limit 0,1) as week_type,(select curri_period from lecture_regist where 1 and order_num=order_member.order_num order by idx desc limit 0,1) as curri_period,(select user_nick from member_info where 1 and idx=order_member.member_idx) as user_nick,(select user_id from member_info where 1 and idx=order_member.member_idx) as order_id from order_member where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo $query;

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from order_member where 1=1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;

?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	$(document).ready(function(){
		$(".datepicker").datepicker({
			showMonthAfterYear: true,
			changeYear: true,
			changeMonth: true,
			dateFormat: "yy-mm-dd",
			dayNames: ["일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일"],
			dayNamesMin: ["일", "월", "화", "수", "목", "금", "토"],
			dayNamesShort: ["일", "월", "화", "수", "목", "금", "토"],
			MonthNames: ["1월", "2월", "3월", "4월", "5월", "6월", "7월", "8월", "9월", "10월", "11월", "12월"],
			MonthNamesShort: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"]
		});
	});

	function go_view(no){
		location.href = "order_view.php?order_num="+no+"&<?php echo $total_param ?>";
	}
	
	function go_list(){
		location.href = "order_list.php";
	}
	
	
	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}

var check  = 0;                                                                            //체크 여부 확인
function CheckAll(){                
	var boolchk;                                                                              //boolean형 변수 
	var chk = document.getElementsByName("order_num[]")                 //체크박스의 name값
		
		if(check){ check=0; boolchk = false; }else{ check=1; boolchk = true; }    //체크여부에 따른 true/false 설정
			
			for(i=0; i<chk.length;i++){                                                                    
				chk[i].checked = boolchk;                                                                //체크되어 있을경우 설정변경
			}

}

function go_tot_send() {
	var check = chkFrm('frm');
		if(check) {
			
			if(confirm('ERP 로 전송된 주문내역은 전송취소가 불가능 하므로 신중하게 선택해 주십시오.')){
				if(confirm('선택하신 주문을 ERP 로 전송하시겠습니까?')){
					document.frm.send_mode.value = "totsend";
					frm.submit();
				}
			}

		} else {
			false;
		}
}

function cate_sel_1(z){
				
		var tmp = z.options[z.selectedIndex].value; 
		//alert(tmp);
		_fra_admin.location.href="head_select_1.php?cate_code1="+tmp+"&fm=s_mem&fname=scServer";
			
}

function go_excel() {
	var check = chkFrm('order_excel_frm');
		if(check) {
			order_excel_frm.submit();
		} else {
			false;
		}
}
//-->
</SCRIPT>

<body>
<div id="wrap" class="skin_type01">
	<?php include $_SERVER["DOCUMENT_ROOT"].""."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<?php include $_SERVER["DOCUMENT_ROOT"].""."/master/include/calcurate_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>정산 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>정산관리</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="calcurate_list.php">
						<input type="hidden" name="v_sect" value="<?php echo $v_sect?>"/>
						<input type="hidden" name="bmenu" value="<?php echo $bmenu?>"/>
						<input type="hidden" name="smenu" value="<?php echo $smenu?>"/>
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
							<th scope="row">업체별 보기</th>
							<td colspan="2">
								<select name="s_group" style="vertical-align:middle;">
									<option value="">선택하세요</option>
									<?php
										$sub_sql = "select idx,com_name from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and member_type='PAT' order by com_name asc";
										$sub_query = mysqli_query($gconnet,$sub_sql);
										$sub_cnt = mysqli_num_rows($sub_query);

										for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
											$sub_row = mysqli_fetch_array($sub_query);
									?>
										<option value="<?php echo $sub_row[idx]?>" <?php echo $s_group==$sub_row[idx]?"selected":""?>><?php echo $sub_row[com_name]?></option>
									<?php }?>	
								</select>
							</td>
							<th scope="row">기간별 보기</th>
							<td colspan="2">
								<input type="text" name="s_date" style="width:40%;" id="s_date" value="<?php echo $s_date?>" readonly> ~ <input type="text" name="e_date" style="width:40%;" id="e_date" value="<?php echo $e_date?>" readonly>
							</td>
						</tr>
					</form>
				</table>
				<!-- 검색창 종료 -->

				<!-- 엑셀 출력을 위한 전송 폼 시작 -->
			<form name="order_excel_frm" id="order_excel_frm" method="post" action="order_excel_list.php">
			<input type="hidden" name="mode" value="ser">
			<input type="hidden" name="v_sect" value="<?php echo $v_sect?>"/>

			<input type="hidden" name="v_cate" value="<?php echo $v_cate?>"/>
			<input type="hidden" name="s_pay_type" value="<?php echo $s_pay_type?>"/>
			<input type="hidden" name="s_pay_sect" value="<?php echo $s_pay_sect?>"/>
			<input type="hidden" name="s_receipt_ok" value="<?php echo $s_receipt_ok?>"/>
			<input type="hidden" name="s_taxbill_ok" value="<?php echo $s_taxbill_ok?>"/>
			<input type="hidden" name="s_date" value="<?php echo $s_date?>"/>
			<input type="hidden" name="e_date" value="<?php echo $e_date?>"/>
			<input type="hidden" name="s_group" value="<?php echo $s_group?>"/>
			<input type="hidden" name="s_mem_sect" value="<?php echo $s_mem_sect?>"/>
			<input type="hidden" name="field" value="<?php echo $field?>"/>
			<input type="hidden" name="keyword" value="<?php echo htmlspecialchars($keyword)?>"/>
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
							<p class="txt">검색결과 총 <span><?php echo $num?></span>건</p>
							<div class="btn_wrap">
								<!--<select id="s_cnt_set" onchange="go_cnt_set(this)">
									<option value="10" <?php echo $s_cnt=="10"?"selected":""?>>10개보기</option>
									<option value="20" <?php echo $s_cnt=="20"?"selected":""?>>20개보기</option>
									<option value="30" <?php echo $s_cnt=="30"?"selected":""?>>30개보기</option>
									<option value="40" <?php echo $s_cnt=="40"?"selected":""?>>40개보기</option>
								</select>
								<select id="s_order_set" onchange="go_order_set(this)">
									<option value="1" <?php echo $s_order=="1"?"selected":""?>>회원가입일 최신순</option>
									<option value="2" <?php echo $s_order=="2"?"selected":""?>>회원가입일 오래된순</option>
									<option value="3" <?php echo $s_order=="3"?"selected":""?>>회원명 올림차순</option>
									<option value="4" <?php echo $s_order=="4"?"selected":""?>>회원명 내림차순</option>
								</select>
								<button class="btn_del" onclick="go_tot_del();"><span>선택삭제</span></button>-->
							</div>
						</div>
					<!-- 목록 옵션 종료 -->

			<table class="search_list">

				<thead>
					<tr>
						<!--<th width="10%">주문번호</th>-->
						<th width="5%">번호</th>
						<th width="15%">업체명</th>
						<th width="10%">주문횟수</th>
						<th width="13%">주문금액</th>
						<th width="13%">카드/현금 결제</th>
						<th width="11%">포인트 결제</th>
						<th width="11%">실 결제금액</th>
						<th width="11%">업체 수수료율</th>
						<th width="11%">업체 지급금액</th>
					</tr>
				</thead>
				<tbody>
				<?php if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>정산할 내역이 없습니다.</strong></td>
					</tr>
				<?php } ?>

				<?php
				for ($ikm=0; $ikm<mysqli_num_rows($result); $ikm++){
					$row = mysqli_fetch_array($result);
					
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $ikm;

							switch ($row[pay_type]) {
									case "stripe" : 
									$pay_type = "Stripe";
									break;
									case "paypal" : 
									$pay_type = "Paypal";
									break;
									case "mgram" : 
									$pay_type = "MoneyGram";
									break;
									case "wsunion" : 
									$pay_type = "WESTERN UNION";
									break;
									case "" : 
									$pay_type = "";
									break;
								}	
								
								$calendar_sql = "SELECT idx FROM  lecture_calendar where 1=1 and order_num = '".$row[order_num]."' ";
								$calendar_query = mysqli_query($gconnet,$calendar_sql);

								if(mysqli_num_rows($calendar_query) > 0){
									$calendar_yn = "수업승인";
								} else {
									$calendar_yn = "미승인";
								}

				?>
				
								
					<tr>
						<!--<td><a href="javascript:go_view('<?php echo $row[order_num]?>');"><?php echo $row[order_num]?></a></td>-->
						<td><a href="javascript:go_view('<?php echo $row[order_num]?>');"><?php echo $listnum?></a></td>
						<td><?php echo substr($row['order_date'],0,10)?></td>
						<td><?php echo substr($row['order_date'],0,10)?></td>
						<td><?php echo substr($row['order_date'],0,10)?></td>
						<td><?php echo substr($row['order_date'],0,10)?></td>
						<td><?php echo substr($row['order_date'],0,10)?></td>
						<td><?php echo substr($row['order_date'],0,10)?></td>
						<td><?php echo substr($row['order_date'],0,10)?></td>
						<td><?php echo substr($row['payment_date'],0,10)?></td>
					</tr>

			<?php
					$total_price_total_org = $total_price_total_org+$row[price_total_org];
					$total_price_discount_def = $total_price_discount_def+$row[price_discount_def];
					$total_pay_point = $total_pay_point+$row[pay_point];
					$total_pay_refund = $total_pay_refund+$row[pay_refund];
					$total_price_total = $total_price_total+$row[price_total];
				} // 루프 종료 
			?>	
			
				<tr>
					<td style="height:29px; text-align:center; border:1px solid #cdcdcd; border-right:0 none; background:#eee">총 계</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
			</table>

			<!--<div class="table_btn align_l mt20 pl20">
							<button>선택 가입승인</button>
							<button>선택 탈퇴처리</button>
						</div>-->
						<div class="pagination mt0">
							<?php include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/paging.php";?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- content 종료 -->
	</div>
</div>

<script type="text/javascript">
	$(function() {
		$( "#s_date" ).datepicker({
			changeYear:true,
			changeMonth:true,
			dateFormat:'yy-mm-dd',
			showMonthAfterYear:true,
			constrainInput: true,
			dayNamesMin: ['일','월', '화', '수', '목', '금', '토' ],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
		});

		$( "#e_date" ).datepicker({
			changeYear:true,
			changeMonth:true,
			dateFormat:'yy-mm-dd',
			showMonthAfterYear:true,
			constrainInput: true,
			dayNamesMin: ['일','월', '화', '수', '목', '금', '토' ],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
		});
	});
</script>

<?php include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>