<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$idx = trim(sqlfilter($_REQUEST['idx']));
$pageNo = trim(sqlfilter($_REQUEST['pageNo']));
$field = trim(sqlfilter($_REQUEST['field']));
$keyword = sqlfilter($_REQUEST['keyword']);
$v_sect = sqlfilter($_REQUEST['v_sect']); 
$s_gubun = sqlfilter($_REQUEST['s_gubun']); // 게시 시작일
$s_level = sqlfilter($_REQUEST['s_level']); // 게시 종료일
$s_gender = sqlfilter($_REQUEST['s_gender']); 
$s_sect1 = trim(sqlfilter($_REQUEST['s_sect1'])); 
$s_sect2 = trim(sqlfilter($_REQUEST['s_sect2'])); 
$s_order = trim(sqlfilter($_REQUEST['s_order'])); 
################## 파라미터 조합 #####################
$total_param = 'bmenu='.$bmenu.'&smenu='.$smenu.'&field='.$field.'&keyword='.$keyword.'&v_sect='.$v_sect.'&s_gubun='.$s_gubun.'&s_level='.$s_level.'&s_gender='.$s_gender.'&s_sect1='.$s_sect1.'&s_sect2='.$s_sect2.'&s_order='.$s_order.'&pageNo='.$pageNo;

$sql = "SELECT * FROM curri_info where 1 and idx = '".$idx."' and is_del = 'N'";
$query = mysqli_query($gconnet,$sql);

if(mysqli_num_rows($query) == 0){
?>
<SCRIPT LANGUAGE="JavaScript">
	<!--
	alert('등록된 대주제가 없습니다.');
	location.href =  "curri_list.php?<?=$total_param?>";
	//-->
</SCRIPT>
<?
exit;
}

$row = mysqli_fetch_array($query);
$bbs_code = "curri_info";

$sql_file_1 = "select * from curri_lecture_info where 1 and curri_info_idx='".$row['idx']."' and is_del='N' order by align asc";
$query_file_1 = mysqli_query($gconnet,$sql_file_1);
$attach_count_1 = mysqli_num_rows($query_file_1);

if($attach_count_1 == 0){
	$attach_count_1 = 1;
}
?>
<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/curri_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li><?=get_code_value("cate_name1","cate_code1",$v_sect)?></li>
						<li>대주제 수정</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>대주제 정보 수정</h3>
				</div>
				<div class="write">

				<form name="frm" action="curri_modify_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
					<input type="hidden" name="idx" id="curri_idx" value="<?=$idx?>"/>
					<input type="hidden" name="total_param" value="<?=$total_param?>"/>
					<input type="hidden" name="curri_type" id="curri_type" value="<?=$v_sect?>"/>
					<input type="hidden" name="attach_count_1" id="attach_count_1" value="<?=$attach_count_1?>"/>
					
					<p class="tit" style="background-image:url(../images/common/play.png); background-repeat:no-repeat; background-position:left center; font-size:16px; color:#454545; padding-left:22px; margin-bottom:10px;">대주제 등록사항</p>
					<table>
						<caption>대주제 수동등록</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
					<tr>
						<th scope="row">제목</th>
						<td colspan="3">
							<input type="text" style="width:60%;" name="curri_title" required="yes" message="제목" value="<?=$row['curri_title']?>">
						</td>
					</tr>
					<tr>
						<th scope="row">정렬순서</th>
						<td>
							<input type="text" style="width:20%;" name="align" required="yes" message="정렬순서" is_num="yes" value="<?=$row['align']?>">
							높은 숫자 우선, 숫자만 입력
						</td>
						<th scope="row">노출여부</th>
						<td>
							<input type="radio" name="view_ok" value="Y" required="yes" message="노출여부" <?=$row[view_ok]=="Y"?"checked":""?>> Y &nbsp;
							<input type="radio" name="view_ok" value="N" required="yes" message="노출여부" <?=$row[view_ok]=="N"?"checked":""?>> N
						</td>
					</tr>
					<?
						$sql_file = "select idx,file_org,file_chg,file_content from board_file where 1=1 and board_tbname='curri_info' and board_code='sphoto' and board_idx='".$row['idx']."' order by idx asc ";
						$query_file = mysqli_query($gconnet,$sql_file);
						$cnt_file = mysqli_num_rows($query_file);

						if($cnt_file < 1){
							$cnt_file = 1;
						}
						
						for($i_file=0; $i_file<$cnt_file; $i_file++){
							$row_file = mysqli_fetch_array($query_file);
							$k_file = $i_file+1;
					?>
						
						<input type="hidden" name="pfile1_idx_<?=$i_file?>" value="<?=$row_file['idx']?>" />
						<input type="hidden" name="pfile1_old_name_<?=$i_file?>" value="<?=$row_file['file_chg']?>" />
						<input type="hidden" name="pfile1_old_org_<?=$i_file?>" value="<?=$row_file['file_org']?>" />
					<tr>
						<th scope="row">이미지</th>
						<td colspan="3">
							<input type="file" style="width:400px;" required="no" message="이미지" name="photo1_<?=$i_file?>"> (최적화 사이즈 : 가로 500, 세로 354 픽셀)
							<?if($row_file['file_chg']){?>
								<br>기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=curri_info"><?=$row_file['file_org']?></a>
								(기존파일 삭제 : <input type="checkbox" name="pdel1_org_<?=$i_file?>" value="Y">)
							<?} else{ ?>
								<input type="hidden" name="pdel1_org_<?=$i_file?>" value="">
							<?}?>
						</td>
					</tr>
				<?}?>

					<tr>
						<th scope="row">설명글</th>
						<td colspan="3">
							<textarea style="width:90%;height:100px;" name="curri_detail" id="editor_111" required="yes" message="설명글" value=""><?=$row['curri_detail']?></textarea>
						</td>
					</tr>
				
			<!--
				<?
						$sql_file = "select idx,file_org,file_chg,file_content from board_file where 1=1 and board_tbname='curri_info' and board_code='photo' and board_idx='".$row['idx']."' order by idx asc ";
						$query_file = mysqli_query($gconnet,$sql_file);
						$cnt_file = mysqli_num_rows($query_file);

						if($cnt_file < 1){
							$cnt_file = 1;
						}
						
						for($i_file=0; $i_file<$cnt_file; $i_file++){
							$row_file = mysqli_fetch_array($query_file);
							$k_file = $i_file+1;
					?>
						
						<input type="hidden" name="pfile_idx_<?=$i_file?>" value="<?=$row_file['idx']?>" />
						<input type="hidden" name="pfile_old_name_<?=$i_file?>" value="<?=$row_file['file_chg']?>" />
						<input type="hidden" name="pfile_old_org_<?=$i_file?>" value="<?=$row_file['file_org']?>" />
					<tr>
						<th scope="row">오버 이미지</th>
						<td colspan="3">
							<input type="file" style="width:400px;" required="no" message="오버 이미지" name="photo_<?=$i_file?>"> (최적화 사이즈 : 가로 344 픽셀, 세로 526 픽셀)
							<?if($row_file['file_chg']){?>
								<br>기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=curri_info"><?=$row_file['file_org']?></a>
								(기존파일 삭제 : <input type="checkbox" name="pdel_org_<?=$i_file?>" value="Y">)
							<?} else{ ?>
								<input type="hidden" name="pdel_org_<?=$i_file?>" value="">
							<?}?>
						</td>
					</tr>
				<?}?>
				-->
					</table>

					<p class="tit" style="background-image:url(../images/common/play.png); background-repeat:no-repeat; background-position:left center; font-size:16px; color:#454545; padding-left:22px; margin-bottom:10px;">소주제 등록</p>
					<div style="text-align:right;padding-right:10px;margin-bottom:10px;">
						<a href="javascript:addForm_1();" class="btn_green">추가</a> <a href="javascript:delForm_1();" class="btn_red">취소</a>
					</div>
					
					<?
						for($i_file=0; $i_file<$attach_count_1; $i_file++){ // 등록된 소주제 루프 시작 
							$row_file = mysqli_fetch_array($query_file_1);
					?>
						
						<input type="hidden" name="lecture_idx_<?=$i_file?>" value="<?=$row_file['idx']?>" />

						<table <?if($i_file > 0){?>style="margin-top:-20px;"<?}?>>
						<caption> 상세보기</caption>
						<colgroup>
							<col style="width:15%">
							<col style="width:35%">
							<col style="width:15%">
							<col style="width:35%">
						</colgroup>
						<tr>
							<th scope="row">소주제 제목</th>
							<td colspan="3">
								<input type="text" style="width:60%;" id="lecture_title_<?=$i_file?>" name="lecture_title_<?=$i_file?>" required="yes" message="소주제 제목" value="<?=$row_file['lecture_title']?>"> 등록된 소주제 삭제 : <input type="checkbox" name="lecture_del_<?=$i_file?>" value="Y">
							</td>
						</tr>
						<?
						$sql_file_lect = "select idx,file_org,file_chg from board_file where 1 and board_tbname='curri_lecture_info' and board_code='photo' and board_idx='".$row_file['idx']."' order by idx asc ";
						$query_file_lect = mysqli_query($gconnet,$sql_file_lect);
						$cnt_file_lect = mysqli_num_rows($query_file_lect);

						if($cnt_file_lect < 1){
							$cnt_file_lect = 1;
						}
						
						for($i_file_lect=0; $i_file_lect<$cnt_file_lect; $i_file_lect++){ // 소주제 이미지 루프 시작 
							$row_file_lect = mysqli_fetch_array($query_file_lect);
							$k_file_lect = $i_file_lect+1;
						?>
						<tr>
							<th scope="row">섬네일 이미지</th>
							<td colspan="3">
								<input type="hidden" name="pfile2_idx_<?=$i_file?>_<?=$i_file_lect?>" value="<?=$row_file_lect['idx']?>" />
								<input type="hidden" name="pfile2_old_name_<?=$i_file?>_<?=$i_file_lect?>" value="<?=$row_file_lect['file_chg']?>" />
								<input type="hidden" name="pfile2_old_org_<?=$i_file?>_<?=$i_file_lect?>" value="<?=$row_file_lect['file_org']?>" />

								<input type="file" style="width:400px;" required="no" message="섬네일 이미지" name="photo2_<?=$i_file?>_<?=$i_file_lect?>"> (최적화 사이즈 : 가로 240, 세로 170 픽셀)
								<?if($row_file_lect['file_chg']){?>
									<br>기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file_lect['file_chg']?>&on=<?=$row_file_lect['file_org']?>&dir=curri_lecture_info"><?=$row_file_lect['file_org']?></a>
									(기존파일 삭제 : <input type="checkbox" name="pdel2_org_<?=$i_file?>_<?=$i_file_lect?>" value="Y">)
								<?}?>
							</td>
						</tr>
						<?} // 소주제 이미지 루프 종료 ?>

						<?
						$sql_file_lect = "select idx,file_org,file_chg from board_file where 1 and board_tbname='curri_lecture_info' and board_code='movie' and board_idx='".$row_file['idx']."' order by idx asc ";
						$query_file_lect = mysqli_query($gconnet,$sql_file_lect);
						$cnt_file_lect = mysqli_num_rows($query_file_lect);

						if($cnt_file_lect < 1){
							$cnt_file_lect = 1;
						}
						
						for($i_file_lect=0; $i_file_lect<$cnt_file_lect; $i_file_lect++){ // 음원 루프 시작 
							$row_file_lect = mysqli_fetch_array($query_file_lect);
							$k_file_lect = $i_file_lect+1;
						?>
						<tr>
							<th scope="row">음원</th>
							<td colspan="3">
								<input type="hidden" name="pfile3_idx_<?=$i_file?>_<?=$i_file_lect?>" value="<?=$row_file_lect['idx']?>" />
								<input type="hidden" name="pfile3_old_name_<?=$i_file?>_<?=$i_file_lect?>" value="<?=$row_file_lect['file_chg']?>" />
								<input type="hidden" name="pfile3_old_org_<?=$i_file?>_<?=$i_file_lect?>" value="<?=$row_file_lect['file_org']?>" />

								<input type="file" style="width:400px;" required="no" message="음원" name="photo3_<?=$i_file?>_<?=$i_file_lect?>">
								<?if($row_file_lect['file_chg']){?>
									<br>기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file_lect['file_chg']?>&on=<?=$row_file_lect['file_org']?>&dir=curri_lecture_info"><?=$row_file_lect['file_org']?></a>
									(기존파일 삭제 : <input type="checkbox" name="pdel3_org_<?=$i_file?>_<?=$i_file_lect?>" value="Y">)
								<?}?>
							</td>
						</tr>
						<?} // 음원 루프 종료 ?>

						<tr>
						<th scope="row">정답 스크립트</th>
						<td colspan="3">
							<textarea style="width:90%;height:100px;" name="lecture_correct_<?=$i_file?>" id="lecture_correct_<?=$i_file?>" required="yes" message="정답 스크립트" value=""><?=$row_file['lecture_correct']?></textarea>
						</td>
						</tr>
						<tr>
						<th scope="row">정답 스크립트 (한글)</th>
						<td colspan="3">
							<textarea style="width:90%;height:100px;" name="lecture_correct_kor_<?=$i_file?>" id="lecture_correct_kor_<?=$i_file?>" required="yes" message="정답 스크립트 (한글)" value=""><?=$row_file['lecture_correct_kor']?></textarea>
						</td>
						</tr>
						<tr>
						<th scope="row">힌트창 문구</th>
						<td colspan="3">
							<textarea style="width:90%;height:100px;" name="lecture_hint_<?=$i_file?>" id="lecture_hint_<?=$i_file?>" required="yes" message="힌트창 문구" value=""><?=$row_file['lecture_hint']?></textarea>
						</td>
						</tr>
						</table>
					<?} // 등록된 소주제 루프 종료?>

						<div id="addedFormDiv_1"></div>

					</form>

					<div class="write_btn align_r">
						<a href="javascript:go_list();" class="btn_list">취소</a>
						<button class="btn_modify" type="button" onclick="go_submit();">정보수정</button>
					</div>
				</div>
			<!-- content 종료 -->
	</div>
</div>

<script type="text/javascript" src="/smarteditor2/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript">
	
	/*var oEditors_1 = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors_1,
		elPlaceHolder: "editor_1",
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
	
	function cate_sel_1(z,level){
		var tmp = z.options[z.selectedIndex].value; 
		_fra_admin.location.href="/pro_inc/cate_select.php?cate_code1="+tmp+"&fm=frm&fname=curri_cate_sub&cate_level="+level+"";
	}

	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			//oEditors_1.getById["editor_1"].exec("UPDATE_CONTENTS_FIELD", []);
			frm.submit();
		} else {
			false;
		}
	}

	function go_list(){
		location.href = "curri_view.php?idx=<?=$idx?>&<?=$total_param?>";
	}

var count_1 = <?=$attach_count_1?>;
function addForm_1(){
	var addedFormDiv = document.getElementById("addedFormDiv_1");
	var str = "";

	<?if($v_sect == "CG0006" || $v_sect == "CG0007" || $v_sect == "CG0008"){?>
		if(count_1 >= 16){
			alert("소주제 추가는 16개 까지만 가능합니다.");
			return;
		}
	<?}else{?>
		if(count_1 >= 10){
			alert("소주제 추가는 10개 까지만 가능합니다.");
			return;
		}
	<?}?>
	
	str+='<table style="margin-top:-20px;"><colgroup><col style="width:15%"><col style="width:35%"><col style="width:15%"><col style="width:35%"></colgroup><tr><th scope="row">소주제 제목</th><td colspan="3"><input type="text" style="width:60%;" id="lecture_title_'+count_1+'" name="lecture_title_'+count_1+'" required="yes" message="소주제 제목" value=""></td></tr><tr><th scope="row">섬네일 이미지</th><td colspan="3"><input type="file" style="width:400px;" required="yes" message="섬네일 이미지" name="photo2_'+count_1+'_0"> (최적화 사이즈 : 가로 240, 세로 170 픽셀)</td></tr><tr><th scope="row">음원</th><td colspan="3"><input type="file" style="width:400px;" required="yes" message="음원" name="photo3_'+count_1+'_0"></td></tr><tr><th scope="row">정답 스크립트</th><td colspan="3"><textarea style="width:90%;height:100px;" name="lecture_correct_'+count_1+'" id="lecture_correct_'+count_1+'" required="yes" message="정답 스크립트" value=""></textarea></td></tr><tr><th scope="row">정답 스크립트 (한글)</th><td colspan="3"><textarea style="width:90%;height:100px;" name="lecture_correct_kor_'+count_1+'" id="lecture_correct_kor_'+count_1+'" required="yes" message="정답 스크립트 (한글)" value=""></textarea></td></tr><tr><th scope="row">힌트창 문구</th><td colspan="3"><textarea style="width:90%;height:100px;" name="lecture_hint_'+count_1+'" id="lecture_hint_'+count_1+'" required="yes" message="힌트창 문구" value=""></textarea></td></tr></table>';

	var addedDiv = document.createElement("div"); // 폼 생성
    addedDiv.id = "added_1_"+count_1; // 폼 Div에 ID 부 여 (삭제를 위해)
    addedDiv.innerHTML  = str; // 폼 Div안에 HTML삽입
    addedFormDiv.appendChild(addedDiv); // 삽입할 DIV에 생성한 폼 삽입
    count_1++;
	frm.attach_count_1.value = count_1;
}
function delForm_1(){
  var addedFormDiv = document.getElementById("addedFormDiv_1");
   if(count_1 >1){ // 현재 폼이 두개 이상이면
      var addedDiv = document.getElementById("added_1_"+(--count_1));
         addedFormDiv.removeChild(addedDiv); // 폼 삭제 
		 frm.attach_count_1.value = count_1;
    }else{ // 마 지막 폼만 남아있다면
      //  document.baseForm.reset(); // 폼 내용 삭제
     }
}
</script>

<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>
<script type="text/javascript">
function foldDaumPostcode(zip) {
		 var element_wrap = document.getElementById('post_wrap_'+zip+'');
        // iframe을 넣은 element를 안보이게 한다.
        element_wrap.style.display = 'none';
    }

    function execDaumPostcode(zip,ad1,ad2) {
		 var element_wrap = document.getElementById('post_wrap_'+zip+'');
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
				//document.getElementById('zip_code2').value = data.postcode2;
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