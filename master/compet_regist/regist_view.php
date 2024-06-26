<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 공모전주
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 게시 시작일
$s_level = sqlfilter($_REQUEST['s_level']); // 게시 종료일
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order.'&pageNo='.$pageNo;

$sql = "SELECT *,(select user_id from member_info where 1 and idx=compet_regist_info.member_idx) as com_id,(select compet_title from compet_info where 1 and idx=compet_regist_info.compet_idx) as compet_title FROM compet_regist_info where 1 and idx = '".$idx."' and is_del = 'N'";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('등록된 참가작이 없습니다.');
	location.href =  "regist_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

if($row[select_champ] == "Y"){
	$view_ok = "<font style='color:blue;'>우승작 (".$row[select_champ_depth]." 위)</font>";
} elseif($row[select_first] == "Y"){
	$view_ok = "<font style='color:green;'>1차 선정작</font>";
} else {
	$view_ok = "<font style='color:black;'>선정대기</font>";
}

//if($row['member_idx'] != $_SESSION['manage_coinc_idx']) {
?>
<!--<SCRIPT LANGUAGE="JavaScript">
	
	alert('등록된 공모전가 없습니다.');
	location.href =  "regist_list.php?<?=$total_param?>";
	//
</SCRIPT>-->
<?
//exit;
//}
?>

<script type="text/javascript">
	function go_view(no){
		location.href = "regist_view.php?idx="+no+"&<?=$total_param?>";
	}
	
	function go_modify(no){
		location.href = "regist_modify.php?idx="+no+"&<?=$total_param?>";
	}

	function go_delete(no){
		if(confirm('참가작 정보를 삭제하시면 다시는 복구가 불가능 합니다. 정말 삭제 하시겠습니까?')){
			if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "regist_delete_action.php?idx="+no+"&<?=$total_param?>&compet_idx=<?=$row['compet_idx']?>";
			}
		}
	}

	function go_list(){
		location.href = "regist_list.php?<?=$total_param?>";
	}

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
		}
	}

	function estimate_del(){
		if(confirm('작성하신 평가를 삭제 하시겠습니까?')){
			document.frm.mode.value="delete";
			document.frm.submit();
		}
	}

	function go_submit_quiz(){
		var check = chkFrm('frm_quiz');
		if(check) {
			frm_quiz.submit();
		} else {
			false;
		}
	}

$(function() {
	$(".datepicker").datepicker({
		changeYear:true,
		changeMonth:true,
		minDate: '-90y',
		yearRange: 'c-90:c',
		dateFormat:'yy-mm-dd',
		showMonthAfterYear:true,
		constrainInput: true,
		dayNamesMin: ['일','월', '화', '수', '목', '금', '토' ],
		monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월']
	});
});
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/regist_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>참가작 관리</li>
						<li>등록된 참가작 상세보기</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>등록된 참가작 상세보기</h3>
				</div>
				<div class="write">

					<table>
						<caption>공모전정보 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th scope="row">디자이너회원 ID</th>
							<td>
								<?=$row[com_id]?>
							</td>
							<th scope="row">디자이너 닉네임</th>
							<td>
								<?=$row[member_name]?>
							</td>
						</tr>
						<tr>
							<th scope="row">공모전 제목</th>
							<td colspan="3"><?=$row['compet_title']?></td>
						</tr>
						<tr>
							<th scope="row">작품 제목</th>
							<td colspan="3"><?=$row['work_title']?></td>
						</tr>
						<tr>
							<th scope="row">스톡컨텐츠 여부</th>
							<td colspan="3">
							<?if($row['stock_ok'] == "Y"){?>
								스톡컨텐츠를 사용 하였습니다.
							<?}else{?>
								스톡컨텐츠를 사용하지 않았습니다.
							<?}?>
							</td>
						</tr>
						<tr>
							<th scope="row">작품설명</th>
							<td colspan="3"><?=nl2br($row['work_detail'])?></td>
						</tr>
						<?
							$sect_sql = "select file_chg from board_file where 1 and board_tbname='compet_regist_info' and board_code='list' and board_idx='".$row['idx']."' order by idx asc";
							$sect_result = mysqli_query($gconnet,$sect_sql);
							for ($i=0; $i<mysqli_num_rows($sect_result); $i++){
								$sect_row = mysqli_fetch_array($sect_result);
						?>	
							<tr>
								<th scope="row">미리보기 이미지</th>
								<td colspan="3">
									<img src="/upload_file/compet_regist_info/img_thumb/<?=$sect_row['file_chg']?>" style="max-width:90%;">
								</td>
							</tr>
						<?}?>
						<?
							$sect_sql = "select file_chg from board_file where 1 and board_tbname='compet_regist_info' and board_code='detail' and board_idx='".$row['idx']."' order by idx asc";
							$sect_result = mysqli_query($gconnet,$sect_sql);
							for ($i=0; $i<mysqli_num_rows($sect_result); $i++){
								$sect_row = mysqli_fetch_array($sect_result);
						?>	
							<tr>
								<th scope="row">상세작품 이미지</th>
								<td colspan="3">
									<img src="/upload_file/compet_regist_info/img_thumb/<?=$sect_row['file_chg']?>" style="max-width:90%;">
								</td>
							</tr>
						<?}?>
					<tr>
						<th scope="row">등록일시</th>
						<td colspan="3">
							<?=$row[wdate]?>
						</td>
					</tr>
					<tr>
						<th scope="row">선정여부</th>
						<td colspan="3">
							<?=$view_ok?>
						</td>
					</tr>
					<?if($row['select_first_date']){?>
					<tr>
						<th scope="row">1차 선정된 일시</th>
						<td colspan="3">
							<?=$row[select_first_date]?>
						</td>
					</tr>
					<?}?>
					<?if($row['select_champ_date']){?>
					<tr>
						<th scope="row">우승작 선정된 일시</th>
						<td colspan="3">
							<?=$row[select_champ_date]?>
						</td>
					</tr>
					<?}?>
					</table>

					<div class="write_btn align_r">
						<a href="javascript:go_list();" class="btn_gray">목록보기</a>
						<a href="javascript:go_modify('<?=$row[idx]?>');" class="btn_green">정보수정</a>
						<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제하기</a>	
					</div>
				
			<?if($row[select_first] == "Y"){ // 1차 선정작일때 
			
				$estimate_sql = "select * from compet_regist_estimate_info where 1 and compet_idx='".$row['compet_idx']."' and regist_idx='".$row['idx']."' and is_del='N'";
				$estimate_query = mysqli_query($gconnet,$estimate_sql);
				//echo $estimate_sql;
				if(mysqli_num_rows($estimate_query) > 0){
					$estimate_row = mysqli_fetch_array($estimate_query);
					$estimate_idx = $estimate_row['idx'];
					$mode = "update";
				} else {
					$mode = "regist";
				}
			?>
					<p class="tit">평가 남기기</p>
					<table>
					<colgroup>
						<col width="15%" />
						<col width="35%" />
						<col width="15%" />
						<col width="35%" />
					</colgroup>
			
						<form name="frm" action="regist_view_action.php" target="_fra_admin" method="post" >
							<input type="hidden" name="compet_idx" value="<?=$row['compet_idx']?>"/>
							<input type="hidden" name="regist_idx" value="<?=$row['idx']?>"/>
							<input type="hidden" name="mode" value="<?=$mode?>"/>
							<input type="hidden" name="estimate_idx" value="<?=$estimate_idx?>"/>
							<input type="hidden" name="total_param" value="<?=$total_param?>"/>
							<tr>
							<th scope="row">창의성 평가</th>
							<td colspan="3">
								<textarea style="width:90%;height:100px;" name="txt_creative" required="no"  message="창의성 평가" value=""><?=$estimate_row[txt_creative]?></textarea>
							</td>
							</tr>
							<tr>
								<th>창의성 평점</th>
								<td colspan="3">
									<input type="radio" required="no" message="창의성 평점"  name="point_creative" value="1" <?=$estimate_row[point_creative]=="1"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />
									
									<input type="radio" required="no" message="창의성 평점"  name="point_creative" value="2" <?=$estimate_row[point_creative]=="2"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />

									<input type="radio" required="no" message="창의성 평점"  name="point_creative" value="3" <?=$estimate_row[point_creative]=="3"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />

									<input type="radio" required="no" message="창의성 평점"  name="point_creative" value="4" <?=$estimate_row[point_creative]=="4"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />

									<input type="radio" required="no" message="창의성 평점"  name="point_creative" value="5" <?=$estimate_row[point_creative]=="5"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />
						</td>
					</tr>

					<tr>
							<th scope="row">상품가치성 평가</th>
							<td colspan="3">
								<textarea style="width:90%;height:100px;" name="txt_product" required="no"  message="상품가치성 평가" value=""><?=$estimate_row[txt_product]?></textarea>
							</td>
							</tr>
							<tr>
								<th>상품가치성 평점</th>
								<td colspan="3">
									<input type="radio" required="no" message="상품가치성 평점"  name="point_product" value="1" <?=$estimate_row[point_product]=="1"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />
									
									<input type="radio" required="no" message="상품가치성 평점"  name="point_product" value="2" <?=$estimate_row[point_product]=="2"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />

									<input type="radio" required="no" message="상품가치성 평점"  name="point_product" value="3" <?=$estimate_row[point_product]=="3"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />

									<input type="radio" required="no" message="상품가치성 평점"  name="point_product" value="4" <?=$estimate_row[point_product]=="4"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />

									<input type="radio" required="no" message="상품가치성 평점"  name="point_product" value="5" <?=$estimate_row[point_product]=="5"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />
						</td>
					</tr>

					<tr>
							<th scope="row">작품성 평가</th>
							<td colspan="3">
								<textarea style="width:90%;height:100px;" name="txt_art" required="no"  message="작품성 평가" value=""><?=$estimate_row[txt_art]?></textarea>
							</td>
							</tr>
							<tr>
								<th>작품성 평점</th>
								<td colspan="3">
									<input type="radio" required="no" message="작품성 평점"  name="point_art" value="1" <?=$estimate_row[point_art]=="1"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />
									
									<input type="radio" required="no" message="작품성 평점"  name="point_art" value="2" <?=$estimate_row[point_art]=="2"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />

									<input type="radio" required="no" message="작품성 평점"  name="point_art" value="3" <?=$estimate_row[point_art]=="3"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />

									<input type="radio" required="no" message="작품성 평점"  name="point_art" value="4" <?=$estimate_row[point_art]=="4"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />

									<input type="radio" required="no" message="작품성 평점"  name="point_art" value="5" <?=$estimate_row[point_art]=="5"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />
						</td>
					</tr>

					<tr>
							<th scope="row">완성도 평가</th>
							<td colspan="3">
								<textarea style="width:90%;height:100px;" name="txt_complete" required="no"  message="완성도 평가" value=""><?=$estimate_row[txt_complete]?></textarea>
							</td>
							</tr>
							<tr>
								<th>완성도 평점</th>
								<td colspan="3">
									<input type="radio" required="no" message="완성도 평점"  name="point_complete" value="1" <?=$estimate_row[point_complete]=="1"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />
									
									<input type="radio" required="no" message="완성도 평점"  name="point_complete" value="2" <?=$estimate_row[point_complete]=="2"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />

									<input type="radio" required="no" message="완성도 평점"  name="point_complete" value="3" <?=$estimate_row[point_complete]=="3"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />

									<input type="radio" required="no" message="완성도 평점"  name="point_complete" value="4" <?=$estimate_row[point_complete]=="4"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />

									<input type="radio" required="no" message="완성도 평점"  name="point_complete" value="5" <?=$estimate_row[point_complete]=="5"?"checked":""?>><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" /><img src="/img/icon/view_list_star_on.gif" width="13" height="13" />
						</td>
					</tr>

						</form>
					</table>
					<div style="margin-top:-20px;margin-bottom:20px;text-align:right;padding-right:10px;">
						<a href="javascript:go_submit();" class="btn_blue">평가 등록</a>
					<?if($mode == "update"){?>
						<a href="javascript:estimate_del();" class="btn_red">평가 삭제</a>
					<?}?>
					</div>
			<?}?>
					
		<!-- content 종료 -->
	</div>
</div>

<script type="text/javascript">
<!-- 
<?if(mysqli_num_rows($query_add_1) == 0){?>
	var count_1 = 1;          
<?}else{?>
	var count_1 = <?=mysqli_num_rows($query_add_1)?>;          
<?}?>              
function addForm_1(){
	var addedFormDiv = document.getElementById("addedFormDiv_1");
	var str = "";
	str+="<br><input type='text' style='width:50%;' name='ans_title_"+count_1+"' required='no'  message='보기' value='ans_title_"+count_1+"'> <input type='checkbox' name='ans_view_ok_"+count_1+"' value='Y'> 승인시 체크 &nbsp; <input type='checkbox' name='ans_correct_"+count_1+"' value='Y'> 정답일경우 체크"; // 추가할 폼(에 들어갈 HTML)
	var addedDiv = document.createElement("div"); // 폼 생성
    addedDiv.id = "added_1_"+count_1; // 폼 Div에 ID 부 여 (삭제를 위해)
    addedDiv.innerHTML  = str; // 폼 Div안에 HTML삽입
    addedFormDiv.appendChild(addedDiv); // 삽입할 DIV에 생성한 폼 삽입
    count_1++;
	frm_quiz.tafter_add_count_1.value = count_1;
}
function delForm_1(){
  var addedFormDiv = document.getElementById("addedFormDiv_1");
   if(count_1 >1){ // 현재 폼이 두개 이상이면
      var addedDiv = document.getElementById("added_1_"+(--count_1));
       // 마지막으로 생성된 폼의 ID를 통해 Div객체를 가져옴
        addedFormDiv.removeChild(addedDiv); // 폼 삭제 
		frm_quiz.tafter_add_count_1.value = count_1;
    }else{ // 마 지막 폼만 남아있다면
      //  document.baseForm.reset(); // 폼 내용 삭제
     }
}

function check_txt_len(len,id,id2){
		var cur_length = Number($("#"+id+"").val().length);
		var len = Number(len);
		if(cur_length > len){
			alert("글자가 "+len+"자를 초과했어요!");
			//$("#"+id+"").val().substring(0, len));
			$("#"+id+"").val($("#"+id+"").val().substring(0, len));
		}
		/*if(cur_length > len) {
			$("#"+id2+"").show();
			$("#"+id+"").val().substring(0, len));
		}*/
	}

function go_check_submit2(){
	var check = chkFrm('frm_chk_regist2');
	if(check) {
		frm_chk_regist2.submit();
	} else {
		return;
	}
}

	function go_sub_modify(frm_name) {
		var check = chkFrm(frm_name);
		if(check) {
			document.forms[frm_name].submit();
		} else {
			return;
		}
	}

	function go_sub_delete(frm_name){
		if(confirm('정말로 삭제 하시겠습니까?')){	
			document.forms[frm_name].is_del.value="Y";
			document.forms[frm_name].submit();		
		}
	}
//-->
</script>

<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=159b59ff08bfbf0937e2a3b28f9aba5b&libraries=services"></script>
<script>
	// 지도 불러오기
	addr_view("<?=$row['regist_address']?>");

	// 지도 함수
	function addr_view(addr){
		var mapContainer = document.getElementById('msg_map'), // 지도를 표시할 div 
		mapOption = {
			center: new daum.maps.LatLng(<?=$row[map_x]?>, <?=$row[map_y]?>), // 지도의 중심좌표
			level: 3 // 지도의 확대 레벨
		};  

		// 지도를 생성합니다    
		var map = new daum.maps.Map(mapContainer, mapOption); 
								
		// 주소-좌표 변환 객체를 생성합니다
		var geocoder = new daum.maps.services.Geocoder();
		if(addr) {
			mapContainer.style.display = 'block';
			// 주소로 좌표를 검색합니다
			geocoder.addressSearch(addr, function(result, status) {

			// 정상적으로 검색이 완료됐으면 
			 if (status === daum.maps.services.Status.OK) {
					var coords = new daum.maps.LatLng(result[0].y, result[0].x);
					// 결과값으로 받은 위치를 마커로 표시합니다
					var marker = new daum.maps.Marker({
						map: map,
						position: coords
					});

					// 인포윈도우로 장소에 대한 설명을 표시합니다
					var infowindow = new daum.maps.InfoWindow({
						content: '<div style="width:150px;text-align:center;padding:6px 0;"><?=$row[regist_title]?></div>'
					});
					infowindow.open(map, marker);

					// 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
					map.setCenter(coords);
				 } 
			}); 
		}
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>