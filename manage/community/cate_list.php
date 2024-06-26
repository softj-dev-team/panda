<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>

<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/admin_top.php"; // 관리자페이지 상단메뉴?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/community_left.php"; // 사이트설정 좌측메뉴?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
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

$where .= " and cate_level = '1' and cate_code1 != 'tsys' "; 

$pageScale = 20; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

/*$query =	" SELECT * ";
$query = $query." FROM ( ";
$query = $query." SELECT	ROW_NUMBER() OVER(ORDER BY cate_align DESC) AS rowNumber ";
$query = $query.",	idx,cate_level,cate_code1,cate_name1,cate_align,wdate,head_ok,aca_ok,stu_ok,nonmem_ok ";
$query = $query." FROM board_cate WITH(NOLOCK) ";
$query = $query." WHERE 1=1  ".$where;
$query = $query."	) AS S ";
$query = $query." WHERE S.rowNumber BETWEEN ".$StarRowNum." AND ".$EndRowNum." "; */

$order_by = " order by cate_align desc ";

$query = "select * from board_cate where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from board_cate where 1=1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_delete_1(idx,catecode){
		if(confirm('카테고리를 삭제하시면 사이트 운영에 버그가 발생할수도 있습니다. 그래도 삭제 하시겠습니까?')){
			//if(confirm('카테고리 카테고리를 삭제하시면 소속된 중카테고리, 소카테고리 카테고리가 모두 삭제됩니다. 정말로 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "cate_delete_action.php?idx="+idx+"&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&cate_code1="+catecode+"&cate_code2=<?=$cate_code2?>&cate_code3=<?=$cate_code3?>&cate_level=1";
			//}
		}
	}

	function go_delete_2(idx,catecode1,catecode2){
		if(confirm('카테고리를 삭제하시면 사이트 운영에 버그가 발생할수도 있습니다. 그래도 삭제 하시겠습니까?')){
			if(confirm('중카테고리 카테고리를 삭제하시면 소속된 소카테고리 카테고리가 모두 삭제됩니다. 정말로 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "cate_delete_action.php?idx="+idx+"&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&cate_code1="+catecode1+"&cate_code2="+catecode2+"&cate_code3=<?=$cate_code3?>&cate_level=2";
			}
		}
	}

	function go_delete_3(idx,catecode1,catecode2,catecode3){
		if(confirm('카테고리를 삭제하시면 사이트 운영에 버그가 발생할수도 있습니다. 그래도 삭제 하시겠습니까?')){
			if(confirm('정말로 삭제 하시겠습니까?')){	
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
		document.getElementById(dis_t).style.display = 'block';
	} 

	function go_middle_cancel(rnum) { 
		var dis_t = "cate2_insert_"+rnum;
		document.getElementById(dis_t).style.display = 'none';
	} 

	function go_low_regist(rnum) { 
		var dis_t = "cate3_insert_"+rnum;
		document.getElementById(dis_t).style.display = 'block';
	} 

	function go_low_cancel(rnum) { 
		var dis_t = "cate3_insert_"+rnum;
		document.getElementById(dis_t).style.display = 'none';
	} 
		
//-->
</SCRIPT>

<!-- content -->
<section id="content">
	<div class="inner">

		<h3>
			게시판 카테고리 등록하기
		</h3>
		<div class="cont">
						
			<table class="t_list">
				<thead>
					<tr>
						<th width="20%">카테고리 코드</th>
						<th width="50%">카테고리 명</th>
						<th width="20%">정렬순서</th>
						<th width="10%">등록</th>
					</tr>
				</thead>
				<tbody>
			
				<form method="post" action="cate_write_action.php" name="frm_1" target="_fra_admin" id="frm_1">
				<input type="hidden" name="bmenu" value="<?=$bmenu?>">
				<input type="hidden" name="smenu" value="<?=$smenu?>">
				<input type="hidden" name="v_step" value="<?=$v_step?>">
				<input type="hidden" name="pageNo" value="<?=$pageNo?>">
				<input type="hidden" name="field" value="<?=$field?>">
				<input type="hidden" name="keyword" value="<?=$keyword?>">
				<input type="hidden" name="cate_level" value="1"> <!-- 카테고리 -->
					<tr>
						<td><input type="text" style="width:90%;" name="cate_code1" onKeyup="checkNumber()" required="yes" message="카테고리 코드" value=""></td>
						<td><input type="text" style="width:90%;" name="cate_name1" required="yes" message="카테고리 명" value=""></td>
						<td><input type="text" style="width:20%;" name="cate_align" required="yes" message="정렬순서" is_num="yes" value=""> 숫자만 입력, 높은숫자 우선순위</td>
						<td><a href="javascript:go_submit_1();" class="btn_blue2">등록</a></td>						
					</tr>
				</form>
							
			</tbody>
			</table>
			
			<!-- //Goods List -->
			</div>

		<h3>
			게시판 카테고리 관리
		</h3>
		<div class="cont">
		
			<table class="t_list">
				<thead>
					<tr>
						<th width="20%">카테고리 코드</th>
						<th width="30%">카테고리 명</th>
						<th width="15%">정렬순서</th>
						<th width="10%">등록일</th>
						<!--<th width="15%">접근권한 허용</th>
						<th width="8%">하위카테고리등록</th>-->
						<th width="15%">카테고리 삭제여부</th>
						<th width="10%">설정수정</th>
						<!--<th width="10%">삭제</th>-->
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>등록된 카테고리가 없습니다.</strong></td>
					</tr>
				<? } ?>

				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){ // 카테고리 루프 시작
					$row = mysqli_fetch_array($result);

					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				?>
						<form name="frm_cate1_<?=$i?>" method="post" action="cate_modify_action.php"  target="_fra_admin">
							<input type="hidden" name="idx" value="<?=$row[idx]?>"/>
							<input type="hidden" name="cate_level" value="<?=$row[cate_level]?>"> <!-- 카테고리 구분-->
							<input type="hidden" name="bmenu" value="<?=$bmenu?>">
							<input type="hidden" name="smenu" value="<?=$smenu?>">
							<input type="hidden" name="v_step" value="<?=$v_step?>">
							<input type="hidden" name="pageNo" value="<?=$pageNo?>">
							<input type="hidden" name="field" value="<?=$field?>">
							<input type="hidden" name="keyword" value="<?=$keyword?>">
							<tr>
								<td style="text-align:left;padding-left:10px;"><!--<a href="cate_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=2&cate_code1=<?=$row[cate_code1]?>">--><?=$row[cate_code1]?><!--</a>--></td>
								<td style="text-align:left;padding-left:10px;"><input type="text" style="width:90%;" name="cate_name1" required="yes" message="카테고리 명" value="<?=$row[cate_name1]?>"></td>
								<td><input type="text" style="width:20%;" name="cate_align" required="yes" message="정렬순서" is_num="yes" value="<?=$row[cate_align]?>"> 숫자만 입력, 높은숫자 우선</td>
								<td><?=substr($row[wdate],0,10)?></td>
								<!--<td><input type="checkbox" name="head_ok" value="Y" <?=$row[head_ok]=="Y"?"checked":""?>> 본부장 <input type="checkbox" name="aca_ok" value="Y" <?=$row[aca_ok]=="Y"?"checked":""?>> 학원장 <input type="checkbox" name="stu_ok" value="Y" <?=$row[stu_ok]=="Y"?"checked":""?>> 학생 <input type="checkbox" name="nonmem_ok" value="Y" <?=$row[nonmem_ok]=="Y"?"checked":""?>> 비회원</td>
								<td><a href="javascript:go_middle_regist('<?=$i?>');" class="btn_blue2">하위등록</a></td>-->
								<td><input type="radio" name="is_del" value="N" <?=$row[is_del]=="N"?"checked":""?>> 정상사용 <input type="radio" name="is_del" value="Y" <?=$row[is_del]=="Y"?"checked":""?>> 카테고리 삭제 
								</td>
								<td><a href="javascript:go_modify('frm_cate1_<?=$i?>');" class="btn_blue2">설정수정</a></td>						
								<!--<td><a href="javascript:go_delete_1('<?=$row[idx]?>','<?=$row[cate_code1]?>');" class="btn_blue2">삭제</a></td>-->		
							</tr>
						</form>
						
					<!-- 중카테고리 코드 입력창 시작 -->
						<tr id="cate2_insert_<?=$i?>" style="display:none;">
						<td colspan="7">
							<br>		
							<table class="t_list" style="width:95%;" align="center">
							<thead>
							<tr>
								<th width="15%">중카테고리 코드</th>
								<th width="30%">중카테고리 명</th>
								<th width="20%">정렬순서</th>
								<th width="20%">접근권한 허용</th>
								<th width="7%">등록</th>
								<th width="8%">등록취소</th>
							</tr>
							</thead>
							<tbody>
								<form method="post" action="cate_write_action.php" name="frm2_insert_<?=$i?>" target="_fra_admin" id="frm2_insert_<?=$i?>">
									<input type="hidden" name="bmenu" value="<?=$bmenu?>">
									<input type="hidden" name="smenu" value="<?=$smenu?>">
									<input type="hidden" name="v_step" value="<?=$v_step?>">
									<input type="hidden" name="pageNo" value="<?=$pageNo?>">
									<input type="hidden" name="field" value="<?=$field?>">
									<input type="hidden" name="keyword" value="<?=$keyword?>">
									<input type="hidden" name="cate_code1" value="<?=$row[cate_code1]?>"> <!-- 카테고리 코드 -->
									<input type="hidden" name="cate_name1" value="<?=$row[cate_name1]?>"> <!-- 카테고리 명 -->
									<input type="hidden" name="cate_level" value="2"> <!-- 중카테고리 -->
									<tr>
										<td><input type="text" style="width:90%;" name="cate_code2" onKeyup="checkNumber()" required="yes" message="중카테고리 코드" value=""></td>
										<td ><input type="text" style="width:90%;" name="cate_name2" required="yes" message="중카테고리 명" value=""></td>
										<td><input type="text" style="width:20%;" name="cate_align" required="yes" message="정렬순서" is_num="yes" value=""> 숫자만 입력, 높은숫자 우선순위</td>
										<td><input type="checkbox" name="head_ok" value="Y"> 본부장 <input type="checkbox" name="aca_ok" value="Y"> 학원장 <input type="checkbox" name="stu_ok" value="Y"> 학생 <input type="checkbox" name="nonmem_ok" value="Y"> 비회원</td>
										<td><a href="javascript:go_modify('frm2_insert_<?=$i?>');" class="btn_blue2">등록</a></td>
										<td><a href="javascript:go_middle_cancel('<?=$i?>');" class="btn_blue2">등록취소</a></td>
									</tr>
								</form>
							</tbody>
							</table>
							<br>
								</td>

						</tr>
					<!-- 중카테고리 코드 입력창 종료 -->

						<?if($v_step > 1 && $row[cate_code1] == $cate_code1){ // 중카테고리 리스트 시작 ?>
							<?
								$cate2_sql = "select idx,cate_level,cate_code2,cate_name2,cate_align,wdate,head_ok,aca_ok,stu_ok,nonmem_ok from board_cate where 1=1 and cate_level='2' and cate_code1 = '".$cate_code1."' ORDER BY cate_align DESC";
								$cate2_query = mysqli_query($gconnet,$cate2_sql);
								$cate2_cnt = mysqli_num_rows($cate2_query);

								if($cate2_cnt == 0){
							?>
								<tr>
									<td colspan="7">등록된 중카테고리가 없습니다.</td>		
								</tr>
							<?
								}

								for($cate2_i=0; $cate2_i<$cate2_cnt; $cate2_i++){ // 중카테고리 루프 시작
									$cate2_row = mysqli_fetch_array($cate2_query);
							?>
									
									<form name="frm_cate2_<?=$cate2_i?>" method="post" action="cate_modify_action.php"  target="_fra_admin">
										<input type="hidden" name="idx" value="<?=$cate2_row[idx]?>"/>
										<input type="hidden" name="cate_level" value="<?=$cate2_row[cate_level]?>"> <!-- 카테고리 구분-->
										<input type="hidden" name="cate_code1" value="<?=$cate_code1?>"> <!-- 카테고리 코드-->
										<input type="hidden" name="bmenu" value="<?=$bmenu?>">
										<input type="hidden" name="smenu" value="<?=$smenu?>">
										<input type="hidden" name="v_step" value="<?=$v_step?>">
										<input type="hidden" name="pageNo" value="<?=$pageNo?>">
										<input type="hidden" name="field" value="<?=$field?>">
										<input type="hidden" name="keyword" value="<?=$keyword?>">
										<tr>
											<td style="text-align:left;padding-left:20px;">
												<img class="notRe" src="/manage/images/re.gif" alt="" />&nbsp;<a href="cate_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=3&cate_code1=<?=$row[cate_code1]?>&cate_code2=<?=$cate2_row[cate_code2]?>"><?=$cate2_row[cate_code2]?></a>
											</td>
											<td style="text-align:left;padding-left:10px;"><input type="text" style="width:90%;" name="cate_name2" required="yes" message="중카테고리 명" value="<?=$cate2_row[cate_name2]?>"></td>
											<td><input type="text" style="width:20%;" name="cate_align" required="yes" message="정렬순서" is_num="yes" value="<?=$cate2_row[cate_align]?>"> 숫자만 입력, 높은숫자 우선</td>
											<td><?=substr($cate2_row[wdate],0,10)?></td>
											<td><input type="checkbox" name="head_ok" value="Y" <?=$cate2_row[head_ok]=="Y"?"checked":""?>> 본부장 <input type="checkbox" name="aca_ok" value="Y" <?=$cate2_row[aca_ok]=="Y"?"checked":""?>> 학원장 <input type="checkbox" name="stu_ok" value="Y" <?=$cate2_row[stu_ok]=="Y"?"checked":""?>> 학생 <input type="checkbox" name="nonmem_ok" value="Y" <?=$cate2_row[nonmem_ok]=="Y"?"checked":""?>> 비회원</td>
											<td><a href="javascript:go_low_regist('<?=$cate2_i?>');" class="btn_blue2">하위등록</a></td>
											<td><a href="javascript:go_modify('frm_cate2_<?=$cate2_i?>');" class="btn_blue2">수정</a></td>						
											<td><a href="javascript:go_delete_2('<?=$cate2_row[idx]?>','<?=$row[cate_code1]?>','<?=$cate2_row[cate_code2]?>');" class="btn_blue2">삭제</a></td>		
										</tr>
									</form>

									<!-- 소카테고리 코드 입력창 시작 -->
									<tr id="cate3_insert_<?=$cate2_i?>" style="display:none;">
									<td colspan="7">
									<br>		
										<table class="t_list" style="width:95%;" align="center">
										<thead>
										<tr>
											<th width="15%">소카테고리 코드</th>
											<th width="30%">소카테고리 명</th>
											<th width="20%">정렬순서</th>
											<th width="20%">접근권한 허용</th>
											<th width="7%">등록</th>
											<th width="8%">등록취소</th>
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
											<input type="hidden" name="cate_code1" value="<?=$row[cate_code1]?>"> <!-- 카테고리 코드 -->
											<input type="hidden" name="cate_name1" value="<?=$row[cate_name1]?>"> <!-- 카테고리 명 -->
											<input type="hidden" name="cate_code2" value="<?=$cate2_row[cate_code2]?>"> <!-- 중카테고리 코드 -->
											<input type="hidden" name="cate_name2" value="<?=$cate2_row[cate_name2]?>"> <!-- 중카테고리 명 -->
											<input type="hidden" name="cate_level" value="3"> <!-- 소카테고리 -->
											<tr>
												<td><input type="text" style="width:90%;" name="cate_code3" onKeyup="checkNumber()" required="yes" message="소카테고리 코드" value=""></td>
												<td ><input type="text" style="width:90%;" name="cate_name3" required="yes" message="소카테고리 명" value=""></td>
												<td><input type="text" style="width:20%;" name="cate_align" required="yes" message="정렬순서" is_num="yes" value=""> 숫자만 입력, 높은숫자 우선순위</td>
												<td><input type="checkbox" name="head_ok" value="Y"> 본부장 <input type="checkbox" name="aca_ok" value="Y"> 학원장 <input type="checkbox" name="stu_ok" value="Y"> 학생 <input type="checkbox" name="nonmem_ok" value="Y"> 비회원</td>
												<td><a href="javascript:go_modify('frm3_insert_<?=$cate2_i?>');" class="btn_blue2">등록</a></td>
												<td><a href="javascript:go_low_cancel('<?=$cate2_i?>');" class="btn_blue2">등록취소</a></td>
											</tr>
										</form>
									</tbody>
									</table>
									<br>
										</td>

									</tr>
									<!-- 소카테고리 코드 입력창 종료 -->
									
										<?if($v_step == 3 && $row[cate_code1] == $cate_code1 && $cate2_row[cate_code2] == $cate_code2){ // 소카테고리 리스트 시작 ?>
											<?
												$cate3_sql = "select idx,cate_level,cate_code3,cate_name3,cate_align,wdate,head_ok,aca_ok,stu_ok,nonmem_ok from board_cate where 1=1 and cate_level='3' and cate_code1 = '".$cate_code1."' and cate_code2 = '".$cate_code2."' ORDER BY cate_align DESC ";
												$cate3_query = mysqli_query($gconnet,$cate3_sql);
												$cate3_cnt = mysqli_num_rows($cate3_query);

												if($cate3_cnt == 0){
											?>
												<tr>
													<td colspan="7">등록된 소카테고리가 없습니다.</td>		
												</tr>
											<?
											}

													for($cate3_i=0; $cate3_i<$cate3_cnt; $cate3_i++){ // 소카테고리 루프 시작
														$cate3_row = mysqli_fetch_array($cate3_query);
													?>
															<form name="frm_cate3_<?=$cate3_i?>" method="post" action="cate_modify_action.php"  target="_fra_admin">
																<input type="hidden" name="idx" value="<?=$cate3_row[idx]?>"/>
																<input type="hidden" name="cate_level" value="<?=$cate3_row[cate_level]?>"> <!-- 카테고리 구분-->
																<input type="hidden" name="cate_code1" value="<?=$cate_code1?>"> <!-- 카테고리 코드-->
																<input type="hidden" name="cate_code2" value="<?=$cate_code2?>"> <!-- 중카테고리 코드-->
																<input type="hidden" name="bmenu" value="<?=$bmenu?>">
																<input type="hidden" name="smenu" value="<?=$smenu?>">
																<input type="hidden" name="v_step" value="<?=$v_step?>">
																<input type="hidden" name="pageNo" value="<?=$pageNo?>">
																<input type="hidden" name="field" value="<?=$field?>">
																<input type="hidden" name="keyword" value="<?=$keyword?>">
															<tr>
																<td style="text-align:left;padding-left:30px;">
																	<img class="notRe" src="/manage/images/re.gif" alt="" />&nbsp;<?=$cate3_row[cate_code3]?></a>
																</td>
																<td style="text-align:left;padding-left:10px;"><input type="text" style="width:90%;" name="cate_name3" required="yes" message="중카테고리 명" value="<?=$cate3_row[cate_name3]?>"></td>
																<td><input type="text" style="width:20%;" name="cate_align" required="yes" message="정렬순서" is_num="yes" value="<?=$cate3_row[cate_align]?>"> 숫자만 입력, 높은숫자 우선</td>
																<td><?=substr($cate3_row[wdate],0,10)?></td>
																<td><input type="checkbox" name="head_ok" value="Y" <?=$cate3_row[head_ok]=="Y"?"checked":""?>> 본부장 <input type="checkbox" name="aca_ok" value="Y" <?=$cate3_row[aca_ok]=="Y"?"checked":""?>> 학원장 <input type="checkbox" name="stu_ok" value="Y" <?=$cate3_row[stu_ok]=="Y"?"checked":""?>> 학생 <input type="checkbox" name="nonmem_ok" value="Y" <?=$cate3_row[nonmem_ok]=="Y"?"checked":""?>> 비회원</td>
																<td></td>
																<td><a href="javascript:go_modify('frm_cate3_<?=$cate3_i?>');" class="btn_blue2">수정</a></td>						
																<td><a href="javascript:go_delete_3('<?=$cate3_row[idx]?>','<?=$row[cate_code1]?>','<?=$cate2_row[cate_code2]?>','<?=$cate3_row[cate_code3]?>');" class="btn_blue2">삭제</a></td>		
															</tr>
														</form>

													<?} // 소카테고리 루프 종료 ?>
								
										<?} // 소카테고리 리스트 종료?>

							   <?} // 중카테고리 루프 종료 ?>
								
						<?} // 중카테고리 리스트 종료?>

				<?} // 카테고리 루프 종료 ?>	
			
			</tbody>
			</table>
			
			<!-- //Goods List -->
				<!-- paginate -->
					<div class="paginate">
					<?
						$prev_img_path="../img/btn_pre.gif";
				  		$next_img_path="../img/btn_next.gif";
						$first_img_path="../img/btn_next_end.gif";
						$last_img_path="../img/btn_pre_end.gif";
						include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";
					?>
				</div>
				<!-- //paginate -->


		</div>
		
			
	</div>
</section>
<!-- //content -->
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>