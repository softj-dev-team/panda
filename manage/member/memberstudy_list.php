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

$where = " and member_idx='".$member_idx."' and is_del='N'";

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

$query_cnt = "select idx from memberstudy_auth where 1 ".$where;
$result_cnt = mysqli_query($gconnet,$query_cnt);
$num = mysqli_num_rows($result_cnt);

$pageScale = 20; // 페이지당 20 개씩 
$start = ($pageNo-1)*$pageScale;

$StarRowNum = (($pageNo-1) * $pageScale);
$EndRowNum = $pageScale;

$order_by = " order by wdate desc"; 

//$query = "select *,(select align from curri_lecture_info where 1 and is_del='N' and idx=memberstudy_auth.lecture_info_idx) as study_num,(select lecture_title from curri_lecture_info where 1 and is_del='N' and idx=memberstudy_auth.lecture_info_idx) as lecture_title from memberstudy_auth where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

$query = "select *,(select curri_type from curri_info where 1 and is_del='N' and idx=memberstudy_auth.curri_info_idx) as curri_type,(select curri_title from curri_info where 1 and is_del='N' and idx=memberstudy_auth.curri_info_idx) as curri_title,(select lecture_title from curri_lecture_info where 1 and is_del='N' and idx=memberstudy_auth.lecture_info_idx) as lecture_title from memberstudy_auth where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

//echo "<br><br>쿼리 = ".$query."<br><Br>";

$result = mysqli_query($gconnet,$query);

$iTotalSubCnt = $num;
$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
					<!--<div style="text-align:right;padding-right:10px;padding-top:10px;"><a href="javascript:main_product_pop();" class="btn_green">소주제 리스트</a></div>-->

				<form method="post" name="list_frm2" target="_fra_admin" id="list_frm2">
					<input type="hidden" name="pageNo" value="<?=$pageNo?>" />
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<input type="hidden" name="member_idx" value="<?=$member_idx?>"/>
					<table class="search_list" style="margin-top:10px;">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:5%;">
								<col style="width:5%;">
								<col style="width:15%;">
								<col style="width:35%;">
								<col style="width:30%;">
								<col style="width:10%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col"><input type="checkbox" id="" name="checkNum" onclick="javascript:CheckAll()"></th>
									<th scope="col">번호</th>
									<th scope="col">학습일</th>
									<th scope="col">학습과정</th>
									<th scope="col">학습제목</th>
									<th scope="col">성공률</th>
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
								<td><input type="checkbox" name="memberstudy_idx[]" id="memberstudy_idx[]" value="<?=$row["idx"]?>" required="yes"  message="학습정보"/></td>
								<td><?=$listnum?></td>
								<td><?=$row['wdate']?></td>
								<td style="text-align:left;padding-left:10px;">[<?=get_code_value("cate_name1","cate_code1",$row['curri_type'])?>]&nbsp; <?=$row['curri_title']?></td>
								<td style="text-align:left;padding-left:10px;"><?=$row['lecture_title']?></td>
								<td><?=$row['per_success']?> %</td>
							</tr>
						<?}?>

						<input type="hidden" name="lecture_idx_arr" value="<?=$lecture_idx?>"/>
						</table>

						</form>
						
						<div style="text-align:right;margin-top:10px;padding-right:10px;">
							<a href="javascript:go_tot_del2();" class="btn_red">선택삭제</a>
							<a href="javascript:memberstudy_regist();" class="btn_blue">학습 추가</a>
						</div>

					<div class="pagination mt0">
					<?
						$target_link = "memberstudy_list.php";
						$target_id = "memberstudy_list_area";
						$target_param = $total_param;
						include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging_ajax.php";	
					?>
					</div>

<script>
var check  = 0;                                                                            //체크 여부 확인
function CheckAll(){                
	var boolchk;                                                                              //boolean형 변수 
	var chk = document.getElementsByName("memberstudy_idx[]")                 //체크박스의 name값
		
		if(check){ check=0; boolchk = false; }else{ check=1; boolchk = true; }    //체크여부에 따른 true/false 설정
			
			for(i=0; i<chk.length;i++){                                                                    
				chk[i].checked = boolchk;                                                                //체크되어 있을경우 설정변경
			}

}

function go_tot_del2() {
	var check = chkFrm('list_frm2');
	if(check) {
		if(confirm('선택하신 학습정보를 삭제 하시겠습니까?')){
			list_frm2.action = "memberstudy_list_action_del.php";
			list_frm2.submit();
		}
	} else {
		false;
	}
}

function go_tot_stop2() {
	var check = chkFrm('list_frm2');
	if(check) {
		if(confirm('선택하신 학습정보를 정지 하시겠습니까?')){
			list_frm2.action = "memberstudy_list_action_stop.php";
			list_frm2.submit();
		}
	} else {
		false;
	}
}
</script>