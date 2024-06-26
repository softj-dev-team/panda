<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
	$idx = trim(sqlfilter($_REQUEST['sms_idx']));

	$where = " and is_del='N' and idx='".$idx."'";

	$query = "select *,(select file_chg from board_file where 1 and board_tbname='sms_save' and board_code='mms' and board_idx=a.idx order by idx asc limit 0,1) as file_chg,(select idx from board_file where 1 and board_tbname='sms_save' and board_code='mms' and board_idx=a.idx order by idx asc limit 0,1) as file_idx_cp from sms_save a where 1 ".$where;
	//echo "<br><br>쿼리 = ".$query."<br><Br>";
	$result = mysqli_query($gconnet,$query);
	$row = mysqli_fetch_array($result);
?>
	   <input type="hidden" id="file_idx_cp" name="file_idx_cp" value="<?=$row['file_idx_cp']?>">
	   
		<p class="top_text">90byte(한글45자) 초과시 자동으로 장문 전환</p>
		<a href="#layer5" class="imgsmsbtn  btn-example">
		  <img src="<?=$_P_DIR_WEB_FILE?>sms/img_thumb/<?=$row['file_chg']?>" id="add_image_fetch" style="width:375px;height:225px;">
		</a>
		 <textarea id="sms" name="sms_content" required="yes" message="발송내용" onkeyup="sms_text_count();"><?=$row['sms_content']?></textarea>
		<a href="javascript:;" class="sms080">무료거부 0808870505</a>

<script>
	$(document).ready(function() {
		sms_text_count();
	});
</script>