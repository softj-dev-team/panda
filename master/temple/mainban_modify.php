<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>

<? include $_SERVER["DOCUMENT_ROOT"]."/yonex_master/include/check_login.php"; // 관리자 로그인여부 확인?>
<? include $_SERVER["DOCUMENT_ROOT"]."/yonex_master/include/admin_top.php"; // 관리자페이지 상단메뉴?>
<? include $_SERVER["DOCUMENT_ROOT"]."/yonex_master/include/product_left.php"; // 사이트설정 좌측메뉴?>

<?
$idx = trim(sqlfilter($_REQUEST['idx']));
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
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&v_sect='.$v_sect.'&s_group='.$s_group.'&field='.$field.'&keyword='.$keyword.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_sect3='.$s_sect3.'&s_sect4='.$s_sect4.'&pageNo='.$pageNo;

$sql = "SELECT *,(select file_c from product_info where 1 and idx=main_display_set.pro_idx) as file_c,(select pro_name from product_info where 1 and idx=main_display_set.pro_idx) as pro_name FROM main_display_set where 1=1 and idx = '".$idx."' ";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('해당하는 상품전시 설정내용이 없습니다.');
	location.href =  "mainban_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

if($v_sect == "Time Sale"){
	$sect_title = "타임세일";
} else {
	if($s_sect1 == "pc"){
		$sect_title = "PC";
	} elseif($s_sect1 == "mobile"){
		$sect_title = "모바일";
	}
}

$end_date_arr = explode(" ",$row[end_date]);
$end_time_arr = explode(":",$end_date_arr[1]);
$start_date_arr = explode(" ",$row[start_date]);
$start_time_arr = explode(":",$start_date_arr[1]);
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

	function go_view(no){
		location.href = "mainban_view.php?idx="+no+"&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "mainban_list.php?<?=$total_param?>";
	}

	function main_product_pop(){
		//location.href = 
		window.open("main_product.php?proidx=<?=$row[pro_idx]?>","pro_pro_view", "top=100,left=100,scrollbars=yes,resizable=no,width=910,height=500");
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

<!-- content -->
<body onload="javascript:fnSetEditorHTML();">
<section id="content">
	<div class="inner">
		<h3><?=$sect_title?> 상품전시 수정</h3>
		<div class="cont">
			
			<form name="frm" action="mainban_modify_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
			<input type="hidden" name="total_param" value="<?=$total_param?>"/>
			<input type="hidden" name="idx" value="<?=$idx?>"/>
			<input type="hidden" name="pro_name" value="<?=$row[pro_name]?>"/>
			<input type="hidden" name="pro_idx" value="<?=$row[pro_idx]?>"/>
			<input type="hidden" name="s_sect1" value="<?=$s_sect1?>"/>
			<?if($v_sect == "Time Sale"){?>	
				<input type="hidden" name="main_sect" value="<?=$row[main_sect]?>"/>
			<?}?>
			<table class="t_view">
				<colgroup>
					<col width="10%" />
					<col width="40%" />
					<col width="10%" />
					<col width="40%" />
				</colgroup>
				<?//if($s_sect1 == "pc" || $v_sect == "Time Sale"){?>
					<tr>
						<th >메인구분</th>
						<td colspan="3">
							<select name="main_type" size="1" style="vertical-align:middle;" required="yes"  message="메인구분">
								<option value="">메인구분</option>
								<option value="badmin" <?=$row[main_type]=="badmin"?"selected":""?>>배드민턴</option>
								<option value="tenis" <?=$row[main_type]=="tenis"?"selected":""?>>테니스</option>
							</select>
						</td>
					</tr>
			<?//}?>
				<?if($v_sect != "Time Sale"){?>	
					<tr>
						<th >전시위치</th>
						<td colspan="3">
							<select name="main_sect" size="1" style="vertical-align:middle;" required="yes"  message="전시위치">
							<option value="">선택하세요</option>
						<?if($s_sect1 == "pc"){?>
							<option value="Sale Now On" <?=$row[main_sect]=="Sale Now On"?"selected":""?>>Sale Now On</option>
							<option value="Issue Item" <?=$row[main_sect]=="Issue Item"?"selected":""?>>Issue Item</option>
							<option value="New Item" <?=$row[main_sect]=="New Item"?"selected":""?>>New Item</option>
							<!--<option value="Time Sale" <?=$row[main_sect]=="Time Sale"?"selected":""?>>Time Sale</option>-->
							<option value="Weekly Best" <?=$row[main_sect]=="Weekly Best"?"selected":""?>>Weekly Best</option>
							<option value="Yonex All" <?=$row[main_sect]=="Yonex All"?"selected":""?>>Yonex All</option>
						<? } elseif($s_sect1 == "mobile"){ ?>
							<option value="모바일 메인 라켓" <?=$row[main_sect]=="모바일 메인 라켓"?"selected":""?>>모바일 메인 라켓</option>
							<option value="모바일 메인 신발" <?=$row[main_sect]=="모바일 메인 신발"?"selected":""?>>모바일 메인 신발</option>
							<option value="모바일 메인 Apparel" <?=$row[main_sect]=="모바일 메인 Apparel"?"selected":""?>>모바일 메인 Apparel</option>
							<option value="모바일 메인 ACC" <?=$row[main_sect]=="모바일 메인 ACC"?"selected":""?>>모바일 메인 ACC</option>
							<option value="모바일 메인 Legend Collection" <?=$row[main_sect]=="모바일 메인 Legend Collection"?"selected":""?>>모바일 메인 Legend Collection</option>
						<? } ?>
							</select>
						</td>
					</tr>
				<?}?>	
					<tr>
						<th >상품선택</th>
						<td colspan="3"><span id="pro_name_txt"><img src='<?=$_P_DIR_WEB_FILE?>product/img_thumb/<?=$row[file_c]?>' border='0' width='120' height='120'>&nbsp;<?=$row[pro_name]?></span>&nbsp;<a href="javascript:main_product_pop();" class="btn_blue2">상품찾기</a></td>
					</tr>
			
			<?if($v_sect == "Time Sale"){?>
					<tr>
						<th >시작일자</th>
						<td ><input type="text" name="start_date" style="width:20%;" id="start_date" onClick="new CalendarFrame.Calendar(this)" value="<?=$start_date_arr[0]?>" required="yes" message="시작일자" readonly style="vertical-align:middle;"> &nbsp; 
						<select name="start_hour" style="width:20%;" required="yes" message="시작시간">
							<option value="">시작시간</option>
							<?
								$st = 0;
								$ed = 24;
								for($i=$st; $i<$ed; $i++){
									$aph_s = fnzero($i);
							?>
								<option value="<?=$aph_s?>" <?=$start_time_arr[0]==$aph_s?"selected":""?>><?=$aph_s?></option>
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
								<option value="<?=$aph_s?>" <?=$start_time_arr[1]==$aph_s?"selected":""?>><?=$aph_s?></option>
							<?}?>
							</select> 분
						</td>
						<th >오픈여부</th>
						<td >
							<input type="radio" name="timesale_type" required="yes"  message="오픈여부" value="Y" <?=$row[timesale_type]=="Y"?"checked":""?>> 오픈함 <input type="radio" name="timesale_type" required="yes"  message="오픈여부" value="N" <?=$row[timesale_type]=="N"?"checked":""?>> 오픈예정
						</td>
					</tr>
					<tr>
						<th >종료일자</th>
						<td colspan="3"><input type="text" name="end_date" style="width:10%;" id="end_date" onClick="new CalendarFrame.Calendar(this)" value="<?=$end_date_arr[0]?>" required="yes" message="종료일자" readonly style="vertical-align:middle;"> &nbsp; 
						<select name="end_hour" style="width:10%;" required="yes" message="종료시간">
							<option value="">종료시간</option>
							<?
								$st = 0;
								$ed = 24;
								for($i=$st; $i<$ed; $i++){
									$aph_s = fnzero($i);
							?>
								<option value="<?=$aph_s?>" <?=$end_time_arr[0]==$aph_s?"selected":""?>><?=$aph_s?></option>
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
								<option value="<?=$aph_s?>" <?=$end_time_arr[1]==$aph_s?"selected":""?>><?=$aph_s?></option>
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
						<td ><input type="radio" name="view_ok" required="yes"  message="설정여부" value="Y" <?=$row[view_ok]=="Y"?"checked":""?>> 설정함 <input type="radio" name="view_ok" required="yes"  message="설정여부" value="N" <?=$row[view_ok]=="N"?"checked":""?>> 설정해제 </td>
						<th > 정렬순서</th>
						<td ><input type="text" style="width:20%;" name="align" value="<?=$row['align']?>" required="yes" message="정렬순서" is_num="yes"> * 숫자만 입력가능, 높은 숫자 우선으로 정렬됨.</td>
					</tr>

					<!--<tr>
						<th >간략 설명</th>
						<td width="*" colspan="3">
							<textarea style="width:90%;height:50px;" name="main_memo" id="main_memo" required="no"  message="간략설명"><?=stripslashes($row[main_memo])?></textarea>
							<input type="text" style="width:20%;" name="main_memo" id="main_memo" required="no"  message="간략설명" value="<?=stripslashes($row[main_memo])?>">
						</td>
					</tr>-->
				
			</table>
			</form>

			<div class="align_c margin_t20">
				<!-- 등록 -->
				<a href="javascript:go_submit();" class="btn_blue2">수정</a>
				<!-- 목록 -->
				<a href="javascript:go_view('<?=$row[idx]?>');" class="btn_blue2">취소</a>
			</div>
		</div>
	</div>
</section>
<!-- //content -->
<!--//js-->
<!--footer-->
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
