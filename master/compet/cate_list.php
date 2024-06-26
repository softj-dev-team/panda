<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$v_sect = trim(sqlfilter($_REQUEST['v_sect']));
$v_step = trim(sqlfilter($_REQUEST['v_step']));

if(!$v_step){
	$v_step = "1";
}

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

if(!$pageNo){
	$pageNo = 1;
}

$cate_code1 = trim(sqlfilter($_REQUEST['cate_code1']));
$cate_code2 = trim(sqlfilter($_REQUEST['cate_code2']));
$cate_code3 = trim(sqlfilter($_REQUEST['cate_code3']));

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&v_sect='.$v_sect.'&cate_code1='.$cate_code1.'&cate_code2='.$cate_code2.'&cate_code3='.$cate_code3.'&field='.$field.'&keyword='.$keyword;

$where = " and cate_level = '1' and del_ok = 'N' and type='section'"; // 대분류만 우선 출력

if($v_sect){
	$where .= " and type='".$v_sect."'";
}

if($keyword){
	$where .= " and cate_name1 like '%".$keyword."%'";
}

$pageScale = 20; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

/*$query =	" SELECT * ";
$query = $query." FROM ( ";
$query = $query." SELECT	ROW_NUMBER() OVER(ORDER BY cate_align DESC) AS rowNumber ";
$query = $query.",	idx,cate_level,cate_code1,cate_name1,cate_align,wdate,head_ok,aca_ok,stu_ok,nonmem_ok ";
$query = $query." FROM common_code WITH(NOLOCK) ";
$query = $query." WHERE 1=1  ".$where;
$query = $query."	) AS S ";
$query = $query." WHERE S.rowNumber BETWEEN ".$StarRowNum." AND ".$EndRowNum." ";*/

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$order_by = " order by cate_align desc ";
$query = "select * from common_code where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from common_code where 1=1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>

<script type="text/javascript">
<!--
	function go_delete(frm_name){
		if(confirm('정말로 삭제 하시겠습니까?')){	
			document.forms[frm_name].del_ok.value="Y";
			document.forms[frm_name].submit();		
		}
	}

	function go_delete_2(idx,catecode1,catecode2){
		if(confirm('카테고리를 삭제하시면 복구가 불가능하며, 사이트 운영에 버그가 발생할수도 있습니다. \n\n그래도 삭제 하시겠습니까?')){
			if(confirm('중분류 카테고리를 삭제하시면 소속된 소분류 카테고리가 모두 삭제됩니다. \n\n삭제된 내용은 복구되지 않습니다. 정말로 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "cate_delete_action.php?idx="+idx+"&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&cate_code1="+catecode1+"&cate_code2="+catecode2+"&cate_code3=<?=$cate_code3?>&cate_level=2";
			}
		}
	}

	function go_delete_3(idx,catecode1,catecode2,catecode3){
		if(confirm('카테고리를 삭제하시면 복구가 불가능하며, 사이트 운영에 버그가 발생할수도 있습니다. \n\n그래도 삭제 하시겠습니까?')){
			if(confirm('삭제된 내용은 복구되지 않습니다. 정말로 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "cate_delete_action.php?idx="+idx+"&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&cate_code1="+catecode1+"&cate_code2="+catecode2+"&cate_code3="+catecode3+"&cate_level=3";
			}
		}
	}

	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}

	function go_modify(frm_name) {
		var check = chkFrm(frm_name);
		if(check) {
			document.forms[frm_name].submit();
		} else {
			return;
		}
	}

	function go_submit_1() {
		var check = chkFrm('frm_1');
		if(check) {
			frm_1.submit();
		} else {
			return;
		}
	}
	
	function go_list(){
		location.href = "cate_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>";
	}

	function go_middle_regist(rnum) { 
		var dis_t = "cate2_insert_"+rnum;
		document.getElementById(dis_t).style.display = '';
	} 

	function go_middle_cancel(rnum) { 
		var dis_t = "cate2_insert_"+rnum;
		document.getElementById(dis_t).style.display = 'none';
	} 

	function go_low_regist(rnum) { 
		var dis_t = "cate3_insert_"+rnum;
		document.getElementById(dis_t).style.display = '';
	} 

	function go_low_cancel(rnum) { 
		var dis_t = "cate3_insert_"+rnum;
		document.getElementById(dis_t).style.display = 'none';
	} 

	function go_mic_regist(rnum) { 
		var dis_t = "cate4_insert_"+rnum;
		document.getElementById(dis_t).style.display = '';
	} 

	function go_mic_cancel(rnum) { 
		var dis_t = "cate4_insert_"+rnum;
		document.getElementById(dis_t).style.display = 'none';
	} 
	
//-->
</script>

<body>
<div id="wrap" > <!-- class="skin_type01" -->
	<?//echo "bmenu = ".$bmenu."<br>";?>
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/compet_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>공모전 관리</li>
						<li>공모전 업종관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>공모전 업종관리</h3>
				</div>
				<div class="list">
				<!-- 검색창 시작 
				<table class="search">
				<form name="s_mem" id="s_mem" method="post" action="cate_list.php">
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
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
							<th scope="row">옵션검색</th>
							<td colspan="5">
								<select name="v_sect" size="1" style="vertical-align:middle;width:40%;" >
									<option value="">옵션타입 선택</option>
									<option value="section" <?=$v_sect=="section"?"selected":""?>>업종</option>
									<option value="basic" <?=$v_sect=="basic"?"selected":""?>>기본옵션</option>
									<option value="period" <?=$v_sect=="period"?"selected":""?>>진행기간</option>
									<option value="display" <?=$v_sect=="display"?"selected":""?>>노출옵션</option>
								</select>
								<input type="text" placeholder="옵션명" name="keyword" id="keyword" style="width:40%;" value="<?=$keyword?>">
							</td>
						</tr>
					</form>
				</table>
				<!-- 검색창 종료 
					<div class="align_r mt20">
						<!--<button class="btn_down">엑셀다운로드</button> 
						<button class="btn_search" onclick="s_mem.submit();">검색</button>
					</div> 
					<ul class="list_tab" style="height:20px;">
						<!--<li class="on"><a href="#">월단위 결과</a></li>
						<li><a href="#">월단위 결과</a></li>
						<li><a href="#">월단위 결과</a></li>
					</ul>-->
					<div class="search_wrap">
					<!-- 리스트 시작 -->
						<div class="list_tit" style="margin-top:0px;">
							<h3>공모전 업종 등록</h3>
						</div>
											
						<table class="search_list">
							<caption>분류등록</caption>
							<colgroup>
								<col style="width:70%;">
								<col style="width:15%;">
								<col style="width:15%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">옵션이름</th>
									<th scope="col">사용여부</th>
									<th scope="col">등록</th>
								</tr>
							</thead>
							<tbody>
							<form method="post" action="cate_write_action.php" name="frm_1" target="_fra_admin" id="frm_1" enctype="multipart/form-data">
								<input type="hidden" name="bmenu" value="<?=$bmenu?>">
								<input type="hidden" name="smenu" value="<?=$smenu?>">
								<input type="hidden" name="v_step" value="<?=$v_step?>">
								<input type="hidden" name="pageNo" value="<?=$pageNo?>">
								<input type="hidden" name="field" value="<?=$field?>">
								<input type="hidden" name="keyword" value="<?=$keyword?>">
								<input type="hidden" name="cate_level" value="1"> <!-- 대분류 -->

								<input type="hidden" name="type" value="section">

								<tr>
									<td>
										<input type="text" style="width:90%;" name="cate_name1" required="yes" message="업종이름" value="">
									</td>
									<td>
										<select name="is_del" id="is_del" required="yes" message="사용여부">
											<option value="N" <?=$row[is_del]=="N"?"selected":""?>>정상사용</option> 
											<option value="Y" <?=$row[is_del]=="Y"?"selected":""?>>사용안함</option> 
										</select>
									</td>
									<td><a href="javascript:go_submit_1();" class="btn_blue">등록</a></td>						
								</tr>
							</form>
						</tbody>
						</table>

						<div class="list_tit" style="margin-top:20px;">
							<h3>공모전 업종 관리</h3>
						</div>
						<!--<div style="text-align:right;padding-right:10px;padding-top:10px;padding-bottom:10px;"><font style="color:red;">코드를 클릭하시면 세부코드 목록이 나옵니다.</font></div>-->
						<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:60%;">
								<col style="width:15%;">
								<col style="width:10%;">
								<col style="width:15%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">업종이름</th>
									<th scope="col">사용여부</th>
									<th scope="col">등록일</th>
									<th scope="col">관리</th>
								</tr>
							</thead>
							<tbody>
						<? if($num==0) { ?>
							<tr>
								<td colspan="10" height="40">등록된 업종이 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){ // 대분류 루프 시작
								$row = mysqli_fetch_array($result);

								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

						?>
							<form name="frm_cate1_<?=$i?>" method="post" action="cate_modify_action.php"  target="_fra_admin" enctype="multipart/form-data">
								<input type="hidden" name="idx" value="<?=$row[idx]?>"/>
								<input type="hidden" name="cate_level" value="<?=$row[cate_level]?>"> <!-- 분류 구분-->
								<input type="hidden" name="bmenu" value="<?=$bmenu?>">
								<input type="hidden" name="smenu" value="<?=$smenu?>">
								<input type="hidden" name="v_step" value="<?=$v_step?>">
								<input type="hidden" name="pageNo" value="<?=$pageNo?>">
								<input type="hidden" name="field" value="<?=$field?>">
								<input type="hidden" name="keyword" value="<?=$keyword?>">
								<input type="hidden" name="del_ok" value="N">

								<input type="hidden" name="type" value="<?=$row[type]?>">

								<tr>
									<td>
										<input type="text" style="width:90%;" name="cate_name1" required="yes" message="업종명" value="<?=$row[cate_name1]?>">
									</td>
									<td>
										<select name="is_del" id="is_del" required="yes" message="사용여부">
											<option value="N" <?=$row[is_del]=="N"?"selected":""?>>정상사용</option> 
											<option value="Y" <?=$row[is_del]=="Y"?"selected":""?>>사용안함</option> 
										</select>
									</td>
									<td><?=substr($row[wdate],0,10)?></td>
									<td>
										<a href="javascript:go_modify('frm_cate1_<?=$i?>');" class="btn_green" >수정</a>&nbsp;<a href="javascript:go_delete('frm_cate1_<?=$i?>');" class="btn_red">삭제</a>
									</td>						
								</tr>
							</form>

							<!-- 중분류 코드 입력창 시작 -->
							<tr id="cate2_insert_<?=$i?>" style="display:none;">
							<td colspan="7">
								<br>		
								<table class="t_list" style="width:95%;" align="center">
								<thead>
								<tr>
									<th width="60%">코드명</th>
									<!--<th width="30%">메인 이미지</th>
									<th width="30%">정렬순서</th>-->
									<th width="10%">사용여부</th>
									<th width="15%">등록</th>
									<th width="15%">등록취소</th>
								</tr>
								</thead>
								<tbody>
									<form method="post" action="cate_write_action.php" name="frm2_insert_<?=$i?>" target="_fra_admin" id="frm2_insert_<?=$i?>" enctype="multipart/form-data">
										<input type="hidden" name="bmenu" value="<?=$bmenu?>">
										<input type="hidden" name="smenu" value="<?=$smenu?>">
										<input type="hidden" name="v_step" value="<?=$v_step?>">
										<input type="hidden" name="pageNo" value="<?=$pageNo?>">
										<input type="hidden" name="field" value="<?=$field?>">
										<input type="hidden" name="keyword" value="<?=$keyword?>">
										<input type="hidden" name="cate_code1" value="<?=$row[cate_code1]?>"> <!-- 대분류 코드 -->
										<input type="hidden" name="cate_name1" value="<?=$row[cate_name1]?>"> <!-- 대분류 명 -->
										<input type="hidden" name="cate_level" value="2"> <!-- 중분류 -->
										<tr>
											<td ><input type="text" style="width:90%;" name="cate_name2" required="yes" message="코드명" value=""></td>
											<!--<td><input type="file" name="file1" style="width:50%;" required="no"  message="메인 이미지"/>
												<br> <font style="color:blue;">이미지를 등록하시면 쇼핑몰 메인화면 카테고리에 설정됩니다</font>
											</td>-->
											<td><!--<input type="text" style="width:20%;" name="cate_align" required="yes" message="정렬순서" is_num="yes" value=""> 숫자만 입력, 높은숫자 우선순위-->
												<select name="is_del" id="is_del" required="yes" message="사용여부">
													<option value="N">정상사용</option> 
													<option value="Y">사용안함</option> 
												</select>
											</td>
											<td><a href="javascript:go_modify('frm2_insert_<?=$i?>');" class="btn_blue">등록</a></td>
											<td><a href="javascript:go_middle_cancel('<?=$i?>');" class="btn_red">등록취소</a></td>
										</tr>
									</form>
								</tbody>
								</table>
								<br>
								</td>
							</tr>
						<!-- 중분류 코드 입력창 종료 -->

						<?if($v_step > 1 && $row[cate_code1] == $cate_code1){ // 중분류 리스트 시작 ?>
							<?
								$cate2_sql = "select idx,cate_level,cate_code2,cate_name2,cate_align,wdate,is_del from common_code where 1 and cate_level='2' and cate_code1 = '".$cate_code1."' and del_ok = 'N' ORDER BY cate_align DESC";
								$cate2_query = mysqli_query($gconnet,$cate2_sql);
								$cate2_cnt = mysqli_num_rows($cate2_query);

								if($cate2_cnt == 0){
							?>
								<tr>
									<td colspan="7">해당하는 세부코드가 없습니다.</td>		
								</tr>
							<?
								}

								for($cate2_i=0; $cate2_i<$cate2_cnt; $cate2_i++){ // 중분류 루프 시작
									$cate2_row = mysqli_fetch_array($cate2_query);
							?>
									
									<form name="frm_cate2_<?=$cate2_i?>" method="post" action="cate_modify_action.php"  target="_fra_admin" enctype="multipart/form-data">
										<input type="hidden" name="idx" value="<?=$cate2_row[idx]?>"/>
										<input type="hidden" name="cate_level" value="<?=$cate2_row[cate_level]?>"> <!-- 분류 구분-->
										<input type="hidden" name="cate_code1" value="<?=$cate_code1?>"> <!-- 대분류 코드-->
										<input type="hidden" name="bmenu" value="<?=$bmenu?>">
										<input type="hidden" name="smenu" value="<?=$smenu?>">
										<input type="hidden" name="v_step" value="<?=$v_step?>">
										<input type="hidden" name="pageNo" value="<?=$pageNo?>">
										<input type="hidden" name="field" value="<?=$field?>">
										<input type="hidden" name="keyword" value="<?=$keyword?>">
										<input type="hidden" name="del_ok" value="N">
										<tr class="tr_2depth">
											<td>
												<img class="notRe" src="../img/icon/re.gif" alt="" />&nbsp;<!--<a href="cate_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=3&cate_code1=<?=$row[cate_code1]?>&cate_code2=<?=$cate2_row[cate_code2]?>">--><?=$cate2_row[cate_code2]?><!--</a>-->
											</td>
											<td><input type="text" style="width:90%;" name="cate_name2" required="yes" message="중분류 명" value="<?=$cate2_row[cate_name2]?>"></td>
											<!--<td>
											<?if($cate2_row['file_c']){?>
												기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$cate2_row['file_c']?>&on=<?=$cate2_row['file_o']?>&dir=cate_banner"><?=$cate2_row['file_o']?></a>
												(기존파일 삭제 : <input type="checkbox" name="del_org1" value="Y">)
											<?}?>
											<input type="hidden" name="file_old_name1" value="<?=$cate2_row[file_c]?>" />
											<input type="hidden" name="file_old_org1" value="<?=$cate2_row[file_o]?>" />
											<br>
											<input type="file" name="file1" style="width:50%;" required="no"  message="배너 이미지"/>
											<br> <font style="color:blue;">이미지를 등록하시면 쇼핑몰 메인화면 카테고리에 설정됩니다</font>
											<?if($cate2_row['file_c2']){?>
												기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$cate2_row['file_c2']?>&on=<?=$cate2_row['file_o2']?>&dir=cate_banner"><?=$cate2_row['file_o2']?></a>
												(기존파일 삭제 : <input type="checkbox" name="del_org2" value="Y">)
											<?}?>
											<input type="hidden" name="file_old_name2" value="<?=$cate2_row[file_c2]?>" />
											<input type="hidden" name="file_old_org2" value="<?=$cate2_row[file_o2]?>" />
											<br>
											<input type="file" name="file2" style="width:50%;" required="no"  message="배너 이미지"/>
											<br> <font style="color:blue;">검색레이어 아이콘 설정</font>
											</td>-->

											<td>
												<!--<input type="text" style="width:20%;" name="cate_align" required="yes" message="정렬순서" is_num="yes" value="<?=$cate2_row[cate_align]?>"> 숫자만 입력, 높은숫자 우선-->
											</td>
											<td>
											<select name="is_del" id="is_del" required="yes" message="사용여부">
												<option value="N" <?=$cate2_row[is_del]=="N"?"selected":""?>>정상사용</option> 
												<option value="Y" <?=$cate2_row[is_del]=="Y"?"selected":""?>>사용안함</option> 
											</select>
											</td>
											<td><?=substr($cate2_row[wdate],0,10)?></td>
											<td><!--<a href="javascript:go_low_regist('<?=$cate2_i?>');" class="btn_blue">소분류등록</a>--></td>
											<td>
												<a href="javascript:go_modify('frm_cate2_<?=$cate2_i?>');" class="btn_green">수정</a>
												<a href="javascript:go_delete('frm_cate2_<?=$cate2_i?>');" class="btn_red">삭제</a>
											</td>						
										</tr>
									</form>

									<!-- 소분류 코드 입력창 시작 -->
									<tr id="cate3_insert_<?=$cate2_i?>" style="display:none;">
									<td colspan="7">
									<br>		
										<table class="t_list" style="width:95%;" align="center">
										<thead>
										<tr>
											<th width="40%">소분류 명</th>
											<th width="30%">정렬순서</th>
											<th width="15%">등록</th>
											<th width="15%">등록취소</th>
										</tr>
									</thead>
									<tbody>
										
										<form method="post" action="cate_write_action.php" name="frm3_insert_<?=$cate2_i?>" target="_fra_admin" id="frm3_insert_<?=$cate2_i?>">
											<input type="hidden" name="bmenu" value="<?=$bmenu?>">
											<input type="hidden" name="smenu" value="<?=$smenu?>">
											<input type="hidden" name="v_step" value="<?=$v_step?>">
											<input type="hidden" name="pageNo" value="<?=$pageNo?>">
											<input type="hidden" name="field" value="<?=$field?>">
											<input type="hidden" name="keyword" value="<?=$keyword?>">
											<input type="hidden" name="cate_code1" value="<?=$row[cate_code1]?>"> <!-- 대분류 코드 -->
											<input type="hidden" name="cate_name1" value="<?=$row[cate_name1]?>"> <!-- 대분류 명 -->
											<input type="hidden" name="cate_code2" value="<?=$cate2_row[cate_code2]?>"> <!-- 중분류 코드 -->
											<input type="hidden" name="cate_name2" value="<?=$cate2_row[cate_name2]?>"> <!-- 중분류 명 -->
											<input type="hidden" name="cate_level" value="3"> <!-- 소분류 -->
											<tr>
												<td ><input type="text" style="width:90%;" name="cate_name3" required="yes" message="소분류 명" value=""></td>
												<td><input type="text" style="width:20%;" name="cate_align" required="yes" message="정렬순서" is_num="yes" value=""> 숫자만 입력, 높은숫자 우선순위</td>
												<td><a href="javascript:go_modify('frm3_insert_<?=$cate2_i?>');" class="btn_blue">등록</a></td>
												<td><a href="javascript:go_low_cancel('<?=$cate2_i?>');" class="btn_red">등록취소</a></td>
											</tr>
										</form>
									</tbody>
									</table>
									<br>
										</td>

									</tr>
									<!-- 소분류 코드 입력창 종료 -->

								<?
								$sect3_prev_sql = "select idx from common_code where 1  and cate_level = '3' and is_del='N' and cate_code1='".$cate_code1."' ";
								$sect3_prev_result = mysqli_query($gconnet,$sect3_prev_sql);
								if(mysqli_num_rows($sect3_prev_result) > 0){
									$sect2_sql = "select cate_code2 from common_code where 1  and cate_level = '2' and is_del='N' and cate_code1='".$cate_code1."' order by idx asc limit 0,1";
									$sect2_result = mysqli_query($gconnet,$sect2_sql);
									$row2 = mysqli_fetch_array($sect2_result);
									//$cate_code2 = $row2['cate_code2'];
								}	
									if($v_step > 2 && $row[cate_code1] == $cate_code1 && $cate2_row[cate_code2] == $cate_code2){ // 소분류 리스트 시작 ?>
									<?
									$cate3_sql = "select idx,cate_level,cate_code3,cate_name3,cate_align,wdate,is_del from common_code where 1=1 and cate_level='3' and cate_code1 = '".$cate_code1."' and cate_code2 = '".$cate_code2."' and del_ok = 'N' ORDER BY cate_align DESC ";
									$cate3_query = mysqli_query($gconnet,$cate3_sql);
									$cate3_cnt = mysqli_num_rows($cate3_query);

									//echo $cate3_sql;

									if($cate3_cnt == 0){
									?>
										<tr>
											<td colspan="7">등록된 소분류가 없습니다.</td>		
										</tr>
									<?
									}

									for($cate3_i=0; $cate3_i<$cate3_cnt; $cate3_i++){ // 소분류 루프 시작
									$cate3_row = mysqli_fetch_array($cate3_query);
									?>
										<form name="frm_cate3_<?=$cate3_i?>" method="post" action="cate_modify_action.php"  target="_fra_admin">
											<input type="hidden" name="idx" value="<?=$cate3_row[idx]?>"/>
											<input type="hidden" name="cate_level" value="<?=$cate3_row[cate_level]?>"> <!-- 분류 구분-->
											<input type="hidden" name="cate_code1" value="<?=$cate_code1?>"> <!-- 대분류 코드-->
											<input type="hidden" name="cate_code2" value="<?=$cate_code2?>"> <!-- 중분류 코드-->
											<input type="hidden" name="bmenu" value="<?=$bmenu?>">
											<input type="hidden" name="smenu" value="<?=$smenu?>">
											<input type="hidden" name="v_step" value="<?=$v_step?>">
											<input type="hidden" name="pageNo" value="<?=$pageNo?>">
											<input type="hidden" name="field" value="<?=$field?>">
											<input type="hidden" name="keyword" value="<?=$keyword?>">
											<input type="hidden" name="del_ok" value="N">
											<tr>
												<td style="padding-left:10px;">
													<img class="notRe" src="../img/icon/re_red.gif" alt="" />&nbsp;<!--<a href="cate_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=4&cate_code1=<?=$row[cate_code1]?>&cate_code2=<?=$cate2_row[cate_code2]?>&cate_code3=<?=$cate3_row[cate_code3]?>">--><?=$cate3_row[cate_code3]?><!--</a>-->
												</td>
												<td>
													<input type="text" style="width:90%;" name="cate_name3" required="yes" message="소분류 명" value="<?=$cate3_row[cate_name3]?>">
												</td>
												<td>
													<input type="text" style="width:20%;" name="cate_align" required="yes" message="정렬순서" is_num="yes" value="<?=$cate3_row[cate_align]?>"> 숫자만 입력, 높은숫자 우선
												</td>
												<td>
													<select name="is_del" id="is_del" required="yes" message="사용여부">
														<option value="N" <?=$cate3_row[is_del]=="N"?"selected":""?>>정상사용</option> 
														<option value="Y" <?=$cate3_row[is_del]=="Y"?"selected":""?>>사용안함</option> 
													</select>
												</td>
												<td><?=substr($cate3_row[wdate],0,10)?></td>
												<td>&nbsp;</td>
												<td>
													<a href="javascript:go_modify('frm_cate3_<?=$cate3_i?>');" class="btn_green">수정</a>
													<a href="javascript:go_delete('frm_cate3_<?=$cate3_i?>');" class="btn_red">삭제</a>
												</td>						
											</tr>
										</form>
										<?} // 소분류 루프 종료 ?>
									<?} // 소분류 리스트 종료?>
							   <?} // 중분류 루프 종료 ?>
							<?} // 중분류 리스트 종료?>
						<?} // 대분류 루프 종료 ?>	
						</tbody>
						</table>
						<!-- 페이징 시작 -->
						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>
						<!-- 페이징 종료 -->

					</div>
				</div>
		<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
