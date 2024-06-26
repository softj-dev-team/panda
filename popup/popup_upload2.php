<?include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/db_conn.php"; ?>
<div class="popup cart_popup" style="display:none">
	<div class="popup_title">
		<p>데이터 업로드</p>
		<span class="btn_close" onClick="file_upload_close();popup_close();"></span>
	</div>
	<div class="popup_con">
		<!--<ul class="popup_tab">
			<li data-tab="tab1" class="on">직접 업로드</li>
			<li data-tab="tab2">웹하드 업로드</li>
		</ul>-->
		<div class="tab_con">
			<div id="tab1" class="tab1 on">
				<div style="margin-top:-30px;"><textarea name="order_memo" id="order_memo" placeholder="작업시 필요한 사항을 메모해주세요" required='no' message='작업메모'><?=$cart_row[order_memo]?></textarea></div>
				<div class="pop_radio" style="margin-top:10px;">
				<ul>
			<?if($pro_type == "indig"){?>
				<input type="hidden" name="sian_ok" value="N">
			<?}else{?>
				<li><label><span style="color:red;">&#42;</span> 시안확인 여부&nbsp;&nbsp;&nbsp;&nbsp;</label>
					<input type="radio" id="type_1" name="sian_ok" value="Y" <?=$cart_row[sian_ok]=="Y"?"checked":""?> required='yes' message='시안 확인여부'>
					<label for="type_1">시안 확인 후 인쇄</label>
				</li>
				<li>
					<input type="radio" id="type_2" name="sian_ok" value="N" <?=$cart_row[sian_ok]=="N"?"checked":""?> required='yes' message='시안 확인여부'>
					<label for="type_2">시안 확인 안하고 인쇄</label>
				</li>
			<?}?>
				</ul>
				</div>
		
				<div>
				<?
					$sql_file = "select idx,file_org,file_chg from board_file where 1=1 and board_tbname='".$mode."' and board_idx='".$cart_row[idx]."' order by idx asc ";
					//echo $sql_file;
					$query_file = mysqli_query($gconnet,$sql_file);
					$cnt_file = mysqli_num_rows($query_file);
					for($i_file=0; $i_file<$cnt_file; $i_file++){
						$row_file = mysqli_fetch_array($query_file);
						$k_file = $i_file+1;
					?>
						
						<input type="hidden" name="file_idx_<?=$i_file?>" value="<?=$row_file['idx']?>" />
						<input type="hidden" name="file_old_name_<?=$i_file?>" value="<?=$row_file['file_chg']?>" />
						<input type="hidden" name="file_old_org_<?=$i_file?>" value="<?=$row_file['file_org']?>" />
						<li style="margin-top:5px;margin-bottom:5px;">
							첨부된 파일 <?=$k_file?> : <a href="/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=order"><?=$row_file['file_org']?></a>
									(기존파일 삭제 : <input type="checkbox" name="del_org_<?=$i_file?>" value="Y">)
						</li>
				<?}?>	
				</div>
				<div class="file_box" style="margin-top:10px;">
					<!--<label for="file_image1_0">파일 찾기</label>
					<input type="file" onchange='file_change_1(0);' id='file_image1_0' name='file_image1_0' required='no' message='첨부파일'>
					<input type="text" id='file_text1_0' name='file_text1_0' value="">-->
					<iframe style="width:100%;height:300px;" src="file_tmp.php" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" name="file_upload_section" id="file_upload_section"></iframe>
				</div>
	
				<!--<span class="txt">
					* 데이터가 여러개인 경우, 폴더 하나로 압축해서 업로드 해주세요.<br>
					직접 업로드의 최대 파일 용량은 200MB 입니다. 초과시 웹하드 업로드를 이용해주세요.
				</span>-->
			</div>
			<!--<div id="tab2" class="tab2">
				<span class="txt">* Webhard.co.kr 접수 후 아이디 : indigoworld / 비밀번호 : 1234</span>
				<span class="txt">* 웹하드 게스트 폴더 내에 “성함 + 핸드폰 번호 (예 : 조현우 010-3424-7022)”로 폴더 생성 후 데이터를 업로드해주세요.</span>
				<div class="file_box">
					<label for="btn_file2">파일 찾기</label>
					<input type="file" id="btn_file2">
					<input type="text" id="" name="" value="">
				</div>
				<textarea name="" id="" placeholder="작업 메모"></textarea>
			</div>-->
		</div>
		<div class="pop_btn" id="display_cart_button" style="margin-top:250px;">
		<?if($cart_idx){?>
			<?if($mode == "product_option"){?>
				<button type="button" class="btn_red" onclick="go_cart();">장바구니 담기</button>
			<?}else{?>
				<button type="button" class="btn_red" onclick="go_cart();">장바구니 수정</button>
			<?}?>
		<?}else{?>
			<button type="button" class="btn_red" onclick="go_cart();">장바구니 담기</button>
		<?}?>
		</div>
	</div>
</div>

<script type="text/javascript">
function file_find_1(idx){
	$('#file_image1_'+idx+'').click();
}
function file_change_1(idx){
	var cval = $('#file_image1_'+idx+'').val();
	$('#file_text1_'+idx+'').val(cval);
}
function file_upload_close(){
	file_upload_section.location.href="/goods/file_tmp.php";
}
</script>