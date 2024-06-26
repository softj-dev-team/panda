<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
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

if($v_sect == "com"){
	$pay_str = "입금완료";
	$select_date = "payment_date";
} elseif($v_sect == "pre"){
	$pay_str = "입금대기";
	$select_date = "order_date";
} elseif($v_sect == "reing"){
	$pay_str = "취소신청";
	$select_date = "cancel_ing_date";
} elseif($v_sect == "can"){
	$pay_str = "취소완료";
	$select_date = "cancel_date";
}

//$where = " and is_del='N'";

if($v_sect){
	$where .= " and a.orderstat ='".$v_sect."'";
}

if(!$pageNo){
	$pageNo = 1;
}

$today = get_local_datetime(date("Y-m-d H:i:s"), "Y-m-d");
$point_cur_date = get_gtc_datetime($today." 23:59:59", "Y-m-d H:i:s");
if($s_cate){
	if($s_cate == "today"){ // 오늘
		$where .= " and a.".$select_date." >= '".date("Y-m-d 00:00:00")."' and a.".$select_date." <= '".date("Y-m-d 23:59:59")."' ";

	} elseif($s_cate == "1week"){ // 1 주일전
		$calcu_date =  get_local_datetime(date("Y-m-d 00:00:00",strtotime("-1 week")), "Y-m-d");
		$calcu_date = get_gtc_datetime($calcu_date." 00:00:00", "Y-m-d H:i:s");
		$where .= " and a.".$select_date." >= '".$calcu_date."' and a.".$select_date." <= '".$point_cur_date."' ";

	} elseif($s_cate == "2week"){ // 2 주일전
		$calcu_date =  get_local_datetime(date("Y-m-d 00:00:00",strtotime("-2 week")), "Y-m-d");
		$calcu_date = get_gtc_datetime($calcu_date." 00:00:00", "Y-m-d H:i:s");
		$where .= " and a.".$select_date." >= '".$calcu_date."' and a.".$select_date." <= '".$point_cur_date."' ";

	} elseif($s_cate == "1month"){ // 1 달전
		$calcu_date =  get_local_datetime(date("Y-m-d 00:00:00",strtotime("-1 month")), "Y-m-d");
		$calcu_date = get_gtc_datetime($calcu_date." 00:00:00", "Y-m-d H:i:s");
		$where .= " and a.".$select_date." >= '".$calcu_date."' and a.".$select_date." <= '".$point_cur_date."' ";

	} elseif($s_cate == "3month"){ // 3 달전
		$calcu_date =  get_local_datetime(date("Y-m-d 00:00:00",strtotime("-3 month")), "Y-m-d");
		$calcu_date = get_gtc_datetime($calcu_date." 00:00:00", "Y-m-d H:i:s");
		$where .= " and a.".$select_date." >= '".$calcu_date."' and a.".$select_date." <= '".$point_cur_date."' ";

	} elseif($s_cate == "6month"){ // 6 달전
		$calcu_date =  get_local_datetime(date("Y-m-d 00:00:00",strtotime("-6 month")), "Y-m-d");
		$calcu_date = get_gtc_datetime($calcu_date." 00:00:00", "Y-m-d H:i:s");
		$where .= " and a.".$select_date." >= '".$calcu_date."' and a.".$select_date." <= '".$point_cur_date."' ";

	}  elseif($s_cate == "6month"){ // 12 달전
		$calcu_date =  get_local_datetime(date("Y-m-d 00:00:00",strtotime("-12 month")), "Y-m-d");
		$calcu_date = get_gtc_datetime($calcu_date." 00:00:00", "Y-m-d H:i:s");
		$where .= " and a.".$select_date." >= '".$calcu_date."' and a.".$select_date." <= '".$point_cur_date."' ";
	}

}else{
	if($s_date){
		$where .= " and a.".$select_date." >= '".get_gtc_datetime($s_date." 00:00:00", "Y-m-d H:i:s")."' ";
	}
	if($e_date){
		$where .= " and a.".$select_date." <= '".get_gtc_datetime($e_date." 23:59:59", "Y-m-d H:i:s")."' ";
	}
}

if($s_pay_type){
	$where .= " and a.pay_sect_1 = '".$s_pay_type."'";
	//$where .= " and order_num in (select order_num from order_member where 1 and pay_sect_1 = '".$s_pay_type."')";
}

if($v_cate){
	//$where .= " and order_num in (select order_num from ticket_payment_info where 1 and ad_info_idx in (select idx from ad_info where 1 and member_idx='".$v_cate."'))";
	//$where .= " and ad_info_idx in (select idx from ad_info where 1 and member_idx='".$v_cate."')";
	$where .= " and member_idx='".$v_cate."'";
}

if ($field && $keyword){
	if($field == "pro_name"){ // 공모전 제목
		$where .= " and order_num in (select order_num from compet_info where 1 and compet_title like '%".$keyword."%')";
		//$where .= " and ad_info_idx in (select idx from ad_info where 1 and ad_title like '%".$keyword."%')";
	}elseif($field == "ticket_name"){ // 티켓명
		//$where .= " and order_num in (select order_num from ticket_payment_info where 1 and ticket_idx in (select idx from ad_info_ticket where 1 and ticket_name like '%".$keyword."%'))";
		$where .= " and ticket_idx in (select idx from ad_info_ticket where 1 and ticket_name like '%".$keyword."%')";
	} else {
		$where .= " and ".$field." like '%".$keyword."%'";
	}
}

$pageScale = 10;  

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by a.idx desc ";

$query = "select a.*,(select user_id from member_info where 1 and idx=a.member_idx) as buy_id,(select user_name from member_info where 1 and idx=a.member_idx) as buy_name from order_member a where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select a.idx from order_member a where 1 ".$where;
//echo "query_cnt = ".$query_cnt."<br>";
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
		location.href = "order_view.php?order_num="+no+"&<?=$total_param ?>";
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
	<? include $_SERVER["DOCUMENT_ROOT"].""."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"].""."/manage/include/payment_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>결제내역 관리</li>
						<li><?=$pay_str?></li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$pay_str?> 리스트</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="order_list.php">
						<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
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
							<th scope="row">결제방식</th>
							<td colspan="2">
								<select size="1" name="s_pay_type">
									<option value="">선택하세요</option>
									<option value="card_isp" <?=$s_pay_type=="card_isp"?"selected":""?>>신용카드</option>
									<option value="bank_iche" <?=$s_pay_type=="bank_iche"?"selected":""?>>계좌이체</option>
									<option value="pay_virt" <?=$s_pay_type=="pay_virt"?"selected":""?>>가상계좌</option>
									<option value="handphone" <?=$s_pay_type=="handphone"?"selected":""?>>휴대폰 결제</option>
									<!--<option value="refund" <?=$s_pay_type=="refund"?"selected":""?>>적립금 결제</option>-->
								</select>
							</td>
							<th scope="row"><?=$v_sect_date?> 기간</th>
							<td colspan="2">
								<input type="text" name="s_date" style="width:40%;" id="s_date" class="datepicker" value="<?=$s_date?>" readonly> ~ <input type="text" name="e_date" style="width:40%;" id="e_date" class="datepicker" value="<?=$e_date?>" readonly>
							</td>
						</tr>
						<tr>
							<th scope="row">멤버십 신청회원</th>
							<td colspan="2">
								<select name="v_cate" size="1" style="vertical-align:middle;width:80%;">
									<option value="">선택하세요</option>
								<?
								$sub_sql = "select idx,user_name from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and member_type='GEN' and del_yn='N' order by user_name asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
								?>
									<option value="<?=$sub_row[idx]?>" <?=$v_cate==$sub_row[idx]?"selected":""?>><?=$sub_row[user_name]?></option>
								<?}?>		
								</select>
							<th scope="row">조건검색</th>
							<td colspan="2">
								<select name="field" size="1" style="vertical-align:middle;width:40%;">
									<!--<option value="user_id" <?=$field=="user_id"?"selected":""?>>구매자 아이디</option>-->
									<option value="payment_str" <?=$field=="payment_str"?"selected":""?>>멤버십 종류</option>
									<option value="order_email" <?=$field=="order_email"?"selected":""?>>이메일</option>
									<option value="order_cell" <?=$field=="order_cell"?"selected":""?>>휴대전화</option>
									<!--<option value="ticket_name" <?=$field=="ticket_name"?"selected":""?>>티켓명</option>-->
								</select>
								<input type="text" name="keyword" id="keyword" style="width:50%;" value="<?=htmlspecialchars($keyword)?>" >
							</td>
						</tr>
				</form>
				</table>
				<!-- 검색창 종료 -->

				<!-- 엑셀 출력을 위한 전송 폼 시작 -->
			<form name="order_excel_frm" id="order_excel_frm" method="post" action="order_excel_list.php">
			<input type="hidden" name="field" value="<?=$field?>"/>
			<input type="hidden" name="keyword" value="<?=$keyword?>"/>
			<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
			<input type="hidden" name="v_cate" value="<?=$v_cate?>"/>
			<input type="hidden" name="s_date" value="<?=$s_date?>"/>
			<input type="hidden" name="e_date" value="<?=$e_date?>"/>
			<input type="hidden" name="s_pay_type" value="<?=$s_pay_type?>"/>
			<input type="hidden" name="s_pay_sect" value="<?=$s_pay_sect?>"/>
			<input type="hidden" name="s_receipt_ok" value="<?=$s_receipt_ok?>"/>
			<input type="hidden" name="s_taxbill_ok" value="<?=$s_taxbill_ok?>"/>
			<input type="hidden" name="s_mem_sect" value="<?=$s_mem_sect?>"/>
			<input type="hidden" name="s_group" value="<?=$s_group?>"/>
			</form>
			<!-- 엑셀 출력을 위한 전송 폼 종료 -->

					<div class="align_r mt20">
						<button class="btn_search" onclick="s_mem.submit();">검색</button>
						<button class="btn_down" onclick="order_excel_frm.submit();">엑셀다운로드</button>
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

			<table class="search_list">

				<thead>
					<tr>
						<!--<th width="10%">주문번호</th>-->
						<th width="4%">번호</th>
						<th width="10%">회원 아이디</th>
						<th width="10%">회원명</th>
						<th width="12%">연락처</th>
						<th width="18%">멤버십 종류</th>
						<th width="12%">결제수단</th>
						<th width="12%">전체금액</th>
						<th width="12%">결제금액</th>
					<?if($v_sect == "com"){?>
						<th width="10%">입금완료일</th>
					<?}elseif($v_sect == "pre"){?>
						<th width="10%">결제요청일</th>
					<?}elseif($v_sect == "reing"){?>
						<th width="10%">취소요청일</th>	
					<?}elseif($v_sect == "can"){?>
						<th width="10%">취소완료일</th>	
					<?}?>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>결제내역이 없습니다.</strong></td>
					</tr>
				<? } ?>

				<?
				for ($ikm=0; $ikm<mysqli_num_rows($result); $ikm++){
					$row = mysqli_fetch_array($result);
					
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $ikm;

					$payment_ticket_sql = "select idx from ticket_payment_info where 1 and is_del = 'N' and order_num='".$row['order_num']."'";
					$payment_ticket_query = mysqli_query($gconnet,$payment_ticket_sql);
					$payment_ticket_cnt = mysqli_num_rows($payment_ticket_query);
					
				?>
					<tr>
						<td><a href="javascript:go_view('<?=$row['order_num']?>');"><?=$listnum?></a></td>
						<td><a href="javascript:go_view('<?=$row['order_num']?>');"><?=$row['user_id']?></a></td>
						<td><a href="javascript:go_view('<?=$row['order_num']?>');"><?=$row['order_name']?></a></td>
						<td><a href="javascript:go_view('<?=$row['order_num']?>');"><?=$row['order_cell']?></a></td>
						<td><a href="javascript:go_view('<?=$row['order_num']?>');"><?=$row['payment_str']?></a></td>
						<td><a href="javascript:go_view('<?=$row['order_num']?>');"><?=get_payment_method($row['pay_sect_1'])?></a></td>
						<td><a href="javascript:go_view('<?=$row['order_num']?>');"><?=number_format($row['price_total_org'])?> </a></td>
						<td><a href="javascript:go_view('<?=$row['order_num']?>');"><?=number_format($row['price_total'])?></a></td>
					<?if($v_sect == "com"){?>
						<td><a href="javascript:go_view('<?=$row['order_num']?>');"><?=substr($row['payment_date'],0,10)?></a></td>
					<?}elseif($v_sect == "pre"){?>
						<td><a href="javascript:go_view('<?=$row['order_num']?>');"><?=substr($row['order_date'],0,10)?></a></td>
					<?}elseif($v_sect == "reing"){?>
						<td><a href="javascript:go_view('<?=$row['order_num']?>');"><?=substr($row['cancel_ing_date'],0,10)?></a></td>
					<?}elseif($v_sect == "can"){?>
						<td><a href="javascript:go_view('<?=$row['order_num']?>');"><?=substr($row['cancel_date'],0,10)?></a></td>
					<?}?>
					</tr>

			<?
					$total_price_total_org = $total_price_total_org+$row['price_total_org'];
					$total_pay_refund = $total_pay_refund+$row['pay_refund'];
					$total_price_total = $total_price_total+$row['price_total'];
				} // 루프 종료 
			?>	
			
				<tr>
					<td style="height:29px; text-align:center; border:1px solid #cdcdcd; border-right:0 none; background:#eee">총 계</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td><?=number_format($total_price_total_org)?> 원</td>
					<td><?=number_format($total_price_total)?> 원</td>
					<td></td>
				</tr>
			</tbody>
			</table>

			<!--<div class="table_btn align_l mt20 pl20">
							<button>선택 가입승인</button>
							<button>선택 탈퇴처리</button>
						</div>-->
						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/paging.php";?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>