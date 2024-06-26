<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>

<? include $_SERVER["DOCUMENT_ROOT"]."/yonex_master/include/check_login.php"; // 관리자 로그인여부 확인?>
<? include $_SERVER["DOCUMENT_ROOT"]."/yonex_master/include/admin_top.php"; // 관리자페이지 상단메뉴?>
<? include $_SERVER["DOCUMENT_ROOT"]."/yonex_master/include/product_left.php"; // 사이트설정 좌측메뉴?>

<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
$v_sect = trim(sqlfilter($_REQUEST['v_sect']));
$s_group = trim(sqlfilter($_REQUEST['s_group']));

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = urldecode(sqlfilter($_REQUEST['keyword']));

$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));
$s_sect3 = trim(sqlfilter($_REQUEST['s_sect3']));
$s_sect4 = trim(sqlfilter($_REQUEST['s_sect4']));

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&v_sect='.$v_sect.'&s_group='.$s_group.'&field='.$field.'&keyword='.$keyword.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_sect3='.$s_sect3.'&s_sect4='.$s_sect4;

if($v_sect == "Time Sale"){
	$sect_title = "타임세일";
} else {
	if($s_sect1 == "pc"){
		$sect_title = "PC";
	} elseif($s_sect1 == "mobile"){
		$sect_title = "모바일";
	}
}
?>

<script language="JavaScript"> 
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			/*if($("#link_sect_2").prop("checked") == true) {// 링크 주소 별도입력
				if($("#link_url").val() == ""){
					alert("링크주소를 입력해 주세요.");
					return;
				}
			}*/

			frm.submit();

		} else {
			false;
		}
	}
	
	function go_list(){
		location.href = "mainban_list.php?<?=$total_param?>";
	}

	function main_product_pop(){
		//location.href = 
		window.open("main_product.php","pro_pro_view", "top=100,left=100,scrollbars=yes,resizable=no,width=1010,height=500");
	}

	function link_ck() { 
		/*if (document.frm.link_sect.link_sect_1.checked) { // 개별 상품 링크
			link_sect_txt1.style.display = '';
			link_sect_txt2.style.display = 'none';
		} else*/ if($("#link_sect_2").prop("checked") == true) { // 링크 주소 별도입력
			link_sect_txt1.style.display = 'none';
			link_sect_txt2.style.display = '';
		}  else if($("#link_sect_3").prop("checked") == true) { // 링크없음
			link_sect_txt1.style.display = 'none';
			link_sect_txt2.style.display = 'none';
		}
	} 
	
function Display_1(form){
	
	var target1 = document.all['banner_size_txt1'];

	if(form.main_sect.value == "topsch_right"){
		target1.innerText = "가로 : 140 픽셀, 세로 : 54 픽셀";
	} else if (form.main_sect.value == "flash_right"){
		target1.innerText = "가로 : 190 픽셀, 세로 : 260 픽셀";
	} else if (form.main_sect.value == "new_left"){
		target1.innerText = "가로 : 181 픽셀, 세로 : 176 픽셀";
	} else if (form.main_sect.value == "new_right"){
		target1.innerText = "가로 : 181 픽셀, 세로 : 203 픽셀";
	} else if (form.main_sect.value == "new_down"){
		target1.innerText = "가로 : 313 픽셀, 세로 : 103 픽셀";
	}
	
}
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/exper_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>체험관리</li>
						<li>메인화면 체험배치</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>메인화면 체험배치 설정</h3>
				</div>
				<div class="write">
			
			<form name="frm" action="mainban_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
			<input type="hidden" name="total_param" value="<?=$total_param?>"/>
			<input type="hidden" name="s_sect1" value="<?=$s_sect1?>"/>
			<input type="hidden" name="main_sect" value="exp"/>
					
			<table class="t_view">
				<colgroup>
					<col width="10%" />
					<col width="40%" />
					<col width="10%" />
					<col width="40%" />
				</colgroup>
				<input type="hidden" name="pro_name" value=""/>
				<input type="hidden" name="pro_idx" value=""/>
			 
				<?if($v_sect != "Time Sale"){?>		
					<tr>
						<th >메인위치</th>
						<td colspan="3">
							<select name="main_type" size="1" style="vertical-align:middle;" required="yes"  message="메인위치">
								<option value="">선택하세요</option>
								<option value="시간노출 TOP" <?=$s_sect4=="시간노출 TOP"?"selected":""?>>시간노출 TOP</option>
								<option value="중단" <?=$s_sect4=="중단"?"selected":""?>>중단</option>
								<option value="BEST 띠배너" <?=$s_sect4=="BEST 띠배너"?"selected":""?>>BEST 띠배너</option>
								<option value="BEST 중단롤링" <?=$s_sect4=="BEST 중단롤링"?"selected":""?>>BEST 중단롤링</option>
							</select>
						</td>
					</tr>
			<?}?>
					<tr>
						<th >체험선택</th>
						<td colspan="3"><span id="pro_name_txt"></span>&nbsp;<a href="javascript:main_product_pop();" class="btn_green">체험찾기</a></td>
					</tr>
			
			<?if($v_sect == "Time Sale"){?>
					<tr>
						<th >시작일자</th>
						<td ><input type="text" name="start_date" style="width:20%;" id="start_date" onClick="new CalendarFrame.Calendar(this)" value="" required="yes" message="시작일자" readonly style="vertical-align:middle;"> &nbsp; 
						<select name="start_hour" style="width:20%;" required="yes" message="시작시간">
							<option value="">시작시간</option>
							<?
								$st = 0;
								$ed = 24;
								for($i=$st; $i<$ed; $i++){
									$aph_s = fnzero($i);
							?>
								<option value="<?=$aph_s?>"><?=$aph_s?></option>
							<?}?>
							</select> 시 
							<select name="start_minute" style="width:20%;" required="yes" message="시작분">
							<option value="">시작분</option>
							<?
								$st = 0;
								$ed = 60;
								for($i=$st; $i<$ed; $i++){
									$k = $i+1;
									$aph_s = fnzero($i);
							?>
								<option value="<?=$aph_s?>"><?=$aph_s?></option>
							<?}?>
							</select> 분
						</td>
						<th >오픈여부</th>
						<td >
							<input type="radio" name="timesale_type" required="yes"  message="오픈여부" value="Y"> 오픈함 <input type="radio" name="timesale_type" required="yes"  message="오픈여부" value="N"> 오픈예정
						</td>
					</tr>
					<tr>
						<th >종료일자</th>
						<td colspan="3"><input type="text" name="end_date" style="width:10%;" id="end_date" onClick="new CalendarFrame.Calendar(this)" value="" required="yes" message="종료일자" readonly style="vertical-align:middle;"> &nbsp; 
						<select name="end_hour" style="width:10%;" required="yes" message="종료시간">
							<option value="">종료시간</option>
							<?
								$st = 0;
								$ed = 24;
								for($i=$st; $i<$ed; $i++){
									$aph_s = fnzero($i);
							?>
								<option value="<?=$aph_s?>"><?=$aph_s?></option>
							<?}?>
							</select> 시 
							<select name="end_minute" style="width:10%;" required="yes" message="종료분">
							<option value="">종료분</option>
							<?
								$st = 0;
								$ed = 60;
								for($i=$st; $i<$ed; $i++){
									$k = $i+1;
									$aph_s = fnzero($i);
							?>
								<option value="<?=$aph_s?>"><?=$aph_s?></option>
							<?}?>
							</select> 분
						</td>
					</tr>
					<tr>
						<th >회원 판매가</th>
						<td ><input type="text" style="width:20%;" name="sale_price_mem" value="<?=$row[sale_price_mem]?>" required="yes" message="회원 판매가" is_num="yes"> 원 </td>
						<th >비회원 판매가</th>
						<td ><input type="text" style="width:20%;" name="sale_price_non" value="<?=$row[sale_price_non]?>" required="yes" message="비회원 판매가" is_num="yes"> 원 </td>
					</tr>
			<?}?>		
					<tr>
						<th >설정여부</th>
						<td ><input type="radio" name="view_ok" required="yes"  message="설정여부" value="Y"> 설정함 <input type="radio" name="view_ok" required="yes"  message="설정여부" value="N"> 설정안함 </td>
						<th > 정렬순서</th>
						<td ><input type="text" style="width:20%;" name="align" required="yes" message="정렬순서" is_num="yes"> * 숫자만 입력가능, 높은 숫자 우선으로 정렬됨.</td>
					</tr>
					<!--<tr>
						<th >간략 설명</th>
						<td width="*" colspan="3">
							<textarea style="width:90%;height:50px;" name="main_memo" id="main_memo" required="no"  message="간략설명"></textarea>
							<input type="text" style="width:20%;" name="main_memo" id="main_memo" required="no"  message="간략설명">
						</td>
					</tr>-->
			</table>
			</form>

			<div class="write_btn align_r">
						<a href="javascript:go_list();" class="btn_list">취소</a>
						<button class="btn_modify" type="button" onclick="go_submit();">등록</button>
					</div>
				</div>
		<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>

		