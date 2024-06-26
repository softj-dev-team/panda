<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include "../inc/header.php" ?>
	<?
		$mode = trim(sqlfilter($_REQUEST['mode']));
		$mode_sub = trim(sqlfilter($_REQUEST['mode_sub']));
		$idx = trim(sqlfilter($_REQUEST['idx']));
		$add_price = trim(sqlfilter($_REQUEST['add_price']));
		$customer_name = trim(sqlfilter($_REQUEST['customer_name']));

		$sql = "select *,(select user_name from member_info where 1 and idx=".$mode.".member_idx) as customer_name,(select cate_name1 from paper_info where 1 and cate_type=".$mode.".pro_type and paper_type='paper' and  cate_code1=".$mode.".paper_cover_1 and cate_level = '1') as paper_name_1,(select cate_name2 from paper_info where 1 and cate_type=".$mode.".pro_type and paper_type='paper' and  cate_code2=".$mode.".paper_cover_2 and cate_level = '2') as paper_name_2,(select cate_name3 from paper_info where 1 and cate_type=".$mode.".pro_type and paper_type='paper' and  cate_code3=".$mode.".paper_cover_3 and cate_level = '3') as paper_name_3 from ".$mode." where 1 and idx='".$idx."'";
		$query = mysqli_query($gconnet,$sql);
		$row = mysqli_fetch_array($query);
		
		if(!$customer_name){
			$customer_name = $row[customer_name];
		}
	?>
<script type="text/javascript" src="/js/printThis.js"></script>
</head>
<body>
	<div class="popup_wrap">

	<div id="print_this_area"> <!-- 프린트 에이리어 시작 -->
	
		<p class="popup_title" >견 적 서</p>

		<form method="post" action="popup_estimate.php" name="frm" id="frm" target="_self">
			<input type="hidden" name="mode" value="<?=$mode?>">
			<input type="hidden" name="mode_sub" value="<?=$mode_sub?>">
			<input type="hidden" name="idx" value="<?=$idx?>">
		
		<div class="table_wrap">
			<table class="table_l">
				<caption>견적서</caption>
				<colgroup>
					<col style="width:33%;">
					<col style="width:67%;">
				</colgroup>
				<tr>
					<th scope="row">견적일</th>
					<td>
						<span class="date"><!--<?=substr($row[wdate],0,4)?>년 <?=substr($row[wdate],5,2)?>월 <?=substr($row[wdate],8,2)?>일--><?=date("Y")?>년 <?=date("m")?>월 <?=date("d")?>일</span>
					</td>
				</tr>
				<tr>
					<th scope="row">수　신</th>
					<td>
						<span class="num">
							<input type="text" id="customer_name" name="customer_name" value="<?=$customer_name?>" placeholder="수신자 성명" style="width:50%;" onblur="frm.submit();"> 귀하
						</span>
					</td>
				</tr>
			</table>
			<table class="table_r">
				<caption>상호</caption>
				<colgroup>
					<col style="width:20%;">
					<col style="width:30%;">
					<col style="width:20%;">
					<col style="width:30%;">
				</colgroup>
				<tr>
					<th scope="row">사업자번호</th>
					<td colspan="3">
						<span class="company_num">201-85-22977</span>
					</td>
				</tr>
				<tr>
					<th scope="row">상　　　호</th>
					<td>
						<span class="company_name">(주)가나씨앤피</span>
					</td>
					<th scope="row">성명</th>
					<td>
						<span class="name">조순호</span>
						<img src="../images/common/img_stamp.png" alt="img_stamp">
					</td>
				</tr>
				<tr>
					<th scope="row">사업장주소</th>
					<td colspan="3">
						<span class="address">서울시 중구 충무로 29 (초동) 아시아미디어타위 303호</span>
					</td>
				</tr>
				<tr>
					<th scope="row">대 표 전 화</th>
					<td colspan="3">
						<span class="call">02-2272-7377</span>
					</td>
				</tr>
				<tr>
					<th scope="row">팩　　　스</th>
					<td colspan="3">
						<span class="fax">02-2272-1145</span>
					</td>
				</tr>
				<tr>
					<th scope="row">홈 페 이 지</th>
					<td colspan="3">
						<span class="homepage">teacherin.co.kr</span>
					</td>
				</tr>
			</table>
		</div>
		<span class="txt">아래와 같이 견적합니다.</span>
		<div class="price_box" style="margin-top:0;">
			<dl>
				<dd class="plus" style="width:29%;">
					공급가 <span><?=number_format($row[pro_total_price])?></span>원
				</dd>
				<?if($add_price){?>
					<dd class="plus" style="width:24%;">
				<?}else{?>
					<dd style="width:24%;">
				<?}?>
					부가세 <span><?=number_format($row[pro_total_price_vat])?></span>원
				</dd>
				<?if($add_price){?>
				<dd style="width:20%;">
					추가비 <span><?=number_format($add_price)?></span>원
				</dd>
				<?}?>
				<dd class="total" style="width:27%;">
					총 금액 <span><?=number_format($row[payment_total_price]+$add_price)?></span>원
				</dd>
			</dl>
		</div>
		<div class="table_wrap1">
			<table class="table_1">
				<caption>재질 및 규격</caption>
				<colgroup>
					<col style="width:45%;">
					<col style="width:55%;">
				</colgroup>
				<thead>
					<tr>
						<th scope="col" colspan="2">재질 및 규격</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row">품명</th>
						<td>
							<span class="product"><?=pro_type($row[pro_type],$row[pro_cate1],$row[pro_cate2])?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">재질(표지)</th>
						<td>
							<span class="material"><?=$row[paper_name_1]?> <?=$row[paper_name_2]?> <?=$row[paper_name_3]?></span>
						</td>
					</tr>
				<?
					$sub_sql = "select *,(select cate_name1 from paper_info where 1 and cate_type=".$mode_sub.".pro_type and paper_type='paper' and  cate_code1=".$mode_sub.".paper_inner_1 and cate_level = '1') as paper_name_1,(select cate_name2 from paper_info where 1 and cate_type=".$mode_sub.".pro_type and paper_type='paper' and  cate_code2=".$mode_sub.".paper_inner_2 and cate_level = '2') as paper_name_2,(select cate_name3 from paper_info where 1 and cate_type=".$mode_sub.".pro_type and paper_type='paper' and  cate_code3=".$mode_sub.".paper_inner_3 and cate_level = '3') as paper_name_3 from ".$mode_sub." where 1 and ".$mode."_idx='".$row[idx]."'";
					$sub_query = mysqli_query($gconnet,$sub_sql);
					for($i_sub=0; $i_sub<mysqli_num_rows($sub_query); $i_sub++){
						$row_sub = mysqli_fetch_array($sub_query);
				?>
					<tr>
						<th scope="row">재질(내지)</th>
						<td>
							<span class="material"><?=$row_sub[paper_name_1]?> <?=$row_sub[paper_name_2]?> <?=$row_sub[paper_name_3]?></span>
						</td>
					</tr>
				<?}?>
				<?
					$size_input_arr = explode("/",$row[size_input]);
					$pront_color_arr = explode("/",$row[pront_color]);
					$after_color_arr = explode("/",$row[after_color]);

					$pront_color_sql = "select txt_1 from product_info_add where 1 and idx='".$pront_color_arr[0]."'";
					$pront_color_query = mysqli_query($gconnet,$pront_color_sql);
					$pront_color_row = mysqli_fetch_array($pront_color_query);

					$after_color_sql = "select txt_1 from product_info_add where 1 and idx='".$after_color_arr[0]."'";
					$after_color_query = mysqli_query($gconnet,$after_color_sql);
					$after_color_row = mysqli_fetch_array($after_color_query);
				?>
					<tr>
						<th scope="row">규격</th>
						<td>
							<span class="size"><?=strtoupper($size_input_arr[0])?> (<?=$row[size_width]?>X<?=$row[size_height]?>)</span>
						</td>
					</tr>
					<tr>
						<th scope="row">수량</th>
						<td>
							<span class="amount">
							<?if($row[quantity_cnt]){?> 
								<?=number_format($row[quantity])?>매 <?=number_format($row[quantity_cnt])?>건
							<?}else{?>
								<?=number_format($row[quantity])?>부
							<?}?>
							</span>
						</td>
					</tr>
				<?if($row[pro_type] == "purch"){?>
					<tr>
						<th scope="row">인쇄컬러 앞</th>
						<td>
							<span class="front"><?=$pront_color_row[txt_1]?> <?if($pront_color_arr[2]){?>UV <?}?><?=$pront_color_arr[1]?>도 <?if($pront_color_arr[3]){?>+<?=$pront_color_arr[3]?><?}?></span>
						</td>
					</tr>
					<tr>
						<th scope="row">인쇄컬러 뒤</th>
						<td>
							<span class="back"><?=$after_color_row[txt_1]?> <?if($after_color_arr[2]){?>UV <?}?><?=$after_color_arr[1]?>도 <?if($after_color_arr[3]){?>+<?=$after_color_arr[3]?><?}?></span>
						</td>
					</tr>
				<?}elseif($row[pro_type] == "indig"){?>
					<tr>
						<th scope="row">인쇄도수</th>
						<td>
							<span class="front"><?=$row[pront_color]?></span>
						</td>
					</tr>
				<?}?>
				</tbody>
			</table>
			<table class="table_2">
				<caption>재질 및 규격</caption>
				<colgroup>
					<col style="width:40%;">
					<col style="width:60%;">
				</colgroup>
				<thead>
					<tr>
						<th scope="col" colspan="2">인쇄 세부항목</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th scope="row">용지대</th>
						<td><?=number_format($row[paper_price])?>원</td>
					</tr>
				<?if($row[ctp_price]){?>
					<tr>
						<th scope="row"><?if($row[pro_type] == "digit"){?>기본옵션비<?}else{?>판비<?}?></th>
						<td><?=number_format($row[ctp_price])?>원</td>
					</tr>
				<?}?>
				<?if($row[color_price]){?>
					<tr>
						<th scope="row">인쇄비</th>
						<td><?=number_format($row[color_price])?>원</td>
					</tr>
				<?}?>
				<?if($row[coating_price]){?>
					<tr>
						<th scope="row">코팅비</th>
						<td><?=number_format($row[coating_price])?>원</td>
					</tr>
				<?}?>
				<?if($row[jebon_price]){?>
					<tr>
						<th scope="row">제본비</th>
						<td><?=number_format($row[jebon_price])?>원</td>
					</tr>
				<?}?>
					<tr>
						<th scope="row">주문건</th>
						<td>1건</td>
					</tr>
					<tr>
						<th scope="row">합계</th>
						<td><?=number_format($row[pro_total_price])?>원</td>
					</tr>
					<tr>
						<th scope="row">부가세</th>
						<td><?=number_format($row[pro_total_price_vat])?>원</td>
					</tr>
					<tr>
						<th scope="row">추가비용</th>
						<td>
							<input type="text" id="add_price" name="add_price" value="<?=$add_price?>" placeholder="추가비용 입력" onblur="frm.submit();">
						</td>
					</tr>
				</tbody>
			</table>

			</form>

			<table class="table_3">
				<caption>후가공 내영</caption>
				<colgroup>
					<col style="width:45%;">
					<col style="width:55%;">
				</colgroup>
				<thead>
					<tr>
						<th scope="col" colspan="2">후가공 내역</th>
					</tr>
				</thead>
				<tbody>
				<?if($row[pro_type] == "digit"){
					$sql_dopt = "select cate_name1 from paper_info where 1 and cate_type='".$row[pro_type]."' and paper_type='gibon' and  cate_code1='".$row[after_color]."' and cate_level = '1'";
					$query_dopt = mysqli_query($gconnet,$sql_dopt);
					$row_dopt = mysqli_fetch_array($query_dopt);
					?>
					<tr>
						<th scope="row">기본옵션</th>
						<td>
							<?=$row_dopt[cate_name1]?>
						</td>
					</tr>
					<?if($row[after_color] == "pap2418"){?>
					<tr>
						<th scope="row">옵션텍스트</th>
						<td>
							<?=$row[size_top]?>
						</td>
					</tr>
					<?}?>
				<?}?>
					<tr>
						<th scope="row">제본</th>
						<td>
							<?=$row[jebon_type]?>
						</td>
					</tr>
					<tr>
						<th scope="row">코팅</th>
						<td>
						<?if($row[pro_type] == "indig" || $row[pro_type] == "purch"){?>
							<?=$row[cover_coating]?>
						<?}else{?>
							<?if($row[cover_coating] == "Y"){?>
								코팅추가
							<?}else{?>
								코팅없음
							<?}?>
						<?}?>
						</td>
					</tr>
				<?if($row[pro_cate2] == "pt0014" || $row[pro_cate2] == "pt0013" || $row[pro_cate2] == "pt0012"){?>
					<tr>
						<th scope="row">타공</th>
						<td>
						<?if($row[cover_osi] == "Y"){?>
							타공추가
						<?}else{?>
							타공없음
						<?}?>
						</td>
					</tr>
				<?}else{?>
					<tr>
						<th scope="row">오시</th>
						<td>
					<?if($row[pro_type] == "purch"){?>
							<?=$row[cover_osi]?>
					<?}else{?>
						<?if($row[cover_osi] == "Y"){?>
							오시추가
						<?}else{?>
							오시없음
						<?}?>
					<?}?>
						</td>
					</tr>
				<?}?>
				<?if($row[pro_cate2]=="pt0004" || $row[pro_cate1]=="pt0021"){?>
					<tr>
						<th scope="row">형압</th>
						<td>
						<?if($row[cover_jup] == "Y"){?>
							형압추가
						<?}else{?>
							형압없음
						<?}?>
						</td>
					</tr>
				<?}else{?>
					<tr>
						<th scope="row">접지</th>
						<td>
					<?if($row[pro_type] == "purch"){?>
							<?=$row[cover_jup]?>
					<?}else{?>
						<?if($row[cover_jup] == "Y"){?>
							접지추가
						<?}else{?>
							접지없음
						<?}?>
					<?}?>
						</td>
					</tr>
				<?}?>
					<tr>
						<th scope="row">박</th>
						<td><?=$row[cover_bak]?> <?=$row[cover_bak_w]?>cm X <?=$row[cover_bak_d]?>cm</td>
					</tr>
				<?if($row[pro_cate1] == "pt0020" || $row[pro_cate1] == "pt0018"){?>
					<tr>
						<th scope="row">재단</th>
						<td><?=$row[cover_osi2]?></td>
					</tr>
				<?}?>
				</tbody>
			</table>
			<ul class="account_num">
				<li>
					<strong>계좌번호 :</strong> <span>국민은행 003101-04-120207</span>
				</li>
				<li>
					<strong>예금주 :</strong> <span>(주)가나씨앤피</span>
				</li>
			</ul>
		</div>

	</div> <!-- 프린트 에이리어 종료 -->

		<form method="post" action="popup_estimate_action.php" name="frm_order" id="frm_order" target="estimate_fra">
			<input type="hidden" name="mode" value="<?=$mode?>">
			<input type="hidden" name="mode_sub" value="<?=$mode_sub?>">
			<input type="hidden" name="idx" value="<?=$row[idx]?>">
			<input type="hidden" name="customer_name" value="<?=$customer_name?>">
			<input type="hidden" name="add_price" value="<?=$add_price?>">

		<div class="popup_b">
			<div class="info">
				<ul>
					<li>본 견적의 유효기간은 견적일로부터 15일 입니다.</li>
					<li>본 견적의 사양은 배송비 별도이고, 디자인 의뢰시 디자인 금액은 합산됩니다.</li>
					<li>본 견적은 100% 선입금시 진행되며, 입금은 발주를 의미합니다.<br>(단, 입금 방식은 견적 담당자와 상의할 수 있습니다.)</li>
					<li>본 견적은 사양과 작업 난이도에 따라 가격 변동될 수 있습니다.</li>
				</ul>
			</div>
			<div class="btn_wrap">
				<button type="button" class="btn_gray" onclick="email_send_v();">이메일로 보내기</button>
				<button type="button" class="btn_gray" onclick="print_this();">견적서 인쇄</button>
				<button type="button" class="btn_white" onClick="self.close();">창 닫기</button>
			</div>

			<div class="btn_wrap" id="email_send" style="display:none;">
				<input type="text" name="receive_email" id="receive_email" style="width:80%;" required="yes" message="수신메일" is_email="yes" placeholder="수신 이메일을 입력해주세요"> 
				<div style="margin-top:10px;"><button type="button" class="btn_gray" onclick="go_submit_sheet();">메일발송</button> <button type="button" class="btn_white" onclick="email_send_c();">발송취소</button></div>
			</div>

		</div>
	
	</form>

  </div>

<script language=javascript>
<!--
function print_this(){
	//$("#print_this_area").printThis();
	var cus_name = $("#customer_name").val();
	var ad_price = $("#add_price").val();
	window.open('popup_estimate_print.php?mode=<?=$mode?>&mode_sub=<?=$mode_sub?>&idx=<?=$idx?>&customer_name='+cus_name+'&add_price='+ad_price+'','popup','width=650, height=750, left=200, top=100, scrollbars=no');
}

function email_send_v(){
	$("#email_send").show();
}

function email_send_c(){
	$("#receive_email").val("");
	$("#email_send").hide();
}

function go_submit_sheet() {
	var check = chkFrm('frm_order');
	if(check) {
		document.getElementById("estimate_fra").style.display = "block";
		frm_order.submit();
	} else {
		false;
	}
}
//-->
</script>

<iframe name="estimate_fra" id="estimate_fra" width="650" height="750" frameborder="0" scrolling="yes" marginheight="0" marginwidth="0" style="display:none;"></iframe>

</body>
</html>