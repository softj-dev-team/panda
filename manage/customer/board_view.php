<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_board_config.php"; // 게시판 설정파일 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
if(!$_AUTH_VIEW){
	error_back("본문보기 권한이 없습니다.");
	exit;
}

$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$bbs_code = sqlfilter($_REQUEST['bbs_code']);
$s_cate_code = trim(sqlfilter($_REQUEST['s_cate_code'])); // 게시판 카테고리 코드
$v_sect = trim(sqlfilter($_REQUEST['v_sect'])); // 게시판 분류
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&v_sect='.$v_sect.'&s_cate_code='.$s_cate_code.'&pageNo='.$pageNo;

if($bbs_code == "reviews"){
	$sql = "select a.*,b.user_id,b.user_name,b.file_chg from board_content a inner join member_info b on a.product_idx=b.idx where 1=1 and a.idx = '".$idx."' and a.bbs_code='".$bbs_code."'";
} else {
	$sql = "SELECT * FROM board_content a where 1=1 and a.idx = '".$idx."' and a.bbs_code='".$bbs_code."'";
}

$query = mysqli_query($gconnet,$sql);

//echo $sql; exit;

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('해당하는 게시물이 없습니다.');
	location.href =  "board_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

################## 조회수 관리 시작 ############################

if($_SESSION['manage_coinc_idx'] == $row['member_idx']){ 
} else {  // 작성자 본인이 열람하는것이 아닐때 시작
		
	$sql_prev = "select idx from board_view_cnt where 1=1 and board_tbname='board_content' and board_code = '".$row['bbs_code']."' and board_idx='".$row['idx']."' and member_idx = '".$_SESSION['manage_coinc_idx']."' ";
	$query_prev = mysqli_query($gconnet,$sql_prev);
	$cnt_prev = mysqli_num_rows($query_prev);

	if($cnt_prev == 0){ // 현 게시물을 처음 볼때 한해서 조회수를 증가시킨다 시작 
			
			$query_view_cnt = " insert into board_view_cnt set "; 
			$query_view_cnt .= " board_tbname = 'board_content', ";
			$query_view_cnt .= " board_code = '".$row['bbs_code']."', ";
			$query_view_cnt .= " board_idx = '".$row['idx']."', ";
			$query_view_cnt .= " member_idx = '".$_SESSION['manage_coinc_idx']."', ";
			$query_view_cnt .= " cnt = '1', ";
			$query_view_cnt .= " wdate = now() ";
			$result_view_cnt = mysqli_query($gconnet,$query_view_cnt);

			$sql_cnt = "update board_content set cnt=cnt+1 where 1=1 and idx = '".$row['idx']."'";
			$query_cnt = mysqli_query($gconnet,$sql_cnt);
	
	} // 현 게시물을 처음 볼때 한해서 조회수를 증가시킨다 종료 

}  // 작성자 본인이 열람하는것이 아닐때 종료

$current_cnt_query = "select sum(cnt) as current_cnt from board_view_cnt where 1=1 and board_tbname='board_content' and board_code = '".$row['bbs_code']."' and board_idx='".$row['idx']."' ";
$current_cnt_result = mysqli_query($gconnet,$current_cnt_query);
$current_cnt_row = mysqli_fetch_array($current_cnt_result);
if ($current_cnt_row['current_cnt']){
	$current_cnt = $current_cnt_row['current_cnt'];
} else{
	$current_cnt = 1;
} 

################## 조회수 관리 종료 ############################


if($s_cate_code) {
	$sql_sub1 = "select cate_name1 from board_cate where cate_code1='".$s_cate_code."' and cate_level='1' ";
	$query_sub1 = mysqli_query($gconnet,$sql_sub1);
	$row_sub1 = mysqli_fetch_array($query_sub1);
	$bbs_cate_name = $row_sub1['cate_name1'];
}

if($bbs_code){
	$bbs_str = $_include_board_board_title;
} elseif($s_cate_code) {
	$bbs_str = $bbs_cate_name." 카테고리에 해당하는 ";
}

			switch ($row[bbs_sect]) {
						case "t1" : 
						$bbs_sect = "의뢰자가 자주묻는 질문";
						break;
						case "t2" : 
						$bbs_sect = "디자이너가 자주묻는 질문";
						break;
						case "t3" : 
						$bbs_sect = "진행중";
						break;
						case "t4" : 
						$bbs_sect = "심사중";
						break;
						case "t5" : 
						$bbs_sect = "완료";
						break;
						case "t6" : 
						$bbs_sect = "일반";
						break;	
				} 

################### 댓글 서브페이징 시작 ####################

$pageNo_sub = trim(sqlfilter($_REQUEST['pageNo_sub']));
$total_param_sub = $total_param.'&idx='.$idx;
	
################### 댓글 서브페이징 종료 ####################
?>
<!-- content -->
<script type="text/javascript">
<!--
function go_view(no){
		location.href = "board_view.php?idx="+no+"&<?=$total_param?>";
}
	
function go_modify(no){
		location.href = "board_modify.php?idx="+no+"&<?=$total_param?>";
}

function go_reply(no){
		location.href = "board_reply.php?idx="+no+"&<?=$total_param?>";
}

function go_delete(no){
	if(confirm('해당 게시물을 숨기시겠습니까?')){
	//	if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "board_delete_action.php?idx="+no+"&<?=$total_param?>";
	//	}
	}
}

function go_delete_can(no){
	if(confirm('숨겨진 글을 원상복구 하시겠습니까?')){
	//	if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "board_delete_cancel_action.php?idx="+no+"&<?=$total_param?>";
	//	}
	}
}

function go_delete_complete(no){
	if(confirm('완전히 삭제하신 데이터는 이후 복구가 불가능 합니다. 그래도 삭제 하시겠습니까?')){	
		if(confirm('정말 완전히 삭제 하시겠습니까?')){
			_fra_admin.location.href = "board_delete_complete_action.php?idx="+no+"&<?=$total_param?>";
		}
	}
}

function go_list(){
	location.href = "board_list.php?<?=$total_param?>";
}

function go_submit() {
	var check = chkFrm('frm');
	if(check) {
		frm.submit();
	} else {
		false;
	}
}

function go_delete_com(no){
	if(confirm('해당 댓글을 숨기시겠습니까?')){
		_fra_admin.location.href = "delete_action_com.php?board_idx=<?=$row[idx]?>&idx="+no+"&<?=$total_param?>&pageNo=<?=$pageNo?>";
	}
}

function go_delete_can_com(no){
	if(confirm('숨겨진 댓글을 원상복구 하시겠습니까?')){
		_fra_admin.location.href = "delete_cancel_action_com.php?board_idx=<?=$row[idx]?>&idx="+no+"&<?=$total_param?>&pageNo=<?=$pageNo?>";
	}
}

function comment_delete(no){
	if(confirm('해당 댓글을 삭제하시겠습니까?')){
		_fra_admin.location.href = "comment_delete_action.php?idx=<?=$row[idx]?>&comment_idx="+no+"&<?=$total_param?>";
	}
}

function go_reco(){
	if(confirm('정말 추천 하시겠습니까?')){
	//	if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "board_view_reco_action.php?idx=<?=$row['idx']?>&bbs_code=<?=$row['bbs_code']?>&<?=$total_param?>";
	//	}
	}
}

function pay_back_submit() {
	var check = chkFrm('pay_back_frm');
	if(check) {
		pay_back_frm.submit();
	} else {
		false;
	}
}

function comment_reply(ktmp) {
		
	var cur_comment_1 = "comment_in_"+ktmp+"_1";
	var cur_comment_2 = "comment_in_"+ktmp+"_2";
	var cur_comment_3 = "comment_in_"+ktmp+"_3";
		
	var next_viewObj1 = document.getElementById(cur_comment_1);
	next_viewObj1.style.display = 'block';

	var next_viewObj2 = document.getElementById(cur_comment_2);
	next_viewObj2.style.display = 'block';

	var next_viewObj3 = document.getElementById(cur_comment_3);
	next_viewObj3.style.display = 'block';

}

function go_comment_reply(frm_name) {
	var check = chkFrm(frm_name);
	if(check) {
		document.forms[frm_name].submit();
	} else {
		return;
	}
}

function go_comment_reco(no){
	if(confirm('추천 하시겠습니까?')){
	//	if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
			_fra_admin.location.href = "comment_view_reco_action.php?board_idx=<?=$row['idx']?>&comment_idx="+no+"&bbs_code=<?=$row['bbs_code']?>&<?=$total_param?>";
	//	}
	}
}

function go_reply_modify(no){
	location.href = "board_modify.php?idx="+no+"&orgin_idx=<?=$row[idx]?>&<?=$total_param?>";
}

function go_reply_delete(no){
	if(confirm('답변을 삭제하시면 영구 삭제가 됩니다. 그래도 삭제 하시겠습니까?')){
		if(confirm('삭제된 답변은 복구되지 않습니다. 정말로 삭제 하시겠습니까?')){
			_fra_admin.location.href = "board_delete_complete_action.php?idx="+no+"&orgin_idx=<?=$row[idx]?>&<?=$total_param?>";
		}
	}
}
//-->		
</script>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/customer_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li><li>고객센터</li>
						<li><?=$bbs_str?></li>
					</ul>
				</div>
				<div class="list_tit">
					<h3><?=$bbs_str?> 상세보기</h3>
				</div>
				<div class="write">
			<table class="t_view">
				<colgroup>
					<col width="10%" />
					<col width="40%" />
					<col width="10%" />
					<col width="40%" />
				</colgroup>
				<?if($bbs_code == "faq"){?>
					<tr>
						<th>분류</th>
						<td colspan="3">
							<?=$row['bbs_sect']?>
						</td>
					</tr>
				<?}?>
					<tr>
						<th ><?if($bbs_code == "data_list"){?>제목<?}else{?>제 목<?}?></th>
						<td colspan="3">
							<?if($row[is_del] == "Y"){?>
								<strike><?=$row[subject]?></strike>
									<?if($row[del_sect] == "AD"){?>
										<br> <font style="color:red;">이 글은 관리자에 의해 삭제되었습니다.</font>
									<?}elseif($row[del_sect] == "DD"){?>
										<br> <font style="color:red;">이 글은 작성자 본인에 의해 삭제되었습니다.</font>
									<?}?>
							<?}else{?>
								<?=$row[subject]?> 
							<?}?>
						</td>
					</tr>
			<?if($_include_board_write_auth != "AD"){?>
				<tr>
						<th >작성자</th>
						<td colspan="3"><?=$row[writer]?></td>
					</tr>
			<? } ?>
					<tr>
						<!--<th >분류</th>
						<td ><?=$bbs_sect?></td>-->
						<th>조회수</th>
						<td><?//=$current_cnt?><?=$row[cnt]?></td>
						<th>등록일</th>
						<td><?=$row[write_time]?></td>
					</tr>
			
					<?
						$sql_file = "select file_org,file_chg from board_file where 1=1 and board_tbname='board_content' and board_code = '".$row['bbs_code']."' and board_idx='".$row['idx']."' order by idx asc ";
						$query_file = mysqli_query($gconnet,$sql_file);
						
						for($i_file=0; $i_file<mysqli_num_rows($query_file); $i_file++){
							$row_file = mysqli_fetch_array($query_file);
							$k_file = $i_file+1;
					?>

						<tr>
							<th >첨부파일 <?=$k_file?></th>
							<td colspan="3">
								<a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=<?=$row['bbs_code']?>"><?=$row_file['file_org']?></a>
							</td>
						</tr>
					<?}?>
					<tr>
						<th ><?if($bbs_code == "data_list"){?>내 용<?}else{?>내 용<?}?></th>
						<td colspan="3">
						<?if($row['is_html'] == "Y"){?>
							<?=stripslashes($row[content])?>
						<?}else{?>
							<?=nl2br(stripslashes($row[content]))?>
						<?}?>
						</td>
					</tr>
			</table>
		<?if($bbs_code == "qna" && $row['depth'] == 0){
					$sql_answer = "select idx,subject,content,write_time,writer from board_content where p_no = '".$row[idx]."' ";
					//echo $sql_answer;
					$query_answer = mysqli_query($gconnet,$sql_answer);
					$answer_yn = mysqli_num_rows($query_answer);
					$answer_row = mysqli_fetch_array($query_answer);
				?>
				<p class="tit">관리자 답변</p>
				<table class="t_view">
				<colgroup>
					<col width="10%" />
					<col width="40%" />
					<col width="10%" />
					<col width="40%" />
				</colgroup>
					<tr>
						<th>작성자</th>
						<td colspan="3">
							<?=$answer_row[writer]?>
						</td>
					</tr>
					<tr>
						<th >답변내용</th>
						<td colspan="3">
							<?=nl2br($answer_row[content])?>
						</td>
					</tr>
					<tr>
						<th >응답일시</th>
						<td colspan="3">
							<?=$answer_row[write_time]?>
						</td>
					</tr>
				</table>
				<div style="text-align:right;margin-top:-20px;margin-bottom:10px;padding-right:10px;">
				<?if($answer_yn > 0){?>
					<a href="javascript:go_reply_modify('<?=$answer_row[idx]?>');" class="btn_gray">답변수정</a>
					<a href="javascript:go_reply_delete('<?=$answer_row[idx]?>');" class="btn_red">답변삭제</a>	
				<?}else{?>
					<a href="javascript:go_reply('<?=$row[idx]?>');" class="btn_blue">답변달기</a>
				<?}?>
				</div>
			<?}?>
			
			<table class="t_view">
				<colgroup>
					<col width="10%" />
					<col width="90%" />
				</colgroup>
			<tr>
				<th >이전글</th>
				<td >
		<?
			$up_query = "select idx,subject,is_del,del_sect from board_content where bbs_code='".$bbs_code."' and idx > ".$idx." and step='0' order by idx asc limit 0,1";
			if($my_list){ // 1:1 게시판일때
				if($view_idx){
					$up_query .= " and view_idx = '".$view_idx."' ";
				}
			}

			//echo $query;
			$up_result = mysqli_query($gconnet,$up_query);
			$up_row = mysqli_fetch_array($up_result);

				if(mysqli_num_rows($up_result) == 0){
		?>
				이전글이 없습니다.
		<? } else { ?>
			<a href="board_view.php?idx=<?=$up_row['idx']?>&<?=$total_param?>">
				<?if($up_row[is_del] == "Y"){?>
					<strike><?=string_cut2(stripslashes($up_row[subject]),40)?></strike>
					<?if($up_row[del_sect] == "AD"){?>
							<br> <font style="color:red;">이 글은 관리자에 의해 삭제되었습니다.</font>
					<?}elseif($up_row[del_sect] == "DD"){?>
							<br> <font style="color:red;">이 글은 작성자 본인에 의해 삭제되었습니다.</font>
					<?}?>
				<?}else{?>
					<?=string_cut2(stripslashes($up_row[subject]),40)?> 
				<?}?>
			</a>
		<? } ?>
				</td>
			</tr>
			<tr>
				<th >다음글</th>
				<td >
		<?
			$down_query = "select idx,subject,is_del,del_sect from board_content where bbs_code='".$bbs_code."' and idx < ".$idx." and step='0' order by idx desc limit 0,1";
			if($my_list){ // 1:1 게시판일때
				if($view_idx){
					$down_query .= " and view_idx = '".$view_idx."' ";
				}
			}

			//echo $query;
			$down_result = mysqli_query($gconnet,$down_query);
			$down_row = mysqli_fetch_array($down_result);

				if(mysqli_num_rows($down_result) == 0){
		?>
				다음글이 없습니다.
		<? } else { ?>
			<a href="board_view.php?idx=<?=$down_row['idx']?>&<?=$total_param?>">
				<?if($down_row[is_del] == "Y"){?>
					<strike><?=string_cut2(stripslashes($down_row[subject]),40)?></strike>
					<?if($down_row[del_sect] == "AD"){?>
							<br> <font style="color:red;">이 글은 관리자에 의해 삭제되었습니다.</font>
					<?}elseif($down_row[del_sect] == "DD"){?>
							<br> <font style="color:red;">이 글은 작성자 본인에 의해 삭제되었습니다.</font>
					<?}?>
				<?}else{?>
					<?=string_cut2(stripslashes($down_row[subject]),40)?> 
				<?}?>
			</a>
		<? } ?>		
				</td>
			</tr>
			</table>
			
			<table width="100%" align="center">
				<tr>
					<td align="left" style="padding-left:20px;">
						<!-- 목록 -->
						<a href="javascript:go_list();" class="btn_gray">목록보기</a>
					<? if($_AUTH_WRITE){ // 본문작성 권한 있을때 ?>	
						<!-- 수정 -->
						<a href="javascript:go_modify('<?=$row[idx]?>');" class="btn_blue">수정하기</a>
					<?}?>
				<? if($bbs_code == "qna" || $bbs_code == "private"  || $bbs_code == "protalk"){ ?>	
					<? if($_AUTH_REPLY){ // 덧글작성 권한 있을때 ?>	
						<!-- 답글 
						<a href="javascript:go_reply('<?=$row[idx]?>');" class="btn_blue">답글달기</a>-->
					<?}?>
				<?}?>
					</td>
					<td align="right" style="padding-right:20px;">
					<? if($_AUTH_WRITE){ // 본문작성 권한 있을때 ?>	
						<!-- 완전삭제 -->
						<a href="javascript:go_delete_complete('<?=$row[idx]?>');" class="btn_red">삭제하기</a>	
					<?}?>
					</td>
				</tr>
			</table>

	<?if($_include_board_is_comment == "Y"){ // 한줄댓글 가능한 게시판일때 시작 ?>		
			<br><br>
			<table class="t_view">
				<colgroup>
					<col width="10%" />
					<col width="90%" />
				</colgroup>
			
		<?
			if(!$pageNo_sub){
				$pageNo_sub = 1;
			}

			$where_sub .= " and board_tbname = 'board_content' and board_code = '".$row['bbs_code']."' and board_idx='".$row['idx']."' ";

			$pageScale_sub = 50; // 페이지당 50 개씩 
			$start_sub = ($pageNo_sub-1)*$pageScale_sub;

			$StarRowNum_sub = (($pageNo_sub-1) * $pageScale_sub);
			$EndRowNum_sub = $pageScale_sub;

			/*$query_sub =	" SELECT * ";
			$query_sub = $query_sub." FROM ( ";
			$query_sub = $query_sub." SELECT	ROW_NUMBER() OVER(ORDER BY b.idx DESC) AS rowNumber ";
			$query_sub = $query_sub." ,a.idx,a.pro_cate1,a.pro_cate2,a.pro_cate3,a.file_c,a.pro_name,a.pro_code,a.pro_nonprice,a.pro_memprice,a.pro_align,a.pro_cnt,a.use_ok,b.wdate ";
			$query_sub = $query_sub." FROM _SHOP_product_info a INNER JOIN _SHOP_product_product b ON a.idx = b.pro_yun_idx ";
			$query_sub = $query_sub." WHERE 1=1  ".$where_sub;
			$query_sub = $query_sub."	) AS S ";
			$query_sub = $query_sub." WHERE S.rowNumber BETWEEN ".$StarRowNum_sub." AND ".$EndRowNum_sub." ";*/
			
			$order_by_sub = " ORDER BY ref asc, step asc, depth desc ";

			$query_sub = "select * from board_comment where 1=1 ".$where_sub.$order_by_sub." limit ".$StarRowNum_sub." , ".$EndRowNum_sub;

			//echo "<br><br>쿼리 = ".$query_sub."<br><Br>";

			$result_sub = mysqli_query($gconnet,$query_sub);

			$query_sub_cnt = "select idx from board_comment where 1=1 ".$where_sub;
			$result_sub_cnt = mysqli_query($gconnet,$query_sub_cnt);
			$num_sub = mysqli_num_rows($result_sub_cnt);

			//echo $num_sub; //exit;

			$iTotalSubCnt_sub = $num_sub;
			$totalpage_sub	= ($iTotalSubCnt_sub - 1)/$pageScale_sub  + 1;

			//echo mysqli_num_rows($result_sub);
			
			?>

			<? if($num_sub == 0){ ?>
				<tr><td style="text-align:center;" colspan="4" height='30'>등록된 한줄댓글이 없습니다.</td></tr>
			<? } ?>

			<?
				for ($i_sub=0; $i_sub<mysqli_num_rows($result_sub); $i_sub++){ // 댓글 반복 시작
					$row_sub = mysqli_fetch_array($result_sub);

					$listnum_sub	= $iTotalSubCnt_sub - (( $pageNo_sub - 1 ) * $pageScale_sub ) - $i_sub;
					$cancomdelete = true;
			?>		
			<!-- 댓글 내용 시작 -->
			<tr>
				<th ><b><?=$row_sub[writer]?></b></th>
				<td >
				<?
					if($row_sub[depth]>0) {
						for ($k=0;($k<5&&$k<$row_sub[depth]); $k++){
							echo "&nbsp;&nbsp;";
						}
						echo '<img class="notRe" src="/image/icon/re.gif" alt="답글" /> ';
				    }
				  ?>
				<?if($row_sub[is_del] == "Y"){?>
					<strike><?=nl2br(stripslashes($row_sub[content]))?></strike>
					<?if($row_sub[del_sect] == "AD"){?>
						<br> <font style="color:red;">이 글은 관리자에 의해 삭제되었습니다.</font>
					<?}elseif($row_sub[del_sect] == "DD"){?>
						<br> <font style="color:red;">이 글은 작성자 본인에 의해 삭제되었습니다.</font>
					<?}?>
				<?}else{?>
					<?=nl2br(stripslashes($row_sub[content]))?>
				<? } ?>		
					&nbsp;&nbsp; 
						<!-- 추천수 
						<b>[추천 : <?=$row_sub[reco]?>]</b>
						<?if($row_sub[is_del] != "Y"){?>
							<!-- 댓글달기 
							&nbsp;<a href="javascript:comment_reply(<?=$row_sub[idx]?>);"><font style="color:blue;">[댓글달기]</font></a>
							<!--  추천하기 
							&nbsp;<a href="javascript:go_comment_reco(<?=$row_sub[idx]?>);"><font style="color:green;">[추천하기]</font></a> -->
						<?}?>
						&nbsp;&nbsp;<?=$row_sub[write_time]?>&nbsp;
						<!-- 삭제 -->
						<!--<?if($row_sub[is_del] != "Y"){?>
							<a href="javascript:go_delete_com('<?=$row_sub[idx]?>');" class="btn_blue2">숨기기</a>
						<?} elseif($row_sub[is_del] == "Y"){?>
							<a href="javascript:go_delete_can_com('<?=$row_sub[idx]?>');" class="btn_blue2">숨김해제</a>
						<?}?>-->
						&nbsp;<a href="javascript:comment_delete(<?=$row_sub[idx]?>);" class="btn_red">댓글삭제</a>	
					 <?if($row_sub[file_chg]){?>
						<br><br><img src="<?=$_P_DIR_WEB_FILE.$row['bbs_code']?>/img_thumb/<?=$row_sub[file_chg]?>" border="0">	
					 <?}?>
				</td>
			</tr>
			<!-- 댓글 내용 종료 -->

			<!-- 댓글에 대한 덧글 시작 -->
			
			<form name="comment_reply_frm_<?=$row_sub[idx]?>" action="comment_reply_action.php" target="_fra_admin" method="post" enctype="multipart/form-data">
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<input type="hidden" name="board_code" value="<?=$row['bbs_code']?>">
					<input type="hidden" name="board_idx" value="<?=$row['idx']?>">
					<input type="hidden" name="member_idx" value="0">
					<input type="hidden" name="p_no" value="<?=$row_sub['idx']?>">
					<input type="hidden" name="passwd" value="<?=$_SESSION['manage_coinc_password']?>">

					<tr id="comment_in_<?=$row_sub[idx]?>_1" style="display:none;padding-left:30px;">
						<th > 작성자</th>
						<td ><input type="text" name="writer" id="writer" style="width:200px;" value="<?=$_SESSION['manage_coinc_name']?>" required="yes" message="한줄댓글 작성자"></td>
					</tr>
					<tr id="comment_in_<?=$row_sub[idx]?>_2" style="display:none;padding-left:30px;">
						<th > 내용</th>
						<td ><textarea name="content" id="content" style="width:650px;height:100px;" required="yes" message="한줄댓글 내용"></textarea> 
					</td>
					</tr>
					<tr id="comment_in_<?=$row_sub[idx]?>_3" style="display:none;padding-left:30px;">
						<th > 이미지 첨부</th>
						<td ><input id="file1" name="file1" type="file" style="width:300px;" /> &nbsp;&nbsp; <a href="javascript:go_comment_reply('comment_reply_frm_<?=$row_sub[idx]?>');" class="btn_blue">댓글 등록</a> </td>
					</tr>

			</form>

			<!-- 댓글에 대한 덧글 종료 -->
		<?
			} // 댓글 반복 종료
		?>

		</table>
		<!-- paginate -->
			<div class="pagination mt0">
				<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging_sub.php";?>
			</div>
		<!-- //paginate -->
		
	<? if($_AUTH_REPLY){ // 댓글작성 권한 있을때 ?>
		<br><br>
		<table class="t_view">
			<colgroup>
				<col width="10%" />
				<col width="90%" />
		</colgroup>
		
		<form name="frm" action="comment_write_action.php" target="_fra_admin" method="post" enctype="multipart/form-data">
		<input type="hidden" name="total_param" value="<?=$total_param?>"/>
		<input type="hidden" name="board_code" value="<?=$row['bbs_code']?>">
		<input type="hidden" name="board_idx" value="<?=$row[idx]?>">
		<input type="hidden" name="member_idx" value="0">
		<input type="hidden" name="passwd" value="<?=$_SESSION['manage_coinc_password']?>">
		
			<tr>
				<th >한줄댓글 작성자</th>
				<td ><input type="text" name="writer" id="writer" style="width:200px;" value="<?=$_SESSION['manage_coinc_name']?>" required="yes" message="한줄댓글 작성자"></td>
			</tr>
			<tr>
				<th >한줄댓글 내용</th>
				<td ><textarea name="content" id="content" style="width:650px;height:100px;" required="yes" message="한줄댓글 내용"></textarea>
				</td>
			</tr>
			<tr>
				<th >이미지 첨부</th>
				<td ><input id="file1" name="file1" type="file" style="width:300px;" /> &nbsp;&nbsp; <a href="javascript:go_submit();" class="btn_blue">댓글 등록</a></td>
			</tr>
		
		</form>
	<?} ?>
			</table>

<? }  // 한줄댓글 가능한 게시판일때 종료 ?>
									
		</div>
	</div>
</section>
<!-- //content -->

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>