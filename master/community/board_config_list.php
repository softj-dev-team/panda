<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>

<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/admin_top.php"; // 관리자페이지 상단메뉴?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/community_left.php"; // 게시판관리 좌측메뉴?>
<?
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2;

if(!$pageNo){
	$pageNo = 1;
}

$where .= " and cate1 != 'tsys' "; // 고객센터용 게시판 제외한다.

if($s_sect1){
	$where .= " and cate1 = '".$s_sect1."' "	;
}

if($s_sect2){
	$where .= " and close_ok = '".$s_sect2."' "	;
}

if ($field && $keyword){
	$where .= "and ".$field." like '%".$keyword."%'";
}

$pageScale = 10; // 페이지당 10 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by board_align desc ";

$query = "select * from board_config where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from board_config where 1=1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale;
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_view(no){
		location.href = "board_config_view.php?idx="+no+"&pageNo=<?=$pageNo?>&<?=$total_param?>";
	}
	
	function go_list(){
		location.href = "board_config_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "board_config_write.php?<?=$total_param?>";
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
	
//-->
</SCRIPT>

<!-- content -->
<section id="content">
	<div class="inner">
		<h3>
			생성된 게시판 리스트 <?//=$_SERVER["DOCUMENT_ROOT"]?>
		</h3>
		<div class="cont">
			<!-- srch_bar -->
			<form name="s_mem" method="post" action="board_config_list.php">
			<input type="hidden" name="mode" value="ser">
			<input type="hidden" name="bbs_code" value="<?=$bbs_code?>"/>
			<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
			<input type="hidden" name="smenu" value="<?=$smenu?>"/>
			<dl class="srch_bar">
				<dt>카테고리별 검색</dt>
				<dd>
					<select name="s_sect1" size="1" style="vertical-align:middle;">
							<option value="">게시판 카테고리</option>
						<?
						$sect1_sql = "select cate_code1,cate_name1 from board_cate where cate_level = '1' and cate_code1 != 'tsys' and is_del != 'Y' order by cate_align desc";
						$sect1_result = mysqli_query($gconnet,$sect1_sql);
							for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
								$row1 = mysqli_fetch_array($sect1_result);
						?>
							<option value="<?=$row1[cate_code1]?>" <?=$row1[cate_code1]==$s_sect1?"selected":""?>><?=$row1[cate_name1]?></option>
						<?}?>
					</select> &nbsp; 
				</dd>

				<dt>게시판 형태</dt>
				<dd>
					
					<select name="s_sect2" size="1" style="vertical-align:middle;">
							<option value="">일반형/과금형</option>
							<option value="N" <?=$s_sect2=="N"?"selected":""?>>일반형 게시판</option>
							<option value="Y" <?=$s_sect2=="Y"?"selected":""?>>과금형 게시판</option>
					</select> &nbsp; 

				</dd>

				<dt>조건 검색</dt>
				<dd>
					
					<select name="field" size="1" style="vertical-align:middle;">
							<option value="">검색기준</option>
							<option value="board_code" <?=$field=="board_code"?"selected":""?>>게시판 코드</option>
							<option value="board_title" <?=$field=="board_title"?"selected":""?>>게시판 명</option>
					</select>
					
					<input type="text" name="keyword" id="keyword" style="width:200px;" value="<?=$keyword?>" >

					<input type="image" src="/manage/img/btn_search.gif" alt="검색" align="absmiddle"/>
				</dd>
			</dl>
			</form>
			<!-- //srch_bar -->
			<div class="clear"><?//=$query?></div>
			<br>
			<!-- button -->
			<table width="100%" align="center">
				<tr>
					<td align="right">
						<a href="javascript:go_regist();" class="btn_blue2_big">게시판 생성하기</a>
					</td>
				</tr>
			</table>
			<br>
			<!-- //button -->			
			<!-- Goods List -->
			
			<table class="t_list">
				<thead>
					<tr>
						<th width="5%">번호</th>
						<th width="10%">카테고리</th>
						<th width="8%">게시판 코드</th>
						<th width="17%">게시판 명</th>
						<th width="7%">게시판형태</th>
						<th width="10%">생성일자</th>
						<th width="8%">바로가기</th>
						<th width="10%">삭제여부</th>
						<th width="15%">정렬순서</th>
						<th width="10%">순서 및 삭제여부 변경</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>생성된 게시판이 없습니다.</strong></td>
					</tr>
				<? } ?>
	
				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);

					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

					$reg_time3 = to_time(substr($row[wdate],0,10));

					$sql_sub1 = "select cate_name1 from board_cate where cate_code1='".$row[cate1]."' and cate_level='1' ";
					$query_sub1 = mysqli_query($gconnet,$sql_sub1);
					$row_sub1 = mysqli_fetch_array($query_sub1);
					$pro_sect1 = $row_sub1[cate_name1];

					if($row[is_del] == "Y"){
						$del_ok = "삭제된 게시판";
					} else {
						$del_ok = "정상사용중";
					}

					if($row[close_ok] == "Y"){
						$close_ok = "과금형";
					} else {
						$close_ok = "일반형";
					}

				?>
				<form name="frm_cate1_<?=$i?>" method="post" action="board_config_list_modify_action.php"  target="_fra_admin">
					<input type="hidden" name="idx" value="<?=$row[idx]?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"> 
					<input type="hidden" name="pageNo" value="<?=$pageNo?>"/>
					
					<tr>
						<td><?=$listnum?></td>
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$pro_sect1?></a></td>
						<td ><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[board_code]?></a></td>
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$row[board_title]?></a></td>
						<td><a href="javascript:go_view('<?=$row[idx]?>');"><?=$close_ok?></a></td>
						<td><?=substr($row[wdate],0,10)?></td>
						<td><a href="board_list.php?bmenu=<?=$bmenu?>&s_cate_code=<?=$row[cate1]?>&bbs_code=<?=$row[board_code]?>" class="btn_blue2">바로가기</a></td>
						<td><input type="radio" name="is_del" value="N" <?=$row[is_del]=="N"?"checked":""?>> 정상사용 <input type="radio" name="is_del" value="Y" <?=$row[is_del]=="Y"?"checked":""?>> 게시판 삭제 </td>
						<td><input type="text" style="width:20%;" name="board_align" required="yes" message="정렬순서" is_num="yes" value="<?=$row[board_align]?>"> 숫자만 입력, 높은숫자 우선</td>	
						<td><a href="javascript:go_modify('frm_cate1_<?=$i?>');" class="btn_blue2">수정하기</a></td>			
					</tr>
				
				</form>
				<?}?>	
			
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