<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"] . "/manage/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$bbs_code = trim(sqlfilter($_REQUEST['bbs_code'])); 
$bbs_sect = trim(sqlfilter($_REQUEST['bbs_sect'])); 
$lang = trim(sqlfilter($_REQUEST['lang']));

################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&bbs_code='.$bbs_code.'&bbs_sect='.$bbs_sect.'&lang='.$lang;

if(!$lang){
	$lang = 'kor';
}
if(!$pageNo){
	$pageNo = 1;
}

$where = " and is_del='N'";

if ($bbs_code){
	$where .= " and board_code = '".$bbs_code."'";
}
if ($bbs_sect){
	$where .= " and board_sect = '".$bbs_sect."'";
}
if ($lang){
	$where .= " and lang = '".$lang."'";
}

if ($field && $keyword){
	if($field == "subtent"){
		$where .= " and (a.subject like '%".$keyword."%' or a.content like '%".$keyword."%')";
	} else {
		$where .= " and ".$field." like '%".$keyword."%'";
	}
}

if ($bbs_code == "faq"){
	$bbs_code_str = "FAQ";
}

$query = "select * from board_category where 1 ".$where." ORDER BY priority ASC LIMIT 20 ";
$result = mysqli_query($gconnet, $query);
$total_count = mysqli_num_rows($result);
?>

<script type="text/javascript">
    function go_regist() {
        location.href = "board_category_write.php?<?=$total_param?>"
    }

    function go_update(idx, type) {
        priority_update_frm.target_idx.value = idx;
        priority_update_frm.method_type.value = type;
        priority_update_frm.submit();
    }

    function go_remove(idx) {
        remove_frm.target_idx.value = idx;
        remove_frm.submit();
    }
</script>

<body>
	<!--  우선순위 변경 액션폼  -->
	<form name="priority_update_frm" id="priority_update_frm" action="./global_priority_update_action.php" method="post">
		<input type="hidden" name="total_param" id="total_param" value="<?=$total_param?>"/>
		<input type="hidden" name="reurl" value="<?=$_SERVER['REQUEST_URI']?>">
		<input type="hidden" name="target_storage" value="board_category">
		<input type="hidden" id="target_idx" name="target_idx">
		<input type="hidden" id="method_type" name="method_type">
	</form>

	<!--  삭제 액션폼  -->
	<form name="remove_frm" id="remove_frm" action="./board_category_remove_action.php" method="post">
		<input type="hidden" name="total_param" id="total_param" value="<?=$total_param?>"/>
		<input type="hidden" id="target_idx" name="target_idx">
	</form>

	<div id="wrap" class="skin_type01">
		<? include $_SERVER["DOCUMENT_ROOT"] . "/manage/include/admin_top.php"; // 상단메뉴?>
		<div class="sub_wrap">
			<? include $_SERVER["DOCUMENT_ROOT"] . "/manage/include/customer_left.php"; // 좌측메뉴?>
			<!-- content 시작 -->
			<div class="container clearfix">
				<div class="content">
					<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
					<div class="navi">
						<ul class="clearfix">
							<li>HOME</li>
							<li>고객센터</li>
							<li><?=$bbs_code_str?> 카테고리 관리</li>
						</ul>
					</div>
					<div class="list_tit">
						<h3><?=$bbs_code_str?> 카테고리 리스트<?=$lang === 'kor' ? '' : ' (영문)'?></h3>
						<button class="btn_add" onclick="go_regist();" style="width:150px;">
							<span>등록</span></button>
					</div>
					<div class="list">
						<!-- 2023-05-02 jwc management_category 클래스 추가 -->
						<div class="search_wrap management_category">
							<table class="search_list">
								<caption>검색결과</caption>
								<colgroup>
									<col style="width: 5%">
									<col style="width: 40%">
									<col style="width: 25%">
									<col style="width: 15%">
									<col style="width: 15%">
								</colgroup>
								<thead>
								<tr>
									<th scope="col">노출 순위</th>
									<th scope="col">카테고리명</th>
									<th scope="col">등록된 글 수</th>
									<th scope="col">최신글 등록일</th>
									<th scope="col">순서</th>
								</tr>

								</thead>
								<tbody>

								<? if($total_count === 0){ ?>
									<tr>
										<td colspan="10" height="40">등록된 데이타가 없습니다.</strong></td>
									</tr>
								<? } ?>
								<?
								for($i = 0; $i < $total_count; $i++){
									$row = mysqli_fetch_array($result);
									$idx = $row['idx'];
									?>
									<tr>
										<td><?=$i + 1?></td>
										<td><a href="javascript:go_modify('<?=$idx?>')"><?=$row['subject']?></td>
										<td><?=mysqli_num_rows(mysqli_query($gconnet, "SELECT idx FROM partner_board_list WHERE cidx='$idx'"))?></td>
										<td><?=$row['ndate']?></td>
										<td>
											<?
											if($total_count > 1){
												if($i === 0){ ?>
													<a href="javascript:;"
													   onclick="go_update('<?=$idx?>', 'down')">▼</a>
													&nbsp;&nbsp;
													<a href="javascript:;" onclick="go_remove('<?=$idx?>')">삭제</a>
												<? }elseif($i === $total_count - 1){ ?>
													<a href="javascript:;" onclick="go_update('<?=$idx?>', 'up')">▲</a>
													&nbsp;&nbsp;
													<a href="javascript:;" onclick="go_remove('<?=$idx?>')">삭제</a>
												<? }else{ ?>
													<a href="javascript:;"
													   onclick="go_update('<?=$idx?>', 'down')">▼</a>
													<a href="javascript:;" onclick="go_update('<?=$idx?>', 'up')">▲</a>
													&nbsp;&nbsp;
													<a href="javascript:;" onclick="go_remove('<?=$idx?>')">삭제</a>
												<? }
											}else{
												?>
												<a href="javascript:;" onclick="go_remove('<?=$idx?>')">삭제</a>
											<? } ?>
										</td>
									</tr>
								<? } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- content 종료 -->
		</div>
	</div>
	<? include $_SERVER["DOCUMENT_ROOT"] . "/pro_inc/include_bottom_admin_tail.php"; ?>

	<script>
		function go_modify(idx){
            window.open("board_category_modify_popup.php?target_idx="+idx,"board_category", "top=100,left=100,scrollbars=yes,resizable=no,width=700,height=300");
		}
	</script>
</body>
</html>