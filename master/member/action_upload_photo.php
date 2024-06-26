<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$bbs = "member";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	$_P_DIR_WEB_FILE = $_P_DIR_WEB_FILE.$bbs."/";

	################ 사진 이미지 업로드 ##############
	if ($_FILES['membership_photo']['size']>0){
		$file_o = $_FILES['membership_photo']['name']; 
		$i_width = "143";
		$i_height = "143";
		$i_width2 = "";
		$i_height2 = "";
		//$watermark_sect = "imgw";
		$watermark_sect = "";
		$file_c = uploadFileThumb_1($_FILES, "membership_photo", $_FILES['membership_photo'], $_P_DIR_FILE,$i_width,$i_height,$i_width2,$i_height2,$watermark_sect);

	?>
		<script type="text/javascript">
		//<![CDATA[
		if (parent.upload_photo_callback) {
			parent.upload_photo_callback("<?=$file_o?>","<?=$file_c?>");
		}
		//]]>
		</script>
<?
	}
?>