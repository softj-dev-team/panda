<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>

<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 관리자페이지 상단메뉴?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/patin_left.php"; // 사이트설정 좌측메뉴?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));

$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

if(!$pageNo){
	$pageNo = 1;
}

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword;

$pageScale = 20; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

//$where .= " and idx != '1' "; // 기본 설정 등급은 공개되지 않도록 한다.

$where = " and level_sect='PAT' ";

/*$query =	" SELECT * ";
$query = $query." FROM ( ";
$query = $query." SELECT	ROW_NUMBER() OVER(ORDER BY member_level_align DESC) AS rowNumber ";
$query = $query.",	idx,member_level_level,member_level_code1,member_level_name1,member_level_align,wdate,head_ok,aca_ok,stu_ok,nonmem_ok ";
$query = $query." FROM member_level_set WITH(NOLOCK) ";
$query = $query." WHERE 1=1  ".$where;
$query = $query."	) AS S ";
$query = $query." WHERE S.rowNumber BETWEEN ".$StarRowNum." AND ".$EndRowNum." "; */

$order_by = " order by level_align desc ";

$query = "select * from member_level_set where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from member_level_set where 1=1 ".$where;
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
				_fra_admin.location.href = "member_level_delete_action.php?idx="+idx+"&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&member_level_sect=<?=$member_level_sect?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&member_level_code1="+catecode+"&member_level_code2=<?=$member_level_code2?>&member_level_code3=<?=$member_level_code3?>&member_level_level=1";
			//}
		}
	}

	function go_delete_2(idx,catecode1,catecode2){
		if(confirm('카테고리를 삭제하시면 사이트 운영에 버그가 발생할수도 있습니다. 그래도 삭제 하시겠습니까?')){
			if(confirm('중카테고리 카테고리를 삭제하시면 소속된 소카테고리 카테고리가 모두 삭제됩니다. 정말로 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "member_level_delete_action.php?idx="+idx+"&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&member_level_sect=<?=$member_level_sect?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&member_level_code1="+catecode1+"&member_level_code2="+catecode2+"&member_level_code3=<?=$member_level_code3?>&member_level_level=2";
			}
		}
	}

	function go_delete_3(idx,catecode1,catecode2,catecode3){
		if(confirm('카테고리를 삭제하시면 사이트 운영에 버그가 발생할수도 있습니다. 그래도 삭제 하시겠습니까?')){
			if(confirm('정말로 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "member_level_delete_action.php?idx="+idx+"&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&member_level_sect=<?=$member_level_sect?>&pageNo=<?=$pageNo?>&field=<?=$field?>&keyword=<?=$keyword?>&member_level_code1="+catecode1+"&member_level_code2="+catecode2+"&member_level_code3="+catecode3+"&member_level_level=3";
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
		location.href = "member_level_list.php?bmenu=<?=$bmenu?>&smenu=<?=$smenu?>";
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
			가수등급 등록
		</h3>
		<div class="cont">
						
			<table class="t_list">
				<thead>
					<tr>
						<th width="10%">등급코드</th>
						<th width="15%">등급이름</th>
						<th width="15%">등급 아이콘</th>
						<th width="10%">섭외금액</th>
						<th width="15%">승급기준 판매수</th>
						<th width="25%">정렬순서</th>
						<th width="10%">등록</th>
					</tr>
				</thead>
				<tbody>
			
				<form method="post" action="member_level_write_action.php" name="frm_1" target="_fra_admin" id="frm_1" enctype="multipart/form-data">
				<input type="hidden" name="total_param" value="<?=$total_param?>">
				<input type="hidden" name="member_level_sect" value="<?=$member_level_sect?>">
					<tr>
						<td><input type="text" style="width:50%;" name="level_code" onKeyup="checkNumber()" required="yes" message="등급코드" value=""></td>
						<td><input type="text" style="width:60%;" name="level_name" required="yes" message="등급이름" value=""></td>
						<td><input type="file" style="width:70%;" id="file1" name="file1" required="no" message="등급아이콘" value=""></td>
						<td><input type="text" style="width:50%;" name="level_price" required="yes" message="섭외금액" is_num="yes"> 원</td>
						<td><input type="text" style="width:30%;" name="level_gijun" required="yes" message="승급기준 판매수" is_num="yes"> 회</td>
						<td><input type="text" style="width:20%;" name="level_align" required="yes" message="정렬순서" is_num="yes" value=""> 숫자만 입력, 높은숫자 우선순위</td>
						<td><a href="javascript:go_submit_1();" class="btn_blue2">등록</a></td>						
					</tr>
				</form>
							
			</tbody>
			</table>
			
			<!-- //Goods List -->
			</div>

		<h3>
			가수등급 관리 
		</h3>
		<div class="cont">
		
			<table class="t_list">
				<thead>
					<tr>
						<th width="10%">등급코드</th>
						<th width="10%">등급이름</th>
						<th width="15%">등급 아이콘</th>
						<th width="10%">섭외금액</th>
						<th width="10%">승급기준 판매수</th>
						<th width="20%">정렬순서</th>
						<th width="7%">사용여부</th>
						<th width="10%">등록일</th>
						<th width="8%">설정수정</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>등록된 가수등급이 없습니다.</strong></td>
					</tr>
				<? } ?>

				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){ //  루프 시작
					$row = mysqli_fetch_array($result);

					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				?>
						<form name="frm_cate1_<?=$i?>" method="post" action="member_level_modify_action.php"  target="_fra_admin" enctype="multipart/form-data">
							<input type="hidden" name="idx" value="<?=$row[idx]?>"/>
							<input type="hidden" name="total_param" value="<?=$total_param?>">
							<input type="hidden" name="pageNo" value="<?=$pageNo?>">
							
						<?if($row[level_code] == "wedga1"){ // 가수가입시 기본가수 ?>	
							<input type="hidden" name="level_gijun" value="<?=$row[level_gijun]?>">
							<input type="hidden" name="level_align" value="<?=$row[level_align]?>"> 
							<input type="hidden" name="is_del" value="<?=$row[is_del]?>"> 
							<tr>
								<td><?=$row[level_code]?></td>
								<td><input type="text" style="width:90%;" name="level_name" required="yes" message="등급이름" value="<?=$row[level_name]?>"></td>
								<td><input type="file" style="width:70%;" id="file1" name="file1" required="no" message="등급아이콘" value="">
								<?if($row['file_chg']){?>
								<br>		기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row['file_chg']?>&on=<?=$row['file_org']?>&dir=level"><?=$row['file_org']?></a>
									(기존파일 삭제 : <input type="checkbox" name="del_org1" value="Y">)
									<input type="hidden" name="file_old_name1" value="<?=$row[file_chg]?>" />
									<input type="hidden" name="file_old_org1" value="<?=$row[file_org]?>" />
								<?}?>
								</td>
								<td><input type="text" style="width:50%;" name="level_price" required="yes" message="섭외금액" is_num="yes" value="<?=$row[level_price]?>"> 원</td>
								<td colspan="3"> 가수가입시 기본으로 설정되는 등급입니다. 기본설정 등급은 등급이름과 섭외금액만 변경가능 합니다. </td>
								<td><?=substr($row[wdate],0,10)?></td>
								<td><a href="javascript:go_modify('frm_cate1_<?=$i?>');" class="btn_blue2">설정수정</a></td>
							</tr>
						<? } else { // 가수가입시 기본가수 아닌 일반 생성가수 ?>
							<tr>
								<td><?=$row[level_code]?></td>
								<td><input type="text" style="width:90%;" name="level_name" required="yes" message="등급이름" value="<?=$row[level_name]?>"></td>
								<td><input type="file" style="width:70%;" id="file1" name="file1" required="no" message="등급아이콘" value="">
								<?if($row['file_chg']){?>
								<br>		기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row['file_chg']?>&on=<?=$row['file_org']?>&dir=level"><?=$row['file_org']?></a>
									(기존파일 삭제 : <input type="checkbox" name="del_org1" value="Y">)
									<input type="hidden" name="file_old_name1" value="<?=$row[file_chg]?>" />
									<input type="hidden" name="file_old_org1" value="<?=$row[file_org]?>" />
								<?}?>
								</td>
								<td><input type="text" style="width:50%;" name="level_price" required="yes" message="섭외금액" is_num="yes" value="<?=$row[level_price]?>"> 원</td>
								<td><input type="text" style="width:40%;" name="level_gijun" required="yes" message="승급기준 판매수" is_num="yes" value="<?=$row[level_gijun]?>"> 회 </td>
								<td><input type="text" style="width:20%;" name="level_align" required="yes" message="정렬순서" is_num="yes" value="<?=$row[level_align]?>"> 숫자만 입력, 높은숫자 우선</td>
								<td><input type="radio" name="is_del" value="N" <?=$row[is_del]=="N"?"checked":""?>> 사용 <input type="radio" name="is_del" value="Y" <?=$row[is_del]=="Y"?"checked":""?>> 삭제 </td>
								<td><?=substr($row[wdate],0,10)?></td>
								<td><a href="javascript:go_modify('frm_cate1_<?=$i?>');" class="btn_blue2">설정수정</a></td>
							</tr>
						<?}?>
						</form>
						
				<?} // 루프 종료 ?>	
			
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