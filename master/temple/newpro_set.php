<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
	$p_title = "추천사찰 설정";
?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/temple_left.php"; // 좌측메뉴?>
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>메뉴 관리</li>
						<li><?=$p_title?></li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$p_title?></h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="write">
				<form name="frm" action="newpro_set_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="mode" id="mode" value="<?=$mode?>"/>
					<input type="hidden" name="upidx" id="upidx" value="<?=$upidx?>"/>

					<input type="hidden" name="pro_name" value=""/>
					<input type="hidden" name="pro_idx" value=""/>

					<table>
						<caption>게시글 등록</caption>
						<colgroup>
							<col style="width:15%;">
							<col style="width:35%;">
							<col style="width:15%;">
							<col style="width:35%;">
						</colgroup>
						<tr id="area_manual_btn">
							<th>사찰등록</th>
							<td colspan="3"><span id="pro_name_txt"></span>&nbsp;<a href="javascript:main_temple_pop();" class="btn_green">사찰찾기</a>
								&nbsp;<span style="margin-left:90%;text-align:right;padding-right:10px;"><a href="javascript:go_submit();" class="btn_blue">저장</a></span>
							</td>
						</tr>
					</table>
				</form>
					

<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword;

$where = " and temple_info_idx in (select idx from temple_info where 1 and is_del = 'N' and view_ok='Y')";

if(!$pageNo){
	$pageNo = 1;
}

if(!$s_order){
	$s_order = 1;
}

if($s_sect1){
	//$where .= " and idx in (select temple_info_idx from temple_info_add where 1 and tag_value = '".$s_gubun."' and cate_type='cate' and cate_level='1')";
}
if($s_sect2){
	//$where .= " and idx in (select temple_info_idx from temple_info_add where 1 and tag_value = '".$s_gubun."' and cate_type='cate' and cate_level='2')";
}
if($s_gubun){
	//$where .= " and view_ok = '".$s_sect2."'";
}

if ($field && $keyword){
	$where .= " and ".$field." like '%".$keyword."%'";
}

$query_cnt = "select idx from temple_info_new_list where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$pageScale = 20; // 페이지당 20 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by align desc"; 

$query = "select *,(select file_chg from board_file where 1 and board_tbname='temple_info' and board_code='photo' and board_idx=temple_info_new_list.temple_info_idx order by idx asc limit 0,1) as file_chg,(select temple_title from temple_info where 1 and idx=temple_info_new_list.temple_info_idx) as temple_title from temple_info_new_list where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
		
	<div class="search_wrap">
		<form method="post" name="list_frm" target="_fra_admin" id="list_frm">
			<input type="hidden" name="pageNo" value="<?=$pageNo?>" />
			<input type="hidden" name="total_param" value="<?=$total_param?>"/>

				<table class="search_list" id="area_manual_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:5%;">
								<col style="width:15%;">
								<col style="width:30%;">
								<col style="width:30%;">			
								<col style="width:15%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col"><input type="checkbox" id="" name="checkNum" onclick="javascript:CheckAll()"></th>
									<th scope="col">No</th>
									<th scope="col">이미지</th>
									<th scope="col">사찰명</th>
									<th scope="col">정렬순서</th>
									<th scope="col">등록일시</th>
								</tr>
							</thead>
							<tbody>
						<? if($num==0) { ?>
							<tr>
								<td colspan="7" height="40">등록된 추천사찰이 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);
								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

								if($i == mysqli_num_rows($result)-1){
									$temple_idx .= $row['idx'];
								} else {
									$temple_idx .= $row['idx'].",";
								}
						?>
						<tr>
							<td><input type="checkbox" name="temple_idx[]" id="temple_idx[]" value="<?=$row["idx"]?>" required="yes"  message="메뉴"/></td>
							<td><?=$listnum?></td>
							<td>
							<?if($row[file_chg]){?>
								<img src="<?=$_P_DIR_WEB_FILE?>temple_info/img_thumb/<?=$row['file_chg']?>" style="max-width:90%;">
							<?}?>
							</td>
							<td style="text-align:left;padding-left:10px;"><?=$row['temple_title']?></td>
							<td style="text-align:left;padding-left:10px;">
								<input type="text" style="width:40%;" name="align_<?=$row["idx"]?>" required="yes" message="정렬순서" is_num="yes" value="<?=$row["align"]?>"> 숫자만 입력
							</td>
							<td><?=$row[wdate]?></td>
						</tr>
					<?}?>	
						</tbody>
						</table>
						<input type="hidden" name="temple_idx_arr" value="<?=$temple_idx?>"/>
					</form>
			</div>
						<div style="text-align:right;margin-top:20px;padding-right:10px;">
							<a href="javascript:go_tot_del();" class="btn_red">선택삭제</a>
							<a href="javascript:go_tot_align();" class="btn_green">순서적용</a>
						</div>

						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>
					
				</div>
			</div>
		</div>

<script type="text/javascript">
<!--
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

function go_submit() {
	var check = chkFrm('frm');
	if(check) {
		frm.submit();	
	} else {
		false;
	}
}

	function main_temple_pop(){
		//location.href = 
		window.open("main_product.php","pro_pro_view", "top=100,left=100,scrollbars=yes,resizable=no,width=1010,height=500");
	}

var check  = 0;                                                                            //체크 여부 확인
function CheckAll(){                
	var boolchk;                                                                              //boolean형 변수 
	var chk = document.getElementsByName("temple_idx[]")                 //체크박스의 name값
		
		if(check){ check=0; boolchk = false; }else{ check=1; boolchk = true; }    //체크여부에 따른 true/false 설정
			
			for(i=0; i<chk.length;i++){                                                                    
				chk[i].checked = boolchk;                                                                //체크되어 있을경우 설정변경
			}

}

function set_new_list_type(str){
	if(str == "M"){
		$("#area_manual_btn").show();
		$("#area_manual_list").show();
	} else {
		$("#area_manual_btn").hide();
		$("#area_manual_list").hide();
	}
}

function go_tot_del() {
	var check = chkFrm('list_frm');
	if(check) {
		if(confirm('선택하신 메뉴를 삭제 하시겠습니까?')){
			list_frm.action = "newpro_list_action_del.php";
			list_frm.submit();
		}
	} else {
		false;
	}
}

function go_tot_align() {
	list_frm.action = "newpro_list_action_align.php";
	list_frm.submit();
}
//-->
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>