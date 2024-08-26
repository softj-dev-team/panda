<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login.php"; // 관리자 로그인여부 확인?>
<?
$bmenu = trim(sqlfilter($_REQUEST['bmenu']));
$smenu = trim(sqlfilter($_REQUEST['smenu']));
?>

<script language="JavaScript"> 
	function go_submit() {
		var check = chkFrm('frm');
		if(check) {
			/*if($("#link_sect_2").prop("checked") == true) {// 링크 주소 별도입력
				if($("#link_url").val() == ""){
					alert("링크주소를 입력해 주세요.");
					return;
				}
			}*/
			frm.submit();
		} else {
			false;
		}
	}
	
	function go_list(){
		location.href = "sms_sample_list.php?<?=$total_param?>";
	}

	function main_product_pop(){
		//location.href = 
		window.open("main_product.php","pro_pro_view", "top=100,left=100,scrollbars=yes,resizable=no,width=1010,height=500");
	}

	function link_ck() { 
		/*if (document.frm.link_sect.link_sect_1.checked) { // 개별 상품 링크
			link_sect_txt1.style.display = '';
			link_sect_txt2.style.display = 'none';
		} else*/ if($("#link_sect_2").prop("checked") == true) { // 링크 주소 별도입력
			$("#link_sect_txt2").hide();
			$("#link_sect_txt3").show();
		}  else if($("#link_sect_3").prop("checked") == true) { // 링크없음
			$("#link_sect_txt2").hide();
			$("#link_sect_txt3").hide();
		}
	} 
	
function Display_1(form){
	
	var target1 = document.all['banner_size_txt1'];

	if(form.main_sect.value == "topsch_right"){
		target1.innerText = "가로 : 140 픽셀, 세로 : 54 픽셀";
	} else if (form.main_sect.value == "flash_right"){
		target1.innerText = "가로 : 190 픽셀, 세로 : 260 픽셀";
	} else if (form.main_sect.value == "new_left"){
		target1.innerText = "가로 : 181 픽셀, 세로 : 176 픽셀";
	} else if (form.main_sect.value == "new_right"){
		target1.innerText = "가로 : 181 픽셀, 세로 : 203 픽셀";
	} else if (form.main_sect.value == "new_down"){
		target1.innerText = "가로 : 313 픽셀, 세로 : 103 픽셀";
	}
	
}

function section_ck(){
	if($("#section_1").prop("checked") == true) {
		$("#link_sect_txt1").hide();
		$("#link_sect_txt2").hide();
		$("#link_sect_txt3").hide();
		$("#link_sect_txt4").hide();
		$("#link_sect_txt5").show();
	} else if($("#section_2").prop("checked") == true) {
		$("#link_sect_txt1").show();
		//$("#link_sect_txt2").show();
		$("#link_sect_txt3").show();
		$("#link_sect_txt4").show();
		$("#link_sect_txt5").hide();
	}
}
</script>

<body>
<div id="wrap" class="skin_type01">
	<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/admin_top.php"; // 상단메뉴?>
	<div class="sub_wrap">
		<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/sms_left.php"; // 좌측메뉴?>
		<!-- content 시작 -->
		<div class="container clearfix">
			<div class="content">
			<a href="javascript:location.reload();" class="btn_refresh">새로고침</a>
				<div class="navi">
					<ul class="clearfix">
						<li>HOME</li>
						<li>문자관리</li>
						<li>샘플문자 등록</li>
					</ul>
				</div>
				<div class="list_tit">
					<h3>샘플문자 등록</h3>
				</div>
				<div class="write">

			<form name="frm" action="sms_sample_write_action.php" target="_fra_admin" method="post"  enctype="multipart/form-data">
			<input type="hidden" name="bmenu" value="<?=$bmenu?>"/>
			<input type="hidden" name="smenu" value="<?=$smenu?>"/>
			
			<table>
				<colgroup>
					<col width="10%" />
					<col width="40%" />
					<col width="10%" />
					<col width="40%" />
				</colgroup>
				
					<tr>
						<th>문자구분</th>
						<td>
							<select name="send_type" id="send_type" size="1" style="vertical-align:middle;width:80%;" required="yes" message="문자 구분">
								<option value="">선택하세요</option>
								<option value="gen" <?=$row['send_type']=="gen"?"selected":""?>>문자</option>
								<option value="adv" <?=$row['send_type']=="adv"?"selected":""?>>광고문자</option>
								<option value="elc" <?=$row['send_type']=="elc"?"selected":""?>>선거문자</option>
								<option value="pht" <?=$row['send_type']=="pht"?"selected":""?>>포토문자</option>
								<option value="test" <?=$row['send_type']=="test"?"selected":""?>>테스트문자</option>
							</select>							
						</td>
						<th>문자타입</th>
						<td>
							<select name="sms_type" id="sms_type" size="1" style="vertical-align:middle;width:80%;" required="yes" message="문자타입">
								<option value="">선택하세요</option>
								<option value="sms" <?=$row['sms_type']=="sms"?"selected":""?>>단문</option>
								<option value="lms" <?=$row['sms_type']=="lms"?"selected":""?>>장문</option>
								<option value="mms" <?=$row['sms_type']=="mms"?"selected":""?>>이미지문자</option>
							</select>							
						</td>
					</tr>
					<tr>
						<th>카테고리</th>
						<td width="*" colspan="3">
							<select name="sms_category" id="sms_category" size="1" style="vertical-align:middle;width:80%;" required="no" message="카테고리">
									<option value="">선택하세요</option>
								<?
								$sub_sql = "select cate_code1,cate_name1 from common_code where 1 and type='smsmenu' and cate_level = '1' and is_del='N' and del_ok='N' order by cate_align desc"; 
								$sub_query = mysqli_query($gconnet,$sub_sql);
				
								$sub_k = 0;
								for($sub_i=0; $sub_i<mysqli_num_rows($sub_query); $sub_i++){
									$sub_row = mysqli_fetch_array($sub_query);
									$sub_k = $sub_k+1;
								?>
									<option value="<?=$sub_row['cate_code1']?>" <?=$row['sms_category']==$sub_row['cate_code1']?"selected":""?>><?=$sub_row['cate_name1']?></option>
								<?}?>	
							</select>
						</td>
					</tr>
					<tr>
						<th>이미지 (이미지문자 전용)</th>
						<td width="*" colspan="3">
					<?
						$_include_board_file_cnt = 1;			
						$sql_file = "select idx,file_org,file_chg from board_file where 1 and board_tbname='sms_save' and board_code='mms' and board_idx='".$row['idx']."' order by idx asc";
						$query_file = mysqli_query($gconnet,$sql_file);
						$cnt_file = mysqli_num_rows($query_file);

						if($cnt_file < $_include_board_file_cnt){
							$cnt_file = $_include_board_file_cnt;
						}
						
						for($i_file=0; $i_file<$cnt_file; $i_file++){
							$row_file = mysqli_fetch_array($query_file);
							$k_file = $i_file+1;
					?>
						
						<input type="hidden" name="file_idx_<?=$i_file?>" value="<?=$row_file['idx']?>" />
						<input type="hidden" name="file_old_name_<?=$i_file?>" value="<?=$row_file['file_chg']?>" />
						<input type="hidden" name="file_old_org_<?=$i_file?>" value="<?=$row_file['file_org']?>" />
						<input type="file" id="file_add_<?=$i_file?>" name="file_add_<?=$i_file?>" accept="image/*">
						<?if($row_file['file_chg']){?>
							<br> 기존파일 : <a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=sms"><?=$row_file['file_org']?></a>
							(기존파일 삭제 : <input type="checkbox" name="del_org_<?=$i_file?>" value="Y">)
						<?}?>
					<?}?>
						</td>
					</tr>
					<tr>
						<th>샘플문자 내용</th>
						<td width="*" colspan="3">
							<textarea style="width:90%;height:80px;" name="sms_content" id="sms_content" required="yes" message="샘플문자 내용"></textarea>
						</td>
					</tr>
				
			</table>
			</form>

			<div class="write_btn align_r">
						<a href="javascript:go_list();" class="btn_gray">목록보기</a>
						<a href="javascript:go_submit();" class="btn_blue">등록하기</a>
					</div>
				</div>
			</div>
		</div>
		<!-- content 종료 -->
	</div>
</div>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_bottom_admin_tail.php"; ?>
</body>
</html>