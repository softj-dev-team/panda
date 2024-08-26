<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
	$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
	$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
	
	$target_param = "member_idx=".$member_idx;

	$where = " and is_del='N' and member_idx='".$member_idx."'";

	if(!$pageNo){
		$pageNo = 1;
	}

	$s_cnt = 5; // 기본목록 5개

	$pageScale = $s_cnt;  
	$start = ($pageNo-1)*$pageScale;

	$StarRowNum = (($pageNo-1) * $pageScale);
	$EndRowNum = $pageScale;

	$order_by = " order by idx desc";

	$query = "select *,(select user_id from member_info where 1 and idx=member_panalty_info.admin_idx) as admin_id from member_panalty_info where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;
	$result = mysqli_query($gconnet,$query);

	$query_cnt = "select idx from member_panalty_info where 1 ".$where;
	$result_cnt = mysqli_query($gconnet,$query_cnt);
	$num = mysqli_num_rows($result_cnt);

	$iTotalSubCnt = $num;
	$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>

	<div class="list">
		<div class="search_wrap">
			<table class="search_list">
				<caption>검색결과</caption>
				<colgroup>
					<col style="width:10%;">
					<col style="width:15%;">
					<col style="width:35%">
					<col style="width:25%;">
					<col style="width:15%;">
				</colgroup>
				<thead>
					<tr>
						<th scope="col">No</th>
						<th scope="col">패널티 일자</th>
						<th scope="col">사유</th>
						<th scope="col">적용일</th>
						<th scope="col">관리자</th>
					</tr>
				</thead>
				<tbody>
				<? if($num==0) { ?>
					<tr>
						<td colspan="10" height="40">등록된 패널티 내역이 없습니다.</strong></td>
					</tr>
				<? } ?>
				<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
				?>
					<tr>
						<td><?=$listnum?></td>
						<td><?=$arr_panalty_type[$row['panalty_type']]?></td>
						<td style="text-align:left;padding-top:10px;padding-right:5px;padding-left:5px;padding-bottom:10px;"><?=nl2br($row['panalty_memo'])?></td>
						<td><?=$row['wdate']?></td>
						<td><?=$row['admin_id']?></td>
					</tr>
				<?}?>
			</table>

			<div class="pagination mt0">
			<?
				$target_link = "inner_member_view_panalty_history.php";
				$target_id = "area_panalty_history";
				include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging_ajax.php";	
			?>
			</div>

		</div>
	</div>
