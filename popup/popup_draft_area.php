<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 
	
	$mode = trim(sqlfilter($_REQUEST['mode']));
	$mode_sub = trim(sqlfilter($_REQUEST['mode_sub']));
	$idx = trim(sqlfilter($_REQUEST['idx']));

	$sql = "select *,(select order_name from order_member where 1 and order_num=".$mode.".order_num) as order_name,(select order_date from order_member where 1 and order_num=".$mode.".order_num) as order_date,(select cate_name1 from paper_info where 1 and cate_type=".$mode.".pro_type and paper_type='paper' and  cate_code1=".$mode.".paper_cover_1 and cate_level = '1') as paper_name_1,(select cate_name2 from paper_info where 1 and cate_type=".$mode.".pro_type and paper_type='paper' and  cate_code2=".$mode.".paper_cover_2 and cate_level = '2') as paper_name_2,(select cate_name3 from paper_info where 1 and cate_type=".$mode.".pro_type and paper_type='paper' and  cate_code3=".$mode.".paper_cover_3 and cate_level = '3') as paper_name_3 from ".$mode." where 1 and idx='".$idx."'";
	$query = mysqli_query($gconnet,$sql);
	$row = mysqli_fetch_array($query);

	if($row[sian_conf]=="R"){
		$sian_ok_str = "확인 요청 중";
	}elseif($row[sian_conf]=="I"){
		$sian_ok_str = "수정 요청 중";
	}elseif($row[sian_conf]=="Y"){
		$sian_ok_str = "확인됨";
	} else {
		$sian_ok_str = "확인안됨";
	}

	$size_input_arr = explode("/",$row[size_input]);
	$pront_color_arr = explode("/",$row[pront_color]);
	$after_color_arr = explode("/",$row[after_color]);

	$pront_color_sql = "select txt_1 from product_info_add where 1 and idx='".$pront_color_arr[0]."'";
	$pront_color_query = mysqli_query($gconnet,$pront_color_sql);
	$pront_color_row = mysqli_fetch_array($pront_color_query);

	$after_color_sql = "select txt_1 from product_info_add where 1 and idx='".$after_color_arr[0]."'";
	$after_color_query = mysqli_query($gconnet,$after_color_sql);
	$after_color_row = mysqli_fetch_array($after_color_query);

?>
	<div class="popup_title">
		<p>시안 확인</p>
		<span class="type"><?=$sian_ok_str?></span>
		<span class="btn_close" onClick="popup_close();"></span>
	</div>
	<div class="pop_con">
		<table>
			<caption>시안확인</caption>
			<colgroup>
				<col style="width:15%;">
				<col style="width:35%;">
				<col style="width:15%;">
				<col style="width:35%;">
			</colgroup>
			<tr>
				<th scope="row">주문명</th>
				<td>
					<span class="order_name"><?=$row[caption]?></span>
				</td>
				<th scope="row">주문일자</th>
				<td>
					<span class="date"><?=substr($row[order_date],0,10)?></span>
				</td>
			</tr>
			<tr>
				<th scope="row">주문내용</th>
				<td colspan="3">
					<span class="order_con">
						<?=pro_type($row[pro_type],$row[pro_cate1],$row[pro_cate2])?> / <?=$row[paper_name_1]?> <?=$row[paper_name_2]?> <?=$row[paper_name_3]?> / <?=strtoupper($size_input_arr[0])?> (재단:<?=$row[size_width]?>X<?=$row[size_height]?> 작업:<?=$row[size_width2]?>X<?=$row[size_height2]?>) 
						<?if($row[pro_type] == "purch"){?>	
							/ 앞면 <?=$pront_color_row[txt_1]?> <?if($pront_color_arr[2]){?>UV <?}?><?=$pront_color_arr[1]?>도 <?if($pront_color_arr[3]){?>+<?=$pront_color_arr[3]?><?}?> / 뒷면 <?=$after_color_row[txt_1]?> <?if($after_color_arr[2]){?>UV <?}?><?=$after_color_arr[1]?>도 <?if($after_color_arr[3]){?>+<?=$after_color_arr[3]?><?}?> 
						<?}elseif($row[pro_type] == "indig"){?>
							/ 인쇄도수 <?=$row[pront_color]?>
						<?}?>
						<?if($row[quantity_cnt]){?> 
							/ <?=number_format($row[quantity])?>매 / <?=number_format($row[quantity_cnt])?>건
						<?}else{?>
							/ <?=number_format($row[quantity])?>부
						<?}?>
					</span>
				</td>
			</tr>
			<tr>
				<th scope="row">첨부파일</th>
				<td colspan="3">
					<p class="file">
					<?
						$sql_file = "select idx,file_org,file_chg from board_file where 1=1 and board_tbname='".$mode."' and (board_code !='sian' or board_code ='' or board_code is null) and board_idx='".$row[idx]."' order by idx asc ";
						//echo $sql_file;
						$query_file = mysqli_query($gconnet,$sql_file);
						$cnt_file = mysqli_num_rows($query_file);
						for($i_file=0; $i_file<$cnt_file; $i_file++){
							$row_file = mysqli_fetch_array($query_file);
							$k_file = $i_file+1;

							$imageSize = filesize($_P_DIR_FILE."order"."/".$row_file['file_chg']) / 1024;
						    $imageSize = floor($imageSize);
					?>
						<button type="button" onclick="location.href='/pro_inc/download_file.php?nm=<?=$row_file['file_chg']?>&on=<?=$row_file['file_org']?>&dir=order';"><?=$row_file['file_org']?> <span class="size">(<?=$imageSize?> KB)</span></button>
					<?}?>
				   </p>
				</td>
			</tr>
		</table>
		<div class="info">
			<ul class="warning">
				<li>사방 재단선 기준 여백이 충분히 나가있는지 확인하시기 바랍니다.</li>
				<li>해당 미리보기는 저화질 JPG 미리보기이므로 <!--PDF--> 다운로드를 이용하여 확인하시길 권장합니다.</li>
			</ul>
		<?
			$sql_file_sian = "select idx,file_org,file_chg,wdate from board_file where 1 and board_tbname='".$mode."' and board_code='sian' and board_idx='".$row[idx]."' order by idx asc";
			$query_file_sian = mysqli_query($gconnet,$sql_file_sian);

			$file_sian_cnt = mysqli_num_rows($query_file_sian);

			$query_file_sian2 = mysqli_query($gconnet,$sql_file_sian);
			$query_file_sian3 = mysqli_query($gconnet,$sql_file_sian);
			//if(mysqli_num_rows($query_file_sian) > 0){
			for($i_file_sian=0; $i_file_sian<mysqli_num_rows($query_file_sian); $i_file_sian++){
				$row_file_sian = mysqli_fetch_array($query_file_sian);
		?>
			<button type="button" class="btn_down" onclick="location.href='/pro_inc/download_file.php?nm=<?=$row_file_sian['file_chg']?>&on=<?=$row_file_sian['file_org']?>&dir=order';"><span>시안 <!--PDF--> 다운로드</span></button>
		<?}?>
		</div>

<?if($file_sian_cnt > 0){ // 시안 업로드 후?>		
	<?if($row[sian_conf]=="Y"){?>
	<?}else{?>
		<div class="btn_area">
			<button type="button" class="gray_btn" onclick="go_sian_ok('<?=$row[idx]?>');"><span>시안 확인하기</span></button>
			<button type="button" class="gray_btn" onclick="go_sian_mok('<?=$row[idx]?>');"><span>시안 수정요청</span></button>
			<button type="button" class="gray_btn" onclick="popup_close();get_data('/popup/popup_mod_upload_area.php','popup_mod_upload_area','cart_idx=<?=$row[idx]?>');popup_open2();"><span>파일수정 업로드</span></button>
		</div>
	<?}?>
<?} else { // 시안 업로드 전?>
	<div class="btn_area">
		<button type="button" class="gray_btn" onclick="popup_close();get_data('/popup/popup_mod_upload_area.php','popup_mod_upload_area','cart_idx=<?=$row[idx]?>');popup_open2();"><span>파일수정 업로드</span></button>
	</div>
<?} // 시안 업로드 여부 종료?>

	
		<div class="draft">
			<div class="img_area" style="color:#fff;">
			<?
				for($i_file_sian2=0; $i_file_sian2<mysqli_num_rows($query_file_sian2); $i_file_sian2++){
					$row_file_sian2 = mysqli_fetch_array($query_file_sian2);
					$k_file_sian2 = $i_file_sian2+1;
			?>
				<div id="tab<?=$k_file_sian2?>" <?if($k_file_sian2 == 1){?>class="on"<?}?>>
					<?if(upload_img_type($_P_DIR_FILE."order/".$row_file_sian2['file_chg'])){?>
						<img src="<?=$_P_DIR_WEB_FILE?>order/<?=$row_file_sian2['file_chg']?>" alt="<?=$row_file_sian2['file_org']?>">
					<?}?>
				</div>
			<?}?>
			</div>
			<div class="tab_area">
				<ul>
				<?
				for($i_file_sian3=0; $i_file_sian3<mysqli_num_rows($query_file_sian3); $i_file_sian3++){
					$row_file_sian3 = mysqli_fetch_array($query_file_sian3);
					$k_file_sian3 = $i_file_sian3+1;
				?>
					<li data-tab="tab<?=$k_file_sian3?>" <?if($k_file_sian3 == 1){?>class="on"<?}?>>
						<span class="img">
							<?if(upload_img_type($_P_DIR_FILE."order/".$row_file_sian3['file_chg'])){?>
								<img src="<?=$_P_DIR_WEB_FILE?>order/<?=$row_file_sian3['file_chg']?>" alt="<?=$row_file_sian3['file_org']?>" style="width:80px;height:60px;">
							<?}?>
						</span>
						<span class="txt"><?if($k_file_sian3 == 1){?>앞면<?}else{?>뒷면<?}?></span>
					</li>
				<?}?>
				</ul>
			</div>
		</div>
	</div>
