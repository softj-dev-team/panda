<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
$member_idx = trim(sqlfilter($_REQUEST['member_idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 비슷회원
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 게시 시작일
$s_level = sqlfilter($_REQUEST['s_level']); // 게시 종료일
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); 
################## 파라미터 조합 #####################
$total_param = 'field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order.'&member_idx='.$member_idx;

$where = " and temple_idx in (select idx from temple_info where 1 and is_del = 'N') and member_shop_idx='".$member_idx."'";

if(!$pageNo){
	$pageNo = 1;
}

if(!$s_order){
	$s_order = 1;
}

if($s_sect1){
	//$where .= " and product_cate_code1='".$s_sect1."'";
}
if($s_sect2){
	//$where .= " and idx in (select product_info_idx from product_info_add where 1 and tag_value = '".$s_gubun."' and cate_type='cate' and cate_level='2')";
}
if($v_sect){
	$where .= " and apply_ok='".$v_sect."'";
}

if ($field && $keyword){
	$where .= " and ".$field." like '%".$keyword."%'";
}

$query_cnt = "select idx from member_temple_add where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$pageScale = 20; // 페이지당 20 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by idx desc"; 

$query = "select *,(select temple_title from temple_info where 1 and idx=member_temple_add.temple_idx) as temple_title,(select user_name from member_info where 1 and idx=(select member_idx from temple_info where 1 and idx=member_temple_add.temple_idx)) as user_name,(select file_chg from board_file where 1 and board_tbname='temple_info' and board_code='photo' and board_idx=member_temple_add.temple_idx order by idx asc limit 0,1) as file_chg from member_temple_add where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
					<div style="text-align:right;padding-right:10px;padding-top:10px;"><a href="javascript:main_product_pop();" class="btn_green">관리사찰 신청</a></div>

					<table class="search_list" style="margin-top:10px;">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:10%;">
								<col style="width:20%;">
								<col style="width:10%;">
								<col style="width:15%;">
								<col style="width:10%;">
								<col style="width:15%;">
								<col style="width:15%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">번호</th>
									<th scope="col">이미지</th>
									<th scope="col">사찰명</th>
									<th scope="col">사찰회원</th>
									<th scope="col">신청일시</th>
									<th scope="col">신청상태</th>
									<th scope="col">승인(거부)일시</th>
									<th scope="col">상태설정</th>
								</tr>
							</thead>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);
								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

								if($row['apply_ok'] == "I"){
									$apply_ok = "승인대기";
								} elseif($row['apply_ok'] == "Y"){
									$apply_ok = "승인";
								} elseif($row['apply_ok'] == "N"){
									$apply_ok = "미승인";
								}
						?>
						<form name="frm_cate1_<?=$i?>" method="post" action="member_temple_request_modaction.php"  target="_fra_admin" enctype="multipart/form-data">
							<input type="hidden" name="idx" value="<?=$row[idx]?>"/>
							<tr>
								<td><?=$listnum?></td>
								<td><a href="../temple/temple_view.php?idx=<?=$row['temple_idx']?>" target="_blank">
									<img src="<?=$_P_DIR_WEB_FILE?>temple_info/img_thumb/<?=$row['file_chg']?>" style="max-width:90%;">
								</a></td>
								<td><a href="../temple/temple_view.php?idx=<?=$row['temple_idx']?>" target="_blank"><?=$row['temple_title']?></a></td>
								<td><a href="../temple/temple_view.php?idx=<?=$row['temple_idx']?>" target="_blank"><?=$row['user_name']?></a></td>
								<td><a href="../temple/temple_view.php?idx=<?=$row['temple_idx']?>" target="_blank"><?=$row['wdate']?></a></td>
								<td><a href="../temple/temple_view.php?idx=<?=$row['temple_idx']?>" target="_blank"><?=$apply_ok?></a></td>
								<td><a href="../temple/temple_view.php?idx=<?=$row['temple_idx']?>" target="_blank"><?=$row['appdate']?></a></td>
								<td>
									<!--<select name="apply_ok" required="yes" message="승인여부" size="1" style="vertical-align:middle;" onchange="go_frm_modify('frm_cate1_<?=$i?>');">--> 
									<select name="apply_ok" required="yes" message="승인여부" size="1" style="vertical-align:middle;" onchange="go_temple_request_mod(this.value,'<?=$row[idx]?>');">
										<option value="">선택하세요</option>
										<option value="I" <?=$row[apply_ok]=="I"?"selected":""?>>승인대기</option>
										<option value="Y" <?=$row[apply_ok]=="Y"?"selected":""?>>승인</option>
										<option value="N" <?=$row[apply_ok]=="N"?"selected":""?>>미승인</option>
									</select>
								</td>
							</tr>
						</form>
						<?}?>
						</table>

					<div class="pagination mt0">
					<?
						$target_link = "member_temple_request.php";
						$target_id = "member_temple_request_area";
						$target_param = $total_param;
						include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging_ajax.php";	
					?>
					</div>