<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_admin.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/master/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$bbs = "member";
	$_P_DIR_FILE = $_P_DIR_FILE.$bbs."/";
	$_P_DIR_FILE2 = $_P_DIR_FILE."img_thumb/";
	//$_P_DIR_FILE3 = $_P_DIR_FILE."img_thumb2/";
	
	$file_old_name1 = $_REQUEST["membership_photo_org"];
	if($file_old_name1){
		unlink($_P_DIR_FILE.$file_old_name1); // 
		unlink($_P_DIR_FILE2.$file_old_name1); // 원본 작은 섬네일 파일 삭제
		//unlink($_P_DIR_FILE3.$file_old_name1); // 원본 중간 섬네일 파일 삭제
	}
?>