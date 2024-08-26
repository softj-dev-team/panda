<!DOCTYPE html>
<html lang="ko">
<head>
	<?php include "../inc/header.php" ?>
	<?
		$mode = trim(sqlfilter($_REQUEST['mode']));
		$mode_sub = trim(sqlfilter($_REQUEST['mode_sub']));
		$idx = trim(sqlfilter($_REQUEST['idx']));
		
		$sql = "select *,(select order_name from order_member where 1 and order_num=".$mode.".order_num) as order_name,(select user_id from order_member where 1 and order_num=".$mode.".order_num) as user_id,(select order_date from order_member where 1 and order_num=".$mode.".order_num) as order_date,(select cate_name1 from paper_info where 1 and cate_type=".$mode.".pro_type and paper_type='paper' and  cate_code1=".$mode.".paper_cover_1 and cate_level = '1') as paper_name_1,(select cate_name2 from paper_info where 1 and cate_type=".$mode.".pro_type and paper_type='paper' and  cate_code2=".$mode.".paper_cover_2 and cate_level = '2') as paper_name_2,(select cate_name3 from paper_info where 1 and cate_type=".$mode.".pro_type and paper_type='paper' and  cate_code3=".$mode.".paper_cover_3 and cate_level = '3') as paper_name_3 from ".$mode." where 1 and idx='".$idx."'";
		$query = mysqli_query($gconnet,$sql);
		$row = mysqli_fetch_array($query);
	?>
</head>
<body onload="window.print();">
	<div class="popup_wrap">
		<p class="popup_title">거 래 명 세 표</p>
		<div class="table_wrap">
			<table class="table_l">
				<caption>고객</caption>
				<colgroup>
					<col style="width:33%;">
					<col style="width:67%;">
				</colgroup>
				<tr>
					<th scope="row">고 객 명</th>
					<td>
						<span class="name"><?=$row[order_name]?> <?if($row[user_id]){?>(<?=$row[user_id]?>)<?}?></span>
					</td>
				</tr>
				<tr>
					<th scope="row">접수번호</th>
					<td>
						<span class="num"><?=$row[order_num]?></span>
					</td>
				</tr>
				<tr>
					<th scope="row">접수시간</th>
					<td>
						<span class="time"><?=substr($row[order_date],0,10)?></span>
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
					<th scope="row">업　　　태</th>
					<td>
						<span class="type">제조, 서비스</span>
					</td>
					<th scope="row">종목</th>
					<td>
						<span class="event">인쇄업</span>
					</td>
				</tr>
			</table>
		</div>
		<div class="price_box">
			<dl>
				<dd class="plus">
					공급가 <span><?=number_format($row[pro_total_price])?></span>원
				</dd>
				<dd>
					부가세 <span><?=number_format($row[pro_total_price_vat])?></span>원
				</dd>
				<dd class="total">
					총 금액 <span><?=number_format($row[payment_total_price])?></span>원
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
						<th scope="col" colspan="2">재질 및 규격</th>
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
				</tbody>
			</table>
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
		</div>
		<div class="popup_b">
			<span class="date"><?=date("Y")?>년 <?=date("m")?>월 <?=date("d")?>일</span>
			<span class="txt">상기 내용을 영수합니다.</span>
			<div class="info">
				<ul>
					<li>
						배송비(택배, 화물, 퀵)는 별도입니다.
					</li>
					<li>
						모든 인쇄물 작업은 가나프린트 작업 가이드에 준하여 작업하셔야 하며 이에 부합하지 않은 작업에 대해서는
						책임을 지지 않습니다.
					</li>
				</ul>
			</div>
		</div>
	</div>
</body>
</html>