<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 비슷회원
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 게시 시작일
$s_level = sqlfilter($_REQUEST['s_level']); // 게시 종료일
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order;

$where = " and is_del = 'N'";

if($v_sect){
	$where .= " and curri_type = '".$v_sect."'";
}

if(!$pageNo){
	$pageNo = 1;
}

if(!$s_order){
	$s_order = 1;
}

if($s_sect1){
	$where .= " and idx in (select curri_info_idx from curri_info_add where 1 and tag_value = '".$s_sect1."' and cate_type='cate' and cate_level='1')";
}
if($s_sect2){
	$where .= " and idx in (select curri_info_idx from curri_info_add where 1 and tag_value = '".$s_sect2."' and cate_type='cate' and cate_level='2')";
}
if($s_gubun){
	$where .= " and view_ok = '".$s_gubun."'";
}

if ($field && $keyword){
	$where .= " and ".$field." like '%".$keyword."%'";
}

$query_cnt = "select idx from curri_info where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$pageScale = 20; // 페이지당 20 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by align asc "; 

$query = "select *,(select file_chg from board_file where 1 and board_tbname='curri_info' and board_code='sphoto' and board_idx=curri_info.idx order by idx asc limit 0,1) as file_chg from curri_info where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;

$query_cnt2 = "select idx from curri_info where 1 and is_del='N' and curri_type='".$v_sect."'";
$result_cnt2 = mysqli_query($gconnet,$query_cnt2);
$num2 = mysqli_num_rows($result_cnt2);

if($v_sect == "CG0001"){
	$max_cnt = "10";
} elseif($v_sect == "CG0006"){
	$max_cnt = "5";
} elseif($v_sect == "CG0007"){
	$max_cnt = "5";
} elseif($v_sect == "CG0008"){
	$max_cnt = "5";
} elseif($v_sect == "CG0002"){
	$max_cnt = "2";
}
?>
<script type="text/javascript">
<!--	 
	function go_view(no){
		location.href = "curri_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "curri_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "curri_write.php?<?=$total_param?>";
	}

	function go_regist_add(no){
		location.href = "curri_write_add.php?curri_idx="+no+"&<?=$total_param?>";
	}
	
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


var check  = 0;                                                                            //체크 여부 확인
function CheckAll(){                
	var boolchk;                                                                              //boolean형 변수 
	var chk = document.getElementsByName("curri_idx[]")                 //체크박스의 name값
		
		if(check){ check=0; boolchk = false; }else{ check=1; boolchk = true; }    //체크여부에 따른 true/false 설정
			
			for(i=0; i<chk.length;i++){                                                                    
				chk[i].checked = boolchk;                                                                //체크되어 있을경우 설정변경
			}

}

function go_tot_del() {
	var check = chkFrm('frm');
	if(check) {
		if(confirm('선택하신 대주제를 삭제 하시겠습니까?')){
			frm.action = "curri_list_action_del.php";
			frm.submit();
		}
	} else {
		false;
	}
}

function go_tot_align() {
	frm.action = "curri_list_action_align.php";
	frm.submit();
}

function go_tot_excel() {
link_sect_txt2.style.display = 'none';
//var check = chkFrm('frm');
	//if(check) {
		frm.action = "curri_list_action_excel.php";
		frm.submit();
	/*} else {
		false;
	}*/
}

function cate_sel_1(z,level){
	var tmp = z.options[z.selectedIndex].value; 
	_fra_admin.location.href="/pro_inc/cate_select.php?cate_code1="+tmp+"&fm=s_mem&fname=s_sect2&cate_level="+level+"";
}
//-->
</script>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/curri_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li><?=get_code_value("cate_name1","cate_code1",$v_sect)?></li>
						<li>등록된 대주제 리스트</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>대주제 리스트</h3>
				<?if($num2 < $max_cnt){?>
					<button class="btn_add" onclick="go_regist();" style="width:180px;"><span>대주제 등록</span></button>
				<?}?>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="curri_list.php">
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
						<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
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
							<th scope="row">노출여부</th>
							<td colspan="2">
								<select name="s_gubun" size="1" style="vertical-align:middle;width:30%" >
									<option value="">노출여부</option>
									<option value="Y" <?=$s_gubun=="Y"?"selected":""?>>Y</option>
									<option value="N" <?=$s_gubun=="N"?"selected":""?>>N</option>
								</select>
							</td>
							<th scope="row">조건검색</th>
							<td colspan="2">
								<select name="field" size="1" style="vertical-align:middle;width:20%;">
									<option value="">검색기준</option>
									<option value="curri_title" <?=$field=="curri_title"?"selected":""?>>제목</option>
									<option value="curri_detail" <?=$field=="curri_detail"?"selected":""?>>설명글</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:50%;" value="<?=$keyword?>">
							</td>
						</tr>
					</form>
				</table>
				<!-- 검색창 종료 -->

				<div class="align_r mt20">
						<button class="btn_search" onclick="s_mem.submit();">검색</button>
						<!--<button class="btn_down" onclick="order_excel_frm.submit();">엑셀다운로드</button>-->
					</div>
					<ul class="list_tab" style="height:20px;">
						<!--<li class="on"><a href="#">월단위 결과</a></li>
						<li><a href="#">월단위 결과</a></li>
						<li><a href="#">월단위 결과</a></li>-->
					</ul>
					<div class="search_wrap">
					<!-- 목록 옵션 시작 -->
						<div class="result">
							<p class="txt">검색결과 총 <span><?=$num?></span>건</p>
							<div class="btn_wrap">
								<!--<select id="s_cnt_set" onchange="go_cnt_set(this)">
									<option value="10" <?=$s_cnt=="10"?"selected":""?>>10개보기</option>
									<option value="20" <?=$s_cnt=="20"?"selected":""?>>20개보기</option>
									<option value="30" <?=$s_cnt=="30"?"selected":""?>>30개보기</option>
									<option value="40" <?=$s_cnt=="40"?"selected":""?>>40개보기</option>
								</select>
								<select id="s_order_set" onchange="go_order_set(this)">
									<option value="1" <?=$s_order=="1"?"selected":""?>>회원가입일 최신순</option>
									<option value="2" <?=$s_order=="2"?"selected":""?>>회원가입일 오래된순</option>
									<option value="3" <?=$s_order=="3"?"selected":""?>>회원명 올림차순</option>
									<option value="4" <?=$s_order=="4"?"selected":""?>>회원명 내림차순</option>
								</select>
								<button class="btn_del" onclick="go_tot_del();"><span>선택삭제</span></button>-->
							</div>
						</div>
					<!-- 목록 옵션 종료 -->

				<!-- 리스트 시작 -->
				<form method="post" name="frm" target="_fra_admin" id="frm">
					<input type="hidden" name="pageNo" value="<?=$pageNo?>" />
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
				
					<p style="text-align:right;padding-right:10px;padding-bottom:5px;"><font style="color:red;">* 낮은 숫자 우선으로 정렬됩니다.</font></p>
					<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:5%;">
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:20%;">
								<col style="width:20%;">			
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:10%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col"><input type="checkbox" id="" name="checkNum" onclick="javascript:CheckAll()"></th>
									<th scope="col">No</th>
									<th scope="col">이미지</th>
									<th scope="col">제목</th>
									<th scope="col">설명글</th>
									<th scope="col">정렬순서</th>
									<th scope="col">노출여부</th>
									<th scope="col">조회수</th>
									<th scope="col">등록일</th>
								</tr>
							</thead>
							<tbody>
						<? if($num==0) { ?>
							<tr>
								<td colspan="10" height="40">등록된 대주제가 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);
								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

								if($i == mysqli_num_rows($result)-1){
									$curri_idx .= $row['idx'];
								} else {
									$curri_idx .= $row['idx'].",";
								}
						?>
						<tr>
							<td><input type="checkbox" name="curri_idx[]" id="curri_idx[]" value="<?=$row["idx"]?>" required="yes"  message="대주제"/></td>
							<td><?=$listnum?></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');">
							<?if($row[file_chg]){?>
								<img src="<?=$_P_DIR_WEB_FILE?>curri_info/img_thumb/<?=$row['file_chg']?>" style="max-width:90%;">
							<?}?>
							</a></td>
							<td style="text-align:left;padding-left:10px;"><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row['curri_title']?></a></td>
							<td style="text-align:left;padding-left:10px;"><a href="javascript:go_view('<?=$row[idx]?>');">
								<?=string_cut2(strip_tags($row['curri_detail']),80)?>
							</a></td>
							<td style="text-align:left;padding-left:10px;">
								<input type="text" style="width:40%;" name="align_<?=$row["idx"]?>" required="yes" message="정렬순서" is_num="yes" value="<?=$row["align"]?>"> 숫자만 입력
							</td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row['view_ok']?></a></td>
							<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=number_format($row['vcnt'])?></a></td>
							<td><?=substr($row[wdate],0,10)?></td>
						</tr>
					<?}?>	
						</tbody>
						</table>

						<input type="hidden" name="curri_idx_arr" value="<?=$curri_idx?>"/>
					</form>
						
						<div style="text-align:right;margin-top:10px;padding-right:10px;">
							<a href="javascript:go_tot_del();" class="btn_red">선택삭제</a>
							<a href="javascript:go_tot_align();" class="btn_green">순서적용</a>
						</div>
						
						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>

					</div>
				</div>
			
	<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
