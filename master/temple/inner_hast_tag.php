<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<?
	$set_hast_id = trim($_REQUEST['set_hast_id']);
	$del_hast_id = trim($_REQUEST['del_hast_id']);
	if($del_hast_id){
		$set_hast_id = str_replace($del_hast_id,"",$set_hast_id);
	}
	$set_hast_id_arr = explode("||",$set_hast_id);
?>
	<ul>
<?
	for($i=0; $i<sizeof($set_hast_id_arr); $i++){ // 배열 루프 시작 
		if($set_hast_id_arr[$i]){ // 배열값이 있을때 시작 
			if($i == sizeof($set_hast_id_arr)-1){
				$temple_hash_id .= $set_hast_id_arr[$i];
			} else {
				$temple_hash_id .= $set_hast_id_arr[$i]."||";
			}
?>	
			<li style="margin-top:5px;margin-bottom:5px;"><?=$set_hast_id_arr[$i]?> <a href="javascript:del_hast_tag('<?=$set_hast_id_arr[$i]?>');" class="btn_red">삭제</a></li> 		
<?	
		} // 배열값이 있을때 종료 
	} // 배열 루프 종료 
?>
	</ul>

	<script type="text/javascript">
	<!--
		$("#temple_hash_id").val("<?=$temple_hash_id?>");
	//-->
	</script>