<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']);
$s_gubun = sqlfilter($_REQUEST['s_gubun']);
$s_level = sqlfilter($_REQUEST['s_level']);
$s_gender = sqlfilter($_REQUEST['s_gender']);
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1']));
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2']));
$s_cnt = trim(sqlfilter($_REQUEST['s_cnt'])); // 목록 갯수
$s_order = trim(sqlfilter($_REQUEST['s_order'])); // 목록 정렬
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_cnt='.$s_cnt.'&s_order='.$s_order;

$sub_checkin_arr = explode(":",$row['sub_checkin']);
$sub_checkin_h = $sub_checkin_arr[0];
$sub_checkin_m = $sub_checkin_arr[1];
$sub_checkout_arr = explode(":",$row['sub_checkout']);
$sub_checkout_h = $sub_checkout_arr[0];
$sub_checkout_m = $sub_checkout_arr[1];

$row['time_1'] = 30;
$row['time_2'] = 30;
?>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/parklot_left.php"; // 좌측메뉴?>
		<div class="container clearfix">
			<div class="content">
				<!-- 네비게이션 시작 -->
				<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>주차장 관리</li>
						<li>주차장등록</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>주차장 등록</h3>
				</div>
				<!-- 네비게이션 종료 -->
				<div class="write">
						
				<form name="frm" id="frm" action="parklot_write_action.php" target="_fra_admin" method="post" enctype="multipart/form-data">
					<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
					<input type="hidden" name="smenu" value="<?=$smenu?>"/>
					
					<table>
						<caption>게시글 등록</caption>
						<colgroup>
							<col style="width:15%;">
							<col style="width:35%;">
							<col style="width:15%;">
							<col style="width:35%;">
						</colgroup>
						<tr>
							<th scope="row">공유자</th>
							<td>
								<select name="member_idx" id="member_idx" required="yes" message="공유자" size="1" style="vertical-align:middle;width:40%;" >
									<option value="">선택하세요</option>
								<?
								$sub_sql = "select idx,user_name from member_info where 1 and memout_yn not in ('Y','S') and del_yn='N' and member_type in ('GEN','PAT') order by user_name asc";
								$sub_query = mysqli_query($gconnet,$sub_sql);
								$sub_cnt = mysqli_num_rows($sub_query);

								for($sub_i=0; $sub_i<$sub_cnt; $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
								?>
									<option value="<?=$sub_row['idx']?>" <?=$row['member_idx']==$sub_row['idx']?"selected":""?>><?=$sub_row['user_name']?></option>
								<?}?>
								</select>
							</td>
							<th scope="row">주차장 번호</th>
							<td>
								<input type="text" style="width:80%;" name="parklot_name" id="parklot_name" value="<?=$row['parklot_name']?>" required="yes"  message="주차장 번호">
							</td>
						</tr>
						<tr class="address">
								<th scope="col">주소</th>
								<td scope="col" colspan="3">
								<!--<p>
									<input type="text" name="parklot_zip" id="parklot_zip" value="<?=$row['parklot_zip']?>" readonly required="yes"  message="우편번호" is_num="yes">
									<a href="javascript:execDaumPostcode('parklot_zip', 'parklot_addr1', 'parklot_addr2');" class="btn_green">우편번호검색</a>
								</p>-->
								<p>
									<select name="sido" id="sido" style="vertical-align:middle;width:20%;" required="yes" message="지역" onchange="area_sel_1(this)">
										<option value="">시/도</option>
										<?
										$sect1_sql = "select bjd_code,k_name from code_bjd where 1 and filter='SIDO' and del_yn='N' order by k_name asc";
										$sect1_result = mysqli_query($gconnet,$sect1_sql);
											for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
												$row1 = mysqli_fetch_array($sect1_result);
										?>
											<option value="<?=$row1['bjd_code']?>" <?=$sido==$row1['bjd_code']?"selected":""?>><?=$row1['k_name']?></option>
										<?}?>
									</select>
									<select name="gugun" id="gugun" style="vertical-align:middle;width:20%;" required="yes" message="지역">
										<option value="">구/군</option>
									<?if($sido){?>
										<?
										$sect1_sql = "select bjd_code,k_name from code_bjd where 1 and filter='SGG' and del_yn='N' and pre_code='".$sido."' order by k_name asc";
										$sect1_result = mysqli_query($gconnet,$sect1_sql);
											for ($i=0; $i<mysqli_num_rows($sect1_result); $i++){
												$row1 = mysqli_fetch_array($sect1_result);
										?>
											<option value="<?=$row1['bjd_code']?>" <?=$gugun==$row1['bjd_code']?"selected":""?>><?=$row1['k_name']?></option>
										<?}?>
									<?}?>
									</select>
								</p>
								<p style="margin-top:10px;">
									<input type="text" name="parklot_addr1" id="parklot_addr1" value="<?=$row['parklot_addr1']?>" style="width:50%;" required="yes"  message="기본주소">
									<a href="javascript:execDaumPostcode('parklot_addr1', 'parklot_addr2');" class="btn_green">주소검색</a>
								</p>
								<!-- 우편번호 레이어 시작 -->
								<div id="post_wrap_parklot_addr1" style="display:none;border:1px solid;width:100%;height:300px;margin:5px 0;position:relative">
									<div><img src="//i1.daumcdn.net/localimg/localimages/07/postcode/320/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1;width:30px;" onclick="foldDaumPostcode('parklot_zip')" alt="접기 버튼"></div>
								</div>
								<!-- 우편번호 레이어 종료 -->
								<p>
									<input type="text" name="parklot_addr2" id="parklot_addr2" value="<?=$row['parklot_addr2']?>" style="width:50%;" required="no" message="상세주소" onblur="set_map_xy();">
									<span class="info">상세주소</span>
								</p>
							</td>
						</tr>
					<?
						$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='parklot_info' and board_code='image' and board_idx='".$row['idx']."' order by idx asc ";
						$query_file = mysqli_query($gconnet,$sql_file);
						$cnt_file = mysqli_num_rows($query_file);

						if($cnt_file < 8){
							$cnt_file = 8;
						}
						
						for($i_file=0; $i_file<$cnt_file; $i_file++){
							$row_file = mysqli_fetch_array($query_file);
							$k_file = $i_file+1;
					?>
						
						<input type="hidden" name="file_idx_<?=$i_file?>" value="<?=$row_file['idx']?>" />
						<input type="hidden" name="file_old_name_<?=$i_file?>" value="<?=$row_file['file_chg']?>" />
						<input type="hidden" name="file_old_org_<?=$i_file?>" value="<?=$row_file['file_org']?>" />
						
						<tr>
							<th>주차장 이미지 <?=$k_file?></th>
							<td colspan="3">
								<input type="file" style="width:30%;" required="no" message="주차장 이미지" name="file_<?=$i_file?>" accept="image/*">
								<?if($row_file['file_chg']){?>
									기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=parklot"><?=$row_file['file_org']?></a>
									(기존파일 삭제 : <input type="checkbox" name="del_org_<?=$i_file?>" value="Y">)
								<?} else{ ?>
									<input type="hidden" name="del_org_<?=$i_file?>" value="">
								<?}?>
							</td>
						</tr>
					<?}?>

					<?
						$sql_file = "select * from parklot_public_time where 1 and is_del='N' and parklot_idx='".$row['idx']."'";
						$query_file = mysqli_query($gconnet,$sql_file);
						$cnt_file = mysqli_num_rows($query_file);

						if($cnt_file < 7){
							$cnt_file = 7;
						}
						
						$now_yoil = date('w', strtotime(date("Y-m-d")));
					?>
						<tr>
							<th>공유시간 <?//=$inc_pubyoil_arr[$now_yoil]?></th>
							<td colspan="3">
							<?
								for($file_i=0; $file_i<$cnt_file; $file_i++){
									$row_file = mysqli_fetch_array($query_file);
									$file_k = $file_i+1;
							?>
								<input type="hidden" name="time_idx_<?=$file_k?>" value="<?=$row_file['idx']?>" />

								<div <?if($file_i > 0){?>style="margin-top:10px;"<?}?>> <?=$inc_pubyoil_arr[$file_k]?> : 
									<select style="width:15%;" name="pub_time_s_h_<?=$file_k?>" id="pub_time_s_h_<?=$file_k?>" required="no" message="공유시작 시각">
										<option value="">시각선택</option>
										<?
										$st = 0;
										$ed = 24;
											for($i=$st; $i<$ed; $i++){
										?>
											<option value="<?=fnzero($i)?>" <?=$pub_time_s_h==fnzero($i)?"selected":""?>><?=fnzero($i)?></option>
										<?}?>
									</select> <select style="width:15%;" name="pub_time_s_m_<?=$file_k?>" id="pub_time_s_m_<?=$file_k?>" required="no" message="공유시작 분">
										<option value="">분선택</option>
										<?
										$st = 0;
										$ed = 60;
											for($i=$st; $i<$ed; $i++){
										?>
											<option value="<?=fnzero($i)?>" <?=$pub_time_s_m==fnzero($i)?"selected":""?>><?=fnzero($i)?></option>
										<?}?>
									</select> 
									~
									&nbsp;<select style="width:15%;" name="pub_time_e_h_<?=$file_k?>" id="pub_time_e_h_<?=$file_k?>" required="no" message="공유종료 시각">
										<option value="">시각선택</option>
										<?
										$st = 0;
										$ed = 24;
											for($i=$st; $i<$ed; $i++){
										?>
											<option value="<?=fnzero($i)?>" <?=$pub_time_e_h==fnzero($i)?"selected":""?>><?=fnzero($i)?></option>
										<?}?>
									</select> <select style="width:15%;" name="pub_time_e_m_<?=$file_k?>" id="pub_time_e_m_<?=$file_k?>" required="no" message="공유종류 분">
										<option value="">분선택</option>
										<?
										$st = 0;
										$ed = 60;
											for($i=$st; $i<$ed; $i++){
										?>
											<option value="<?=fnzero($i)?>" <?=$pub_time_e_m==fnzero($i)?"selected":""?>><?=fnzero($i)?></option>
										<?}?>
									</select>						
									&nbsp; 공유시 체크 : <input type="checkbox" name="pub_time_yn_<?=$file_k?>" id="pub_time_yn_<?=$file_k?>" value="Y">
								</div>
							<?}?>
							</td>
						</tr>
						<tr>
							<th scope="row">연락처</th>
							<td>
								<input type="text" style="width:80%;" name="parklot_cell" id="parklot_cell" value="<?=$row['parklot_cell']?>" required="no" message="연락처">
							</td>
							<th scope="row">생년월일</th>
							<td>
								<input type="text" style="width:80%;" name="birthday" id="birthday" value="<?=$row['birthday']?>" readonly autocomplete="off" class="datepicker" required="no" message="생년월일">
							</td>
						</tr>
						<tr>
							<th scope="row">차량번호</th>
							<td colspan="3">
								<input type="text" style="width:20%;" name="car_num" id="car_num" value="<?=$row['car_num']?>" required="no" message="차량번호">
							</td>
						</tr>
						<tr>
							<th scope="row">기본 가격정보</th>
							<td colspan="3">
								기본 <input type="text" style="width:10%;" name="time_1" id="time_1" value="<?=$row['time_1']?>" readonly required="yes" message="기본 시간"> 분 주차에 <input type="text" style="width:15%;" name="price_1" id="price_1" value="<?=$row['price_1']?>" required="yes" message="기본 금액"> 원 부과
							</td>
						</tr>
						<tr>
							<th scope="row">추가 가격정보</th>
							<td colspan="3">
								기본시간에 추가로 <input type="text" style="width:10%;" name="time_2" id="time_2" value="<?=$row['time_2']?>" readonly required="yes" message="기본 시간"> 분 당 <input type="text" style="width:15%;" name="price_2" id="price_2" value="<?=$row['price_2']?>" required="yes" message="기본 금액"> 원이 추가로 부과됨.
							</td>
						</tr>
						<!--<tr>
							<th scope="row">일 주차요금</th>
							<td>
								<input type="text" style="width:40%;" name="price_3" id="price_3" value="<?=$row['price_3']?>" required="no" message="일 주차요금"> 원
							</td>
							<th scope="row">월 추자요금</th>
							<td>
								<input type="text" style="width:40%;" name="price_4" id="price_4" value="<?=$row['price_4']?>" required="no" message="월 주차요금"> 원
							</td>
						</tr>-->
						<tr>
							<th scope="row">이용규칙</th>
							<td colspan="3">
								<textarea placeholder="이용규칙" style="width:80%;height:100px;" name="rule_info" id="editor_1"><?=stripslashes($row['rule_info'])?></textarea>
							</td>
						</tr>
				
					</table>
				</form>

					<div class="write_btn align_r mt35">
						<button class="btn_modify" onclick="go_submit();">저장</button>
						<a href="javascript:go_list();" class="btn_list">목록</a>
						<!--<button class="btn_del">취소</button>-->
					</div>
				</div>
			</div>
		</div>

<script type="text/javascript" src="/smarteditor2/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript">

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

	/*var oEditors_5 = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors_5,
		elPlaceHolder: "editor_5",
		sSkinURI: "/smarteditor2/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){
				//alert("아싸!");
			}
		}, //boolean
		fOnAppLoad : function(){
			//예제 코드
			//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
		},
		fCreator: "createSEditor2"
	});

	var oEditors_6 = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors_6,
		elPlaceHolder: "editor_6",
		sSkinURI: "/smarteditor2/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){
				//alert("아싸!");
			}
		}, //boolean
		fOnAppLoad : function(){
			//예제 코드
			//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
		},
		fCreator: "createSEditor2"
	});

	var oEditors_7 = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors_7,
		elPlaceHolder: "editor_7",
		sSkinURI: "/smarteditor2/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){
				//alert("아싸!");
			}
		}, //boolean
		fOnAppLoad : function(){
			//예제 코드
			//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
		},
		fCreator: "createSEditor2"
	});

	var oEditors_8 = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors_8,
		elPlaceHolder: "editor_8",
		sSkinURI: "/smarteditor2/SmartEditor2Skin.html",
		htParams : {bUseToolbar : true,
			fOnBeforeUnload : function(){
				//alert("아싸!");
			}
		}, //boolean
		fOnAppLoad : function(){
			//예제 코드
			//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
		},
		fCreator: "createSEditor2"
	});*/

	function area_sel_1(z){
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="/pro_inc/area_select_1.php?cate_code1="+tmp+"&fm=frm&fname=gugun";
	}

	function area_sel_2(z){
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="/pro_inc/area_select_2.php?cate_code1="+tmp+"&fm=s_mem&fname=s_sect3";
	} 

function go_submit() {
	var check = chkFrm('frm');
	if(check) {
		/*oEditors_5.getById["editor_5"].exec("UPDATE_CONTENTS_FIELD", []);
		oEditors_6.getById["editor_6"].exec("UPDATE_CONTENTS_FIELD", []);
		oEditors_7.getById["editor_7"].exec("UPDATE_CONTENTS_FIELD", []);
		oEditors_8.getById["editor_8"].exec("UPDATE_CONTENTS_FIELD", []);*/
		frm.submit();
	} else {
		false;
	}
}

function go_list(){
		location.href = "parklot_list.php?<?=$total_param?>&pageNo=<?=$pageNo?>";
}

function set_cell_num(target_id){
	var cell_inp = $("#"+target_id+"").val();
	var cell_ninp = cell_inp.replace(/\-/g,"");
	$("#"+target_id+"").val(cell_ninp);
}

// register artwork 파일 업로드
/*targetFile2 = document.querySelector(".upload_preview");
if (targetFile2) {
   targetFile2.addEventListener("change", fileName2);
}
// Uploaded Artwork Preview 
function fileName2() {
  var fileMax = 50 * 1024 * 1024; // 50M

  if (targetFile2.files.length > 10) {
    modalNumber();
    targetFile2.value = "";
    targetFileBox.innerHTML = "";
    //함수 종료
    return;
  } else {
    
	fileList = "";
	
    for (i = 0; i < targetFile2.files.length; i++) {
				
		const imageSrc = URL.createObjectURL(targetFile2.files[i]);
		//alert (imageSrc);
      //파일의 개수만큼 리스트를 추가적으로 생성
      fileList +=
        "<li><img src='"+imageSrc+"' style='max-width:300px;'> <button type='button' class='btn_typeH btn_black btn_del_regi file'>Delete</button><input type='hidden' name='up_file_preview_list[]' id='up_file_preview_list_"+i+"' value='"+targetFile2.files[i].name+"'/></li>";

      //파일 용량 제한
      var fileSize = targetFile2.files[i].size;

      //100M 넘으면 alert 발생 후 값을 반영하지 않음
      if (fileSize > fileMax) {
        alert("Only files less than 100M can be attached.");
        targetFile2.value = "";
        targetFileBox.innerHTML = "";
      }
    }
    targetFileBox = document.querySelector(".file_preview_ul");
    targetFileBox.innerHTML = fileList;
  }

  // Uploaded Artwork Preview 파일 삭제
  for (j = 0; j < targetFile2.files.length; j++) {
    let btn_fileDel2 = document.querySelectorAll(
      ".file_preview_ul .btn_del_regi"
    );
    btn_fileDel2[j].addEventListener("click", function (e) {
      var target = e.target;
      var nodeLi = target.closest("li");
      nodeLi.remove();
      //실제 파일 삭제는 아님. 리스트 이름만 삭제됨
    });
  }
}

function old_del_artwork(delnum){
	$("#old_artwork_"+delnum+"").remove();
}
function old_del_preview(delnum){
	$("#old_preview_"+delnum+"").remove();
}*/
</script>

<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
function foldDaumPostcode(zip) {
		 var element_wrap = document.getElementById('post_wrap_'+zip+'');
        // iframe을 넣은 element를 안보이게 한다.
        element_wrap.style.display = 'none';
    }

    //function execDaumPostcode(zip,ad1,ad2) {
	function execDaumPostcode(ad1,ad2) {
		 var element_wrap = document.getElementById('post_wrap_'+ad1+'');
		// 현재 scroll 위치를 저장해놓는다.
        var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
        new daum.Postcode({
            oncomplete: function(data) {
                // 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var fullAddr = data.address; // 최종 주소 변수
                var extraAddr = ''; // 조합형 주소 변수

                // 기본 주소가 도로명 타입일때 조합한다.
                if(data.addressType === 'R'){
                    //법정동명이 있을 경우 추가한다.
                    if(data.bname !== ''){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있을 경우 추가한다.
                    if(data.buildingName !== ''){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
                    fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
                }
				
				//document.getElementById(''+zip+'').value = data.zonecode;
				document.getElementById(''+ad1+'').value = fullAddr;
				document.getElementById(''+ad2+'').focus();

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_wrap.style.display = 'none';

                // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                document.body.scrollTop = currentScroll;
            },
            // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
            onresize : function(size) {
                element_wrap.style.height = size.height+'px';
            },
            width : '100%',
            height : '100%'
        }).embed(element_wrap);

        // iframe을 넣은 element를 보이게 한다.
        element_wrap.style.display = 'block';
    }
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>