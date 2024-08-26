<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_default.php"; // 공통함수 인클루드 ?>
<? include $_SERVER["DOCUMENT_ROOT"]."/pro_inc/include_htmlheader_manage.php"; // 관리자페이지 헤더?>
<? include $_SERVER["DOCUMENT_ROOT"]."/manage/include/check_login_frame.php"; // 관리자 로그인여부 확인?>
<?
	$sql = "select temple_layout,temple_url from temple_info where 1 order by idx asc";
	$query = mysqli_query($gconnet,$sql);
	for($i=0; $i<mysqli_num_rows($query); $i++){
		$row = mysqli_fetch_array($query);

		$temple_layout = $row['temple_layout'];
		$temple_url = $row['temple_url'];
		
		$mini_home_url = $_SERVER["DOCUMENT_ROOT"]."/mybuddha/temple_home/".$temple_url;
		if(!is_dir($mini_home_url)){
			mkdir($mini_home_url, 0777); 
			chmod($mini_home_url, 0755);
			copy($_SERVER["DOCUMENT_ROOT"]."/mybuddha/_minimall/layout".$temple_layout."/index.php",$mini_home_url."/index.php"); 
		} else {
			copy($_SERVER["DOCUMENT_ROOT"]."/mybuddha/_minimall/layout".$temple_layout."/index.php",$mini_home_url."/index.php");  
		}

	}

?>