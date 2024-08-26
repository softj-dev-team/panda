<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
	$send_type = trim(sqlfilter($_REQUEST['send_type']));
	$sms_type = trim(sqlfilter($_REQUEST['sms_type']));
	$sms_category = trim(sqlfilter($_REQUEST['sms_category']));
	$keyword = trim(sqlfilter($_REQUEST['keyword']));
	$target_id = trim(sqlfilter($_REQUEST['target_id']));
	$transmit_type = "save";
	$member_idx = $_SESSION['member_coinc_idx'];
	
	$total_param = 'send_type='.$send_type.'&sms_type='.$sms_type.'&sms_category='.$sms_category.'&keyword='.$keyword.'&target_id='.$target_id;

	$where = " and is_del='N' and transmit_type='".$transmit_type."' and member_idx='".$member_idx."'";

	if(!$pageNo){
		$pageNo = 1;
	}

	if(!$s_cnt){
		$s_cnt = 3; // 기본목록 3개
	}

	if(!$s_order){
		$s_order = 1; 
	}

	if($send_type){
		$where .= " and send_type='".$send_type."'";
	}
	if($sms_type == "sms"){
		$where .= " and sms_type in ('sms','lms')";
	} elseif($sms_type == "mms"){
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

	$query = "select *,(select file_chg from board_file where 1 and board_tbname='sms_save' and board_code='mms' and board_idx=a.idx order by idx asc limit 0,1) as file_chg from sms_save a where 1 ".$where.$order_by." limit ".$StarRowNum." , ".$EndRowNum;

	//echo "<br><br>쿼리 = ".$query."<br><Br>";

	$result = mysqli_query($gconnet,$query);

	$query_cnt = "select idx from sms_save a where 1 ".$where;
	$result_cnt = mysqli_query($gconnet,$query_cnt);
	$num = mysqli_num_rows($result_cnt);

	$iTotalSubCnt = $num;
	$totalpage	= ($iTotalSubCnt - 1)/$pageScale  + 1;
?>
			<div class="point_pop samll">
                <ul class="list_ul">
                    <li>저장된 메세지를 클릭하면 입력창에 제목과 내용이 입려됩니다.</li>
                </ul>
	        </div>
        
			<div class="tab_btn_are">
				<div class="btn">
					<a href="javascript:CheckAll();">전체선택</a>
					<a href="javascript:go_tot_del();">삭제</a>
				</div>
				<div class="input_tab">
					<input type="text" id="pop_sms_keyword" value="<?=$keyword?>">
					<a href="javascript:sms_save_list_find();">
						<img src="images/search.png">
					</a>
				</div>
			</div>
        
			<form method="post" name="sms_save_frm" id="sms_save_frm" target="_fra">
			<input type="hidden" name="pageNo" value="<?=$pageNo?>" />
			<input type="hidden" name="total_param" value="<?=$total_param?>"/>
			<div class="sample">
			<?
				for ($i=0; $i<mysqli_num_rows($result); $i++){
					$row = mysqli_fetch_array($result);
					$listnum	= $iTotalSubCnt - (( $pageNo - 1 ) * $pageScale ) - $i;
			?>	
				<div class="sample_box">
					<input type="checkbox" name="save_idx[]" id="save_idx[]" value="<?=$row["idx"]?>" required="yes"  message="저장된 메세지">
					<?if($row['file_chg']){?>
						<img src="<?=$_P_DIR_WEB_FILE?>sms/img_thumb/<?=$row['file_chg']?>" onclick="set_sms_form('<?=$row['idx']?>');" style="cursor:pointer;">
					<?}?>
					<span onclick="set_sms_form('<?=$row['idx']?>');" style="cursor:pointer;"><?=nl2br($row['sms_content'])?></a>
				</div>
			<?}?>	
			</div>
			</form>
        
			<div class="pagenation">
			<?
				$target_link = "inner_mms_save_list.php";
				$target_id = $target_id;
				$target_param = $total_param;
				include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/paging_front_ajax.php";
			?>
			</div>