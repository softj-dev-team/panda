<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 사찰주
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 게시 시작일
$s_level = sqlfilter($_REQUEST['s_level']); // 게시 종료일
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order.'&pageNo='.$pageNo;

$sql = "SELECT *,(select user_name from member_info where 1 and idx=temple_info.member_idx) as user_name,(select user_id from member_info where 1 and idx=temple_info.member_idx) as user_id,(select file_chg from board_file where 1 and board_tbname='temple_info' and board_code='photo' and board_idx=temple_info.idx order by idx asc limit 0,1) as file_chg,(select file_chg from board_file where 1 and board_tbname='temple_info' and board_code='logo' and board_idx=temple_info.idx order by idx asc limit 0,1) as logo_file_chg,(select file_chg from board_file where 1 and board_tbname='temple_info' and board_code='sphoto' and board_idx=temple_info.idx order by idx asc limit 0,1) as sphoto_file_chg FROM temple_info where 1 and idx = '".$idx."' and is_del = 'N'";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('등록된 사찰이 없습니다.');
	location.href =  "temple_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

$sql_file_1 = "select * from temple_info_add where 1 and temple_info_idx='".$row['idx']."' and cate_type='mem' order by idx asc";
$query_file_1 = mysqli_query($gconnet,$sql_file_1);

$sql_file_2 = "select * from temple_info_add where 1 and temple_info_idx='".$row['idx']."' and cate_type='hast' order by idx asc";
$query_file_2 = mysqli_query($gconnet,$sql_file_2);
?>

<script type="text/javascript">
	function go_view(no){
		location.href = "temple_view.php?idx="+no+"&<?=$total_param?>";
	}
	
	function go_modify(no){
		location.href = "temple_modify.php?idx="+no+"&<?=$total_param?>";
	}

	function go_delete(no){
		if(confirm('사찰 정보를 삭제하시면 다시는 복구가 불가능 합니다. 정말 삭제 하시겠습니까?')){
			if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "temple_delete_action.php?idx="+no+"&<?=$total_param?>";
			}
		}
	}

	function go_list(){
		location.href = "temple_list.php?<?=$total_param?>";
	}

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			frm.submit();
		} else {
			false;
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
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/temple_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>사찰 관리</li>
						<li>등록된 사찰 상세보기</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>등록된 사찰정보 상세보기</h3>
				</div>
				<div class="write">

					<table>
						<caption>사찰정보 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
												
						<tr>
							<th>사찰회원</th>
							<td><?=$row['user_name']?></td>
							<th>레이아웃</th>
							<td><?=get_temple_layout($row['temple_layout'])?></td>
						</tr>
					<tr>
						<th scope="row">섬네일 이미지</th>
						<td colspan="3">
							<img src="<?=$_P_DIR_WEB_FILE?>temple_info/img_thumb/<?=$row['file_chg']?>" style="max-width:90%;">
						</td>
					</tr>
					<tr>
						<th scope="row">사찰명</th>
						<td colspan="3">
						<?if($row['logo_file_chg']){?>
							<img src="<?=$_P_DIR_WEB_FILE?>temple_info/img_thumb/<?=$row['logo_file_chg']?>" style="max-width:90%;">
						<?}else{?>
							<?=$row['temple_title']?>
						<?}?>
						</td>
					</tr>
					<tr>
						<th scope="row">배경이미지</th>
						<td colspan="3">
						<?if($row['sphoto_file_chg']){?>
							<img src="<?=$_P_DIR_WEB_FILE?>temple_info/img_thumb/<?=$row['sphoto_file_chg']?>" style="max-width:90%;">
						<?}?>
						</td>
					</tr>
					<tr class="address">
								<th scope="col">주소</th>
								<td scope="col" colspan="3">
									<?=$row[addr1]?> &nbsp; <?=$row[addr2]?>
								</td>
					</tr>
					<tr>
						<th scope="row">대표자/담당자</th>
						<td colspan="3">
						    <div>
							<ul>
							<?
							$i_file_cnt = mysqli_num_rows($query_file_1);
							for($i_file=0; $i_file<$i_file_cnt; $i_file++){
								$row_file = mysqli_fetch_array($query_file_1);
							?>
								<li style="margin-top:5px;margin-bottom:5px;"><?=$row_file['tag_value_1']?> / <?=$row_file['tag_value_2']?></li>
							<?}?>
							</ul>
							</div>
						</td>
					</tr>
					<tr>
						<th scope="row">사찰 키워드</th>
						<td colspan="3">
							<div>
							<ul>
							<?
							$i_file_cnt = mysqli_num_rows($query_file_2);
							for($i_file=0; $i_file<$i_file_cnt; $i_file++){
								$row_file = mysqli_fetch_array($query_file_2);
							?>
								<li style="margin-top:5px;margin-bottom:5px;"><?=$row_file['tag_value_1']?></li>
							<?}?>
							</ul>
							</div>	
						</td>
					</tr>
					<tr>
						<th scope="row">홈페이지</th>
						<td colspan="3">
							<?=$row['temple_url']?>
						</td>
					</tr>
						
						<tr>
							<th scope="row">조회수</th>
							<td>
								<?=number_format($row['vcnt'])?>
							</td>
							<th scope="row">등록일시</th>
							<td>
								<?=$row[wdate]?>
							</td>
						</tr>


					</table>

					<div class="write_btn align_r">
						<a href="javascript:go_list();" class="btn_gray">목록보기</a>
						<a href="javascript:go_modify('<?=$row[idx]?>');" class="btn_green">정보수정</a>
						<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제하기</a>	
					</div>
								
					<!--<p class="tit">승인 및 관리자 메모</p>
					<table>
					<colgroup>
						<col width="15%" />
						<col width="35%" />
						<col width="15%" />
						<col width="35%" />
					</colgroup>
			
						<form name="frm" action="temple_view_action.php" target="_fra_admin" method="post" >
							<input type="hidden" name="idx" value="<?=$idx?>"/>
							<input type="hidden" name="total_param" value="<?=$total_param?>"/>
							<tr>
							<th scope="row">승인여부</th>
							<td colspan="3">
								<select name="view_ok" required="yes" message="승인여부" size="1" style="vertical-align:middle;" >
									<option value="">선택하세요</option>
									<option value="Y" <?=$row[view_ok]=="Y"?"selected":""?>>승인</option>
									<option value="N" <?=$row[view_ok]=="N"?"selected":""?>>미승인</option>
								</select>
							</td>
							</tr>
							<!--<tr>
							<th scope="row">정렬순서</th>
							<td colspan="3">
								<input type="text" style="width:20%;" name="align" value="<?=$row[align]?>" required="yes" message="정렬순서" is_num="yes">
								<font style="color:red;">* 높은 숫자를 우선으로 정렬됨. 숫자만 입력.</font>
							</td>
							</tr>					
							<tr>
							<th scope="row">관리자 메모</th>
							<td colspan="3">
								<textarea style="width:90%;height:50px;" name="admin_memo" required="no"  message="관리자 메모사항" value=""><?=$row[admin_memo]?></textarea>
							</td>
							</tr>
						</form>
					</table>
					<div style="margin-top:-20px;margin-bottom:20px;text-align:right;padding-right:10px;"><a href="javascript:go_submit();" class="btn_blue">설정변경</a></div>-->
			<!--
				<div class="list_tit">
					<h3>티켓등록</h3>
				</div>
				<form name="frm_chk_regist2" id="frm_chk_regist2" action="temple_ticket_regist_action.php" target="_fra_admin" method="post" >
					<input type="hidden" name="temple_info_idx" value="<?=$row['idx']?>"/>
					<input type="hidden" name="member_idx" value="<?=$row['member_idx']?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>	
					<table class="search_list" style="margin-top:10px;">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:20%;">
								<col style="width:9%;">
								<col style="width:22%;">
								<col style="width:17%;">
								<col style="width:8%;">
								<col style="width:9%;">
								<col style="width:8%;">
								<col style="width:7%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">티켓명</th>
									<th scope="col">티켓타입</th>
									<th scope="col">운영일시(고정일)</th>
									<th scope="col">예약가능(예약일)</th>
									<th scope="col">최소인원</th>
									<th scope="col">가격</th>
									<th scope="col">발행수</th>
									<th scope="col">발행</th>
								</tr>
							</thead>
							<tbody>
							<tr>
								<td><input type="text" placeholder="20자 이내로 티켓명을 적어주세요." name="ticket_name" id="ticket_name" required="yes" message="티켓명" onblur="check_txt_len('20','ticket_name','txt_len_1');" style="width:98%;"></td>
								<td>
									<select name="ticket_type" size="1" style="vertical-align:middle;" required="yes" message="티켓타입">
										<option value="">선택하세요</option>
										<option value="A">고정일 운영</option>
										<option value="B">예약일 운영</option>
									</select>
								</td>
							<td>
								<input type="text" name="config_date" id="config_date" style="width:50%;" class="datepicker" value="" required="yes" message="운영일" readonly>
								<select name="config_date_t" id="config_date_t" required="yes" message="운영시각">
									<option value="00:00">00:00</option>
									<option value="00:30">00:30</option>
									<option value="01:00">01:00</option>
									<option value="01:30">01:30</option>
									<option value="02:00">02:00</option>
									<option value="02:30">02:30</option>
									<option value="03:00">03:00</option>
									<option value="03:30">03:30</option>
									<option value="04:00">04:00</option>
									<option value="04:30">04:30</option>
									<option value="05:00">05:00</option>
									<option value="05:30">05:30</option>
									<option value="06:00">06:00</option>
									<option value="06:30">06:30</option>
									<option value="07:00">07:00</option>
									<option value="07:30">07:30</option>
									<option value="08:00">08:00</option>
									<option value="08:30">08:30</option>
									<option value="09:00">09:00</option>
									<option value="09:30">09:30</option>
									<option value="10:00" selected>10:00</option>
									<option value="10:30">10:30</option>
									<option value="11:00">11:00</option>
									<option value="11:30">11:30</option>
									<option value="12:00">12:00</option>
									<option value="12:30">12:30</option>
									<option value="13:00">13:00</option>
									<option value="13:30">13:30</option>
									<option value="14:00">14:00</option>
									<option value="14:30">14:30</option>
									<option value="15:00">15:00</option>
									<option value="15:30">15:30</option>
									<option value="16:00">16:00</option>
									<option value="16:30">16:30</option>
									<option value="17:00">17:00</option>
									<option value="17:30">17:30</option>
									<option value="18:00">18:00</option>
									<option value="18:30">18:30</option>
									<option value="19:00">19:00</option>
									<option value="19:30">19:30</option>
									<option value="20:00">20:00</option>
									<option value="20:30">20:30</option>
									<option value="21:00">21:00</option>
									<option value="21:30">21:30</option>
									<option value="22:00">22:00</option>
									<option value="22:30">22:30</option>
									<option value="23:00">23:00</option>
									<option value="23:30">23:30</option>
								</select><span>시</span>
								<br> <input type="text" name="config_limit_date" id="config_limit_date" style="width:15%;" required="no" message="일 전 까지 구매" is_num="yes"><span>일 전 까지 구매</span>
							</td>
								<td>
									<span>구매일로부터</span><input type="text" name="useable_limit_date" id="useable_limit_date" style="width:15%;" required="no" message="일 까지 예약 가능" is_num="yes"><span>일 까지 예약 가능</span>
								</td>
								<td><input type="text" name="ticket_people" id="ticket_people" style="width:70%;" required="yes" message="최소인원" is_num="yes"> <span>명</span>	</td>
								<td><input type="text" name="ticket_price" id="ticket_price" required="yes" message="가격" is_num="yes" style="width:70%;"> <span>원</span>	</td>
								<td><input type="text" name="ticket_cnt" id="ticket_cnt" required="yes" message="발행수" is_num="yes" style="width:70%;"> <span>장</span>	</td>
								<td><a href="javascript:go_check_submit2();" class="btn_blue">발행</a></td>
							</tr>
							</body>
					</table>
				</form>

						<table class="search_list">
							<caption>검색결과</caption>
							<colgroup>
								<col style="width:18%;">
								<col style="width:8%;">
								<col style="width:20%;">
								<col style="width:16%;">
								<col style="width:7%;">
								<col style="width:8%;">
								<col style="width:8%;">
								<col style="width:15%;">
							</colgroup>
							<thead>
								<tr>
									<th scope="col">티켓명</th>
									<th scope="col">티켓타입</th>
									<th scope="col">운영일시(고정일)</th>
									<th scope="col">예약가능(예약일)</th>
									<th scope="col">최소인원</th>
									<th scope="col">가격</th>
									<th scope="col">발행수</th>
									<th scope="col">관리</th>
								</tr>
							</thead>
							<tbody>
			<?
			################### 댓글 서브페이징 시작 ####################
			$pageNo_sub = trim(sqlfilter($_REQUEST['pageNo_sub']));
			$total_param_sub = $total_param.'&idx='.$idx;
			################### 댓글 서브페이징 종료 ####################
			?>
				<input type="hidden" name="pageNo_sub" value="<?=$pageNo_sub?>"/>	
			<?
			$where_sub = " and temple_info_idx='".$row['idx']."' and is_del = 'N' and view_ok = 'Y'";

			if(!$pageNo_sub){
				$pageNo_sub = 1;
			}

			$pageScale_sub = 100; // 페이지당 100 개씩 
			$start_sub = ($pageNo_sub-1)*$pageScale_sub;

			$StarRowNum_sub = (($pageNo_sub-1) * $pageScale_sub);
			$EndRowNum_sub = $pageScale_sub;

			$order_by_sub = "  order by align desc";

			$sub_sql = "select * from temple_info_ticket where 1 ".$where_sub.$order_by_sub." limit ".$StarRowNum_sub." , ".$EndRowNum_sub;
			$sub_query = mysqli_query($gconnet,$sub_sql);
			$sub_cnt = mysqli_num_rows($sub_query);

			$query_sub_cnt = "select idx from temple_info_ticket where 1 ".$where_sub;
			$result_sub_cnt = mysqli_query($gconnet,$query_sub_cnt);
			$num_sub = mysqli_num_rows($result_sub_cnt);

			$iTotalSubCnt_sub = $num_sub;
			$totalpage_sub	= ($iTotalSubCnt_sub - 1)/$pageScale_sub  + 1;

			for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
				$sub_row = mysqli_fetch_array($sub_query);
			?> 
				<form name="frm_cate1_<?=$sub_i?>" method="post" action="temple_ticket_modify_action.php"  target="_fra_admin" enctype="multipart/form-data">
					<input type="hidden" name="idx" value="<?=$sub_row[idx]?>"/>
					<input type="hidden" name="temple_info_idx" value="<?=$row['idx']?>"/>
					<input type="hidden" name="member_idx" value="<?=$row['member_idx']?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>	
					<input type="hidden" name="is_del" value="N">
					<tr>
						<td><?=$sub_row['ticket_name']?></td>
						<td>
							<?if($sub_row['ticket_type'] == "A"){?>
								고정일
							<?}elseif($sub_row['ticket_type'] == "B"){?>
								예약일
							<?}?>
						</td>
						<td>
						<?if($sub_row['ticket_type'] == "A"){?>
							<p>운영일시 : <?=$sub_row['config_date']?></p>
							<p><?=$sub_row['config_limit_date']?> 일 전 까지 구매가능</p>	
						<?}?>
						</td>
						<td>
						<?if($sub_row['ticket_type'] == "B"){?>
							<p>구매 후 <?=$sub_row['useable_limit_date']?>일까지 이용가능</p>
						<?}?>
						</td>
						<td><?=number_format($sub_row['ticket_people'])?> <span>명</span></td>
						<td><?=number_format($sub_row['ticket_price'])?> <span>원</span>	</td>
						<td><input type="text" name="ticket_cnt" id="ticket_cnt" required="yes" message="발행수" is_num="yes" style="width:70%;" value="<?=$sub_row['ticket_cnt']?>"> <span>장</span>	</td>		
						<td>
							<a href="javascript:go_sub_modify('frm_cate1_<?=$sub_i?>');" class="btn_green" >수정</a>&nbsp;<a href="javascript:go_sub_delete('frm_cate1_<?=$sub_i?>');" class="btn_red">삭제</a>
						</td>						
					</tr>
					</form>			
			<?}?>
			</tbody>
		</table>
		-->
			
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
	addr_view("<?=$row['temple_address']?>");

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
						content: '<div style="width:150px;text-align:center;padding:6px 0;"><?=$row[temple_title]?></div>'
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