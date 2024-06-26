<?php include "../inc/header.php" ?>
<?
	$mode = trim(sqlfilter($_REQUEST['mode']));
	$mode_sub = trim(sqlfilter($_REQUEST['mode_sub']));
	$idx = trim(sqlfilter($_REQUEST['idx']));
	$add_price = trim(sqlfilter($_REQUEST['add_price']));
	$customer_name = trim(sqlfilter($_REQUEST['customer_name']));
	$receive_email = trim(sqlfilter($_REQUEST['receive_email']));

	$sql = "select *,(select user_name from member_info where 1 and idx=".$mode.".member_idx) as customer_name,(select cate_name1 from paper_info where 1 and cate_type=".$mode.".pro_type and paper_type='paper' and  cate_code1=".$mode.".paper_cover_1 and cate_level = '1') as paper_name_1,(select cate_name2 from paper_info where 1 and cate_type=".$mode.".pro_type and paper_type='paper' and  cate_code2=".$mode.".paper_cover_2 and cate_level = '2') as paper_name_2,(select cate_name3 from paper_info where 1 and cate_type=".$mode.".pro_type and paper_type='paper' and  cate_code3=".$mode.".paper_cover_3 and cate_level = '3') as paper_name_3 from ".$mode." where 1 and idx='".$idx."'";

	//echo $sql;
	
	$query = mysqli_query($gconnet,$sql);
	$row = mysqli_fetch_array($query);
		
	if(!$customer_name){
		$customer_name = $row[customer_name];
	}
?>
<script src="/js/html2canvas.js"></script>
<script src="/js/canvas2image.js"></script>
<?
############ 메일에 첨부할 이미지 만들기 #########
$chHtml = '
	<div class="popup_wrap" id="print_this_area">
	
		<p class="popup_title" >견 적 서</p>

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
						<span class="date">'.date("Y").'년 '.date("m").'월 '.date("d").'일</span>
					</td>
				</tr>
				<tr>
					<th scope="row">수　신</th>
					<td>
						<span class="num">
							'.$customer_name.' 귀하
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
						<span class="fax">02-2272-7377</span>
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
					공급가 <span>'.number_format($row[pro_total_price]).'</span>원
				</dd>
				';
				if($add_price){
					$chHtml .= '<dd class="plus" style="width:24%;">';
				} else{	
					$chHtml .= '<dd style="width:24%;">';
				}
				$chHtml .= '부가세 <span>'.number_format($row[pro_total_price_vat]).'</span>원</dd>';
				
				if($add_price){
					$chHtml .= '<dd style="width:20%;">
						추가비 <span>'.number_format($add_price).'</span>원
					</dd>';
				}
				$chHtml .= '<dd class="total" style="width:27%;">
					총 금액 <span>'.number_format($row[payment_total_price]+$add_price).'</span>원
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
							<span class="product">'.pro_type($row[pro_type],$row[pro_cate1],$row[pro_cate2]).'</span>
						</td>
					</tr>
					<tr>
						<th scope="row">재질(표지)</th>
						<td>
							<span class="material">'.$row[paper_name_1].' '.$row[paper_name_2].' '.$row[paper_name_3].'</span>
						</td>
					</tr>
				';
					$sub_sql = "select *,(select cate_name1 from paper_info where 1 and cate_type=".$mode_sub.".pro_type and paper_type='paper' and  cate_code1=".$mode_sub.".paper_inner_1 and cate_level = '1') as paper_name_1,(select cate_name2 from paper_info where 1 and cate_type=".$mode_sub.".pro_type and paper_type='paper' and  cate_code2=".$mode_sub.".paper_inner_2 and cate_level = '2') as paper_name_2,(select cate_name3 from paper_info where 1 and cate_type=".$mode_sub.".pro_type and paper_type='paper' and  cate_code3=".$mode_sub.".paper_inner_3 and cate_level = '3') as paper_name_3 from ".$mode_sub." where 1 and ".$mode."_idx='".$row[idx]."'";
					$sub_query = mysqli_query($gconnet,$sub_sql);
					
					for($i_sub=0; $i_sub<mysqli_num_rows($sub_query); $i_sub++){
						$row_sub = mysqli_fetch_array($sub_query);
					
						$chHtml .= '
							<tr>
								<th scope="row">재질(내지)</th>
								<td>
									<span class="material">'.$row_sub[paper_name_1].' '.$row_sub[paper_name_2].' '.$row_sub[paper_name_3].'</span>
								</td>
							</tr>
						';
					}

					$size_input_arr = explode("/",$row[size_input]);
					$pront_color_arr = explode("/",$row[pront_color]);
					$after_color_arr = explode("/",$row[after_color]);

					$pront_color_sql = "select txt_1 from product_info_add where 1 and idx='".$pront_color_arr[0]."'";
					$pront_color_query = mysqli_query($gconnet,$pront_color_sql);
					$pront_color_row = mysqli_fetch_array($pront_color_query);

					$after_color_sql = "select txt_1 from product_info_add where 1 and idx='".$after_color_arr[0]."'";
					$after_color_query = mysqli_query($gconnet,$after_color_sql);
					$after_color_row = mysqli_fetch_array($after_color_query);

					if($pront_color_arr[2]){
						$pront_color_uv = "UV ";
					}
					if($pront_color_arr[3]){
						$pront_color_etc = "+".$pront_color_arr[3];
					}
					$pront_color_txt = $pront_color_row[txt_1]." ".$pront_color_uv.$pront_color_arr[1]." 도 ".$pront_color_etc;

					if($after_color_arr[2]){
						$after_color_uv = "UV ";
					}
					if($after_color_arr[3]){
						$after_color_etc = "+".$after_color_arr[3];
					}
					$after_color_txt = $after_color_row[txt_1]." ".$after_color_uv.$after_color_arr[1]." 도 ".$after_color_etc;

					if($row[pro_type] == "digit"){
						$ctp_name = "기본옵션비";
					} else {
						$ctp_name = "판비";
					}
				
					$chHtml .= '
					<tr>
						<th scope="row">규격</th>
						<td>
							<span class="size">'.strtoupper($size_input_arr[0]).' ('.$row[size_width].'X'.$row[size_height].')</span>
						</td>
					</tr>
					<tr>
						<th scope="row">수량</th>
						<td>
							<span class="amount">';
							if($row[quantity_cnt]){ 
								$chHtml .= ''.number_format($row[quantity]).'매 '.number_format($row[quantity_cnt]).'건';
							}else{
								$chHtml .= ''.number_format($row[quantity]).'부';
							}
							$chHtml .= '</span>';

						$chHtml .= '</td>
					</tr>
					<tr>
						<th scope="row">인쇄컬러 앞</th>
						<td>
							<span class="front">'.$pront_color_txt.'</span>
						</td>
					</tr>
					<tr>
						<th scope="row">안쇄컬러 뒤</th>
						<td>
							<span class="back">'.$after_color_txt.'</span>
						</td>
					</tr>
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
						<td>'.number_format($row[paper_price]).'원</td>
					</tr>';
				if($row[ctp_price]){
					$chHtml .='<tr>
						<th scope="row">'.$ctp_name.'</th>
						<td>'.number_format($row[ctp_price]).'원</td>
					</tr>';
				}
				if($row[color_price]){
					$chHtml .='<tr>
						<th scope="row">인쇄비</th>
						<td>'.number_format($row[color_price]).'원</td>
					</tr>';
				}
				if($row[coating_price]){
					$chHtml .='<tr>
						<th scope="row">코팅비</th>
						<td>'.number_format($row[coating_price]).'원</td>
					</tr>';
				}
				if($row[jebon_price]){
					$chHtml .='<tr>
						<th scope="row">제본비</th>
						<td>'.number_format($row[jebon_price]).'원</td>
					</tr>';
				}
				$chHtml .='	<tr>
						<th scope="row">주문건</th>
						<td>1건</td>
					</tr>
					<tr>
						<th scope="row">합계</th>
						<td>'.number_format($row[pro_total_price]).'원</td>
					</tr>
					<tr>
						<th scope="row">부가세</th>
						<td>'.number_format($row[pro_total_price_vat]).'원</td>
					</tr>
					<tr>
						<th scope="row">추가비용</th>
						<td>
							'.number_format($add_price).'원
						</td>
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
				<tr>
					<th scope="row">제본</th>
					<td>'.$row[jebon_type].'</td>
				</tr>
				';
				if($row[pro_type] == "digit"){
					$sql_dopt = "select cate_name1 from paper_info where 1 and cate_type='".$row[pro_type]."' and paper_type='gibon' and  cate_code1='".$row[after_color]."' and cate_level = '1'";
					$query_dopt = mysqli_query($gconnet,$sql_dopt);
					$row_dopt = mysqli_fetch_array($query_dopt);
					$chHtml .='<tr>
						<th scope="row">기본옵션</th>
						<td>'.$row_dopt[cate_name1].'</td>
					</tr>';
					if($row[after_color] == "pap2418"){
						$chHtml .='<tr>
							<th scope="row">옵션텍스트</th>
							<td>'.$row[size_top].'</td>
						</tr>';
						}
				}
				$chHtml .='	<tr>
						<th scope="row">코팅</th>
						<td>';
						if($row[pro_type] == "indig" || $row[pro_type] == "purch"){
							$chHtml .=''.$row[cover_coating].'';
						}else{
							if($row[cover_coating] == "Y"){
								$chHtml .='코팅추가';
							}else{
								$chHtml .='코팅없음';
							}
						}
						$chHtml .='</td>
					</tr>';
				if($row[pro_cate2] == "pt0014" || $row[pro_cate2] == "pt0013" || $row[pro_cate2] == "pt0012"){
					$chHtml .='<tr>
						<th scope="row">타공</th>
						<td>';
						if($row[cover_osi] == "Y"){
							$chHtml .='타공추가';
						}else{
							$chHtml .='타공없음';
						}
						$chHtml .='</td>
					</tr>';
				}else{
					$chHtml .='<tr>
						<th scope="row">오시</th>
						<td>';
					if($row[pro_type] == "purch"){
						$chHtml .=''.$row[cover_osi].'';
					} else {
						if($row[cover_osi] == "Y"){
							$chHtml .='오시추가';
						}else{
							$chHtml .='오시없음';
						}
					}
						$chHtml .='</td>
					</tr>';
				}
				if($row[pro_cate2]=="pt0004" || $row[pro_cate1]=="pt0021"){
					$chHtml .='<tr>
						<th scope="row">형압</th>
						<td>';
						if($row[cover_jup] == "Y"){
							$chHtml .='형압추가';
						}else{
							$chHtml .='형압없음';
						}
						$chHtml .='</td>
					</tr>';
				}else{
					$chHtml .='<tr>
						<th scope="row">접지</th>
						<td>';
					if($row[pro_type] == "purch"){
						$chHtml .=''.$row[cover_jup].'';
					} else {
						if($row[cover_jup] == "Y"){
							$chHtml .='접지추가';
						}else{
							$chHtml .='접지없음';
						}
					}
						$chHtml .='</td>
					</tr>';
				}
				$chHtml .='	<tr>
						<th scope="row">박</th>
						<td>'.$row[cover_bak].' '.$row[cover_bak_w].'cm X '.$row[cover_bak_d].'cm</td>
					</tr>';
				if($row[pro_cate1] == "pt0020" || $row[pro_cate1] == "pt0018"){
					$chHtml .='	<tr>
						<th scope="row">재단</th>
						<td>'.$row[cover_osi2].'</td>
					</tr>';
				}
				$chHtml .='
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
</div>
';
############ 메일에 첨부할 이미지 만들기 끝 #########
echo $chHtml; //exit;
$mail_orgnum = time()."_".randomChar(5);
?>
<script type='text/javascript'>
<!--
	function  html2img(){
		var canvas ="";
		html2canvas($("#print_this_area"), {
		onrendered: function(canvas) {

		$("#imgurl").val(canvas.toDataURL("image/png"));

		//Canvas2Image.saveAsPNG(canvas); 
		//canvas2image.js를 사용하여 바로 저장하려고 했으나 IE에서 이게 안돼서 아래와 같은 방법으로 저장함.

		$("#downform").submit();
		}
		});
	}

	$(document).ready(function(){
		html2img();
	});
//-->
</script>

<form id="downform" action="popup_estimate_action_img.php" method="post" target="downiframe">
    <input type="hidden" name="imgurl" id="imgurl" />
	<input type="hidden" name="mail_orgnum" id="mail_orgnum" value="<?=$mail_orgnum?>"/>
</form>

<iframe name="downiframe" width="500" height="500" style="display:none;"></iframe>

<?
	$FROMNAME = "친절한인선생";
	$FROMEMAIL = "info@teacherin.co.kr";  //관리자 메일 수정요함!
	$SUBJECT = "[친절한인선생] 견적서가 도착했습니다.";

	$tomail = $receive_email;
				
	$content = '
		<img src="http://'.$_SERVER['HTTP_HOST'].'/upload_file/estimate_attach_file/'.$mail_orgnum.'.png" alt="estimate" style="border:0;"/>		
	';

	$pwd_mail = mail_utf($FROMEMAIL,$FROMNAME,$tomail, $SUBJECT, $content); // 메일을 발송한다.
	
?>