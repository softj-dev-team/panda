<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); // 파트너
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 게시 시작일
$s_level = sqlfilter($_REQUEST['s_level']); // 게시 종료일
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order.'&pageNo='.$pageNo;

$sql = "SELECT *,(select com_name from member_info where 1 and member_type = 'PAT' and idx=exp_info.member_idx) as com_name,(select cate_name1 from viva_cate where 1 and set_code='exper' and cate_level = '1' and cate_code1=exp_info.cate_code1) as cate_name1,(select sum(idx) from exp_info_regist where 1 and exp_info_idx=exp_info.idx) as answer_cnt FROM exp_info where 1=1 and idx = '".$idx."' ";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('등록된 체험이 없습니다.');
	location.href =  "exp_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);

//if($row['member_idx'] != $_SESSION['manage_coinc_idx']) {
?>
<!--<SCRIPT LANGUAGE="JavaScript">
	
	alert('등록된 체험이 없습니다.');
	location.href =  "exp_list.php?<?=$total_param?>";
	//
</SCRIPT>-->
<?
//exit;
//}

$sql_add_1 = "select * from exp_option_info where 1 and exp_info_idx='".$row[idx]."' and is_del = 'N' and view_ok = 'Y' order by align asc";
//echo $sql_add_1;
$query_add_1 = mysqli_query($gconnet,$sql_add_1);
?>

<script type="text/javascript">
	function go_view(no){
		location.href = "exp_view.php?idx="+no+"&<?=$total_param?>";
	}
	
	function go_modify(no){
		location.href = "exp_modify.php?idx="+no+"&<?=$total_param?>";
	}

	function go_delete(no){
		if(confirm('체험 정보를 삭제하시면 다시는 복구가 불가능 합니다. 정말 삭제 하시겠습니까?')){
			if(confirm('삭제하신 데이터는 복구할수 없도록 영구 삭제 됩니다. 그래도 삭제 하시겠습니까?')){	
				_fra_admin.location.href = "exp_delete_action.php?idx="+no+"&<?=$total_param?>";
			}
		}
	}

	function go_list(){
		location.href = "exp_list.php?<?=$total_param?>";
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

</script>

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
	str+="<br><input type='text' name='option_title_"+count_1+"' id='opt_title_"+count_1+"' style='width:60%;' value='option_title_"+count_1+"'/>"; // 추가할 폼(에 들어갈 HTML)
	var addedDiv = document.createElement("div"); // 폼 생성
    addedDiv.id = "added_1_"+count_1; // 폼 Div에 ID 부 여 (삭제를 위해)
    addedDiv.innerHTML  = str; // 폼 Div안에 HTML삽입
	//$("#added_1_"+count_1+"").html(str);
    addedFormDiv.appendChild(addedDiv); // 삽입할 DIV에 생성한 폼 삽입
    count_1++;
	frm_request.tafter_add_count_1.value = count_1;
}
function delForm_1(){
  var addedFormDiv = document.getElementById("addedFormDiv_1");
   if(count_1 >1){ // 현재 폼이 두개 이상이면
      var addedDiv = document.getElementById("added_1_"+(--count_1));
       // 마지막으로 생성된 폼의 ID를 통해 Div객체를 가져옴
        addedFormDiv.removeChild(addedDiv); // 폼 삭제 
		frm_request.tafter_add_count_1.value = count_1;
    }else{ // 마 지막 폼만 남아있다면
      //  document.baseForm.reset(); // 폼 내용 삭제
     }
}
//-->
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/exper_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>체험관리</li>
						<li>등록된 체험 상세보기</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>등록된 체험정보 상세보기</h3>
				</div>
				<div class="write">

					<table>
						<caption>체험정보 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th scope="row">카테고리</th>
							<td colspan="3">
								<?=$row[cate_name1]?>
							</td>
						</tr>
						<tr>
							<th scope="row">파트너명</th>
							<td colspan="3">
								<?=$row[com_name]?>
							</td>
						</tr>
						<tr>
							<th scope="row">대표이미지</th>
							<td colspan="3">
								<img src="<?=get_exp_image($row[idx],"file_chg")?>">
								<div style="margin-top:10px;"><?=nl2br($row[file_txt])?></div>
								<div style="margin-top:10px;"><?=nl2br($row[file_txt_a])?></div>
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 1</th>
							<td colspan="3">
							<?if($row[file_chg2]){?>
								<img src="<?=$_P_DIR_WEB_FILE?>expinfo/img_thumb/<?=$row[file_chg2]?>">
							<?}?>
								<div style="margin-top:10px;"><?=nl2br($row[file_txt2])?></div>
								<div style="margin-top:10px;"><?=nl2br($row[file_txt2_a])?></div>
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 2</th>
							<td colspan="3">
							<?if($row[file_chg3]){?>
								<img src="<?=$_P_DIR_WEB_FILE?>expinfo/img_thumb/<?=$row[file_chg3]?>">
							<?}?>
								<div style="margin-top:10px;"><?=nl2br($row[file_txt3])?></div>
								<div style="margin-top:10px;"><?=nl2br($row[file_txt3_a])?></div>
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 3</th>
							<td colspan="3">
							<?if($row[file_chg4]){?>
								<img src="<?=$_P_DIR_WEB_FILE?>expinfo/img_thumb/<?=$row[file_chg4]?>">
							<?}?>
								<div style="margin-top:10px;"><?=nl2br($row[file_txt4])?></div>
								<div style="margin-top:10px;"><?=nl2br($row[file_txt4_a])?></div>
							</td>
						</tr>
						<tr>
							<th scope="row">추가 이미지 4</th>
							<td colspan="3">
							<?if($row[file_chg5]){?>
								<img src="<?=$_P_DIR_WEB_FILE?>expinfo/img_thumb/<?=$row[file_chg5]?>">
							<?}?>
								<div style="margin-top:10px;"><?=nl2br($row[file_txt5])?></div>
								<div style="margin-top:10px;"><?=nl2br($row[file_txt5_a])?></div>
							</td>
						</tr>
						<tr>
							<th scope="row">체험제목</th>
							<td colspan="3">
								<?=$row[exp_title]?>
							</td>
						</tr>
						<tr>
							<th scope="row">상품 상세보기 링크</th>
							<td colspan="3">
								<a href="<?=$row[exp_link]?>" target="_blank"><?=$row[exp_link]?></a>
							</td>
						</tr>
						<tr>
							<th scope="row">참가자격 SNS 팔로워수</th>
							<td colspan="3">
								 참가자의 연동 SNS 팔로워수가 <?=number_format($row[exp_limit_cnt])?> 명 미만일시 참여불가
							</td>
						</tr>
						<tr>
							<th scope="row">체험 시작일시</th>
							<td colspan="3">
								<?=$row[s_date]?> <?=$row[s_time]?>
							</td>
						</tr>
						<tr>
							<th scope="row">체험 종료일시</th>
							<td colspan="3">
								<?=$row[e_date]?> <?=$row[e_time]?>
							</td>
						</tr>
						<tr>
							<th scope="row">체험수량</th>
							<td><?=number_format($row[set_click_cnt])?> 명 까지 체험 신청 가능</td>
							<th scope="row">현재 체험 신청수</th>
							<td><?=number_format($row[answer_cnt])?> 명</td>
						</tr>

						<tr>
							<th scope="row">시간설정 노출</th>
							<td>
								<?if($row[time_yn] == "Y"){?>
									설정된 시간에만 노출
								<?}elseif($row[time_yn] == "N"){?>
									시간 관계없이 노출
								<?}?>
							</td>
							<th scope="row">체험 노출시간</th>
							<td>
							<?if($row[time_yn] == "Y"){?>
								<?=substr($row[view_stime],0,2)?> 시 <?=substr($row[view_stime],2,2)?> 분 ~ <?=substr($row[view_etime],0,2)?> 시 <?=substr($row[view_etime],2,2)?> 분
							<?}?>
							</td>
						</tr>

						<tr>
							<th scope="row">체험신청 금액</th>
							<td>
								체험 신청 시 <?=number_format($row[exp_money])?> 원 결제
							</td>
							<th scope="row">소멸될 코인</th>
							<td>
								체험 신청 시 <?=number_format($row[exp_coin])?> 코인 차감
							</td>
						</tr>
						<tr>
							<th scope="row">체험내용</th>
							<td colspan="3">
								<?=nl2br($row[exp_content])?>
							</td>
						</tr>
						<tr>
							<th scope="row">쇼핑하기 링크</th>
							<td colspan="3">
								<a href="<?=$row[exp_shop_link]?>" target="_blank"><?=$row[exp_shop_link]?></a>
							<br><span style="color:red;">* http:// 포함하여 입력. 체험등록 파트너의 상품이 어플내에 등록되어 있지 않을때 이동할 주소.</span>
							</td>
						</tr>
						<tr>
							<th scope="row">등록일시</th>
							<td colspan="3">
								<?=$row[wdate]?>
							</td>
						</tr>
					<?if($row[mdate]){?>
						<tr>
							<th scope="row">마지막 수정일시</th>
							<td colspan="3">
								<?=$row[mdate]?>
							</td>
						</tr>
					<?}?>
					<?if($row[vdate]){?>
						<tr>
							<th scope="row">관리자 승인일시</th>
							<td colspan="3">
								<?=$row[vdate]?>
							</td>
						</tr>
					<?}?>
					</table>

					<p class="tit">체험신청 정보입력</p>
					<table>
					<colgroup>
						<col width="15%" />
						<col width="35%" />
						<col width="15%" />
						<col width="35%" />
					</colgroup>
					<form name="frm_request" id="frm_request" action="exp_request_action.php" target="_fra_admin" method="post" enctype="multipart/form-data">
						<input type="hidden" name="exp_info_idx" value="<?=$row['idx']?>"/>
						<input type="hidden" name="total_param" value="<?=$total_param?>"/>
						<?if(mysqli_num_rows($query_add_1) == 0){?>
							<input type="hidden" name="tafter_add_count_1" value="1"/>
						<?}else{?>
							<input type="hidden" name="tafter_add_count_1" value="<?=mysqli_num_rows($query_add_1)?>"/>
						<?}?>
						<input type="hidden" name="member_idx" value="<?=$row[member_idx]?>"/>
					<tr>
						<th scope="row">상품 옵션</th>
						<td colspan="3">
							<a href="javascript:addForm_1();" class="btn_green">옵션추가</a> <a href="javascript:delForm_1();" class="btn_gray">추가취소</a><br><br>
							<?if(mysqli_num_rows($query_add_1) == 0){?>
								<input type="text" name="option_title_0" id="opt_title_0" style="width:60%;" value="" required="no" message="옵션명" />
							<?}?>
							<?
							for($i_file=0; $i_file<mysqli_num_rows($query_add_1); $i_file++){
								$row_file = mysqli_fetch_array($query_add_1);
							?>
								<input type="hidden" name="option_idx_<?=$i_file?>" value="<?=$row_file['idx']?>" />
								<br><br><input type="text" name="option_title_<?=$i_file?>" id="opt_title_<?=$i_file?>"  value="<?=$row_file['opt_title']?>" style="width:60%;" /> 삭제 : <input type="checkbox" name="option_del_<?=$i_file?>" value="Y">
							<?}?>
							<div id="addedFormDiv_1"></div>
						</td>
					</tr>	
					<tr>
						<th scope="row">제품금액</th>
						<td>
							<input type="text" style="width:30%;" name="exp_o_money" id="exp_o_money" value="<?=$row['exp_o_money']?>" required="yes" message="제품금액" is_num="yes"> 원
						</td>
						<th scope="row">배송비</th>
						<td>
							<input type="text" style="width:30%;" name="exp_d_money" id="exp_d_money" value="<?=$row['exp_d_money']?>" required="yes" message="배송비" is_num="yes"> 원
						</td>
					</tr>
					<tr>
							<th scope="row">상세설명</th>
							<td colspan="3">
								 <textarea style="width:90%;height:100px;" name="regi_content" id="regi_content" required="no" message="정책안내" value=""><?=$row[regi_content]?></textarea>
							</td>
						</tr>
					</form>
					</table>
					<div style="margin-top:-20px;margin-bottom:20px;text-align:right;padding-right:10px;"><a href="javascript:go_submit_request();" class="btn_blue">체험신청 정보설정</a></div>
				
					<div class="write_btn align_r">
						<a href="javascript:go_list();" class="btn_gray">목록보기</a>
						<a href="javascript:go_modify('<?=$row[idx]?>');" class="btn_green">정보수정</a>
						<a href="javascript:go_delete('<?=$row[idx]?>');" class="btn_red">삭제하기</a>	
					</div>

					<p class="tit">승인 및 관리자 메모</p>
					<table>
					<colgroup>
						<col width="15%" />
						<col width="35%" />
						<col width="15%" />
						<col width="35%" />
					</colgroup>
			
						<form name="frm" action="exp_view_action.php" target="_fra_admin" method="post" >
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
							<tr>
							<th scope="row">관리자 메모</th>
							<td colspan="3">
								<textarea style="width:90%;height:50px;" name="admin_memo" required="no"  message="관리자 메모사항" value=""><?=$row[admin_memo]?></textarea>
							</td>
							</tr>
						</form>
					</table>
					<div style="margin-top:-20px;margin-bottom:20px;text-align:right;padding-right:10px;"><a href="javascript:go_submit();" class="btn_blue">설정변경</a></div>

			
		<!-- content 종료 -->
	</div>
</div>

<script type="text/javascript" src="/smtechg/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript">

var oEditors = [];

// 추가 글꼴 목록
//var aAdditionalFontSet = [["MS UI Gothic", "MS UI Gothic"], ["Comic Sans MS", "Comic Sans MS"],["TEST","TEST"]];

nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors,
	elPlaceHolder: "regi_content",
	sSkinURI: "/smtechg/SmartEditor2Skin.html",	
	htParams : {
		bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
		bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
		bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
		//aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
		fOnBeforeUnload : function(){
			//alert("완료!");
		}
	}, //boolean
	fOnAppLoad : function(){
		//예제 코드
		//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
		//oEditors.getById['ir1'].setDefaultFont("나눔고딕", 9);	
		setDefaultFont();
	},
	fCreator: "createSEditor2"
});

function pasteHTML() {
	var sHTML = "<span style='color:#FF0000;'>이미지도 같은 방식으로 삽입합니다.<\/span>";
	oEditors.getById["ir1"].exec("PASTE_HTML", [sHTML]);
}

function showHTML() {
	var sHTML = oEditors.getById["regi_content"].getIR();
	alert(sHTML);
}

function setDefaultFont() {
	var sDefaultFont = '나눔고딕';
	var nFontSize = 14;
	oEditors.getById["regi_content"].setDefaultFont(sDefaultFont, nFontSize);
	//oEditors.getById[obj].exec("SET_CONTENTS", [""]);  // 내용초기화
	// var sHTML = oEditors.getById[obj].getIR();
}

	function go_submit_request(){
		var check = chkFrm('frm_request');
		if(check) {
			oEditors.getById["regi_content"].exec("UPDATE_CONTENTS_FIELD", []);
			frm_request.submit();
		} else {
			false;
		}
	}
</script>

<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>