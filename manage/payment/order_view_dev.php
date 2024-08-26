<?
include "../../pro_inc/include_default.php"; // 공통함수 인클루드 
include "../include/admin_header.php"; // 관리자페이지 헤더
include "../include/admin_top.php"; // 관리자페이지 상단메뉴
include "../include/payment_left.php"; // 사이트설정 좌측메뉴

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

$scDept = trim(sqlfilter($_REQUEST['scDept']));			// 본부장
$scServer	= trim(sqlfilter($_REQUEST['scServer']));			// 학원구분
$s_mem_sect =  trim(sqlfilter($_REQUEST['s_mem_sect'])); // 주문자 구분

$sc_local = trim(sqlfilter($_REQUEST['sc_local']));			// 지역검색

################## 파라미터 조합 #####################
$total_param = 'field='.$field.'&keyword='.urlencode($keyword).'&v_sect='.$v_sect.'&v_cate='.$v_cate.'&s_date='.$s_date.'&e_date='.$e_date.'&s_pay_type='.$s_pay_type.'&s_pay_sect='.$s_pay_sect.'&s_receipt_ok='.$s_receipt_ok.'&s_taxbill_ok='.$s_taxbill_ok.'&scDept='.$scDept.'&scServer='.$scServer.'&s_mem_sect='.$s_mem_sect.'&sc_local='.$sc_local.'&pageNo='.$pageNo;

$order_num =  trim(sqlfilter($_REQUEST['order_num']));

$where = " and orderstat != 'non' ";

if($v_sect == "ing"){
	$pay_str = "주문결제";
	$where .= " and orderstat in ('pre','com') and ( delvstat IS NULL or delvstat = 'd_pre' ) and order_num = '".$order_num."' ";
} elseif($v_sect == "ingb"){
	$pay_str = "주문배송";
	$where .= " and orderstat in ('pre','com') and delvstat in ('d_ing','d_com','d_conf') and order_num = '".$order_num."' ";
} elseif($v_sect == "can"){
	$pay_str = "입금완료 주문취소";
	//$where .= " and orderstat in ('can') and repaystat not in ('rep_com') and order_num = '".$order_num."' ";
	$where .= " and orderstat in ('can') and order_num = '".$order_num."' ";
} elseif($v_sect == "canp"){
	$pay_str = "주문취소 환불완료";
	//$where .= " and orderstat in ('can') and repaystat in ('rep_com') and order_num = '".$order_num."' ";
	$where .= " and orderstat in ('can') and order_num = '".$order_num."' ";
} elseif($v_sect == "can1"){
	$pay_str = "입금대기 주문취소";
	$where .= " and orderstat in ('can1') and order_num = '".$order_num."' ";
} elseif($v_sect == "reing"){
	$pay_str = "반품신청";
	//$where .= " and orderstat in ('reing') and repay_head_ok = 'Y' and order_num = '".$order_num."' ";
	$where .= " and orderstat in ('reing') and order_num = '".$order_num."' ";
} elseif($v_sect == "recom"){
	$pay_str = "반품완료";
	$where .= " and orderstat in ('recom') and order_num = '".$order_num."' ";
} else {
	$where .= " and order_num = '".$order_num."' ";
}

$sql = "select * from ".NS."order_member where 1=1 ".$where;

//echo $sql."<br><br>";

$query = mysqli_query($GLOBALS['gconnet'],$sql);
	
	if(mysqli_num_rows($query) == 0){
	?>
		<SCRIPT LANGUAGE="JavaScript">
			<!--
				alert('해당하는 주문내역이 없습니다.');
				location.href =  "order_list.php?<?=$total_param?>";
			//-->
		</SCRIPT>
	<?
	exit;
	}

	$row = mysqli_fetch_array($query);
	$row = htmlspecialchars_array($row);

	/*$sql_mile_pre4 = "select coupon_title,coupon_price from ".NS."member_coupon where order_num='".$row[order_num]."' "; // 사용한 쿠폰 
	$result_mile_pre4 = mysqli_query($GLOBALS['gconnet'],$sql_mile_pre4);
	$cnt_mile_pre4 = mysqli_num_rows($result_mile_pre4);*/

	$pay_sect_str1 = get_payment_method($row['pay_sect_1']);
	$pay_sect_str = get_pay_type($row['pay_type'], $row["pay_sect_1"]);

	$delv_stat_str = get_delivery_status($row["delvstat"]);
	$orderstat = get_order_status_color($row["orderstat"]);

	/*if (Trim($row['ServerGbn'])=="GISA"){
		$order_mem_sect = "본부장";
	} elseif (Trim($row['ServerGbn'])=="PARTNR" && Trim($row['UserGbn'])=="ACADEMY"){
		$order_mem_sect = "학원";
	} elseif (Trim($row['ServerGbn'])=="PARTNR" && Trim($row['UserGbn'])=="STD"){
		$order_mem_sect = "학생";
	}*/

	if($row[ES_SENDNO] == "Y"){
		$escrow_ok = "<font style='color:blue;'>에스크로결제</font>";
	} else {
		$escrow_ok = "일반결제";
	}
	
	/*$academy_sql = "select ServerNm from ".NS."GCServerM where 1=1 and Server='".$row['academy']."' ";
	$academy_query = mysqli_query($GLOBALS['gconnet'],$academy_sql);
	$academy_row = mysqli_fetch_array($academy_query);

	$academy_name = $academy_row['ServerNm']; // 학원 

	$head_sql = "select DHNm,Region from ".NS."GCDeptHead where DHCd = '".$row['head']."' ";
	$head_query = mysqli_query($GLOBALS['gconnet'],$head_sql);
	$head_row = mysqli_fetch_array($head_query);
	$head_name = $head_row['DHNm']; // 본부장 명 
	$head_area = $head_row['Region']; // 본부장 영업지역 */

	$delivery_set_sql = "select set_price1,set_price2,set_price3 from ".NS."delivery_set where 1 order by idx desc limit 0,1";
	$delivery_set_query = mysqli_query($GLOBALS['gconnet'],$delivery_set_sql);
	$delivery_set_row = mysqli_fetch_array($delivery_set_query);

	$delivery_set_price1 = $delivery_set_row[set_price1];
	$delivery_set_price2 = $delivery_set_row[set_price2];
	$delivery_set_price3 = $delivery_set_row[set_price3];
		
?>

<script language=javascript> 
<!--
function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			if(confirm('결제취소를 진행합니다. \n취소하게 되면 카드전표가 파기되어 이전 상태로 복구가 불가능하며,  \n사용, 적립된 포인트가 취소됩니다.')){
				if(confirm('확실히 결제취소를 진행 하시겠습니까?')){
					frm.submit();
				}
			}
		} else {
			false;
		}
	}

	function go_submit2() {
		var check = chkFrm('frm2');
		if(check) {
			
			if(confirm('배송상태를 변경합니다. 신중히 결정해 주십시오.')){
				if(confirm('배송상태를 확실히 변경 하시겠습니까?')){

					if(document.frm2.delvstat.value != "d_pre" && document.frm2.delvstat.value != ""){
						if(!document.frm2.delvname.value){
							alert("배송등록자 입력해 주세요.");
							document.frm2.delvname.focus();
							return false;
						}

						if(!document.frm2.delvcom.value){
							alert("배송업체 이름을 선택해 주세요.");
							document.frm2.delvcom.focus();
							return false;
						}

						if(!document.frm2.delvnum.value){
							alert("배송번호를 입력해 주세요!");
							document.frm2.delvnum.focus();
							return false;
						}

						/*
						if(!document.frm2.delvlink.value){
							alert("배송상태 확인링크를 입력해 주세요!");
							document.frm2.delvlink.focus();
							return false;
						}
						*/

					}
				
				frm2.submit();
				
				}
			}
		} else {
			return false;
		}
	}

function go_submit3() {
	var check_num = Number(document.frm3.refund_ok_cnt.value); 
	var check = chkFrm('frm3');

	if(check) {
		if(confirm('반품신청을 최종 승인합니다. \nPG사 결제승인 내역은 취소되지 않고 직접 환불처리 해야합니다. \n최종 승인하게 되면 이전 상태로 복구가 불가능하며, \n사용, 적립된 포인트가 취소됩니다.')){
			if(confirm('반품신청을 확실히 승인 하시겠습니까?')){
				frm3.submit();
			}
		}
	} else {
		return false;
	}
}

function go_submit4() {
	var check_num = Number(document.frm3.refund_ok_cnt.value); 
	var check = chkFrm('frm4');

	if(check) {
		if(confirm('구매거절을 최종 승인합니다. \nPG사 결제승인 내역이 취소됩니다. \n최종 승인하게 되면 이전 상태로 복구가 불가능하며,  \n사용, 적립된 포인트가 취소됩니다.')){
			if(confirm('구매거절을 확실히 승인 하시겠습니까?')){
				frm4.submit();
			}
		}
	} else {
		return false;
	}
}

function delvstat_sel(z) { 
	
	var tmp = z.options[z.selectedIndex].value; 

		if (tmp == "d_pre" || tmp == ""){	
			delv_txt_0.style.display = 'none';
			delv_txt_1.style.display = 'none';
			delv_txt_2.style.display = 'none';
			delv_txt_3.style.display = 'none';
		} else{
			delv_txt_0.style.display = '';
			delv_txt_1.style.display = '';
			delv_txt_2.style.display = '';
			delv_txt_3.style.display = '';
		}
 } 

 function view_pic(ref) {
			ref = ref;
			var window_left = (screen.width-1024) / 2;
			var window_top = (screen.height-768) / 2;
			window.open(ref, "pic_window", 'width=600,height=400,status=no,scrollbars=yes,top=' + window_top + ', left=' + window_left +'');
	}

function Display_1(form){
	if(form.order_result.value == "can"){
		document.all.select_txt1.style.display= "block";
		document.all.select_txt2.style.display= "block";
	}else{
		document.all.select_txt1.style.display= "none";
		document.all.select_txt2.style.display= "none";
	}
}

function Display_2(form){
	if(form.order_result.value == "payre"){
		document.all.select_txt2.style.display= "block";
	}else{
		document.all.select_txt2.style.display= "none";
	}
}

function k_Popup(url,target,flag) 
{ 
  var objPopup = window.open(url,target,flag); 
  if (objPopup == null) 
  { 
    document.write("<object id='DHTMLEdit' classid='clsid:2D360201-FFF5-11d1-8D03-00A0C959BC0A' width='1' height='1' align='middle'><PARAM NAME='ActivateApplets' VALUE='1'><PARAM NAME='ActivateActiveXControls' VALUE='1'></object>"); 
    setTimeout('k_Popup2("'+url+'","'+target+'","'+flag+'")', 500); 
  } 
} 

function k_Popup2(url,target,flag) 
{ 
  try 
  { 
    DHTMLEdit.DOM.Script.execScript('window.open("'+url+'","'+target+'","'+flag+'")'); 
  } 
  catch (e) 
  { 
    // DHTML ActiveX 까정 없을때 ^^ 
  } 
} 

function go_list(){
	location.href = "order_list.php?<?=$total_param ?>";
}

function go_delete(no){
		if(confirm('결제정보를 삭제하시면 차후 복구가 불가능하여 이후로는 해당 데이터를 열람할 수 없습니다.\n\n그래도 삭제 하시겠습니까?')){
			if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "order_delete_action.php?order_num="+no+"&<?=$total_param ?>";
			//location.href = "mem_ilban_delete_action.php?no="+no+"&pageNo="+page;
			}
		}
	}

	function go_dtrace() {
		var check = chkFrm('delv_trace');
			if(check) {
				delv_trace.submit();
			} else {
				false;
			}
	}

	function go_return_modify(frm_name) {
		var check = chkFrm(frm_name);
		if(check) {
			document.forms[frm_name].submit();
		} else {
			return;
		}
	}

	function go_admin_memo() {
		var check = chkFrm('admin_memo');
			if(check) {
				admin_memo.submit();
			} else {
				false;
			}
	}

	function go_jehu_member(member_idx){
		//location.href = 
		window.open("product_jehu_member.php?member_idx="+member_idx+"","promemview", "top=100,left=100,scrollbars=yes,resizable=no,width=1080,height=600");
	}

<?if(Trim($row['delvstat']) == "d_pre" || Trim($row['delvstat']) == "d_ing" || Trim($row['delvstat']) == "d_com" || Trim($row['delvstat']) == "d_conf"){?>
	function reqPayment() {
		alert("배송상태가 <?=$delv_stat_str?> 이라 취소하실수 없습니다.");
		return
	}
<? } else { ?>
	function reqPayment() {
		if(confirm('주문내용을 취소 하시겠습니까?')){
			if(confirm('한번 취소한 주문은 다시 복구하실수 없습니다.\n\n정말 취소 하시겠습니까?')){
				//setTemp(); // 예제 테스트를 위한 함수 (*_tmp->*)   
				var form = document.payform;
				
				if(!document.payform.MSTR2.value){
					alert("취소유형을 선택해 주십시오.");
					return
				}

				if(!document.payform.MSTR.value){
					alert("취소사유를 입력해 주십시오.");
					return
				}

				PAY_REQUEST(form);
			}
		}
	}
<?}?>
//-->
</script>

<script type="text/javascript">
 /* 신용카드 영수증 연동 스크립트 */
function show_receipt() {
	
	var send_dt = appr_tm.value;
		
	url="http://www.allthegate.com/customer/receiptLast3.jsp"
	url=url+"?sRetailer_id="+sRetailer_id.value;
	url=url+"&approve="+approve.value;
	url=url+"&send_no="+send_no.value;
	url=url+"&send_dt="+send_dt.substring(0,8);
		
	window.open(url, "window","toolbar=no,location=no,directories=no,status=,menubar=no,scrollbars=no,resizable=no,width=420,height=700,top=0,left=150");
}

function fn_Bill(trd_no, vat) {
        var url = "https://www.mcash.co.kr/cp/sales_detail/PrintReceipt_CN.php?trd_no="+trd_no +"&vat="+vat;

        var win = window.open(url, "mcashBill", "menubar=no, resizable=yes");
        if(win.focus) win.focus();
    }
</script>

	<section id="content">
	<div class="inner">
		<h3><?=$pay_str?>내역 상세보기</h3>
		<div class="cont">
		
		<p><font style="color:blue;"><b>주문상품정보</b></font></p>  
		<table class="t_list">
				<thead>
					<tr>
						<th width="10%">상품이미지</th>
						<th width="10%">대분류</th>
						<!--<th width="10%">중분류</th>-->
						<th width="">상품명</th>
						<th width="10%">옵 션</th>
						<th width="10%">가 격</th>
						<th width="10%">수 량</th>
						<th width="10%">합 계</th>
						<th width="10%">적립금</th>
					</tr>
				</thead>
				<tbody>
				<?	
					$product_sql = "select *,(select file_c from product_info where 1 and idx=order_product.product_idx) as file_c,(select user_name from member_info where 1 and idx=order_product.sales_member_idx) as com_name,(select user_id from member_info where 1 and idx=order_product.sales_member_idx) as com_id
										from ".NS."order_product 
										where order_num = '".$row['order_num']."' "; 
					//echo $product_sql;
					$product_query = mysqli_query($GLOBALS['gconnet'],$product_sql);
					
					$refund_ok_cnt = 0;
					$refund_ok_price = 0;

					while($product_row = mysqli_fetch_array($product_query)){ // 장바구니 루프 시작 
						$product_row = htmlspecialchars_array($product_row);

						$price_dan = $product_row[price_dan];

						$category = "";
						$sect1_sql = "select a.cate_name1 
											from ".NS."product_cate a inner join ".NS."product_category_set b 
											on a.cate_code1=b.cate_code1 
											where b.product_idx='".$product_row["product_idx"]."' and a.cate_level='1' and b.cate_level='1' 
											order by a.cate_align desc";
						$sect1_result = mysqli_query($GLOBALS['gconnet'],$sect1_sql);
						$cate_name1 = array();
						while($row1 = mysqli_fetch_array($sect1_result)){
							$cate_name1[] = htmlspecialchars($row1['cate_name1']);
						}
						$category .= implode(", ", $cate_name1);

						$sect1_sql = "select a.cate_name2 
											from ".NS."product_cate a inner join ".NS."product_category_set b 
											on a.cate_code2=b.cate_code2 
											where b.product_idx='".$product_row["product_idx"]."' and a.cate_level='2' and b.cate_level='2' 
											order by a.cate_align desc";
						$sect1_result = mysqli_query($GLOBALS['gconnet'],$sect1_sql);
						$cate_name2 = array();
						while($row1 = mysqli_fetch_array($sect1_result)){
							$cate_name2[] = htmlspecialchars($row1['cate_name2']);
						}
						if(count($cate_name1) && count($cate_name2)) $category .= ", ";
						$category .= implode(", ", $cate_name2);

						$sect1_sql = "select a.cate_name3 
											from ".NS."product_cate a inner join ".NS."product_category_set b 
											on a.cate_code3=b.cate_code3 
											where b.product_idx='".$product_row["product_idx"]."' and a.cate_level='3' and b.cate_level='3' 
											order by a.cate_align desc";
						$sect1_result = mysqli_query($GLOBALS['gconnet'],$sect1_sql);
						$cate_name3 = array();
						while($row1 = mysqli_fetch_array($sect1_result)){
							$cate_name3[] = htmlspecialchars($row1['cate_name3']);
						}
						if((count($cate_name1) || count($cate_name2)) && count($cate_name3)) $category .= ", ";
						$category .= implode(", ", $cate_name3);

						$promotion_txt = get_promotion_txt($product_row["p_cnt"], $product_row["promotion_basis"], $product_row["promotion_bonus"]);

						if(member_type($product_row[sales_member_idx]) == "AD"){
							$mem_path = "masterid";
						} else {
							$mem_path = member_id($product_row[sales_member_idx]);
						}
				?>
					
						<tr> 
							<td>
								<?if($product_row[file_c]){?>
									<img src="<?=$_P_DIR_WEB_FILE?>product/<?=$mem_path?>/img_thumb/<?=$product_row[file_c]?>" style="width:120px;height:120px;border:0;">
								<?}else{?>
									<img src="../../images/main/noimage.gif" style="width:120px;height:120px;border:0;" />
								<?}?>
							</td>
							<td><?=$category?></td>
							<!--<td><?=htmlspecialchars($row2[cate_name2])?></td>-->
							<td style="text-align:left;padding-left:10px;">
								<?=$product_row["p_name"]?>
							</td>
							<td>
							<?
								$sql_opt2 = "select idx,opt_title,opt_name,opt_sect,opt_price from ".NS."order_product_opt where order_num = '".$product_row[order_num]."' and order_product_idx = '".$product_row[idx]."' and product_idx = '".$product_row[product_idx]."' ";
								$query_opt2 = mysqli_query($GLOBALS['gconnet'],$sql_opt2);
					
								for ($k=0; $k<mysqli_num_rows($query_opt2); $k++){
									$row_opt2 = mysqli_fetch_array($query_opt2);

									if($row_opt2[opt_sect] == "A"){
										$opt_sect = "<font style='color:blue;'><b>+</b></font>";
										$price_dan = $price_dan+$row_opt2[opt_price];
									} elseif($row_opt2[opt_sect] == "M"){
										$opt_sect = "<font style='color:red;'><b>-</b></font>";
										$price_dan = $price_dan-$row_opt2[opt_price];
									} 
											
									if($row_opt2[opt_price] && $row_opt2[opt_price] > 0){
										$opt_price = number_format($row_opt2[opt_price])." 원";
									} else {
										$opt_sect = "";
										$opt_price = 0;
									}
								?>
									<br><font style="color:blue;"><?=$row_opt2[opt_title]?></font> : <?=$row_opt2[opt_name]?><?if($row_opt2[opt_price]){?>  : <?=$opt_sect?> <?=$opt_price?><?}?> 
							<?}?>
							</td>
							<td><?=number_format($price_dan,0)?>원</td>
							<td><?=number_format($product_row[p_cnt],0)?><?=$promotion_txt?> 개</td>
							 <?
								$each_price_dan = $price_dan*$product_row[p_cnt];
								if($row["member_idx"]){
									$each_pro_salepoint = $product_row['product_sale_point'] * $product_row["p_cnt"];
								}else{
									$each_pro_salepoint = 0;
								}
							?>
							<td><strong><?=number_format($each_price_dan,0)?></strong></td>
							<td><?=number_format($each_pro_salepoint,0)?> 포인트</td>
						</tr>
						<tr> 
							<td></td>
							<td>
								배송상태 : <?=get_delivery_status_color($product_row["delvstat"])?>
							</td>
							<td style="text-align:left;padding-left:10px;">
							<?if($product_row["delvlink"]){?>
								배송추적 링크 : <a href="<?=$product_row["delvlink"]?>" target="_blank" class="btn_blue2">배송추적 클릭</a>
							<br> 송장번호 : <?=$product_row["delvnum"]?>
							<?}?>
							</td>
							<td>
							<?if($product_row["delvcom"]){?>
								배송업체 : <?=get_delivery_company($product_row["delvcom"])?>
							<?}?>
							</td>
							<td colspan="2">
								입점업체 : <?=$product_row["com_name"]?>
							</td>
							<td colspan="2">
								입점업체 아이디 : <?=$product_row["com_id"]?>
							</td>
						</tr>				
					<?
					}
				?>
			 			
			 <tr>
				<td colspan="8" style="text-align:right;padding-right:10px;">
					<span style="font-size:14px;">
						상품금액 <strong><?=number_format($row["price_total_org"] - $row["price_delivery"],0)?>원</strong> 
						<img src="../img/icon/order_pc_plus.gif" width="15" height="16" hspace="5" align="absmiddle" /> 
						배송비 <strong><?=number_format($row[price_delivery],0)?> 원 (추가배송비 <?=number_format($row["price_extra_delivery"])?>포함)</strong> 
						<img src="../img/icon/order_pc_minus.gif" width="15" height="16" hspace="5" align="absmiddle" /> 
						포인트사용 <strong><?=number_format($row["pay_refund"])?>원</strong> 
						<img src="../img/icon/order_pc_result.gif" width="15" height="16" hspace="5" align="absmiddle" /> 
						<span style="color:#e62106;"><strong>총 결제금액 <?=number_format($row["price_total"],0)?>원</strong></span>
					</span><br />
					<span style="color:#7c7c7c;line-height:25px;">* <?=number_format($delivery_set_price1,0)?>원 이상 구매시 무료</span>
				</td>
			</tr>
			</tbody>
			
			</table>
			<br><br>
			<p><font style="color:blue;"><b>주문자정보</b></font></p>  
				<table class="t_view">
				<colgroup>
					<col width="20%" />
					<col width="30%" />
					<col width="20%" />
					<col width="30%" />
				</colgroup>
					<tr>
						<th >주문번호</th>
						<td>
							<?=$row[order_num]?>
							<?
							/*
							if($row[orderstat] == "com"){?> &nbsp; 
								<?if ($row['pay_sect_1'] == "card_isp" || $row['pay_sect_1'] == "card_ans" || $row['pay_sect_1'] == "card_gen"){ // 신용카드 결제일 경우?>
									<script language="JavaScript" src="http://pgweb.uplus.co.kr:7085/WEB_SERVER/js/receipt_link.js"></script> <!-- 실거래일때는 7085 포트 생략하고 상점아이디는 t 생략 -->
									<a href="javascript:showReceiptByTID('tmachoen', '<?=$row[ApprNo]?>', 'authdata')" class="btn_blue2_big">신용카드 영수증 출력</a>
								<?} elseif ($row['pay_sect_1'] == "pay_virt"){ // 가상계좌 결제일 경우?>
									<script language="JavaScript" src="http://pgweb.uplus.co.kr:7085/WEB_SERVER/js/receipt_link.js"></script> <!-- 실거래일때는 7085 포트 생략하고 상점아이디는 t 생략, test 아닌 service -->
									<a href="javascript:showCashReceipts('tmachoen','<?=$row[order_num]?>','<?=$row[pay_bank_time]?>','CAS','test')" class="btn_blue2_big">현금영수증 출력</a>
								<?}?>
							<?
							}
							*/
							?>
						</td>
						<th >주문자 아이디</th>
						<td >
						<?if($row[user_id] == "" || $row[user_id] == " "){?>
							비회원 구매
						<?}else{?>
							<?=$row[user_id]?>
						<?}?>
						</td>
					</tr>
					<tr>
						<th >주문자 명</th>
						<td ><?=$row[order_name]?></td>
						<th >전화번호</th>
						<td ><?=$row[order_tel]?></td>
					</tr>
					
					<tr>
						<th >휴대전화</th>
						<td ><?=$row[order_cell]?></td>
						<th >이메일</th>
						<td><?=$row[order_email]?></td>
					</tr>
					<tr>
						<th>주소</th>
						<td colspan="3">[<?=$row["order_zipcode"]?>] <?=$row["order_addr1"]?> <?=$row["order_addr2"]?></td>
					</tr>
				</table>
				<br><br>
				<?if($row[delvcom] == "대한통운"){ ?>
					<form name="delv_trace" method="post" action="https://www.doortodoor.co.kr/parcel/doortodoor.do" target="_blank">
						<input type="hidden" id="fsp_action" name="fsp_action" value="PARC_ACT_002"/>
						<input type="hidden" id="fsp_cmd" name="fsp_cmd" value="retrieveInvNoACT"/>
						<input type="hidden" id="invc_no" name="invc_no" value="<?=$row[delvnum]?>"/>
					</form>
				<?} elseif($row[delvcom] == "우체국"){ ?>
					<form name="delv_trace" method="post" action="http://service.epost.go.kr/trace.RetrieveRegiPrclDeliv.postal" target="_blank">
						<input type="hidden" id="sid1" name="sid1" value="<?=$row[delvnum]?>"/>
					</form>
				<? } ?>

				<p><font style="color:blue;"><b>배송지정보</b></font></p> 
				<table class="t_view">
				<colgroup>
					<col width="20%" />
					<col width="30%" />
					<col width="20%" />
					<col width="30%" />
				</colgroup>
					<tr>
						<th >배송지 받는사람</th>
						<td><?=$row[send_name]?></td>
						<th >전화번호</th>
						<td ><?=$row[send_tel]?></td>
					</tr>
					<tr>
						<th >휴대전화</th>
						<td ><?=$row[send_cell]?></td>
						<th>이메일</th>
						<td><?=$row["send_email"]?></td>
					</tr>
					<tr>
						<th >배송지 주소</th>
						<td colspan="3">[<?=$row['send_zipcode']?>] <?=$row['send_addr1']?> <?=$row['send_addr2']?> 
						<?if($row[delvcom] && $row[delvnum]){?>
							<!--&nbsp;&nbsp; <a href="javascript:go_dtrace();"><img src="/img/icon/order_btn_truck.gif" align="absmiddle"/></a>-->
						<?}?>
						</td>
					</tr>
					<tr>
						<th >배송요청 사항</th>
						<td colspan="3" style="padding-left:20px;padding-top:10px;padding-bottom:10px;padding-right:10px;"><?=nl2br($row[order_memo])?></td>
					</tr>					
				</table>
				<br><br>

			<?if ($row['receipt_ok'] == "P" || $row['receipt_ok'] == "C"){ ?>
			<!-- 현금영수증 시작 -->
			<p><font style="color:blue;"><b>현금영수증 신청정보</b></font></p> 
			<table class="t_view">
				<colgroup>
					<col width="20%" />
					<col width="30%" />
					<col width="20%" />
					<col width="30%" />
				</colgroup>
				<tr>
					<th >현금영수증 종류</th>
					<td colspan="3">
					<?if ($row['receipt_ok'] == "P"){?>
						소득공제용
					<?}elseif ($row['receipt_ok'] == "C"){?>
						지출증빙용
					<?}?>
					</td>
				</tr>
			<?if ($row['receipt_ok'] == "P"){?>
				<tr>
					<th >현금영수증 정보</th>
					<td colspan="3">
					<?=$row['receipt_sect']?> : 
					<?if ($row['receipt_sect'] == "휴대폰 번호"){?>
						<?=$row['receipt_cell']?>
					<?}elseif ($row['receipt_sect'] == "주민등록 번호"){?>
						<?=$row['receipt_ssn']?>
					<?}?>
					</td>
				</tr>
			<?}elseif ($row['receipt_ok'] == "C"){?>
				<tr>
					<th >현금영수증 사업자번호</th>
					<td colspan="3">
						<?=$row['receipt_comnum']?>
					</td>
				</tr>
			<?}?>
			</table>
			<br><br>
			<!-- 현금영수증 종료 -->
			<?}?>

			<?if ($row['taxbill_ok'] == "Y"){ ?>
			<!-- 세금계산서 시작 -->
			<p><font style="color:blue;"><b>세금계산서 신청정보</b></font></p> 
			<table class="t_view">
				<colgroup>
					<col width="20%" />
					<col width="30%" />
					<col width="20%" />
					<col width="30%" />
				</colgroup>
				<tr>
					<th >상 호</th>
					<td><?=$row[taxbill_comname]?> </td>
					<th >대표자</th>
					<td><?=$row[taxbill_ch_name]?> </td>
				</tr>
				<tr>
					<th >사업자 등록번호</th>
					<td colspan="3"><?=$row[taxbill_comnum]?> </td>
				</tr>
				<tr>
					<th >사업장 소재지</th>
					<td colspan="3"><?=$row[taxbill_addr]?> </td>
				</tr>
				<tr>
					<th >업 태</th>
					<td><?=$row[taxbill_upt]?> </td>
					<th >종 목</th>
					<td><?=$row[taxbill_mok]?> </td>
				</tr>
				<tr>
					<th >이메일</th>
					<td colspan="3"><?=$row[taxbill_email]?> </td>
				</tr>
			</table>
			<br><br>
			<!-- 세금계산서 종료 -->
			<?}?>
			<p><font style="color:blue;"><b>결제정보</b></font></p> 
			<table class="t_view">
			<colgroup>
					<col width="20%" />
					<col width="30%" />
					<col width="20%" />
					<col width="30%" />
				</colgroup>
					<tr>
					<th>주문상태</th>
					<td><?=$orderstat?></td>
					<th>결제수단</th>
					<td><?=$pay_sect_str?></td>
				</tr>
				<?
					$price_total_org = $row[price_total_org];
					$price_org_total = $price_total_org - $row[price_delivery];
				?>
				<tr>
					<th>상품 결제금액</th>
					<td><?=number_format($price_org_total,0)?> 원</td>
					<th>배송료</th>
					<td><?=number_format($row[price_delivery],0)?> 원</td>
				</tr>
				<tr>
					<th>사용 적립금</th>
					<td><?=number_format($row[pay_refund],0)?> 원</td>
					<th>실 결제금액</th>
					<td ><?=number_format($row[price_total],0)?> 원</td>
				</tr>
			
				<!--<tr>
					<th>사용한 쿠폰</th>
					<td colspan="3">
					<?
						if(mysqli_num_rows($result_mile_pre4) > 0) {  // 결제시 사용한 쿠폰이 있을때 
							$row_mile_pre4 = mysqli_fetch_array($result_mile_pre4);
					?>
						<?=$row_mile_pre4[coupon_title]?> &nbsp; (쿠폰 액면가 : <?=number_format($row_mile_pre4[coupon_price],0)?> 원)	
					<? } ?>
					</td>
				</tr>-->

				
				<?if($row[pay_sect_1] == "pay_virt"){?>
				<tr>
					<th>입금은행 명</th>
					<td colspan="3"><?=get_bank_name($row[pay_bank])?></td>
				</tr>
				<tr>
					<th>입금은행 계좌번호</th>
					<td colspan="3"><?=$row[pay_bank_num]?></p>
					</td>
				</tr>
				<tr>
					<th>예금주</th>
					<td colspan="3"><?=$row[pay_bank_name]?></td>
				</tr>
				<!--
				<tr>
					<th>입금기한</th>
					<td colspan="3"><?=$row[pay_bank_time]?> 까지</td>
				</tr>-->
				<?}?>				
				<tr>
					<th>결제일시</th>
					<td colspan="3"><?=$row['order_date']?></td>
				</tr>

				<?if($row[orderstat] == "can" || $row[orderstat] == "can1"){?>
					
					<tr>
						<th>결제취소 일시</th>
						<td colspan="3"><?=$row['cancel_date']?></td>
					</tr>
					<tr>
						<th>취소유형</th>
						<td colspan="3"><?=$row['cancel_sect_1']?></td>
					</tr>
					<tr>
						<th>취소사유</th>
						<td colspan="3"><?=nl2br(stripslashes($row['cancel_memo']))?></td>
					</tr>
					<tr>
						<th>취소구분</th>
						<td colspan="3">
							<?if($row[cancel_sect] == "C"){?>
								고객 직접취소
							<?}else{?>
								관리자가 취소
							<?}?>
						</td>
					</tr>

				<?}elseif($row[orderstat] == "reing" ||$row[orderstat] == "recom"){?>
					
					<!--<tr>
						<th>반품신청 일시</th>
						<td colspan="3"><?=$row['repay_ing_date']?></td>
					</tr>
					<tr>
						<th >반품승인된 상품갯수</th>
						<td ><?=$refund_ok_cnt?> 개</td>
						<th >반품승인에 따른 상품금액</th>
						<td ><?=number_format($refund_ok_price,0)?> 원</td>
					</tr>

					<tr >
						<th>반품신청 사유</th>
						<td colspan="3">
							<?=nl2br($return_memo)?>
						</td>
					</tr>-->
					<?//if (Trim($row['ServerGbn'])=="PARTNR" && Trim($row['UserGbn'])=="ACADEMY"){ // 학원주문 반품?>
						<!--<tr >
							<th>본부장 수량변경 사유</th>
							<td colspan="3">
								<?=nl2br($head_return_memo)?>
							</td>
						</tr>-->
					<?// } else { //  본부장, 학원 주문 반품?>
						<tr >
							<th>관리자 반품처리 메모</th>
							<td colspan="3">
								<?=nl2br($row['head_return_memo'])?>
							</td>
						</tr>
					<?// } ?>
					<tr>
						<th>반품처리 일시</th>
						<td colspan="3"><?=$row['repay_done_date']?></td>
					</tr>

				<?}?>
			
			</table>

			<?if($row[orderstat] == "pre" || ($row[orderstat] == "com" && $row["pay_sect_1"] == "card_isp" && (!$row["delvstat"] || $row["delvstat"] == "d_pre"))){?>
			<br><br>
			<p><font style="color:blue;"><b>전체 결제취소</b></font></p> 

			<form name="frm" action="order_cancel_action.php" target="_fra_admin" method="post" >
			<input type="hidden" name="order_num" value="<?=$order_num?>"/>
			<input type="hidden" name="total_param" value="<?=$total_param?>"/>		
			<table class="t_view">
				<colgroup>
					<col width="20%" />
					<col width="" />
				</colgroup>

				<tr id="select_txt1" style="display:;">
					<th>주문취소유형</th>
					<td>
						<select style="width:40%; height:24px;"  name="cancel_sect_1" id="cancel_sect_1" required="no" message="취소유형">
							<option value="">취소 유형을 선택하세요.</option>
							<option value="단순변심">단순변심</option>
							<option value="오배송">오배송</option>
							<option value="훼손상품">훼손상품</option>
							<option value="기타사유">기타사유</option>
						</select>
					</td>
				</tr>
		
				<tr id="select_txt2" style="display:;">
					<th>주문취소사유</th>
					<td>
						<textarea name="cancel_memo" id="cancel_memo" required="no" message="취소사유" style="width:90%;"></textarea>
					</td>
				</tr>

				<tr>
					<th>결제취소</th>
					<td>
						<a href="#" onclick="go_submit();" class="btn_blue2">결제취소</a>
					</td>
				</tr>
			</table>
			</form>

			<?}?>
				
		<?if(($row[orderstat] == "com" || $row[orderstat] == "reing") && $row["delvstat"] != "d_deny"){?>
			<!--<br><br>
			<p><font style="color:blue;"><b>반품처리</b></font></p>  

			<form name="frm3" action="order_refund_action.php" target="_fra_admin" method="post" >
				<input type="hidden" name="order_num" value="<?=$order_num?>"/>
				<input type="hidden" name="total_param" value="<?=$total_param?>"/>
				<input type="hidden" name="refund_ok_cnt" value="<?=$refund_ok_cnt?>"/>

				<table class="t_view">
				<colgroup>
					<col width="20%" />
					<col width="" />
				</colgroup>
					<tr>
						<th>확인자명</th>
						<td><input type=text name="repay_name" style="width:100px;" size=17 maxlength=30 value="<?=htmlspecialchars($session_admin_name)?>" required="yes" message="확인자명" ></td>
					</tr>
					<tr >
						<th>관리자 반품처리 메모</th>
						<td>
							<textarea name="head_return_memo" id="head_return_memo" required="no" message="관리자 반품처리 메모" style="width:90%;height:50px;"></textarea> <a href="#" onclick="go_submit3();" class="btn_blue2">반품처리</a>
						</td>
					</tr>
					<!--
					<tr>
						<th>반품 적립금처리</th>
						<td>
							반품을 승인하며, 적립금 <input type="text" style="width:100px;" name="refund_com_point" value="<?=get_order_product_points($row["order_num"])?>" id="refund_com_point" required="yes" message="지급할 적립금" is_num="yes"> 원을 
							<input type="radio" id="mile_sect_m" name="mile_sect" value="M" checked /> <label for="mile_sect_m">차감</label> 
							<input type="radio" id="mile_sect_a" name="mile_sect" value="A" /> <label for="mile_sect_a">적립</label> 합니다. <a href="#" onclick="go_submit3();" class="btn_blue2">반품처리</a>
						</td>
					</tr>
					
				</table>
			</form>-->
			<?}?>

			<?if($row[orderstat] == "reing" && $row["delvstat"] == "d_deny"){?>
			<!--<br><br>
			<p><font style="color:blue;"><b>구매거절확인 처리</b></font></p>  

			<form name="frm4" action="order_escrow_refund_action.php" target="_fra_admin" method="post" >
				<input type="hidden" name="order_num" value="<?=$order_num?>"/>
				<input type="hidden" name="total_param" value="<?=$total_param?>"/>
				<input type="hidden" name="refund_ok_cnt" value="<?=$refund_ok_cnt?>"/>

				<table class="t_view">
				<colgroup>
					<col width="20%" />
					<col width="" />
				</colgroup>
					<tr>
						<th>확인자명</th>
						<td><input type=text name="repay_name" style="width:100px;" size=17 maxlength=30 value="<?=htmlspecialchars($session_admin_name)?>" required="yes" message="확인자명" ></td>
					</tr>
					<tr >
						<th>관리자 반품처리 메모</th>
						<td>
							<textarea name="head_return_memo" id="head_return_memo" required="no" message="관리자 반품처리 메모" style="width:90%;height:50px;"></textarea> <a href="#" onclick="go_submit4();" class="btn_blue2">반품처리</a>
						</td>
					</tr>
				</table>
			</form>-->
			<?}?>

			<br><br>
			<p><font style="color:blue;"><b>관리자 비고메모</b></font></p>
			<table class="t_view">
			<colgroup>
					<col width="20%" />
					<col width="" />
				</colgroup>
						
				<form name="admin_memo" action="order_admin_memo_action.php" target="_fra_admin" method="post" >
					<input type="hidden" name="order_num" value="<?=$order_num?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					
					<?if($row[orderstat] == "can"){?>
						<!--<tr>
							<th >환불상태</th>
							<td>
							<select name="repaystat" size="1" style="vertical-align:middle;" required="yes" message="환불상태">
								<option value="">환불상태 설정</option>
								<option value="" <?=$row[repaystat]==""?"selected":""?>>환불예정</option>
								<option value="rep_com" <?=$row[repaystat]=="rep_com"?"selected":""?>>환불완료</option>
							</select> 
							</td>
						</tr>-->
					<?}?>
						<tr>
							<th >관리자 비고메모</th>
							<td>
								<textarea name="admin_bigo" id="admin_bigo" required="yes" message="관리자 비고메모" style="width:90%;height:50px;"><?=$row['admin_bigo']?></textarea>
								 &nbsp; <a href="#" onclick="go_admin_memo();" class="btn_blue2">비고입력</a>
							</td>
						</tr>
					
					
				</form>
			</table>

			<div class="align_c margin_t20">
				<!-- 목록 -->
				<a href="#" onclick="go_list();" class="btn_blue2">목록</a>
				<!-- 삭제 -->
				<a href="#" onclick="go_delete('<?=$row[order_num]?>');" class="btn_blue2">삭제</a>	
			</div>
		</div>
		
	</div>
</section>
<!-- //content -->

<? include "../include/admin_bottom.php"; ?>