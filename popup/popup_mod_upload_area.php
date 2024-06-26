<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 
	
	$_SESSION['order_tmp_filecode'] = "";

	$cart_idx = trim(sqlfilter($_REQUEST['cart_idx']));

	$cart_sql = "select * from order_product where 1 and idx='".$cart_idx."'";
	$cart_query = mysqli_query($gconnet,$cart_sql);
	$cart_row = mysqli_fetch_array($cart_query);
?>
	<div class="popup2_title">
		<p>파일 수정 업로드</p>
		<span class="btn_close" onClick="popup_close2();"></span>
	</div>
	<div class="popup2_con">
		<div class="tab_con">
			<div id="tab1" class="tab1 on">
				<div style="margin-top:-30px;"><textarea name="order_memo" id="order_memo" placeholder="작업시 필요한 사항을 메모해주세요" required='no' message='작업메모'><?=$cart_row[order_memo]?></textarea></div>
				<div class="pop_radio" style="margin-top:10px;">
				<!--<ul>
				<li>
					<input type="radio" id="type_1" name="sian_ok" value="Y" <?=$cart_row[sian_ok]=="Y"?"checked":""?> required='yes' message='시안 확인여부'>
					<label for="type_1">시안 확인 후 인쇄</label>
				</li>
				<li>
					<input type="radio" id="type_2" name="sian_ok" value="N" <?=$cart_row[sian_ok]=="N"?"checked":""?> required='yes' message='시안 확인여부'>
					<label for="type_2">시안 확인 안하고 인쇄</label>
				</li>
				</ul>-->
				</div>
				
				<div>
				<?
					$sql_file = "select idx,file_org,file_chg from board_file where 1=1 and board_tbname='order_product' and (board_code !='sian' or board_code ='' or board_code is null) and board_idx='".$cart_row[idx]."' order by idx asc";
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
					<iframe style="width:100%;height:300px;" src="/mypage/file_tmp.php" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" name="file_upload_section" id="file_upload_section"></iframe>
				</div>
			</div>
			
		</div>
		<div class="pop_btn" id="display_cart_button" style="margin-top:250px;">
			<button type="button" class="btn_red" onclick="go_mod_upload('<?=$cart_row[idx]?>');">파일 수정 업로드</button>
			<button type="button" class="btn_gray" onclick="popup_close2();">닫기</button>
		</div>
	</div>
