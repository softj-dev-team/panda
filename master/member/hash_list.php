<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
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

$where .= " and set_code='memhas' and cate_level = '1' and is_del = 'N' "; // 상품 대해시태그만 우선 출력

$pageScale = 20; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

/*$query =	" SELECT * ";
$query = $query." FROM ( ";
$query = $query." SELECT	ROW_NUMBER() OVER(ORDER BY cate_align DESC) AS rowNumber ";
$query = $query.",	idx,cate_level,cate_code1,cate_name1,cate_align,wdate,heexp_ok,aca_ok,stu_ok,nonmem_ok ";
$query = $query." FROM viva_cate WITH(NOLOCK) ";
$query = $query." WHERE 1=1  ".$where;
$query = $query."	) AS S ";
$query = $query." WHERE S.rowNumber BETWEEN ".$StarRowNum." AND ".$EndRowNum." ";*/

$order_by = " order by cate_align desc ";
$query = "select * from viva_cate where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from viva_cate where 1=1 ".$where;
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
			document.forms[frm_name].is_del.value="Y";
			document.forms[frm_name].submit();		
		}
	}

	function go_delete_2(idx,catecode1,catecode2){
		if(confirm('해시태그를 삭제하시면 복구가 불가능하며, 사이트 운영에 버그가 발생할수도 있습니다. \n\n그래도 삭제 하시겠습니까?')){
			if(confirm('중해시태그 해시태그를 삭제하시면 소속된 소해시태그 해시태그가 모두 삭제됩니다. \n\n삭제된 내용은 복구되지 않습니다. 정말로 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "hash_delete_action.php?idx="+idx+"&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&cate_code1="+catecode1+"&cate_code2="+catecode2+"&cate_code3=<?=$cate_code3?>&cate_level=2";
			}
		}
	}

	function go_delete_3(idx,catecode1,catecode2,catecode3){
		if(confirm('해시태그를 삭제하시면 복구가 불가능하며, 사이트 운영에 버그가 발생할수도 있습니다. \n\n그래도 삭제 하시겠습니까?')){
			if(confirm('삭제된 내용은 복구되지 않습니다. 정말로 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "hash_delete_action.php?idx="+idx+"&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=<?=$v_step?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&cate_code1="+catecode1+"&cate_code2="+catecode2+"&cate_code3="+catecode3+"&cate_level=3";
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
		location.href = "hash_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>";
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
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/member_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>회원 관리</li>
						<li>해시태그 관리</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>회원 해시태그 등록/수정</h3>
				</div>
				<div class="list">
					<div class="search_wrap">
					<!-- 검색창 시작 -->
					<form name="s_mem" method="post" action="hash_list.php">
						<input type="hidden" name="v_sect" value="<?=$v_sect?>"/>
						<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
						<input type="hidden" name="smenu" value="<?=$smenu?>"/>
						<div class="search_area">
							<!--<select name="s_sect1" size="1" style="vertical-align:middle;" >
								<option value="">로그인여부</option>
								<option value="Y" <?=$s_sect1=="Y"?"selected":""?>>로그인 가능</option>
								<option value="N" <?=$s_sect1=="N"?"selected":""?>>로그인 차단</option>
							</select>
							&nbsp;&nbsp;
							<select name="field" size="1" style="vertical-align:middle;">
								<option value="">검색기준</option>
								<option value="user_id" <?=$field=="user_id"?"selected":""?>>아이디</option>
								<option value="com_name" <?=$field=="com_name"?"selected":""?>>가맹점명</option>
								<option value="presi_name" <?=$field=="presi_name"?"selected":""?>>점주님명</option>
								<option value="cell" <?=$field=="cell"?"selected":""?>>휴대전화</option>
								<option value="email" <?=$field=="email"?"selected":""?>>이메일</option>
							</select>
							<input type="text" title="검색" name="keyword" id="keyword" value="<?=$keyword?>">
							<button onclick="s_mem.submit();">검색</button>-->
						</div>
						<div class="result">
							<div class="btn_wrap" style="height:36px;">
								<!--<select>
									<option>전체</option>
								</select>-->
							</div>
						</div>
					</form>
					<!-- 검색창 종료 -->
					<!-- 리스트 시작 -->
						<div class="list_tit" style="margin-top:-50px;">
							<h3>회원 해시태그 등록</h3>
						</div>
						
						<table class="search_list">
							<caption>해시태그등록</caption>
							<colgroup>
								<col style="width:40%;">
								<col style="width:50%">
								<col style="width:10%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">해시태그명</th>
									<th scope="col">정렬순서</th>
									<th scope="col">등록</th>
								</tr>
							</thead>
							<tbody>
							<form method="post" action="hash_write_action.php" name="frm_1" target="_fra_admin" id="frm_1" enctype="multipart/form-data">
								<input type="hidden" name="bmenu" value="<?=$bmenu?>">
								<input type="hidden" name="smenu" value="<?=$smenu?>">
								<input type="hidden" name="v_step" value="<?=$v_step?>">
								<input type="hidden" name="pageNo" value="<?=$pageNo?>">
								<input type="hidden" name="field" value="<?=$field?>">
								<input type="hidden" name="keyword" value="<?=$keyword?>">
								<input type="hidden" name="cate_level" value="1"> <!-- 대해시태그 -->
								<tr>
									<td><input type="text" style="width:90%;" name="cate_name1" required="yes" message="해시태그명" value=""></td>
									<td><input type="text" style="width:20%;" name="cate_align" required="yes" message="정렬순서" is_num="yes" value=""> 숫자만 입력, 높은숫자 우선순위</td>
									<td><a href="javascript:go_submit_1();" class="btn_blue">등록</a></td>						
								</tr>
							</form>
						</tbody>
						</table>

						<div class="list_tit" style="margin-top:20px;">
							<h3>회원 해시태그 관리</h3>
						</div>
						<!--해시태그코드를 클릭하시면 하위 해시태그 목록이 나옵니다.-->
						<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:20%;">
								<col style="width:20%">
								<col style="width:25%;">
								<col style="width:10%;">
								<col style="width:10%;">
								<!--<col style="width:10%;">-->
								<col style="width:15%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">해시태그코드</th>
									<th scope="col">해시태그명</th>
									<th scope="col">정렬순서</th>
									<th scope="col">사용여부</th>
									<th scope="col">등록일</th>
									<!--<th scope="col">하위등록</th>-->
									<th scope="col">관리</th>
								</tr>
							</thead>
							<tbody>
						<? if($num==0) { ?>
							<tr>
								<td colspan="10" height="40">등록된 해시태그가 없습니다.</strong></td>
							</tr>
						<? } ?>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){ // 대해시태그 루프 시작
								$row = mysqli_fetch_array($result);

								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

						?>
							<form name="frm_cate1_<?=$i?>" method="post" action="hash_modify_action.php"  target="_fra_admin" enctype="multipart/form-data">
								<input type="hidden" name="idx" value="<?=$row[idx]?>"/>
								<input type="hidden" name="cate_level" value="<?=$row[cate_level]?>"> <!-- 해시태그 구분-->
								<input type="hidden" name="bmenu" value="<?=$bmenu?>">
								<input type="hidden" name="smenu" value="<?=$smenu?>">
								<input type="hidden" name="v_step" value="<?=$v_step?>">
								<input type="hidden" name="pageNo" value="<?=$pageNo?>">
								<input type="hidden" name="field" value="<?=$field?>">
								<input type="hidden" name="keyword" value="<?=$keyword?>">
								<!--<input type="hidden" name="is_del" value="N">-->
								<tr>
									<td style="text-align:left;padding-left:10px;"><!--<a href="hash_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=2&cate_code1=<?=$row[cate_code1]?>">--><?=$row[cate_code1]?><!--</a>--></td>
									<td style="text-align:left;padding-left:10px;"><input type="text" style="width:90%;" name="cate_name1" required="yes" message="해시태그명" value="<?=$row[cate_name1]?>"></td>
									<td><input type="text" style="width:20%;" name="cate_align" required="yes" message="정렬순서" is_num="yes" value="<?=$row[cate_align]?>"> 숫자만 입력, 높은숫자 우선</td>
									<td>
										<select name="is_del" id="is_del" required="yes" message="사용여부">
											<option value="N" <?=$row[is_del]=="N"?"selected":""?>>정상사용</option> 
											<option value="Y" <?=$row[is_del]=="Y"?"selected":""?>>사용안함</option> 
										</select>
									</td>
									<td><?=substr($row[wdate],0,10)?></td>
									<!--<td><a href="javascript:go_middle_regist('<?=$i?>');" class="btn_blue2">하위등록</a></td>-->
									<td>
										<a href="javascript:go_modify('frm_cate1_<?=$i?>');" class="btn_green">수정</a>
										<!--<a href="javascript:go_delete('frm_cate1_<?=$i?>');" class="btn_red">삭제</a>-->
									</td>						
								</tr>
							</form>

							<!-- 중해시태그 코드 입력창 시작 -->
							<tr id="cate2_insert_<?=$i?>" style="display:none;">
							<td colspan="7">
								<br>		
								<table class="t_list" style="width:95%;" align="center">
								<thead>
								<tr>
									<th width="20%">중해시태그 명</th>
									<th width="30%">메인 이미지</th>
									<th width="30%">정렬순서</th>
									<th width="10%">등록</th>
									<th width="10%">등록취소</th>
								</tr>
								</thead>
								<tbody>
									<form method="post" action="hash_write_action.php" name="frm2_insert_<?=$i?>" target="_fra_admin" id="frm2_insert_<?=$i?>" enctype="multipart/form-data">
										<input type="hidden" name="bmenu" value="<?=$bmenu?>">
										<input type="hidden" name="smenu" value="<?=$smenu?>">
										<input type="hidden" name="v_step" value="<?=$v_step?>">
										<input type="hidden" name="pageNo" value="<?=$pageNo?>">
										<input type="hidden" name="field" value="<?=$field?>">
										<input type="hidden" name="keyword" value="<?=$keyword?>">
										<input type="hidden" name="cate_code1" value="<?=$row[cate_code1]?>"> <!-- 대해시태그 코드 -->
										<input type="hidden" name="cate_name1" value="<?=$row[cate_name1]?>"> <!-- 대해시태그 명 -->
										<input type="hidden" name="cate_level" value="2"> <!-- 중해시태그 -->
										<tr>
											<td ><input type="text" style="width:90%;" name="cate_name2" required="yes" message="중해시태그 명" value=""></td>
											<td><input type="file" name="file1" style="width:50%;" required="no"  message="메인 이미지"/>
												<br> <font style="color:blue;">이미지를 등록하시면 쇼핑몰 메인화면 해시태그에 설정됩니다</font>
											</td>
											<td><input type="text" style="width:20%;" name="cate_align" required="yes" message="정렬순서" is_num="yes" value=""> 숫자만 입력, 높은숫자 우선순위</td>
											<td><a href="javascript:go_modify('frm2_insert_<?=$i?>');" class="btn_blue2">등록</a></td>
											<td><a href="javascript:go_middle_cancel('<?=$i?>');" class="btn_blue2">등록취소</a></td>
										</tr>
									</form>
								</tbody>
								</table>
								<br>
								</td>
							</tr>
						<!-- 중해시태그 코드 입력창 종료 -->

						<?if($v_step > 1 && $row[cate_code1] == $cate_code1){ // 중해시태그 리스트 시작 ?>
							<?
								$cate2_sql = "select idx,cate_level,cate_code2,cate_name2,cate_align,wdate,is_del,file_o,file_c,file_o2,file_c2 from viva_cate where 1=1 and cate_level='2' and cate_code1 = '".$cate_code1."' and is_del = 'N' ORDER BY cate_align DESC";
								$cate2_query = mysqli_query($gconnet,$cate2_sql);
								$cate2_cnt = mysqli_num_rows($cate2_query);

								if($cate2_cnt == 0){
							?>
								<tr>
									<td colspan="7">해당하는 하위해시태그가 없습니다.</td>		
								</tr>
							<?
								}

								for($cate2_i=0; $cate2_i<$cate2_cnt; $cate2_i++){ // 중해시태그 루프 시작
									$cate2_row = mysqli_fetch_array($cate2_query);
							?>
									
									<form name="frm_cate2_<?=$cate2_i?>" method="post" action="hash_modify_action.php"  target="_fra_admin" enctype="multipart/form-data">
										<input type="hidden" name="idx" value="<?=$cate2_row[idx]?>"/>
										<input type="hidden" name="cate_level" value="<?=$cate2_row[cate_level]?>"> <!-- 해시태그 구분-->
										<input type="hidden" name="cate_code1" value="<?=$cate_code1?>"> <!-- 대해시태그 코드-->
										<input type="hidden" name="bmenu" value="<?=$bmenu?>">
										<input type="hidden" name="smenu" value="<?=$smenu?>">
										<input type="hidden" name="v_step" value="<?=$v_step?>">
										<input type="hidden" name="pageNo" value="<?=$pageNo?>">
										<input type="hidden" name="field" value="<?=$field?>">
										<input type="hidden" name="keyword" value="<?=$keyword?>">
										<input type="hidden" name="is_del" value="N">
										<tr>
											<td style="text-align:left;padding-left:20px;">
												<img class="notRe" src="/img/icon/re.gif" alt="" />&nbsp;<!--<a href="hash_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&v_step=3&cate_code1=<?=$row[cate_code1]?>&cate_code2=<?=$cate2_row[cate_code2]?>">--><?=$cate2_row[cate_code2]?><!--</a>-->
											</td>
											<td style="text-align:left;padding-left:10px;"><input type="text" style="width:90%;" name="cate_name2" required="yes" message="중해시태그 명" value="<?=$cate2_row[cate_name2]?>">
											<br><br>

									<?
									$sect1_sql = "select common_cate_code,common_cate_name from product_common_cate where 1 and cate_type = 'suba' and is_del='N' order by cate_align desc";
									$sect1_result = mysqli_query($gconnet,$sect1_sql);
									for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
										$row1 = mysqli_fetch_array($sect1_result);

										$sect1_check_sql = "select idx from cate_common_set where 1 and cate_idx='".$cate2_row[idx]."' and common_code = '".$row1[common_cate_code]."' ";
										$sect1_check_result = mysqli_query($gconnet,$sect1_check_sql);
										if(mysqli_num_rows($sect1_check_result)>0){
											$check_sect1 = "checked";
										} else {
											$check_sect1 = "";
										}
								?>
									<input type="checkbox" name="pro_cate1[]" id="cate1_<?=$row1[common_cate_code]?>" required="no"  message="서브해시태그" value="<?=$row1[common_cate_code]?>" <?=$check_sect1?>> <?=$row1[common_cate_name]?> &nbsp; 
								<?}?>
											</td>
											<td>
											<!--<?if($cate2_row['file_c']){?>
												기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$cate2_row['file_c']?>&on=<?=$cate2_row['file_o']?>&dir=cate_banner"><?=$cate2_row['file_o']?></a>
												(기존파일 삭제 : <input type="checkbox" name="del_org1" value="Y">)
											<?}?>
											<input type="hidden" name="file_old_name1" value="<?=$cate2_row[file_c]?>" />
											<input type="hidden" name="file_old_org1" value="<?=$cate2_row[file_o]?>" />
											<br>
											<input type="file" name="file1" style="width:50%;" required="no"  message="배너 이미지"/>
											<br> <font style="color:blue;">이미지를 등록하시면 쇼핑몰 메인화면 해시태그에 설정됩니다</font>-->
											<?if($cate2_row['file_c2']){?>
												기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$cate2_row['file_c2']?>&on=<?=$cate2_row['file_o2']?>&dir=cate_banner"><?=$cate2_row['file_o2']?></a>
												(기존파일 삭제 : <input type="checkbox" name="del_org2" value="Y">)
											<?}?>
											<input type="hidden" name="file_old_name2" value="<?=$cate2_row[file_c2]?>" />
											<input type="hidden" name="file_old_org2" value="<?=$cate2_row[file_o2]?>" />
											<br>
											<input type="file" name="file2" style="width:50%;" required="no"  message="배너 이미지"/>
											<br> <font style="color:blue;">검색레이어 아이콘 설정</font>
											</td>
											<td><input type="text" style="width:20%;" name="cate_align" required="yes" message="정렬순서" is_num="yes" value="<?=$cate2_row[cate_align]?>"> 숫자만 입력, 높은숫자 우선</td>
											<td>
											<select name="is_del" id="is_del" required="yes" message="사용여부">
												<option value="N" <?=$cate2_row[is_del]=="N"?"selected":""?>>정상사용</option> 
												<option value="Y" <?=$cate2_row[is_del]=="Y"?"selected":""?>>사용안함</option> 
											</select>
											</td>
											<td>&nbsp;</td>
											<td><?=substr($cate2_row[wdate],0,10)?></td>
											<td>
												<a href="javascript:go_modify('frm_cate2_<?=$cate2_i?>');" class="btn_blue2">수정</a>
												<a href="javascript:go_delete('frm_cate2_<?=$cate2_i?>');" class="btn_red">삭제</a>
											</td>						
										</tr>
									</form>
									
							   <?} // 중해시태그 루프 종료 ?>
							<?} // 중해시태그 리스트 종료?>
						<?} // 대해시태그 루프 종료 ?>	
						</tbody>
						</table>
						<!-- 페이징 시작 -->
						<div class="pagination mt0">
							<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging.php";?>
						</div>
						<!-- 페이징 종료 -->

					</div>
				</div>
			</div>
		</div>
		<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>
