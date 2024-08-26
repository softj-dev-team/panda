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
$cafe_name = sqlfilter($_REQUEST['cafe_name']); // 입점업체
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.urlencode($keyword).'&v_sect='.$v_sect.'&v_cate='.$v_cate.'&s_date='.$s_date.'&e_date='.$e_date.'&s_pay_type='.$s_pay_type.'&s_pay_sect='.$s_pay_sect.'&s_receipt_ok='.$s_receipt_ok.'&s_taxbill_ok='.$s_taxbill_ok.'&s_mem_sect='.$s_mem_sect.'&s_group='.$s_group.'&cafe_name='.$cafe_name;

if(!$pageNo){
	$pageNo = 1;
}

$where = " and is_del='N'";

if($s_date || $e_date){ // 범위지정으로 시작일, 혹은 종료일
	$s_cal_date = $s_date." 00:00:00";
	$e_cal_date = $e_date." 23:59:59";

	if($s_date){
		$where .= " and wdate >= '".$s_cal_date."'";
		$v_s_date = $s_date;
	}

	if($e_date){
		$where .= " and wdate <= '".$e_cal_date."'";
		$v_e_date = $e_date;
	}

}

if($s_group){
	$where .= " and partner_idx='".$s_group."'";
}

if ($field && $keyword){
	$where .= " and ".$field." like '%".$keyword."%'";
}

$pageScale = 100; // 페이지당 100 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by wdate asc ";

if($s_group){
	$query = "select *,(select user_name from member_info where 1 and member_type = 'PAT' and idx=compet_regist_price_info.partner_idx) as user_name,(select user_id from member_info where 1 and member_type = 'PAT' and idx=compet_regist_price_info.partner_idx) as user_id from compet_regist_price_info where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

	//echo $query;

	$result = mysqli_query($gconnet,$query);

	$query_cnt = "select idx from compet_regist_price_info where 1 ".$where;
	$result_cnt = mysqli_query($gconnet,$query_cnt);
	$num = mysqli_num_rows($result_cnt);

	$iTotalSubCnt = $num;
	$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
}

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;

?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	
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

	function go_period(s_cate){
		document.s_mem.s_taxbill_ok.value = s_cate;
		document.s_mem.submit();
	}

	function main_product_pop(){
		//location.href = 
		window.open("main_product.php?type=ing","pro_pro_view", "top=100,left=100,scrollbars=yes,resizable=no,width=1010,height=500");
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
						<li>상금지급 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>지급대기 리스트</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="calcurate_list.php">
						<input type="hidden" name="mode" value="ser">
						<input type="hidden" name="v_sect" value="<?php echo $v_sect?>"/>
						<input type="hidden" name="bmenu" value="<?php echo $bmenu?>"/>
						<input type="hidden" name="smenu" value="<?php echo $smenu?>"/>
						<!--<input type="hidden" name="s_group" id="s_group" value="<?php echo $s_group?>"/>-->
						<input type="hidden" name="cafe_name" id="cafe_name" value="<?php echo $cafe_name?>">
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
							<th scope="row">디자이너 회원</th>
							<td colspan="2">
								<select name="s_group" style="vertical-align:middle;" onchange="s_mem.submit();">
									<option value="">선택하세요</option>
									<?php
										$sub_sql = "select idx,user_name from member_info where 1 and memout_yn != 'Y' and memout_yn != 'S' and member_type='GEN' and del_yn='N' order by user_name asc";
										$sub_query = mysqli_query($gconnet,$sub_sql);
										$sub_cnt = mysqli_num_rows($sub_query);

										for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
											$sub_row = mysqli_fetch_array($sub_query);
									?>
										<option value="<?php echo $sub_row[idx]?>" <?php echo $s_group==$sub_row[idx]?"selected":""?>><?php echo $sub_row[user_name]?></option>
									<?php }?>	
								</select>
								<!--<span id="cafe_name_area"><?php echo $cafe_name?></span>
								<a href="javascript:main_product_pop();" class="btn_green">히얼업 찾기</a>-->
							</td>
							<th scope="row">신청일자 기간별 보기</th>
							<td colspan="2">
								<input type="text" name="s_date" style="width:40%;" id="s_date" value="<?php echo $s_date?>" readonly> ~ <input type="text" name="e_date" style="width:40%;" id="e_date" value="<?php echo $e_date?>" readonly>
							</td>
						</tr>
					</form>
				</table>
				<!-- 검색창 종료 -->

			<!-- 엑셀 출력을 위한 전송 폼 시작 -->
			<form name="order_excel_frm" id="order_excel_frm" method="post" action="calcurate_excel_list.php">
			<input type="hidden" name="mode" value="ser">
			<input type="hidden" name="v_sect" value="<?php echo $v_sect?>"/>
			<input type="hidden" name="s_taxbill_ok" value="<?php echo $s_taxbill_ok?>"/>
			<input type="hidden" name="s_date" value="<?php echo $s_date?>"/>
			<input type="hidden" name="e_date" value="<?php echo $e_date?>"/>
			<input type="hidden" name="field" value="<?php echo $field?>"/>
			<input type="hidden" name="keyword" value="<?php echo htmlspecialchars($keyword)?>"/>
			<input type="hidden" name="s_group" id="s_group" value="<?php echo $s_group?>"/>
			<input type="hidden" name="cafe_name" id="cafe_name" value="<?php echo $cafe_name?>">
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
		
		<!--<form name="frm_calc_com" id="frm_calc_com" method="post" action="calcurate_all_ok.php"  target="_fra_admin">
			<input type="hidden" name="total_param" id="total_param" value="<?php echo $total_param?>"/>
			<input type="hidden" name="pageNo" id="pageNo" value="<?php echo $pageNo?>"/>-->

			<table class="search_list">

				<thead>
					<tr>
						<th width="13%">신청일시</th>
						<th width="10%">히얼업 이름</th>
						<th width="12%">히얼업 아이디</th>
						<th width="10%">아티스트 회신율</th>
						<th width="10%">신청실링</th>
						<th width="10%">환급은행</th>
						<th width="10%">환급계좌</th>
						<th width="10%">예금주</th>
						<th width="8%">송금금액</th>
						<th width="7%">송금완료</th>
					</tr>
				</thead>
				<tbody>
			<?php if(!$s_group){?>
				<tr>
					<td colspan="9" height="40"><strong>히얼업 선택하여 검색해 주세요.</strong></td>
				</tr>
		   <?php } else {?>
				<?php  if($num==0) { ?>
					<tr>
						<td colspan="9" height="40"><strong>환급신청 내역이 없습니다.</strong></td>
					</tr>
				<?php  } ?>

				<?php
				for ($ikm=0; $ikm<mysqli_num_rows($result); $ikm++){
					$row = mysqli_fetch_array($result);
					
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $ikm;

					if($row[calc_stat] == "Y"){
						$calc_stat_str = "환급완료";
					} elseif($row[calc_stat] == "N"){
						$calc_stat_str = "미환급";
					}

					if($ikm == mysqli_num_rows($result)-1){
						$calc_idx_arr .= $row[idx];
					} else {
						$calc_idx_arr .= $row[idx].",";
					}
										
				?>	
				<form name="frm_modify_<?=$i?>" method="post" action="calcurate_sendmoney_action.php"  target="_fra_admin">
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<input type="hidden" name="pageNo" value="<?=$pageNo?>"/>
					<input type="hidden" name="idx" value="<?=$row[idx]?>"/>
					<tr>
						<td><?php echo $row[wdate]?></td>
						<td><?php echo $row[user_name]?></td>
						<td><?php echo $row[user_id]?></td>
						<td><?=number_format(get_support_reply_cnt($row['partner_idx'],$row[wdate]))?>/<?=number_format(get_support_info_cnt($row['partner_idx'],$row[wdate]))?> (<?=get_support_reply_per($row['partner_idx'],$row[wdate])?>%)</td>
						<td><?php echo number_format($row[total_sil_cnt])?> 개</td>
						<td><?php echo get_bank_name($row[bank_code])?></td>
						<td><?php echo $row[refund_account]?></td>
						<td><?php echo $row[refund_nm]?></td>
						<td>
							<input type="text" style="width:90%;" name="send_money" required="yes" message="송금금액" is_num="yes" value="">
						</td>
						<td><a href="javascript:go_modify('frm_modify_<?=$i?>');" class="btn_blue">송금하기</a></td>
					</tr>
				</form>								
			<?php
					$total_price_total = $total_price_total+$row[total_sil_cnt];
				} // 루프 종료 
			?>	
				
				<tr>
					<td style="height:29px; text-align:center; border:1px solid #cdcdcd; border-right:0 none; background:#eee">총 계</td>
					<td></td>
					<td></td>
					<td></td>
					<td><?php echo number_format($total_price_total)?> 개</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			<?php }?>
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

	function go_calc_ok(idx){
		var calc_stat_val = $("#calc_stat_"+idx+"").val();
		var calc_stat_memo = $("#admin_bigo_"+idx+"").val();

		if(calc_stat_val == "Y"){
			if(confirm('환급완료 처리된 내역은 복구할수 없습니다. 입금내역 등 을 확인하여 신중하게 설정해 주십시오.')){
				if(confirm('정말 환급완료 처리 하시겠습니까?')){		
					_fra_admin.location.href="calcurate_dan_ok.php?idx="+idx+"&calc_stat="+calc_stat_val+"&admin_bigo="+calc_stat_memo+"&<?=$total_param?>&pageNo=<?=$pageNo?>";
				} else {
					$("#calc_stat_"+idx+"").val("N");
				}
			} else {
				$("#calc_stat_"+idx+"").val("N");
			}
		} 
	}

	function calc_total_price(){
		var total_price = $("#total_price_total").val();
		var calc_per = $("#calc_per").val();
		var calc_total = total_price * (1 - calc_per/100);
		/*alert(total_price);
		alert(calc_per);
		alert(calc_total);*/
		$("#calc_total").val(calc_total);
	}

	function go_calc_submit() {
		var check = chkFrm('frm_calc_com');
		if(check) {
			frm_calc_com.submit();
		} else {
			false;
		}
	}

	function go_modify(frm_name) {
		var check = chkFrm(frm_name);
		if(check) {
			if(confirm('송금완료 처리된 내역은 취소하거나 수정할 수 없습니다. 송금할 금액 등 을 확인하시어 신중하게 설정해 주십시오.')){
				if(confirm('정말 송금완료 처리 하시겠습니까?')){		
					document.forms[frm_name].submit();
				}
			}
		} else {
			return;
		}
	}
</script>

<?php include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>