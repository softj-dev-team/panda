<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>

<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/admin_top.php"; // 관리자페이지 상단메뉴?>
<? include $_SERVER["DOCUMENT_ROOT"]."/weddingcrea/manage/include/community_left.php"; // 게시판관리 좌측메뉴?>
<?
/*if(!$_AUTH_LIST){
	error_back("게시판 접근권한이 없습니다.");
	exit;
}*/

$s_cate_code = trim(sqlfilter($_REQUEST['s_cate_code'])); // 게시판 카테고리 코드
$bbs_code = trim(sqlfilter($_REQUEST['bbs_code'])); // 게시판 코드
$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // 게시판 분류
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&v_sect='.$v_sect.'&s_cate_code='.$s_cate_code;

if(!$pageNo){
	$pageNo = 1;
}

if ($v_sect){
	$where .= "and bbs_sect = '".$v_sect."'";
}

if($bbs_code){
	$where .= " and bbs_code = '".$bbs_code."' "; // 선택한 게시판에 해당하는 내용만 추출한다
} elseif($s_cate_code) {
	
	$sc_board_sql = "select board_code from board_config where 1=1 and cate1 = '".$s_cate_code."' ";
	$sc_board_query = mysqli_query($gconnet,$sc_board_sql);
	$sc_board_cnt = mysqli_num_rows($sc_board_query);

	for ($sc_board_j=0; $sc_board_j<$sc_board_cnt; $sc_board_j++){
		$sc_board_row = mysqli_fetch_array($sc_board_query);

			if($sc_board_j == $sc_board_cnt-1){
				$sc_board_where .= "'".$sc_board_row['board_code']."'";
			} else {
				$sc_board_where .= "'".$sc_board_row['board_code']."',";
			}

	}

	if($sc_board_cnt > 0){
		$where .= " and bbs_code in (".$sc_board_where.") "; // 해당 카테고리로 만들어진 게시판이 있을 경우 카테고리에 해당하는 게시판 코드를 추출하여 in query 로 내용을 추출한다.
	} else {
		$where .= " and idx = '0' "; // 해당 카테고리로 만들어진 게시판이 없을 경우 글 리스트를 추출하지 않는다.
	}

}

if ($field && $keyword){
	if($field == "subtent"){
		$where .= "and (subject like '%".$keyword."%' or content like '%".$keyword."%')";
	} else {
		$where .= "and ".$field." like '%".$keyword."%'";
	}
}

$pageScale = 20; // 페이지당 20 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " ORDER BY ref desc, step asc, depth asc ";

$query = "select * from board_content where 1=1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$query_cnt = "select idx from board_content where 1=1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

//echo $num;

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale;

if($s_cate_code) {
	$sql_sub1 = "select cate_name1 from board_cate where cate_code1='".$s_cate_code."' and cate_level='1' ";
	$query_sub1 = mysqli_query($gconnet,$sql_sub1);
	$row_sub1 = mysqli_fetch_array($query_sub1);
	$bbs_cate_name = $row_sub1['cate_name1'];
}

if($bbs_code){
	$bbs_str = $bbs_cate_name." >> ".$_include_board_board_title;
} elseif($s_cate_code) {
	$bbs_str = $bbs_cate_name." 카테고리에 해당하는 ";
}
?>

<SCRIPT LANGUAGE="JavaScript">
<!--
	function go_view(no,bcode){
		location.href = "board_view.php?idx="+no+"&bbs_code="+bcode+"&pageNo=<?=$pageNo?>&bmenu=<?=$bmenu?>&smenu=<?=$smenu?>&field=<?=$field?>&keyword=<?=$keyword?>&s_cate_code=<?=$s_cate_code?>&v_sect=<?=$v_sect?>";
	}
	
	function go_list(){
		location.href = "board_list.php?<?=$total_param?>";
	}

	function go_regist(){
		location.href = "board_write.php?<?=$total_param?>";
	}
	
	function go_search() {
		if(!frm_page.field.value || !frm_page.keyword.value) {
			alert("검색조건 또는 검색어를 입력해 주세요!!") ;
			exit;
		}
		frm_page.submit();
	}
	
//-->
</SCRIPT>

<!-- content -->
<section id="content">
	<div class="inner">
		<h3>
			<?=$bbs_str?> 게시판 리스트 
		</h3>
		<div class="cont">

			<!-- 카테고리별 게시판 리스트 시작 -->
			<form name="s_mem_cate" method="post" action="board_list.php">
			<input type="hidden" name="mode" value="ser">
			<input type="hidden" name="s_cate_code" value="<?=$s_cate_code?>"/>
			<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
			<input type="hidden" name="smenu" value="<?=$smenu?>"/>
			<dl class="srch_bar">
				<dt><?=$bbs_cate_name?> 카테고리 게시판 </dt>
				<dd>
					<select name="bbs_code" size="1" style="vertical-align:middle;" onchange="s_mem_cate.submit();">
							<option value=""><?=$bbs_cate_name?> 카테고리 해당하는 게시판 바로가기</option>
						<?
						$sect1_sql = "select board_code,board_title from board_config where 1=1 and cate1 != 'tsys' and cate1 = '".$s_cate_code."' order by board_align desc";
						$sect1_result = mysqli_query($gconnet,$sect1_sql);
						$cate_sect_cnt = mysqli_num_rows($sect1_result);

							for ($i=0; $i<$cate_sect_cnt; $i++){
								$row1 = mysqli_fetch_array($sect1_result);
						?>
							<option value="<?=$row1[board_code]?>" <?=$row1[board_code]==$bbs_code?"selected":""?>><?=$row1[board_title]?></option>
						<?}?>
					</select> &nbsp; 
				</dd>
			</dl>
			</form>
			<!-- // 카테고리별 게시판 리스트 종료 -->
			<div class="clear"></div>
			<br>
	
	<? if($cate_sect_cnt > 0) { ?>

			<!-- srch_bar -->
			<form name="s_mem" method="post" action="board_list.php">
			<input type="hidden" name="mode" value="ser">
			<input type="hidden" name="s_cate_code" value="<?=$s_cate_code?>"/>
			<input type="hidden" name="bbs_code" value="<?=$bbs_code?>"/>
			<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
			<input type="hidden" name="smenu" value="<?=$smenu?>"/>
			<dl class="srch_bar">
				<dt>분류</dt>
				<dd>
					<select name="v_sect" size="1" style="vertical-align:middle;">
							<option value="">분류별 보기</option>
							<option value="info" <?=$v_sect=="info"?"selected":""?>>정보</option>
							<option value="quest" <?=$v_sect=="quest"?"selected":""?>>질문</option>
							<option value="free" <?=$v_sect=="free"?"selected":""?>>자유</option>
							<option value="comp" <?=$v_sect=="comp"?"selected":""?>>불만</option>
					</select>
				&nbsp;&nbsp;	
				</dd>
				<dt>조건 검색</dt>
				<dd>
					
					<select name="field" size="1" style="vertical-align:middle;">
							<option value="">검색기준</option>
							<option value="subject" <?=$field=="subject"?"selected":""?>>제목</option>
							<option value="content" <?=$field=="content"?"selected":""?>>내용</option>
							<option value="subtent" <?=$field=="subtent"?"selected":""?>>제목+내용</option>
							<option value="writer" <?=$field=="writer"?"selected":""?>>작성자</option>
					</select>
					
					<input type="text" name="keyword" id="keyword" style="width:200px;" value="<?=$keyword?>" >

					<input type="image" src="/manage/img/btn_search.gif" alt="검색" align="absmiddle"/>
				</dd>
			</dl>
			</form>
			<!-- //srch_bar -->
			<div class="clear"><?//=$query?></div>
			<br>
		<?// if($_AUTH_WRITE){ ?>
			<!-- button -->
			<table width="100%" align="center">
				<tr>
					<td align="right">
					<?if($bbs_code){?>	
						<a href="javascript:go_regist();" class="btn_blue2">등록하기</a>
					<?}else{?>
						<a href="javascript:alert('먼저 상단의 게시판 바로가기에서 등록할 게시판을 선택해 주세요.');" class="btn_blue2">등록하기</a>
					<?}?>
					</td>
				</tr>
			</table>
			<br>
		<?//}?>
			<!-- //button -->			
			<!-- Goods List -->
	<? } ?>

	<? if($cate_sect_cnt==0) { ?>
		<table class="t_list">
			<thead>
			<tr>
				<th height="40" align="center"><strong><?=$bbs_cate_name?> 카테고리로 생성된 게시판이 없습니다.</strong></th>
			</tr>
			</thead>
		</table>
	<? } else {  // 카테고리로 생성된 게시판이 있다면 시작  ?>
			
			<table class="t_list">
				<thead>
					<tr>
						<th width="5%">번호</th>
						<?if(!$bbs_code){?>
							<th width="10%">게시판 명</th>
							<th width="10%">분류</th>
							<th width="40%">글제목</th>
						<?}else{?>
							<th width="10%">분류</th>
							<th width="50%">글제목</th>
						<?}?>
						<th width="10%">글쓴이</th>
						<th width="5%">첨부파일</th>
						<th width="10%">등록일</th>
						<th width="5%">조회수</th>
						<th width="5%">추천수</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40"><strong>등록된 게시물이 없습니다.</strong></td>
					</tr>
				<? } ?>
		<?
		if($_include_board_is_notice == "Y"){ // 리스트 공지사항 보기가 가능한 게시판에 한하여 시작 

			$query_sub_notice="select idx,bbs_code,p_no,ref,step,depth,subject,writer,cnt,reco,write_time,is_del,del_sect,bbs_sect from board_content where 1=1 and bbs_sect='notice' and step='0' and bbs_code = '".$bbs_code."' ".$order_by;
			//echo $query_sub_notice;
			$result_sub_notice=mysqli_query($gconnet,$query_sub_notice);
			if(mysqli_num_rows($result_sub_notice)==0) {
			} else { //공지사항 지정글 시작

				for ($i_sub_notice=0; $i_sub_notice<mysqli_num_rows($result_sub_notice); $i_sub_notice++){ // 공지사항 지정글 루프
					$row_sub_notice = mysqli_fetch_array($result_sub_notice);
					$reg_time3 = to_time(substr($row_sub_notice[write_time],0,10));

					$sql_sub_notice_2 = "select board_title from board_config where 1=1 and board_code = '".$row_sub_notice['bbs_code']."' ";
					$query_sub_notice_2 = mysqli_query($gconnet,$sql_sub_notice_2);
					$row_sub_notice_2 = mysqli_fetch_array($query_sub_notice_2);

					$sql_sub_notice_file = "select file_org,file_chg from board_file where 1=1 and board_tbname='board_content' and board_code = '".$row_sub_notice['bbs_code']."' and board_idx='".$row_sub_notice['idx']."' order by idx asc limit 0,1";
					$query_sub_notice_file = mysqli_query($gconnet,$sql_sub_notice_file);
					$row_sub_notice_file = mysqli_fetch_array($query_sub_notice_file);
					
					$bbs_sect_notice = "";

					switch ($row_sub_notice[bbs_sect]) {
						
						case "info" : 
						$bbs_sect_notice = "정보";
						break;

						case "quest" : 
						$bbs_sect_notice = "질문";
						break;

						case "free" : 
						$bbs_sect_notice = "자유";
						break;

						case "comp" : 
						$bbs_sect_notice = "불만";
						break;

						case "add" : 
						$bbs_sect_notice = "홍보";
						break;

						case "notice" : 
						$bbs_sect_notice = "공지";
						break;

				   }
		?>
			<tr>
				<td >N</td>
				<?if(!$bbs_code){?>	
					<td><a href="javascript:go_view('<?=$row_sub_notice[idx]?>','<?=$row_sub_notice['bbs_code']?>');"><?=$row_sub_notice_2['board_title']?></a></td>
				<?}?>
				<td ><?=$bbs_sect_notice?></td>
				<td style="text-align:left;padding-left:10px;">
				<?
					if($row_sub_notice[depth]>0) {
						for ($k_sub_notice=0;($k_sub_notice<5&&$k_sub_notice<$row_sub_notice[depth]); $k_sub_notice++){
							echo "&nbsp;&nbsp;";
						}
						echo '<img class="notRe" src="/img/icon/re.gif" alt="답글" /> ';
					}
				?>
					<a href="javascript:go_view('<?=$row_sub_notice[idx]?>','<?=$row_sub_notice['bbs_code']?>');">
						<?if($row_sub_notice[is_del] == "Y"){?>
							<strike><?=string_cut2(stripslashes($row_sub_notice[subject]),40)?></strike>
							<?if($row_sub_notice[del_sect] == "AD"){?>
								<br><br> <font style="color:red;">이 글은 관리자에 의해 삭제되었습니다.</font>
							<?}elseif($row_sub_notice[del_sect] == "DD"){?>
								<br><br> <font style="color:red;">이 글은 작성자 본인에 의해 삭제되었습니다.</font>
							<?}?>
						<?}else{?>
							<?=string_cut2(stripslashes($row_sub_notice[subject]),40)?> <?=now_date($reg_time3)?>
						<?}?>
					</a>
				</td>
				<td ><?=$row_sub_notice[writer]?></td>
				<td>
				<?if($row_sub_notice_file['file_chg']){?>
					<a href="/pro_inc/download_file.php?nm=<?=$row_sub_notice_file['file_chg']?>&on=<?=$row_sub_notice_file['file_org']?>&dir=<?=$row_sub_notice['bbs_code']?>"><?//=$row_sub_notice_file['file_org']?><img src='/img/icon/ico_file.gif' border='0' align='absmiddle'></a>
				<?}?>
				</td>
				<td><?=substr($row_sub_notice[write_time],0,10)?></td>
				<td><?=$row_sub_notice[cnt]?></td>
				<td><?=$row_sub_notice[reco]?></td>	
			</tr>
	<?
				} // 공지사항 지정글 루프 종료 
			}	// 공지사항 지정글 종료

			
			$query_sub_add="select idx,bbs_code,p_no,ref,step,depth,subject,writer,cnt,reco,write_time,is_del,del_sect,bbs_sect from board_content where 1=1 and bbs_sect='add' and step='0' and bbs_code = '".$bbs_code."' ".$order_by;
			//echo $query_sub_add;
			$result_sub_add=mysqli_query($gconnet,$query_sub_add);
			if(mysqli_num_rows($result_sub_add)==0) {
			} else { //홍보 지정글 시작

				for ($i_sub_add=0; $i_sub_add<mysqli_num_rows($result_sub_add); $i_sub_add++){ // 홍보 지정글 루프
					$row_sub_add = mysqli_fetch_array($result_sub_add);
					$reg_time3 = to_time(substr($row_sub_add[write_time],0,10));

					$sql_sub_add_2 = "select board_title from board_config where 1=1 and board_code = '".$row_sub_add['bbs_code']."' ";
					$query_sub_add_2 = mysqli_query($gconnet,$sql_sub_add_2);
					$row_sub_add_2 = mysqli_fetch_array($query_sub_add_2);

					$sql_sub_add_file = "select file_org,file_chg from board_file where 1=1 and board_tbname='board_content' and board_code = '".$row_sub_add['bbs_code']."' and board_idx='".$row_sub_add['idx']."' order by idx asc limit 0,1";
					$query_sub_add_file = mysqli_query($gconnet,$sql_sub_add_file);
					$row_sub_add_file = mysqli_fetch_array($query_sub_add_file);
					
					$bbs_sect_add = "";

					switch ($row_sub_add[bbs_sect]) {
						
						case "info" : 
						$bbs_sect_add = "정보";
						break;

						case "quest" : 
						$bbs_sect_add = "질문";
						break;

						case "free" : 
						$bbs_sect_add = "자유";
						break;

						case "comp" : 
						$bbs_sect_add = "불만";
						break;

						case "add" : 
						$bbs_sect_add = "홍보";
						break;

						case "notice" : 
						$bbs_sect_add = "공지";
						break;

				   }
		?>
			<tr>
				<td ><b>N</b></td>
				<?if(!$bbs_code){?>	
					<td><a href="javascript:go_view('<?=$row_sub_add[idx]?>','<?=$row_sub_add['bbs_code']?>');"><?=$row_sub_add_2['board_title']?></a></td>
				<?}?>
				<td ><?=$bbs_sect_add?></td>
				<td style="text-align:left;padding-left:10px;">
				<?
					if($row_sub_add[depth]>0) {
						for ($k_sub_add=0;($k_sub_add<5&&$k_sub_add<$row_sub_add[depth]); $k_sub_add++){
							echo "&nbsp;&nbsp;";
						}
						echo '<img class="notRe" src="/img/icon/re.gif" alt="답글" /> ';
					}
				?>
					<a href="javascript:go_view('<?=$row_sub_add[idx]?>','<?=$row_sub_add['bbs_code']?>');">
						<?if($row_sub_add[is_del] == "Y"){?>
							<strike><?=string_cut2(stripslashes($row_sub_add[subject]),40)?></strike>
							<?if($row_sub_add[del_sect] == "AD"){?>
								<br><br> <font style="color:red;">이 글은 관리자에 의해 삭제되었습니다.</font>
							<?}elseif($row_sub_add[del_sect] == "DD"){?>
								<br><br> <font style="color:red;">이 글은 작성자 본인에 의해 삭제되었습니다.</font>
							<?}?>
						<?}else{?>
							<?=string_cut2(stripslashes($row_sub_add[subject]),40)?> <?=now_date($reg_time3)?>
						<?}?>
					</a>
				</td>
				<td ><?=$row_sub_add[writer]?></td>
				<td>
				<?if($row_sub_add_file['file_chg']){?>
					<a href="/pro_inc/download_file.php?nm=<?=$row_sub_add_file['file_chg']?>&on=<?=$row_sub_add_file['file_org']?>&dir=<?=$row_sub_add['bbs_code']?>"><?//=$row_sub_add_file['file_org']?><img src='/img/icon/ico_file.gif' border='0' align='absmiddle'></a>
				<?}?>
				</td>
				<td><?=substr($row_sub_add[write_time],0,10)?></td>
				<td><?=$row_sub_add[cnt]?></td>
				<td><?=$row_sub_add[reco]?></td>	
			</tr>
	<?
				} // 홍보 지정글 루프 종료 
			}	// 홍보 지정글 종료

		} // 리스트 공지사항 보기가 가능한 게시판에 한하여 종료
	?>	

			<?
			for ($i=0; $i<mysqli_num_rows($result); $i++){
				$row = mysqli_fetch_array($result);

				$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				$reg_time3 = to_time(substr($row[write_time],0,10));
				
				$sql_sub2 = "select board_title from board_config where 1=1 and board_code = '".$row['bbs_code']."' ";
				$query_sub2 = mysqli_query($gconnet,$sql_sub2);
				$row_sub2 = mysqli_fetch_array($query_sub2);

				$sql_file = "select file_org,file_chg from board_file where 1=1 and board_tbname='board_content' and board_code = '".$row['bbs_code']."' and board_idx='".$row['idx']."' order by idx asc limit 0,1";
				$query_file = mysqli_query($gconnet,$sql_file);
				$row_file = mysqli_fetch_array($query_file);
				
				$bbs_sect = "";

				switch ($row[bbs_sect]) {
						
						case "info" : 
						$bbs_sect = "정보";
						break;

						case "quest" : 
						$bbs_sect = "질문";
						break;

						case "free" : 
						$bbs_sect = "자유";
						break;

						case "comp" : 
						$bbs_sect = "불만";
						break;

						case "add" : 
						$bbs_sect = "홍보";
						break;

						case "notice" : 
						$bbs_sect = "공지";
						break;

				  } 

			?>
					<tr>
							<td><?=$listnum?></td>
							<?if(!$bbs_code){?>	
								<td><a href="javascript:go_view('<?=$row[idx]?>','<?=$row['bbs_code']?>');"><?=$row_sub2['board_title']?></a></td>
							<?}?>
							<td ><?=$bbs_sect?></td>
							<td style="text-align:left;padding-left:10px;">
								<?
								if($row[depth]>0) {
									for ($k=0;($k<5&&$k<$row[depth]); $k++){
										echo "&nbsp;&nbsp;";
									}
									echo '<img class="notRe" src="/img/icon/re.gif" alt="답글" /> ';
								}
								?>
								<a href="javascript:go_view('<?=$row[idx]?>','<?=$row['bbs_code']?>');">
										<?if($row[is_del] == "Y"){?>
											<strike><?=string_cut2(stripslashes($row[subject]),40)?></strike>
											<?if($row[del_sect] == "AD"){?>
												<br> <font style="color:red;">이 글은 관리자에 의해 삭제되었습니다.</font>
											<?}elseif($row[del_sect] == "DD"){?>
												<br> <font style="color:red;">이 글은 작성자 본인에 의해 삭제되었습니다.</font>
											<?}?>
										<?}else{?>
											<?=string_cut2(stripslashes($row[subject]),40)?> <?=now_date($reg_time3)?>
										<?}?>
								</a>
							</td>
							<td><a href="javascript:go_view('<?=$row[idx]?>','<?=$row['bbs_code']?>');"><?=$row[writer]?></a></td>
							<td>
							<?if($row_file['file_chg']){?>
								<a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=<?=$row['bbs_code']?>"><?//=$row_file['file_org']?><img src='/img/icon/ico_file.gif' border='0' align='absmiddle'></a>
							<?}?>
							</td>
							<td><?=substr($row[write_time],0,10)?></td>
							<td><?=$row[cnt]?></td>
							<td><?=$row[reco]?></td>	
						</tr>
					
			<?}?>	
			
			</tbody>
			</table>
	
	<? } // 카테고리로 생성된 게시판이 있다면 종료 ?>
	
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