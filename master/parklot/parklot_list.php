<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); 
$s_gubun = sqlfilter($_REQUEST['s_gubun']); 
$s_level = sqlfilter($_REQUEST['s_level']); 
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_cnt = trim(sqlfilter($_REQUEST['s_cnt']));  
$s_order = trim(sqlfilter($_REQUEST['s_order']));  
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_cnt='.$s_cnt.'&s_order='.$s_order;

$where = " and is_del='N'";

if(!$pageNo){
	$pageNo = 1;
}

if(!$s_cnt){
	$s_cnt = 10; // 기본목록 10개
}

if(!$s_order){
	$s_order = 1; 
}

if($v_sect == "auth"){
	$where .= " and auth_status='Y'";
	$parklot_str = "인증 주자창";
} elseif($v_sect == "public"){
	$where .= " and public_status='Y'";
	$parklot_str = "공유 주자창";
} else {
	$parklot_str = "주자창";
}

if($s_gubun){
	$where .= " and auth_status = '".$s_gubun."'";
}
if($s_level){
	$where .= " and assign_status = '".$s_level."'";
}
if($s_sect1){
	$where .= " and sido = '".$s_sect1."'";
}
if($s_sect2){
	$where .= " and gugun = '".$s_sect2."'";
}

if ($field && $keyword){
	if ($field == "tel"){
		$where .= " and (tel_1 like '%".$keyword."%' or tel_2 like '%".$keyword."%')";
	} else {
		$where .= " and ".$field." like '%".$keyword."%'";
	}
}

$pageScale = $s_cnt;  
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

if($s_order == 1){
	$order_by = " order by idx desc ";
} elseif($s_order == 2){
	$order_by = " order by wdate asc ";
} elseif($s_order == 3){
	$order_by = " order by user_name asc ";
} elseif($s_order == 4){
	$order_by = " order by user_name desc ";
}

$query = "select * from parklot_info a where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from parklot_info where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
<script type="text/javascript">
<!--	 
	function go_view(no){
		location.href = "parklot_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "parklot_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "parklot_write.php?<?=$total_param?>";
	}

	function go_regist_add(no){
		location.href = "parklot_write_add.php?parklot_idx="+no+"&<?=$total_param?>";
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

	function go_cnt_set(z){
		var tmp = z.options[z.selectedIndex].value; 
		$("#s_cnt").val(tmp);
		$("#s_mem").submit();
	}

	function go_order_set(z){
		var tmp = z.options[z.selectedIndex].value; 
		$("#s_order").val(tmp);
		$("#s_mem").submit();
	}

var check  = 0;                                                                            //체크 여부 확인
function CheckAll(){                
	var boolchk;                                                                              //boolean형 변수 
	var chk = document.getElementsByName("parklot_idx[]")                 //체크박스의 name값
		
		if(check){ check=0; boolchk = false; }else{ check=1; boolchk = true; }    //체크여부에 따른 true/false 설정
			
			for(i=0; i<chk.length;i++){                                                                    
				chk[i].checked = boolchk;                                                                //체크되어 있을경우 설정변경
			}

}

function go_tot_del() {
	var check = chkFrm('frm');
	if(check) {
		if(confirm('선택하신 주차장을 삭제 하시겠습니까?')){
			//if(confirm('삭제하신 주차장정보는 복구할 수 없습니다. 정말 삭제 하시겠습니까?')){
				frm.action = "parklot_list_action_del.php";
				frm.submit();
			//}
		}
	} else {
		false;
	}
}

function go_tot_stop() {
	var check = chkFrm('frm');
	if(check) {
		if(confirm('선택하신 주차장의 로그인을 정지 상태로 설정하시겠습니까?')){
			frm.action = "parklot_list_action_stop.php";
			frm.submit();
		}
	} else {
		false;
	}
}

function go_tot_start() {
	var check = chkFrm('frm');
	if(check) {
		if(confirm('선택하신 주차장의 로그인을 활성화 상태로 설정하시겠습니까?')){
			frm.action = "parklot_list_action_start.php";
			frm.submit();
		}
	} else {
		false;
	}
}

	function cate_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		//alert(tmp);
		_fra_admin.location.href="cate_select_1.php?cate_code1="+tmp+"&fm=s_mem&fname=s_sect1";
	}

	 function cate_sel_2(z){
		var ktmp = document.s_mem.s_gender.value;
		if(z){
			var tmp = z.options[z.selectedIndex].value; 
			_fra_admin.location.href="cate_select_2.php?cate_code1="+ktmp+"&cate_code2="+tmp+"&fm=s_mem&fname=s_sect2";
		} else {
			_fra_admin2.location.href="cate_select_2.php?cate_code1="+ktmp+"&fm=s_mem&fname=s_sect2";
		}
	}
	
//-->
</script>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/parklot_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>주차장 관리</li>
						<li><?=$parklot_str?> 정보 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$parklot_str?> 리스트</h3>
				<?if(!$v_sect){?>
					<button class="btn_add" onclick="go_regist();" style="width:15%;"><span>주차장 등록</span></button>
				<?}?>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="list">
				<!-- 검색창 시작 -->
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="<?=basename($_SERVER['PHP_SELF'])?>">
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
						<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
						<input type="hidden" name="s_cnt" id="s_cnt" value="<?=$s_cnt?>"/>
						<input type="hidden" name="s_order" id="s_order" value="<?=$s_order?>"/>
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
							<th scope="row">상태별 검색</th>
							<td colspan="2">
							<?if($v_sect != "auth"){?>
								<select name="s_gubun" id="s_gubun" style="vertical-align:middle;width:20%;" >
									<option value="">인증상태</option>
									<option value="Y">인증됨</option>
									<option value="N">인증안됨</option>
								</select> &nbsp;
							<?}?>
								<select name="s_level" id="s_level" style="vertical-align:middle;width:20%;" >
									<option value="">배정상태</option>
									<option value="Y">배정됨</option>
									<option value="N">배정안됨</option>
									<option value="C">기간만료</option>
								</select>
							</td>
							<th scope="row">지역검색</th>
							<td colspan="2">
								<select name="s_sect1" id="s_sect1" style="vertical-align:middle;width:30%;" onchange="area_sel_1(this)">
									<option value="">시/도</option>
									<?
									$sect1_sql = "select bjd_code,k_name from code_bjd where 1 and filter='SIDO' and del_yn='N' order by k_name asc";
									$sect1_result = mysqli_query($gconnet,$sect1_sql);
										for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
											$row1 = mysqli_fetch_array($sect1_result);
									?>
										<option value="<?=$row1['bjd_code']?>" <?=$s_sect1==$row1['bjd_code']?"selected":""?>><?=$row1['k_name']?></option>
									<?}?>
									</select>
								&nbsp;
								<select name="s_sect2" id="s_sect2" style="vertical-align:middle;width:30%;">
									<option value="">구/군</option>
								<?if($s_sect1){?>
									<?
									$sect1_sql = "select bjd_code,k_name from code_bjd where 1 and filter='SGG' and del_yn='N' and pre_code='".$s_sect1."' order by k_name asc";
									$sect1_result = mysqli_query($gconnet,$sect1_sql);
										for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
											$row1 = mysqli_fetch_array($sect1_result);
									?>
										<option value="<?=$row1['bjd_code']?>" <?=$s_sect2==$row1['bjd_code']?"selected":""?>><?=$row1['k_name']?></option>
									<?}?>
								<?}?>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">조건검색</th>
							<td colspan="5">
								<select name="field" id="field" size="1" style="vertical-align:middle;width:20%;">
									<option value="">검색기준</option>
									<option value="parklot_name" <?=$field=="parklot_name"?"selected":""?>>주차장 번호</option>
									<option value="parklot_addr" <?=$field=="parklot_addr"?"selected":""?>>주차장 주소</option>
									<option value="parklot_cell" <?=$field=="parklot_cell"?"selected":""?>>주차장 연락처</option>
									<option value="car_num" <?=$field=="car_num"?"selected":""?>>차량번호</option>
								</select>
								<input type="text" title="검색" name="keyword" id="keyword" style="width:40%;" value="<?=$keyword?>">
							</td>
						</tr>
					</form>
				</table>
				<!-- 검색창 종료 -->
		
			<!-- 엑셀 출력을 위한 전송 폼 시작 -->
			<form name="order_excel_frm" id="order_excel_frm" method="post" action="parklot_excel_list.php">
				<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
				<input type="hidden" name="s_gubun" value="<?=$s_gubun?>"/>
				<input type="hidden" name="s_level" value="<?=$s_level?>"/>
				<input type="hidden" name="s_sect1" value="<?=$s_sect1?>"/>
				<input type="hidden" name="s_sect2" value="<?=$s_sect2?>"/>
				<input type="hidden" name="field" value="<?=$field?>"/>
				<input type="hidden" name="keyword" value="<?=htmlspecialchars($keyword)?>"/>
				<input type="hidden" name="s_gender" value="<?=$s_gender?>"/>
				<input type="hidden" name="s_cnt" value="<?=$s_cnt?>"/>
				<input type="hidden" name="s_order" value="<?=$s_order?>"/>
			</form>
			<!-- 엑셀 출력을 위한 전송 폼 종료 -->

					<div class="align_r mt20">
						<button class="btn_search" onclick="s_mem.submit();">검색</button>
						<button class="btn_down" onclick="order_excel_frm.submit();">엑셀다운로드</button>
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
								<select id="s_cnt_set" onchange="go_cnt_set(this)">
									<option value="10" <?=$s_cnt=="10"?"selected":""?>>10개보기</option>
									<option value="20" <?=$s_cnt=="20"?"selected":""?>>20개보기</option>
									<option value="30" <?=$s_cnt=="30"?"selected":""?>>30개보기</option>
									<option value="40" <?=$s_cnt=="40"?"selected":""?>>40개보기</option>
								</select>
								<!--<select id="s_order_set" onchange="go_order_set(this)">
									<option value="1" <?=$s_order=="1"?"selected":""?>>주차장가입일 최신순</option>
									<option value="2" <?=$s_order=="2"?"selected":""?>>주차장가입일 오래된순</option>
									<option value="3" <?=$s_order=="3"?"selected":""?>>주차장명 올림차순</option>
									<option value="4" <?=$s_order=="4"?"selected":""?>>주차장명 내림차순</option>
								</select>
								<button class="btn_del" onclick="go_tot_del();"><span>선택삭제</span></button>-->
							</div>
						</div>
					<!-- 목록 옵션 종료 -->
				
				<form method="post" name="frm" target="_fra_admin" id="frm">
					<input type="hidden" name="pageNo" value="<?=$pageNo?>" />
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>

						<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:5%;">
								<col style="width:10%">
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:25%;">
								<col style="width:10%;">
								<col style="width:10%;">
								<col style="width:15%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col"><input type="checkbox" id="" name="checkNum" onclick="javascript:CheckAll()"></th>
									<th scope="col">No</th>
									<th scope="col">지역</th>
									<th scope="col">구</th>
									<th scope="col">주차장 번호</th>
									<th scope="col">주차장 주소</th>
									<th scope="col">인증상태</th>
									<th scope="col">배정상태</th>
									<th scope="col">등록일시</th>
								</tr>
							</thead>
							<tbody>
						<? if($num==0) { ?>
							<tr>
								<td colspan="10" height="40">등록된 주차장이 없습니다.</strong></td>
							</tr>
						<? } ?>
					<?
						for ($i=0; $i<mysqli_num_rows($result); $i++){
							$row = mysqli_fetch_array($result);

							$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

							if($i == mysqli_num_rows($result)-1){
								$parklot_idx .= $row['idx'];
							} else {
								$parklot_idx .= $row['idx'].",";
							}

							if($row['auth_status'] == "Y"){
								$login_ok = "<font style='color:blue;'>인증</font>";
							}elseif($row['auth_status'] == "N"){
								$login_ok = "<font style='color:red;'>인증안됨</font>";
							}

							if($row['assign_status'] == "Y"){
								$master_ok = "<font style='color:blue;'>배정됨</font>";
							}elseif($row['assign_status'] == "N"){
								$master_ok = "<font style='color:black;'>배정안됨</font>";
							}elseif($row['assign_status'] == "C"){
								$master_ok = "<font style='color:red;'>기간만료</font>";
							}

					?>
							<tr>
								<td><input type="checkbox" name="parklot_idx[]" id="parklot_idx[]" value="<?=$row["idx"]?>" required="yes"  message="주차장"/></td>
								<td><?=$listnum?></td>
								<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row['sido']?></a></td>
								<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row['gugun']?></a></td>
								<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row['parklot_name']?></a></td>
								<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row['parklot_addr1']?>&nbsp;<?=$row['parklot_addr2']?></a></td>
								<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$login_ok?></a></td>
								<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$master_ok?></a></td>
								<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row['wdate']?></a></td>
							</tr>
					<?}?>	
						</tbody>
						</table>

						<input type="hidden" name="parklot_idx_arr" value="<?=$parklot_idx?>"/>
					</form>
						
						<div style="text-align:right;margin-top:10px;padding-right:10px;">
							<a href="javascript:go_tot_del();" class="btn_red">선택삭제</a>
							<!--<a href="javascript:go_tot_stop();" class="btn_green">선택 정지</a>
							<a href="javascript:go_tot_start();" class="btn_blue">선택 활성화</a>-->
						</div>

						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>
					</div>
				</div>
		<!-- content 종료 -->
	</div>
</div>

<script>
	function area_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="/pro_inc/area_select_1.php?cate_code1="+tmp+"&fm=s_mem&fname=s_sect2";
	}

	function area_sel_2(z){
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="/pro_inc/area_select_2.php?cate_code1="+tmp+"&fm=s_mem&fname=s_sect3";
	} 
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>