<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
$curri_info_idx = trim(sqlfilter($_REQUEST['curri_info_idx']));
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
$total_param = 'field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order.'&curri_info_idx='.$curri_info_idx;

$where = " and curri_info_idx='".$curri_info_idx."' and is_del='N'";

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
	//$where .= " and apply_ok='".$v_sect."'";
}

if ($field && $keyword){
	$where .= " and ".$field." like '%".$keyword."%'";
}

$query_cnt = "select idx from curri_lecture_info where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$pageScale = 20; // 페이지당 20 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by align asc"; 

$query = "select *,(select file_chg from board_file where 1 and board_tbname='curri_lecture_info' and board_code='photo' and board_idx=curri_lecture_info.idx order by idx asc limit 0,1) as file_photo,(select file_org from board_file where 1 and board_tbname='curri_lecture_info' and board_code='photo' and board_idx=curri_lecture_info.idx order by idx asc limit 0,1) as file_photo_org,(select file_chg from board_file where 1 and board_tbname='curri_lecture_info' and board_code='movie' and board_idx=curri_lecture_info.idx order by idx asc limit 0,1) as file_movie,(select file_org from board_file where 1 and board_tbname='curri_lecture_info' and board_code='movie' and board_idx=curri_lecture_info.idx order by idx asc limit 0,1) as file_movie_org from curri_lecture_info where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
					<!--<div style="text-align:right;padding-right:10px;padding-top:10px;"><a href="javascript:main_product_pop();" class="btn_green">소주제 리스트</a></div>-->

				<form method="post" name="frm" target="_fra_admin" id="frm">
					<input type="hidden" name="pageNo" value="<?=$pageNo?>" />
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
				
					<p style="text-align:right;padding-right:10px;padding-bottom:5px;"><font style="color:red;">* 낮은 숫자 우선으로 정렬됩니다.</font></p>

					<table class="search_list" style="margin-top:10px;">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:5%;">
								<col style="width:10%;">
								<col style="width:15%;">
								<col style="width:20%;">
								<col style="width:20%;">
								<col style="width:10%;">
								<col style="width:15%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col"><input type="checkbox" id="" name="checkNum" onclick="javascript:CheckAll()"></th>
									<th scope="col">번호</th>
									<th scope="col">이미지</th>
									<th scope="col">소주제 제목</th>
									<th scope="col">정답 스크립트</th>
									<th scope="col">정렬순서</th>
									<th scope="col">음원파일</th>
									<th scope="col">힌트창 문구</th>
								</tr>
							</thead>
						<?
							for ($i=0; $i<mysqli_num_rows($result); $i++){
								$row = mysqli_fetch_array($result);
								$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;

								if($i == mysqli_num_rows($result)-1){
									$lecture_idx .= $row['idx'];
								} else {
									$lecture_idx .= $row['idx'].",";
								}
						?>
							<tr>
								<td><input type="checkbox" name="lecture_idx[]" id="lecture_idx[]" value="<?=$row["idx"]?>" required="yes"  message="소주제"/></td>
								<td><a href="javascript:exam_list_view('<?=$row['idx']?>');"><?=$listnum?></a></td>
								<td><a href="javascript:exam_list_view('<?=$row['idx']?>');">
								<?if($row[file_photo]){?>
									<!--<a href="/pro_inc/download_file.php?nm=<?=$row['file_photo']?>&on=<?=$row['file_photo_org']?>&dir=curri_lecture_info">--><img src="<?=$_P_DIR_WEB_FILE?>curri_lecture_info/img_thumb/<?=$row['file_photo']?>" style="max-width:90%;"><!--</a>-->
								<?}?>
								</a></td>
								<td><a href="javascript:exam_list_view('<?=$row['idx']?>');"><?=$row['lecture_title']?></a></td>
								<td><a href="javascript:exam_list_view('<?=$row['idx']?>');"><?=string_cut2(strip_tags($row['lecture_correct']),80)?></a></td>
								<td>
									<input type="text" style="width:40%;" name="align_<?=$row["idx"]?>" required="yes" message="정렬순서" is_num="yes" value="<?=$row["align"]?>"> 숫자만 입력
								</td>
								<td><a href="javascript:exam_list_view('<?=$row['idx']?>');">
									<!--<a href="/pro_inc/download_file.php?nm=<?=$row['file_movie']?>&on=<?=$row['file_movie_org']?>&dir=curri_lecture_info">--><?=$row['file_movie_org']?><!--</a>-->
								</a></td>
								<td><a href="javascript:exam_list_view('<?=$row['idx']?>');"><?=string_cut2(strip_tags($row['lecture_hint']),80)?></a></td>
							</tr>
						<?}?>

						<input type="hidden" name="lecture_idx_arr" value="<?=$lecture_idx?>"/>
						</table>

						</form>
						
						<div style="text-align:right;margin-top:10px;padding-right:10px;">
							<a href="javascript:go_tot_del();" class="btn_red">선택삭제</a>
							<a href="javascript:go_tot_align();" class="btn_green">순서적용</a>
						</div>

					<div class="pagination mt0">
					<?
						$target_link = "exam_list_movie.php";
						$target_id = "exam_list_movie_area";
						$target_param = $total_param;
						include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging_ajax.php";	
					?>
					</div>

<script>
var check  = 0;                                                                            //체크 여부 확인
function CheckAll(){                
	var boolchk;                                                                              //boolean형 변수 
	var chk = document.getElementsByName("lecture_idx[]")                 //체크박스의 name값
		
		if(check){ check=0; boolchk = false; }else{ check=1; boolchk = true; }    //체크여부에 따른 true/false 설정
			
			for(i=0; i<chk.length;i++){                                                                    
				chk[i].checked = boolchk;                                                                //체크되어 있을경우 설정변경
			}

}

function go_tot_del() {
	var check = chkFrm('frm');
	if(check) {
		if(confirm('선택하신 소주제를 삭제 하시겠습니까?')){
			frm.action = "lecture_list_action_del.php";
			frm.submit();
		}
	} else {
		false;
	}
}

function go_tot_align() {
	frm.action = "lecture_list_action_align.php";
	frm.submit();
}
</script>