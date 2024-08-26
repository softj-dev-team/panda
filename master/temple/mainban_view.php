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

$sql = "SELECT *,(select file_c from product_info where 1 and idx=main_display_set.pro_idx) as file_c,(select pro_name from product_info where 1 and idx=main_display_set.pro_idx) as pro_name,(select product_memo_dan from product_info where 1 and idx=main_display_set.pro_idx) as product_memo_dan FROM main_display_set where 1=1 and idx = '".$idx."' and section = '".$_SESSION['admin_coinc_section']."' ";
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

if($row[view_ok] == "Y"){
		$view_ok = "설정함";
} elseif($row[view_ok] == "N"){
		$view_ok = "설정해제";
}


if($row[link_sect] == "P"){
	$link_sect = "상품 정보로 링크";
} elseif($row[link_sect] == "U"){
	$link_sect = "별도 URL 링크";
} elseif($row[link_sect] == "N"){
	$link_sect = "링크없음";
} 

if($row[timesale_type] == "Y"){
	$timesale_type = "오픈";
} elseif($row[timesale_type] == "N"){
	$timesale_type = "오픈예정";
} else {
	$timesale_type = "";
}

if($v_sect == "Time Sale"){
	$sect_title = "타임세일";
} else {
	if($s_sect1 == "pc"){
		$sect_title = "PC";
	} elseif($s_sect1 == "mobile"){
		$sect_title = "모바일";
	}
}

if($row[main_type] == "badmin"){
	$main_type = "배드민턴";
} elseif($row[main_type] == "tenis"){
	$main_type = "테니스";
} else {
	$main_type = "";
}
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_view(no){
		location.href = "mainban_view.php?idx="+no+"&<?=$total_param?>";
	}
	
	function go_modify(no){
		location.href = "mainban_modify.php?idx="+no+"&<?=$total_param?>";
	}
	
	function go_delete(no){
		if(confirm('정말 삭제 하시겠습니까?')){
			if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "mainban_delete_action.php?idx="+no+"&<?=$total_param?>";
			}
		}
	}

	function go_list(){
		location.href = "mainban_list.php?<?=$total_param?>";
	}

	function view_pic(ref) {
			ref = ref;
			var window_left = (screen.width-1024) / 2;
			var window_top = (screen.height-768) / 2;
			window.open(ref, "pic_window", 'width=600,height=400,status=no,scrollbars=yes,top=' + window_top + ', left=' + window_left +'');
	}

function go_submit() {
	var check = chkFrm('frm');
	if(check) {
		frm.submit();
	} else {
		false;
	}
}

//-->
</SCRIPT>

<section id="content">
	<div class="inner">
		<h3><?=$sect_title?> 전시설정 상세보기</h3>
		<div class="cont">
			<table class="t_view">
				<colgroup>
					<col width="20%" />
					<col width="30%" />
					<col width="20%" />
					<col width="30%" />
				</colgroup>
				<?//if($s_sect1 == "pc" || $v_sect == "Time Sale"){?>
					<tr>
						<th >메인구분</th>
						<td colspan="3">
							<?=$main_type?>
						</td>
					</tr>
				<?//}?>
				<?if($v_sect != "Time Sale"){?>		
					<tr>
						<th>배너프로그램 위치</th>
						<td ><?=$row[main_sect]?></td>
						<th >설정여부</th>
						<td ><?=$view_ok?></td>
					</tr>
				<?}else{?>
					<tr>
						<th >설정여부</th>
						<td colspan="3"><?=$view_ok?></td>
					</tr>
				<?}?>

					<tr>
						<th >상품 이미지</th>
						<td colspan="3" style="padding-left:10px;padding-top:10px;padding-bottom:10px;">
						<?if($row[file_c] != "" && $row[file_c] != " "){?>
							<img src="<?=$_P_DIR_WEB_FILE?>product/img_thumb/<?=$row[file_c]?>" border="0" width="120" height="120">
						<?}?>
						</td>
					</tr>
					<tr>
						<th >상품명</th>
						<td colspan="3"><?=stripslashes($row[pro_name])?></td>
					</tr>
					<tr>
						<th >상품특징</th>
						<td colspan="3"><?=stripslashes($row[product_memo_dan])?></td>
					</tr>

					<?if($v_sect == "Time Sale"){?>
					<tr>
						<th >시작일시</th>
						<td ><?=$row[start_date]?></td>
						<th >종료일시</th>
						<td ><?=$row[end_date]?></td>
					</tr>
					<tr>
						<th >회원 판매가</th>
						<td ><?=number_format($row[sale_price_mem])?> 원</td>
						<th >비회원 판매가</th>
						<td ><?=number_format($row[sale_price_non])?> 원</td>
					</tr>
				<?}?>		

					<tr>
						<th >오픈여부</th>
						<td ><?=$timesale_type?></td>
						<th>등록일</th>
						<td><?=$row[wdate]?></td>
					</tr>

					<form name="frm" action="mainban_view_action.php" target="_fra_admin" method="post" >
					<input type="hidden" name="idx" value="<?=$idx?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					
					<tr>
						
						<th >설정여부</th>
						<td colspan="3">
						<select name="view_ok" size="1" style="vertical-align:middle;" required="yes" message="설정여부">
						<option value="">선택하세요</option>
						<option value="Y" <?=$row[view_ok]=="Y"?"selected":""?>>설정함</option>
						<option value="N" <?=$row[view_ok]=="N"?"selected":""?>>설정해제</option>
						</select>
						&nbsp; <a href="javascript:go_submit();"><img src="/yonex_master/images/btn_save.gif" align="absmiddle"></a>
						</td>
					</tr>
							
					
					</form>

			</table>

			<div class="align_c margin_t20">
				<!-- 목록 -->
				<a href="javascript:go_list();" class="btn_blue2">목록</a>
				<!-- 수정 -->
				<a href="javascript:go_modify('<?=$row[idx]?>');" class="btn_blue2">수정하기</a>
				<!-- 삭제 -->
				<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_blue2">삭제</a>	
			</div>
		</div>
	</div>
</section>
<!-- //content -->

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>