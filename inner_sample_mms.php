<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
	$send_type = trim(sqlfilter($_REQUEST['send_type']));
	$sms_type = trim(sqlfilter($_REQUEST['sms_type']));
	$sms_category = trim(sqlfilter($_REQUEST['sms_category']));
	$keyword = trim(sqlfilter($_REQUEST['keyword']));
	$target_id = trim(sqlfilter($_REQUEST['target_id']));
	$transmit_type = "";
	$member_idx = $_SESSION['member_coinc_idx'];
	
	$total_param = 'send_type='.$send_type.'&sms_type='.$sms_type.'&sms_category='.$sms_category.'&keyword='.$keyword.'&target_id='.$target_id;

	$where = " and is_del='N' and sample_yn='Y'";

	if(!$pageNo){
		$pageNo = 1;
	}

	if(!$s_cnt){
		$s_cnt = 6; // 기본목록 6개
	}

	if(!$s_order){
		$s_order = 1; 
	}

	if($send_type){
		$where .= " and send_type='".$send_type."'";
	}
	if($sms_type){
		$where .= " and sms_type='".$sms_type."'";
	}
	if($sms_category){
		$where .= " and sms_category='".$sms_category."'";
	}
	if($keyword){
		$where .= " and (sms_title like '%".$keyword."%' or sms_content like '%".$keyword."%')";
	}
	
	$pageScale = $s_cnt;  
	$start = ($pageNo-1)*$pageScale;

	$StarRowNum = (($pageNo-1) * $pageScale);
	$EndRowNum = $pageScale;

	if($s_order == 1){
		$order_by = " order by idx desc ";
	} elseif($s_order == 2){
		$order_by = " order by wdate asc ";
	} elseif($s_order == 3){
		$order_by = " order by user_name asc ";
	} elseif($s_order == 4){
		$order_by = " order by user_name desc ";
	}

	$query = "select *,(select file_chg from board_file where 1 and board_tbname='sms_save' and board_code='mms' and board_idx=a.idx order by idx asc limit 0,1) as file_chg,(select idx from board_file where 1 and board_tbname='sms_save' and board_code='mms' and board_idx=a.idx order by idx asc limit 0,1) as file_idx_cp from sms_save a where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

	//echo "<br><br>쿼리 = ".$query."<br><Br>";

	$result = mysqli_query($gconnet,$query);

	$query_cnt = "select idx from sms_save a where 1 ".$where;
	$result_cnt = mysqli_query($gconnet,$query_cnt);
	$num = mysqli_num_rows($result_cnt);

	$iTotalSubCnt = $num;
	$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
	 <div class="sample">
		<?
			for ($i=0; $i<mysqli_num_rows($result); $i++){
				$row = mysqli_fetch_array($result);
				$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
		?>	
			 <div class="sample_box imeif">
				<?if($row['file_chg']){?>
					<img src="<?=$_P_DIR_WEB_FILE?>sms/img_thumb/<?=$row['file_chg']?>">
				<?}?>
				<?=nl2br($row['sms_content'])?>
				<div class="sample_btn">
					 <a href="javascript:set_sms_form('<?=$row['idx']?>');" class="btn01 btn">전송</a>
					 <a href="javascript:go_sample_save('sms_save_frm_<?=$row['idx']?>');" class="btn02 btn">저장</a>
				</div>
			</div>
			
			<form method="post" name="sms_save_frm_<?=$row['idx']?>" id="sms_save_frm_<?=$row['idx']?>" target="_fra" enctype="multipart/form-data" action="sms_action.php">
				<input type="hidden" name="pageNo" value="<?=$pageNo?>" />
				<input type="hidden" name="total_param" value="<?=$total_param?>"/>
				
				<input type="hidden" id="send_type" name="send_type" value="<?=$send_type?>">
				<input type="hidden" id="sms_type" name="sms_type" value="<?=$sms_type?>">
				<input type="hidden" id="sms_category" name="sms_category" value="<?=$sms_category?>">
				<input type="hidden" id="transmit_type" name="transmit_type" value="save">
				<input type="hidden" id="act_mode" name="act_mode" value="inner1">
				
				<input type="hidden" id="file_idx_cp" name="file_idx_cp" value="<?=$row['file_idx_cp']?>">
				
				 <textarea id="sms_content_<?=$row['idx']?>" name="sms_content" style="display:none;"><?=$row['sms_content']?></textarea>
			</form>
		<?}?>
	  </div>
	  
	        
      <div class="pagenation">
     <?
		$target_link = "inner_sample_mms.php";
		$target_id = $target_id;
		$target_param = $total_param;
		include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging_front_ajax.php";
	 ?>
     </div>
	