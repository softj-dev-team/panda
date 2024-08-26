<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"].""."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$order_num = trim(sqlfilter($_REQUEST['order_num']));
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
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.urlencode($keyword).'&v_sect='.$v_sect.'&v_cate='.$v_cate.'&s_date='.$s_date.'&e_date='.$e_date.'&s_pay_type='.$s_pay_type.'&s_pay_sect='.$s_pay_sect.'&s_receipt_ok='.$s_receipt_ok.'&s_taxbill_ok='.$s_taxbill_ok.'&s_mem_sect='.$s_mem_sect.'&s_group='.$s_group.'&pageNo='.$pageNo;;

$where = " and orderstat != 'non' and order_num = '".$order_num."'";

if($v_sect == "com"){
	$pay_str = "입금완료";
} elseif($v_sect == "pre"){
	$pay_str = "입금대기";
} elseif($v_sect == "reing"){
	$pay_str = "취소신청";
} elseif($v_sect == "can"){
	$pay_str = "취소완료";
}

$sql = "select *,(select s_date from membership_auth where 1 and order_num=a.order_num) as s_date,(select e_date from membership_auth where 1 and order_num=a.order_num) as e_date from order_member a where 1 ".$where;

//echo $sql."<br>";
//exit;

$query = mysqli_query($gconnet,$sql);
	
	if(mysqli_num_rows($query) == 0){
	?>
		<SCRIPT LANGUAGE="JavaScript">
			<!--
				alert('해당하는 내역이 없습니다.');
				location.href =  "order_list.php?<?=$total_param?>";
			//-->
		</SCRIPT>
	<?
	exit;
	}

	$row = mysqli_fetch_array($query);

?>

<script language=javascript> 
<!--

	function go_submit2() {
		var check = chkFrm('frm2');
		if(check) {
			
			if(confirm('배송상태를 변경합니다. 신중히 결정해 주십시오.')){
				if(confirm('배송상태를 확실히 변경 하시겠습니까?')){

					if(document.frm2.delvstat.value != "d_pre" && document.frm2.delvstat.value != ""){
						/*if(!document.frm2.delvname.value){
							alert("배송등록자 입력해 주세요.");
							document.frm2.delvname.focus();
							return false;
						}*/

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

						
						if(!document.frm2.delvlink.value){
							alert("배송상태 확인링크를 입력해 주세요!");
							document.frm2.delvlink.focus();
							return false;
						}
						

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
			//delv_txt_0.style.display = 'none';
			delv_txt_1.style.display = 'none';
			delv_txt_2.style.display = 'none';
			delv_txt_3.style.display = 'none';
		} else{
			//delv_txt_0.style.display = '';
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

function order_product_can(stat,opidx){
	if(stat == "reing"){
		var stat_str = "환불신청";
	} else if(stat == "recom"){
		var stat_str = "환불완료";
	}

	if(confirm('선택하신 상품을 '+stat_str+' 처리합니다. \n입금완료 여부 등을 따져 신중하게 판단해 주십시오.')){
		if(confirm('정말 '+stat_str+' 하시겠습니까?')){
			$("#order_product_stat_"+opidx+"").val(stat);
			$("#order_product_can_form_"+opidx+"").submit();
		}
	}
}

	function go_after_submit() {
		var check = chkFrm('after_frm');
		if(check) {
			if(confirm('입금완료 설정합니다. 입금상태 등을 상세히 파악하여 신중하게 결정하세요.')){
				if(confirm('입금완료가 되면 입금대기 상태로 복구는 불가능합니다. 확실히 입금완료로 설정 하시겠습니까?')){
					after_frm.submit();
				}
			}
		} else {
			false;
		}
	}

	function go_cancel_submit() {
		var check = chkFrm('cancel_frm');
		if(check) {
			if(confirm('환불완료 설정합니다. 신청사항 한번 더 확인하여 신중하게 결정하시고, 환불해줄 금액을 먼저 입금한 다음에 설정해주세요.')){
				if(confirm('환불완료 처리가 되면 이전상태로 복구는 불가능합니다. 확실히 환불완료 설정 하시겠습니까?')){
					cancel_frm.submit();
				}
			}
		} else {
			false;
		}
	}

	function go_calendar_set(){
		if(confirm('수업 승인하면 일괄로 캘린더상에 적용됩니다. 정말 승인 하시겠습니까?')){
			_fra_admin.location.href="order_calendar_set.php?order_num=<?=$row[order_num]?>";
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
	
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"].""."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"].""."/master/include/payment_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>결제내역 관리</li>
						<li><?=$pay_str?></li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$pay_str?> 상세보기</h3>
				</div>
				<div class="write">
					<p class="tit">신청한 멤버십 정보</p>
					<table>
						<caption>수강신청 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th scope="row">멤버십 이름</th>
							<td colspan="3">
								<?=$row['payment_str']?>
							</td>
						</tr>
						<tr>
							<th scope="row">멤버십 시작일</th>
							<td>
								<?=$row['s_date']?> 
							</td>
							<th scope="row">멤버십 종료일</th>
							<td>
								<?=$row['e_date']?> 
							</td>
						</tr>
					</table>
			
				<p class="tit">결제정보</p>
					<table>
						<caption>수강신청 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th scope="row">회원 아이디</th>
							<td><?=$row[user_id]?></td>
							<th scope="row">회원명</th>
							<td><?=$row[order_name]?></td>
						</tr>
						<tr>
							<th scope="row">전체금액</th>
							<td><?=number_format($row['price_total_org'])?> 원 </td>
							<!--<th scope="row">할인금액</th>
							<td><?=number_format($row['pay_refund'])?> 원 </td>-->
							<th scope="row">결제금액</th>
							<td><?=number_format($row['price_total'])?> 원</td>
						</tr>
						<!--<tr>
							<th scope="row">결제금액</th>
							<td colspan="3"><?=number_format($row['price_total'])?> 원</td>
						</tr>-->
						<tr>
							<th scope="row">결제수단</th>
							<td colspan="3"><?=get_payment_method($row['pay_sect_1'])?></td>
						</tr>
						<tr>
							<th scope="row">요청일시</th>
							<td colspan="3"><?=$row['order_date']?></td>
						</tr>
						<tr>
							<th scope="row">입금상태</th>
							<td colspan="3"><?=get_order_status($row['orderstat'])?></td>
						</tr>
					<?if($row['pay_sect_1'] == "pay_virt"){?>
						<tr>
							<th scope="row">가상계좌 입금은행</th>
							<td colspan="3"><?=$row['pay_bank']?></td>
						</tr>
						<tr>
							<th scope="row">가상계좌 계좌번호</th>
							<td colspan="3"><?=$row['pay_bank_num']?></td>
						</tr>
						<tr>
							<th scope="row">가상계좌 예금주</th>
							<td colspan="3"><?=$row['pay_bank_name']?></td>
						</tr>
					<?}?>
					<?if($row[orderstat] == "com"){?>
						<tr>
							<th scope="row">입금일자</th>
							<td colspan="3"><?=substr($row['payment_date'],0,10)?></td>
						</tr>
					<?}else{?>
						<?if($row[orderstat] == "pre"){?>
						<!--<form name="after_frm" action="order_after_payment_action.php" target="_fra_admin" method="post" >
						<input type="hidden" name="order_num" value="<?=$order_num?>"/>
						<input type="hidden" name="total_param" value="<?=$total_param?>"/>
						<tr>
							<th scope="row">입금완료 설정</th>
							<td colspan="3">입금완료일 : <input type="text" name="payment_date" id="payment_date" style="width:15%;" class="datepicker" value="" required="yes" message="입금완료일" readonly> <a href="javascript:go_after_submit();" class="btn_blue">입금완료 설정하기</a></td>
						</tr>
						</form>-->
						<?}?>
					<?}?>
					</table>

				<?if($row[orderstat] == "can"){?>
					<p class="tit">환불정보</p>
					<table>
						<caption>수강신청 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th scope="row">환불요청 금액</th>
							<td colspan="3"><?=number_format($row['cancel_ing_payment'])?> 원</td>
						</tr>
						<tr>
							<th scope="row">환불요청 일자</th>
							<td colspan="3"><?=$row['cancel_ing_date']?></td>
						</tr>
						<tr>
							<th scope="row">환불완료 금액</th>
							<td colspan="3"><?=number_format($row['cancel_payment'])?> 원</td>
						</tr>
						<tr>
							<th scope="row">환불완료 일자</th>
							<td colspan="3"><?=$row['cancel_date']?></td>
						</tr>
					</table>
				<?}elseif($row[orderstat] == "reing"){
						if($row['com_package'] == "gold"){
							$payback_charge_hal = 50; // 환불 수수료 %
						} elseif($row['com_package'] == "premium"){
							$payback_charge_hal = 20; // 환불 수수료 %
						}
					?>
					<p class="tit">환불요청</p>
					<table>
						<caption>수강신청 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th scope="row">환불요청일자</th>
							<td colspan="3"><?=$row['cancel_ing_date']?></td>
						</tr>
						<tr>
							<th scope="row">환불요청금액</th>
							<td colspan="3"><?=number_format($row['cancel_ing_payment'])?> 원 (결제 금액의 <?=(100-$payback_charge_hal)?>%)</td>
						</tr>
					<form name="cancel_frm" action="order_cancel_payment_action.php" target="_fra_admin" method="post" >
						<input type="hidden" name="order_num" value="<?=$row['order_num']?>"/>
						<input type="hidden" name="total_param" value="<?=$total_param?>"/>
						<tr>
							<th scope="row">환불할 금액</th>
							<td colspan="3"><input type="text" name="cancel_payment" id="cancel_payment" style="width:15%;" value="" required="yes" message="환불할 금액"> 원</td>
						</tr>
						<tr>
							<th scope="row">환불설정</th>
							<td colspan="3">환불일자 : <input type="text" name="cancel_date" id="cancel_date" style="width:15%;" class="datepicker" value="" required="yes" message="환불일자" readonly> <a href="javascript:go_cancel_submit();" class="btn_blue">환불완료 설정하기</a></td>
						</tr>
						<!--<tr>
							<th scope="row">환불할 포인트</th>
							<td colspan="3"><input type="text" name="refund_point" id="refund_point" style="width:15%;" value="" required="yes" message="환불할 포인트"> P</td>
						</tr>-->
					</form>
					</table>
				<?}elseif($row[orderstat] == "com"){?>
					<!--<p class="tit">환불정보</p>
					<table>
						<caption>수강신청 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
					<form name="cancel_frm" action="order_cancel_payment_action.php" target="_fra_admin" method="post" >
						<input type="hidden" name="payment_idx" value="<?=$row['idx']?>"/>
						<input type="hidden" name="total_param" value="<?=$total_param?>"/>
						<tr>
							<th scope="row">환불할 금액</th>
							<td colspan="3"><input type="text" name="cancel_payment" id="cancel_payment" style="width:15%;" value="" required="yes" message="환불할 금액"> 원</td>
						</tr>
						<tr>
							<th scope="row">환불설정</th>
							<td colspan="3">환불일자 : <input type="text" name="cancel_date" id="cancel_date" style="width:15%;" class="datepicker" value="" required="yes" message="환불일자" readonly> <a href="javascript:go_cancel_submit();" class="btn_blue">환불 설정하기</a></td>
						</tr>
						<!--<tr>
							<th scope="row">환불할 포인트</th>
							<td colspan="3"><input type="text" name="refund_point" id="refund_point" style="width:15%;" value="" required="yes" message="환불할 포인트"> P</td>
						</tr>
					</form>
					</table>-->
				<?}?>
				
					<div class="write_btn align_r">
						<a href="javascript:go_list();" class="btn_gray">목록보기</a>
						<!--<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제하기</a>-->	
					</div>

					<p class="tit">관리자 메모</p>
					<table>
					<colgroup>
						<col width="15%" />
						<col width="35%" />
						<col width="15%" />
						<col width="35%" />
					</colgroup>
			
						<form name="admin_memo" action="order_admin_memo_action.php" target="_fra_admin" method="post" >
							<input type="hidden" name="order_num" value="<?=$order_num?>"/>
							<input type="hidden" name="total_param" value="<?=$total_param?>"/>
							<tr>
							<th scope="row">관리자 메모</th>
							<td colspan="3">
								<textarea name="admin_bigo" id="admin_bigo" required="yes" message="관리자 메모" style="width:90%;height:100px;"><?=$row['admin_bigo']?></textarea>
							</td>
							</tr>
						</form>
					</table>
					<div style="margin-top:-20px;margin-bottom:20px;text-align:right;padding-right:10px;"><a href="javascript:go_admin_memo();" class="btn_green">메모저장</a></div>

			
		<!-- content 종료 -->
	</div>
</div>

<!-- //content -->
<script>
function go_cancel_total_2() {
	var check = chkFrm('cancel_total_form_2');
	if(check) {
		if(confirm('취소신청된 주문을 취소완료로 상태를 바꾸시겠습니까?\n\n 고객이 요청한 환급은행과 계좌에 입금하시고 나서 취소완료로 변경 하십시오.')){
			if(confirm('취소완료된 주문은 다시 복구하실수 없습니다.\n\n정말 취소완료로 상태변경을 하시겠습니까?')){
				cancel_total_form_2.submit();
			}
		}
	} else {
		false;
	}
}
<?if($cancel_type == "part" || $cancel_type == "total"){?>
var check  = 0;                                                                            //체크 여부 확인
function CheckAll(){                
	var boolchk;                                                                              //boolean형 변수 
	var chk = document.getElementsByName("order_idx[]")                 //체크박스의 name값
		
		if(check){ check=0; boolchk = false; }else{ check=1; boolchk = true; }    //체크여부에 따른 true/false 설정
			
			for(i=0; i<chk.length;i++){                                                                    
				chk[i].checked = boolchk;                                                                //체크되어 있을경우 설정변경
			}

}

function go_cancel_total() {
	var check = chkFrm('cancel_total_form');
	if(check) {
		if(confirm('주문내용을 취소 하시겠습니까?')){
			if(confirm('한번 취소한 주문은 다시 복구하실수 없습니다.\n\n정말 취소 하시겠습니까?')){
				cancel_total_form.submit();
			}
		}
	} else {
		false;
	}
}

function go_cancel_part() {
	var check = chkFrm('cancel_part_form');
	if(check) {
		if(confirm('주문내용을 취소 하시겠습니까?')){
			if(confirm('한번 취소한 주문은 다시 복구하실수 없습니다.\n\n정말 취소 하시겠습니까?')){
				cancel_part_form.submit();
			}
		}
	} else {
		false;
	}
}

	function cancel_frm_open(){
		$("#cancel_title").show();
		$("#cancel_form").show();
	}
	function cancel_frm_close(){
		$("#cancel_title").hide();
		$("#cancel_form").hide();
	}
	cancel_frm_close();

	function delv_mod_open(){
		$("#delv_mod_title").show();
		$("#delv_mod_form").show();
	}
	function delv_mod_close(){
		$("#delv_mod_title").hide();
		$("#delv_mod_form").hide();
	}
	delv_mod_close();

function go_delv_mod() {
	var check = chkFrm('delv_mod_frm');
	if(check) {
		if(confirm('배송지를 수정 하시겠습니까?')){
			delv_mod_frm.submit();
		}
	} else {
		false;
	}
}
<?}?>
</script>

<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>
<script>
    // 우편번호 찾기 찾기 화면을 넣을 element
   
    function foldDaumPostcode(zip) {
		 var element_wrap = document.getElementById('post_wrap_'+zip+'');
        // iframe을 넣은 element를 안보이게 한다.
        element_wrap.style.display = 'none';
    }

    function execDaumPostcode(zip,ad1,ad2) {
		 var element_wrap = document.getElementById('post_wrap_'+zip+'');
		// 현재 scroll 위치를 저장해놓는다.
        var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = data.address; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 기본 주소가 도로명 타입일때 조합한다.
                if(data.addressType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }

              document.getElementById(''+zip+'').value = data.zonecode;
				  //document.getElementById('zip_code2').value = data.postcode2;
				document.getElementById(''+ad1+'').value = fullAddr;
				 document.getElementById(''+ad2+'').focus();

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_wrap.style.display = 'none';

                // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                document.body.scrollTop = currentScroll;
            },
            // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
            onresize : function(size) {
                element_wrap.style.height = size.height+'px';
            },
            width : '100%',
            height : '100%'
        }).embed(element_wrap);

        // iframe을 넣은 element를 보이게 한다.
        element_wrap.style.display = 'block';
    }
</script>

<? include $_SERVER["DOCUMENT_ROOT"].""."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>